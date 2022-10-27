package gr.cyberstream.obi.payments.exceptions;

public class ProcessUpdateServiceServerErrorException extends Exception {

	private static final long serialVersionUID = 1L;

	public ProcessUpdateServiceServerErrorException() {
		super();
	}
	
	public ProcessUpdateServiceServerErrorException(String message) {
		super(message);
	}

	public ProcessUpdateServiceServerErrorException(Throwable e) {
		super(e);
	}	
}
