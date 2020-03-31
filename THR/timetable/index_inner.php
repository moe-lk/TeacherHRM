<?php
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();

$timezone = "Asia/Colombo";
if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);

if ($_SESSION['timeout'] + 30 * 60 < time()) {
	session_unset();
    session_destroy();
    session_start();
	header("Location: ../index.php") ;
	exit() ;
}
$_SESSION["timeout"] = time();


$replace_data=array("'","/","!","&","*"," ","-","@",'"',"?",":","“","”");
$replace_data_new=array("'","/","!","&","*"," ","-","@",'"',"?",":","“","”",".");
$pageid=$_GET["pageid"];
$menu = $_GET['menu'];
$tpe = $_GET['tpe'];
$id = $_GET['id'];
$fm = $_GET['fm'];
$lng = $_GET['lng'];
$curPage = $_GET['curPage'];
$ttle = $_GET['ttle'];
$ttle = str_replace("_"," ",$ttle);
//str_replace(",","",$amount);

if($pageid=='') $pageid="0";


$nicNO = $_SESSION["NIC"];
$loggedSchool=$_SESSION['loggedSchoolSearch'];
//$loggedSchool=$_SESSION['loggedSchool'];
$loggedPositionName=$_SESSION['loggedPositionName'];
$accLevel=$_SESSION["accLevel"];

$sql = "SELECT InstitutionName FROM CD_CensesNo where CenCode='$loggedSchool' order by InstitutionName";
$stmt = $db->runMsSqlQuery($sql);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$InstitutionNameSelec=$row['InstitutionName'];

/* if($accLevel!='3000'){
	header("Location:../index.php");
	exit();
} */
//$nicNO = '791231213V';
$querySaveVal = "";

$theamPath = "../cms/images/";
$theam = "theam1";
if($theam == "theam1"){
	$theamMenuFontColor = "#0888e2";
	$theamMenuButtonColor = "#3973b1";
}
if($theam == "theam2"){
	$theamMenuFontColor = "#d98813";
	$theamMenuButtonColor = "#3a2a07";
}
if($theam == "theam3"){
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
        <script src="js/FilterDB.js"  language="javascript"></script>
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
        
<script language="JavaScript">
 function openNewWin(sta){ 	
	  if (sta == '1' ){							
		//exText="This is a tooltip text. Please put the mouse pointer on the link and read the text";
	 }	
 }
  function aedWin(vID,AED,vCat,tblName,redirect_page) {
 	
	  if (AED == 'D'){							
		exText="Are you sure you want to Delete this Record?";
		getCon=confirm(exText);
		if(getCon){		
		  top.location.href="save.php?vID="+(vID)+"&AED="+(AED)+"&cat="+(vCat)+"&tblName="+(tblName)+"&redirect_page="+(redirect_page);
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
    function aedWin1(vID,AED,vCat,vDes,tblName,mainID,redirect_page) {//vAED
 	
	 if (AED == 'D'){							
		exText="Are you sure you want to Remove this Record?";
		getCon=confirm(exText);
		if(getCon){		
		  top.location.href="save.php?vID="+(vID)+"&AED="+(AED)+"&cat="+(vCat)+"&vDes="+(vDes)+"&tblName="+(tblName)+"&mainID="+(mainID)+"&redirect_page="+(redirect_page);
		}
	 } 
	 if(AED=='R') {
	 	exText="Are you sure you want to reject this record?";
		getCon=confirm(exText);
		if(getCon){		
		  top.location.href="save.php?vID="+(vID)+"&AED="+(AED)+"&cat="+(vCat)+"&vDes="+(vDes)+"&tblName="+(tblName)+"&mainID="+(mainID)+"&redirect_page="+(redirect_page);
		}
	 } 
	 if(AED=='ED1') {	 
		if(vDes=='0') exText="Are you sure you want to approve this record?";
		if(vDes=='1') exText="Are you sure you want to un-approve this record?";
		getCon=confirm(exText);
		if(getCon){		
		  top.location.href="save.php?vID="+(vID)+"&AED="+(AED)+"&cat="+(vCat)+"&vDes="+(vDes)+"&tblName="+(tblName)+"&mainID="+(mainID)+"&redirect_page="+(redirect_page);
		}	 
	 }	
	 if(AED=='ED') {	 
		if(vDes=='Active') exText="Are you sure you want to deactive this record?";
		if(vDes=='Deactive') exText="Are you sure you want to active this record?";
		getCon=confirm(exText);
		if(getCon){		
		  top.location.href="save.php?vID="+(vID)+"&AED="+(AED)+"&cat="+(vCat)+"&vDes="+(vDes)+"&tblName="+(tblName)+"&mainID="+(mainID)+"&redirect_page="+(redirect_page);
		}	 
	 }	
 }

</script>
<script src="js/form_check.js" type="text/javascript"></script>
<script src="selectpage.js" type="text/javascript"></script>

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
           		<div id="admin_button" style="cursor:default;"><a href="#" id="admin_link" style="cursor:default;"><span style="cursor:default;"><?php echo $loggedPositionName;?></span></a></div>
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
                            <?php include('../mainmenu.php')?>
                            <div class="mcib_middle">
								
                                <div class="containerHeaderOne">
                                	<?php if($pageid!=0){?>
                                    <div class="midArea"> 
                                        <div class="productsAreaRight">
                                            <ul id="menu" class="menu">
                                            <?php if($accLevel!='1000'){?>
                                            	<!--<li <?php if($pageid==0){?>class="active"<?php }?>><a href="../timetable/">Search School</a></li>-->
                                                <?php if($accLevel=='3000'){?>
                                                <li <?php if($pageid==1 || $pageid==0){?>class="active"<?php }?>><a href="grade-1.html">Grade</a></li>
                                                <li <?php if($pageid==2){?>class="active"<?php }?>><a href="subject-2.html">Subject</a></li>
                                                <li <?php if($pageid==3){?>class="active"<?php }?>><a href="learningPoints-3.html">Learning locations</a></li>
                                                <li <?php if($pageid==4 || $pageid==11){?>class="active"<?php }?>><a href="classStructure-4.html">Class Structure</a></li>
                                                <li <?php if($pageid==12){?>class="active"<?php }?>><a href="classGroup-12.html">Class Group</a></li>
                                                <li <?php if($pageid==5){?>class="active"<?php }?>><a href="generateTimetable-5.html">Generate Timetable</a></li>
                                                <!--<li <?php if($pageid==6){?>class="active"<?php }?>><a href="assignTeachers-6.html">Assign Teachers</a></li>-->
                                                <?php }?>
                                                <li <?php if($pageid==7 || $pageid==8){?>class="active"<?php }?>><a href="printTimeTable-7.html">View & Print</a>
                                               
                                                </li>
                                                
                                                <li <?php if($pageid==9){?>class="active"<?php }?>><a href="complianceReports-9-C.html">Compliance Reports&nbsp;&nbsp;</a></li>
                                                <?php }?>
                                                <?php if($accLevel=='1000'){?>
                                                <li <?php if($pageid==13){?>class="active"<?php }?>><a href="myTimetable-13.html">My Timetable&nbsp;&nbsp;</a></li> <?php } ?>
                                            </ul>
											<div id="geographical" class="contenttab" style="padding-top:10px;"></div>
                                        </div>
                                    </div> 
                                   <?php }?>
                                <div style="float:left; margin-left:10px; width:960px; text-align:center;"><span style="font-size:14px;"><u><?php echo $InstitutionNameSelec ?></u></span></div>
								<div style="width:960px; height:auto; float:left; margin-left:10px;">
                                <?php 
								if($accLevel=='1000')$pageid=0;
								//if($pageid==0)include('searchSchool.php');
								if($accLevel!='1000'){
									if($pageid==1 || $pageid==0)include('grade.php');
									if($pageid==2)include('subject.php');
									
									if($pageid==3)include('learningPoints.php');
									if($pageid==4)include('classStructure.php');
									if($pageid==5)include('generateTimetable.php');
									if($pageid==6)include('assignTeachers.php');
									if($pageid==7)include('printTimeTable.php');
									if($pageid==8)include('printTimeTableTeacher.php');
									if($pageid==9)include('complianceReports.php');
									if($pageid==10)include('generateTimetableLearningLocation.php');
									
									if($pageid==11)include('subject_teachers.php');
									
									if($pageid==12)include('classGroup.php');
								}
								if($accLevel=='1000')if($pageid==13 || $pageid==0)include('myTimetable.php');
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