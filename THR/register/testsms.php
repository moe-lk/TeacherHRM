<?php 
include('../testsm/sms.php');
$tpNumber="0775213233";
$sms_content = 'test successfully 2';
$config = array('message' => $sms_content, 'recepient' => $tpNumber);//0779105338
$smso = new sms();
$result = $smso->sendsms($config,1);
if ($result[0] == 1) {
	//SMS Sent
	//echo 'ok';
	$statusOf="Success";
} else if ($result[0] == 2) {
	//SMS Sent
	//echo 'ok';
	$statusOf="Success2";
} else {
	//SMS wasn't Sent
	//echo 'error';
	$statusOf="Fail";
}
echo $statusOf;
?>