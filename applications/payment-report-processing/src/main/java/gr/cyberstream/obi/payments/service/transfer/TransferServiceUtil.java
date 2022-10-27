package gr.cyberstream.obi.payments.service.transfer;

import java.text.SimpleDateFormat;
import java.util.Date;

public class TransferServiceUtil {
	
	private TransferServiceUtil() {
	}
	
	public static String getFileName(String orgId, Date date, String cycle, String lobId, String extension) {
		
		SimpleDateFormat dateformat = new SimpleDateFormat("yyyyMMdd");
		
		if (lobId != null)
			return "DPG" + orgId + dateformat.format(date) + cycle + "." + lobId + ".PNO." + extension;
		else
			return "DPG" + orgId + dateformat.format(date) + cycle + ".PNO." + extension;
	}
}
