<?php
//echo md5('HOsd@0117213133');
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
//include 'sidemenu/dynamic_menu.php';
$db = new DBManager();

$NICUser = $_SESSION["NIC"];
$accLevel = $_SESSION["accLevel"];
$loggedPositionName = $_SESSION['loggedPositionName'];
$accessRoleType = $_SESSION['AccessRoleType'];
$ProCode = $_SESSION['ProCodeU'];
$District = $_SESSION['DistCodeU'];
$ZONECODE = $_SESSION['ZoneCodeU'];

if ($_SESSION['NIC'] == '') {
    header("Location: index.php");
    exit();
}

if ($_SESSION['timeout'] + 30 * 60 < time()) {
    session_unset();
    session_destroy();
    session_start();
    header("Location: index.php");
    exit();
}
$timeTableShow = "N";


// *** check loging user is principal and allowa time table
/*
  $checkPrinciple = "SELECT
  TeacherMast.NIC,
  StaffServiceHistory.PositionCode,
  StaffServiceHistory.InstCode,
  TeacherMast.CurServiceRef,
  TeacherMast.SurnameWithInitials,
  Passwords.AccessRole
  FROM
  TeacherMast
  INNER JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID
  INNER JOIN Passwords ON TeacherMast.NIC = Passwords.NICNo
  WHERE
  TeacherMast.NIC = '$NICUser'
  AND Passwords.AccessLevel = '3000'";

  $isAvailablePrinc = $db->rowCount($checkPrinciple);
  if ($isAvailablePrinc == 1) {
  $timeTableShow = "Y";
  }
 * 
 */
// ***


$checkAccessRol = "SELECT
	TeacherMast.NIC,
	StaffServiceHistory.PositionCode,
	StaffServiceHistory.InstCode,
	TeacherMast.CurServiceRef,
	TeacherMast.SurnameWithInitials,
	Passwords.AccessRole,
	Passwords.AccessLevel
FROM
	TeacherMast
INNER JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID
INNER JOIN Passwords ON TeacherMast.NIC = Passwords.NICNo
WHERE
	TeacherMast.NIC = '$NICUser'";
$stmt = $db->runMsSqlQuery($checkAccessRol);
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $loggedSchoolID = trim($row['InstCode']);
}


$schoolType = "Select IsNationalSchool from CD_CensesNo where CenCode='$loggedSchoolID'";
$stmtTyp = $db->runMsSqlQuery($schoolType);
while ($row = sqlsrv_fetch_array($stmtTyp, SQLSRV_FETCH_ASSOC)) {
    $IsNationalSchool = trim($row['IsNationalSchool']);
}

if ($IsNationalSchool == 1) {
    $_SESSION['schoolType'] = "N"; //National
} else {
    $_SESSION['schoolType'] = "P"; //Province
}

$sqlLevel = "SELECT [NICNo]
      ,[CurPassword]
      ,[LastUpdate]
      ,[AccessRole]
      ,[AccessLevel]
  FROM [dbo].[Passwords]
  where NICNo='$NICUser'";
$stmtTypL = $db->runMsSqlQuery($sqlLevel);
$rowL = sqlsrv_fetch_array($stmtTypL, SQLSRV_FETCH_ASSOC);
$loggedAccessLevel = trim($rowL['AccessLevel']);

$_SESSION['loggedSchool'] = $loggedSchoolID;
$loggedPositionName = $_SESSION['loggedPositionName'];


$menuIDs = explode("_", $menu);

if ($pageid == '0') {
    $activeMainMenuNo = "1";
    $activeSubMenu = "2";
} else {
    $activeMainMenuNo = $menuIDs[0];
    $activeSubMenu = $menuIDs[1];
}

$theam = "theam1";
$theamMenuFontColor;
if ($theam == "theam1") {
    $theamMenuFontColor = "#0888e2";
    $theamMenuButtonColor = "#3973b1";
}
if ($theam == "theam2") {
    $theamMenuFontColor = "#d98813";
    $theamMenuButtonColor = "#3a2a07";
}
if ($theam == "theam3") {
    $theamMenuFontColor = "#c2379b";
    $theamMenuButtonColor = "#8839b1";
}
$theamPath = "../cms/images/";


//echo $loggedAccessLevel;
//$retirementShow = "Y";

/*
  $sqlPrivi = "SELECT * FROM TG_Privilages where AccessRoleValue='$loggedAccessLevel'";
  $stmtPrivi = $db->runMsSqlQuery($sqlPrivi);
  while ($rowPrivi = sqlsrv_fetch_array($stmtPrivi, SQLSRV_FETCH_ASSOC)) {
  $PrivilageModuleArr[] = $rowPrivi['PrivilageModule'];
  }
 * 
 */
//print_r($PrivilageModuleArr);
//if(in_array("P5",$PrivilageModuleArr))
?>




<!DOCTYPE html>
<html>
    <head>

        <link rel="icon" 
              type="image/png" 
              href="images/favicon.png">


        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <!-- <title>National Education Management Information System | Ministry of Education Sri Lanka</title>  -->
        <title>National Education Management Information System | Ministry of Education Sri Lanka</title> 
        <!--<link href="css/emis.css" rel="stylesheet" type="text/css">-->
        <link href="css/emis.css" rel="stylesheet" type="text/css">
        <link href="../css/mStyle.css" rel="stylesheet" type="text/css" />
        <link href="css/category_tab.css" rel="stylesheet" type="text/css" />
        <link href="../cms/css/main_menu1.css" rel="stylesheet" type="text/css" />
        <link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
        <link href="../cms/css/grid_style.css" rel="stylesheet" type="text/css" />
        <link href="../cms/css/flexigrid.css" rel="stylesheet" type="text/css"/>

        <style type="text/css">

            /*menu style*/

            .mcib_top{
                 width:960px;
                height:33px;
                float:left;
                padding:1px 10px 1px 10px;
                font-size:12px;
                color:#FFF;
                font-weight:bold;
                line-height:34px;
                background:url(<?php echo $theamPath ?><?php echo $theam ?>/backgrounds/block_inner_back.png);
            }
            .link1{
                color:<?php echo $theamMenuFontColor ?>;
                cursor: pointer;
            }


        </style>


        <script src="js/jquery-1.9.1.js"></script>
        <script src="js/jquery.tabify.js" type="text/javascript" charset="utf-8"></script>
        <script src="js/teacherFilter.js" type="text/javascript"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#menu').tabify();
                $('#menu2').tabify();
            });
        </script>
        <script type="text/javascript">
            // IE9 fix
            if (!window.console) {
                var console = {
                    log: function() {
                    },
                    warn: function() {
                    },
                    error: function() {
                    },
                    time: function() {
                    },
                    timeEnd: function() {
                    }
                }
            }
        </script>
        <!-- <script src = "selectpage.js"></script> -->
    </head>
    <body>
        <!-- Begin Page Content -->
        <form id="form1" name="form1" action="" method="POST">
            <div class="container">

                <div id="header_outer" style="background:url(<?php echo $theamPath ?><?php echo $theam ?>/backgrounds/menu_back.gif) repeat-x">
                    <div id="header_inner">
                        <div id="header_top">
                            <div class="header_top_left">&nbsp;&nbsp; </div>
                            <div class="header_top_right">
                                <div id="admin_button" style="cursor:default;"><a href="#" id="admin_link" style="cursor:default;"><span style="cursor:default;"><?php echo $loggedPositionName; ?></span></a></div>
                                <!-- <div id="mail_button"><a href="#"></a><div id="mail_alert" class="alert">05</div></div>-->
                                <div id="user_welcome">Welcome <?php echo $_SESSION["fullName"]; ?>, &nbsp;&nbsp; <span class="link1" onClick="logoutForm('mail');">Logout?</span></div>
                            </div>
                        </div>
                        <div id="header_logo" style="margin-top:0px;"><img src="../images/header.png" width="960" height="150" /></div>

                        <div style="clear:both"></div>
                    </div>
                </div>

        <!--header end-->
        <div id="main_content_outer">
                    <div id="main_content_inner">

                        <div class="main_content_inner_block">
                            <?php include('../mainmenu.php') ?>
                            <div class="mcib_middle">

                                <div class="containerHeaderOne">

                                    <div class="midArea"> <!-- midArea -->
                                        <!-- productsAreaLeft -->
                                        <div class="productsAreaRight">
                                            <ul id="menu" class="menu">
                                                <li class="active"><a href="#schools">School Details</a></li>
                                                <li><a href="#teacher">Teacher Details</a></li>
                                                <li><a href="#teacherAssis">Teacher Assistant</a></li>   
                                                <!-- <li><a href="#teaching">Teaching</a></li>
                                                <li><a href="#service">Service</a></li>
                                                <li><a href="#outOfService">Out of Service</a></li>
                                                <li><a href="#columns">Columns</a></li>
                                                <li><a href="#querySave">Saved Query</a></li> -->
                                            </ul>
                                        <div>
                    <?PHP
                        $sqlUsr = "SELECT Passwords.NICNo, Passwords.AccessLevel, TeacherMast.emailaddr, TeacherMast.Title, CD_Title.TitleName + TeacherMast.SurnameWithInitials AS name, CD_AccessRoles.AccessRoleType 
                                   FROM Passwords 
                                   INNER JOIN TeacherMast ON Passwords.NICNo = TeacherMast.NIC 
                                   INNER JOIN CD_Title ON TeacherMast.Title = CD_Title.TitleCode 
                                   INNER JOIN CD_AccessRoles ON Passwords.AccessLevel = CD_AccessRoles.AccessRoleValue 
                                   WHERE (Passwords.NICNo = N'$NICUser')";
                        $stmt1 = $db->runMsSqlQuery($sql);

                        while ($row = sqlsrv_fetch_array($stmt1)) {
                            $_SESSION["AccessLevel"] = $row["AccessLevel"];
                            $ACCESSLEVEL = $row["AccessLevel"];
                            $emailaddr = $row["emailaddr"];
                            $_SESSION["fullName"] = $row["name"];
                            $accessRoleType = trim($row["AccessRoleType"]);
                        }
                        $DS03 = 'DS03'; 
                        $RN01 = 'RN01';
                        $RT01 = 'RT01';
                        $DS01 = 'DS01';
                        $SP44 = 'SP44';
                        ?>
                <!-- if($accessRoleType == "PD" || $accessRoleType == "NC" || $accessRoleType == "MO" || $accessRoleType == "ZN"){
                echo " -->
              
                </div>
                <div class="main_content_inner_block" style="padding:20px;">
                <h1>DASHBOARD</h1>
                <div id="schools">
                    <?php
                    // var_dump($rowPD);
                    if ($accessRoleType == "NC" || $accessRoleType == "MO") {
                        $SCType = null;
                        $ProCode = null;
                        $District = null;
                        $ZONECODE = null;
                        $Division = null;
                        echo "<h2>Total Number of Schools</h2>";
                        }
                    // var_dump($_SESSION['AccessRoleType']);
                    if ($accessRoleType == "PD"){
                        $sqlPD = "SELECT TeacherMast.NIC, StaffServiceHistory.InstCode, CD_CensesNo.InstitutionName, CD_CensesNo.DivisionCode, CD_CensesNo.ZoneCode, CD_CensesNo.DistrictCode, CD_Provinces.ProCode, CD_Provinces.Province 
                                      FROM TeacherMast
                                      INNER JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID
                                      INNER JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
                                      INNER JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
                                      INNER JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
                                      WHERE (TeacherMast.NIC = N'$NICUser')"; 
                            $stmtPD = $db->runMsSqlQuery($sqlPD);
                            while ($rowPD = sqlsrv_fetch_array($stmtPD)) {
                                $ProCode = trim($rowPD["ProCode"]);
                                $District = trim($rowPD["DistrictCode"]);
                                $District = null;
                                $ZONECODE = null;
                                $Division = null;
                                $SCType = null;
                                echo "<h2>Total Number of Schools in ".$rowPD['Province']." Province</h2>";
                                }  
                    }
                    if ($accessRoleType == "ZN") {
                        $sqlPD = "SELECT TeacherMast.NIC, StaffServiceHistory.InstCode, CD_CensesNo.InstitutionName, CD_CensesNo.DivisionCode, CD_CensesNo.ZoneCode, CD_CensesNo.DistrictCode, CD_Provinces.ProCode, CD_Zone.InstitutionName AS Zone
                        FROM TeacherMast
                        INNER JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID
                        INNER JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
                        INNER JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
                        INNER JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
                        INNER JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
                        WHERE (TeacherMast.NIC = N'$NICUser')";
                        $stmtPD = $db->runMsSqlQuery($sqlPD);
                        while ($rowPD = sqlsrv_fetch_array($stmtPD)) {
                            $ProCode = trim($rowPD["ProCode"]);
                            $District = trim($rowPD["DistrictCode"]);
                            $ZONECODE = trim($rowPD["InstCode"]);       
                            $Division = null;
                            $SCType = null;
                            echo "<h2>Total Number of Schools in ".$rowPD['Zone']." Zone</h2>";
                            }
                    }

                    ?>
<style>
#customers {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 80%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding-left: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 5px;
  padding-bottom: 5px;
  text-align: left;
  background-color: #92495C;
  color: white;
}
</style>
                    <table id="customers">
                    <tr>
                        <th><h3>School Type</h3></th>
                        <th><h3>Number</h3></th>
                    </tr>
                    <tr>
                        <td>
                            <h3>
                                National Schools: 
                            </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    $sql = "SELECT DISTINCT COUNT(CenCode) AS SCHL FROM CD_CensesNo
                                    INNER JOIN CD_Districts ON DistrictCode = DistCode
							        INNER JOIN CD_Provinces ON CD_Provinces.ProCode = CD_Districts.ProCode
                                    WHERE InstType IS NOT NULL AND SchoolStatus = 'Y' 
                                    AND SchoolType = '1'";
                                    if($accessRoleType == "PD"){
                                        $sql .= " AND CD_Provinces.ProCode = '$ProCode'";
                                    }
                                    if($accessRoleType == "ZN"){
                                        $sql .= " AND CD_CensesNo.ZoneCode = '$ZONECODE'";
                                    }
                                    $rcount = $db->runMsSqlQueryForSP($sql, $params);
                                    $qResult = $rcount['result'];
                                    $count = $rcount['count'];
                                    while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                        echo $row['SCHL'];
                                        global $NS;
                                        $NS = $row['SCHL'];
                                    }
                                ?>
                                

                            </h3>
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            <h3>
                                Provincial Schools: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    $sql = "SELECT DISTINCT COUNT(CenCode) AS SCHL FROM CD_CensesNo
                                    INNER JOIN CD_Districts ON DistrictCode = DistCode
							        INNER JOIN CD_Provinces ON CD_Provinces.ProCode = CD_Districts.ProCode
                                    WHERE InstType IS NOT NULL AND SchoolStatus = 'Y' 
                                    AND SchoolType = '3'";
                                    if($accessRoleType == "PD"){
                                        $sql .= " AND CD_Provinces.ProCode = '$ProCode'";
                                    }
                                    if($accessRoleType == "ZN"){
                                        $sql .= " AND CD_CensesNo.ZoneCode = '$ZONECODE'";
                                    }
                                    $rcount = $db->runMsSqlQueryForSP($sql, $params);
                                    $qResult = $rcount['result'];
                                    $count = $rcount['count'];
                                    while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                        echo $row['SCHL'];
                                        global $PS;
                                        $PS = $row['SCHL'];

                                    }
                                ?>
                            </h3>
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            <h3 style="text-align: right; color: #000000;"><strong>Government Schools: </strong></h3>
                        </td>
                        <td>
                        <h3 style="text-align: right; color: #000000;"><strong><?php echo $NS + $PS ?></strong></h3>     
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h3>
                                Private Aided Schools: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    $sql = "SELECT DISTINCT COUNT(CenCode) AS SCHL FROM CD_CensesNo
                                    INNER JOIN CD_Districts ON DistrictCode = DistCode
							        INNER JOIN CD_Provinces ON CD_Provinces.ProCode = CD_Districts.ProCode
                                    WHERE InstType IS NOT NULL AND SchoolStatus = 'Y' 
                                    AND SchoolType = '4'";
                                    if($accessRoleType == "PD"){
                                        $sql .= " AND CD_Provinces.ProCode = '$ProCode'";
                                    }
                                    if($accessRoleType == "ZN"){
                                        $sql .= " AND CD_CensesNo.ZoneCode = '$ZONECODE'";
                                    }
                                    $rcount = $db->runMsSqlQueryForSP($sql, $params);
                                    $qResult = $rcount['result'];
                                    $count = $rcount['count'];
                                    while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                         echo $row['SCHL'];
                                         global $PAS;
                                        $PAS = $row['SCHL'];
                                    }
                                ?>
                            </h3>
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            <h3>
                                Private Un-Aided Schools: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    $sql = "SELECT DISTINCT COUNT(CenCode) AS SCHL FROM CD_CensesNo
                                    INNER JOIN CD_Districts ON DistrictCode = DistCode
							        INNER JOIN CD_Provinces ON CD_Provinces.ProCode = CD_Districts.ProCode
                                    WHERE InstType IS NOT NULL AND SchoolStatus = 'Y' 
                                    AND SchoolType = '5'";
                                    if($accessRoleType == "PD"){
                                        $sql .= " AND CD_Provinces.ProCode = '$ProCode'";
                                    }
                                    if($accessRoleType == "ZN"){
                                        $sql .= " AND CD_CensesNo.ZoneCode = '$ZONECODE'";
                                    }
                                    $rcount = $db->runMsSqlQueryForSP($sql, $params);
                                    $qResult = $rcount['result'];
                                    $count = $rcount['count'];
                                    while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                         echo $row['SCHL'];
                                         global $PUAS;
                                        $PUAS = $row['SCHL'];
                                    }
                                ?>
                            </h3>
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            <h3>
                                Pirivena: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    $sql = "SELECT DISTINCT COUNT(CenCode) AS SCHL FROM CD_CensesNo
                                    INNER JOIN CD_Districts ON DistrictCode = DistCode
							        INNER JOIN CD_Provinces ON CD_Provinces.ProCode = CD_Districts.ProCode
                                    WHERE InstType IS NOT NULL AND SchoolStatus = 'Y' 
                                    AND SchoolType = '6'";
                                    if($accessRoleType == "PD"){
                                        $sql .= " AND CD_Provinces.ProCode = '$ProCode'";
                                    }
                                    if($accessRoleType == "ZN"){
                                        $sql .= " AND CD_CensesNo.ZoneCode = '$ZONECODE'";
                                    }
                                    $rcount = $db->runMsSqlQueryForSP($sql, $params);
                                    $qResult = $rcount['result'];
                                    $count = $rcount['count'];
                                    while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                         echo $row['SCHL'];
                                         global $PIRS;
                                        $PIRS = $row['SCHL'];
                                    }
                                ?>
                            </h3>
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            <h3>
                                Meheni Institutions: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    $sql = "SELECT DISTINCT COUNT(CenCode) AS SCHL FROM CD_CensesNo
                                    INNER JOIN CD_Districts ON DistrictCode = DistCode
							        INNER JOIN CD_Provinces ON CD_Provinces.ProCode = CD_Districts.ProCode
                                    WHERE InstType IS NOT NULL AND SchoolStatus = 'Y' 
                                    AND SchoolType = '2'";
                                    if($accessRoleType == "PD"){
                                        $sql .= " AND CD_Provinces.ProCode = '$ProCode'";
                                    }
                                    if($accessRoleType == "ZN"){
                                        $sql .= " AND CD_CensesNo.ZoneCode = '$ZONECODE'";
                                    }
                                    $rcount = $db->runMsSqlQueryForSP($sql, $params);
                                    $qResult = $rcount['result'];
                                    $count = $rcount['count'];
                                    while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                         echo $row['SCHL'];
                                         global $MIS;
                                        $MIS = $row['SCHL'];
                                    }
                                ?>
                            </h3>
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            <h3>
                                Special Education Schools: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    $sql = "SELECT DISTINCT COUNT(CenCode) AS SCHL FROM CD_CensesNo
                                    INNER JOIN CD_Districts ON DistrictCode = DistCode
							        INNER JOIN CD_Provinces ON CD_Provinces.ProCode = CD_Districts.ProCode
                                    WHERE InstType IS NOT NULL AND SchoolStatus = 'Y' 
                                    AND SchoolType = '7'";
                                    if($accessRoleType == "PD"){
                                        $sql .= " AND CD_Provinces.ProCode = '$ProCode'";
                                    }
                                    if($accessRoleType == "ZN"){
                                        $sql .= " AND CD_CensesNo.ZoneCode = '$ZONECODE'";
                                    }
                                    $rcount = $db->runMsSqlQueryForSP($sql, $params);
                                    $qResult = $rcount['result'];
                                    $count = $rcount['count'];
                                    while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                         echo $row['SCHL'];
                                         global $SES;
                                        $SES = $row['SCHL'];
                                    }
                                ?>
                            </h3>
                        </td>
                        
                    </tr>
                    <tr>
                        <td>
                            <h3>
                                Education Institution: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    $sql = "SELECT DISTINCT COUNT(CenCode) AS SCHL FROM CD_CensesNo
                                    INNER JOIN CD_Districts ON DistrictCode = DistCode
							        INNER JOIN CD_Provinces ON CD_Provinces.ProCode = CD_Districts.ProCode
                                    WHERE InstType IS NOT NULL AND SchoolStatus = 'Y' 
                                    AND SchoolType = '8'";
                                    if($accessRoleType == "PD"){
                                        $sql .= " AND CD_Provinces.ProCode = '$ProCode'";
                                    }
                                    if($accessRoleType == "ZN"){
                                        $sql .= " AND CD_CensesNo.ZoneCode = '$ZONECODE'";
                                    }
                                    $rcount = $db->runMsSqlQueryForSP($sql, $params);
                                    $qResult = $rcount['result'];
                                    $count = $rcount['count'];
                                    while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                         echo $row['SCHL'];
                                         global $EIS;
                                        $EIS = $row['SCHL'];
                                    }
                                ?>
                            </h3>
                        </td>
                    </tr>
                    <tr>
                    <td><h3 style="text-align: right; color: #000000;">Total Schools/ Institutions: </h3></td>
                    <td><h3 style="text-align: right; color: #000000;"><?php echo  $NS+$PS+$PAS+$PUAS+$PIRS+$MIS+$SES+$EIS?></h3></td>
                    </tr>
                    <tr>
                        <td>
                            <h3>Data Source: Teacher Human Resource Management Portal - NEMIS</h3>
                        </td>
                        <td>
                            <h3>Date: <?php echo date("d/m/Y") ?></h3>
                        </td>
                    </tr>
                </table>
        </div>        
<!-- SP_TG_GetDashboaerdFor_LooggedUser '800093325v', NULL, NULL, NULL, NULL, '1', 'DS03', 'RN01', 'RT01', 'DS01' -->
<div id="teacher">

                    <?php
                    // var_dump($rowPD);
                    if ($accessRoleType == "NC" || $accessRoleType == "MO") {
                        $SCType = null;
                        $ProCode = null;
                        $District = null;
                        $ZONECODE = null;
                        $Division = null;

                        echo "<h2>Total Number of Teachers</h2>";
                    }
                    // var_dump($_SESSION['AccessRoleType']);
                    if ($accessRoleType == "PD"){
                        $sqlPD = "SELECT TeacherMast.NIC, StaffServiceHistory.InstCode, CD_CensesNo.InstitutionName, CD_CensesNo.DivisionCode, CD_CensesNo.ZoneCode, CD_CensesNo.DistrictCode, CD_Provinces.ProCode, CD_Provinces.Province 
                                      FROM TeacherMast
                                      INNER JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID
                                      INNER JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
                                      INNER JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
                                      INNER JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
                                      WHERE (TeacherMast.NIC = N'$NICUser')"; 
                            $stmtPD = $db->runMsSqlQuery($sqlPD);
                            while ($rowPD = sqlsrv_fetch_array($stmtPD)) {
                                $ProCode = trim($rowPD["ProCode"]);
                                $District = trim($rowPD["DistrictCode"]);
                                $District = null;
                                $ZONECODE = null;
                                $Division = null;
                                $SCType = null;
                                
                                echo "<h2>Total Number of Teachers in ".$rowPD['Province']." Province</h2>";
                            }  
                    }
                    if ($accessRoleType == "ZN") {
                        $sqlPD = "SELECT TeacherMast.NIC, StaffServiceHistory.InstCode, CD_CensesNo.InstitutionName, CD_CensesNo.DivisionCode, CD_CensesNo.ZoneCode, CD_CensesNo.DistrictCode, CD_Provinces.ProCode, CD_Zone.InstitutionName AS Zone
                        FROM TeacherMast
                        INNER JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID
                        INNER JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
                        INNER JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
                        INNER JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
                        INNER JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
                        WHERE (TeacherMast.NIC = N'$NICUser')";
                        $stmtPD = $db->runMsSqlQuery($sqlPD);
                        while ($rowPD = sqlsrv_fetch_array($stmtPD)) {
                            $ProCode = trim($rowPD["ProCode"]);
                            $District = trim($rowPD["DistrictCode"]);
                            $ZONECODE = trim($rowPD["InstCode"]);       
                            $Division = null;
                            $SCType = null;
                            
                            echo "<h2>Total Number of Teachers in ".$rowPD['Zone']." Zone</h2>";
                        }
                    }

                    ?>

                    <?php
                        $Sctype = '1';

                        $params2 = array(
                                array($NICUser, SQLSRV_PARAM_IN),
                                array($AccLevel, SQLSRV_PARAM_IN),
                                array($ProCode, SQLSRV_PARAM_IN),
                                array($District, SQLSRV_PARAM_IN),
                                array($ZONECODE, SQLSRV_PARAM_IN),
                                array($Sctype, SQLSRV_PARAM_IN),
                                array($DS03, SQLSRV_PARAM_IN),
                                array($RN01, SQLSRV_PARAM_IN),
                                array($RT01, SQLSRV_PARAM_IN),
                                array($DS01, SQLSRV_PARAM_IN),
                                array($SP44, SQLSRV_PARAM_IN)                    
                            );

                        // $params2 = array($NICUser,$AccLevel,$ProCode,$District,$ZONECODE,$Sctype);
                        $sql = "{call SP_TG_GetDashboaerdFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)}";
                        $rcount = $db->runMsSqlQueryForSP($sql, $params2);
                        $qResult = $rcount['result'];
                        $count = $rcount['count'];
                        while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                            // echo $row['TCHR'];
                            global $NST;
                            $NST = $row['TCHR'];
                        }
                        $Sctype = '3';

                                    $params2 = array(
                                            array($NICUser, SQLSRV_PARAM_IN),
                                            array($AccLevel, SQLSRV_PARAM_IN),
                                            array($ProCode, SQLSRV_PARAM_IN),
                                            array($District, SQLSRV_PARAM_IN),
                                            array($ZONECODE, SQLSRV_PARAM_IN),
                                            array($Sctype, SQLSRV_PARAM_IN),
                                            array($DS03, SQLSRV_PARAM_IN),
                                            array($RN01, SQLSRV_PARAM_IN),
                                            array($RT01, SQLSRV_PARAM_IN),
                                            array($DS01, SQLSRV_PARAM_IN),
                                            array($SP44, SQLSRV_PARAM_IN)                    
                                        );

                                    // $params2 = array($NICUser,$AccLevel,$ProCode,$District,$ZONECODE,$Sctype);
                                    $sql = "{call SP_TG_GetDashboaerdFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)}";
                                    $rcount = $db->runMsSqlQueryForSP($sql, $params2);
                                    $qResult = $rcount['result'];
                                    $count = $rcount['count'];
                                    while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                        global $PRST;
                                        $PRST = $row['TCHR'];
                        }
                        $Sctype = '4';

                                    $params2 = array(
                                            array($NICUser, SQLSRV_PARAM_IN),
                                            array($AccLevel, SQLSRV_PARAM_IN),
                                            array($ProCode, SQLSRV_PARAM_IN),
                                            array($District, SQLSRV_PARAM_IN),
                                            array($ZONECODE, SQLSRV_PARAM_IN),
                                            array($Sctype, SQLSRV_PARAM_IN),
                                            array($DS03, SQLSRV_PARAM_IN),
                                            array($RN01, SQLSRV_PARAM_IN),
                                            array($RT01, SQLSRV_PARAM_IN),
                                            array($DS01, SQLSRV_PARAM_IN),
                                            array($SP44, SQLSRV_PARAM_IN)                    
                                        );

                                    // $params2 = array($NICUser,$AccLevel,$ProCode,$District,$ZONECODE,$Sctype);
                                    $sql = "{call SP_TG_GetDashboaerdFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)}";
                                    $rcount = $db->runMsSqlQueryForSP($sql, $params2);
                                    $qResult = $rcount['result'];
                                    $count = $rcount['count'];
                                    while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                        global $PAST;
                                        $PAST = $row['TCHR'];
                        }
                        $Sctype = '5';

                                    $params2 = array(
                                            array($NICUser, SQLSRV_PARAM_IN),
                                            array($AccLevel, SQLSRV_PARAM_IN),
                                            array($ProCode, SQLSRV_PARAM_IN),
                                            array($District, SQLSRV_PARAM_IN),
                                            array($ZONECODE, SQLSRV_PARAM_IN),
                                            array($Sctype, SQLSRV_PARAM_IN),
                                            array($DS03, SQLSRV_PARAM_IN),
                                            array($RN01, SQLSRV_PARAM_IN),
                                            array($RT01, SQLSRV_PARAM_IN),
                                            array($DS01, SQLSRV_PARAM_IN),
                                            array($SP44, SQLSRV_PARAM_IN)                    
                                        );

                                    // $params2 = array($NICUser,$AccLevel,$ProCode,$District,$ZONECODE,$Sctype);
                                    $sql = "{call SP_TG_GetDashboaerdFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)}";
                                    $rcount = $db->runMsSqlQueryForSP($sql, $params2);
                                    $qResult = $rcount['result'];
                                    $count = $rcount['count'];
                                    while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                        global $PAUST;
                                        $PAUST = $row['TCHR'];
                        }
                        $Sctype = '6';

                                    $params2 = array(
                                            array($NICUser, SQLSRV_PARAM_IN),
                                            array($AccLevel, SQLSRV_PARAM_IN),
                                            array($ProCode, SQLSRV_PARAM_IN),
                                            array($District, SQLSRV_PARAM_IN),
                                            array($ZONECODE, SQLSRV_PARAM_IN),
                                            array($Sctype, SQLSRV_PARAM_IN),
                                            array($DS03, SQLSRV_PARAM_IN),
                                            array($RN01, SQLSRV_PARAM_IN),
                                            array($RT01, SQLSRV_PARAM_IN),
                                            array($DS01, SQLSRV_PARAM_IN),
                                            array($SP44, SQLSRV_PARAM_IN)                    
                                        );

                                    // $params2 = array($NICUser,$AccLevel,$ProCode,$District,$ZONECODE,$Sctype);
                                    $sql = "{call SP_TG_GetDashboaerdFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)}";
                                    $rcount = $db->runMsSqlQueryForSP($sql, $params2);
                                    $qResult = $rcount['result'];
                                    $count = $rcount['count'];
                                    while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                        global $PIRT;
                                        $PIRT = $row['TCHR'];
                        }
                        $Sctype = '2';

                                    $params2 = array(
                                            array($NICUser, SQLSRV_PARAM_IN),
                                            array($AccLevel, SQLSRV_PARAM_IN),
                                            array($ProCode, SQLSRV_PARAM_IN),
                                            array($District, SQLSRV_PARAM_IN),
                                            array($ZONECODE, SQLSRV_PARAM_IN),
                                            array($Sctype, SQLSRV_PARAM_IN),
                                            array($DS03, SQLSRV_PARAM_IN),
                                            array($RN01, SQLSRV_PARAM_IN),
                                            array($RT01, SQLSRV_PARAM_IN),
                                            array($DS01, SQLSRV_PARAM_IN),
                                            array($SP44, SQLSRV_PARAM_IN)                    
                                        );

                                    // $params2 = array($NICUser,$AccLevel,$ProCode,$District,$ZONECODE,$Sctype);
                                    $sql = "{call SP_TG_GetDashboaerdFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)}";
                                    $rcount = $db->runMsSqlQueryForSP($sql, $params2);
                                    $qResult = $rcount['result'];
                                    $count = $rcount['count'];
                                    while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                        global $MIT;
                                        $MIT = $row['TCHR'];
                        }
                        $Sctype = '7';

                                    $params2 = array(
                                            array($NICUser, SQLSRV_PARAM_IN),
                                            array($AccLevel, SQLSRV_PARAM_IN),
                                            array($ProCode, SQLSRV_PARAM_IN),
                                            array($District, SQLSRV_PARAM_IN),
                                            array($ZONECODE, SQLSRV_PARAM_IN),
                                            array($Sctype, SQLSRV_PARAM_IN),
                                            array($DS03, SQLSRV_PARAM_IN),
                                            array($RN01, SQLSRV_PARAM_IN),
                                            array($RT01, SQLSRV_PARAM_IN),
                                            array($DS01, SQLSRV_PARAM_IN),
                                            array($SP44, SQLSRV_PARAM_IN)                    
                                        );

                                    // $params2 = array($NICUser,$AccLevel,$ProCode,$District,$ZONECODE,$Sctype);
                                    $sql = "{call SP_TG_GetDashboaerdFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)}";
                                    $rcount = $db->runMsSqlQueryForSP($sql, $params2);
                                    $qResult = $rcount['result'];
                                    $count = $rcount['count'];
                                    while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                        global $SEST;
                                        $SEST = $row['TCHR'];
                        }
                        $Sctype = '8';

                                    $params2 = array(
                                            array($NICUser, SQLSRV_PARAM_IN),
                                            array($AccLevel, SQLSRV_PARAM_IN),
                                            array($ProCode, SQLSRV_PARAM_IN),
                                            array($District, SQLSRV_PARAM_IN),
                                            array($ZONECODE, SQLSRV_PARAM_IN),
                                            array($Sctype, SQLSRV_PARAM_IN),
                                            array($DS03, SQLSRV_PARAM_IN),
                                            array($RN01, SQLSRV_PARAM_IN),
                                            array($RT01, SQLSRV_PARAM_IN),
                                            array($DS01, SQLSRV_PARAM_IN),
                                            array($SP44, SQLSRV_PARAM_IN)                    
                                        );

                                    // $params2 = array($NICUser,$AccLevel,$ProCode,$District,$ZONECODE,$Sctype);
                                    $sql = "{call SP_TG_GetDashboaerdFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)}";
                                    $rcount = $db->runMsSqlQueryForSP($sql, $params2);
                                    $qResult = $rcount['result'];
                                    $count = $rcount['count'];
                                    while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                        global $EIT;
                                        $EIT = $row['TCHR'];
                                    }


                                    $Tchrval = $NST + $PRST + $PAST + $PUAST + $PIRT + $MIT + $SEST + $EIT;
                                    ?>
                                    <script type="text/javascript">
                                        var nstp = <?php echo $NST/$Tchrval*100 ?>;
                                        var prstp = <?php echo $PRST/$Tchrval*100 ?>;
                                        var pastp = <?php echo $PAST/$Tchrval*100 ?>;
                                        var paustp = <?php echo $PUAST/$Tchrval*100 ?>;
                                        var pirtp = <?php echo $PIRT/$Tchrval*100 ?>;
                                        var mitp = <?php echo $MIT/$Tchrval*100 ?>;
                                        var sestp = <?php echo $SEST/$Tchrval*100 ?>;
                                        var eitp = <?php echo $EIT/$Tchrval*100 ?>;
                                    </script>
<table id="customers">
<tr>
    <th><h3>School Type</h3></th>
    <th><h3>Number</h3></th>
</tr>
<tr>
<td>
                            <h3>
                                National School teachers: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    echo $NST;
                                ?>
                            </h3>
                        </td>
                        </tr>
                        <tr>
                        <td>
                            <h3>
                                Provincial School teachers: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    echo $PRST;
                                ?>
                            </h3>
                        </td>
                        <tr>
                        <td>
                            <h3 style="text-align: right; color: #000000;">Government School Teachers: </h3>
                        </td>
                        <td>
                        <h3 style="text-align: right; color: #000000;"><?php echo $NST + $PRST ?></h3>     
                        </td>
                    </tr>
                        </tr>
                        <tr>
                        <td>
                            <h3>
                                Private Aided School teachers: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    echo $PAST;
                                ?>
                            </h3>
                        </td>
                        </tr>
                        <tr>
                        <td>
                            <h3>
                                Private Un-Aided School teachers: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    echo $PAUST;
                                ?>
                            </h3>
                        </td>
                        </tr>
                        <tr>
                        <td>
                            <h3>
                                Pirivena teachers: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    echo $PIRT;
                                ?>
                            </h3>
                        </td>
                        </tr>
                        <tr>
                        <td>
                            <h3>
                                Meheni Institutions teachers: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    echo $MIT;
                                ?>
                            </h3>
                        </td>
                        </tr>
                        <tr>
                        <td>
                            <h3>
                                Special Education School teachers: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    echo $SEST;
                                ?>
                            </h3>
                        </td>
                        </tr>
                        <tr>
                        <td>
                            <h3>
                                Education Institution teachers: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    echo $EIT;
                                ?>
                            </h3>
                        </td>
                        </tr>
                        <tr>
                        <td><h3 style="text-align: right; color: #000000;">Total Teachers: </h3></td>
                        <td><h3 style="text-align: right; color: #000000;"><?php echo  $NST+$PRST+$PAST+$PAUST+$PIRT+$MIT+$SEST+$EIT?></h3></td>
                        </tr>
                        <tr>
                        <td>
                            <h3>Data Source: Teacher Human Resource Management Portal - NEMIS</h3>
                        </td>
                        <td>
                            <h3>Date: <?php echo date("d/m/Y") ?></h3>
                        </td>
                    </tr>
                        </table>
</div>
<div id="teacherAssis">

                    <?php
                    // var_dump($rowPD);
                    if ($accessRoleType == "NC" || $accessRoleType == "MO") {
                        $SCType = null;
                        $ProCode = null;
                        $District = null;
                        $ZONECODE = null;
                        $Division = null;

                        echo "<h2>Total Number of Teacher Assistants</h2>";
                    }
                    // var_dump($_SESSION['AccessRoleType']);
                    if ($accessRoleType == "PD"){
                        $sqlPD = "SELECT TeacherMast.NIC, StaffServiceHistory.InstCode, CD_CensesNo.InstitutionName, CD_CensesNo.DivisionCode, CD_CensesNo.ZoneCode, CD_CensesNo.DistrictCode, CD_Provinces.ProCode, CD_Provinces.Province 
                                      FROM TeacherMast
                                      INNER JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID
                                      INNER JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
                                      INNER JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
                                      INNER JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
                                      WHERE (TeacherMast.NIC = N'$NICUser')"; 
                            $stmtPD = $db->runMsSqlQuery($sqlPD);
                            while ($rowPD = sqlsrv_fetch_array($stmtPD)) {
                                $ProCode = trim($rowPD["ProCode"]);
                                $District = trim($rowPD["DistrictCode"]);
                                $District = null;
                                $ZONECODE = null;
                                $Division = null;
                                $SCType = null;
                                
                                echo "<h2>Total Number of Teacher Assistants in ".$rowPD['Province']." Province</h2>";
                            }  
                    }
                    if ($accessRoleType == "ZN") {
                        $sqlPD = "SELECT TeacherMast.NIC, StaffServiceHistory.InstCode, CD_CensesNo.InstitutionName, CD_CensesNo.DivisionCode, CD_CensesNo.ZoneCode, CD_CensesNo.DistrictCode, CD_Provinces.ProCode, CD_Zone.InstitutionName AS Zone
                        FROM TeacherMast
                        INNER JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID
                        INNER JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
                        INNER JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
                        INNER JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
                        INNER JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
                        WHERE (TeacherMast.NIC = N'$NICUser')";
                        $stmtPD = $db->runMsSqlQuery($sqlPD);
                        while ($rowPD = sqlsrv_fetch_array($stmtPD)) {
                            $ProCode = trim($rowPD["ProCode"]);
                            $District = trim($rowPD["DistrictCode"]);
                            $ZONECODE = trim($rowPD["InstCode"]);       
                            $Division = null;
                            $SCType = null;
                            
                            echo "<h2>Total Number of Teacher Assistants in ".$rowPD['Zone']." Zone</h2>";
                        }
                    }

                    ?>

                    <?php
                    $Sctype = '1';

                    $params2 = array(
                            array($NICUser, SQLSRV_PARAM_IN),
                            array($AccLevel, SQLSRV_PARAM_IN),
                            array($ProCode, SQLSRV_PARAM_IN),
                            array($District, SQLSRV_PARAM_IN),
                            array($ZONECODE, SQLSRV_PARAM_IN),
                            array($Sctype, SQLSRV_PARAM_IN),
                            array($DS03, SQLSRV_PARAM_IN),
                            array($RN01, SQLSRV_PARAM_IN),
                            array($RT01, SQLSRV_PARAM_IN),
                            array($DS01, SQLSRV_PARAM_IN),
                            array($SP44, SQLSRV_PARAM_IN)                    
                        );

                    // $params2 = array($NICUser,$AccLevel,$ProCode,$District,$ZONECODE,$Sctype);
                    $sql = "{call SP_TG_GetAssistantsFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)}";
                    $rcount = $db->runMsSqlQueryForSP($sql, $params2);
                    $qResult = $rcount['result'];
                    $count = $rcount['count'];
                    while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                        // echo $row['TCHRASSIS']; 
                        global $NSTA;
                        $NSTA = $row['TCHRASSIS'];
                    }

                    $Sctype = '3';

                                    $params2 = array(
                                        array($NICUser, SQLSRV_PARAM_IN),
                                        array($AccLevel, SQLSRV_PARAM_IN),
                                        array($ProCode, SQLSRV_PARAM_IN),
                                        array($District, SQLSRV_PARAM_IN),
                                        array($ZONECODE, SQLSRV_PARAM_IN),
                                        array($Sctype, SQLSRV_PARAM_IN),
                                        array($DS03, SQLSRV_PARAM_IN),
                                        array($RN01, SQLSRV_PARAM_IN),
                                        array($RT01, SQLSRV_PARAM_IN),
                                        array($DS01, SQLSRV_PARAM_IN),
                                        array($SP44, SQLSRV_PARAM_IN)                    
                                    );

                                // $params2 = array($NICUser,$AccLevel,$ProCode,$District,$ZONECODE,$Sctype);
                                $sql = "{call SP_TG_GetAssistantsFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)}";
                                $rcount = $db->runMsSqlQueryForSP($sql, $params2);
                                $qResult = $rcount['result'];
                                $count = $rcount['count'];
                                while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                    //  echo $row['TCHRASSIS'];
                                     global $PRSTA;
                                     $PRSTA = $row['TCHRASSIS'];
                                }
                    
                                $Sctype = '4';

                                $params2 = array(
                                    array($NICUser, SQLSRV_PARAM_IN),
                                    array($AccLevel, SQLSRV_PARAM_IN),
                                    array($ProCode, SQLSRV_PARAM_IN),
                                    array($District, SQLSRV_PARAM_IN),
                                    array($ZONECODE, SQLSRV_PARAM_IN),
                                    array($Sctype, SQLSRV_PARAM_IN),
                                    array($DS03, SQLSRV_PARAM_IN),
                                    array($RN01, SQLSRV_PARAM_IN),
                                    array($RT01, SQLSRV_PARAM_IN),
                                    array($DS01, SQLSRV_PARAM_IN),
                                    array($SP44, SQLSRV_PARAM_IN)                    
                                );

                            // $params2 = array($NICUser,$AccLevel,$ProCode,$District,$ZONECODE,$Sctype);
                            $sql = "{call SP_TG_GetAssistantsFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)}";
                            $rcount = $db->runMsSqlQueryForSP($sql, $params2);
                            $qResult = $rcount['result'];
                            $count = $rcount['count'];
                            while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                //  echo $row['TCHRASSIS'];
                                 global $PASTA;
                                 $PASTA = $row['TCHRASSIS'];
                            }
                            $Sctype = '5';

                            $params2 = array(
                                array($NICUser, SQLSRV_PARAM_IN),
                                array($AccLevel, SQLSRV_PARAM_IN),
                                array($ProCode, SQLSRV_PARAM_IN),
                                array($District, SQLSRV_PARAM_IN),
                                array($ZONECODE, SQLSRV_PARAM_IN),
                                array($Sctype, SQLSRV_PARAM_IN),
                                array($DS03, SQLSRV_PARAM_IN),
                                array($RN01, SQLSRV_PARAM_IN),
                                array($RT01, SQLSRV_PARAM_IN),
                                array($DS01, SQLSRV_PARAM_IN),
                                array($SP44, SQLSRV_PARAM_IN)                    
                            );

                        // $params2 = array($NICUser,$AccLevel,$ProCode,$District,$ZONECODE,$Sctype);
                        $sql = "{call SP_TG_GetAssistantsFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)}";
                        $rcount = $db->runMsSqlQueryForSP($sql, $params2);
                        $qResult = $rcount['result'];
                        $count = $rcount['count'];
                        while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                            //  echo $row['TCHRASSIS'];
                             global $PUASTA;
                             $PUASTA = $row['TCHRASSIS'];
                        }
                        $Sctype = '6';

                                    $params2 = array(
                                        array($NICUser, SQLSRV_PARAM_IN),
                                        array($AccLevel, SQLSRV_PARAM_IN),
                                        array($ProCode, SQLSRV_PARAM_IN),
                                        array($District, SQLSRV_PARAM_IN),
                                        array($ZONECODE, SQLSRV_PARAM_IN),
                                        array($Sctype, SQLSRV_PARAM_IN),
                                        array($DS03, SQLSRV_PARAM_IN),
                                        array($RN01, SQLSRV_PARAM_IN),
                                        array($RT01, SQLSRV_PARAM_IN),
                                        array($DS01, SQLSRV_PARAM_IN),
                                        array($SP44, SQLSRV_PARAM_IN)                    
                                    );

                                // $params2 = array($NICUser,$AccLevel,$ProCode,$District,$ZONECODE,$Sctype);
                                $sql = "{call SP_TG_GetAssistantsFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)}";
                                $rcount = $db->runMsSqlQueryForSP($sql, $params2);
                                $qResult = $rcount['result'];
                                $count = $rcount['count'];
                                while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                    //  echo $row['TCHRASSIS'];
                                     global $PIRTA;
                                     $PIRTA = $row['TCHRASSIS'];
                                }
                                $Sctype = '2';

                                $params2 = array(
                                    array($NICUser, SQLSRV_PARAM_IN),
                                    array($AccLevel, SQLSRV_PARAM_IN),
                                    array($ProCode, SQLSRV_PARAM_IN),
                                    array($District, SQLSRV_PARAM_IN),
                                    array($ZONECODE, SQLSRV_PARAM_IN),
                                    array($Sctype, SQLSRV_PARAM_IN),
                                    array($DS03, SQLSRV_PARAM_IN),
                                    array($RN01, SQLSRV_PARAM_IN),
                                    array($RT01, SQLSRV_PARAM_IN),
                                    array($DS01, SQLSRV_PARAM_IN),
                                    array($SP44, SQLSRV_PARAM_IN)                    
                                );

                            // $params2 = array($NICUser,$AccLevel,$ProCode,$District,$ZONECODE,$Sctype);
                            $sql = "{call SP_TG_GetAssistantsFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)}";
                            $rcount = $db->runMsSqlQueryForSP($sql, $params2);
                            $qResult = $rcount['result'];
                            $count = $rcount['count'];
                            while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                //  echo $row['TCHRASSIS'];
                                 global $MITA;
                                 $MITA = $row['TCHRASSIS'];
                            }
                            $Sctype = '7';

                                    $params2 = array(
                                        array($NICUser, SQLSRV_PARAM_IN),
                                        array($AccLevel, SQLSRV_PARAM_IN),
                                        array($ProCode, SQLSRV_PARAM_IN),
                                        array($District, SQLSRV_PARAM_IN),
                                        array($ZONECODE, SQLSRV_PARAM_IN),
                                        array($Sctype, SQLSRV_PARAM_IN),
                                        array($DS03, SQLSRV_PARAM_IN),
                                        array($RN01, SQLSRV_PARAM_IN),
                                        array($RT01, SQLSRV_PARAM_IN),
                                        array($DS01, SQLSRV_PARAM_IN),
                                        array($SP44, SQLSRV_PARAM_IN)                    
                                    );

                                // $params2 = array($NICUser,$AccLevel,$ProCode,$District,$ZONECODE,$Sctype);
                                $sql = "{call SP_TG_GetAssistantsFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)}";
                                $rcount = $db->runMsSqlQueryForSP($sql, $params2);
                                $qResult = $rcount['result'];
                                $count = $rcount['count'];
                                while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                    //  echo $row['TCHRASSIS'];
                                     global $SESTA;
                                     $SESTA = $row['TCHRASSIS'];
                                }
                                $Sctype = '8';

                                $params2 = array(
                                    array($NICUser, SQLSRV_PARAM_IN),
                                    array($AccLevel, SQLSRV_PARAM_IN),
                                    array($ProCode, SQLSRV_PARAM_IN),
                                    array($District, SQLSRV_PARAM_IN),
                                    array($ZONECODE, SQLSRV_PARAM_IN),
                                    array($Sctype, SQLSRV_PARAM_IN),
                                    array($DS03, SQLSRV_PARAM_IN),
                                    array($RN01, SQLSRV_PARAM_IN),
                                    array($RT01, SQLSRV_PARAM_IN),
                                    array($DS01, SQLSRV_PARAM_IN),
                                    array($SP44, SQLSRV_PARAM_IN)                    
                                );

                            // $params2 = array($NICUser,$AccLevel,$ProCode,$District,$ZONECODE,$Sctype);
                            $sql = "{call SP_TG_GetAssistantsFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)}";
                            $rcount = $db->runMsSqlQueryForSP($sql, $params2);
                            $qResult = $rcount['result'];
                            $count = $rcount['count'];
                            while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                //  echo $row['TCHRASSIS'];
                                 global $EITA;
                                 $EITA = $row['TCHRASSIS'];
                            }
                            $Assistval = $NSTA + $PRSTA + $PASTA + $PUASTA + $PIRTA + $MITA + $SESTA + $EITA;
                    ?>
                    <script type="text/javascript">
                        var nstap = <?php echo $NSTA/$Assistval*100 ?>;
                        var prstap = <?php echo $PRSTA/$Assistval*100 ?>;
                        var pastap = <?php echo $PASTA/$Assistval*100 ?>;
                        var paustap = <?php echo $PUASTA/$Assistval*100 ?>;
                        var pirtap = <?php echo $PIRTA/$Assistval*100 ?>;
                        var mitap = <?php echo $MITA/$Assistval*100 ?>;
                        var sestap = <?php echo $SESTA/$Assistval*100 ?>;
                        var eitap = <?php echo $EITA/$Assistval*100 ?>;
                    </script>
                    <table id="customers">
<tr>
    <th><h3>School Type</h3></th>
    <th><h3>Number</h3></th>
</tr>
<td>
                            <h3>
                                National School teacher Assistants:
                                </h3>
                        </td>
                            <td>
                            <h3> 
                                <?php
                                    echo $NSTA;
                                ?>
                            </h3>
                        </td>
                        </tr>
                        <tr>
                        <td>
                            <h3>
                                Provincial School teacher Assistants: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    echo $PRSTA;
                                ?>
                            </h3>
                        </td>
                        </tr>
                        <tr>
                        <td>
                            <h3 style="text-align: right; color: #000000;">Government School Teacher Assistants: </h3>
                        </td>
                        <td>
                        <h3 style="text-align: right; color: #000000;"><?php echo $NSTA + $PRSTA ?></h3>     
                        </td>
                    </tr>
                        <tr>
                        <td>
                            <h3>
                                Private Aided School teacher Assistants: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    echo $PASTA;
                                ?>
                            </h3>
                        </td>
                        </tr>
                        <tr>
                        <td>
                            <h3>
                                Private Un-Aided School teacher Assistants: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    echo $PUASTA;
                                ?>
                            </h3>
                        </td>
                        </tr>
                        <tr>
                        <td>
                            <h3>
                                Pirivena teacher Assistants: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    echo $PIRTA;
                                ?>
                            </h3>
                        </td>
                        </tr>
                        <tr>
                        <td>
                            <h3>
                                Meheni Institutions teacher Assistants: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    echo $MITA;
                                ?>
                            </h3>
                        </td>
                        </tr>
                        <tr>
                        <td>
                            <h3>
                                Special Education School teacher Assistants: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    echo $SESTA;
                                ?>
                            </h3>
                        </td>
                        </tr>
                        <tr>
                        <td>
                            <h3>
                                Education Institution teacher Assistants: 
                                </h3>
                        </td>
                            <td>
                            <h3>
                                <?php
                                    echo $EITA;
                                ?>
                            </h3>
                        </td>
                        </tr>
                        <tr>
                        <td><h3 style="text-align: right; color: #000000;">Total Teacher Assistants: </h3></td>
                        <td><h3 style="text-align: right; color: #000000;"><?php echo  $NSTA+$PRSTA+$PASTA+$PUASTA+$PIRTA+$MITA+$SESTA+$EITA?></h3></td>
                        </tr>
                        <tr>
                    <tr>
                        <td>
                            <h3>Data Source: Teacher Human Resource Management Portal - NEMIS</h3>
                        </td>
                        <td>
                            <h3>Date: <?php echo date("d/m/Y") ?></h3>
                        </td>
                    </tr>
                </table>               
</div>
            </div>
        </div>
    </body>
</html>