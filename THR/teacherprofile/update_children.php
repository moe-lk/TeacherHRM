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
      ,[ChildName]
      ,[Gender]
      ,CONVERT(varchar(20), DOB, 121) AS DOBx
      ,CONVERT(varchar(19), LastUpdate, 121) AS LastUpdatex
      ,[UpdateBy]
      ,[RecordLog]
  FROM [MOENational].[dbo].[ArchiveUP_StaffChildren]
where LastUpdate like'%2017%' and ID>110000";// and ID<20000";
  
  $stmtZ= $db->runMsSqlQuery($zSql);
  while ($rowZ = sqlsrv_fetch_array($stmtZ, SQLSRV_FETCH_ASSOC)){
	  $j++;
	    echo $IDo = trim($rowZ['ID']);
  		$NIC = trim($rowZ['NIC']);
		$ChildName=$rowZ['ChildName'];
		$Gender=$rowZ['Gender'];
		$DOB=$rowZ['DOBx'];
		$LastUpdate=$rowZ['LastUpdatex'];
		$RecordLog=$rowZ['RecordLog'];
		
		echo "__";
 		echo $queryMainSave="INSERT INTO UP_StaffChildren (NIC,ChildName,Gender,DOB,LastUpdate,RecordLog,IsApproved)
			 VALUES				   
		('$NIC','$ChildName','$Gender','$DOB','$LastUpdate','$RecordLog','N')";
		
   //$queryMainSave = "UPDATE UP_StaffChildren SET NIC='$NIC',ChildName='$ChildName',Gender='$Gender',DOB='$DOB',LastUpdate='$LastUpdate',RecordLog='$RecordLog'";
        $db->runMsSqlQuery($queryMainSave);
		
		$reqTabMobAc="SELECT ID FROM UP_StaffChildren where NIC='$NIC'  ORDER BY ID DESC";
		$stmtMobAc= $db->runMsSqlQuery($reqTabMobAc);
		$rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
		$StaffChildIDn = trim($rowMobAc['ID']);
		
		$queryMainUpdateuuu = "UPDATE TG_EmployeeUpdateChildInfo SET StaffChildID='$StaffChildIDn' WHERE StaffChildID='$IDo'";
		$db->runMsSqlQuery($queryMainUpdateuuu);
		
		echo "<br>";

  }
  
  echo $j;
?>