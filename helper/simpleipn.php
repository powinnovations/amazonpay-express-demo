<?php

/* This is a stand-alone IPN handler. It will validate the IPN and set some 
 * variables you can use to do your own parsing and handling. You can modify 
 * the section near the bottom of this script. For now, it will log all IPNs
 * to your /tmp folder assuming you are on Linux. You can modify the path 
 * below.
 */

$header = getallheaders();
$body = file_get_contents("php://input");

if (isset($header["x-amz-sns-message-type"])) {
    $result = parseIpn($header, json_decode($body, true));
    if ($result != "") {
        // Error occurred. $result will have it.
    }
}

function parseIpn($header, $body) {
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

        /* Here, we output the contents of the response XML (data) to a file.
         * You can add your IPN handling loginc here.
         */
        file_put_contents("/tmp/ipn_{$messageId}", print_r($data, true));
    } else {
        return "Invalid signature.";
    }
}
