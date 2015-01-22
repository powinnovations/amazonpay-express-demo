using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

public partial class success : System.Web.UI.Page
{
    protected void Page_Load(object sender, EventArgs e)
    {
        Response.Write("The Transaction was Successful! Following are the parameters returned" + "<br/><br/>");
        foreach (string key in Request.QueryString)
        {
            Response.Write(key + " =      " + Request.QueryString[key] + "<br/>");
        }

    }
}