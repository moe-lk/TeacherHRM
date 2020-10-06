<?php
// phpinfo();
// Modification history
// 17 May 2018: Dr Chandana Gamage: 12-digit NIC verification code added. Code formatting re-adjusted

session_start();
$request = $_REQUEST['request'];

session_start(); // Why are we calling session start again?
include 'db_config/DBManager.php';
$db = new DBManager();

// Process for username-password sign-in into the system
if ($request == 'signIn') {
 $userName = trim($_POST['userName']);
 $password = trim($_POST['txtpassword']);
 $nic = "";
 $error_msg = "";

 // An empty username is an error
 if ($userName == "") {
  $error_msg = "Enter your User Name";
 }

 // An empty password is an error
 if ($password == "") {
  // Process this empty password error only if username is not emmpty
  if (!$error_msg)
   $error_msg = "Enter your password";
 }

 // Handle user names of wrong lengths. Only correct lengths are 10 and 12.
 $userNameLength = strlen($userName);
 if ($userNameLength < 10)
  $error_msg = "Enter User Name of correct length";
 if ($userNameLength == 11)
  $error_msg = "Enter User Name of correct length";
 if ($userNameLength > 12)
  $error_msg = "Enter User Name of correct length";

 // Proceed if we have input for both username and password
 if (!$error_msg) {

  // Process the old NIC numbers of 10-digits
  if (strlen($userName) == 10) {
   //used algorithm is 11 - (N1*3 + N2*2 + N3*7 + N4*6 + N5*5 + N6*4 + N7*3 + N8*2) % 11
   $result = 11 - ($userName[0] * 3 + $userName[1] * 2 + $userName[2] * 7 + $userName[3] * 6 + $userName[4] * 5 + $userName[5] * 4 + $userName[6] * 3 + $userName[7] * 2) % 11;

   if ($result == '11') {
    $result = '0';
   }

   if ($result == '10') {
    $result = '0';
   }

   if (($result == $userName[8]) && (($userName[9] == 'v') || ($userName[9] == 'x') || ($userName[9] == 'V')||($userName[9] == 'X'))) { // compare with check digit at 9th position and V or X in 10th position
    // At this point, we have a valid NIC
   } else {
    $error_msg = "NIC you have entered is not valid.";
    $_SESSION['error_msg'] = $error_msg;
    header("Location:index.php");
    exit();
   }
   // we have finished checking the 10-digit NIC

  // Process the new NIC numbers of 12-digits
  } else if (strlen($userName) == 12) {
   //used algorithm is 11 - (N1*8 + N2*4 + N3*3 + N4*2 + N5*7 + N6*6 + N7*5 + N8*8 + N9*4 + N10*3 + N11*2) % 11
   $result = 11 - ($userName[0] * 8 + $userName[1] * 4 + $userName[2] * 3 + $userName[3] * 2 + $userName[4] * 7 + $userName[5] * 6 + $userName[6] * 5 + $userName[7] * 8 + $userName[8] * 4 + $userName[9] * 3 + $userName[10] * 2) % 11;

   if ($result == '11') {
    $result = '0';
   }

   if ($result == '10') {
    $result = '0';
   }

   if ($result == $userName[11]) { // compare with check digit at 12th position
    // At this point, we have a valid NIC
   } else {
    $error_msg = "NIC you have entered is not valid.";
    $_SESSION['error_msg'] = $error_msg;
    header("Location:index.php");
    exit();
   }
   
  } // we have finished checking the 12-digit NIC

  $passwordMD5 = md5($password);

  // We are going to pulll the existence status for stored MD5 hash of the password for the given username. 
  $sql = "SELECT Passwords.NICNo,(CD_Title.TitleName +' '+ TeacherMast.SurnameWithInitials) AS name,Passwords.AccessLevel,Passwords.IsnewPW, Passwords.AccessRole
  FROM Passwords
  INNER JOIN TeacherMast ON Passwords.NICNo = TeacherMast.NIC
  LEFT OUTER JOIN CD_Title ON TeacherMast.Title = CD_Title.TitleCode
  WHERE (Passwords.NICNo = N'$userName') AND (Passwords.CurPassword = N'$passwordMD5')";

  $stmt = $db->runMsSqlQuery($sql);
  $row = sqlsrv_fetch_array($stmt);
  $nic = trim($row["NICNo"]);
  $fulName = $row["name"];
  $accLevel = trim($row["AccessLevel"]);
  $IsnewPW = trim($row["IsnewPW"]);
  //$loggedPositionName =  trim($row["AccessRole"]);

  $reqTabMobAc = "SELECT AccessRole,AccessRoleID,HigherLevel,ControlLevel,AccessRoleType FROM CD_AccessRoles where AccessRoleValue='$accLevel'";

  $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
  $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
  $loggedPositionName = trim($rowMobAc['AccessRole']);
  $AccessRoleID = trim($rowMobAc['AccessRoleID']);
  $HigherLevel = trim($rowMobAc['HigherLevel']);
  $ControlLevel = trim($rowMobAc['ControlLevel']);
  $AccessRoleType = trim($rowMobAc['AccessRoleType']);

  // if we have a correct NIC retrieve the records for that NIC from the database
  if ($nic != "") {

   $sqlProDiv = "SELECT  CD_Districts.ProCode, CD_Districts.DistCode, CD_CensesNo.ZoneCode, CD_CensesNo.DivisionCode
   FROM TeacherMast
   INNER JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID 
   INNER JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode 
   INNER JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
   WHERE (TeacherMast.NIC = '$nic')";

   $stmtProDiv = $db->runMsSqlQuery($sqlProDiv);
   $rowProDiv = sqlsrv_fetch_array($stmtProDiv);
   $ProCodeU = trim($rowProDiv["ProCode"]);
   $DistCodeU = trim($rowProDiv["DistCode"]);
   $ZoneCodeU = trim($rowProDiv["ZoneCode"]);
   $DivisionCodeU = trim($rowProDiv["DivisionCode"]);

   $_SESSION["NIC"] = $nic;
   $_SESSION["fullName"] = $fulName;
   $_SESSION["accLevel"] = $accLevel;
   $_SESSION['loggedAccessLevel'] = $accLevel;
   $_SESSION['loggedPositionName'] = $loggedPositionName;
   $_SESSION['AccessRoleID'] = $AccessRoleID;
   $_SESSION['SeeHigherLevel'] = $HigherLevel;
   $_SESSION['SeeControlLevel'] = $ControlLevel;
   $_SESSION['AccessRoleType'] = $AccessRoleType;

   $_SESSION["ProCodeU"] = $ProCodeU;
   $_SESSION["DistCodeU"] = $DistCodeU;
   $_SESSION["ZoneCodeU"] = $ZoneCodeU;
   $_SESSION["DivisionCodeU"] = $DivisionCodeU;

   $_SESSION["timeout"] = time();
   //header("Location:Form1.php");
   if ($IsnewPW == 'Y') {
    header("Location:user/change_password-9C--$nic-C.html");
   } else {
    header("Location:module_main.php");
   }
  } else {
   if (!$error_msg)
    $error_msg = "Incorrect User name or Password.";
   $_SESSION['error_msg'] = $error_msg;
   header("Location:index.php");
  }
 } else {
  if (!$error_msg)
   $error_msg = "Incorrect User name or Password.";
  $_SESSION['error_msg'] = $error_msg;
  header("Location:index.php");
 }
}

// Process for user to sign-out of the system
if ($request == 'signOut') {
 session_start();
 session_unset();
 session_destroy();
 unset($_SESSION);
 header("Location:index.php");
}
?>
