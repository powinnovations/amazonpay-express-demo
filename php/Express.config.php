<?php

$merchantId="YOUR_SELLER_ID"; // SellerID
$accessKey="YOUR_MWS_ACCESS_KEY"; // MWS Access Key
$secretKey="YOUR_MWS_SECRET_KEY"; // MWS Secret Key
$lwaClientId ="YOUR_LOGIN_WITH_AMAZON_CLIENT_ID"; // Login With Amazon Client ID

/* Add http:// or https:// before your Return URL
* The webpage of your site where the buyer should be redirected to after the payment is made
* In this example you can link it to the Result.php, which checks for the success or failure of the payment
* and routes it to the appropriate URL defined
*/
$returnURL   = "http://yourdomain.com/Result.php";

?>
