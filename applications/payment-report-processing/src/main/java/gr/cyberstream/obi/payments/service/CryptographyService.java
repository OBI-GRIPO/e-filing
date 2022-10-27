package gr.cyberstream.obi.payments.service;

import java.io.ByteArrayOutputStream;
import java.io.File;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.security.SecureRandom;
import java.security.Security;
import java.util.Iterator;

import org.bouncycastle.bcpg.ArmoredOutputStream;
import org.bouncycastle.bcpg.CompressionAlgorithmTags;
import org.bouncycastle.bcpg.PublicKeyAlgorithmTags;
import org.bouncycastle.bcpg.SymmetricKeyAlgorithmTags;
import org.bouncycastle.bcpg.sig.KeyFlags;
import org.bouncycastle.jce.provider.BouncyCastleProvider;
import org.bouncycastle.openpgp.PGPCompressedData;
import org.bouncycastle.openpgp.PGPCompressedDataGenerator;
import org.bouncycastle.openpgp.PGPEncryptedData;
import org.bouncycastle.openpgp.PGPEncryptedDataGenerator;
import org.bouncycastle.openpgp.PGPEncryptedDataList;
import org.bouncycastle.openpgp.PGPException;
import org.bouncycastle.openpgp.PGPLiteralData;
import org.bouncycastle.openpgp.PGPObjectFactory;
import org.bouncycastle.openpgp.PGPOnePassSignatureList;
import org.bouncycastle.openpgp.PGPPrivateKey;
import org.bouncycastle.openpgp.PGPPublicKey;
import org.bouncycastle.openpgp.PGPPublicKeyEncryptedData;
import org.bouncycastle.openpgp.PGPSecretKey;
import org.bouncycastle.openpgp.PGPSecretKeyRing;
import org.bouncycastle.openpgp.PGPSecretKeyRingCollection;
import org.bouncycastle.openpgp.PGPSignature;
import org.bouncycastle.openpgp.PGPSignatureSubpacketVector;
import org.bouncycastle.openpgp.PGPUtil;
import org.bouncycastle.openpgp.operator.PBESecretKeyDecryptor;
import org.bouncycastle.openpgp.operator.bc.BcKeyFingerprintCalculator;
import org.bouncycastle.openpgp.operator.bc.BcPBESecretKeyDecryptorBuilder;
import org.bouncycastle.openpgp.operator.bc.BcPGPDataEncryptorBuilder;
import org.bouncycastle.openpgp.operator.bc.BcPGPDigestCalculatorProvider;
import org.bouncycastle.openpgp.operator.bc.BcPublicKeyDataDecryptorFactory;
import org.bouncycastle.openpgp.operator.bc.BcPublicKeyKeyEncryptionMethodGenerator;


public class CryptographyService {
	
	private static final int KEY_FLAGS = 27;
	private static final int[] MASTER_KEY_CERTIFICATION_TYPES = new int[] { PGPSignature.POSITIVE_CERTIFICATION,
			PGPSignature.CASUAL_CERTIFICATION, PGPSignature.NO_CERTIFICATION, PGPSignature.DEFAULT_CERTIFICATION };
	
	private PGPSecretKey key;
	
	public void loadKeys(InputStream in) throws IOException, PGPException {
		
		PGPSecretKeyRingCollection keyRingCollection = new PGPSecretKeyRingCollection(PGPUtil.getDecoderStream(in), new BcKeyFingerprintCalculator());
		
		/*
		 * just loop through the collection till we find a key suitable for encryption,
		 * in the real world you would probably want to be a bit smarter about this.
		 */
		Iterator<PGPSecretKeyRing> ringIterator = keyRingCollection.getKeyRings();
		
		while (ringIterator.hasNext()) {
			
			PGPSecretKeyRing ring = ringIterator.next();
			
			Iterator<PGPSecretKey> keyIterator = ring.getSecretKeys();  
			
			while (key == null && keyIterator.hasNext()) {
				
				key = keyIterator.next();
			}
		}
	}
	
	public void decryptFile(InputStream in, OutputStream out, char[] passwd) throws IOException, PGPException {
		
		Security.addProvider(new BouncyCastleProvider());
		
		PGPObjectFactory pgpFactory = new PGPObjectFactory(PGPUtil.getDecoderStream(in), new BcKeyFingerprintCalculator());
		
		PGPEncryptedDataList enc;
		
		Object o = pgpFactory.nextObject();
		
		// the first object might be a PGP marker packet.
		if (o instanceof PGPEncryptedDataList) {
			enc = (PGPEncryptedDataList)o;
		} else {
			enc = (PGPEncryptedDataList)pgpFactory.nextObject();
		}
		
		// find the secret key
		Iterator<PGPEncryptedData> iterator = enc.getEncryptedDataObjects();
		
		PGPPublicKeyEncryptedData publicKeyEncryptedData = null;
		
		while (iterator.hasNext()) {
			
			publicKeyEncryptedData = (PGPPublicKeyEncryptedData) iterator.next();
		}
		
		if (key == null) {
			throw new IllegalArgumentException("Secret key for message not found.");
		}
		
		PBESecretKeyDecryptor decryptorFactory = new BcPBESecretKeyDecryptorBuilder(
				new BcPGPDigestCalculatorProvider()).build(passwd);
		
		if (publicKeyEncryptedData != null) {
			
			InputStream clear = publicKeyEncryptedData.getDataStream(new BcPublicKeyDataDecryptorFactory(key.extractPrivateKey(decryptorFactory)));
			
			PGPObjectFactory plainFactory = new PGPObjectFactory(clear, new BcKeyFingerprintCalculator());
			
			Object message = plainFactory.nextObject();
			
			if (message instanceof PGPCompressedData) {
				PGPCompressedData compressedData = (PGPCompressedData) message;
				
				PGPObjectFactory compressedFactory = new PGPObjectFactory(compressedData.getDataStream(), new BcKeyFingerprintCalculator());
				
				message = compressedFactory.nextObject();
			}
			
			if (message instanceof PGPLiteralData) {
				
				PGPLiteralData literalData = (PGPLiteralData) message;
				
				InputStream unc = literalData.getInputStream();
				
				int ch;
				
				while ((ch = unc.read()) >= 0) {
					
					out.write(ch);
				}
				
			} else if (message instanceof PGPOnePassSignatureList) {
				
				throw new PGPException("Encrypted message contains a signed message - not literal data.");
				
			} else {
				
				throw new PGPException("Message is not a simple encrypted file - type unknown.");
			}
			
			if (publicKeyEncryptedData.isIntegrityProtected() && !publicKeyEncryptedData.verify()) {
				throw new PGPException("Message failed integrity check");
			}
			
		} else {
			
			throw new PGPException("Message is null");
		}
	}
	
	public void encryptFile(OutputStream out, String fileName, boolean armor, boolean withIntegrityCheck)
			throws IOException, PGPException {
		
		Security.addProvider(new BouncyCastleProvider());
		
		if (armor) {
			out = new ArmoredOutputStream(out);
		}
		
		ByteArrayOutputStream byteArrayOutput = new ByteArrayOutputStream();
		
		PGPCompressedDataGenerator compressedData = new PGPCompressedDataGenerator(CompressionAlgorithmTags.ZIP);
		
		PGPUtil.writeFileToLiteralData(compressedData.open(byteArrayOutput), PGPLiteralData.BINARY, new File(fileName));
		
		compressedData.close();
		
		BcPGPDataEncryptorBuilder dataEncryptor = new BcPGPDataEncryptorBuilder(SymmetricKeyAlgorithmTags.TRIPLE_DES);
		dataEncryptor.setWithIntegrityPacket(withIntegrityCheck);
		dataEncryptor.setSecureRandom(new SecureRandom());
		
		PGPEncryptedDataGenerator encryptedDataGenerator = new PGPEncryptedDataGenerator(dataEncryptor);
		encryptedDataGenerator.addMethod(new BcPublicKeyKeyEncryptionMethodGenerator(key.getPublicKey()));
		
		byte[] bytes = byteArrayOutput.toByteArray();
		
		try (
				OutputStream output = encryptedDataGenerator.open(out, bytes.length)) {
			
			output.write(bytes);
						
		} finally {
			
			out.close();
		}
	}
	
	public static PGPPrivateKey findPrivateKey(InputStream keyIn, long keyID, char[] pass) throws IOException, PGPException {
		
		PGPSecretKeyRingCollection pgpSec = new PGPSecretKeyRingCollection(PGPUtil.getDecoderStream(keyIn), new BcKeyFingerprintCalculator());
		return findPrivateKey(pgpSec.getSecretKey(keyID), pass);
	}
	
	public static PGPPrivateKey findPrivateKey(PGPSecretKey pgpSecretKey, char[] pass) throws PGPException {
		
		if (pgpSecretKey == null)
			return null;
		
		PBESecretKeyDecryptor decryptor = new BcPBESecretKeyDecryptorBuilder(new BcPGPDigestCalculatorProvider()).build(pass);
		return pgpSecretKey.extractPrivateKey(decryptor);
	}
	
	public static boolean isForEncryption(PGPPublicKey key) {
		
		if (key.getAlgorithm() == PublicKeyAlgorithmTags.RSA_SIGN || key.getAlgorithm() == PublicKeyAlgorithmTags.DSA
				|| key.getAlgorithm() == PublicKeyAlgorithmTags.EC || key.getAlgorithm() == PublicKeyAlgorithmTags.ECDSA) {
			return false;
		}
		
		return hasKeyFlags(key, KeyFlags.ENCRYPT_COMMS | KeyFlags.ENCRYPT_STORAGE);
	}
	
	private static boolean hasKeyFlags(PGPPublicKey encryptionKey, int keyUsage) {
		
		if (encryptionKey.isMasterKey()) {
			
			for (int i = 0; i != MASTER_KEY_CERTIFICATION_TYPES.length; i++) {
				
				for (Iterator<PGPSignature> signatureIterator = encryptionKey.getSignaturesOfType(MASTER_KEY_CERTIFICATION_TYPES[i]); signatureIterator.hasNext();) {
					
					PGPSignature signature = signatureIterator.next();
					
					if (!isMatchingUsage(signature, keyUsage)) {
						return false;
					}
				}
			}
			
		} else {
			
			for (Iterator<PGPSignature> signatureIterator = encryptionKey.getSignaturesOfType(PGPSignature.SUBKEY_BINDING); signatureIterator.hasNext();) {

				PGPSignature signature = signatureIterator.next();
				
				if (!isMatchingUsage(signature, keyUsage)) {
					return false;
				}
			}
		}
		
		return true;
	}
	
	private static boolean isMatchingUsage(PGPSignature signature, int keyUsage) {
		
		if (signature.hasSubpackets()) {
			
			PGPSignatureSubpacketVector signatureSubpacketVector = signature.getHashedSubPackets();
			
			if (signatureSubpacketVector.hasSubpacket(KEY_FLAGS) && signatureSubpacketVector.getKeyFlags() == 0 && keyUsage == 0) {
				return false;
			}
		}
		
		return true;
	}
}
