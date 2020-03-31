<?php

function getApproveList($processType, $requestID) {
    global $db;

    $nicNO = $_SESSION["NIC"];
    $accLevel = $_SESSION["accLevel"];   
    $saveStatus = true;
    //$dateTime = date('Y-m-d H:i:s');
    $msg = "";

    // *****
    //  get logged user current service location
    $sqlService = "SELECT 
    TeacherMast.NIC, 
    StaffServiceHistory.InstCode,
    CD_CensesNo.InstitutionName,
    CD_CensesNo.DivisionCode,
    CD_CensesNo.ZoneCode, 
    CD_CensesNo.DistrictCode, 
    CD_Provinces.ProCode 
FROM
    TeacherMast
        INNER JOIN
    StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID
        INNER JOIN
    CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
        INNER JOIN
    CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
        INNER JOIN
    CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
WHERE
    (TeacherMast.NIC = N'$nicNO')";

    $resService = $db->runMsSqlQuery($sqlService);
    $rowS = sqlsrv_fetch_array($resService, SQLSRV_FETCH_ASSOC);
    $servicePlace = $rowS['InstCode'];
    $DivisionCode = trim($rowS['DivisionCode']);
    $ZoneCode = $rowS['ZoneCode'];
    $DistrictCode = $rowS['DistrictCode'];
    $ProCode = $rowS['ProCode'];

    if ($DivisionCode == '') {
        // get zone code for work place as division
        $sqlED = "SELECT ZoneCode
FROM MOENational.dbo.CD_Division
WHERE (CenCode = N'$servicePlace')";
        $resED = $db->runMsSqlQuery($sqlED);
        $rowED = sqlsrv_fetch_array($resED, SQLSRV_FETCH_ASSOC);
        $ZoneCode = $rowED['ZoneCode'];
        $DivisionCode = $servicePlace;
    }
    // get province code in cence tbl
    $sqlPr = "SELECT CD_CensesNo.CenCode
FROM CD_CensesNo 
INNER JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode 
INNER JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
WHERE (CD_Provinces.ProCode = N'$ProCode') AND (CD_CensesNo.CenCode LIKE 'PD%')";
    $resPr = $db->runMsSqlQuery($sqlPr);
    $rowPr = sqlsrv_fetch_array($resPr, SQLSRV_FETCH_ASSOC);
    $provinceCode = $rowPr['CenCode'];

    // **
    // *****
    // get approvel process for logged user
    $sqlApp = "SELECT DISTINCT TG_ApprovalProcess.ApproveAccessRoleValue, CD_AccessRoles.AccessRoleType,TG_ApprovalProcess.ApproveOrder,TG_ApprovalProcessMain.ID AS approvalProcMainID
FROM
    TG_ApprovalProcessMain
        INNER JOIN
    TG_ApprovalProcess ON TG_ApprovalProcessMain.ID = TG_ApprovalProcess.ApprovalProcMainID
        INNER JOIN
    CD_AccessRoles ON TG_ApprovalProcess.ApproveAccessRoleValue = CD_AccessRoles.AccessRoleValue
WHERE
    (TG_ApprovalProcess.Enable = 'Y')
        AND (TG_ApprovalProcessMain.ProcessType = '$processType')
        AND (TG_ApprovalProcessMain.AccessRoleValue = '$accLevel') ORDER BY TG_ApprovalProcess.ApproveOrder";



    $resApp = $db->runMsSqlQuery($sqlApp);
    $TotaAppProcessRows = $db->rowCount($sqlApp);

    while ($rowP = sqlsrv_fetch_array($resApp, SQLSRV_FETCH_ASSOC)) {
        $approveUserRole = $rowP['ApproveAccessRoleValue'];
        $approveUserRoleType = $rowP['AccessRoleType'];
        $approveOrder = $rowP['ApproveOrder'];
        $approvalProcMainID = $rowP['approvalProcMainID'];
        $approvedStatus = '';
        if ($approveOrder == 1)
            $approvedStatus = 'P';


        // do school
        if ($approveUserRoleType == 'SC') {

            $sqlP = "SELECT  StaffServiceHistory.NIC
FROM
    StaffServiceHistory
        INNER JOIN
    Passwords ON StaffServiceHistory.NIC = Passwords.NICNo
        INNER JOIN
    TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
WHERE
    (StaffServiceHistory.InstCode = N'$servicePlace')
        AND (Passwords.AccessLevel = '$approveUserRole')";
            $resSC = $db->runMsSqlQuery($sqlP);
            if ($resSC) {
                while ($rowPri = sqlsrv_fetch_array($resSC, SQLSRV_FETCH_ASSOC)) {
                    $approveUserNic = $rowPri['NIC'];


                    $queryRetirement = "INSERT INTO TG_Request_Approve
           (RequestID,RequestType,RequestUserNIC,ApprovelUserNIC,ApproveProcessOrder,ApprovalProcMainID,ApprovedStatus)
     VALUES
           ('$requestID','$processType','$nicNO','$approveUserNic','$approveOrder','$approvalProcMainID','$approvedStatus')";
                    $saveStatus = $db->runMsSqlQuery($queryRetirement);
                    if (!$saveStatus)
                        $saveStatus = false;
                }
            }
            else {
                $queryRetirement = "INSERT INTO TG_Request_Approve
           (RequestID,RequestType,RequestUserNIC,ApprovelUserNIC,ApproveProcessOrder,ApprovalProcMainID,ApprovedStatus)
     VALUES
           ('$requestID','$processType','$nicNO','','$approveOrder','$approvalProcMainID','$approvedStatus')";
                $saveStatus = $db->runMsSqlQuery($queryRetirement);
                if (!$saveStatus)
                    $saveStatus = false;
            }
        }
        // do division
        if ($approveUserRoleType == 'ED') {
            $sqlP = "SELECT StaffServiceHistory.NIC
FROM
    StaffServiceHistory
        INNER JOIN
    Passwords ON StaffServiceHistory.NIC = Passwords.NICNo
        INNER JOIN
    TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
WHERE
    (StaffServiceHistory.InstCode = N'$DivisionCode')
        AND (Passwords.AccessLevel = '$approveUserRole')";
            $resED = $db->runMsSqlQuery($sqlP);
            if ($resP) {
                // ***
                // get division nominator NIC
                $sqlEDNo= "SELECT StaffServiceHistory.NIC AS nominatorNIC, Passwords.AccessRole, Passwords.AccessLevel
FROM StaffServiceHistory 
INNER JOIN Passwords ON StaffServiceHistory.NIC = Passwords.NICNo 
INNER JOIN TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
WHERE Passwords.AccessLevel = 6000 AND StaffServiceHistory.InstCode = '$DivisionCode'";
                $resEDNo = $db->runMsSqlQuery($sqlEDNo);
                $rowEDNo = sqlsrv_fetch_array($resEDNo, SQLSRV_FETCH_ASSOC);
                $edNominatorNIC = $rowEDNo['nominatorNIC'];
                // **
                
                
                while ($rowPri = sqlsrv_fetch_array($resED, SQLSRV_FETCH_ASSOC)) {
                    $approveUserNic = $rowPri['NIC'];


                    $queryRetirement = "INSERT INTO TG_Request_Approve
           (RequestID,RequestType,RequestUserNIC,ApprovelUserNIC,ApproveUserNominatorNIC,ApproveProcessOrder,ApprovalProcMainID,ApprovedStatus)
     VALUES
           ('$requestID','$processType','$nicNO','$approveUserNic','$edNominatorNIC','$approveOrder','$approvalProcMainID','$approvedStatus')";
                    $saveStatus = $db->runMsSqlQuery($queryRetirement);
                    if (!$saveStatus)
                        $saveStatus = false;
                }
            }
            else {
                $queryRetirement = "INSERT INTO TG_Request_Approve
           (RequestID,RequestType,RequestUserNIC,ApprovelUserNIC,ApproveProcessOrder,ApprovalProcMainID,ApprovedStatus)
     VALUES
           ('$requestID','$processType','$nicNO','','$approveOrder','$approvalProcMainID','$approvedStatus')";
                $saveStatus = $db->runMsSqlQuery($queryRetirement);
                if (!$saveStatus)
                    $saveStatus = false;
            }
        }
        // do zone
        if ($approveUserRoleType == 'ZN') {
            $sqlP = "SELECT StaffServiceHistory.NIC
FROM
    StaffServiceHistory
        INNER JOIN
    Passwords ON StaffServiceHistory.NIC = Passwords.NICNo
        INNER JOIN
    TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
WHERE
    (StaffServiceHistory.InstCode = N'$ZoneCode')
        AND (Passwords.AccessLevel = '$approveUserRole')";
            $resZN = $db->runMsSqlQuery($sqlP);

            if ($resZN) {
                // ***
                // get zonal nominator NIC
                $sqlZNNo= "SELECT StaffServiceHistory.NIC AS nominatorNIC, Passwords.AccessRole, Passwords.AccessLevel
FROM StaffServiceHistory 
INNER JOIN Passwords ON StaffServiceHistory.NIC = Passwords.NICNo 
INNER JOIN TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
WHERE Passwords.AccessLevel = 10000 AND StaffServiceHistory.InstCode = '$ZoneCode'";
                $resZNNo = $db->runMsSqlQuery($sqlZNNo);
                $rowZNNo = sqlsrv_fetch_array($resZNNo, SQLSRV_FETCH_ASSOC);
                $znNominatorNIC = $rowZNNo['nominatorNIC'];
                // **
                
                
                
                while ($rowPri = sqlsrv_fetch_array($resZN, SQLSRV_FETCH_ASSOC)) {
                    $approveUserNic = $rowPri['NIC'];


                    $queryRetirement = "INSERT INTO TG_Request_Approve
           (RequestID,RequestType,RequestUserNIC,ApprovelUserNIC,ApproveUserNominatorNIC,ApproveProcessOrder,ApprovalProcMainID,ApprovedStatus)
     VALUES
           ('$requestID','$processType','$nicNO','$approveUserNic','$znNominatorNIC','$approveOrder','$approvalProcMainID','$approvedStatus')";
                    $saveStatus = $db->runMsSqlQuery($queryRetirement);
                    if (!$saveStatus)
                        $saveStatus = false;
                }
            }
            else {
                $queryRetirement = "INSERT INTO TG_Request_Approve
           (RequestID,RequestType,RequestUserNIC,ApprovelUserNIC,ApproveProcessOrder,ApprovalProcMainID,ApprovedStatus)
     VALUES
           ('$requestID','$processType','$nicNO','','$approveOrder','$approvalProcMainID',$approvedStatus')";
                $saveStatus = $db->runMsSqlQuery($queryRetirement);
                if (!$saveStatus)
                    $saveStatus = false;
            }
        }
        // do province
        if ($approveUserRoleType == 'PD') {
            $sqlP = "SELECT StaffServiceHistory.NIC
FROM
    StaffServiceHistory
        INNER JOIN
    Passwords ON StaffServiceHistory.NIC = Passwords.NICNo
        INNER JOIN
    TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
WHERE
    (StaffServiceHistory.InstCode = N'$provinceCode')
        AND (Passwords.AccessLevel = '$approveUserRole')";
            $resPD = $db->runMsSqlQuery($sqlP);

            if ($resPD) {
                // ***
                // get provincial nominator NIC
                $sqlPDNo= "SELECT StaffServiceHistory.NIC AS nominatorNIC, Passwords.AccessRole, Passwords.AccessLevel
FROM StaffServiceHistory 
INNER JOIN Passwords ON StaffServiceHistory.NIC = Passwords.NICNo 
INNER JOIN TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
WHERE Passwords.AccessLevel = 15000 AND StaffServiceHistory.InstCode = '$provinceCode'";
                $resPDNo = $db->runMsSqlQuery($sqlPDNo);
                $rowPDNo = sqlsrv_fetch_array($resPDNo, SQLSRV_FETCH_ASSOC);
                $pdNominatorNIC = $rowPDNo['nominatorNIC'];
                // **
                
                while ($rowPri = sqlsrv_fetch_array($resPD, SQLSRV_FETCH_ASSOC)) {
                    $approveUserNic = $rowPri['NIC'];


                    $queryRetirement = "INSERT INTO TG_Request_Approve
           (RequestID,RequestType,RequestUserNIC,ApprovelUserNIC,ApproveUserNominatorNIC,ApproveProcessOrder,ApprovalProcMainID,ApprovedStatus)
     VALUES
           ('$requestID','$processType','$nicNO','$approveUserNic','$pdNominatorNIC','$approveOrder','$approvalProcMainID','$approvedStatus')";
                    $saveStatus = $db->runMsSqlQuery($queryRetirement);
                    if (!$saveStatus)
                        $saveStatus = false;
                }
            }
            else{
                $queryRetirement = "INSERT INTO TG_Request_Approve
           (RequestID,RequestType,RequestUserNIC,ApprovelUserNIC,ApproveProcessOrder,ApprovalProcMainID,ApprovedStatus)
     VALUES
           ('$requestID','$processType','$nicNO','','$approveOrder','$approvalProcMainID','$approvedStatus')";
                    $saveStatus = $db->runMsSqlQuery($queryRetirement);
                    if (!$saveStatus)
                        $saveStatus = false;
            }
        }
        // do national
        if ($approveUserRoleType == 'NC') {
            
        }
        // do moe user
        if ($approveUserRoleType == 'MO') {
            
        }
    }
    if($saveStatus)
        $msg = "Save successfully.";
    else
        $msg = "Save fail.";
    
    if($TotaAppProcessRows<1){
        $msg = "Approval process isn't assigned. Please contact your administrator.";
    }
    return $msg;

    // **
}

?>
