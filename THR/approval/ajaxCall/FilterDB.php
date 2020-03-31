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
