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
	
	//Address history CUR
	$sqlCAllreadyAdd = "SELECT ID FROM StaffAddrHistory WHERE NIC='$NICno' and AddrType='CUR'";
	$stmtCr = $db->runMsSqlQuery($sqlCAllreadyAdd);
	$rowCr = sqlsrv_fetch_array($stmtCr, SQLSRV_FETCH_ASSOC);
	$CurResRefEmis=$rowCr['ID'];
	
	//Address history PER
	$sqlCAllreadyPerAdd = "SELECT ID FROM StaffAddrHistory WHERE NIC='$NICno' and AddrType='PER'";
	$stmtPr = $db->runMsSqlQuery($sqlCAllreadyPerAdd);
	$rowPr = sqlsrv_fetch_array($stmtPr, SQLSRV_FETCH_ASSOC);
	$PerResRefEmis=$rowPr['ID'];
	
	$queryAddUpdate = "UPDATE TeacherMast SET PerResRef='$PerResRefEmis',CurResRef='$CurResRefEmis' WHERE NIC='$NICno'"; 
	$db->runMsSqlQuery($queryAddUpdate);
		
		
	$isAvailableAdd=$db->rowAvailable($sqlCAllreadyAdd);
	
	
}
echo $y;

echo "Completed.";


?>