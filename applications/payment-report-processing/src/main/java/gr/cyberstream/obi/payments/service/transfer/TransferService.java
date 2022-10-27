package gr.cyberstream.obi.payments.service.transfer;

import java.io.InputStream;
import java.util.Date;

import gr.cyberstream.obi.payments.exceptions.ReportNotFoundException;
import gr.cyberstream.obi.payments.exceptions.TransferServiceConnectException;

public interface TransferService {
	
	public void connect() throws TransferServiceConnectException;

	public InputStream readReport(String orgId, Date date, String cycle, String lobId, String extension) 
			throws TransferServiceConnectException, ReportNotFoundException;
	
	public void close();
}
