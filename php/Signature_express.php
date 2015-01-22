<?php

require_once 'Express.config.php';
session_start();

if (isset($_REQUEST["csrf"]) && $_REQUEST["csrf"] == $_SESSION["token"]) {
    $_SESSION = array();
    session_destroy();
    
    // Mandatory fields
    $amount      = $_REQUEST["amount"];
    
    /*The webpage of your site where your customer should be redirected to after the order is successful
     *In this example you can link it to success.php
     **/
    $returnURL   = "RETURN_URL_OF_YOUR_SITE";
    
    // Optional fields
    $currencyCode            = $_REQUEST["currencyCode"];
    $sellerNote              = $_REQUEST["sellerNote"];
    $sellerOrderId           = "YOUR_CUSTOM_ORDER_REFERENCE_ID";
    $shippingAddressRequired = "true";
    $paymentAction           = "AuthorizeAndCapture"; // other values None,Authorize
    
    // Getting the MerchantID/sellerID, MWS secret Key, MWS Access Key from the configuration file
    if ($merchantId == "") {
        throw new InvalidArgumentException("merchantId not set in the configuration file");
    }
    if ($accessKey == "") {
        throw new InvalidArgumentException("accessKey not set in the configuration file");
    }
    if ($secretKey == "") {
        throw new InvalidArgumentException("secretKey not set in the configuration file");
    }
    if ($lwaClientId == "") {
        throw new InvalidArgumentException("Login With Amazon ClientID is not set in the configuration file");
    }
    
    //Addding the parameters to the PHP data structure
    $parameters["accessKey"]               = $accessKey;
    $parameters["amount"]                  = $amount;
    $parameters["sellerId"]                = $merchantId;
    $parameters["returnURL"]               = $returnURL;
    $parameters["lwaClientId"]             = $lwaClientId;
    $parameters["sellerNote"]              = $sellerNote;
    $parameters["currencyCode"]            = $currencyCode;
    $parameters["shippingAddressRequired"] = $shippingAddressRequired;
    $parameters["paymentAction"]           = $paymentAction;
    
    uksort($parameters, 'strcmp');
    
    //call the function to sign the parameters and return the URL encoded signature
    $Signature = _urlencode(_signParameters($parameters, $secretKey));
    
    //add the signature to the parameters data structure
    $parameters["signature"] = $Signature;
    
    //echoing the parameters will be picked up by the ajax success function in the front end
    echo (json_encode($parameters));
}
    
else{
    throw new Exception("Unknown Entity");
}

function _signParameters(array $parameters, $key)
{
    $stringToSign = null;
    $algorithm    = "HmacSHA256";
    $stringToSign = _calculateStringToSignV2($parameters);
    
    return _sign($stringToSign, $key, $algorithm);
}

function _calculateStringToSignV2(array $parameters)
{
    $data = 'POST';
    $data .= "\n";
    $data .= "payments.amazon.com";
    $data .= "\n";
    $data .= "/";
    $data .= "\n";
    $data .= _getParametersAsString($parameters);
    return $data;
}

function _getParametersAsString(array $parameters)
{
    $queryParameters = array();
    foreach ($parameters as $key => $value) {
        $queryParameters[] = $key . '=' . _urlencode($value);
    }
    return implode('&', $queryParameters);
}

function _urlencode($value)
{
    return str_replace('%7E', '~', rawurlencode($value));
}

function _sign($data, $key, $algorithm)
{
    if ($algorithm === 'HmacSHA1') {
        $hash = 'sha1';
    } else if ($algorithm === 'HmacSHA256') {
        $hash = 'sha256';
    } else {
        throw new Exception("Non-supported signing method specified");
    }
    return base64_encode(hash_hmac($hash, $data, $key, true));
}
