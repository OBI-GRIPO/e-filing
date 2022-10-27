package gr.cyberstream.obi.payments.service.transfer;

import java.io.ByteArrayInputStream;
import java.io.InputStream;
import java.util.Date;

import com.jcraft.jsch.ChannelSftp;
import com.jcraft.jsch.JSch;
import com.jcraft.jsch.JSchException;
import com.jcraft.jsch.Session;
import com.jcraft.jsch.SftpException;

import gr.cyberstream.obi.payments.exceptions.ReportNotFoundException;
import gr.cyberstream.obi.payments.exceptions.TransferServiceConnectException;

public class SFTPTransferService implements TransferService {
	
	private String host;
	private int port;
	private String username;
	private String password;
	
	private String knownHosts;
	
	private ChannelSftp channel;

	public SFTPTransferService(String host, int port, String username, String password, String knownHosts) {
		
		this.host = host;
		this.port = port;
		this.username = username;
		this.password = password;
		this.knownHosts = knownHosts;
	}
	
	@Override
	public void connect() throws TransferServiceConnectException {
		
		channel = null;
		
		try {
			
			channel = setupSFTP();
			
			channel.connect();
			
		} catch (JSchException e) {
			
			throw new TransferServiceConnectException("Unable to setup SFTP Server [" + host + ", " + port + ", " + username + "]. " + e.getMessage());
			
		}
	}
	
	@Override
	public InputStream readReport(String orgId, Date date, String cycle, String lobId, String extension) throws TransferServiceConnectException, ReportNotFoundException {
		
		if (channel == null)
			throw new TransferServiceConnectException("Transfer Service connection is not initialized.");
		
		InputStream reportInputStream = null;
		
		String filename = TransferServiceUtil.getFileName(orgId, date, cycle, lobId, extension);
		
		try {
			
			if (!channel.pwd().endsWith("IN")) {
				
				channel.cd("IN");
			}
			
			reportInputStream = channel.get(filename);
						
		} catch (SftpException e) {
			
			if (e.id == 2) {
				
				throw new ReportNotFoundException("File /IN/" + filename + " not found. " + e.getMessage());
				
			} else {
				
				throw new TransferServiceConnectException("Unable to get file /IN/" + filename + ". " + e.getMessage());
			}
		}
	    
	    return reportInputStream;
	}
	
	@Override
	public void close() {
		
		channel.exit();
	}
	
	private ChannelSftp setupSFTP() throws JSchException {
		
		JSch jsch = new JSch();
		
		jsch.setKnownHosts(new ByteArrayInputStream(knownHosts.getBytes()));
				
		Session jschSession = jsch.getSession(username, host);
		
		jschSession.setPort(port);
		jschSession.setPassword(password);
		
		jschSession.connect();
		
	    return (ChannelSftp) jschSession.openChannel("sftp");
	}

}
