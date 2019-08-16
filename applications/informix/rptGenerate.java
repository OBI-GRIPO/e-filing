import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.StringReader;
import java.io.Writer;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.HashMap;
import java.util.Map;
import javax.servlet.ServletConfig;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import freemarker.template.Configuration;
import freemarker.template.Template;
import freemarker.template.TemplateException;
import freemarker.template.TemplateExceptionHandler;

@WebServlet("/rptGenerate")
public class rptGenerate extends HttpServlet {
	private static final long serialVersionUID = 1L;
	Configuration cfg;
	private final String USER_AGENT = "Mozilla/5.0"; 

    public rptGenerate() {
        super();
        // TODO Auto-generated constructor stub
    }

	public void init(ServletConfig config) throws ServletException {
		super.init(config);
		cfg = new Configuration(Configuration.VERSION_2_3_27);
		cfg.setDefaultEncoding("UTF-8");
		cfg.setTemplateExceptionHandler(TemplateExceptionHandler.RETHROW_HANDLER);
		cfg.setLogTemplateExceptions(false);
		cfg.setWrapUncheckedExceptions(true);
	}

	private String GetTemplate(String RequestID, String Render) throws Exception {
		String RestURL = "http://localhost/template.php?RID=" + RequestID + "&Render=" + Render;
		URL obj = new URL(RestURL);
		HttpURLConnection con = (HttpURLConnection) obj.openConnection();
		con.setRequestMethod("GET");
		con.setRequestProperty("User-Agent", USER_AGENT);
		BufferedReader in = new BufferedReader(
		        new InputStreamReader(con.getInputStream()));
		String inputLine;
		String sOut = "";
		while ((inputLine = in.readLine()) != null) {
			sOut += inputLine;
		}
		in.close();
		return sOut;
	}
	
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		response.getWriter().append("Error 100");
	}
	
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		String RequestID = request.getParameter("RequestID");
		String Render    = request.getParameter("Render");
		String templateStr = "";
		try {
			templateStr = GetTemplate(RequestID,Render);
		} catch (Exception e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}
		Template t = new Template("name", new StringReader(templateStr), cfg);
		Map<String,Object> root = new HashMap<>();
		root.put("dummy", "");
		Writer out = response.getWriter();
		try {
			t.process(root, out);
		} catch (TemplateException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}		
	}
}
