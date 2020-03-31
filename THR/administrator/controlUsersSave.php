<?php

require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();

$vID = $_REQUEST['vID'];
$vDes = $_REQUEST['vDes'];
$tblName = $_REQUEST['tblName'];
$mainID = $_REQUEST['mainID'];
$redirect_page = $_REQUEST['redirect_page'];
$status = $_REQUEST['AED'];
$cat = $_REQUEST['cat'];
$field_name = "AttachFile";

$updateBy = trim($_SESSION["NIC"]);
$loggedSchool = $_SESSION['loggedSchool'];
$AccessRoleType = $_SESSION['AccessRoleType'];


if ($status == 'normal') {
    //Title,SurnameWithInitials,FullName,TpNumber,EmailAdd,NIC,CurPassword,CurPasswordRT,InstCode,ProCode,DistrictCode,ZoneCode
    $AccessLevel = $_REQUEST['AccessLevel'];
    $Title = $_REQUEST['Title'];
    $SurnameWithInitials = trim($_REQUEST['SurnameWithInitials']);
    $FullName = trim($_REQUEST['FullName']);
    $TpNumber = trim($_REQUEST['TpNumber']);
    $EmailAdd = trim($_REQUEST['EmailAdd']);
    $NIC = trim($_REQUEST['NIC']);
    $CurPassword = trim($_REQUEST['CurPassword']);
    $CurPasswordRT = trim($_REQUEST['CurPasswordRT']);
    $GenderCode = $_REQUEST['GenderCode'];
    $InstCode = $_REQUEST['InstCode'];
    $ProCode = $_REQUEST['ProCode'];
    $DistrictCode = $_REQUEST['DistrictCode'];
    $ZoneCode = $_REQUEST['ZoneCode'];

    $maxAccount = 0; //Zero (0) men unlimited
    /*
     * $AccessLevel == 100000 || $AccessLevel == 99999 || $AccessLevel == 17000 || $AccessLevel == 17050 || $AccessLevel == 13050 || $AccessLevel == 12050 || $AccessLevel == 11050 || $AccessLevel == 11000 || $AccessLevel == 7000 || $AccessLevel == 8000
     */

    $msg = "";


    if ($AccessLevel == "") {
        $msg .= "Please select a user type.<br/>";
    }
  
    
    if ($SurnameWithInitials == "") {
        $msg .= "Please enter name with initials.<br/>";
    }
    
    if ($FullName == "") {
        $msg .= "Please enter full name.<br/>";
    }
    
    if ($NIC == "") {
        $msg .= "Please enter a valid NIC.<br/>";
    }

    if ($CurPassword == "") {
        $msg .= "Please enter password.<br/>";
    }

    if ($CurPassword != $CurPasswordRT) {
        $msg = "Password does not match the confirm password";
    }
    if ($InstCode == '') {
        if($AccessLevel == 99000 || $AccessLevel == 99999){
            
        }else{
            $msg .= "Please select department.<br/>";
        }
        
    }

    if ($AccessLevel == '100000' || $AccessLevel == '99999' || $AccessLevel == '17050' || $AccessLevel == '13050' || $AccessLevel == '11070' || $AccessLevel == '8000')
        $maxAccount = 1;



    //echo $msg;
    //echo $InstCode;
    //echo "</br>";
    //echo "hii";
    //exit();
    

    
    
    if($AccessLevel == 99999){
        $countTotal = "SELECT
	Passwords.NICNo,
	TeacherMast.SurnameWithInitials,
	Passwords.AccessRole,
 Passwords.AccessLevel
FROM
	Passwords
INNER JOIN TeacherMast ON Passwords.NICNo = TeacherMast.NIC
WHERE
	(
		Passwords.AccessLevel = '$AccessLevel'
	)
"; //$loggedSchool
    }else{
        $countTotal = "SELECT        Passwords.NICNo, TeacherMast.SurnameWithInitials, CD_CensesNo.InstitutionName, CD_CensesNo.DistrictCode, CD_CensesNo.ZoneCode, CD_CensesNo.DivisionCode, Passwords.AccessRole, 
								 Passwords.AccessLevel, StaffServiceHistory.InstCode
		FROM            Passwords INNER JOIN
								 TeacherMast ON Passwords.NICNo = TeacherMast.NIC INNER JOIN
								 StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID INNER JOIN
								 CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
		WHERE    (Passwords.AccessLevel='$AccessLevel') and (StaffServiceHistory.InstCode = N'$InstCode')"; //$loggedSchool
    }
  

    $isAvailable = $db->rowAvailable($countTotal);
    if ($isAvailable == 1 and $maxAccount == 1) {
        $msg = "User account of this user type is already exist.";
    }


    $countSql = "SELECT * FROM Passwords where NICNo='$NIC'"; //and AccessLevel='$AccessLevel'
    $isAvailable = $db->rowAvailable($countSql);
    if ($isAvailable == 1) {
        $msg = "User NIC number already exist.";
    }

    if ($msg != '') {
        $_SESSION['success_update'] = $msg;
        header("Location:controlUser-20.html");
        exit();
    }

    if ($CurPassword != $CurPasswordRT && ($CurPassword != '')) {
        $msg = "Password mismatch. Please try again.";
    } else {
        $passwordMD5 = md5($CurPassword);
        //echo "dfdf";exit();
        $sql = "SELECT AccessRole from CD_AccessRoles Where AccessRoleValue='$AccessLevel'";
        $stmt = $db->runMsSqlQuery($sql);
        $rowA = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $AccessRole = strtoupper($rowA['AccessRole']);
        ///exit();				
        $LastUpdate = date('Y-m-d H:i:s');
        $appDate = date('Y-m-d');

        if ($vDes == 'E') {
            if ($chngepw == 'Y') {
                $queryUpate = "UPDATE Passwords SET	CurPassword='$passwordMD5', LastUpdate='$LastUpdate', AccessRole='$AccessRole', AccessLevel='$AccessLevel' WHERE NICNo='$NICSrch'";

                $dateU = date('Y-m-d H:i:s');
            } else {
                $queryUpate = "UPDATE Passwords SET LastUpdate='$LastUpdate', AccessRole='$AccessRole', AccessLevel='$AccessLevel' 
				WHERE
					   NICNo='$NICSrch'";
                $statusOf == "";
            }

            $db->runMsSqlQuery($queryUpate);
            $msg = "Record update successfully." . $msg1;
        } else {
            $queryGradeSave = "INSERT INTO Passwords
		   (NICNo,CurPassword,LastUpdate,AccessRole,AccessLevel,IsnewPW)
	 VALUES
		   ('$NIC','$passwordMD5','$LastUpdate','$AccessRole','$AccessLevel','Y')";
            $db->runMsSqlQuery($queryGradeSave);
            //SurnameWithInitials,FullName,TpNumber,EmailAdd,Title,

            $countSql = "SELECT NIC FROM TeacherMast where NIC='$NIC'";
            $isAvailable = $db->rowAvailable($countSql);

            if ($isAvailable == 1) {
                
            } else {
                $queryTMSave = "INSERT INTO StaffServiceHistory
				   (NIC,ServiceRecTypeCode,AppDate,InstCode,LastUpdate,RecordLog,UpdateBy)
			 VALUES
				   ('$NIC','NA01','$appDate','$InstCode','$LastUpdate','Create User account','$updateBy')";
                $db->runMsSqlQuery($queryTMSave);

                $reqTabMobAc = "SELECT ID FROM StaffServiceHistory where NIC='$NIC' ORDER BY ID DESC";
                $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
                $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
                $newHisID = trim($rowMobAc['ID']);

                $queryTMSave = "INSERT INTO TeacherMast
				   (NIC,SurnameWithInitials,FullName,Title,LastUpdate,MobileTel,emailaddr,GenderCode,UpdateBy,CurServiceRef)
			 VALUES
				   ('$NIC','$SurnameWithInitials','$FullName','$Title','$LastUpdate','$TpNumber','$EmailAdd','$GenderCode','$updateBy','$newHisID')";
                $db->runMsSqlQuery($queryTMSave);
            }

            $_SESSION['success_update'] = "Account created successfully.";
            header("Location:controlUser-20.html");
            exit();
        }
    }
}

if ($status == 'D') {
    //echo $vID;
    //exit();
    if ($cat == 'Approval') {
        $sqlDel = "DELETE FROM $tblName
      WHERE ID=$vID";
        $db->runMsSqlQuery($sqlDel);

        $sqlDel2 = "DELETE FROM TG_ApprovalProcess
      WHERE ApprovalProcMainID=$vID";
        $db->runMsSqlQuery($sqlDel2);

        header("Location:$redirect_page");
        // redirect("reservation_customer_info-54-4_1--E--104.html");
        exit();
    }
    $sqlDel = "DELETE FROM $tblName
      WHERE ID=$vID";
    $db->runMsSqlQuery($sqlDel);

    header("Location:$redirect_page");
    // redirect("reservation_customer_info-54-4_1--E--104.html");
    exit();
}

if ($status == 'ED') { //Enable/disable records
    if ($vDes == 'Active')
        $cEnabled = "N";
    if ($vDes == 'Deactive')
        $cEnabled = "Y";

    $sqlDel = "UPDATE $tblName SET StatusOf='$cEnabled'
      WHERE ID=$vID";
    $db->runMsSqlQuery($sqlDel);
    header("Location:$redirect_page");
    // redirect("reservation_customer_info-54-4_1--E--104.html");
    exit();
}
?>