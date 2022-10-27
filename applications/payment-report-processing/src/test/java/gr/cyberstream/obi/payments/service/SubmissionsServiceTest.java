package gr.cyberstream.obi.payments.service;

import static org.junit.Assert.assertNotNull;
import static org.junit.Assert.assertTrue;

import java.util.Arrays;
import java.util.List;
import java.util.Map;

import org.junit.Test;

import gr.cyberstream.obi.payments.model.Submission;
import gr.cyberstream.obi.payments.service.submissions.MongoDBSubmissionsService;
import gr.cyberstream.obi.payments.service.submissions.SubmissionsService;

public class SubmissionsServiceTest {
	
	@Test
	public void getAllSubmissions() {
		
		MongoDBSubmissionsService submissionsService = new MongoDBSubmissionsService("192.168.3.245");
		
		Map<String, Submission> submissions = submissionsService.getSubmissions();
		
		assertNotNull(submissions);
		assertTrue(submissions.size() > 0);
	}

	@Test
	public void getSubmissionsWithoutCaseIdPrefix() {
		
		SubmissionsService submissionsService = new MongoDBSubmissionsService("192.168.3.245");
		
		List<Submission> submissions = submissionsService
				.getSubmissions(Arrays.asList(new String[] {"0003398548", "0002997704"} ));
		
		assertNotNull(submissions);
		assertTrue(submissions.size() == 2);
	}
	
	@Test
	public void getSubmissionsWithCaseIdPrefix() {
		
		SubmissionsService submissionsService = new MongoDBSubmissionsService("192.168.3.245");
		
		List<Submission> submissions = submissionsService
				.getSubmissions(Arrays.asList(new String[] {"22-0003398548", "22-0002997704"} ));
		
		assertNotNull(submissions);
		assertTrue(submissions.size() == 2);
	}
}
