<?php 
session_start();
$oldnic='';
$newnic='';
$connic='';
$NICUser = $_SESSION["NIC"];
if (isset($_POST["FrmSrch"])) {
    $NICSrch = $_REQUEST['NICNo'];
}

$show_status = TRUE;

if (isset($_POST["FrmSubmit"])) {
    
    $AccessLevel = $_REQUEST['AccessLevel'];
    $CurPassword = $_REQUEST['CurPassword'];
    $CurPasswordRT = $_REQUEST['CurPasswordRT'];
    $insertTyp = $_REQUEST['insertTyp'];
    $NICSrch = $_REQUEST['NICNo'];
    $NICUser = $_SESSION["NIC"];

    $sqlnic='SELECT NIC FROM TeacherMast';
    $server = "DESKTOP-OESJB7N\SQLEXPRESS"; 
    $connectionInfo = array("UID" => "sa", "PWD" => "na1234", "Database"=>"MOENational");
    $conn= sqlsrv_connect($server, $connectionInfo);
    // $NICUser = $_REQUEST['NIC'];
    $db = new DBManager();

    // $request = $_REQUEST['request'];
    $newnic = trim($_POST['newnic']); // Working var_dump
    $connic = trim($_POST['connic']); // Working var_dump
    // $oldnic = trim($_POST['oldnic']);
    
    $sqlsel1 = "SELECT (NIC) FROM TEACHERMAST where NIC='$newnic'"; // starting to check if the NIC exist in the system
    $param1 = array($newnic);
    // sqlsrv_query($conn, $sqlsel1, $param1);
    
    $sqlsel2 = "SELECT (NIC) FROM StaffAddrHistory where NIC='$newnic'";
    $param2 = array($newnic);
    // sqlsrv_query($conn, $sqlsel2, $param2);

    $sqlsel3 = "SELECT (NIC) FROM StaffQualification where NIC='$newnic'";
    $param3 = array($newnic);

    $sqlsel4 = "SELECT (NIC) FROM StaffServiceHistory where NIC='$newnic'";
    $param4 = array($newnic);
    
    $sqlsel5 = "SELECT NICNo FROM Passwords where NICNo='$newnic'";
    $param5 = array($newnic);

    $sqlsel6 = "SELECT (NIC) FROM TeacherMedium where NIC='$newnic'";
    $param6 = array($newnic);

    $sqlsel7 = "SELECT (NIC) FROM TeacherSubject where NIC='$newnic'";
    $param7 = array($newnic);


    
    $total = $db->rowCount($sqlsel1) + $db->rowCount($sqlsel2) + $db->rowCount($sqlsel3) + $db->rowCount($sqlsel4) + $db->rowCount($sqlsel5) + $db->rowCount($sqlsel6) + $db->rowCount($sqlsel7);
    echo $total;
    if($total==0){
        if($newnic=='' || $connic==''){
            ?>
                <script type="text/javascript">
                alert("Complete all the fileds");
                window.location.href = "index.php";
                </script>
            <?php
        }   
        elseif($newnic == $connic){
            $nicLength = strlen($newnic);
            if ($nicLength < 10){
                ?>
                <script type="text/javascript">
                alert("Enter NIC of correct length");
                window.location.href = "index.php";
                </script>
                <?php
            }  
            if ($nicLength == 11){
            // $error_msg = "Enter NIC of correct length";
                ?>
                <script type="text/javascript">
                alert("Enter NIC of correct length");
                window.location.href = "index.php";
                </script>
                <?php
            }        
            if ($nicLength > 12){
                // $error_msg = "Enter NIC of correct length";
                ?>
                <script type="text/javascript">
                alert("Enter NIC of correct length");
                 window.location.href = "index.php";
                </script>
                <?php
                }
                
            if (strlen($newnic) == 10) {   
                //used algorithm is 11 - (N1*3 + N2*2 + N3*7 + N4*6 + N5*5 + N6*4 + N7*3 + N8*2) % 11
                $result = 11 - ($newnic[0] * 3 + $newnic[1] * 2 + $newnic[2] * 7 + $newnic[3] * 6 + $newnic[4] * 5 + $newnic[5] * 4 + $newnic[6] * 3 + $newnic[7] * 2) % 11;
                
                if ($result == '11') {
                    $result = '0';
                } 
                if ($result == '10') {
                        $result = '0';
                }
                if (($result == $newnic[8]) && (($newnic[9] == 'v') || ($newnic[9] == 'x') || ($newnic[9] == 'V')||($newnic[9] == 'X'))) { // compare with check digit at 9th position and V or X in 10th position
                    // At this point, we have a valid NIC
                    ?>
                    <script>
                    var txt;
                    var r = confirm("This Action will be changed the NIC number in the system.");
                    if(r == false){
                        window.location.href = "index.php";
                        }
                    </script>
    
                    <?php
                        if ( sqlsrv_begin_transaction( $conn ) === false ) {
                            die( print_r( sqlsrv_errors(), true ));
                        }
                        $sql1 = "UPDATE TEACHERMAST SET NIC = '$_POST[newnic]' WHERE NIC= '$NICSrch'";
                        $params1 = array($newnic);
                        $stmt1 = sqlsrv_query( $conn, $sql1, $params1 );
    
                        $sql2 = "UPDATE StaffAddrHistory SET NIC = '$_POST[newnic]' WHERE NIC= '$NICSrch'";
                        $params2 = array($newnic);
                        $stmt2 = sqlsrv_query( $conn, $sql2, $params2 );
    
                        $sql3 = "UPDATE StaffQualification SET NIC = '$_POST[newnic]' WHERE NIC= '$NICSrch'";
                        $params3 = array($newnic);
                        $stmt3 = sqlsrv_query( $conn, $sql3, $params3 );
    
                        $sql4 = "UPDATE StaffServiceHistory SET NIC = '$_POST[newnic]' WHERE NIC= '$NICSrch'";
                        $params4 = array($newnic);
                        $stmt4 = sqlsrv_query( $conn, $sql4, $params4 );
    
                        $sql5 = "UPDATE Passwords SET NICNo = '$_POST[newnic]' WHERE NICNo= '$NICSrch'";
                        $params5 = array($newnic);
                        $stmt5 = sqlsrv_query( $conn, $sql5, $params5 );
    
                        $sql6 = "UPDATE TeacherMedium SET NIC = '$_POST[newnic]' WHERE NIC= '$NICSrch'";
                        $params6 = array($newnic);
                        $stmt6 = sqlsrv_query( $conn, $sql6, $params6 );
    
                        $sql7 = "UPDATE TeacherSubject SET NIC = '$_POST[newnic]' WHERE NIC= '$NICSrch'";
                        $params7 = array($newnic);
                        $stmt7 = sqlsrv_query( $conn, $sql7, $params7 );
    
                        $sql8 = "INSERT INTO changeNICLog(oldNIC, newNIC, changedBy) VALUES ('$NICSrch','$_POST[newnic]', '$NICUser')";
                        $params8 = array($NICSrch,$newnic,$NICUser);
                        $stmt8 = sqlsrv_query( $conn, $sql8, $params8 );
                        
                        if($stmt1 && $stmt2 && $stmt3 && $stmt4 && $stmt5 && $stmt6 && $stmt7 && $stmt8) {
                            sqlsrv_commit( $conn );
                            echo "Updates committed.<br />";?>
                            <script type="text/javascript">
                            alert("NIC Updated in the system.");
                            window.location.href = "index.php";
                            </script>
                            <?php
                        } else {
                            sqlsrv_rollback( $conn );
                            echo "Updates rolled back.<br />";
                        }
                }
                else{
                    ?>
                        <script type="text/javascript">
                        alert("NIC you have entered is not valid.");
                        window.location.href = "index.php";
                        </script>
                    <?php
                }
            }
            elseif (strlen($newnic) == 12) {
                //used algorithm is 11 - (N1*8 + N2*4 + N3*3 + N4*2 + N5*7 + N6*6 + N7*5 + N8*8 + N9*4 + N10*3 + N11*2) % 11
                $result = 11 - ($newnic[0] * 8 + $newnic[1] * 4 + $newnic[2] * 3 + $newnic[3] * 2 + $newnic[4] * 7 + $newnic[5] * 6 + $newnic[6] * 5 + $newnic[7] * 8 + $newnic[8] * 4 + $newnic[9] * 3 + $newnic[10] * 2) % 11;
                 
                if ($result == '11') {
                    $result = '0';
                }
                 
                if ($result == '10') {
                    $result = '0';
                }
                else {
                ?>
                    <script type="text/javascript">
                    alert("NIC you have entered is not valid.");
                    window.location.href = "index.php";
                    </script>
                <?php 
                }
                    
            }
        }
    }
    else{
        ?>
            <script type="text/javascript">
            alert("The NIC number already exist in the system.");
            window.location.href = "index.php";
            </script>
        <?php

    }// stop  check if the NIC exist in the system

    

    
}

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
	CD_Zone.InstitutionName as ZoneName,
	CD_Division.InstitutionName as DivisionName,
        CD_Districts.ProCode,
	CD_CensesNo.ZoneCode,
	CD_CensesNo.DivisionCode
FROM
	TeacherMast
INNER JOIN CD_Title ON TeacherMast.Title = CD_Title.TitleCode
LEFT JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID
LEFT JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
LEFT JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
LEFT JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
LEFT JOIN CD_Division ON CD_CensesNo.DivisionCode = CD_Division.CenCode
WHERE TeacherMast.NIC = N'$NICSrch'";

    $stmt = $db->runMsSqlQuery($srchQry);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $SurnameWithInitials = $row['SurnameWithInitials'];
    $FullName = $row['FullName'];
    $TitleName = $row['TitleName'];
    $InstitutionName = $row['InstitutionName'];
    $DistName = $row['DistName'];
    $ZoneName = $row['ZoneName'];
    $DivisionName = $row['DivisionName'];
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
                    <td colspan="2" style="border-bottom:1px; border-bottom-style:solid;">
<?php
if ($NICSrch != '') {
    if ($CurPassword == '') {
        ?>
        <span style="color:#F00; font-weight:bold;">User account does not exist.</span><?php 
    }
    else{
        ?>
        <!--<span style="color:#090; font-weight:bold;">User account already exist.</span>-->
        <?php
    }
}
?>


</td>
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
                                    <td><strong>Zone</strong></td>
                                    <td>:</td>
                                    <td><?php echo $ZoneName ?></td> 
                                </tr>
				<tr>
                                    <td><strong>Division</strong></td>
                                    <td>:</td>
                                    <td><?php echo $DivisionName ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Institution</strong></td>
                                    <td>:</td>
                                    <td><?php echo $InstitutionName ?></td>
                                </tr>
                                <!-- <div class="main_content_inner_block"> -->
<?php
    if ($CurPassword != '' && $show_status== TRUE && in_array($AccessID, array_map("trim", explode(',', $SeeControlLevel)))) {  
?>
<br>
<!-- <div style="padding-left: 10px;"> -->
<table>
    <tr>
    <td><h4>Change NIC number:</h4></td>
    </tr>
    <!-- <tr>
        <td>Enter old NIC Number:</td>
        <td><input type="text" id="oldnic" size="20" value='<?php //echo $oldnic ?>' name="oldnic"></td>
    </tr>
    <tr> -->
        <td>Enter new NIC Number:</td>
        <td><input type="text" id="newnic" size="20" value='<?php echo $newnic ?>' name="newnic"></td>
    </tr>
    <tr>
    <td>Confirm NIC Number:</td>
        <td><input type="text" id="connic" size="20" value='<?php echo $connic ?>' name="connic"></td>
    </tr>
    <tr>
        <td><br><input name="FrmSubmit" type="submit" id="FrmSubmit" value="Change NIC" /></td>
    </tr>
</table>
</form>
<!-- </div> -->
</div>
</div>
<script>
    function myFunction() {
        var pass = document.getElementById("newnic").value;
        var cpass = document.getElementById("connic").value;
        submitOK = "true";

    if (pass != cpass) {
        alert("ID numbers are not matching");
        submitOK = "false";
    }
    if (submitOK == "false") {
        return false;
    }
}

</script>
<?php 
    }
    else{
        ?>
        <script type="text/javascript">
            alert("The user is not belong to your zone");
            window.location.href = "index.php";
        </script>
        <?php
    }
}
?>


<?php

?>