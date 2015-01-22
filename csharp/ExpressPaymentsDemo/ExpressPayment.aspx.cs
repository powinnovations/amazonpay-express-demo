using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Reflection;
using System.Net;
using System.Text;
using System.IO;
using System.Text.RegularExpressions;
using System.Security.Cryptography;
using System.Globalization;
using System.Xml.Serialization;
using System.Configuration;
using System.Web.Script.Services;
using System.Data;
using System.Data.SqlClient;
using System.Xml;
using System.Web.Services;
using System.Web.Script.Serialization;
using System.Web.Configuration;


public partial class ExpressPayment : System.Web.UI.Page
{

    protected void Page_Load(object sender, EventArgs e)
    { }

    /* view session state to prevent cross-site request forgery
	 *supporting doc :http://msdn.microsoft.com/en-us/library/ms972969.aspx#securitybarriers_topic6 */
    protected override void OnInit(EventArgs e)
    {
        ViewStateUserKey = Session.SessionID;
        base.OnInit(e);
    }

    [System.Web.Services.WebMethod]
    public static string AddRequiredParameters(string Amount, string CurrencyCode, string SellerNote)
    {
        // Mandatory fields
        string sellerId = WebConfigurationManager.AppSettings["sellerId"];
        string accessKey = WebConfigurationManager.AppSettings["accessKey"];
        string secretKey = WebConfigurationManager.AppSettings["secretKey"];
        string lwaClientId = WebConfigurationManager.AppSettings["lwaClientId"];

        if (String.IsNullOrEmpty(sellerId))
            throw new ArgumentNullException("sellerId", "sellerId is NULL, set the value in the configuration file ");
        if (String.IsNullOrEmpty(accessKey))
            throw new ArgumentNullException("accessKey", "accessKey is NULL, set the value in the configuration file ");
        if (String.IsNullOrEmpty(secretKey))
            throw new ArgumentNullException("secretKey", "secretKey is NULL, set the value in the configuration file ");
        if (String.IsNullOrEmpty(lwaClientId))
            throw new ArgumentNullException("lwaClientId", "lwaClientId is NULL, set the value in the configuration file ");

        string amount = Amount;
		/* Add http:// or https:// before your Return URL 
		 *The webpage of your site where your customer should be redirected to after the order is successful
		 *In this example you can link it to the success.aspx to see the GET parameters*/
        string returnURL = "RETURN_URL_OF_YOUR_SITE";

        // Optional fields
        string currencyCode = CurrencyCode;
        string sellerNote = SellerNote;
        string sellerOrderId = "YOUR_CUSTOM_ORDER_REFERENCE_ID";
        string shippingAddressRequired = "true";
        string paymentAction = "AuthorizeAndCapture";

        IDictionary<String, String> parameters = new Dictionary<String, String>();
        parameters.Add("accessKey", accessKey);
        parameters.Add("sellerId", sellerId);
        parameters.Add("amount", amount);
        parameters.Add("returnURL", returnURL);
        parameters.Add("lwaClientId", lwaClientId);
        parameters.Add("sellerNote", sellerNote);
        parameters.Add("currencyCode", currencyCode);
        parameters.Add("shippingAddressRequired", "true");
        parameters.Add("paymentAction", paymentAction);

        string Signature = SignParameters(parameters, secretKey);

        IDictionary<String, String> SortedParameters =
                  new SortedDictionary<String, String>(parameters, StringComparer.Ordinal);
        SortedParameters.Add("signature", UrlEncode(Signature, false));

        var jsonSerializer = new System.Web.Script.Serialization.JavaScriptSerializer();
        return (jsonSerializer.Serialize(SortedParameters));

    }


    /**
      * Convert Dictionary of parameters to URL encoded query string
      */
    private static string GetParametersAsString(IDictionary<String, String> parameters)
    {
        StringBuilder data = new StringBuilder();
        foreach (String key in (IEnumerable<String>)parameters.Keys)
        {
            String value = parameters[key];
            if (value != null)
            {
                data.Append(key);
                data.Append('=');
                data.Append(UrlEncode(value, false));
                data.Append('&');
            }
        }
        String result = data.ToString();
        return result.Remove(result.Length - 1);
    }

    /**
     * Computes RFC 2104-compliant HMAC signature for request parameters
     * Implements AWS Signature, as per following spec:
     *
     * If Signature Version is 2, string to sign is based on following:
     *
     *    1. The HTTP Request Method followed by an ASCII newline (%0A)
     *    2. The HTTP Host header in the form of lowercase host, followed by an ASCII newline.
     *    3. The URL encoded HTTP absolute path component of the URI
     *       (up to but not including the query string parameters);
     *       if this is empty use a forward '/'. This parameter is followed by an ASCII newline.
     *    4. The concatenation of all query string components (names and values)
     *       as UTF-8 characters which are URL encoded as per RFC 3986
     *       (hex characters MUST be uppercase), sorted using lexicographic byte ordering.
     *       Parameter names are separated from their values by the '=' character
     *       (ASCII character 61), even if the value is empty.
     *       Pairs of parameter and values are separated by the '&' character (ASCII code 38).
     *
     */
    private static String SignParameters(IDictionary<String, String> parameters, String key)
    {
        String signatureVersion = "2";

        KeyedHashAlgorithm algorithm = new HMACSHA256();

        String stringToSign = null;
        if ("2".Equals(signatureVersion))
        {
            String signatureMethod = "HmacSHA256";
            algorithm = KeyedHashAlgorithm.Create(signatureMethod.ToUpper());
            stringToSign = CalculateStringToSignV2(parameters);
        }
        else
        {
            throw new Exception("Invalid Signature Version specified");
        }

        return Sign(stringToSign, key, algorithm);
    }



    private static String CalculateStringToSignV2(IDictionary<String, String> parameters)
    {
        StringBuilder data = new StringBuilder();
        IDictionary<String, String> sorted =
              new SortedDictionary<String, String>(parameters, StringComparer.Ordinal);
        data.Append("POST");
        data.Append("\n");
        data.Append("payments.amazon.com");
        data.Append("\n");
        data.Append("/");
        data.Append("\n");
        foreach (KeyValuePair<String, String> pair in sorted)
        {
            if (pair.Value != null)
            {
                data.Append(UrlEncode(pair.Key, false));
                data.Append("=");
                data.Append(UrlEncode(pair.Value, false));
                data.Append("&");
            }

        }

        String result = data.ToString();
        return result.Remove(result.Length - 1);
    }


    private static String UrlEncode(String data, bool path)
    {
        StringBuilder encoded = new StringBuilder();
        String unreservedChars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_.~" + (path ? "/" : "");

        foreach (char symbol in System.Text.Encoding.UTF8.GetBytes(data))
        {
            if (unreservedChars.IndexOf(symbol) != -1)
            {
                encoded.Append(symbol);
            }
            else
            {
                encoded.Append("%" + String.Format("{0:X2}", (int)symbol));
            }
        }

        return encoded.ToString();

    }

    /**
     * Computes RFC 2104-compliant HMAC signature.
     */
    private static String Sign(String data, String key, KeyedHashAlgorithm algorithm)
    {
        Encoding encoding = new UTF8Encoding();
        algorithm.Key = encoding.GetBytes(key);
        return Convert.ToBase64String(algorithm.ComputeHash(
            encoding.GetBytes(data.ToCharArray())));
    }

}