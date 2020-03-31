<?php 
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
include_once '../approveProcessfunction.php';

$db = new DBManager();

$vID = $_REQUEST['vID'];
$vDes = $_REQUEST['vDes'];
$tblName = $_REQUEST['tblName'];
$mainID = $_REQUEST['mainID'];
$redirect_page = $_REQUEST['redirect_page'];
$status = $_REQUEST['AED'];	
$cat = $_REQUEST['cat'];
if($cat=='applyForTraining'){
	$Remarks=addslashes($_REQUEST['Remarks']);//echo "<br>";
	$TrainingCallID=$_REQUEST['TrainingCallID'];//echo "<br>";
	$ApplyNIC=$_REQUEST['ApplyNIC'];
	$ApplyDate=date('Y-m-d');
	$IsApproved="N";
	//exit();
	$dte=date("ymdHms");
	$uploadpath="teachertrainingapplication";
	$fileSaveName="";
	if($_FILES['ApplicationDoc']['name']!='') { //save file	
		$fileSaveName=$dte.$_FILES['ApplicationDoc']['name']; 						
		$uppth2=$uploadpath."/".$fileSaveName;	
		copy ($_FILES['ApplicationDoc']['tmp_name'], $uppth2);
		//$insArrCusE[$field_name]=$fileSaveName;													
	}	
	$queryGradeSave="INSERT INTO TG_TeacherTrainingCallApply
		   (TrainingCallID,Remarks,ApplyNIC,ApplyDate,ApplicationDoc,IsApproved)
	 VALUES
		   ('$TrainingCallID','$Remarks','$ApplyNIC','$ApplyDate','$fileSaveName','$IsApproved')";
		 
	$countSql="SELECT * FROM TG_TeacherTrainingCallApply where TrainingCallID='$TrainingCallID' and ApplyNIC='$ApplyNIC'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		$msg="Already exist.";
	}else{ 
	
		$newID=$db->runMsSqlQueryInsert($queryGradeSave);
		//$db->runMsSqlQuery($queryGradeSave);
		//$msg="Successfully Updated.";
	    $processType = 'ApplyForTraining';
	    $msg = getApproveList($processType, $newID);
	}
	
	$_SESSION['success_update']=$msg;
	header("Location:applyForTraining-4.html");	
	exit() ;
}

if($cat=='callATraining'){
	$Title=addslashes($_REQUEST['Title']);//echo "<br>";
	$TrainingCode=addslashes($_REQUEST['TrainingCode']);
	$TrainingDescription=addslashes($_REQUEST['TrainingDescription']);//echo "<br>";
	$StartDate=$_REQUEST['StartDate'];//echo "<br>";
	$EndDate=$_REQUEST['EndDate'];//echo "<br>";
	$ClosingDate=$_REQUEST['ClosingDate'];
	$ApplyDate=$_REQUEST['ApplyDate'];
	$GenerateFrom=$_REQUEST['GenerateFrom'];
	$TrainingFor=",".$_REQUEST['T1'].",".$_REQUEST['T2'].",".$_REQUEST['T3'].",".$_REQUEST['T4'];
	//exit();
	$dte=date("ymdHms");
	$uploadpath="trainingcallapplication";
	$fileSaveName="";
	if($_FILES['Reference']['name']!='') { //save file	
		$fileSaveName=$dte.$_FILES['Reference']['name']; 						
		$uppth2=$uploadpath."/".$fileSaveName;	
		copy ($_FILES['Reference']['tmp_name'], $uppth2);
		//$insArrCusE[$field_name]=$fileSaveName;													
	}	
	$queryGradeSave="INSERT INTO $tblName
		   (Title,TrainingDescription,StartDate,EndDate,ClosingDate,TrainingFor,Reference,GenerateFrom,ApplyDate,TrainingCode)
	 VALUES
		   ('$Title','$TrainingDescription','$StartDate','$EndDate','$ClosingDate','$TrainingFor','$fileSaveName','$GenerateFrom','$ApplyDate','$TrainingCode')";
		 
	$countSql="SELECT * FROM $tblName where TrainingCode='$TrainingCode' and StartDate='$StartDate'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		$msg="Already exist.";
	}else{ 
	
		$newID=$db->runMsSqlQueryInsert($queryGradeSave);
		//$db->runMsSqlQuery($queryGradeSave);
		$msg="Successfully Updated.";
	   //$processType = 'RequestTeacherTraining';
	   //$msg = getApproveList($processType, $newID);
	}
	
	$_SESSION['success_update']=$msg;
	header("Location:callATraining-3.html");	
	exit() ;
	
}



if($cat=='requestTeacherTraining'){
	/*  $newID=6;
	  $processType = 'RequestTeacherTraining';
	  echo  $msg = getApproveList($processType, $newID);
	   exit(); */
	$NIC=$_REQUEST['NIC']; //echo "<br>";
	$SchoolID=$_REQUEST['SchoolID'];
	$Title=addslashes($_REQUEST['Title']);//echo "<br>";
	$Description=addslashes($_REQUEST['Description']);//echo "<br>";
	$StartDate=$_REQUEST['StartDate'];//echo "<br>";
	$EndDate=$_REQUEST['EndDate'];//echo "<br>";
	$Venue=$_REQUEST['Venue'];
	$NoofSessions=$_REQUEST['NoofSessions'];
	$IsApproved="N";//$_REQUEST['IsApproved'];
	$ApplyDate=$_REQUEST['ApplyDate'];
	//echo print_r($_REQUEST['ExtraActivities']);
	//exit();
	$dte=date("ymdHms");
	$uploadpath="trainingrequestfiles";
	$fileSaveName="";
	if($_FILES['Reference']['name']!='') { //save file	
		$fileSaveName=$dte.$_FILES['Reference']['name']; 						
		$uppth2=$uploadpath."/".$fileSaveName;	
		copy ($_FILES['Reference']['tmp_name'], $uppth2);
		//$insArrCusE[$field_name]=$fileSaveName;													
	}
	
	 // exit();
	$queryGradeSave="INSERT INTO $tblName
		   (NIC,SchoolID,Title,Description,StartDate,EndDate,Venue,NoofSessions,Reference,IsApproved,ApplyDate)
	 VALUES
		   ('$NIC','$SchoolID','$Title','$Description','$StartDate','$EndDate','$Venue','$NoofSessions','$fileSaveName','$IsApproved','$ApplyDate')";
		//exit();   
	$countSql="SELECT * FROM $tblName where NIC='$NIC' and StartDate='$StartDate'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		$msg="Already exist.";
	}else{ 
	
		//$newID=$db->runMsSqlQueryInsert($queryGradeSave);
		//$db->runMsSqlQuery($queryGradeSave);
		//$msg="Successfully Updated.";
	   $processType = 'RequestTeacherTraining';
	   //$newID=6;
	   $msg = getApproveList($processType, $newID);
	   //exit();
	   if($msg!='Save successfully.'){
		   $sqlDelxx="DELETE FROM $tblName
		  WHERE ID=$newID";
		  $db->runMsSqlQuery($sqlDelxx);
	   }else{
		   $_SESSION['success_update']=$msg." Insert the training sessions.";
		   header("Location:requestTrainingSessions-2a---A-$newID.html");	
		   exit() ;
	   }
		
	}
	
	$_SESSION['success_update']=$msg;
	header("Location:requestTraining-2.html");	
	exit() ;
	
}

if($cat=='requestTeacherTrainingSessions'){
	$TrainingID=$_REQUEST['TrainingID']; //echo "<br>";
	$Title=addslashes($_REQUEST['Title']);
	$Description=addslashes($_REQUEST['Description']);//echo "<br>";
	$StartDate=$_REQUEST['StartDate'];//echo "<br>";
	$EndDate=$_REQUEST['EndDate'];//echo "<br>";
	$NoofHours=$_REQUEST['NoofHours'];
	$ApplyDate=$_REQUEST['ApplyDate'];
	
	$queryGradeSave="INSERT INTO $tblName
		   (TrainingID,Title,Description,StartDate,EndDate,NoofHours,ApplyDate)
	 VALUES
		   ('$TrainingID','$Title','$Description','$StartDate','$EndDate','$NoofHours','$ApplyDate')";
	
	$db->runMsSqlQueryInsert($queryGradeSave);
	
	$_SESSION['success_update']="Save successfully.";
    header("Location:requestTrainingSessions-2a----$TrainingID.html");	
    exit() ;
}

if($cat=='teacherQualification'){
	$NIC=$_REQUEST['NIC']; //echo "<br>";
	$QCode=$_REQUEST['QCode'];//echo "<br>";
	$Description=addslashes($_REQUEST['Description']);//echo "<br>";
	$EffectiveDate=$_REQUEST['EffectiveDate'];//echo "<br>";
	$SchoolID=$_REQUEST['SchoolID'];//echo "<br>";
	//$RequestedDate=date('Y-m-d');//echo "<br>";//$_REQUEST['RequestedDate'];
	$IsApproved="N";//$_REQUEST['IsApproved'];
	$ApplyDate=$_REQUEST['ApplyDate'];
	//echo print_r($_REQUEST['ExtraActivities']);
	//exit();
	$dte=date("ymdHms");
	$uploadpath="qualificationfiles";
	$fileSaveName="";
	if($_FILES['Reference']['name']!='') { //save file	
		$fileSaveName=$dte.$_FILES['Reference']['name']; 						
		$uppth2=$uploadpath."/".$fileSaveName;	
		copy ($_FILES['Reference']['tmp_name'], $uppth2);
		//$insArrCusE[$field_name]=$fileSaveName;													
	}
	
	 // exit();
	$queryGradeSave="INSERT INTO $tblName
		   (NIC,QCode,Description,EffectiveDate,Reference,IsApproved,ApplyDate,SchoolID)
	 VALUES
		   ('$NIC','$QCode','$Description','$EffectiveDate','$fileSaveName','$IsApproved','$ApplyDate','$SchoolID')";
		//exit();   
	$countSql="SELECT * FROM $tblName where NIC='$NIC' and EffectiveDate='$EffectiveDate'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		$msg="Already exist.";
	}else{ 
		$newID=$db->runMsSqlQueryInsert($queryGradeSave);
		//$db->runMsSqlQuery($queryGradeSave);
		//$msg="Successfully Updated.";
	   $processType = 'TeacherQualification';
	   $msg = getApproveList($processType, $newID);
	   
	   if($msg!='Save successfully.'){
		   $sqlDelxx="DELETE FROM $tblName
		  WHERE ID=$newID";
		  $db->runMsSqlQuery($sqlDelxx);
	   }
	   
	}
	
	$_SESSION['success_update']=$msg;
	header("Location:teacherQualification-1.html");	
	exit() ;
	
}

if ($status == 'D'){
	//echo $vID;
	//exit();
	if($cat=='Leave'){
		$sqlDel="DELETE FROM StaffLeaveDetail
		  WHERE ID=$vID";
		  $db->runMsSqlQuery($sqlDel);
		  
		  $sqlDelxx="DELETE FROM TG_Request_Approve
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