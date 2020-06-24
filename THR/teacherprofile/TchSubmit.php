<?php
require_once '../error_handle.php';
// include "connection.php";
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

$serverName = "DESKTOP-OESJB7N\SQLEXPRESS";
$connectionInfo = array( "Database"=>"MOENational", "UID"=>"sa", "PWD"=>"na1234");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn === false ) {
    die( print_r( sqlsrv_errors(), true ));
}



// var_dump($_REQUEST);
$nicNO = $_REQUEST['id'];


// $MedTch2 = $_REQUEST["MedTch2"];
// $MedTch3 = $_REQUEST["MedTch3"];
// $GradTch1 = $_REQUEST["GradTch1"];
// $GradTch2 = $_REQUEST["GradTch2"];
// $GradTch3 = $_REQUEST["GradTch3"];

if ($_REQUEST["MedTch1"] != 'Select') {
    $MedTch1 = $_REQUEST["MedTch1"];
} else {
    $MedTch1 = "";
}

if ($_REQUEST["MedTch2"] != 'Select') {
    $MedTch2 = $_REQUEST["MedTch2"];
} else {
    $MedTch2 = "";
}

if ($_REQUEST["MedTch3"] != 'Select') {
    $MedTch3 = $_REQUEST["MedTch3"];
} else {
    $MedTch3 = "";
}

if ($_REQUEST["GradTch1"] != 'Select') {
    $GradTch1 = $_REQUEST["GradTch1"];
} else {
    $GradTch1 = "";
}

if ($_REQUEST["GradTch2"] != 'Select') {
    $GradTch2 = $_REQUEST["GradTch2"];
} else {
    $GradTch2 = "";
}

if ($_REQUEST["GradTch3"] != 'Select') {
    $GradTch3 = $_REQUEST["GradTch3"];
} else {
    $GradTch3 = "";
}

if ($_REQUEST["SubTch1"] != 'Select') {
    $SubTch1 = $_REQUEST["SubTch1"];
} else {
    $SubTch1 = "";
}

if ($_REQUEST["SubTch2"] != 'Select') {
    $SubTch2 = $_REQUEST["SubTch2"];
} else {
    $SubTch2 = "";
}

if ($_REQUEST["SubTch3"] != 'Select') {
    $SubTch3 = $_REQUEST["SubTch3"];
} else {
    $SubTch3 = "";
}
$otherTch1 = $_REQUEST["otherTch1"];
$otherTch2 = $_REQUEST["otherTch2"];
$otherTch3 = $_REQUEST["otherTch3"];

if ($_REQUEST["otherspecial"] != 'Select') {
    $otherspecial = $_REQUEST["otherspecial"];
} else {
    $otherspecial = "";
}
$state = '0';
// $otherspecial = $_REQUEST["otherspecial"];

$id = $_REQUEST["id"];
    // echo "gg" . $MedTch1;
    $today = date("Y/m/d");

    $SQL1 = "SELECT TOP(1) * FROM TeacherMast
            join StaffServiceHistory on TeacherMast.CurServiceRef = StaffServiceHistory.ID
            join CD_CensesNo on StaffServiceHistory.InstCode = CD_CensesNo.CenCode 
            WHERE StaffServiceHistory.NIC = '$nicNO' 
            ORDER BY StaffServiceHistory.AppDate DESC";

    $stmt1 = $db->runMsSqlQuery($SQL1);
    while ($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
        $SchType = Trim($row1['SchoolType']);
    }

$sqlCheck = "SELECT * FROM TeachingDetails WHERE NIC = '$nicNO' AND RecStatus = '1'";
$TotalRows = $db->rowCount($sqlCheck);

// if(!$TotalRows){
//     $SQLU = "UPDATE [dbo].[TeachingDetails]
//             SET [RecStatus] = '2' WHERE NIC = '$nicNO'";
// }
// else{
    // var_dump($conn);
    
// function insertsub(){
    /* Begin transaction. */  
if( sqlsrv_begin_transaction($conn) === false )   
{   
     echo "Could not begin transaction.\n";  
     die( print_r( sqlsrv_errors(), true));  
}

    $sql = "INSERT INTO [dbo].[Temp_TeachingDetails]
            ([NIC]
            ,[TchSubject1]
            ,[TchSubject2]
            ,[TchSubject3]
            ,[Other1]
            ,[Other2]
            ,[Other3]
            ,[Medium1]
            ,[Medium2]
            ,[Medium3]
            ,[GradeCode1]
            ,[GradeCode2]
            ,[GradeCode3]
            ,[OtherSpecial]
            ,[SchoolType]
            ,[RecStatus]
            ,[RecordLog]
            ,[LastUpdate])
            VALUES
            (
            ?, 
            ?, 
            ?, 
            ?,
            ?,
            ?,
            ?, 
            ?, 
            ?, 
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?,
            ?
        )";

    // $stmt = $db->runMsSqlQuery($sql);
    $params = array($nicNO, $SubTch1, $SubTch2, $SubTch3, $otherTch1, $otherTch2, $otherTch3, $MedTch1, $MedTch2, $MedTch3, $GradTch1, $GradTch2, $GradTch3, $otherspecial, $SchType,$state,$NICUser,$dateNow);
    $stmt = sqlsrv_query( $conn, $sql, $params );
    // var_dump($stmt);
    if($stmt){
        sqlsrv_commit($conn);
        // echo "Successfully Added";
        // echo "<script>alert('Successfully Added')</script>";
        echo ("<script LANGUAGE='JavaScript'>
        window.alert('Succesfully Updated');
        window.location.href='teaching_subj-12--$nicNO.html';
        </script>");
    } else {
        sqlsrv_rollback( $conn );
        echo "Updates rolled back.<br />";
        echo ("<script LANGUAGE='JavaScript'>
        window.alert('Update Failed!, Please try again.');
        window.location.href='teaching_subj-12--$nicNO.html';
        </script>");
    }
// }

// insertsub($sql,$conn,$stmt, $params,$nicNO, $SubTch1, $SubTch2, $SubTch3, $otherTch1, $otherTch2, $otherTch3, $MedTch1, $MedTch2, $MedTch3, $GradTch1, $GradTch2, $GradTch3, $otherspecial, $SchType,$NICUser,$dateNow);
    // sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC);
    // var_dump($sql);

    // echo ("<script LANGUAGE='JavaScript'>
    //     window.alert('Succesfully Updated');
    //     window.location.href='teaching_subj-12--$nicNO.html';
    //     </script>");
// }



    // // } else {
    // //     echo ("<script LANGUAGE='JavaScript'>
    //     window.alert('ERROR OCCURED!');
    //     window.location.href='Location: teaching_subj-12--NIC.html';
    //     </script>");
// }
