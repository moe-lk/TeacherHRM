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
      ,[TeacherMastID]
      ,[PermResiID]
      ,[CurrResID]
      ,[dDateTime]
      ,[ZoneCode]
      ,[IsApproved]
      ,[ApproveComment]
      ,[ApproveDate]
      ,[ApprovedBy]
      ,[UpdateBy]
  FROM [MOENational].[dbo].[TG_EmployeeUpdatePersInfo]
  where IsApproved='N' and TeacherMastID!=0 and (TeacherMastID<90000 and TeacherMastID>50000)";// and ID<20000"; //ZoneCode='ZN2304' and 
  
  $stmtZ= $db->runMsSqlQuery($zSql);
  while ($rowZ = sqlsrv_fetch_array($stmtZ, SQLSRV_FETCH_ASSOC)){
	  
	    $IDo = trim($rowZ['ID']);
  		$NIC = trim($rowZ['NIC']);
		$TeacherMastID=$rowZ['TeacherMastID'];
		
		$selectOld="SELECT [ID]
      ,[NIC]
      ,[SurnameWithInitials]
      ,[FullName]
      ,[Title]
      ,[PerResRef]
      ,[CurResRef]
      ,[MobileTel]
      ,CONVERT(varchar(19), DOB, 121) AS DOB
      ,[GenderCode]
      ,[EthnicityCode]
      ,[ReligionCode]
      ,[CivilStatusCode]
      ,[SpouseName]
      ,[SpouseNIC]
      ,[SpouseOccupationCode]
      ,CONVERT(varchar(19), SpouseDOB, 121) AS SpouseDOB
      ,[SpouseOfficeAddr]      
      ,[HQualificatinRef]
      ,[CurServiceRef]
      ,CONVERT(varchar(19), LastUpdate, 121) AS LastUpdate
      ,[UpdateBy]
      ,[RecStatus]
      ,[emailaddr]      
      ,[RecordLog]
      ,[IsApproved]
  FROM [MOENational].[dbo].[new36_UP_TeacherMast] WHERE ID='$TeacherMastID'";
  $stmtZold= $db->runMsSqlQuery($selectOld);
  $rowZold = sqlsrv_fetch_array($stmtZold, SQLSRV_FETCH_ASSOC);
  
  $ID=$rowZold['ID'];
  $NIC=trim($rowZold['NIC']);
  $SurnameWithInitials=$rowZold['SurnameWithInitials'];
  $FullName=$rowZold['FullName'];
  $Title=$rowZold['Title'];
  $PerResRef=$rowZold['PerResRef'];
  $CurResRef=$rowZold['CurResRef'];
  $MobileTel=$rowZold['MobileTel'];
  $DOB=$rowZold['DOB'];
  $GenderCode=$rowZold['GenderCode'];
  $EthnicityCode=$rowZold['EthnicityCode'];
  $ReligionCode=$rowZold['ReligionCode'];
  $CivilStatusCode=$rowZold['CivilStatusCode'];
  $SpouseName=$rowZold['SpouseName'];
  $SpouseNIC=$rowZold['SpouseNIC'];
  $SpouseOccupationCode=$rowZold['SpouseOccupationCode'];
  $SpouseDOB=$rowZold['SpouseDOB'];
  $SpouseOfficeAddr=$rowZold['SpouseOfficeAddr'];
  $HQualificatinRef=$rowZold['HQualificatinRef'];
  $CurServiceRef=$rowZold['CurServiceRef'];
  $LastUpdate=$rowZold['LastUpdate'];
  $UpdateBy=$rowZold['UpdateBy'];
  $RecStatus=$rowZold['RecStatus'];
  $emailaddr=$rowZold['emailaddr'];
  $RecordLog=$rowZold['RecordLog'];
  $IsApproved=$rowZold['IsApproved'];
  
  
  $sqlpMast="SELECT ID FROM UP_TeacherMast where ID='$TeacherMastID'";
  $isAvailablePmast=$db->rowAvailable($sqlpMast);
  if($isAvailablePmast!=1){
	  $j++;
	  echo $IDo;echo "__";echo $ID;
		if($ID!=''){
 		echo $queryMainSave="INSERT INTO UP_TeacherMast (ID,NIC,SurnameWithInitials,FullName,Title,PerResRef,CurResRef,MobileTel,DOB,GenderCode,EthnicityCode,ReligionCode,CivilStatusCode,SpouseName,SpouseNIC,SpouseOccupationCode,SpouseDOB,SpouseOfficeAddr,HQualificatinRef,CurServiceRef,LastUpdate,UpdateBy,RecStatus,emailaddr,RecordLog,IsApproved)
			 VALUES				   
		('$ID','$NIC','$SurnameWithInitials','$FullName','$Title','$PerResRef','$CurResRef','$MobileTel','$DOB','$GenderCode','$EthnicityCode','$ReligionCode','$CivilStatusCode','$SpouseName','$SpouseNIC','$SpouseOccupationCode','$SpouseDOB', '$SpouseOfficeAddr','$HQualificatinRef','$CurServiceRef','$LastUpdate','$UpdateBy','$RecStatus','$emailaddr','$RecordLog','N')";
		
       // $db->runMsSqlQueryIDOD($queryMainSave);
		}
		
		
		
		//exit();
		echo "<br>";
		}

  }
  
  echo $j;exit();
?>