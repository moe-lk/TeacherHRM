<?php 
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();
exit();

/* $sql="UPDATE TG_EmployeeUpdatePersInfo SET TeacherMastID='50829' WHERE ID='5626'";
$db->runMsSqlQuery($sql); */ echo "hi";
$j=0;
$zSql="SELECT [ID]
      ,[NIC]
      ,[TeacherMastID]
      
  FROM [MOENational].[dbo].[TG_EmployeeUpdateFamilyInfo]
where [IsApproved]!='Y' and dDateTime like'%2017%' and ID>20000";
  
  $stmtZ= $db->runMsSqlQuery($zSql);
  while ($rowZ = sqlsrv_fetch_array($stmtZ, SQLSRV_FETCH_ASSOC)){
	  $j++;
  		echo $NIC = trim($rowZ['NIC']);
		echo $ID = $rowZ['ID'];
		
		$sqlCAllready="SELECT ID FROM UP_TeacherMast Where NIC='$NIC' and SurnameWithInitials IS NULL";
		$stmtArc= $db->runMsSqlQuery($sqlCAllready);
  		$rowZArch = sqlsrv_fetch_array($stmtArc, SQLSRV_FETCH_ASSOC);
		$TeacherMastID=$rowZArch['ID'];
		
		$queryMainSave = "UPDATE TG_EmployeeUpdateFamilyInfo SET TeacherMastID='$TeacherMastID' WHERE NIC='$NIC'";
					//$db->runMsSqlQuery($queryMainSave);	
		$db->runMsSqlQuery($queryMainSave);
		echo "Updated $NIC";
			
		
		echo "__";
		
		
		
		echo "<br>";

  }
  
  echo $j;
?>