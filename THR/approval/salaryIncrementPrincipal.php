<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
//$nicNO='722381718V';

$sqlChkNo = "SELECT id FROM TG_Approval WHERE (ApproveDesignationCode = N'$accLevel') AND (ApproveInstCode = N'$loggedSchool') AND (RequestType = 'PrincipalIncrement')";
	$totNominiRow = $db->rowCount($sqlChkNo);
	if($totNominiRow>0){
	  $tblField =  'ApproveDesignationCode';
	}else{
	  $tblField = 'ApproveDesignationNominiCode';
	}

  
$approvSql="SELECT        TG_Approval.id AS ReqAppID, TG_Approval.RequestID, TG_Approval.RequestType, TG_Approval.ApproveDesignationCode, 
                         TG_Approval.ApproveDesignationNominiCode, TG_Approval.ApprovedStatus, CONVERT(varchar(20),TG_Approval.DateTime,121) AS DateTime, 
                         TG_Approval.Remarks, TG_IncrementRequest.NIC, TG_IncrementRequest.ServCode, TG_IncrementRequest.SalaryScale, TG_IncrementRequest.CurrentSalaryStep, CONVERT(varchar(20),TG_IncrementRequest.EffectiveDate,121) AS EffectiveDate , CONVERT(varchar(20),TG_IncrementRequest.LastUpdate,121) AS LastUpdate,
                         TG_IncrementRequest.IsApproved , CD_CensesNo.InstitutionName
FROM            TG_IncrementRequest INNER JOIN
                         TG_Approval ON TG_IncrementRequest.ID = TG_Approval.RequestID 
						 INNER JOIN
                         CD_CensesNo ON TG_IncrementRequest.SchoolID = CD_CensesNo.CenCode
WHERE        (TG_Approval.RequestType = 'PrincipalIncrement') AND (TG_Approval.$tblField = N'$accLevel') AND (TG_Approval.ApproveInstCode = N'$loggedSchool') AND (TG_Approval.ApprovedStatus = N'P')";


$TotaRows=$db->rowCount($approvSql);

$uploadPath="../approval/trainingrequestfiles/";

?>

    <form method="post" action="incrementAction.php" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if($msg!='' || $_SESSION['success_update']!=''){//if( || $_SESSION['success_update']!=''){  ?>   
          <div class="mcib_middle_full">
          <div class="form_error"><?php echo $msg; echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
        </div>
        <?php }?> 
        <div style="width:738px; float:left;">
		<?php if($id==''){?>
        <table width="100%" cellpadding="0" cellspacing="0">
       
        	<tr>
                  <td height="25"><?php echo $TotaRows ?> Record(s) found.</td>
                <td>&nbsp;</td>
                </tr>
			  <tr>
                  <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="6%" height="25" align="center" bgcolor="#999999">#</td>
                      <td width="31%" align="center" bgcolor="#999999">Principal Name</td>
                      <td width="22%" align="center" bgcolor="#999999">School</td>
                      <td width="14%" align="center" bgcolor="#999999">Effective Date</td>
                      <td width="19%" align="center" bgcolor="#999999">Apply Date</td>
                      <td width="8%" align="center" bgcolor="#999999">Action</td>
                    </tr>
                    <?php 
					$i=1;
					$stmt = $db->runMsSqlQuery($approvSql);
                     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$NICApplicant=$row['NIC'];
						$RequestID=$row['RequestID'];//SurnameWithInitials,InstitutionName,EndDate,StartDate,Description,Title
						
						$sqlPers="SELECT        TeacherMast.ID, TeacherMast.NIC, TeacherMast.SurnameWithInitials, TeacherMast.FullName, TeacherMast.Title, TeacherMast.MobileTel, CONVERT(varchar(20), 
							 TeacherMast.DOB, 121) AS DOB, CD_nEthnicity.EthnicityName, CD_Religion.ReligionName, CD_Gender.[Gender Name], TeacherMast.emailaddr, CD_Title.TitleName,
							  TeacherMast.GenderCode, TeacherMast.EthnicityCode, TeacherMast.ReligionCode
	FROM            TeacherMast INNER JOIN
							 CD_Gender ON TeacherMast.GenderCode = CD_Gender.GenderCode INNER JOIN
							 CD_nEthnicity ON TeacherMast.EthnicityCode = CD_nEthnicity.Code INNER JOIN
							 CD_Religion ON TeacherMast.ReligionCode = CD_Religion.Code INNER JOIN
							 CD_Title ON TeacherMast.Title = CD_Title.TitleCode
	WHERE        (TeacherMast.NIC = N'$NICApplicant')";
	
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
												 CONVERT(varchar(20),StaffAddrHistory.AppDate,121) AS AppDate, CD_DSec.DSName, CD_Districts.DistName, CD_DSec.DSCode, CD_Districts.DistCode
						FROM            StaffAddrHistory INNER JOIN
												 CD_DSec ON StaffAddrHistory.DSCode = CD_DSec.DSCode INNER JOIN
												 CD_Districts ON StaffAddrHistory.DISTCode = CD_Districts.DistCode
						WHERE        (StaffAddrHistory.NIC = '$NICApplicant') AND (StaffAddrHistory.AddrType = N'PER')";
						
						$resAB = $db->runMsSqlQuery($sqlPerAdd);
						$rowAB = sqlsrv_fetch_array($resAB, SQLSRV_FETCH_ASSOC);
						$Address = $rowAB['Address'];
						$Tel = trim($rowAB['Tel']);
						$AppDate = $rowAB['AppDate'];
						$DSName = $rowAB['DSName'];
						$DistName = $rowAB['DistName'];
						$DSCode = trim($rowAB['DSCode']);
						$DistCode = trim($rowAB['DistCode']);
					

					?>
                    <tr>
                      <td height="20" bgcolor="#FFFFFF"><?php echo $i++; ?></td>
                      <td align="left" bgcolor="#FFFFFF"><?php echo $SurnameWithInitials; ?></td>
                      <td align="left" bgcolor="#FFFFFF"><?php echo $row['InstitutionName']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['EffectiveDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['LastUpdate']; ?></td>
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
			
$countTotal="SELECT        TG_IncrementRequest.NIC, TG_IncrementRequest.SalaryScale, TG_IncrementRequest.CurrentSalaryStep, CONVERT(varchar(20),TG_IncrementRequest.EffectiveDate, 121) AS EffectiveDate, CD_CensesNo.InstitutionName, CD_Service.ServiceName, 
                         TG_IncrementRequest.ID, TG_IncrementRequest.SchoolID,TG_IncrementRequest.AttachFile, TG_IncrementRequest.QuecAnswers
FROM            CD_CensesNo INNER JOIN
                         TG_IncrementRequest ON CD_CensesNo.CenCode = TG_IncrementRequest.SchoolID INNER JOIN
                         CD_Service ON TG_IncrementRequest.ServCode = CD_Service.ServCode
WHERE        (TG_IncrementRequest.ID = '$id')";//$NICUser

$stmt = $db->runMsSqlQuery($countTotal);
$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
$NICApply=$row['NIC'];
$SalaryScale=$row['SalaryScale'];
$CurrentSalaryStep=$row['CurrentSalaryStep'];
$EffectiveDateInc=$row['EffectiveDate'];
$InstitutionName=$row['InstitutionName'];
$ServiceName=$row['ServiceName'];
$SchoolID=$row['SchoolID'];
$AttachFile=$row['AttachFile'];
$QuecAnswers=$row['QuecAnswers'];

$sqlPers="SELECT        TeacherMast.ID, TeacherMast.NIC, TeacherMast.SurnameWithInitials, TeacherMast.FullName, TeacherMast.Title, TeacherMast.MobileTel, CONVERT(varchar(20), 
							 TeacherMast.DOB, 121) AS DOB, CD_nEthnicity.EthnicityName, CD_Religion.ReligionName, CD_Gender.[Gender Name], TeacherMast.emailaddr, CD_Title.TitleName,
							  TeacherMast.GenderCode, TeacherMast.EthnicityCode, TeacherMast.ReligionCode
	FROM            TeacherMast INNER JOIN
							 CD_Gender ON TeacherMast.GenderCode = CD_Gender.GenderCode INNER JOIN
							 CD_nEthnicity ON TeacherMast.EthnicityCode = CD_nEthnicity.Code INNER JOIN
							 CD_Religion ON TeacherMast.ReligionCode = CD_Religion.Code INNER JOIN
							 CD_Title ON TeacherMast.Title = CD_Title.TitleCode
	WHERE        (TeacherMast.NIC = N'$NICApply')";
	
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
						
						$sql1stAppDate="SELECT     CONVERT(varchar(10), 
							 StaffServiceHistory.AppDate, 121) AS AppDate
FROM         TeacherMast INNER JOIN
                      StaffServiceHistory ON TeacherMast.NIC = StaffServiceHistory.NIC
WHERE     (StaffServiceHistory.ServiceRecTypeCode = N'NA01') AND (TeacherMast.NIC=N'$NICApply')";
							$resAppDate = $db->runMsSqlQuery($sql1stAppDate);
							$rowAppd = sqlsrv_fetch_array($resAppDate, SQLSRV_FETCH_ASSOC);
							$AppDateFirst = $rowAppd['AppDate'];
						
						$sqlPerAdd="SELECT    StaffAddrHistory.Address, StaffAddrHistory.Tel, 
												 CONVERT(varchar(20),StaffAddrHistory.AppDate,121) AS AppDate, CD_DSec.DSName, CD_Districts.DistName, CD_DSec.DSCode, CD_Districts.DistCode
						FROM            StaffAddrHistory INNER JOIN
												 CD_DSec ON StaffAddrHistory.DSCode = CD_DSec.DSCode INNER JOIN
												 CD_Districts ON StaffAddrHistory.DISTCode = CD_Districts.DistCode
						WHERE        (StaffAddrHistory.NIC = '$NICApply') AND (StaffAddrHistory.AddrType = N'PER')";
						
						$resAB = $db->runMsSqlQuery($sqlPerAdd);
						$rowAB = sqlsrv_fetch_array($resAB, SQLSRV_FETCH_ASSOC);
						$Address = $rowAB['Address'];
						$Tel = trim($rowAB['Tel']);
						$AppDate = $rowAB['AppDate'];
						$DSName = $rowAB['DSName'];
						$DistName = $rowAB['DistName'];
						$DSCode = trim($rowAB['DSCode']);
						$DistCode = trim($rowAB['DistCode']);		

			
			?>
        <table width="100%" cellpadding="0" cellspacing="0">
        
			  <tr>
			    <td colspan="2" >&nbsp;</td>
	      </tr>
			  <tr>
			    <td colspan="2" style="border-bottom:1px; border-bottom-style:solid;"><strong>Request Details</strong></td>
	      </tr>
			  <tr>
			    <td colspan="2" ><table width="100%" cellspacing="1" cellpadding="1">
			      <tr>
			        <td>&nbsp;</td>
			        <td>&nbsp;</td>
			        <td colspan="4">&nbsp;</td>
		          </tr>
			      <tr>
			        <td width="21%" height="25">Applicant Name</td>
			        <td width="1%">:</td>
			        <td colspan="4"><?php echo $SurnameWithInitials ?></td>
		          </tr>
			      <tr>
			        <td height="25">Working School</td>
			        <td>:</td>
			        <td colspan="4"><?php echo $InstitutionName ?></td>
		          </tr>
			      <tr>
			        <td height="25">Personal Address</td>
			        <td>:</td>
			        <td colspan="4"><?php echo $Address ?></td>
		          </tr>
			      <tr>
			        <td height="25">NIC</td>
			        <td>:</td>
			        <td><?php echo $NICApply ?></td>
			        <td align="right">1st Appintment Date</td>
			        <td>:</td>
			        <td><?php echo $AppDateFirst ?></td>
		          </tr>
			      <tr>
			        <td height="25">Date Of Birth</td>
			        <td>:</td>
			        <td width="28%"><?php echo $DOB ?></td>
			        <td width="23%" align="right">Mobile Number</td>
			        <td width="1%">:</td>
			        <td width="26%"><?php echo $MobileTel ?></td>
		          </tr>
			      <tr>
			        <td height="25">Designation &amp; Grade</td>
			        <td>:</td>
			        <td><?php echo $ServiceName ?></td>
			        <td>&nbsp;</td>
			        <td>&nbsp;</td>
			        <td>&nbsp;</td>
		          </tr>
			      <tr>
			        <td valign="top">Education Qualification</td>
			        <td valign="top">:</td>
			        <td colspan="4" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="2%" align="center" valign="top" bgcolor="#FFFFFF"><strong>#</strong></td>
                      <td width="15%" align="left" valign="top" bgcolor="#FFFFFF"><strong>Qualification</strong></td>
                      <td width="33%" align="left" valign="top" bgcolor="#FFFFFF"><strong>Description</strong></td>
                      <td width="28%" align="left" valign="top" bgcolor="#FFFFFF"><strong>Subjects</strong></td>
                      <td width="12%" align="left" valign="top" bgcolor="#FFFFFF"><strong>Effective Date</strong></td>
                      </tr>
                    <?php 
					$i=1;
					$sql = "SELECT        StaffQualification.ID, StaffQualification.NIC, StaffQualification.QCode, CONVERT(varchar(20),StaffQualification.EffectiveDate, 121) AS EffectiveDate, StaffQualification.Reference, StaffQualification.LastUpdate, 
                         StaffQualification.UpdateBy, StaffQualification.RecordLog, CD_Qualif.Description, CD_QualificationCategory.Description AS Expr1
FROM            StaffQualification INNER JOIN
                         CD_Qualif ON StaffQualification.QCode = CD_Qualif.Qcode INNER JOIN
                         CD_QualificationCategory ON CD_Qualif.Category = CD_QualificationCategory.Code
WHERE        (StaffQualification.NIC = '$NICApply')
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
                      <td height="25" align="left" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                      <td align="left" bgcolor="#FFFFFF"><?php echo $catTitle ?></td>
                      <td align="left" bgcolor="#FFFFFF"><?php echo $Description ?></td>
                      <td align="left" bgcolor="#FFFFFF"><?php echo $SubjectName; ?></td>
                      <td align="left" bgcolor="#FFFFFF"><?php echo $EffectiveDate; ?></td>
                      </tr>
                    <?php }?>
                  </table></td>
		          </tr>
			      <tr>
			        <td height="25">Teaching Subject</td>
			        <td>:</td>
			        <td colspan="4"><?php 
					  $sqlList="SELECT * From TG_SchoolTimetableTeachersTemp where NIC='$NICApply' order by TeacherName";
					  
  $i=1;
   $stmt = $db->runMsSqlQuery($sqlList);
                      $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
					  echo substr($row['SubjectName'], 0, -1);
					 
					  ?></td>
		          </tr>
			      <tr>
			        <td height="25">Salary Scale</td>
			        <td>:</td>
			        <td colspan="4"><?php echo $SalaryScale ?></td>
		          </tr>
			      <tr>
			        <td height="25">Current Salary</td>
			        <td>:</td>
			        <td colspan="4"><?php echo $CurrentSalaryStep ?></td>
		          </tr>
			      <tr>
			        <td height="25" align="left" valign="top">Effective Date</td>
			        <td valign="top">:</td>
			        <td colspan="4"><?php echo $EffectiveDateInc ?></td>
		          </tr>
                  <tr>
			        <td height="25" align="left" valign="top">Service Letter</td>
			        <td valign="top">:</td>
			        <td colspan="4"><?php if($AttachFile){?><a href="<?php echo "../hr/incrementattachments/$AttachFile"; ?>" target="_blank">View</a><?php }else{echo "Not Available";}?></td>
		          </tr>
		        </table></td>
	      </tr>
			  <tr>
			    <td colspan="2" >&nbsp;</td>
	      </tr>
            <tr bgcolor="#3399FF">
              <td height="30" colspan="2" valign="middle" style="border-bottom: 1px; border-bottom-style: solid; font-size: 14px; color: #FFFFFF;">&nbsp;&nbsp;<strong>Take an Action</strong></td>
          </tr>
            <tr>
              <td valign="top">&nbsp;</td>
              <td valign="top">&nbsp;</td>
            </tr>
            

           <?php $sqlApp="SELECT        TG_Approval.ID, TG_Approval.RequestType, TG_Approval.RequestID, TG_Approval.ApproveInstCode, TG_Approval.ApproveDesignationCode, TG_Approval.ApproveDesignationNominiCode, 
                         TG_Approval.ApprovedStatus, TG_Approval.ApprovedByNIC, CONVERT(varchar(10),TG_Approval.DateTime,121) AS DateTime, TG_Approval.Remarks, CD_CensesNo.InstitutionName, CD_AccessRoles.AccessRole
FROM            TG_Approval INNER JOIN
                         CD_CensesNo ON TG_Approval.ApproveInstCode = CD_CensesNo.CenCode INNER JOIN
                         CD_AccessRoles ON TG_Approval.ApproveDesignationCode = CD_AccessRoles.AccessRoleValue 
						 WHERE (TG_Approval.RequestType = 'PrincipalIncrement') AND (TG_Approval.RequestID = '$id')
ORDER BY TG_Approval.id ASC";
						 
					$resABC = $db->runMsSqlQuery($sqlApp);
				
				$saveOk="N";
				$ApID="";
				while ($rowABC = sqlsrv_fetch_array($resABC, SQLSRV_FETCH_ASSOC)){
					$AccessRole= $rowABC['AccessRole'];
					$InstitutionName= $rowABC['InstitutionName'];
					$ApproveInstCode= trim($rowABC['ApproveInstCode']);
					$ApproveDesignationCode= trim($rowABC['ApproveDesignationCode']);
					$ApproveDesignationNominiCode= trim($rowABC['ApproveDesignationNominiCode']);
					$ApprovedStatus= trim($rowABC['ApprovedStatus']);
					$IDApp= trim($rowABC['ID']);
					$Remarks= trim($rowABC['Remarks']);
					$DateTime= trim($rowABC['DateTime']);
					//echo $accLevel;
					$activate="N";
					
					//echo "-$ApproveInstCode-";echo "<br>";
					//echo "-$loggedSchool-";echo "<br>";
					//echo $loggedSchool;echo "<br>";
					if($ApproveInstCode==$loggedSchool and ($ApproveDesignationCode==$accLevel || $ApproveDesignationNominiCode==$accLevel)){
						$saveOk="Y";
						$activate="Y";
						$ApID=$IDApp;
					}
					
					$sqlEmpDes="SELECT        TeacherMast.ID, TeacherMast.NIC, TeacherMast.SurnameWithInitials, CD_AccessRoles.AccessRoleValue, CD_CensesNo.InstitutionName, CD_CensesNo.CenCode, CD_AccessRoles.AccessRole
FROM            CD_CensesNo INNER JOIN
                         StaffServiceHistory ON CD_CensesNo.CenCode = StaffServiceHistory.InstCode INNER JOIN
                         TeacherMast INNER JOIN
                         Passwords ON TeacherMast.NIC = Passwords.NICNo INNER JOIN
                         CD_AccessRoles ON Passwords.AccessLevel = CD_AccessRoles.AccessRoleValue ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
WHERE        (CD_AccessRoles.AccessRoleValue = '$ApproveDesignationCode') AND (CD_CensesNo.CenCode = N'$ApproveInstCode') AND (StaffServiceHistory.ServiceRecTypeCode != 'RT01' or StaffServiceHistory.ServiceRecTypeCode IS NULL)";
					$resED = $db->runMsSqlQuery($sqlEmpDes);
					$rowED = sqlsrv_fetch_array($resED, SQLSRV_FETCH_ASSOC);
					$SurnameWithInitialsED= $rowED['SurnameWithInitials'];
					
						 ?>
                            <?php if($i==1){?>
             <tr>
			    <td colspan="2" bgcolor="#CCCCCC" ><table width="100%" cellspacing="1" cellpadding="1">
                <tr>
                <td height="25" align="center" bgcolor="#FFFFFF"><strong>#</strong></td>
                <td align="center" bgcolor="#FFFFFF"><strong>Task</strong></td>
                <td align="center" bgcolor="#FFFFFF"><strong>Yes</strong></td>
                <td align="center" bgcolor="#FFFFFF"><strong>No</strong></td>
              </tr>
            <?php 
			$exQa=explode(",",$QuecAnswers);
			$x=0;
			$sql="SELECT [ID]
      ,[QuestionType]
      ,[QuestionInc]
      ,[OrderID]
      ,[RecordLog]
  FROM [dbo].[CD_TG_IncrementQuestions] WHERE QuestionType='Principal' ORDER BY OrderID ASC";
			$stmt = $db->runMsSqlQuery($sql);
			while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				$QuestionInc=trim($row['QuestionInc']);
				$IDQec=$row['ID'];
				$radField="Q".$IDQec;
				$x++;
				$answerY=$IDQec."_Y";
				$answerN=$IDQec."_N";
			?>
              
              <tr>
                <td width="7%" height="25" align="center" bgcolor="#FFFFFF"><?php echo sprintf("%02d",$x);; ?>.</td>
                <td width="78%" align="left" bgcolor="#FFFFFF">&nbsp;<?php echo $QuestionInc; ?></td>
                <td width="7%" align="center" bgcolor="#FFFFFF"><input type="radio" name="<?php echo $radField ?>" id="<?php echo $radField ?>" value="Y" <?php if(in_array($answerY,$exQa)){?>checked="checked"<?php }?>/></td>
                <td width="8%" align="center" bgcolor="#FFFFFF"><input type="radio" name="<?php echo $radField ?>" id="<?php echo $radField ?>" value="N" <?php if(in_array($answerN,$exQa)){?>checked="checked"<?php }?>/></td>
              </tr>
              <?php } ?>
            </table></td>
	      </tr>
			  <tr>
			    <td colspan="2" >&nbsp;</td>
	      </tr>  
                            
                           <?php }$i++;?>
            <tr>
              <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                <tr>
                  <td width="15%" style="font-weight: bold">Officer Name<?php //echo "-$ApproveInstCode-";//echo "<br>";
					//echo "-$loggedSchool-";echo "<br>";?><?php //echo "-$ApproveDesignationCode-"; echo "<br>"; echo "-$accLevel-";?></td>
                  <td width="1%">:</td>
                  <td width="34%"><?php echo $SurnameWithInitialsED; ?></td>
                  <td width="16%" style="font-weight: bold">Comment</td>
                  <td width="1%">:</td>
                  <td width="33%" rowspan="4"><textarea name="ApproveComment" id="ApproveComment" cols="35" rows="5" <?php if($activate=='N'){?>disabled="disabled"<?php }?>><?php echo $Remarks ?></textarea></td>
                </tr>
                <tr>
                  <td style="font-weight: bold">Designation</td>
                  <td>:</td>
                  <td><?php echo $AccessRole; ?> [<?php echo $InstitutionName ?>]</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td style="font-weight: bold">Date</td>
                  <td>:</td>
                  <td><?php if($ApprovedStatus=='A' || $ApprovedStatus=='R'){echo $DateTime;}else{ echo date('Y-m-d');} ?></td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td style="font-weight: bold">Action</td>
                  <td>:</td>
                  <td>
                
                  <select class="select2a_n" id="ApprovedStatus" name="ApprovedStatus" <?php if($activate=='N'){?>disabled="disabled"<?php }?>>
                  	  <option value="" <?php if($ApprovedStatus==''){?> selected="selected"<?php }?>>Not approved from previous user</option>
                  	  <option value="P" <?php if($ApprovedStatus=='P'){?> selected="selected"<?php }?>>Pending</option>
                   	  <option value="A" <?php if($ApprovedStatus=='A'){?> selected="selected"<?php }?>>Approve</option>
                      <option value="R" <?php if($ApprovedStatus=='R'){?> selected="selected"<?php }?>>Reject</option>
                  </select>
                
                  </td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
            </tr>
            
            <tr>
              <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;">&nbsp;</td>
            </tr>
            <tr>
              <td valign="top">&nbsp;</td>
              <td valign="top">&nbsp;</td>
            </tr>
            <?php }?>
              <?php if($saveOk=="Y"){?>
            <tr>
              <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                <tr>
                  <td width="32%">&nbsp;</td>
                  <td width="68%"><input type="hidden" name="ApID" value="<?php echo $ApID ?>" />
                  <input type="hidden" name="RequestID" value="<?php echo $id ?>" />
                  <input type="hidden" name="RequestType" value="PrincipalIncrement" />
                  <input type="hidden" name="ApprovedByNIC" value="<?php echo $nicNO ?>" />
                  <input type="hidden" name="NICApply" value="<?php echo $NICApply ?>" />
                  <input type="hidden" name="cat" value="TeacherIncrement" /><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                </tr>
              </table></td>
              <td valign="top">&nbsp;</td>
            </tr>
              <?php }?>
         
              </table>        
        <?php }?>
              
    </div>
    
    </form>