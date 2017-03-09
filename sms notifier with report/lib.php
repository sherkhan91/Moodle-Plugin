<?php

require_once('../../config.php');
// Send SMS pk Api Function
/*
 * This function will send the SMS using sendsms.pk.API is only for Pakistan's users.
 *
 * @param int   $to  User id
 * @param string $msg  Message Text
 * @return String $status return will shows the status of message.
 */
function send_sms($to, $msg) 
{
    global $CFG;
    require_once('sms_api/sms.php');

    $apikey=$CFG->block_sms_apikey;         // API Key.

    $sms = new sendsmsdotpk($apikey);	    // Making a new sendsms dot pk object.
    
    // isValid.
    if ($sms->isValid()) {
        $status = get_string('valid_key', 'block_sms');
    } else {
        $status = "KEY: " . $apikey . " IS NOT VALID";
    }
    $msg = stripslashes($msg);
    // SEND SMS.
    if ($sms->sendsms($to, $msg, 0)) {
        $status = get_string('sent', 'block_sms');
    } else {
        $status = get_string('error', 'block_sms');
    }
    return $status;
}




