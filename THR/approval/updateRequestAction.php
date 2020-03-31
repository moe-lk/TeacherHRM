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
$NICUser = str_replace(" ", "", $_SESSION["NIC"]);
include('../activityLog.php');
if ($cat == 'services') {
    $ApprovedStatus = $_REQUEST['ApprovedStatus'];
    $Remarks = $_REQUEST['ApproveComment'];
    $ApproveID = $_REQUEST['ApID'];
    $RequestID = $_REQUEST['RequestID'];
    $RequestType = $_REQUEST['RequestType'];

    /* //exit(); */
    $nextID = $ApproveID + 1;
    $nowDate = date('Y-m-d H:i:s');
    /* //exit(); */

    $sqlInsertTT = "UPDATE TG_Approval SET ApprovedByNIC='$NICUser',ApprovedStatus='$ApprovedStatus',DateTime='$nowDate',Remarks='$Remarks' WHERE id='$ApproveID'";
    $db->runMsSqlQuery($sqlInsertTT);



    if ($ApprovedStatus != 'R') {
        $sqlCountPending = "Select ID from TG_Approval WHERE (RequestType='$RequestType') and (RequestID='$RequestID') and (id='$nextID')";
        $TotaRowsP = $db->rowCount($sqlCountPending);

        if ($TotaRowsP == 0) {
            /* 	//update data into main table - Start
              //	$sqlCopy="INSERT INTO StaffServiceHistory SELECT * FROM UP_StaffServiceHistory where ID='$RequestID'";

              //NIC,ServiceRecTypeCode,AppDate,InstCode,SecGRCode,WorkStatusCode,ServiceTypeCode,EmpTypeCode,PositionCode,Cat2003Code,Reference,LeaveEndDate,LastUpdate,UpdateBy,RecordLog */
            $sqlCopy = "SELECT NIC, CONVERT(varchar(20), AppDate, 121) AS AppDate, InstCode, ServiceRecTypeCode, 
                         SecGRCode,WorkStatusCode, ServiceTypeCode,EmpTypeCode, PositionCode, Cat2003Code, Reference,UpdateBy,RecordLog,MainHistID
FROM            UP_StaffServiceHistory WHERE        (ID = '$RequestID')"; /* // CONVERT(varchar(20), LastUpdate, 121) AS LastUpdate */
            $resED = $db->runMsSqlQuery($sqlCopy);
            $rowED = sqlsrv_fetch_array($resED, SQLSRV_FETCH_ASSOC);

            $NIC = trim($rowED['NIC']);
            $ServiceRecTypeCode = trim($rowED['ServiceRecTypeCode']);
            $AppDate = trim($rowED['AppDate']);
            $InstCode = trim($rowED['InstCode']);
            $SecGRCode = trim($rowED['SecGRCode']);
            $WorkStatusCode = trim($rowED['WorkStatusCode']);
            $ServiceTypeCode = trim($rowED['ServiceTypeCode']);
            $EmpTypeCode = trim($rowED['EmpTypeCode']);
            $PositionCode = trim($rowED['PositionCode']);
            $Cat2003Code = trim($rowED['Cat2003Code']);
            $Reference = trim($rowED['Reference']);
            $LastUpdate = date('Y-m-d H:i:s');
            $UpdateBy = trim($rowED['UpdateBy']);
            $RecordLog = trim($rowED['RecordLog']);
            $MainHistID = trim($rowED['MainHistID']);

            if ($MainHistID > 0) {
                $queryMainUpdate = "UPDATE StaffServiceHistory SET ServiceRecTypeCode='$ServiceRecTypeCode',AppDate='$AppDate',InstCode='$InstCode',SecGRCode='$SecGRCode',WorkStatusCode='$WorkStatusCode',ServiceTypeCode='$ServiceTypeCode',EmpTypeCode='$EmpTypeCode',PositionCode='$PositionCode',Cat2003Code='$Cat2003Code',Reference='$Reference',LastUpdate='$LastUpdate',UpdateBy='$UpdateBy',RecordLog='$RecordLog' WHERE ID='$MainHistID'";
                $db->runMsSqlQuery($queryMainUpdate);
                
                if($ServiceRecTypeCode=="NA01"){
                    $sqlUpdateUpTm = "UPDATE TeacherMast SET DOFA='$AppDate',DOACAT='$Cat2003Code' WHERE NIC='$NIC'";
                    $db->runMsSqlQuery($sqlUpdateUpTm);
                }
            } else {
                $queryMainSave = "INSERT INTO StaffServiceHistory			   (NIC,ServiceRecTypeCode,AppDate,InstCode,SecGRCode,WorkStatusCode,ServiceTypeCode,EmpTypeCode,PositionCode,Cat2003Code,Reference,LeaveEndDate,LastUpdate,UpdateBy,RecordLog)
				 VALUES			   ('$NIC','$ServiceRecTypeCode','$AppDate','$InstCode','$SecGRCode','$WorkStatusCode','$ServiceTypeCode','$EmpTypeCode','$PositionCode','$Cat2003Code','$Reference','','$LastUpdate','$UpdateBy','$RecordLog')";
                /* //$db->runMsSqlQuery($queryMainSave);	 */
                $db->runMsSqlQueryInsert($queryMainSave);

                $sqlCopyss = "SELECT ID FROM StaffServiceHistory WHERE (NIC = '$NIC') ORDER BY AppDate Desc"; /*  // CONVERT(varchar(20), LastUpdate, 121) AS LastUpdate */
                $resEDss = $db->runMsSqlQuery($sqlCopyss);
                $rowEDss = sqlsrv_fetch_array($resEDss, SQLSRV_FETCH_ASSOC);
                $newID = $rowEDss['ID'];

                /* //$db->runMsSqlQuery($sqlCopy);
                  //update data into main table - End
                  //echo "<br>"; */

                $sqlUpdateUpTm = "UPDATE TeacherMast SET CurServiceRef='$newID' WHERE NIC='$NIC'";
                $db->runMsSqlQuery($sqlUpdateUpTm);
            }

            $sqlUpdateUp = "UPDATE UP_StaffServiceHistory SET IsApproved='Y' WHERE ID='$RequestID'";
            $db->runMsSqlQuery($sqlUpdateUp);

            audit_trail($NIC, $_SESSION["NIC"], 'approval\updateRequestAction.php', 'Insert/Update', 'StaffServiceHistory,TeacherMast', 'Approve service info.');

            /* //$queryTmpDel = "DELETE FROM UP_StaffServiceHistory WHERE ID='$RequestID'";
              //$db->runMsSqlQuery($queryTmpDel); */
        } else {
            $sqlInsertTTApp = "UPDATE TG_Approval
			   SET ApprovedStatus='P'
		 WHERE RequestType='$RequestType' and RequestID='$RequestID' and id='$nextID'";

            $db->runMsSqlQuery($sqlInsertTTApp);
        }
    } else {
        $sqlCopy = "SELECT
	NIC
	MainHistID
FROM
	UP_StaffServiceHistory
WHERE
	(ID = '$RequestID')"; /* // CONVERT(varchar(20), LastUpdate, 121) AS LastUpdate */
        $resED = $db->runMsSqlQuery($sqlCopy);
        $rowED = sqlsrv_fetch_array($resED, SQLSRV_FETCH_ASSOC);

        $sqlUP = "UPDATE UP_StaffServiceHistory SET IsApproved='R' WHERE ID='$RequestID'";
        $db->runMsSqlQuery($sqlUP);

        $sqlUP = "UPDATE TG_Approval SET ApprovedStatus='R' WHERE RequestID='$RequestID' and ApprovedStatus='P'";
        $db->runMsSqlQuery($sqlUP);

        $NIC = trim($rowED['NIC']);
        audit_trail($NIC, $_SESSION["NIC"], 'approval\updateRequestAction.php', 'Update', 'TG_Approval', 'Reject service info.');
    }







    $_SESSION['success_update'] = "Your Action Submited successfully.";
    header("Location:updateRequestServices-22.html");
    exit();
}
?>