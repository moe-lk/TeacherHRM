<?php
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
if ($_SESSION['NIC'] == '') {
    header("Location: ../index.php");
    exit();
}/* if($_SESSION['loggedSchoolSearch']==''){$_SESSION["ses_expire"]="Session expired. Select a school again.";header("Location: index.php") ;exit() ;}*/
include '../db_config/DBManager.php';
$db = new DBManager();
$timezone = "Asia/Colombo";
if (function_exists('date_default_timezone_set')) {
    date_default_timezone_set($timezone);
}
if ($_SESSION['timeout'] + 60 * 60 < time()) {
    session_unset();
    session_destroy();
    session_start();
    header("Location: ../index.php");
    exit();
}
$_SESSION["timeout"] = time();
$replace_data = array("'", "/", "!", "&", "*", " ", "-", "@", '"', "?", ":", "“", "”");
$replace_data_new = array("'", "/", "!", "&", "*", " ", "-", "@", '"', "?", ":", "“", "”", ".");
$pageid = $_GET["pageid"];
$menu = $_GET['menu'];
$tpe = $_GET['tpe'];
$id = $_GET['id'];
//  var_dump($id);
$fm = $_GET['fm'];
$lng = $_GET['lng'];
$curPage = $_GET['curPage'];
$ttle = $_GET['ttle'];
$ttle = str_replace("_", " ", $ttle);
/* //str_replace(",","",$amount); */
if ($pageid == '') {
    $pageid = "0";
}
$NICUser = trim($_SESSION["NIC"]);
$loggedSchool = trim($_SESSION['loggedSchool']);
$loggedPositionName = $_SESSION['loggedPositionName'];
$loggedSchool = trim($_SESSION['loggedSchoolSearch']);
$sqlList = "SELECT InstitutionName FROM CD_CensesNo where CenCode='$loggedSchool'";
$stmt = $db->runMsSqlQuery($sqlList);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$InstitutionName = $row['InstitutionName'];
$sqlTName = "SELECT SurnameWithInitials FROM TeacherMast where NIC='$id'";
$stmtTn = $db->runMsSqlQuery($sqlTName);
$rowTn = sqlsrv_fetch_array($stmtTn, SQLSRV_FETCH_ASSOC);
$SurnameWithInitialsT = $rowTn['SurnameWithInitials'];/* $nicNO='791231213V'; */
$querySaveVal = "";
$theamPath = "../cms/images/";
$theam = "theam1";
if ($theam == "theam1") {
    $theamMenuFontColor = "#0888e2";
    $theamMenuButtonColor = "#3973b1";
}
if ($theam == "theam2") {
    $theamMenuFontColor = "#d98813";
    $theamMenuButtonColor = "#3a2a07";
}
if ($theam == "theam3") {
    $theamMenuFontColor = "#c2379b";
    $theamMenuButtonColor = "#8839b1";
}
$url = (!empty($_SERVER['HTTPS'])) ? "https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] : "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
$exUrl = explode('/', $url);
$folderLocation = count($exUrl) - 2;
$ModuleFolder = $exUrl[$folderLocation];
if ($pageid == 1 || $pageid == 2) {
    $sql = "SELECT CONVERT(varchar(10), LastUpdate, 121) AS LastUpdate FROM TeacherMast WHERE (NIC='$id') ORDER BY LastUpdate DESC";
    $stmt = $db->runMsSqlQuery($sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $LastUpdate = trim($row['LastUpdate']);
}
if ($pageid == 4) {
    $sql = "SELECT CONVERT(varchar(10), LastUpdate, 121) AS LastUpdate FROM StaffQualification WHERE (NIC='$id') ORDER BY LastUpdate DESC";
    $stmt = $db->runMsSqlQuery($sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $LastUpdate = trim($row['LastUpdate']);
}
if ($pageid == 5) {
    $sql = "SELECT CONVERT(varchar(10), LastUpdate, 121) AS LastUpdate FROM TeacherSubject WHERE (NIC='$id') ORDER BY LastUpdate DESC";
    $stmt = $db->runMsSqlQuery($sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $LastUpdate = trim($row['LastUpdate']);
}
if ($pageid == 8) {
    $sql = "SELECT CONVERT(varchar(10), LastUpdate, 121) AS LastUpdate FROM StaffServiceHistory WHERE (NIC='$id') ORDER BY LastUpdate DESC";
    $stmt = $db->runMsSqlQuery($sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $LastUpdate = trim($row['LastUpdate']);
}
if ($pageid == 9) {
    $sql = "SELECT CONVERT(varchar(10), LastUpdate, 121) AS LastUpdate FROM Passwords WHERE (NICNo='$id') ORDER BY LastUpdate DESC";
    $stmt = $db->runMsSqlQuery($sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $LastUpdate = trim($row['LastUpdate']);
}
if ($pageid == 30) {
    $sql = "SELECT CONVERT(varchar(10), LastUpdate, 121) AS LastUpdate FROM Passwords WHERE (NICNo='$id') ORDER BY LastUpdate DESC";
    $stmt = $db->runMsSqlQuery($sql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $LastUpdate = trim($row['LastUpdate']);
}
$dateNow = date("Y/m/d");

// var_dump($_REQUEST);
$nicNO = $_REQUEST['id'];
$AppCat = $_REQUEST["AppCat"];
$MedApp = $_REQUEST["MedApp"];
$SubApp = $_REQUEST["SubApp"];
$otherSub = $_REQUEST["otherSub"];

$SQL1 = "SELECT TOP(1)
*
FROM
TeacherMast
join StaffServiceHistory on TeacherMast.CurServiceRef = StaffServiceHistory.ID
join CD_CensesNo on StaffServiceHistory.InstCode = CD_CensesNo.CenCode 
WHERE StaffServiceHistory.NIC = '$nicNO' ORDER BY StaffServiceHistory.AppDate DESC";

$stmt1 = $db->runMsSqlQuery($SQL1);
while ($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
    $SchType = Trim($row1['SchoolType']);
    // var_dump($SchType);
}
// var_dump($SchType);
if ($SubApp != 'Select') {
    $sql = "INSERT INTO [dbo].[Temp_AppoinmentDetails]
([NIC]
,[AppCategory]
,[AppSubject]
,[Medium]
,[SchoolType]
,[OtherSub]
,[RecordStatus]
,[LastUpdate]
,[RecordLog])
VALUES
('$nicNO', 
'$AppCat', 
'$SubApp', 
'$MedApp', 
'$SchType',
NULL, 
'0',
'$dateNow',
'$NICUser')";
} else {
    $sql = "INSERT INTO [dbo].[Temp_AppoinmentDetails]
([NIC]
,[AppCategory]
,[AppSubject]
,[Medium]
,[SchoolType]
,[OtherSub]
,[RecordStatus]
,[LastUpdate]
,[RecordLog])
VALUES
('$nicNO', 
'$AppCat', 
NULL, 
'$MedApp', 
'$SchType',
'$otherSub', 
'0',
'$dateNow',
'$NICUser')";
}

$stmt = $db->runMsSqlQuery($sql);
sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC);

// var_dump($sql);
echo ("<script LANGUAGE='JavaScript'>
    window.alert('Succesfully Updated');
    window.location.href='Appoint_subj-13--$nicNO.html';
    </script>");
