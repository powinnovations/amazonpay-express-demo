###`csharp` - Pay with Amazon - Custom Integration Express Demo. 
####Setup Procedure
* In Visual Studio open `Website` and provide the `ExpressPaymentsDemo` folder as the location
* Add Your `Seller ID`, `MWS Accesskey`, `MWS secret Key`, `Login with Amazon Client ID` to the [`Web.config`](https://github.com/amzn/pay-with-amazon-express-demo/blob/master/csharp/ExpressPaymentsDemo/Web.config)
* Add the value for `returnURL` in the [`ExpressPayment.aspx.cs`](https://github.com/amzn/pay-with-amazon-express-demo/blob/master/csharp/ExpressPaymentsDemo/ExpressPayment.aspx.cs)
* You may also add or change the values for the `optional parameters` in the [`ExpressPayment.aspx.cs`](https://github.com/amzn/pay-with-amazon-express-demo/blob/master/csharp/ExpressPaymentsDemo/ExpressPayment.aspx.cs)
* Build and run the project.