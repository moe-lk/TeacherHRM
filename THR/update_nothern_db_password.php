<?php 
include("db_include_tg/config.php");
include("db_include_tg/class_db.php");
include("db_include_tg/common.php");
include("db_include_tg/my_functions.php");
$dbObj=new mySqlDB;
$dbObj->connect($hostname,$username,$password);
$dbObj->dBSelect($dbname);

include "db_config/DBManager.php";
$db = new DBManager();

/* $fieldMErge=array("SchID");//
$selDataMerge=$dbObj->querySelect("stafftb",$fieldMErge,array("SchID"),"A","NICno!=''");
echo "Total"; echo count($selDataMerge);echo "<br>"; */

$fieldMErge=array("SchID","NICno","DateOfFirstAppoint","CurPosition");//
$sqlStr="(CurPosition='Assistant Principal') or (CurPosition='Deputy Principal') or (CurPosition='Performing Principal') or (CurPosition='Principal') or (CurPosition='SectionalHead') or (CurPosition='Taacher') or (CurPosition='Teacher') or (CurPosition='Teacher (ENGLISH MED') or (CurPosition='Teacher Educator') or (CurPosition='TeacherAssistant')  or (CurPosition='TeacherLibrarian')";

//$sqlStr="NICno='918460187V'";

$selDataMerge=$dbObj->querySelect("stafftb",$fieldMErge,array("SchID"),"A",$sqlStr);
//echo "Teachers"; echo count($selDataMerge);echo "<br>";
$y=$t=0;
 for($x=0;$x<count($selDataMerge);$x++){ 
	$SchID=$selDataMerge[$x][0];
	$NICno=$selDataMerge[$x][1];
	$DateOfFirstAppoint=$selDataMerge[$x][2];
	$CurPosition=$selDataMerge[$x][3];	
	
	$password1=date('Y-m-d', strtotime($DateOfFirstAppoint));
	$CurPassword=str_replace("-","",$password1);
	
	$LastUpdate="2016-09-21";
	
	$AccessRole="TEACHER";
	$AccessLevel=1000;
	
	if($CurPosition=='Principal'){
		$AccessRole="PRINCIPAL";
		$AccessLevel=3000;
	}
	
	$sqlCAllreadyAdd = "SELECT NICNo FROM Passwords WHERE NICNo='$NICno'";// and AppDate='$FromDate'
	$isAvailableAdd=$db->rowAvailable($sqlCAllreadyAdd);
	if($isAvailableAdd==1){
		/* echo $queryAddUpdate = "UPDATE Passwords SET CurPassword='$CurPassword' WHERE NIC='$NICno'"; 
		$db->runMsSqlQuery($queryAddUpdate); */
	}else{
		$y++;
		$queryRegis = "INSERT INTO Passwords (NICNo,CurPassword,LastUpdate,AccessRole,AccessLevel)
			 VALUES				   
		('$NICno','$CurPassword','$LastUpdate','$AccessRole','$AccessLevel')";
		//$db->runMsSqlQuery($queryRegis);
			
	}
	
}
echo $y;
echo "Complete";

?>