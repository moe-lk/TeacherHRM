<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
$msg = "";
$success = "";
include('../smservices/sms.php');

if (isset($_POST["FrmSubmit"])) {
include('../activityLog.php');

    $dateU = date('Y-m-d H:i:s');
    $dateUP = date('Y-m-d');
    $UpdateBy = "Add by $NICUser";
    //teacher mast
    $RegID = $_REQUEST['RegID'];
    $IsApproved = $_REQUEST['IsApproved'];
    $ApproveComment = addslashes($_REQUEST['ApproveComment']);
    $msg = "";
//get data from temp table - Start
        $reqTab = "SELECT [ID]
      ,[NIC]
      ,[TeacherMastID]
      ,[ServisHistCurrentID]
      ,[ServisHistFirstID]
      ,[AddressHistID]
      ,[dDateTime]
      ,[ZoneCode]
      ,[IsApproved]
      ,[ApproveDate]
      ,[ApprovedBy]
      ,[UpdateBy]
	  ,[AddressHistIDCur]
  FROM [dbo].[TG_EmployeeRegister] WHERE ID='$RegID'";

        $stmtE = $db->runMsSqlQuery($reqTab);
        $rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC);
        $NIC = trim($rowE['NIC']);
        $TeacherMastID = trim($rowE['TeacherMastID']);
        $ServisHistCurrentID = trim($rowE['ServisHistCurrentID']);
        $ServisHistFirstID = trim($rowE['ServisHistFirstID']);
        $AddressHistID = trim($rowE['AddressHistID']);
        $AddressHistIDCur = trim($rowE['AddressHistIDCur']);


    if ($IsApproved == 'Y') {

        $sql_tec = "SELECT NIC,SurnameWithInitials,FullName,Title,PerResRef,MobileTel,emailaddr,CONVERT(varchar(20),DOB,121) AS DOB,GenderCode,EthnicityCode,ReligionCode,CivilStatusCode,CurServiceRef,RecStatus,CONVERT(varchar(20),LastUpdate,121) AS LastUpdate,UpdateBy,RecordLog,CurResRef,CONVERT(varchar(20),DOFA,121) AS DOFA,DOACAT FROM ArchiveUP_TeacherMast where ID='$TeacherMastID'";
        $stmtTec = $db->runMsSqlQuery($sql_tec);

        $rowTec = sqlsrv_fetch_array($stmtTec, SQLSRV_FETCH_ASSOC);
        $Ar_NIC = trim($rowTec['NIC']);
        $Ar_SurnameWithInitials = trim($rowTec['SurnameWithInitials']);
        $Ar_FullName= trim($rowTec['FullName']);
        $Ar_Title = trim($rowTec['Title']);
        $Ar_PerResRef = trim($rowTec['PerResRef']);
        $Ar_MobileTel = trim($rowTec['MobileTel']);
        $Ar_emailaddr = trim($rowTec['emailaddr']);
        $Ar_DOB = trim($rowTec['DOB']);
        $Ar_GenderCode = trim($rowTec['GenderCode']);
        $Ar_EthnicityCode = trim($rowTec['EthnicityCode']);
        $Ar_ReligionCode = trim($rowTec['ReligionCode']);
        $Ar_CivilStatusCode = trim($rowTec['CivilStatusCode']);
        $Ar_CurServiceRef = trim($rowTec['CurServiceRef']);
        $Ar_RecStatus = trim($rowTec['RecStatus']);
        $Ar_LastUpdate = trim($rowTec['LastUpdate']);
        $Ar_UpdateBy = $_SESSION["NIC"];
        $Ar_RecordLog = 'Registration Approved by - '.$_SESSION["NIC"];
        $Ar_CurResRef = trim($rowTec['CurResRef']);
        $Ar_DOFA = $rowTec['DOFA'];
        $Ar_DOACAT = trim($rowTec['DOACAT']);

        $sqlCopyMaster = "INSERT INTO TeacherMast (
	NIC,
	SurnameWithInitials,
	FullName,
	Title,
	PerResRef,
	MobileTel,
	emailaddr,
	DOB,
	GenderCode,
	EthnicityCode,
	ReligionCode,
	CivilStatusCode,
	CurServiceRef,
	RecStatus,
	LastUpdate,
	UpdateBy,
	RecordLog,
	CurResRef,
	DOFA,
	DOACAT
)
VALUES
	(
		'$Ar_NIC',
		'$Ar_SurnameWithInitials',
		'$Ar_FullName',
		'$Ar_Title',
		'$Ar_PerResRef',
		'$Ar_MobileTel',
		'$Ar_emailaddr',
		'$Ar_DOB',
		'$Ar_GenderCode',
		'$Ar_EthnicityCode',
		'$Ar_ReligionCode',
		'$Ar_CivilStatusCode',
		'$Ar_CurServiceRef',
		'$Ar_RecStatus',
		'$Ar_LastUpdate',
		'$Ar_UpdateBy',
		'$Ar_RecordLog',
		'$Ar_CurResRef',
                '$Ar_DOFA',
                '$Ar_DOACAT'
	)";

        $db->runMsSqlQuery($sqlCopyMaster);


        $reqTabMobAc = "SELECT ID FROM TeacherMast where NIC='$NIC' ORDER BY ID DESC";
        $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
        $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
        $thsTeacherMasterID = trim($rowMobAc['ID']);


        $sqlCopyMaster = "INSERT INTO StaffAddrHistory (NIC,AddrType,Address,DSCode,DISTCode,Tel,AppDate,UpdateBy,LastUpdate,RecordLog,GSDivision)
	SELECT NIC,AddrType,Address,DSCode,DISTCode,Tel,AppDate,UpdateBy,LastUpdate,RecordLog,GSDivision FROM ArchiveUP_StaffAddrHistory where ID='$AddressHistID'";
        $db->runMsSqlQuery($sqlCopyMaster);

        $reqTabMobAc = "SELECT ID FROM StaffAddrHistory where NIC='$NIC' and AddrType='PER' ORDER BY ID DESC";
        $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
        $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
        $perAddressID = trim($rowMobAc['ID']);


        $sqlCopyMasterC = "INSERT INTO StaffAddrHistory	NIC,AddrType,Address,DSCode,DISTCode,Tel,AppDate,UpdateBy,LastUpdate,RecordLog,GSDivision)
	SELECT NIC,AddrType,Address,DSCode,DISTCode,Tel,AppDate,UpdateBy,LastUpdate,RecordLog,GSDivision FROM ArchiveUP_StaffAddrHistory where ID='$AddressHistIDCur'";
        $db->runMsSqlQuery($sqlCopyMasterC);

        $reqTabMobAc = "SELECT ID FROM StaffAddrHistory where NIC='$NIC' and AddrType='CUR' ORDER BY ID DESC";
        $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
        $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
        $curAddressID = trim($rowMobAc['ID']);



        $sqlCopyMaster = "INSERT INTO StaffServiceHistory			   (NIC,ServiceRecTypeCode,AppDate,InstCode,SecGRCode,WorkStatusCode,ServiceTypeCode,EmpTypeCode,PositionCode,Cat2003Code,Reference,UpdateBy,LastUpdate,RecordLog)
	SELECT NIC,ServiceRecTypeCode,AppDate,InstCode,SecGRCode,WorkStatusCode,ServiceTypeCode,EmpTypeCode,PositionCode,Cat2003Code,Reference,UpdateBy,LastUpdate,RecordLog FROM ArchiveUP_StaffServiceHistory where ID='$ServisHistCurrentID'";
        //$db->runMsSqlQuery($sqlCopyMaster);
        $db->runMsSqlQuery($sqlCopyMaster);

        $reqTabMobAc = "SELECT ID FROM StaffServiceHistory where NIC='$NIC' ORDER BY ID DESC";
        $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
        $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
        $curMasterID = trim($rowMobAc['ID']);

        //update data into master table - End
        //update TeacherMaster
        $queryMainUpdate = "UPDATE TeacherMast SET PerResRef='$perAddressID', CurResRef='$curAddressID', CurServiceRef='$curMasterID' WHERE NIC='$NIC'";
        $db->runMsSqlQuery($queryMainUpdate);


        $sqlCopyMaster = "INSERT INTO StaffServiceHistory			   (NIC,ServiceRecTypeCode,AppDate,InstCode,SecGRCode,WorkStatusCode,ServiceTypeCode,EmpTypeCode,PositionCode,Cat2003Code,Reference,UpdateBy,LastUpdate,RecordLog)
	SELECT NIC,ServiceRecTypeCode,AppDate,InstCode,SecGRCode,WorkStatusCode,ServiceTypeCode,EmpTypeCode,PositionCode,Cat2003Code,Reference,UpdateBy,LastUpdate,RecordLog FROM ArchiveUP_StaffServiceHistory where ID='$ServisHistFirstID'";
        //$db->runMsSqlQuery($sqlCopyMaster);
        $db->runMsSqlQuery($sqlCopyMaster);

        $reqTabMobAc = "SELECT ID FROM StaffServiceHistory where NIC='$NIC' ORDER BY ID DESC";
        $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
        $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
        $firtstMasterID = trim($rowMobAc['ID']);



        //update data into master table - End


        $queryMainUpdate = "UPDATE TG_EmployeeRegister SET IsApproved='Y',ApproveDate='$dateU',ApprovedBy='$NICUser', ApproveComment='$ApproveComment' WHERE id='$RegID'";
        $db->runMsSqlQuery($queryMainUpdate);

        $TeacherMastID = trim($rowE['TeacherMastID']);
        $ServisHistCurrentID = trim($rowE['ServisHistCurrentID']);
        $ServisHistFirstID = trim($rowE['ServisHistFirstID']);
        $AddressHistID = trim($rowE['AddressHistID']);
        $AddressHistIDCur = trim($rowE['AddressHistIDCur']);
        //Delete temp record
        $queryTmpDel = "DELETE FROM ArchiveUP_TeacherMast WHERE ID='$TeacherMastID'";
        $db->runMsSqlQuery($queryTmpDel);

        $queryTmpDel = "DELETE FROM ArchiveUP_StaffAddrHistory WHERE ID='$AddressHistID'";
        $db->runMsSqlQuery($queryTmpDel);

        $queryTmpDel = "DELETE FROM ArchiveUP_StaffAddrHistory WHERE ID='$AddressHistIDCur'";
        $db->runMsSqlQuery($queryTmpDel);

        $queryTmpDel = "DELETE FROM ArchiveUP_StaffServiceHistory WHERE ID='$ServisHistCurrentID'";
        $db->runMsSqlQuery($queryTmpDel);

        $queryTmpDel = "DELETE FROM ArchiveUP_StaffServiceHistory WHERE ID='$ServisHistFirstID'";
        $db->runMsSqlQuery($queryTmpDel);

        $queryTmpDel = "DELETE FROM ArchiveUP_StaffAssignDetails WHERE ServiceRecRef='$ServisHistCurrentID'";
        $db->runMsSqlQuery($queryTmpDel);
        $queryTmpDel = "DELETE FROM ArchiveUP_StaffAssignDetails WHERE ServiceRecRef='$ServisHistFirstID'";
        $db->runMsSqlQuery($queryTmpDel);

        $queryTmpDel = "DELETE FROM TG_EmployeeRegister WHERE id='$RegID'";
        $db->runMsSqlQuery($queryTmpDel);


        $msg .= "Your action was successffully submitted.<br>";

        $reqTabMob = "SELECT MobileTel FROM TeacherMast where NIC='$NIC'";
        $stmtMob = $db->runMsSqlQuery($reqTabMob);
        $rowMob = sqlsrv_fetch_array($stmtMob, SQLSRV_FETCH_ASSOC);
        $MobileTel = trim($rowMob['MobileTel']);

        $tpNumber = numberFormat($MobileTel);


        audit_trail($Ar_NIC, $_SESSION["NIC"], 'approval\newRegistration.php', 'insert,update', 'TeacherMast', 'User Account Approved.');


        /* Send SMS via GOV SMS */
        $sms_content = 'Registration approved';
        $config = array('message' => $sms_content, 'recepient' => $tpNumber); //0779105338
        $smso = new sms();
        $result = $smso->sendsms($config, 1);
        if ($result[0] == 1) {
            //SMS Sent
            //echo 'ok';
            $statusOf = "Success";
        } else if ($result[0] == 2) {
            //SMS Sent
            //echo 'ok';
            //$statusOf="Success";
        } else {
            //SMS wasn't Sent
            //echo 'error';
            $statusOf = "Fail";
        }
        //end SMS
        if ($result[0] != 2) {
            $queryRegissms = "INSERT INTO TG_SMS (NIC,ModuleName,dDateTime,StatusOf,RecID) VALUES ('$NIC','Registration Approved','$dateU','$statusOf','$thsTeacherMasterID')";
            $db->runMsSqlQuery($queryRegissms);
        }
    } else {
        //Delete temp record
        $queryTmpDel = "DELETE FROM ArchiveUP_TeacherMast WHERE ID='$TeacherMastID'";
        $db->runMsSqlQuery($queryTmpDel);

        $queryTmpDel = "DELETE FROM ArchiveUP_StaffAddrHistory WHERE ID='$AddressHistID'";
        $db->runMsSqlQuery($queryTmpDel);

        $queryTmpDel = "DELETE FROM ArchiveUP_StaffAddrHistory WHERE ID='$AddressHistIDCur'";
        $db->runMsSqlQuery($queryTmpDel);

        $queryTmpDel = "DELETE FROM ArchiveUP_StaffServiceHistory WHERE ID='$ServisHistCurrentID'";
        $db->runMsSqlQuery($queryTmpDel);

        $queryTmpDel = "DELETE FROM ArchiveUP_StaffServiceHistory WHERE ID='$ServisHistFirstID'";
        $db->runMsSqlQuery($queryTmpDel);

        $queryTmpDel = "DELETE FROM ArchiveUP_StaffAssignDetails WHERE ServiceRecRef='$ServisHistCurrentID'";
        $db->runMsSqlQuery($queryTmpDel);
        $queryTmpDel = "DELETE FROM ArchiveUP_StaffAssignDetails WHERE ServiceRecRef='$ServisHistFirstID'";
        $db->runMsSqlQuery($queryTmpDel);

        //$queryTmpDel = "DELETE FROM TG_EmployeeRegister WHERE id='$RegID'";
        //$db->runMsSqlQuery($queryTmpDel);


        $queryMainUpdate = "UPDATE TG_EmployeeRegister SET IsApproved='R',ApproveDate='$dateU',ApprovedBy='$NICUser', ApproveComment='$ApproveComment' WHERE id='$RegID'";
        $db->runMsSqlQuery($queryMainUpdate);
        $msg .= "Your action was successffully submitted.<br>";

        audit_trail($NIC, $_SESSION["NIC"], 'approval\newRegistration.php', 'Delete,Update', 'TeacherMast', 'User Account registration reject.');

        $reqTabMob = "SELECT MobileTel FROM TeacherMast where NIC='$NIC'";
        $stmtMob = $db->runMsSqlQuery($reqTabMob);
        $rowMob = sqlsrv_fetch_array($stmtMob, SQLSRV_FETCH_ASSOC);
        $MobileTel = trim($rowMob['MobileTel']);

        $tpNumber = numberFormat($MobileTel);

        /* Send SMS via GOV SMS */
        $sms_content = 'Registration reject';
        $config = array('message' => $sms_content, 'recepient' => $tpNumber); //0779105338
        $smso = new sms();
        $result = $smso->sendsms($config, 1);
        if ($result[0] == 1) {
            //SMS Sent
            //echo 'ok';
            $statusOf = "Success";
        } else if ($result[0] == 2) {
            //SMS Sent
            //echo 'ok';
            //$statusOf="Success";
        } else {
            //SMS wasn't Sent
            //echo 'error';
            $statusOf = "Fail";
        }
        //end SMS
        if ($result[0] != 2) {
            $queryRegissms = "INSERT INTO TG_SMS (NIC,ModuleName,dDateTime,StatusOf,RecID) VALUES ('$NIC','Registration Reject','$dateU','$statusOf','$thsTeacherMasterID')";
            $db->runMsSqlQuery($queryRegissms);
        }
    }
}

if ($id != '') {
    $reqTab = "SELECT [ID]
      ,[NIC]
      ,[TeacherMastID]
      ,[ServisHistCurrentID]
      ,[ServisHistFirstID]
      ,[AddressHistID]
      ,[dDateTime]
      ,[ZoneCode]
      ,[IsApproved]
      ,[ApproveDate]
      ,[ApprovedBy]
      ,[UpdateBy]
	  ,[AddressHistIDCur]
  FROM [dbo].[TG_EmployeeRegister] WHERE ID='$id'";

    $stmtE = $db->runMsSqlQuery($reqTab);
    $rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC);
    $NIC = trim($rowE['NIC']);
    $TeacherMastID = trim($rowE['TeacherMastID']);
    $ServisHistCurrentID = trim($rowE['ServisHistCurrentID']);
    $ServisHistFirstID = trim($rowE['ServisHistFirstID']);
    $AddressHistID = trim($rowE['AddressHistID']);
    $AddressHistIDCur = trim($rowE['AddressHistIDCur']);

    $sqlteachrMst = "SELECT        ArchiveUP_TeacherMast.ID, ArchiveUP_TeacherMast.NIC, ArchiveUP_TeacherMast.SurnameWithInitials, ArchiveUP_TeacherMast.FullName,
                         ArchiveUP_TeacherMast.MobileTel, CONVERT(varchar(20), ArchiveUP_TeacherMast.DOB, 121) AS DOB, ArchiveUP_TeacherMast.emailaddr, CD_Title.TitleName, CD_Gender.[Gender Name],
                         CD_nEthnicity.EthnicityName, CD_Religion.ReligionName, CD_CivilStatus.CivilStatusName
FROM            CD_Religion INNER JOIN
                         CD_Gender INNER JOIN
                         ArchiveUP_TeacherMast INNER JOIN
                         CD_Title ON ArchiveUP_TeacherMast.Title = CD_Title.TitleCode ON CD_Gender.GenderCode = ArchiveUP_TeacherMast.GenderCode INNER JOIN
                         CD_nEthnicity ON ArchiveUP_TeacherMast.EthnicityCode = CD_nEthnicity.Code ON CD_Religion.Code = ArchiveUP_TeacherMast.ReligionCode INNER JOIN
                         CD_CivilStatus ON ArchiveUP_TeacherMast.CivilStatusCode = CD_CivilStatus.Code
WHERE        (ArchiveUP_TeacherMast.ID = '$TeacherMastID')"; //(ArchiveUP_TeacherMast.NIC = '850263230V')

    $stmtTM = $db->runMsSqlQuery($sqlteachrMst);
    $rowTM = sqlsrv_fetch_array($stmtTM, SQLSRV_FETCH_ASSOC);
    $NIC = trim($rowTM['NIC']);
    $SurnameWithInitials = trim($rowTM['SurnameWithInitials']);
    $FullName = trim($rowTM['FullName']);
    $MobileTel = trim($rowTM['MobileTel']);
    $emailaddr = trim($rowTM['emailaddr']);
    $DOB = trim($rowTM['DOB']);
    $TitleName = trim($rowTM['TitleName']);
    $GenderName = trim($rowTM['Gender Name']);
    $EthnicityName = trim($rowTM['EthnicityName']);
    $ReligionName = trim($rowTM['ReligionName']);
    $CivilStatusName = trim($rowTM['CivilStatusName']);

    $sqlContactInfo = "SELECT        CD_Districts.DistName, ArchiveUP_StaffAddrHistory.Address, ArchiveUP_StaffAddrHistory.Tel, ArchiveUP_StaffAddrHistory.ID, CONVERT(varchar(20), ArchiveUP_StaffAddrHistory.AppDate, 121) AS AppDate,
                         CD_DSec.DSName, ArchiveUP_StaffAddrHistory.GSDivision
FROM            ArchiveUP_StaffAddrHistory INNER JOIN
                         CD_Districts ON ArchiveUP_StaffAddrHistory.DISTCode = CD_Districts.DistCode INNER JOIN
                         CD_DSec ON ArchiveUP_StaffAddrHistory.DSCode = CD_DSec.DSCode
WHERE        (ArchiveUP_StaffAddrHistory.ID = '$AddressHistID')";
    $stmtCI = $db->runMsSqlQuery($sqlContactInfo);
    $rowCI = sqlsrv_fetch_array($stmtCI, SQLSRV_FETCH_ASSOC);

    $Address = trim($rowCI['Address']);
    $Tel = trim($rowCI['Tel']);
    $AppDateCI = trim($rowCI['AppDate']);
    $DSName = trim($rowCI['DSName']);
    $DistName = trim($rowCI['DistName']);
    $GSDivision = trim($rowCI['GSDivision']);

    $sqlContactInfoCr = "SELECT        CD_Districts.DistName, ArchiveUP_StaffAddrHistory.Address, ArchiveUP_StaffAddrHistory.Tel, ArchiveUP_StaffAddrHistory.ID, CONVERT(varchar(20), ArchiveUP_StaffAddrHistory.AppDate, 121) AS AppDate,
                         CD_DSec.DSName, ArchiveUP_StaffAddrHistory.GSDivision
FROM            ArchiveUP_StaffAddrHistory INNER JOIN
                         CD_Districts ON ArchiveUP_StaffAddrHistory.DISTCode = CD_Districts.DistCode INNER JOIN
                         CD_DSec ON ArchiveUP_StaffAddrHistory.DSCode = CD_DSec.DSCode
WHERE        (ArchiveUP_StaffAddrHistory.ID = '$AddressHistIDCur')";
    $stmtCICr = $db->runMsSqlQuery($sqlContactInfoCr);
    $rowCICr = sqlsrv_fetch_array($stmtCICr, SQLSRV_FETCH_ASSOC);

    $AddressT = trim($rowCICr['Address']);
    $TelT = trim($rowCICr['Tel']);
    $AppDateCIT = trim($rowCICr['AppDate']);
    $DSNameT = trim($rowCICr['DSName']);
    $DistNameT = trim($rowCICr['DistName']);
    $GSDivisionT = trim($rowCICr['GSDivision']);
    /* $sqlContactInfo="SELECT    Address, Tel, ID,  CONVERT(varchar(20), AppDate, 121) AS AppDate, DSCode, DISTCode
      FROM            ArchiveUP_StaffAddrHistory
      WHERE        (ArchiveUP_StaffAddrHistory.ID = '$AddressHistID')";
      $stmtCI= $db->runMsSqlQuery($sqlContactInfo);
      $rowCI = sqlsrv_fetch_array($stmtCI, SQLSRV_FETCH_ASSOC);

      $Address = trim($rowCI['Address']);
      $Tel = trim($rowCI['Tel']);
      $AppDateCI = trim($rowCI['AppDate']);
      $DSCode = trim($rowCI['DSCode']);
      $DISTCode = trim($rowCI['DISTCode']);

      $sqlDist="SELECT [DistCode]
      ,[DistName]
      ,[ProCode]
      ,[RecordLog]
      FROM [dbo].[CD_Districts] WHERE DistCode='$DISTCode'";
      $stmtD= $db->runMsSqlQuery($sqlDist);
      $rowD = sqlsrv_fetch_array($stmtD, SQLSRV_FETCH_ASSOC);
      $DistName = trim($rowD['DistName']);

      $sqlDist="SELECT [DistName]
      ,[DSCode]
      ,[DSName]
      ,[RecordLog]
      FROM [dbo].[CD_DSec] WHERE DSCode='$DSCode'";
      $stmtD= $db->runMsSqlQuery($sqlDist);
      $rowD = sqlsrv_fetch_array($stmtD, SQLSRV_FETCH_ASSOC);
      $DSName = trim($rowD['DSName']); */

    $sqlCurrentApp = "SELECT        CD_CAT2003.Cat2003Name, CD_CensesNo.InstitutionName, CD_ServiceRecType.Description, CD_SecGrades.GradeName, CD_Positions.PositionName,
                         CD_Service.ServiceName, ArchiveUP_StaffServiceHistory.ID, CONVERT(varchar(20), ArchiveUP_StaffServiceHistory.AppDate, 121) AS AppDate, ArchiveUP_StaffServiceHistory.InstCode,ArchiveUP_StaffServiceHistory.Reference
FROM            CD_CAT2003 LEFT JOIN
                         ArchiveUP_StaffServiceHistory ON CD_CAT2003.Cat2003Code = ArchiveUP_StaffServiceHistory.Cat2003Code LEFT JOIN
                         CD_CensesNo ON ArchiveUP_StaffServiceHistory.InstCode = CD_CensesNo.CenCode LEFT JOIN
                         CD_SecGrades ON ArchiveUP_StaffServiceHistory.SecGRCode = CD_SecGrades.GradeCode LEFT JOIN
                         CD_Positions ON ArchiveUP_StaffServiceHistory.PositionCode = CD_Positions.Code LEFT JOIN
                         CD_ServiceRecType ON ArchiveUP_StaffServiceHistory.ServiceRecTypeCode = CD_ServiceRecType.DutyCode LEFT JOIN
                         CD_Service ON ArchiveUP_StaffServiceHistory.ServiceTypeCode = CD_Service.ServCode
WHERE        (ArchiveUP_StaffServiceHistory.ID = '$ServisHistCurrentID')";

    $stmtCA = $db->runMsSqlQuery($sqlCurrentApp);
    $rowCA = sqlsrv_fetch_array($stmtCA, SQLSRV_FETCH_ASSOC);

    $Cat2003Name = trim($rowCA['Cat2003Name']);
    $InstitutionNameC = trim($rowCA['InstitutionName']);
    $DescriptionC = trim($rowCA['Description']);
    $GradeNameC = trim($rowCA['GradeName']);
    $PositionNameC = trim($rowCA['PositionName']);
    $ServiceNameC = trim($rowCA['ServiceName']);
    $AppDateC = trim($rowCA['AppDate']);
    $InstCodeC = trim($rowCA['InstCode']);
    $PFReferenceC = trim($rowCA['Reference']);

    $sqlCurrDis = "SELECT        CD_Districts.DistName, CD_Zone.InstitutionName, CD_Division.InstitutionName AS Expr1, CD_CensesNo.CenCode
FROM            CD_CensesNo INNER JOIN
                         CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode INNER JOIN
                         CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode INNER JOIN
                         CD_Division ON CD_CensesNo.DivisionCode = CD_Division.CenCode
WHERE        (CD_CensesNo.CenCode = N'$InstCodeC')";
    $stmtCA = $db->runMsSqlQuery($sqlCurrDis);
    $rowCA = sqlsrv_fetch_array($stmtCA, SQLSRV_FETCH_ASSOC);
    $DistNameC = trim($rowCA['DistName']);
    $InstitutionNameCZone = trim($rowCA['InstitutionName']);
    $InstitutionNameCDivision = trim($rowCA['Expr1']);

    $sqlCurrentApp = "SELECT        CD_CAT2003.Cat2003Name, CD_CensesNo.InstitutionName, CD_ServiceRecType.Description, CD_SecGrades.GradeName, CD_Positions.PositionName,
                         CD_Service.ServiceName, ArchiveUP_StaffServiceHistory.ID, CONVERT(varchar(20), ArchiveUP_StaffServiceHistory.AppDate, 121) AS AppDate, ArchiveUP_StaffServiceHistory.InstCode, ArchiveUP_StaffServiceHistory.Reference
FROM            CD_CAT2003 LEFT JOIN
                         ArchiveUP_StaffServiceHistory ON CD_CAT2003.Cat2003Code = ArchiveUP_StaffServiceHistory.Cat2003Code LEFT JOIN
                         CD_CensesNo ON ArchiveUP_StaffServiceHistory.InstCode = CD_CensesNo.CenCode LEFT JOIN
                         CD_SecGrades ON ArchiveUP_StaffServiceHistory.SecGRCode = CD_SecGrades.GradeCode LEFT JOIN
                         CD_Positions ON ArchiveUP_StaffServiceHistory.PositionCode = CD_Positions.Code LEFT JOIN
                         CD_ServiceRecType ON ArchiveUP_StaffServiceHistory.ServiceRecTypeCode = CD_ServiceRecType.DutyCode LEFT JOIN
                         CD_Service ON ArchiveUP_StaffServiceHistory.ServiceTypeCode = CD_Service.ServCode
WHERE        (ArchiveUP_StaffServiceHistory.ID = '$ServisHistFirstID')";

    $stmtCA = $db->runMsSqlQuery($sqlCurrentApp);
    $rowCA = sqlsrv_fetch_array($stmtCA, SQLSRV_FETCH_ASSOC);

    $Cat2003NameF = trim($rowCA['Cat2003Name']);
    $InstitutionNameF = trim($rowCA['InstitutionName']);
    $DescriptionF = trim($rowCA['Description']);
    $GradeNameF = trim($rowCA['GradeName']);
    $PositionNameF = trim($rowCA['PositionName']);
    $ServiceNameF = trim($rowCA['ServiceName']);
    $AppDateF = trim($rowCA['AppDate']);
    $InstCodeF = trim($rowCA['InstCode']);
    $PFReferenceF = trim($rowCA['Reference']);

    $sqlFirstDis = "SELECT        CD_Districts.DistName, CD_Zone.InstitutionName, CD_Division.InstitutionName AS Expr1, CD_CensesNo.CenCode
FROM            CD_CensesNo INNER JOIN
                         CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode INNER JOIN
                         CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode INNER JOIN
                         CD_Division ON CD_CensesNo.DivisionCode = CD_Division.CenCode
WHERE        (CD_CensesNo.CenCode = N'$InstCodeF')";
    $stmtCA = $db->runMsSqlQuery($sqlFirstDis);
    $rowCA = sqlsrv_fetch_array($stmtCA, SQLSRV_FETCH_ASSOC);
    $DistNameF = trim($rowCA['DistName']);
    $InstitutionNameFZone = trim($rowCA['InstitutionName']);
    $InstitutionNameFDivision = trim($rowCA['Expr1']);
}

if ($id == '') {

    $Per_Page = 30;  // Per Page
    //Get the page number

    $Page = 1;

    //Determine if it is the first page

    /* if(isset($_GET["Page"]))
      {
      $Page=(int)$_GET["Page"];
      if ($Page < 1)
      $Page = 1;
      } */

    if ($menu) {
        $Page = (int) $menu;
        if ($Page < 1)
            $Page = 1;
    }

    $Page_Start = (($Per_Page * $Page) - $Per_Page) + 1;
    $Page_End = $Page_Start + $Per_Page - 1;


    $NICSearch = "";
    if (isset($_POST["FrmSrch"])) {
        $NICSearch = $_REQUEST['NICSearch'];
    }
    $approvSql = "WITH LIMIT AS(SELECT        TG_EmployeeRegister.ID, TG_EmployeeRegister.NIC, TG_EmployeeRegister.TeacherMastID, TG_EmployeeRegister.ServisHistCurrentID,
							 TG_EmployeeRegister.ServisHistFirstID, TG_EmployeeRegister.AddressHistID, CONVERT(varchar(20), TG_EmployeeRegister.dDateTime, 121) AS dDateTime,
							 TG_EmployeeRegister.IsApproved, ArchiveUP_TeacherMast.SurnameWithInitials, CD_Title.TitleName, CD_Zone.InstitutionName, CD_Districts.DistName, CD_Provinces.Province, ROW_NUMBER() OVER (ORDER BY TG_EmployeeRegister.ID DESC) AS 'RowNumber'
	FROM            ArchiveUP_TeacherMast LEFT JOIN
							 TG_EmployeeRegister ON ArchiveUP_TeacherMast.ID = TG_EmployeeRegister.TeacherMastID LEFT JOIN
							 CD_Title ON ArchiveUP_TeacherMast.Title = CD_Title.TitleCode LEFT JOIN
							 CD_Zone ON TG_EmployeeRegister.ZoneCode = CD_Zone.CenCode LEFT JOIN
							 CD_Districts ON CD_Zone.DistrictCode = CD_Districts.DistCode LEFT JOIN
							 CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
							 WHERE TG_EmployeeRegister.IsApproved='N'";
    if ($NICSearch)
        $approvSql .= " and (TG_EmployeeRegister.NIC like '%$NICSearch%')";
    //if ($accLevel == '11050' || $accLevel == '11000' || $accLevel == '10000')
    if ($AccessRoleType=="ZN")
        $approvSql .= " and TG_EmployeeRegister.ZoneCode='$loggedSchool'";

    $approvSql .= ")
	select * from LIMIT WHERE RowNumber BETWEEN $Page_Start AND $Page_End";

    $countTotal = "SELECT        TG_EmployeeRegister.ID
	FROM            ArchiveUP_TeacherMast LEFT JOIN
							 TG_EmployeeRegister ON ArchiveUP_TeacherMast.ID = TG_EmployeeRegister.TeacherMastID LEFT JOIN
							 CD_Title ON ArchiveUP_TeacherMast.Title = CD_Title.TitleCode LEFT JOIN
							 CD_Zone ON TG_EmployeeRegister.ZoneCode = CD_Zone.CenCode LEFT JOIN
							 CD_Districts ON CD_Zone.DistrictCode = CD_Districts.DistCode
							 WHERE TG_EmployeeRegister.IsApproved='N'";
    if ($NICSearch)
        $countTotal .= " and (TG_EmployeeRegister.NIC like '%$NICSearch%')";
    //if ($accLevel == '11050' || $accLevel == '11000' || $accLevel == '10000')
    if ($AccessRoleType=="ZN")
        $countTotal .= " and TG_EmployeeRegister.ZoneCode='$loggedSchool'";
    $TotaRows = $db->rowCount($countTotal);
    if (!$TotaRows)
        $TotaRows = 0;

    //Declare previous/next page row guide

    $Prev_Page = $Page - 1;
    $Next_Page = $Page + 1;

    if ($TotaRows <= $Per_Page) {
        $Num_Pages = 1;
    } else if (($TotaRows % $Per_Page) == 0) {
        $Num_Pages = ($TotaRows / $Per_Page);
    } else {
        $Num_Pages = ($TotaRows / $Per_Page) + 1;
        $Num_Pages = (int) $Num_Pages;
    }

    //Determine where the page will end

    $Page_End = $Per_Page * $Page;
    if ($Page_End > $TotaRows) {
        $Page_End = $TotaRows;
    }
}
?>

<?php if ($id == '') { ?>
    <div style="width:738px; margin-top:10px;"><form method="post" action="" name="frmSrch" id="frmSrch"><table width="100%" cellspacing="1" cellpadding="1">
                <tr>
                    <td width="19%">Search by NIC</td>
                    <td width="27%"><input name="NICSearch" type="text" class="input2_n" id="NICSearch" value="" placeholder="NIC"/></td>
                    <td width="11%"><input name="FrmSrch" type="submit" id="FrmSrch" style="background-image: url(../cms/images/searchN.png); width:84px; height:26px; background-color:transparent; border:none; cursor:pointer;" value="" /></td>
                    <td width="43%"><div id="txt_available" style="font-weight:bold;"></div></td>
                </tr>
                <tr>
                    <td colspan="4" style="border-bottom:1px; border-bottom-style:solid;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
            </table></form>
    </div>
<?php } ?>
<form method="post" action="newRegistration-16.html" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">

<?php if ($msg != '' || $success != '') {//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){   ?>
        <div class="mcib_middle_full" style="float:left;">
            <div class="form_error"><?php
    echo $msg;
    echo $success;
    echo $_SESSION['success_update'];
    $_SESSION['success_update'] = "";
    ?><?php
    echo $_SESSION['fail_update'];
    $_SESSION['fail_update'] = "";
    ?></div>
        </div>
<?php } ?>
    <div style="width:738px; float:left;">
<?php if ($id == '') { ?>

            <table width="100%" cellpadding="0" cellspacing="0">

                <tr>
                    <td width="57%"><?php echo $TotaRows ?> Record(s) found. Showing <?php echo $Per_Page ?> records per page.</td>
                    <td width="43%">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                            <tr>
                                <td width="4%" height="25" align="center" bgcolor="#999999">#</td>
                                <td width="29%" align="center" bgcolor="#999999">Employee Name</td>
                                <td width="14%" align="center" bgcolor="#999999">NIC</td>
                                <td width="11%" align="center" bgcolor="#999999">Request Date</td>
                                <td width="35%" align="center" bgcolor="#999999">Zone</td>
                                <td width="7%" align="center" bgcolor="#999999">Action</td>
                            </tr>
                <?php
                //$i=1;
                $stmt = $db->runMsSqlQuery($approvSql);
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $RequestID = $row['ID'];
                    $InstitutionName = $row['InstitutionName'];
                    $DistName = $row['DistName'];
                    $RowNumber = $row['RowNumber'];
                    $TeacherMastID = $row['TeacherMastID'];
                    ?>
                                <tr>
                                    <td height="20" bgcolor="#FFFFFF"><?php echo $RowNumber; ?></td>
                                    <td bgcolor="#FFFFFF"><?php echo $row['SurnameWithInitials']; ?></td>
                                    <td bgcolor="#FFFFFF"><?php echo $row['NIC']; ?></td>
                                    <td bgcolor="#FFFFFF" align="center"><?php echo substr($row['dDateTime'], 0, 10); ?></td>
                                    <td bgcolor="#FFFFFF" align="left">&nbsp;<?php echo "$InstitutionName ($DistName)"; ?></td>
                                    <td bgcolor="#FFFFFF" align="center"><?php if ($TeacherMastID < 10000) {
                        echo "";
                    } else { ?><a href="newRegistration-16--<?php echo $RequestID ?>.html"><img src="images/more_info.png" /></a><?php } ?></td>
                                </tr>
    <?php } ?>
                        </table></td>
                </tr>

                <tr>
                    <td colspan="2"><table width="100%" cellspacing="1" cellpadding="1">
                            <tr>
                                <td width="67%">Page <?php echo $Page ?> of <?php echo $Num_Pages ?></td>
                                <td width="20%" align="right"><?php
//Previous page

                            if ($Prev_Page) {
                                echo " <a href='$ttle-$pageid-$Prev_Page.html?Page=$Prev_Page#related'><< Previous</a> ";
                            }

//Display total pages
//for($i=1; $i<=$Num_Pages; $i++){


                            /* for($i=1; $i<=5; $i++){
                              if($i != $Page)
                              {
                              echo "<a href='$_SERVER[SCRIPT_NAME]?id=$id&Page=$i#related'>$i</a>&nbsp;";
                              }
                              else
                              {
                              echo "<b> $i </b>";
                              }
                              } */
                            ?></td>
                                <td width="2%" align="center"><?php if ($Prev_Page and $Page != $Num_Pages) { ?> | <?php } ?></td>
                                <td width="11%" align="left"><?php
                                    //Create next page link

                                    if ($Page != $Num_Pages) {
                                        //echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Next_Page#related'>Next>></a> ";
                                        echo " <a href ='$ttle-$pageid-$Next_Page.html?Page=$Next_Page#related'>Next>></a> ";
                                    }
                                    ?></td>
                            </tr>
                        </table></td>
                </tr>

            </table> <?php } else { ?>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td colspan="2" align="center" height="30px" style="font-size:16px; font-weight:bold;"><u>Registration Form</u></td>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;"><strong>Personal Information</strong></td>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td align="right" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td width="50%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="30%" align="left" valign="top"><strong>NIC</strong></td>
                                <td width="3%" align="left" valign="top"><strong>:</strong></td>
                                <td width="67%" align="left" valign="top"><?php echo $NIC ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Title</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $TitleName ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Surname with Initials</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $SurnameWithInitials ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Full Name</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $FullName ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Date of Birth</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $DOB; ?></td>
                            </tr>

                        </table>
                    </td>
                    <td width="50%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td align="left" valign="top"><strong>Civil Status</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $CivilStatusName ?></td>
                            </tr>
                            <tr>
                                <td width="38%" align="left" valign="top"><strong>Ethnicity</strong></td>
                                <td width="3%" align="left" valign="top"><strong>:</strong></td>
                                <td width="59%" align="left" valign="top"><?php echo $EthnicityName ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Gender</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $GenderName ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Religion</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $ReligionName ?></td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;"><strong>Contact Information (Permanent)</strong></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td align="left" valign="top"><strong>Address</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" rowspan="4" align="left" valign="top"><?php echo $Address ?></td>
                                <td align="left" valign="top"><strong>GS Division</strong></td>
                                <td align="left" valign="top">:</td>
                                <td align="left" valign="top"><?php echo $GSDivision ?></td>
                            </tr>
                            <tr>
                                <td width="15%" align="left" valign="top">&nbsp;</td>
                                <td width="1%" align="left" valign="top">&nbsp;</td>
                                <td width="19%" align="left" valign="top"><strong>Telephone</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="30%" align="left" valign="top"><?php echo $Tel ?></td>
                            </tr>
                            <tr>
                                <td height="31" align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top"><strong>Mobile Number</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $MobileTel ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top"><strong>Email Address</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $emailaddr ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>District</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $DistName ?></td>
                                <td rowspan="2" align="left" valign="top"><strong>Date from which you have been residing in this address</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top">
                                    <?php
                                    if($AppDateCI=="1900-01-01"){
                                        $AppDateCI = "";
                                    }
                                echo $AppDateCI

                                        ?>
                                </td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong> Division</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $DSName ?></td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;"><strong>Contact Information (Temporary)</strong></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td align="left" valign="top"><strong>Address</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" rowspan="4" align="left" valign="top"><?php echo $AddressT ?></td>
                                <td align="left" valign="top"><strong>GS Division</strong></td>
                                <td align="left" valign="top">:</td>
                                <td align="left" valign="top"><?php echo $GSDivisionT ?></td>
                            </tr>
                            <tr>
                                <td width="15%" align="left" valign="top">&nbsp;</td>
                                <td width="1%" align="left" valign="top">&nbsp;</td>
                                <td width="19%" align="left" valign="top"><strong>Telephone</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="30%" align="left" valign="top"><?php echo $TelT ?></td>
                            </tr>
                            <tr>
                                <td height="31" align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td rowspan="4" align="left" valign="top"><strong>Date from which you have been residing in this address</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top">
                                    <?php
                                    if($AppDateCIT=="1900-01-01"){
                                        $AppDateCIT = "";
                                    }
                                    echo $AppDateCIT
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>District</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $DistNameT ?></td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong> Division</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $DSNameT ?></td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;"><strong>Details of present appointment (to the school/Institution from which your salary is being paid at present)</strong></td>
                </tr>

                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="15%" align="left" valign="top"><strong>District</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $DistNameC ?></td>
                                <td width="22%" align="left" valign="top"><strong>Date of Appointment</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="27%" align="left" valign="top"><?php echo $AppDateC; ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Zone</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $InstitutionNameCZone ?></td>
                                <td align="left" valign="top"><strong>Teaching Section</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $GradeNameC ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Division</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $InstitutionNameCDivision ?></td>
                                <td align="left" valign="top"><strong>Position</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $PositionNameC ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>School/Institution Name</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $InstitutionNameC ?></td>
                                <td align="left" valign="top"><strong>Service Category</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $ServiceNameC ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Employment Basis</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $DescriptionC ?></td>
                                <td align="left" valign="top"><strong>Category as per 01/2016 circular</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $Cat2003Name ?></td>
                            </tr>
                            <!--Added by Dharshana -start-->
                            <tr>
                                <td align="left" valign="top"><strong>Reference Number</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $PFReferenceC ?></td>
                            </tr>
                            <!--Added by Dharshana -end-->
                        </table></td>
                </tr>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;"><strong>Details of the first appointment to government sector</strong></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="15%" align="left" valign="top"><strong>District</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $DistNameF ?></td>
                                <td width="22%" align="left" valign="top"><strong>Date of Appointment</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="27%" align="left" valign="top"><?php echo $AppDateF; ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Zone</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $InstitutionNameFZone ?></td>
                                <td align="left" valign="top"><strong>Teaching Section</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $GradeNameF ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Division</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $InstitutionNameFDivision ?></td>
                                <td align="left" valign="top"><strong>Position</strong></td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top"><?php echo $PositionNameF ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>School/Institution Name</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $InstitutionNameF ?></td>
                                <td align="left" valign="top"><strong>Service Category</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $ServiceNameF ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Employment Basis</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $DescriptionF ?></td>
                                <td align="left" valign="top"><strong>Category as per 01/2016 circular</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $Cat2003NameF ?></td>
                            </tr>
                            <!--Added by Dharshana -start-->
                            <tr>
                                <td align="left" valign="top"><strong>Reference Number</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $PFReferenceF ?></td>
                            </tr>
                            <!--Added by Dharshana -end-->
                        </table></td>
                </tr>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr bgcolor="#3399FF">
                    <td height="30" colspan="2" valign="middle" style="border-bottom: 1px; border-bottom-style: solid; font-size: 14px; color: #FFFFFF;">&nbsp;&nbsp;<strong>Take an Action</strong></td>
                </tr>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="15%" style="font-weight: bold">Officer Name</td>
                                <td width="1%">:</td>
                                <td width="34%"><?php echo $_SESSION["fullName"]; ?></td>
                                <td width="16%" style="font-weight: bold">Comment</td>
                                <td width="1%">:</td>
                                <td width="33%" rowspan="3"><textarea name="ApproveComment" id="ApproveComment" cols="35" rows="5"></textarea></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold">Designation</td>
                                <td>:</td>
                                <td><?php echo $loggedPositionName; ?></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold">Action</td>
                                <td>:</td>
                                <td><select class="select2a_n" id="IsApproved" name="IsApproved">
                                        <option value="Y">Approve</option>
                                        <option value="R">Reject</option>
                                    </select></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="32%">&nbsp;</td>
                                <td width="68%"><input type="hidden" name="RegID" value="<?php echo $id ?>" /><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                            </tr>
                        </table></td>
                    <td valign="top">&nbsp;</td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
<?php } ?>
    </div>

</form>
</div>
