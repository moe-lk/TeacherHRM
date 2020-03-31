<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php

$msg = "";
$tblNam = "TG_ApprovalProcessMain";
$countTotal = "SELECT * FROM $tblNam"; //$NICUser
$redirect_page = "approvalProcess-1.html";
$NICUserUpdate = $NICUser;
$NICUser = $id;

$isAvailablePerAdd = $isAvailableCurAdd = $isAvailablePmast = "";
$success = "";
include('../activityLog.php');
if (isset($_POST["FrmSubmit"])) {
    $perAddStatus = $_REQUEST['perAddStatus'];
    $curAddStatus = $_REQUEST['curAddStatus'];
    $pMastStatus = $_REQUEST['pMastStatus'];
    $Title = $_REQUEST['Title'];
    $SurnameWithInitials = $_REQUEST['SurnameWithInitials'];
    $FullName = $_REQUEST['FullName'];
    $DOB = $_REQUEST['DOB'];
    $EthnicityCode = $_REQUEST['EthnicityCode'];
    $GenderCode = $_REQUEST['GenderCode'];
    $ReligionCode = $_REQUEST['ReligionCode'];
    $emailaddr = $_REQUEST['emailaddr'];
    $MobileTel = $_REQUEST['MobileTel'];
    $LastUpdate = date('Y-m-d H:i:s');
    $RecStatus = "0";
    $msg = "";

    $sqlServiceRef = " SELECT        TeacherMast.CurServiceRef, CD_CensesNo.ZoneCode
FROM            StaffServiceHistory LEFT JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode LEFT JOIN
                         TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
WHERE        (TeacherMast.NIC = '$NICUser')";
    $stmtCAllready = $db->runMsSqlQuery($sqlServiceRef);
    $rowAllready = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC);
    $CurServiceRef = trim($rowAllready['CurServiceRef']);
    $ZoneCode = trim($rowAllready['ZoneCode']);

    $sqlCAllready = "SELECT * FROM TG_EmployeeUpdatePersInfo WHERE NIC='$NICUser' and IsApproved='N'";
    $stmtCAllready = $db->runMsSqlQuery($sqlCAllready);
    $rowAllready = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC);
    $TeacherMastIDA = trim($rowAllready['TeacherMastID']);
    $PermResiIDA = trim($rowAllready['PermResiID']);
    $CurrResIDA = trim($rowAllready['CurrResID']);

    $Address = $_REQUEST['Address'];
    $DSCode = $_REQUEST['DSCode'];
    $DISTCode = $_REQUEST['DISTCode'];
    $Tel = $_REQUEST['Tel'];
    $AppDate = $_REQUEST['AppDate'];
    $GSDivision = $_REQUEST['GSDivision'];
    $LastUpdate = date('Y-m-d H:i:s');
    //Current details
    $AddressC = $_REQUEST['AddressC'];
    $DSCodeC = $_REQUEST['DSCodeC'];
    $DISTCodeC = $_REQUEST['DISTCodeC'];
    $TelC = $_REQUEST['TelC'];
    $AppDateC = $_REQUEST['AppDateC'];
    $GSDivisionC = $_REQUEST['GSDivisionC'];

    if ($SurnameWithInitials == "") {
        $msg .= "Please enter Surname With Initials.<br>";
    }

    if ($Address == "") {
        $msg .= "Please enter Permanant Residance address.<br>";
    }
    if ($DSCode == "") {
        $msg .= "Please select Permanant Residance division.<br>";
    }
    if ($DISTCode == "") {
        $msg .= "Please select Permanant Residance district.<br>";
    }

 


    if ($msg == '') {
        if ($PermResiIDA == '') {//$perAddStatus=='Add'
            $queryMainSave = "INSERT INTO UP_StaffAddrHistory
			   (NIC,AddrType,Address,DSCode,DISTCode,Tel,AppDate,LastUpdate,UpdateBy,RecordLog,GSDivision)
		 VALUES
			   ('$NICUser','PER','$Address','$DSCode','$DISTCode','$Tel','$AppDate','$LastUpdate','$NICUserUpdate','First change','$GSDivision')";	
            $db->runMsSqlQuery($queryMainSave);

   //         $db->runMsSqlQuery($queryMainSave);

            $reqTabMobAc = "SELECT ID FROM UP_StaffAddrHistory where NIC='$NICUser' and AddrType='PER' ORDER BY ID DESC";
            $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
            $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
            $PermResiID = trim($rowMobAc['ID']);
        } else {//if($perAddStatus=='Update'){
            $queryMainUpdate = "UPDATE UP_StaffAddrHistory SET Address='$Address',DSCode='$DSCode',DISTCode='$DISTCode',Tel='$Tel',AppDate='$AppDate',LastUpdate='$LastUpdate',UpdateBy='$NICUserUpdate',RecordLog='Edit record', GSDivision='$GSDivision' WHERE ID='$PermResiIDA'"; //NIC='$NICUser' and AddrType='PER'";            
            $PermResiID = $PermResiIDA;
        }
    }

    if ($msg == '') {
        $CurrResID = 0;
        if ($CurrResIDA == '') {//$curAddStatus=='Add'
            if ($AddressC != '') {
                $queryMainSave = "INSERT INTO UP_StaffAddrHistory
			   (NIC,AddrType,Address,DSCode,DISTCode,Tel,AppDate,LastUpdate,UpdateBy,RecordLog,GSDivision)
		 VALUES
			   ('$NICUser','CUR','$AddressC','$DSCodeC','$DISTCodeC','$TelC','$AppDateC','$LastUpdate','$NICUserUpdate','First change','$GSDivisionC')";
                // $db->runMsSqlQuery($queryMainSave);	
                $db->runMsSqlQuery($queryMainSave);

                $reqTabMobAc = "SELECT ID FROM UP_StaffAddrHistory where NIC='$NICUser' and AddrType='CUR' ORDER BY ID DESC";
                $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
                $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
                $CurrResID = trim($rowMobAc['ID']);
            }
        } else {//if($curAddStatus=='Update'){
            $queryMainUpdate = "UPDATE UP_StaffAddrHistory SET Address='$AddressC',DSCode='$DSCodeC',DISTCode='$DISTCodeC',Tel='$TelC',AppDate='$AppDateC',LastUpdate='$LastUpdate',UpdateBy='$NICUserUpdate',RecordLog='Edit record',GSDivision='$GSDivisionC' WHERE ID='$CurrResIDA'"; //NIC='$NICUser' and AddrType='CUR'";

            $db->runMsSqlQuery($queryMainUpdate);

            $CurrResID = $CurrResIDA;
        }
    }



    if ($msg == '') {
        //if($pMastStatus=='Add'){
        $TeacherMastID = 0;
        if ($TeacherMastIDA == '') {
            $queryMainSave = "INSERT INTO UP_TeacherMast
			   (NIC,Title,SurnameWithInitials,FullName,DOB,EthnicityCode,PerResRef,CurResRef,CurServiceRef,GenderCode,ReligionCode,emailaddr,MobileTel,RecStatus,LastUpdate,UpdateBy,RecordLog)
		 VALUES
			   ('$NICUser','$Title','$SurnameWithInitials','$FullName','$DOB','$EthnicityCode','$PermResiID','$CurrResID','$CurServiceRef','$GenderCode','$ReligionCode','$emailaddr','$MobileTel','0','$LastUpdate','$NICUserUpdate','First change')";
            //$db->runMsSqlQuery($queryMainSave);	
            $db->runMsSqlQuery($queryMainSave);

            $reqTabMobAc = "SELECT ID FROM UP_TeacherMast where NIC='$NICUser' ORDER BY ID DESC";
            $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
            $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
            $TeacherMastID = trim($rowMobAc['ID']);

            if ($TeacherMastID == 0)
                $msg = "Error on page. Please check your internet connection and try again";
        }else {//if($pMastStatus=='Update')
            $queryMainUpdate = "UPDATE UP_TeacherMast SET Title='$Title',SurnameWithInitials='$SurnameWithInitials',FullName='$FullName',DOB='$DOB',EthnicityCode='$EthnicityCode',PerResRef='$PermResiID',CurResRef='$CurrResID',CurServiceRef='$CurServiceRef',GenderCode='$GenderCode',ReligionCode='$ReligionCode',emailaddr='$emailaddr',MobileTel='$MobileTel',LastUpdate='$LastUpdate',UpdateBy='$NICUserUpdate',RecordLog='Edit record' WHERE ID='$TeacherMastIDA'"; //NIC='$NICUser' and 

            $db->runMsSqlQuery($queryMainUpdate);
            $TeacherMastID = $TeacherMastIDA;
        }
    }

    if ($msg == '') {
        $isAvailable = $db->rowAvailable($sqlCAllready);
        if ($isAvailable == 1) {
            $queryMainUpdate = "UPDATE TG_EmployeeUpdatePersInfo SET TeacherMastID='$TeacherMastID',PermResiID='$PermResiID',CurrResID='$CurrResID',ZoneCode='$ZoneCode',dDateTime='$LastUpdate',IsApproved='N',ApproveDate='',ApprovedBy='',UpdateBy='$NICUserUpdate' WHERE NIC='$NICUser' and IsApproved='N'";
            $db->runMsSqlQuery($queryMainUpdate);
            audit_trail($NICUser, $_SESSION["NIC"], 'teacherprofile\personalInfo.php', 'Update', 'UP_StaffAddrHistory,UP_TeacherMast,TG_EmployeeUpdatePersInfo', 'Update user personal info.');
            
        } else {

            $queryRegis = "INSERT INTO TG_EmployeeUpdatePersInfo				   (NIC,TeacherMastID,PermResiID,CurrResID,dDateTime,ZoneCode,IsApproved,ApproveComment,ApproveDate,ApprovedBy,UpdateBy)
			 VALUES				   
		('$NICUser','$TeacherMastID','$PermResiID','$CurrResID','$LastUpdate','$ZoneCode','N','','','','$NICUserUpdate')";
            $db->runMsSqlQuery($queryRegis);
            
            audit_trail($NICUser, $_SESSION["NIC"], 'teacherprofile\personalInfo.php', 'Insert', 'UP_StaffAddrHistory,UP_TeacherMast,TG_EmployeeUpdatePersInfo', 'Insert user personal info.');
        }

        $success = "Your update request submitted successfully. Data will be displaying after the approvals.";
    }
}

if ($menu == 'E') {
    $sqlCAllready = "SELECT * FROM TG_EmployeeUpdatePersInfo WHERE NIC='$NICUser' and IsApproved='N'";
    $stmtCAllready = $db->runMsSqlQuery($sqlCAllready);
    $rowAllready = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC);
    $TeacherMastIDA = trim($rowAllready['TeacherMastID']);
    $PermResiIDA = trim($rowAllready['PermResiID']);
    $CurrResIDA = trim($rowAllready['CurrResID']);

    /* address */

    $perAddStatus = "Update";
    $curAddStatus = "Update";
    $pMastStatus = "Update";
    $sqlPerAdd = "SELECT    UP_StaffAddrHistory.Address, UP_StaffAddrHistory.Tel, 
							 CONVERT(varchar(20),UP_StaffAddrHistory.AppDate,121) AS AppDate, CD_DSec.DSName, CD_Districts.DistName, CD_DSec.DSCode, CD_Districts.DistCode, UP_StaffAddrHistory.GSDivision
	FROM            UP_StaffAddrHistory LEFT JOIN
							 CD_DSec ON UP_StaffAddrHistory.DSCode = CD_DSec.DSCode LEFT JOIN
							 CD_Districts ON UP_StaffAddrHistory.DISTCode = CD_Districts.DistCode
	WHERE   UP_StaffAddrHistory.ID = '$PermResiIDA'"; //     (UP_StaffAddrHistory.NIC = '$NICUser') AND (UP_StaffAddrHistory.AddrType = N'PER')";

    $isAvailablePerAdd = $db->rowAvailable($sqlPerAdd);
    //if($isAvailable==1){
    $resAB = $db->runMsSqlQuery($sqlPerAdd);
    $rowAB = sqlsrv_fetch_array($resAB, SQLSRV_FETCH_ASSOC);
    $Address = $rowAB['Address'];
    $Tel = trim($rowAB['Tel']);
    $AppDate = $rowAB['AppDate'];
    $DSName = $rowAB['DSName'];
    $DistName = $rowAB['DistName'];
    $DSCode = trim($rowAB['DSCode']);
    $DistCode = trim($rowAB['DistCode']);
    $GSDivision = $rowAB['GSDivision'];

    $sqlCurAdd = "SELECT    UP_StaffAddrHistory.Address, UP_StaffAddrHistory.Tel, 
							 CONVERT(varchar(20),UP_StaffAddrHistory.AppDate,121) AS AppDate, CD_DSec.DSName, CD_Districts.DistName, CD_DSec.DSCode, CD_Districts.DistCode, UP_StaffAddrHistory.GSDivision
	FROM            UP_StaffAddrHistory LEFT JOIN
							 CD_DSec ON UP_StaffAddrHistory.DSCode = CD_DSec.DSCode LEFT JOIN
							 CD_Districts ON UP_StaffAddrHistory.DISTCode = CD_Districts.DistCode
	WHERE  UP_StaffAddrHistory.ID ='$CurrResIDA'"; //(UP_StaffAddrHistory.NIC = '$NICUser') AND (UP_StaffAddrHistory.AddrType = N'CUR')";//538093300V

    $isAvailableCurAdd = $db->rowAvailable($sqlCurAdd);

    $resABC = $db->runMsSqlQuery($sqlCurAdd);
    $rowABC = sqlsrv_fetch_array($resABC, SQLSRV_FETCH_ASSOC);
    $AddressC = $rowABC['Address'];
    $TelC = trim($rowABC['Tel']);
    $AppDateC = $rowABC['AppDate'];
    $DSNameC = $rowABC['DSName'];
    $DistNameC = $rowABC['DistName'];
    $DSCodeC = trim($rowABC['DSCode']);
    $DistCodeC = trim($rowABC['DistCode']);
    $GSDivisionC = $rowABC['GSDivision'];

    $sqlPmast = "SELECT        UP_TeacherMast.ID, UP_TeacherMast.NIC, UP_TeacherMast.SurnameWithInitials, UP_TeacherMast.FullName, UP_TeacherMast.Title, UP_TeacherMast.MobileTel, CONVERT(varchar(20), 
                         UP_TeacherMast.DOB, 121) AS DOB, CD_nEthnicity.EthnicityName, CD_Religion.ReligionName, CD_Gender.[Gender Name], UP_TeacherMast.emailaddr, CD_Title.TitleName,
                          UP_TeacherMast.GenderCode, UP_TeacherMast.EthnicityCode, UP_TeacherMast.ReligionCode
FROM            UP_TeacherMast LEFT JOIN
                         CD_Gender ON UP_TeacherMast.GenderCode = CD_Gender.GenderCode LEFT JOIN
                         CD_nEthnicity ON UP_TeacherMast.EthnicityCode = CD_nEthnicity.Code LEFT JOIN
                         CD_Religion ON UP_TeacherMast.ReligionCode = CD_Religion.Code LEFT JOIN
                         CD_Title ON UP_TeacherMast.Title = CD_Title.TitleCode
WHERE  UP_TeacherMast.ID='$TeacherMastIDA'"; //     (UP_TeacherMast.NIC = N'$NICUser') AND (UP_TeacherMast.RecStatus = N'0')";//538093300V

    $isAvailablePmast = $db->rowAvailable($sqlPmast);
    $resPm = $db->runMsSqlQuery($sqlPmast);
    $rowPm = sqlsrv_fetch_array($resPm, SQLSRV_FETCH_ASSOC);
    $SurnameWithInitials = $rowPm['SurnameWithInitials'];
    $FullName = $rowPm['FullName'];
    $TitleCode = trim($rowPm['Title']);
    $MobileTel = trim($rowPm['MobileTel']);
    $DOB = $rowPm['DOB'];
    $EthnicityName = $rowPm['EthnicityName'];
    $ReligionName = $rowPm['ReligionName'];
    $GenderName = $rowPm['Gender Name'];
    $emailaddr = $rowPm['emailaddr'];
    $TitleName = $rowPm['TitleName'];

    $GenderCode = trim($rowPm['GenderCode']);
    $EthnicityCode = trim($rowPm['EthnicityCode']);
    $ReligionCode = trim($rowPm['ReligionCode']);
}

if ($isAvailablePmast != 1) {
    $pMastStatus = "Add";
    $sqlPers = "SELECT        TeacherMast.ID, TeacherMast.NIC, TeacherMast.SurnameWithInitials, TeacherMast.FullName, TeacherMast.Title, TeacherMast.MobileTel, CONVERT(varchar(20), 
							 TeacherMast.DOB, 121) AS DOB, CD_nEthnicity.EthnicityName, CD_Religion.ReligionName, CD_Gender.[Gender Name], TeacherMast.emailaddr, CD_Title.TitleName,
							  TeacherMast.GenderCode, TeacherMast.EthnicityCode, TeacherMast.ReligionCode
	FROM            TeacherMast LEFT JOIN
							 CD_Gender ON TeacherMast.GenderCode = CD_Gender.GenderCode LEFT JOIN
							 CD_nEthnicity ON TeacherMast.EthnicityCode = CD_nEthnicity.Code LEFT JOIN
							 CD_Religion ON TeacherMast.ReligionCode = CD_Religion.Code LEFT JOIN
							 CD_Title ON TeacherMast.Title = CD_Title.TitleCode
	WHERE        (TeacherMast.NIC = N'$NICUser')";

    $resA = $db->runMsSqlQuery($sqlPers);
    $rowA = sqlsrv_fetch_array($resA, SQLSRV_FETCH_ASSOC);
    $SurnameWithInitials = $rowA['SurnameWithInitials'];
    $FullName = $rowA['FullName'];
    $TitleCode = trim($rowA['Title']);
    $MobileTel = $rowA['MobileTel'];
    $DOB = $rowA['DOB'];
    $EthnicityName = $rowA['EthnicityName'];
    $ReligionName = $rowA['ReligionName'];
    $GenderName = $rowA['Gender Name'];
    $emailaddr = $rowA['emailaddr'];
    $TitleName = $rowA['TitleName'];

    $GenderCode = trim($rowA['GenderCode']);
    $EthnicityCode = trim($rowA['EthnicityCode']);
    $ReligionCode = trim($rowA['ReligionCode']);
}
/* address */
if ($isAvailablePerAdd != 1) {
    $perAddStatus = "Add";
    $sqlPerAdd = "SELECT    StaffAddrHistory.Address, StaffAddrHistory.Tel, 
							 CONVERT(varchar(20),StaffAddrHistory.AppDate,121) AS AppDate, CD_DSec.DSName, CD_Districts.DistName, CD_DSec.DSCode, CD_Districts.DistCode, StaffAddrHistory.GSDivision
	FROM            StaffAddrHistory LEFT JOIN
							 CD_DSec ON StaffAddrHistory.DSCode = CD_DSec.DSCode LEFT JOIN
							 CD_Districts ON StaffAddrHistory.DISTCode = CD_Districts.DistCode
	WHERE        (StaffAddrHistory.NIC = '$NICUser') AND (StaffAddrHistory.AddrType = N'PER')";

    $resAB = $db->runMsSqlQuery($sqlPerAdd);
    $rowAB = sqlsrv_fetch_array($resAB, SQLSRV_FETCH_ASSOC);
    $Address = $rowAB['Address'];
    $Tel = trim($rowAB['Tel']);
    $AppDate = $rowAB['AppDate'];
    $DSName = $rowAB['DSName'];
    $DistName = $rowAB['DistName'];
    $DSCode = trim($rowAB['DSCode']);
    $DistCode = trim($rowAB['DistCode']);
    $GSDivision = trim($rowAB['GSDivision']);
}
if ($isAvailableCurAdd != 1) {
    $curAddStatus = "Add";
     $sqlCurAdd = "SELECT    StaffAddrHistory.Address, StaffAddrHistory.Tel, 
							 CONVERT(varchar(20),StaffAddrHistory.AppDate,121) AS AppDate, CD_DSec.DSName, CD_Districts.DistName, CD_DSec.DSCode, CD_Districts.DistCode, StaffAddrHistory.GSDivision
	FROM            StaffAddrHistory LEFT JOIN
							 CD_DSec ON StaffAddrHistory.DSCode = CD_DSec.DSCode LEFT JOIN
							 CD_Districts ON StaffAddrHistory.DISTCode = CD_Districts.DistCode
	WHERE        (StaffAddrHistory.NIC = '$NICUser') AND (StaffAddrHistory.AddrType = N'CUR')"; //538093300V

    $resABC = $db->runMsSqlQuery($sqlCurAdd);
    $rowABC = sqlsrv_fetch_array($resABC, SQLSRV_FETCH_ASSOC);
    $AddressC = $rowABC['Address'];
    $TelC = trim($rowABC['Tel']);
    $AppDateC = $rowABC['AppDate'];
    $DSNameC = $rowABC['DSName'];
    $DistNameC = $rowABC['DistName'];
    $DSCodeC = trim($rowABC['DSCode']);
    $DistCodeC = trim($rowABC['DistCode']);
    $GSDivisionC = trim($rowABC['GSDivision']);
}

//$TotaRows = $db->rowCount($countTotal);
?>
<?php if ($menu == '') { ?>
    <div class="main_content_inner_block">
        <div class="mcib_middle1">
            <table width="945" cellpadding="0" cellspacing="0">
                <tr>
                    <td valign="top"><span style="color:#090; font-weight:bold;">*If your personal data record is inaccurate, you can submit an update request</span></td>
                    <td align="right" valign="top"><a href="personalInfo-1-E-<?php echo $id ?>.html"><img src="../cms/images/udate-request.png" width="170" height="26" /></a></td>
                </tr>
                <tr>
                    <td width="50%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="30%" align="left" valign="top"><strong>NIC</strong></td>
                                <td width="3%" align="left" valign="top"><strong>:</strong></td>
                                <td width="67%" align="left" valign="top"><?php echo $NICUser ?></td>
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
                                <td align="left" valign="top"><?php echo $DOB ?></td>
                            </tr>

                        </table>
                    </td>
                    <td width="50%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
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
                            <tr>
                                <td align="left" valign="top"><strong>Email Address</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $emailaddr ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Mobile Number</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $MobileTel ?></td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;"><strong>Permanent Residence Details</strong></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="15%" align="left" valign="top"><strong> Address</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" rowspan="5" align="left" valign="top"><?php echo $Address ?></td>
                                <td width="19%" align="left" valign="top"><strong>District</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="30%" align="left" valign="top"><?php echo $DistName ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top"><strong>DS Division</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $DSName ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top"><strong>GS Division</strong></td>
                                <td align="left" valign="top">:</td>
                                <td align="left" valign="top"><?php echo $GSDivision ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top"><strong>Telephone</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $Tel ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top"><strong>Effective Date</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $AppDate ?></td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;"><strong>Temporary Residence Details</strong></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="15%" align="left" valign="top"><strong> Address</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" rowspan="5" align="left" valign="top"><?php echo $AddressC ?></td>
                                <td width="19%" align="left" valign="top"><strong>District</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="30%" align="left" valign="top"><?php echo $DistNameC ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top"><strong>DS Division</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $DSNameC ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top"><strong>GS Division</strong></td>
                                <td align="left" valign="top">:</td>
                                <td align="left" valign="top"><?php echo $GSDivisionC ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top"><strong>Telephone</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $TelC ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top"><strong>Effective Date</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $AppDateC ?></td>
                            </tr>
                        </table></td>
                </tr>

                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px; font-weight:bold;">Pending Request(s)</td>
                </tr>
                <tr>
                    <td><u>Personal Details</u></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                </tr>
    <?php
    $x = 1;

    $sqlCAllready = "SELECT * FROM TG_EmployeeUpdatePersInfo WHERE NIC='$NICUser' and IsApproved='N'";
    $stmtCAllready = $db->runMsSqlQuery($sqlCAllready);
    $rowAllready = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC);
    $TeacherMastIDA = trim($rowAllready['TeacherMastID']);
    $PermResiIDA = trim($rowAllready['PermResiID']);
    $CurrResIDA = trim($rowAllready['CurrResID']);

    $sqlPmast = "SELECT        UP_TeacherMast.ID, UP_TeacherMast.NIC, UP_TeacherMast.SurnameWithInitials, UP_TeacherMast.FullName, UP_TeacherMast.Title, UP_TeacherMast.MobileTel, CONVERT(varchar(20), 
                         UP_TeacherMast.DOB, 121) AS DOB, CD_nEthnicity.EthnicityName, CD_Religion.ReligionName, CD_Gender.[Gender Name], UP_TeacherMast.emailaddr, CD_Title.TitleName,
                          UP_TeacherMast.GenderCode, UP_TeacherMast.EthnicityCode, UP_TeacherMast.ReligionCode
FROM            UP_TeacherMast LEFT JOIN
                         CD_Gender ON UP_TeacherMast.GenderCode = CD_Gender.GenderCode LEFT JOIN
                         CD_nEthnicity ON UP_TeacherMast.EthnicityCode = CD_nEthnicity.Code LEFT JOIN
                         CD_Religion ON UP_TeacherMast.ReligionCode = CD_Religion.Code LEFT JOIN
                         CD_Title ON UP_TeacherMast.Title = CD_Title.TitleCode
WHERE  (UP_TeacherMast.ID='$TeacherMastIDA')";
//WHERE  (UP_TeacherMast.NIC='$NICUser') and (UP_TeacherMast.IsApproved IS NULL)";
//     (UP_TeacherMast.NIC = N'$NICUser') AND (UP_TeacherMast.RecStatus = N'0')";//538093300V

    $isAvailablePmast = $db->rowAvailable($sqlPmast);
    //if($isAvailablePmast==1){
    $resPm = $db->runMsSqlQuery($sqlPmast);
    while ($rowPm = sqlsrv_fetch_array($resPm, SQLSRV_FETCH_ASSOC)) {
        //$rowPm = sqlsrv_fetch_array($resPm, SQLSRV_FETCH_ASSOC);
        $SurnameWithInitials = $rowPm['SurnameWithInitials'];
        $FullName = $rowPm['FullName'];
        $TitleCode = trim($rowPm['Title']);
        $MobileTel = trim($rowPm['MobileTel']);
        $DOB = $rowPm['DOB'];
        $EthnicityName = $rowPm['EthnicityName'];
        $ReligionName = $rowPm['ReligionName'];
        $GenderName = $rowPm['Gender Name'];
        $emailaddr = $rowPm['emailaddr'];
        $TitleName = $rowPm['TitleName'];

        $GenderCode = trim($rowPm['GenderCode']);
        $EthnicityCode = trim($rowPm['EthnicityCode']);
        $ReligionCode = trim($rowPm['ReligionCode']);
        ?>
                    <tr>
                        <td><table width="100%" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="9%" rowspan="5" align="left" valign="top"><strong><?php echo $x++ ?>)</strong></td>
                                    <td width="28%" align="left" valign="top"><strong>NIC</strong></td>
                                    <td width="3%" align="left" valign="top"><strong>:</strong></td>
                                    <td width="60%" align="left" valign="top"><?php echo $NICUser ?></td>
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
                                    <td align="left" valign="top"><?php echo $DOB ?></td>
                                </tr>

                            </table></td>
                        <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="30%" align="left" valign="top"><strong>Ethinicity</strong></td>
                                    <td width="3%" align="left" valign="top"><strong>:</strong></td>
                                    <td width="49%" align="left" valign="top"><?php echo $EthnicityName ?></td>
                                    <td width="18%" rowspan="5" align="center" valign="middle"><a href="personalInfo-1-E-<?php echo $NICUser ?>-<?php echo $IDchild ?>.html"><strong><img src="images/edit.png" alt="delete" width="32" height="32" title="edit"/></strong></a></td>
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
                                <tr>
                                    <td align="left" valign="top"><strong>Email Address</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top"><?php echo $emailaddr ?></td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top"><strong>Mobile Number</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top"><?php echo $MobileTel ?></td>
                                </tr>
                            </table></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
    <?php }//} ?>
    <?php if ($isAvailablePmast != 1) { ?>
                    <tr>
                        <td>No record(s) found.</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>

    <?php } ?>
                <tr>
                    <td><u>Permanent Residence Details</u></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
    <?php
    $sqlPerAdd = "SELECT    UP_StaffAddrHistory.Address, UP_StaffAddrHistory.Tel, 
							 CONVERT(varchar(20),UP_StaffAddrHistory.AppDate,121) AS AppDate, CD_DSec.DSName, CD_Districts.DistName, CD_DSec.DSCode, CD_Districts.DistCode,UP_StaffAddrHistory.GSDivision
	FROM            UP_StaffAddrHistory LEFT JOIN
							 CD_DSec ON UP_StaffAddrHistory.DSCode = CD_DSec.DSCode LEFT JOIN
							 CD_Districts ON UP_StaffAddrHistory.DISTCode = CD_Districts.DistCode
	WHERE        (UP_StaffAddrHistory.ID = '$PermResiIDA') AND (UP_StaffAddrHistory.AddrType = N'PER')";
    //WHERE        (UP_StaffAddrHistory.NIC = '$NICUser') AND (UP_StaffAddrHistory.AddrType = N'PER') AND (UP_StaffAddrHistory.IsApproved IS NULL)";
    //$PermResiIDA,$CurrResIDA

    $isAvailablePresi = $db->rowAvailable($sqlPerAdd);
    $resAB = $db->runMsSqlQuery($sqlPerAdd);
    $d = 1;
    while ($rowAB = sqlsrv_fetch_array($resAB, SQLSRV_FETCH_ASSOC)) {
        $Address = $rowAB['Address'];
        $Tel = trim($rowAB['Tel']);
        $AppDate = $rowAB['AppDate'];
        $DSName = $rowAB['DSName'];
        $DistName = $rowAB['DistName'];
        $DSCode = trim($rowAB['DSCode']);
        $DistCode = trim($rowAB['DistCode']);
        $GSDivision = trim($rowAB['GSDivision']);
        ?>
                    <tr>
                        <td colspan="2"><table width="100%" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="4%" align="left" valign="top"><strong><?php echo $d++ ?>)</strong></td>
                                    <td width="14%" align="left" valign="top"><strong> Address</strong></td>
                                    <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                    <td width="36%" rowspan="5" align="left" valign="top"><?php echo $Address ?></td>
                                    <td width="16%" align="left" valign="top"><strong>District</strong></td>
                                    <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                    <td width="28%" align="left" valign="top"><?php echo $DistName ?></td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top"><strong>DS Division</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top"><?php echo $DSName ?></td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top"><strong>GS Division</strong></td>
                                    <td align="left" valign="top">:</td>
                                    <td align="left" valign="top"><?php echo $GSDivision ?></td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top"><strong>Telephone</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top"><?php echo $Tel ?></td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top"><strong>Effective Date</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top"><?php echo $AppDate ?></td>
                                </tr>
                            </table></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
    <?php } ?>
    <?php if ($isAvailablePresi != 1) { ?>
                    <tr>
                        <td>No record(s) found.</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>

    <?php } ?>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td><u>Temporary Residence Details</u></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
    <?php
    $sqlPerAdd = "SELECT    UP_StaffAddrHistory.Address, UP_StaffAddrHistory.Tel, 
							 CONVERT(varchar(20),UP_StaffAddrHistory.AppDate,121) AS AppDate, CD_DSec.DSName, CD_Districts.DistName, CD_DSec.DSCode, CD_Districts.DistCode,UP_StaffAddrHistory.GSDivision
	FROM            UP_StaffAddrHistory LEFT JOIN
							 CD_DSec ON UP_StaffAddrHistory.DSCode = CD_DSec.DSCode LEFT JOIN
							 CD_Districts ON UP_StaffAddrHistory.DISTCode = CD_Districts.DistCode
	WHERE        (UP_StaffAddrHistory.ID = '$CurrResIDA') AND (UP_StaffAddrHistory.AddrType = N'CUR')";
    //WHERE        (UP_StaffAddrHistory.NIC = '$NICUser') AND (UP_StaffAddrHistory.AddrType = N'CUR') AND (UP_StaffAddrHistory.IsApproved IS NULL)";
    //$PermResiIDA,$CurrResIDA
    $isAvailablePresi = $db->rowAvailable($sqlPerAdd);
    $resAB = $db->runMsSqlQuery($sqlPerAdd);
    $d = 1;
    while ($rowAB = sqlsrv_fetch_array($resAB, SQLSRV_FETCH_ASSOC)) {
        $Address = $rowAB['Address'];
        $Tel = trim($rowAB['Tel']);
        $AppDate = $rowAB['AppDate'];
        $DSName = $rowAB['DSName'];
        $DistName = $rowAB['DistName'];
        $DSCode = trim($rowAB['DSCode']);
        $DistCode = trim($rowAB['DistCode']);
        $GSDivision = trim($rowAB['GSDivision']);
        ?>
                    <tr>
                        <td colspan="2"><table width="100%" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="4%" align="left" valign="top"><strong><?php echo $d++ ?>)</strong></td>
                                    <td width="14%" align="left" valign="top"><strong> Address</strong></td>
                                    <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                    <td width="36%" rowspan="5" align="left" valign="top"><?php echo $Address ?></td>
                                    <td width="16%" align="left" valign="top"><strong>District</strong></td>
                                    <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                    <td width="28%" align="left" valign="top"><?php echo $DistName ?></td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top"><strong>DS Division</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top"><?php echo $DSName ?></td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top"><strong>GS Division</strong></td>
                                    <td align="left" valign="top">:</td>
                                    <td align="left" valign="top"><?php echo $GSDivision ?></td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top"><strong>Telephone</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top"><?php echo $Tel ?></td>
                                </tr>
                                <tr>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top">&nbsp;</td>
                                    <td align="left" valign="top"><strong>Effective Date</strong></td>
                                    <td align="left" valign="top"><strong>:</strong></td>
                                    <td align="left" valign="top"><?php echo $AppDate ?></td>
                                </tr>
                            </table></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
    <?php } ?>
    <?php if ($isAvailablePresi != 1) { ?>
                    <tr>
                        <td>No record(s) found.</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>

    <?php } ?>
            </table>
        </div>
    </div>
<?php } ?>
<?php if ($menu == 'E') { ?>
    <div class="main_content_inner_block">
        <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
                <?php if ($msg != '' || $success != '') {//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){   ?>   
                <div class="mcib_middle1">
                    <div class="mcib_middle_full">
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
                        <?php if ($success == '') { ?>
                    <table width="945" cellpadding="0" cellspacing="0">
                        <tr>
                            <td valign="top"><span style="color:#090; font-weight:bold;">*If your personal data record is inaccurate, you can submit an update request</span></td>
                            <td align="right" valign="top"><a href="personalInfo-1--<?php echo $id ?>.html"><img src="../cms/images/current-details.png" width="138" height="26" /></a></td>
                        </tr>
                        <tr>
                            <td width="50%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                    <tr>
                                        <td width="30%" align="left" valign="top"><strong>NIC</strong></td>
                                        <td width="3%" align="left" valign="top"><strong>:</strong></td>
                                        <td width="67%" align="left" valign="top"><?php echo $NICUser ?>

                                            <input type="hidden" name="perAddStatus" value="<?php echo $perAddStatus ?>" />
                                            <input type="hidden" name="curAddStatus" value="<?php echo $curAddStatus ?>" />
                                            <input type="hidden" name="pMastStatus" value="<?php echo $pMastStatus ?>" /></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"><strong>Title</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><select class="select2a_n" id="Title" name="Title">
                                                <!--<option value="">School Name</option>-->
        <?php
        echo $sql = "SELECT TitleCode,TitleName FROM CD_Title order by TitleName asc";
        $stmt = $db->runMsSqlQuery($sql);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $TitleCodedb = trim($row['TitleCode']);
            $TitleName = $row['TitleName'];
            $seltebr = "";
            if ($TitleCodedb == $TitleCode) {
                $seltebr = "selected";
            }
            echo "<option value=\"$TitleCodedb\" $seltebr>$TitleName</option>";
        }
        ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"><strong>Surname with Initials</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><input name="SurnameWithInitials" type="text" class="input2_n" id="SurnameWithInitials" value="<?php echo $SurnameWithInitials ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"><strong>Full Name</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><input name="FullName" type="text" class="input2_n" id="FullName" value="<?php echo $FullName ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"><strong>Date of Birth</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><table width="100%" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td width="13%"><input name="DOB" type="text" class="input3new" id="DOB" value="<?php echo $DOB; ?>" size="10" style="height:20px; line-height:20px;" readonly/>
                                                    </td>
                                                    <td width="87%">
                                                        <input name="f_trigger_1" type="image" id="f_trigger_1" src="../cms/images/calender_icon.gif" align="top" width="16" height="16"  />
                                                        <script type="text/javascript">
                                                            //2005-10-03 11:46:00 
                                                            Calendar.setup({
                                                                inputField: "DOB", // id of the input field
                                                                ifFormat: "%Y-%m-%d", // format of the input field
                                                                showsTime: false, // will display a time selector
                                                                button: "f_trigger_1", // trigger for the calendar (button ID)
                                                                singleClick: true, // double-click mode
                                                                step: 1                // show all years in drop-down boxes (instead of every other year as default)
                                                            });
                                                        </script>
                                                    </td>
                                                </tr>
                                            </table></td>
                                    </tr>

                                </table>
                            </td>
                            <td width="50%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                    <tr>
                                        <td width="38%" align="left" valign="top"><strong>Ethnicity</strong></td>
                                        <td width="3%" align="left" valign="top"><strong>:</strong></td>
                                        <td width="59%" align="left" valign="top"><?php //echo $EthnicityName  ?>
                                            <select class="select2a_n" id="EthnicityCode" name="EthnicityCode">
                                                <!--<option value="">School Name</option>-->
        <?php
        $sql = "SELECT Code,EthnicityName FROM CD_nEthnicity order by EthnicityName asc";
        $stmt = $db->runMsSqlQuery($sql);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $Coded = trim($row['Code']);
            $EthnicityNamed = $row['EthnicityName'];
            $seltebr = "";
            if ($Coded == $EthnicityCode) {
                $seltebr = "selected";
            }
            echo "<option value=\"$Coded\" $seltebr>$EthnicityNamed</option>";
        }
        ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"><strong>Gender</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><?php //echo $GenderName ?>
                                            <select class="select2a_n" id="GenderCode" name="GenderCode">
                                                <!--<option value="">School Name</option>-->
        <?php
        $sql = "SELECT [GenderCode],[Gender Name] FROM CD_Gender order by GenderCode asc";
        $stmt = $db->runMsSqlQuery($sql);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $GenderCoded = trim($row['GenderCode']);
            $GenderName = $row['Gender Name'];
            $seltebr = "";
            if ($GenderCoded == $GenderCode) {
                $seltebr = "selected";
            }
            echo "<option value=\"$GenderCoded\" $seltebr>$GenderName</option>";
        }
        ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"><strong>Religion</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><?php //echo $ReligionName ?>
                                            <select class="select2a_n" id="ReligionCode" name="ReligionCode">
                                                <!--<option value="">School Name</option>-->
        <?php
        $sql = "SELECT Code,ReligionName FROM CD_Religion order by ReligionName asc";
        $stmt = $db->runMsSqlQuery($sql);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $Coded = trim($row['Code']);
            $ReligionNamed = $row['ReligionName'];
            $seltebr = "";
            if ($Coded == $ReligionCode) {
                $seltebr = "selected";
            }
            echo "<option value=\"$Coded\" $seltebr>$ReligionNamed</option>";
        }
        ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"><strong>Email Address</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><input name="emailaddr" type="text" class="input2_n" id="emailaddr" value="<?php echo $emailaddr ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"><strong>Mobile Number</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><input name="MobileTel" type="text" class="input2_n" id="MobileTel" value="<?php echo $MobileTel ?>"/></td>
                                    </tr>
                                </table></td>
                        </tr>
                        <tr>
                            <td valign="top">&nbsp;</td>
                            <td valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;"><strong>Permanent Residence Details</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                    <tr>
                                        <td width="15%" align="left" valign="top"><strong> Address</strong></td>
                                        <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                        <td width="34%" rowspan="5" align="left" valign="top">
                                            <textarea name="Address" cols="45" rows="4" class="textarea1auto" id="Address"><?php echo $Address ?></textarea></td>
                                        <td width="19%" align="left" valign="top"><strong>District</strong></td>
                                        <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                        <td width="30%" align="left" valign="top"><?php //echo $DistCode  ?><select class="select2a_n" id="DISTCode" name="DISTCode" onchange="Javascript:show_division('divisionlst', this.options[this.selectedIndex].value, '');">
                                                <!--<option value="">School Name</option>-->
        <?php
        $sql = "SELECT DistCode,DistName FROM CD_Districts order by DistName asc";
        $stmt = $db->runMsSqlQuery($sql);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $DistCoded = trim($row['DistCode']);
            $DistNamed = $row['DistName'];
            $seltebr = "";
            if ($DistCoded == $DistCode) {
                $seltebr = "selected";
            }
            echo "<option value=\"$DistCoded\" $seltebr>$DistNamed</option>";
        }
        ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top"><strong>DS Division</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><?php //echo $DSName  ?><div id="txt_division"><select class="select2a_n" id="DSCode" name="DSCode">
                                                    <!--<option value="">School Name</option>-->
        <?php
        $sql = "SELECT DSCode,DSName FROM CD_DSec where DistName='$DistCode' order by DSName asc";
        $stmt = $db->runMsSqlQuery($sql);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $DSCoded = trim($row['DSCode']);
            $DSNamed = $row['DSName'];
            $seltebr = "";
            if ($DSCoded == $DSCode) {
                $seltebr = "selected";
            }
            echo "<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
        }
        ?>
                                                </select></div></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top"><strong>GS Division</strong></td>
                                        <td align="left" valign="top">:</td>
                                        <td align="left" valign="top"><input name="GSDivision" type="text" class="input2_n" id="GSDivision" value="<?php echo $GSDivision ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top"><strong>Telephone</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><input name="Tel" type="text" class="input2_n" id="Tel" value="<?php echo $Tel ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top"><strong>Effective Date</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><table width="100%" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td width="13%"><input name="AppDate" type="text" class="input3new" id="AppDate" value="<?php echo $AppDate; ?>" size="10" style="height:20px; line-height:20px;" readonly/>
                                                    </td>
                                                    <td width="87%">
                                                        <input name="f_trigger_3" type="image" id="f_trigger_3" src="../cms/images/calender_icon.gif" align="top" width="16" height="16"  />
                                                        <script type="text/javascript">
                                                            //2005-10-03 11:46:00 
                                                            Calendar.setup({
                                                                inputField: "AppDate", // id of the input field
                                                                ifFormat: "%Y-%m-%d", // format of the input field
                                                                showsTime: false, // will display a time selector
                                                                button: "f_trigger_3", // trigger for the calendar (button ID)
                                                                singleClick: true, // double-click mode
                                                                step: 1                // show all years in drop-down boxes (instead of every other year as default)
                                                            });
                                                        </script>
                                                    </td>
                                                </tr>
                                            </table></td>
                                    </tr>
                                </table></td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;"><strong>Temporary Residence Details</strong></td>
                        </tr>
                       <!-- <tr>
                          <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                              <td width="5%"><input name="checkbox" type="checkbox" class="input1" id="checkbox" /></td>
                              <td width="95%" align="left" valign="middle">Same as permanant details</td>
                            </tr>
                          </table></td>
                          <td valign="top">&nbsp;</td>
                        </tr> -->
                        <tr>
                            <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                    <tr>
                                        <td width="15%" align="left" valign="top"><strong> Address</strong></td>
                                        <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                        <td width="34%" rowspan="5" align="left" valign="top">
                                            <textarea name="AddressC" cols="45" rows="4" class="textarea1auto" id="AddressC"><?php echo $AddressC ?></textarea></td>
                                        <td width="19%" align="left" valign="top"><strong>District</strong></td>
                                        <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                        <td width="30%" align="left" valign="top"><?php //echo $DistNameC  ?><select class="select2a_n" id="DISTCodeC" name="DISTCodeC" onchange="Javascript:show_divisionC('divisionlstCurrent', this.options[this.selectedIndex].value, '');">
                                                <!--<option value="">School Name</option>-->
        <?php
        $sql = "SELECT DistCode,DistName FROM CD_Districts order by DistName asc";
        $stmt = $db->runMsSqlQuery($sql);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $DistCoded = trim($row['DistCode']);
            $DistNamed = $row['DistName'];
            $seltebr = "";
            if ($DistCoded == $DistCodeC) {
                $seltebr = "selected";
            }
            echo "<option value=\"$DistCoded\" $seltebr>$DistNamed</option>";
        }
        ?>
                                            </select></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top"><strong>DS Division</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><?php //echo $DSCodeC;//echo $DSNameC  ?><div id="txt_divisionC"><select class="select2a_n" id="DSCodeC" name="DSCodeC">
                                                    <!--<option value="">School Name</option>-->
        <?php
        $sql = "SELECT DSCode,DSName FROM CD_DSec where DistName='$DistCodeC' order by DSName asc";
        $stmt = $db->runMsSqlQuery($sql);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $DSCoded = trim($row['DSCode']);
            $DSNamed = $row['DSName'];
            $seltebr = "";
            if ($DSCoded == $DSCodeC) {
                $seltebr = "selected";
            }
            echo "<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
        }
        ?>
                                                </select></div></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top"><strong>GS Division</strong></td>
                                        <td align="left" valign="top">:</td>
                                        <td align="left" valign="top"><input name="GSDivisionC" type="text" class="input2_n" id="GSDivisionC" value="<?php echo $GSDivisionC ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top"><strong>Telephone</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><input name="TelC" type="text" class="input2_n" id="TelC" value="<?php echo $TelC ?>"/></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top">&nbsp;</td>
                                        <td align="left" valign="top"><strong>Effective Date</strong></td>
                                        <td align="left" valign="top"><strong>:</strong></td>
                                        <td align="left" valign="top"><table width="100%" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td width="13%"><input name="AppDateC" type="text" class="input3new" id="AppDateC" value="<?php echo $AppDateC; ?>" size="10" style="height:20px; line-height:20px;" readonly/>
                                                    </td>
                                                    <td width="87%">
                                                        <input name="f_trigger_2" type="image" id="f_trigger_2" src="../cms/images/calender_icon.gif" align="top" width="16" height="16"  />
                                                        <script type="text/javascript">
                                                            //2005-10-03 11:46:00 
                                                            Calendar.setup({
                                                                inputField: "AppDateC", // id of the input field
                                                                ifFormat: "%Y-%m-%d", // format of the input field
                                                                showsTime: false, // will display a time selector
                                                                button: "f_trigger_2", // trigger for the calendar (button ID)
                                                                singleClick: true, // double-click mode
                                                                step: 1                // show all years in drop-down boxes (instead of every other year as default)
                                                            });
                                                        </script>
                                                    </td>
                                                </tr>
                                            </table></td>
                                    </tr>
                                </table></td>
                        </tr>
                        <tr>
                            <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                    <tr>
                                        <td width="32%">&nbsp;</td>
                                        <td width="68%"><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
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
            <?php
            }?>