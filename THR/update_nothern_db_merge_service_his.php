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

$fieldMErge=array("SchID","NICno","DateOfEffect");//
$sqlStr="(CurPosition='Assistant Principal') or (CurPosition='Deputy Principal') or (CurPosition='Performing Principal') or (CurPosition='Principal') or (CurPosition='SectionalHead') or (CurPosition='Taacher') or (CurPosition='Teacher') or (CurPosition='Teacher (ENGLISH MED') or (CurPosition='Teacher Educator') or (CurPosition='TeacherAssistant')  or (CurPosition='TeacherLibrarian')";

//$sqlStr="NICno='918460187V'";

$selDataMerge=$dbObj->querySelect("stafftb",$fieldMErge,array("SchID"),"A",$sqlStr);
//echo "Teachers"; echo count($selDataMerge);echo "<br>";
$y=$t=0;
 for($x=0;$x<count($selDataMerge);$x++){
	$SchID=$selDataMerge[$x][0];
	$NICno=$selDataMerge[$x][1];
	$DateOfEffect="";//$selDataMerge[$x][2];	
	
	$selDataSchool=$dbObj->querySelect("schdata",array("CenID"),array("Scid"),"A","Scid='$SchID' and CenID!=''");
	$CenID=$selDataSchool[0][0];
	
	$CensusCode="SC".str_pad($CenID, 5, '0', STR_PAD_LEFT);
	
	$LastUpdateEmis="2016-09-21";
	$UpdateByEmis="System Migration NP";
	$RecordLogEmis="Data Migration of Nothern Provice - Insert";	
	$RecordLogEmisU="Data Migration of Nothern Provice - Update";		
	
	
	$sqlCAllreadyAdd = "SELECT ID FROM StaffServiceHistory WHERE NIC='$NICno' and InstCode='$CensusCode' ORDER BY AppDate Desc";// and AppDate='$FromDate'
	$isAvailableAdd=$db->rowAvailable($sqlCAllreadyAdd);
	if($isAvailableAdd==1){
		$stmtCr = $db->runMsSqlQuery($sqlCAllreadyAdd);
		$rowCr = sqlsrv_fetch_array($stmtCr, SQLSRV_FETCH_ASSOC);
		$newHistID=$rowCr['ID'];
		
		$queryAddUpdate = "UPDATE TeacherMast SET CurServiceRef='$newHistID' WHERE NIC='$NICno'"; 
		$db->runMsSqlQuery($queryAddUpdate);
	}else{
	
		$queryRegis = "INSERT INTO StaffServiceHistory				   (NIC,ServiceRecTypeCode,AppDate,InstCode,SecGRCode,WorkStatusCode,ServiceTypeCode,EmpTypeCode,PositionCode,Cat2003Code,Reference,LastUpdate,UpdateBy,RecordLog)
			 VALUES				   
		('$NICno','$ServiceRecTypeCode','$DateOfEffect','$CensusCode','$SecGRCode','$WorkStatusCode','$ServiceTypeCode','$EmpTypeCode','$PositionCode','$Cat2003Code','$Reference','$LastUpdateEmis','$UpdateByEmis','$RecordLogEmis')";
		$newHistID=$db->runMsSqlQueryInsert($queryRegis);
		
		$queryAddUpdate = "UPDATE TeacherMast SET CurServiceRef='$newHistID' WHERE NIC='$NICno'"; 
		$db->runMsSqlQuery($queryAddUpdate);
			
	}
	
}
echo $y;

echo "Completed.";

?>