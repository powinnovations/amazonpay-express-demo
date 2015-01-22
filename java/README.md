###`Java` - Pay with Amazon - Custom Integration Express Demo. 
####Setup Procedure
* In your IDE open the project `ExpressPaymentsDemo` and start your Tomcat server
* Add Your `Seller ID`, `MWS Accesskey`, `MWS secret Key`, `Login with Amazon Client ID` to the [`Express.config.properties`](https://github.com/amzn/pay-with-amazon-express-demo/blob/master/java/ExpressPaymentsDemo/src/java/Express.config.properties)
* Add the value for `returnURL` in the [`SignRequest.java`](https://github.com/amzn/pay-with-amazon-express-demo/blob/master/java/ExpressPaymentsDemo/src/java/SignRequest.java)
* You may also add or change the values for the optional parameters in the [`SignRequest.java`](https://github.com/amzn/pay-with-amazon-express-demo/blob/master/java/ExpressPaymentsDemo/src/java/SignRequest.java)
* Build and run the project.