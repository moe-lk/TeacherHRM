<?php

session_start();
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
include '../../db_config/DBManager.php';
$db = new DBManager();

$nicNO = $_SESSION["NIC"];
$accLevel = $_SESSION["AccessLevel"];
$RequestType = $_REQUEST["RequestType"];

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
$errorsSubjOver=$errorsSubjUnder=",";
$errorsSubjOver=array();
$errorsSubjUnder=array();
while ($row = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
   $SubjectID=$row['SubjectID'];  
   $PeriodsPerWeek=$row['PeriodsPerWeek'];
   $countval = count(array_keys($currentto, $SubjectID, true));
   
   if($PeriodsPerWeek<$countval){
       $errorsSubjOver[]=$SubjectID;
       //$errorsSubjOver.=$SubjectID.",";
   }
   if($PeriodsPerWeek>$countval){
       $errorsSubjUnder[]=$SubjectID;
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


if ($RequestType == "checkNICValidation") {
    $cNIC = $_POST["NIC"];

    if (strlen($cNIC) == 10) {
        //used algorithm 11 - (N1*3 + N2*2 + N3*7 + N4*6 + N5*5 + N6*4 + N7*3 + N8*2) % 11
        $result = 11 - ($cNIC[0] * 3 + $cNIC[1] * 2 + $cNIC[2] * 7 + $cNIC[3] * 6 + $cNIC[4] * 5 + $cNIC[5] * 4 + $cNIC[6] * 3 + $cNIC[7] * 2) % 11;

        if ($result == '11') {
            $result = '0';
        }
        if ($result == '10') {
            $result = '0';
        }
        if ($result == $cNIC[8]) {
            echo json_encode("1");
            // valid
        } else {
            echo json_encode("0");
            // invalid
        }
    } else if (strlen($cNIC) == 12) {
        echo json_encode("1");
        // valid
    } else if (trim($cNIC) == '') {
        echo json_encode("0");
        // invalid
    }
}
?>
