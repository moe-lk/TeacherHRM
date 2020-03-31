<?php 
require_once 'error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include 'db_config/DBManager.php';
$db = new DBManager();

	$sql = "SELECT CenCode FROM CD_CensesNo where InstType like'%ZE%'"; 
	$stmt = $db->runMsSqlQuery($sql);
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$CenCode=trim($row['CenCode']);
		
		
		$restZone = substr($CenCode, -4, 4);
		$zoneCodeLoged="ZN".$restZone;
		
	echo 	$queryUpate="UPDATE CD_CensesNo SET	CenCode='$zoneCodeLoged' WHERE CenCode='$CenCode'";

$db->runMsSqlQuery($queryUpate);
	
	
	}
	
	 $sql = "SELECT ID,InstCode FROM StaffServiceHistory where InstCode like'%ZE%'"; 
	$stmt = $db->runMsSqlQuery($sql);
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$InstCode=trim($row['InstCode']);
		$ID=trim($row['ID']);
		
		$restZone = substr($InstCode, -4, 4);
		$zoneCodeLoged="ZN".$restZone;
		
		$queryUpate="UPDATE StaffServiceHistory SET	InstCode='$zoneCodeLoged' WHERE ID='$ID'";
	$db->runMsSqlQuery($queryUpate);
	
	} 


?>