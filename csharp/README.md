###`csharp` - Pay with Amazon - Express Demo
####Setup Procedure
* In Visual Studio open `Website` and provide the `ExpressPaymentsDemo` folder as the location
* Add Your `Seller ID`, `MWS Accesskey`, `MWS secret Key`, `Login with Amazon Client ID` to the [`Web.config`](https://github.com/amzn/pay-with-amazon-express-demo/blob/master/csharp/ExpressPaymentsDemo/Web.config)
* Add your `Seller ID` in the place of `YOUR_SELLER_ID_HERE` in [`ExpressPayment.aspx`](https://github.com/amzn/pay-with-amazon-express-demo/blob/master/csharp/ExpressPaymentsDemo/ExpressPayment.aspx)
* Add the value for `returnURL` and `cancelReturnURL` in the [`ExpressPayment.aspx.cs`](https://github.com/amzn/pay-with-amazon-express-demo/blob/master/csharp/ExpressPaymentsDemo/ExpressPayment.aspx.cs)
* Demo implementation link `returnURL` has been pre-filled to link to [`Success.aspx`](https://github.com/amzn/pay-with-amazon-express-demo/blob/master/csharp/ExpressPaymentsDemo/Success.aspx)
* You may also add or change the values for the `optional parameters` in the [`ExpressPayment.aspx.cs`](https://github.com/amzn/pay-with-amazon-express-demo/blob/master/csharp/ExpressPaymentsDemo/ExpressPayment.aspx.cs)
* Build and run the project.
* Use your Sandbox test account credentials to login.
