package gr.cyberstream.obi.payments.model;

import java.io.Serializable;
import java.math.BigDecimal;
import java.text.SimpleDateFormat;
import java.util.Date;

public class Payment implements Serializable {
	
	private static final long serialVersionUID = 1L;

	// Κωδικοποιημένη δραστηριότητα.
	private String lobId;
	
	// 5ψήφιος κωδικός Οργανισμού σε κωδικοποίηση της ΔΙΑΣ.
	private String actorId;
	
	// Κωδικός Πληρωμής.
	private String riMid;
	
	// Ποσό εντολής πληρωμής σε EUR.
	private BigDecimal paidAmount;
	
	// Ημερομηνία πίστωσης. Δεν είναι συμπληρωμένη εάν το πεδίο Source έχει την τιμή ON.
	private Date bkDate;
	
	// Ημερομηνία παραλαβής πληρωμής.
	private Date receivingDate;
	
	/*
	 * Ημερομηνία και ώρα λήψης της εντολής πληρωμής από την Τράπεζα Πληρωτή.
	 * Η ώρα περιέχεται μόνο εφόσον η πληρωμή έχει αποσταλεί από την Τράπεζα Πληρωτή με online μήνυμα, 
	 * οπότε και δύναται να περιέχει την πραγματική ημερομηνία και ώρα πληρωμής ή την εσωτερική λογιστική 
	 * ημερομηνία της Τράπεζας Εντολέα (με Ώρα 00:00:00). Στη δεύτερη περίπτωση, ως πραγματική ημερομηνία 
	 * και ώρα πληρωμής θεωρείται η τιμή του πεδίου creationDtTm (Παράρτημα Ε.1) και οι τιμές των πεδίων 
	 * Ημερομηνία Παραλαβής και Ώρα Παραλαβής (Παράρτημα Ε.2).
	 */
	private Date paymentDate;
	
	/*
	 * Οι τιμές που ακολουθούν υποδηλώνουν ότι η πληρωμή έχει εκκαθαριστεί (εφόσον είναι συμπληρωμένη η ημερομηνία πίστωσης, bkDate) 
	 * και θα πιστωθεί στο λογαριασμό του Οργανισμού εντός της ημέρας:
	 * 0: Επιτυχής
	 * 1: Επιτυχής√
	 * Οι τιμές που ακολουθούν εμφανίζονται μόνο σε αρχεία που παραλαμβάνονται μέσω Payment Gateway (download):
	 * 2: Απορριφθείσα
	 * 3: Απορριφθείσα – Μη επιβεβαιωμένη.
	 */
	private String status;
	
	/*
	 * Κωδικός αιτιολογίας επιστροφής
	 * AM05: Διπλή καταβολή [Επιτρεπτή χρήση: έως και 3 εργάσιμες μετά την bkDate της αρχικής πληρωμής].
	 * MS03: Γενικός κωδικός απόρριψης [Επιτρεπτή χρήση: έως και 3 εργάσιμες μετά την bkDate της αρχικής πληρωμής].
	 * FOCR: Απόρριψη κατόπιν αιτήματος του πληρωτή ή μετά την παρέλευση 3 εργασίμων ημερών από την αρχική πληρωμή.
	 *       [Επιτρεπτή χρήση: έως και 1 μήνα μετά την bkDate της αρχικής πληρωμής].
	 * AM06: Το ποσό πληρωμής είναι μικρότερο του οφειλόμενου [Επιτρεπτή χρήση: έως και 3 εργάσιμες μετά την bkDate της αρχικής πληρωμής].
	 * BE01: Τα στοιχεία πληρωμής δεν συμφωνούν με τις υποχρεώσεις του πελάτη.
	 *       Ο κωδικός πληρωμής δεν είναι συμπληρωμένος (formerly CreditorConsistency)
	 *       [Επιτρεπτή χρήση: έως και 3 εργάσιμες μετά την bkDate της αρχικής πληρωμής].
	 * BE06: Ανύπαρκτος ή λανθασμένος κωδικός πληρωμής [Επιτρεπτή χρήση: έως και 3 εργάσιμες μετά την bkDate της αρχικής πληρωμής].
	 * DT01: Καθυστερημένη πληρωμή (eg, wrong or missing settlement date) 
	 *       [Επιτρεπτή χρήση: έως και 3 εργάσιμες μετά την bkDate της αρχικής πληρωμής].
	 */
	private String returnCode;
	
	// Επιστρεφόμενο ποσό. Είναι ίσο με το αρχικό ποσό της εντολής πληρωμής.
	private BigDecimal returnAmount;	
	
	// Μοναδικός κωδικός αναφοράς, ο οποίος δημιουργείται από την υπηρεσία. Αποτελεί τον μοναδικό κωδικό για την αποστολή επιστροφής 
	// ή για τη συμφωνία με τα εισερχόμενα online μηνύματα.
	private String rid;
	
	// Μοναδικός κωδικός αναφοράς του Συστήματος Πληρωμών ΔΙΑΣ.
	private String diasReference;
	
	// Μοναδικός κωδικός αναφοράς της Τράπεζας Πληρωτή.
	private String transactionId;
	
	// Κωδικός αναφοράς πληρωτή.
	private String endToEndId;
	
	// Κωδικός της Τράπεζας Πληρωτή σε μορφή BIC8 ή BIC11.
	private String debtorBankBic;
	
	// 3ψήφιος κωδικός της Τράπεζας Πληρωτή σύμφωνα με την κωδικοποίηση της ΔΙΑΣ.
	private String debtorBankCode;
	
	// Ονοματεπώνυμο ή Επωνυμία Πληρωτή.
	private String debtorName;
	
	// Συμπληρώνεται με την τιμή DCT.
	private String service;
	
	/*
	 * Κωδικοποιημένη πληροφορία που προσδιορίζει το μέσο πληρωμής, ως εξής:
	 * Από 0000 έως 9991: Λοιπά
	 * 9992: IRIS
	 * 9993: Κατάστημα με μετρητά
	 * 9994: Κατάστημα με χρέωση λογαριασμού
	 * 9995: APS
	 * 9996: Internet Banking
	 * 9997: Phone Banking
	 * 9998: ATM
	 * 9999: Κεντρικές Υπηρεσίες
	 */
	private String channel;
	
	/*
	 * Προέλευση της εντολής πληρωμής, ως εξής:
	 * B:  Η πληρωμή παρελήφθη μέσω αρχείου.
	 * OB: Έχει γίνει online πληρωμή στην Τράπεζα Πληρωτή (έχει προηγηθεί ενημέρωση με SOAP API, εφόσον ο Οργανισμός συνδέεται με αυτό τον τρόπο).
	 * ON: Η πληρωμή παρελήφθη με online μήνυμα αλλά δεν έχει πιστωθεί ακόμη στον λογαριασμό του Οργανισμού. 
	 *     Η τιμή αυτή εμφανίζεται μόνο για αρχεία που παραλαμβάνονται μέσω Payment Gateway (download).
	 * OR: Η πληρωμή παρελήφθη με online μήνυμα (προηγούμενη τιμή ON).
	 * Στις περιπτώσεις B, OB και OR η πληρωμή έχει εκκαθαριστεί και θα πιστωθεί στο λογαριασμό του Οργανισμού εντός της ημέρας.
	 */
	private String source;
	
	/*
	 * Περιέχει:
	 * - στα 25 πρώτα ψηφία τον Κωδικό Πληρωμής και στα υπόλοιπα, τα στοιχεία όπως μεταφέρθηκαν στη ΔΙΑΣ από την Τράπεζα Πληρωτή ή
	 * - μόνο τα πληροφοριακά στοιχεία όπως μεταφέρθηκαν στη ΔΙΑΣ από την Τράπεζα Πληρωτή, εάν δεν έχει αναγνωρισθεί Κωδικός Πληρωμής
	 *   (Παράρτημα Α, Παράμετρος 5).
	 */
	private String remittanceInformation;
	
	// Επιτυχής
	public static final String STATUS_SUCCESS = "0";
	// Επιτυχής√
	public static final String STATUS_SUCCESS_CHECK = "1";
	// Απορριφθείσα
	public static final String STATUS_REJECTED = "2";
	// Απορριφθείσα – Μη επιβεβαιωμένη
	public static final String STATUS_REJECTED_NOT_APPROVED = "3";
	
	// Διπλή καταβολή
	public static final String RETURN_CODE_DUPLICATION = "AM05";
	// Γενικός κωδικός απόρριψης
	public static final String RETURN_CODE_NOT_SPECIFIED_REASON_AGENT_GENERATED = "MS03";
	// Απόρριψη κατόπιν αιτήματος του πληρωτή ή μετά την παρέλευση 3 εργασίμων ημερών από την αρχική πληρωμή.
	public static final String RETURN_CODE_FOLLOWING_CANCELATION_REQUEST = "FOCR";
	// Το ποσό πληρωμής είναι μικρότερο του οφειλόμενου
	public static final String RETURN_CODE_TOO_LOW_AMOUNT = "AM06";
	// Τα στοιχεία πληρωμής δεν συμφωνούν με τις υποχρεώσεις του πελάτη. Ο κωδικός πληρωμής δεν είναι συμπληρωμένος (formerly CreditorConsistency)
	public static final String RETURN_CODE_INCONCISTENT_WITH_END_CUSTOMER = "BE01";
	// Ανύπαρκτος ή λανθασμένος κωδικός πληρωμής
	public static final String RETURN_CODE_UNKNOWN_END_CUSTOMER = "BE06";
	// Καθυστερημένη πληρωμή (eg, wrong or missing settlement date)
	public static final String RETURN_CODE_INVALID_DATE = "DT01";
	
	// IRIS
	public static final String CHANNEL_IRIS = "9992";
	// Κατάστημα με μετρητά
	public static final String CHANNEL_IN_STORE_CASH = "9993";
	// Κατάστημα με χρέωση λογαριασμού
	public static final String CHANNEL_IN_STORE_ACCOUNT = "9994";
	// APS
	public static final String CHANNEL_APS = "9995";
	// Internet Banking
	public static final String CHANNEL_INTERNET_BANKING= "9996";
	// Phone Banking
	public static final String CHANNEL_PHONE_BANKING = "9997";
	// ATM
	public static final String CHANNEL_ATM = "9998";
	// Κεντρικές Υπηρεσίες
	public static final String CHANNEL_CENTRAL_SERVICES = "9999";
	
	/*
	 * Προέλευση της εντολής πληρωμής, ως εξής:
	 * B:  Η πληρωμή παρελήφθη μέσω αρχείου.
	 * OB: Έχει γίνει online πληρωμή στην Τράπεζα Πληρωτή (έχει προηγηθεί ενημέρωση με SOAP API, εφόσον ο Οργανισμός συνδέεται με αυτό τον τρόπο).
	 * ON: Η πληρωμή παρελήφθη με online μήνυμα αλλά δεν έχει πιστωθεί ακόμη στον λογαριασμό του Οργανισμού. 
	 *     Η τιμή αυτή εμφανίζεται μόνο για αρχεία που παραλαμβάνονται μέσω Payment Gateway (download).
	 * OR: Η πληρωμή παρελήφθη με online μήνυμα (προηγούμενη τιμή ON).
	 * Στις περιπτώσεις B, OB και OR η πληρωμή έχει εκκαθαριστεί και θα πιστωθεί στο λογαριασμό του Οργανισμού εντός της ημέρας.
	 */
	// Η πληρωμή παρελήφθη μέσω αρχείου.
	public static final String SOURCE_B = "B";
	// Έχει γίνει online πληρωμή στην Τράπεζα Πληρωτή (έχει προηγηθεί ενημέρωση με SOAP API, εφόσον ο Οργανισμός συνδέεται με αυτό τον τρόπο).
	public static final String SOURCE_OB = "OB";
	// Η πληρωμή παρελήφθη με online μήνυμα αλλά δεν έχει πιστωθεί ακόμη στον λογαριασμό του Οργανισμού. 
	// Η τιμή αυτή εμφανίζεται μόνο για αρχεία που παραλαμβάνονται μέσω Payment Gateway (download).
	public static final String SOURCE_ON = "ON";
	// Η πληρωμή παρελήφθη με online μήνυμα (προηγούμενη τιμή ON).
	public static final String SOURCE_OR = "OR";
	
	public Payment() {
	}
	
	public Payment(String lobId, String actorId, BigDecimal paidAmount, Date bkDate, Date receivingDate,
			String status, String rid, String transactionId, String debtorBankBic, String debtorName,
			String service, String source) {
		
		this.lobId = lobId;
		this.actorId = actorId;
		this.paidAmount = paidAmount;
		this.bkDate = bkDate;
		this.receivingDate = receivingDate;
		this.status = status;
		this.rid = rid;
		this.transactionId = transactionId;
		this.debtorBankBic = debtorBankBic;
		this.debtorName = debtorName;
		this.service = service;
		this.source = source;
	}
	
	@Override
	public String toString() {
		
		SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd");
				
		StringBuilder builder = new StringBuilder();
		
		builder.append("{");
		builder.append("\"riMid\": \""); builder.append(riMid.replace("\"", "")); builder.append("\",");
		builder.append("\"paidAmount\": "); builder.append(paidAmount); builder.append(",");
		builder.append("\"bkDate\": \""); builder.append(dateFormat.format(bkDate)); builder.append("\",");
		builder.append("\"returnCode\": \""); builder.append(returnCode); builder.append("\",");
		builder.append("\"returnAmount\": "); builder.append(returnAmount);
		builder.append("}");
		
		return builder.toString();
	}
	
	public String toLine() {
		
		SimpleDateFormat dateFormat = new SimpleDateFormat("yyyy-MM-dd");
		SimpleDateFormat datetimeFormat = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		
		StringBuilder builder = new StringBuilder();
		
		builder.append(lobId); 														// 0
		builder.append(";");
		builder.append(actorId);													// 1
		builder.append(";");
		builder.append(riMid == null ? "" : riMid);									// 2
		builder.append(";");
		builder.append(paidAmount);													// 3
		builder.append(";");
		builder.append(dateFormat.format(bkDate));									// 4
		builder.append(";");
		builder.append(dateFormat.format(receivingDate));							// 5
		builder.append(";");
		builder.append(datetimeFormat.format(paymentDate));							// 6
		builder.append(";");
		builder.append(status);														// 7
		builder.append(";");
		builder.append(returnCode == null ? "" : returnCode);						// 8
		builder.append(";");
		builder.append(returnAmount == null ? "" : returnAmount);					// 9
		builder.append(";");
		builder.append(rid);														// 10
		builder.append(";");
		builder.append(diasReference == null ? "" : diasReference);					// 11
		builder.append(";");
		builder.append(transactionId);												// 12
		builder.append(";");
		builder.append(endToEndId == null ? "" : endToEndId);						// 13
		builder.append(";");
		builder.append(debtorBankBic);												// 14
		builder.append(";");
		builder.append(debtorBankCode == null ? "" : debtorBankCode);				// 15
		builder.append(";");
		builder.append(debtorName);													// 16
		builder.append(";");
		builder.append(service);													// 17
		builder.append(";");
		builder.append(channel == null ? "" : channel);								// 18
		builder.append(";");
		builder.append(source);														// 19
		builder.append(";");
		builder.append(remittanceInformation == null ? "" : remittanceInformation);	// 20
				
		return builder.toString();
	}
	
	public String toReturnLine() {

		StringBuilder builder = new StringBuilder();
		
		builder.append(lobId); 														// 0
		builder.append(";");
		builder.append(rid);														// 1
		builder.append(";");
		builder.append(diasReference == null ? "" : diasReference);					// 2
		builder.append(";");
		builder.append(transactionId);												// 3
		builder.append(";");
		builder.append(returnCode == null ? "" : returnCode);						// 4
		builder.append(";");
		builder.append(paidAmount);													// 5
		builder.append(";");
		builder.append(returnAmount == null ? "" : returnAmount);					// 6
				
		return builder.toString();
	}

	public String getLobId() {
		return lobId;
	}

	public void setLobId(String lobId) {
		this.lobId = lobId;
	}

	public String getActorId() {
		return actorId;
	}

	public void setActorId(String actorId) {
		this.actorId = actorId;
	}

	public String getRiMid() {
		return riMid;
	}

	public void setRiMid(String riMid) {
		this.riMid = riMid;
	}

	public BigDecimal getPaidAmount() {
		return paidAmount;
	}

	public void setPaidAmount(BigDecimal paidAmount) {
		this.paidAmount = paidAmount;
	}

	public Date getBkDate() {
		return bkDate;
	}

	public void setBkDate(Date bkDate) {
		this.bkDate = bkDate;
	}

	public Date getReceivingDate() {
		return receivingDate;
	}

	public void setReceivingDate(Date receivingDate) {
		this.receivingDate = receivingDate;
	}

	public Date getPaymentDate() {
		return paymentDate;
	}

	public void setPaymentDate(Date paymentDate) {
		this.paymentDate = paymentDate;
	}

	public String getStatus() {
		return status;
	}

	public void setStatus(String status) {
		this.status = status;
	}

	public String getReturnCode() {
		return returnCode;
	}

	public void setReturnCode(String returnCode) {
		this.returnCode = returnCode;
	}

	public BigDecimal getReturnAmount() {
		return returnAmount;
	}

	public void setReturnAmount(BigDecimal returnAmount) {
		this.returnAmount = returnAmount;
	}

	public String getRid() {
		return rid;
	}

	public void setRid(String rid) {
		this.rid = rid;
	}

	public String getDiasReference() {
		return diasReference;
	}

	public void setDiasReference(String diasReference) {
		this.diasReference = diasReference;
	}

	public String getTransactionId() {
		return transactionId;
	}

	public void setTransactionId(String transactionId) {
		this.transactionId = transactionId;
	}

	public String getEndToEndId() {
		return endToEndId;
	}

	public void setEndToEndId(String endToEndId) {
		this.endToEndId = endToEndId;
	}

	public String getDebtorBankBic() {
		return debtorBankBic;
	}

	public void setDebtorBankBic(String debtorBankBic) {
		this.debtorBankBic = debtorBankBic;
	}

	public String getDebtorBankCode() {
		return debtorBankCode;
	}

	public void setDebtorBankCode(String debtorBankCode) {
		this.debtorBankCode = debtorBankCode;
	}

	public String getDebtorName() {
		return debtorName;
	}

	public void setDebtorName(String debtorName) {
		this.debtorName = debtorName;
	}

	public String getService() {
		return service;
	}

	public void setService(String service) {
		this.service = service;
	}

	public String getChannel() {
		return channel;
	}

	public void setChannel(String channel) {
		this.channel = channel;
	}

	public String getSource() {
		return source;
	}

	public void setSource(String source) {
		this.source = source;
	}

	public String getRemittanceInformation() {
		return remittanceInformation;
	}

	public void setRemittanceInformation(String remittanceInformation) {
		this.remittanceInformation = remittanceInformation;
	}
}

