package gr.cyberstream.obi.payments.service;

import static org.junit.Assert.assertNotNull;
import static org.junit.Assert.assertTrue;

import java.util.List;

import org.junit.Test;

import gr.cyberstream.obi.payments.exceptions.ReportReadException;
import gr.cyberstream.obi.payments.exceptions.TransferServiceConnectException;
import gr.cyberstream.obi.payments.model.Payment;

public class ReportProcessingServiceTest {

	@Test
	public void getPaymentsTest() {
		
		PaymentsReportService paymentsReportService = new PaymentsReportService("10.0.0.182", 22, "obi", "cyberstream",
				"|1|QGv6yfU1PIX6tyqLwVx4Sq7vQWE=|iK25xsuGOCWAoZxlkQUQu6lgrFc= ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQDf5kYFqrxSh6WlulywLr80hyfePfz5Bs6bdPlx3CydcwR23rbZxBsd6xrlvV0HR5X40iRCnPCDT4IPHSFhK11cUHbqAe1KmpyMNbHUOUUNKbcIamPBacQ5utqzu7RhugExOVbgP14oZS9wBnRZrtCXKG90Vx0Pr7pmtCIRsrp29b4l0uvGq4IKtF4mUSUxLkAQvQLvrhGhU8smJkuKmhjKLJO/t2mXpEQ1KkXYwDXnfqI/5sLchvrBHJ42hEAIuJYx0qVkcEmknZN7adPL/oikt2Ie2vJj/QfgIQKMOzmxH0Q5VxhW2xPT7PsKAXSo3ah1Qyrt3VyYsmVukfUeQc/Z");
		
		List<Payment> payments;
		
		try {
			payments = paymentsReportService.getPayments();
			
			assertNotNull(payments);
			assertTrue(payments.size() == 1);
			
		} catch (ReportReadException e) { 

			assertTrue(false);
			
		} catch (TransferServiceConnectException e) {
			
			assertTrue(false);
		}
	}
}
