<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
    window.onload = function () {
        document.getElementById('provinceDept').style.display = "none";
        document.getElementById('districtDept').style.display = "none";
        document.getElementById('zoneDept').style.display = "none";
        document.getElementById('devisionDept').style.display = "none";


    }
    function loadDept(selCode) {
        //alert(selCode);
        //provinceDept,districtDept,zoneDept,devisionDept
        if (selCode == 14000 || selCode == 17050 || selCode == 17060) {
            document.getElementById('provinceDept').style.display = "block";
            document.getElementById('districtDept').style.display = "none";
            document.getElementById('zoneDept').style.display = "none";
            document.getElementById('devisionDept').style.display = "none";
            document.getElementById('userTpe').value = "PR";

            document.getElementById("InstCodeP").disabled = false;
            document.getElementById("InstCodeD").disabled = true;
            document.getElementById("InstCodeZ").disabled = true;
            document.getElementById("InstCodeDI").disabled = true;

        }

        if (selCode == 11075 || selCode == 12000 || selCode == 13050) {
            document.getElementById('provinceDept').style.display = "none";
            document.getElementById('districtDept').style.display = "block";
            document.getElementById('zoneDept').style.display = "none";
            document.getElementById('devisionDept').style.display = "none";
            document.getElementById('userTpe').value = "DI";

            document.getElementById("InstCodeD").disabled = false;
            document.getElementById("InstCodeP").disabled = true;
            document.getElementById("InstCodeZ").disabled = true;
            document.getElementById("InstCodeDI").disabled = true;
        }

        if (selCode == 9000 || selCode == 11060 || selCode == 11070) {
            document.getElementById('provinceDept').style.display = "none";
            document.getElementById('districtDept').style.display = "none";
            document.getElementById('zoneDept').style.display = "block";
            document.getElementById('devisionDept').style.display = "none";
            document.getElementById('userTpe').value = "ZE";

            document.getElementById("InstCodeZ").disabled = false;
            document.getElementById("InstCodeD").disabled = true;
            document.getElementById("InstCodeP").disabled = true;
            document.getElementById("InstCodeDI").disabled = true;
        }

        if (selCode == 5000 || selCode == 6000 || selCode == 8000) {
            document.getElementById('provinceDept').style.display = "none";
            document.getElementById('districtDept').style.display = "none";
            document.getElementById('zoneDept').style.display = "none";
            document.getElementById('devisionDept').style.display = "block";
            document.getElementById('userTpe').value = "ED";

            document.getElementById("InstCodeDI").disabled = false;
            document.getElementById("InstCodeD").disabled = true;
            document.getElementById("InstCodeP").disabled = true;
            document.getElementById("InstCodeZ").disabled = true;
        }
    }

</script>
<?php
//echo $_SESSION['loggedAccessLevel'];
//echo $accLevel;
$loggedSchool = $_SESSION['loggedSchool'];
$AccessRoleType = $_SESSION['AccessRoleType'];

$SeeControlLevel = $_SESSION['SeeControlLevel'];

$SeeControlLevel = str_replace('1,2,', '', $SeeControlLevel);
$SeeControlLevel = str_replace('1,', '', $SeeControlLevel);
$SeeControlLevel = str_replace('2,', '', $SeeControlLevel);

$AccessRoleValueIDs = "";
$sqlU = "SELECT CD_AccessRoles.AccessRoleID,
CD_AccessRoles.AccessRole,
CD_AccessRoles.AccessRoleValue
FROM
CD_AccessRoles
WHERE
CD_AccessRoles.AccessRoleID IN ($SeeControlLevel)";
$stmt = $db->runMsSqlQuery($sqlU);
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $AccessRoleValueIDs .= trim($row['AccessRoleValue']) . ",";
}
$AccessRoleValueIDs = rtrim($AccessRoleValueIDs, ',');
//$SeeControlLevel 
//12050,
if ($AccessRoleType == 'PD') {//Province
    $rest = substr($loggedSchool, -3, 1);
    $proCodeLoged = "P0" . $rest;
}

if ($AccessRoleType == 'DN') {//District
    //echo $rest = substr($loggedSchool, -3, 1);
    $restDistrict = substr($loggedSchool, -4, 2);
    $distCodeLoged = "D" . $restDistrict;

    $sql = "SELECT ProCode from CD_Districts Where DistCode='$distCodeLoged'";
    $stmt = $db->runMsSqlQuery($sql);
    $rowA = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $proCodeLoged = strtoupper($rowA['ProCode']);
}

if ($AccessRoleType == 'ZN') {//Zone
    $restZone = substr($loggedSchool, -4, 4);
    $zoneCodeLoged = "ZN" . $restZone;

    $sql = "SELECT        CD_Zone.CenCode, CD_Zone.DistrictCode, CD_Districts.ProCode
FROM            CD_Zone INNER JOIN
                         CD_Districts ON CD_Zone.DistrictCode = CD_Districts.DistCode
WHERE        (CD_Zone.CenCode = N'$zoneCodeLoged')";
    $stmt = $db->runMsSqlQuery($sql);
    $rowA = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $proCodeLoged = strtoupper(trim($rowA['ProCode']));
    $distCodeLoged = strtoupper(trim($rowA['DistrictCode']));
}

if ($AccessRoleType == 'ED') {//Division
    $restZone = substr($loggedSchool, -4, 4);
    $divCodeLoged = "ED" . $restZone;

    $sql = "SELECT     CD_Division.CenCode, CD_Division.DistrictCode, CD_Division.ZoneCode, CD_Districts.ProCode
FROM         CD_Division INNER JOIN
                      CD_Districts ON CD_Division.DistrictCode = CD_Districts.DistCode
WHERE        (CD_Division.CenCode = N'$divCodeLoged')";
    $stmt = $db->runMsSqlQuery($sql);
    $rowA = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $zoneCodeLoged = strtoupper(trim($rowA['ZoneCode']));
    $proCodeLoged = strtoupper(trim($rowA['ProCode']));
    $distCodeLoged = strtoupper(trim($rowA['DistrictCode']));
}

$sql = "SELECT InstitutionName from CD_CensesNo Where CenCode='$loggedSchool'";
$stmt = $db->runMsSqlQuery($sql);
$rowA = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$InstitutionNameLoged = strtoupper($rowA['InstitutionName']);



$msg = "";
$tblNam = "Passwords";


if ($AccessRoleType == 'NC') {
    $sqlList = "SELECT        Passwords.NICNo, TeacherMast.SurnameWithInitials, CD_CensesNo.InstitutionName, CD_CensesNo.DistrictCode, CD_CensesNo.ZoneCode, CD_CensesNo.DivisionCode, Passwords.AccessRole, 
													 Passwords.AccessLevel, StaffServiceHistory.InstCode
							FROM            Passwords INNER JOIN
													 TeacherMast ON Passwords.NICNo = TeacherMast.NIC LEFT JOIN
													 StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID LEFT JOIN
													 CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
							WHERE    (Passwords.AccessLevel IN($AccessRoleValueIDs))";
}

if ($AccessRoleType == 'PD') {

    $sqlList = " SELECT        Passwords.NICNo, TeacherMast.SurnameWithInitials, CD_CensesNo.InstitutionName, CD_CensesNo.DistrictCode, CD_CensesNo.ZoneCode, CD_CensesNo.DivisionCode, Passwords.AccessRole, 
                         Passwords.AccessLevel, StaffServiceHistory.InstCode, CD_Districts.ProCode
FROM            CD_Districts LEFT JOIN
                         CD_CensesNo ON CD_Districts.DistCode = CD_CensesNo.DistrictCode RIGHT OUTER JOIN
                         Passwords LEFT OUTER JOIN
                         TeacherMast ON Passwords.NICNo = TeacherMast.NIC LEFT OUTER JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID ON CD_CensesNo.CenCode = StaffServiceHistory.InstCode
WHERE      (Passwords.AccessLevel IN($AccessRoleValueIDs)) and (CD_Districts.ProCode = N'$proCodeLoged')";
} else if ($AccessRoleType == 'DN') {

    $sqlList = " SELECT        Passwords.NICNo, TeacherMast.SurnameWithInitials, CD_CensesNo.InstitutionName, CD_CensesNo.DistrictCode, CD_CensesNo.ZoneCode, CD_CensesNo.DivisionCode, Passwords.AccessRole, 
                         Passwords.AccessLevel, StaffServiceHistory.InstCode, CD_Districts.ProCode
FROM            CD_Districts LEFT JOIN
                         CD_CensesNo ON CD_Districts.DistCode = CD_CensesNo.DistrictCode RIGHT OUTER JOIN
                         Passwords LEFT OUTER JOIN
                         TeacherMast ON Passwords.NICNo = TeacherMast.NIC LEFT OUTER JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID ON CD_CensesNo.CenCode = StaffServiceHistory.InstCode
WHERE      (Passwords.AccessLevel IN($AccessRoleValueIDs)) and (CD_Districts.DistCode = N'$distCodeLoged')";
} else if ($AccessRoleType == 'ZN') {

    $sqlList = " SELECT        Passwords.NICNo, TeacherMast.SurnameWithInitials, CD_CensesNo.InstitutionName, CD_CensesNo.DistrictCode, CD_CensesNo.ZoneCode, CD_CensesNo.DivisionCode, Passwords.AccessRole, 
                         Passwords.AccessLevel, StaffServiceHistory.InstCode, CD_Districts.ProCode
FROM            CD_Districts LEFT JOIN
                         CD_CensesNo ON CD_Districts.DistCode = CD_CensesNo.DistrictCode RIGHT OUTER JOIN
                         Passwords LEFT OUTER JOIN
                         TeacherMast ON Passwords.NICNo = TeacherMast.NIC LEFT OUTER JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID ON CD_CensesNo.CenCode = StaffServiceHistory.InstCode
WHERE      (Passwords.AccessLevel IN($AccessRoleValueIDs)) and (CD_CensesNo.ZoneCode = N'$zoneCodeLoged')";
} else if ($AccessRoleType == 'ED') {

    $sqlList = " SELECT        Passwords.NICNo, TeacherMast.SurnameWithInitials, CD_CensesNo.InstitutionName, CD_CensesNo.DistrictCode, CD_CensesNo.ZoneCode, CD_CensesNo.DivisionCode, Passwords.AccessRole, 
                         Passwords.AccessLevel, StaffServiceHistory.InstCode, CD_Districts.ProCode
FROM            CD_Districts LEFT JOIN
                         CD_CensesNo ON CD_Districts.DistCode = CD_CensesNo.DistrictCode RIGHT OUTER JOIN
                         Passwords LEFT OUTER JOIN
                         TeacherMast ON Passwords.NICNo = TeacherMast.NIC LEFT OUTER JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID ON CD_CensesNo.CenCode = StaffServiceHistory.InstCode
WHERE      (Passwords.AccessLevel IN($AccessRoleValueIDs)) and (CD_CensesNo.DivisionCode = N'$divCodeLoged')";
}

$TotaRows = $db->rowCount($sqlList);
?>


<div class="main_content_inner_block">
    <form method="post" action="controlUsersSave.php" name="frmSave" id="frmSave" enctype="multipart/form-data" >
        <?php if ($_SESSION['success_update'] != '' || $_SESSION['success_update'] != '') { ?>   
            <div class="mcib_middle1">
                <div class="mcib_middle_full">
                    <div class="form_error"><?php
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
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td width="49%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">

                            <tr>
                                <td align="left" valign="top">Create by</td>
                                <td>:</td>
                                <td><?php echo $_SESSION["fullName"]; ?>  [<?php echo $loggedPositionName; ?>] <br><?php echo $InstitutionNameLoged ?></td>
                            </tr>
                            <tr>
                                <td>User Type <span class="form_error">*</span></td>
                                <td>:</td><!--onChange="Javascript:show_district('districtListUser', this.options[this.selectedIndex].value, '');"-->
                                <td><select class="select2a_n" id="AccessLevel" name="AccessLevel" onChange="loadDept(this.options[this.selectedIndex].value)">
                                        <option value="">-Select User Type-</option>
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
                                            echo "<option value=\"$AccessRoleValueDB\" $seltebr>$AccessRoleDB</option>";
                                        }
                                        ?>
                                    </select></td>
                            </tr>
                            <tr>
                                <td>Title</td>
                                <td>:</td>
                                <td><select class="select2a_n" id="Title" name="Title" tabindex="2">
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
                                <td>Name with initials <span class="form_error">*</span></td>
                                <td>:</td>
                                <td><input name="SurnameWithInitials" type="text" class="input2_n" id="SurnameWithInitials" value="<?php //echo $SurnameWithInitials      ?>" tabindex="3"/></td>
                            </tr>
                            <tr>
                                <td>Full Name <span class="form_error">*</span></td>
                                <td>:</td>
                                <td><input name="FullName" type="text" class="input2_n" id="FullName" value="<?php //echo $FullName      ?>" tabindex="4"/></td>
                            </tr>
                            <tr>
                                <td>Gender</td>
                                <td>:</td>
                                <td><select class="select2a_n" id="GenderCode" name="GenderCode" tabindex="8">
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
                                <td>Telephone</td>
                                <td>:</td>
                                <td><input name="TpNumber" type="text" class="input2_n" id="TpNumber" /></td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>:</td>
                                <td><input name="EmailAdd" type="text" class="input2_n" id="EmailAdd" /></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>
                                    <input type="hidden" name="AED" value="normal"/>
                                    <input type="hidden" name="vDes" value="<?php echo $fm ?>"/>
                                    <input type="hidden" name="vID" value="<?php echo $id ?>"/>
                                    <input type="hidden" name="userTpe" id="userTpe" value=""/>
                                    <input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                            </tr>
                        </table>
                    </td>
                    <td width="51%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td align="left" valign="top">NIC(User name) <span class="form_error">*</span></td>
                                <td align="left" valign="top">:</td>
                                <td><input name="NIC" type="text" class="input2_n" id="NIC" value="" tabindex="1"/></td>
                            </tr>
                            <tr>
                                <td width="28%" align="left" valign="top">Password <span class="form_error">*</span></td>
                                <td width="3%" align="left" valign="top">:</td>
                                <td width="69%"><input name="CurPassword" type="password" class="input3" id="CurPassword" value=""/></td>
                            </tr>
                            <tr>
                                <td>Re-type Password <span class="form_error">*</span></td>
                                <td>:</td>
                                <td><input name="CurPasswordRT" type="password" class="input3" id="CurPasswordRT" value="" /></td>
                            </tr>

                            <tr>
                                <td colspan="3"><div id="provinceDept" style="display:block;">
                                        <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                            <tbody>
                                                <tr>
                                                    <td width="29%">Province Department <span class="form_error">*</span></td>
                                                    <td width="3%">:</td>
                                                    <td width="68%">
                                                        <?php
                                                        $sqlSup = "where CenCode LIKE '%PD%'";
                                                        if ($AccessRoleType == 'PD') {
                                                            $sqlSup = "where CenCode='$loggedSchool'";
                                                        }
                                                        ?>
                                                        <select class="select2a" id="InstCodeP" name="InstCode" tabindex="27">
                                                            <option value="">-Select-</option>
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
  FROM [dbo].[CD_CensesNo] $sqlSup
  order by InstitutionName";
                                                            $stmt = $db->runMsSqlQuery($sql);
                                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                $CenCode = trim($row['CenCode']);
                                                                $InstitutionName = addslashes($row['InstitutionName']);
                                                                echo "<option value=\"$CenCode\">$InstitutionName $CenCode</option>";
                                                            }
                                                            ?>
                                                        </select></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="districtDept" style="display:block;">
                                        <table width="100%" border="0" cellspacing="2" cellpadding="2">
                                            <tbody>
                                                <tr>
                                                    <td width="29%" align="left">District Resource Center <span class="form_error">*</span></td>
                                                    <td width="3%">:</td>
                                                    <td width="68%"><select class="select2a" id="InstCodeD" name="InstCode" tabindex="27">
                                                            <option value="">-Select-</option>
                                                            <?php
                                                            $DivisionCode = "abc";

                                                            if ($AccessRoleType == 'PD') {

                                                                $sql = "SELECT        CD_CensesNo.InstType, CD_CensesNo.CenCode, CD_CensesNo.InstitutionName, CD_CensesNo.DistrictCode, CD_Districts.ProCode
FROM            CD_CensesNo INNER JOIN
                         CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
WHERE        (CD_CensesNo.CenCode LIKE '%DI%') AND (CD_Districts.ProCode = N'$proCodeLoged')";
                                                            } else
                                                            if ($AccessRoleType == 'DN') {
                                                                $sql = "SELECT [InstType]
							,[CenCode]
							,[InstitutionName]
							FROM [dbo].[CD_CensesNo] where CenCode='$loggedSchool' order by InstitutionName";
                                                            } else {
                                                                $sql = "SELECT [InstType]
							,[CenCode]
							,[InstitutionName]
							FROM [dbo].[CD_CensesNo] where CenCode LIKE '%DI%'
							order by InstitutionName";
                                                            }

                                                            $stmt = $db->runMsSqlQuery($sql);
                                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                $CenCode = trim($row['CenCode']);
                                                                $InstitutionName = addslashes(str_replace("EDUCATION RESOURCE CENTRE ", "", $row['InstitutionName']));
                                                                echo "<option value=\"$CenCode\">$InstitutionName $CenCode</option>";
                                                            }
                                                            ?>
                                                        </select></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="zoneDept" style="display:block;"><table width="100%" border="0" cellspacing="2" cellpadding="2">
                                            <tbody>
                                                <tr>
                                                    <td width="28%">Province</td>
                                                    <td width="3%">:</td>
                                                    <td width="69%">


                                                        <select class="select2a_n" id="ProCode" name="ProCode" onChange="Javascript:show_district('districtListUser', this.options[this.selectedIndex].value, '');" <?php echo $disaTxt ?>>
                                                            <!--<option value="">Select Province</option>-->
                                                            <?php
//if($accLevel==13050 || $accLevel==12050 || $accLevel==12000 || $accLevel==11050 || $accLevel==11000 || $accLevel==10000){
                                                            if ($AccessRoleType == 'NC') {
                                                                $sql = "SELECT ProCode,Province FROM CD_Provinces order by Province asc";
                                                            } else {
                                                                $sql = "SELECT ProCode,Province FROM CD_Provinces where  ProCode='$proCodeLoged'";
                                                            }

                                                            $stmt = $db->runMsSqlQuery($sql);
                                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                $ProCoded = trim($row['ProCode']);
                                                                $DistNamed = $row['Province'];
                                                                $seltebr = "";
                                                                if ($ProCoded == $proCodeLoged) {
                                                                    $seltebr = "selected";
                                                                }
                                                                echo "<option value=\"$ProCoded\" $seltebr>$DistNamed</option>";
                                                            }
                                                            ?>
                                                        </select></td>
                                                </tr>
                                                <tr>
                                                    <td>District</td>
                                                    <td>:</td>
                                                    <td><div id="txt_district"><select class="select2a_n" id="DistrictCode" name="DistrictCode" onchange="Javascript:show_zone('zonelistUser', this.options[this.selectedIndex].value, '');" <?php echo $disaTxt ?>>
                                                                <!--<option value="">-Select District-</option>-->
                                                                <?php
                                                                /* if($accLevel==13050 || $accLevel==12050 || $accLevel==12000 || $accLevel==11050 || $accLevel==11000 || $accLevel==10000){ */
                                                                if ($AccessRoleType == 'PD') {
                                                                    $sql = "SELECT DistCode,DistName FROM CD_Districts where ProCode='$proCodeLoged' order by DistName asc";
                                                                    echo "<option value=\"\">-Select District-</option>-->";
                                                                } else {
                                                                    $sql = "SELECT DistCode,DistName FROM CD_Districts where DistCode='$distCodeLoged' order by DistName asc";
                                                                }
                                                                $stmt = $db->runMsSqlQuery($sql);
                                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                    $DistCoded = trim($row['DistCode']);
                                                                    $DistNamed = $row['DistName'];
                                                                    $seltebr = "";
                                                                    if ($DistCoded == $distCodeLoged) {
                                                                        $seltebr = "selected";
                                                                    }
                                                                    echo "<option value=\"$DistCoded\" $seltebr>$DistNamed</option>";
                                                                }
                                                                ?>
                                                            </select></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Zone List <?php echo $loggedSchool; ?> <span class="form_error">*</span></td>
                                                    <td>:</td>
                                                    <td><div id="txt_zone"><select class="select2a" id="InstCodeZ" name="InstCode" tabindex="27">
                                                                <!--<option value="">-Select Zone-</option>-->
                                                                <?php
                                                                $DivisionCode = "abc";

                                                                if ($AccessRoleType == 'ZN') {
                                                                    $sql = "SELECT [InstType]
								,[CenCode]
								,[InstitutionName]
								FROM [dbo].[CD_CensesNo] where CenCode='$loggedSchool' order by InstitutionName";
                                                                } else {
                                                                    $sql = "SELECT [InstType]
								,[CenCode]
								,[InstitutionName]
								FROM [dbo].[CD_CensesNo] where CenCode LIKE '%ZN%' and DistrictCode='$distCodeLoged'
								order by InstitutionName";

                                                                    echo "<option value=\"\">-Select Zone-</option>-->";
                                                                }

                                                                $stmt = $db->runMsSqlQuery($sql);
                                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                    $ZoneCoded = trim($row['CenCode']);
                                                                    $InstitutionName = addslashes(str_replace("EDUCATION RESOURCE CENTRE ", "", $row['InstitutionName']));
                                                                    $seltebr = "";
                                                                    if ($ZoneCoded == $zoneCodeLoged) {
                                                                        $seltebr = "selected";
                                                                    }

                                                                    echo "<option value=\"$ZoneCoded\" $seltebr>$InstitutionName</option>";
                                                                }
                                                                ?>
                                                            </select></div></td>
                                                </tr>
                                            </tbody>
                                        </table></div>
                                    <div id="devisionDept" style="display:block;"><table width="100%" border="0" cellspacing="2" cellpadding="2">
                                            <tbody>
                                                <tr>
                                                    <td width="28%">Province</td>
                                                    <td width="3%">:</td>
                                                    <td width="69%"><select class="select2a_n" id="ProCode" name="ProCode" onchange="Javascript:show_district_div('districtlistForDevi', this.options[this.selectedIndex].value, '');" <?php echo $disaTxt ?>>
                                                            <!--<option value="">Select Province</option>-->
                                                            <?php
                                                            /* if($accLevel==13050 || $accLevel==12050 || $accLevel==12000 || $accLevel==10000 || $accLevel==11000 || $accLevel==11050){ */
                                                            if ($AccessRoleType == 'NC') {
                                                                $sql = "SELECT ProCode,Province FROM CD_Provinces order by Province asc";
                                                            } else {
                                                                $sql = "SELECT ProCode,Province FROM CD_Provinces where ProCode='$proCodeLoged'";
                                                            }
                                                            $stmt = $db->runMsSqlQuery($sql);
                                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                $ProCoded = trim($row['ProCode']);
                                                                $DistNamed = $row['Province'];
                                                                $seltebr = "";
                                                                if ($ProCoded == $proCodeLoged) {
                                                                    $seltebr = "selected";
                                                                }
                                                                echo "<option value=\"$ProCoded\" $seltebr>$DistNamed</option>";
                                                            }
                                                            ?>
                                                        </select></td>
                                                </tr>
                                                <tr>
                                                    <td>District</td>
                                                    <td>:</td>
                                                    <td><div id="txt_district_div"><select class="select2a_n" id="DistrictCode" name="DistrictCode" onchange="Javascript:show_zone_div('zonelistForDevi', this.options[this.selectedIndex].value, '');" <?php echo $disaTxt ?>>
                                                                <!--<option value="">-Select District-</option>-->
                                                                <?php
                                                                /* if($accLevel==13050 || $accLevel==12050 || $accLevel==12000 || $accLevel==10000 || $accLevel==11000 || $accLevel==11050){ */
                                                                if ($AccessRoleType == 'PD') {
                                                                    $sql = "SELECT DistCode,DistName FROM CD_Districts where ProCode='$proCodeLoged' order by DistName asc";
                                                                    echo "<option value=\"\">-Select District-</option>";
                                                                } else if ($AccessRoleType == 'DN') {
                                                                    $sql = "SELECT DistCode,DistName FROM CD_Districts where DistCode='$distCodeLoged' order by DistName asc";
                                                                } else if ($AccessRoleType == 'ZN') {
                                                                    $sql = "SELECT DistCode,DistName FROM CD_Districts where DistCode='$distCodeLoged' order by DistName asc";
                                                                } else {
                                                                    $sql = "SELECT DistCode,DistName FROM CD_Districts  order by DistName asc"; //where DistCode='$distCodeLoged' 20170721
                                                                }
                                                                $stmt = $db->runMsSqlQuery($sql);
                                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                    $DistCoded = trim($row['DistCode']);
                                                                    $DistNamed = $row['DistName'];
                                                                    $seltebr = "";
                                                                    if ($DistCoded == $distCodeLoged) {
                                                                        $seltebr = "selected";
                                                                    }
                                                                    echo "<option value=\"$DistCoded\" $seltebr>$DistNamed</option>";
                                                                }
                                                                ?>
                                                            </select></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Zone</td>
                                                    <td>:</td>
                                                    <td><div id="txt_zone_div"><select class="select2a_n" id="ZoneCode" name="ZoneCode" onChange="Javascript:show_division('divisionListUser', this.options[this.selectedIndex].value, document.frmSave.DistrictCode.value);" tabindex="25">
                                                                <!--<option value="">-Select Zone-</option>-->
                                                                <?php
//distCodeLoged,ProCode='$proCodeLoged'
                                                                if ($AccessRoleType == 'ZN') {
                                                                    $sql = "SELECT CenCode,InstitutionName FROM CD_Zone where CenCode='$zoneCodeLoged'";
                                                                } else if ($AccessRoleType == 'DN') {
                                                                    $sql = "SELECT CenCode,InstitutionName FROM CD_Zone where DistrictCode='$distCodeLoged'  order by InstitutionName ASC ";
                                                                    echo " <option value=\"\">-Select Zone-</option>";
                                                                } else if ($AccessRoleType == 'ED') {
                                                                    $sql = "SELECT CenCode,InstitutionName FROM CD_Zone where CenCode='$zoneCodeLoged'";
                                                                } else {
                                                                    $sql = "SELECT CenCode,InstitutionName FROM CD_Zone where DistrictCode='$distCodeLoged' order by InstitutionName asc";
                                                                    echo " <option value=\"\">-Select Zone-</option>";
                                                                }

                                                                $stmt = $db->runMsSqlQuery($sql);
                                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                    $zoneCoded = trim($row['CenCode']);
                                                                    $DSNamed = $row['InstitutionName'];
                                                                    $seltebr = "";
                                                                    if ($zoneCoded == $zoneCodeLoged) {
                                                                        $seltebr = "selected";
                                                                    }
                                                                    echo "<option value=\"$zoneCoded\" $seltebr>$DSNamed</option>";
                                                                }
                                                                ?>
                                                            </select></div></td>
                                                </tr>
                                                <tr>
                                                    <td>Division<?php //echo $divCodeLoged;     ?> <span class="form_error">*</span></td>
                                                    <td>:</td>
                                                    <td><div id="txt_division">
                                                            <select class="select2a" id="InstCodeDI" name="InstCode" tabindex="27">
                                                                <option value="">-Select Division-</option>
                                                                <?php
                                                                if ($AccessRoleType == 'ED') {
                                                                    $sql = "SELECT [InstType]
								,[CenCode]
								,[InstitutionName]
								FROM [dbo].[CD_CensesNo] where CenCode LIKE '%ED%' and DivisionCode='$divCodeLoged'
								order by InstitutionName";
                                                                } else {
                                                                    $sql = "SELECT [InstType]
								,[CenCode]
								,[InstitutionName]
								FROM [dbo].[CD_CensesNo] where CenCode LIKE '%ED%' and ZoneCode='$zoneCodeLoged'
								order by InstitutionName";
                                                                }
                                                                $stmt = $db->runMsSqlQuery($sql);
                                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                    $CenCode = trim($row['CenCode']);
                                                                    $InstitutionName = addslashes(str_replace("EDUCATION RESOURCE CENTRE ", "", $row['InstitutionName']));
                                                                    $seltebr = "";
                                                                    if ($CenCode == $loggedSchool) {
                                                                        $seltebr = "selected";
                                                                    }
                                                                    echo "<option value=\"$CenCode\" $seltebr>$InstitutionName</option>";
                                                                }
                                                                ?>
                                                            </select>
                                                        </div></td>
                                                </tr>
                                            </tbody>
                                        </table></div>
                                </td>
                            </tr>


                        </table></td>
                </tr>
                <tr>
                    <td><?php echo $TotaRows ?> Record(s) found.</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                            <tr>
                                <td width="2%" height="25" align="center" bgcolor="#999999">#</td>
                                <td width="7%" align="center" bgcolor="#999999">NIC</td>
                                <td width="30%" align="center" bgcolor="#999999">Name</td>
                                <td width="21%" align="center" bgcolor="#999999">Designation</td>
                                <td width="34%" align="center" bgcolor="#999999">Location</td>
                                <td width="6%" align="center" bgcolor="#999999">Delete</td>
                            </tr>
                            <?php
                            $i = 1;
                            // echo $sqlList;
                            $stmt = $db->runMsSqlQuery($sqlList);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $NICNo = trim($row['NICNo']);
                                $SurnameWithInitials = $row['SurnameWithInitials'];
                                $InstitutionName = $row['InstitutionName'];
                                $AccessRole = $row['AccessRole'];
                                $Expr1 = $row['ID'];
                                ?>
                                <tr>
                                    <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                                    <td align="left" bgcolor="#FFFFFF"><?php echo $NICNo ?></td>
                                    <td bgcolor="#FFFFFF" align="left"><?php echo $SurnameWithInitials ?></td>
                                    <td bgcolor="#FFFFFF" align="left"><?php echo $AccessRole ?></td>
                                    <td bgcolor="#FFFFFF" align="left"><?php echo $InstitutionName ?></td>
                                    <td bgcolor="#FFFFFF" align="center"><a href="javascript:aedWin('<?php echo $NICNo ?>','D','ControlUser','<?php echo $tblNam ?>','<?php echo "$ttle-$pageid.html"; ?>')">Delete <?php //echo $Expr1      ?></a></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td bgcolor="#FFFFFF">&nbsp;</td>
                                <td bgcolor="#FFFFFF">&nbsp;</td>
                                <td bgcolor="#FFFFFF">&nbsp;</td>
                                <td bgcolor="#FFFFFF">&nbsp;</td>
                                <td bgcolor="#FFFFFF">&nbsp;</td>
                                <td bgcolor="#FFFFFF">&nbsp;</td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </div>

    </form>
</div>


<script>

    $("#frmSave").submit(function (event) {//alert('hi');
        var dialogStatus = false;
        var AccessLevel = trim($("#AccessLevel").val());
        var SurnameWithInitials = trim($("#SurnameWithInitials").val());
        var FullName = trim($("#FullName").val());
        var NIC = trim($("#NIC").val());
        var CurPassword = trim($("#CurPassword").val());
        var CurPasswordRT = trim($("#CurPasswordRT").val());
        var validationStatus = false;
        //alert("hi");

        if (AccessLevel == "") {
                    $("#AccessLevel").attr('class', 'input2_error');
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

        if (NIC == "") {
            $("#NIC").attr('class', 'input2_error');
            dialogStatus = true;
        }
        else {
         validationStatus = validateNIC(NIC);
         if(validationStatus == false) {
          $("#NIC").attr('class', 'input2_error');
          dialogStatus = true;
         }
        }
        
        if (CurPassword == "") {
            $("#CurPassword").attr('class', 'input3_error');
            dialogStatus = true;
        }
        
        if (CurPasswordRT == "") {
            $("#CurPasswordRT").attr('class', 'input3_error');
            dialogStatus = true;
        }

        if (dialogStatus) {

            event.preventDefault();
        }


    });

function validateNIC(nic) {
 // Do NIC validation here
 var  nicLength = nic.length;
 var result = 0;

 // Handle NICs of wrong lengths. Only correct lengths are 10 and 12.
 if (nicLength < 10)
  return false;
 else if (nicLength == 11)
  return false;
 else if (nicLength > 12)
  return false;

 // Process the old NIC numbers of 10-digits
 if (nicLength == 10) {
  //used algorithm is 11 - (N1*3 + N2*2 + N3*7 + N4*6 + N5*5 + N6*4 + N7*3 + N8*2) % 11
  result = 11 - (nic.charAt(0) * 3 + nic.charAt(1) * 2 + nic.charAt(2) * 7 + nic.charAt(3) * 6 + nic.charAt(4) * 5 + nic.charAt(5) * 4 + nic.charAt(6) * 3 + nic.charAt(7) * 2) % 11;

  if (result == 11) {
   result = 0;
  }
  else if (result == 10) {
   result = 0;
  }

  if ((result == nic.charAt(8))&& ((nic.charAt(9) == 'v') || (nic.charAt(9) == 'x') || (nic.charAt(9) == 'V')||(nic.charAt(9) == 'X'))) { // compare with check digit at 9th position and V or X in 10th position
   // At this point, we have a valid NIC
   return true;
  } else {
   return false;
  }
  // we have finished checking the 10-digit NIC

 // Process the new NIC numbers of 12-digits
 } else if (nicLength == 12) {
  //used algorithm is 11 - (N1*8 + N2*4 + N3*3 + N4*2 + N5*7 + N6*6 + N7*5 + N8*8 + N9*4 + N10*3 + N11*2) % 11
  result = 11 - (nic.charAt(0) * 8 + nic.charAt(1) * 4 + nic.charAt(2) * 3 + nic.charAt(3) * 2 + nic.charAt(4) * 7 + nic.charAt(5) * 6 + nic.charAt(6) * 5 + nic.charAt(7) * 8 + nic.charAt(8) * 4 + nic.charAt(9) * 3 + nic.charAt(10) * 2) % 11;

  if (result == 11) {
   result = 0;
  }
  else if (result == 10) {
   result = 0;
  }

  if (result == nic.charAt(11)) { // compare with check digit at 12th position
   // At this point, we have a valid NIC
   return true;
  } else {
   return false;
  }
   
 } // we have finished checking the 12-digit NIC
}

    function trim(str) {
        return str.replace(/^\s+|\s+$/g, '');

    }
    
    $(function() {
                $(".input2_n").click(function() {
                    changeTextBoxCss1(this);
                });

                $(".input2_n").keyup(function() {
                    changeTextBoxCss1(this);
                });
                
                $(".input3").keyup(function() {
                    changeTextBoxCssSm(this);
                });
                
                $(".input3").click(function() {
                    changeTextBoxCssSm(this);
                });
                
                $(".select2a_n").keyup(function() {
                    changeTextBoxCssDb(this);
                });
                
                $(".select2a_n").click(function() {
                    changeTextBoxCssDb(this);
                }); 
            });
            
            function changeTextBoxCss1(obj) {
                var currentId1 = $(obj).attr('id');
                // alert(currentId1);
                $("#" + currentId1).attr('class', 'input2_n');
            }
            
            function changeTextBoxCssSm(obj) {
                var currentId1 = $(obj).attr('id');
                // alert(currentId1);
                $("#" + currentId1).attr('class', 'input3');
            }
            
            function changeTextBoxCssDb(obj) {
                var currentId1 = $(obj).attr('id');
                // alert(currentId1);
                $("#" + currentId1).attr('class', 'select2a_n');
            }

</script>


