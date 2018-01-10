# Introduction
Have a simple payments need? [Express Integration](https://pay.amazon.com/developer/express) is the quickest way to enable [Amazon Pay](https://pay.amazon.com/) on your web and mobile sites. For simple purchases where you already know the tax and shipping costs, you can set up with Express Integration in minutes by adding simple HTML and JavaScript snippets. The demos included in this repository show how to perform an Express Integration in various languages (C#, Java, PHP, Ruby).

##### These examples are suitable for the following scenarios:
* If your parameters will change dynamically on the checkout page and cannot be preloaded.
* If you have a multi-item shopping cart.
* If your store contains multiple items wherein using static buttons for each item is not feasible. 

##### The demos follow this pattern:
* A simple cart page is displayed with Item name and Quantity.
* The [dynamic parameters](https://github.com/amzn/amazonpay-express-demo#dynamic-parameters) are passed to the backend and a signature is calculated.
* The signature is appended to a data-structure along with the other parameters and encoded in JSON.
* The JSON-encoded string is then passed through the Javascript in the frontend.
* The checkout process begins once the signature and the other required input parameters are verified.
    
###### For language-specific installation and configuration instructions, please see the readme file in the subdirectory of the language of your choice. To run the demos you will also need to [register with Amazon Pay](https://pay.amazon.com/signup).

# Dynamic Parameters
##### 1. `amount`
The amount of the payment.
##### 2. `sellerNote`
The message that will appear in the checkout pages (maximum length of 1024 characters).
##### 3. `sellerOrderId`
The seller-specified identifier of this order. This is displayed in emails to your buyers and in the transaction history on the Amazon Payments website.                                                             
We recommend that you use the following characters only:
`lowercase a-z`, `uppercase A-Z`, `numbers 0-9`, `dash (-)`, or `underscore (_)`.  
`Max length: 50 characters`                                                           
##### 4. `currencyCode`
The currency to use to charge the buyer (eg - 'USD'). This defaults to currency for your region.

# Endpoint URL
##### 1.Sandbox Endpoint
`https://static-na.payments-amazon.com/OffAmazonPayments/us/sandbox/js/Widgets.js`
##### 2. Production Endpoint
`https://static-na.payments-amazon.com/OffAmazonPayments/us/js/Widgets.js` 
