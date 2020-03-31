<?php

//require_once '../smservices/sms.php';
include('../smservices/sms.php');

/* Send SMS via GOV SMS */
$sms_content = 'SMS Content 1';
$config = array('message' => $sms_content, 'recepient' => '0772261631');//0779105338
$smso = new sms();
$result = $smso->sendsms($config);
if ($result[0] == 1) {
    //SMS Sent
    echo 'ok';
} else {
    //SMS wasn't Sent
    echo 'error';
}

//Bulk SMS
/*$dataarray=array(array('sms_content' => 'Test 1','sms_recepient' => '0779105338'),array('sms_content' => 'Test 2','sms_recepient' => '0779105338'),array('sms_content' => 'Test 3','sms_recepient' => '0779105338'));
$smso = new sms();
$result = $smso->sendsmsbatch($dataarray);
if ($result[0] == 1) {
    //SMS Sent
    echo 'ok';
} else {
    //SMS wasn't Sent
    echo 'error';
}*/
?>
