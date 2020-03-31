<?php 
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();
//exit();

/* $sql="UPDATE TG_EmployeeUpdatePersInfo SET TeacherMastID='50829' WHERE ID='5626'";
$db->runMsSqlQuery($sql); */ echo "hi";
$j=0;
echo $zSql="SELECT [ID]
      ,[NIC]
      ,[StaffChildID]
      
  FROM [MOENational].[dbo].[TG_EmployeeUpdateChildInfo]
  where IsApproved='Y'";// and ID<20000";
  
  $stmtZ= $db->runMsSqlQuery($zSql);
  while ($rowZ = sqlsrv_fetch_array($stmtZ, SQLSRV_FETCH_ASSOC)){
	  $j++;
	    echo $StaffChildID = trim($rowZ['StaffChildID']);
  		//$NIC = trim($rowZ['NIC']);
		
		
		echo "__";

		
		$queryMainUpdateuuu = "UPDATE UP_StaffChildren SET IsApproved='Y' WHERE ID='$StaffChildID'";
		$db->runMsSqlQuery($queryMainUpdateuuu);
		
		echo "<br>";

  }
  
  echo $j;
?>