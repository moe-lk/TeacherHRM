<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
include_once '../approveProcessfunction.php';
//include('js/common.js.php'); 
include('js/ajaxloadpage.js.php'); 

$cat="transferTeacherNational";
$tblName="TG_TeacherTransferNational";
$TransferType="TTRN";

if($fm=='' || $fm=='A'){
			$sql = "SELECT SurnameWithInitials,FullName,GenderCode,CivilStatusCode,SpouseName,SpouseOccupationCode, SpouseOfficeAddr,CurServiceRef,CONVERT(varchar(20),DOB,121) AS dateofBirth FROM TeacherMast where NIC='$NICUser'";
		
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$SurnameWithInitials=$row['SurnameWithInitials'];
			$FullName=$row['FullName'];
			$GenderCode=$row['GenderCode'];
			$CivilStatusCode=$row['CivilStatusCode'];
			$SpouseName=$row['SpouseName'];
			$SpouseOccupationCode=$row['SpouseOccupationCode'];
			$SpouseOfficeAddr=$row['SpouseOfficeAddr'];
			$CurServiceRef=$row['CurServiceRef'];
			$DOB=$row['dateofBirth'];
		}
		
		$sqlSPos = "SELECT PositionName FROM CD_Positions where Code='$SpouseOccupationCode'";
		$stmtSpos = $db->runMsSqlQuery($sqlSPos);
		while ($row = sqlsrv_fetch_array($stmtSpos, SQLSRV_FETCH_ASSOC)) {
			$PositionName=$row['PositionName'];
		}
		
		$sqlAdd = "SELECT * FROM StaffAddrHistory where NIC='$NICUser' and AddrType='PER'";
		$stmtAdd = $db->runMsSqlQuery($sqlAdd);
		while ($row = sqlsrv_fetch_array($stmtAdd, SQLSRV_FETCH_ASSOC)) {
			$Address=$row['Address'];
		}
		
		$sqlMS = "SELECT * FROM CD_CivilStatus where Code='$CivilStatusCode'";
		$stmtMS = $db->runMsSqlQuery($sqlMS);
		while ($row = sqlsrv_fetch_array($stmtMS, SQLSRV_FETCH_ASSOC)) {
			$CivilStatusName=$row['CivilStatusName'];
		}
		
		$sqlCS="SELECT        CD_CensesNo.InstitutionName, StaffServiceHistory.WorkStatusCode, StaffServiceHistory.ServiceTypeCode, StaffServiceHistory.SecGRCode, StaffServiceHistory.PositionCode
		FROM            StaffServiceHistory INNER JOIN
								 CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode where StaffServiceHistory.ID='$CurServiceRef'";
		$stmtCS = $db->runMsSqlQuery($sqlCS);
		while ($row = sqlsrv_fetch_array($stmtCS, SQLSRV_FETCH_ASSOC)) {
			$InstitutionName=$row['InstitutionName'];
			$WorkStatusCode=$row['WorkStatusCode'];
			$ServiceTypeCode=$row['ServiceTypeCode'];
			$SecGRCode=$row['SecGRCode'];
			$PositionCode=$row['PositionCode'];
		}
		$sqlTG = "SELECT * FROM CD_Service where ServCode='$ServiceTypeCode'";
		$stmtTG = $db->runMsSqlQuery($sqlTG);
		while ($row = sqlsrv_fetch_array($stmtTG, SQLSRV_FETCH_ASSOC)) {
			$ServiceName=$row['ServiceName'];
		}
		
		$sqlFA = "SELECT CONVERT(varchar(20),AppDate,121) AS firstAppDate FROM StaffServiceHistory where NIC='$NICUser' and ServiceRecTypeCode='NA01'";
		$stmtFA = $db->runMsSqlQuery($sqlFA);
		while ($row = sqlsrv_fetch_array($stmtFA, SQLSRV_FETCH_ASSOC)) {
			$firstAppDate=$row['firstAppDate'];
		}
		
		$appointmnt=$tching=$capableteach="";
		$sqlteaching="SELECT        CD_Subject.SubjectName, TeacherSubject.NIC, TeacherSubject.SubjectType
		FROM            TeacherSubject INNER JOIN
						 CD_Subject ON TeacherSubject.SubjectCode = CD_Subject.SubCode
		WHERE        (TeacherSubject.NIC = N'$NICUser') AND (TeacherSubject.SubjectType = N'APP') ORDER BY CD_Subject.SubjectName";
		$stmtEQ = $db->runMsSqlQuery($sqlteaching);
		while ($row = sqlsrv_fetch_array($stmtEQ, SQLSRV_FETCH_ASSOC)) {
			$appointmnt.=$row['SubjectName']." ,";
		}
		$sqlteaching="SELECT        CD_Subject.SubjectName, TeacherSubject.NIC, TeacherSubject.SubjectType
		FROM            TeacherSubject INNER JOIN
						 CD_Subject ON TeacherSubject.SubjectCode = CD_Subject.SubCode
		WHERE        (TeacherSubject.NIC = N'$NICUser') AND (TeacherSubject.SubjectType = N'TCH') ORDER BY CD_Subject.SubjectName";
		$stmtEQ = $db->runMsSqlQuery($sqlteaching);
		while ($row = sqlsrv_fetch_array($stmtEQ, SQLSRV_FETCH_ASSOC)) {
			$tching.=$row['SubjectName']." ,";
		}
		$sqlteaching="SELECT        CD_Subject.SubjectName, TeacherSubject.NIC, TeacherSubject.SubjectType
		FROM            TeacherSubject INNER JOIN
						 CD_Subject ON TeacherSubject.SubjectCode = CD_Subject.SubCode
		WHERE        (TeacherSubject.NIC = N'$NICUser') AND (TeacherSubject.SubjectType = N'CAP') ORDER BY CD_Subject.SubjectName";
		$stmtEQ = $db->runMsSqlQuery($sqlteaching);
		while ($row = sqlsrv_fetch_array($stmtEQ, SQLSRV_FETCH_ASSOC)) {
			$capableteach.=$row['SubjectName']." ,";
		}
	
	}else{
		$sqlTransf="SELECT        TransferType, TransferRequestType, ExpectSchool, LikeToOtherSchool, ReasonForTransfer, ExtraActivities, RequestedDate, IsApproved, SchoolID, NIC, ExpectSchool2, ExpectSchool3, ExpectSchool4, ExpectSchool5
FROM            $tblName
WHERE        (ID = '$id')";

			$stmtTr = $db->runMsSqlQuery($sqlTransf);
			while ($row = sqlsrv_fetch_array($stmtTr, SQLSRV_FETCH_ASSOC)) {
				$TransferType=$row['TransferType'];
				$TransferRequestType=trim($row['TransferRequestType']);
				$ExpectSchool=trim($row['ExpectSchool']);
				$LikeToOtherSchool=trim($row['LikeToOtherSchool']);
				$ReasonForTransfer=$row['ReasonForTransfer'];
				$ExtraActivities=$row['ExtraActivities'];
				$RequestedDate=$row['RequestedDate'];
				$IsApproved=$row['IsApproved'];
				$SchoolID=$loggedSchool=$row['SchoolID'];
				$NIC=$NICUser=$row['NIC'];
				$ExpectSchool2=$row['ExpectSchool2'];
				$ExpectSchool3=$row['ExpectSchool3'];
				$ExpectSchool4=$row['ExpectSchool4'];
				$ExpectSchool5=$row['ExpectSchool5'];
			}
			
		//////Start form values
		
		$sql = "SELECT SurnameWithInitials,FullName,GenderCode,CivilStatusCode,SpouseName,SpouseOccupationCode, SpouseOfficeAddr,CurServiceRef,CONVERT(varchar(20),DOB,121) AS dateofBirth FROM TeacherMast where NIC='$NIC'";

		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$SurnameWithInitials=$row['SurnameWithInitials'];
			$FullName=$row['FullName'];
			$GenderCode=$row['GenderCode'];
			$CivilStatusCode=$row['CivilStatusCode'];
			$SpouseName=$row['SpouseName'];
			$SpouseOccupationCode=$row['SpouseOccupationCode'];
			$SpouseOfficeAddr=$row['SpouseOfficeAddr'];
			$CurServiceRef=$row['CurServiceRef'];
			$DOB=$row['dateofBirth'];
		}
		
		$sqlSPos = "SELECT PositionName FROM CD_Positions where Code='$SpouseOccupationCode'";
		$stmtSpos = $db->runMsSqlQuery($sqlSPos);
		while ($row = sqlsrv_fetch_array($stmtSpos, SQLSRV_FETCH_ASSOC)) {
			$PositionName=$row['PositionName'];
		}
		
		$sqlAdd = "SELECT * FROM StaffAddrHistory where NIC='$NIC' and AddrType='PER'";
		$stmtAdd = $db->runMsSqlQuery($sqlAdd);
		while ($row = sqlsrv_fetch_array($stmtAdd, SQLSRV_FETCH_ASSOC)) {
			$Address=$row['Address'];
		}
		
		$sqlMS = "SELECT * FROM CD_CivilStatus where Code='$CivilStatusCode'";
		$stmtMS = $db->runMsSqlQuery($sqlMS);
		while ($row = sqlsrv_fetch_array($stmtMS, SQLSRV_FETCH_ASSOC)) {
			$CivilStatusName=$row['CivilStatusName'];
		}
		
		$sqlCS="SELECT        CD_CensesNo.InstitutionName, StaffServiceHistory.WorkStatusCode, StaffServiceHistory.ServiceTypeCode, StaffServiceHistory.SecGRCode, StaffServiceHistory.PositionCode
		FROM            StaffServiceHistory INNER JOIN
								 CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode where StaffServiceHistory.ID='$CurServiceRef'";
		$stmtCS = $db->runMsSqlQuery($sqlCS);
		while ($row = sqlsrv_fetch_array($stmtCS, SQLSRV_FETCH_ASSOC)) {
			$InstitutionName=$row['InstitutionName'];
			$WorkStatusCode=$row['WorkStatusCode'];
			$ServiceTypeCode=$row['ServiceTypeCode'];
			$SecGRCode=$row['SecGRCode'];
			$PositionCode=$row['PositionCode'];
		}
		$sqlTG = "SELECT * FROM CD_Service where ServCode='$ServiceTypeCode'";
		$stmtTG = $db->runMsSqlQuery($sqlTG);
		while ($row = sqlsrv_fetch_array($stmtTG, SQLSRV_FETCH_ASSOC)) {
			$ServiceName=$row['ServiceName'];
		}
		
		$sqlFA = "SELECT CONVERT(varchar(20),AppDate,121) AS firstAppDate FROM StaffServiceHistory where NIC='$NIC' and ServiceRecTypeCode='NA01'";
		$stmtFA = $db->runMsSqlQuery($sqlFA);
		while ($row = sqlsrv_fetch_array($stmtFA, SQLSRV_FETCH_ASSOC)) {
			$firstAppDate=$row['firstAppDate'];
		}
		
		if($TransferRequestType=='WZ' || $TransferRequestType=='OZ'){
			$lableZne="Zone";
			$sql = "Select CenCode,InstitutionName FROM CD_Zone
	  where CenCode='$TransferRequestTypeID'
	  order by InstitutionName";
			$stmt = $db->runMsSqlQuery($sql);
			while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				$zonename=$row['InstitutionName'];
				//echo "<input type=\"hidden\" name=\"TransferRequestTypeID\" value=\"$ZoneCode\" />";
			}
		}
		if($TransferRequestType=='NS'){
			$lableZne="National School";
			$sqlWhere="where IsNationalSchool='1' and CenCode='$TransferRequestTypeID'";
		
			$sql = "Select CenCode,InstitutionName FROM CD_CensesNo
	$sqlWhere
	order by InstitutionName";
			$stmt = $db->runMsSqlQuery($sql);
			while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				$zonename=addslashes($row['InstitutionName']);
			}
		}
		if($TransferRequestType=='OP'){
			$lableZne="Province";
		}
		
		$iAvailTolArr = explode(',',$ExtraActivities);
		$ActivityTitle="";
		for($t=0;$t<count($iAvailTolArr);$t++){
		  $actiID=$iAvailTolArr[$t];
			$sqlMS = "SELECT * FROM TG_TeacherExtraActivity where ID='$actiID'";
			$stmtMS = $db->runMsSqlQuery($sqlMS);
			while ($row = sqlsrv_fetch_array($stmtMS, SQLSRV_FETCH_ASSOC)) {
				$ActivityTitle.=$row['ActivityTitle'].",";
			}
		}
		
		$sql = "SELECT InstitutionName
  FROM CD_CensesNo
  Where CenCode='$ExpectSchool'";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$requestedSchool=$row['InstitutionName'];
		}
		
		$workOtherSchool="No";
		if($LikeToOtherSchool=='Y')$workOtherSchool="Yes";
		
		$appointmnt=$tching=$capableteach="";
		$sqlteaching="SELECT        CD_Subject.SubjectName, TeacherSubject.NIC, TeacherSubject.SubjectType
FROM            TeacherSubject INNER JOIN
                         CD_Subject ON TeacherSubject.SubjectCode = CD_Subject.SubCode
WHERE        (TeacherSubject.NIC = N'$NIC') AND (TeacherSubject.SubjectType = N'APP') ORDER BY CD_Subject.SubjectName";
		$stmtEQ = $db->runMsSqlQuery($sqlteaching);
		while ($row = sqlsrv_fetch_array($stmtEQ, SQLSRV_FETCH_ASSOC)) {
			$appointmnt.=$row['SubjectName']." ,";
		}
		$sqlteaching="SELECT        CD_Subject.SubjectName, TeacherSubject.NIC, TeacherSubject.SubjectType
FROM            TeacherSubject INNER JOIN
                         CD_Subject ON TeacherSubject.SubjectCode = CD_Subject.SubCode
WHERE        (TeacherSubject.NIC = N'$NIC') AND (TeacherSubject.SubjectType = N'TCH') ORDER BY CD_Subject.SubjectName";
		$stmtEQ = $db->runMsSqlQuery($sqlteaching);
		while ($row = sqlsrv_fetch_array($stmtEQ, SQLSRV_FETCH_ASSOC)) {
			$tching.=$row['SubjectName']." ,";
		}
		$sqlteaching="SELECT        CD_Subject.SubjectName, TeacherSubject.NIC, TeacherSubject.SubjectType
FROM            TeacherSubject INNER JOIN
                         CD_Subject ON TeacherSubject.SubjectCode = CD_Subject.SubCode
WHERE        (TeacherSubject.NIC = N'$NIC') AND (TeacherSubject.SubjectType = N'CAP') ORDER BY CD_Subject.SubjectName";
		$stmtEQ = $db->runMsSqlQuery($sqlteaching);
		while ($row = sqlsrv_fetch_array($stmtEQ, SQLSRV_FETCH_ASSOC)) {
			$capableteach.=$row['SubjectName']." ,";
		}
							
		
		$sqlFA = "SELECT CONVERT(varchar(20),AppDate,121) AS currentAppDate FROM StaffServiceHistory where NIC='$NIC' and InstCode='$SchoolID'"; //ORDER BY ID DESC
		$stmtFA = $db->runMsSqlQuery($sqlFA);
		while ($row = sqlsrv_fetch_array($stmtFA, SQLSRV_FETCH_ASSOC)) {
			$currentAppDate=$row['currentAppDate'];
		}
	}
	
?>

<table width="945" cellpadding="0" cellspacing="0">
        	  <tr>
        	    <td bgcolor="#FFFFFF"><strong>Personal Information</strong></td>
      	    </tr>
        	  <tr>
        	    <td><table width="100%" cellspacing="1" cellpadding="1">
        	      <tr>
        	        <td width="23%" bgcolor="#F7E2DD">Full Name:</td>
        	        <td colspan="3" bgcolor="#EDEEF3"><?php echo ucwords(strtolower(($FullName))); ?>
                      </td>
       	          </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Name With Initials :</td>
        	        <td width="46%" bgcolor="#EDEEF3"><?php echo ucwords(strtolower(($SurnameWithInitials))); ?></td>
        	        <td width="14%" bgcolor="#F7E2DD">NIC :</td>
        	        <td width="17%" bgcolor="#EDEEF3"><?php echo $NICUser ?><input type="hidden" name="NIC" value="<?php echo $NICUser ?>"/></td>
      	        </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Gender :</td>
        	        <td bgcolor="#EDEEF3"><?php if($GenderCode==1){echo "Male";}else if($GenderCode==2){echo "Female";}else {echo "N/A";}?></td>
        	        <td bgcolor="#F7E2DD">Marital Status :</td>
        	        <td bgcolor="#EDEEF3">&nbsp;</td>
      	        </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Date of Birth :</td>
        	        <td bgcolor="#EDEEF3"><?php echo $DOB; ?></td>
        	        <td bgcolor="#F7E2DD">Age up to <?php echo $todate=date('Y-12-31'); ?> :</td>
        	        <td bgcolor="#EDEEF3"><?php echo calculateCurrentAge($DOB,$todate); ?></td>
      	        </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Permanent Address :</td>
        	        <td colspan="3" bgcolor="#EDEEF3"><?php echo ucwords(strtolower(($Address))); ?></td>
       	          </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Spouse Occupation :</td>
        	        <td colspan="3" bgcolor="#EDEEF3"><?php echo ucwords(strtolower(($PositionName))); ?></td>
       	          </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Spouse Occupation Address :</td>
        	        <td colspan="3" bgcolor="#EDEEF3"><?php echo ucwords(strtolower(($SpouseOfficeAddr))); ?></td>
       	          </tr>
        	      <tr>
        	        <td valign="top" bgcolor="#F7E2DD">Chiildren Below 5 Years :</td>
        	        <td colspan="3" bgcolor="#EDEEF3"><?php 
					  $sqlFA = "SELECT ChildName,CONVERT(varchar(20),DOB,121) AS ChildDOB FROM StaffChildren where NIC='$NICUser'";
$stmtFA = $db->runMsSqlQuery($sqlFA);


					  ?><table width="100%" cellspacing="1" cellpadding="1">
                        <tr>
                          <td width="54%" bgcolor="#CCCCCC"><strong>Name</strong></td>
                          <td width="46%" bgcolor="#CCCCCC"><strong>DOB</strong></td>
                        </tr>
                        <?php 
						$c=0;
						$todate=date('Y-m-d');
						while ($row = sqlsrv_fetch_array($stmtFA, SQLSRV_FETCH_ASSOC)) {
								$ChildName=$row['ChildName'];
								$ChildDOB=$row['ChildDOB'];
								$ageChild=calculateCurrentAge($ChildDOB,$todate);
								if($ageChild<=5){
									$c=1;
							?>
                        <tr>
                          <td bgcolor="#FFFFFF"><?php echo $ChildName ?></td>
                          <td bgcolor="#FFFFFF"><?php echo $ChildDOB ?> - <?php echo $ageChild; ?></td>
                        </tr>
                        
                        <?php }}?>
                        <?php if($c==0){?>
                        <tr>
                          <td bgcolor="#FFFFFF">N/A</td>
                          <td bgcolor="#FFFFFF">N/A</td>
                        </tr>
                        <?php }?>
                      </table></td>
       	          </tr>
      	      </table></td>
      	    </tr>
        	  <tr>
        	    <td>&nbsp;</td>
      	    </tr>
        	  <tr>
        	    <td><strong>Career Information</strong></td>
      	    </tr>
        	  <tr>
        	    <td><table width="100%" cellspacing="1" cellpadding="1">
        	      <tr>
        	        <td bgcolor="#F7E2DD">Reqistration Number :</td>
        	        <td bgcolor="#EDEEF3">&nbsp;</td>
      	        </tr>
        	      <tr>
        	        <td width="23%" bgcolor="#F7E2DD">1st Appointment Date :</td>
        	        <td width="77%" bgcolor="#EDEEF3"><?php echo $firstAppDate ?></td>
       	          </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">1st Appointment Place :</td>
        	        <td bgcolor="#EDEEF3">&nbsp;</td>
       	          </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Appointment Type :</td>
        	        <td bgcolor="#EDEEF3"><select name="select3" class="select2a_n" id="select3">
        	          <option value="NED">National Educatio Diploma</option>
        	          <option value="OZ">Disapathi</option>
        	          <option value="TE">Teaching Exams</option>
        	          <option value="OT">Other</option>
      	          </select></td>
       	          </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Current Appointment Date :</td>
        	        <td bgcolor="#EDEEF3"><?php 
					 $sqlFA = "SELECT CONVERT(varchar(20),AppDate,121) AS currentAppDate FROM StaffServiceHistory where NIC='$NICUser' and InstCode='$loggedSchool'"; //ORDER BY ID DESC
$stmtFA = $db->runMsSqlQuery($sqlFA);
while ($row = sqlsrv_fetch_array($stmtFA, SQLSRV_FETCH_ASSOC)) {
	echo $currentAppDate=$row['currentAppDate'];
}
	

					  ?></td>
       	          </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Current School Name :</td>
        	        <td bgcolor="#EDEEF3"><?php echo ucwords(strtolower(($InstitutionName))); ?></td>
       	          </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Current School Address :</td>
        	        <td bgcolor="#EDEEF3"><?php //echo ucwords(strtolower(($SpouseOfficeAddr))); ?></td>
       	          </tr>
                   <tr>
                      <td valign="top" bgcolor="#F7E2DD">Service Details :</td>
                      <td bgcolor="#EDEEF3"><table width="100%" cellspacing="1" cellpadding="1">
                        <tr>
                          <td width="19%" bgcolor="#CCCCCC">&nbsp;</td>
                          <td width="31%" bgcolor="#CCCCCC">School</td>
                          <td width="15%" bgcolor="#CCCCCC">District</td>
                          <td width="9%" bgcolor="#CCCCCC">&nbsp;</td>
                          <td width="11%" bgcolor="#CCCCCC">Duration</td>
                          <td width="15%" bgcolor="#CCCCCC">Score</td>
                        </tr>
                        <?php $sqlStHistry="SELECT        CD_CensesNo.InstitutionName, CD_CensesNo.DistrictCode, CD_Districts.DistName
FROM            StaffServiceHistory INNER JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode INNER JOIN
                         CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
WHERE        (StaffServiceHistory.NIC = '$NICUser') ORDER BY StaffServiceHistory.AppDate ASC";
 //echo $TotaRows=$db->rowCount($sqlStHistry);
 $stmtSH = $db->runMsSqlQuery($sqlStHistry);
						while ($rowh = sqlsrv_fetch_array($stmtSH, SQLSRV_FETCH_ASSOC)) {
								$InstitutionName=$rowh['InstitutionName'];
								$DistrictCode=$rowh['DistrictCode'];
								$DistName=$rowh['DistName'];
								?>
                        <tr>
                          <td bgcolor="#FFFFFF">&nbsp;</td>
                          <td bgcolor="#FFFFFF"><?php echo $InstitutionName;?></td>
                          <td bgcolor="#FFFFFF"><?php echo $DistName ?> <?php //echo $DistrictCode ?></td>
                          <td bgcolor="#FFFFFF">&nbsp;</td>
                          <td bgcolor="#FFFFFF">&nbsp;</td>
                          <td bgcolor="#FFFFFF">&nbsp;</td>
                        </tr>
                        <?php }?>
                      </table></td>
                    </tr>
      	      </table></td>
      	    </tr>
        	  <tr>
        	    <td>&nbsp;</td>
      	    </tr>
        	  <tr>
        	    <td><strong>Teaching Qualification</strong></td>
   	      </tr>
        	  <tr>
        	    <td><table width="100%" cellspacing="1" cellpadding="1">
        	      <tr>
        	        <td width="23%" bgcolor="#F7E2DD">Education Qualifications :</td>
        	        <td width="77%" bgcolor="#EDEEF3"><?php 
					  
$sqlEQ = "SELECT        StaffQualification.ID, StaffQualification.NIC, CD_Qualif.Description
FROM            StaffQualification INNER JOIN
                         CD_Qualif ON StaffQualification.QCode = CD_Qualif.Qcode
WHERE        (StaffQualification.NIC = '$NICUser')";
$stmtEQ = $db->runMsSqlQuery($sqlEQ);
while ($row = sqlsrv_fetch_array($stmtEQ, SQLSRV_FETCH_ASSOC)) {
	echo $Description=$row['Description']." ,";
}
					  ?></td>
       	          </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Teaching Grade :</td>
        	        <td bgcolor="#EDEEF3"><?php echo $ServiceName ?></td>
       	          </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Vocational Training :</td>
        	        <td bgcolor="#EDEEF3">&nbsp;</td>
       	          </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Degree Subjects :</td>
        	        <td bgcolor="#EDEEF3">&nbsp;</td>
       	          </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Teaching Subject :</td>
        	        <td bgcolor="#EDEEF3"><?php echo $tching ?></td>
       	          </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Appointment Subject :</td>
        	        <td bgcolor="#EDEEF3"><?php echo $appointmnt ?></td>
       	          </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Capable Subject :</td>
        	        <td bgcolor="#EDEEF3"><?php echo $capableteach ?></td>
       	          </tr>
                  <tr>
                      <td bgcolor="#F7E2DD">Vocational Training :</td>
                      <td bgcolor="#EDEEF3">&nbsp;</td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD">Degree Subjects :</td>
                      <td bgcolor="#EDEEF3">&nbsp;</td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD">Teaching Class (if primary):</td>
                      <td bgcolor="#EDEEF3">&nbsp;</td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD">Special Trainer for Grade I,II :</td>
                      <td bgcolor="#EDEEF3"><select name="select4" class="select5" id="select4">
                        <option value="Y">Yes</option>
                        <option value="N">No</option>
                      </select></td>
                    </tr>
      	      </table></td>
      	    </tr>
        	  <tr>
        	    <td>&nbsp;</td>
      	    </tr>
        	  <tr>
        	    <td bgcolor="#FFFFFF"><strong>Current Timetable</strong></td>
      	    </tr>
        	  <tr>
        	    <td bgcolor="#EDEEF3"><table width="100%" cellspacing="1" cellpadding="1">
                        <tr>
                          <td width="36%" bgcolor="#CCCCCC"><strong>Subject\Grade</strong></td>
                          <td width="8%" align="center" bgcolor="#CCCCCC"><strong>6</strong></td>
                          <td width="8%" align="center" bgcolor="#CCCCCC"><strong>7</strong></td>
                          <td width="8%" align="center" bgcolor="#CCCCCC"><strong>8</strong></td>
                          <td width="8%" align="center" bgcolor="#CCCCCC"><strong>9</strong></td>
                          <td width="8%" align="center" bgcolor="#CCCCCC"><strong>10</strong></td>
                          <td width="8%" align="center" bgcolor="#CCCCCC"><strong>11</strong></td>
                          <td width="8%" align="center" bgcolor="#CCCCCC"><strong>12</strong></td>
                          <td width="8%" align="center" bgcolor="#CCCCCC"><strong>13</strong></td>
                        </tr>
                        <?php 
						$grandTotal6=$grandTotal7=$grandTotal8=$grandTotal9=$grandTotal10=$grandTotal11=$grandTotal12=$grandTotal13=0;
  						$sqlTeachSub = "SELECT [SubjectID] FROM [MOENational].[dbo].[TG_SchoolTimeTable] where TeacherID='$NICUser' and SchoolID='$loggedSchool' GROUP BY SubjectID";
						$stmtAdd = $db->runMsSqlQuery($sqlTeachSub);
						while ($row = sqlsrv_fetch_array($stmtAdd, SQLSRV_FETCH_ASSOC)) {
							$SubjectID=$row['SubjectID'];
							
						$sqlSub = "SELECT SubjectName FROM CD_Subject where SubCode='$SubjectID'";
						$stmtSub = $db->runMsSqlQuery($sqlSub);
						while ($rowSub = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
							$SubjectName=$rowSub['SubjectName'];
						}
							
						?>
                        <tr>
                          <td bgcolor="#FFFFFF"><?php echo $SubjectName ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NICUser' and TG_SchoolTimeTable.SchoolID='$loggedSchool' and TG_SchoolGrade.GradeTitle='6' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal6+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NICUser' and TG_SchoolTimeTable.SchoolID='$loggedSchool' and TG_SchoolGrade.GradeTitle='7' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal7+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NICUser' and TG_SchoolTimeTable.SchoolID='$loggedSchool' and TG_SchoolGrade.GradeTitle='8' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal8+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NICUser' and TG_SchoolTimeTable.SchoolID='$loggedSchool' and TG_SchoolGrade.GradeTitle='9' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal9+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NICUser' and TG_SchoolTimeTable.SchoolID='$loggedSchool' and TG_SchoolGrade.GradeTitle='10' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal10+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NICUser' and TG_SchoolTimeTable.SchoolID='$loggedSchool' and TG_SchoolGrade.GradeTitle='11' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal11+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NICUser' and TG_SchoolTimeTable.SchoolID='$loggedSchool' and TG_SchoolGrade.GradeTitle='12' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal12+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NICUser' and TG_SchoolTimeTable.SchoolID='$loggedSchool' and TG_SchoolGrade.GradeTitle='13' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal13+=$TotaRows;
						  ?></td>
                        </tr>
                        <?php }?>
                        <tr>
                          <td bgcolor="#CCCCCC"><strong>Total</strong></td>
                          <td align="center" bgcolor="#CCCCCC"><strong><?php echo $grandTotal6 ?></strong></td>
                          <td align="center" bgcolor="#CCCCCC"><strong><?php echo $grandTotal7 ?></strong></td>
                          <td align="center" bgcolor="#CCCCCC"><strong><?php echo $grandTotal8 ?></strong></td>
                          <td align="center" bgcolor="#CCCCCC"><strong><?php echo $grandTotal9 ?></strong></td>
                          <td align="center" bgcolor="#CCCCCC"><strong><?php echo $grandTotal10 ?></strong></td>
                          <td align="center" bgcolor="#CCCCCC"><strong><?php echo $grandTotal11 ?></strong></td>
                          <td align="center" bgcolor="#CCCCCC"><strong><?php echo $grandTotal12 ?></strong></td>
                          <td align="center" bgcolor="#CCCCCC"><strong><?php echo $grandTotal13 ?></strong></td>
                        </tr>
                </table></td>
      	    </tr>
        	  <tr>
        	    <td>&nbsp;</td>
      	    </tr>
        	  <tr>
        	    <td><strong>Transfer Details</strong></td>
      	    </tr>
        	  <tr>
        	    <td><table width="100%" cellspacing="1" cellpadding="1">
        	      <tr>
        	        <td width="23%" bgcolor="#F7E2DD">Request Type :</td>
        	        <td width="77%" bgcolor="#EDEEF3"><select name="TransferRequestType" class="select2a_n" id="TransferRequestType" onchange="showSchoolTransferDet()">
                      <option value="WZ" <?php if($TransferRequestType=='WZ'){?>selected="selected"<?php }?>>Within the zone</option>
                      <option value="OZ" <?php if($TransferRequestType=='OZ'){?>selected="selected"<?php }?>>Other zone</option>
                      <option value="OP" <?php if($TransferRequestType=='OP'){?>selected="selected"<?php }?>>Other province</option>
                      <option value="NS" <?php if($TransferRequestType=='NS'){?>selected="selected"<?php }?>>National school</option>
                      </select></td>
       	          </tr>
        	      <tr>
                      <td bgcolor="#F7E2DD"><div id="zoneSchoolLable">Current Zone :</div></td>
                      <td bgcolor="#EDEEF3"><div id="zoneSchool"><?php echo ucwords(strtolower(($zonename))); ?>
                      <input type="hidden" name="TransferRequestTypeID" value="<?php echo $ZoneCode ?>" /></div></td>
                    </tr>
        	      
                    <tr>
                      <td bgcolor="#F7E2DD">Expect School 1 :</td>
                      <td bgcolor="#EDEEF3"><div id="changeSchool"><select class="select2a_n" id="ExpectSchool" name="ExpectSchool">
                            <?php
                            $sql = "SELECT [InstType]
      ,[CenCode]
      ,[InstitutionName]
      
  FROM [dbo].[CD_CensesNo]
  Where IsNationalSchool='1'
  order by InstitutionName";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$ex1school=trim($row['CenCode']);
								$sclName=$row['InstitutionName'];
								$selTxt="";
								if($ExpectSchool==$ex1school)$selTxt="selected";
                                echo "<option value=\"$ex1school\" $selTxt>$sclName</option>";
                            }
                            ?>
                      </select></div></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#F7E2DD">Expect School 2 :</td>
                      <td bgcolor="#EDEEF3"><select class="select2a_n" id="ExpectSchool2" name="ExpectSchool2">
                            <?php
                            $sql = "SELECT [InstType]
      ,[CenCode]
      ,[InstitutionName]
      ,[DistrictCode]
      ,[RecordLog]
      ,[ZoneCode]
      ,[DivisionCode]
      ,[IsNationalSchool]
      ,[SchoolType]
  FROM [dbo].[CD_CensesNo]
  Where IsNationalSchool='1'
  order by InstitutionName";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$ex1school=trim($row['CenCode']);
								$sclName=$row['InstitutionName'];
								$selTxt="";
								if($ExpectSchool2==$ex1school)$selTxt="selected";
                                echo "<option value=\"$ex1school\" $selTxt>$sclName</option>";
                            }
                            ?>
                      </select></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#F7E2DD">Expect School 3 :</td>
                      <td bgcolor="#EDEEF3"><select class="select2a_n" id="ExpectSchool3" name="ExpectSchool3">
                            <?php
                            $sql = "SELECT [InstType]
      ,[CenCode]
      ,[InstitutionName]
      ,[DistrictCode]
      ,[RecordLog]
      ,[ZoneCode]
      ,[DivisionCode]
      ,[IsNationalSchool]
      ,[SchoolType]
  FROM [dbo].[CD_CensesNo]
  Where IsNationalSchool='1'
  order by InstitutionName";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$ex1school=trim($row['CenCode']);
								$sclName=$row['InstitutionName'];
								$selTxt="";
								if($ExpectSchool3==$ex1school)$selTxt="selected";
                                echo "<option value=\"$ex1school\" $selTxt>$sclName</option>";
                            }
                            ?>
                      </select></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#F7E2DD">Expect School 4 :</td>
                      <td bgcolor="#EDEEF3"><select class="select2a_n" id="ExpectSchool4" name="ExpectSchool4">
                            <?php
                            $sql = "SELECT [InstType]
      ,[CenCode]
      ,[InstitutionName]
      ,[DistrictCode]
      ,[RecordLog]
      ,[ZoneCode]
      ,[DivisionCode]
      ,[IsNationalSchool]
      ,[SchoolType]
  FROM [dbo].[CD_CensesNo]
  Where IsNationalSchool='1'
  order by InstitutionName";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$ex1school=trim($row['CenCode']);
								$sclName=$row['InstitutionName'];
								$selTxt="";
								if($ExpectSchool4==$ex1school)$selTxt="selected";
                                echo "<option value=\"$ex1school\" $selTxt>$sclName</option>";
                            }
                            ?>
                      </select></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#F7E2DD">Expect School 5 :</td>
                      <td bgcolor="#EDEEF3"><select class="select2a_n" id="ExpectSchool5" name="ExpectSchool5">
                            <?php
                            $sql = "SELECT [InstType]
      ,[CenCode]
      ,[InstitutionName]
      ,[DistrictCode]
      ,[RecordLog]
      ,[ZoneCode]
      ,[DivisionCode]
      ,[IsNationalSchool]
      ,[SchoolType]
  FROM [dbo].[CD_CensesNo]
  Where IsNationalSchool='1'
  order by InstitutionName";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$ex1school=trim($row['CenCode']);
								$sclName=$row['InstitutionName'];
								$selTxt="";
								if($ExpectSchool5==$ex1school)$selTxt="selected";
                                echo "<option value=\"$ex1school\" $selTxt>$sclName</option>";
                            }
                            ?>
                      </select></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#F7E2DD">Extra Activities :</td>
                      <td bgcolor="#EDEEF3"><div class="noofCharactor" >Select Multiple Activities by clicking with the &quot;Ctrl&quot; key .</div><select name="ExtraActivities[]" size="5" multiple="multiple" class="textarea1" id="ExtraActivities[]">
           <?php
            $iAvailTolArr = explode(',',$ExtraActivities);
			$sqlMS = "SELECT * FROM TG_TeacherExtraActivity where ActivityTitle!=''";
			$stmtMS = $db->runMsSqlQuery($sqlMS);
			while ($row = sqlsrv_fetch_array($stmtMS, SQLSRV_FETCH_ASSOC)) {
				$ActivityTitle=$row['ActivityTitle'];
				$ActivityID=$row['ID'];?>
				
			<option value="<?php echo $ActivityID ?>"
            <?php
				for($n=0;$n<count($iAvailTolArr);$n++){ 
					$SelectedKeywordca=trim($iAvailTolArr[$n]);
						if($ActivityID==$SelectedKeywordca){
							echo 'selected="selected"';
						}
						else{
							echo "";
						}
				}
            ?>
            ><?php echo $ActivityTitle; ?></option>
            <?php }?>

            </select></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#F7E2DD">Reason for Transfer :</td>
                      <td bgcolor="#EDEEF3"><textarea name="ReasonForTransfer" cols="85" rows="5" class="textarea1auto" id="ReasonForTransfer"><?php echo $ReasonForTransfer ?></textarea></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD">Would like to Work Another School :</td>
                      <td bgcolor="#EDEEF3"><table width="30%" cellspacing="1" cellpadding="1">
                        <tr>
                          <td width="11%"><input type="radio" name="LikeToOtherSchool" id="radio" value="Y" <?php if($LikeToOtherSchool=='Y' || $fm=='' || $fm=='A'){?>checked="checked"<?php }?>/></td>
                          <td width="29%">Yes</td>
                          <td width="11%"><input type="radio" name="LikeToOtherSchool" id="radio2" value="N" <?php if($LikeToOtherSchool=='N'){?>checked="checked"<?php }?>/></td>
                          <td width="49%">No</td>
                        </tr>
                      </table></td>
                    </tr>
       	        </table></td>
      	    </tr>
        	  <tr>
        	    <td>&nbsp;</td>
      	    </tr>
        	  <tr>
        	    <td><table width="100%" cellspacing="1" cellpadding="1">
        	      <tr>
        	        <td width="23%">&nbsp;</td>
        	        <td width="77%"><input type="hidden" name="cat" value="<?php echo $cat ?>" />
                      <input type="hidden" name="tblName" value="<?php echo $tblName ?>" />
                      <input type="hidden" name="TransferType" value="<?php echo $TransferType ?>" />
                      <input type="hidden" name="AED" value="<?php echo $fm ?>" />
                      <input type="hidden" name="vID" value="<?php echo $id ?>" />
                      <?php if($fm!='V'){?> <input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value=""/><?php }?></td>
      	        </tr>
      	      </table></td>
      	    </tr>
        	  <tr>
                  <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                    
                    <?php if($id!=''){?>
                    <tr>
                        <td colspan="2" ><span style="font-size:20px; font-weight:bold">Approvals</span></td>
                  </tr>
                      <tr>
                        <td height="1" colspan="2" bgcolor="#CCCCCC" ></td>
                  </tr>
          <?php 
   $i=1;
   $sqlLeave="SELECT        TG_Request_Approve.id AS ReqAppID, TG_Request_Approve.RequestUserNIC, TG_Request_Approve.ApprovelUserNIC, TG_Request_Approve.ApproveUserNominatorNIC, 
                         TG_Request_Approve.ApproveProcessOrder, TG_Request_Approve.ApprovedStatus, TG_Request_Approve.DateTime, TG_Request_Approve.Remarks, 
                         TeacherMast.SurnameWithInitials, TG_ApprovalProcess.ApproveAccessRoleName
FROM            TG_Request_Approve INNER JOIN
                         TeacherMast ON TG_Request_Approve.ApprovelUserNIC = TeacherMast.NIC INNER JOIN
                         TG_ApprovalProcess ON TG_Request_Approve.ApprovalProcessID = TG_ApprovalProcess.ID
WHERE        (TG_Request_Approve.RequestType = 'TransferTeacherNational') AND (TG_Request_Approve.RequestID = '$id')
ORDER BY TG_Request_Approve.ApproveProcessOrder";
$TotaRows=$db->rowCount($sqlLeave);
   $stmt = $db->runMsSqlQuery($sqlLeave);
                            while ($rowas = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					  
					  //$Expr1=$row['id'];
					  $ApproveAccessRoleName=trim($rowas['ApproveAccessRoleName']);
					  $SurnameWithInitials=trim($rowas['SurnameWithInitials']);
					  $Remarks=trim($rowas['Remarks']);
					  $ApprovelUserNIC=trim($rowas['ApprovelUserNIC']);
					  $ReqAppID=$rowas['ReqAppID'];
					  $ApproveProcessOrder=$rowas['ApproveProcessOrder'];
					  $statName="";
					  
							$ApprovedStatus=trim($rowas['ApprovedStatus']);//echo "hi";
							//if($ApprovedStatus=='P')
							if($ApprovedStatus=='P' || $ApprovedStatus==''){
								$statName="Pending";
							}else if($ApprovedStatus=='A'){
								$statName="Approved";
							}else if($ApprovedStatus=='R'){
								$statName="Rejected";
							}
							
						
					  ?>
			  <tr>
                  <td colspan="2" ><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="1%" height="30" align="center"><img src="images/re_enter.png" width="10" height="10" /></td>
                      <td colspan="2"><?php echo $ApproveAccessRoleName ?> - <?php echo $SurnameWithInitials ?></td>
                    </tr>
                     <?php if($ApproveProcessOrder=='1'){
						 
						 $countTotal="SELECT        TG_SchoolSummary.ID, TG_SchoolSummary.SchoolID, TG_SchoolSummary.TotalNoofStudents, TG_SchoolSummary.Grade1t5Classes, 
                         TG_SchoolSummary.Grade6t11Classes, TG_SchoolSummary.ScienceClasses, TG_SchoolSummary.CommerceClasses, TG_SchoolSummary.ArtClasses, 
                         TG_SchoolSummary.Grade1t5Students, TG_SchoolSummary.Grade6t11Students, TG_SchoolSummary.ScienceStudents, TG_SchoolSummary.CommerceStudents, 
                         TG_SchoolSummary.ArtStudents, TG_SchoolSummary.GradeFrom, TG_SchoolSummary.GradeTo, CD_CensesNo.InstitutionName
FROM            TG_SchoolSummary INNER JOIN
                         CD_CensesNo ON TG_SchoolSummary.SchoolID = CD_CensesNo.CenCode
WHERE        (TG_SchoolSummary.SchoolID = '$SchoolID')";

$stmtTG = $db->runMsSqlQuery($countTotal);
while ($row = sqlsrv_fetch_array($stmtTG, SQLSRV_FETCH_ASSOC)) {
	$InstitutionName=$row['InstitutionName'];
	$TotalNoofStudents=$row['TotalNoofStudents'];
	$Grade1t5Classes=$row['Grade1t5Classes'];
	$Grade6t11Classes=$row['Grade6t11Classes'];
	$ScienceClasses=$row['ScienceClasses'];
	$CommerceClasses=$row['CommerceClasses'];
	$ArtClasses=$row['ArtClasses'];
	$Grade1t5Students=$row['Grade1t5Students'];
	$Grade6t11Students=$row['Grade6t11Students'];
	$ScienceStudents=$row['ScienceStudents'];
	$CommerceStudents=$row['CommerceStudents'];
	$ArtStudents=$row['ArtStudents'];
	$GradeFrom=$row['GradeFrom'];
	$GradeTo=$row['GradeTo'];
}

						 
						 ?>
                    <tr>
                       <td height="20">&nbsp;</td>
                       <td valign="top" bgcolor="#F7E2DD">Grade :</td>
                       <td align="left" valign="top" bgcolor="#EDEEF3">From <?php echo $GradeFrom ?> To <?php echo $GradeTo ?></td>
                    </tr>
                    <tr>
                      <td height="20">&nbsp;</td>
                      <td valign="top" bgcolor="#F7E2DD">Number of Students :</td>
                      <td align="left" valign="top" bgcolor="#EDEEF3"><?php echo $TotalNoofStudents ?></td>
                    </tr>
                    <tr>
                      <td height="20">&nbsp;</td>
                      <td valign="top" bgcolor="#F7E2DD">Teachers Summary :</td>
                      <td align="left" valign="top" bgcolor="#EDEEF3"><table width="100%" cellspacing="1" cellpadding="1">
                        <tr>
                          <td width="46%" bgcolor="#CCCCCC">Type</td>
                          <td width="15%" align="center" bgcolor="#CCCCCC">Need</td>
                          <td width="15%" align="center" bgcolor="#CCCCCC">Available</td>
                          <td width="12%" align="center" bgcolor="#CCCCCC">Less</td>
                          <td width="12%" align="center" bgcolor="#CCCCCC">More</td>
                        </tr>
                        <?php 
						$sqlFA = "SELECT        TG_SchoolTeacherTypeWise.ID, TG_SchoolTeacherTypeWise.SchoolID, TG_SchoolTeacherTypeWise.TeacherNeed, TG_SchoolTeacherTypeWise.TeacherAvailable, 
                         TG_TeachersType.Title
FROM            TG_SchoolTeacherTypeWise INNER JOIN
                         TG_TeachersType ON TG_SchoolTeacherTypeWise.TeacherTypeID = TG_TeachersType.ID
WHERE        (TG_SchoolTeacherTypeWise.SchoolID = '$SchoolID')";
						$stmtFA = $db->runMsSqlQuery($sqlFA);
						
							while ($rowas = sqlsrv_fetch_array($stmtFA, SQLSRV_FETCH_ASSOC)) {
						//$Expr1=$row['id'];
							$Title=trim($rowas['Title']);
							$TeacherNeed=trim($rowas['TeacherNeed']);
							$TeacherAvailable=trim($rowas['TeacherAvailable']);
							
							$balanceTeachOver=$balanceTeachUnder=0;
					 		$balanceTeachUnder=$TeacherNeed-$TeacherAvailable;
					  		if($balanceTeachOver<0)$balanceTeachOver=$TeacherAvailable-$TeacherNeed;
					  ?>
                        <tr>
                          <td bgcolor="#FFFFFF"><?php echo $Title ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php echo $TeacherNeed ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php echo $TeacherAvailable ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php echo $balanceTeachUnder ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php echo $balanceTeachOver ?></td>
                        </tr>
                        <?php }?>
                      </table></td>
                    </tr>
                    <tr>
                      <td height="20">&nbsp;</td>
                      <td valign="top" bgcolor="#F7E2DD">School Summary :</td>
                      <td align="left" valign="top" bgcolor="#EDEEF3"><table width="100%" cellspacing="1" cellpadding="1">
                        <tr>
                          <td bgcolor="#CCCCCC">Grade</td>
                          <td bgcolor="#CCCCCC">No. of Classes</td>
                          <td bgcolor="#CCCCCC">No. of Students</td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFFFFF">Grade 1-5</td>
                          <td bgcolor="#FFFFFF"><?php echo $Grade1t5Classes ?></td>
                          <td bgcolor="#FFFFFF"><?php echo $Grade1t5Students ?></td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFFFFF">Grade 6-11</td>
                          <td bgcolor="#FFFFFF"><?php echo $Grade6t11Classes ?></td>
                          <td bgcolor="#FFFFFF"><?php echo $Grade6t11Students ?></td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFFFFF">Science</td>
                          <td bgcolor="#FFFFFF"><?php echo $ScienceClasses ?></td>
                          <td bgcolor="#FFFFFF"><?php echo $ScienceStudents ?></td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFFFFF">Commerce</td>
                          <td bgcolor="#FFFFFF"><?php echo $CommerceClasses ?></td>
                          <td bgcolor="#FFFFFF"><?php echo $CommerceStudents ?></td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFFFFF">Art</td>
                          <td bgcolor="#FFFFFF"><?php echo $ArtClasses ?></td>
                          <td bgcolor="#FFFFFF"><?php echo $ArtStudents ?></td>
                        </tr>
                      </table></td>
                    </tr>
                    <?php }?>
                    <?php 
                      if($ApprovelUserNIC==$nicNO){}else{?>
                    <tr>
                      <td height="20">&nbsp;</td>
                      <td valign="top" bgcolor="#F7E2DD">Release :</td>
                      <td align="left" valign="top" bgcolor="#EDEEF3"><?php echo $Remarks;?></td>
                    </tr>
                    <tr>
                      <td height="20" width="1%">&nbsp;</td>
                      <td width="20%" valign="top" bgcolor="#F7E2DD">Approvel Status :</td>
                      <td align="left" valign="top" bgcolor="#EDEEF3"><?php  echo $statName;?></td>
                    </tr>
                    
                    <?php }?>
                    
                  </table></td>
          </tr>
          
                <tr>
                  <td width="27%">&nbsp;</td>
                  <td width="73%">&nbsp;</td>
                </tr>
                <?php }?>
                
                <?php }?>
                    </table></td>
        	  </tr>
              </table>