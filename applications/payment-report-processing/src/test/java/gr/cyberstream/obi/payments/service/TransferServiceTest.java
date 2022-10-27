package gr.cyberstream.obi.payments.service;

import static org.junit.Assert.assertTrue;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.util.Calendar;

import org.junit.Test;

import gr.cyberstream.obi.payments.exceptions.ReportNotFoundException;
import gr.cyberstream.obi.payments.exceptions.TransferServiceConnectException;
import gr.cyberstream.obi.payments.service.transfer.LocalTransferService;
import gr.cyberstream.obi.payments.service.transfer.SFTPTransferService;
import gr.cyberstream.obi.payments.service.transfer.TransferService;

public class TransferServiceTest {

	@Test
	public void getReport() {
		
		String orgId = "OBI";
		
		String extension = "csv";
		
		// ftp.dias.com.gr
		String host = "10.0.0.182";
		int port = 22;
		// OBI
		String username = "obi";
		String password = "cyberstream";
		
		Calendar cal = Calendar.getInstance();
		
		TransferService transferService = new SFTPTransferService(host, port, username, password,
				"|1|QGv6yfU1PIX6tyqLwVx4Sq7vQWE=|iK25xsuGOCWAoZxlkQUQu6lgrFc= ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDf5kYFqrxSh6WlulywLr80hyfePfz5Bs6bdPlx3CydcwR23rbZxBsd6xrlvV0HR5X40iRCnPCDT4IPHSFhK11cUHbqAe1KmpyMNbHUOUUNKbcIamPBacQ5utqzu7RhugExOVbgP14oZS9wBnRZrtCXKG90Vx0Pr7pmtCIRsrp29b4l0uvGq4IKtF4mUSUxLkAQvQLvrhGhU8smJkuKmhjKLJO/t2mXpEQ1KkXYwDXnfqI/5sLchvrBHJ42hEAIuJYx0qVkcEmknZN7adPL/oikt2Ie2vJj/QfgIQKMOzmxH0Q5VxhW2xPT7PsKAXSo3ah1Qyrt3VyYsmVukfUeQc/Z");
		
		try {
			
			transferService.connect();
			
		} catch (TransferServiceConnectException e) {
			
			assertTrue(false);
		}
		
		try (
				InputStream reportIn = transferService.readReport(orgId, cal.getTime(), "05", null, extension);
				BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(reportIn, "Cp1252"));	) {
						
			String line;
			
			// Ignore header line
			line = bufferedReader.readLine();
			
		    while ((line = bufferedReader.readLine()) != null) {
		    	
		    	// Ignore footer line
		    	if (line.startsWith("\"eof\"")) {
		    		break;
		    	}
		    	
		        System.out.println(line);
		    }
			
		} catch (IOException e) {
			
			assertTrue(false);
			
		} catch (TransferServiceConnectException e) {
			
			assertTrue(false);
			
		} catch (ReportNotFoundException e) {
			
		} finally {
			
			transferService.close();
		}
	}
	
	@Test
	public void getLocalFileReport() {
		
		String orgId = "OBI";
		
		String extension = "csv";
		
		Calendar cal = Calendar.getInstance();
		cal.set(2020, 8, 16);
		
		TransferService transferService = new LocalTransferService();
		
		try {
			
			transferService.connect();
			
		} catch (TransferServiceConnectException e) {
			
			assertTrue(false);
		}
		
		try (
				InputStream reportIn = transferService.readReport(orgId, cal.getTime(), "05", null, extension);
				BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(reportIn, "Cp1253"));	) {
						
			String line;
			
			// Ignore header line
			line = bufferedReader.readLine();
			
		    while ((line = bufferedReader.readLine()) != null) {
		    	
		    	// Ignore footer line
		    	if (line.startsWith("\"eof\"")) {
		    		break;
		    	}
		    	
		        System.out.println(line);
		    }
			
		} catch (IOException e) {
			
			assertTrue(false);
			
		} catch (TransferServiceConnectException e) {
			
			assertTrue(false);
			
		} catch (ReportNotFoundException e) {


		} finally {
			
			transferService.close();
		}
	}
}
