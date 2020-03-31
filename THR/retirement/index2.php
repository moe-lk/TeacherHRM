<?php
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();
$currDate = date('Y-m-d');


$replace_data = array("'", "/", "!", "&", "*", " ", "-", "@", '"', "?", ":", "“", "”");
$replace_data_new = array("'", "/", "!", "&", "*", " ", "-", "@", '"', "?", ":", "“", "”", ".");
$pageid = $_GET["pageid"];
$menu = $_GET['menu'];
$tpe = $_GET['tpe'];
$id = $_GET['id'];
$fm = $_GET['fm'];
$lng = $_GET['lng'];
$curPage = $_GET['curPage'];
$ttle = $_GET['ttle'];
$ttle = str_replace("_", " ", $ttle);
//str_replace(",","",$amount);
$redirect_page = '../module_main.php';

$url = (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] : "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$exUrl = explode('/', $url);
$folderLocation = count($exUrl) - 2;
$ModuleFolder = $exUrl[$folderLocation];


if ($pageid == '')
    $pageid = "0";
//echo $pageid;

$nicNO = $_SESSION["NIC"];
$loggedSchool = $_SESSION['loggedSchool'];
$loggedPositionName = $_SESSION['loggedPositionName'];

// ***

$showVoluntary = "N";
$showAgeUpon = "N";


$sqlP = "SELECT fVoluntaryYear, fRetirementYear
FROM TG_RetiremntParms";

$stmtP = $db->runMsSqlQuery($sqlP);
while ($rowP = sqlsrv_fetch_array($stmtP, SQLSRV_FETCH_ASSOC)) {
    $fVoluntaryYear = $rowP["fVoluntaryYear"];
    $fRetirementMonth = $rowP["fRetirementYear"];
    // remove six month from fRetirementYear
    $fRetirementMonth = $fRetirementMonth - 6;
}
// *** calculate total years afetr remove six months
$years = $fRetirementMonth / 12;
$fRetirementYear = round($years, 0, PHP_ROUND_HALF_DOWN);
$remainder = $fRetirementMonth % 12;
$parmRetirementYear = $fRetirementYear . "." . $remainder;
// **
// *** get employed age
$sqlAChk = "SELECT ID, NIC, CONVERT(varchar(20),DOB,121) AS DOB
FROM MOENational.dbo.TeacherMast
WHERE (NIC = N'$nicNO')";
$stmt = $db->runMsSqlQuery($sqlAChk);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$bDay = $row["DOB"];
$d1 = new DateTime($currDate);
$d2 = new DateTime($bDay);
$diff = $d2->diff($d1);
$empAge = $diff->format('%y.%m');

if ($empAge >= $parmRetirementYear) {
    $showAgeUpon = "Y";
} else {
    $sqlChk = "SELECT ID, NIC, ServiceRecTypeCode, CONVERT(varchar(20),AppDate,121) AS AppDate
FROM StaffServiceHistory
WHERE (NIC = N'$nicNO') AND (ServiceRecTypeCode = N'NA01')";
// 513502036V / 565930915V
    $totRow = $db->rowCount($sqlChk);
    $stmt = $db->runMsSqlQuery($sqlChk);
    if ($totRow > 0) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $fAppDate = $row['AppDate'];
            $d1 = new DateTime($currDate);
            $d2 = new DateTime($fAppDate);

            $diff = $d2->diff($d1);
            //$workedYears = $diff->y;
            $totWorked = $diff->format('%y.%m');
            
            // *** calculate total years of voluntary parameter                
                $VolYears = $fVoluntaryYear / 12;
                $fVoluntaryYear = round($VolYears, 0, PHP_ROUND_HALF_DOWN);
                $remainder = $fVoluntaryYear % 12;
                $parmVoluntaryYear = $fVoluntaryYear . "." . $remainder;
            // **
                
            if($totWorked>=$parmVoluntaryYear){
                $showVoluntary = "Y";
            }else{                
                $_SESSION['error_msg'] = "You don’t have permission to access. <br>Your service is less than 20 years.";
                header("Location:$redirect_page");                
            }
            
            
        }
    } else {
        $_SESSION['error_msg'] = "You don’t have permission to access. <br>You haven't fill your service information.";
        header("Location:$redirect_page");
    }
}

// **


// *** end
// check retirement Age upon
// *** end

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
?>

<!DOCTYPE html>
<html>
    <head>

        <link rel="icon" 
              type="image/png" 
              href="images/favicon.png">


        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>EMIS System</title>
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
                height:30px;
                float:left;
                padding:5px 10px 5px 10px;
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
        <script src="js/FilterDB.js"  language="javascript"></script>
        <script type="text/javascript">

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
        <script language="JavaScript">

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

                <div id="header_outer" style="background:url(images/menu_back.gif) repeat-x">
                    <div id="header_inner">
                        <div id="header_top">
                            <div class="header_top_left">&nbsp;&nbsp; </div>
                            <div class="header_top_right">
                                <div id="admin_button" style="cursor:default;"><a href="#" id="admin_link" style="cursor:default;"><span style="cursor:default;"><?php echo $loggedPositionName; ?></span></a></div>
                                <!-- <div id="mail_button"><a href="#"></a><div id="mail_alert" class="alert">05</div></div>-->
                                <div id="user_welcome">Welcome <?php echo $_SESSION["fullName"]; ?>, &nbsp;&nbsp; <span class="link1" onClick="logoutForm('mail');">Logout?</span></div>
                            </div>
                        </div>
                        <div id="header_logo" style="margin-top:0px;"><img src="images/header.png" width="960" height="150" /></div>

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
                                                <?php
//$showVoluntary = 'Y';
//$showAgeUpon = 'N';

                                                if ($showAgeUpon == "Y") {
                                                    ?>
                                                    <li <?php if ($pageid == 1 || $pageid == 0) { ?>class="active"<?php } ?>><a href="retirementType-1.html">Age Upon<input type="hidden" name="hRetirementType" value="AU"/></a></li>
                                                    <?php
                                                }
                                                ?>

                                                <?php
                                                if ($showVoluntary == "Y") {
                                                    ?>
                                                    <li <?php if ($pageid == 1 || $pageid == 0) { ?>class="active"<?php } ?>><a href="retirementType-1.html">Voluntary<input type="hidden" name="hRetirementType" value="VL"/></a></li>
                                                    <?php
                                                }
                                                ?>
                                                <?php
                                                // show or hide status tab

                                                $sqlC = "SELECT id
FROM TG_Request_Approve
WHERE (RequestUserNIC = N'$nicNO')";
                                                $totRows = $db->rowCount($sqlC);
                                                if ($totRows > 0) {
                                                    ?>

                                                    <li <?php if ($pageid == 2) { ?>class="active"<?php } ?>><a href="retirementStatus-2.html">Status</a></li>
                                                    <?php
                                                }
                                                ?>



                                            </ul>
                                            <div id="geographical" class="contenttab"></div>
                                        </div>
                                    </div> 

                                    <div style="width:945px; height:auto; float:left; margin-left:10px;">
                                        <?php
                                        if ($pageid == 1 || $pageid == 0)
                                            include('retirementType.php');
                                        if ($pageid == 2)
                                            include('retirementStatus.php');
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="mcib_bottom"></div>
                        </div>
                    </div>
                </div>








                <!--/ container-->
                <!-- End Page Content -->
            </div>
        </form>
    </body>
</html>
