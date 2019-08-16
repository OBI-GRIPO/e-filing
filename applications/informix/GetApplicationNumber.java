
import java.io.IOException;
import javax.servlet.ServletConfig;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.sql.*;


@WebServlet("/GetApplicationNumber")
public class GetApplicationNumber extends HttpServlet {
	private static final long serialVersionUID = 1L;
	//private final String USER_AGENT = "Mozilla/5.0";
    public GetApplicationNumber() {
        super();
    }

	public void init(ServletConfig config) throws ServletException {
		super.init(config);
	}

	
	protected void doGet(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {

	    response.getWriter().append("{\"ERROR\":201,\"ERROR_DESCRIPTION\":\"Get method not available!\"}");
	}
	
	
	// ---------------------------------------------------------------------------
	
	protected void doPost(HttpServletRequest request, HttpServletResponse response) throws ServletException, IOException {
		try{
		    Class.forName("com.informix.jdbc.IfxDriver");
	    }catch (Exception e){
		    response.getWriter().append("{\"ERROR\":101,\"ERROR_DESCRIPTION\":\"failed to load Informix JDBC driver\"}");
		    return;
	    }
		String ConnectionString = request.getParameter("ConnectionString");
		Connection conn = null;
		try{
			conn = DriverManager.getConnection(ConnectionString);
	    }catch (SQLException e){
		    response.getWriter().append("{\"ERROR\":102,\"ERROR_DESCRIPTION\":\"" +  e.getMessage() + "\"}");
		    return;
	    }
		String SQL    = request.getParameter("SQL");
		String vResponse  = "";
		
		Integer  CountIDappli = 0;
		String extidappli  = "";
		Date  dtappli = null;
		String extidrecept  = "";
		Date  dtrecept = null;
		String rfappli  = "";
		
		try{
			Statement stmt=conn.createStatement();			
			ResultSet rs=stmt.executeQuery(SQL);
			while(rs.next()) CountIDappli++;
			extidappli = rs.getString(1);
			dtappli = rs.getDate(2);	
			extidrecept = rs.getString(3);
			dtrecept = rs.getDate(4);
			rfappli = rs.getString(5);
			conn.close();
		
		}catch(Exception e){  }	
		vResponse  = "{\"ERROR\":0,\"ERROR_DESCRIPTION\":\"\"," +
					   "\"CountIDappli\":" + CountIDappli +
					   ",\"RFappli\":\"" + rfappli + "\"" +  		 // 
					   ",\"ExtIDappli\":\"" + extidappli + "\"" +    // Αριθμός Κατάθεσης
					   ",\"DTappli\":\"" + dtappli + "\"" +	         // Ημερομηνία Κατάθεσης
					   ",\"ExtIDrecept\":\"" + extidrecept + "\"" +  // Αριθμός Παραλαβής
					   ",\"DTrecept\":\"" + dtrecept + "\"}";        // Ημερομηνία Παραλαβής
		response.getWriter().append(vResponse);	
	}
}


















