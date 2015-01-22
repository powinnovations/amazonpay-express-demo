
import java.io.IOException;
import java.io.PrintWriter;
import java.security.InvalidKeyException;
import java.security.NoSuchAlgorithmException;
import java.util.HashMap;
import java.util.Map;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;
import javax.servlet.http.HttpSession;
import org.json.simple.JSONObject;

public class ExpressPaymentServlet extends HttpServlet {

    /**
     * Processes requests for both HTTP <code>GET</code> and <code>POST</code>
     * methods.
     *
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    protected void processRequest(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        response.setContentType("json");
        PrintWriter out = response.getWriter();
        String CSRFtoken = request.getParameter("CSRFtoken");
        HttpSession session = request.getSession(true);
        if (CSRFtoken.equals(session.getAttribute("CSRFtoken").toString())) {
            try {

                SignRequest json_params = new SignRequest();

                String amount = request.getParameter("amount");
                String sellerNote = request.getParameter("sellerNote");
                String currencyCode = request.getParameter("currencyCode");

                Map<String, String> Requestvars = new HashMap<String, String>();
                Requestvars.put("amount", amount);
                Requestvars.put("sellerNote", sellerNote);
                Requestvars.put("currencyCode", currencyCode);

                Map<String, String> prepareParameters = json_params.prepareParameters(Requestvars);
                out.println(new JSONObject(prepareParameters));

            } catch (NoSuchAlgorithmException ex) {
                Logger.getLogger(ExpressPaymentServlet.class.getName()).log(Level.SEVERE, null, ex);
            } catch (IllegalStateException ex) {
                Logger.getLogger(ExpressPaymentServlet.class.getName()).log(Level.SEVERE, null, ex);
            } catch (InvalidKeyException ex) {
                Logger.getLogger(ExpressPaymentServlet.class.getName()).log(Level.SEVERE, null, ex);
            } finally {

                out.close();
            }
        } else {
            try {
                throw new IllegalAccessException("unknown entity");
            } catch (IllegalAccessException ex) {
                Logger.getLogger(ExpressPaymentServlet.class.getName()).log(Level.SEVERE, null, ex);
            }
        }
    }

    // <editor-fold defaultstate="collapsed" desc="HttpServlet methods. Click on the + sign on the left to edit the code.">
    /**
     * Handles the HTTP <code>GET</code> method.
     *
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     * @throws java.io.UnsupportedEncodingException
     */
    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {

        processRequest(request, response);
    }

    /**
     * Handles the HTTP <code>POST</code> method.
     *
     * @param request servlet request
     * @param response servlet response
     * @throws ServletException if a servlet-specific error occurs
     * @throws IOException if an I/O error occurs
     */
    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {

        processRequest(request, response);
    }

    /**
     * Returns a short description of the servlet.
     *
     * @return a String containing servlet description
     */
    @Override
    public String getServletInfo() {
        return "Express Payments Demo Servlet";
    }// </editor-fold>

}
