###`PHP` - Pay with Amazon - Express Demo
####Setup Procedure
* Add the PHP files to your server directory.
* Add Your `Seller ID`, `MWS Accesskey`, `MWS secret Key`, `Login with Amazon Client ID` to the [`Express.config.php`](https://github.com/amzn/pay-with-amazon-express-demo/tree/master/php/Express.config.php)
* Add your `Seller ID` in the place of `YOUR_SELLER_ID_HERE` in [`ExpressPayment.php`](https://github.com/amzn/pay-with-amazon-express-demo/blob/master/php/ExpressPayment.php)
* Add the value for `$returnURL` in the [`ExpressSignature.php`](https://github.com/amzn/pay-with-amazon-express-demo/tree/master/php/ExpressSignature.php)
* Demo implementation link `returnURL` has been pre-filled to link to [`Result.php`](https://github.com/amzn/pay-with-amazon-express-demo/tree/master/php/Result.php) which parses the URL to check for order success or failure.
* Add the URL locations to the pages on your server for Success, Buyer Abandoned and Failure in [`Result.php`](https://github.com/amzn/pay-with-amazon-express-demo/tree/master/php/Result.php)
* You may also add or change the values for the optional parameters in the [`ExpressSignature.php`](https://github.com/amzn/pay-with-amazon-express-demo/tree/master/php/ExpressSignature.php)
* Start your PHP server and navigate to the [`ExpressPayment.php`](https://github.com/amzn/pay-with-amazon-express-demo/tree/master/php/ExpressPayment.php)
* Use your Sandbox test account credentials to login.
