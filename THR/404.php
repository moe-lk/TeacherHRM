<?php //echo md5('HOsd@0117213133');
require_once 'error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include 'db_config/DBManager.php';
$db = new DBManager();

$NICUser=$_SESSION["NIC"];
$accLevel=$_SESSION["accLevel"];

$timeTableShow="N";

/*
$checkPrinciple="SELECT        TeacherMast.CurResRef,TeacherMast.NIC, StaffServiceHistory.PositionCode, CD_Positions.PositionName, StaffServiceHistory.InstCode
FROM            CD_Positions INNER JOIN
                         TeacherMast INNER JOIN
                         StaffServiceHistory ON TeacherMast.CurResRef = StaffServiceHistory.ID ON CD_Positions.Code = StaffServiceHistory.PositionCode
						 where TeacherMast.NIC='$NICUser' and StaffServiceHistory.PositionCode='SP09'";

*/

$checkPrinciple="SELECT        TeacherMast.NIC, StaffServiceHistory.PositionCode, StaffServiceHistory.InstCode, TeacherMast.CurServiceRef, TeacherMast.SurnameWithInitials,
                         Passwords.AccessRole
FROM            TeacherMast INNER JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID INNER JOIN
                         Passwords ON TeacherMast.NIC = Passwords.NICNo
                         where TeacherMast.NIC='$NICUser' and Passwords.AccessLevel='3000'";

$isAvailablePrinc=$db->rowCount($checkPrinciple);
if($isAvailablePrinc==1){
    $timeTableShow="Y";
    
   /*  $stmt = $db->runMsSqlQuery($checkPrinciple);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $loggedSchoolID=$row['InstCode'];
        $loggedPositionName=$row['AccessRole'];
    }
    $_SESSION['loggedSchool']=$loggedSchoolID;
    $_SESSION['loggedPositionName']=$loggedPositionName; */
}

$checkAccessRol="SELECT        TeacherMast.NIC, StaffServiceHistory.PositionCode, StaffServiceHistory.InstCode, TeacherMast.CurServiceRef, TeacherMast.SurnameWithInitials,
                         Passwords.AccessRole, Passwords.AccessLevel
FROM            TeacherMast INNER JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID INNER JOIN
                         Passwords ON TeacherMast.NIC = Passwords.NICNo
                         where TeacherMast.NIC='$NICUser'";
$stmt = $db->runMsSqlQuery($checkAccessRol);
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $loggedSchoolID=trim($row['InstCode']);
       // $loggedPositionName=trim($row['AccessRole']);
		//$loggedAccessLevel=trim($row['AccessLevel']);
    }
	
	
	
	$schoolType="Select IsNationalSchool from CD_CensesNo where CenCode='$loggedSchoolID'";
	$stmtTyp = $db->runMsSqlQuery($schoolType);
	while ($row = sqlsrv_fetch_array($stmtTyp, SQLSRV_FETCH_ASSOC)) {
        $IsNationalSchool=trim($row['IsNationalSchool']);
    }
	
	if($IsNationalSchool==1){
		$_SESSION['schoolType']="N";//National
	}else{
		$_SESSION['schoolType']="P";//Province
	}
	
	$sqlLevel="SELECT [NICNo]
      ,[CurPassword]
      ,[LastUpdate]
      ,[AccessRole]
      ,[AccessLevel]
  FROM [dbo].[Passwords]
  where NICNo='$NICUser'";
  	$stmtTypL = $db->runMsSqlQuery($sqlLevel);
	$rowL = sqlsrv_fetch_array($stmtTypL, SQLSRV_FETCH_ASSOC);
    $loggedAccessLevel=trim($rowL['AccessLevel']);
	$loggedPositionName=trim($rowL['AccessRole']);
  
	$_SESSION['loggedSchool']=$loggedSchoolID;
    $_SESSION['loggedPositionName']=$loggedPositionName;
	$_SESSION['loggedAccessLevel']=$loggedAccessLevel;


$menuIDs=explode("_",$menu);
if($pageid=='0'){
$activeMainMenuNo = "1";
$activeSubMenu ="2";
}else {
$activeMainMenuNo = $menuIDs[0];
$activeSubMenu =$menuIDs[1];

}

$theam = "theam1";
$theamMenuFontColor; 
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
$theamPath = "cms/images/";



//$retirementShow = "Y";
$sqlPrivi="SELECT * FROM TG_Privilages where AccessRoleValue='$loggedAccessLevel'"; 
$stmtPrivi = $db->runMsSqlQuery($sqlPrivi);
while ($rowPrivi = sqlsrv_fetch_array($stmtPrivi, SQLSRV_FETCH_ASSOC)) {
	$PrivilageModuleArr[]=$rowPrivi['PrivilageModule'];
}
//print_r($PrivilageModuleArr);
//if(in_array("P5",$PrivilageModuleArr))
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>EMIS</title>
<link rel="shortcut icon" href="site.png" type="image/x-icon">
<link href="cms/css/main_menu1.css" rel="stylesheet" type="text/css" />
<link href="cms/css/screen.css" rel="stylesheet" type="text/css" />
<link href="cms/css/grid_style.css" rel="stylesheet" type="text/css" />
<link href="cms/css/flexigrid.css" rel="stylesheet" type="text/css"/>


<script type="text/javascript" src="cms/js/jquery-1.2.3.pack.js"></script>
<script type="text/javascript" src="cms/js/flexigrid.js"></script>
<script type="text/javascript" src="cms/js/menu1.js"></script>
<script type="text/javascript">
function logoutForm() {
    var action = "login.php?request=signOut";
    document.getElementById('form1').action = action;
    document.getElementById('form1').submit();
}
</script>
<script type="text/javascript">
	$(document).ready(function(){
		//nenu("id of the active main menu", active sbu menu," path of the icon folder")
		
		var getActiveMainMenu = "mm"+<?php echo $activeMainMenuNo ?>;
		var imgParth = "<?php echo $theamPath ?><?php echo $theam ?>/icon/";							
		$('#'+getActiveMainMenu).toggleClass("active");
		changeIcon(getActiveMainMenu, imgParth);
		
		$(function() {
			$('.input_img img').each(function() {
				var maxWidth = 140; // Max width for the image
				var maxHeight = 100;    // Max height for the image
				var width = $(this).width();    // Current image width
				var height = $(this).height();  // Current image height
				var margin_top=0;
				var margin_left=0;
				// Check if the current width is larger than the max
				if(width>height && width>maxWidth)
				{
					ratio = maxWidth / width;   // get ratio for scaling image
					$(this).css("width", maxWidth); // Set new width
					$(this).css("height", height * ratio); // Scale height based on ratio
					margin_top=(maxHeight-(height * ratio))/2;
					$(this).css("margin-top", margin_top);
				}
				else if(height>width && height>maxHeight)
				{
					ratio = maxHeight / height; // get ratio for scaling image
					$(this).css("height", maxHeight);   // Set new height
					$(this).css("width", width * ratio);    // Scale width based on ratio
					margin_width=(maxWidth-(width * ratio))/2;
					$(this).css("margin-left", margin_width);
				}
				else
				{
					$(this).css("height", maxHeight);   // Set new height
					$(this).css("width", maxWidth);    // Scale width based on ratio
				}
			});
		});
		
		//clear search field value when forcus
		$('#search_input1').focus(function() {
			if($(this).val() == "Search Text"){
  				$(this).val("");
			}
		});
		
		//add text to search field when blue
		$('#search_input1').blur(function() {
			if($(this).val() == "" && $(this).val() != "Search Text"){
  				$(this).val("Search Text");
			}
		});
	});
</script>



<script language="JavaScript">
 function openNewWin(sta){ 	
	  if (sta == '1' ){							
		//exText="This is a tooltip text. Please put the mouse pointer on the link and read the text";
	 }	
 }
  function aedWin(vID,vAED,vCat,vDes) {
 	
	  if (vAED == 'D'){							
		exText="Are you sure you want to Delete this Record?";
		getCon=confirm(exText);
		if(getCon){		
		  top.location.href="save.php?vID="+(vID)+"&aed="+(vAED)+"&cat="+(vCat)+"&vDes="+(vDes)+"&tblName="+(tblName)+"&mainID="+(mainID)+"&redirect_page="+(redirect_page);
		}
	 } 
	 if(vAED=='R') {
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
	 }	
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

<style type="text/css">

/*menu style*/
#nav li a{
	float:left;
	display:block;
	width:120px;
	height:57px;
	margin:0 5px 0 0;
	padding-top:5px;
	background:url(cms/images/fixed_images/hover_back.png) no-repeat;
	text-align:center;
	text-decoration:none;
	color: #222222;
	font-size:12px;
	font-family: 'PT Sans',sans-serif;
	text-shadow: 0 1px 0 #FFFFFF;
	filter:alpha(opacity=80);
	-moz-opacity:0.8;
	-khtml-opacity: 0.8;
	opacity: 0.8;
	}
	
#nav li a:hover{
	background:url(cms/images/fixed_images/active_back.png) no-repeat;
	}
	
#nav li a.active{
	float:left;
	display:block;
	width:120px;
	height:57px;
	margin:0 5px 0 0;
	padding-top:5px;
	text-align:center;
	text-decoration:none;
	color:<?php echo $theamMenuFontColor ?>;
	font-size:12px;
	font-family: 'PT Sans',sans-serif;
	text-shadow: 0 1px 0 #FFFFFF;
	background:url(cms/images/fixed_images/active_back.png) no-repeat;
	filter:alpha(opacity=100);
	-moz-opacity:1;
	-khtml-opacity: 1;
	opacity: 1;
	}
	
#nav li a.active img{
	filter:alpha(opacity=100);
	-moz-opacity:1;
	-khtml-opacity: 1;
	opacity: 1;
}

#nav li .sub_nav a:hover{
	background-color:<?php echo $theamMenuFontColor ?>;
	color:#FFF;
	text-shadow: none;
	background-image:none;
	border:1px solid <?php echo $theamMenuFontColor ?>;
	border-top:none;
}
/*menu style*/

	
.link1{
	color:<?php echo $theamMenuFontColor ?>;
	}
	
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
	
/*grid style*/
.flexigrid div.mDiv
	{
	width:960px;
	height:30px;
	float:left;
	padding:5px 10px 5px 10px;
	font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size:12px;
	color:#FFF;
	font-weight:bold;
	line-height:34px;
	background:url(<?php echo $theamPath ?><?php echo $theam ?>/backgrounds/block_inner_back.png);
	}

.flexigrid div.fbutton1 .add
		{
			float:right;
			border-radius: 4px;
			padding:4px 10px 4px 10px;
			background-color:<?php echo $theamMenuButtonColor ?> !important;
			background:url(cms/images/fixed_images/grid_icon/add.png) no-repeat center left;;
			color:#FFF;
		}	
	
.flexigrid div.fbutton .alfabet:hover	
		{
			padding:5px;
			background-color:<?php echo $theamMenuButtonColor ?> !important;
			color:#FFF;
			border-radius: 2px;
		}
		
th{
	color:<?php echo $theamMenuFontColor ?>;
	font-weight:bold;	
}

.flexigrid div.hDiv th.thOver div, .flexigrid div.hDiv th.sorted.thOver div
	{
	border-bottom: 1px solid <?php echo $theamMenuFontColor ?>;;
	padding-bottom: 4px;
	}
	
.flexigrid div.pDiv
	{
	background:none ;
	border: 1px solid #ccc;
	border-top: 0px;
	overflow: hidden;
	white-space: nowrap;
	color:#FFF;
	}
/*grid style*/

/*form style*/
.input2:hover, .input3:hover, .textarea1:hover, .select2:hover, .select3:hover{
	border-top:1px solid <?php echo $theamMenuButtonColor ?>;
	}

/*form style*/
</style>
</head>
<body>

<!--header start-->
<div id="header_outer" style="background:url(<?php echo $theamPath ?><?php echo $theam ?>/backgrounds/menu_back.gif) repeat-x">
	<div id="header_inner">
      <div id="header_top">
       	<div class="header_top_left">&nbsp;&nbsp; </div>
            <div class="header_top_right">
           		<div id="admin_button" style="cursor:default;"><a href="#" id="admin_link" style="cursor:default;"><span style="cursor:default;"><?php if($NICUser){echo $loggedPositionName;}else{echo "Anonymous User";}?></span></a></div>
               <!-- <div id="mail_button"><a href="#"></a><div id="mail_alert" class="alert">05</div></div>-->
               <form id="form1" name="form1" action="" method="POST">
                 <?php if($NICUser){?><div id="user_welcome">Welcome <?php echo ucwords(strtolower($_SESSION["fullName"])); ?>, &nbsp;&nbsp; <span class="link1" style="cursor:pointer;" onClick="logoutForm('mail');">Logout?</span></div><?php }?></form>
            </div>
        </div>
        <div id="header_logo" style="margin-top:0px;"><img src="images/header.png" width="960" height="150" /></div>
       
        <div style="clear:both"></div>
	</div>
</div>

<!--header end-->
<div id="main_content_outer">
	<div id="main_content_inner">
    
		<div class="main_content_inner_block">
			<div class="mcib_top"></div>
            <div class="mcib_middle">
             <h2 style="text-align:center;">The requested page cannot be found!</h2>
                        
                        <br>
                        
                        <a href="module_main.php"><h3 style="text-align:center;">GO TO HOME PAGE</h3></a>
            </div>
            <div class="mcib_bottom"></div>
		</div>
      
        
    
  
    
</div>
</div>
</body>
</html>