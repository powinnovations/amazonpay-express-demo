
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStream;
import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.security.InvalidKeyException;
import java.security.NoSuchAlgorithmException;
import java.security.SignatureException;
import org.apache.commons.codec.binary.Base64;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;
import java.util.Properties;
import java.util.TreeMap;
import java.util.logging.Level;
import java.util.logging.Logger;
import javax.crypto.Mac;
import javax.crypto.spec.SecretKeySpec;

public class SignRequest {

    private static String secretKey = null;
    private static String accessKey = null;
    private static String lwaClientId = null;
    private static String sellerId = null;

    // Constant value used to generate signature according to
    // AWS signature version 2 standard
    // http://docs.aws.amazon.com/general/latest/gr/signature-version-2.html
    private final static String CHARACTER_ENCODING = "UTF-8";
    private final static String ALGORITHM = "HmacSHA256";
    private final static String SERVICE_URL = "payments.amazon.com";
    private final static String REQUEST_URI = "/";
    private final static String HTTP_VERB = "POST";

    private void getproperties() throws IOException {
        Properties properties = new Properties();
        String propFileName = "Express.config.properties";
        InputStream inputStream = getClass().getClassLoader().getResourceAsStream(propFileName);

        if (inputStream != null) {
            properties.load(inputStream);
        } else {
            throw new FileNotFoundException("property file '" + propFileName + "' not found in the classpath");
        }
        /* Mandatory Fields - getting the values for the below from the properties file*/
        secretKey = properties.getProperty("secretKey").trim();
        accessKey = properties.getProperty("accessKey").trim();
        lwaClientId = properties.getProperty("lwaClientId").trim();
        sellerId = properties.getProperty("sellerId").trim();

        if (secretKey.isEmpty()) {
            throw new NullPointerException("secretKey value is empty,Enter the appropriate value in the Properties file");
        }
        if (accessKey.isEmpty()) {
            throw new NullPointerException("accessKey value is empty,Enter the appropriate value in the Properties file");
        }
        if (lwaClientId.isEmpty()) {
            throw new NullPointerException("lwaClientId value is empty,Enter the appropriate value in the Properties file");
        }
        if (sellerId.isEmpty()) {
            throw new NullPointerException("sellerId value is empty, Enter the appropriate value in the Properties file ");
        }

    }

    public Map<String, String> prepareParameters(Map<String, String> Requestvars) throws NoSuchAlgorithmException, IllegalStateException, UnsupportedEncodingException, InvalidKeyException, IOException {
        getproperties();
        // Mandatory fields
        String amount = Requestvars.get("amount");

        /* Add http:// or https:// before your Return URL
         *The webpage of your site where your customer should be redirected to after the order is successful
         *In this example you can link it to the success.java to see the GET parameters*/
        String returnURL = "http://localhost:8080/ExpressPaymentsDemo/Success.jsp";

        // Optional fields
        String currencyCode = Requestvars.get("currencyCode");
        String sellerNote = Requestvars.get("sellerNote");
        String sellerOrderId = "YOUR_CUSTOM_ORDER_ID";
        String shippingAddressRequired = "true";
        String paymentAction = "AuthorizeAndCapture";//other values: None,Authorize

        Map<String, String> parameters = new HashMap<String, String>();

        // Put mandatory field to parameter map
        parameters.put("sellerId", sellerId);
        parameters.put("accessKey", accessKey);
        parameters.put("amount", amount);
        parameters.put("returnURL", returnURL);
        parameters.put("lwaClientId", lwaClientId);

        // Put optional fields if there is any
        parameters.put("currencyCode", currencyCode);
        parameters.put("sellerNote", sellerNote);
        parameters.put("shippingAddressRequired", shippingAddressRequired);
        parameters.put("paymentAction", paymentAction);

        String formattedParameters;
        try {
            formattedParameters = calculateStringToSignV2(parameters);
            String signature = sign(formattedParameters, secretKey, ALGORITHM);
            parameters.put("signature", urlEncode(signature));
        } catch (SignatureException ex) {
            Logger.getLogger(SignRequest.class.getName()).log(Level.SEVERE, null, ex);
        }

        return (parameters);
    }

    /*
     * String to sign is based on following:
     *
     * 1. The HTTP Request Method followed by an ASCII newline ("POST" in our case)
     *
     * 2. The HTTP Host header in the form of lowercase host, followed by an
     * ASCII newline. ("payments.amazon.com" in our case)
     *
     * 3. The URL encoded HTTP absolute path component of the URI ("/" in our case)
     *
     * 4. The concatenation of all query string components (names and values) as
     * UTF-8 characters which are URL encoded as per RFC 3986 (hex characters
     * MUST be uppercase), sorted using lexicographic byte ordering. Parameter
     * names are separated from their values by the '=' character (ASCII
     * character 61), even if the value is empty. Pairs of parameter and values
     * are separated by the '&' character (ASCII code 38).
     */
    private String calculateStringToSignV2(Map<String, String> parameters)
            throws SignatureException {
        // Create flattened (String) representation
        StringBuilder data = new StringBuilder();
        data.append(HTTP_VERB + "\n");
        data.append(SERVICE_URL).append("\n");
        data.append(REQUEST_URI).append("\n");

        Map<String, String> sorted = new TreeMap<String, String>();
        sorted.putAll(parameters);
        Iterator<Map.Entry<String, String>> pairs = sorted.entrySet().iterator();
        while (pairs.hasNext()) {
            Map.Entry<String, String> pair = pairs.next();
            String key = pair.getKey();
            data.append(urlEncode(key));
            data.append("=");
            String value = pair.getValue();
            data.append(urlEncode(value));
            if (pairs.hasNext()) {
                data.append("&");
            }
        }
        return data.toString();
    }
    /*
     * Sign the text with the given secret key and convert to base64
     */

    private String sign(String data, String key, String algorithm) throws SignatureException {
        byte[] signature;
        try {
            Mac mac = Mac.getInstance(algorithm);
            mac.init(new SecretKeySpec(key.getBytes(), algorithm));
            signature = Base64.encodeBase64(mac.doFinal(data.getBytes(CHARACTER_ENCODING)));
        } catch (Exception e) {
            throw new SignatureException("Failed to generate signature: " + e.getMessage(), e);
        }

        return new String(signature);
    }

    private String urlEncode(String rawValue) {
        String value = (rawValue == null) ? "" : rawValue;
        String encoded = null;

        try {
            encoded = URLEncoder.encode(value, CHARACTER_ENCODING)
                    .replace("+", "%20").replace("*", "%2A")
                    .replace("%7E", "~");
        } catch (UnsupportedEncodingException e) {
            System.err.println("Unknown encoding: " + CHARACTER_ENCODING);
        }
        return encoded;
    }
}
