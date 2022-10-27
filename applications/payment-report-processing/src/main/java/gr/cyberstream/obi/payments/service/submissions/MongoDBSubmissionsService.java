package gr.cyberstream.obi.payments.service.submissions;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.stream.Collectors;

import org.bson.Document;
import org.bson.conversions.Bson;
import org.bson.types.ObjectId;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import com.mongodb.MongoClientSettings;
import com.mongodb.ServerAddress;
import com.mongodb.client.MongoClients;
import com.mongodb.client.MongoCollection;
import com.mongodb.client.MongoCursor;
import com.mongodb.client.MongoDatabase;
import com.mongodb.client.MongoClient;
import static com.mongodb.client.model.Filters.*;
import static com.mongodb.client.model.Projections.*;

import gr.cyberstream.obi.payments.model.Submission;

public class MongoDBSubmissionsService implements SubmissionsService {
	
	private Logger logger = LoggerFactory.getLogger("gr.cyberstream.obi.payments.service.SubmissionsService");
	
	private String host;
	private static final String DATABASE = "formio";
	private static final String COLLECTION = "submissions";
	
	private static final String FIELD_ID = "_id";
	private static final String FIELD_OWNER = "owner";
	private static final String FIELD_FORM = "form";
	private static final String FIELD_DATA = "data";
	
	public MongoDBSubmissionsService(String host) {
		
		logger.info("Initializing MongoDBSubmissionsService. Host:{}", host);
		
		this.host = host;
	}
	
	@SuppressWarnings("unchecked")
	public Submission getSubmission(String rf) {
		
		Submission submission = null;
		
		try ( MongoClient mongoClient = MongoClients.create(MongoClientSettings.builder()
				.applyToClusterSettings(builder -> builder.hosts(Arrays.asList(new ServerAddress(host)))).build()); ) {
			
			MongoDatabase database = mongoClient.getDatabase(DATABASE);
			MongoCollection<Document> collection = database.getCollection(COLLECTION);
			
			MongoCursor<Document> submissionsCursor = collection.find(or(
						eq(FIELD_DATA + "." + Submission.FIELD_SUBMISSION_PAYMENT_RF, rf),
						eq(FIELD_DATA + "." + Submission.FIELD_FINAL_PAYMENT_RF, rf)))
					.projection(fields(include(FIELD_ID, FIELD_FORM, FIELD_OWNER, FIELD_DATA + "." + "." + Submission.FIELD_APPLICANT_CATEGORY,
							FIELD_DATA + "." + Submission.FIELD_APPLICANT_EMAIL, FIELD_DATA + "." + Submission.FIELD_APPLICANT_NAME,
							FIELD_DATA + "." + Submission.FIELD_APPLICANT_SURNAME, FIELD_DATA + "." + Submission.FIELD_APPLICANT_VAT_NO,
							FIELD_DATA + "." + Submission.FIELD_APPLICATION_TYPE, FIELD_DATA + "." + Submission.FIELD_CASE_ID, 
							FIELD_DATA + "." + Submission.FIELD_SUBMISSION_PAYMENT_DATE, FIELD_DATA + "." + Submission.FIELD_SUBMISSION_PAYMENT_AMOUNT, 
							FIELD_DATA + "." + Submission.FIELD_SUBMISSION_PAYMENT_RF, FIELD_DATA + "." + Submission.FIELD_FINAL_PAYMENT_DATE,
							FIELD_DATA + "." + Submission.FIELD_FINAL_PAYMENT_AMOUNT, FIELD_DATA + "." + Submission.FIELD_FINAL_PAYMENT_RF,
							FIELD_DATA + "." + Submission.FIELD_STATUS)))
					.iterator();
			
			while(submissionsCursor.hasNext()) {
					
				Document doc = submissionsCursor.next();
				
				String id = doc.get(FIELD_ID, ObjectId.class) != null ? doc.get(FIELD_ID, ObjectId.class).toString() : null;
				
				if (submission == null) {
					
					String formId = doc.get(FIELD_FORM, ObjectId.class) != null ? doc.get(FIELD_FORM, ObjectId.class).toString() : null;
					String ownerId = doc.get(FIELD_OWNER, ObjectId.class) != null ? doc.get(FIELD_OWNER, ObjectId.class).toString() : null;
					
					submission = new Submission(id, formId, ownerId, (Map<String, Object>) doc.get(FIELD_DATA));
					
				} else {
					
					logger.warn("## Submission with RF {} already found. Submission {} will be ignored.", rf, id);
				}
			}
		}
		
		return submission;
	}
	
	@SuppressWarnings("unchecked")
	public List<Submission> getSubmissions(List<String> caseIds) {
		
		List<Submission> submissions = new ArrayList<>();
		
		try ( MongoClient mongoClient = MongoClients.create(MongoClientSettings.builder()
				.applyToClusterSettings(builder -> builder.hosts(Arrays.asList(new ServerAddress(host)))).build()); ) {
			
			MongoDatabase database = mongoClient.getDatabase(DATABASE);
			MongoCollection<Document> collection = database.getCollection(COLLECTION);
			
			List<Bson> filters = caseIds.stream().map(caseId -> regex(FIELD_DATA + "." + Submission.FIELD_CASE_ID, ".*" + caseId))
					.collect(Collectors.toList());
			
			MongoCursor<Document> submissionsCursor = collection.find(or(filters))
					.projection(fields(include(FIELD_ID, FIELD_FORM, FIELD_OWNER, FIELD_DATA + "." + Submission.FIELD_APPLICANT_CATEGORY,
							FIELD_DATA + "." + Submission.FIELD_APPLICANT_EMAIL, FIELD_DATA + "." + Submission.FIELD_APPLICANT_NAME,
							FIELD_DATA + "." + Submission.FIELD_APPLICANT_SURNAME, FIELD_DATA + "." + Submission.FIELD_APPLICANT_VAT_NO,
							FIELD_DATA + "." + Submission.FIELD_APPLICATION_TYPE, FIELD_DATA + "." + Submission.FIELD_CASE_ID, 
							FIELD_DATA + "." + Submission.FIELD_SUBMISSION_PAYMENT_DATE, FIELD_DATA + "." + Submission.FIELD_SUBMISSION_PAYMENT_RF,
							FIELD_DATA + "." + Submission.FIELD_SUBMISSION_PAYMENT_AMOUNT, FIELD_DATA + "." + Submission.FIELD_FINAL_PAYMENT_DATE,
							FIELD_DATA + "." + Submission.FIELD_FINAL_PAYMENT_RF, FIELD_DATA + "." + Submission.FIELD_FINAL_PAYMENT_AMOUNT,
							FIELD_DATA + "." + Submission.FIELD_STATUS)))
					.iterator();
			
			while(submissionsCursor.hasNext()) {
					
				Document doc = submissionsCursor.next();
				
				String id = doc.get(FIELD_ID, ObjectId.class) != null ? doc.get(FIELD_ID, ObjectId.class).toString() : null;
				String formId = doc.get(FIELD_FORM, ObjectId.class) != null ? doc.get(FIELD_FORM, ObjectId.class).toString() : null;
				String ownerId = doc.get(FIELD_OWNER, ObjectId.class) != null ? doc.get(FIELD_OWNER, ObjectId.class).toString() : null;
				
				Submission submission = new Submission(id, formId, ownerId, (Map<String, Object>) doc.get(FIELD_DATA));
				
				submissions.add(submission);
			}
		}
		
		return submissions;
	}
	
	@SuppressWarnings("unchecked")
	public Map<String, Submission> getSubmissions() {
		
		Map<String, Submission> submissions = new HashMap<>();
		
		try ( MongoClient mongoClient = MongoClients.create(MongoClientSettings.builder()
				.applyToClusterSettings(builder -> builder.hosts(Arrays.asList(new ServerAddress(host)))).build()); ) {
			
			MongoDatabase database = mongoClient.getDatabase(DATABASE);
			MongoCollection<Document> collection = database.getCollection(COLLECTION);
			
			MongoCursor<Document> submissionsCursor = collection.find()
					.projection(fields(include(FIELD_ID, FIELD_FORM, FIELD_OWNER, FIELD_DATA + "." + Submission.FIELD_APPLICANT_CATEGORY,
							FIELD_DATA + "." + Submission.FIELD_APPLICANT_EMAIL, FIELD_DATA + "." + Submission.FIELD_APPLICANT_NAME,
							FIELD_DATA + "." + Submission.FIELD_APPLICANT_SURNAME, FIELD_DATA + "." + Submission.FIELD_APPLICANT_VAT_NO,
							FIELD_DATA + "." + Submission.FIELD_APPLICATION_TYPE, FIELD_DATA + "." + Submission.FIELD_CASE_ID,
							FIELD_DATA + "." + Submission.FIELD_SUBMISSION_PAYMENT_DATE, FIELD_DATA + "." + Submission.FIELD_SUBMISSION_PAYMENT_RF,
							FIELD_DATA + "." + Submission.FIELD_SUBMISSION_PAYMENT_AMOUNT, FIELD_DATA + "." + Submission.FIELD_FINAL_PAYMENT_DATE,
							FIELD_DATA + "." + Submission.FIELD_FINAL_PAYMENT_RF, FIELD_DATA + "." + Submission.FIELD_FINAL_PAYMENT_AMOUNT,
							FIELD_DATA + "." + Submission.FIELD_STATUS)))
					.iterator();
			
			while(submissionsCursor.hasNext()) {
				
				Document doc = submissionsCursor.next();
				
				try {
					
					String id = doc.get(FIELD_ID, ObjectId.class) != null ? doc.get(FIELD_ID, ObjectId.class).toString() : null;
					String formId = doc.get(FIELD_FORM, ObjectId.class) != null ? doc.get(FIELD_FORM, ObjectId.class).toString() : null;
					String ownerId = doc.get(FIELD_OWNER, ObjectId.class) != null ? doc.get(FIELD_OWNER, ObjectId.class).toString() : null;
					
					submissions.put(id, new Submission(id, formId, ownerId, (Map<String, Object>) doc.get(FIELD_DATA)));
				
				} catch (Exception e) {
					
					logger.warn("## Unable to create submission object. {}", e.getMessage());
				}
			}
		}
		
		return submissions;
	}
}
