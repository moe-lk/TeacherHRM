<?php

function checkApprovalAvailableRegister1($NIC, $NICUser, $formName, $ActivityType, $TableName, $Description) {
    global $db;
    /*
      $nicNO = $_SESSION["NIC"];

      $sqlAppr="SELECT        TG_ApprovalProcessMain.ID, TG_ApprovalProcessMain.ProcessType, TG_ApprovalProcessMain.AccessRoleID, TG_ApprovalProcessMain.AccessRoleValue,
      TG_ApprovalProcessMain.Enable
      FROM            TG_ApprovalProcessMain INNER JOIN
      TG_ApprovalProcess ON TG_ApprovalProcessMain.ID = TG_ApprovalProcess.ApprovalProcMainID
      WHERE        (TG_ApprovalProcessMain.Enable = 'Y') AND (TG_ApprovalProcessMain.AccessRoleValue = '$accLevel') AND (TG_ApprovalProcessMain.ProcessType = '$processType')";
      return $TotaRows=$db->rowCount($sqlAppr);
     * 
     */

    
}

function audit_trail($NIC, $NICUser, $formName, $ActivityType, $TableName, $Description){
    global $db;
    $date = date('Y-m-d H:i:s');
    $queryLog = "INSERT INTO TG_ActivityLog (
	RTimeStamp,
	ApplicantNIC,
	LoggedNIC,
	FormRe,
	ActivityType,
	TableName,
	Description
)
VALUES
	(
        '$date',
        '$NIC',
        '$NICUser',
        '$formName',
        '$ActivityType',
        '$TableName',
        '$Description'		
	)";

    $db->runMsSqlQuery($queryLog);
	//exit();
}

?>