package gr.cyberstream.obi.payments.service;

import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;

import org.bouncycastle.openpgp.PGPException;
import org.junit.Test;

public class CryptographyServiceTest {
	
	@Test
	public void testLoadKeys() {
		
		String keysFile = "/temp/CyberStreamDev.asc";
	
		CryptographyService cryptoService = new CryptographyService();
	
		try (InputStream keyIn = new FileInputStream(keysFile)) {
			
			cryptoService.loadKeys(keyIn);
			
		} catch (FileNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
			
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
			
		} catch (PGPException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}

	@Test
	public void testEncrypt() {
		
		String keysFile = "/temp/CyberStreamDev.asc";
		
		CryptographyService cryptoService = new CryptographyService();
		
		try (
				InputStream keyIn = new FileInputStream(keysFile);
				OutputStream out = new FileOutputStream("/temp/encrypted.pgp");) {
			
			cryptoService.loadKeys(keyIn);
			
			//encrypt it!
			cryptoService.encryptFile(out, "/temp/clear.txt", true, true);
			
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
			
		} catch (PGPException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
	
	@Test
	public void testDecrypt() {
		
		String keysFile = "/temp/CyberStreamDev.asc";
		
		CryptographyService cryptoService = new CryptographyService();
		
		try (
				InputStream fileIn = new FileInputStream("/temp/encrypted.pgp");
				InputStream keyIn = new FileInputStream(keysFile);
				OutputStream output = new FileOutputStream("/temp/decrypted.txt");) {
			
			cryptoService.loadKeys(keyIn);
			
			cryptoService.decryptFile(fileIn, output, new String("cyberstream").toCharArray());
			
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
			
		} catch (PGPException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}		
	}
}
