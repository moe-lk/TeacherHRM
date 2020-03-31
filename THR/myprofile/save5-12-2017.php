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
$field_name = "AttachFile";
/* echo $_FILES[$field_name]['name']; echo "hi";
  exit(); */
$_SESSION['success_update'] = "";

$updateBy = trim($_SESSION["NIC"]);

include('../activityLog.php');

if ($status == 'special') {
    if ($cat == 'familyinfo') {
        $fmRec = $_REQUEST['fmRec']; /* echo "-<br>"; */
        $menuRec = $_REQUEST['menuRec']; /* echo "-<br>"; */
        $NICUser = $_REQUEST['nicSelected']; /* echo "-<br>"; */

        $familiInfoMainStatus = $_REQUEST['familiInfoMainStatus'];
        $curAddStatus = $_REQUEST['curAddStatus'];
        /* CivilStatusCode , SpouseNIC, SpouseName, SpouseDOB, SpouseOccupationCode, SpouseOfficeAddr
          $AddrType = $_REQUEST['AddrType']; */
        $CivilStatusCode = $_REQUEST['CivilStatusCode'];
        $SpouseNIC = $_REQUEST['SpouseNIC'];
        $SpouseName = $_REQUEST['SpouseName'];
        $SpouseDOB = $_REQUEST['SpouseDOB'];
        $SpouseOccupationCode = $_REQUEST['SpouseOccupationCode'];
        $SpouseOfficeAddr = $_REQUEST['SpouseOfficeAddr'];
        $AppDate = date('Y-m-d H:i:s');
        $LastUpdate = date('Y-m-d H:i:s');
        /* $UpdateBy = $_REQUEST['DSCode'];
          $RecordLog = $_REQUEST['DSCode']; */
        $msg = "";

        // get profile user current service ref number and zone code
        $sqlServiceRef = "SELECT
	TeacherMast.CurServiceRef,
	CD_CensesNo.ZoneCode
FROM
	StaffServiceHistory
INNER JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
INNER JOIN TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
WHERE
	(TeacherMast.NIC = '$NICUser')";
        $stmtCAllready = $db->runMsSqlQuery($sqlServiceRef);
        $rowAllready = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC);
        $CurServiceRef = trim($rowAllready['CurServiceRef']);
        $ZoneCode = trim($rowAllready['ZoneCode']);


        $sqlCAllready = "SELECT * FROM TG_EmployeeUpdateFamilyInfo WHERE NIC='$NICUser' and IsApproved='N'";
        $stmtCAllready = $db->runMsSqlQuery($sqlCAllready);
        $rowAllready = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC);
        $TeacherMastIDA = trim($rowAllready['TeacherMastID']);

        if ($CivilStatusCode == "") {
            $msg .= "Civil status.<br>";
        }
        if ($CivilStatusCode == 2 || $CivilStatusCode == 3) {
            if ($SpouseNIC == "") {
                $msg .= "Spouse NIC.<br>";
            }
            if ($SpouseName == "") {
                $msg .= "Spouse full name.<br>";
            }
        }

        if ($msg == '') {
            if ($TeacherMastIDA == '') {/* $familiInfoMainStatus=='Add'){ */
                $queryMainSave = "INSERT INTO UP_TeacherMast
				   (NIC,CivilStatusCode,SpouseName,SpouseNIC,SpouseOccupationCode,SpouseDOB,SpouseOfficeAddr,LastUpdate,UpdateBy,RecordLog)
			 VALUES
				   ('$NICUser','$CivilStatusCode','$SpouseName','$SpouseNIC','$SpouseOccupationCode','$SpouseDOB','$SpouseOfficeAddr','$LastUpdate','$updateBy','change')";
                /* $db->runMsSqlQuery($queryMainSave);	 */
                $db->runMsSqlQuery($queryMainSave);

                $reqTabMobAc = "SELECT ID FROM UP_TeacherMast where NIC='$NICUser' and SurnameWithInitials IS NUll ORDER BY ID DESC";
                $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
                $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
                $TeacherMastID = trim($rowMobAc['ID']);
            } else {/* if($familiInfoMainStatus=='Update'){ */
                $queryMainUpdate = "UPDATE UP_TeacherMast SET CivilStatusCode='$CivilStatusCode',SpouseName='$SpouseName',SpouseNIC='$SpouseNIC',SpouseOccupationCode='$SpouseOccupationCode',SpouseDOB='$SpouseDOB',SpouseOfficeAddr='$SpouseOfficeAddr',LastUpdate='$LastUpdate',UpdateBy='$updateBy',RecordLog='Edit record' WHERE ID='$TeacherMastIDA'";

                $db->runMsSqlQuery($queryMainUpdate);
                $TeacherMastID = $TeacherMastIDA;
            }
        }

        if ($msg == '') {
            $isAvailable = $db->rowAvailable($sqlCAllready);
            if ($isAvailable == 1) {

                $queryMainUpdate = "UPDATE TG_EmployeeUpdateFamilyInfo SET TeacherMastID='$TeacherMastID',dDateTime='$LastUpdate',ZoneCode='$ZoneCode',IsApproved='N',ApproveDate='',ApprovedBy='',UpdateBy='$updateBy' WHERE NIC='$NICUser' and IsApproved='N'";
                $db->runMsSqlQuery($queryMainUpdate);
                
                audit_trail($NICUser, $_SESSION["NIC"], 'teacherprofile\familyInfo.php', 'Update', 'UP_TeacherMast', 'Update family info.');
            } else {

                $queryRegis = "INSERT INTO TG_EmployeeUpdateFamilyInfo				   (NIC,TeacherMastID,dDateTime,ZoneCode,IsApproved,ApproveDate,ApprovedBy,UpdateBy)
				 VALUES				   
			('$NICUser','$TeacherMastID','$LastUpdate','$ZoneCode','N','','','$updateBy')";
                $db->runMsSqlQuery($queryRegis);
                
                audit_trail($NICUser, $_SESSION["NIC"], 'teacherprofile\familyInfo.php', 'Insert', 'UP_TeacherMast', 'Insert family info.');
            }

            $_SESSION['success_update'] = "Your update request submitted successfully. Data will be displaying after the approvals.";
            header("Location:familyInfo-2--$NICUser.html");
            exit();
        }
        if ($msg) {
            $_SESSION['success_update'] = "Error(s) on the page. Please fill the, <br>" . "" . $msg;

            header("Location:familyInfo-2-$menuRec-$NICUser.html");

            exit();
        }
    }

    if ($cat == 'services') {

        $fmRec = $_REQUEST['fmRec']; /* echo "-<br>"; */
        $menuRec = $_REQUEST['menuRec']; /* echo "-<br>"; */
        $NICUser = $_REQUEST['nicSelected']; /* echo "-<br>"; */
        
        $ServiceRecTypeCode = $_REQUEST['ServiceRecTypeCode'];
        $ProCode = $_REQUEST['ProCode'];
        $DistrictCode = $_REQUEST['DistrictCode'];
        $ZoneCode = $_REQUEST['ZoneCode'];
        $DivisionCode = $_REQUEST['DivisionCode'];
        $InstCode = $_REQUEST['InstCode'];
        $Reference = $_REQUEST['Reference'];
        $SecGRCode = $_REQUEST['SecGRCode'];
        $PositionCode = $_REQUEST['PositionCode'];
        $Cat2003Code = $_REQUEST['Cat2003Code'];
        $ServiceTypeCode = $_REQUEST['ServiceTypeCode'];
        $AppDate = $_REQUEST['AppDate'];

        $msg1 = "";
        if (!$ServiceRecTypeCode)
            $msg1 .= "* Employment Basis<br>";
        if (!$InstCode)
            $msg1 .= "* School<br>";
        if (!$SecGRCode)
            $msg1 .= "* Section/Grade<br>";
        if (!$PositionCode)
            $msg1 .= "* Position<br>";
        if (!$AppDate)
            $msg1 .= "* Date of Appoinment<br>";
        if (!$ServiceTypeCode)
            $msg1 .= "* Service Category<br>";
        if (!$Cat2003Code)
            $msg1 .= "* 2003/38 Circular Category<br>";

        if ($msg1) {
            $_SESSION['success_update'] = "Error(s) on the page. Please fill the, <br>" . "" . $msg1;

            if ($fmRec == '') {
                header("Location:services-8-$menuRec-$NICUser.html");
            } else {
                header("Location:services-8-$menuRec-$NICUser-$fmRec.html");
            }
            /*   redirect("reservation_customer_info-54-4_1--E--104.html"); */
            exit();
        }


        $LastUpdate = date('Y-m-d H:i:s');
        $RecStatus = "0";

        $sql = "SELECT StaffServiceHistory.InstCode, TeacherMast.NIC FROM  TeacherMast LEFT JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID WHERE (TeacherMast.NIC = N'$NICUser')";
        $stmt = $db->runMsSqlQuery($sql);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $InstCodeCurrent = trim($row['InstCode']);


        $sqlCenseQ = "SELECT        CD_CensesNo.DistrictCode, CD_CensesNo.ZoneCode, CD_CensesNo.DivisionCode, CD_Districts.ProCode
	FROM            CD_CensesNo LEFT JOIN
							 CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
	WHERE        (CD_CensesNo.CenCode = '$InstCodeCurrent')";

        $resABCq = $db->runMsSqlQuery($sqlCenseQ);
        $rowABCq = sqlsrv_fetch_array($resABCq, SQLSRV_FETCH_ASSOC);
        $InstitutionName = $rowABCq['InstitutionName'];
        $DistrictCodex = trim($rowABCq['DistrictCode']);
        $ZoneCodex = trim($rowABCq['ZoneCode']);
        $DivisionCodex = trim($rowABCq['DivisionCode']);
        $ProCodex = trim($rowABCq['ProCode']);

        /* 	exit(); */
        $sqlUserAcc = "SELECT   Passwords.AccessLevel
	FROM            TeacherMast INNER JOIN
							 Passwords ON TeacherMast.NIC = Passwords.NICNo
							 WHERE (TeacherMast.NIC='$NICUser')";
        $resuacc = $db->runMsSqlQuery($sqlUserAcc);
        $rowuacc = sqlsrv_fetch_array($resuacc, SQLSRV_FETCH_ASSOC);
        $AccessLevel = $rowuacc['AccessLevel'];
//exit();

        if ($fmRec == '') {/* $perAddStatus=='Add' */
            $queryMainSave = "INSERT INTO UP_StaffServiceHistory			   (NIC,ServiceRecTypeCode,AppDate,InstCode,SecGRCode,WorkStatusCode,ServiceTypeCode,EmpTypeCode,PositionCode,Cat2003Code,Reference,LeaveEndDate,LastUpdate,UpdateBy,RecordLog,MainHistID,IsApproved)
			 VALUES
				   ('$NICUser','$ServiceRecTypeCode','$AppDate','$InstCode','$SecGRCode','','$ServiceTypeCode','','$PositionCode','$Cat2003Code','$Reference','','$LastUpdate','$updateBy','update by $updateBy','0','N')";
            $db->runMsSqlQuery($queryMainSave);
            /* $newHisID=$db->runMsSqlQueryInsert($queryMainSave); */

            $reqTabMobAc = "SELECT ID FROM UP_StaffServiceHistory where NIC='$NICUser' ORDER BY ID DESC";
            $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
            $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
            $newHisID = trim($rowMobAc['ID']);

            audit_trail($NICUser, $_SESSION["NIC"], 'teacherprofile\service.php', 'Insert', 'UP_StaffServiceHistory,TG_Approval', 'Insert user services.');

            $_SESSION['success_update'] = "Your update request submitted successfully for the higher level approvals"; /* .Data will be displaying after the approvals."; Data will be displaying after the approvals."; */
        } else {/* if($perAddStatus=='Update'){ */
            if ($menuRec == 'U') {
                $queryMainUpdate = "INSERT INTO UP_StaffServiceHistory			   (NIC,ServiceRecTypeCode,AppDate,InstCode,SecGRCode,WorkStatusCode,ServiceTypeCode,EmpTypeCode,PositionCode,Cat2003Code,Reference,LeaveEndDate,LastUpdate,UpdateBy,RecordLog,MainHistID,IsApproved)
			 VALUES
				   ('$NICUser','$ServiceRecTypeCode','$AppDate','$InstCode','$SecGRCode','','$ServiceTypeCode','','$PositionCode','$Cat2003Code','$Reference','','$LastUpdate','$updateBy','update by $updateBy','$fmRec','N')";
                $db->runMsSqlQuery($queryMainUpdate);

                $reqTabMobAc = "SELECT ID FROM UP_StaffServiceHistory where NIC='$NICUser' ORDER BY ID DESC";
                $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
                $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
                $newHisID = trim($rowMobAc['ID']);
                
                audit_trail($NICUser, $_SESSION["NIC"], 'teacherprofile\service.php', 'Insert', 'UP_StaffServiceHistory,TG_Approval', 'Insert user services.');
            } else {
                $queryMainUpdate = "UPDATE UP_StaffServiceHistory SET ServiceRecTypeCode='$ServiceRecTypeCode',AppDate='$AppDate',InstCode='$InstCode',SecGRCode='$SecGRCode',WorkStatusCode='$WorkStatusCode',ServiceTypeCode='$ServiceTypeCode',EmpTypeCode='$EmpTypeCode',PositionCode='$PositionCode',Cat2003Code='$Cat2003Code',Reference='$Reference',LastUpdate='$LastUpdate',UpdateBy='$updateBy',RecordLog='Edit record',IsApproved='N' WHERE ID='$fmRec'";
                $db->runMsSqlQuery($queryMainUpdate);
                $newHisID = $fmRec;

                $delQu = "DELETE From TG_Approval where RequestID='$newHisID' and RequestType='ServiceUpdate'";
                $db->runMsSqlQuery($delQu);
                
                audit_trail($NICUser, $_SESSION["NIC"], 'teacherprofile\service.php', 'Update', 'UP_StaffServiceHistory,TG_Approval', 'Update pending user services.');
            }

            $_SESSION['success_update'] = "Your update request submitted successfully for the higher level approvals";
        }

        $DateTime = date('Y-m-d H:i:s');
        $queryMainSave = "INSERT INTO TG_Approval			   (RequestType,RequestID,ApproveInstCode,ApproveDesignationCode,ApproveDesignationNominiCode,ApprovedStatus,ApprovedByNIC,DateTime,Remarks)
				 VALUES
					   ('ServiceUpdate','$newHisID','','','','RQ','$updateBy','$DateTime','')";
        /* $db->runMsSqlQuery($queryMainSave);	 */
        $db->runMsSqlQuery($queryMainSave);

        if ($ServiceRecTypeCode == 'NA01') {
            $RequestType = "ServiceUpdate";
            $RequestID = $newHisID;
            $ApproveInstCode = $InstCodeCurrent;
            $ApproveDesignationCode = 3000;
            $ApproveDesignationNominiCode = 3000;
            $ApprovedStatus = "P";
            $ApprovedByNIC = "";

            $DateTime = date('Y-m-d H:i:s');

            /*   commented by Duminda, based on Dehiowita zone request, Mr. Nandasiri, UOM workshop 2016-09-26 skp principal $ApprovedStatus */
            if ($AccessLevel == '1000') {
                $Remarks = "ManualUpdate";
                $queryMainSave = "INSERT INTO TG_Approval			   (RequestType,RequestID,ApproveInstCode,ApproveDesignationCode,ApproveDesignationNominiCode,ApprovedStatus,ApprovedByNIC,DateTime,Remarks)
					 VALUES
						   ('$RequestType','$RequestID','$ApproveInstCode','$ApproveDesignationCode','$ApproveDesignationNominiCode','Y','$ApprovedByNIC','$DateTime','$Remarks')";
                /* $db->runMsSqlQuery($queryMainSave);	 */
                $db->runMsSqlQueryInsert($queryMainSave);
                /*  $ApprovedStatus=""; */
            }

            $ApproveInstCode = $ZoneCode;
            $ApproveDesignationCode = 11050;
            $ApproveDesignationNominiCode = 11000;
            $DateTime = $Remarks = "";

            $queryMainSave = "INSERT INTO TG_Approval			   (RequestType,RequestID,ApproveInstCode,ApproveDesignationCode,ApproveDesignationNominiCode,ApprovedStatus,ApprovedByNIC,DateTime,Remarks)
					 VALUES
						   ('$RequestType','$RequestID','$ApproveInstCode','$ApproveDesignationCode','$ApproveDesignationNominiCode','$ApprovedStatus','$ApprovedByNIC','$DateTime','$Remarks')";
            /* $db->runMsSqlQuery($queryMainSave);	 */
            $db->runMsSqlQuery($queryMainSave);
        } else {
            if ($ZoneCode == $ZoneCodex) {
                /*
                 *
                 * 
                 */
                $sqlAC = "SELECT
TG_ServiceApprovalCycle.TransferType,
TG_ServiceApprovalCycle.AccessRoleValueNomini,
TG_ServiceApprovalCycle.AccessRoleValueCoordinator,
TG_ServiceApprovalCycle.AccessRoleType,
TG_ServiceApprovalCycle.Status
FROM
TG_ServiceApprovalCycle
WHERE
TG_ServiceApprovalCycle.TransferType = 'ZZ'";
                $stmtAC = $db->runMsSqlQuery($sqlAC);
                while ($rowAC = sqlsrv_fetch_array($stmtAC, SQLSRV_FETCH_ASSOC)) {
                    $RequestType = "ServiceUpdate";
                    $RequestID = $newHisID;
                    $ApproveDesignationCode = $rowAC['AccessRoleValueCoordinator'];
                    $ApproveDesignationNominiCode = $rowAC['AccessRoleValueNomini'];
                    $ApprovedByNIC = "";

                    if ($rowAC['AccessRoleType'] == "PC") {
                        // for principle system update
                        if ($AccessLevel == '1000') {
                            $ApproveInstCode = $InstCodeCurrent;
                            $ApprovedStatus = 'Y';
                            $DateTime = date('Y-m-d H:i:s');
                            $Remarks = "ManualUpdate";

                            $queryMainSave = "INSERT INTO TG_Approval (
	RequestType,
	RequestID,
	ApproveInstCode,
	ApproveDesignationCode,
	ApproveDesignationNominiCode,
	ApprovedStatus,
	ApprovedByNIC,
	DateTime,
	Remarks
)
VALUES
	(
		'$RequestType',
		'$RequestID',
		'$ApproveInstCode',
		'$ApproveDesignationCode',
		'$ApproveDesignationNominiCode',
		'$ApprovedStatus',
		'$ApprovedByNIC',
		'$DateTime',
		'$Remarks'
	)";
                            $db->runMsSqlQueryInsert($queryMainSave);
                        }
                    } else {
                        $ApproveInstCode = $ZoneCodex;
                        $ApprovedStatus = "P";
                        $DateTime = '';
                        $Remarks = '';

                        $queryMainSave = "INSERT INTO TG_Approval (
	RequestType,
	RequestID,
	ApproveInstCode,
	ApproveDesignationCode,
	ApproveDesignationNominiCode,
	ApprovedStatus,
	ApprovedByNIC,
	DateTime,
	Remarks
)
VALUES
	(
		'$RequestType',
		'$RequestID',
		'$ApproveInstCode',
		'$ApproveDesignationCode',
		'$ApproveDesignationNominiCode',
		'$ApprovedStatus',
		'$ApprovedByNIC',
		'$DateTime',
		'$Remarks'
	)";
                        $db->runMsSqlQueryInsert($queryMainSave);
                    }
                }
                /*
                 * 
                 */
            }
            /* and $fm=='DAD' */
            if ($ZoneCode != $ZoneCodex and $ProCode == $ProCodex) {
                /*
                 *
                 * 
                 */
                $sqlAC = "SELECT
TG_ServiceApprovalCycle.TransferType,
TG_ServiceApprovalCycle.AccessRoleValueNomini,
TG_ServiceApprovalCycle.AccessRoleValueCoordinator,
TG_ServiceApprovalCycle.AccessRoleType,
TG_ServiceApprovalCycle.Status
FROM
TG_ServiceApprovalCycle
WHERE
TG_ServiceApprovalCycle.TransferType = 'PZ'";
                $stmtAC = $db->runMsSqlQuery($sqlAC);
                while ($rowAC = sqlsrv_fetch_array($stmtAC, SQLSRV_FETCH_ASSOC)) {
                    $RequestType = "ServiceUpdate";
                    $RequestID = $newHisID;
                    $ApproveDesignationCode = $rowAC['AccessRoleValueCoordinator'];
                    $ApproveDesignationNominiCode = $rowAC['AccessRoleValueNomini'];
                    $ApprovedByNIC = "";

                    if ($rowAC['AccessRoleType'] == "PC") {
                        // for principle system update
                        if ($AccessLevel == '1000') {
                            $ApproveInstCode = $InstCodeCurrent;
                            $ApprovedStatus = 'Y';
                            $DateTime = date('Y-m-d H:i:s');
                            $Remarks = "ManualUpdate";

                            $queryMainSave = "INSERT INTO TG_Approval (
	RequestType,
	RequestID,
	ApproveInstCode,
	ApproveDesignationCode,
	ApproveDesignationNominiCode,
	ApprovedStatus,
	ApprovedByNIC,
	DateTime,
	Remarks
)
VALUES
	(
		'$RequestType',
		'$RequestID',
		'$ApproveInstCode',
		'$ApproveDesignationCode',
		'$ApproveDesignationNominiCode',
		'$ApprovedStatus',
		'$ApprovedByNIC',
		'$DateTime',
		'$Remarks'
	)";
                            $db->runMsSqlQueryInsert($queryMainSave);
                        }
                    } else {
                        if ($rowAC['Status'] == 'N') {
                            $ApproveInstCode = $ZoneCode;
                        } else {
                            $ApproveInstCode = $ZoneCodex;
                        }
                        $ApprovedStatus = "P";
                        $DateTime = '';
                        $Remarks = '';

                        $queryMainSave = "INSERT INTO TG_Approval (
	RequestType,
	RequestID,
	ApproveInstCode,
	ApproveDesignationCode,
	ApproveDesignationNominiCode,
	ApprovedStatus,
	ApprovedByNIC,
	DateTime,
	Remarks
)
VALUES
	(
		'$RequestType',
		'$RequestID',
		'$ApproveInstCode',
		'$ApproveDesignationCode',
		'$ApproveDesignationNominiCode',
		'$ApprovedStatus',
		'$ApprovedByNIC',
		'$DateTime',
		'$Remarks'
	)";
                        $db->runMsSqlQueryInsert($queryMainSave);
                    }
                }
                /*
                 * 
                 */
            }

            if ($ProCode != $ProCodex) {
                /*
                 *
                 * 
                 */
                $sqlAC = "SELECT
TG_ServiceApprovalCycle.TransferType,
TG_ServiceApprovalCycle.AccessRoleValueNomini,
TG_ServiceApprovalCycle.AccessRoleValueCoordinator,
TG_ServiceApprovalCycle.AccessRoleType,
TG_ServiceApprovalCycle.Status
FROM
TG_ServiceApprovalCycle
WHERE
TG_ServiceApprovalCycle.TransferType = 'PP'";
                $stmtAC = $db->runMsSqlQuery($sqlAC);
                while ($rowAC = sqlsrv_fetch_array($stmtAC, SQLSRV_FETCH_ASSOC)) {
                    $RequestType = "ServiceUpdate";
                    $RequestID = $newHisID;
                    $ApproveDesignationCode = $rowAC['AccessRoleValueCoordinator'];
                    $ApproveDesignationNominiCode = $rowAC['AccessRoleValueNomini'];
                    $ApprovedByNIC = "";

                    if ($rowAC['AccessRoleType'] == "PC") {
                        // for principle system update
                        if ($AccessLevel == '1000') {
                            $ApproveInstCode = $InstCodeCurrent;
                            $ApprovedStatus = 'Y';
                            $DateTime = date('Y-m-d H:i:s');
                            $Remarks = "ManualUpdate";

                            $queryMainSave = "INSERT INTO TG_Approval (
	RequestType,
	RequestID,
	ApproveInstCode,
	ApproveDesignationCode,
	ApproveDesignationNominiCode,
	ApprovedStatus,
	ApprovedByNIC,
	DateTime,
	Remarks
)
VALUES
	(
		'$RequestType',
		'$RequestID',
		'$ApproveInstCode',
		'$ApproveDesignationCode',
		'$ApproveDesignationNominiCode',
		'$ApprovedStatus',
		'$ApprovedByNIC',
		'$DateTime',
		'$Remarks'
	)";
                            $db->runMsSqlQueryInsert($queryMainSave);
                        }
                    } else {
                        if ($rowAC['Status'] == 'N') {
                            $ApproveInstCode = $ZoneCode;
                        } else {
                            $ApproveInstCode = $ZoneCodex;
                        }
                        if ($rowAC['AccessRoleType'] == 'PD') {
                            $proCode=$rest = substr($ProCodex, -1);
                            $proDepCode="PD0".$proCode."01";
                            $ApproveInstCode = $proDepCode;
                        }
                        $ApprovedStatus = "P";
                        $DateTime = '';
                        $Remarks = '';

                        $queryMainSave = "INSERT INTO TG_Approval (
	RequestType,
	RequestID,
	ApproveInstCode,
	ApproveDesignationCode,
	ApproveDesignationNominiCode,
	ApprovedStatus,
	ApprovedByNIC,
	DateTime,
	Remarks
)
VALUES
	(
		'$RequestType',
		'$RequestID',
		'$ApproveInstCode',
		'$ApproveDesignationCode',
		'$ApproveDesignationNominiCode',
		'$ApprovedStatus',
		'$ApprovedByNIC',
		'$DateTime',
		'$Remarks'
	)";
                        $db->runMsSqlQueryInsert($queryMainSave);
                    }
                }
                /*
                 * 
                 */
            }
        }
        header("Location:services-8--$NICUser.html");
        /*   redirect("reservation_customer_info-54-4_1--E--104.html"); */
        exit();
    }


    /* 	exit();
      sqlsrv_query($queryGradeSave);
     */
}

if ($status == 'D') {
    /* echo $vID;
      exit(); */
    $_SESSION['success_update'] = "Record removed successfully.";
    if ($cat == 'Teaching' || $cat == 'TeachingTmp') {
        
        $sql = "SELECT NIC FROM $tblName WHERE ID = $vID";
        $stmt = $db->runMsSqlQuery($sql);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $NICUser = trim($row['NIC']);
               
        
        $sqlDel = "DELETE FROM $tblName
      WHERE ID=$vID";
        $db->runMsSqlQuery($sqlDel);

        if ($cat == 'TeachingTmp') {
            $sqlDel2 = "DELETE FROM TG_EmployeeUpdateTeaching
		  WHERE TeachingID=$vID";
            $db->runMsSqlQuery($sqlDel2);
            
             audit_trail($NICUser, $_SESSION["NIC"], 'teacherprofile\teaching.php', 'Delete', $tblName.'TG_EmployeeUpdateTeaching', 'Delete pending approval teaching info.');
        }


        header("Location:$redirect_page");
        /*    redirect("reservation_customer_info-54-4_1--E--104.html"); */
        exit();
    }
    if ($cat == 'Qualification') {
        $sqlDel = "DELETE FROM $tblName
      WHERE ID=$vID";
        $db->runMsSqlQuery($sqlDel);

        $sqlDel2 = "DELETE FROM QualificationSubjects
      WHERE QualificationID=$vID";
        $db->runMsSqlQuery($sqlDel2);

        header("Location:$redirect_page");
        /*   redirect("reservation_customer_info-54-4_1--E--104.html"); */
        exit();
    }
    if ($cat == 'QualificationTmp') {
        $vID = trim($vID);
        $sql = "SELECT NIC FROM $tblName WHERE ID = $vID";
        $stmt = $db->runMsSqlQuery($sql);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $NICUser = trim($row['NIC']);
       
           
        $sqlDel1 = "DELETE FROM TG_EmployeeUpdateQualification
      WHERE QualificationID=$vID";
        $db->runMsSqlQuery($sqlDel1);

        $sqlDel2 = "DELETE FROM UP_QualificationSubjects
      WHERE QualificationID=$vID";
        $db->runMsSqlQuery($sqlDel2);
        
        $sqlDel = "DELETE FROM UP_StaffQualification
      WHERE ID = $vID";
        $db->runMsSqlQuery($sqlDel);
        
        audit_trail($NICUser, $_SESSION["NIC"], 'teacherprofile\qualification.php', 'Delete', $tblName.'UP_QualificationSubjects,TG_EmployeeUpdateQualification', 'Delete pending approval qualification info.');

        header("Location:$redirect_page");
        /*  redirect("reservation_customer_info-54-4_1--E--104.html"); */
        exit();
    }
    if ($cat == 'Children') {
        $sqlDel = "DELETE FROM $tblName
      WHERE ID=$vID";
        $db->runMsSqlQuery($sqlDel);

        header("Location:$redirect_page");
        /*      redirect("reservation_customer_info-54-4_1--E--104.html"); */
        exit();
    }
    if ($cat == 'ChildrenTemp') {
        $sql = "SELECT NIC FROM $tblName WHERE ID = $vID";
        $stmt = $db->runMsSqlQuery($sql);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $NICUser = trim($row['NIC']);
        
        $sqlDel = "DELETE FROM $tblName
      WHERE ID=$vID";
        $db->runMsSqlQuery($sqlDel);

        $sqlDel2 = "DELETE FROM TG_EmployeeUpdateChildInfo
      WHERE StaffChildID=$vID";
        $db->runMsSqlQuery($sqlDel2);
        
        audit_trail($NICUser, $_SESSION["NIC"], 'teacherprofile\familyInfoChild.php', 'Delete', $tblName, 'Delete pending approval family child info.');

        header("Location:$redirect_page");
        /*   redirect("reservation_customer_info-54-4_1--E--104.html"); */
        exit();
    }

    if ($cat == 'Approval') {
        $sqlDel = "DELETE FROM $tblName
      WHERE ID=$vID";
        $db->runMsSqlQuery($sqlDel);

        $sqlDel2 = "DELETE FROM TG_ApprovalProcess
      WHERE ApprovalProcMainID=$vID";
        $db->runMsSqlQuery($sqlDel2);

        header("Location:$redirect_page");
        /*   redirect("reservation_customer_info-54-4_1--E--104.html"); */
        exit();
    }

    if ($cat == 'services') {
        $sql = "SELECT NIC FROM $tblName WHERE ID = $vID";
        $stmt = $db->runMsSqlQuery($sql);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $NICUser = trim($row['NIC']);
        
        
        $sqlDel = "DELETE FROM $tblName WHERE ID=$vID";
        $db->runMsSqlQuery($sqlDel);

        $sqlDel2 = "DELETE FROM TG_Approval
      WHERE RequestType='ServiceUpdate' and RequestID=$vID";
        $db->runMsSqlQuery($sqlDel2);
        
        audit_trail($NICUser, $_SESSION["NIC"], 'teacherprofile\services.php', 'Delete', $tblName.'TG_Approval', 'Delete pending approval service info.');


        header("Location:$redirect_page");
        exit();
    }
    $sqlDel = "DELETE FROM $tblName
      WHERE ID=$vID";
    $db->runMsSqlQuery($sqlDel);

    header("Location:$redirect_page");
    /*     redirect("reservation_customer_info-54-4_1--E--104.html"); */
    exit();
}
?>