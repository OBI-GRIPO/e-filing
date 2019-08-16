
import java.io.IOException;
import javax.servlet.ServletConfig;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import java.sql.*;


@WebServlet("/UpdateCS")
public class UpdateCS extends HttpServlet {
	private static final long serialVersionUID = 1L;
	//private final String USER_AGENT = "Mozilla/5.0";
    public UpdateCS() {
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

		
		PreparedStatement pstmt = null;
		int numUpd = 0;
	
		try {
			pstmt = conn.prepareStatement(SQL);
		} catch (SQLException e) {
			// TODO Auto-generated catch block
		    response.getWriter().append("{\"ERROR\":102,\"ERROR_DESCRIPTION\":\"" +  e.getMessage() + "\"}");
			//e1.printStackTrace();
		}

		try {
			numUpd = pstmt.executeUpdate();
			conn.commit();
			pstmt.close();
		} catch (SQLException e) {
			// TODO Auto-generated catch block
		    response.getWriter().append("{\"ERROR\":102,\"ERROR_DESCRIPTION\":\"" +  e.getMessage() + "\"}");
			//e1.printStackTrace();
		}

		
		vResponse  = "{\"ERROR\":0,\"ERROR_DESCRIPTION\":\"\",\"NumUpd\":" + numUpd + "}";
		response.getWriter().append(vResponse);
	}
}





















