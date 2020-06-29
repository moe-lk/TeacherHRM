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


$url = (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] : "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$exUrl = explode('/', $url);
$folderLocation = count($exUrl) - 2;
$ModuleFolder = $exUrl[$folderLocation];
$CenCodex = trim($_SESSION['loggedSchool']);
$nicNO = $_SESSION["NIC"];

$loggedPositionName = $_SESSION['loggedPositionName'];
$accLevel = trim($_SESSION["accLevel"]);
$AccessRoleType = $_SESSION['AccessRoleType'];
//$nicNO = '791231213V';
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

// $LOGGEDUSERID = $nicNO; 
// $ACCESSLEVEL = "";
// $DIVCODE = '';
// $ZONECODE = '';
// $DISTCODE = '';
// $ProCode = '';
// $Province = null;
// $District = null;
// $Division = null;
// $SCType = null;

// var_dump($_SESSION);
$sql = "SELECT Passwords.NICNo, Passwords.AccessLevel, TeacherMast.emailaddr, TeacherMast.Title, CD_Title.TitleName + TeacherMast.SurnameWithInitials AS name, CD_AccessRoles.AccessRoleType FROM Passwords INNER JOIN TeacherMast ON Passwords.NICNo = TeacherMast.NIC INNER JOIN CD_Title ON TeacherMast.Title = CD_Title.TitleCode INNER JOIN CD_AccessRoles ON Passwords.AccessLevel = CD_AccessRoles.AccessRoleValue WHERE (Passwords.NICNo = N'$nicNO')";
$stmt1 = $db->runMsSqlQuery($sql);

while ($row = sqlsrv_fetch_array($stmt1)) {
    $_SESSION["AccessLevel"] = $row["AccessLevel"];
    $ACCESSLEVEL = $row["AccessLevel"];
    $emailaddr = $row["emailaddr"];
    $_SESSION["fullName"] = $row["name"];
    $accessRoleType = trim($row["AccessRoleType"]);
}

// Teacher and Principal
if ($AccessRoleType == "SC") {
    $CenCodex = trim($_SESSION['loggedSchool']);
    $detailSql = "SELECT CD_CensesNo.CenCode, CD_CensesNo.DistrictCode, CD_CensesNo.ZoneCode, CD_CensesNo.DivisionCode, CD_Districts.ProCode FROM CD_CensesNo INNER JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode WHERE (CD_CensesNo.CenCode = N'$CenCodex')";
    $stmt = $db->runMsSqlQuery($detailSql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $ProCodex = trim($row['ProCode']);
    $DistrictCodex = trim($row['DistrictCode']);
    $ZoneCodex = trim($row['ZoneCode']);
    $DivisionCodex = trim($row['DivisionCode']);
    $disaTxt = "disabled";
} 
else if ($AccessRoleType == "ED") {
    $restZone = substr($CenCodex, -4, 4);
    $divCodeLoged = "ED" . $restZone;
    $sql = "SELECT CD_Division.CenCode, CD_Division.DistrictCode, CD_Division.ZoneCode, CD_Districts.ProCode FROM CD_Division INNER JOIN CD_Districts ON CD_Division.DistrictCode = CD_Districts.DistCode WHERE (CD_Division.CenCode = N'$divCodeLoged')";
    $stmt = $db->runMsSqlQuery($sql);
    $rowA = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $ZoneCodex = strtoupper(trim($rowA['ZoneCode']));
    $ProCodex = strtoupper(trim($rowA['ProCode']));
    $DistrictCodex = strtoupper(trim($rowA['DistrictCode']));
    $DivisionCodex = $divCodeLoged;
    $CenCodex = "";
    $disaTxtDiv = "disabled";
} 
else if ($AccessRoleType == "ZN") {
    $restZone = substr($CenCodex, -4, 4);
    $zoneCodeLoged = "ZN" . $restZone;
    $sql = "SELECT * from CD_Provinces Where ProCode='$ProCodex'";
    $stmt = $db->runMsSqlQuery($sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $ProName = trim($row['Province']);

    $detailSql = "SELECT CD_CensesNo.CenCode, CD_CensesNo.DistrictCode, CD_CensesNo.ZoneCode, CD_CensesNo.DivisionCode, CD_Districts.ProCode, CD_Districts.DistName FROM CD_CensesNo INNER JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode WHERE (CD_CensesNo.CenCode = N'$CenCodex')";
    $stmt = $db->runMsSqlQuery($detailSql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $ProCodex = trim($row['ProCode']);

    $DistrictCodex = trim($row['DistrictCode']);
    $DistrictName = trim($row['DistName']);
    var_dump($DistrictName);
    $ZoneCodex = $zoneCodeLoged;
    $CenCodex = "";
    $disaTxt = "disabled";
} 
else if ($AccessRoleType == "DN") {
    $restDistrict = substr($CenCodex, -4, 2);
    $DistrictCodex = "D" . $restDistrict;
    $sql = "SELECT ProCode from CD_Districts Where DistCode='$DistrictCodex'";
    $stmt = $db->runMsSqlQuery($sql);
    $rowA = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $ProCodex = strtoupper(trim($rowA['ProCode']));
    $CenCodex = "";
    $disaTxtPro = "disabled";
    $disaTxtDis = "disabled";
} 
else if ($AccessRoleType == "PD") {
    $ZoneCodex = "";
    $rest = substr($CenCodex, -3, 1);
    $ProCodex = "P0" . $rest;
    var_dump($ProCodex);
    // $sql = "SELECT * from CD_Provinces Where ProCode='$ProCodex'";
    // $stmt = $db->runMsSqlQuery($sql);
    // $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $ProName = trim($row['Province']);
    $disaTxtPro = "disabled";
    $CenCodex= '';
} 
else {
    // $ProCodex = "P01";
    $disaTxt = "";
    $disaTxtDiv = "";
    $disaTxtPro = "";
    $disaTxtDis = "";
}

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

// var_dump($zoneCodeLoged);

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="icon" type="image/png" href="images/favicon.png">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <!-- <title>National Education Management Information System | Ministry of Education Sri Lanka</title>  -->
        <title>National Education Management Information System | Ministry of Education Sri Lanka</title> 
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
            .select2a{
                width:260px;
            }
        </style>
        <script src="js/jquery-1.9.1.js"></script>
        <script src="js/jquery.tabify.js" type="text/javascript" charset="utf-8"></script>
        <script src="js/teacherFilter.js" language="javascript"></script>
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
        <script src="selectpage.js" type="text/javascript"></script>
        
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
                                    <div class="midArea">
                                        <div class="productsAreaRight">
                                            <ul id="menu" class="menu">
                                                <li class="active"><a href="#geographical">Geographical</a></li>
                                                <li><a href="#biographical">Biographical</a></li>
                                                <li><a href="#qualifications">Qualifications</a></li>   
                                                <li><a href="#teaching">Teaching</a></li>
                                                <li><a href="#service">Service</a></li>
                                                <li><a href="#outOfService">Out of Service</a></li>
                                                <li><a href="#columns">Columns</a></li>
                                                <li><a href="#querySave">Saved Query</a></li>
                                            </ul>
<!--geographical tab --><!--geographical tab --><!--geographical tab --><!--geographical tab --><!--geographical tab --><!--geographical tab --><!--geographical tab --><!--geographical tab --><!--geographical tab -->                                            
                                            <div id="geographical" class="contenttab">
                                                <ul id="itemContainer">
                                                    <li>                                           
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
                                                                        <?php //School Type //School Type //School Type //School Type //School Type //School Type //School Type                                 
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
                                                                <div class="divSimple">
                                                                    <select class="select2a_n1" style="width:260px;" id="ProCode" name="ProCode" onchange="Javascript:show_district('districtList', this.options[this.selectedIndex].value, ''); " <?php echo $disaTxt ?><?php echo $disaTxtPro ?><?php echo $disaTxtDiv ?>>
                                                                        <?php //Province //Province //Province //Province //Province //Province //Province //Province
                                                                            $sql = "SELECT ProCode,Province FROM CD_Provinces order by Province asc";
                                                                            $stmt = $db->runMsSqlQuery($sql);
                                                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                                $ProCoded = trim($row['ProCode']);
                                                                                $ProNamed = $row['Province'];
                                                                                $seltebr = "";
                                                                                if ($ProCoded == $ProCodex) {
                                                                                    $seltebr = "selected";
                                                                                }
                                                                                if($ProCoded == '' && $ProCodex == ''){
                                                                                    echo "<option value=\"\" disabled selected hidden>Select Province</option>";
                                                                                }
                                                                                else{
                                                                                    echo "<option value=\"$ProCoded\" $seltebr>$ProNamed</option>";
                                                                                }
                                                                                
                                                                            }
                                                                            
                                                                        ?>
                                                                    </select>
                                                                    <?php // var_dump( trim($ProCodex)); ?>
                                                                    <?php // var_dump( trim($ProCoded)); ?>
                                                                    <?php
                                                                        // $sql = "SELECT ProCode,Province FROM CD_Provinces order by Province asc";
                                                                        // $stmt = $db->runMsSqlQuery($sql);
                                                                        // while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                        //     $DistCoded = trim($row['ProCode']);
                                                                        //     $DistNamed = $row['Province'];
                                                                        //     echo $DistCoded . $DistNamed. "<br>";
                                                                        // }
                                                                    ?>
                                                                    <!-- <script> console.log(ProCode.value); </script> -->
                                                                    
                                                                    <input type="checkbox" name="groupBy[]" id="chkProvince" value="PRO">
                                                                </div>

                                                                <div class="divSimple"  id="txt_district">
                                                                    <select class="select2a_n1" style="width:260px;" id="DistrictCode" name="DistrictCode" onchange="Javascript:show_zone('zonelist', this.options[this.selectedIndex].value, ''); loadAccordingToDistrict();" <?php echo $disaTxt ?><?php echo $disaTxtDiv ?> <?php echo $disaTxtDis ?>>
                                                                        <?php //District //District //District //District //District //District //District //District //District
                                                                            $sql = "SELECT DistCode,DistName FROM CD_Districts where ProCode='$ProCodex' order by DistName asc"; //x
                                                                            $stmt = $db->runMsSqlQuery($sql);

                                                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                                $DistCoded = trim($row['DistCode']); //x
                                                                                $DistNamed = $row['DistName']; // 
                                                                                $seltebr = "";
                                                                                if ($DistCoded == $DistrictCodex) {
                                                                                    $seltebr = "selected";
                                                                                }
                                                                                if($DistCoded== '' || $DistNamed == null){
                                                                                    
                                                                                    echo "<option value=\"\" disabled selected hidden>Select District</option>";   
                                                                                }
                                                                                
                                                                                
                                                                            }
                                                                            
                                                                         ?>
                                                                    </select>
                                                                    
                                                                    <input type="checkbox" name="groupBy[]" id="chkDistrict" value="DIS">
                                                                </div>
                                                            </div>
                                                               <?php // var_dump($DistNamed); ?>
                                                            <div>
                                                                <label for="username" class="labelTxt" style="margin-left:22px;"><strong>Zone :</strong></label>
                                                                <label for="username" class="labelTxt"><strong>Division :</strong></label>
                                                                <label for="username" class="labelTxt">&nbsp;</label>
                                                            </div>

                                                            <div>
                                                                <?php 
                                                                    // var_dump($ZoneCodex);
                                                                    // var_dump($row['CenCode']);
                                                                ?>
                                                                <div class="divSimple" style="margin-left:22px;"  id="txt_zone">
                                                                    <select  class="select2a_n1" style="width:260px;" id="ZoneCode" name="ZoneCode" onchange="Javascript:show_division('divisionList', this.options[this.selectedIndex].value, document.frmSave.DistrictCode.value); loadAccordingToZone();" <?php echo $disaTxt ?><?php echo $disaTxtDiv ?>>
                                                                        <?php //Zone //Zone //Zone //Zone //Zone //Zone //Zone //Zone //Zone //Zone //Zone //Zone //Zone //Zone //Zone //Zone 
                                                                        echo $DistrictCodex;
                                                                        $sql = "SELECT CenCode,InstitutionName FROM CD_Zone where DistrictCode='$DistrictCodex' order by InstitutionName asc";
                                                                        $stmt = $db->runMsSqlQuery($sql);
                                                                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                            $DSCoded = trim($row['CenCode']);
                                                                            $DSNamed = $row['InstitutionName'];
                                                                            $seltebr = "";
                                                                            if ($DSCoded == null) {
                                                                                $seltebr = "selected";
                                                                            }
                                                                            if($DSCoded==""){
                                                                               echo "<option value=\"\" selected>Zone Name</option>";
                                                                            }else{
                                                                               echo "<option value=\"$DSCoded\" $seltebr>$DSNamed</option>"; 
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                    <?php
                                                                    // $sql = "SELECT CenCode,InstitutionName FROM CD_Zone where DistrictCode='$DistrictCodex' order by InstitutionName asc";
                                                                    // $stmt = $db->runMsSqlQuery($sql);
                                                                    // while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                    //     $DSCoded = trim($row['CenCode']);
                                                                    //     var_dump($DSCoded);
                                                                    //     // var_dump($disaTxtPro);
                                                                    //     // var_dump($disaTxtDiv);
                                                                    // }
                                                                    ?>
                                                                    <input type="checkbox" name="groupBy[]" id="chkZone" value="ZON">
                                                                </div>
                                                                <div class="divSimple"  id="txt_division">
                                                                    <select class="select2a_n1" style="width:260px;" id="DivisionCode" name="DivisionCode" onchange="Javascript:show_cences('censesList', this.options[this.selectedIndex].value, document.frmSave.DistrictCode.value); loadAccordingToDivision();" <?php echo $disaTxtDiv ?>>
                                                                        <option value="">Division Name</option>
                                                                        <?php //Division //Division //Division //Division //Division //Division //Division //Division //Division //Division 
                                                                        $sql = "SELECT CenCode,InstitutionName FROM CD_Division where DistrictCode='$DistrictCodex' and ZoneCode='$ZoneCodex' order by InstitutionName asc";
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
                                                                    <?php
                                                                        
                                
                                                                    ?>
                                                                    <input type="checkbox" name="groupBy[]" id="chkDivision" value="DIV">
                                                                </div> 
<!-- Start of school status --><!-- Start of school status --><!-- Start of school status --><!-- Start of school status --><!-- Start of school status -->
                                                            <div>
                                                                <label for="username" class="labelTxt" style="margin-left:22px;"><strong>School status:</strong></label>
                                                            </div>
                                                                <div class="divSimple" style="margin-left:22px; width:100%;">
                                                                    <select style="width:260px;" class="selectRpt" id="cmbSchoolStatus" name="cmbSchoolStatus" onchange="loadAccordingToSCStatus();">
                                                                        <option value="">All</option>
                                                                        <option value="Y">Functioning</option>
                                                                        <option value="N">Not Functioning</option>
                                                                    </select>
                                                                    <input type="checkbox" name="groupBy[]" id="chkStat" value="FUN">
                                                                </div>
                                                            </div>                           
<!-- End of school status --><!-- End of school status --><!-- End of school status --><!-- End of school status --><!-- End of school status --><!-- End of school status -->
                                                            </div>
                                                            <div>
                                                                <label for="username" class="labelTxt" style="margin-left:22px;"><strong>School :</strong></label>
                                                            </div>

                                                            <div>
                                                                <div class="divSimple" style="margin-left:22px; width:880px;" id="txt_showInstitute">
                                                                    <select style="width:853px;" id="InstCode" name="InstCode" onchange="disableCheckBox();">
                                                                        <option value="">School Name</option>
                                                                        <?php //School //School //School //School //School //School //School //School //School //School 
                                                                            $DivisionCode = "abc";
                                                                            if ($AccessRoleType == "ZN") {
                                                                                $sql = "SELECT [InstType],[CenCode],[InstitutionName],[DistrictCode],[RecordLog],[ZoneCode],[DivisionCode],[IsNationalSchool],[SchoolType] FROM [dbo].[CD_CensesNo] where DivisionCode='$DivisionCodex' order by InstitutionName";
                                                                            } else {
                                                                                $sql = "SELECT [InstType],[CenCode],[InstitutionName],[DistrictCode],[RecordLog],[ZoneCode],[DivisionCode],[IsNationalSchool],[SchoolType] FROM [dbo].[CD_CensesNo] where CenCode='$CenCodex' order by InstitutionName";
                                                                            }
                                                                            $stmt = $db->runMsSqlQuery($sql);
                                                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                                $CenCode = trim($row['CenCode']);
                                                                                $InstitutionName = addslashes($row['InstitutionName']);
                                                                                $seltebr = "";
                                                                                if ($CenCode == $CenCodex) {
                                                                                    $seltebr = "selected";
                                                                                }
                                                                                echo "<option value=\"$CenCode\" $seltebr>$InstitutionName $CenCode</option>";
                                                                            }
                                                                        ?>                              
                                                                    </select>
                                                                    <input type="checkbox" name="groupBy[]" id="chkSchool" value="SCH">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <?php 
                                                
                                            ?>
                                    
<!--End geographical tab --><!--End geographical tab --><!--End geographical tab --><!--End geographical tab --><!--End geographical tab --><!--End geographical tab --><!--End geographical tab -->

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
                                                                    $sql = "SELECT BioCoulmName, BioColumValue, BioColumID FROM TG_QuerySaveBiography WHERE (ID = '$saveQID')";

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
																  Qcode,
																  Description
																FROM CD_Qualif
																WHERE (Qcode <> N'')
																ORDER BY Description";
                                                                            $stmt = $db->runMsSqlQuery($sql);
                                                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                                echo '<option value=' . $row['Qcode'] . '>' . $row['Description'] . '</option>';
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
                                                                    <td width="205" align="center" bgcolor="#FFFFFF"><strong>Type</strong></td>
                                                                    <td width="311" align="center" bgcolor="#FFFFFF"><strong>Subject</strong></td>
                                                                    <td width="262" align="center" bgcolor="#FFFFFF"><strong>Grade</strong></td>
                                                                    <td width="43" align="center" bgcolor="#FFFFFF"><strong>Remove</strong></td>
                                                                </tr>
                                                                <?php
                                                                if ($querySaveVal != "") {
                                                                    $sql = "SELECT  CD_SecGrades.GradeName, CD_Subject.SubjectName, CD_SubjectTypes.SubTypeName
FROM            TG_QuerySaveTeaching INNER JOIN
                         CD_Subject ON TG_QuerySaveTeaching.SubCode = CD_Subject.SubCode INNER JOIN
                         CD_SubjectTypes ON TG_QuerySaveTeaching.SubType = CD_SubjectTypes.SubType INNER JOIN
                         CD_SecGrades ON TG_QuerySaveTeaching.GradeCode = CD_SecGrades.GradeCode
WHERE        (TG_QuerySaveTeaching.ID = '$saveQID')";

                                                                    $stmt = $db->runMsSqlQuery($sql);
                                                                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                        echo "<tr>";
                                                                        echo "<td width=\"205\" bgcolor=\"#FFFFFF\">" . $row["SubTypeName"] . "</td>";
                                                                        echo "<td width=\"311\" bgcolor=\"#FFFFFF\">" . $row["SubjectName"] . "</td>";
                                                                        echo "<td width=\"262\" bgcolor=\"#FFFFFF\">" . $row["GradeName"] . "</td>";
                                                                        echo "<td width=\"43\" align=\"center\" bgcolor=\"#FFFFFF\"><img src=\"images/trash.png\" width=\"14\" height=\"14\" onclick=\"rmvRow(this);\"/></td>";
                                                                        echo "</tr>";
                                                                    }
                                                                }
                                                                ?>

                                                            </table>


                                                            <!--Item selected area-->
                                                            <div class="divSimple" style="margin-left:0px; margin-top: 10px; width:auto;">
                                                                <label style="float:left; width:150px;"><strong>Type :</strong></label>
                                                                <label style="float:left; width:451px;"><strong>Subject :</strong></label>
                                                                <label style="float:left; width:200px;"><strong>Grade :</strong></label>

                                                                <div style="float:left; margin-right:30px; margin-top:8px; width:auto;">
                                                                    <div style="width:150px; float:left;">
                                                                        <select style="width: auto;" id="teachingType" name="teachingType" onchange="">
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
                                                                        </select>
                                                                    </div>
                                                                    <div style="width:451px; float:left;">
                                                                        <select style="width: auto;" id="teachingSubject" name="teachingSubject" onchange="">
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
                                                                        </select>
                                                                    </div>
                                                                    <div style="width:200px; float:left;">
                                                                        <select style="width: auto;" id="teachingGrade" name="teachingGrade" onchange="">
                                                                            <option value="">All</option>
                                                                            <?php
                                                                            $sql = "SELECT GradeCode, LTRIM(GradeName) AS GradeName
																FROM CD_SecGrades
																WHERE (GradeCode <> N'')
																ORDER BY GradeName";
                                                                            $stmt = $db->runMsSqlQuery($sql);
                                                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                                                echo '<option value=' . $row['GradeCode'] . '>' . $row['GradeName'] . '</option>';
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                    </div>
                                                                    <div style="float:left;">
                                                                        <img src="images/add_b.png" width="48" height="19" onClick="addRowToTeachingtbl();"/>
                                                                    </div>
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
                                            
                                            <!--serviceType tab -->
                                            <div id="outOfService" class="contenttab">
                                                <ul id="itemContainer">
                                                    <li>
                                                        <div class="productsItemBoxTextxxx">
                                                            <!--Item selected area-->
                                                            <div class="divSimple" style="margin-left:0px; margin-top: 10px; width:auto;">
                                                            
                                                                <label style="float:left; width:200px;"><strong>Resigned :</strong></label>
                                                                <label style="float:left; width:200px;"><strong>Dismissed :</strong></label>
                                                                <label style="float:left; width:200px;"><strong>Retired :</strong></label>
															    <div style="float:left; margin-right:30px; margin-top:8px; width:auto;">
                                                                    <div style="width:200px; float:left;"><input name="resignT" id="resignT" type="checkbox" value="RN01"></div>
                                                                    <div style="width:200px; float:left;"><input name="dissmissedT" id="dissmissedT" type="checkbox" value="DS03"></div>
                                                                    <div style="width:200px; float:left;"><input name="retiredT" id="retiredT" type="checkbox" value="RT01"></div>
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
                                                                    <td width="8%" align="center"><input type="checkbox" name="selectColum[]"  value="Religion" /></td>
                                                                    <td width="25%">Religion</td>
                                                                    <td width="4%">&nbsp;</td>
                                                                    <td width="6%"><input type="checkbox" id="chkQuli" name="selectColum[]" disabled value="Qualification" /></td>
                                                                    <td width="25%">Qualification</td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="center"><input type="checkbox" name="selectColum[]"  value="District" /></td>
                                                                    <td>District</td>
                                                                    <td>&nbsp;</td>
                                                                    <td align="center"><input type="checkbox" name="selectColum[]"  value="Civil" /></td>
                                                                    <td>Civil Status</td>
                                                                    <td>&nbsp;</td>
                                                                    <td><input type="checkbox" name="selectColum[]" disabled value="Teaching" id="chkTeach"/></td>
                                                                    <td>Teaching</td>
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
                                                                </tr>
                                                                <tr>
                                                                    <td align="center"><input type="checkbox" name="selectColum[]"  value="Division" /></td>
                                                                    <td>Division</td>
                                                                    <td>&nbsp;</td>
                                                                    <td align="center"><input type="checkbox" name="selectColum[]"  value="DOB" /></td>
                                                                    <td>DOB</td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="center"><input type="checkbox" name="selectColum[]"  value="Gender" /></td>
                                                                    <td>Gender</td>
                                                                    <td>&nbsp;</td>
                                                                    <td align="center"><input type="checkbox" name="selectColum[]"  value="Mobile" /></td>
                                                                    <td>Mobile No</td>
                                                                    
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="center"><input type="checkbox" name="selectColum[]"  value="Status" /></td>
                                                                    <td>School Status</td> <!--Added school status with checkbox-->
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                    <td>&nbsp;</td>
                                                                </tr>
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
                                    <input type="hidden" value="" name="txtLoggedUser"/>
                                    <input type="hidden" value="" name="txtAccessLevel"/>
                                    <input type="hidden" value="" name="txtAccessLevel"/>

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
                <?php 
                ?> 
                
                <!--/ container-->
                <!-- End Page Content -->
            </div>
        </form>
    </body>
</html>

