<%@ Page Language="C#" %>

<!DOCTYPE html>

<script runat="server">

</script>

<html xmlns="http://www.w3.org/1999/xhtml">
<head runat="server">
    <title></title>
    <script>
        function getParameterByName(name) {
            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
            return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
        }

        // Get the value for the resultCode from the URL
        var resultCode = getParameterByName('resultCode');

        // If the resultCode is failure then this parameter will contain the reason code
        var failureCode = getParameterByName('failureCode');

        // Get all the parameters as is from the URL
        var urlParams = '?' + window.location.search.substring(1);

        /* If the Order was a success then redirect the User to the Success URL.
         * BUYER_ABANDONED_URL -  URL where the buyer is sent when they abandon the order.
         * FAILURE_URL -  For all other Failure scenarios the Buyer is sent here.
         */

        if (resultCode === 'Success') {
            var successUrl = 'Success.aspx';
            window.location.href = successUrl + urlParams;
        } else if (resultCode === 'Failure' && failureCode === 'BuyerAbandoned') {
            var abandonUrl = 'BUYER_ABANDONED_URL';
            window.location.href = abandonUrl + urlParams;
        } else if (resultCode === 'Failure' && failureCode === 'AmazonRejected') {
            var failureUrl = 'FAILURE_URL';
            window.location.href = failureUrl + urlParams;
        } else if (resultCode === 'Failure' && failureCode === 'TemporarySystemIssue') {
            var tempIssueUrl = 'FAILURE_URL';
            window.location.href = failureUrl + urlParams;
        }
    </script>
</head>
<body>
    <form id="form1" runat="server">
        <div>
        </div>
    </form>
</body>
</html>
