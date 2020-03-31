<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
//$nicNO='722381718V';
$sqlChkNo = "SELECT id FROM TG_Request_Approve WHERE (ApproveUserNominatorNIC = N'$nicNO') AND (RequestType = 'TransferPrincipleNormal')";
	$totNominiRow = $db->rowCount($sqlChkNo);
	if($totNominiRow>0){
	  $tblField =  'ApproveUserNominatorNIC';
	}else{
	  $tblField = 'ApprovelUserNIC';
	}
	
$approvSql="SELECT        TG_PrincipleTransfer.TransferType, TG_PrincipleTransfer.ID, TG_PrincipleTransfer.TransferRequestType, TG_PrincipleTransfer.ExpectSchool, 
                         TG_PrincipleTransfer.LikeToOtherSchool, TG_PrincipleTransfer.ReasonForTransfer, TG_PrincipleTransfer.ExtraActivities, CONVERT(varchar(20),TG_PrincipleTransfer.RequestedDate,121) AS RequestedDate, 
                         TG_PrincipleTransfer.IsApproved, TG_Request_Approve.RequestType, TG_Request_Approve.ApprovelUserNIC, TG_Request_Approve.ApproveUserNominatorNIC, 
                         TeacherMast.SurnameWithInitials, CD_CensesNo.InstitutionName, TG_Request_Approve.ApprovedStatus
FROM            TG_PrincipleTransfer INNER JOIN
                         TG_Request_Approve ON TG_PrincipleTransfer.ID = TG_Request_Approve.RequestID INNER JOIN
                         TeacherMast ON TG_PrincipleTransfer.NIC = TeacherMast.NIC INNER JOIN
                         CD_CensesNo ON TG_PrincipleTransfer.SchoolID = CD_CensesNo.CenCode
WHERE        (TG_Request_Approve.RequestType = 'TransferPrincipleNormal') AND (TG_Request_Approve.$tblField = N'$NICUser') AND 
                         (TG_Request_Approve.ApprovedStatus = N'P')";

$TotaRows=$db->rowCount($approvSql);
?>

    <form method="post" action="transferAction.php" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if($msg!='' || $_SESSION['success_update']!=''){//if( || $_SESSION['success_update']!=''){  ?>   
          <div class="mcib_middle_full">
          <div class="form_error"><?php echo $msg; echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
        </div>
        <?php }?> 
         <div style="width:738px; float:left;">
		<?php if($id==''){?>
        <table width="100%" cellpadding="0" cellspacing="0">
       
        	<tr>
                  <td><?php echo $TotaRows ?> Record(s) found.</td>
                  <td>&nbsp;</td>
                </tr>
			  <tr>
                  <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="3%" height="25" align="center" bgcolor="#999999">#</td>
                      <td width="28%" align="center" bgcolor="#999999">Employee Name</td>
                      <td width="19%" align="center" bgcolor="#999999">School</td>
                      <td width="11%" align="center" bgcolor="#999999">Request Date</td>
                      <td width="11%" align="center" bgcolor="#999999">Status</td>
                      <td width="9%" align="center" bgcolor="#999999">Action</td>
                    </tr>
                    <?php 
					$i=1;
					$stmt = $db->runMsSqlQuery($approvSql);
                     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$RequestID=$row['ID'];
					?>
                    <tr>
                      <td height="20" bgcolor="#FFFFFF"><?php echo $i++; ?></td>
                      <td bgcolor="#FFFFFF"><?php echo $row['SurnameWithInitials']; ?></td>
                      <td bgcolor="#FFFFFF"><?php echo $row['InstitutionName']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['RequestedDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php //echo $row['FromDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><a href="<?php echo $ttle ?>-<?php echo $pageid ?>--<?php echo $RequestID ?>.html"><img src="images/more_info.png" /></a></td>
                    </tr>
                   <?php }?>
                  </table></td>
          </tr>
         
                <tr>
                  <td width="56%">&nbsp;</td>
                  <td width="44%">&nbsp;</td>
                </tr>
          
              </table> <?php }?>
              
        <?php if($id!=''){
			
			$sqlTransf="SELECT        TransferType, TransferRequestType, ExpectSchool, LikeToOtherSchool, ReasonForTransfer, ExtraActivities, RequestedDate, IsApproved, SchoolID, NIC
FROM            TG_PrincipleTransfer
WHERE        (ID = '$id')";

			$stmtTr = $db->runMsSqlQuery($sqlTransf);
			while ($row = sqlsrv_fetch_array($stmtTr, SQLSRV_FETCH_ASSOC)) {
				$TransferType=$row['TransferType'];
				$TransferRequestType=trim($row['TransferRequestType']);
				$ExpectSchool=trim($row['ExpectSchool']);
				$LikeToOtherSchool=$row['LikeToOtherSchool'];
				$ReasonForTransfer=$row['ReasonForTransfer'];
				$ExtraActivities=$row['ExtraActivities'];
				$RequestedDate=$row['RequestedDate'];
				$IsApproved=$row['IsApproved'];
				$SchoolID=$row['SchoolID'];
				$NIC=$row['NIC'];
				
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
					
		////End form values
			
			
			?>
        <table width="100%" cellpadding="0" cellspacing="0">
        
			  <tr>
			    <td colspan="2" ><table width="100%" cellspacing="2" cellpadding="2">
                    <tr>
                      <td bgcolor="#F7E2DD">Working School :</td>
                      <td bgcolor="#EDEEF3"><?php echo $InstitutionName ?></td>
                    </tr>
                    <tr>
                      <td width="21%" bgcolor="#F7E2DD">Request Type :</td>
                      <td width="79%" bgcolor="#EDEEF3"><?php if($TransferRequestType=="WZ")echo "Within the zone";
					  if($TransferRequestType=='OZ')echo "Other zone";
					  if($TransferRequestType=='OP')echo "Other province";
					  if($TransferRequestType=='NS')echo "National school";
					  ?></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD"><?php echo $lableZne ?> :</td>
                      <td bgcolor="#EDEEF3"><?php echo ucwords(strtolower(($zonename))); ?></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD">Full Name :</td>
                      <td bgcolor="#EDEEF3"><?php echo ucwords(strtolower(($FullName))); ?></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD">Name with Initials :</td>
                      <td bgcolor="#EDEEF3"><?php echo ucwords(strtolower(($SurnameWithInitials))); ?></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD">Gender :</td>
                      <td bgcolor="#EDEEF3"><?php if($GenderCode==1){echo "Male";}else if($GenderCode==2){echo "Female";}else {echo "N/A";}?><!--<select name="select2" class="select5" id="select2">
                        <option value="1" >Male</option>
                        <option value="2">Female</option>
                      </select>--></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD">Permanent Address :</td>
                      <td bgcolor="#EDEEF3"><?php echo $Address ?></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD">Marital Status :</td>
                      <td bgcolor="#EDEEF3"><?php echo $CivilStatusName ?><!--<select name="select2" class="select5" id="select2">
                        <option value="WZ">Married</option>
                        <option value="OZ">Un-Married</option>
                      </select>--></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD">Date of Birth :</td>
                      <td bgcolor="#EDEEF3"><?php echo $DOB; ?><!--<table width="100%" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="13%"><input name="DOB" type="text" class="input3new" id="DOB" value="<?php echo $DOB; ?>" size="10" style="height:20px; line-height:20px;" readonly/>
                      </td>
                            <td width="87%">
                      <input name="f_trigger_d" type="image" id="f_trigger_d" src="../cms/images/calender_icon.gif" align="top" width="16" height="16"  />
                  <script type="text/javascript">
                            //2005-10-03 11:46:00 
                                Calendar.setup({
                                inputField     :    "DOB",      // id of the input field
                                ifFormat       :    "%Y-%m-%d",       // format of the input field
                                showsTime      :    false,            // will display a time selector
                                button         :    "f_trigger_d",   // trigger for the calendar (button ID)
                                singleClick    :    true,           // double-click mode
                                step           :    1                // show all years in drop-down boxes (instead of every other year as default)
                            });
                          </script>
                </td>
                          </tr>
                      </table>--></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD">Age up to <?php echo $todate=date('Y-12-31'); ?> :</td>
                      <td bgcolor="#EDEEF3"><?php echo calculateCurrentAge($DOB,$todate); ?></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD">Reqistration Number :</td>
                      <td bgcolor="#EDEEF3">&nbsp;</td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD">Teaching Grade :</td>
                      <td bgcolor="#EDEEF3"><?php echo $ServiceName ?></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD">Education Qualifications :</td>
                      <td bgcolor="#EDEEF3">
                      <?php 
					  
$sqlEQ = "SELECT        StaffQualification.ID, StaffQualification.NIC, CD_Qualif.Description
FROM            StaffQualification INNER JOIN
                         CD_Qualif ON StaffQualification.QCode = CD_Qualif.Qcode
WHERE        (StaffQualification.NIC = '$NIC')";
$stmtEQ = $db->runMsSqlQuery($sqlEQ);
while ($row = sqlsrv_fetch_array($stmtEQ, SQLSRV_FETCH_ASSOC)) {
	echo $Description=$row['Description']." ,";
}
					  ?>
                      </td>
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
                      <td bgcolor="#F7E2DD">1st Appointment Date :</td>
                      <td bgcolor="#EDEEF3"><?php echo $firstAppDate ?></td>
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
                      <td bgcolor="#EDEEF3"><?php echo $currentAppDate;?></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD">Current School Name :</td>
                      <td bgcolor="#EDEEF3"><?php echo $InstitutionName ?></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD">Current School Address :</td>
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
                    <tr>
                      <td align="left" valign="top" bgcolor="#F7E2DD">Teaching Qualifications :</td>
                      <td align="left" bgcolor="#EDEEF3"><table width="100%" cellspacing="1" cellpadding="1">
                        <tr>
                          <td width="15%" bgcolor="#CCCCCC">Appointment</td>
                          <td width="85%" bgcolor="#FFFFFF"><?php echo $appointmnt;?></td>
                        </tr>
                        <tr>
                          <td bgcolor="#CCCCCC">Teaching</td>
                          <td bgcolor="#FFFFFF"><?php echo $tching; ?></td>
                        </tr>
                        <tr>
                          <td bgcolor="#CCCCCC">Capable</td>
                          <td bgcolor="#FFFFFF"><?php echo $capableteach;?></td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top" bgcolor="#F7E2DD">Teacher Timetable :</td>
                      <td align="right" bgcolor="#EDEEF3"><table width="100%" cellspacing="1" cellpadding="1">
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
  						$sqlTeachSub = "SELECT [SubjectID] FROM [MOENational].[dbo].[TG_SchoolTimeTable] where TeacherID='$NIC' and SchoolID='$SchoolID' GROUP BY SubjectID";
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
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NIC' and TG_SchoolTimeTable.SchoolID='$SchoolID' and TG_SchoolGrade.GradeTitle='6' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal6+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NIC' and TG_SchoolTimeTable.SchoolID='$SchoolID' and TG_SchoolGrade.GradeTitle='7' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal7+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NIC' and TG_SchoolTimeTable.SchoolID='$SchoolID' and TG_SchoolGrade.GradeTitle='8' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal8+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NIC' and TG_SchoolTimeTable.SchoolID='$SchoolID' and TG_SchoolGrade.GradeTitle='9' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal9+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NIC' and TG_SchoolTimeTable.SchoolID='$SchoolID' and TG_SchoolGrade.GradeTitle='10' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal10+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NIC' and TG_SchoolTimeTable.SchoolID='$SchoolID' and TG_SchoolGrade.GradeTitle='11' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal11+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NIC' and TG_SchoolTimeTable.SchoolID='$SchoolID' and TG_SchoolGrade.GradeTitle='12' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal12+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NIC' and TG_SchoolTimeTable.SchoolID='$SchoolID' and TG_SchoolGrade.GradeTitle='13' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
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
                      <td valign="top" bgcolor="#F7E2DD">Extra Activities :</td>
                      <td bgcolor="#EDEEF3"><?php echo $ActivityTitle; ?></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD">Expect School/Province :</td>
                      <td bgcolor="#EDEEF3"><?php echo $requestedSchool; ?></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#F7E2DD">Reason for Transfer :</td>
                      <td bgcolor="#EDEEF3"><?php echo $ReasonForTransfer ?></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD">Would like to Work Another School :</td>
                      <td bgcolor="#EDEEF3"><?php echo $workOtherSchool ?></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD">Spouse Occupation :</td>
                      <td bgcolor="#EDEEF3"><?php echo $PositionName; ?></td>
                    </tr>
                    <tr>
                      <td bgcolor="#F7E2DD">Spouse Occupation Address :</td>
                      <td bgcolor="#EDEEF3"><?php echo $SpouseOfficeAddr; ?></td>
                    </tr>
                    
                    <tr>
                      <td bgcolor="#F7E2DD">Spouse Letter() :</td>
                      <td bgcolor="#EDEEF3"></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#F7E2DD">Chiildren Below 5 Years :</td>
                      <td bgcolor="#EDEEF3"><?php 
					  $sqlFA = "SELECT ChildName,CONVERT(varchar(20),DOB,121) AS ChildDOB FROM StaffChildren where NIC='$NIC'";
$stmtFA = $db->runMsSqlQuery($sqlFA);


					  ?><table width="100%" cellspacing="1" cellpadding="1">
                        <tr>
                          <td bgcolor="#CCCCCC"><strong>Name</strong></td>
                          <td bgcolor="#CCCCCC"><strong>DOB</strong></td>
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
WHERE        (StaffServiceHistory.NIC = '$NIC') ORDER BY StaffServiceHistory.AppDate ASC";
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
                    <!--
                    <tr>
                      <td valign="top" bgcolor="#F7E2DD">&nbsp;</td>
                      <td bgcolor="#EDEEF3">&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td><input type="hidden" name="cat" value="transferTeacher" />
                      <input type="hidden" name="tblName" value="TG_TeacherTransfer" />
                      <input type="hidden" name="TransferType" value="TTR" />
                      <input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value=""/></td>
                    </tr>
                    -->
                    </table></td>
	      </tr>
			  <tr>
			    <td colspan="2" >&nbsp;</td>
	      </tr>
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
WHERE        (TG_Request_Approve.RequestType = 'TransferPrincipleNormal') AND (TG_Request_Approve.RequestID = '$id')
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
							
						//}//echo $statName;
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
                      if($ApprovelUserNIC==$nicNO){?>
                    <tr>
                      <td height="20">&nbsp;</td>
                      <td valign="top" bgcolor="#F7E2DD">Release :</td>
                      <td valign="top" bgcolor="#EDEEF3"><select name="Remarks" class="select2a_new" id="Remarks">
                        <option value="P">With Replacement</option>
                        <option value="A">Without Replacement</option>
                      </select></td>
                    </tr>
                    <tr>
                      <td height="20" width="1%">&nbsp;</td>
                      <td width="20%" valign="top" bgcolor="#F7E2DD">Approvel Status :<?php //echo $ApprovelUserNIC;echo "_";echo $nicNO;echo "_"; ?></td>
                      <td valign="top" bgcolor="#EDEEF3">
                        <select name="ApprovedStatus" class="select2a_new" id="ApprovedStatus">
                        <option value="P">Pending</option>
                        <option value="A">Approve</option>
                        <option value="R">Reject</option>
                      </select>
                      </td>
                    </tr>
                    <tr>
                      <td height="20">&nbsp;</td>
                      <td valign="top" bgcolor="#F7E2DD">&nbsp;</td>
                      <td valign="top" bgcolor="#EDEEF3"><input type="hidden" value="<?php echo $ReqAppID ?>" name="ReqAppID" id="ReqAppID" />
                      <input type="hidden" value="<?php echo $id ?>" name="TransferID" id="TransferID" />
                      <input type="hidden" value="TransferPrincipleNormal" name="cat" />
                      <input type="hidden" value="<?php echo $ApproveProcessOrder ?>" name="ApproveProcessOrder" />
                      <input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/submit.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                    </tr>
                    
                   
					<?php }else{?>
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
                  <td width="56%">&nbsp;</td>
                  <td width="44%">&nbsp;</td>
                </tr>
                <?php }?>
              </table>        
        <?php }?>
              </div>
    
    </form>