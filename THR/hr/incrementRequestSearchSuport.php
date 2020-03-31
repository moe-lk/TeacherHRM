<?php 
if (isset($_POST["FrmSubmit"])) {
	$msg="";
    $InstCode=$_REQUEST['InstCode'];
	$DivCode=$_REQUEST['DivisionCode'];
	$ZonCode=$_REQUEST['ZoneCode'];
	$DistCode=$_REQUEST['DistrictCode'];

	$_SESSION['loggedSchoolSearch']=$InstCode;
	//$_SESSION['NIC']=$nicNOsrch;
	if($nicNOsrch!=''){
		$countSql="SELECT NIC FROM TeacherMast where NIC='$nicNOsrch'";
		$isAvailable=$db->rowAvailable($countSql);
		if($isAvailable==1){
			header("Location: incrementRequestList-4--$nicNOsrch.html") ;
			exit() ;
		}else{
			$msg.= "Given NIC not exist.<br>";
		}
	}else if($InstCode!=''){
		//echo "hi";
		header("Location: incrementRequestList-3--$InstCode.html") ;
		exit() ;
	}else if($DivCode!=''){
		header("Location: incrementRequestList-3--$DivCode.html") ;
		exit() ;
	}else if($ZonCode!=''){
		header("Location: incrementRequestList-3--$ZonCode.html") ;
		exit() ;
	}else if($DistCode!=''){
		header("Location: incrementRequestList-3--$DistCode.html") ;
		exit() ;
	}
	if($msg==''){
	//if($accLevel=='1000'){
		header("Location:incrementRequestTeacherList-3.html");
	/* }else if($accLevel=='3000'){
		header("Location:grade-1.html");
	}else{
		header("Location:teacherList-2.html");
	} */
	exit();
	}
}



?>