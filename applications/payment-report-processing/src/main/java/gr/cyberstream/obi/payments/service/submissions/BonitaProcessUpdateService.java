package gr.cyberstream.obi.payments.service.submissions;

import org.springframework.http.HttpHeaders;
import org.springframework.http.HttpMethod;
import org.springframework.http.HttpStatus;
import org.springframework.http.MediaType;
import org.springframework.web.reactive.function.BodyInserters;
import org.springframework.web.reactive.function.client.WebClient;

import gr.cyberstream.obi.payments.exceptions.ProcessUpdateServiceAuthenticationException;
import gr.cyberstream.obi.payments.exceptions.ProcessUpdateServiceServerErrorException;
import gr.cyberstream.obi.payments.model.ProcessUpdateData;
import reactor.core.publisher.Mono;

public class BonitaProcessUpdateService implements ProcessUpdateService {
	
	private String nodeHost;
	
	public BonitaProcessUpdateService(String nodeHost) {
		
		this.nodeHost = nodeHost;
	}

	public String updateProcess(String formId, String submissionId, String paymentRF) {
		
		ProcessUpdateData data = new ProcessUpdateData(formId, submissionId, paymentRF);
		
		WebClient client = WebClient.builder()
				.baseUrl("http://" + nodeHost)
				.defaultHeader(HttpHeaders.CONTENT_TYPE, MediaType.APPLICATION_JSON_VALUE)
				.build();
		
		client.method(HttpMethod.POST).uri("/send/payment")
			.body(BodyInserters.fromPublisher(Mono.just(data), ProcessUpdateData.class))
			.retrieve()
			.onStatus(HttpStatus::is4xxClientError, clientResponse ->
				Mono.error(new ProcessUpdateServiceAuthenticationException()))
			.onStatus(HttpStatus::is5xxServerError, clientResponse ->
				Mono.error(new ProcessUpdateServiceServerErrorException()));
		
		return "FAILED";
	}
}
