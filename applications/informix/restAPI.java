
import java.io.IOException;
import javax.servlet.ServletConfig;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.sql.*;


@WebServlet("/restAPI")
public class restAPI extends HttpServlet {
	private static final long serialVersionUID = 1L;
	//private final String USER_AGENT = "Mozilla/5.0";
    public restAPI() {
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
		String idappli  = "";
		Integer  CountIDappli = 0;
		try{
			Statement stmt=conn.createStatement();			
			ResultSet rs=stmt.executeQuery(SQL);
			while(rs.next()) CountIDappli++;
			idappli = rs.getString(1);
			conn.close();
		
		}catch(Exception e){ idappli="null";}	
		vResponse  = "{\"ERROR\":0,\"ERROR_DESCRIPTION\":\"\"," +
					   "\"CountIDappli\":" + CountIDappli + 
					   ",\"idappli\":\"" + idappli + "\"}";
		response.getWriter().append(vResponse);	
	}
}





















