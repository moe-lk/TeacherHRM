<?php

session_start();
$request = $_REQUEST['request'];

session_start();
include 'db_config/DBManager.php';
$db = new DBManager();


if ($request == 'signIn') {
    $userName = trim($_POST['userName']);
    $password = trim($_POST['txtpassword']);
    $nic = "";
    $error_msg = "";
    if ($userName == "") {
        $error_msg = "Enter your User Name";
    }
    if ($password == "") {
        if (!$error_msg)
            $error_msg = "Enter your password";
    }


    if (!$error_msg) {

        if (strlen($userName) == 10) {
            //used algorithm 11 - (N1*3 + N2*2 + N3*7 + N4*6 + N5*5 + N6*4 + N7*3 + N8*2) % 11
            $result = 11 - ($userName[0] * 3 + $userName[1] * 2 + $userName[2] * 7 + $userName[3] * 6 + $userName[4] * 5 + $userName[5] * 4 + $userName[6] * 3 + $userName[7] * 2) % 11;


	    if ($result == '11') {
                $result = '0';
            }
            if ($result == '10') {
                $result = '0';
            }
            if ($result == $userName[8]) {
                // echo "valid NIC";
            } else {
                $error_msg = "NIC you have entered is not valid.";
                $_SESSION['error_msg'] = $error_msg;
                header("Location:index.php");
                exit();
            }
        } else if (strlen($userName) == 12) {
           //  echo "valid NIC";
        } else if (trim($userName) == '') {
            $error_msg = "NIC you have entered is not valid.";
            $_SESSION['error_msg'] = $error_msg;
            header("Location:index.php");
            exit();
        }

        //

        $passwordMD5 = md5($password);

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

        if ($nic != "") {

            $sqlProDiv = "SELECT  CD_Districts.ProCode, CD_Districts.DistCode, CD_CensesNo.ZoneCode, 
                      CD_CensesNo.DivisionCode
FROM         TeacherMast INNER JOIN
                      StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID INNER JOIN
                      CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode INNER JOIN
                      CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
WHERE     (TeacherMast.NIC = '$nic')";

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
if ($request == 'signOut') {
    session_start();
    session_unset();
    session_destroy();
    unset($_SESSION);
    header("Location:index.php");
}
?>