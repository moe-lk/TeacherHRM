<?php 
function getFieldActValue($reqstdVal,$table,$schField,$srchVale){
	global $db ;
	$sqlStr="Select $reqstdVal from $table where $schField='$srchVale'";
	$stmtSub = $db->runMsSqlQuery($sqlStr);
	while ($rowSub= sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
		$restValue=$rowSub[$reqstdVal];
	}
	return $restValue;
}
function getThisPerionSubjects($SchoolID,$LearningLocation,$valueField,$Day){
	global $db ;
	$commonOut="";
	//$sqlStr="Select GradeID,ClassID from TG_SchoolTimeTable where SchoolID='$SchoolID' and PeriodNumber='$valueField' and Day='$Day'";
	$sqlStr="SELECT        TG_SchoolTimeTable.SubjectID, CD_Subject.SubjectName, TG_SchoolClassStructure.ClassID, TG_SchoolGrade.GradeTitle
FROM            CD_Subject INNER JOIN
                         TG_SchoolTimeTable ON CD_Subject.SubCode = TG_SchoolTimeTable.SubjectID INNER JOIN
                         TG_SchoolClassStructure ON TG_SchoolTimeTable.ClassID = TG_SchoolClassStructure.ID INNER JOIN
                         TG_SchoolGrade ON TG_SchoolTimeTable.GradeID = TG_SchoolGrade.ID
						 where TG_SchoolTimeTable.SchoolID='$SchoolID' and TG_SchoolTimeTable.PeriodNumber='$valueField' and TG_SchoolTimeTable.Day='$Day'";
	$stmtSub = $db->runMsSqlQuery($sqlStr);
	while ($rowSub= sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
		$GradeID=$rowSub["GradeTitle"]."-".$rowSub["ClassID"]."(".trim($rowSub["SubjectName"]).")";
		//$ClassID=$rowSub["ClassID"];
		$commonOut.=$GradeID.",";
	}
	return $commonOut;
}

function getTeachingDetails($SchoolID,$NIC,$PeriodNumber,$Day,$FieldID){
	global $db ;
	$params4 = array(
			array($SchoolID, SQLSRV_PARAM_IN),
			array($NIC, SQLSRV_PARAM_IN),
			array($PeriodNumber, SQLSRV_PARAM_IN),
			array($Day, SQLSRV_PARAM_IN)
		);
		$sql = "{call SP_TG_GetTeachingDetailsTT( ?, ?, ?, ?)}";
		$stmt = $db->runMsSqlQuery($sql, $params4);
		$SubjOver=array();
		$subject=$grade=",";
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$GradeTitle=$row['GradeTitle'];//." - ".$row['SubCode'];//SubjectName
			$SubjectName=$row['SubjectName'];
			$ClassID=$row['ClassID'];
			$TeacherID=$row['TeacherID'];
			//return $NIC;
			if($TeacherID!=''){
				if(!in_array($SubjectName,$SubjOver)){
					$SubjOver[]=$SubjectName;
					$subject.=$SubjectName.",";
				}
				//$subject.=$SubjectName.",";
				$grade.=$GradeTitle."-".$ClassID.",";
			}
			
		}
		$subjectFinal=substr($subject, 0, -1);
		$gradeFinal=substr($grade, 0, -1);
		$subjectFinal2=substr($subjectFinal, 1);
		$gradeFinal2=substr($gradeFinal, 1);
		if($subjectFinal2)return $summery=$gradeFinal2."(".$subjectFinal2.")"; 
		//return $SubjectName;
		
}

function getTTID($SchoolID,$GradeID,$ClassID,$FieldID){
       
	   //include '../db_config/DBManager.php';
global $db ;//= new DBManager();

		$params3 = array(
			array($GradeID, SQLSRV_PARAM_IN),
			array($SchoolID, SQLSRV_PARAM_IN),
			array($ClassID, SQLSRV_PARAM_IN),
			array($FieldID, SQLSRV_PARAM_IN)
		);
		
		$sql = "{call SP_TG_GetTimetableValues( ?, ?, ? ,?)}";
		$stmt = $db->runMsSqlQuery($sql, $params3);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$ID=$row['ID'];//." - ".$row['SubCode'];//SubjectName
		}
		return $ID;
	}

function getGroupSubjects($SchoolID,$GradeID,$SubjectID){
       
	   //include '../db_config/DBManager.php';
global $db ;//= new DBManager();
if($SubjectID!=''){
		$params3 = array(
			array($GradeID, SQLSRV_PARAM_IN),
			array($SchoolID, SQLSRV_PARAM_IN),
			//array($ClassID, SQLSRV_PARAM_IN),
			array($SubjectID, SQLSRV_PARAM_IN)
		);
		
		$sql = "{call SP_TG_GetGroupSubjectValues( ?, ?, ? )}";
		$stmt = $db->runMsSqlQuery($sql, $params3);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$ID=$row['GroupSubject'];//." - ".$row['SubCode'];//SubjectName
		}
		return $ID;
}
}


function getSubjectCodeOnly($SchoolID,$GradeID,$ClassID,$FieldID){
       
	   //include '../db_config/DBManager.php';
global $db ;//= new DBManager();

		$params3 = array(
			array($GradeID, SQLSRV_PARAM_IN),
			array($SchoolID, SQLSRV_PARAM_IN),
			array($ClassID, SQLSRV_PARAM_IN),
			array($FieldID, SQLSRV_PARAM_IN)
		);
		
		$sql = "{call SP_TG_GetTimetableValues( ?, ?, ? ,?)}";
		$stmt = $db->runMsSqlQuery($sql, $params3);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$SubjectID=$row['SubCode'];//." - ".$row['SubCode'];//SubjectName
		}
		return $SubjectID;
	}

//getTeacherName


function getTeacherNIC($SchoolID,$GradeID,$ClassID,$SubjectID){
       
	   //include '../db_config/DBManager.php';
global $db ;//= new DBManager();

		$params3 = array(
			array($GradeID, SQLSRV_PARAM_IN),
			array($SchoolID, SQLSRV_PARAM_IN),
			array($ClassID, SQLSRV_PARAM_IN),
			array($SubjectID, SQLSRV_PARAM_IN)
		);
		
		$sql = "{call SP_TG_GetTimetableTeacherName( ?, ?, ? ,?)}";
		$stmt = $db->runMsSqlQuery($sql, $params3);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$TeacherID=$row['TeacherID'];//." - ".$row['SubCode'];//SubjectName
		}
		return $TeacherID;
	}
	
function getTeacherName($SchoolID,$GradeID,$ClassID,$SubjectID){
       
	   //include '../db_config/DBManager.php';
global $db ;//= new DBManager();

		$params3 = array(
			array($GradeID, SQLSRV_PARAM_IN),
			array($SchoolID, SQLSRV_PARAM_IN),
			array($ClassID, SQLSRV_PARAM_IN),
			array($SubjectID, SQLSRV_PARAM_IN)
		);
		
		$sql = "{call SP_TG_GetTimetableTeacherName( ?, ?, ? ,?)}";
		$stmt = $db->runMsSqlQuery($sql, $params3);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$SurnameWithInitials=$row['SurnameWithInitials'];//." - ".$row['SubCode'];//SubjectName
		}
		return $SurnameWithInitials;
	}

function getSubjectNameOnly($SchoolID,$GradeID,$ClassID,$FieldID){
       
	   //include '../db_config/DBManager.php';
global $db ;//= new DBManager();

		$params3 = array(
			array($GradeID, SQLSRV_PARAM_IN),
			array($SchoolID, SQLSRV_PARAM_IN),
			array($ClassID, SQLSRV_PARAM_IN),
			array($FieldID, SQLSRV_PARAM_IN)
		);
		
		$sql = "{call SP_TG_GetTimetableValues( ?, ?, ? ,?)}";
		$stmt = $db->runMsSqlQuery($sql, $params3);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$SubjectID=$row['SubjectName'];
		}
		return $SubjectID;
	}

function getAlreadyInTeacher($SchoolID,$GradeID,$ClassID,$FieldID,$getTTID){
       
	   //include '../db_config/DBManager.php';
global $db ;//= new DBManager();


	

		$params3 = array(
			array($GradeID, SQLSRV_PARAM_IN),
			array($SchoolID, SQLSRV_PARAM_IN),
			array($ClassID, SQLSRV_PARAM_IN),
			array($FieldID, SQLSRV_PARAM_IN)
		);
		
		$sql = "{call SP_TG_TTGetAlreadyInTeacherNIC( ?, ?, ? ,?)}";
		$stmt = $db->runMsSqlQuery($sql, $params3);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$NIC=$row['TeacherID'];
			if($NIC){
			//." - ".$row['SubCode'];//SubjectName
			//and GradeID='$GradeID' and ClassID='$ClassID' 
			$countTotal="SELECT ID FROM TG_SchoolTimeTable where SchoolID='$SchoolID' and FieldID='$FieldID' and ID!='$getTTID' and TeacherID='$NIC'";
			$TotaRows=$db->rowCount($countTotal);
	
			if($TotaRows==1){
				$NIC.='-Err';
			}else{
					
			}
			}
			
		}
		return $NIC;
	}
	
function getSubjectNameCommon($SubCode){
	global $db ;
	$sqlStr="Select SubjectName from CD_Subject where SubCode='$SubCode'";
	$stmtSub = $db->runMsSqlQuery($sqlStr);
	while ($rowSub= sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
		$restValue=$rowSub['SubjectName'];
	}
	return $restValue;
}
?>