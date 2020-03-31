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
    height: 400px;
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

// var_dump($accessRoleType);
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

$params3 = array(
    array($LOGGEDUSERID, SQLSRV_PARAM_IN),
    array($ACCESSLEVEL, SQLSRV_PARAM_IN),
    array($ProCode, SQLSRV_PARAM_IN)
);

$params4 = array(
    array($LOGGEDUSERID, SQLSRV_PARAM_IN),
    array($ACCESSLEVEL, SQLSRV_PARAM_IN),
    array($SCType, SQLSRV_PARAM_IN),
    array($ProCode, SQLSRV_PARAM_IN),
    array($District, SQLSRV_PARAM_IN),
    array($ZONECODE, SQLSRV_PARAM_IN),
    array($Division, SQLSRV_PARAM_IN)
);
// end login manageing

//  foreach ($params4 as $name => $locations) {
//  foreach ($locations as $location) {
//  echo "ArrName {$name} and title {$location}<br />";
//  }
//  }
 

//var_dump($params);
?>
<form id="formReport" name="formReport" action="" method="post" enctype="multipart/form-data" onSubmit="return submitForm(formReport);">
    <div class="main_content_inner_block">
        <div id="geographical" class="contenttab">
            <div class="row">
                <div class="column" >
                    

                    <div style="padding-top: 10px; width: 100%;">
                        <div style="width: 20%;float:  left;margin-top: 2px;">Province :</div>
                        <div style="width: 100%; padding-left: 10px;">
                        <select class="selectRpt" id="cmbProvince" name="cmbProvince" onchange="Javascript:show_district('districtList', this.options[this.selectedIndex].value, '');">

<?php
//Province
$sql = "{call SP_GetProvinceFor_LoggedUser( ?, ?, ? )}";
//$stmt = $db->runMsSqlQuery($sql, $params);

$rcount = $db->runMsSqlQueryForSP($sql, $params);
$qResult = $rcount['result'];
$count = $rcount['count'];
if($count>1)
    echo "<option value=\"\">All</option>";
//SELECT @@ROWCOUNT;
while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
    if ($sqProvince == trim($row['PROCODE']))
        echo '<option selected="selected" value=' . $row['PROCODE'] . '>' . $row['Province'] . '</option>';
    else
        echo '<option value=' . $row['PROCODE'] . '>' . $row['Province'] . '</option>';
}
?>
</select>
                        </div>
                    </div>

                    <div style="padding-top: 10px; width: 100%;">
                        <div style="width: 20%;float:  left;margin-top: 2px;">District :</div>
                        <div style="width: 100%; padding-left: 10px;" id="divdistrict">
                        <select class="selectRpt" id="cmbDistrict" name="cmbDistrict" onchange="Javascript:show_zone('zonelist', this.options[this.selectedIndex].value, '');">

<?php
//District
$sql = "{call SP_TG_GetDistrictFor_LoggedUser( ?, ?, ?)}"; // removed this part
$rcount = $db->runMsSqlQueryForSP($sql, $params);
$qResult = $rcount['result'];
$count = $rcount['count'];
if($count>1)
echo "<option value=\"\">All</option>";

while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
    if ($sqDistrict == trim($row['DistCode']))
        echo '<option selected="selected" value=' . $row['DistCode'] . '>' . $row['DistName'] . '</option>';
    else
        echo '<option value=' . $row['DistCode'] . '>' . $row['DistName'] . '</option>';
}
?>
</select>
                        </div>
                    </div>
                    <div style="padding-top: 10px; width: 100%;">
                        <div style="width: 20%;float:  left;margin-top: 2px;">Zone :</div>
                        <div style="width: 100%; padding-left: 10px;" id="divzone">
                            <select class="selectRpt" id="cmbZone" name="cmbZone" onchange="Javascript:show_division('divisionList', this.options[this.selectedIndex].value, '');">

                            <?php
//Zone
//$sql = "SELECT CenCode,CONCAT(CenCode,'- ',InstitutionName) AS Zone FROM [CD_CensesNo] WHERE (InstType = 'ZN')";
                                $sql = "{call SP_TG_GetZonesFor_LooggedUser( ?, ?, ? ,?, ?)}";

                                $rcount = $db->runMsSqlQueryForSP($sql, $params1);
                                $qResult = $rcount['result'];
                                $count = $rcount['count'];
                                if($count>1)
                                echo "<option value=\"\">All</option>";
                                                                        
                                                                        
                                while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                if ($sqZone == trim($row['CenCode']))
                                    echo '<option selected="selected" value=' . $row['CenCode'] . '>' . $row['Zone'] . '</option>';
                                else
                                    echo '<option value=' . $row['CenCode'] . '>' . $row['Zone'] . '</option>';
                                }
                         ?>
                        </select>
                        </div>
                    </div>

                    <div style="padding-top: 10px; width: 100%;">
                        <div style="width: 20%;float:  left;margin-top: 2px;">Division :</div>
                        <div style="width: 100%; padding-left: 10px;" id="divdivision">
                            <select class="selectRpt" id="cmbDivision" name="cmbDivision" onchange="Javascript:show_cences('censesList', this.options[this.selectedIndex].value, '');">
                            <?php 
//Division
//$sql = "SELECT CenCode,CONCAT(CenCode,'- ',InstitutionName) AS Division FROM [CD_CensesNo] WHERE (InstType = 'ED')";
                            $sql = "{call SP_TG_GetDivisionFor_LooggedUser( ?, ?, ? , ?, ?)}";
                            $rcount = $db->runMsSqlQueryForSP($sql, $params1);
                            $qResult = $rcount['result'];
                            $count = $rcount['count'];

                            echo "<option value=\"\">All</option>";
                                                                        
                            while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                            if ($sqDivision == trim($row['CenCode']))
                                echo '<option selected="selected" value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
                            else
                                echo '<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
                            }
                            ?>
                            </select>
                        </div>
                    </div>
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
                                                                            if ($sqScType == $row['ID'])
                                                                                echo '<option selected="selected" value=' . $row['ID'] . '>' . $row['Category'] . '</option>';
                                                                            else
                                                                                echo '<option value=' . $row['ID'] . '>' . $row['Category'] . '</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                        </select></div>
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
                        <div style="width: 100%; padding-left: 10px;" id ="divschool">
                            <select class="selectRpt" id="cmbSchool" name="cmbSchool">

                            <?php
//School
                                                                        if($AccessRoleType == 'ZN'){
                                                                            $sql = "SELECT * FROM CD_CensesNo WHERE (CD_CensesNo.DivisionCode = N'$CenCodex')";
                                                                        }
                                                                        else{
                                                                            $sql = "{call SP_TG_GetCensesFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?, ?)}";
                                                                        }
                                                                        
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
<script src="selectpage.js"  language="javascript"></script>
<script>


</script>