package gr.cyberstream.obi.payments.service.transfer;

import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.InputStream;
import java.util.Date;

import gr.cyberstream.obi.payments.exceptions.TransferServiceConnectException;

public class LocalTransferService implements TransferService {

	@Override
	public InputStream readReport(String orgId, Date date, String cycle, String lobId, String extension) throws TransferServiceConnectException {
		
		InputStream reportInputStream = null;
		
		String filename = TransferServiceUtil.getFileName(orgId, date, cycle, lobId, extension);
		
		try {
			
			reportInputStream = new FileInputStream("/temp/" + filename);
			
		} catch (FileNotFoundException e) {
			
			throw new TransferServiceConnectException("Unable to get file " + "/temp/" + filename + ". " + e.getMessage());
		}
		
		return reportInputStream;
	}

	@Override
	public void connect() throws TransferServiceConnectException {
		
		// Not needed on local files
	}

	@Override
	public void close() {
		
		// Not needed on local files
	}
}
