<html>
   <body>
        <br />
        <p>Your transaction was successful. Following are the Parameters returned:</p>
        <br />
    </body>
    <?php
    echo ("<pre>");
    print_r($_GET);
    echo ("</pre>");

    /* begin signature validation */
    require_once 'Express.config.php';

    $signatureReturned = $_GET['signature'];
    $parameters = $_GET;
    unset($parameters['signature']);

    if(isset($parameters['sellerOrderId'])) {
        $parameters['sellerOrderId'] = rawurlencode($parameters['sellerOrderId']);
    }
    uksort($parameters, 'strcmp');

    $parseUrl = parse_url($returnURL);
    $stringToSign = "GET\n" . $parseUrl['host'] . "\n" . $parseUrl['path'] . "\n";

    foreach ($parameters as $key => $value) {
        $queryParameters[] = $key . '=' . str_replace('%7E', '~', rawurlencode($value));
    }
    $stringToSign .= implode('&', $queryParameters);

    $signatureCalculated = base64_encode(hash_hmac("sha256", $stringToSign, $secretKey, true));
    $signatureCalculated = str_replace('%7E', '~', rawurlencode($signatureCalculated));

    if ($signatureReturned == $signatureCalculated) {
        echo "Signature was successfully validated.";
    } else {
        echo "Signature does not match.";
    }
    ?>
<html>
