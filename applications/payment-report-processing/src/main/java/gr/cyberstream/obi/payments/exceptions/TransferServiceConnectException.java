package gr.cyberstream.obi.payments.exceptions;

public class TransferServiceConnectException extends Exception {

	private static final long serialVersionUID = 1L;

	public TransferServiceConnectException() {
		super();
	}
	
	public TransferServiceConnectException(String message) {
		super(message);
	}

	public TransferServiceConnectException(Throwable e) {
		super(e);
	}
}
