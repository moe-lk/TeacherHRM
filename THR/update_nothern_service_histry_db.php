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

/* $fieldMErge=array("SNICno","SchoolID","FromDate","ToDate");//
$selDataMerge=$dbObj->querySelect("service",$fieldMErge,array("ServiseSNo"),"A","SNICno!=''");
echo "Total"; echo count($selDataMerge);echo "<br>"; */

$fieldMErge=array("SNICno","SchoolID","FromDate","ToDate");

$sqlStr="SchoolID!=''";
//$sqlStr="SNICno='625160901V'";
$selDataMerge=$dbObj->querySelect("service",$fieldMErge,array("FromDate"),"A",$sqlStr);
//echo "Teachers"; echo count($selDataMerge);echo "<br>";
$y=$t=0;
 for($x=0;$x<count($selDataMerge);$x++){
	$SNICno=trim($selDataMerge[$x][0]);
	$SchoolID=$selDataMerge[$x][1];
	$FromDate=$selDataMerge[$x][2];
	$ToDate=$selDataMerge[$x][3];
	
	
	$selDataSchool=$dbObj->querySelect("schdata",array("CenID"),array("Scid"),"A","Scid='$SchoolID' and CenID!=''");
	$CenID=$selDataSchool[0][0];
	
	$CensusCode="SC".str_pad($CenID, 5, '0', STR_PAD_LEFT);
	
	$LastUpdateEmis="2016-09-21";
	$UpdateByEmis="System Migration NP";
	$RecordLogEmis="Data Migration of Nothern Provice - Insert";	
	$RecordLogEmisU="Data Migration of Nothern Provice - Update";	
	
	
	$sqlCAllreadyAdd = "SELECT ID FROM StaffServiceHistory WHERE NIC='$SNICno' and InstCode='$CensusCode' and AppDate='$FromDate'";
	$isAvailableAdd=$db->rowAvailable($sqlCAllreadyAdd);
	if($isAvailableAdd==1){
		
	}else{
		$y++;
		$queryRegis = "INSERT INTO StaffServiceHistory				   (NIC,ServiceRecTypeCode,AppDate,InstCode,SecGRCode,WorkStatusCode,ServiceTypeCode,EmpTypeCode,PositionCode,Cat2003Code,Reference,LastUpdate,UpdateBy,RecordLog)
			 VALUES				   
		('$SNICno','$ServiceRecTypeCode','$FromDate','$CensusCode','$SecGRCode','$WorkStatusCode','$ServiceTypeCode','$EmpTypeCode','$PositionCode','$Cat2003Code','$Reference','$LastUpdateEmis','$UpdateByEmis','$RecordLogEmis')";
		$db->runMsSqlQuery($queryRegis);
	}
	
}

echo $y;

echo "Completed.";

?>