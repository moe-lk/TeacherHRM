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

//Rejected
/* $zSql="SELECT TeacherMastID,PermResiID,CurrResID
  FROM [MOENational].[dbo].[TG_EmployeeUpdatePersInfo]
where [IsApproved]='R'";// and ID<20000"; // and dDateTime like'%2017%' and ID>20000 // ,[TeacherMastID]     
  //and NIC='638484331V', 846483071V
  $stmtZ= $db->runMsSqlQuery($zSql);
  while ($rowZ = sqlsrv_fetch_array($stmtZ, SQLSRV_FETCH_ASSOC)){
		$TeacherMastID=trim($rowZ['TeacherMastID']);
		$PermResiID=trim($rowZ['PermResiID']);
		$CurrResID=trim($rowZ['CurrResID']);
		
		$sqlDel2 = "DELETE FROM UP_TeacherMast  WHERE ID=$TeacherMastID";
        $db->runMsSqlQuery($sqlDel2);
		
		$sqlDel2 = "DELETE FROM UP_StaffAddrHistory  WHERE ID=$PermResiID";
        $db->runMsSqlQuery($sqlDel2);
		
		$sqlDel2 = "DELETE FROM UP_StaffAddrHistory  WHERE ID=$CurrResID";
        $db->runMsSqlQuery($sqlDel2);
		
  }
  $sqlDel2 = "DELETE FROM TG_EmployeeUpdatePersInfo  WHERE IsApproved='R'";
        $db->runMsSqlQuery($sqlDel2);
	  exit(); */
$zSql="SELECT [ID]
      ,[NIC]
  FROM [MOENational].[dbo].[TG_EmployeeUpdatePersInfo]
where [IsApproved]!='Y'";// and ID<20000"; // and dDateTime like'%2017%' and ID>20000 // ,[TeacherMastID]     
  //and NIC='638484331V', 846483071V
  $stmtZ= $db->runMsSqlQuery($zSql);
  while ($rowZ = sqlsrv_fetch_array($stmtZ, SQLSRV_FETCH_ASSOC)){
		
		$idPersInfo=$rowZ['ID'];
		$NICUser=trim($rowZ['NIC']);
		
		$sqlCAllready="SELECT ID,NIC FROM UP_TeacherMast Where NIC='$NICUser' and SurnameWithInitials IS NULL";
   		$isAvailable=$db->rowAvailable($sqlCAllready);
		if($isAvailable==1){
			
		   $sqlCAllreadyNN="SELECT ID FROM UP_TeacherMast Where NIC='$NICUser' and SurnameWithInitials IS NOT NULL"; 
		   $stmtArc= $db->runMsSqlQuery($sqlCAllreadyNN);
		   $rowZArch = sqlsrv_fetch_array($stmtArc, SQLSRV_FETCH_ASSOC);
		   $IDCorrect=$rowZArch['ID'];
		   
  		   $zSqlvvv="SELECT ID FROM TG_EmployeeUpdatePersInfo where TeacherMastID='$IDCorrect' and IsApproved='N'";
		   $isAvailablevvv=$db->rowAvailable($zSqlvvv);
			if($isAvailablevvv==1){
				
			}else{
			    echo $j;echo "__";
		  		echo $IDCorrect;
		  
			   $queryMainSave = "UPDATE TG_EmployeeUpdatePersInfo SET TeacherMastID='$IDCorrect' WHERE NIC='$NICUser' and ID='$idPersInfo'";
			  // $db->runMsSqlQuery($queryMainSave);
			  
			   echo "__";
   
			echo $NICUser;echo "<br>";
			  $j++;
			  
			}
		  
		}
   
		
  }
  
  echo $j;
?>