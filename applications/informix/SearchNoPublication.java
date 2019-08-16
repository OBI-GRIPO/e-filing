
import java.io.IOException;
import javax.servlet.ServletConfig;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.sql.*;


@WebServlet("/SearchNoPublication")
public class SearchNoPublication extends HttpServlet {
	private static final long serialVersionUID = 1L;
	//private final String USER_AGENT = "Mozilla/5.0";
    public SearchNoPublication() {
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
		
		try {
			conn.commit();
		} catch (SQLException e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
		}
		
		
		String SQL    = request.getParameter("SQL");
		String vResponse  = "";
		String IDappli  = "";
		Date Dateappli = null;
		Integer Typeappli = null;
		Integer  CountIDappli = 0;
		try{
			Statement stmt=conn.createStatement();			
			ResultSet rs=stmt.executeQuery(SQL);
			while(rs.next()) CountIDappli++;
			IDappli = rs.getString(1);
			Dateappli = rs.getDate(2);
			Typeappli = rs.getInt(3);
			conn.close();
		
		}catch(Exception e){ 
			IDappli="null";		
			vResponse = "{\"ERROR\":202,\"ERROR_DESCRIPTION\":\"" +  e.getMessage() + "\"}";
			
			response.getWriter().append(vResponse);
			return;
		}	
		vResponse  = "{\"ERROR\":0,\"ERROR_DESCRIPTION\":\"\"," +
					   "\"CountIDappli\":" + CountIDappli +
					   ",\"Dateappli\":\"" + Dateappli + "\"" +					   
					   ",\"Typeappli\":\"" + Typeappli + "\"" +
					   ",\"IDappli\":\"" + IDappli + "\"}";
		response.getWriter().append(vResponse);	
	}
}





















