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

//if (isset($_POST["FrmSubmit"])) {
	$msg="";
    $InstCode=$_REQUEST['InstCode'];
	$DivCode=$_REQUEST['DivisionCode'];
	$ZonCode=$_REQUEST['ZoneCode'];
	$DistCode=$_REQUEST['DistrictCode'];
	$ProCode=$_REQUEST['ProCode'];
	$nicNOsrch=$_REQUEST['srchNic'];

	$_SESSION['loggedSchoolSearch']=$InstCode;
	//$_SESSION['NIC']=$nicNOsrch;
	if($nicNOsrch!=''){
		$countSql="SELECT NIC FROM TeacherMast where NIC='$nicNOsrch'";
		$isAvailable=$db->rowAvailable($countSql);
		if($isAvailable==1){
			//header("Location: retirementType-3--$nicNOsrch.html") ;
			header("Location: retirementList-5--$nicNOsrch.html") ;
			exit() ;
		}else{
			$msg.= "Given NIC not exist.<br>";
		}
	}else if($InstCode!=''){
		//echo "hi";
		header("Location: retirementList-5--$InstCode.html") ;
		exit() ;
	}else if($DivCode!=''){
		header("Location: retirementList-5--$DivCode.html") ;
		exit() ;
	}else if($ZonCode!=''){
		header("Location: retirementList-5--$ZonCode.html") ;
		exit() ;
	}else if($DistCode!=''){
		header("Location: retirementList-5--$DistCode.html") ;
		exit() ;
	}else if($ProCode!=''){
		header("Location: retirementList-5--$ProCode.html") ;
		exit() ;
	}
	if($msg==''){
	//if($accLevel=='1000'){
		header("Location:retirementList-5.html");
	/* }else if($accLevel=='3000'){
		header("Location:grade-1.html");
	}else{
		header("Location:teacherList-2.html");
	} */
	exit();
	}
//}



?>