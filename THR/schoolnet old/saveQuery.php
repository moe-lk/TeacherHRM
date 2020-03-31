<?php
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();
date_default_timezone_set("Asia/Colombo");

$NICNo = $_SESSION["NIC"];
$redirect_page = "index.php";


$cmbSchoolType = $_REQUEST["cmbSchoolType"];
$cmbProvince = $_REQUEST["cmbProvince"];
$cmbDistrict = $_REQUEST["cmbDistrict"];
$cmbZone = $_REQUEST["cmbZone"];
$cmbDivision = $_REQUEST["cmbDivision"];
$cmbSchool = $_REQUEST["cmbSchool"];

// Query save

if (isset($_REQUEST["rSaveQuery"])) {
    $radioSQ = $_REQUEST["rSaveQuery"];
    if ($radioSQ == "RSQ") {
        $qName = trim($_REQUEST["txtquerySaveName"]);
        $sql = "SELECT TOP (1)
  ID,
  SequenceID
FROM TG_QuerySave
WHERE (ID > 0)
AND (SequenceID <> N'')
ORDER BY ID DESC";
        $stmt = $db->runMsSqlQuery($sql);
        while ($row3 = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $sequenceID = $row3["SequenceID"];
            $querySaveID = $row3["ID"];
        }
        if ($sequenceID == "")
            $sequenceID = 1;
        else
            $sequenceID += 1;

        if ($qName == "")
            $qName = $sequenceID;

        $sqlInsert = "INSERT INTO TG_QuerySave VALUES('$sequenceID','$NICNo','$qName','bb')";
        $stmt = $db->runMsSqlQuery($sqlInsert);

        if (!empty($cmbSchoolType)) {
            $sqlInsert = "INSERT INTO TG_QuerySaveGeography VALUES('$sequenceID','ST','$cmbSchoolType')";
            $stmt = $db->runMsSqlQuery($sqlInsert);
        }
        if (!empty($cmbProvince)) {
            $sqlInsert = "INSERT INTO TG_QuerySaveGeography VALUES('$sequenceID','PR','$cmbProvince')";
            $stmt = $db->runMsSqlQuery($sqlInsert);
        }
        if (!empty($cmbDistrict)) {
            $sqlInsert = "INSERT INTO TG_QuerySaveGeography VALUES('$sequenceID','DI','$cmbDistrict')";
            $stmt = $db->runMsSqlQuery($sqlInsert);
        }
        if (!empty($cmbZone)) {
            $sqlInsert = "INSERT INTO TG_QuerySaveGeography VALUES('$sequenceID','ZN','$cmbZone')";
            $stmt = $db->runMsSqlQuery($sqlInsert);
        }
        if (!empty($cmbDivision)) {
            $sqlInsert = "INSERT INTO TG_QuerySaveGeography VALUES('$sequenceID','DV','$cmbDivision')";
            $stmt = $db->runMsSqlQuery($sqlInsert);
        }
        if (!empty($cmbSchool)) {
            $sqlInsert = "INSERT INTO TG_QuerySaveGeography VALUES('$sequenceID','SC','$cmbSchool')";
            $stmt = $db->runMsSqlQuery($sqlInsert);
        }


        if (isset($_REQUEST["txtBioFeildName"])) {
            $arrBioFeildName = $_REQUEST["txtBioFeildName"];
            $arrBioItemName = $_REQUEST["txtBioItemName"];
            $arrBioItemCode= $_REQUEST["txtBioItemCode"];
            for ($i = 0; $i < count($arrBioFeildName); $i++) {
              echo  $sqlInsert = "INSERT INTO TG_QuerySaveBiography VALUES('$sequenceID','$arrBioFeildName[$i]','$arrBioItemName[$i]','$arrBioItemCode[$i]')"; 
              echo "<br>";
                $stmt = $db->runMsSqlQuery($sqlInsert);
            }
        }

        if (isset($_REQUEST["txtQuliName"])) {
            $arrQulificationCode = $_REQUEST["txtQuliName"];
            for ($i = 0; $i < count($arrQulificationCode); $i++) {
                $sqlInsert = "INSERT INTO TG_QuerySaveQualification VALUES('$sequenceID','$arrQulificationCode[$i]')";
                $stmt = $db->runMsSqlQuery($sqlInsert);
            }
        }

        if (isset($_REQUEST["txtTeachType"])) {
            $arrTeachType = $_REQUEST["txtTeachType"];
            $arrTeachSubject = $_REQUEST["txtTeachSubject"];
            $arrTeachGrade = $_REQUEST["txtTeachGrade"];
            for ($i = 0; $i < count($arrTeachType); $i++) {
                $sqlInsert = "INSERT INTO TG_QuerySaveTeaching VALUES('$sequenceID','$arrTeachType[$i]','$arrTeachSubject[$i]','$arrTeachGrade[$i]')";
                $stmt = $db->runMsSqlQuery($sqlInsert);
            }
        }

        if (isset($_REQUEST["txtSPosition"])) {
            $arrSerPosition = $_REQUEST["txtSPosition"];
            $arrSerType = $_REQUEST["txtSType"];
            for ($i = 0; $i < count($arrSerPosition); $i++) {
                $sqlInsert = "INSERT INTO TG_QuerySaveService VALUES('$sequenceID','$arrSerPosition[$i]','$arrSerType[$i]')";
                $stmt = $db->runMsSqlQuery($sqlInsert);
            }
        }

        if (isset($_REQUEST["selectColum"])) {
            echo "hi";
            $arryColum = $_REQUEST["selectColum"];
            echo count($arryColum);
            for ($i = 0; $i < count($arryColum); $i++) {
               echo $sqlInsert = "INSERT INTO TG_QuerySaveSelectedColum VALUES('$sequenceID','$arryColum[$i]')";
               echo "</br>";
                $stmt = $db->runMsSqlQuery($sqlInsert);
            }
        }
    }
}

header("Location: $redirect_page");
exit();

?>
