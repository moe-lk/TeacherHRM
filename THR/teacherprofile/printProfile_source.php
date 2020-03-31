<?php 
$tpe="PDF";
if($tpe=='PDF') {
	//$month=$_REQUEST['rpt_month'];
	require_once("../PDF/dompdf_config.inc.php");	
	$file="printProfile.php";
	$fp = fopen($file, "r");	
	while ($line = fread($fp, 8192)) {	
		$html_content.= $line;		
	} 
	$html_content;
	fclose($fp);	
	$dompdf = new DOMPDF();
	$dompdf->load_html($html_content);
	$dompdf->set_paper("a4", "landscape");
	$dompdf->render();
	$flnme=$main_head."pdf";
	$dompdf->stream($flnme);
	
}
?>
<!DOCTYPE html>
<html>
    <head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Teacher Profile - Print</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
</head>

<body <?php if($tpe=='PRINT'){?>onload="javascript:print();"<?php }?>><?php 
//if($tpePage=='Print'){
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();

$NICUser="665352056V";
//$NICUser=$_GET['userN'];
//Personal information start

$sqlPers="SELECT        TeacherMast.ID, TeacherMast.NIC, TeacherMast.SurnameWithInitials, TeacherMast.FullName, TeacherMast.Title, TeacherMast.MobileTel, CONVERT(varchar(20), 
							 TeacherMast.DOB, 121) AS DOB, CD_nEthnicity.EthnicityName, CD_Religion.ReligionName, CD_Gender.[Gender Name], TeacherMast.emailaddr, CD_Title.TitleName,
							  TeacherMast.GenderCode, TeacherMast.EthnicityCode, TeacherMast.ReligionCode
	FROM            TeacherMast INNER JOIN
							 CD_Gender ON TeacherMast.GenderCode = CD_Gender.GenderCode INNER JOIN
							 CD_nEthnicity ON TeacherMast.EthnicityCode = CD_nEthnicity.Code INNER JOIN
							 CD_Religion ON TeacherMast.ReligionCode = CD_Religion.Code INNER JOIN
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
	
	$sqlPerAdd="SELECT    StaffAddrHistory.Address, StaffAddrHistory.Tel, 
							 CONVERT(varchar(20),StaffAddrHistory.AppDate,121) AS AppDate, CD_DSec.DSName, CD_Districts.DistName, CD_DSec.DSCode, CD_Districts.DistCode, StaffAddrHistory.GSDivision
	FROM            StaffAddrHistory INNER JOIN
							 CD_DSec ON StaffAddrHistory.DSCode = CD_DSec.DSCode INNER JOIN
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
	
	$sqlCurAdd="SELECT    StaffAddrHistory.Address, StaffAddrHistory.Tel, 
							 CONVERT(varchar(20),StaffAddrHistory.AppDate,121) AS AppDate, CD_DSec.DSName, CD_Districts.DistName, CD_DSec.DSCode, CD_Districts.DistCode, StaffAddrHistory.GSDivision
	FROM            StaffAddrHistory INNER JOIN
							 CD_DSec ON StaffAddrHistory.DSCode = CD_DSec.DSCode INNER JOIN
							 CD_Districts ON StaffAddrHistory.DISTCode = CD_Districts.DistCode
	WHERE        (StaffAddrHistory.NIC = '$NICUser') AND (StaffAddrHistory.AddrType = N'CUR')";//538093300V
	
	$resABC = $db->runMsSqlQuery($sqlCurAdd);
	$rowABC = sqlsrv_fetch_array($resABC, SQLSRV_FETCH_ASSOC);
	$AddressC = $rowABC['Address'];
	$TelC = trim($rowABC['Tel']);
	$AppDateC = $rowABC['AppDate'];
	$DSNameC = $rowABC['DSName'];
	$DistNameC = $rowABC['DistName'];
	$DSCodeC = trim($rowABC['DSCode']);
	$DistCodeC = trim($rowABC['DistCode']);
	$GSDivisionT = trim($rowAB['GSDivision']);

//Personal information end

//Family info start

$sqlPers="SELECT        TeacherMast.NIC, TeacherMast.CivilStatusCode, TeacherMast.SpouseName, TeacherMast.SpouseNIC, 
                         TeacherMast.SpouseOccupationCode, CONVERT(varchar(20), 
                         TeacherMast.SpouseDOB, 121) AS SpouseDOB, TeacherMast.SpouseOfficeAddr, CD_Positions.PositionName, 
                         CD_CivilStatus.CivilStatusName
FROM            TeacherMast LEFT JOIN
                         CD_Positions ON TeacherMast.SpouseOccupationCode = CD_Positions.Code LEFT JOIN
                         CD_CivilStatus ON TeacherMast.CivilStatusCode = CD_CivilStatus.Code
WHERE        TeacherMast.NIC = N'$NICUser'";

	$resA = $db->runMsSqlQuery($sqlPers);
	$rowA = sqlsrv_fetch_array($resA, SQLSRV_FETCH_ASSOC);//print_r($rowA);
	
	$CivilStatusCode = trim($rowA['CivilStatusCode']);
	$SpouseName = $rowA['SpouseName'];
	$SpouseNIC = trim($rowA['SpouseNIC']);
	$SpouseOccupationCode = trim($rowA['SpouseOccupationCode']);
	$SpouseDOB= $rowA['SpouseDOB'];
	$SpouseOfficeAddr = $rowA['SpouseOfficeAddr'];
	$PositionName = $rowA['PositionName'];
	$CivilStatusName = $rowA['CivilStatusName'];
//end family info

//services strat

$sqlCurAdd="SELECT        StaffServiceHistory.ID, StaffServiceHistory.NIC, CONVERT(varchar(20), StaffServiceHistory.AppDate, 121) AS AppDate, StaffServiceHistory.InstCode, StaffServiceHistory.ServiceRecTypeCode, 
                         CD_SecGrades.GradeName, CD_Service.ServiceName, CD_Positions.PositionName, CD_CAT2003.Cat2003Name, CD_ServiceRecType.Description, StaffServiceHistory.SecGRCode,  StaffServiceHistory.Reference,
                         StaffServiceHistory.ServiceTypeCode, StaffServiceHistory.PositionCode, StaffServiceHistory.Cat2003Code
FROM            StaffServiceHistory LEFT JOIN
                         CD_SecGrades ON StaffServiceHistory.SecGRCode = CD_SecGrades.GradeCode LEFT JOIN
                         CD_Service ON StaffServiceHistory.ServiceTypeCode = CD_Service.ServCode LEFT JOIN
                         CD_CAT2003 ON StaffServiceHistory.Cat2003Code = CD_CAT2003.Cat2003Code LEFT JOIN
                         CD_ServiceRecType ON StaffServiceHistory.ServiceRecTypeCode = CD_ServiceRecType.DutyCode LEFT JOIN
                         CD_Positions ON StaffServiceHistory.PositionCode = CD_Positions.Code
WHERE        (StaffServiceHistory.NIC = '$NICUser') ORDER BY StaffServiceHistory.AppDate ASC";// and StaffServiceHistory.ID='588449'

/*$sqlCurAdd="SELECT        StaffServiceHistory.ID, StaffServiceHistory.NIC, CONVERT(varchar(20), StaffServiceHistory.AppDate, 121) AS AppDate, StaffServiceHistory.InstCode, StaffServiceHistory.SecGRCode,
                         StaffServiceHistory.ServiceTypeCode, StaffServiceHistory.Cat2003Code, StaffServiceHistory.ServiceRecTypeCode, StaffServiceHistory.PositionCode FROM StaffServiceHistory
WHERE        (StaffServiceHistory.NIC = '$NICUser') ORDER BY StaffServiceHistory.AppDate ASC";*/

	
	$resABC = $db->runMsSqlQuery($sqlCurAdd);
//services end

?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top"><br />
      <table width="945" border="0" cellspacing="1" cellpadding="0" bgcolor="#666666">
        <tr>
          <td bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="2" cellpadding="0">
            <tr>
              <td bgcolor="#FFFFFF"><table width="100%" border="0" cellspacing="2" cellpadding="6" bgcolor="#666666">
                <tr>
                  <td bgcolor="#FFFFFF"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="font-family: Tahoma, Geneva, sans-serif; font-size: 10px; padding: 0; margin: 0;">
                   
                <tr>
                  <td colspan="2"><table width="100%" cellspacing="1" cellpadding="1" style="font-family: Tahoma, Geneva, sans-serif; font-size: 10px; padding: 0; margin: 0;">
                    <tr>
                      <td width="50%" align="center" style="font-size:14px; text-decoration:underline;">Teacher Profile</td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                  
                </tr>
                <tr>
                    <td colspan="2"><table width="100%" cellspacing="1" cellpadding="0" style="font-family: Tahoma, Geneva, sans-serif; font-size: 12px; padding: 0; margin: 0;">
                      
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF" style="border-bottom:1px; border-bottom-style:solid;"><strong>Personal Information</strong></td>
                        </tr>
                      <tr>
                        <td colspan="2" align="center" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                      <tr>
                        <td width="50%" align="left" valign="top" bgcolor="#FFFFFF"><table width="100%" cellspacing="2" cellpadding="2" style="font-family: Tahoma, Geneva, sans-serif; font-size: 12px; padding: 0; margin: 0;">
                            <tr>
                                <td width="30%" align="left" valign="top">NIC</td>
                                <td width="3%" align="left" valign="top">:</td>
                              <td width="67%" align="left" valign="top"><?php echo $NICUser ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">Title</td>
                                <td align="left" valign="top">:</td>
                              <td align="left" valign="top"><?php echo $TitleName ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">Surname with Initials</td>
                                <td align="left" valign="top">:</td>
                              <td align="left" valign="top"><?php echo $SurnameWithInitials ?></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top">Full Name</td>
                              <td align="left" valign="top">:</td>
                              <td align="left" valign="top"><?php echo $FullName ?></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top">Date of Birth</td>
                              <td align="left" valign="top">:</td>
                              <td align="left" valign="top"><?php echo $DOB ?></td>
                            </tr>
                            
                            </table></td>
                        <td width="50%" align="left" valign="top" bgcolor="#FFFFFF"><table width="100%" cellspacing="2" cellpadding="2" style="font-family: Tahoma, Geneva, sans-serif; font-size: 12px; padding: 0; margin: 0;">
                            <tr>
                                <td width="38%" align="left" valign="top">Ethinicity</td>
                                <td width="3%" align="left" valign="top">:</td>
                              <td width="59%" align="left" valign="top"><?php echo $EthnicityName ?></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top">Gender</td>
                              <td align="left" valign="top">:</td>
                              <td align="left" valign="top"><?php echo $GenderName ?></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top">Religion</td>
                              <td align="left" valign="top">:</td>
                              <td align="left" valign="top"><?php echo $ReligionName ?></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top">Email Address</td>
                              <td align="left" valign="top">:</td>
                              <td align="left" valign="top"><?php echo $emailaddr ?></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top">Mobile Number</td>
                              <td align="left" valign="top">:</td>
                              <td align="left" valign="top"><?php echo $MobileTel ?></td>
                            </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF"><span style="border-bottom:1px; border-bottom-style:solid;">Permanant Residance Details</span></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center" valign="top" bgcolor="#FFFFFF"><table width="100%" cellspacing="2" cellpadding="2" style="font-family: Tahoma, Geneva, sans-serif; font-size: 12px; padding: 0; margin: 0;">
                    <tr>
                      <td width="15%" align="left" valign="top">Address</td>
                      <td width="1%" align="left" valign="top">:</td>
                      <td width="34%" rowspan="5" align="left" valign="top"><div style="width:384px; height:110px;"><?php echo $Address ?></div></td>
                      <td width="19%" align="left" valign="top">District</td>
                      <td width="1%" align="left" valign="top">:</td>
                      <td width="30%" align="left" valign="top"><?php echo $DistName ?></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">DS Division</td>
                      <td align="left" valign="top">:</td>
                      <td align="left" valign="top"><?php echo $DSName ?></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">GS Division</td>
                      <td align="left" valign="top">:</td>
                      <td align="left" valign="top"><?php echo $GSDivision ?></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">Telephone</td>
                      <td align="left" valign="top">:</td>
                      <td align="left" valign="top"><?php echo $Tel ?></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">Effective Date</td>
                      <td align="left" valign="top">:</td>
                      <td align="left" valign="top"><?php echo $AppDate ?></td>
                    </tr>
                  </table></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF"><span style="border-bottom:1px; border-bottom-style:solid;">Current Residance Details</span></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center" valign="top" bgcolor="#FFFFFF"><table width="100%" cellspacing="2" cellpadding="2" style="font-family: Tahoma, Geneva, sans-serif; font-size: 12px; padding: 0; margin: 0;">
                    <tr>
                      <td width="15%" align="left" valign="top"> Address</td>
                      <td width="1%" align="left" valign="top">:</td>
                      <td width="34%" rowspan="5" align="left" valign="top"><div style="width:384px; height:110px;"><?php echo $AddressC ?></div></td>
                      <td width="19%" align="left" valign="top">District</td>
                      <td width="1%" align="left" valign="top">:</td>
                      <td width="30%" align="left" valign="top"><?php echo $DistNameC ?></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">DS Division</td>
                      <td align="left" valign="top">:</td>
                      <td align="left" valign="top"><?php echo $DSNameC ?></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">GS Division</td>
                      <td align="left" valign="top">:</td>
                      <td align="left" valign="top"><?php echo $GSDivisionC ?></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">Telephone</td>
                      <td align="left" valign="top">:</td>
                      <td align="left" valign="top"><?php echo $TelC ?></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">&nbsp;</td>
                      <td align="left" valign="top">Effective Date</td>
                      <td align="left" valign="top">:</td>
                      <td align="left" valign="top"><?php echo $AppDateC ?></td>
                    </tr>
                  </table></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                        </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF" style="border-bottom:1px; border-bottom-style:solid;"><strong>Family Information</strong></td>
                        </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF"><table width="100%" cellspacing="2" cellpadding="2" style="font-family: Tahoma, Geneva, sans-serif; font-size: 12px; padding: 0; margin: 0;">
                          <tr>
                            <td width="15%">Civil Status</td>
                            <td width="1%">:</td>
                            <td width="34%"><?php echo $CivilStatusName ?></td>
                            <td width="16%">&nbsp;</td>
                            <td width="17%">&nbsp;</td>
                            <td width="17%">&nbsp;</td>
                          </tr>
                        </table></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF"><span style="border-bottom:1px; border-bottom-style:solid;">Details of Spouse</span></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="left" valign="top" bgcolor="#FFFFFF"><table width="100%" cellspacing="2" cellpadding="2" style="font-family: Tahoma, Geneva, sans-serif; font-size: 12px; padding: 0; margin: 0;">
                            <tr>
                                <td width="30%" align="left" valign="top">NIC</td>
                                <td width="3%" align="left" valign="top">:</td>
                              <td width="67%" align="left" valign="top"><?php echo $SpouseNIC ?></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top">Full Name</td>
                              <td align="left" valign="top">:</td>
                              <td align="left" valign="top"><?php echo $SpouseName ?></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top">Date of Birth</td>
                              <td align="left" valign="top">:</td>
                              <td align="left" valign="top"><?php echo $SpouseDOB ?></td>
                            </tr>
                            
                            
                            </table></td>
                        <td align="left" valign="top" bgcolor="#FFFFFF"><table width="100%" cellspacing="2" cellpadding="2" style="font-family: Tahoma, Geneva, sans-serif; font-size: 12px; padding: 0; margin: 0;">
                      <tr>
                        <td width="38%">Occupation</td>
                        <td width="3%">:</td>
                        <td width="59%"><?php echo $PositionName ?></td>
                      </tr>
                      <tr>
                        <td>Office Address</td>
                        <td>:</td>
                        <td><?php echo $SpouseOfficeAddr ?></td>
                      </tr>
                    </table></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF"><span style="border-bottom:1px; border-bottom-style:solid;">Details of Children</span></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#999999"><table width="100%" cellspacing="1" cellpadding="1" style="font-family: Tahoma, Geneva, sans-serif; font-size: 12px; padding: 0; margin: 0;">
                    <tr>
                      <td width="3%" align="center" valign="top" bgcolor="#CCCCCC">#</td>
                      <td width="51%" align="left" valign="top" bgcolor="#CCCCCC">Child's Name</td>
                      <td width="24%" align="left" valign="top" bgcolor="#CCCCCC">Date of Birth</td>
                      <td width="22%" align="left" valign="top" bgcolor="#CCCCCC">Gender</td>
                      </tr>
                    <?php 
					$i=1;
					$sql = "SELECT        StaffChildren.ID, StaffChildren.NIC, StaffChildren.ChildName, StaffChildren.Gender, CONVERT(varchar(20),StaffChildren.DOB,121) AS DOB, StaffChildren.LastUpdate, StaffChildren.UpdateBy, 
                         StaffChildren.RecordLog, CD_Gender.[Gender Name]
FROM            StaffChildren INNER JOIN
                         CD_Gender ON StaffChildren.Gender = CD_Gender.GenderCode
WHERE        (StaffChildren.NIC = N'$NICUser') order by StaffChildren.DOB asc";
					$stmt = $db->runMsSqlQuery($sql);
					while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$ChildName=trim($row['ChildName']);
						$DOB=$row['DOB'];?>
                    <tr>
                      <td height="25" align="center" valign="middle" bgcolor="#FFFFFF"><?php echo $i++ ?>)</td>
                      <td align="left" valign="middle" bgcolor="#FFFFFF"><?php echo $ChildName ?></td>
                      <td align="left" valign="middle" bgcolor="#FFFFFF"><?php echo $DOB ?></td>
                      <td align="left" valign="middle" bgcolor="#FFFFFF"><?php echo $row['Gender Name']; ?></td>
                      </tr>
                   <?php }?>
                  </table></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                        </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF" style="border-bottom:1px; border-bottom-style:solid;"><strong>Qualifications</strong></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#999999"><table width="100%" cellspacing="1" cellpadding="1" style="font-family: Tahoma, Geneva, sans-serif; font-size: 12px; padding: 0; margin: 0;">
                    <tr>
                      <td width="3%" align="center" valign="top" bgcolor="#CCCCCC">#</td>
                      <td width="18%" align="left" valign="top" bgcolor="#CCCCCC">Qualification Title</td>
                      <td width="35%" align="left" valign="top" bgcolor="#CCCCCC">Description</td>
                      <td width="30%" align="left" valign="top" bgcolor="#CCCCCC">Subjects</td>
                      <td width="14%" align="left" valign="top" bgcolor="#CCCCCC">Effective Date</td>
                      </tr>
                    <?php 
					$i=1;
					$sql = "SELECT        StaffQualification.ID, StaffQualification.NIC, StaffQualification.QCode, CONVERT(varchar(20),StaffQualification.EffectiveDate, 121) AS EffectiveDate, StaffQualification.Reference, StaffQualification.LastUpdate, 
                         StaffQualification.UpdateBy, StaffQualification.RecordLog, CD_Qualif.Description, CD_QualificationCategory.Description AS Expr1
FROM            StaffQualification INNER JOIN
                         CD_Qualif ON StaffQualification.QCode = CD_Qualif.Qcode INNER JOIN
                         CD_QualificationCategory ON CD_Qualif.Category = CD_QualificationCategory.Code
WHERE        (StaffQualification.NIC = '$NICUser')
";
					$stmt = $db->runMsSqlQuery($sql);
					while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$catTitle=trim($row['Expr1']);
						$Description=$row['Description'];
						$EffectiveDate=$row['EffectiveDate'];
						$Expr1=$row['ID'];
						
						$SubjectName="";
						$sqlSub="SELECT CD_Subject.SubjectName
FROM            QualificationSubjects INNER JOIN
                         CD_Subject ON QualificationSubjects.SubjectCode = CD_Subject.SubCode
WHERE        (QualificationSubjects.QualificationID = '$Expr1')";
					$stmtSub = $db->runMsSqlQuery($sqlSub);
					while ($rowSub = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
						$SubjectName.=trim($rowSub['SubjectName']).",";
					}
						?>
                    <tr>
                      <td align="center" valign="top" bgcolor="#FFFFFF"><?php echo $i++ ?>)</td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $catTitle ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $Description ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $SubjectName; ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $EffectiveDate; ?></td>
                      </tr>
                    <?php }?>
                  </table></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF" style="border-bottom:1px; border-bottom-style:solid;"><strong>Teaching</strong></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#999999"><table width="100%" cellspacing="1" cellpadding="1" style="font-family: Tahoma, Geneva, sans-serif; font-size: 12px; padding: 0; margin: 0;">
                    <tr>
                      <td width="3%" align="center" valign="top" bgcolor="#CCCCCC">#</td>
                      <td width="18%" align="left" valign="top" bgcolor="#CCCCCC">Category</td>
                      <td width="35%" align="left" valign="top" bgcolor="#CCCCCC">Subject Name</td>
                      <td width="20%" align="left" valign="top" bgcolor="#CCCCCC">Medium</td>
                      <td width="24%" align="left" valign="top" bgcolor="#CCCCCC">Section/Grade</td>
                      </tr>
                    <?php 
					$i=1;
					//$NICUser="592770830V";
					$sql = "SELECT        TeacherSubject.ID, TeacherSubject.NIC, TeacherSubject.SubjectType, TeacherSubject.SubjectCode, 
                         TeacherSubject.MediumCode, TeacherSubject.SecGradeCode, CD_SubjectTypes.SubTypeName, CD_Subject.SubjectName, 
                         CD_Medium.Medium, CD_SecGrades.GradeName
FROM            TeacherSubject INNER JOIN
                         CD_SubjectTypes ON TeacherSubject.SubjectType = CD_SubjectTypes.SubType INNER JOIN
                         CD_Subject ON TeacherSubject.SubjectCode = CD_Subject.SubCode INNER JOIN
                         CD_Medium ON TeacherSubject.MediumCode = CD_Medium.Code INNER JOIN
                         CD_SecGrades ON TeacherSubject.SecGradeCode = CD_SecGrades.GradeCode
WHERE        (TeacherSubject.NIC = N'$NICUser')
";
					$stmt = $db->runMsSqlQuery($sql);
					while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$SubjectName=trim($row['SubjectName']);
						$SubTypeName=$row['SubTypeName'];
						$Medium=$row['Medium'];
						$GradeName=$row['GradeName'];
						$Expr1=$row['ID'];
						
						?>
                    <tr>
                      <td align="center" valign="top" bgcolor="#FFFFFF"><?php echo $i++ ?>)</td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $SubTypeName ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $SubjectName ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $Medium; ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $GradeName; ?></td>
                      </tr>
                    <?php }?>
                  </table></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" valign="top" bgcolor="#FFFFFF" style="border-bottom:1px; border-bottom-style:solid;"><strong>Services</strong></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="center" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                      <?php 
				$x=1;
				while ($rowABC = sqlsrv_fetch_array($resABC, SQLSRV_FETCH_ASSOC)){
					$StaffServiceHistoryID= $rowABC['ID'];
		$AppDate = $rowABC['AppDate'];
		$InstCode = trim($rowABC['InstCode']);
		/* $SecGRCode = trim($rowABC['SecGRCode']);
		$ServiceRecTypeCode = trim($rowABC['ServiceRecTypeCode']);
		$ServiceTypeCode = trim($rowABC['ServiceTypeCode']);
		$Cat2003Code = trim($rowABC['Cat2003Code']);
		$PositionCode = trim($rowABC['PositionCode']); */
		
		$GradeName = trim($rowABC['GradeName']);
		$Description = trim($rowABC['Description']);
		$ServiceName = trim($rowABC['ServiceName']);
		$Cat2003Name = trim($rowABC['Cat2003Name']);
		$PositionName = trim($rowABC['PositionName']);
		
		$sqlCenseQ="SELECT        CD_CensesNo.InstitutionName, CD_Districts.DistName, CD_Provinces.Province, CD_Zone.InstitutionName AS ZoneN, CD_Division.InstitutionName AS DivisionN
	FROM            CD_Division INNER JOIN
							 CD_Provinces INNER JOIN
							 CD_CensesNo INNER JOIN
							 CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode ON CD_Provinces.ProCode = CD_Districts.ProCode INNER JOIN
							 CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode ON CD_Division.CenCode = CD_CensesNo.DivisionCode
	WHERE        (CD_CensesNo.CenCode = '$InstCode')";
	
		$resABCq = $db->runMsSqlQuery($sqlCenseQ);
		$rowABCq = sqlsrv_fetch_array($resABCq, SQLSRV_FETCH_ASSOC);
		$InstitutionName = $rowABCq['InstitutionName'];
		$DistName = trim($rowABCq['DistName']);
		$Province = $rowABCq['Province'];
		$ZoneN = $rowABCq['ZoneN'];
		$DivisionN = $rowABCq['DivisionN'];
	
				?>
                <?php if($x>1){?>
<tr>
                  <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <?php }?>
                <tr>
                  <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2" style="font-family: Tahoma, Geneva, sans-serif; font-size: 12px; padding: 0; margin: 0;">
                    <tr>
                      <td width="3%" rowspan="6" align="left" valign="top"><?php echo $x++ ?>)</td>
                      <td align="left" valign="top">Appoinment Type</td>
                      <td align="left" valign="top">:</td>
                      <td align="left" valign="top"><?php echo $Description ?></td>
                      <td width="23%" align="left" valign="top">Personal File Reference</td>
                      <td align="left" valign="top">:</td>
                      <td align="left" valign="top"><?php echo $Reference ?></td>
                      </tr>
                    <tr>
                      <td width="16%" align="left" valign="top">Province</td>
                      <td width="1%" align="left" valign="top">:</td>
                      <td width="29%" align="left" valign="top"><?php echo $Province ?></td>
                      <td align="left" valign="top">Section/Grade</td>
                      <td width="1%" align="left" valign="top">:</td>
                      <td width="27%" align="left" valign="top"><?php echo $GradeName ?></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">District</td>
                      <td align="left" valign="top">:</td>
                      <td width="29%" align="left" valign="top"><?php echo $DistName ?></td>
                      <td align="left" valign="top">Position</td>
                      <td align="left" valign="top">:</td>
                      <td align="left" valign="top"><?php echo $PositionName ?></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">Zone</td>
                      <td align="left" valign="top">:</td>
                      <td width="29%" align="left" valign="top"><?php echo $ZoneN ?></td>
                      <td align="left" valign="top">Date of Appoinment</td>
                      <td align="left" valign="top">:</td>
                      <td align="left" valign="top"><?php echo $AppDate ?></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">DS Division</td>
                      <td align="left" valign="top">:</td>
                      <td width="29%" align="left" valign="top"><?php echo $DivisionN ?> </td>
                      <td align="left" valign="top">Service Category</td>
                      <td align="left" valign="top">:</td>
                      <td align="left" valign="top"><?php echo $ServiceName ?></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">School</td>
                      <td align="left" valign="top">:</td>
                      <td align="left" valign="top"><?php echo $InstitutionName ?></td>
                      <td align="left" valign="top">2003/38 Circular Category</td>
                      <td align="left" valign="top">:</td>
                      <td align="left" valign="top"><?php echo $Cat2003Name ?></td>
                    </tr>
                  </table></td>
                </tr>
                
            <?php }?>
                      <tr>
                        <td colspan="2" align="center" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                      </table></td>
          </tr>
                <tr>
                  <td width="59%">&nbsp;</td>
                  <td width="41%" align="right">Report generated on <?php echo date('Y-m-d H:i:s'); ?></td>
                </tr>
              
                  </table></td>
                </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
    </table></td>
  </tr>
</table>
</body>
</html>