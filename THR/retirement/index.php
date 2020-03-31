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
$accLevel = $_SESSION["accLevel"];
$AccessRoleType = $_SESSION['AccessRoleType'];

// ***
// check employee complete 20year of service
$showVoluntary = "N";
$showAgeUpon = "N";

/*
  $datetime1 = new DateTime('2011-01-01');
  $datetime2 = new DateTime('2010-02-01');
  $interval = $datetime1->diff($datetime2);
  //echo $interval->format('%y.%m');
 * 
 */

if ($AccessRoleType != 'NC') {

    $sqlP = "SELECT fVoluntaryYear, fRetirementYear
FROM TG_RetiremntParms";

    $stmtP = $db->runMsSqlQuery($sqlP);
    while ($rowP = sqlsrv_fetch_array($stmtP, SQLSRV_FETCH_ASSOC)) {
        $fVoluntaryYear = $rowP["fVoluntaryYear"];
        // $fRetirementYear = $rowP["fRetirementYear"];
        $fRetirementYear2 = $rowP["fRetirementYear"];
        // remove six month from fRetirementYear
        /* $fRetirementYear = $fRetirementYear - 1;
          $fRetirementYear = $fRetirementYear . ".6"; */
    }
    $p = $fRetirementYear2 - 6;
    $checkAge = strtotime(date("Y-m-d", strtotime($currDate)) . " -$p month");
    $checkAge = date("Y-m-d", $checkAge);
//echo  $fRetirementYear;

    $sqlChk = "SELECT ID, NIC, ServiceRecTypeCode, CONVERT(varchar(20),AppDate,121) AS AppDate
FROM StaffServiceHistory
WHERE (NIC = N'$nicNO') AND (ServiceRecTypeCode = N'NA01')";
// 513502036V / 565930915V
    $totRow = $db->rowCount($sqlChk);
    $stmt = $db->runMsSqlQuery($sqlChk);

    if ($totRow > 0) {

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $fAppDate = $row['AppDate'];
        $d1 = new DateTime($currDate);
        $d2 = new DateTime($fAppDate);

        $diff = $d2->diff($d1);
        $workedYears = $diff->y * 12;
        // $totWorked = $diff->format('%y.%m');


        if ($workedYears < $fVoluntaryYear) {
            $redirect_page = '../module_main.php';
            $_SESSION['error_msg'] = "You don’t have permission to access. <br>Your service is less than 20 years.";
            header("Location:$redirect_page");
        } else {
            $showVoluntary = "Y";
        }

        $sqlAgeUp = "SELECT ID FROM TeacherMast where NIC='$nicNO' and DOB<'$checkAge'";
        $totRowAgeUp = $db->rowCount($sqlAgeUp);
        if ($totRowAgeUp > 0) {
            $showVoluntary = "";
            $showAgeUpon = "Y";
        }
    } else {
        $redirect_page = '../module_main.php';
        $_SESSION['error_msg'] = "You don’t have permission to access.";
        header("Location:$redirect_page");
    }
// *** end
// check retirement Age upon
// *** end
}
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

        <link type='text/css' href='../assets/css/dashboard.css' rel='stylesheet' media='screen'/>
        <link rel="stylesheet" href="../assets/css/jquery-ui.css">

        <script src="../assets/js/jquery-latest.min.js" type="text/javascript"></script>
        <script src="../assets/js/jquery-ui.js"></script>
        <script src="../assets/js/back/script.js"></script>

        <style>
            .fields_errors{
                border-color: rgba(229, 103, 23, 0.8);
                box-shadow: 0 1px 1px rgba(229, 103, 23, 0.075) inset, 0 0 8px rgba(229, 103, 23, 0.6);
                outline: 0 none;
            }

        </style>

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
                }
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

                                <div class="midArea"> 
                                    <div class="productsAreaRight">
                                        <ul id="menu" class="menu">
                                            <?php
//$showVoluntary = 'Y';
//$showAgeUpon = 'Y';
                                            if ($pageid == 1 || $pageid == 0 || $pageid == 3) {
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
                                                if ($showAgeUpon == 'Y' || $showVoluntary == 'Y') {
                                                    echo '<div id="geographical" class="contenttab"></div>';
                                                }
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
                                        <!-- <div id="geographical" class="contenttab"></div>-->
                                    </div>
                                </div> 

                                <div style="width:945px; height:auto; float:left; margin-left:10px;">
                                    <?php
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
TG_Privilage.AccessRoleID = $AccessRoleID AND
TG_DynMenu.ID IN (7,8) AND
TG_DynMenu.ShowMenu = 1";
                                    $stCh = $db->runMsSqlQuery($sqlMChild);
                                    $count = 0;
                                    $arrPageID = array();
                                    while ($rowC = sqlsrv_fetch_array($stCh, SQLSRV_FETCH_ASSOC)) {
                                        $arrPageID[] = array($rowC['PageID'], $rowC['PHPPage']);
                                    }

                                    //var_dump($arrPageID);
                                    for ($index = 0; $index < count($arrPageID); $index++) {
                                        if ($arrPageID[$index][0] == '0') {
                                            if ($pageid == 1 || $pageid == 0 || $pageid == 3) {
                                                include('retirementType.php');
                                            }
                                            if ($pageid == 2)
                                                include('retirementStatus.php');
                                            if ($pageid == 5)
                                                include('retirementList.php');
                                        }
                                        //for permistion check
                                        if ($arrPageID[$index][0] == '4') {
                                            if ($pageid == 4) {
                                                // for page load
                                                include('retirementSearch.php');
                                            }
                                        }
                                    }

                                    /*
                                      if ($pageid == 1 || $pageid == 0 || $pageid == 3) {
                                      include('retirementType.php');

                                      }
                                      if ($pageid == 2)
                                      include('retirementStatus.php');
                                      if ($pageid == 4)
                                      include('retirementSearch.php');
                                      if ($pageid == 5)
                                      include('retirementList.php');
                                     * 
                                     */
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
