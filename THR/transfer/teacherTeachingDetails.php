<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 

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
		
		/* $sqlTG = "SELECT * FROM CD_Service where ServCode='$ServiceTypeCode'";
		$stmtTG = $db->runMsSqlQuery($sqlTG);
		while ($row = sqlsrv_fetch_array($stmtTG, SQLSRV_FETCH_ASSOC)) {
			$ServiceName=$row['ServiceName'];
		  } *///Define on staffPersonalDetails.php
		  
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
		
	}
	
?>

<table width="945" cellpadding="0" cellspacing="0">
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
        	  </table>