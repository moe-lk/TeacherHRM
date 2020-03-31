<?php
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
include("../db_config/my_functions.php");
date_default_timezone_set("Asia/Colombo");
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


if ($pageid == '')
    $pageid = "0";
//echo $pageid;

$nicNO = str_replace(" ","",$_SESSION["NIC"]);
$NICUser = str_replace(" ","",$_SESSION["NIC"]);
$loggedSchool = $_SESSION['loggedSchool'];
$loggedPositionName = $_SESSION['loggedPositionName'];
$accLevel=$_SESSION["accLevel"];


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

$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
$exUrl=explode('/',$url);
$folderLocation=count($exUrl)-2;
$ModuleFolder=$exUrl[$folderLocation];
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
        <!--<link href="../cms/css/main_menu1.css" rel="stylesheet" type="text/css" />-->
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
				<form id="form1" name="form1" action="" method="POST">
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
				</form>

                <div id="main_content_outer">
                    <div id="main_content_inner">

                        <div class="main_content_inner_block">
                             <?php include('../mainmenu.php')?>
                            <div class="mcib_middle">

                                <div class="containerHeaderOne">

                                    <div class="midArea"> 
                                        <div class="productsAreaRight">
                                            <ul id="menu" class="menu">
												 <li <?php if ($pageid == 16 || $pageid == 0) { ?>class="active"<?php } ?>><a href="newRegistration-16.html">New Registration</a></li>

                                                <li <?php if ($pageid == 1 || $pageid == 3) { ?>class="active"<?php } ?>><a href="retirementRequest-1.html">Retirement</a></li>
                                                <li <?php if ($pageid == 2) { ?>class="active"<?php } ?>><a href="leaveRequest-2.html">Leave</a></li>
                                                <li <?php if ($pageid == 4) { ?>class="active"<?php } ?>><a href="transferTeacherNormal-4.html">Transfer Teacher Normal</a></li>
                                                <li <?php if ($pageid == 5) { ?>class="active"<?php } ?>><a href="transferPrincipleNormal-5.html">Transfer Principle Normal</a></li>
                                                <li <?php if ($pageid == 6) { ?>class="active"<?php } ?>><a href="transferTeacherNational-6.html">Transfer Teacher National</a></li>
                                                <li <?php if ($pageid == 7) { ?>class="active"<?php } ?>><a href="transferPrincipleNational-7.html">Transfer Principle National</a></li>
                                                <li <?php if ($pageid == 8) { ?>class="active"<?php } ?>><a href="transferVacancyTeacherNational-8.html">Vacancy Teacher National</a></li>
                                                <li <?php if ($pageid == 9) { ?>class="active"<?php } ?>><a href="transferVacancyPrincipleNational-9.html">Vacancy Principle National</a></li>
                                                <li <?php if ($pageid == 10) { ?>class="active"<?php } ?>><a href="transferVacancyTeacherNormal-10.html">Vacancy Teacher Normal</a></li>
                                                <li <?php if ($pageid == 11) { ?>class="active"<?php } ?>><a href="transferVacancyPrincipleNormal-11.html">Vacancy Principle Normal</a></li>
                                                <li <?php if ($pageid == 12) { ?>class="active"<?php } ?>><a href="teacherQualification-12.html">Teacher Qualification</a></li>
                                                <li <?php if ($pageid == 13) { ?>class="active"<?php } ?>><a href="teacherRequestTraining-13.html">Teacher Request Training</a></li>
                                                
                                                <li <?php if ($pageid == 14) { ?>class="active"<?php } ?>><a href="teacherApplyTraining-14.html">Teacher Apply Training</a></li>
                                                <li <?php if ($pageid == 15) { ?>class="active"<?php } ?>><a href="updateRequestPersonalInfo-15.html">Update Request Personal Info</a></li>
                                               <li <?php if ($pageid == '17') { ?>class="active"<?php } ?>><a href="updateRequestFamilyInfo-17.html">Update Request Family Info(Spouse)</a></li>
                                               <li <?php if ($pageid == '17a') { ?>class="active"<?php } ?>><a href="updateRequestFamilyInfoChild-17a.html">Update Request Family Info(Child)</a></li>
                                               <li <?php if ($pageid == 18) { ?>class="active"<?php } ?>><a href="updateRequestQalification-18.html">Update Request Qalification</a></li>
                                               <li <?php if ($pageid == 19) { ?>class="active"<?php } ?>><a href="updateRequestTeaching-19.html">Update Request Teaching</a></li>
                                                
                                            </ul>
                                            <div id="geographical" class="contenttab"></div>
                                        </div>
                                    </div> 

                                    <div style="width:945px; height:auto; float:left; margin-left:10px;">
                                        <?php
                                        if ($pageid == 1) include('retirementRequest.php');                                        if ($pageid == 2)include('leaveRequest.php');
                                        if ($pageid == 3)include('retirementMoreDetail.php');

										if ($pageid == 4)include("transferTeacherNormal.php");
										if ($pageid == 5)include("transferPrincipleNormal.php");
										if ($pageid == 6)include("transferTeacherNational.php");
										if ($pageid == 7)include("transferPrincipleNational.php");
										if ($pageid == 8)include("transferVacancyTeacherNational.php");
										if ($pageid == 9)include("transferVacancyPrincipleNational.php");
										if ($pageid == 10)include("transferVacancyTeacherNormal.php");
										if ($pageid == 11)include("transferVacancyPrincipleNormal.php");
										
										if ($pageid == 12)include("teacherQualification.php");
										if ($pageid == 13)include("teacherRequestTraining.php");
										if ($pageid == 14)include("teacherApplyTraining.php");
										if($accLevel=='11050' || $accLevel=='11000' || $accLevel=='99999'){
											if ($pageid == 15)include("updateRequestPersonalInfo.php");
											if ($pageid == 16 || $pageid == 0)include("newRegistration.php");
											if ($pageid == '17')include("updateRequestFamilyInfo.php");
											if ($pageid == '17a')include("updateRequestFamilyInfoChild.php");
											if ($pageid == 18)include("updateRequestQalification.php");
											if ($pageid == 19)include("updateRequestTeaching.php");
										}
										if ($pageid == 20)include("index1.php");
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
