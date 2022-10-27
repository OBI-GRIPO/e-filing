package gr.cyberstream.obi.payments.service;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.math.BigDecimal;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import gr.cyberstream.obi.payments.exceptions.ReportNotFoundException;
import gr.cyberstream.obi.payments.exceptions.ReportReadException;
import gr.cyberstream.obi.payments.exceptions.TransferServiceConnectException;
import gr.cyberstream.obi.payments.model.Payment;
import gr.cyberstream.obi.payments.service.transfer.SFTPTransferService;
import gr.cyberstream.obi.payments.service.transfer.TransferService;

public class PaymentsReportService {
	
	private Logger logger = LoggerFactory.getLogger("gr.cyberstream.obi.payments.service.PaymentsReportService");
	
	private TransferService transferService;
	
	private static final String ORGANIZATION_ID = "OBI";
	private static final String REPORT_EXTENSION = "csv";
	private String lobId; // "OBI90772"
	
	private SimpleDateFormat dateFormat;
	private SimpleDateFormat datetimeFormat;
	
	public PaymentsReportService() {
	}
	
	public PaymentsReportService(String host, int port, String username, String password, String knownHosts) {
		
		logger.info("Initializing PaymentsReportService. {}:{} - {}", host, port, username);
		
		this.transferService = new SFTPTransferService(host, port, username, password, knownHosts);
		
		dateFormat = new SimpleDateFormat("yyyy-MM-dd");
		datetimeFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
	}

	public List<Payment> getPayments() throws TransferServiceConnectException, ReportReadException {
		
		List<Payment> payments = new ArrayList<>();
		
		try {
			
			transferService.connect();
			
		} catch (TransferServiceConnectException e) {
			
			throw new ReportReadException("Unable to connect to transfer service. " + e.getMessage());
		}
		
		Date date = new Date();
		
		for (int cycle = 1; cycle <= 6; cycle++) {
			
			payments.addAll(processReport(date, "0" + cycle));
		}
		
		transferService.close();
		
		return payments;
	}
	
	public List<Payment> processReport(Date date, String cycle) throws TransferServiceConnectException, ReportReadException {
	
		List<Payment> reportPayments = new ArrayList<>();
		
		try (
				InputStream reportIn = transferService.readReport(ORGANIZATION_ID, date, cycle, lobId, REPORT_EXTENSION);
				BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(reportIn, "Cp1253"));	) {
			
			String line;
			
			// Ignore header line
			line = bufferedReader.readLine();
			
		    while ((line = bufferedReader.readLine()) != null) {
		    	
		    	// Ignore footer line
		    	if (line.startsWith("\"eof\"")) {
		    		break;
		    	}
		    	
		        reportPayments.add(processLine(line));
		    }
			
		} catch (IOException e) {
			
			throw new ReportReadException("Unable to read report file. " + e.getMessage());
			
		} catch (ReportNotFoundException e) {
			
			logger.debug("Report file for cycle {} not found. {}", cycle, e.getMessage());	
		}
		
		return reportPayments;
	}
	
	private Payment processLine(String line) {
		
		String[] values = line.split(";");
        
        BigDecimal paidAmount = new BigDecimal(values[3].trim().replace(',', '.'));
        
        Payment payment = new Payment(values[0].trim(), values[1].trim(), paidAmount, getDateValue(values, 4),
        		getDateValue(values, 5), values[7].trim(), values[10].trim(), values[12].trim(), values[14].trim(),
        		values[16].trim(), values[17].trim(), values[19].trim());
        
        payment.setRiMid(getStringValue(values, 2));
        
        payment.setPaymentDate(getDatetimeValue(values, 6));
        payment.setDiasReference(getStringValue(values, 11));
        payment.setEndToEndId(getStringValue(values, 13));
        payment.setDebtorBankCode(getStringValue(values, 15));
		payment.setChannel(getStringValue(values, 18));
		payment.setRemittanceInformation(getStringValue(values, 20));
		
		return payment;
	}
	
	private String getStringValue(String[] values, int index) {
		
		if (values.length > index && values[index] != null && values[index].trim().length() > 0) {
		    
			return values[index].trim();
		}
		
		return null;
	}
	
	private Date getDateValue(String[] values, int index) {
		
		Date date = null;
		
		if (values.length > index && values[index] != null && values[index].trim().length() > 0) {
			
			try {
				date = dateFormat.parse(values[index].trim());
				
			} catch (ParseException e) {
				
				logger.warn("Unable to parse date {}. {}", values[index].trim(), e.getMessage());
			}
		}
        
		return date;
	}
	
	private Date getDatetimeValue(String[] values, int index) {
		
		Date date = null;
		
		if (values.length > index && values[index] != null && values[index].trim().length() > 0) {
			
			try {
				date = datetimeFormat.parse(values[index].trim());
				
			} catch (ParseException e) {
				
				logger.warn("Unable to parse datetime {}. {}", values[index].trim(), e.getMessage());
			}
		}
        
		return date;
	}
}
