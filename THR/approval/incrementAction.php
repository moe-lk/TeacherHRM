<?php 
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();

include('../smservices/sms.php');

$vID = $_REQUEST['vID'];
$vDes = $_REQUEST['vDes'];
$tblName = $_REQUEST['tblName'];
$mainID = $_REQUEST['mainID'];
$redirect_page = $_REQUEST['redirect_page'];
$status = $_REQUEST['AED'];	
$cat = $_REQUEST['cat'];

if($cat=='TeacherIncrement'){
	$ApprovedStatus=$_REQUEST['ApprovedStatus'];
	$Remarks=addslashes($_REQUEST['ApproveComment']);
	$RequestID=$_REQUEST['RequestID'];
	$ApproveID=$_REQUEST['ApID'];
	$approveLevel=$_REQUEST['approveLevel'];
	$ApprovedByNIC=$_REQUEST['ApprovedByNIC'];
	$ApprovedByName=$_REQUEST['ApprovedByName'];
	$NIC=$_REQUEST['NICApply'];
	$nextOrder=$ApproveID+1;
	$nowDate=date('Y-m-d H:i:s');
	
	if($ApprovedStatus!='A' and $ApprovedStatus!='R' ){
		$_SESSION['success_update']="Please select your Action.";
		header("Location:teacherIncrementRequest-20.html");	
     	exit() ;
	}
	
	$sqlCountPending="Select id from TG_Approval WHERE RequestType='TeacherIncrement' and RequestID='$RequestID' and (ApprovedStatus='A' or ApprovedStatus='R')";
	$TotaRowsP=$db->rowCount($sqlCountPending);
	if($TotaRowsP==0)$approveLevel=1;
			
	if($approveLevel==1){
		$grpAns=",";
	$sql="SELECT * FROM [dbo].[CD_TG_IncrementQuestions] WHERE QuestionType='Teacher' ORDER BY OrderID ASC";
	$stmt = $db->runMsSqlQuery($sql);
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		
		$IDQec=$row['ID'];
		$radField="Q".$IDQec;
		$answerQ=$_REQUEST[$radField];
		$grpAns.=$IDQec."_".$answerQ.",";
	}
	$answerApprov=substr($grpAns, 0, -1);
		$sqlInsertTTApp="UPDATE TG_IncrementRequest
           SET QuecAnswers='$answerApprov' WHERE ID='$RequestID'";
           
			$db->runMsSqlQuery($sqlInsertTTApp);
	}
				
	
$sqlInsertTT="UPDATE TG_Approval
           SET ApprovedStatus='$ApprovedStatus' , Remarks='$Remarks', DateTime='$nowDate', ApprovedByNIC='$ApprovedByNIC'
     WHERE RequestType='TeacherIncrement' and id='$ApproveID'";
           
		$db->runMsSqlQuery($sqlInsertTT);
		
		if($ApprovedStatus=='A'){
		    $sqlInsertTTApp="UPDATE TG_Approval
           SET ApprovedStatus='P'
     WHERE RequestType='TeacherIncrement' and RequestID='$RequestID' and id='$nextOrder'";
           
			$db->runMsSqlQuery($sqlInsertTTApp);
			
			//sms start
			$reqTabMob="SELECT MobileTel FROM TeacherMast where NIC='$NIC'";
			$stmtMob= $db->runMsSqlQuery($reqTabMob);
			$rowMob = sqlsrv_fetch_array($stmtMob, SQLSRV_FETCH_ASSOC);
			$MobileTel = trim($rowMob['MobileTel']);
			
			$tpNumber=numberFormat($MobileTel);
	
			/* Send SMS via GOV SMS */
			$sms_content = 'Increment request approved by $ApprovedByName';
			$config = array('message' => $sms_content, 'recepient' => $tpNumber);//0779105338
			$smso = new sms();
			$result = $smso->sendsms($config,2);
			if ($result[0] == 1) {
				$statusOf="Success";
			} else if ($result[0] == 2) {
					//SMS Sent
					//echo 'ok';
					//$statusOf="Success";
			} else {
				$statusOf="Fail";
			}
			//end SMS
			if ($result[0] != 2) {
				$queryRegissms = "INSERT INTO TG_SMS (NIC,ModuleName,dDateTime,StatusOf,RecID) VALUES ('$NIC','Increment Request Approved','$dateU','$statusOf','$RequestID')";
				$db->runMsSqlQuery($queryRegissms);
			}
			
			//sms end
			
		}else{
			
			//sms start
			$reqTabMob="SELECT MobileTel FROM TeacherMast where NIC='$NIC'";
			$stmtMob= $db->runMsSqlQuery($reqTabMob);
			$rowMob = sqlsrv_fetch_array($stmtMob, SQLSRV_FETCH_ASSOC);
			$MobileTel = trim($rowMob['MobileTel']);
			
			$tpNumber=numberFormat($MobileTel);
	
			/* Send SMS via GOV SMS */
			$sms_content = 'Increment request rejected by $ApprovedByName';
			$config = array('message' => $sms_content, 'recepient' => $tpNumber);//0779105338
			$smso = new sms();
			$result = $smso->sendsms($config,2);
			if ($result[0] == 1) {
				$statusOf="Success";
			} else if ($result[0] == 2) {
					//SMS Sent
					//echo 'ok';
					//$statusOf="Success";
			} else {
				$statusOf="Fail";
			}
			//end SMS
			if ($result[0] != 2) {
				$queryRegissms = "INSERT INTO TG_SMS (NIC,ModuleName,dDateTime,StatusOf,RecID) VALUES ('$NIC','Increment Request Reject','$dateU','$statusOf','$RequestID')";
				$db->runMsSqlQuery($queryRegissms);
			}
			//sms end
			
		}
		
		$sqlCountPending="Select id from TG_Approval WHERE RequestType='TeacherIncrement' and RequestID='$RequestID' and (ApprovedStatus='P' or ApprovedStatus='R')";
		$TotaRowsP=$db->rowCount($sqlCountPending);
		if($TotaRowsP==0){
			$sqlInsertTTApp="UPDATE TG_IncrementRequest
           SET IsApproved='Y' WHERE ID='$RequestID'";
           
			$db->runMsSqlQuery($sqlInsertTTApp);
		}
		
	$_SESSION['success_update']="Your Action Submited successfully.";
	 header("Location:teacherIncrementRequest-20.html");	
     exit() ;
}

if($cat=='PrincipalIncrement'){
	$ApprovedStatus=$_REQUEST['ApprovedStatus'];
	$Remarks=$_REQUEST['ApproveComment'];
	$RequestID=$_REQUEST['RequestID'];
	$ApproveID=$_REQUEST['ApID'];
	$approveLevel=$_REQUEST['approveLevel'];
	$ApprovedByNIC=$_REQUEST['ApprovedByNIC'];
	$nextOrder=$ApproveID+1;
	$nowDate=date('Y-m-d H:i:s');
	
	$sqlCountPending="Select id from TG_Approval WHERE RequestType='PrincipalIncrement' and RequestID='$RequestID' and (ApprovedStatus='A' or ApprovedStatus='R')";
	$TotaRowsP=$db->rowCount($sqlCountPending);
	if($TotaRowsP==0)$approveLevel=1;
	
	if($approveLevel==1){
	$sql="SELECT * FROM [dbo].[CD_TG_IncrementQuestions] WHERE QuestionType='Principal' ORDER BY OrderID ASC";
	$stmt = $db->runMsSqlQuery($sql);
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		
		$IDQec=$row['ID'];
		$radField="Q".$IDQec;
		$answerQ=$_REQUEST[$radField];
		$grpAns.=$IDQec."_".$answerQ.",";
	}
	$answerApprov=substr($grpAns, 0, -1);
		$sqlInsertTTApp="UPDATE TG_IncrementRequest
           SET QuecAnswers='$answerApprov' WHERE ID='$RequestID'";
           
			$db->runMsSqlQuery($sqlInsertTTApp);
	}
				
	
$sqlInsertTT="UPDATE TG_Approval
           SET ApprovedStatus='$ApprovedStatus' , Remarks='$Remarks', DateTime='$nowDate', ApprovedByNIC='$ApprovedByNIC'
     WHERE RequestType='PrincipalIncrement' and id='$ApproveID'";
           
		$db->runMsSqlQuery($sqlInsertTT);
		
		if($ApprovedStatus=='A'){
		   $sqlInsertTTApp="UPDATE TG_Approval
           SET ApprovedStatus='P'
     WHERE RequestType='PrincipalIncrement' and RequestID='$RequestID' and ApproveProcessOrder='$nextOrder'";
           
		$db->runMsSqlQuery($sqlInsertTTApp);
		}
		
		$sqlCountPending="Select id from TG_Approval WHERE RequestType='PrincipalIncrement' and RequestID='$RequestID' and (ApprovedStatus='P' or ApprovedStatus='R')";
		$TotaRowsP=$db->rowCount($sqlCountPending);
		if($TotaRowsP==0){
			$sqlInsertTTApp="UPDATE TG_IncrementRequest
           SET IsApproved='Y' WHERE ID='$RequestID'";
           
			$db->runMsSqlQuery($sqlInsertTTApp);
		}
		
	$_SESSION['success_update']="Your Action Submited successfully.";
	 header("Location:principalIncrementRequest-21.html");	
     exit() ;
}


if($cat=='applyForTraining'){
	$ApprovedStatus=$_REQUEST['ApprovedStatus'];
	$Remarks=$_REQUEST['Remarks'];
	$ReqAppID=strip_tags($_REQUEST['ReqAppID']);
	$approveForID=$_REQUEST['approveForID'];
	$ApproveProcessOrder=$_REQUEST['ApproveProcessOrder'];
	$nextOrder=$ApproveProcessOrder+1;
	$nowDate=date('Y-m-d H:i:s');
	//exit();
$sqlInsertTT="UPDATE TG_Request_Approve
           SET ApprovedStatus='$ApprovedStatus' , Remarks='$Remarks', DateTime='$nowDate'
     WHERE RequestType='ApplyForTraining' and id='$ReqAppID'";
           
		$db->runMsSqlQuery($sqlInsertTT);
		
		if($ApprovedStatus=='A'){
		   $sqlInsertTTApp="UPDATE TG_Request_Approve
           SET ApprovedStatus='P'
     WHERE RequestType='ApplyForTraining' and RequestID='$approveForID' and ApproveProcessOrder='$nextOrder'";
           
		$db->runMsSqlQuery($sqlInsertTTApp);
		}
		
		$sqlCountPending="Select id from TG_Request_Approve WHERE RequestType='ApplyForTraining' and RequestID='$approveForID' and (ApprovedStatus='P' or ApprovedStatus='R')";
		$TotaRowsP=$db->rowCount($sqlCountPending);
		if($TotaRowsP==0){
			$sqlInsertTTApp="UPDATE TG_TeacherTrainingCallApply
           SET IsApproved='Y' WHERE ID='$approveForID'";
           
			$db->runMsSqlQuery($sqlInsertTTApp);
		}
		
	$_SESSION['success_update']="Your Action Submited successfully.";
	 header("Location:teacherRequestTraining-13.html");	
     exit() ;
}


?>