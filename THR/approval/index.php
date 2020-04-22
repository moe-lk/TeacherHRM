<?php
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
if ($_SESSION['NIC'] == '') {
    header("Location: ../index.php");
    exit();
}
include '../db_config/DBManager.php';
include("../db_config/my_functions.php");
date_default_timezone_set("Asia/Colombo");
$db = new DBManager();
$currDate = date('Y-m-d');

$timezone = "Asia/Colombo";
if (function_exists('date_default_timezone_set'))
    date_default_timezone_set($timezone);

if ($_SESSION['timeout'] + 60 * 60 < time()) {
    session_unset();
    session_destroy();
    session_start();
    header("Location: ../index.php");
    exit();
}
$_SESSION["timeout"] = time();


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


if ($pageid == '')
    $pageid = "0";
//echo $pageid;

$nicNO = str_replace(" ", "", $_SESSION["NIC"]);
$NICUser = str_replace(" ", "", $_SESSION["NIC"]);
$loggedSchool = $_SESSION['loggedSchool'];
$loggedPositionName = $_SESSION['loggedPositionName'];
$accLevel = $_SESSION["accLevel"];
$ProCodeU = $_SESSION["ProCodeU"];

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

$url = (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] : "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$exUrl = explode('/', $url);
$folderLocation = count($exUrl) - 2;
$ModuleFolder = $exUrl[$folderLocation];
?>

<!DOCTYPE html>
<html>

<head>

    <link rel="icon" type="image/png" href="images/favicon.png">


    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title>National Education Management Information System | Ministry of Education Sri Lanka</title>
    <!--<link href="css/emis.css" rel="stylesheet" type="text/css">-->
    <link href="css/emis.css" rel="stylesheet" type="text/css">
    <link href="../css/mStyle.css" rel="stylesheet" type="text/css" />
    <link href="css/category_tab.css" rel="stylesheet" type="text/css" />
    <!--<link href="../cms/css/main_menu1.css" rel="stylesheet" type="text/css" />-->
    <link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
    <link href="../cms/css/grid_style.css" rel="stylesheet" type="text/css" />
    <link href="../cms/css/flexigrid.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
        /*menu style*/



        .mcib_top {
            width: 960px;
            height: 33px;
            float: left;
            padding: 1px 10px 1px 10px;
            font-size: 12px;
            color: #FFF;
            font-weight: bold;
            line-height: 34px;
            background: url(<?php echo $theamPath ?><?php echo $theam ?>/backgrounds/block_inner_back.png);
        }

        .link1 {
            color: <?php echo $theamMenuFontColor ?>;
            cursor: pointer;
        }
    </style>


    <script src="js/jquery-1.9.1.js"></script>
    <script src="js/jquery.tabify.js" type="text/javascript" charset="utf-8"></script>
    <script src="js/FilterDB.js" language="javascript"></script>
    <script type="text/javascript">

    </script>
    <script type="text/javascript">
        // IE9 fix
        if (!window.console) {
            var console = {
                log: function() {},
                warn: function() {},
                error: function() {},
                time: function() {},
                timeEnd: function() {}
            }
        }
    </script>
    <script language="javascript" type="text/javascript">
        function popitup(url) {

            newwindow = window.open(url, 'name', 'height=700,width=1000,scrollbars=yes');

            if (window.focus) {
                newwindow.focus()
            }

            return false;

        }
    </script>
</head>

<body>
    <!-- Begin Page Content -->

    <div class="container">
        <form id="form1" name="form1" action="" method="POST">
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
        </form>

        <div id="main_content_outer">
            <div id="main_content_inner">

                <div class="main_content_inner_block">
                    <?php include('../mainmenu.php') ?>
                    <div class="mcib_middle">

                        <div class="containerHeaderOne">



                            <div style="width:960px; height:auto; float:left; margin-left:10px;">
                                <?php
                                include("index1.php");

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

</body>

</html>