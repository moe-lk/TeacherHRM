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

if ($status == 'D'){
	//echo $vID;
	//exit();
	if($cat=='Leave'){
		$sqlDel="DELETE FROM TG_StaffLeave
		  WHERE ID=$vID";
		  $db->runMsSqlQuery($sqlDel);
		  
		  $sqlDelxx="DELETE FROM TG_Approval_Leave
		  WHERE RequestID=$vID";
		  $db->runMsSqlQuery($sqlDelxx);
		  
		  header("Location:$redirect_page");	
		// redirect("reservation_customer_info-54-4_1--E--104.html");
		 exit() ;

	}else{
		$sqlDel="DELETE FROM $tblName
		  WHERE ID=$vID";
		  $db->runMsSqlQuery($sqlDel);
		  
		  header("Location:$redirect_page");	
		// redirect("reservation_customer_info-54-4_1--E--104.html");
		 exit() ;
	}
}

?>