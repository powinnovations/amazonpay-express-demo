# pay-with-amazon-express-demo
Pay with Amazon - Express Payments Demo - Custom Integration


#Introduction

Login and Pay with Amazon provides millions of buyers a secure, trusted, and convenient way to pay for
their purchases on your site. To complete their purchase, buyers simply select a shipping address and
payment method stored in their Amazon Payments account.
If your parameters will change dynamically on the checkout page and cannot be preloaded.
This project includes examples in languages such as PHP, C#, Java, Ruby to provide this feature.

The examples showcase a simple cart page where in the dynamic parameters are passed through the button. The parameters are then used to calculate the signature
Once the signature is verified, the checkout process begins.

#Getting Started
To use the Pay with Amazon button you will need the following:
1. Register with Amazon Payments
2. Download this folder as a zip or clone the project

#Dynamic Parameters

amount
The amount of the payment.

#sellerNote
The message that will appear in the checkout pages.
(Max length: 1024 characters)

#sellerOrderId
The seller?specified identifier of this order. This is displayed in buyer emails and in the transaction history on the Amazon Payments website.
We recommend that you use the following characters only: lowercase a?z, uppercase A?Z, numbers 0-9, dash (?), or underscore (_).
Max length: 50 characters

#currencyCode
The currency to use to charge the buyer.
Default: current seller region
Example: USD