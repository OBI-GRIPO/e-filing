package gr.cyberstream.obi.payments.model;

public class PaymentsFile {

	// Κωδικοποιημένη δραστηριότητα.
	public static final String COL_LOB_ID = "LobID";
	
	// 5ψήφιος κωδικός Οργανισμού σε κωδικοποίηση της ΔΙΑΣ.
	public static final String COL_ACTOR_ID = "ActorID";
	
	// Κωδικός Πληρωμής.
	public static final String COL_RI_MID = "RI_MID";
	
	// Ποσό εντολής πληρωμής σε EUR.
	public static final String COL_PAID_AMOUNT = "PaidAmount";
	
	// Ημερομηνία πίστωσης. Δεν είναι συμπληρωμένη εάν το πεδίο Source έχει την τιμή ON.
	public static final String COL_BK_DATE = "BkDate";
	
	// Ημερομηνία παραλαβής πληρωμής.
	public static final String COL_RECEIVING_DATE = "ReceivingDate";
	 
	/*
	 * Ημερομηνία και ώρα λήψης της εντολής πληρωμής από την Τράπεζα Πληρωτή.
	 * Η ώρα περιέχεται μόνο εφόσον η πληρωμή έχει αποσταλεί από την Τράπεζα Πληρωτή με online μήνυμα, 
	 * οπότε και δύναται να περιέχει την πραγματική ημερομηνία και ώρα πληρωμής ή την εσωτερική λογιστική 
	 * ημερομηνία της Τράπεζας Εντολέα (με Ώρα 00:00:00). Στη δεύτερη περίπτωση, ως πραγματική ημερομηνία 
	 * και ώρα πληρωμής θεωρείται η τιμή του πεδίου creationDtTm (Παράρτημα Ε.1) και οι τιμές των πεδίων 
	 * Ημερομηνία Παραλαβής και Ώρα Παραλαβής (Παράρτημα Ε.2).
	 */
	public static final String COL_PAYMENT_DT_TM = "PaymentDtTm";
	
	/*
	 * Οι τιμές που ακολουθούν υποδηλώνουν ότι η πληρωμή έχει εκκαθαριστεί (εφόσον είναι συμπληρωμένη η ημερομηνία πίστωσης, BkDate) 
	 * και θα πιστωθεί στο λογαριασμό του Οργανισμού εντός της ημέρας:
	 * 0: Επιτυχής
	 * 1: Επιτυχής√
	 * Οι τιμές που ακολουθούν εμφανίζονται μόνο σε αρχεία που παραλαμβάνονται μέσω Payment Gateway (download):
	 * 2: Απορριφθείσα
	 * 3: Απορριφθείσα – Μη επιβεβαιωμένη.
	 */
	public static final String COL_STATUS = "Status";
	
	// Μοναδικός κωδικός αναφοράς, ο οποίος δημιουργείται από την υπηρεσία. Αποτελεί τον μοναδικό κωδικό για την αποστολή επιστροφής 
	// ή για τη συμφωνία με τα εισερχόμενα online μηνύματα.
	public static final String COL_RID = "RID";
	
	// Μοναδικός κωδικός αναφοράς του Συστήματος Πληρωμών ΔΙΑΣ.
	public static final String COL_DIAS_REFERENCE = "DIASReference";
	
	// Μοναδικός κωδικός αναφοράς της Τράπεζας Πληρωτή.
	public static final String COL_TRANSACTION_ID = "TransactionID";
	
	// Κωδικός αναφοράς πληρωτή.
	public static final String COL_END_TO_END_ID = "EndToEndID";
	
	// Κωδικός της Τράπεζας Πληρωτή σε μορφή BIC8 ή BIC11.
	public static final String COL_DEBTOR_BACK_BIC = "DebtorBankBIC";
	
	// 3ψήφιος κωδικός της Τράπεζας Πληρωτή σύμφωνα με την κωδικοποίηση της ΔΙΑΣ.
	public static final String COL_DEBTOR_BANK_CODE = "DebtorBankCode";
	
	// Ονοματεπώνυμο ή Επωνυμία Πληρωτή.
	public static final String COL_DEBTOR_NAME = "DebtorName";
	
	// Συμπληρώνεται με την τιμή DCT.
	public static final String COL_SERVICE = "Service";
	
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
	public static final String COL_CHANNEL = "Channel";
	
	/*
	 * Προέλευση της εντολής πληρωμής, ως εξής:
	 * B:  Η πληρωμή παρελήφθη μέσω αρχείου.
	 * OB: Έχει γίνει online πληρωμή στην Τράπεζα Πληρωτή (έχει προηγηθεί ενημέρωση με SOAP API, εφόσον ο Οργανισμός συνδέεται με αυτό τον τρόπο).
	 * ON: Η πληρωμή παρελήφθη με online μήνυμα αλλά δεν έχει πιστωθεί ακόμη στον λογαριασμό του Οργανισμού. 
	 *     Η τιμή αυτή εμφανίζεται μόνο για αρχεία που παραλαμβάνονται μέσω Payment Gateway (download).
	 * OR: Η πληρωμή παρελήφθη με online μήνυμα (προηγούμενη τιμή ON).
	 * Στις περιπτώσεις B, OB και OR η πληρωμή έχει εκκαθαριστεί και θα πιστωθεί στο λογαριασμό του Οργανισμού εντός της ημέρας.
	 */
	public static final String COL_SOURCE = "Source";
	
	/*
	 * Περιέχει:
	 * - στα 25 πρώτα ψηφία τον Κωδικό Πληρωμής και στα υπόλοιπα, τα στοιχεία όπως μεταφέρθηκαν στη ΔΙΑΣ από την Τράπεζα Πληρωτή ή
	 * - μόνο τα πληροφοριακά στοιχεία όπως μεταφέρθηκαν στη ΔΙΑΣ από την Τράπεζα Πληρωτή, εάν δεν έχει αναγνωρισθεί Κωδικός Πληρωμής
	 *   (Παράρτημα Α, Παράμετρος 5).
	 */
	public static final String COL_REMITTANCE_INFORMATION = "RemittanceInformation";
		
	// Κωδικός αιτιολογίας επιστροφής
	public static final String REJECTION_COL_RETURN_CODE = "ReturnCode";
	
	// Επιστρεφόμενο ποσό. Είναι ίσο με το αρχικό ποσό της εντολής πληρωμής.
	public static final String REJECTION_COL_RETURN_AMOUNT = "ReturnAmount";
	
	private PaymentsFile() {
	}
}
