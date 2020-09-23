<?php
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
if($_SESSION['NIC']=='') {
	header("Location: ../index.php") ;
	exit() ;
}
include '../db_config/DBManager.php';
$db = new DBManager();

// include '/LoggedHandle.php';

$url = (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] : "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$exUrl = explode('/', $url);
$folderLocation = count($exUrl) - 2;
$ModuleFolder = $exUrl[$folderLocation];

$nicNO = $_SESSION["NIC"];
$loggedPositionName = $_SESSION['loggedPositionName'];
$AccessRoleType = $_SESSION['AccessRoleType'];

$CenCodex = trim($_SESSION['loggedSchool']);
$restZone = substr($CenCodex, -4, 4);
$zoneCodeLoged = "ZN" . $restZone;
// var_dump($zoneCodeLoged);
// var_dump($AccessRoleType);

$querySaveVal = "";

$theamPath = "../cms/images/";
$theam = "theam1";
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


// **** query save
$sqProvince = "";
$sqDistrict = "";
$sqZone = "";
$sqDivision = "";
$sqSchool = "";
$sqScType = "";

if (isset($_POST["hidquerySave"])) {
    $querySaveVal = $_POST["hidquerySave"];

    if ($querySaveVal != "") {
        $queryName = $_POST["hidqueryName"];
        $sql = "SELECT ID, NIC, QueryName, Query FROM TG_QuerySave WHERE (NIC = N'$nicNO') AND (QueryName = N'$queryName')";

        $stmt = $db->runMsSqlQuery($sql);
        while ($row = sqlsrv_fetch_array($stmt)) {
            $saveQID = $row["ID"];
        }
    }

    if ($querySaveVal != "") {
        $sql = "SELECT GeoCoulmName, GeoColumValue FROM TG_QuerySaveGeography WHERE (ID = $saveQID) AND (GeoCoulmName = N'ST')";
        $stmt = $db->runMsSqlQuery($sql);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $sqScType = $row["GeoColumValue"];
        }

        $sql = "SELECT
  GeoCoulmName,
  GeoColumValue
FROM TG_QuerySaveGeography
WHERE (ID = $saveQID) AND (GeoCoulmName = N'PR')";
        $stmt = $db->runMsSqlQuery($sql);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $sqProvince = $row["GeoColumValue"];
        }

        $sql = "SELECT
  GeoCoulmName,
  GeoColumValue
FROM TG_QuerySaveGeography
WHERE (ID = $saveQID) AND (GeoCoulmName = N'DI')";
        $stmt = $db->runMsSqlQuery($sql);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $sqDistrict = $row["GeoColumValue"];
        }

        $sql = "SELECT
  GeoCoulmName,
  GeoColumValue
FROM TG_QuerySaveGeography
WHERE (ID = $saveQID) AND (GeoCoulmName = N'ZN')";
        $stmt = $db->runMsSqlQuery($sql);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $sqZone = $row["GeoColumValue"];
        }

        $sql = "SELECT
  GeoCoulmName,
  GeoColumValue
FROM TG_QuerySaveGeography
WHERE (ID = $saveQID) AND (GeoCoulmName = N'DV')";
        $stmt = $db->runMsSqlQuery($sql);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $sqDivision = $row["GeoColumValue"];
        }

        $sql = "SELECT
  GeoCoulmName,
  GeoColumValue
FROM TG_QuerySaveGeography
WHERE (ID = $saveQID) AND (GeoCoulmName = N'SC')";
        $stmt = $db->runMsSqlQuery($sql);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $sqSchool = $row["GeoColumValue"];
        }
    }
}


// *** end query save





$LOGGEDUSERID = $nicNO; // 172839946V
$ACCESSLEVEL = "";
$DIVCODE = '';
$ZONECODE = null;
$DISTCODE = null;
$ProCode = '';
$Province = null;
$District = null;
$Division = null;
$SCType = null;

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



//   $sql = "SELECT
//   Passwords.AccessLevel,
//   TeacherMast.emailaddr,
//   TeacherMast.Title,
//   CD_Title.TitleName + TeacherMast.SurnameWithInitials AS name,
//   StaffServiceHistory.InstCode,
//   CD_Districts.DistName,
//   CD_Districts.DistCode,
//   CD_Provinces.ProCode,
//   CD_Provinces.Province,
//   Passwords.NICNo,
//   CD_CensesNo.CenCode,
//   CD_CensesNo.InstitutionName,
//   CD_CensesNo.ZoneCode,
//   CD_CensesNo.DivisionCode,
//   Passwords.AccessRole
//   FROM CD_CensesNo
//   INNER JOIN Passwords
//   INNER JOIN TeacherMast
//   ON Passwords.NICNo = TeacherMast.NIC
//   INNER JOIN CD_Title
//   ON TeacherMast.Title = CD_Title.TitleCode
//   INNER JOIN StaffServiceHistory
//   ON TeacherMast.NIC = StaffServiceHistory.NIC
//   ON CD_CensesNo.CenCode = StaffServiceHistory.InstCode
//   INNER JOIN CD_Provinces
//   INNER JOIN CD_Districts
//   ON CD_Provinces.ProCode = CD_Districts.ProCode
//   ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
//   WHERE (Passwords.NICNo = N'$nicNO')";

//   //echo $sql;
//   $stmt = $db->runMsSqlQuery($sql);
//   while ($row = sqlsrv_fetch_array($stmt)) {
//   $DIVCODE = $row["DivisionCode"];
//   $ZONECODE = $row["ZoneCode"];
//   $DISTCODE = $row["DistCode"];
//   $ProCode = $row["ProCode"];
//   $Province = $row["ProCode"];
//   $District = $row["DistCode"];
//   $Division = $row["DivisionCode"];
//   //$SCType = $row[""];
//   }



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
/*
  if ($Division == "" || empty($Division) || isset($Division)) {
  $Division = null;
  }
  if ($ZONECODE == "" || empty($ZONECODE) || isset($ZONECODE)) {
  $ZONECODE = null;
  }
* 
*/



// login mangeing


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
        <script src = "selectpage.js"></script>
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
        
    </head>
    <body>
        <!-- Begin Page Content -->
        <form id="form1" name="form1" action="" method="POST">
            <div class="container">
                <!--<div class="logOut" style="background-color: #212121;">
                    <div align="right" style="margin-top:10px;">
                        <label style="text-align:right; margin-right:30px; width:auto; font-family:Calibri; color: #FFFFFF;"><?php echo $_SESSION["fullName"]; ?></label>
                        <input type="button" class="logOutButton" align="right" name="" id="" value="Log Out" onClick="logoutForm('mail');"/>
                    </div>

                </div>-->

                <!-- <div class="containerHeader" style="text-align:center; background:url(images/menu_back.gif) repeat-x" >
                     <div align="right" style="margin-top:10px;">
                         <label style="text-align:right; margin-right:30px; width:auto; font-family:Calibri; color: #FFFFFF;"><b><?php echo $_SESSION["fullName"]; ?></b></label>
                         <input type="button" class="logOutButton" align="right" name="" id="" value="Log Out" onClick="logoutForm('mail');"/>
 
                     </div>
                     <img src="images/header.png" width="960" style="margin-top:0px;" />
                 </div>
                -->

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
                                                <li class="active"><a href="#geographical">Geographical</a></li>
                                                <li><a href="#biographical">Biographical</a></li>
                                                <li><a href="#qualifications">Qualifications</a></li>   
                                                <li><a href="#teaching">Teaching</a></li>
                                                <li><a href="#service">Service</a></li>
                                                <li><a href="#Appointmentdetails">Appointmnet</a></li>
                                                <li><a href="#Teachingdetails">Teaching</a></li>
                                                <!-- <li><a href="#CircularCat">Circular Category</a></li> -->
                                                <li><a href="#outOfService">Out of Service</a></li>
                                                <li><a href="#columns">Columns</a></li>
                                                <li><a href="#querySave">Saved Query</a></li>
                                            </ul>

                                            <!--geographical tab -->
                                            <div id="geographical" class="contenttab">
                                                <ul id="itemContainer">
                                                    <li>                                           
                                                        <div class="productsItemBoxText">
                                                            <table>
                                                                <tr>
                                                                    <td>
                                                                        <label for="username" class="labelTxt"><strong>Province :</strong></label>
                                                                    </td>
                                                                    <td>
                                                                        <label for="username" class="labelTxt"><strong>District :</strong></label>
                                                                    </td>
                                                                    <td>
                                                                        <label for="username" class="labelTxt" style="margin-left:22px;"><strong>Zone :</strong></label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                    <div class="divSimple" id = "divprovince">
                                                                    <select style="width:260px;" id="cmbProvince" name="cmbProvince" 
                                                                    onchange="Javascript:show_district('districtList', this.options[this.selectedIndex].value, '');">

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
                                                                    <input type="checkbox" name="groupBy[]" id="chkProvince" value="PRO">

                                                                </div>
                                                                    </td>
                                                                    <td>
                                                                    <div class="divSimple" id="divdistrict">
                                                                    <select style="width:260px;" id="cmbDistrict" name="cmbDistrict" onchange="Javascript:show_zone('zonelist', this.options[this.selectedIndex].value, '');">

                                                                        <?php
//District
// $sql = "SELECT DistCode,DistName FROM [CD_Districts] WHERE (DistCode != '')";
//if ($ProCode == "")
                                                                        // var_dump($sqProvince);
                                                                        $sql = "{call SP_TG_GetDistrictFor_LoggedUser( ?, ?, ?)}"; // removed this part

                                                                        //$stmt = $db->runMsSqlQuery($sql, $params);
                                                                        
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
                                                                    <input type="checkbox" name="groupBy[]" id="chkDistrict" value="DIS">
                                                                </div>
                                                                    </td>
                                                                    <td>
                                                                    <div class="divSimple" style="margin-left:22px;" id="divzone">
                                                                    <select style="width:260px;" id="cmbZone" name="cmbZone" onchange="Javascript:show_division('divisionList', this.options[this.selectedIndex].value, document.form1.cmbZone.value);">
                                                                        
                                                                        <?php
//Zone
//$sql = "SELECT CenCode,CONCAT(CenCode,'- ',InstitutionName) AS Zone FROM [CD_CensesNo] WHERE (InstType = 'ZN')";
                                                                        $sql = "{call SP_TG_GetZonesFor_LooggedUser( ?, ?, ? ,?, ?)}";

                                                                        // $stmt = $db->runMsSqlQuery($sql, $params4);
                                                                

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
                                                                    <input type="checkbox" name="groupBy[]" id="chkZone" value="ZON">
                                                                </div>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                        <label for="username" class="labelTxt"><strong>Division :</strong></label>
                                                                    </td>
                                                                    <td>
                                                                        <label for="username" class="labelTxt" style="margin-left:22px;"><strong>School Type :</strong></label>
                                                                    </td>
                                                                    <td>
                                                                        <label for="username" class="labelTxt" style="margin-left:22px;"><strong>School status:</strong></label>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>
                                                                    <div class="divSimple" id="divdivision">
                                                                    <select style="width:260px;" id="cmbDivision" name="cmbDivision" onchange="Javascript:show_cences('censesList', this.options[this.selectedIndex].value, document.form1.cmbDivision.value);">
                                                                        <?php 
//Division
//$sql = "SELECT CenCode,CONCAT(CenCode,'- ',InstitutionName) AS Division FROM [CD_CensesNo] WHERE (InstType = 'ED')";
                                                                        $sql = "{call SP_TG_GetDivisionFor_LooggedUser( ?, ?, ? , ?, ?)}";

                                                                        //$stmt = $db->runMsSqlQuery($sql, $params1); 
                                                                        
                                                                        $rcount = $db->runMsSqlQueryForSP($sql, $params1);
                                                                        $qResult = $rcount['result'];
                                                                        $count = $rcount['count'];
                                                                        // if($count>1)
                                                                        echo "<option value=\"\">All</option>";
                                                                        
                                                                        while ($row = sqlsrv_fetch_array($qResult, SQLSRV_FETCH_ASSOC)) {
                                                                            if ($sqDivision == trim($row['CenCode']))
                                                                                echo '<option selected="selected" value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
                                                                            else
                                                                                echo '<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                    <input type="checkbox" name="groupBy[]" id="chkDivision" value="DIV">
                                                                </div> 
                                                                    </td>
                                                                    <td>
                                                                    <div class="divSimple" style="">
                                                                    <select style="width:260px;" onchange="loadAccordingToSCType();" id="cmbSchoolType" name="cmbSchoolType">

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
                                                                    <input type="checkbox" name="groupBy[]" id="chkSCType" value="SCT">

                                                                </div>
                                                                    </td>
                                                                    <td>
                                                                        <!-- Start of school status --><!-- Start of school status --><!-- Start of school status --><!-- Start of school status --><!-- Start of school status -->
                                                                    <div class="divSimple" style="padding-left:22px; width:100%;">
                                                                        <!-- <select style="width:260px;" class="selectRpt" id="cmbSchoolStatus" name="cmbSchoolStatus" onchange="Javascript:show_cences_status('censesStatusList', this.options[this.selectedIndex].value, document.form1.cmbSchoolStatus.value);"> -->
                                                                        <select style=" width:260px;" class="selectRpt" id="cmbSchoolStatus" name="cmbSchoolStatus" onchange="loadAccordingToSCStatus();">  
                                                                            <option value="">All</option>
                                                                            <option value="Y">Functioning</option>
                                                                            <option value="N">Not Functioning</option>
                                                                        </select>
                                                                        <input type="checkbox" name="groupBy[]" id="chkStat" value="FUN">
                                                                    </div>
<!-- End of school status --><!-- End of school status --><!-- End of school status --><!-- End of school status --><!-- End of school status --><!-- End of school status -->

                                                                    </td>
                                                                </tr>
                                                                </table>
                                                                <!-- <tr colspan = "3"> -->
                                                                <div style="padding-left:5px;">
                                                                    <label for="username" class="labelTxt" style=""><strong>School :</strong></label>
                                                                </div>
                                                                <!-- </tr>
                                                                <tr colspan = "3"> -->
                                                                <div class="divSimple" style="padding-left:5px; width:950px; " id ="divschool">
                                                                    <select style="width:894px;" id="cmbSchool" name="cmbSchool" onchange="disableCheckBox();">
                                                                        
                                                                        <?php
//School
// $sql = "SELECT CenCode,CONCAT(CenCode,'- ',InstitutionName) AS School FROM [CD_CensesNo] WHERE (InstType = 'SC')";
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
                                                                    <input type="checkbox" name="groupBy[]" id="chkSchool" value="SCH">
                                                                    <!-- <script>window.alert(cmbSchool.value) </script> -->
                                                                </div>
                                                                <!-- </tr>
                                                            </table> -->

                
                                                                

                                                        </div>

                                                    </li>
                                                </ul>
                                            </div>
                                            <!--End geographical tab -->

                                            <!--biographical tab -->
                                            <div id="biographical" class="contenttab">
                                                <ul id="itemContainer">
                                                    <li>

                                                        <div class="productsItemBoxText">
                                                            <!--selected items goes to this table-->
                                                            <table style="width:921px;" border="0" bgcolor="#2D65A0" id="tblMainBioDetails" cellpadding="3" cellspacing="1">
                                                                <tr>
                                                                    <td width="28%" bgcolor="#FFFFFF"><strong>Biography</strong></td>
                                                                    <td width="10%" bgcolor="#FFFFFF">&nbsp;</td>
                                                                    <td width="41%" align="center" bgcolor="#FFFFFF"><strong>Category</strong></td>
                                                                    <td width="11%" align="center" bgcolor="#FFFFFF"><strong>Remove</strong></td>
                                                                    <td  align="center" bgcolor="#FFFFFF"><strong>Group</strong></td>
                                                                </tr>
                                                                <?php
                                                                if ($querySaveVal != "") {
                                                                    $sql = "SELECT
  BioCoulmName,
  BioColumValue,
  BioColumID
FROM TG_QuerySaveBiography
WHERE (ID = '$saveQID')";

                                                                    $stmt = $db->runMsSqlQuery($sql);
                                                                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                        echo "<tr>";
                                                                        echo "<td  bgcolor=\"#FFFFFF\">" . $row["BioCoulmName"] . "<input type=\"hidden\" name=\"txtBioFeildName[]\" value=\"" . $row["BioCoulmName"] . "\"/></td>";
                                                                        echo "<td  align=\"center\" bgcolor=\"#FFFFFF\"<input type=\"hidden\" name=\"txtBioOperation[]\" value=\"\"/>=</td>";
                                                                        echo "<td  bgcolor=\"#FFFFFF\"><input type=\"hidden\" name=\"txtBioItemCode[]\" value=\"" . $row["BioColumID"] . "\"/><input type=\"hidden\" name=\"txtBioItemName[]\" value=\"" . $row["BioColumValue"] . "\"/>" . $row["BioColumValue"] . "</td>";
                                                                        echo "<td  align=\"center\" bgcolor=\"#FFFFFF\"><img src=\"images/trash.png\" width=\"14\" height=\"14\" onclick=\"rmvRow(this);\"/></td>";
                                                                        echo "<td  align=\"center\" bgcolor=\"#FFFFFF\"><input type=\"checkbox\" value=\"" . $row["BioCoulmName"] . "\" onclick=\"disableBioCheckbox(this);\" id=\"\" name=\"groupBy[]\"></td>";
                                                                        echo "</tr>";
                                                                    }
                                                                }
                                                                ?>

                                                            </table>

                                                            <div>
                                                                <!--Item selected area-->
                                                                <div class="divSimple" style="margin-left:0px; margin-top: 10px; width:auto;">
                                                                    <label style="float:left; width:756px;"><strong>Biography Parameter:</strong></label>

                                                                    <div style="float:left; margin-right:30px; margin-top:8px; width:auto;">
                                                                        <div style="width:150px; float:left;">
                                                                            <select style="width:100px;" id="bioItems" name="bioItems" onchange="loadBioDetails();">
                                                                                <option value="">All</option>
                                                                                <option value="G">Gender</option>
                                                                                <option value="E">Ethnicity</option>
                                                                                <option value="R">Religion</option>
                                                                                <option value="C">Civil Status</option>

                                                                            </select>
                                                                        </div>
                                                                        <div id="bioDetail" style="width:159px; float:left;">

                                                                        </div>                                                    
                                                                        <div style="float:left; display: none;" id="dioImg">
                                                                            <img width="48" height="19" onclick="addValuesToTable();" src="images/add_b.png" style="margin-left:40px;">
                                                                        </div>
                                                                    </div>


                                                                </div>

                                                                <!--End item selected area-->
                                                            </div>



                                                        </div>

                                                    </li>
                                                </ul>
                                            </div>
                                            <!--End biographical tab -->

                                            <!--Qualification tab -->
                                            <div id="qualifications" class="contenttab">
                                                <ul id="itemContainer">
                                                    <li>

                                                        <div class="productsItemBoxText">
                                                            <table width="800" border="0" bgcolor="#2D65A0" id="tblMainQuliDetails" cellpadding="3" cellspacing="1">
                                                                <tr>
                                                                    <td width="91%" align="center" bgcolor="#FFFFFF"><strong>Qualification</strong></td>
                                                                    <td width="9%" align="center" bgcolor="#FFFFFF"><strong>Remove</strong></td>
                                                                </tr>
                                                                <?php
                                                                if ($querySaveVal != "") {
                                                                    $sql = "SELECT CD_Qualif.Description FROM TG_QuerySaveQualification INNER JOIN CD_Qualif ON TG_QuerySaveQualification.QualificationValue = CD_Qualif.Qcode WHERE (TG_QuerySaveQualification.ID = '$saveQID')";

                                                                    $stmt = $db->runMsSqlQuery($sql);
                                                                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                        echo "<tr>";
                                                                        echo "<td width=\"91%\" bgcolor=\"#FFFFFF\">" . $row["Description"] . "</td>";
                                                                        echo "<td width=\"9%\" align=\"center\" bgcolor=\"#FFFFFF\"><img src=\"images/trash.png\" width=\"14\" height=\"14\" onclick=\"rmvRow(this);\"/></td>";
                                                                        echo "</tr>";
                                                                    }
                                                                }
                                                                ?>
                                                            </table>


                                                            <div>
                                                                <!--Item selected area-->
                                                                <div class="divSimple" style="margin-left:0px; margin-top: 10px; width:800px;">
                                                                    <label for="" class="labelTxt"><strong>Qualification :</strong></label>

                                                                    <div style="float: left; width: 710px;">
                                                                        <select style="width: auto;" id="qulificationItem" name="qulificationItem" >
                                                                            <option value="">All</option>
                                                                            <?php
                                                                            $sql = "SELECT
                                                                            Code, Description
                                                                          FROM CD_QualificationCategory
                                                                          WHERE (Code <> N'')
                                                                          ORDER BY Description";
                                                                            $stmt = $db->runMsSqlQuery($sql);
                                                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                                echo '<option value=' . $row['Code'] . '>' . $row['Description'] . '</option>';
                                                                            }
                                                                            ?>
                                                                        </select>

                                                                    </div>
                                                                    <div style="float: left;">
                                                                        <img src="images/add_b.png" style="margin-left:40px;" width="48" height="19" onClick="addRowToQulification();"/>
                                                                    </div>


                                                                </div>

                                                                <!--End item selected area-->
                                                            </div>

                                                        </div>

                                                    </li>
                                                </ul>
                                            </div>
                                            <!--End Qualification tab -->
                                            
                                            <!--Teaching tab -->
                                            <div id="teaching" class="contenttab">
                                                <ul id="itemContainer">
                                                    <li>

                                                        <div class="productsItemBoxText">

                                                            <table width="850" border="0" bgcolor="#2D65A0" id="tblMainTeachDetails" cellpadding="3" cellspacing="1">
                                                                <tr>
                                                                    <td width="105" align="center" bgcolor="#FFFFFF"><strong>Category</strong></td>
                                                                    <td width="311" align="center" bgcolor="#FFFFFF"><strong>Subject</strong></td>
                                                                    <td width="102" align="center" bgcolor="#FFFFFF"><strong>Medium</strong></td>
                                                                    <td width="200" align="center" bgcolor="#FFFFFF"><strong>Section</strong></td>
                                                                    <td width="103" align="center" bgcolor="#FFFFFF"><strong>Remove</strong></td>
                                                                </tr>
                                                                <?php
                                                                if ($querySaveVal != "") {
                                                                    $sql = "SELECT  CD_SubjectTypes.SubTypeName, CD_Subject.SubjectName, CD_Medium.Medium, CD_SecGrades.GradeName
FROM            TG_QuerySaveTeaching INNER JOIN
                         CD_Subject ON TG_QuerySaveTeaching.SubCode = CD_Subject.SubCode INNER JOIN
                         CD_SubjectTypes ON TG_QuerySaveTeaching.SubType = CD_SubjectTypes.SubType INNER JOIN
                         CD_Medium ON TG_QuerySaveTeaching.MediumCode = CD_Medium.Code INNER JOIN
                         CD_SecGrades ON TG_QuerySaveTeaching.GradeCode = CD_SecGrades.GradeCode
WHERE        (TG_QuerySaveTeaching.ID = '$saveQID')";

                                                                    $stmt = $db->runMsSqlQuery($sql);
                                                                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                        echo "<tr>";
                                                                        echo "<td width=\"105\" bgcolor=\"#FFFFFF\">" . $row["SubTypeName"] . "</td>";
                                                                        echo "<td width=\"311\" bgcolor=\"#FFFFFF\">" . $row["SubjectName"] . "</td>";
                                                                        echo "<td width=\"102\" bgcolor=\"#FFFFFF\">" . $row["GradeName"] . "</td>";
                                                                        echo "<td width=\"200\" bgcolor=\"#FFFFFF\">" . $row["Medium"] . "</td>";
                                                                        echo "<td width=\"103\" align=\"center\" bgcolor=\"#FFFFFF\"><img src=\"images/trash.png\" width=\"14\" height=\"14\" onclick=\"rmvRow(this);\"/></td>";
                                                                        echo "</tr>";
                                                                    }
                                                                }
                                                                ?>

                                                            </table>


                                                            <!--Item selected area-->
                                                            <div class="divSimple" style="margin-left:0px; margin-top: 10px; width:auto;">
                                                               
                                                                <!-- 
                                                                
                                                                 -->
                                                                <!-- <div style="float:left; margin-right:30px; margin-top:8px; width:auto;"> -->
                                                                <table><tr>
                                                                    <div style="width:100%; float:left;">
                                                                    <td><label style="float:left; width:auto;"><strong>Category :</strong></label></td>
                                                                    <td style="padding: 3px;"><select style="width: auto;" id="teachingType" name="teachingType" onchange="">
                                                                            <option value="">All</option>
                                                                            <?php
                                                                            $sql = "SELECT
																  SubType,
																  SubTypeName
																FROM CD_SubjectTypes";
                                                                            $stmt = $db->runMsSqlQuery($sql);
                                                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                                echo '<option value=' . $row['SubType'] . '>' . $row['SubTypeName'] . '</option>';
                                                                            }
                                                                            ?>
                                                                        </select></td>
                                                                    </div>
                                                                    </tr>

                                                                    <tr>
                                                                    <div style="width:100%; float:left;">
                                                                    <td><label style="float:left; width: auto;"><strong>Subject :</strong></label></td>
                                                                    <td style="padding: 3px;"><select style="width: auto;" id="teachingSubject" name="teachingSubject" onchange="">
                                                                            <option value="">All</option>
                                                                            <?php
                                                                            $sql = "SELECT
																  SubCode,
																  SubjectName
																FROM CD_Subject
																WHERE (SubCode <> N'')
																ORDER BY SubjectName";
                                                                            $stmt = $db->runMsSqlQuery($sql);
                                                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                                echo '<option value=' . $row['SubCode'] . '>' . $row['SubjectName'] . '</option>';
                                                                            }
                                                                            ?>
                                                                        </select></td>
                                                                    </div>
                                                                    </tr>
                                                                    <tr>
                                                                    <div style="width:100%; float:left;">
                                                                    <td><label style="float:left; width:auto"><strong>Medium :</strong></label></td>
                                                                    <td style="padding: 3px;"><select style="width: auto;" id="teachingMedium" name="teachingMedium" onchange="">
                                                                            <option value="">All</option>
                                                                            <?php
                                                                            $sql = "SELECT Code, LTRIM(Medium) AS Medium
																FROM CD_Medium
																WHERE (Code <> N'')
																ORDER BY Medium";
                                                                            $stmt = $db->runMsSqlQuery($sql);
                                                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                                echo '<option value=' . $row['Code'] . '>' . $row['Medium'] . '</option>';
                                                                            }
                                                                            ?>
                                                                        </select></td>
                                                                    </div>
                                                                    </tr>
                                                                    <!---------> 
                                                                    <tr>         
                                                                    <div style="width: 100%; float:left;">
                                                                    <td><label style="float:left; width:;150px"><strong>Section :</strong></label></td>
                                                                    <td style="padding: 3px;"><select style="width: auto;" id="teachingGrade" name="teachingGrade" onchange="">
                                                                            <option value="">All</option>
                                                                            <?php
                                                                            $sql = "SELECT GradeCode, LTRIM(GradeName) AS Grade
																FROM CD_SecGrades
																WHERE (GradeCode <> N'')
																ORDER BY Grade";
                                                                            $stmt = $db->runMsSqlQuery($sql);
                                                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                                echo '<option value=' . $row['GradeCode'] . '>' . $row['Grade'] . '</option>';
                                                                            }
                                                                            ?>
                                                                        </select></td>
                                                                    </div>
                                                                    </tr>
                                                                    <!--------->
                                                                    <tr><td>
                                                                    <div style="float:left;">
                                                                        <img src="images/add_b.png" width="48" height="19" onClick="addRowToTeachingtbl();"/>
                                                                    </div>
                                                                    </td></tr>
                                                                    </table>
                                                                </div>


                                                            </div>

                                                            <!--End item selected area-->
                                                        </div>

                                                    </li>
                                                </ul>
                                            </div>
                                            <!--End Teaching tab -->

                                            <!--Service tab -->
                                            <div id="service" class="contenttab">
                                                <ul id="itemContainer">
                                                    <li>
                                                        <div class="productsItemBoxText">
                                                            <table width="850" border="0" bgcolor="#2D65A0" id="tblMainServiceDetails" cellpadding="3" cellspacing="1">
                                                                <tr>
                                                                    <td width="205" align="center" bgcolor="#FFFFFF"><strong>Position</strong></td>
                                                                    <td width="311" align="center" bgcolor="#FFFFFF"><strong>Service Type</strong></td>
                                                                    <td width="43" align="center" bgcolor="#FFFFFF"><strong>Remove</strong></td>
                                                                </tr>
                                                                <?php
                                                                if ($querySaveVal != "") {
                                                                    $sql = "SELECT CD_Positions.PositionName, CD_Service.ServiceName FROM TG_QuerySaveService INNER JOIN CD_Positions ON TG_QuerySaveService.Code = CD_Positions.Code INNER JOIN CD_Service ON TG_QuerySaveService.ServCode = CD_Service.ServCode WHERE (TG_QuerySaveService.ID = '$saveQID')";

                                                                    $stmt = $db->runMsSqlQuery($sql);
                                                                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                        echo "<tr>";
                                                                        echo "<td width=\"205\" bgcolor=\"#FFFFFF\">" . $row["PositionName"] . "</td>";
                                                                        echo "<td width=\"311\" bgcolor=\"#FFFFFF\">" . $row["ServiceName"] . "</td>";
                                                                        echo "<td width=\"43\" align=\"center\" bgcolor=\"#FFFFFF\"><img src=\"images/trash.png\" width=\"14\" height=\"14\" onclick=\"rmvRow(this);\"/></td>";
                                                                        echo "</tr>";
                                                                    }
                                                                }
                                                                ?>
                                                            </table>


                                                            <!--Item selected area-->
                                                            <div class="divSimple" style="margin-left:0px; margin-top: 10px; width:auto;">
                                                                <label style="float:left; width:380px;"><strong>Position :</strong></label>
                                                                <label style="float:left; width:500px;"><strong>Service Type :</strong></label>

                                                                <div style="float:left; margin-right:30px; margin-top:8px; width:auto;">
                                                                    <div style="width:380px; float:left;">
                                                                        <select style="width: auto;" id="serviceposition" name="serviceposition" onchange="">
                                                                            <option value="">All</option>
                                                                            <?php
                                                                            $sql = "SELECT Code, PositionName
																FROM CD_Positions
																WHERE (Code <> N'')
																ORDER BY PositionName";
                                                                            $stmt = $db->runMsSqlQuery($sql);
                                                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                                echo '<option value=' . $row['Code'] . '>' . $row['PositionName'] . '</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div style="width:421px; float:left;">
                                                                        <select style="width: auto;" id="serviceType" name="serviceType" onchange="">
                                                                            <option value="">All</option>
                                                                            <?php
                                                                            $sql = "SELECT ServCode, LTRIM(ServiceName) AS serviceName
                                                                            
																FROM CD_Service
																WHERE (ServCode <> N'')";
                                                                            $stmt = $db->runMsSqlQuery($sql);
                                                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                                echo '<option value=' . $row['ServCode'] . '>' . $row['serviceName'] . '</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>

                                                                    <div style="float:left;">
                                                                        <img src="images/add_b.png" width="48" height="19" onClick="addRowToServicetbl();"/>
                                                                    </div>
                                                                </div>


                                                            </div>

                                                            <!--End item selected area-->


                                                        </div>

                                                    </li>
                                                </ul>
                                            </div>
                                            <!--End Service tab -->
                                            <!-- Appointment Details tab -->
                                            <div id="Appointmentdetails" class="contenttab">
                                                <ul id="itemContainer">
                                                    <li>
                                                    <div class="productsItemBoxText">

                                                        <table width="850" border="0" bgcolor="#2D65A0" id="tblMainTeachDetails" cellpadding="3" cellspacing="1">
                                                            <tr>
                                                                <td width="105" align="center" bgcolor="#FFFFFF"><strong>Grade Span</strong></td>
                                                                <td width="311" align="center" bgcolor="#FFFFFF"><strong>Subject</strong></td>
                                                                <td width="102" align="center" bgcolor="#FFFFFF"><strong>Medium</strong></td>
                                                                <td width="200" align="center" bgcolor="#FFFFFF"><strong>Section</strong></td>
                                                                <td width="103" align="center" bgcolor="#FFFFFF"><strong>Remove</strong></td> 
                                                            </tr>
                                                        </table>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div> 
                                            <!-- End Appointment Details tab --> -->
                                            <!-- Teaching Details tab -->
                                            <!-- <div id="Teachingdetails" class="contenttab">
                                                <ul id="itemContainer">
                                                    <li>
                                                    <div class="productsItemBoxText">

                                                        <table width="850" border="0" bgcolor="#2D65A0" id="tbl2MainTeachDetails" cellpadding="3" cellspacing="1">
                                                            <tr>
                                                                <td width="105" align="center" bgcolor="#FFFFFF"><strong>Grade Span</strong></td>
                                                                <td width="311" align="center" bgcolor="#FFFFFF"><strong>Subject</strong></td>
                                                                <td width="102" align="center" bgcolor="#FFFFFF"><strong>Medium</strong></td>
                                                                <td width="200" align="center" bgcolor="#FFFFFF"><strong>Section</strong></td>
                                                                <td width="103" align="center" bgcolor="#FFFFFF"><strong>Remove</strong></td>
                                                            </tr>
                                                        </table>
                                                        </div>
                                                    </li>
                                                </ul>
                                                
                                            </div> -->
                                            <!-- End Teaching Details tab -->

                                             <!--Teaching Detail tab -->
                                            <!-- <div id="Teachingdetails" class="contenttab">
                                                <ul id="itemContainer">
                                                    <li>

                                                        <div class="productsItemBoxText">

                                                            <table width="850" border="0" bgcolor="#2D65A0" id="tbl2MainTeachDetails" cellpadding="3" cellspacing="1">
                                                                <tr>
                                                                    <td width="105" align="center" bgcolor="#FFFFFF"><strong>Grade Span</strong></td>
                                                                    <td width="311" align="center" bgcolor="#FFFFFF"><strong>Subject</strong></td>
                                                                    <td width="102" align="center" bgcolor="#FFFFFF"><strong>Medium</strong></td>
                                                                    
                                                                    <td width="103" align="center" bgcolor="#FFFFFF"><strong>Remove</strong></td>
                                                                </tr>
                                                                <?php
//                                                                 if ($querySaveVal != "") {
//                                                                     $sql = "SELECT CD_TeachSubjects.SubjectName, CD_Medium.Medium, CD_TeachSubCategory.GradeName
// FROM            TG_QuerySaveTeaching INNER JOIN
//                          CD_TeachSubjects ON TG_QuerySaveTeaching.Code = CD_TeachSubjects.Code INNER JOIN
//                          CD_Medium ON TG_QuerySaveTeaching.MediumCode = CD_Medium.Code INNER JOIN
//                          CD_TeachSubCategory ON TG_QuerySaveTeaching.GradeCode = CD_SecGrades.GradeCode
// WHERE        (TG_QuerySaveTeaching.ID = '$saveQID')";

//                                                                     $stmt = $db->runMsSqlQuery($sql);
//                                                                     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//                                                                         echo "<tr>";
//                                                                         echo "<td width=\"105\" bgcolor=\"#FFFFFF\">" . $row["SubTypeName"] . "</td>";
//                                                                         echo "<td width=\"311\" bgcolor=\"#FFFFFF\">" . $row["SubjectName"] . "</td>";
//                                                                         echo "<td width=\"102\" bgcolor=\"#FFFFFF\">" . $row["GradeName"] . "</td>";
//                                                                         echo "<td width=\"200\" bgcolor=\"#FFFFFF\">" . $row["Medium"] . "</td>";
//                                                                         echo "<td width=\"103\" align=\"center\" bgcolor=\"#FFFFFF\"><img src=\"images/trash.png\" width=\"14\" height=\"14\" onclick=\"rmvRow(this);\"/></td>";
//                                                                         echo "</tr>";
//                                                                     }
//                                                                 }
                                                                ?>

                                                            </table> -->


                                                            <!--Item selected area-->
                                                            <!-- <div class="divSimple" style="margin-left:0px; margin-top: 10px; width:auto;"> -->
                                                               
                                                                <!-- 
                                                                
                                                                 -->
                                                                <!-- <div style="float:left; margin-right:30px; margin-top:8px; width:auto;"> -->
                                                                <!-- <table><tr>
                                                                    <div style="width:100%; float:left;">
                                                                    <td><label style="float:left; width:auto;"><strong>Teacher Hours :</strong></label></td>
                                                                    <td style="padding: 3px;"><select style="width: auto;" id="teaching2Type" name="teaching2Type" onchange="">
                                                                            <option value="1">Most Teaching Hours</option>
                                                                            <option value="2">Second Teaching Hours</option>
                                                                            <option value="3">Capable Teaching Hours</option>
                                                                        </select></td>
                                                                    </div>
                                                                    </tr>

                                                                    <tr>
                                                                    <div style="width:100%; float:left;">
                                                                    <td><label style="float:left; width: auto;"><strong>Subject :</strong></label></td>
                                                                    <td style="padding: 3px;"><select style="width: auto;" id="teaching2Subject" name="teaching2Subject" onchange="">
                                                                            <option value="">All</option> -->
                                                                            <?php
                                                                //             $sql = "SELECT
																//   Code,
																//   SubjectName
																// FROM CD_TeachSubjects
																// WHERE (Code <> N'')
																// ORDER BY SubjectName";
                                                                //             $stmt = $db->runMsSqlQuery($sql);
                                                                //             while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                //                 echo '<option value=' . $row['Code'] . '>' . $row['SubjectName'] . '</option>';
                                                                //             }
                                                                            ?>
                                                                        <!-- </select></td>
                                                                    </div>
                                                                    </tr>
                                                                    <tr>
                                                                    <div style="width:100%; float:left;">
                                                                    <td><label style="float:left; width:auto"><strong>Medium :</strong></label></td>
                                                                    <td style="padding: 3px;"><select style="width: auto;" id="teaching2Medium" name="teaching2Medium" onchange="">
                                                                            <option value="">All</option>
                                                                            <?php
                                                                //             $sql = "SELECT Code, LTRIM(Medium) AS Medium
																// FROM CD_Medium
																// WHERE (Code <> N'')
																// ORDER BY Medium";
                                                                //             $stmt = $db->runMsSqlQuery($sql);
                                                                //             while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                //                 echo '<option value=' . $row['Code'] . '>' . $row['Medium'] . '</option>';
                                                                //             }
                                                                            ?>
                                                                        </select></td>+
                                                                    </div>
                                                                    </tr> -->
                                                                    <!---------> 
                                                                    <!-- <tr>         
                                                                    <div style="width: 100%; float:left;">
                                                                    <td><label style="float:left; width:;150px"><strong>Section :</strong></label></td>
                                                                    <td style="padding: 3px;"><select style="width: auto;" id="teaching2Grade" name="teaching2Grade" onchange="">
                                                                            <option value="">All</option>
                                                                            <?php
                                                                //             $sql = "SELECT GradeCode, LTRIM(CategoryName) AS Grade
																// FROM CD_TeachSubCategory
																// WHERE (GradeCode <> N'')
																// ORDER BY Grade";
                                                                //             $stmt = $db->runMsSqlQuery($sql);
                                                                //             while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                //                 echo '<option value=' . $row['GradeCode'] . '>' . $row['Grade'] . '</option>';
                                                                //             }
                                                                            ?>
                                                                        </select></td>
                                                                    </div>
                                                                    </tr> -->
                                                                    <!--------->
                                                                    <!-- <tr><td>
                                                                    <div style="float:left;">
                                                                        <img src="images/add_b.png" width="48" height="19" onClick="addRowToTeachingDetailstbl();"/>
                                                                    </div>
                                                                    </td></tr>
                                                                    </table>
                                                                </div>


                                                            </div> -->

                                                            <!--End item selected area-->
                                                        <!-- </div>

                                                    </li>
                                                </ul>
                                            </div> -->
                                            <!--End Teaching details tab -->

                                            <!--outOfService tab -->
                                            <div id="outOfService" class="contenttab">
                                                <ul id="itemContainer">
                                                    <li>

                                                        <div class="productsItemBoxTextxxx">

														
                                                            <!--Item selected area-->
                                                            <div class="divSimple" style="margin-left:0px; margin-top: 10px; width:auto;">
                                                            
                                                                <label style="float:left; width:200px;"><strong>Resigned :</strong></label>
                                                                <label style="float:left; width:200px;"><strong>Dismissed :</strong></label>
                                                                <label style="float:left; width:200px;"><strong>Retired :</strong></label>
                                                                <label style="float:left; width:200px;"><strong>Dead :</strong></label>
															<div style="float:left; margin-right:30px; margin-top:8px; width:auto;">
                                                            <div style="width:200px; float:left;"><input name="resignT" id="resignT" type="checkbox" value="RN01"></div>
                                                            <div style="width:200px; float:left;"><input name="dissmissedT" id="dissmissedT" type="checkbox" value="DS03"></div>
                                                            <div style="width:200px; float:left;"><input name="retiredT" id="retiredT" type="checkbox" value="RT01"></div>
                                                            <div style="width:200px; float:left;"><input name="deadT" id="deadT" type="checkbox" value="DS01"></div>
                                                                    <!--<div style="float:left;">
                                                                        <img src="images/add_b.png" width="48" height="19" onClick="addRowToServicetbl();"/>
                                                                    </div>-->
                                                                </div>
                                                              

                                                            </div>

                                                            <!--End item selected area-->


                                                        </div>

                                                    </li>
                                                </ul>
                                            </div>
                                            <!--End serviceType tab -->
                                            
                                            <!--Select Colum-->
                                            <div id="columns" class="contenttab">
                                                <ul id="itemContainer" style="list-style-type:none;">
                                                    <li>
                                                        <div class="productsItemBoxText">
                                                            <table  style="width:800px;" border="0">
                                                                <tr>
                   
                                                                    <td width="5%" align="center"><input type="checkbox" name="selectColum[]" id="" value="Province"></td>
                                                                    <td width="23%">Province</td>
                                                                    <td width="4%">&nbsp;</td>
                                                                    <td width="8%" align="center"><input type="checkbox" name="selectColum[]"  value="Religion"/></td>
                                                                    <td width="25%">Religion</td>
                                                                    <td width="4%">&nbsp;</td>
                                                                    <td width="6%"><input type="checkbox" id="chkQuli" name="selectColum[]" disabled value="Qualification"/></td>
                                                                    <td width="25%">Qualification</td>
                                                                    <td><input type="checkbox" name="selectColum[]" disabled value="Category" id="chkCat"/></td>
                                                                    <td>Category</td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="center"><input type="checkbox" name="selectColum[]"  value="District" /></td>
                                                                    <td>District</td>
                                                                    <td>&nbsp;</td>
                                                                    <td align="center"><input type="checkbox" name="selectColum[]"  value="Civil" /></td>
                                                                    <td>Civil Status</td>
                                                                    <td>&nbsp;</td>
                                                                    <td><input type="checkbox" name="selectColum[]" disabled value="Subject" id="chkTeach"/></td>
                                                                    <td>Subject</td><!--OG teaching-->
                                                                    <td><input type="checkbox" name="selectColum[]"  value="DOFA" /></td>
                                                                    <td>DOFA</td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="center"><input type="checkbox" name="selectColum[]"  value="Zone" /></td>
                                                                    <td>Zone</td>
                                                                    <td>&nbsp;</td>
                                                                    <td align="center"><input type="checkbox" name="selectColum[]"  value="Ethnicity" /></td>
                                                                    <td>Ethnicity</td>
                                                                    <td>&nbsp;</td>
                                                                    <td><input type="checkbox" name="selectColum[]" disabled value="Position" id="chkPosition"/></td>
                                                                    <td>Position</td>
                                                                    <td><input type="checkbox" name="selectColum[]" value="Censes" id="chkCenses"/></td>
                                                                    <td>Censes No</td>
                                                                    <td>&nbsp;</td>
                                                                    <!--  -->
                                                                </tr>
                                                                <tr>
                                                                    <td align="center"><input type="checkbox" name="selectColum[]"  value="Division" /></td>
                                                                    <td>Division</td>
                                                                    <td>&nbsp;</td>
                                                                    <td align="center"><input type="checkbox" name="selectColum[]"  value="DOB" /></td>
                                                                    <td>DOB</td>
                                                                    <td>&nbsp;</td>
                                                                    <td><input type="checkbox" name="selectColum[]" disabled value="Service" id="chkService"/></td>
                                                                    <td>Service</td>
                                                                    <!-- <td><input type="checkbox" name="selectColum[]" disabled value="Subject2" id="chkCat2"/></td>
                                                                    <td>Subject2</td>
                                                                    <td>&nbsp;</td>> -->
                                                                    
                                                                </tr>
                                                                <tr>
                                                                    <td align="center"><input type="checkbox" name="selectColum[]"  value="Gender" /></td>
                                                                    <td>Gender</td>
                                                                    <td>&nbsp;</td>
                                                                    <td align="center"><input type="checkbox" name="selectColum[]"  value="Mobile" /></td>
                                                                    <td>Mobile No</td>
                                                                    <td>&nbsp;</td>
                                                                    <td><input type="checkbox" name="selectColum[]" disabled value="Medium" id="chkMed"/></td>
                                                                    <td>Medium</td>
                                                                    <!-- <td><input type="checkbox" name="selectColum[]" disabled value="Medium2" id="chkMed2"/></td>
                                                                    <td>Medium2</td>
                                                                    <td>&nbsp;</td> -->
                                                                </tr>
                                                                <tr>
                                                                    <td align="center"><input type="checkbox" name="selectColum[]"  value="Status" /></td>
                                                                    <td>School Status</td> <!--Added school status with checkbox-->
                                                                    <td>&nbsp;</td>
                                                                    <td align="center"><input type="checkbox" name="selectColum[]"  value="Type" /></td>
                                                                    <td>School Type</td>
                                                                    <td>&nbsp;</td>
                                                                    <td><input type="checkbox" name="selectColum[]" disabled value="Section" id="chksect"/></td>
                                                                    <td>Section</td>
                                                                    <!-- <td><input type="checkbox" name="selectColum[]" disabled value="Section2" id="chksect2"/></td>
                                                                    <td>Section2</td>
                                                                    <td>&nbsp;</td> -->
                                                                    
                                                                </tr>

                                                                <!-- <tr>
                                                                    <td align="center"><input type="checkbox" name="selectColum[]" disabled value="Teacher_Hours" id="chkTeach2"/></td>
                                                                    <td>Teacher Hours</td>
                                                                    <td>&nbsp;</td>  
                                                                </tr> -->
                                                            </table>					
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <!--End Select Colum-->

                                            <!--Query Save-->
                                            <div id="querySave" class="contenttab">
                                                <ul id="itemContainer">
                                                    <li>
                                                        <div class="productsItemBoxText">
                                                            <div style="overflow:scroll; width:922px; height:211px;">
                                                                <?php
                                                                $sql = "SELECT ID, NIC, QueryName, Query FROM TG_QuerySave WHERE (NIC = N'$nicNO')";

                                                                $stmt = $db->runMsSqlQuery($sql);
                                                                while ($row = sqlsrv_fetch_array($stmt)) {
                                                                    echo "<div id=\"" . $row["QueryName"] . "\" style=\"border-bottom: 1px solid #000; cursor: pointer;\" onClick=\"SaveQueryForm(this.id);\">" . $row["QueryName"] . "</div>";
                                                                }
                                                                ?>

                                                            </div>			
                                                            <div style="margin-top: 15px;">                                                
                                                                <input type="hidden" name="hidquerySave" id="hidquerySave" value=""/>
                                                                <input type="hidden" name="hidqueryName" id="hidqueryName" value=""/>
                                                            </div>
                                                        </div>

                                                    </li>
                                                </ul>
                                            </div>
                                            <!-- Query Save -->

                                        </div>

                                    </div> <!-- midArea -->
                                    <!-- <input type="hidden" value="" name="txtLoggedUser"/>
                                    <input type="hidden" value="" name="txtAccessLevel"/>
                                    <input type="hidden" value="" name="txtAccessLevel"/> -->

                                </div>

                                <div style="border-bottom:1px solid #666; width: 95%; float:left; margin:20px;  margin-top: 30px;"></div>

                                <div class="containerHeaderTwo">
                                    <div style="margin-top:8px; margin-left:42px;">Select a Report Type :</div>
                                    <div style="margin-top:8px; margin-left:42px;">
                                        <input type="radio" name="reportT" id="reportT" value="DR" onclick="unchekedBoxes();">Detail Report<br>
                                        <input type="radio" name="reportT" id="reportT" value="SR">Summary Report<br>
                                        <input type="checkbox" name="rExportXLS" id="rExportXLS" value="XLS" onclick="hideEmailReport();">Export to Excel<br>
                                        <input type="checkbox" name="rSaveQuery" id="rSaveQuery" value="RSQ" onClick="loadQNameDiv();">Save Query<br>
                                    </div>
                                    <div style="margin-left:15px; margin-top:10px; width:800px;" id="divRptHedding">
                                        <label style="margin-top: 20px;">Please enter a report heading :&nbsp;</label>
                                        <input type="text" style="width: 400px;" maxlength="80" value="" name="txtRptHedding" id="txtRptHedding"/>
                                    </div>
                                    <div style="display: none; margin-left:15px; margin-top:5px" id="hiddenVal">
                                        <label style="color: red; margin-top: 20px; width:200px;">Please enter a email address :&nbsp;</label>
                                        <input type="text" style="width: 200px;" value="<?php echo $emailaddr; ?>" name="txtemailAddress" id="txtemailAddress"/>
                                    </div>
                                    <div style="display: none; margin-left:15px; margin-top:5px" id="hiddenQName">
                                        <label style="color: red; margin-top: 20px; width:200px;">Please enter a query save name :&nbsp;</label>
                                        <input type="text" style="width: 200px;" value="" name="txtquerySaveName" id="txtquerySaveName"/>
                                    </div>

                                    <input type="button" class="report" name="genPDF" id="genPDF" value="Print Report" onClick="submitForm('report');"/>
                                    <input type="button" class="report" name="saveQuery" style="display: none; margin-left:30px;" id="saveQuery" value="Query Save" onClick="submitForm('Qsave');"/>
                                    <!--<input type="button" name="genPDF" id="genPDF" value="Summary Report" onClick="submitForm('generateSummaryPDF.php');"/>-->
                                    <input type="button" class="report" name="genEmail" id="genEmail" value="Email Report" onClick="submitForm('mail');"/>
                                    <div style="margin-top: 20px; float: left; margin-left:15px;">
                                        <label>Note : The report you are trying to view may take few minutes to load.</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mcib_bottom"></div>
                        </div>


                    </div>
                </div>
                <?php // added select box handling
                    // $sql = "SELECT StaffServiceHistory.InstCode FROM StaffServiceHistory INNER JOIN
                    // TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef INNER JOIN 
                    // CD_CensesNo ON CD_CensesNo.CenCode = StaffServiceHistory.InstCode
                    // WHERE (StaffServiceHistory.NIC = @LOGGEDUSERID)";
                    // // $stmt = $db->runMsSqlQuery($sql);

                    // $ZONECODE = $stmt[''];


                    // if($loggedPositionName = 'NEMIS Provincial Coordinator'){
                      
                    // }
                ?> 
                
                <!--/ container-->
                <!-- End Page Content -->
            </div>
        </form>
    </body>
</html>
