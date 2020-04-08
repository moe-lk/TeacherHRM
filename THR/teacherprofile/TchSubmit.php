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

$MedTch1 = $_REQUEST['MedTch1'];
$GradTch1 = $_REQUEST["GradTch1"];

if ($_REQUEST["SubTch1"] != 'Select') {
    $SubTch1 = $_REQUEST["SubTch1"];
} else {
    $SubTch1 = $_REQUEST["otherTch1"];
}

$MedTch2 = $_REQUEST["MedTch2"];
$GradTch2 = $_REQUEST["GradTch2"];

if ($_REQUEST["SubTch2"] != 'Select') {
    $SubTch2 = $_REQUEST["SubTch2"];
} else {
    $SubTch2 = $_REQUEST["otherTch2"];
}

$MedTch3 = $_REQUEST["MedTch3"];
$GradTch3 = $_REQUEST["GradTch3"];

if ($_REQUEST["SubTch3"] != 'Select') {
    $SubTch3 = $_REQUEST["SubTch3"];
} else {
    $SubTch3 = $_REQUEST["otherTch3"];
}
// var_dump($SubTch3);
$id = $_REQUEST["id"];
// echo "gg" . $MedTch1;
$today = date("Y/m/d");

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
}

$sql = " INSERT INTO [dbo].[TempTeachingDetailsTemp]
([NIC]
,[TchSubject1]
,[TchSubject2]
,[TchSubject3]
,[Medium1]
,[Medium2]
,[Medium3]
,[GradeCode1]
,[GradeCode2]
,[GradeCode3]
,[SchoolType]
,[RecStatus]
,[RecordLog]
,[LastUpdate])
VALUES(
    '$nicNO', 
    '$SubTch1', 
    '$SubTch2', 
    '$SubTch3', 
    '$MedTch1', 
    '$MedTch2', 
    '$MedTch3',
    '$GradTch1',
    '$GradTch2',
    '$GradTch3',
    '$SchType',
    '0',
    '$NICUser',
    '$dateNow'
)";

$stmt = $db->runMsSqlQuery($sql);
sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC);

echo ("<script LANGUAGE='JavaScript'>
    window.alert('Succesfully Updated');
    window.location.href='teaching_subj-12--$nicNO.html';
    </script>");
    // // } else {
    // //     echo ("<script LANGUAGE='JavaScript'>
    //     window.alert('ERROR OCCURED!');
    //     window.location.href='Location: teaching_subj-12--NIC.html';
    //     </script>");
// }