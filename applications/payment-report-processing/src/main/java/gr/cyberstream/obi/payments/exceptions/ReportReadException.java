package gr.cyberstream.obi.payments.exceptions;

public class ReportReadException extends Exception {

	private static final long serialVersionUID = 1L;

	public ReportReadException() {
		super();
	}
	
	public ReportReadException(String message) {
		super(message);
	}

	public ReportReadException(Throwable e) {
		super(e);
	}
	
	
}
