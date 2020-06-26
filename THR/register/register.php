<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<link type='text/css' href='../assets/css/dashboard.css' rel='stylesheet' media='screen'/>
<link rel="stylesheet" href="../assets/css/jquery-ui.css">

<script src="../assets/js/jquery-latest.min.js" type="text/javascript"></script>
<script src="../assets/js/jquery-ui.js"></script>
<script src="../assets/js/back/script.js"></script>

<style>
    .fields_errors{
        border-color: rgba(229, 103, 23, 0.8);
        box-shadow: 0 1px 1px rgba(229, 103, 23, 0.075) inset, 0 0 8px rgba(229, 103, 23, 0.6);
        outline: 0 none;
    }

</style>

<?php
$curser = "document.frmSave.NIC.focus()";
include_once '../approveProcessfunction.php';
include('../smservices/sms.php');
include('../activityLog.php');
$msg = "";
$success = "";

$reqTab = "SELECT [ID],[NIC] FROM [dbo].[TG_EmployeeRegister] WHERE IsApproved='R'";
$stmtE = $db->runMsSqlQuery($reqTab);

while ($rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC)) {

    $NICR = $rowE['NIC'];

    $queryTmpDel = "DELETE FROM ArchiveUP_TeacherMast WHERE NIC='$NICR'";
    $db->runMsSqlQuery($queryTmpDel);

    $queryTmpDel = "DELETE FROM ArchiveUP_StaffAddrHistory WHERE NIC='$NICR'";
    $db->runMsSqlQuery($queryTmpDel);

    $queryTmpDel = "DELETE FROM ArchiveUP_StaffServiceHistory WHERE NIC='$NICR'";
    $db->runMsSqlQuery($queryTmpDel);

    $queryTmpDel = "DELETE FROM ArchiveUP_StaffAssignDetails WHERE NIC='$NICR'";
    $db->runMsSqlQuery($queryTmpDel);

    $queryTmpDel = "DELETE FROM TG_EmployeeRegister WHERE NIC='$NICR'";
    $db->runMsSqlQuery($queryTmpDel);
}


if (isset($_POST["FrmSubmit"])) {



    $appProcType = "NewRegistration";
    //$AccessRoleValue=$_REQUEST['AccessRoleValue'];
    $dateU = date('Y-m-d H:i:s');
    $dateUP = date('Y-m-d');

    //teacher mast
    $NIC = $_REQUEST['NIC'];
    if ($NICUser == '')
        $NICUser = $NIC;
    $UpdateBy = "Add by $NICUser";


    $Title = $_REQUEST['Title'];
    $SurnameWithInitials = $_REQUEST['SurnameWithInitials'];
    $FullName = $_REQUEST['FullName'];
    $DOB = $_REQUEST['DOB'];
    $CivilStatusCode = $_REQUEST['CivilStatusCode'];
    $EthnicityCode = $_REQUEST['EthnicityCode'];
    $GenderCode = $_REQUEST['GenderCode'];
    $ReligionCode = $_REQUEST['ReligionCode'];

    //address history
    $Address = $_REQUEST['Address'];
    $DISTCode = $_REQUEST['DISTCode'];
    $DSCode = $_REQUEST['DSCode'];
    $GSDivision = $_REQUEST['GSDivision'];
    $Tel = $_REQUEST['Tel'];
    $MobileTel = $_REQUEST['MobileTel'];
    $emailaddr = $_REQUEST['emailaddr'];
    $AppDateAdd = $_REQUEST['AppDateAdd'];

    $AddressT = $_REQUEST['AddressT'];
    $DISTCodeT = $_REQUEST['DISTCodeT'];
    $DSCodeT = $_REQUEST['DSCodeT'];
    $GSDivisionT = $_REQUEST['GSDivisionT'];
    $TelT = $_REQUEST['TelT'];
    //$MobileTelT = $_REQUEST['MobileTelT'];
    //$emailaddrT = $_REQUEST['emailaddrT'];
    $AppDateAddT = $_REQUEST['AppDateAddT'];

    //service history first
    $DistrictCodeF = $_REQUEST['DistrictCodeF'];
    $ZoneCodeF = $_REQUEST['ZoneCodeF'];
    $DivisionCodeF = $_REQUEST['DivisionCodeF'];
    $InstCodeF = $_REQUEST['InstCodeF'];
    $ServiceRecTypeCodeF = $_REQUEST['ServiceRecTypeCodeF'];
    $AppDateF = $_REQUEST['AppDateF'];
    $SecGRCodeF = $_REQUEST['SecGRCodeF'];
    $PositionCodeF = $_REQUEST['PositionCodeF'];
    $ServiceTypeCodeF = $_REQUEST['ServiceTypeCodeF'];
    $Cat2003CodeF = $_REQUEST['Cat2003CodeF'];
    $PFReferenceF = $_REQUEST['PFReferenceF'];
    $firstAppStatus = $_REQUEST['firstAppStatus'];
    $WorkStatusCode = "";
    $EmpTypeCode = "";
    $AssignInstDetails = "";

    //service history current
    $DistrictCode = $_REQUEST['DistrictCode'];
    $ZoneCode = $_REQUEST['ZoneCode'];
    $DivisionCode = $_REQUEST['DivisionCode'];
    $InstCode = $_REQUEST['InstCode'];
    $ServiceRecTypeCode = $_REQUEST['ServiceRecTypeCode'];
    $AppDate = $_REQUEST['AppDate'];
    $SecGRCode = $_REQUEST['SecGRCode'];
    $PositionCode = $_REQUEST['PositionCode'];
    $ServiceTypeCode = $_REQUEST['ServiceTypeCode'];
    $Cat2003Code = $_REQUEST['Cat2003Code'];
    $PFReference = $_REQUEST['PFReference'];



    //Check user duplicate

    $countSql = "SELECT NIC FROM TeacherMast where NIC='$NIC'";
    $isAvailable = $db->rowAvailable($countSql);
    if ($isAvailable == 1) {
        $msg .= "Already Registered.<br>";
    }

    $countSql = "SELECT * FROM ArchiveUP_TeacherMast where NIC='$NIC'";
    $isAvailable = $db->rowAvailable($countSql);
    if ($isAvailable == 1) {
        $msg .= "Already Registered. Approval pending.<br>";
    }

    $countSql = "SELECT * FROM ArchiveUP_StaffAddrHistory where NIC='$NIC'";
    $isAvailable = $db->rowAvailable($countSql);
    if ($isAvailable == 1) {
        $msg .= "Duplicate Contact Information.<br>";
    }

    $countSql = "SELECT * FROM ArchiveUP_StaffServiceHistory where NIC='$NIC'  and ServiceRecTypeCode!='NA01'";
    $isAvailable = $db->rowAvailable($countSql);
    if ($isAvailable == 1) {
        $msg .= "Duplicate Details of present appointment.<br>";
    }

    $countSql = "SELECT * FROM ArchiveUP_StaffServiceHistory where NIC='$NIC' and ServiceRecTypeCode='NA01'";
    $isAvailable = $db->rowAvailable($countSql);
    if ($isAvailable == 1) {
        $msg .= "Duplicate Details of the first appointment.<br>";
    }
    // end check user duplication


    if ($msg == "") {

        // Insert contact info
        $queryMainSave = "INSERT INTO ArchiveUP_StaffAddrHistory
				   (NIC,AddrType,Address,DSCode,DISTCode,Tel,AppDate,UpdateBy,LastUpdate,RecordLog,GSDivision)
			 VALUES
				   ('$NIC','PER','$Address','$DSCode','$DISTCode','$Tel','$AppDateAdd','$UpdateBy','$dateU','register','$GSDivision')";

        $db->runMsSqlQuery($queryMainSave);

        $reqTabMobAc = "SELECT ID FROM ArchiveUP_StaffAddrHistory where NIC='$NIC' and AddrType='PER' ORDER BY ID DESC";
        $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
        $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
        $PerResRefID = trim($rowMobAc['ID']);


        $queryMainSaveCUR = "INSERT INTO ArchiveUP_StaffAddrHistory
				   (NIC,AddrType,Address,DSCode,DISTCode,Tel,AppDate,UpdateBy,LastUpdate,RecordLog,GSDivision)
			 VALUES
				   ('$NIC','CUR','$AddressT','$DSCodeT','$DISTCodeT','$TelT','$AppDateAddT','$UpdateBy','$dateU','register','$GSDivisionT')";

        $db->runMsSqlQuery($queryMainSaveCUR);

        $reqTabMobAc = "SELECT ID FROM ArchiveUP_StaffAddrHistory where NIC='$NIC' and AddrType='CUR' ORDER BY ID DESC";
        $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
        $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
        $CurResRefID = trim($rowMobAc['ID']);
        //End insert contact info
        // Insert service info

        if($firstAppStatus=="Y"){
                $ServiceRecTypeCodeF = 'NA01';


                //first appointment  start
                $queryMainSave = "INSERT INTO ArchiveUP_StaffServiceHistory
                                       (NIC,ServiceRecTypeCode,AppDate,InstCode,SecGRCode,WorkStatusCode,ServiceTypeCode,EmpTypeCode,PositionCode,Cat2003Code,Reference,UpdateBy,LastUpdate,RecordLog)
                             VALUES
                                       ('$NIC','$ServiceRecTypeCodeF','$AppDateF','$InstCodeF','$SecGRCodeF','$WorkStatusCode','$ServiceTypeCodeF','$EmpTypeCode','$PositionCodeF','$Cat2003CodeF','$PFReferenceF','$UpdateBy','$dateU','register')";

                $db->runMsSqlQuery($queryMainSave);

                $reqTabMobAc = "SELECT ID FROM ArchiveUP_StaffServiceHistory where NIC='$NIC' ORDER BY ID DESC";
                $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
                $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
                $FirstAppID = trim($rowMobAc['ID']);

                //first appointment assign start
                $queryfirstAss = "INSERT INTO ArchiveUP_StaffAssignDetails			   (NIC,ServiceRecRef,AssignInstCode,AssignInstDetails,StartDate,EndDate,AssignbedPositionCode,Reference,UpdateBy,LastUpdate,RecordLog)
                                     VALUES			   ('$NIC','$FirstAppID','$InstCodeF','$AssignInstDetails','$AppDateF','','$PositionCodeF','register','$UpdateBy','$dateU','register')";
                $db->runMsSqlQuery($queryfirstAss);

                //first appointment assign end

        }


        //
        //
        //current appointment start

        $queryCurrentSave = "INSERT INTO ArchiveUP_StaffServiceHistory			   (NIC,ServiceRecTypeCode,AppDate,InstCode,SecGRCode,WorkStatusCode,ServiceTypeCode,EmpTypeCode,PositionCode,Cat2003Code,Reference,UpdateBy,LastUpdate,RecordLog)
			 VALUES
				   ('$NIC','$ServiceRecTypeCode','$AppDate','$InstCode','$SecGRCode','$WorkStatusCode','$ServiceTypeCode','$EmpTypeCode','$PositionCode','$Cat2003Code','$PFReference','$UpdateBy','$dateU','register')";

        $db->runMsSqlQuery($queryCurrentSave);

        $reqTabMobAc = "SELECT ID FROM ArchiveUP_StaffServiceHistory where NIC='$NIC' ORDER BY ID DESC";
        $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
        $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
        $CurServiceRefID = trim($rowMobAc['ID']);


        //current appointment assign start
        $querycurrentAss = "INSERT INTO ArchiveUP_StaffAssignDetails				   (NIC,ServiceRecRef,AssignInstCode,AssignInstDetails,StartDate,EndDate,AssignbedPositionCode,Reference,UpdateBy,LastUpdate,RecordLog)
				 VALUES				   ('$NIC','$CurServiceRefID','$InstCode','$AssignInstDetails','$AppDate','','$PositionCode','register','$UpdateBy','$dateU','register')";
        $db->runMsSqlQuery($querycurrentAss);

        //current appointment assign end
        // End insert service info

        /* --------------------------------------------- */

        //teacher mast start

        $newIDMast = 0;

        $queryMainSavex = "INSERT INTO ArchiveUP_TeacherMast (
	NIC,
	SurnameWithInitials,
	FullName,
	Title,
	PerResRef,
	MobileTel,
	emailaddr,
	DOB,
	GenderCode,
	EthnicityCode,
	ReligionCode,
	CivilStatusCode,
	CurServiceRef,
	LastUpdate,
	UpdateBy,
	RecordLog,
	CurResRef,
        DOFA,
        DOACAT
)
VALUES
	(
		'$NIC',
		'$SurnameWithInitials',
		'$FullName',
		'$Title',
		'$PerResRefID',
		'$MobileTel',
		'$emailaddr',
		'$DOB',
		'$GenderCode',
		'$EthnicityCode',
		'$ReligionCode',
		'$CivilStatusCode',
		'$CurServiceRefID',
		'$dateU',
		'$UpdateBy',
		'register',
		'$CurResRefID',
                '$AppDateF',
                '$Cat2003CodeF'
	)";

        $db->runMsSqlQuery($queryMainSavex);

        $reqTabMobAc = "SELECT ID FROM ArchiveUP_TeacherMast where NIC='$NIC'";
        $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
        $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
        $newIDMast = trim($rowMobAc['ID']);

        if ($newIDMast == 0)
            $msg = "Error on page. Please check your internet connection, Details without special character and try again";

        //}
        //teacher mast end
        if ($msg == '') {
            $queryRegis = "INSERT INTO TG_EmployeeRegister				   (NIC,TeacherMastID,ServisHistCurrentID,ServisHistFirstID,AddressHistID,dDateTime,ZoneCode,IsApproved,ApproveComment,ApproveDate,ApprovedBy,UpdateBy,AddressHistIDCur)
					 VALUES				   ('$NIC','$newIDMast','$CurServiceRefID','$FirstAppID','$PerResRefID','$dateU','$ZoneCode','N','','','','$NICUser','$CurResRefID')";
            $db->runMsSqlQuery($queryRegis);

            $success = "Form submitted successfully. Data will be appeared after the management approvals";

            audit_trail($NIC, $NICUser, 'register.php', 'insert', 'ArchiveUP_TeacherMast', 'User temporary Registered');


            $tpNumber = numberFormat($MobileTel);

            /* Send SMS via GOV SMS */
            $sms_content = 'Registration request submitted successfully';
            $config = array('message' => $sms_content, 'recepient' => $tpNumber); //0779105338
            $smso = new sms();
            $result = $smso->sendsms($config);
            if ($result[0] == 1) {
                //SMS Sent
                //echo 'ok';
                $statusOf = "Success";
            } else {
                //SMS wasn't Sent
                //echo 'error';
                $statusOf = "Fail";
            }
            //end SMS
            $queryRegissms = "INSERT INTO TG_SMS (NIC,ModuleName,dDateTime,StatusOf,RecID) VALUES ('$NIC','Registration','$dateU','$statusOf','0')";
            $db->runMsSqlQuery($queryRegissms);
        }
    }
}
?>
<body onLoad="<?php echo $curser ?>">
    <div class="main_content_inner_block">
        <div class="mcib_middleReg"><form method="post" action="" name="frmSrch" id="frmSrch"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                        <td width="19%">Check Availability</td>
                        <td width="27%"><input name="NICSearch" type="text" class="input2_n" id="NICSearch" value="" placeholder="NIC" /></td>
                        <td width="11%"><div style="margin-top:5px;"><a onClick="Javascript:show_available('availabaleS', document.frmSrch.NICSearch.value, '');"><img src="../cms/images/searchN.png" width="84" height="26" /></a></div></td>
                        <td width="43%"><div id="txt_available" style="font-weight:bold;"></div></td>
                    </tr>
                </table></form>
        </div>
        <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="">
            <!-- validation error both server side & client side -->
            <div class="error" style="display: none;">
                <div id="dialog" title="Error" style="display: none;">
                    <p>Please fill required information.</p>
                </div>
            </div><!--enderror-->

            <div class="mcib_middle1">
                <?php if ($msg != '' || $success != '') {//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){     ?>
                    <div class="mcib_middle_full">
                        <div class="form_error"><?php
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
                            <td colspan="2" align="center" height="30px" style="font-size:16px; font-weight:bold;"><u>Registration Form</u></td>
                        <tr>
                            <td colspan="2" valign="top">&nbsp;</td>
                        <tr>
                            <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;"><strong>Personal Information</strong></td>
                        <tr>
                            <td valign="top">&nbsp;</td>
                            <td align="right" valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="50%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                    <tr>
                                        <td width="30%" align="left" valign="top">NIC <span class="form_error_sched">*</span> </td>
                                        <td width="3%" align="left" valign="top"><strong>:</strong></td>
                                        <td width="67%" align="left" valign="top"><input name="NIC" type="text" class="input2_n" id="NIC" value="" tabindex="1" onchange="CheckNIC()"/>
                                            <input type="hidden" name="perAddStatus" value="<?php echo $perAddStatus ?>" />
                                            <input type="hidden" name="curAddStatus" value="<?php echo $curAddStatus ?>" />
                                            <input type="hidden" name="pMastStatus" value="<?php echo $pMastStatus ?>" />

                                            <div id="nicerror" style="color: #F00; display: none;">

                                                <br>
                                                Entered NIC is not valid</div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">Title <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><select class="select2a_n" id="Title" name="Title" tabindex="2">
                                                <!--<option value="">School Name</option>-->
                                                <option value="">Select</option>
                                                <?php
                                                $sql = "SELECT TitleCode,TitleName FROM CD_Title order by TitleName asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $TitleCodedb = trim($row['TitleCode']);
                                                    $TitleName = $row['TitleName'];
                                                    $seltebr = "";
                                                    if ($TitleCodedb == $TitleCode) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$TitleCodedb\" $seltebr>$TitleName</option>";
                                                }
                                                ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">Surname with Initials <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><input name="SurnameWithInitials" type="text" class="input2_n" id="SurnameWithInitials" value="<?php //echo $SurnameWithInitials      ?>" tabindex="3"/></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">Full Name <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><input name="FullName" type="text" class="input2_n" id="FullName" value="<?php //echo $FullName      ?>" tabindex="4"/></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">Date of Birth <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><table width="100%" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td width="13%"><input name="DOB" type="text" class="input3new" id="DOB" value="<?php //echo $DOB;       ?>" size="10" style="width:100px;" readonly/>
                                                    </td>
                                                    <td width="87%">
                                                        <input name="f_trigger_1" type="image" id="f_trigger_1" src="../cms/images/calender_icon.gif" align="top" width="16" height="16"  tabindex="5"/>
                                                        <script type="text/javascript">
                                                            //2005-10-03 11:46:00
                                                            Calendar.setup({
                                                                inputField: "DOB", // id of the input field
                                                                ifFormat: "%Y-%m-%d", // format of the input field
                                                                showsTime: false, // will display a time selector
                                                                button: "f_trigger_1", // trigger for the calendar (button ID)
                                                                singleClick: true, // double-click mode
                                                                step: 1                // show all years in drop-down boxes (instead of every other year as default)
                                                            });
                                                        </script>
                                                    </td>
                                                </tr>
                                            </table></td>
                                    </tr>

                                </table>
                            </td>
                            <td width="50%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                    <tr>
                                        <td align="left" valign="top">Civil Status <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top">:</td>
                                        <td align="left" valign="top"><select class="select2a_n" id="CivilStatusCode" name="CivilStatusCode" tabindex="6">
                                                <!--<option value="">School Name</option>-->
                                                <option value="">Select</option>
                                                <?php
                                                $sql = "SELECT [Code],[CivilStatusName] FROM CD_CivilStatus order by Code asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $CodeC = trim($row['Code']);
                                                    $CivilStatusName = $row['CivilStatusName'];
                                                    $seltebr = "";
                                                    if ($CodeC == $CivilStatusCodex) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$CodeC\" $seltebr>$CivilStatusName</option>";
                                                }
                                                ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td width="38%" align="left" valign="top">Ethnicity <span class="form_error_sched">*</span></td>
                                        <td width="3%" align="left" valign="top"><strong>:</strong></td>
                                        <td width="59%" align="left" valign="top"><?php //echo $EthnicityName      ?>
                                            <select class="select2a_n" id="EthnicityCode" name="EthnicityCode" tabindex="7">
                                                <!--<option value="">School Name</option>-->
                                                <option value="">Select</option>
                                                <?php
                                                $sql = "SELECT Code,EthnicityName FROM CD_nEthnicity order by EthnicityName asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $Coded = trim($row['Code']);
                                                    $EthnicityNamed = $row['EthnicityName'];
                                                    $seltebr = "";
                                                    if ($Coded == $EthnicityCodex) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$Coded\" $seltebr>$EthnicityNamed</option>";
                                                }
                                                ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">Gender <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><?php //echo $GenderName      ?>
                                            <select class="select2a_n" id="GenderCode" name="GenderCode" tabindex="8">
                                                <!--<option value="">School Name</option>-->
                                                <option value="">Select</option>
                                                <?php
                                                $sql = "SELECT [GenderCode],[Gender Name] FROM CD_Gender order by GenderCode asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $GenderCoded = trim($row['GenderCode']);
                                                    $GenderName = $row['Gender Name'];
                                                    $seltebr = "";
                                                    if ($GenderCoded == $GenderCodex) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$GenderCoded\" $seltebr>$GenderName</option>";
                                                }
                                                ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">Religion <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><?php //echo $ReligionName      ?>
                                            <select class="select2a_n" id="ReligionCode" name="ReligionCode" tabindex="9">
                                                <!--<option value="">School Name</option>-->
                                                <option value="">Select</option>
                                                <?php
                                                $sql = "SELECT Code,ReligionName FROM CD_Religion order by ReligionName asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $Coded = trim($row['Code']);
                                                    $ReligionNamed = $row['ReligionName'];
                                                    $seltebr = "";
                                                    if ($Coded == $ReligionCodex) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$Coded\" $seltebr>$ReligionNamed</option>";
                                                }
                                                ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                </table></td>
                        </tr>
                        <tr>
                            <td valign="top">&nbsp;</td>
                            <td valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;"><strong>Contact Information (Permanent)</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                    <tr>
                                        <td align="left" valign="top">Address <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top">:</td>
                                        <td width="34%" rowspan="3" align="left" valign="top">
                                            <textarea name="Address" cols="45" rows="5" class="textarea1a" id="Address" tabindex="10"><?php //echo $Address       ?></textarea></td>
                                        <td align="left" valign="top">GS Division</td>
                                        <td align="left" valign="top">:</td>
                                        <td align="left" valign="top"><input name="GSDivision" type="text" class="input2_n" id="GSDivision" value="<?php //echo $Tel       ?>" tabindex="13"/></td>
                                    </tr>
                                    <tr>
                                        <td width="15%" align="left" valign="top">&nbsp;</td>
                                        <td width="1%" align="left" valign="top">&nbsp;</td>
                                        <td width="19%" align="left" valign="top">Telephone</td>
                                        <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                        <td width="30%" align="left" valign="top"><input name="Tel" type="text" class="input2_n" id="Tel" value="<?php //echo $Tel       ?>" tabindex="14"/></td>
                                    </tr>
                                    <tr>
                                        <td height="31" align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top">Mobile Number <span class="form_error_sched">*</span><br>
                                            (eg:- 0123456789)</td>
                                        <td align="left" valign="top">:</td>
                                        <td align="left" valign="top"><input name="MobileTel" type="text" class="input2_n" id="MobileTel" value="<?php //echo $MobileTel       ?>" tabindex="15"/></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">District</td>
                                        <td align="left" valign="top">:</td>
                                        <td width="34%" align="left" valign="top"><?php //echo $DistCode       ?>
                                            <select class="select2a_n" id="DISTCode" name="DISTCode" onChange="Javascript:show_divisionAdd('divisionlst', this.options[this.selectedIndex].value, '');" tabindex="11">
                                                <!--<option value="">School Name</option>-->
                                                <option value="">Select</option>
                                                <?php
                                                $sql = "SELECT DistCode,DistName FROM CD_Districts order by DistName asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $DistCoded = trim($row['DistCode']);
                                                    $DistNamed = $row['DistName'];
                                                    $seltebr = "";
                                                    if ($DistCoded == $DistCodex) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$DistCoded\" $seltebr>$DistNamed</option>";
                                                }
                                                ?>
                                            </select></td>
                                        <td align="left" valign="top">Email Address</td>
                                        <td align="left" valign="top">:</td>
                                        <td align="left" valign="top"><input name="emailaddr" type="text" class="input2_n" id="emailaddr" value="<?php //echo $emailaddr      ?>" tabindex="16"/></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"> Division</td>
                                        <td align="left" valign="top">:</td>
                                        <td width="34%" align="left" valign="top"><?php //echo $DSName      ?>
                                            <div id="txt_divisionAdd"><select class="select2a_n" id="DSCode" name="DSCode" tabindex="12">
                                                    <!--<option value="">School Name</option>-->
                                                    <option value="">Select</option>
                                                    <?php
                                                    $sql = "SELECT DSCode,DSName FROM CD_DSec where DistName='$DistCode' order by DSName asc";
                                                    $stmt = $db->runMsSqlQuery($sql);
                                                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                        $DSCoded = trim($row['DSCode']);
                                                        $DSNamed = $row['DSName'];
                                                        $seltebr = "";
                                                        if ($DSCoded == $DSCodex) {
                                                            $seltebr = "selected";
                                                        }
                                                        echo "<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
                                                    }
                                                    ?>
                                                </select></div></td>
                                        <td rowspan="2" align="left" valign="top">Date from which you have been residing in this address</td>
                                        <td align="left" valign="top">:</td>
                                        <td align="left" valign="top"><table width="100%" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td width="13%"><input name="AppDateAdd" type="text" class="input3new" id="AppDateAdd" value="<?php //echo $AppDate;      ?>" size="10" style="width:100px;" readonly="readonly"/></td>
                                                    <td width="87%"><input name="f_trigger_3" type="image" id="f_trigger_3" src="../cms/images/calender_icon.gif" align="top" width="16" height="16" tabindex="17"/>
                                                        <script type="text/javascript">
                                                            //2005-10-03 11:46:00
                                                            Calendar.setup({
                                                                inputField: "AppDateAdd", // id of the input field
                                                                ifFormat: "%Y-%m-%d", // format of the input field
                                                                showsTime: false, // will display a time selector
                                                                button: "f_trigger_3", // trigger for the calendar (button ID)
                                                                singleClick: true, // double-click mode
                                                                step: 1                // show all years in drop-down boxes (instead of every other year as default)
                                                            });
                                                        </script></td>
                                                </tr>
                                            </table></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td width="34%" align="left" valign="top"></td>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                </table></td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;"><strong>Contact Information (Temporary)</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                    <tr>
                                        <td align="left" valign="top">Address</td>
                                        <td align="left" valign="top">:</td>
                                        <td width="34%" rowspan="3" align="left" valign="top">
                                            <textarea name="AddressT" cols="45" rows="5" class="textarea1a" id="AddressT" tabindex="18"><?php //echo $Address       ?></textarea></td>
                                        <td align="left" valign="top">GS Division</td>
                                        <td align="left" valign="top">:</td>
                                        <td align="left" valign="top"><input name="GSDivisionT" type="text" class="input2_n" id="GSDivisionT" value="<?php //echo $Tel       ?>" tabindex="21"/></td>
                                    </tr>
                                    <tr>
                                        <td width="15%" align="left" valign="top">&nbsp;</td>
                                        <td width="1%" align="left" valign="top">&nbsp;</td>
                                        <td width="19%" align="left" valign="top">Telephone</td>
                                        <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                        <td width="30%" align="left" valign="top"><input name="TelT" type="text" class="input2_n" id="TelT" value="<?php //echo $Tel       ?>" tabindex="22"/></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td rowspan="4" align="left" valign="top">Date from which you have been residing in this address</td>
                                        <td align="left" valign="top">:</td>
                                        <td align="left" valign="top"><table width="100%" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td width="13%"><input name="AppDateAddT" type="text" class="input3new" id="AppDateAddT" value="<?php //echo $AppDate;       ?>" size="10" style="width:100px;" readonly="readonly"/></td>
                                                    <td width="87%"><input name="f_trigger_35" type="image" id="f_trigger_35" src="../cms/images/calender_icon.gif" align="top" width="16" height="16" tabindex="23"/>
                                                        <script type="text/javascript">
                                                            //2005-10-03 11:46:00
                                                            Calendar.setup({
                                                                inputField: "AppDateAddT", // id of the input field
                                                                ifFormat: "%Y-%m-%d", // format of the input field
                                                                showsTime: false, // will display a time selector
                                                                button: "f_trigger_35", // trigger for the calendar (button ID)
                                                                singleClick: true, // double-click mode
                                                                step: 1                // show all years in drop-down boxes (instead of every other year as default)
                                                            });
                                                        </script></td>
                                                </tr>
                                            </table></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">District</td>
                                        <td align="left" valign="top">:</td>
                                        <td width="34%" align="left" valign="top"><?php //echo $DistCode       ?>
                                            <select class="select2a_n" id="DISTCodeT" name="DISTCodeT" onChange="Javascript:show_divisionAddTmp('divisionlstTmp', this.options[this.selectedIndex].value, '');" tabindex="19">
                                                <!--<option value="">School Name</option>-->
                                                <option value="">Select</option>
                                                <?php
                                                $sql = "SELECT DistCode,DistName FROM CD_Districts order by DistName asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $DistCoded = trim($row['DistCode']);
                                                    $DistNamed = $row['DistName'];
                                                    $seltebr = "";
                                                    if ($DistCoded == $DistCodex) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$DistCoded\" $seltebr>$DistNamed</option>";
                                                }
                                                ?>
                                            </select></td>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top"></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"> Division</td>
                                        <td align="left" valign="top">:</td>
                                        <td width="34%" align="left" valign="top"><?php //echo $DSName      ?><div id="txt_divisionAddTmp"><select class="select2a_n" id="DSCodeT" name="DSCodeT" tabindex="20">
                                                    <!--<option value="">School Name</option>-->
                                                    <option value="">Select</option>
                                                    <?php
                                                    $sql = "SELECT DSCode,DSName FROM CD_DSec where DistName='$DistCode' order by DSName asc";
                                                    $stmt = $db->runMsSqlQuery($sql);
                                                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                        $DSCoded = trim($row['DSCode']);
                                                        $DSNamed = $row['DSName'];
                                                        $seltebr = "";
                                                        if ($DSCoded == $DSCodex) {
                                                            $seltebr = "selected";
                                                        }
                                                        echo "<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
                                                    }
                                                    ?>
                                                </select></div></td>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top"></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td width="34%" align="left" valign="top"></td>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top">&nbsp;</td>
                                    </tr>
                                </table></td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;"><strong>Details of present appointment (to the School/ Institution from which your salary is being paid at present)</strong></td>
                        </tr>

                        <tr>
                            <td colspan="2" valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                    <tr>
                                        <td width="15%" align="left" valign="top">District <span class="form_error_sched">*</span></td>
                                        <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                        <td width="34%" align="left" valign="top"><select class="select2a_n" id="DistrictCode" name="DistrictCode" onChange="Javascript:show_zone('zonelist', this.options[this.selectedIndex].value, '');" tabindex="24">
                                                <!--<option value="">School Name</option>-->
                                                <option value="">Select</option>
                                                <?php
                                                $sql = "SELECT DistCode,DistName FROM CD_Districts order by DistName asc";
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
                                            </select></td>
                                        <td width="22%" align="left" valign="top">Effective Date</td>
                                        <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                        <td width="27%" align="left" valign="top"><table width="100%" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td width="13%"><input name="AppDate" type="text" class="input3new" id="AppDate" value="<?php //echo $AppDate;      ?>" size="10" style="width:100px;" readonly/>
                                                    </td>
                                                    <td width="87%">
                                                        <input name="f_trigger_2" type="image" id="f_trigger_2" src="../cms/images/calender_icon.gif" align="top" width="16" height="16" tabindex="29"/>
                                                        <script type="text/javascript">
                                                            //2005-10-03 11:46:00
                                                            Calendar.setup({
                                                                inputField: "AppDate", // id of the input field
                                                                ifFormat: "%Y-%m-%d", // format of the input field
                                                                showsTime: false, // will display a time selector
                                                                button: "f_trigger_2", // trigger for the calendar (button ID)
                                                                singleClick: true, // double-click mode
                                                                step: 1                // show all years in drop-down boxes (instead of every other year as default)
                                                            });
                                                        </script>
                                                    </td>
                                                </tr>
                                            </table></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">Zone <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top">:</td>
                                        <td width="34%" align="left" valign="top"><div id="txt_zone"><select class="select2a_n" id="ZoneCode" name="ZoneCode" onChange="Javascript:show_division('divisionList', this.options[this.selectedIndex].value, document.frmSave.DistrictCode.value);" tabindex="25">
                                                    <option value="">Zone Name</option>
                                                    <?php
                                                    $sql = "SELECT CenCode,InstitutionName FROM CD_Zone where DistrictCode='$DistrictCode' order by InstitutionName asc";
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
                                                </select></div></td>
                                        <td align="left" valign="top">Section <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><select class="select2a_n" id="SecGRCode" name="SecGRCode" tabindex="30">
                                                <!--<option value="">School Name</option>-->
                                                <option value="">Select</option>
                                                <?php
                                                $sql = "SELECT [GradeCode],[GradeName] FROM CD_SecGrades order by GradeName asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $GradeCode = trim($row['GradeCode']);
                                                    $GradeName = $row['GradeName'];
                                                    $seltebr = "";
                                                    if ($GradeCode == $SecGRCodex) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$GradeCode\" $seltebr>$GradeName</option>";
                                                }
                                                ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">Division <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top">:</td>
                                        <td align="left" valign="top"><div id="txt_division">
                                                <select class="select2a_n" id="DivisionCode" name="DivisionCode" onChange="Javascript:show_cences('censesList', this.options[this.selectedIndex].value, document.frmSave.DistrictCode.value);" tabindex="26">
                                                    <option value="">Division Name</option>
                                                    <?php
                                                    $sql = "SELECT CenCode,InstitutionName FROM CD_Division where DistrictCode='$DistrictCode' and ZoneCode='$ZoneCode' order by InstitutionName asc";
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
                                        <td align="left" valign="top">Position <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top"><select class="select2a_n" id="PositionCode" name="PositionCode" tabindex="31"><option value="">Select</option>
                                                <!--<option value="">School Name</option>-->
                                                <?php
                                                $sql = "SELECT Code,PositionName FROM CD_Positions order by PositionName asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $Code = trim($row['Code']);
                                                    $PositionName = $row['PositionName'];
                                                    $seltebr = "";
                                                    if ($Code == $PositionCodex) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$Code\" $seltebr>$PositionName</option>";
                                                }
                                                ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">School/ Institution <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top">:</td>
                                        <td width="34%" align="left" valign="top"><div id="txt_showInstitute"><select class="select2a" id="InstCode" name="InstCode" tabindex="27">
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
  FROM [dbo].[CD_CensesNo] where DivisionCode='$DivisionCode'
  order by InstitutionName";
                                                    $stmt = $db->runMsSqlQuery($sql);
                                                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                        $CenCode = $row['CenCode'];
                                                        $InstitutionName = addslashes($row['InstitutionName']);
                                                        echo "<option value=\"$CenCode\">$InstitutionName $CenCode</option>";
                                                    }
                                                    ?>
                                                </select></div></td>
                                        <td align="left" valign="top">Service Grade <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><select class="select2a_n" id="ServiceTypeCode" name="ServiceTypeCode" tabindex="32">
                                                <!--<option value="">School Name</option>-->
                                                <option value="">Select</option>
                                                <?php
                                                $sql = "SELECT [ServCode],[ServiceName] FROM CD_Service order by ServiceName asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $ServCode = trim($row['ServCode']);
                                                    $ServiceName = $row['ServiceName'];
                                                    $seltebr = "";
                                                    if ($ServCode == $ServiceTypeCodex) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$ServCode\" $seltebr>$ServiceName</option>";
                                                }
                                                ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">Employment Basis <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top">:</td>
                                        <td width="34%" align="left" valign="top"><select class="select2a_n" id="ServiceRecTypeCode" name="ServiceRecTypeCode" tabindex="28" onchange="disable_fa(this.value);">
                                                <!--<option value="">School Name</option>-->
                                                <option value="">Select</option>
                                                <?php
                                                $sql = "SELECT [DutyCode],[Description] FROM CD_ServiceRecType order by Description asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $DutyCode = trim($row['DutyCode']);
                                                    $Description = $row['Description'];
                                                    $seltebr = "";
                                                    if ($DutyCode == $ServiceRecTypeCodex) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$DutyCode\" $seltebr>$Description</option>";
                                                }
                                                ?>
                                            </select></td>
                                        <td align="left" valign="top">1/2016 Circular Category <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><select class="select2a_n" id="Cat2003Code" name="Cat2003Code" tabindex="33">
                                                <!--<option value="">School Name</option>-->
                                                <option value="">Select</option>
                                                <?php
                                                $sql = "SELECT [Cat2003Code],[Cat2003Name] FROM CD_CAT2003 order by Cat2003Name asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $Cat2003Code = trim($row['Cat2003Code']);
                                                    $Cat2003Name = $row['Cat2003Name'];
                                                    $seltebr = "";
                                                    if ($Cat2003Code == $Cat2003Codex) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$Cat2003Code\" $seltebr>$Cat2003Name</option>";
                                                }
                                                ?>
                                            </select></td>
                                    </tr>
                                    <!--Added by Dharshana Requested by Mr. Niel Gunadasa 02/08/2018-->
                                    <tr>
                                        <td align="left" valign="top">Reference Number</td>
                                        <td align="left" valign="top">:</td>
                                        <td width="30%" align="left" valign="top"><input name="PFReference" type="text" class="input2_n" id="PFReference" value="" tabindex="14"/></td>
                                    </tr>

                                </table></td>
                        </tr>
                        <tr>
                            <td valign="top">&nbsp;</td>
                            <td valign="top">&nbsp;</td>
                        </tr>
                        <tr id="tr_fa1">
                            <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;"><strong>Details of the first appointment to government sector</strong></td>
                        </tr>
                        <tr id="tr_fa2">
                            <td colspan="2" valign="top">&nbsp;</td>
                        </tr>
                        <tr id="tr_fa3">
                            <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                    <tr>
                                        <td width="15%" align="left" valign="top">District <span class="form_error_sched">*</span></td>
                                        <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                        <td width="34%" align="left" valign="top"><select class="select2a_n" id="DistrictCodeF" name="DistrictCodeF" onChange="Javascript:show_zoneF('zonelistF', this.options[this.selectedIndex].value, '');" tabindex="34">
                                                <!--<option value="">School Name</option>-->
                                                <option value="">Select</option>
                                                <?php
                                                $sql = "SELECT DistCode,DistName FROM CD_Districts order by DistName asc";
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
                                            </select></td>
                                        <td width="22%" align="left" valign="top">Effective Date</td>
                                        <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                        <td width="27%" align="left" valign="top"><table width="100%" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td width="13%"><input name="AppDateF" type="text" class="input3new" id="AppDateF" value="<?php //echo $AppDateF;      ?>" size="10" style="width:100px;" readonly="readonly"/></td>
                                                    <td width="87%"><input name="f_trigger_4" type="image" id="f_trigger_4" src="../cms/images/calender_icon.gif" align="top" width="16" height="16" tabindex="38"/>
                                                        <script type="text/javascript">
                                                            //2005-10-03 11:46:00
                                                            Calendar.setup({
                                                                inputField: "AppDateF", // id of the input field
                                                                ifFormat: "%Y-%m-%d", // format of the input field
                                                                showsTime: false, // will display a time selector
                                                                button: "f_trigger_4", // trigger for the calendar (button ID)
                                                                singleClick: true, // double-click mode
                                                                step: 1                // show all years in drop-down boxes (instead of every other year as default)
                                                            });
                                                        </script></td>
                                                </tr>
                                            </table></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">Zone <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top">:</td>
                                        <td width="34%" align="left" valign="top"><div id="txt_zoneF">
                                                <select class="select2a_n" id="ZoneCodeF" name="ZoneCodeF" onChange="Javascript:show_divisionF('divisionListF', this.options[this.selectedIndex].value, document.frmSave.DistrictCodeF.value);" tabindex="35">
                                                    <option value="">Zone Name</option>
                                                    <?php
                                                    $sql = "SELECT CenCode,InstitutionName FROM CD_Zone where DistrictCode='$DistrictCode' order by InstitutionName asc";
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
                                        <td align="left" valign="top">Section <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><select class="select2a_n" id="SecGRCodeF" name="SecGRCodeF" tabindex="39">
                                                <!--<option value="">School Name</option>-->
                                                <option value="">Select</option>
                                                <?php
                                                $sql = "SELECT [GradeCode],[GradeName] FROM CD_SecGrades order by GradeName asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $GradeCode = trim($row['GradeCode']);
                                                    $GradeName = $row['GradeName'];
                                                    $seltebr = "";
                                                    if ($GradeCode == $SecGRCodex) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$GradeCode\" $seltebr>$GradeName</option>";
                                                }
                                                ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">Division <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top">:</td>
                                        <td align="left" valign="top"><div id="txt_divisionF">
                                                <select class="select2a_n" id="DivisionCodeF" name="DivisionCodeF" onChange="Javascript:show_cencesF('censesListF', this.options[this.selectedIndex].value, document.frmSave.DistrictCodeF.value);" tabindex="36">
                                                    <option value="">Division Name</option>
                                                    <?php
                                                    $sql = "SELECT CenCode,InstitutionName FROM CD_Division where DistrictCode='$DistrictCode' and ZoneCode='$ZoneCode' order by InstitutionName asc";
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
                                        <td align="left" valign="top">Position <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top"><select class="select2a_n" id="PositionCodeF" name="PositionCodeF" tabindex="40">
                                                <!--<option value="">School Name</option>-->
                                                <option value="">Select</option>
                                                <?php
                                                $sql = "SELECT Code,PositionName FROM CD_Positions order by PositionName asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $Code = trim($row['Code']);
                                                    $PositionName = $row['PositionName'];
                                                    $seltebr = "";
                                                    if ($Code == $PositionCodex) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$Code\" $seltebr>$PositionName</option>";
                                                }
                                                ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">School/ Institution <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top">:</td>
                                        <td width="34%" align="left" valign="top"><div id="txt_showInstituteF">
                                                <select class="select2a" id="InstCodeF" name="InstCodeF" tabindex="37">
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
  FROM [dbo].[CD_CensesNo] where DivisionCode='$DivisionCode'
  order by InstitutionName";
                                                    $stmt = $db->runMsSqlQuery($sql);
                                                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                        $CenCode = $row['CenCode'];
                                                        $InstitutionName = addslashes($row['InstitutionName']);
                                                        echo "<option value=\"$CenCode\">$InstitutionName $CenCode</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div></td>
                                        <td align="left" valign="top">Service Grade <span class="form_error_sched">*</span></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><select class="select2a_n" id="ServiceTypeCodeF" name="ServiceTypeCodeF" tabindex="41">
                                                <!--<option value="">School Name</option>-->
                                                <option value="">Select</option>
                                                <?php
                                                $sql = "SELECT [ServCode],[ServiceName] FROM CD_Service order by ServiceName asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $ServCode = trim($row['ServCode']);
                                                    $ServiceName = $row['ServiceName'];
                                                    $seltebr = "";
                                                    if ($ServCode == $ServiceTypeCodex) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$ServCode\" $seltebr>$ServiceName</option>";
                                                }
                                                ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">Employment Basis</td>
                                        <td align="left" valign="top">:</td>
                                        <td width="34%" align="left" valign="top"><select class="select2a_n" id="ServiceRecTypeCodeF" name="ServiceRecTypeCodeF" tabindex="38">
                                                <!--<option value="">School Name</option>-->
                                                <option value="">Select</option>
                                                <?php
                                                $sql = "SELECT [DutyCode],[Description] FROM CD_ServiceRecType where DutyCode='NA01' order by Description asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $DutyCode = trim($row['DutyCode']);
                                                    $Description = $row['Description'];
                                                    $seltebr = "";
                                                    if ($DutyCode == $ServiceRecTypeCodex) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$DutyCode\" $seltebr>$Description</option>";
                                                }
                                                ?>
                                            </select></td>
                                        <td align="left" valign="top">1/2016 Circular Category</td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><select class="select2a_n" id="Cat2003CodeF" name="Cat2003CodeF" tabindex="42">
                                                <!--<option value="">School Name</option>-->
                                                <option value="">Select</option>
                                                <?php
                                                $sql = "SELECT [Cat2003Code],[Cat2003Name] FROM CD_CAT2003 order by Cat2003Name asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $Cat2003Code = trim($row['Cat2003Code']);
                                                    $Cat2003Name = $row['Cat2003Name'];
                                                    $seltebr = "";
                                                    if ($Cat2003Code == $Cat2003Codex) {
                                                        $seltebr = "selected";
                                                    }
                                                    echo "<option value=\"$Cat2003Code\" $seltebr>$Cat2003Name</option>";
                                                }
                                                ?>
                                            </select></td>
                                    </tr>
                                    <!--Added by Dharshana Requested by Mr. Niel Gunadasa 02/08/2018-->
                                    <tr>
                                        <td align="left" valign="top">Reference Number</td>
                                        <td align="left" valign="top">:</td>
                                        <td width="30%" align="left" valign="top"><input name="PFReferenceF" type="text" class="input2_n" id="PFReferenceF" value="" tabindex="14"/></td>
                                    </tr>
                                </table></td>
                        </tr>
                        <tr>
                            <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                    <tr>
                                        <td colspan="2"><span class="form_error"><strong>- Fields marked with an asterisk (*) are required.</strong></span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><span class="form_error"><strong>- Inquiry is being processed at the zonal level.</strong></span>
                                            <input type="hidden" id="firstAppStatus" name="firstAppStatus" value="">
                                        </td>
                                    </tr>
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
            </div>

        </form>
        <script>
            // console.log(AppDateF);
        </script>

        <script>
        // $(document).ready(function () {
        //     $("#NIC").change(function () {
        //         var NicNo = $(this).val();
        //         alert(NicNo.length);
        //     });
        // });

        document.getElementById("NIC").addEventListener("change",CheckNIC);  
                                         
        function CheckNIC(){
            var NicNo = document.getElementById("NIC").value;
            // alert(NicNo.charAt(1)*2);
            if(NicNo != "") {
                if(NicNo.length < 10){
                    alert("Please Enter a valid NIC");
                    document.getElementById("NIC").value = "";
                }else if(NicNo.legth == 11){
                    alert("Please Enter a valid NIC");
                    document.getElementById("NIC").value = "";
                }else if(NicNo.length > 12){
                    alert("Please Enter a valid NIC");
                    document.getElementById("NIC").value = "";
                }else{
                    if(NicNo.length == 10){
                        var res = 11 - (NicNo.charAt(0)*3 + NicNo.charAt(1)*2 + NicNo.charAt(2)*7 + NicNo.charAt(3)*6 + NicNo.charAt(4)*5 + NicNo.charAt(5)*4 + NicNo.charAt(6)*3 + NicNo.charAt(7)*2) % 11;

                        if(res == 11){
                            res = 0;
                        }else if(res == 10){
                            res = 0;
                        }
                        if((res == NicNo.charAt(8)) && ((NicNo.charAt(9) == 'v') || (NicNo.charAt(9) == 'x') || (NicNo.charAt(9) == 'V') || (NicNo.charAt(9) == 'X'))){
                            console.log("1");
                        }else{
                            alert("Please Enter a valid NIC");
                            document.getElementById("NIC").value = "";
                        }
                        
                    }else if(NicNo.length == 12){
                        var res = 11 - (NicNo.charAt(0)*8 + NicNo.charAt(1)*4 + NicNo.charAt(2)*3 + NicNo.charAt(3)*2 + NicNo.charAt(4)*7 + NicNo.charAt(5)*6 + NicNo.charAt(6)*5 + NicNo.charAt(7)*8 + NicNo.charAt(8)*4 + NicNo.charAt(9)*3 + NicNo.charAt(10)*2) % 11;

                        if(res == 11){
                            res = 0;
                        }else if(res == 10){
                            res = 0;
                        }
                        if(res == NicNo.charAt(11)){
                            console.log("1");
                        }else{
                            alert("Please Enter a valid NIC");
                            document.getElementById("NIC").value = "";
                        }
                    }
                }                    
            } 
        }

            $("#frmSave").submit(function (event) {
                //alert('hi');

                var dialogStatus = false;//NIC, Title, SurnameWithInitials, FullName, ZoneCode
                var NIC = trim($("#NIC").val());
                var Title = trim($("#Title").val());
                var SurnameWithInitials = trim($("#SurnameWithInitials").val());
                var FullName = trim($("#FullName").val());
                var ZoneCode = trim($("#ZoneCode").val());
                var InstCode = trim($("#InstCode").val());

                var AppDate = trim($("#AppDate").val());

                var DOB = trim($("#DOB").val());

                var CivilStatusCode = trim($("#CivilStatusCode").val());
                var EthnicityCode = trim($("#EthnicityCode").val());
                var GenderCode = trim($("#GenderCode").val());
                var ReligionCode = trim($("#ReligionCode").val());


                var SecGRCode = trim($("#SecGRCode").val());
                var PositionCode = trim($("#PositionCode").val());


                var ServiceTypeCode = trim($("#ServiceTypeCode").val());
                var ServiceRecTypeCode = trim($("#ServiceRecTypeCode").val());
                var Cat2003Code = trim($("#Cat2003Code").val());





                var DistrictCode = trim($("#DistrictCode").val());

                var ZoneCode = trim($("#ZoneCode").val());

                var DivisionCode = trim($("#DivisionCode").val());


                var MobileTel = trim($("#MobileTel").val());
                var Address = trim($("#Address").val());


                var DistrictCodeF = trim($("#DistrictCodeF").val());
                var ZoneCodeF = trim($("#ZoneCodeF").val());
                var DivisionCodeF = trim($("#DivisionCodeF").val());
                var InstCodeF = trim($("#InstCodeF").val());
                var ServiceRecTypeCodeF = trim($("#ServiceRecTypeCodeF").val());
                var AppDateF = trim($("#AppDateF").val());
                var SecGRCodeF = trim($("#SecGRCodeF").val());
                var PositionCodeF = trim($("#PositionCodeF").val());
                var ServiceTypeCodeF = trim($("#ServiceTypeCodeF").val());
                var Cat2003CodeF = trim($("#Cat2003CodeF").val());

                //$("#vUserName").attr('class', 'fields_errors');
                if (NIC == "") {
                    $("#NIC").attr('class', 'input2_error');
                    dialogStatus = true;
                }

                if (NIC != "") {
                    // if (NIC.length == 10)
                    {
                        var nicValStatus = "";
                        $.ajax({
                            url: "ajaxCall/FilterDB.php",
                            type: "POST",
                            data: {
                                RequestType: "checkNICValidation",
                                NIC: NIC
                            },
                            dataType: "json",
                            async: false,
                            success: function (data) {
                                nicValStatus = data;
                            }
                        });

                        //alert('xx'); alert(NIC);
                        if (nicValStatus == '0')
                        {
                            $("#nicerror").show();
                            $("#NIC").attr('class', 'input2_error');

                            dialogStatus = true;
                        } else {
                            $("#nicerror").hide();
                            $("#NIC").attr('class', 'input2_n');
                        }
                         /* if (NIC[9] =='X' || NIC[9]=='V' || NIC[9]=='v' || NIC[9]=='x') {

                         }else{
                         alert('xxx');
                         $("#NIC").attr('class', 'input2_error');
                         dialogStatus = true;
                         } */
                    } /* Changed by Dr Chandana Gamage - we are doing NIC validation in FilterDB.php
                    else if (NIC.length == 12) {
                        if (!(NIC.match("^[0-9]{12}$"))) {
                            $("#NIC").attr('class', 'input2_error');
                            dialogStatus = true;
                        } else {
                            $("#NIC").attr('class', 'input2_n');
                        }
                    } else {
                        $("#NIC").attr('class', 'input2_error');
                        dialogStatus = true;
                    }
                    */
                }

                if (AppDate == "") {
                    $("#AppDate").attr('class', 'input3new_error');
                    dialogStatus = true;
                }

                if (DOB == "") {
                    $("#DOB").attr('class', 'input3new_error');
                    dialogStatus = true;
                }

                if (Title == "") {
                    $("#Title").attr('class', 'input2_error');
                    dialogStatus = true;
                }

                if (SurnameWithInitials == "") {
                    $("#SurnameWithInitials").attr('class', 'input2_error');
                    dialogStatus = true;
                }
                if (FullName == "") {
                    $("#FullName").attr('class', 'input2_error');
                    dialogStatus = true;
                }



                if (CivilStatusCode == "") {
                    $("#CivilStatusCode").attr('class', 'input2_error');
                    dialogStatus = true;
                }
                if (EthnicityCode == "") {
                    $("#EthnicityCode").attr('class', 'input2_error');
                    dialogStatus = true;
                }
                if (GenderCode == "") {
                    $("#GenderCode").attr('class', 'input2_error');
                    dialogStatus = true;
                }
                if (ReligionCode == "") {
                    $("#ReligionCode").attr('class', 'input2_error');
                    dialogStatus = true;
                }


                if (ServiceTypeCode == "") {
                    $("#ServiceTypeCode").attr('class', 'input2_error');
                    dialogStatus = true;
                }
                if (ServiceRecTypeCode == "") {
                    $("#ServiceRecTypeCode").attr('class', 'input2_error');
                    dialogStatus = true;
                }
                if (Cat2003Code == "") {
                    $("#Cat2003Code").attr('class', 'input2_error');
                    dialogStatus = true;
                }







                if (ZoneCode == "") {
                    $("#ZoneCode").attr('class', 'input2_error');
                    dialogStatus = true;
                }

                if (DistrictCode == "") {
                    $("#DistrictCode").attr('class', 'input2_error');
                    dialogStatus = true;
                }

                if (DivisionCode == "") {
                    $("#DivisionCode").attr('class', 'input2_error');
                    dialogStatus = true;
                }


                if (InstCode == "") {
                    $("#InstCode").attr('class', 'input2_error');
                    dialogStatus = true;
                }


                if (SecGRCode == "") {
                    $("#SecGRCode").attr('class', 'input2_error');
                    dialogStatus = true;
                }
                if (PositionCode == "") {
                    $("#PositionCode").attr('class', 'input2_error');
                    dialogStatus = true;
                }


                if (MobileTel == "") {
                    $("#MobileTel").attr('class', 'input2_error');
                    dialogStatus = true;
                }

                if (MobileTel != "") {
                    if (!(MobileTel.match("^[0-9]{10}$")))
                    {
                        $("#MobileTel").attr('class', 'input2_error');
                        dialogStatus = true;
                    } else {
                        $("#MobileTel").attr('class', 'input2_n');
                    }
                }

                if (Address == "") {
                    $("#Address").attr('class', 'textarea1a_error');
                    dialogStatus = true;
                }




                if (ServiceRecTypeCode != "NA01") {

                    if (DistrictCodeF == "") {
                        $("#DistrictCodeF").attr('class', 'input2_error');
                        dialogStatus = true;
                    }
                    if (ZoneCodeF == "") {
                        $("#ZoneCodeF").attr('class', 'input2_error');
                        dialogStatus = true;
                    }
                    if (DivisionCodeF == "") {
                        $("#DivisionCodeF").attr('class', 'input2_error');
                        dialogStatus = true;
                    }
                    if (InstCodeF == "") {
                        $("#InstCodeF").attr('class', 'input2_error');
                        dialogStatus = true;
                    }
                    if (ServiceRecTypeCodeF == "") {
                        $("#ServiceRecTypeCodeF").attr('class', 'input2_error');
                        dialogStatus = true;
                    }

                    if (AppDateF == "") {
                        $("#AppDateF").attr('class', 'input3new_error');
                        dialogStatus = true;
                    }

                    if (SecGRCodeF == "") {
                        $("#SecGRCodeF").attr('class', 'input2_error');
                        dialogStatus = true;
                    }

                    if (PositionCodeF == "") {
                        $("#PositionCodeF").attr('class', 'input2_error');
                        dialogStatus = true;
                    }

                    if (ServiceTypeCodeF == "") {
                        $("#ServiceTypeCodeF").attr('class', 'input2_error');
                        dialogStatus = true;
                    }

                    if (Cat2003CodeF == "") {
                        $("#Cat2003CodeF").attr('class', 'input2_error');
                        dialogStatus = true;
                    }
                }

                if (dialogStatus) {
                    $("#dialog").dialog({
                        modal: true
                    });
                    event.preventDefault();
                }


            });

            function numbersonly(e) {
                var unicode = e.charCode ? e.charCode : e.keyCode
                if (unicode != 8) { //if the key isn't the backspace key (which we should allow)
                    if (unicode < 48 || unicode > 57) //if not a number
                        return false //disable key press
                }
            }

            function disable_fa(faID) {
                // alert(faID);
                if (faID == "NA01") {
                    $('#tr_fa1').hide('');
                    $('#tr_fa2').hide('');
                    $('#tr_fa3').hide('');
                    $('#firstAppStatus').val('N');

                } else {
                    $('#tr_fa1').show('');
                    $('#tr_fa2').show('');
                    $('#tr_fa3').show('');
                    $('#firstAppStatus').val('Y');
                }
            }

        </script>
        <script type="text/javascript">
            /* $('input').keypress(function(e){
             alert(e.which);
             if(e.which==13){  alert($("[tabindex='"+($(this))).val());

             $("[tabindex='"+($(this).attr("tabindex")+1)+"']").focus();
             e.preventDefault();
             }
             });  */
            $('input,select,image,textarea').on('keypress', function (e) {
                if (e.which == 13) {
                    e.preventDefault();
                    var $next = $('[tabIndex=' + (+this.tabIndex + 1) + ']');
                    // console.log($next.length);
                    if (!$next.length) {
                        $next = $('[tabIndex=1]');
                    }
                    $next.focus();
                }
            });
        </script>
    </div>
</body>
