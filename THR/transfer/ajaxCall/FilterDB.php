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
$cat = $_REQUEST["cat"];

if($cat=='showSchoolTransferDet'){
	$TransfrRequestType = $_REQUEST["TransfrRequestType"];
    $SchoolID = $_REQUEST["SchoolID"];
	
	$sqlSchoolDet="SELECT        CD_Zone.InstitutionName AS Expr1, CD_Districts.DistName, CD_Provinces.Province, CD_CensesNo.ZoneCode, CD_CensesNo.DistrictCode, 
                         CD_Provinces.ProCode
FROM            CD_Provinces INNER JOIN
                         CD_Zone INNER JOIN
                         CD_CensesNo ON CD_Zone.CenCode = CD_CensesNo.ZoneCode INNER JOIN
                         CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode ON CD_Provinces.ProCode = CD_Districts.ProCode
WHERE        (CD_CensesNo.CenCode = '$SchoolID')";

	$stmt = $db->runMsSqlQuery($sqlSchoolDet);
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$zonename=$row['Expr1'];
		$DistName=$row['DistName'];
		$Province=$row['Province'];
		$ZoneCode=$row['ZoneCode'];
		$DistrictCode=$row['DistrictCode'];
		$ProCode=$row['ProCode'];
	}

	if($TransfrRequestType=='WZ'){
        $sql = "Select CenCode,InstitutionName FROM CD_Zone
  where CenCode='$ZoneCode'
  order by InstitutionName";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			echo $row['InstitutionName'];
			echo "<input type=\"hidden\" name=\"TransferRequestTypeID\" value=\"$ZoneCode\" />";
		}
	}
	
	if($TransfrRequestType=='OZ'){
		$details="<select class=\"select2a_n\" id=\"TransferRequestTypeID\" name=\"TransferRequestTypeID\" onchange=\"changeSchool()\">";
                            
                           
                            $sql = "Select CenCode,InstitutionName FROM CD_Zone
  where DistrictCode='$DistrictCode'
  order by InstitutionName";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $details.='<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
                            }
                           
                      echo $details.="</select>";
	}
	
	
	if($TransfrRequestType=='OP'){
        // echo $details="Select a Province :";
	}
	if($TransfrRequestType=='NS'){
         $sqlWhere="where IsNationalSchool='1'";
	
		$details="<select class=\"select2a_n\" id=\"TransferRequestTypeID\" name=\"TransferRequestTypeID\" onchange=\"changeSchool()\">";
                            
		$sql = "Select CenCode,InstitutionName FROM CD_CensesNo
$sqlWhere
order by InstitutionName";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$details.='<option value=' . $row['CenCode'] . '>' . addslashes($row['InstitutionName']) . '</option>';
		}
	   
  		echo $details.="</select>";
	}
	
	
}

if($cat=='showLable'){
	$TransfrRequestType = $_REQUEST["TransfrRequestType"];
    $SchoolID = $_REQUEST["SchoolID"];
	
	$sqlSchoolDet="SELECT        CD_Zone.InstitutionName AS Expr1, CD_Districts.DistName, CD_Provinces.Province, CD_CensesNo.ZoneCode, CD_CensesNo.DistrictCode, 
                         CD_Provinces.ProCode
FROM            CD_Provinces INNER JOIN
                         CD_Zone INNER JOIN
                         CD_CensesNo ON CD_Zone.CenCode = CD_CensesNo.ZoneCode INNER JOIN
                         CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode ON CD_Provinces.ProCode = CD_Districts.ProCode
WHERE        (CD_CensesNo.CenCode = '$SchoolID')";

	$stmt = $db->runMsSqlQuery($sqlSchoolDet);
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$zonename=$row['Expr1'];
		$DistName=$row['DistName'];
		$Province=$row['Province'];
		$ZoneCode=$row['ZoneCode'];
		$DistrictCode=$row['DistrictCode'];
		$ProCode=$row['ProCode'];
	}

	
	if($TransfrRequestType=='WZ'){
         echo $details="Current Zone :";
	}
	if($TransfrRequestType=='OZ'){
         echo $details="Select Other Zone :";
	}
	if($TransfrRequestType=='OP'){
         echo $details="Select a Province :";
	}
	if($TransfrRequestType=='NS'){
         echo $details="Select National School :";
	}
	
	
}

if($cat=='changeSchool'){
	$TransfrRequestType = $_REQUEST["TransfrRequestType"];
    $SchoolID = $_REQUEST["SchoolID"];
	$sqlSchoolDet="SELECT        CD_Zone.InstitutionName AS Expr1, CD_Districts.DistName, CD_Provinces.Province, CD_CensesNo.ZoneCode, CD_CensesNo.DistrictCode, 
                         CD_Provinces.ProCode
FROM            CD_Provinces INNER JOIN
                         CD_Zone INNER JOIN
                         CD_CensesNo ON CD_Zone.CenCode = CD_CensesNo.ZoneCode INNER JOIN
                         CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode ON CD_Provinces.ProCode = CD_Districts.ProCode
WHERE        (CD_CensesNo.CenCode = '$SchoolID')";

	$stmt = $db->runMsSqlQuery($sqlSchoolDet);
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$zonename=$row['Expr1'];
		$DistName=$row['DistName'];
		$Province=$row['Province'];
		$ZoneCode=$row['ZoneCode'];
		$DistrictCode=$row['DistrictCode'];
		$ProCode=$row['ProCode'];
	}
	
	if($TransfrRequestType=='WZ')$sqlWhere="where ZoneCode='$ZoneCode'";
	if($TransfrRequestType=='OZ')$sqlWhere="where DistrictCode='$DistrictCode'";
	//if($TransfrRequestType=='OP')$sqlWhere="where ZoneCode='$ZoneCode'";
	if($TransfrRequestType=='NS')$sqlWhere="where IsNationalSchool='1'";
	
		$details="<select class=\"select2a_n\" id=\"ExpectSchool\" name=\"ExpectSchool\">";
                            
                            $sql = "Select CenCode,InstitutionName FROM CD_CensesNo
  $sqlWhere
  order by InstitutionName";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $details.='<option value=' . $row['CenCode'] . '>' . addslashes($row['InstitutionName']) . '</option>';
                            }
                           
                      echo $details.="</select>";
	//echo $sql;
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

?>
