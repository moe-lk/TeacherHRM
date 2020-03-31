<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<style>




/* Create two equal columns that floats next to each other */
.column {
    float: left;
    width: 50%;
    height: 300px;
}

.column2 {
    float: right;
    width: 50%;
    height: 300px;
}


.selectRpt{
	width:250px;
	min-width:150px;
	padding:2px;
	font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size:12px;
	line-height:24px;
	color:#666666;
	border-radius: 2px;
	border:1px solid #CCC;
	}


</style>
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


            <div class="row">
                <div class="column" >
                    <div style="padding-top: 10px; width: 100%;">
                        <div style="width: 20%;float:  left;margin-top: 2px;">School Type :</div>
                        <div>
                            <select class="selectRpt" onchange="loadAccordingToSCType();" id="cmbSchoolType" name="cmbSchoolType">

                            <option value="">All</option>
                            <?php
//School Type

                            $sql = "SELECT ID,Category FROM [CD_CensesCategory]";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                    echo '<option value=' . $row['ID'] . '>' . $row['Category'] . '</option>';
                            }
                            ?>
                        </select></div>
                    </div>

                    <div style="padding-top: 10px; width: 100%;">
                        <div style="width: 20%;float:  left;margin-top: 2px;">Province :</div>
                        <div style="width: 100%; padding-left: 10px;">
                            <select class="selectRpt" id="cmbProvince" name="cmbProvince" onchange="loadAccordingToProvince();">

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
                    </div>

                    <div style="padding-top: 10px; width: 100%;">
                        <div style="width: 20%;float:  left;margin-top: 2px;">District :</div>
                        <div style="width: 100%; padding-left: 10px;">
                            <select class="selectRpt" id="cmbDistrict" name="cmbDistrict" onchange="loadAccordingToDistrict();">

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

                    <div style="padding-top: 10px; width: 100%;">
                        <div style="width: 20%;float:  left;margin-top: 2px;">Zone :</div>
                        <div style="width: 100%; padding-left: 10px;">
                            <select class="selectRpt" id="cmbZone" name="cmbZone" onchange="loadAccordingToZone();">

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
                    </div>

                    <div style="padding-top: 10px; width: 100%;">
                        <div style="width: 20%;float:  left;margin-top: 2px;">Division :</div>
                        <div style="width: 100%; padding-left: 10px;">
                            <select class="selectRpt" id="cmbDivision" name="cmbDivision" onchange="loadAccordingToDivision();">

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
                    </div>

                    <div style="padding-top: 10px; width: 100%;">
                        <div style="width: 20%;float:  left;margin-top: 2px;">School Status</div>
                        <div style="width: 100%; padding-left: 10px;">
                            <select class="selectRpt" id="cmbSchoolStatus" name="cmbSchoolStatus" onchange="loadAccordingToSCStatus();">

                            <option value="">All</option>
                            <option value="Y">Functioning</option>
                            <option value="N">Not Functioning</option>
                            </select>
                        </div>
                    </div>


                    <div style="padding-top: 10px; width: 100%;">
                        <div style="width: 20%;float:  left;margin-top: 2px;">School :</div>
                        <div style="width: 100%; padding-left: 10px;">
                            <select class="selectRpt" id="cmbSchool" name="cmbSchool" onchange="disableCheckBox();">

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
                <div class="column2" >
                  <div style="padding-top: 10px; width: 100%;">
                      <div style=""><strong>Report Type :</strong></div>
                      <br>
                        <div style="width: 100%; padding-left: 10px;">
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
                echo '<div style="padding-bottom:10px;">';
                echo '<input type="radio" name="reportT" id="reportT"  value=' . $row['ID'] . '>' . $row['ReportName'] . '<br>';
                echo "</div>";
            }
            ?>
                        </div>
                    </div>
                </div>
            </div>





        </div>

    </div>





    <div class="containerHeaderTwo" style="width: 291px; margin-top: 0px;">


        <input name="FrmSubmit" type="submit" id="FrmSubmit" class="report" style="margin-right: -593px;" value="Proceed" />
    </div>


</form>
