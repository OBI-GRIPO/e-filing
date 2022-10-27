package gr.cyberstream.obi.payments.model;

import java.io.Serializable;
import java.math.BigDecimal;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Map;

public class Submission implements Serializable {
	
	private static final long serialVersionUID = 1L;
	
	private String id;
	private String formId;
	private String ownerId;

	// applicant_category_m
	private String applicantCategory;
	
	// applicant_email_m
	private String applicantEmail;
	
	// applicant_name_m
	private String applicantName;
	
	// applicant_surname_m
	private String applicantSurname;
	
	// applicant_vat_no_m
	private String applicantVatNo;
	
	
	
	// application_type
	private String applicationType;
	
	// case_id
	private String caseId;
	
	// first_payment_id
	private String submissionPaymentRF;
	
	// final_payment_id
	private String finalPaymentRF;
	
	// total_application_fees
	private BigDecimal submissionPaymentAmount;
	
	// last_total
	private BigDecimal finalPaymentAmount;
	
	// payment_submission_date
	private Date submissionPaymentDate;
	
	// payment_second_date
	private Date finalPaymentDate;
	
	// submission_status
	private String status;
	
	public static final String FIELD_APPLICANT_CATEGORY = "applicant_category_m";
	public static final String FIELD_APPLICANT_EMAIL = "applicant_email_m";
	public static final String FIELD_APPLICANT_NAME = "applicant_name_m";
	public static final String FIELD_APPLICANT_SURNAME = "applicant_surname_m";
	public static final String FIELD_APPLICANT_VAT_NO = "applicant_vat_no_m";
	public static final String FIELD_APPLICATION_TYPE = "application_type";
	public static final String FIELD_CASE_ID = "case_id";
	public static final String FIELD_SUBMISSION_PAYMENT_RF = "first_payment_id";
	public static final String FIELD_SUBMISSION_PAYMENT_DATE = "payment_submission_date";
	public static final String FIELD_SUBMISSION_PAYMENT_AMOUNT = "total_application_fees";
	public static final String FIELD_FINAL_PAYMENT_RF = "final_payment_id";
	public static final String FIELD_FINAL_PAYMENT_DATE = "payment_second_date";
	public static final String FIELD_FINAL_PAYMENT_AMOUNT = "last_total";
	public static final String FIELD_STATUS = "submission_status";
		
	public Submission(String id, String formId, String ownerId, Map<String, Object> data) {
		
		this.id = id;
		this.formId = formId;
		this.ownerId = ownerId;
		
		this.applicantCategory = data.get(FIELD_APPLICANT_CATEGORY) != null ? (String) data.get(FIELD_APPLICANT_CATEGORY) : null;
		this.applicantEmail = data.get(FIELD_APPLICANT_EMAIL) != null ? (String) data.get(FIELD_APPLICANT_EMAIL) : null;
		this.applicantName = data.get(FIELD_APPLICANT_NAME) != null ? (String) data.get(FIELD_APPLICANT_NAME) : null;
		this.applicantSurname = data.get(FIELD_APPLICANT_SURNAME) != null ? (String) data.get(FIELD_APPLICANT_SURNAME) : null;
		this.applicantVatNo = data.get(FIELD_APPLICANT_VAT_NO) != null ? (String) data.get(FIELD_APPLICANT_VAT_NO) : null;
		
		if (data.containsKey(FIELD_APPLICATION_TYPE) && data.get(FIELD_APPLICATION_TYPE) != null
				&& data.get(FIELD_APPLICATION_TYPE) instanceof String) {
		
			this.applicationType = (String) data.get(FIELD_APPLICATION_TYPE);
		}
		
		if (data.containsKey(FIELD_CASE_ID) && data.get(FIELD_CASE_ID) != null) {
			
			if (data.get(FIELD_CASE_ID) instanceof Integer) {
				
				this.caseId = "" + (Integer) data.get(FIELD_CASE_ID);
				
			} else if (data.get(FIELD_CASE_ID) instanceof String) {
				
				this.caseId = (String) data.get(FIELD_CASE_ID);
			}
		}
		
		this.submissionPaymentRF = data.get(FIELD_SUBMISSION_PAYMENT_RF) != null ? (String) data.get(FIELD_SUBMISSION_PAYMENT_RF) : null;
		
		
		if (data.containsKey(FIELD_SUBMISSION_PAYMENT_DATE) && data.get(FIELD_SUBMISSION_PAYMENT_DATE) != null
				&& data.get(FIELD_SUBMISSION_PAYMENT_DATE) instanceof Date) {
			submissionPaymentDate = (Date) data.get(FIELD_SUBMISSION_PAYMENT_DATE);
		}
		
		if (data.containsKey(FIELD_SUBMISSION_PAYMENT_AMOUNT) && data.get(FIELD_SUBMISSION_PAYMENT_AMOUNT) != null
				&& data.get(FIELD_SUBMISSION_PAYMENT_AMOUNT) instanceof Integer) {
			
			submissionPaymentAmount = new BigDecimal((Integer) data.get(FIELD_SUBMISSION_PAYMENT_AMOUNT));
		}
		
		this.finalPaymentRF = data.get(FIELD_FINAL_PAYMENT_RF) != null ? (String) data.get(FIELD_FINAL_PAYMENT_RF) : null;
		
		if (data.containsKey(FIELD_FINAL_PAYMENT_DATE) && data.get(FIELD_FINAL_PAYMENT_DATE) != null
				&& data.get(FIELD_FINAL_PAYMENT_DATE) instanceof Date) {
			finalPaymentDate = (Date) data.get(FIELD_FINAL_PAYMENT_DATE);
		}
		
		if (data.containsKey(FIELD_FINAL_PAYMENT_AMOUNT) && data.get(FIELD_FINAL_PAYMENT_AMOUNT) != null
				&& data.get(FIELD_FINAL_PAYMENT_AMOUNT) instanceof Integer) {
			
			finalPaymentAmount = new BigDecimal((Integer) data.get(FIELD_FINAL_PAYMENT_AMOUNT));
		}
		
		this.status = data.get(FIELD_STATUS) != null ? (String) data.get(FIELD_STATUS) : null;
	}
	
	@Override
	public String toString() {
		
		SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd");
				
		StringBuilder builder = new StringBuilder();
		
		builder.append("{");
		builder.append("\"id\": \""); builder.append(id); builder.append("\",");
		builder.append("\"formId\": \""); builder.append(formId); builder.append("\",");
		builder.append("\"ownerId\": \""); builder.append(ownerId); builder.append("\",");
		builder.append("\"applicantCategory\": \""); builder.append(applicantCategory); builder.append("\",");
		builder.append("\"applicantEmail\": \""); builder.append(applicantEmail); builder.append("\",");
		builder.append("\"applicantName\": \""); builder.append(applicantName); builder.append("\",");
		builder.append("\"applicantSurname\": \""); builder.append(applicantSurname); builder.append("\",");
		builder.append("\"applicantVatNo\": \""); builder.append(applicantVatNo); builder.append("\",");
		builder.append("\"applicationType\": \""); builder.append(applicationType); builder.append("\",");
		builder.append("\"caseId\": \""); builder.append(caseId); builder.append("\",");
		
		builder.append("\"submissionPaymentRF\": \""); builder.append(submissionPaymentRF); builder.append("\",");
		
		if (submissionPaymentAmount != null) {
			builder.append("\"submissionPaymentAmount\": "); builder.append(submissionPaymentAmount); builder.append(",");
		}
		
		if (submissionPaymentDate != null) {
			builder.append("\"submissionPaymentDate\": \""); builder.append(dateFormat.format(submissionPaymentDate)); builder.append("\",");
		}
		
		builder.append("\"finalPaymentRF\": \""); builder.append(finalPaymentRF); builder.append("\",");
		
		if (finalPaymentAmount != null) {
			builder.append("\"finalPaymentAmount\": "); builder.append(finalPaymentAmount); builder.append(",");
		}
		
		if (finalPaymentDate != null) {
			builder.append("\"finalPaymentDate\": \""); builder.append(dateFormat.format(finalPaymentDate)); builder.append("\",");
		}
		
		builder.append("\"status\": \""); builder.append(status); builder.append("\"");
		builder.append("}");
		
		return builder.toString();
	}
	
	public String getId() {
		return id;
	}

	public void setId(String id) {
		this.id = id;
	}
	
	public String getFormId() {
		return formId;
	}

	public void setFormId(String formId) {
		this.formId = formId;
	}

	public String getOwnerId() {
		return ownerId;
	}

	public void setOwnerId(String ownerId) {
		this.ownerId = ownerId;
	}

	public String getApplicantCategory() {
		return applicantCategory;
	}
	
	public void setApplicantCategory(String applicantCategory) {
		this.applicantCategory = applicantCategory;
	}
	
	public String getApplicantEmail() {
		return applicantEmail;
	}
	
	public void setApplicantEmail(String applicantEmail) {
		this.applicantEmail = applicantEmail;
	}
	
	public String getApplicantName() {
		return applicantName;
	}
	
	public void setApplicantName(String applicantName) {
		this.applicantName = applicantName;
	}
	
	public String getApplicantSurname() {
		return applicantSurname;
	}
	
	public void setApplicantSurname(String applicantSurname) {
		this.applicantSurname = applicantSurname;
	}
	
	public String getApplicantVatNo() {
		return applicantVatNo;
	}
	
	public void setApplicantVatNo(String applicantVatNo) {
		this.applicantVatNo = applicantVatNo;
	}
	
	public String getApplicationType() {
		return applicationType;
	}
	
	public void setApplicationType(String applicationType) {
		this.applicationType = applicationType;
	}
	
	public String getCaseId() {
		return caseId;
	}
	
	public void setCaseId(String caseId) {
		this.caseId = caseId;
	}
	
	public String getSubmissionPaymentRF() {
		return submissionPaymentRF;
	}

	public void setSubmissionPaymentRF(String submissionPaymentRF) {
		this.submissionPaymentRF = submissionPaymentRF;
	}

	public String getFinalPaymentRF() {
		return finalPaymentRF;
	}
	
	public BigDecimal getSubmissionPaymentAmount() {
		return submissionPaymentAmount;
	}
	
	public void setSubmissionPaymentAmount(BigDecimal submissionPaymentAmount) {
		this.submissionPaymentAmount = submissionPaymentAmount;
	}
	
	public BigDecimal getFinalPaymentAmount() {
		return finalPaymentAmount;
	}
	
	public void setFinalPaymentAmount(BigDecimal finalPaymentAmount) {
		this.finalPaymentAmount = finalPaymentAmount;
	}

	public void setFinalPaymentRF(String finalPaymentRF) {
		this.finalPaymentRF = finalPaymentRF;
	}

	public Date getSubmissionPaymentDate() {
		return submissionPaymentDate;
	}

	public void setSubmissionPaymentDate(Date submissionPaymentDate) {
		this.submissionPaymentDate = submissionPaymentDate;
	}

	public Date getFinalPaymentDate() {
		return finalPaymentDate;
	}

	public void setFinalPaymentDate(Date finalPaymentDate) {
		this.finalPaymentDate = finalPaymentDate;
	}
	
	public String getStatus() {
		return status;
	}
	
	public void setStatus(String status) {
		this.status = status;
	}
}
