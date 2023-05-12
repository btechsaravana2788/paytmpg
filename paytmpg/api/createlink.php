<?php

/*
* import checksum generation utility
* You can get this utility from https://developer.paytm.com/docs/checksum/
*/
require_once("PaytmChecksum.php");

$paytmParams = array();

$paytmParams["body"] = array(
    "mid"             => "TreisS53682139440228",
    "linkType"        => "FIXED",
    "linkDescription" => "Test Payment",
    "linkName"        => "Test",
	"customerId"	  => "10001001",
	"amount"          => "50.00",
	/* "txnAmount"     => array(
        "value"     => "10.00",
        "currency"  => "INR",
    ), */
	"redirectionUrlSuccess"        => "http://localhost/paytm/api/responseapi.php",
);

/*
* Generate checksum by parameters we have in body
* Find your Merchant Key in your Paytm Dashboard at https://dashboard.paytm.com/next/apikeys 
*/
$checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), "iReAmPir%B8Xwpb2");

$paytmParams["head"] = array(
    "tokenType"	      => "AES",
    "signature"	      => $checksum
);

$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

/* for Staging */
$url = "https://securegw-stage.paytm.in/link/create";

/* for Production */
// $url = "https://securegw.paytm.in/link/create";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
$response = curl_exec($ch);
print_r($response);