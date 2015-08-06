###`Java` - Pay with Amazon - Express Demo
####Setup Procedure
* In your IDE open the project `ExpressPaymentsDemo` and start your Tomcat server
* Add Your `Seller ID`, `MWS Accesskey`, `MWS secret Key`, `Login with Amazon Client ID` to the [`Express.config.properties`](https://github.com/amzn/pay-with-amazon-express-demo/blob/master/java/ExpressPaymentsDemo/src/java/Express.config.properties)
* Add your `Seller ID` in the place of `YOUR_SELLER_ID_HERE` in [`ExpressPayment.jsp`](https://github.com/amzn/pay-with-amazon-express-demo/blob/master/java/ExpressPaymentsDemo/web/ExpressPayment.jsp)
* Add the value for `returnURL` in the [`SignRequest.java`](https://github.com/amzn/pay-with-amazon-express-demo/blob/master/java/ExpressPaymentsDemo/src/java/SignRequest.java)
* Demo implementation link `returnURL` has been pre-filled to link to [`Result.jsp`](https://github.com/amzn/pay-with-amazon-express-demo/blob/master/java/ExpressPaymentsDemo/web/Result.jsp) which parses the URL to check for order success or failure.
* Add the URL locations to the pages on your server for Success, Buyer Abandoned and Failure in [`Result.jsp`](https://github.com/amzn/pay-with-amazon-express-demo/blob/master/java/ExpressPaymentsDemo/web/Result.jsp)
* You may also add or change the values for the optional parameters in the [`SignRequest.java`](https://github.com/amzn/pay-with-amazon-express-demo/blob/master/java/ExpressPaymentsDemo/src/java/SignRequest.java)
* Add the jars to your project [`json-simple-1.1.1.jar`](https://github.com/amzn/pay-with-amazon-express-demo/tree/master/java/ExpressPaymentsDemo/build/web/WEB-INF/lib) and the [`commons-codec-1.10.jar`](https://github.com/amzn/pay-with-amazon-express-demo/tree/master/java/ExpressPaymentsDemo/build/web/WEB-INF/lib)
* Build and run the project.
* Use your Sandbox test account credentials to login.