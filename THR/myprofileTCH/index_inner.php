<?php
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
if ($_SESSION['NIC'] == '') {
    header("Location: ../index.php");
    exit();
}
/* if($_SESSION['loggedSchoolSearch']=='') {
  $_SESSION["ses_expire"]="Session expired. Select a school again.";
  header("Location: index.php") ;
  exit() ;
  } */

include '../db_config/DBManager.php';
$db = new DBManager();

$timezone = "Asia/Colombo";
if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set($timezone);
}
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

if ($pageid == '') {
    $pageid = "0";
}

$NICUser = trim($_SESSION["NIC"]);
$loggedSchool = trim($_SESSION['loggedSchool']);
$loggedPositionName = $_SESSION['loggedPositionName'];

$loggedSchool = trim($_SESSION['loggedSchoolSearch']);

$sqlList = "SELECT InstitutionName FROM CD_CensesNo where CenCode='$loggedSchool'";
$stmt = $db->runMsSqlQuery($sqlList);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$InstitutionName = $row['InstitutionName'];

$sqlTName = "SELECT SurnameWithInitials FROM TeacherMast where NIC='$id'";
$stmtTn = $db->runMsSqlQuery($sqlTName);
$rowTn = sqlsrv_fetch_array($stmtTn, SQLSRV_FETCH_ASSOC);
$SurnameWithInitialsT = $rowTn['SurnameWithInitials'];

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

if ($pageid == 1 || $pageid == 2) {
    $sql = "SELECT CONVERT(varchar(10), LastUpdate, 121) AS LastUpdate FROM TeacherMast WHERE (NIC = '$id') ORDER BY LastUpdate DESC";
    $stmt = $db->runMsSqlQuery($sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $LastUpdate = trim($row['LastUpdate']);
}
if ($pageid == 4) {
    $sql = "SELECT CONVERT(varchar(10), LastUpdate, 121) AS LastUpdate FROM StaffQualification WHERE (NIC = '$id') ORDER BY LastUpdate DESC";
    $stmt = $db->runMsSqlQuery($sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $LastUpdate = trim($row['LastUpdate']);
}
if ($pageid == 5) {
    $sql = "SELECT CONVERT(varchar(10), LastUpdate, 121) AS LastUpdate FROM TeacherSubject WHERE (NIC = '$id') ORDER BY LastUpdate DESC";
    $stmt = $db->runMsSqlQuery($sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $LastUpdate = trim($row['LastUpdate']);
}
if ($pageid == 8) {
    $sql = "SELECT CONVERT(varchar(10), LastUpdate, 121) AS LastUpdate FROM StaffServiceHistory WHERE (NIC = '$id') ORDER BY LastUpdate DESC";
    $stmt = $db->runMsSqlQuery($sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $LastUpdate = trim($row['LastUpdate']);
}

if ($pageid == 9) {
    $sql = "SELECT CONVERT(varchar(10), LastUpdate, 121) AS LastUpdate FROM Passwords WHERE (NICNo = '$id') ORDER BY LastUpdate DESC";
    $stmt = $db->runMsSqlQuery($sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $LastUpdate = trim($row['LastUpdate']);
}
?>

<!DOCTYPE html>
<html>
    <head>

        <link rel="icon" 
              type="image/png" 
              href="../favicon.ico">


        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
        <link rel="stylesheet" type="text/css" media="all" href="../cms/calendar/calendar-tas.css" title="win2k-2" />
        <script type="text/javascript" src="../cms/calendar/calendar.js"></script>
        <script type="text/javascript" src="../cms/calendar/lang/calendar-en.js"></script>
        <script type="text/javascript" src="../cms/calendar/calendar-setup.js"></script>

        <script src="js/jquery-1.9.1.js"></script>
        <script src="selectpage.js"></script>
        <script src="js/jquery.tabify.js" type="text/javascript" charset="utf-8"></script>
        <script src="js/FilterDB.js"  language="javascript"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#menu').tabify();
                $('#menu2').tabify();
            });
        </script>
        <script type="text/javascript">
            // IE9 fix
            if (!window.console) {
                var console = {
                    log: function () {
                    },
                    warn: function () {
                    },
                    error: function () {
                    },
                    time: function () {
                    },
                    timeEnd: function () {
                    }
                };
            }
        </script>
        <script language="JavaScript">
            function openNewWin(sta) {
                if (sta == '1') {
                    //exText="This is a tooltip text. Please put the mouse pointer on the link and read the text";
                }
            }
            function aedWin(vID, AED, vCat, tblName, redirect_page) {

                if (AED == 'D') {
                    exText = "Are you sure you want to Delete this Record?";
                    getCon = confirm(exText);
                    if (getCon) {
                        top.location.href = "save.php?vID=" + (vID) + "&AED=" + (AED) + "&cat=" + (vCat) + "&tblName=" + (tblName) + "&redirect_page=" + (redirect_page);
                    }
                }
                /*if(vAED=='R') {
                 exText="Are you sure you want to reject this record?";
                 getCon=confirm(exText);
                 if(getCon){		
                 top.location.href="save.php?vID="+(vID)+"&aed="+(vAED)+"&cat="+(vCat)+"&vDes="+(vDes);
                 }
                 } 
                 if(vAED=='ED') {	 
                 if(vDes=='0') exText="Are you sure you want to approve this record?";
                 if(vDes=='1') exText="Are you sure you want to un-approve this record?";
                 getCon=confirm(exText);
                 if(getCon){		
                 top.location.href="save.php?vID="+(vID)+"&aed="+(vAED)+"&cat="+(vCat)+"&vDes="+(vDes);
                 }	 
                 }	
                 if(vAED=='ED1') {	 
                 if(vDes=='N') exText="Are you sure you want to approve this record?";
                 if(vDes=='Y') exText="Are you sure you want to un-approve this record?";
                 getCon=confirm(exText);
                 if(getCon){		
                 top.location.href="save.php?vID="+(vID)+"&aed="+(vAED)+"&cat="+(vCat)+"&vDes="+(vDes);
                 }	 
                 }	*/
            }
            function aedWin1(vID, AED, vCat, vDes, tblName, mainID, redirect_page) {//vAED

                if (AED == 'D') {
                    exText = "Are you sure you want to Remove this Record?";
                    getCon = confirm(exText);
                    if (getCon) {
                        top.location.href = "save.php?vID=" + (vID) + "&AED=" + (AED) + "&cat=" + (vCat) + "&vDes=" + (vDes) + "&tblName=" + (tblName) + "&mainID=" + (mainID) + "&redirect_page=" + (redirect_page);
                    }
                }
                if (AED == 'R') {
                    exText = "Are you sure you want to reject this record?";
                    getCon = confirm(exText);
                    if (getCon) {
                        top.location.href = "save.php?vID=" + (vID) + "&AED=" + (AED) + "&cat=" + (vCat) + "&vDes=" + (vDes) + "&tblName=" + (tblName) + "&mainID=" + (mainID) + "&redirect_page=" + (redirect_page);
                    }
                }
                if (AED == 'ED1') {
                    if (vDes == '0')
                        exText = "Are you sure you want to approve this record?";
                    if (vDes == '1')
                        exText = "Are you sure you want to un-approve this record?";
                    getCon = confirm(exText);
                    if (getCon) {
                        top.location.href = "save.php?vID=" + (vID) + "&AED=" + (AED) + "&cat=" + (vCat) + "&vDes=" + (vDes) + "&tblName=" + (tblName) + "&mainID=" + (mainID) + "&redirect_page=" + (redirect_page);
                    }
                }
                if (AED == 'ED') {
                    if (vDes == 'Active')
                        exText = "Are you sure you want to deactive this record?";
                    if (vDes == 'Deactive')
                        exText = "Are you sure you want to active this record?";
                    getCon = confirm(exText);
                    if (getCon) {
                        top.location.href = "save.php?vID=" + (vID) + "&AED=" + (AED) + "&cat=" + (vCat) + "&vDes=" + (vDes) + "&tblName=" + (tblName) + "&mainID=" + (mainID) + "&redirect_page=" + (redirect_page);
                    }
                }
            }

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
                <div id="header_outer" style="background:url(<?php echo $theamPath ?><?php echo $theam ?>/backgrounds/menu_back.gif) repeat-x">
                    <div id="header_inner">
                        <div id="header_top">
                            <div class="header_top_left">&nbsp;&nbsp; </div>
                            <div class="header_top_right">
                                <div id="admin_button" style="cursor:default;"><a href="#" id="admin_link" style="cursor:default;"><span style="cursor:default;"><?php echo $loggedPositionName; ?></span></a></div>
                                <!-- <div id="mail_button"><a href="#"></a><div id="mail_alert" class="alert">05</div></div>-->
                                <?php if ($NICUser) { ?><div id="user_welcome">Welcome <?php echo $_SESSION["fullName"]; ?>, &nbsp;&nbsp; <span class="link1" onClick="logoutForm('mail');">Logout?</span></div><?php } ?>
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

                                <div class="midArea"> 
                                    <?php if ($pageid != 0) { ?>
                                        <div class="productsAreaRight">
                                            <ul id="menu" class="menu">
                                                <?php
                                                // ParentID "1" is for Administrator 
                                                $sqlMChild = "SELECT
TG_DynMenu.ID,
TG_DynMenu.Icon,
TG_DynMenu.Title,
TG_DynMenu.PageID,
TG_DynMenu.Url,
TG_DynMenu.ParentID,
TG_DynMenu.IsParent,
TG_DynMenu.ShowMenu,
TG_DynMenu.ParentOrder,
TG_DynMenu.ChildOrder,
TG_DynMenu.FOrder,
TG_DynMenu.PHPPage
FROM
TG_DynMenu
INNER JOIN TG_Privilage ON TG_DynMenu.ID = TG_Privilage.FormID
WHERE
dbo.TG_Privilage.AccessRoleID = $AccessRoleID AND
dbo.TG_DynMenu.ParentID = 4 AND
dbo.TG_DynMenu.ShowMenu = 1";
                                                $stCh = $db->runMsSqlQuery($sqlMChild);
                                                $count = 0;
                                                $arrPageID = array();
                                                while ($rowC = sqlsrv_fetch_array($stCh, SQLSRV_FETCH_ASSOC)) {
                                                    $active_class = "";
                                                    $count++;

                                                    $rowid = $rowC['ID'];
                                                    $icon = $rowC['Icon'];
                                                    $title = $rowC['Title'];
                                                    $page_id = $rowC['PageID'];
                                                    $url = $rowC['Url'];
                                                    $parent_id = $rowC['ParentID'];
                                                    $is_parent = $rowC['IsParent'];
                                                    $show_menu = $rowC['ShowMenu'];

                                                    $arrPageID[] = array($rowC['PageID'], $rowC['PHPPage']);

                                                    $url = str_replace("NIC",$id,$url);

                                                    if (str_replace("-tab", "", basename($_SERVER['REQUEST_URI'])) == $url) {
                                                        $active_class = "active";
                                                    } else {
                                                        if ($count == 1 && basename($_SERVER['REQUEST_URI']) == "myprofile") {
                                                            $active_class = "active";
                                                        }
                                                    }
                                                    ?>

                                                    <li class="<?php echo $active_class; ?>"><a href="<?php echo $url; ?>"><?php echo $title; ?></a></li>
                                                    <?php
                                                }
                                                ?>


                                            </ul>
                                            <div id="geographical" class="contenttab" style="padding-top:20px;"></div>

                                        </div>
                                    <?php } ?>

                                </div> 
                                <?php if ($pageid != 0) { ?>
                                    <div style="width:660px; height:auto; float:left; margin-left:10px; padding-bottom:10px;">Teacher Name : <?php echo $SurnameWithInitialsT ?> [<?php echo $id ?>]</div> 
                                    <div style="width:300px; height:auto; float:right; margin-right:10px; padding-bottom:10px; text-align:right;">Last Update : <?php echo $LastUpdate; ?></div>     
                                <?php } ?>
                                <div style="width:960px; height:auto; float:left; margin-left:10px;">
                                    <?php
                                    for ($index = 0; $index < count($arrPageID); $index++) {
                                        if ($pageid == $arrPageID[$index][0] || ($index == 0 && $pageid==0)) {
                                          //  echo $pageid . '----' . $arrPageID[$index][1];
                                            include $arrPageID[$index][1];
                                        }
                                    }
                                    
                                    if ($pageid == 3) {
                                        include('familyInfoChild.php');
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