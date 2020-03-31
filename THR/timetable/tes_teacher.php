<?php
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();
$SchoolID="SC05428";
$NIC="582301433V";
$NumberOfPeriods=40;
//$FieldID="TT19";
function getTeachingDetails($SchoolID,$NIC,$PeriodNumberM,$DayTT,$FieldID){
	global $db ;
		$params4 = array(
			array($SchoolID, SQLSRV_PARAM_IN),
			array($NIC, SQLSRV_PARAM_IN),
			array($FieldID, SQLSRV_PARAM_IN),
			//array($DayTT, SQLSRV_PARAM_IN)
		);
		$sql = "{call SP_TG_GetTeachingDetailsTT( ?, ?, ?)}";
		$stmt = $db->runMsSqlQuery($sql, $params4);
		$SubjOver=array();
		$subject=$grade=",";
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$GradeTitle=$row['GradeTitle'];//." - ".$row['SubCode'];//SubjectName
			$SubjectName=$row['SubjectName'];
			$ClassID=$row['ClassID'];
			/* if(!in_array($SubjectName,$SubjOver)){
				$SubjOver[]=$SubjectName;
				$subject.=$SubjectName.",";
			} */
			$subject.=$SubjectName.",";
			$grade.=$GradeTitle."-".$ClassID.",";
			
			
		}
		$subjectFinal=substr($subject, 0, -1);
		$gradeFinal=substr($grade, 0, -1);
		$subjectFinal2=substr($subjectFinal, 1);
		$gradeFinal2=substr($gradeFinal, 1);
		if($subjectFinal2)return $summery=$gradeFinal2."(".$subjectFinal2.")"; 
		//return $SubjectName;
}
?>
<?php //exit();
		  for($i=1;$i<$NumberOfPeriods+1;$i++){
			  $PeriodNumberM=$i%$NumberOfPeriods;
			  if($PeriodNumberM==0)$PeriodNumberM=$NumberOfPeriods;
			  echo $FieldID="TT".$i;echo "-";
			  echo $teachingDetails=getTeachingDetails($SchoolID,$NIC,$PeriodNumberM,'Monday',$FieldID);echo "<br>";
			  
		  }?>