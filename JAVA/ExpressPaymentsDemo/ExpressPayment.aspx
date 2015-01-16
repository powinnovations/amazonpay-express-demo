<%--
*-*-**-***-*****-********-*************
Pay with Amazon Express Payments Demo
Copyright 2015 Amazon.com, Inc. or its affiliates. All Rights Reserved.
Licensed under the Apache License, Version 2.0 (the "License"); 
*-*-**-***-*****-********-*************

You may not use this file except in compliance with the License. You may obtain 
a copy of the License at: 

http://aws.amazon.com/apache2.0/

or in the "license" file accompanying this file. This file is distributed on an
"AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or 
implied. See the License for the specific language governing permissions and 
limitations under the License.
--%>

<%@ Page Language="C#" AutoEventWireup="true" CodeFile="ExpressPayment.aspx.cs" Inherits="ExpressPayment" %>

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title>Express Payments Advanced Sample cart</title>
    <script type='text/javascript' src="https://static-na.payments-amazon.com/OffAmazonPayments/us/sandbox/js/Widgets.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <style>
        label,
        div {
            margin: 10px;
        }
    </style>
</head>

<body>

    <label id="itemname" for="tshirt">Item Name: Long Sleeve Tee</label>
    <div id="amount" value="100">Price: $100</div>

    <label for="QuantitySelect">Qty:</label>
    <select id="QuantitySelect">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
    </select>

    <div id="AmazonPayButton"></div>

    <script type="text/javascript">
        //alert(parseInt($("#amount").attr("value")) * parseInt($("#QuantitySelect option:selected").val()));
        var Amount, CurrencyCode, SellerNote;
        OffAmazonPayments.Button("AmazonPayButton", "A2MQTZXPTWUGRJ", {

            type: "hostedPayment",

            hostedParametersProvider: function (done) {

                $.ajax({
                    type: "POST",
                    url: "ExpressPayment.aspx/AddRequiredParameters",
                    contentType: "application/json",
                    data: JSON.stringify({
                        Amount: parseInt($("#amount").attr("value")) * parseInt($("#QuantitySelect option:selected").val()),
                        CurrencyCode: "USD",
                        SellerNote: $("#itemname").text() + ' QTY: ' + $("#QuantitySelect option:selected").val()
                    }),
                    dataType: "json",
                    cache: false,
                    success: function (data) {
                        done(JSON.parse(data.d));
                    }
                });
            },
            onError: function (errorCode) {
                console.log(errorCode.getErrorCode() + " " + errorCode.getErrorMessage());
            }
        });
    </script>
    <div>
    </div>

</body>
</html>
