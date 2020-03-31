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
$_SESSION['success_update']="";

if($cat=='teacherVacancyNormalCall'){
//echo "hi";
	$Title=$_REQUEST['Title'];
	$SchoolID=$_REQUEST['SchoolID'];
	$OpenDate=$_REQUEST['OpenDate'];
	$EndDate=$_REQUEST['EndDate'];
	$VacancyDescription=$_REQUEST['VacancyDescription'];
	$GenerateFrom=$_REQUEST['GenerateFrom'];
	
	if($Title!='' and $SchoolID!=''){
	$queryGradeSave="INSERT INTO $tblName
           (Title,SchoolID,OpenDate,EndDate,VacancyDescription,GenerateFrom)
     VALUES
           ('$Title','$SchoolID','$OpenDate','$EndDate','$VacancyDescription','$GenerateFrom')";
	  
	/* $countSql="SELECT * FROM $tblNam where SchoolID='$SchoolID' and GradeID='$GradeID'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		$msg="Already exist.";
	}else{  */
		$db->runMsSqlQuery($queryGradeSave);
		$msg="Successfully Updated.";
	//}
	}else{
		$msg="Fill the compulsory fields.";
	}
	$_SESSION['success_update']=$msg;
	header("Location:teacherVacancyNormalCall-9.html");	
    exit() ;
	
	//sqlsrv_query($queryGradeSave);	
}

if($cat=='principalVacancyNormalCall'){
//echo "hi";
	$Title=$_REQUEST['Title'];
	$SchoolID=$_REQUEST['SchoolID'];
	$OpenDate=$_REQUEST['OpenDate'];
	$EndDate=$_REQUEST['EndDate'];
	$VacancyDescription=$_REQUEST['VacancyDescription'];
	$GenerateFrom=$_REQUEST['GenerateFrom'];
	
	if($Title!='' and $SchoolID!=''){
	$queryGradeSave="INSERT INTO $tblName
           (Title,SchoolID,OpenDate,EndDate,VacancyDescription,GenerateFrom)
     VALUES
           ('$Title','$SchoolID','$OpenDate','$EndDate','$VacancyDescription','$GenerateFrom')";
	  
	/* $countSql="SELECT * FROM $tblNam where SchoolID='$SchoolID' and GradeID='$GradeID'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		$msg="Already exist.";
	}else{  */
		$db->runMsSqlQuery($queryGradeSave);
		$msg="Successfully Updated.";
	//}
	}else{
		$msg="Fill the compulsory fields.";
	}
	$_SESSION['success_update']=$msg;
	header("Location:principalVacancyNormalCall-10.html");	
    exit() ;
	
	//sqlsrv_query($queryGradeSave);	
}


if($cat=='teacherVacancyNationalMaster'){
//echo "hi";
	$Title=$_REQUEST['Title'];
	$SchoolID=$_REQUEST['SchoolID'];
	$OpenDate=$_REQUEST['OpenDate'];
	$EndDate=$_REQUEST['EndDate'];
	$VacancyDescription=$_REQUEST['VacancyDescription'];
	$GenerateFrom=$_REQUEST['GenerateFrom'];
	
	if($Title!='' and $SchoolID!=''){
	$queryGradeSave="INSERT INTO $tblName
           (Title,SchoolID,OpenDate,EndDate,VacancyDescription,GenerateFrom)
     VALUES
           ('$Title','$SchoolID','$OpenDate','$EndDate','$VacancyDescription','$GenerateFrom')";
	  
	/* $countSql="SELECT * FROM $tblNam where SchoolID='$SchoolID' and GradeID='$GradeID'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		$msg="Already exist.";
	}else{  */
		$db->runMsSqlQuery($queryGradeSave);
		$msg="Successfully Updated.";
	//}
	}else{
		$msg="Fill the compulsory fields.";
	}
	$_SESSION['success_update']=$msg;
	header("Location:teacherVacancyNationalCall-7.html");	
    exit() ;
	
	//sqlsrv_query($queryGradeSave);	
}

if($cat=='principalVacancyNationalMaster'){
//echo "hi";
	$Title=$_REQUEST['Title'];
	$SchoolID=$_REQUEST['SchoolID'];
	$OpenDate=$_REQUEST['OpenDate'];
	$EndDate=$_REQUEST['EndDate'];
	$VacancyDescription=$_REQUEST['VacancyDescription'];
	$GenerateFrom=$_REQUEST['GenerateFrom'];
	
	if($Title!='' and $SchoolID!=''){
	$queryGradeSave="INSERT INTO $tblName
           (Title,SchoolID,OpenDate,EndDate,VacancyDescription,GenerateFrom)
     VALUES
           ('$Title','$SchoolID','$OpenDate','$EndDate','$VacancyDescription','$GenerateFrom')";
	  
	/* $countSql="SELECT * FROM $tblNam where SchoolID='$SchoolID' and GradeID='$GradeID'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		$msg="Already exist.";
	}else{  */
		$db->runMsSqlQuery($queryGradeSave);
		$msg="Successfully Updated.";
	//}
	}else{
		$msg="Fill the compulsory fields.";
	}
	$_SESSION['success_update']=$msg;
	header("Location:principalVacancyNationalCall-8.html");	
    exit() ;
	
	//sqlsrv_query($queryGradeSave);	
}

if($cat=='teacherVacancyNormal'){
	$NIC=$_REQUEST['NIC']; //echo "<br>";
	$VacancyMasterID=$_REQUEST['VacancyMasterID'];//echo "<br>";
	$TransferType=$_REQUEST['TransferType'];//echo "<br>";
	$ReasonForTransfer=$_REQUEST['ReasonForTransfer'];//echo "<br>";
	$ExtraActivities=$_REQUEST['ExtraActivities'];//echo "<br>";
	$RequestedDate=date('Y-m-d');//echo "<br>";//$_REQUEST['RequestedDate'];
	$IsApproved="N";//$_REQUEST['IsApproved'];
	$ServiceHistoryID=$_REQUEST['ServiceHistoryID'];
	//echo print_r($_REQUEST['ExtraActivities']);
	$cargox="";
	for($ii=0;$ii<count($_REQUEST['ExtraActivities']);$ii++) {
		$cargox.=$_REQUEST['ExtraActivities'][$ii].",";
	}
	$ExtraActivities=",".$cargox;
		//$insArrCusE["vBrandID"]=$_REQUEST['vBrandID'];
	
	 // exit();
	 $queryGradeSave="INSERT INTO $tblName
		   (NIC,VacancyMasterID,ServiceHistoryID,ApplyDate,ExtraActivities,ReasonForTransfer,IsApproved)
	 VALUES
		   ('$NIC','$VacancyMasterID','$ServiceHistoryID','$RequestedDate','$ExtraActivities','$ReasonForTransfer','$IsApproved')";
		   
	$countSql="SELECT * FROM $tblName where NIC='$NIC' and VacancyMasterID='$VacancyMasterID'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		$msg="Already exist.";
	}else{ 
		$newID=$db->runMsSqlQueryInsert($queryGradeSave);
		//$db->runMsSqlQuery($queryGradeSave);
		//$msg="Successfully Updated.";
	   $processType = 'VacancyTeacherNormal';
	   $msg = getApproveList($processType, $newID);
	}
	
	$_SESSION['success_update']=$msg;
	header("Location:teacherVacancyNormal-1a.html");	
	exit() ;
	
}


if($cat=='teacherVacancyNational'){
	$NIC=$_REQUEST['NIC']; //echo "<br>";
	$VacancyMasterID=$_REQUEST['VacancyMasterID'];//echo "<br>";
	$TransferType=$_REQUEST['TransferType'];//echo "<br>";
	$ReasonForTransfer=$_REQUEST['ReasonForTransfer'];//echo "<br>";
	$ExtraActivities=$_REQUEST['ExtraActivities'];//echo "<br>";
	$RequestedDate=date('Y-m-d');//echo "<br>";//$_REQUEST['RequestedDate'];
	$IsApproved="N";//$_REQUEST['IsApproved'];
	$ServiceHistoryID=$_REQUEST['ServiceHistoryID'];
	//echo print_r($_REQUEST['ExtraActivities']);
	$cargox="";
	for($ii=0;$ii<count($_REQUEST['ExtraActivities']);$ii++) {
		$cargox.=$_REQUEST['ExtraActivities'][$ii].",";
	}
	$ExtraActivities=",".$cargox;
		//$insArrCusE["vBrandID"]=$_REQUEST['vBrandID'];
	
	 // exit();
	 $queryGradeSave="INSERT INTO $tblName
		   (NIC,VacancyMasterID,ServiceHistoryID,ApplyDate,ExtraActivities,ReasonForTransfer,IsApproved)
	 VALUES
		   ('$NIC','$VacancyMasterID','$ServiceHistoryID','$RequestedDate','$ExtraActivities','$ReasonForTransfer','$IsApproved')";
		   
	$countSql="SELECT * FROM $tblName where NIC='$NIC' and VacancyMasterID='$VacancyMasterID'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		$msg="Already exist.";
	}else{ 
		$newID=$db->runMsSqlQueryInsert($queryGradeSave);
		//$db->runMsSqlQuery($queryGradeSave);
		//$msg="Successfully Updated.";
	   $processType = 'VacancyTeacherNational';
	   $msg = getApproveList($processType, $newID);
	}
	
	$_SESSION['success_update']=$msg;
	header("Location:teacherVacancyNational-2a.html");	
	exit() ;
	
}

if($cat=='principalVacancyNational'){
	$NIC=$_REQUEST['NIC']; //echo "<br>";
	$VacancyMasterID=$_REQUEST['VacancyMasterID'];//echo "<br>";
	$TransferType=$_REQUEST['TransferType'];//echo "<br>";
	$ReasonForTransfer=$_REQUEST['ReasonForTransfer'];//echo "<br>";
	$ExtraActivities=$_REQUEST['ExtraActivities'];//echo "<br>";
	$RequestedDate=date('Y-m-d');//echo "<br>";//$_REQUEST['RequestedDate'];
	$IsApproved="N";//$_REQUEST['IsApproved'];
	$ServiceHistoryID=$_REQUEST['ServiceHistoryID'];
	//echo print_r($_REQUEST['ExtraActivities']);
	$cargox="";
	for($ii=0;$ii<count($_REQUEST['ExtraActivities']);$ii++) {
		$cargox.=$_REQUEST['ExtraActivities'][$ii].",";
	}
	$ExtraActivities=",".$cargox;
		//$insArrCusE["vBrandID"]=$_REQUEST['vBrandID'];
	
	 // exit();
	 $queryGradeSave="INSERT INTO $tblName
		   (NIC,VacancyMasterID,ServiceHistoryID,ApplyDate,ExtraActivities,ReasonForTransfer,IsApproved)
	 VALUES
		   ('$NIC','$VacancyMasterID','$ServiceHistoryID','$RequestedDate','$ExtraActivities','$ReasonForTransfer','$IsApproved')";
		   
	$countSql="SELECT * FROM $tblName where NIC='$NIC' and VacancyMasterID='$VacancyMasterID'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		$msg="Already exist.";
	}else{ 
		$newID=$db->runMsSqlQueryInsert($queryGradeSave);
		//$db->runMsSqlQuery($queryGradeSave);
		//$msg="Successfully Updated.";
	   $processType = 'VacancyPrincipleNational';
	   $msg = getApproveList($processType, $newID);
	}
	
	$_SESSION['success_update']=$msg;
	header("Location:principalVacancyNational-4a.html");	
	exit() ;
	
}

if($cat=='principalVacancyNormal'){
	$NIC=$_REQUEST['NIC']; //echo "<br>";
	$VacancyMasterID=$_REQUEST['VacancyMasterID'];//echo "<br>";
	$TransferType=$_REQUEST['TransferType'];//echo "<br>";
	$ReasonForTransfer=$_REQUEST['ReasonForTransfer'];//echo "<br>";
	$ExtraActivities=$_REQUEST['ExtraActivities'];//echo "<br>";
	$RequestedDate=date('Y-m-d');//echo "<br>";//$_REQUEST['RequestedDate'];
	$IsApproved="N";//$_REQUEST['IsApproved'];
	$ServiceHistoryID=$_REQUEST['ServiceHistoryID'];
	//echo print_r($_REQUEST['ExtraActivities']);
	$cargox="";
	for($ii=0;$ii<count($_REQUEST['ExtraActivities']);$ii++) {
		$cargox.=$_REQUEST['ExtraActivities'][$ii].",";
	}
	$ExtraActivities=",".$cargox;
		//$insArrCusE["vBrandID"]=$_REQUEST['vBrandID'];
	
	 // exit();
	 $queryGradeSave="INSERT INTO $tblName
		   (NIC,VacancyMasterID,ServiceHistoryID,ApplyDate,ExtraActivities,ReasonForTransfer,IsApproved)
	 VALUES
		   ('$NIC','$VacancyMasterID','$ServiceHistoryID','$RequestedDate','$ExtraActivities','$ReasonForTransfer','$IsApproved')";
		   
	$countSql="SELECT * FROM $tblName where NIC='$NIC' and VacancyMasterID='$VacancyMasterID'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		$msg="Already exist.";
	}else{ 
		$newID=$db->runMsSqlQueryInsert($queryGradeSave);
		//$db->runMsSqlQuery($queryGradeSave);
		//$msg="Successfully Updated.";
	   $processType = 'VacancyPrincipleNormal';
	   $msg = getApproveList($processType, $newID);
	}
	
	$_SESSION['success_update']=$msg;
	header("Location:principalVacancyNormal-3a.html");	
	exit() ;
	
}

if($cat=='transferPrincipleNational'){
	$NIC=$_REQUEST['NIC']; //echo "<br>";
	$SchoolID=$_REQUEST['SchoolID'];//echo "<br>";
	$TransferType=$_REQUEST['TransferType'];//echo "<br>";
	$TransferRequestType=$_REQUEST['TransferRequestType'];//echo "<br>";
	$ExpectSchool=$_REQUEST['ExpectSchool'];//echo "<br>";
	$ExpectSchool2=$_REQUEST['ExpectSchool2'];
	$ExpectSchool3=$_REQUEST['ExpectSchool3'];
	$ExpectSchool4=$_REQUEST['ExpectSchool4'];
	$ExpectSchool5=$_REQUEST['ExpectSchool5'];
	$LikeToOtherSchool=$_REQUEST['LikeToOtherSchool'];//echo "<br>";
	$ReasonForTransfer=$_REQUEST['ReasonForTransfer'];//echo "<br>";
	$ExtraActivities=$_REQUEST['ExtraActivities'];//echo "<br>";
	$RequestedDate=date('Y-m-d');//echo "<br>";//$_REQUEST['RequestedDate'];
	$IsApproved="N";//$_REQUEST['IsApproved'];
	$TransferRequestTypeID=$_REQUEST['TransferRequestTypeID'];
//echo print_r($_REQUEST['ExtraActivities']);
	$cargox="";
	for($ii=0;$ii<count($_REQUEST['ExtraActivities']);$ii++) {
		$cargox.=$_REQUEST['ExtraActivities'][$ii].",";
	}
	$ExtraActivities=",".$cargox;
		//$insArrCusE["vBrandID"]=$_REQUEST['vBrandID'];
	
	 // exit();
	 $queryGradeSave="INSERT INTO $tblName
           (NIC,SchoolID,TransferType,TransferRequestType,ExpectSchool,LikeToOtherSchool,ReasonForTransfer,ExtraActivities,RequestedDate,IsApproved,TransferRequestTypeID,ExpectSchool2,ExpectSchool3,ExpectSchool4,ExpectSchool5)
     VALUES
           ('$NIC','$SchoolID','$TransferType','$TransferRequestType','$ExpectSchool','$LikeToOtherSchool','$ReasonForTransfer','$ExtraActivities','$RequestedDate','$IsApproved','$TransferRequestTypeID','$ExpectSchool2','$ExpectSchool3','$ExpectSchool4','$ExpectSchool5')";
		   
	$countSql="SELECT * FROM $tblName where NIC='$NIC' and SchoolID='$SchoolID' and ExpectSchool='$ExpectSchool'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		$msg="Already exist.";
	}else{ 
		$newID=$db->runMsSqlQueryInsert($queryGradeSave);
		//$db->runMsSqlQuery($queryGradeSave);
		//$msg="Successfully Updated.";
       $processType = 'TransferPrincipleNational';
       $msg = getApproveList($processType, $newID);
	}
	
	$_SESSION['success_update']=$msg;
	header("Location:transferPrincipleNational-4.html");	
    exit() ;
}

if($cat=='transferTeacherNational'){
	$NIC=$_REQUEST['NIC']; //echo "<br>";
	$SchoolID=$_REQUEST['SchoolID'];//echo "<br>";
	$ServiceHistoryID=$_REQUEST['ServiceHistoryID'];//echo "<br>";
	$TransferType=$_REQUEST['TransferType'];//echo "<br>";
	$TransferRequestType=$_REQUEST['TransferRequestType'];//echo "<br>";
	$ExpectSchool=$_REQUEST['ExpectSchool'];//echo "<br>";
	$ExpectSchool2=$_REQUEST['ExpectSchool2'];
	$ExpectSchool3=$_REQUEST['ExpectSchool3'];
	$ExpectSchool4=$_REQUEST['ExpectSchool4'];
	$ExpectSchool5=$_REQUEST['ExpectSchool5'];
	$LikeToOtherSchool=$_REQUEST['LikeToOtherSchool'];//echo "<br>";
	$ReasonForTransfer=$_REQUEST['ReasonForTransfer'];//echo "<br>";
	$ExtraActivities=$_REQUEST['ExtraActivities'];//echo "<br>";
	$RequestedDate=date('Y-m-d');//echo "<br>";//$_REQUEST['RequestedDate'];
	$IsApproved="N";//$_REQUEST['IsApproved'];
	$TransferRequestTypeID=$_REQUEST['TransferRequestTypeID'];
//echo print_r($_REQUEST['ExtraActivities']);
	$cargox="";
	for($ii=0;$ii<count($_REQUEST['ExtraActivities']);$ii++) {
		$cargox.=$_REQUEST['ExtraActivities'][$ii].",";
	}
	$ExtraActivities=",".$cargox;
		//$insArrCusE["vBrandID"]=$_REQUEST['vBrandID'];
	
	 // exit();
	 $queryGradeSave="INSERT INTO $tblName
           (NIC,SchoolID,TransferType,TransferRequestType,ExpectSchool,LikeToOtherSchool,ReasonForTransfer,ExtraActivities,RequestedDate,IsApproved,TransferRequestTypeID,ExpectSchool2,ExpectSchool3,ExpectSchool4,ExpectSchool5,ServiceHistoryID)
     VALUES
           ('$NIC','$SchoolID','$TransferType','$TransferRequestType','$ExpectSchool','$LikeToOtherSchool','$ReasonForTransfer','$ExtraActivities','$RequestedDate','$IsApproved','$TransferRequestTypeID','$ExpectSchool2','$ExpectSchool3','$ExpectSchool4','$ExpectSchool5','$ServiceHistoryID')";
	
	if($status=='E'){
		$sqlInsertTT="UPDATE $tblName
           SET NIC='$NIC' , SchoolID='$SchoolID', TransferType='$TransferType', TransferRequestType='$TransferRequestType', ExpectSchool='$ExpectSchool', LikeToOtherSchool='$LikeToOtherSchool', ReasonForTransfer='$ReasonForTransfer', ExtraActivities='$ExtraActivities', RequestedDate='$RequestedDate', IsApproved='$IsApproved', TransferRequestTypeID='$TransferRequestTypeID', ExpectSchool2='$ExpectSchool2', ExpectSchool3='$ExpectSchool3', ExpectSchool4='$ExpectSchool4', ExpectSchool5='$ExpectSchool5'
     WHERE id='$vID'";
           
		$db->runMsSqlQuery($sqlInsertTT);
	}else{
		$countSql="SELECT * FROM $tblName where NIC='$NIC' and SchoolID='$SchoolID' and ExpectSchool='$ExpectSchool'";
		$isAvailable=$db->rowAvailable($countSql);
		if($isAvailable==1){
			$msg="Already exist.";
		}else{ 
			$newID=$db->runMsSqlQueryInsert($queryGradeSave);
			//$db->runMsSqlQuery($queryGradeSave);
			//$msg="Successfully Updated.";
		   $processType = 'TransferTeacherNational';
		   $msg = getApproveList($processType, $newID);
		}
	}
	
	$_SESSION['success_update']=$msg;
	header("Location:teacherRequestNational-2.html");	
    exit() ;
}

if($cat=='transferPrinciple'){
	
		$NIC=$_REQUEST['NIC']; //echo "<br>";
		$SchoolID=$_REQUEST['SchoolID'];//echo "<br>";
		$TransferType=$_REQUEST['TransferType'];//echo "<br>";
		$TransferRequestType=$_REQUEST['TransferRequestType'];//echo "<br>";
		$ExpectSchool=$_REQUEST['ExpectSchool'];//echo "<br>";
		$LikeToOtherSchool=$_REQUEST['LikeToOtherSchool'];//echo "<br>";
		$ReasonForTransfer=$_REQUEST['ReasonForTransfer'];//echo "<br>";
		$ExtraActivities=$_REQUEST['ExtraActivities'];//echo "<br>";
		$RequestedDate=date('Y-m-d');//echo "<br>";//$_REQUEST['RequestedDate'];
		$IsApproved="N";//$_REQUEST['IsApproved'];
		$TransferRequestTypeID=$_REQUEST['TransferRequestTypeID'];
		$ServiceHistoryID=$_REQUEST['ServiceHistoryID'];
//echo print_r($_REQUEST['ExtraActivities']);
		$cargox="";
		for($ii=0;$ii<count($_REQUEST['ExtraActivities']);$ii++) {
			$cargox.=$_REQUEST['ExtraActivities'][$ii].",";
		}
		$ExtraActivities=",".$cargox;
		//$insArrCusE["vBrandID"]=$_REQUEST['vBrandID'];
	
	 // exit();
	  $queryGradeSave="INSERT INTO $tblName
           (NIC,SchoolID,TransferType,TransferRequestType,ExpectSchool,LikeToOtherSchool,ReasonForTransfer,ExtraActivities,RequestedDate,IsApproved,TransferRequestTypeID,ServiceHistoryID)
     VALUES
           ('$NIC','$SchoolID','$TransferType','$TransferRequestType','$ExpectSchool','$LikeToOtherSchool','$ReasonForTransfer','$ExtraActivities','$RequestedDate','$IsApproved','$TransferRequestTypeID','$ServiceHistoryID')";
		   //exit();
	$countSql="SELECT * FROM $tblName where NIC='$NIC' and SchoolID='$SchoolID' and ExpectSchool='$ExpectSchool'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		$msg="Already exist.";
	}else{ 
		$newID=$db->runMsSqlQueryInsert($queryGradeSave);
		//$db->runMsSqlQuery($queryGradeSave);
		//$msg="Successfully Updated.";
       $processType = 'TransferPrincipleNormal';
       $msg = getApproveList($processType, $newID);
	}
	
	$_SESSION['success_update']=$msg;
	header("Location:principalRequest-3.html");	
    exit() ; 
}

if($cat=='transferTeacher'){
	
		$NIC=$_REQUEST['NIC']; //echo "<br>";
		$SchoolID=$_REQUEST['SchoolID'];//echo "<br>";
		$TransferType=$_REQUEST['TransferType'];//echo "<br>";
		$TransferRequestType=$_REQUEST['TransferRequestType'];//echo "<br>";
		$ExpectSchool=$_REQUEST['ExpectSchool'];//echo "<br>";
		$LikeToOtherSchool=$_REQUEST['LikeToOtherSchool'];//echo "<br>";
		$ReasonForTransfer=$_REQUEST['ReasonForTransfer'];//echo "<br>";
		$ExtraActivities=$_REQUEST['ExtraActivities'];//echo "<br>";
		$RequestedDate=date('Y-m-d');//echo "<br>";//$_REQUEST['RequestedDate'];
		$IsApproved="N";//$_REQUEST['IsApproved'];
		$TransferRequestTypeID=$_REQUEST['TransferRequestTypeID'];
		$ServiceHistoryID=$_REQUEST['ServiceHistoryID'];
//echo print_r($_REQUEST['ExtraActivities']);
		$cargox="";
		for($ii=0;$ii<count($_REQUEST['ExtraActivities']);$ii++) {
			$cargox.=$_REQUEST['ExtraActivities'][$ii].",";
		}
		$ExtraActivities=",".$cargox;
		//$insArrCusE["vBrandID"]=$_REQUEST['vBrandID'];
	
	 // exit();
	  $queryGradeSave="INSERT INTO $tblName
           (NIC,SchoolID,TransferType,TransferRequestType,ExpectSchool,LikeToOtherSchool,ReasonForTransfer,ExtraActivities,RequestedDate,IsApproved,TransferRequestTypeID,ServiceHistoryID)
     VALUES
           ('$NIC','$SchoolID','$TransferType','$TransferRequestType','$ExpectSchool','$LikeToOtherSchool','$ReasonForTransfer','$ExtraActivities','$RequestedDate','$IsApproved','$TransferRequestTypeID','$ServiceHistoryID')";
		   //exit();
	$countSql="SELECT * FROM $tblName where NIC='$NIC' and SchoolID='$SchoolID' and ExpectSchool='$ExpectSchool'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		$msg="Already exist.";
	}else{ 
		$newID=$db->runMsSqlQueryInsert($queryGradeSave);
		//$db->runMsSqlQuery($queryGradeSave);
		//$msg="Successfully Updated.";
       $processType = 'TransferTeacherNormal';
       $msg = getApproveList($processType, $newID);
	}
	
	$_SESSION['success_update']=$msg;
	header("Location:teacherTransferRequest-1.html");	
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