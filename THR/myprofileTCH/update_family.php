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
where [IsApproved]!='Y' and dDateTime like'%2017%' and ID>20000";// and ID<20000";
  
  $stmtZ= $db->runMsSqlQuery($zSql);
  while ($rowZ = sqlsrv_fetch_array($stmtZ, SQLSRV_FETCH_ASSOC)){
	  $j++;
  		echo $TeacherMastID = trim($rowZ['TeacherMastID']);
		$NICUser=trim($rowZ['NIC']);
		
		echo "__";
		
		$sqlArchiveUp="SELECT [ID]
      ,[NIC]      
      ,[CivilStatusCode]
      ,[SpouseName]
      ,[SpouseNIC]
      ,[SpouseOccupationCode]
      ,CONVERT(varchar(20), SpouseDOB, 121) AS SpouseDOBx
      ,[SpouseOfficeAddr]      
      ,CONVERT(varchar(20), LastUpdate, 121) AS LastUpdatex
      ,[UpdateBy]     
      ,[RecordLog]
  FROM [MOENational].[dbo].[ArchiveUP_TeacherMast]
 
  where NIC='$NICUser' and  SurnameWithInitials IS NULL";
   $stmtArc= $db->runMsSqlQuery($sqlArchiveUp);
   $rowZArch = sqlsrv_fetch_array($stmtArc, SQLSRV_FETCH_ASSOC);
   
   
   $CivilStatusCode=$rowZArch['CivilStatusCode'];
   $SpouseName=$rowZArch['SpouseName'];
   $SpouseNIC=$rowZArch['SpouseNIC'];
   $SpouseOccupationCode=$rowZArch['SpouseOccupationCode'];
   $SpouseDOB=$rowZArch['SpouseDOBx'];
   $SpouseOfficeAddr=$rowZArch['SpouseOfficeAddr'];
   $LastUpdate=$rowZArch['LastUpdatex'];
   $UpdateBy=$rowZArch['UpdateBy'];
   
   $sqlCAllready="SELECT * FROM UP_TeacherMast Where NIC='$NICUser' and SurnameWithInitials IS NULL";
   $isAvailable=$db->rowAvailable($sqlCAllready);
   
   if($isAvailable==1){
	   echo "Available $NICUser";
	   echo $queryMainSave = "UPDATE UP_TeacherMast SET NIC='$NICUser',CivilStatusCode='$CivilStatusCode',SpouseName='$SpouseName',SpouseNIC='$SpouseNIC',SpouseOccupationCode='$SpouseOccupationCode',SpouseDOB='$SpouseDOB',SpouseOfficeAddr='$SpouseOfficeAddr',LastUpdate='$LastUpdate',UpdateBy='$UpdateBy',RecordLog='Update from archive Up' WHERE NIC='$NICUser' and SurnameWithInitials IS NULL";
	   echo "<br>";
					//$db->runMsSqlQuery($queryMainSave);	
	   $db->runMsSqlQuery($queryMainSave);
   }else{
		   $queryMainSave = "INSERT INTO UP_TeacherMast
					   (NIC,CivilStatusCode,SpouseName,SpouseNIC,SpouseOccupationCode,SpouseDOB,SpouseOfficeAddr,LastUpdate,UpdateBy,RecordLog)
				 VALUES
					   ('$NICUser','$CivilStatusCode','$SpouseName','$SpouseNIC','$SpouseOccupationCode','$SpouseDOB','$SpouseOfficeAddr','$LastUpdate','$UpdateBy','Update from archive Up')";
					//$db->runMsSqlQuery($queryMainSave);	
			$db->runMsSqlQuery($queryMainSave);
			echo "Updated $NICUser";
   }
		
		echo "<br>";

  }
  
  echo $j;
?>