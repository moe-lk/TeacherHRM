<?php
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();

if ($_SESSION['NIC'] == '') {
    header("Location: ../index.php");
    exit();
}
if ($_SESSION['timeout'] + 60 * 60 < time()) {
    session_unset();
    session_destroy();
    session_start();
    header("Location: ../index.php");
    exit();
}
$_SESSION["timeout"] = time();

include '../db_config/DBManager.php';
$db = new DBManager();

$timezone = "Asia/Colombo";
if (function_exists('date_default_timezone_set'))
    date_default_timezone_set($timezone);

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



$NICUser = $_SESSION["NIC"];
$loggedSchool = $_SESSION['loggedSchool'];
$loggedPositionName = $_SESSION['loggedPositionName'];
$accLevel = trim($_SESSION["accLevel"]);
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

$url = (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] : "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$exUrl = explode('/', $url);
$folderLocation = count($exUrl) - 2;
$ModuleFolder = $exUrl[$folderLocation];

if (isset($_POST["FrmSubmit"])) {
    $_SESSION["cmbSchoolType"] = $_REQUEST["cmbSchoolType"];
    $_SESSION["cmbProvince"] = $_REQUEST["cmbProvince"];
    $_SESSION["cmbDistrict"] = $_REQUEST["cmbDistrict"];
    $_SESSION["cmbZone"] = $_REQUEST["cmbZone"];
    $_SESSION["cmbDivision"] = $_REQUEST["cmbDivision"];
    $_SESSION["cmbSchool"] = $_REQUEST["cmbSchool"];
    $_SESSION["cmbSchoolStatus"] = $_REQUEST["cmbSchoolStatus"];
   // $_SESSION["cmbPosition"] = $_REQUEST["cmbPosition"];
    $_SESSION["reportT"] = $_REQUEST["reportT"];
    
    /*
    echo ("<SCRIPT LANGUAGE='JavaScript'>	
            window.open('natureofwork-1.html');
	</SCRIPT>");
     * 
     */
    
   // window.open('https://www.codexworld.com', '_blank');
    header("Location:natureofwork-1.html");
    die();
}
?>

<!DOCTYPE html>
<html>
    <head>

        <link rel="icon" type="image/png" href="../favicon.ico">


        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>National Education Management Information System | Ministry of Education Sri Lanka</title>
        <!--<link href="css/emis.css" rel="stylesheet" type="text/css">-->
        <link href="css/emis.css" rel="stylesheet" type="text/css">
        <link href="../css/mStyle.css" rel="stylesheet" type="text/css" />
        <link href="css/category_tab.css" rel="stylesheet" type="text/css" />
        <link href="../cms/css/main_menu1.css" rel="stylesheet" type="text/css" />
        <link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
        <link href="../cms/css/grid_style.css" rel="stylesheet" type="text/css" />
       
        <link rel="stylesheet" type="text/css" href="../css/flexigrid.css">

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
        <link rel="stylesheet" type="text/css" media="all" href="../cms/calendar/calendar-tas.css" title="win2k-2" />
        <script type="text/javascript" src="../cms/calendar/calendar.js"></script>
        <script type="text/javascript" src="../cms/calendar/lang/calendar-en.js"></script>
        <script type="text/javascript" src="../cms/calendar/calendar-setup.js"></script>

<!--        <script src="js/jquery-1.9.1.js"></script>-->
        <script src="../js/jquery.js"></script>
<!--        <script src="selectpage.js"></script>-->
        <script src="js/jquery.tabify.js" type="text/javascript" charset="utf-8"></script>
        <script src="js/FilterDB.js"  language="javascript"></script>
        <script type="text/javascript" src="../js/flexigrid.js"></script>
        
        
        

        <style type="text/css">
            .masterFile {
                /*margin:1em 0;*/
                width:200px; 
                float:left; 
                margin-left:0px; 
                border:thick; 
                border-color:#92495C; /*666*/
                border-style:solid; 
                border-width:1px;
                -webkit-border-radius: 3px;
                -moz-border-radius: 3px;
                border-radius: 3px;
            }
            .masterFile h3 {
                /* background:#09C;*/
                background:#92495C;
                color:#fff;
                cursor:pointer;
                margin:0 0 1px 0;
                padding:4px 10px
            }
            .accordion h3.current {
                background:#4289aa;
                cursor:default
            }
            .accordion div.pane {
                padding:5px 10px
            }

        </style>
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

                                <!--                                <div class="midArea"> 
                                                                    <div class="productsAreaRight">
                                                                        <ul id="menu" class="menu">
                                
                                                                            
                                                                        </ul>
                                                                        <div id="geographical" class="contenttab"></div>
                                
                                                                    </div>
                                
                                                                </div> -->

                                <div style="width:945px; height:auto; float:left; margin-left:10px;">
                                    <?php
                                    if ($pageid == "" || $pageid == "0") {
                                        include_once 'quick_report.php';
                                    }


                                    if ($pageid == 1) {
                                        include('natureofwork.php');
                                    }
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