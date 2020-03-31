<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<script src="js/teacherFilter.js"  language="javascript"></script>
<?php

$nicNO = $_SESSION["NIC"];
$LOGGEDUSERID = $nicNO;
$ACCESSLEVEL = "";
$censesStatus = "";

// ** get loged user information
$sql = "SELECT
  Passwords.NICNo,
  Passwords.AccessLevel,
  TeacherMast.emailaddr,
  TeacherMast.Title,
  CD_Title.TitleName + TeacherMast.SurnameWithInitials AS name,
  CD_AccessRoles.AccessRoleType
FROM Passwords
INNER JOIN TeacherMast
  ON Passwords.NICNo = TeacherMast.NIC
INNER JOIN CD_Title
  ON TeacherMast.Title = CD_Title.TitleCode
INNER JOIN CD_AccessRoles
  ON Passwords.AccessLevel = CD_AccessRoles.AccessRoleValue
WHERE (Passwords.NICNo = N'$nicNO')";

$stmt1 = $db->runMsSqlQuery($sql);

while ($row = sqlsrv_fetch_array($stmt1)) {
    $_SESSION["AccessLevel"] = $row["AccessLevel"];
    $ACCESSLEVEL = $row["AccessLevel"];
    $emailaddr = $row["emailaddr"];
    $_SESSION["fullName"] = $row["name"];
    $accessRoleType = trim($row["AccessRoleType"]);
}
//echo $accessRoleType;
// ****


if ($accessRoleType == "PD") {

    $sqlPD = "SELECT 
    TeacherMast.NIC, 
    StaffServiceHistory.InstCode,
    CD_CensesNo.InstitutionName,
    CD_CensesNo.DivisionCode,
    CD_CensesNo.ZoneCode, 
    CD_CensesNo.DistrictCode, 
    CD_Provinces.ProCode 
FROM
    TeacherMast
        INNER JOIN
    StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID
        INNER JOIN
    CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
        INNER JOIN
    CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
        INNER JOIN
    CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
WHERE
    (TeacherMast.NIC = N'$nicNO')";

    $stmtPD = $db->runMsSqlQuery($sqlPD);
    while ($rowPD = sqlsrv_fetch_array($stmtPD)) {
        $ProCode = trim($rowPD["ProCode"]);
        $District = trim($rowPD["DistrictCode"]);
        $District = null;
        $ZONECODE = null;
        $Division = null;
        $SCType = null;
    }
}

// for district
if ($accessRoleType == "DN") {

    $sqlPD = "SELECT 
    TeacherMast.NIC, 
    StaffServiceHistory.InstCode,
    CD_CensesNo.InstitutionName,
    CD_CensesNo.DivisionCode,
    CD_CensesNo.ZoneCode, 
    CD_CensesNo.DistrictCode, 
    CD_Provinces.ProCode 
FROM
    TeacherMast
        INNER JOIN
    StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID
        INNER JOIN
    CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
        INNER JOIN
    CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
        INNER JOIN
    CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
WHERE
    (TeacherMast.NIC = N'$nicNO')";

    $stmtPD = $db->runMsSqlQuery($sqlPD);
    while ($rowPD = sqlsrv_fetch_array($stmtPD)) {
        $ProCode = trim($rowPD["ProCode"]);
        $District = trim($rowPD["DistrictCode"]);
        $ZONECODE = null;
        $Division = null;
        $SCType = null;
    }
}

// For zone
if ($accessRoleType == "ZN") {

    $sqlPD = "SELECT 
    TeacherMast.NIC, 
    StaffServiceHistory.InstCode,
    CD_CensesNo.InstitutionName,
    CD_CensesNo.DivisionCode,
    CD_CensesNo.ZoneCode, 
    CD_CensesNo.DistrictCode, 
    CD_Provinces.ProCode 
FROM
    TeacherMast
        INNER JOIN
    StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID
        INNER JOIN
    CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
        INNER JOIN
    CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
        INNER JOIN
    CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
WHERE
    (TeacherMast.NIC = N'$nicNO')";

    $stmtPD = $db->runMsSqlQuery($sqlPD);
    while ($rowPD = sqlsrv_fetch_array($stmtPD)) {
        $ProCode = trim($rowPD["ProCode"]);
        $District = trim($rowPD["DistrictCode"]);
        $ZONECODE = trim($rowPD["InstCode"]);
        $Division = null;
        $SCType = null;
    }
}

// For Division
if ($accessRoleType == "ED") {

    $sqlPD = "SELECT 
    TeacherMast.NIC, 
    StaffServiceHistory.InstCode,
    CD_CensesNo.InstitutionName,
    CD_CensesNo.DivisionCode,
    CD_CensesNo.ZoneCode, 
    CD_CensesNo.DistrictCode, 
    CD_Provinces.ProCode 
FROM
    TeacherMast
        INNER JOIN
    StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID
        INNER JOIN
    CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
        INNER JOIN
    CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
        INNER JOIN
    CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
WHERE
    (TeacherMast.NIC = N'$nicNO')";

    $stmtPD = $db->runMsSqlQuery($sqlPD);
    while ($rowPD = sqlsrv_fetch_array($stmtPD)) {
        $ProCode = trim($rowPD["ProCode"]);
        $District = trim($rowPD["DistrictCode"]);
        $ZONECODE = trim($rowPD["ZoneCode"]);
        $Division = trim($rowPD["DivisionCode"]);
        $SCType = null;
    }
}


// For National Nominator and MO
if ($accessRoleType == "NC" || $accessRoleType == "MO") {
    $SCType = null;
    $ProCode = null;
    $District = null;
    $ZONECODE = null;
    $Division = null;
}

$params = array(
    array($LOGGEDUSERID, SQLSRV_PARAM_IN),
    array($ACCESSLEVEL, SQLSRV_PARAM_IN),
    array($ProCode, SQLSRV_PARAM_IN)
);


$params1 = array(
    array($LOGGEDUSERID, SQLSRV_PARAM_IN),
    array($ACCESSLEVEL, SQLSRV_PARAM_IN),
    array($ProCode, SQLSRV_PARAM_IN),
    array($District, SQLSRV_PARAM_IN),
    array($ZONECODE, SQLSRV_PARAM_IN)
);

$params4 = array(
    array($LOGGEDUSERID, SQLSRV_PARAM_IN),
    array($ACCESSLEVEL, SQLSRV_PARAM_IN),
    array($SCType, SQLSRV_PARAM_IN),
    array($ProCode, SQLSRV_PARAM_IN),
    array($District, SQLSRV_PARAM_IN),
    array($ZONECODE, SQLSRV_PARAM_IN),
    array($Division, SQLSRV_PARAM_IN),
    array($censesStatus, SQLSRV_PARAM_IN)
);

//var_dump($params);
?>
<form id="formReport" name="formReport" action="" method="post" enctype="multipart/form-data" onSubmit="return submitForm(formReport);">
    <div class="main_content_inner_block">
        <div id="geographical" class="contenttab">


            <div class="productsItemBoxText">
                <div>
                    <label for="username" class="labelTxt" style="margin-left:22px;"><strong>School Type :</strong></label>
                    <label for="username" class="labelTxt"><strong>Province :</strong></label>
                    <label for="username" class="labelTxt"><strong>District :</strong></label>
                </div>

                <div>
                    <div class="divSimple" style="margin-left:22px;">
                        <select style="width:260px;" onchange="loadAccordingToSCType();" id="cmbSchoolType" name="cmbSchoolType">

                            <option value="">All</option>                                
                            <?php
//School Type                                                        

                            $sql = "SELECT ID,Category FROM [CD_CensesCategory]";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                    echo '<option value=' . $row['ID'] . '>' . $row['Category'] . '</option>';
                            }
                            ?>
                        </select>


                    </div>
                    <div class="divSimple">

                        <select style="width:260px;" id="cmbProvince" name="cmbProvince" onchange="loadAccordingToProvince();">

                            <?php
//Province

                            $sql = "{call SP_GetProvinceFor_LoggedUser( ?, ?, ? )}";
                            //$stmt = $db->runMsSqlQuery($sql, $params);

                            $rcount = $db->runMsSqlQueryForSP($sql, $params);
                            $qResult = $rcount['result'];
                            $count = $rcount['count'];
                            if ($count > 1)
                                echo "<option value=\"\">All</option>";
//SELECT @@ROWCOUNT;
                            while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                    echo '<option value=' . $row['PROCODE'] . '>' . $row['Province'] . '</option>';
                            }
                            ?>
                        </select>

                    </div>
                    <div class="divSimple">
                        <select style="width:260px;" id="cmbDistrict" name="cmbDistrict" onchange="loadAccordingToDistrict();">

                            <?php
//District
//$sql = "SELECT DistCode,DistName FROM [CD_Districts] WHERE (DistCode != '')";
//if ($ProCode == "")

                            $sql = "{call SP_TG_GetDistrictFor_LoggedUser( ?, ?, ?)}";

                            //$stmt = $db->runMsSqlQuery($sql, $params);

                            $rcount = $db->runMsSqlQueryForSP($sql, $params);
                            $qResult = $rcount['result'];
                            $count = $rcount['count'];
                            if ($count > 1)
                                echo "<option value=\"\">All</option>";

                            while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                    echo '<option value=' . $row['DistCode'] . '>' . $row['DistName'] . '</option>';
                            }
                            ?>
                        </select>

                    </div>
                </div>

                <div>
                    <label for="username" class="labelTxt" style="margin-left:22px;"><strong>Zone :</strong></label>
                    <label for="username" class="labelTxt"><strong>Division :</strong></label>
                    <label for="username" class="labelTxt"><strong>School Status</strong></label>
                </div>

                <div>
                    <div class="divSimple" style="margin-left:22px;">
                        <select style="width:260px;" id="cmbZone" name="cmbZone" onchange="loadAccordingToZone();">

                            <?php
//Zone
//$sql = "SELECT CenCode,CONCAT(CenCode,'- ',InstitutionName) AS Zone FROM [CD_CensesNo] WHERE (InstType = 'ZN')";
                            $sql = "{call SP_TG_GetZonesFor_LooggedUser( ?, ?, ? ,?, ?)}";

                            // $stmt = $db->runMsSqlQuery($sql, $params4);

                            $rcount = $db->runMsSqlQueryForSP($sql, $params1);
                            $qResult = $rcount['result'];
                            $count = $rcount['count'];
                            if ($count > 1)
                                echo "<option value=\"\">All</option>";


                            while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {                                
                                    echo '<option value=' . $row['CenCode'] . '>' . $row['Zone'] . '</option>';
                            }
                            ?>
                        </select>

                    </div>
                    <div class="divSimple">
                        <select style="width:260px;" id="cmbDivision" name="cmbDivision" onchange="loadAccordingToDivision();">

                            <?php
//Division
//$sql = "SELECT CenCode,CONCAT(CenCode,'- ',InstitutionName) AS Division FROM [CD_CensesNo] WHERE (InstType = 'ED')";
                            $sql = "{call SP_TG_GetDivisionFor_LooggedUser( ?, ?, ? , ?, ?)}";

                            //$stmt = $db->runMsSqlQuery($sql, $params1); 

                            $rcount = $db->runMsSqlQueryForSP($sql, $params1);
                            $qResult = $rcount['result'];
                            $count = $rcount['count'];
                            //if($count>1)
                            echo "<option value=\"\">All</option>";

// $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {                              
                                    echo '<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>                      

                    <div class="divSimple">
                        <select style="width:260px;" id="cmbSchoolStatus" name="cmbSchoolStatus" onchange="loadAccordingToSCStatus();">

                            <option value="">All</option>
                            <option value="Y">Functioning</option>
                            <option value="N">Not Functioning</option>
                        </select>
                    </div>

                </div>

                <div>
                    <label for="username" class="labelTxt" style="margin-left:22px;"><strong>School :</strong></label>

                </div>

                <div>
                    <div class="divSimple" style="margin-left:22px; width:880px;">
                        <select style="width:853px;" id="cmbSchool" name="cmbSchool" onchange="disableCheckBox();">

                            <?php
//School
// $sql = "SELECT CenCode,CONCAT(CenCode,'- ',InstitutionName) AS School FROM [CD_CensesNo] WHERE (InstType = 'SC')";

                            $sql = "{call SP_TG_GetCensesFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?, ?)}";
                            // $stmt = $db->runMsSqlQuery($sql, $params4);

                            $rcount = $db->runMsSqlQueryForSP($sql, $params4);
                            $qResult = $rcount['result'];
                            $count = $rcount['count'];
                            // if($count>1)
                            echo "<option value=\"\">All</option>";


                            while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                if ($sqSchool == trim($row['CenCode']))
                                    echo '<option selected="selected" value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
                                else
                                    echo '<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
                            }
                            ?>                                
                        </select>

                    </div>

                </div>

            </div>


        </div>
        
    </div>



    

    <div class="containerHeaderTwo" style="width: 291px; margin-top: 0px;">
        
        <div class="labelTxt" style="margin-left:32px; margin-bottom: 10px;"><strong>Select a Report Type :</strong></div>

        <div style="margin-left:42px;">
            <?php
            $sql = "SELECT
TG_QuickReport.ID,
TG_QuickReport.ReportName
FROM
TG_QuickReport
WHERE
TG_QuickReport.Enable = 'Y'";
            $stmt = $db->runMsSqlQuery($sql);
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

                echo '<input type="radio" name="reportT" id="reportT" onclick="loadReportFilter();" value=' . $row['ID'] . '>' . $row['ReportName'] . '<br>';
            }
            ?>

        </div>
       
      
        <input name="FrmSubmit" type="submit" id="FrmSubmit" class="report" style="margin-right: -593px;" value="Proceed" />
    </div>
    
    
    
    <div style="margin-top: 10px;">
        <div id="fillter"></div>
    </div>
    
     
    
    
    
<!--    <div class="containerHeaderTwo" style="width: 1200px; margin-top: 0px;">
        
        
    </div>-->
</form>





