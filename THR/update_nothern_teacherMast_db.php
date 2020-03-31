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

/* $fieldMErge=array("SchID","Title","FName","LName","NICno","Gender","DateOfFirstAppoint");//
$selDataMerge=$dbObj->querySelect("stafftb",$fieldMErge,array("SchID"),"A","NICno!=''");
echo "Total"; echo count($selDataMerge);echo "<br>"; */

$fieldMErge=array("SchID","Title","FName","LName","NICno","Gender","DateOfFirstAppoint","MobileNo","DateOfBirth","Gender","Race","CivilStatus","SpouseNIC","NameOfTheSpouse","SpouseOfficialAddr","emailAdd","PermanantAddr","TempAddr","PrivateContactNo");//

$sqlStr="(CurPosition='Assistant Principal') or (CurPosition='Deputy Principal') or (CurPosition='Performing Principal') or (CurPosition='Principal') or (CurPosition='SectionalHead') or (CurPosition='Taacher') or (CurPosition='Teacher') or (CurPosition='Teacher (ENGLISH MED') or (CurPosition='Teacher Educator') or (CurPosition='TeacherAssistant')  or (CurPosition='TeacherLibrarian')";

//$sqlStr="NICno='918460187V'";

$selDataMerge=$dbObj->querySelect("stafftb",$fieldMErge,array("SchID"),"A",$sqlStr);
//echo "Teachers"; echo count($selDataMerge);echo "<br>";
$y=$t=$tt=0;
 for($x=0;$x<count($selDataMerge);$x++){
	$SchID=$selDataMerge[$x][0];
	$Title=$selDataMerge[$x][1];
	$FName=$selDataMerge[$x][2];
	$LName=$selDataMerge[$x][3];
	$NICno=$selDataMerge[$x][4];
	$Gender=$selDataMerge[$x][5];
	$DateOfFirstAppoint=$selDataMerge[$x][6];
	$MobileNo=$selDataMerge[$x][7];
	$DateOfBirth=$selDataMerge[$x][8];
	$Gender=$selDataMerge[$x][9];
	$Race=$selDataMerge[$x][10];
	$CivilStatus=$selDataMerge[$x][11];
	$SpouseNIC=$selDataMerge[$x][12];
	$NameOfTheSpouse=$selDataMerge[$x][13];
	$SpouseOfficialAddr=$selDataMerge[$x][14];
	$emailAdd=$selDataMerge[$x][15];
	$PermanantAddr=stripslashes($selDataMerge[$x][16]);
	$TempAddr=stripslashes($selDataMerge[$x][17]);
	$PrivateContactNo=$selDataMerge[$x][18];
	
	if($LName!='' or $LName!='NULL'){
		$lNme=explode(".",$LName);
		$totalNme=count($lNme);
		
		$surname=$lNme[$totalNme-1];
	}else{
		$surname=="";
	}
	
	if($FName!='' or $FName!='NULL'){
		$initialsName=initials($FName); 
	}else{
		$initialsName=""; 
	}
		
	$NICEmis=$NICno;
	$SurnameWithInitialsEmis=$surname." ".$initialsName;
	
	$FullNameEmis=$FName." ".$surname;
	if($FName=='' or $FName=='NULL')$FullNameEmis=$surname;
	//echo $initialsName;exit();
	if($Title=='Bra Sri.' || $Title=='Bram Sri')$TitleEmis="T01";
	
	if($Title=='Miss' || $Title=='Miss.')$TitleEmis="T10";
	if($Title=='Mr' || $Title=='Mr.')$TitleEmis="T08";
	if($Title=='Mrs' || $Title=='Mrs.')$TitleEmis="T19";
	if($Title=='MS' || $Title=='Ms.')$TitleEmis="T10";
	if($Title=='Rev' || $Title=='Rev.')$TitleEmis="T01";
	if($Title=='Rev.Bro.')$TitleEmis="T04";
	if($Title=='Rev.Fath')$TitleEmis="T03";
	if($Title=='Rev.sis' || $Title=='Rev.Sis.')$TitleEmis="T05";	
	if($Title=='Others')$TitleEmis="T11";
	
	if($Title=='Rev.Sr.')$TitleEmis="T01";
	
	$MobileTelEmis=$MobileNo;
	$DOBEmis=$DateOfBirth;
	
	if($Gender=='Female'){
		$GenderCodeEmis=2;
	}else if($Gender=='Male'){
		$GenderCodeEmis=1;
	}else{
		$GenderCodeEmis="";
	}
	
	if($Race=='Sinhala' || $Race=='Sinhalese' || $Race=='Sinhalis' || $Race=='Srilankan')$EthnicityCodeEmis=1;
	if($Race=='Tamil') $EthnicityCodeEmis=2;
	if($Race=='Muslim') $EthnicityCodeEmis=4;
	
	//$ReligionCodeEmis="";
	
	$CivilStatusCodeEmis=4;//Other
	
	if($CivilStatus=='MARIED' || $CivilStatus=='Marred' || $CivilStatus=='Marrid' || $CivilStatus=='Married' || $CivilStatus=='Merid' || $CivilStatus=='Merried')$CivilStatusCodeEmis=2;
	
	if($CivilStatus=='Singal' || $CivilStatus=='Single')$CivilStatusCodeEmis=1;
	
	if($CivilStatus=='Widow' || $CivilStatus=='Widower')$CivilStatusCodeEmis=3;
	
	$SpouseNameEmis=$NameOfTheSpouse;
	$SpouseNICEmis=$SpouseNIC;
	$SpouseOccupationCodeEmis="";
	$SpouseDOBEmis="";
	$SpouseOfficeAddr=$SpouseOfficialAddr;
	
	$DOFAEmis=date('Y-m-d', strtotime($DateOfFirstAppoint));
	$DOACATEmis="N/A";
	$ProvinceEmis="Northern";
	$HQualificatinRefEmis="";
	$CurServiceRefEmis="";
	$LastUpdateEmis="2016-09-21";
	$UpdateByEmis="System Migration NP";
	$RecStatusEmis="";
	$emailaddrEmis=$emailAdd;
	$RecordLogEmis="Data Migration of Nothern Provice - Insert";	
	$RecordLogEmisU="Data Migration of Nothern Provice - Update";	
	
	
	$PerResRefEmis="";
	$CurResRefEmis="";
	//////////////////
	
	//Teacher mast table
	$sqlCAllready = "SELECT NIC FROM TeacherMast WHERE NIC='$NICno'";
	$isAvailable=$db->rowAvailable($sqlCAllready);
	if($isAvailable==1){
		 //NIC='$NICEmis', ReligionCode='$ReligionCodeEmis',PerResRef='$PerResRefEmis',CurResRef='$CurResRefEmis', ,SpouseOccupationCode='$SpouseOccupationCodeEmis',SpouseDOB='$SpouseDOBEmis',SpouseOfficeAddr='$SpouseOfficeAddrEmis' ,CurServiceRef='$CurServiceRefEmis' ,EthnicityCode='$EthnicityCodeEmis' ,GenderCode='$GenderCodeEmis',DOB='$DOBEmis' 
		/*echo  $queryMainUpdate = "UPDATE TeacherMast SET SurnameWithInitials='$SurnameWithInitialsEmis',FullName='$FullNameEmis',Title='$TitleEmis',MobileTel='$MobileTelEmis',CivilStatusCode='$CivilStatusCodeEmis',SpouseName='$SpouseNameEmis', SpouseNIC='$SpouseNICEmis',DOFA='$DOFAEmis',DOACAT='$DOACATEmis',Province='$ProvinceEmis',HQualificatinRef='$HQualificatinRefEmis',LastUpdate='$LastUpdateEmis',UpdateBy='$UpdateByEmis',RecStatus='$RecStatusEmis',emailaddr='$emailaddrEmis',RecordLog='$RecordLogEmis' WHERE NIC='$NICEmis'"; //echo "<br>";if($y==2)exit(); 
		$db->runMsSqlQuery($queryMainUpdate);*/
			
		//echo "Available<br>";
		/* $queryMainUpdateTm = "UPDATE TeacherMast SET PerResRef='$PerResRefEmis',CurResRef='$CurResRefEmis' WHERE NIC='$NICEmis'"; 
		$db->runMsSqlQuery($queryMainUpdateTm); */
		
	}else{
		$y++;
		echo "TM_";echo $NICEmis; echo "<br>";
		$queryRegis = "INSERT INTO TeacherMast				   (NIC,SurnameWithInitials,FullName,Title,PerResRef,CurResRef,MobileTel,DOB,GenderCode,EthnicityCode,ReligionCode,CivilStatusCode,SpouseName,SpouseNIC,SpouseOccupationCode,SpouseDOB,SpouseOfficeAddr,DOFA,DOACAT,Province,HQualificatinRef,CurServiceRef,LastUpdate,UpdateBy,RecStatus,emailaddr,RecordLog)
			 VALUES				   
		('$NICEmis','$SurnameWithInitialsEmis','$FullNameEmis','$TitleEmis','$PerResRefEmis','$CurResRefEmis','$MobileTelEmis','$DOBEmis','$GenderCodeEmis','$EthnicityCodeEmis','$ReligionCodeEmis','$CivilStatusCodeEmis','$SpouseNameEmis','$SpouseNICEmis','$SpouseOccupationCodeEmis','$SpouseDOBEmis','$SpouseOfficeAddrEmis','$DOFAEmis','$DOACATEmis','$ProvinceEmis','$HQualificatinRefEmis','$CurServiceRefEmis','$LastUpdateEmis','$UpdateByEmis','$RecStatusEmis','$emailaddrEmis','$RecordLogEmis')";
		$db->runMsSqlQuery($queryRegis);
		
	} 
	
	

}
echo "Completed.";
echo "***********<br>;";
echo $tt;
echo "<br>";
echo $t;
echo "<br>";
echo $y;

?>