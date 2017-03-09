<?php

include_once "sms.php";

/*
	Please login to your account in SendSMS.pk and get your API KEY
	by navigating to this URL: http://www.sendsms.pk/api-settings.php
	Enter then API KEY given there in the following variable ($apikey)
*/

$apikey = "9af16fa9db76f0a56d1a";	// Your API KEY
$sms = new sendsmsdotpk($apikey);	// Making a new sendsms dot pk object
