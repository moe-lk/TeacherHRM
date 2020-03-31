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
echo "Total"; echo count($selDataMerge);echo "<br>";
 */
$fieldMErge=array("SchID","NICno","PermanantAddr","TempAddr","PrivateContactNo");//
$sqlStr="(CurPosition='Assistant Principal') or (CurPosition='Deputy Principal') or (CurPosition='Performing Principal') or (CurPosition='Principal') or (CurPosition='SectionalHead') or (CurPosition='Taacher') or (CurPosition='Teacher') or (CurPosition='Teacher (ENGLISH MED') or (CurPosition='Teacher Educator') or (CurPosition='TeacherAssistant')  or (CurPosition='TeacherLibrarian')";

//$sqlStr="NICno='918460187V'";

$selDataMerge=$dbObj->querySelect("stafftb",$fieldMErge,array("SchID"),"A",$sqlStr);
//echo "Teachers"; echo count($selDataMerge);echo "<br>";//exit();
$y=$t=$tt=0;
 for($x=0;$x<count($selDataMerge);$x++){
	$SchID=$selDataMerge[$x][0];
	$NICno=$selDataMerge[$x][1];
	$PermanantAddr=stripslashes($selDataMerge[$x][2]);
	$TempAddr=stripslashes($selDataMerge[$x][3]);
	$PrivateContactNo=$selDataMerge[$x][4];	

	$LastUpdateEmis="2016-09-21";
	$AppDate="2016-09-21";
	$UpdateByEmis="System Migration NP";
	$RecordLogEmis="Data Migration of Nothern Provice - Insert";	
	$RecordLogEmisU="Data Migration of Nothern Provice - Update";	
	
	//Address history CUR
	$sqlCAllreadyAdd = "SELECT ID FROM StaffAddrHistory WHERE NIC='$NICno' and AddrType='CUR'";
	$isAvailableAdd=$db->rowAvailable($sqlCAllreadyAdd);
	if($isAvailableAdd==1){//$PermanantAddr,$TempAddr
		
		//echo $queryAddUpdate = "UPDATE StaffAddrHistory SET Address='$TempAddr',Tel='$PrivateContactNo',LastUpdate='$LastUpdateEmis',UpdateBy='$UpdateByEmis',RecordLog='$RecordLogEmis' WHERE NIC='$NICno' and AddrType='CUR'"; //echo "<br>";if($y==2)exit();
		//$db->runMsSqlQuery($queryAddUpdate);
			
		//echo "Available<br>";
		
		/* $stmtCr = $db->runMsSqlQuery($sqlCAllreadyAdd);
		$rowCr = sqlsrv_fetch_array($stmtCr, SQLSRV_FETCH_ASSOC);
		$newCURID=$rowCr['ID']; */
					  
	}else{
		if($TempAddr!=''){
			$tt++; 
			echo "AD_CUR_";echo $NICEmis; echo "<br>";
			$queryAdd = "INSERT INTO StaffAddrHistory				   (NIC,AddrType,Address,Tel,AppDate,LastUpdate,UpdateBy,RecordLog)
				 VALUES				   
			('$NICno','CUR','$TempAddr','$PrivateContactNo','$AppDate','$LastUpdateEmis','$UpdateByEmis','$RecordLogEmis')";
			$newCURID=$db->runMsSqlQueryInsert($queryAdd);
		}
		
		
	} 
	
	//Address history PER
	$sqlCAllreadyAdd = "SELECT ID FROM StaffAddrHistory WHERE NIC='$NICno' and AddrType='PER'";
	$isAvailableAdd=$db->rowAvailable($sqlCAllreadyAdd);
	if($isAvailableAdd==1){//$PermanantAddr,$TempAddr
		  
		//echo $queryAddUpdate = "UPDATE StaffAddrHistory SET Address='$PermanantAddr',Tel='$PrivateContactNo',LastUpdate='$LastUpdateEmis',UpdateBy='$UpdateByEmis',RecordLog='$RecordLogEmis' WHERE NIC='$NICno' and AddrType='CUR'"; //echo "<br>";if($y==2)exit();
		//$db->runMsSqlQuery($queryAddUpdate);
			
		//echo "Available<br>";
		/* $stmtPr = $db->runMsSqlQuery($sqlCAllreadyAdd);
		$rowPr = sqlsrv_fetch_array($stmtPr, SQLSRV_FETCH_ASSOC);
		$newPERID=$rowPr['ID']; */
			
	}else{
		if($PermanantAddr!=''){
			$t++;
			echo "AD_PER_";echo $NICEmis; echo "<br>";
			$queryAdd = "INSERT INTO StaffAddrHistory				   (NIC,AddrType,Address,Tel,AppDate,LastUpdate,UpdateBy,RecordLog)
				 VALUES				   
			('$NICno','PER','$PermanantAddr','$PrivateContactNo','$AppDate','$LastUpdateEmis','$UpdateByEmis','$RecordLogEmis')";
			$newPERID=$db->runMsSqlQueryInsert($queryAdd);
		}
		
	} 
	
}
echo "***********<br>;";
echo $tt;
echo "<br>";
echo $t;
echo "<br>";
echo $y;

?>