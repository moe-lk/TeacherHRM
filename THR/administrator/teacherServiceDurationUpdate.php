<?php 
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();

$i=0;
$sqlAdd = "SELECT NIC FROM TeacherMast where NIC like '%V%'";
$stmtAdd = $db->runMsSqlQuery($sqlAdd);
while ($row = sqlsrv_fetch_array($stmtAdd, SQLSRV_FETCH_ASSOC)) {
	echo $NIC=trim($row['NIC']);
	$i++;
	
	$sqlHis = "SELECT CONVERT(varchar(20),AppDate,121) AS AppDateEmp,PositionCode FROM StaffServiceHistory where NIC='$NIC'";
	$stmtHis = $db->runMsSqlQuery($sqlHis);
	while ($rowxx = sqlsrv_fetch_array($stmtHis, SQLSRV_FETCH_ASSOC)) {
		echo $NIC;echo "_";
		echo $AppDate=$rowxx['AppDateEmp'];echo "_";
		echo $PositionCode=$rowxx['PositionCode'];echo "_";
		echo "<br>";
		$queryGradeSave="INSERT INTO TG_StaffServiceDurationMaster
			   (NIC,AppDate,PositionCode)
		 VALUES
			   ('$NIC','$AppDate','$PositionCode')";
			   
		$countSql="SELECT * FROM TG_StaffServiceDurationMaster where NIC='$NIC' and AppDate='$AppDate'";
		$isAvailable=$db->rowAvailable($countSql);
		if($isAvailable==1){
			echo $msg="Already exist.";
		}else{ 
			$db->runMsSqlQuery($queryGradeSave);
			//$newID=$db->runMsSqlQueryInsert($queryGradeSave);
			echo $msg="Successfully Updated.";
		}
		
	}
	/* if($i>5){
	exit();
	} */
}
?>