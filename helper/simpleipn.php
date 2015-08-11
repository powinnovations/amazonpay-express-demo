<?php

/*
 * SimpleIPN - This script will verify, parse, and email IPN data. This
 * script will only recognize IPNs generated from an Express button. Your
 * server must be configured to send email (e.g. sendmail).
 *
 * This file should be copied to a location on your web server that is
 * publicly accessible. You will also need to configure your "Merchant URL"
 * in Seller Central via Settings > Integration Settings. Set it to the
 * location of this script.
 */

// Who do you want emails to be sent to?
$to = "changeme@example.com";

// Which notifications do you want to receive?
$ipn_authorize = true;
$ipn_capture = true;
$ipn_refund = true;
$ipn_oro = true; // ORO state changes (e.g. open, close, etc.)




/* You shouldn't need to edit anything below this line */
$header = getallheaders();
$body = file_get_contents("php://input");

if (isset($header["x-amz-sns-message-type"])) {
    $result = parseIpn($header, json_decode($body, true));
    if ($result != "") {
        // Error occurred. $result will have it.
    }
}

function parseIpn($header, $body) {
    global $ipn_authorize, $ipn_capture, $ipn_refund, $ipn_oro, $to;

    if (!array_key_exists("x-amz-sns-message-type", $header)) {
        return "Invalid SNS message type in header.";
    }
    if ($header["x-amz-sns-message-type"] !== "Notification") {
        return "Invalid notification type in header.";
    }
    if ($body["MessageId"] !== $header["x-amz-sns-message-id"]) {
        return "Invalid message Id in header.";
    }
    $message_encoded = $body["Message"];
    $message = json_decode($body["Message"], true);
    if (json_last_error() != 0) {
        return "Invalid JSON in message.";
    }
    $type = $body["Type"];
    $messageId = $body["MessageId"];
    $topicArn = $body["TopicArn"];
    $timestamp = $body["Timestamp"];
    $signature = base64_decode($body["Signature"]);
    $signingCertUrl = $body["SigningCertURL"];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $signingCertUrl);
    curl_setopt($ch, CURLOPT_PORT, 443);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if (!$certificate = curl_exec($ch)) {
        curl_close($ch);
        return curl_error($ch);
    }
    $certKey = openssl_get_publickey($certificate);
    if ($certKey === False) {
        return "Invalid certificate.";
    }
    $signature_string = "Message\n{$message_encoded}\nMessageId\n{$messageId}\nTimestamp\n{$timestamp}\nTopicArn\n{$topicArn}\nType\n{$type}\n";
    $result = openssl_verify($signature_string, $signature, $certKey, OPENSSL_ALGO_SHA1);
    if ($result == 1) {
        $timestamp = $message["Timestamp"];
        $type = $message["NotificationType"];
        $environment = $message["ReleaseEnvironment"];
        $marketplaceId = $message["MarketplaceID"];
        $xml = simplexml_load_string($message["NotificationData"]);
        $data = json_decode(json_encode($xml), true);
        $subject = "";
        $message = "";
        $sendit = false;
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        $headers .= 'From: SimpleIPN' . "\r\n";
        $message = "<html><head><title>SimpleIPN</title></head><body>" . pp($data) . "</body></html>";

        switch ($type) {
            case "OrderReferenceNotification":
                if ($ipn_oro) {
                    $sendit = true;
                }
                $subject = "Order Reference Notification";
                break;

            case "PaymentAuthorize":
                if ($ipn_authorize) {
                    $sendit = true;
                }
                $subject = "Payment Authorize Notification";
                break;

            case "PaymentCapture":
                if ($ipn_capture) {
                    $sendit = true;
                }
                $subject = "Payment Capture Notification";
                break;

            case "PaymentRefund":
                if ($ipn_refund) {
                    $sendit = true;
                }
                $subject = "Payment Refund Notification";
                break;
            default:
                $sendit = false;
        }
        if ($subject === "" || $to === "changeme@example.com") {
            $sendit = false;
        }

        if ($sendit) {
            mail($to, $subject, $message, $headers);
        }


        /* If you want to log to a file you can do something like this */
        //file_put_contents("/tmp/ipn_{$type}_{$messageId}", print_r($data, true));
    } else {
        return "Invalid signature.";
    }
}

function pp($arr) {
    $retStr = '<ul style="margin-left:5px; margin-right:0; padding-left:10px; padding-right:0;">';
    if (is_array($arr)) {
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                $retStr .= '<li>' . $key . ': ' . pp($val) . '</li>';
            } else {
                $retStr .= '<li>' . $key . ': ' . $val . '</li>';
            }
        }
    }
    $retStr .= '</ul>';
    return $retStr;
}
