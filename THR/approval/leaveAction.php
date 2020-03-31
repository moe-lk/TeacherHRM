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

if($cat=='leave'){
	$ApprovedStatus=$_REQUEST['ApprovedStatus'];
	$Remarks=$_REQUEST['ApproveComment'];
	$ApproveID=$_REQUEST['ApID'];
	$RequestID=$_REQUEST['RequestID'];
	$RequestType=$_REQUEST['RequestType'];
//exit();
	$nextID=$ApproveID+1;
	$nowDate=date('Y-m-d H:i:s');
	//exit();
$sqlInsertTT="UPDATE TG_Approval_Leave SET ApprovedByNIC='$NICUser',ApprovedStatus='$ApprovedStatus',DateTime='$nowDate',Remarks='$Remarks' WHERE id='$ApproveID'";
           
		$db->runMsSqlQuery($sqlInsertTT);
		
		if($ApprovedStatus!='R'){
			$sqlCountPending="Select ID from TG_Approval_Leave WHERE (RequestType='$RequestType') and (RequestID='$RequestID') and (id='$nextID')";
			$TotaRowsP=$db->rowCount($sqlCountPending);
			if($TotaRowsP==0){
				//update data into main table - Start
				//$sqlCopy="INSERT INTO StaffServiceHistory SELECT * FROM UP_StaffServiceHistory where ID='$RequestID'";
				//$db->runMsSqlQuery($sqlCopy);
				//update data into main table - End
				
				$sqlUpdateUp="UPDATE TG_StaffLeave SET IsApproved='Y' WHERE ID='$RequestID'";
				$db->runMsSqlQuery($sqlUpdateUp);
				
				//$queryTmpDel = "UPDATE FROM UP_StaffServiceHistory WHERE ID='$RequestID'";
				//$db->runMsSqlQuery($queryTmpDel);
				
				//$queryTmpDel = "DELETE FROM UP_StaffServiceHistory WHERE ID='$RequestID'";
				//$db->runMsSqlQuery($queryTmpDel);
		
			}else{
			   $sqlInsertTTApp="UPDATE TG_Approval_Leave
			   SET ApprovedStatus='P'
		 WHERE RequestType='$RequestType' and RequestID='$RequestID' and id='$nextID'";
			   
				$db->runMsSqlQuery($sqlInsertTTApp);
			}
		}
		
		/*$sqlCountPending="Select ID from TG_Approval WHERE RequestType='$RequestType' and RequestID='$RequestID' and (ApprovedStatus='P' or ApprovedStatus='R' or ApprovedStatus='CR')";
		$TotaRowsP=$db->rowCount($sqlCountPending);
		if($TotaRowsP==0){
			$sqlInsertTTApp="UPDATE TG_StaffLeave
           SET IsApproved='Y' WHERE ID='$approveForID'";
           
			//$db->runMsSqlQuery($sqlInsertTTApp);
		}*/
		
	$_SESSION['success_update']="Your Action Submited successfully.";
	header("Location:leaveRequest-2.html");	
     exit() ;
}

if($cat=='leavexxx'){
	$ApprovedStatus=$_REQUEST['ApprovedStatus'];
	$Remarks=$_REQUEST['Remarks'];
	$ReqAppID=strip_tags($_REQUEST['ReqAppID']);
	$leaveID=$_REQUEST['leaveID'];
	$LeaveType=$_REQUEST['LeaveTypeT'];
	$ApproveProcessOrder=$_REQUEST['ApproveProcessOrder'];
	$nextOrder=$ApproveProcessOrder+1;
	$nowDate=date('Y-m-d H:i:s');
	//exit();
$sqlInsertTT="UPDATE TG_Request_Approve
           SET ApprovedStatus='$ApprovedStatus' , Remarks='$Remarks', DateTime='$nowDate'
     WHERE RequestType='$LeaveType' and id='$ReqAppID'";
	 
           
		$db->runMsSqlQuery($sqlInsertTT);
		
		if($ApprovedStatus=='A'){
		   $sqlInsertTTApp="UPDATE TG_Request_Approve
           SET ApprovedStatus='P'
     WHERE RequestType='$LeaveType' and RequestID='$leaveID' and ApproveProcessOrder='$nextOrder'";
           
		$db->runMsSqlQuery($sqlInsertTTApp);
		}
		
		$sqlCountPending="Select id from TG_Request_Approve WHERE RequestType='TeacherQualification' and RequestID='$approveForID' and (ApprovedStatus='P' or ApprovedStatus='R')";
		$TotaRowsP=$db->rowCount($sqlCountPending);
		if($TotaRowsP==0){
			$sqlInsertTTApp="UPDATE TG_StaffLeave
           SET IsApproved='Y' WHERE ID='$approveForID'";
           
			$db->runMsSqlQuery($sqlInsertTTApp);
		}
		
	$_SESSION['success_update']="Your Action Submited successfully.";
	 header("Location:leaveRequest-2.html");	
     exit() ;
}

?>