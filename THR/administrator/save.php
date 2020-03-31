<?php

require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();

$vID = $_REQUEST['vID'];
$vDes = $_REQUEST['vDes'];
$tblName = $_REQUEST['tblName'];
$mainID = $_REQUEST['mainID'];
$redirect_page = $_REQUEST['redirect_page'];
$status = $_REQUEST['AED'];
$cat = $_REQUEST['cat'];
$field_name="AttachFile";
	/* echo $_FILES[$field_name]['name']; echo "hi";
	exit(); */
if ($status == 'D') {
    //echo $vID;
    //exit();
    if ($cat == 'Approval') {
        $sqlDel = "DELETE FROM $tblName
      WHERE ID=$vID";
        $db->runMsSqlQuery($sqlDel);

        $sqlDel2 = "DELETE FROM TG_ApprovalProcess
      WHERE ApprovalProcMainID=$vID";
        $db->runMsSqlQuery($sqlDel2);

        header("Location:$redirect_page");
        // redirect("reservation_customer_info-54-4_1--E--104.html");
        exit();
    }
	
	if ($cat == 'ControlUser') {
		$sqlDel = "DELETE FROM Passwords
      WHERE NICNo='$vID'";
    	$db->runMsSqlQuery($sqlDel);
		header("Location:$redirect_page");
        exit();
	}
	
    $sqlDel = "DELETE FROM $tblName
      WHERE ID=$vID";
    $db->runMsSqlQuery($sqlDel);

    header("Location:$redirect_page");
    // redirect("reservation_customer_info-54-4_1--E--104.html");
    exit();
}

if ($status == 'ED'){ //Enable/disable records
	if($vDes=='Active') $cEnabled="N";
	if($vDes=='Deactive') $cEnabled="Y";
	
	$sqlDel = "UPDATE $tblName SET StatusOf='$cEnabled'
      WHERE ID=$vID";
    $db->runMsSqlQuery($sqlDel);
	header("Location:$redirect_page");
    // redirect("reservation_customer_info-54-4_1--E--104.html");
    exit();
	
}
?>