<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
$msg = "";
$tblNam = "TG_ApprovalProcessMain";
$countTotal = "SELECT * FROM $tblNam"; /* $NICUser */
$redirect_page = "approvalProcess-1.html";
/* $id='555861770V'; */
$updateBy = $NICUser;
$NICUser = $id;


/* $countSql = "SELECT * FROM $tblNam where ProcessType='$ProcessType' and AccessRoleID='$PositionCode' and Enable = 'Y'"; */
$isAvailablePmast = $isAvailableCurAdd = "";
$success = "";




if (isset($_POST["FrmSubmitxxxxxxxxxxxxxxxxxxxxxxxxxx"])) {
    echo "Contact your administrator ";
    exit();
    /*
      $fmRec = $_REQUEST['fmRec'];
      $menuRec = $_REQUEST['menuRec'];

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
      $msg1 .= "* Appoinment Type<br>";
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

      if ($msg1)
      $msg = "Error(s) on the page. Please fill the, <br>" . "" . $msg1;
      if ($msg == '') {

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


      $sqlUserAcc = "SELECT   Passwords.AccessLevel
      FROM            TeacherMast INNER JOIN
      Passwords ON TeacherMast.NIC = Passwords.NICNo
      WHERE (TeacherMast.NIC='$NICUser')";
      $resuacc = $db->runMsSqlQuery($sqlUserAcc);
      $rowuacc = sqlsrv_fetch_array($resuacc, SQLSRV_FETCH_ASSOC);
      $AccessLevel = $rowuacc['AccessLevel'];


      if ($fmRec == '') {
      $queryMainSave = "INSERT INTO UP_StaffServiceHistory			   (NIC,ServiceRecTypeCode,AppDate,InstCode,SecGRCode,WorkStatusCode,ServiceTypeCode,EmpTypeCode,PositionCode,Cat2003Code,Reference,LeaveEndDate,LastUpdate,UpdateBy,RecordLog,MainHistID,IsApproved)
      VALUES
      ('$NICUser','$ServiceRecTypeCode','$AppDate','$InstCode','$SecGRCode','','$ServiceTypeCode','','$PositionCode','$Cat2003Code','$Reference','','$LastUpdate','$updateBy','update by $updateBy','0','N')";

      $db->runMsSqlQuery($queryMainSave);

      $reqTabMobAc = "SELECT ID FROM UP_StaffServiceHistory where NIC='$NICUser' ORDER BY ID DESC";
      $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
      $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
      $newIDMast = trim($rowMobAc['ID']);




      $success = "Your update request submitted successfully.Data will be displaying after the approvals.";
      } else {

      if ($menuRec == 'U') {

      $queryMainUpdate = "INSERT INTO UP_StaffServiceHistory			   (NIC,ServiceRecTypeCode,AppDate,InstCode,SecGRCode,WorkStatusCode,ServiceTypeCode,EmpTypeCode,PositionCode,Cat2003Code,Reference,LeaveEndDate,LastUpdate,UpdateBy,RecordLog,MainHistID,IsApproved)
      VALUES
      ('$NICUser','$ServiceRecTypeCode','$AppDate','$InstCode','$SecGRCode','','$ServiceTypeCode','','$PositionCode','$Cat2003Code','$Reference','','$LastUpdate','$updateBy','update by $updateBy','$fmRec','N')";
      $db->runMsSqlQuery($queryMainUpdate);

      $reqTabMobAc = "SELECT ID FROM UP_StaffServiceHistory where NIC='$NICUser' ORDER BY ID DESC";
      $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
      $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
      $newIDMast = trim($rowMobAc['ID']);
      } else {
      $queryMainUpdate = "UPDATE UP_StaffServiceHistory SET ServiceRecTypeCode='$ServiceRecTypeCode',AppDate='$AppDate',InstCode='$InstCode',SecGRCode='$SecGRCode',WorkStatusCode='$WorkStatusCode',ServiceTypeCode='$ServiceTypeCode',EmpTypeCode='$EmpTypeCode',PositionCode='$PositionCode',Cat2003Code='$Cat2003Code',Reference='$Reference',LastUpdate='$LastUpdate',UpdateBy='$updateBy',RecordLog='Edit record',IsApproved='N' WHERE ID='$fmRec'";
      $db->runMsSqlQuery($queryMainUpdate);
      $newHisID = $fmRec;

      $delQu = "DELETE From TG_Approval where RequestID='$newHisID' and RequestType='ServiceUpdate'";
      $db->runMsSqlQuery($delQu);
      }

      $success = "Your update request submitted successfully. Data will be displaying after the approvals.";
      }

      if ($ZoneCode == $ZoneCodex) {

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
      }
      }
      }
      if ($ZoneCode != $ZoneCodex and $ProCode == $ProCodex) {

      $RequestType = "ServiceUpdate";
      $RequestID = $newHisID;
      $ApproveInstCode = $InstCodeCurrent;
      $ApproveDesignationCode = 3000;
      $ApproveDesignationNominiCode = 3000;
      $ApprovedStatus = "P";
      $ApprovedByNIC = "";

      $DateTime = date('Y-m-d H:i:s');


      if ($AccessLevel == '1000') {
      $Remarks = "ManualUpdate";
      $queryMainSave = "INSERT INTO TG_Approval			   (RequestType,RequestID,ApproveInstCode,ApproveDesignationCode,ApproveDesignationNominiCode,ApprovedStatus,ApprovedByNIC,DateTime,Remarks)
      VALUES
      ('$RequestType','$RequestID','$ApproveInstCode','$ApproveDesignationCode','$ApproveDesignationNominiCode','Y','$ApprovedByNIC','$DateTime','$Remarks')";

      $db->runMsSqlQueryInsert($queryMainSave);

      }

      $ApproveInstCode = $ZoneCodex;
      $ApproveDesignationCode = 11050;
      $ApproveDesignationNominiCode = 11000;
      $DateTime = $Remarks = "";

      $queryMainSave = "INSERT INTO TG_Approval			   (RequestType,RequestID,ApproveInstCode,ApproveDesignationCode,ApproveDesignationNominiCode,ApprovedStatus,ApprovedByNIC,DateTime,Remarks)
      VALUES
      ('$RequestType','$RequestID','$ApproveInstCode','$ApproveDesignationCode','$ApproveDesignationNominiCode','$ApprovedStatus','$ApprovedByNIC','$DateTime','$Remarks')";

      $db->runMsSqlQueryInsert($queryMainSave);


      $ApproveInstCode = $ZoneCode;
      $ApproveDesignationCode = 11050;
      $ApproveDesignationNominiCode = 11000;
      $queryMainSave = "INSERT INTO TG_Approval			   (RequestType,RequestID,ApproveInstCode,ApproveDesignationCode,ApproveDesignationNominiCode,ApprovedStatus,ApprovedByNIC,DateTime,Remarks)
      VALUES
      ('$RequestType','$RequestID','$ApproveInstCode','$ApproveDesignationCode','$ApproveDesignationNominiCode','$ApprovedStatus','$ApprovedByNIC','$DateTime','$Remarks')";

      $db->runMsSqlQueryInsert($queryMainSave);
      }

      if ($ProCode != $ProCodex) {

      $RequestType = "ServiceUpdate";
      $RequestID = $newHisID;
      $ApproveInstCode = $InstCodeCurrent;
      $ApproveDesignationCode = 3000;
      $ApproveDesignationNominiCode = 3000;
      $ApprovedStatus = "P";
      $ApprovedByNIC = "";
      $DateTime = date('Y-m-d H:i:s');


      if ($AccessLevel == '1000') {
      $Remarks = "ManualUpdate";
      $queryMainSave = "INSERT INTO TG_Approval			   (RequestType,RequestID,ApproveInstCode,ApproveDesignationCode,ApproveDesignationNominiCode,ApprovedStatus,ApprovedByNIC,DateTime,Remarks)
      VALUES
      ('$RequestType','$RequestID','$ApproveInstCode','$ApproveDesignationCode','$ApproveDesignationNominiCode','Y','$ApprovedByNIC','$DateTime','$Remarks')";

      $db->runMsSqlQueryInsert($queryMainSave);
      }

      $ApproveInstCode = $ZoneCodex;
      $ApproveDesignationCode = 11050;
      $ApproveDesignationNominiCode = 11000;
      $DateTime = $Remarks = "";

      $queryMainSave = "INSERT INTO TG_Approval			   (RequestType,RequestID,ApproveInstCode,ApproveDesignationCode,ApproveDesignationNominiCode,ApprovedStatus,ApprovedByNIC,DateTime,Remarks)
      VALUES
      ('$RequestType','$RequestID','$ApproveInstCode','$ApproveDesignationCode','$ApproveDesignationNominiCode','$ApprovedStatus','$ApprovedByNIC','$DateTime','$Remarks')";
      $db->runMsSqlQuery($queryMainSave);

      //PD0101





      $ApproveInstCode = $ProCodex;
      $ApproveDesignationCode = 17050;
      $ApproveDesignationNominiCode = 17000;

      $queryMainSave = "INSERT INTO TG_Approval			   (RequestType,RequestID,ApproveInstCode,ApproveDesignationCode,ApproveDesignationNominiCode,ApprovedStatus,ApprovedByNIC,DateTime,Remarks)
      VALUES
      ('$RequestType','$RequestID','$ProCodex','$ApproveDesignationCode','$ApproveDesignationNominiCode','$ApprovedStatus','$ApprovedByNIC','$DateTime','$Remarks')";

      $db->runMsSqlQuery($queryMainSave);


      $ApproveInstCode = $ZoneCode;
      $ApproveDesignationCode = 11050;
      $ApproveDesignationNominiCode = 11000;
      $queryMainSave = "INSERT INTO TG_Approval			   (RequestType,RequestID,ApproveInstCode,ApproveDesignationCode,ApproveDesignationNominiCode,ApprovedStatus,ApprovedByNIC,DateTime,Remarks)
      VALUES
      ('$RequestType','$RequestID','$ApproveInstCode','$ApproveDesignationCode','$ApproveDesignationNominiCode','$ApprovedStatus','$ApprovedByNIC','$DateTime','$Remarks')";

      $db->runMsSqlQuery($queryMainSave);
      }
      }

     */
}


if ($menu == 'E') {
    $sqlPmast = "SELECT        UP_StaffServiceHistory.ID, UP_StaffServiceHistory.NIC, CONVERT(varchar(20), UP_StaffServiceHistory.AppDate, 121) AS AppDate, UP_StaffServiceHistory.InstCode, UP_StaffServiceHistory.ServiceRecTypeCode, 
                         CD_SecGrades.GradeName, CD_Service.ServiceName, CD_Positions.PositionName, CD_CAT2003.Cat2003Name, CD_ServiceRecType.Description, UP_StaffServiceHistory.SecGRCode, UP_StaffServiceHistory.Reference,
                         UP_StaffServiceHistory.ServiceTypeCode, UP_StaffServiceHistory.PositionCode, UP_StaffServiceHistory.Cat2003Code
FROM            UP_StaffServiceHistory LEFT JOIN
                         CD_SecGrades ON UP_StaffServiceHistory.SecGRCode = CD_SecGrades.GradeCode LEFT JOIN
                         CD_Service ON UP_StaffServiceHistory.ServiceTypeCode = CD_Service.ServCode LEFT JOIN
                         CD_CAT2003 ON UP_StaffServiceHistory.Cat2003Code = CD_CAT2003.Cat2003Code LEFT JOIN
                         CD_ServiceRecType ON UP_StaffServiceHistory.ServiceRecTypeCode = CD_ServiceRecType.DutyCode LEFT JOIN
                         CD_Positions ON UP_StaffServiceHistory.PositionCode = CD_Positions.Code
WHERE        (UP_StaffServiceHistory.ID = '$fm') ORDER BY AppDate ASC"; /*  and StaffServiceHistory.ID='588449' */

    $isAvailablePmast = $db->rowAvailable($sqlPmast);
    $resPm = $db->runMsSqlQuery($sqlPmast);
    $rowPm = sqlsrv_fetch_array($resPm, SQLSRV_FETCH_ASSOC);
    $AppDate = trim($rowPm['AppDate']);
    $InstCode = trim($rowPm['InstCode']);
    $ServiceRecTypeCode = trim($rowPm['ServiceRecTypeCode']);
    $SecGRCode = trim($rowPm['SecGRCode']);
    $ServiceTypeCode = trim($rowPm['ServiceTypeCode']);
    $PositionCode = trim($rowPm['PositionCode']);
    $Cat2003Code = trim($rowPm['Cat2003Code']);
    $Reference = trim($rowPm['Reference']);

    $sqlCenseQ = "SELECT        CD_CensesNo.DistrictCode, CD_CensesNo.ZoneCode, CD_CensesNo.DivisionCode, CD_Districts.ProCode
FROM            CD_CensesNo INNER JOIN
                         CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
WHERE        (CD_CensesNo.CenCode = '$InstCode')";

    $resABCq = $db->runMsSqlQuery($sqlCenseQ);
    $rowABCq = sqlsrv_fetch_array($resABCq, SQLSRV_FETCH_ASSOC);
    $InstitutionName = $rowABCq['InstitutionName'];
    $DistrictCodex = trim($rowABCq['DistrictCode']);
    $ZoneCodex = trim($rowABCq['ZoneCode']);
    $DivisionCodex = trim($rowABCq['DivisionCode']);
    $ProCodex = trim($rowABCq['ProCode']);
}

if ($menu == 'U') {
    $sqlPmast = "SELECT        StaffServiceHistory.ID, StaffServiceHistory.NIC, CONVERT(varchar(20), StaffServiceHistory.AppDate, 121) AS AppDate, StaffServiceHistory.InstCode, StaffServiceHistory.ServiceRecTypeCode, 
                         CD_SecGrades.GradeName, CD_Service.ServiceName, CD_Positions.PositionName, CD_CAT2003.Cat2003Name, CD_ServiceRecType.Description, StaffServiceHistory.SecGRCode, StaffServiceHistory.Reference,
                         StaffServiceHistory.ServiceTypeCode, StaffServiceHistory.PositionCode, StaffServiceHistory.Cat2003Code
FROM            StaffServiceHistory LEFT JOIN
                         CD_SecGrades ON StaffServiceHistory.SecGRCode = CD_SecGrades.GradeCode LEFT JOIN
                         CD_Service ON StaffServiceHistory.ServiceTypeCode = CD_Service.ServCode LEFT JOIN
                         CD_CAT2003 ON StaffServiceHistory.Cat2003Code = CD_CAT2003.Cat2003Code LEFT JOIN
                         CD_ServiceRecType ON StaffServiceHistory.ServiceRecTypeCode = CD_ServiceRecType.DutyCode LEFT JOIN
                         CD_Positions ON StaffServiceHistory.PositionCode = CD_Positions.Code
WHERE        (StaffServiceHistory.ID = '$fm') ORDER BY AppDate ASC"; /*  and StaffServiceHistory.ID='588449' */

    $isAvailablePmast = $db->rowAvailable($sqlPmast);
    $resPm = $db->runMsSqlQuery($sqlPmast);
    $rowPm = sqlsrv_fetch_array($resPm, SQLSRV_FETCH_ASSOC);
    $AppDate = trim($rowPm['AppDate']);
    $InstCode = trim($rowPm['InstCode']);
    $ServiceRecTypeCode = trim($rowPm['ServiceRecTypeCode']);
    $SecGRCode = trim($rowPm['SecGRCode']);
    $ServiceTypeCode = trim($rowPm['ServiceTypeCode']);
    $PositionCode = trim($rowPm['PositionCode']);
    $Cat2003Code = trim($rowPm['Cat2003Code']);
    $Reference = trim($rowPm['Reference']);

    $sqlCenseQ = "SELECT        CD_CensesNo.DistrictCode, CD_CensesNo.ZoneCode, CD_CensesNo.DivisionCode, CD_Districts.ProCode
FROM            CD_CensesNo INNER JOIN
                         CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
WHERE        (CD_CensesNo.CenCode = '$InstCode')";

    $resABCq = $db->runMsSqlQuery($sqlCenseQ);
    $rowABCq = sqlsrv_fetch_array($resABCq, SQLSRV_FETCH_ASSOC);
    $InstitutionName = $rowABCq['InstitutionName'];
    $DistrictCodex = trim($rowABCq['DistrictCode']);
    $ZoneCodex = trim($rowABCq['ZoneCode']);
    $DivisionCodex = trim($rowABCq['DivisionCode']);
    $ProCodex = trim($rowABCq['ProCode']);
}




if ($isAvailablePmast != 1) {
    $curAddStatus = "Add";
    $sqlCurAdd = "SELECT        StaffServiceHistory.ID, StaffServiceHistory.NIC, CONVERT(varchar(20), StaffServiceHistory.AppDate, 121) AS AppDate, StaffServiceHistory.InstCode, StaffServiceHistory.ServiceRecTypeCode, 
                         CD_SecGrades.GradeName, CD_Service.ServiceName, CD_Positions.PositionName, CD_CAT2003.Cat2003Name, CD_ServiceRecType.Description, StaffServiceHistory.SecGRCode,  StaffServiceHistory.Reference,
                         StaffServiceHistory.ServiceTypeCode, StaffServiceHistory.PositionCode, StaffServiceHistory.Cat2003Code
FROM            StaffServiceHistory LEFT JOIN
                         CD_SecGrades ON StaffServiceHistory.SecGRCode = CD_SecGrades.GradeCode LEFT JOIN
                         CD_Service ON StaffServiceHistory.ServiceTypeCode = CD_Service.ServCode LEFT JOIN
                         CD_CAT2003 ON StaffServiceHistory.Cat2003Code = CD_CAT2003.Cat2003Code LEFT JOIN
                         CD_ServiceRecType ON StaffServiceHistory.ServiceRecTypeCode = CD_ServiceRecType.DutyCode LEFT JOIN
                         CD_Positions ON StaffServiceHistory.PositionCode = CD_Positions.Code
WHERE        (StaffServiceHistory.NIC = '$NICUser') ORDER BY StaffServiceHistory.AppDate ASC";


    $resABC = $db->runMsSqlQuery($sqlCurAdd);
}
?>
<?php if ($menu == '') { ?>
    <div class="main_content_inner_block">
        <div class="mcib_middle1">
            <table width="945" cellpadding="0" cellspacing="0">

                <!-- <tr>
                    <td width="50%" valign="top"><span style="color:#090; font-weight:bold;">*If your service data record is inaccurate, you can submit an update request</span></td>
                    <td width="50%" align="right" valign="top">

                        <a href="services-8-E-<?php echo $id ?>.html"><img src="../cms/images/udate-request.png" width="170" height="26" /></a>

                    </td>
                </tr> -->
                <?php if ($_SESSION['success_update']) { ?>
                    <tr>
                        <td colspan="2" valign="top" class="red"><?php
                            echo $_SESSION['success_update'];
                            $_SESSION['success_update'] = "";
                            ?> </td></tr>
                <?php } ?>
                <?php
                $x = 1;
                while ($rowABC = sqlsrv_fetch_array($resABC, SQLSRV_FETCH_ASSOC)) {
                    $StaffServiceHistoryID = $rowABC['ID'];
                    $AppDate = $rowABC['AppDate'];
                    $InstCode = trim($rowABC['InstCode']);


                    $GradeName = trim($rowABC['GradeName']);
                    $Description = trim($rowABC['Description']);
                    $ServiceName = trim($rowABC['ServiceName']);
                    $Cat2003Name = trim($rowABC['Cat2003Name']);
                    $PositionName = trim($rowABC['PositionName']);
                    $Reference = trim($rowABC['Reference']);

                    $posC = $catC = $serC = $srtC = $grdC = "#FFF";


                    if ($GradeName == '')
                        $grdC = "#F00;";

                    if ($Description == '')
                        $srtC = "#F00;";


                    if ($ServiceName == '')
                        $serC = "#F00;";


                    if ($Cat2003Name == '')
                        $catC = "#F00;";


                    if ($PositionName == '')
                        $posC = "#F00;";

                    $sqlCenseQ = "SELECT        CD_CensesNo.InstitutionName, CD_Districts.DistName, CD_Provinces.Province, CD_Zone.InstitutionName AS ZoneN, CD_Division.InstitutionName AS DivisionN
	FROM            CD_Division INNER JOIN
							 CD_Provinces INNER JOIN
							 CD_CensesNo INNER JOIN
							 CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode ON CD_Provinces.ProCode = CD_Districts.ProCode INNER JOIN
							 CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode ON CD_Division.CenCode = CD_CensesNo.DivisionCode
	WHERE        (CD_CensesNo.CenCode = '$InstCode')";

                    $resABCq = $db->runMsSqlQuery($sqlCenseQ);
                    $rowABCq = sqlsrv_fetch_array($resABCq, SQLSRV_FETCH_ASSOC);
                    $InstitutionName = $rowABCq['InstitutionName'];
                    $DistName = trim($rowABCq['DistName']);
                    $Province = $rowABCq['Province'];
                    $ZoneN = $rowABCq['ZoneN'];
                    $DivisionN = $rowABCq['DivisionN'];


                    $sqlPending = "SELECT
dbo.UP_StaffServiceHistory.ID,
dbo.UP_StaffServiceHistory.NIC

FROM
	UP_StaffServiceHistory
WHERE
	(
		UP_StaffServiceHistory.MainHistID = '$StaffServiceHistoryID'
	)
AND (
	UP_StaffServiceHistory.IsApproved = 'N'
)";

                    $checkPending = $db->rowCount($sqlPending);
                    ?>
                    <tr>
                        <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="3%" rowspan="6" align="left" valign="top"><strong><?php echo $x++ ?>)</strong></td>
                                    <td align="left" valign="top"><strong>Employment Basis</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top" style="border:1px; border-style:solid; border-color:<?php echo $srtC ?>;"><?php echo $Description ?></td>
                                    <td width="22%" align="left" valign="top"><strong>Personal File Reference</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top"><?php echo $Reference ?></td>
                                    <td width="4%" rowspan="6" align="left" valign="top">
                                        <?php
                                        if ($checkPending < 1) {
                                            ?>
                                            <!-- <a href="services-8-U-<?php echo $NICUser ?>-<?php echo $StaffServiceHistoryID ?>.html"><strong><img src="images/edit.png" width="32" height="32" title="edit"/></strong></a> -->
                                            <?php
                                        } else {
                                            echo '<img src="images/edit_disable.png" width="32" height="32" title="This record has sent for approval."/>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="15%" align="left" valign="top"><strong> Province</strong></td>
                                    <td width="2%" align="left" valign="top"><strong>:</strong></td>
                                    <td width="27%" align="left" valign="top"><?php echo $Province ?></td>
                                    <td align="left" valign="top"><strong>Section</strong></td>
                                    <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                    <td width="26%" align="left" valign="top" style="border:1px; border-style:solid; border-color:<?php echo $grdC ?>;"><?php echo $GradeName ?></td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top"><strong>District</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td width="27%" align="left" valign="top"><?php echo $DistName ?></td>
                                    <td align="left" valign="top"><strong>Position</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top" style="border:1px; border-style:solid; border-color:<?php echo $posC ?>;"><?php echo $PositionName ?></td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top"><strong>Zone</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td width="27%" align="left" valign="top"><?php echo $ZoneN ?></td>
                                    <td align="left" valign="top"><strong>Date of Appointment</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top"><?php echo $AppDate ?></td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top"><strong> Division</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td width="27%" align="left" valign="top"><?php echo $DivisionN ?> </td>
                                    <td align="left" valign="top"><strong>Service Category</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top" style="border:1px; border-style:solid; border-color:<?php echo $serC ?>;"><?php echo $ServiceName ?></td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top"><strong>School/ Institution</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top"><?php echo $InstitutionName ?></td>
                                    <td align="left" valign="top"><strong>1/2016 Circular Category</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top" style="border:1px; border-style:solid; border-color:<?php echo $catC ?>;"><?php echo $Cat2003Name ?></td>
                                </tr>
                            </table></td>
                    </tr>

                <?php } ?>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:12px; font-weight:bold;">&nbsp;</td>
                </tr>

                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px; font-weight:bold;">Pending Request(s)</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <?php
                $sqlCurAdd = "SELECT        UP_StaffServiceHistory.ID, UP_StaffServiceHistory.NIC, CONVERT(varchar(20), UP_StaffServiceHistory.AppDate, 121) AS AppDate, UP_StaffServiceHistory.InstCode, UP_StaffServiceHistory.ServiceRecTypeCode, 
                         CD_SecGrades.GradeName, CD_Service.ServiceName, CD_Positions.PositionName, CD_CAT2003.Cat2003Name, CD_ServiceRecType.Description, UP_StaffServiceHistory.SecGRCode,  UP_StaffServiceHistory.Reference,
                         UP_StaffServiceHistory.ServiceTypeCode, UP_StaffServiceHistory.PositionCode, UP_StaffServiceHistory.Cat2003Code
FROM            UP_StaffServiceHistory INNER JOIN
                         CD_SecGrades ON UP_StaffServiceHistory.SecGRCode = CD_SecGrades.GradeCode INNER JOIN
                         CD_Service ON UP_StaffServiceHistory.ServiceTypeCode = CD_Service.ServCode INNER JOIN
                         CD_CAT2003 ON UP_StaffServiceHistory.Cat2003Code = CD_CAT2003.Cat2003Code INNER JOIN
                         CD_ServiceRecType ON UP_StaffServiceHistory.ServiceRecTypeCode = CD_ServiceRecType.DutyCode INNER JOIN
                         CD_Positions ON UP_StaffServiceHistory.PositionCode = CD_Positions.Code
WHERE        (UP_StaffServiceHistory.NIC = '$NICUser') AND (UP_StaffServiceHistory.IsApproved='N') ORDER BY AppDate ASC"; /*  and StaffServiceHistory.ID='588449' IsApproved IS NULL UP_StaffServiceHistory.IsApproved !='Y'

                  (UP_StaffServiceHistory.IsApproved IS NULL OR UP_StaffServiceHistory.IsApproved='')
                 */
                $resABC = $db->runMsSqlQuery($sqlCurAdd);

                while ($rowABC = sqlsrv_fetch_array($resABC, SQLSRV_FETCH_ASSOC)) {
                    $Description = $rowABC['Description'];
                    $AppDate = $rowABC['AppDate'];
                    $InstCode = trim($rowABC['InstCode']);
                    $GradeName = $rowABC['GradeName'];
                    $ServiceName = $rowABC['ServiceName'];
                    $PositionName = $rowABC['PositionName'];
                    $Cat2003Name = trim($rowABC['Cat2003Name']);
                    $StaffServiceHistoryID = trim($rowABC['ID']);
                    $Reference = trim($rowABC['Reference']);

                    $sqlCenseQ = "SELECT        CD_CensesNo.InstitutionName, CD_Districts.DistName, CD_Provinces.Province, CD_Zone.InstitutionName AS ZoneN, CD_Division.InstitutionName AS DivisionN
	FROM            CD_Division INNER JOIN
							 CD_Provinces INNER JOIN
							 CD_CensesNo INNER JOIN
							 CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode ON CD_Provinces.ProCode = CD_Districts.ProCode INNER JOIN
							 CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode ON CD_Division.CenCode = CD_CensesNo.DivisionCode
	WHERE        (CD_CensesNo.CenCode = '$InstCode')";

                    $resABCq = $db->runMsSqlQuery($sqlCenseQ);
                    $rowABCq = sqlsrv_fetch_array($resABCq, SQLSRV_FETCH_ASSOC);
                    $InstitutionName = $rowABCq['InstitutionName'];
                    $DistName = trim($rowABCq['DistName']);
                    $Province = $rowABCq['Province'];
                    $ZoneN = $rowABCq['ZoneN'];
                    $DivisionN = $rowABCq['DivisionN'];
                    ?>

                    <tr>
                        <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top" bgcolor="#FCCDD5">&nbsp;</td>
                    </tr>
                    <tr bgcolor="#FCCDD5">
                        <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="3%" rowspan="6" align="left" valign="top"><strong><?php echo $x++ ?>)</strong></td>
                                    <td align="left" valign="top"><strong>Employment Basis</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top"><?php echo $Description ?></td>
                                    <td width="22%" align="left" valign="top"><strong>Personal File Reference</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top"><?php echo $Reference ?></td>
                                    <td width="4%" align="left" valign="top"><a href="services-8-E-<?php echo $NICUser ?>-<?php echo $StaffServiceHistoryID ?>.html"><strong><img src="images/edit.png" width="24" height="24" title="edit"/></strong></a></td>
                                </tr>
                                <tr>
                                    <td width="15%" align="left" valign="top"><strong> Province</strong></td>
                                    <td width="2%" align="left" valign="top"><strong>:</strong></td>
                                    <td width="27%" align="left" valign="top"><?php echo $Province ?></td>
                                    <td align="left" valign="top"><strong>Section</strong></td>
                                    <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                    <td width="26%" align="left" valign="top"><?php echo $GradeName ?></td>
                                    <td width="4%" align="left" valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top"><strong>District</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td width="27%" align="left" valign="top"><?php echo $DistName ?></td>
                                    <td align="left" valign="top"><strong>Position</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top"><?php echo $PositionName ?></td>
                                    <td width="4%" align="left" valign="top"><a href="javascript:aedWin('<?php echo $StaffServiceHistoryID ?>','D','services','UP_StaffServiceHistory','<?php echo "$ttle-$pageid-$menu-$id.html"; ?>')"><img src="images/delete-file.png" width="24" height="24" title="Delete" /></a></td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top"><strong>Zone</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td width="27%" align="left" valign="top"><?php echo $ZoneN ?></td>
                                    <td align="left" valign="top"><strong>Date of Appointment</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top"><?php echo $AppDate ?></td>
                                    <td width="4%" align="left" valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top"><strong> Division</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td width="27%" align="left" valign="top"><?php echo $DivisionN ?> </td>
                                    <td align="left" valign="top"><strong>Service Category</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top"><?php echo $ServiceName ?></td>
                                    <td width="4%" align="left" valign="top">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top"><strong>School/ Institution</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top"><?php echo $InstitutionName ?></td>
                                    <td align="left" valign="top"><strong>1/2016 Circular Category</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top"><?php echo $Cat2003Name ?></td>
                                    <td width="4%" align="left" valign="top">&nbsp;</td>
                                </tr>
                            </table></td>
                    </tr>



                    <tr>
                        <td colspan="2" bgcolor="#FCCDD5">&nbsp;</td>
                    </tr><?php } ?>
            </table>
        </div>
    </div>
<?php } ?>
<?php if ($menu == 'E' || $menu == 'U') { ?>
    <div class="main_content_inner_block"> <div class="mcib_middle1">
            <form method="post" action="save.php" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
                <?php if ($msg != '' || $success != '' || $_SESSION['success_update'] != '') {/* if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  */ ?>   

                    <div class="mcib_middle_full">
                        <div class="form_error" style="margin-top:40px;"><?php
                            echo $msg;
                            echo $success;
                            echo $_SESSION['success_update'];
                            $_SESSION['success_update'] = "";
                            ?><?php
                            echo $_SESSION['fail_update'];
                            $_SESSION['fail_update'] = "";
                            ?></div>
                    </div>
                <?php } ?>
                <?php if ($success == '') { ?>
                    <table width="945" cellpadding="0" cellspacing="0">
                        <tr>
                            <td valign="top"><span style="color:#090; font-weight:bold;">*If your personal data record is inaccurate, you can submit an update request</span></td>
                            <td align="right" valign="top"><a href="services-8--<?php echo $id ?>.html"><img src="../cms/images/current-details.png" width="138" height="26" /></a></td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="50%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                    <tr>
                                        <td width="30%" align="left" valign="top"><strong><span class="red"><strong>*</strong></span><strong></strong>Employment Basis</strong></td>
                                        <td width="3%" align="left" valign="top"><strong>:</strong></td>
                                        <td width="67%" align="left" valign="top">
                                            <input type="hidden" name="fmRec" value="<?php echo $fm ?>" />
                                            <input type="hidden" name="menuRec" value="<?php echo $menu ?>" />
                                            <input type="hidden" name="AED" value="special" />
                                            <input type="hidden" name="cat" value="services" />
                                            <input type="hidden" name="nicSelected" value="<?php echo $id ?>" />
                                            <select class="select2a_n" id="ServiceRecTypeCode" name="ServiceRecTypeCode">
                                                <!--<option value="">School Name</option>-->
                                                <?php
                                                $sql = "SELECT DutyCode,Description FROM CD_ServiceRecType order by Description asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $TitleCodedb = trim($row['DutyCode']);
                                                    $TitleName = $row['Description'];
                                                    $seltebr = "";
                                                    if ($TitleCodedb == $ServiceRecTypeCode) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$TitleCodedb\" $seltebr>$TitleName</option>";
                                                }
                                                ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"><strong>Province</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><select class="select2a_n" id="ProCode" name="ProCode" onchange="Javascript:show_district('districtList', this.options[this.selectedIndex].value, '');" <?php echo $disaTxt ?>>
                                                <!--<option value="">Select Province</option>-->
                                                <?php
                                                $sql = "SELECT ProCode,Province FROM CD_Provinces order by ProCode asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $DistCoded = trim($row['ProCode']);
                                                    $DistNamed = $row['Province'];
                                                    $seltebr = "";
                                                    if ($DistCoded == "$ProCodex") {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$DistCoded\" $seltebr>$DistNamed</option>";
                                                }
                                                ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"><strong>District</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><div id="txt_district"><select class="select2a_n" id="DistrictCode" name="DistrictCode" onchange="Javascript:show_zone('zonelist', this.options[this.selectedIndex].value, '');" <?php echo $disaTxt ?>>
                                                    <option value="">District Name</option>
                                                    <?php
                                                    $sql = "SELECT DistCode,DistName FROM CD_Districts where ProCode='$ProCodex' order by DistName asc";
                                                    $stmt = $db->runMsSqlQuery($sql);
                                                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                        $DistCoded = trim($row['DistCode']);
                                                        $DistNamed = $row['DistName'];
                                                        $seltebr = "";
                                                        if ($DistCoded == $DistrictCodex) {
                                                            $seltebr = "selected";
                                                        }
                                                        echo "<option value=\"$DistCoded\" $seltebr>$DistNamed</option>";
                                                    }
                                                    ?>
                                                </select></div></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"><strong>Zone</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><div id="txt_zone">
                                                <select class="select2a_n" id="ZoneCode" name="ZoneCode" onchange="Javascript:show_division('divisionList', this.options[this.selectedIndex].value, document.frmSave.DistrictCode.value);" <?php echo $disaTxt ?>>
                                                    <option value="">Zone Name</option>
                                                    <?php
                                                    $sql = "SELECT CenCode,InstitutionName FROM CD_Zone where DistrictCode='$DistrictCodex' order by InstitutionName asc";
                                                    $stmt = $db->runMsSqlQuery($sql);
                                                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                        $DSCoded = trim($row['CenCode']);
                                                        $DSNamed = $row['InstitutionName'];
                                                        $seltebr = "";
                                                        if ($DSCoded == $ZoneCodex) {
                                                            $seltebr = "selected";
                                                        }
                                                        echo "<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"><strong> Division</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><div id="txt_division">
                                                <select class="select2a_n" id="DivisionCode" name="DivisionCode" onchange="Javascript:show_cences('censesList', this.options[this.selectedIndex].value, document.frmSave.DistrictCode.value);" <?php echo $disaTxt ?>>
                                                    <option value="">Division Name</option>
                                                    <?php
                                                    $sql = "SELECT CenCode,InstitutionName FROM CD_Division where DistrictCode='$DistrictCodex' and ZoneCode='$ZoneCodex' order by InstitutionName asc";
                                                    $stmt = $db->runMsSqlQuery($sql);
                                                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                        $DSCoded = trim($row['CenCode']);
                                                        $DSNamed = $row['InstitutionName'];
                                                        $seltebr = "";
                                                        if ($DSCoded == $DivisionCodex) {
                                                            $seltebr = "selected";
                                                        }
                                                        echo "<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"><strong><span class="red"><strong>*</strong></span><strong></strong>School/ Institution</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><div id="txt_showInstitute">
                                                <select class="select2a" id="InstCode" name="InstCode">
                                                    <option value="">School Name</option>
                                                    <?php
                                                    $DivisionCode = "abc";
                                                    $sql = "SELECT [InstType]
      ,[CenCode]
      ,[InstitutionName]
      ,[DistrictCode]
      ,[RecordLog]
      ,[ZoneCode]
      ,[DivisionCode]
      ,[IsNationalSchool]
      ,[SchoolType]
  FROM [dbo].[CD_CensesNo] where DivisionCode='$DivisionCodex' and DivisionCode!=''
  order by InstitutionName";
                                                    $stmt = $db->runMsSqlQuery($sql);
                                                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                        $CenCode = trim($row['CenCode']);
                                                        $InstitutionName = addslashes($row['InstitutionName']);
                                                        $seltebr = "";
                                                        if ($CenCode == $InstCode) {
                                                            $seltebr = "selected";
                                                        }
                                                        echo "<option value=\"$CenCode\" $seltebr>$InstitutionName $CenCode</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div></td>
                                    </tr>

                                </table>
                            </td>
                            <td width="50%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                    <tr>
                                        <td width="38%" align="left" valign="top"><strong>Personal File Reference</strong></td>
                                        <td width="3%" align="left" valign="top"><strong>:</strong></td>
                                        <td width="59%" align="left" valign="top"><?php /* echo $EthnicityName */ ?>
                                            <input name="Reference" type="text" class="input2_n" id="Reference" value="<?php echo $Reference ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"><strong><span class="red"><strong>*</strong></span><strong></strong>Section</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><?php /*  echo $GenderName */ ?>
                                            <select class="select2a_n" id="SecGRCode" name="SecGRCode">
                                                <!--<option value="">School Name</option>-->
                                                <?php
                                                $sql = "SELECT [GradeCode],[GradeName] FROM CD_SecGrades order by GradeName asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $GenderCoded = trim($row['GradeCode']);
                                                    $GenderName = $row['GradeName'];
                                                    $seltebr = "";
                                                    if ($GenderCoded == $SecGRCode) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$GenderCoded\" $seltebr>$GenderName</option>";
                                                }
                                                ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"><strong><span class="red"><strong>*</strong></span><strong></strong>Position</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><?php //echo $ReligionName         ?>
                                            <select class="select2a_n" id="PositionCode" name="PositionCode">
                                                <!--<option value="">School Name</option>-->
                                                <?php
                                                $sql = "SELECT Code,PositionName FROM CD_Positions order by PositionName asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $Coded = trim($row['Code']);
                                                    $ReligionNamed = $row['PositionName'];
                                                    $seltebr = "";
                                                    if ($Coded == $PositionCode) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$Coded\" $seltebr>$ReligionNamed</option>";
                                                }
                                                ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"><strong><span class="red"><strong>*</strong></span><strong></strong>Date of Appoinment</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><table width="100%" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td width="13%"><input name="AppDate" type="text" class="input3new" id="AppDate" value="<?php echo $AppDate; ?>" size="10" style="height:20px; line-height:20px;" readonly/></td>
                                                    <td width="87%"><input name="f_trigger_1" type="image" id="f_trigger_1" src="../cms/images/calender_icon.gif" align="top" width="16" height="16"  />
                                                        <script type="text/javascript">

                                                            Calendar.setup({
                                                                inputField: "AppDate", /* id of the input field */
                                                                ifFormat: "%Y-%m-%d", /* format of the input field */
                                                                showsTime: false, /* will display a time selector */
                                                                button: "f_trigger_1", /* trigger for the calendar (button ID) */
                                                                singleClick: true, /* double-click mode */
                                                                step: 1                 /* show all years in drop-down boxes (instead of every other year as default) */
                                                            });
                                                        </script></td>
                                                </tr>
                                            </table></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"><strong><span class="red"><strong>*</strong></span><strong></strong>Service Category</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><select class="select2a_n" id="ServiceTypeCode" name="ServiceTypeCode">
                                                <!--<option value="">School Name</option>-->
                                                <?php
                                                $sql = "SELECT ServCode,ServiceName FROM CD_Service order by ServiceName asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $Coded = trim($row['ServCode']);
                                                    $EthnicityNamed = $row['ServiceName'];
                                                    $seltebr = "";
                                                    if ($Coded == $ServiceTypeCode) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$Coded\" $seltebr>$EthnicityNamed</option>";
                                                }
                                                ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"><strong><span class="red"><strong>*</strong></span><strong></strong>1/2016 Circular Category</strong></td>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top"><select class="select2a_n" id="Cat2003Code" name="Cat2003Code">
                                                <!--<option value="">School Name</option>-->
                                                <?php
                                                $sql = "SELECT Cat2003Code,Cat2003Name FROM CD_CAT2003 order by Cat2003Name asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $Coded = trim($row['Cat2003Code']);
                                                    $EthnicityNamed = $row['Cat2003Name'];
                                                    $seltebr = "";
                                                    if ($Coded == $Cat2003Code) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$Coded\" $seltebr>$EthnicityNamed</option>";
                                                }
                                                ?>
                                            </select></td>
                                    </tr>
                                </table></td>
                        </tr>
                        <tr>
                            <td valign="top">&nbsp;</td>
                            <td valign="top">&nbsp;</td>
                        </tr>

                        <tr>
                            <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                    <tr>
                                        <td width="32%">&nbsp;</td>
                                        <td width="68%"><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                                    </tr>
                                </table></td>
                            <td valign="top">&nbsp;</td>
                        </tr>

                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                <?php } ?>


            </form> </div>
    </div>
<?php }
?>