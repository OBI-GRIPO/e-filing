package gr.cyberstream.obi.payments.model;

import java.util.HashMap;
import java.util.Map;

public class ProcessUpdateData {

	private Map<String, String> submission;
	private Map<String, String> payment;
	
	public ProcessUpdateData(String formId, String submissionId, String paymentRF) {
		
		submission = new HashMap<>();
		submission.put("form", formId);
		submission.put("_id", submissionId);
		
		payment = new HashMap<>();
		payment.put("id", paymentRF);
	}

	public Map<String, String> getSubmission() {
		return submission;
	}

	public void setSubmission(Map<String, String> submission) {
		this.submission = submission;
	}

	public Map<String, String> getPayment() {
		return payment;
	}

	public void setPayment(Map<String, String> payment) {
		this.payment = payment;
	}
}
