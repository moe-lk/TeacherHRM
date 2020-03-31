<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
include('../smservices/sms.php');
$msg = "";
$tblNam = "CD_LeaveType";
$countTotal = "SELECT * FROM $tblNam where LeaveCode!=''";
if (isset($_POST["FrmSrch"])) {
    $NICSrch = $_REQUEST['NICNo'];

    /* $srchQry="SELECT        TeacherMast.SurnameWithInitials, TeacherMast.FullName, TeacherMast.Title, TeacherMast.CurServiceRef, 
      CD_Title.TitleName, Passwords.NICNo, Passwords.CurPassword, Passwords.AccessRole, Passwords.AccessLevel, StaffServiceHistory.InstCode,
      CD_CensesNo.InstitutionName, CD_CensesNo.DistrictCode, CD_Districts.DistName
      FROM            TeacherMast INNER JOIN
      CD_Title ON TeacherMast.Title = CD_Title.TitleCode INNER JOIN
      Passwords ON TeacherMast.NIC = Passwords.NICNo INNER JOIN
      StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID INNER JOIN
      CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode INNER JOIN
      CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
      WHERE        (Passwords.NICNo = N'$NICSrch')"; */
}
$show_status = TRUE;

if (isset($_POST["FrmSubmit"])) {
    //echo "hi";AccessLevel,CurPassword,CurPasswordRT
    $AccessLevel = $_REQUEST['AccessLevel'];
    $CurPassword = $_REQUEST['CurPassword'];
    $CurPasswordRT = $_REQUEST['CurPasswordRT'];
    $chngepw = $_REQUEST['chngepw'];
    $insertTyp = $_REQUEST['insertTyp'];
    $NICSrch = $_REQUEST['NICNo'];
    $pwautogenerate = $_REQUEST['pwautogenerate'];

    // $maxAccount = 0; //Zero (0) men unlimited
    // if ($AccessLevel == 100000 || $AccessLevel == 99999 || $AccessLevel == 17000 || $AccessLevel == 17050 || $AccessLevel == 13050 || $AccessLevel == 12050 || $AccessLevel == 11050 || $AccessLevel == 11000 || $AccessLevel == 7000 || $AccessLevel == 8000)
    //    $maxAccount = 1;

    $countSql = "SELECT * FROM Passwords where NICNo!='$NICSrch' and AccessLevel='$AccessLevel'";
    $isAvailable = $db->rowAvailable($countSql);
    // if ($isAvailable == 1 and $maxAccount == 1) {
    //    $msg = "User account of this user type is already exist.";
    // }

    if ($pwautogenerate == 'Y') {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
        $CurPassword = $CurPasswordRT = substr(str_shuffle($chars), 0, 9);
    }

    //if($CurPassword=='')$CurPassword="DAD";
    //if($CurPassword!='' and $chngepw=='Y'){
    if ($CurPassword != $CurPasswordRT && $chngepw == 'Y' && ($CurPassword != '')) {
        $msg = "Password mismatch. Please try again.";
    }

    if ($msg == '') {
        $passwordMD5 = md5($CurPassword);
        //echo "dfdf";exit();
        $sql = "SELECT AccessRole from CD_AccessRoles Where AccessRoleValue='$AccessLevel'";
        $stmt = $db->runMsSqlQuery($sql);
        $rowA = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $AccessRole = strtoupper($rowA['AccessRole']);

        $LastUpdate = date('Y-m-d H:i:s');

        if ($insertTyp == 'E') {
            if ($chngepw == 'Y' || $pwautogenerate == 'Y') {
                $queryUpate = "UPDATE Passwords SET	CurPassword='$passwordMD5', LastUpdate='$LastUpdate', AccessRole='$AccessRole', AccessLevel='$AccessLevel' WHERE NICNo='$NICSrch'";

                $dateU = date('Y-m-d H:i:s');
                $reqTabMob = "SELECT MobileTel FROM TeacherMast where NIC='$NICSrch'";
                $stmtMob = $db->runMsSqlQuery($reqTabMob);
                $rowMob = sqlsrv_fetch_array($stmtMob, SQLSRV_FETCH_ASSOC);
                $MobileTel = trim($rowMob['MobileTel']);

                $tpNumber = numberFormat($MobileTel);

                /* Send SMS via GOV SMS */
                $sms_content = "Your new password is " . $CurPassword; //exit();
                $config = array('message' => $sms_content, 'recepient' => $tpNumber); //0779105338
                $smso = new sms();
                $result = $smso->sendsms($config, 3);
                if ($result[0] == 1) {
                    //SMS Sent
                    //echo 'ok';
                    $msg1 = " SMS sent successfully.";
                    $statusOf = "Success";
                } else if ($result[0] == 2) {
                    //SMS Sent
                    //echo 'ok';
                    //$statusOf="Success";
                    $msg1 = "";
                } else {
                    //SMS wasn't Sent
                    //echo 'error';
                    $msg1 = " SMS Fail";
                    $statusOf = "Fail";
                }
                //end SMS
                if ($result[0] != 2) {
                    $queryRegissms = "INSERT INTO TG_SMS (NIC,ModuleName,dDateTime,StatusOf,RecID) VALUES ('$NICSrch','User Password change','$dateU','$statusOf','')";
                    $db->runMsSqlQuery($queryRegissms);
                }
            } else {
                $queryUpate = "UPDATE Passwords SET LastUpdate='$LastUpdate', AccessRole='$AccessRole', AccessLevel='$AccessLevel' 
					WHERE
						   NICNo='$NICSrch'";
                $statusOf == "";
            }

            $db->runMsSqlQuery($queryUpate);
            $msg = "Record update successfully." . $msg1;
        } else {
            $queryGradeSave = "INSERT INTO Passwords
			   (NICNo,CurPassword,LastUpdate,AccessRole,AccessLevel)
		 VALUES
			   ('$NICSrch','$passwordMD5','$LastUpdate','$AccessRole','$AccessLevel')";
            $db->runMsSqlQuery($queryGradeSave);

            $dateU = date('Y-m-d H:i:s');
            $reqTabMob = "SELECT MobileTel FROM TeacherMast where NIC='$NICSrch'";
            $stmtMob = $db->runMsSqlQuery($reqTabMob);
            $rowMob = sqlsrv_fetch_array($stmtMob, SQLSRV_FETCH_ASSOC);
            $MobileTel = trim($rowMob['MobileTel']);
            //exit();
            $tpNumber = numberFormat($MobileTel);

            /* Send SMS via GOV SMS */
            $sms_content = "Your new password is " . $CurPassword;
            $config = array('message' => $sms_content, 'recepient' => $tpNumber); //0779105338
            $smso = new sms();
            $result = $smso->sendsms($config, 3);
            if ($result[0] == 1) {
                //SMS Sent
                //echo 'ok';
                $msg1 = "SMS sent successfully.";
                $statusOf = "Success";
            } else if ($result[0] == 2) {
                //SMS Sent
                //echo 'ok';
                //$statusOf="Success";
                $msg1 = "";
            } else {
                //SMS wasn't Sent
                //echo 'error';
                $msg1 = "SMS Fail";
                $statusOf = "Fail";
            }
            //end SMS
            if ($result[0] != 2) {
                $queryRegissms = "INSERT INTO TG_SMS (NIC,ModuleName,dDateTime,StatusOf,RecID) VALUES ('$NICSrch','User Password new user','$dateU','$statusOf','')";
                $db->runMsSqlQuery($queryRegissms);
            }

            $msg = "Account create successfully." . $msg1;
        }
    }



    /* }else{
      $msg="Please enter the password.";
      } */
    /* if($LeaveCode!=''){
      $queryGradeSave="INSERT INTO $tblNam
      (LeaveCode,Description,RecordLog,DutyCode)
      VALUES
      ('$LeaveCode','$Description','$RecordLog','$DutyCode')";

      $countSql="SELECT * FROM $tblNam where LeaveCode='$LeaveCode'";
      $isAvailable=$db->rowAvailable($countSql);
      if($isAvailable==1){
      $msg="Already exist.";
      }else{
      $db->runMsSqlQuery($queryGradeSave);
      //$newID=$db->runMsSqlQueryInsert($queryGradeSave);
      $msg="Successfully Updated.";
      }
      }else{
      $msg="Please enter the Title..";
      } */
    //sqlsrv_query($queryGradeSave);
}


// ******
// get user login details

$AccessRoleType = $_SESSION['AccessRoleType'];
$CenCodex = trim($_SESSION['loggedSchool']);


// ******
if ($NICSrch != '') {


    $srchQry = "SELECT
	TeacherMast.SurnameWithInitials,
	TeacherMast.FullName,
	TeacherMast.Title,
	TeacherMast.CurServiceRef,
	CD_Title.TitleName,
	StaffServiceHistory.InstCode,
	CD_CensesNo.InstitutionName,
	CD_CensesNo.DistrictCode,
	CD_Districts.DistName,
        CD_Districts.ProCode,
	CD_CensesNo.ZoneCode,
	CD_CensesNo.DivisionCode
FROM
	TeacherMast
INNER JOIN CD_Title ON TeacherMast.Title = CD_Title.TitleCode
LEFT JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID
LEFT JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
LEFT JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
WHERE TeacherMast.NIC = N'$NICSrch'";

    $stmt = $db->runMsSqlQuery($srchQry);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $SurnameWithInitials = $row['SurnameWithInitials'];
    $FullName = $row['FullName'];
    $TitleName = $row['TitleName'];
    $InstitutionName = $row['InstitutionName'];
    $DistName = $row['DistName'];
    $ProCode = trim($row['ProCode']);
    $DistrictCode = trim($row['DistrictCode']);
    $ZoneCode = trim($row['ZoneCode']);
    $DivisionCode = trim($row['DivisionCode']);   
    


    if ($AccessRoleType == "SC") {
        $show_status = FALSE;
    } else if ($AccessRoleType == "ED") {
        //Division  
        $restZone = substr($CenCodex, -4, 4);
        $divCodeLoged = "ED" . $restZone;

        $sql = "SELECT     CD_Division.CenCode, CD_Division.DistrictCode, CD_Division.ZoneCode, CD_Districts.ProCode
FROM         CD_Division INNER JOIN
                      CD_Districts ON CD_Division.DistrictCode = CD_Districts.DistCode
WHERE        (CD_Division.CenCode = N'$divCodeLoged')";
        $stmt = $db->runMsSqlQuery($sql);
        $rowA = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $ZoneCodex = strtoupper(trim($rowA['ZoneCode']));
        $ProCodex = strtoupper(trim($rowA['ProCode']));
        $DistrictCodex = strtoupper(trim($rowA['DistrictCode']));

        $DivisionCodex = $divCodeLoged;

        
        if($ProCodex==$ProCode && $DistrictCodex==$DistrictCode && $ZoneCodex==$ZoneCode && $DivisionCodex== $DivisionCode){
            $show_status = TRUE;
        }else{
            $show_status = FALSE;
        }
    } else if ($AccessRoleType == "ZN") {
        //zone
        $restZone = substr($CenCodex, -4, 4);
        $zoneCodeLoged = "ZN" . $restZone;


        $detailSql = "SELECT
	CD_CensesNo.CenCode,
	CD_CensesNo.DistrictCode,
	CD_CensesNo.ZoneCode,
	CD_CensesNo.DivisionCode,
	CD_Districts.ProCode
FROM
	CD_CensesNo
INNER JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
WHERE
	(CD_CensesNo.CenCode = N'$CenCodex')";
        $stmt = $db->runMsSqlQuery($detailSql);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);


        $ProCodex = trim($row['ProCode']);
        $DistrictCodex = trim($row['DistrictCode']);
        $ZoneCodex = $zoneCodeLoged;
        $DivisionCodex = trim($row['DivisionCode']);

        
        if($ProCodex==$ProCode && $DistrictCodex==$DistrictCode && $ZoneCodex==$ZoneCode){
            $show_status = TRUE;
        }else{
            $show_status = FALSE;
        }
        
    } else if ($AccessRoleType == "DN") {
        //District
        $restDistrict = substr($CenCodex, -4, 2);
        $DistrictCodex = "D" . $restDistrict;

        $sql = "SELECT ProCode from CD_Districts Where DistCode='$DistrictCodex'";
        $stmt = $db->runMsSqlQuery($sql);
        $rowA = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $ProCodex = strtoupper(trim($rowA['ProCode']));

                
        if($ProCodex==$ProCode && $DistrictCodex==$DistrictCode){
            $show_status = TRUE;
        }else{
            $show_status = FALSE;
        }
        
    } else if ($AccessRoleType == "PD") {
        //Province
        $rest = substr($CenCodex, -3, 1);
        $ProCodex = "P0" . $rest;      
        
        if($ProCodex==$ProCode){
            $show_status = TRUE;
        }else{
            $show_status = FALSE;
        }
    } else {
        $show_status = TRUE;
    }



    
    
    $paswrdGry = "SELECT
Passwords.NICNo,
Passwords.CurPassword,
Passwords.LastUpdate,
Passwords.AccessRole,
Passwords.AccessLevel,
CD_AccessRoles.AccessRoleID

FROM
Passwords
LEFT JOIN CD_AccessRoles ON Passwords.AccessLevel = CD_AccessRoles.AccessRoleValue
WHERE
Passwords.NICNo = N'$NICSrch'";
    $stmtP = $db->runMsSqlQuery($paswrdGry);
    $rowP = sqlsrv_fetch_array($stmtP, SQLSRV_FETCH_ASSOC);
    $CurPassword = trim($rowP['CurPassword']);
    $AccessRole = $rowP['AccessRole'];
    $AccessLevel = trim($rowP['AccessLevel']);
    $AccessID .= trim($rowP['AccessRoleID']);

    //if()
}
$SeeControlLevel = "";

// For teacher and principle
if($AccessID==""){
 $show_status = TRUE;
 $AccessID = 1;
 $SeeControlLevel = '1,2,';
}
 $SeeControlLevel.= $_SESSION['SeeControlLevel'];

?>


<div class="main_content_inner_block">
    <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if ($msg != '') {//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
            <div class="mcib_middle1">
                <div class="mcib_middle_full">
                    <div class="form_error"><?php
                        echo $msg;
                        echo $_SESSION['success_update'];
                        $_SESSION['success_update'] = "";
                        ?><?php echo $_SESSION['fail_update'];
                    $_SESSION['fail_update'] = "";
                        ?></div>
                </div>
<?php } ?>
            <table width="945" cellpadding="0" cellspacing="0">
                <tr>
                    <td colspan="2" valign="top"><table width="90%" cellspacing="2" cellpadding="2">

                            <tr>
                                <td width="19%"><span class="form_error">*</span> Enter The NIC Number :</td>
                                <td width="18%"><input name="NICNo" type="text" class="input3" id="NICNo" value="<?php echo $NICSrch ?>"/></td>
                                <td width="63%"><input name="FrmSrch" type="submit" id="FrmSrch" style="background-image: url(../cms/images/finduser.png); width:158px; height:26px; background-color:transparent; border:none; cursor:pointer;" value="" /></td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td colspan="2" style="border-bottom:1px; border-bottom-style:solid;"><?php
if ($NICSrch != '') {
    if ($CurPassword == '') {
        ?><span style="color:#F00; font-weight:bold;">User account does not exist.</span><?php } else { ?>
                <!--<span style="color:#090; font-weight:bold;">User account already exist.</span>-->
    <?php }
}
?></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
<?php if ($NICSrch) { ?>
                    <tr>
                        <td width="62%"><table width="100%" cellspacing="1" cellpadding="1">
                                <tr>
                                    <td width="22%"><strong>Title</strong></td>
                                    <td width="1%">:</td>
                                    <td width="77%"><?php echo $TitleName ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Surname With Initials</strong></td>
                                    <td>:</td>
                                    <td><?php echo $SurnameWithInitials ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Full Name</strong></td>
                                    <td>:</td>
                                    <td><?php echo $FullName ?></td>
                                </tr>

                                <tr>
                                    <td><strong>District</strong></td>
                                    <td>:</td>
                                    <td><?php echo $DistName ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Institution</strong></td>
                                    <td>:</td>
                                    <td><?php echo $InstitutionName ?></td>
                                </tr>
    <?php
    if ($show_status== TRUE && in_array($AccessID, array_map("trim", explode(',', $SeeControlLevel)))) {
        ?>
                                    <tr>
                                        <td><strong>Access Level</strong></td>
                                        <td>:</td>
                                        <td><select class="select2a_n" id="AccessLevel" name="AccessLevel">
                                                <!--<option value="">School Name</option>-->
                                                <?php
                                                $sqlU = "SELECT CD_AccessRoles.AccessRoleID,
CD_AccessRoles.AccessRole,
CD_AccessRoles.AccessRoleValue
FROM
CD_AccessRoles
WHERE
CD_AccessRoles.AccessRoleID IN ($SeeControlLevel)";
                                                $stmt = $db->runMsSqlQuery($sqlU);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $AccessRoleValueDB = trim($row['AccessRoleValue']);
                                                    $AccessRoleDB = $row['AccessRole'];
                                                    if ($AccessLevel == $AccessRoleValueDB) {
                                                        $seltebr = "selected";
                                                        echo "<option value=\"$AccessRoleValueDB\" selected=\"selected\">$AccessRoleDB</option>";
                                                    } else {
                                                        echo "<option value=\"$AccessRoleValueDB\" >$AccessRoleDB</option>";
                                                    }
                                                }
                                                ?>
                                            </select></td>
                                    </tr>
        <?php
    }
    ?>
    <?php if ($CurPassword == '') { ?>
                                    <tr>
                                        <td colspan="3"><table width="100%" cellspacing="1" cellpadding="1">
                                                <tr>
                                                    <td width="22%"><strong><span class="form_error">*</span>Password</strong></td>
                                                    <td width="1%">:</td>
                                                    <td width="77%"><input name="CurPassword" type="password" class="input3" id="CurPassword" value=""/></td>
                                                </tr>
                                                <tr>
                                                    <td><strong><span class="form_error">*</span>Re-type Password</strong></td>
                                                    <td>:</td>
                                                    <td><input name="CurPasswordRT" type="password" class="input3" id="CurPasswordRT" value="" /></td>
                                                </tr>
                                            </table></td>
                                    </tr><?php } ?>
    <?php
    // if($SeeControlLevel==$AccessID){
    if ($show_status== TRUE && in_array($AccessID, array_map("trim", explode(',', $SeeControlLevel)))) {
        ?>
                                    <tr>
                                        <td align="left"><strong>Password Auto Generate</strong></td>
                                        <td>:</td>
                                        <td align="left"><input name="pwautogenerate" type="checkbox" class="check1" id="pwautogenerate" value="Y"/></td>
                                    </tr>


                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </table></td>
                        <td width="38%" valign="top"><table width="100%" cellspacing="1" cellpadding="1">
                                <?php if ($CurPassword != '' && $show_status== TRUE && in_array($AccessID, array_map("trim", explode(',', $SeeControlLevel)))) { ?>
                                    <tr>
                                        <td align="left" bgcolor="#FFFFFF"><a style="cursor:hand; cursor:pointer; border-bottom:1px; border-bottom-style:solid; color:#FFF;" onclick="Javascript:show_changepw('change_pw', '', '');">
                                                <img src="../cms/images/change-password.png" width="150" height="26" /></a><input type="hidden" name="insertTyp" value="E" /></td>
                                    </tr>
    <?php } ?>
                                <tr>
                                    <td width="48%"><div id="txt_changepw"><?php if ($CurPassword == 'DAD') { ?><table width="100%" cellspacing="1" cellpadding="1">
                                                    <tr>
                                                        <td width="39%"><strong><span class="form_error">*</span>Password</strong></td>
                                                        <td width="3%">:</td>
                                                        <td width="58%"><input name="CurPassword" type="password" class="input3" id="CurPassword" value=""/></td>
                                                    </tr>
                                                    <tr>
                                                        <td><strong><span class="form_error">*</span>Re-type Password</strong></td>
                                                        <td>:</td>
                                                        <td><input name="CurPasswordRT" type="password" class="input3" id="CurPasswordRT" value="" /></td>
                                                    </tr>
                                                </table><?php } ?></div></td>
                                </tr>
                            </table></td>
                    </tr>
<?php } ?>
            </table>
        </div>

    </form>
</div><!--
<div style="width:945px; width: auto; float: left;">
    <div style="width: 150px; float: left; margin-left: 50px;">
        School
    </div>
    <div style="width: 745px; float: left;">
        <select name="teachingSubject" class="select2a_n" id="teachingSubject" style="width: auto;" onchange="">
            <option value="">School Name</option>
           
        </select>
    </div>
    <div style="width: 150px; float: left;margin-left: 50px;">
        Grade
    </div>
    <div style="width: 745px; float: left;">
        <select name="teachingSubject" class="select2a_n" id="teachingSubject" style="width: auto;" onchange="">
            <option value="">Grade</option>
           
        </select>
    </div>
    <div style="width: 200px; float: left;margin-left: 50px;">
        
    </div>
    
</div>-->