<?php

session_start();
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include '../../db_config/DBManager.php';
include('../myfunction.php');
$db = new DBManager();

$nicNO = $_SESSION["NIC"];
$accLevel = $_SESSION["AccessLevel"];
$RequestType = $_REQUEST["RequestType"];

if($RequestType=='getTeacherNICTT'){
	$GradeID = $_REQUEST["GradeID"];
    $SchoolID = $_REQUEST["SchoolID"];
    $ClassID=$_REQUEST["ClassID"];
	$subjCode=$_REQUEST["subjCode"];
	
	if($subjCode!=''){// first period of each sumbect
		$sql="SELECT TeacherID FROM TG_SchoolSubjectTeacher where SchoolID='$SchoolID' and GradeID='$GradeID' and ClassID='$ClassID' and SubjectID='$subjCode'";
		$stmt = $db->runMsSqlQuery($sql);
		$subjField="";
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$TeacherID=$row['TeacherID'];
			
		}
	}
	
	if (isset($_REQUEST['resultAsJson'])){
		$response = new stdClass();
		$response->teacherID = $TeacherID;
		echo json_encode($response);
	}
}

if($RequestType=='loadGroupSubject'){
	$SchoolID=$_REQUEST['SchoolID'];
	$GradeID=$_REQUEST['GradeID'];
	$ClassID=$_REQUEST['ClassID'];
	$subjCode=$_REQUEST['subjCode'];
	$totalRows=$_REQUEST['totalRows'];
	$det="";
	 //echo $thisSubCode;
		$groupCheck=getGroupSubjects($SchoolID,$GradeID,$subjCode);
		
		$groupCode=explode(",",$groupCheck);
			for($p=0;$p<count($groupCode);$p++){
				$codeGroup=trim($groupCode[$p]);
				if($codeGroup!=''){
					$groubSub="TT".$valueField."_".$p;
					$teacheName=getTeacherName($SchoolID,$GradeID,$ClassID,$codeGroup);
					$groupCubName=getSubjectNameCommon($codeGroup);
					//echo "$teacheName [$groupCubName]";
				//echo "<input type=\"hidden\" id=\"$groubSub\" name=\"$groubSub\" value=\"$codeGroup\">";
		$det.="<br>";		
	
	$det.="<select name=\"$groubSub\" class=\"select2a_new\" id=\"$groubSub\">
	<option value=\"$codeGroup\">$teacheName [$groupCubName]</option>
	</select>";
	 }
			}
			echo $det;
    if($det){                         
		//echo $det;
	}else{
		//echo $det="";	
	}
				
}

if($RequestType=='checkTeacherAvailability'){
	$GradeID = $_REQUEST["GradeID"];
    $SchoolID = $_REQUEST["SchoolID"];
    $ClassID=$_REQUEST["ClassID"];
    $fieldIDTT=$_REQUEST["fieldIDTT"];
	$TeacherID=$_REQUEST["TeacherID"];
	$periodID=$_REQUEST["periodID"];
	$dayTT=$_REQUEST["dayTT"];
	$subjCode=$_REQUEST["subjCode"];
	
	$failTT=$successTT="";
	if($TeacherID!=''){// first period of each sumbect
		
		$countTotal="SELECT * FROM TG_SchoolTimeTable where SchoolID='$SchoolID' and FieldID='$fieldIDTT' and TeacherID='$TeacherID'";//and GradeID='$GradeID' and ClassID='$ClassID' 
		$TotaRows=$db->rowCount($countTotal);
		if($TotaRows>=1){
			$StatusTT='Err';
			//$failTT.=$FieldID.",";
		}else{ 
			$StatusTT='Suc';
			//$successTT.=$FieldID.",";
		}
		
	}
	
	if (isset($_REQUEST['resultAsJson'])){
		$response = new stdClass();
		$response->FieldIDTT = $fieldIDTT;
		$response->StatusTT = $StatusTT;
		$response->successTT = $successTT;
		$response->failTT = $failTT;
		$response->totalRows = $TotaRows;
		$response->teacherID = $TeacherID;
		echo json_encode($response);
	}
	
}

if($RequestType=='checkTeacherAvailability_old'){
	$GradeID = $_REQUEST["GradeID"];
    $SchoolID = $_REQUEST["SchoolID"];
    $ClassID=$_REQUEST["ClassID"];
    $fieldIDTT=$_REQUEST["fieldIDTT"];
	$TeacherID=$_REQUEST["TeacherID"];
	$periodID=$_REQUEST["periodID"];
	$dayTT=$_REQUEST["dayTT"];
	$subjCode=$_REQUEST["subjCode"];
	
	$failTT=$successTT="";
	if($subjCode!=''){// first period of each sumbect
		$sql="SELECT * FROM TG_SchoolTimeTable where SchoolID='$SchoolID' and GradeID='$GradeID' and ClassID='$ClassID' and SubjectID='$subjCode'";
		$stmt = $db->runMsSqlQuery($sql);
		$subjField="";
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$PeriodNumber=$row['PeriodNumber'];
			$Day=$row['Day'];
			$FieldID=$row['FieldID'];
			/* $firstP=explode("_",$FieldID);
			if(count($firstP)==2){
				$FieldID=$firstP[0];
			} */
			
			$countTotal="SELECT * FROM TG_SchoolTimeTable where SchoolID='$SchoolID' and PeriodNumber='$PeriodNumber' and Day='$Day' and TeacherID='$TeacherID'";//and GradeID='$GradeID' and ClassID='$ClassID' 
			$TotaRows=$db->rowCount($countTotal);
			if($TotaRows>=1){
				//$StatusTT='Err';
				$failTT.=$FieldID.",";
			}else{ 
				//$StatusTT='Suc';
				$successTT.=$FieldID.",";
			}
			
			$sqlInsertTT="UPDATE TG_SchoolTimeTable
			   SET TeacherID='$TeacherID'
		 WHERE SchoolID='$SchoolID' and GradeID='$GradeID' and ClassID='$ClassID' and FieldID='$FieldID'";
			   
			$db->runMsSqlQuery($sqlInsertTT);
			
			//$subjField.=$row['FieldID'];
		}
	}
	
	if($subjCode==''){ 
		$countTotal="SELECT * FROM TG_SchoolTimeTable where SchoolID='$SchoolID' and PeriodNumber='$periodID' and Day='$dayTT' and TeacherID='$TeacherID'";//and GradeID='$GradeID' and ClassID='$ClassID' 
		$TotaRows=$db->rowCount($countTotal);
		if($TotaRows>=1){
			$StatusTT='Err';
		}else{ 
			$StatusTT='Suc';
		}
		
		$sqlInsertTT="UPDATE TG_SchoolTimeTable
			   SET TeacherID='$TeacherID'
		 WHERE SchoolID='$SchoolID' and GradeID='$GradeID' and ClassID='$ClassID' and FieldID='$fieldIDTT'";
			   
		$db->runMsSqlQuery($sqlInsertTT);
	}
	
	if (isset($_REQUEST['resultAsJson'])){
		$response = new stdClass();
		$response->FieldIDTT = $fieldIDTT;
		$response->StatusTT = $StatusTT;
		$response->successTT = $successTT;
		$response->failTT = $failTT;
		$response->totalRows = $TotaRows;
		$response->teacherID = $TeacherID;
		echo json_encode($response);
	}
	
}

if($RequestType=='checkDuplicateTeacher'){
    $GradeID = $_REQUEST["GradeID"];
	$ClassID = $_REQUEST["ClassID"];
    $SchoolID = $_REQUEST["SchoolID"];
    $totalRows=$_REQUEST["totalRows"];
    $currentTT=$_REQUEST["currentTT"];
    $currentto=explode(',',$currentTT);
	
	$failTT=$successTT="";
	
	for($x=0;$x<count($currentto);$x++){
		$subjCode=$currentto[$x];
		$d=$x+1;
		$fieldIDTT="TT".$d;
		
		$sql="SELECT TeacherID FROM TG_SchoolSubjectTeacher where SchoolID='$SchoolID' and GradeID='$GradeID' and ClassID='$ClassID' and SubjectID='$subjCode'";
		$stmt = $db->runMsSqlQuery($sql);
		$subjField="";
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$TeacherID=trim($row['TeacherID']);
			
			if($TeacherID!=''){// first period of each sumbect
				$countTotal="SELECT * FROM TG_SchoolTimeTable where SchoolID='$SchoolID' and FieldID='$fieldIDTT' and TeacherID='$TeacherID' and ClassID!='$ClassID'";//and GradeID='$GradeID' and ClassID='$ClassID' //and GradeID!='$GradeID'
				$TotaRows=$db->rowCount($countTotal);
				if($TotaRows>=1){
					//$StatusTT='Err';
					$failTT.=$fieldIDTT.",";
				}else{ 
					//$StatusTT='Suc';
					$successTT.=$fieldIDTT.",";
				}
			}
			
		}
	}
    

	if (isset($_REQUEST['resultAsJson'])){
		$response = new stdClass();
		$response->failTT = $failTT;
		$response->totalRows = $totalRows;
		echo json_encode($response);
	}
    
}


if($RequestType=='checkOverLoad'){
    $GradeID = $_REQUEST["GradeID"];
    $SchoolID = $_REQUEST["SchoolID"];
    $totalRows=$_REQUEST["totalRows"];
    $currentTT=$_REQUEST["currentTT"];
    $currentto=explode(',',$currentTT);
    
$sqlSubject="SELECT        TG_SchoolSubjectMaster.SubjectID, CD_Subject.SubjectName, TG_SchoolSubjectMaster.PeriodsPerWeek
FROM            TG_SchoolSubjectMaster INNER JOIN
                         CD_Subject ON TG_SchoolSubjectMaster.SubjectID = CD_Subject.SubCode
where TG_SchoolSubjectMaster.SchoolID='$SchoolID' and TG_SchoolSubjectMaster.GradeID='$GradeID'";

$stmtSub = $db->runMsSqlQuery($sqlSubject);
$errorsSubjOver=$errorsSubjUnder="";
//$errorsSubjOver=array();
//$errorsSubjUnder=array();
while ($row = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
   $SubjectID=trim($row['SubjectID']);  
   $PeriodsPerWeek=$row['PeriodsPerWeek'];
   $countval = count(array_keys($currentto, $SubjectID, true));
   
   if($PeriodsPerWeek<$countval){
       //$errorsSubjOver[]=$SubjectID;
       $errorsSubjOver.=$SubjectID.",";
   }
   if($PeriodsPerWeek>$countval){
       $errorsSubjUnder.=$SubjectID.",";
      // $errorsSubjUnder[]=$SubjectID;
   }
   
}

	if (isset($_REQUEST['resultAsJson'])){
		$response = new stdClass();
		$response->overLMT = $errorsSubjOver;
		$response->underLMT = $errorsSubjUnder;
		$response->totalRows = $totalRows;
		echo json_encode($response);
	}
    
}

if ($RequestType == "getClassData") {
    $GradeID = $_POST["GradeID"];
	$SchoolID = $_POST["SchoolID"];
    $LOGGEDUSERID = $nicNO; // 172839946V
    $ACCESSLEVEL = $accLevel; //     3000
    
    if ($GradeID == "")
        $GradeID = null;
	
	if ($SchoolID == "")
        $SchoolID = null;


    $params1 = array(
        array($GradeID, SQLSRV_PARAM_IN),
        array($SchoolID, SQLSRV_PARAM_IN)
    );

    $sql = "{call SP_TG_GetClassOfGrade( ?, ?)}";
    $dataSchool = "<option value=\"\">-Select-</option>";
    $stmt = $db->runMsSqlQuery($sql, $params1);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $dataSchool.= '<option value=' . $row['ID'] . '>' . $row['ClassID'] . '</option>';
    }




    $result = array();
    $result[0] = $dataSchool;
    echo json_encode($result);
}

?>
