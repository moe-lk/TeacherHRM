<?php 
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();

$vID = $_REQUEST['vID'];
$vDes = $_REQUEST['vDes'];
$tblName = $_REQUEST['tblName'];
$mainID = $_REQUEST['mainID'];
$redirect_page = $_REQUEST['redirect_page'];
$status = $_REQUEST['AED'];	
$cat = $_REQUEST['cat'];

if($cat=='VacancyTeacherNational'){
	$ApprovedStatus=$_REQUEST['ApprovedStatus'];
	$Remarks=addslashes($_REQUEST['Remarks']);
	$ReqAppID=strip_tags($_REQUEST['ReqAppID']);
	$TransferID=$_REQUEST['TransferID'];
	$ApproveProcessOrder=$_REQUEST['ApproveProcessOrder'];
	$nextOrder=$ApproveProcessOrder+1;
	$nowDate=date('Y-m-d H:i:s');
	//exit();
$sqlInsertTT="UPDATE TG_Request_Approve
           SET ApprovedStatus='$ApprovedStatus' , Remarks='$Remarks', DateTime='$nowDate'
     WHERE RequestType='$cat' and id='$ReqAppID'";
           
		$db->runMsSqlQuery($sqlInsertTT);
		
		if($ApprovedStatus=='A'){
			$countTotal = "SELECT * FROM TG_Request_Approve where RequestType='$cat' and RequestID='$TransferID' and ApproveProcessOrder='$nextOrder'";
			$TotaRows = $db->rowCount($countTotal);
			
			if($TotaRows==0){
				$sqlInsertTTApp="UPDATE TG_TeacherVacancyNational
			   SET IsApproved='Y' WHERE ID='$TransferID'";
			}else{
			   $sqlInsertTTApp="UPDATE TG_Request_Approve
			   SET ApprovedStatus='P'
		 WHERE RequestType='$cat' and RequestID='$TransferID' and ApproveProcessOrder='$nextOrder'";
			}
           
		$db->runMsSqlQuery($sqlInsertTTApp);
		}
		
	$_SESSION['success_update']="Your Action Submited successfully.";
	header("Location:transferVacancyTeacherNational-8.html");	
    exit() ;
}

if($cat=='VacancyPrincipleNational'){
	$ApprovedStatus=$_REQUEST['ApprovedStatus'];
	$Remarks=addslashes($_REQUEST['Remarks']);
	$ReqAppID=strip_tags($_REQUEST['ReqAppID']);
	$TransferID=$_REQUEST['TransferID'];
	$ApproveProcessOrder=$_REQUEST['ApproveProcessOrder'];
	$nextOrder=$ApproveProcessOrder+1;
	$nowDate=date('Y-m-d H:i:s');
	//exit();
$sqlInsertTT="UPDATE TG_Request_Approve
           SET ApprovedStatus='$ApprovedStatus' , Remarks='$Remarks', DateTime='$nowDate'
     WHERE RequestType='$cat' and id='$ReqAppID'";
           
		$db->runMsSqlQuery($sqlInsertTT);
		
		if($ApprovedStatus=='A'){
			$countTotal = "SELECT * FROM TG_Request_Approve where RequestType='$cat' and RequestID='$TransferID' and ApproveProcessOrder='$nextOrder'";
			$TotaRows = $db->rowCount($countTotal);
			
			if($TotaRows==0){
				$sqlInsertTTApp="UPDATE TG_PrincipleVacancyNational
			   SET IsApproved='Y' WHERE ID='$TransferID'";
			}else{
			   $sqlInsertTTApp="UPDATE TG_Request_Approve
			   SET ApprovedStatus='P'
		 WHERE RequestType='$cat' and RequestID='$TransferID' and ApproveProcessOrder='$nextOrder'";
			}
           
		$db->runMsSqlQuery($sqlInsertTTApp);
		}
		
	$_SESSION['success_update']="Your Action Submited successfully.";
	header("Location:transferVacancyPrincipleNational-9.html");	
    exit() ;
}

if($cat=='VacancyPrincipleNormal'){
	$ApprovedStatus=$_REQUEST['ApprovedStatus'];
	$Remarks=addslashes($_REQUEST['Remarks']);
	$ReqAppID=strip_tags($_REQUEST['ReqAppID']);
	$TransferID=$_REQUEST['TransferID'];
	$ApproveProcessOrder=$_REQUEST['ApproveProcessOrder'];
	$nextOrder=$ApproveProcessOrder+1;
	$nowDate=date('Y-m-d H:i:s');
	//exit();
$sqlInsertTT="UPDATE TG_Request_Approve
           SET ApprovedStatus='$ApprovedStatus' , Remarks='$Remarks', DateTime='$nowDate'
     WHERE RequestType='$cat' and id='$ReqAppID'";
           
		$db->runMsSqlQuery($sqlInsertTT);
		
		if($ApprovedStatus=='A'){
			$countTotal = "SELECT * FROM TG_Request_Approve where RequestType='$cat' and RequestID='$TransferID' and ApproveProcessOrder='$nextOrder'";
			$TotaRows = $db->rowCount($countTotal);
			
			if($TotaRows==0){
				$sqlInsertTTApp="UPDATE TG_PrincipleVacancyNormal
			   SET IsApproved='Y' WHERE ID='$TransferID'";
			}else{
			   $sqlInsertTTApp="UPDATE TG_Request_Approve
			   SET ApprovedStatus='P'
		 WHERE RequestType='$cat' and RequestID='$TransferID' and ApproveProcessOrder='$nextOrder'";
			}
           
		$db->runMsSqlQuery($sqlInsertTTApp);
		}
		
	$_SESSION['success_update']="Your Action Submited successfully.";
	header("Location:transferVacancyPrincipleNormal-11.html");	
    exit() ;
}

if($cat=='VacancyTeacherNormal'){
	$ApprovedStatus=$_REQUEST['ApprovedStatus'];
	$Remarks=addslashes($_REQUEST['Remarks']);
	$ReqAppID=strip_tags($_REQUEST['ReqAppID']);
	$TransferID=$_REQUEST['TransferID'];
	$ApproveProcessOrder=$_REQUEST['ApproveProcessOrder'];
	$nextOrder=$ApproveProcessOrder+1;
	$nowDate=date('Y-m-d H:i:s');
	//exit();
$sqlInsertTT="UPDATE TG_Request_Approve
           SET ApprovedStatus='$ApprovedStatus' , Remarks='$Remarks', DateTime='$nowDate'
     WHERE RequestType='$cat' and id='$ReqAppID'";
           
		$db->runMsSqlQuery($sqlInsertTT);
		
		if($ApprovedStatus=='A'){
			$countTotal = "SELECT * FROM TG_Request_Approve where RequestType='$cat' and RequestID='$TransferID' and ApproveProcessOrder='$nextOrder'";
			$TotaRows = $db->rowCount($countTotal);
			
			if($TotaRows==0){
				$sqlInsertTTApp="UPDATE TG_TeacherVacancyNormal
			   SET IsApproved='Y' WHERE ID='$TransferID'";
			}else{
			   $sqlInsertTTApp="UPDATE TG_Request_Approve
			   SET ApprovedStatus='P'
		 WHERE RequestType='$cat' and RequestID='$TransferID' and ApproveProcessOrder='$nextOrder'";
			}
           
		$db->runMsSqlQuery($sqlInsertTTApp);
		}
		
	$_SESSION['success_update']="Your Action Submited successfully.";
	header("Location:transferVacancyTeacherNormal-10.html");	
    exit() ;
}

if($cat=='TransferTeacherNational'){
	$ApprovedStatus=$_REQUEST['ApprovedStatus'];
	$Remarks=addslashes($_REQUEST['Remarks']);
	$ReqAppID=strip_tags($_REQUEST['ReqAppID']);
	$TransferID=$_REQUEST['TransferID'];
	$ApproveProcessOrder=$_REQUEST['ApproveProcessOrder'];
	$nextOrder=$ApproveProcessOrder+1;
	$nowDate=date('Y-m-d H:i:s');
	//exit();
$sqlInsertTT="UPDATE TG_Request_Approve
           SET ApprovedStatus='$ApprovedStatus' , Remarks='$Remarks', DateTime='$nowDate'
     WHERE RequestType='$cat' and id='$ReqAppID'";
           
		$db->runMsSqlQuery($sqlInsertTT);
		
		if($ApprovedStatus=='A'){
			$countTotal = "SELECT * FROM TG_Request_Approve where RequestType='$cat' and RequestID='$TransferID' and ApproveProcessOrder='$nextOrder'";
			$TotaRows = $db->rowCount($countTotal);
			
			if($TotaRows==0){
				$sqlInsertTTApp="UPDATE TG_TeacherTransferNational
			   SET IsApproved='Y' WHERE ID='$TransferID'";
			}else{
			   $sqlInsertTTApp="UPDATE TG_Request_Approve
			   SET ApprovedStatus='P'
		 WHERE RequestType='$cat' and RequestID='$TransferID' and ApproveProcessOrder='$nextOrder'";
			}
           
		$db->runMsSqlQuery($sqlInsertTTApp);
		}
		
	$_SESSION['success_update']="Your Action Submited successfully.";
	header("Location:transferTeacherNormal-4.html");	
    exit() ;
}

if($cat=='TransferTeacherNormal'){
	$ApprovedStatus=$_REQUEST['ApprovedStatus'];
	$Remarks=$_REQUEST['Remarks'];
	$ReqAppID=strip_tags($_REQUEST['ReqAppID']);
	$TransferID=$_REQUEST['TransferID'];
	$ApproveProcessOrder=$_REQUEST['ApproveProcessOrder'];
	$nextOrder=$ApproveProcessOrder+1;
	$nowDate=date('Y-m-d H:i:s');
	//exit();
$sqlInsertTT="UPDATE TG_Request_Approve
           SET ApprovedStatus='$ApprovedStatus' , Remarks='$Remarks', DateTime='$nowDate'
     WHERE RequestType='TransferTeacherNormal' and id='$ReqAppID'";
           
		$db->runMsSqlQuery($sqlInsertTT);
		
		if($ApprovedStatus=='A'){
		   $countTotal = "SELECT * FROM TG_Request_Approve where RequestType='$cat' and RequestID='$TransferID' and ApproveProcessOrder='$nextOrder'";
			$TotaRows = $db->rowCount($countTotal);
			
			if($TotaRows==0){
				$sqlInsertTTApp="UPDATE TG_TeacherTransfer
			   SET IsApproved='Y' WHERE ID='$TransferID'";
			}else{
			   $sqlInsertTTApp="UPDATE TG_Request_Approve
			   SET ApprovedStatus='P'
		 WHERE RequestType='$cat' and RequestID='$TransferID' and ApproveProcessOrder='$nextOrder'";
			}
		   /* $sqlInsertTTApp="UPDATE TG_Request_Approve
           SET ApprovedStatus='P'
     WHERE RequestType='TransferTeacherNormal' and RequestID='$TransferID' and ApproveProcessOrder='$nextOrder'"; */
           
			$db->runMsSqlQuery($sqlInsertTTApp);
		}
		
	$_SESSION['success_update']="Your Action Submited successfully.";
	 header("Location:transferTeacherNormal-4.html");	
     exit() ;
}

if($cat=='TransferPrincipleNormal'){
	$ApprovedStatus=$_REQUEST['ApprovedStatus'];
	$Remarks=$_REQUEST['Remarks'];
	$ReqAppID=strip_tags($_REQUEST['ReqAppID']);
	$TransferID=$_REQUEST['TransferID'];
	$ApproveProcessOrder=$_REQUEST['ApproveProcessOrder'];
	$nextOrder=$ApproveProcessOrder+1;
	$nowDate=date('Y-m-d H:i:s');
	//exit();
$sqlInsertTT="UPDATE TG_Request_Approve
           SET ApprovedStatus='$ApprovedStatus' , Remarks='$Remarks', DateTime='$nowDate'
     WHERE RequestType='TransferTeacherNormal' and id='$ReqAppID'";
           
		$db->runMsSqlQuery($sqlInsertTT);
		
		if($ApprovedStatus=='A'){
		   $countTotal = "SELECT * FROM TG_Request_Approve where RequestType='$cat' and RequestID='$TransferID' and ApproveProcessOrder='$nextOrder'";
			$TotaRows = $db->rowCount($countTotal);
			
			if($TotaRows==0){
				$sqlInsertTTApp="UPDATE TG_TeacherTransfer
			   SET IsApproved='Y' WHERE ID='$TransferID'";
			}else{
			   $sqlInsertTTApp="UPDATE TG_Request_Approve
			   SET ApprovedStatus='P'
		 WHERE RequestType='$cat' and RequestID='$TransferID' and ApproveProcessOrder='$nextOrder'";
			}
		   /* $sqlInsertTTApp="UPDATE TG_Request_Approve
           SET ApprovedStatus='P'
     WHERE RequestType='TransferTeacherNormal' and RequestID='$TransferID' and ApproveProcessOrder='$nextOrder'"; */
           
			$db->runMsSqlQuery($sqlInsertTTApp);
		}
		
	$_SESSION['success_update']="Your Action Submited successfully.";
	 header("Location:transferPrincipleNormal-5.html");	
     exit() ;
}

if($cat=='TransferPrincipleNational'){
	$ApprovedStatus=$_REQUEST['ApprovedStatus'];
	$Remarks=$_REQUEST['Remarks'];
	$ReqAppID=strip_tags($_REQUEST['ReqAppID']);
	$TransferID=$_REQUEST['TransferID'];
	$ApproveProcessOrder=$_REQUEST['ApproveProcessOrder'];
	$nextOrder=$ApproveProcessOrder+1;
	$nowDate=date('Y-m-d H:i:s');
	//exit();
$sqlInsertTT="UPDATE TG_Request_Approve
           SET ApprovedStatus='$ApprovedStatus' , Remarks='$Remarks', DateTime='$nowDate'
     WHERE RequestType='TransferPrincipleNational' and id='$ReqAppID'";
           
		$db->runMsSqlQuery($sqlInsertTT);
		
		if($ApprovedStatus=='A'){
		   $countTotal = "SELECT * FROM TG_Request_Approve where RequestType='$cat' and RequestID='$TransferID' and ApproveProcessOrder='$nextOrder'";
			$TotaRows = $db->rowCount($countTotal);
			
			if($TotaRows==0){
				$sqlInsertTTApp="UPDATE TG_TeacherTransfer
			   SET IsApproved='Y' WHERE ID='$TransferID'";
			}else{
			   $sqlInsertTTApp="UPDATE TG_Request_Approve
			   SET ApprovedStatus='P'
		 WHERE RequestType='$cat' and RequestID='$TransferID' and ApproveProcessOrder='$nextOrder'";
			}
		   /* $sqlInsertTTApp="UPDATE TG_Request_Approve
           SET ApprovedStatus='P'
     WHERE RequestType='TransferTeacherNormal' and RequestID='$TransferID' and ApproveProcessOrder='$nextOrder'"; */
           
			$db->runMsSqlQuery($sqlInsertTTApp);
		}
		
	$_SESSION['success_update']="Your Action Submited successfully.";
	 header("Location:transferPrincipleNational-7.html");	
     exit() ;
}



?>