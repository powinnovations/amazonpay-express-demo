<%@page import="java.util.*" %>
<%@page contentType="text/html" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Order Success Page</title>
    </head>
    <body>
        <%

           // you can get an enumeratable list 
            // of parameter keys by using request.getParameterNames() 
            Enumeration en = request.getParameterNames();
            out.println("The Transaction was Successful! Following are the parameters returned" + "<br/><br/>");
           // enumerate through the keys and extract the values 
            // from the keys! 
            while (en.hasMoreElements()) {
                String parameterName = (String) en.nextElement();
                String parameterValue = request.getParameter(parameterName);
                out.println(parameterName + "     :       " + parameterValue + "<br />");
            }

        %>
    </body>
</html>
