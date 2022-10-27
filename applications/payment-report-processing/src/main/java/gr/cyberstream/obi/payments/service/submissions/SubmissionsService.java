package gr.cyberstream.obi.payments.service.submissions;

import java.util.List;

import gr.cyberstream.obi.payments.model.Submission;

public interface SubmissionsService {

	public Submission getSubmission(String rf);
	public List<Submission> getSubmissions(List<String> caseIds);	
}
