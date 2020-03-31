<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
$nominiStatus = $id;
$approveStatus = $fm;
$requestApproveID = $tpe;
$sqlS = "SELECT id, Remarks
	FROM TG_Request_Approve
	WHERE (id = $requestApproveID)";
$resS = $db->runMsSqlQuery($sqlS);
$rowS = sqlsrv_fetch_array($resS, SQLSRV_FETCH_ASSOC);
$Remarks = $rowS['Remarks'];

$msg = "";
if (isset($_POST["RApproveFrmSubmit"])) {
    //echo "hi";
    $dateTime = date('Y-m-d H:i:s');

    $nominiStatus = $_REQUEST['nominiStatus'];
    $approveStatus = $_REQUEST['approveStatus'];
    $remarks = addslashes($_REQUEST['remarks']);



    if ($nominiStatus) {
        $queryRetirement = "UPDATE TG_Request_Approve SET Remarks = '$remarks',DateTime = '$dateTime' WHERE id = '$requestApproveID'";
        $saveStatus = $db->runMsSqlQuery($queryRetirement);

        if ($saveStatus)
            $msg = "Save successfully.";
        else
            $msg = "Save fail";
    }
    else {
        if ($approveStatus != "P") {
            $queryRetirement = "UPDATE TG_Request_Approve SET ApprovedStatus = '$approveStatus',Remarks = '$remarks',DateTime = '$dateTime' WHERE id = '$requestApproveID'";

            $saveStatus = $db->runMsSqlQuery($queryRetirement);

            if ($saveStatus) {
                $msg = "Save successfully.";
                $sql = "SELECT id, ApproveProcessOrder, ApprovedStatus, RequestType, RequestID
		FROM TG_Request_Approve
		WHERE (id = $requestApproveID)";
                $res = $db->runMsSqlQuery($sql);
                $row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC);
                $approveProcessOrder = $row['ApproveProcessOrder'];
                $requestID = $row['RequestID'];
                $requestType = $row['RequestType'];
                $approveProcessOrder = $approveProcessOrder + 1;
                $sqlUpdate = "UPDATE TG_Request_Approve SET ApprovedStatus = 'P' WHERE RequestID = '$requestID' AND RequestType = 'Retirement' AND ApproveProcessOrder = '$approveProcessOrder'";
                $saveStatus = $db->runMsSqlQuery($sqlUpdate);
            }
            else
                $msg = "Save fail.";
        }
        else {
            $msg = "Please select approve status.";
        }
    }






    //sqlsrv_query($queryGradeSave);
}
?>


<div class="main_content_inner_block">
    <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
<?php if ($msg != '') {//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){      ?>   
            <div class="mcib_middle1">
                <div class="mcib_middle_full">
                    <div class="form_error"><?php
    echo $msg;
    echo $_SESSION['success_update'];
    $_SESSION['success_update'] = "";
    ?><?php
                        echo $_SESSION['fail_update'];
                        $_SESSION['fail_update'] = "";
                        ?></div>
                </div>
                    <?php } ?>
            <table width="945" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="2%" valign="top">
                    </td>
                    <td width="33%" valign="top"></td>
                    <td width="10%" valign="top"></td>
                    <td width="55%" valign="top"></td>
                </tr>
                <tr>
                    <td height="25" colspan="4" style="font-size:13px"><strong>Personal Profile</strong></td>
                </tr>
<?php
//check personal 
$sql = "SELECT ID, NIC, SurnameWithInitials, FullName, Title, PerResRef, CurResRef, MobileTel, CONVERT(varchar(20),DOB,121) AS DOB, GenderCode, EthnicityCode, ReligionCode, CivilStatusCode, SpouseName, SpouseNIC, SpouseOccupationCode, SpouseDOB, SpouseOfficeAddr, DOFA, DOACAT, Province, emailaddr
FROM TeacherMast
WHERE (NIC = N'$nicNO')";

$stmt = $db->runMsSqlQuery($sql);
$personalStatus = false;
//$qulifiStatus = false;
$serviceStatus = false;
$assignmentStatus = false;
$showSubmitB = true;
$perInfoMore = false;
$qualiMore = false;
$servicefoMore = false;
$disActionMore = false;
$leaveMore = false;
$assignmentMore = false;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    foreach ($row as $data) {
        if ($data == "")
            $personalStatus = true;
    }
}

$sql1 = "SELECT ID
FROM StaffQualification
WHERE (NIC = N'$nicNO')";

$totQuli = $db->rowCount($sql1);

// check 1st appointment exist
$sql2 = "SELECT ID
FROM StaffServiceHistory
WHERE (NIC = N'$nicNO') AND (ServiceRecTypeCode = N'NA01')";
$totFService = $db->rowCount($sql2);

$sqlS1 = "SELECT
  CurServiceRef
FROM TeacherMast
WHERE (NIC = N'$nicNO')";
$stmtS1 = $db->runMsSqlQuery($sqlS1);

$rowS1 = sqlsrv_fetch_array($stmtS1, SQLSRV_FETCH_ASSOC);

$currentServiceID = $rowS1["CurServiceRef"];


$sqlS2 = "SELECT s.NIC, s.ID
FROM StaffServiceHistory s
INNER JOIN (
	SELECT NIC,MAX(AppDate) AS MaxDate
	FROM StaffServiceHistory
	GROUP BY NIC
) st ON s.NIC = st.NIC AND s.AppDate = st.MaxDate
WHERE (s.NIC = N'$nicNO')";
$stmtS2 = $db->runMsSqlQuery($sqlS2);

$rowS2 = sqlsrv_fetch_array($stmtS2, SQLSRV_FETCH_ASSOC);

$maxCurrentServiceID = $rowS2["ID"];

if ($currentServiceID != $maxCurrentServiceID) {
    $personalStatus = true;
}

if ($totFService < 1)
    $serviceStatus = true;

$personalStatus = false;
if ($personalStatus) {
    $pImg = "images/incomplete.png";
    $showSubmitB = false;
    $perInfoMore = true;
} else {
    $pImg = "images/complete_tick.png";
    // $showSubmitB = true;
}

if ($totQuli < 1) {
    $qImg = "images/incomplete.png";
    $showSubmitB = false;
    $qualiMore = true;
} else {
    $qImg = "images/complete_tick.png";
    // $showSubmitB = true;
}


if ($serviceStatus) {
    $sImg = "images/incomplete.png";
    $showSubmitB = false;
    $servicefoMore = true;
} else {
    $sImg = "images/complete_tick.png";
    //  $showSubmitB = true;
}

// end personal info
//check  Assignment
$sql3 = "SELECT ID, NIC
FROM StaffServiceHistory
WHERE (ServiceRecTypeCode IN ('AS10a', 'AS10b'))
AND (NIC = N'$nicNO')";
$assginmentIN = $db->rowCount($sql3);

$sql4 = "SELECT ID, NIC
FROM StaffServiceHistory
WHERE (ServiceRecTypeCode = ('DA06'))
AND (NIC = N'$nicNO')";
$assginmentOut = $db->rowCount($sql4);

if ($assginmentIN == $assginmentOut) {
    $amImg = "images/complete_tick.png";
} else {
    $amImg = "images/incomplete.png";
    $assignmentMore = true;
    $showSubmitB = false;
}
// end Assignment
//check leave
$sql5 = "SELECT  ID
FROM StaffServiceHistory
WHERE (ServiceRecTypeCode IN ('LV08a', 'LV08b', 'LV08e', 'LV08f', 'LV08g', 'LV08h', 'LV08i', 'LV08j'))
AND (NIC = N'$nicNO')";
$startLeave = $db->rowCount($sql5);

$sql6 = "SELECT  ID
FROM StaffServiceHistory
WHERE (ServiceRecTypeCode = ('DA03'))
AND (NIC = N'$nicNO')";
$endLeave = $db->rowCount($sql6);

if ($startLeave == $endLeave) {
    $leImg = "images/complete_tick.png";
} else {
    $leImg = "images/incomplete.png";
    $leaveMore = true;
    $showSubmitB = false;
}
//end leave
//check Disciplinary Actions
$sql7 = "SELECT
  ID
FROM StaffServiceHistory
WHERE (ServiceRecTypeCode = ('DS04'))
AND (NIC = N'$nicNO')";
$startDiscipline = $db->rowCount($sql7);

$sql8 = "SELECT
  ID
FROM StaffServiceHistory
WHERE (ServiceRecTypeCode = ('DA08'))
AND (NIC = N'$nicNO')";
$endDiscipline = $db->rowCount($sql8);

if ($startDiscipline == $endDiscipline) {
    $daImg = "images/complete_tick.png";
} else {
    $daImg = "images/incomplete.png";
    $disActionMore = true;
    $showSubmitB = false;
}

// end Disciplinary Actions
?>                
                <tr>
                    <td height="30">&nbsp;</td>
                    <td>Personal Information</td>
                    <td><img src="<?php echo $pImg; ?>"/></td>
                    <td><?php if ($perInfoMore) { ?><a href="http://emis-tss.schoolnet.lk" target="_blank">More Info</a><?php } ?></td>
                </tr>


                <tr>
                    <td height="30">&nbsp;</td>
                    <td>Qualification</td>
                    <td><img src="<?php echo $qImg; ?>"/></td>
                    <td><?php if ($qualiMore) { ?><a href="http://emis-tss.schoolnet.lk" target="_blank">More Info</a><?php } ?></td>
                </tr>

                <tr>
                    <td height="30">&nbsp;</td>
                    <td>Service</td>
                    <td><img src="<?php echo $sImg; ?>"/></td>
                    <td><?php if ($servicefoMore) { ?><a href="http://emis-tss.schoolnet.lk" target="_blank">More Info</a><?php } ?></td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>                
                <tr>
                    <td colspan="2" style="font-size:13px"><strong>Disciplinary Actions</strong></td>
                    <td><img src="<?php echo $daImg; ?>"/></td>
                    <td><?php if ($disActionMore) { ?><a href="http://emis-tss.schoolnet.lk" target="_blank">More Info</a><?php } ?></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td colspan="2" style="font-size:13px"><strong>Special Leaves</strong></td>
                    <td><img src="<?php echo $leImg; ?>"/></td>
                    <td><?php if ($leaveMore) { ?><a href="http://emis-tss.schoolnet.lk" target="_blank">More Info</a><?php } ?></td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>

                <tr>
                    <td colspan="2" style="font-size:13px"><strong>Assignment</strong></td>
                    <td><img src="<?php echo $amImg; ?>"/></td>
                    <td><?php if ($assignmentMore) { ?><a href="http://emis-tss.schoolnet.lk" target="_blank">More Info</a><?php } ?></td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td height="20" colspan="4">&nbsp;</td>
                </tr>
<?php
$showSubmitB = true;
if ($showSubmitB) {
    ?>
                    <tr>
                        <td colspan="4" ><fieldset style="border:1px solid #80ACEE; border-radius:10px;"><legend style="font-size:13px; color: #666666;"><b>Approve Employee</b></legend>
                                <table width="100%" border="0">
                                    <tr>
                                        <td width="15%">Approve Status :</td>
                                        <td width="85%"><select name="approveStatus" <?php if ($nominiStatus) { ?>disabled<?php } ?>>                                            
                                                <option value="P"<?php if (trim($approveStatus) == 'P') { ?> selected="selected"<?php } ?>>Pending</option>


                                                <option value="A" <?php if (trim($approveStatus) == 'A') { ?> selected="selected"<?php } ?>>Approve</option> 
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="80">Remarks :</td>
                                        <td><textarea style="height:60px; width:750px;" name="remarks"><?php echo $Remarks; ?></textarea></td>
                                    </tr>
                                    <tr>
                                        <td height="40">
                                            <input type="hidden" name="nominiStatus" value="<?php echo $nominiStatus; ?>"/>
                                        </td>
                                        <td><input name="RApproveFrmSubmit" type="submit" id="RApproveFrmSubmit"  style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value=""/></td>
                                    </tr>
                                </table>
                            </fieldset></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td height="35" colspan="2">&nbsp;</td>
                    </tr>
    <?php
}
?>

            </table>
        </div>

    </form>
</div>