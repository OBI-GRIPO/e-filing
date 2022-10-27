package gr.cyberstream.obi.payments.exceptions;

public class ReportNotFoundException extends Exception {

	private static final long serialVersionUID = 1L;

	public ReportNotFoundException() {
		super();
	}
	
	public ReportNotFoundException(String message) {
		super(message);
	}

	public ReportNotFoundException(Throwable e) {
		super(e);
	}
}
