<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
include_once '../approveProcessfunction.php';
$msg = "";

//$countSql = "SELECT * FROM $tblNam where ProcessType='$ProcessType' and AccessRoleID='$PositionCode' and Enable = 'Y'";
$uploadpath="incrementattachments";
$success="";
if (isset($_POST["FrmSubmit"])) {
	$_SESSION['success_update']="";
    $ServCode = $_REQUEST['ServCode'];
	$SalaryScale = $_REQUEST['SalaryScale'];
	$CurrentSalaryStep = $_REQUEST['CurrentSalaryStep'];
    $EffectiveDate = $_REQUEST['EffectiveDate'];
	$LastUpdate = date('Y-m-d H:i:s');
	//$UpdateBy = $_REQUEST['DSCode'];
	//$RecordLog = $_REQUEST['DSCode'];
	$msg="";
	$processType="TeacherIncrement";
	if($loggedPositionName=='PRINCIPAL')$processType="PrincipalIncrement";
	$countProcx=checkApprovalAvailable($processType);
	if($countProcx!='0'){
		
		if ($ServCode == "") {
			$msg.= "Please select Designation & Grade.<br>";
		}
		
		if ($CurrentSalaryStep == "") {
			$msg.= "Please select Current Salary Step.<br>";
		}
		if ($EffectiveDate == "") {
			$msg.= "Please select effective date.<br>";
		}
		if($msg==''){
			/* $isAvailable=$db->rowAvailable($sqlCAllready);
			if($isAvailable==1){
				
				$queryMainUpdate = "UPDATE TG_EmployeeUpdateQualification SET QualificationID='$QualificationID',dDateTime='$LastUpdate',IsApproved='N',ApproveDate='',ApprovedBy='',UpdateBy='$NICUser' WHERE NIC='$NICUser' and IsApproved='N'";
				$db->runMsSqlQuery($queryMainUpdate);
				
			}else{ */
				
				$dte=date("ymdHms");
				$field_name="AttachFile";
				$fileSaveName="";
				$_FILES[$field_name]['name']; 	 
				if($_FILES[$field_name]['name']!='') { //save file	
					$fileSaveName=$dte.$_FILES[$field_name]['name']; 
											
					$uppth2=$uploadpath."/".$fileSaveName;	
					copy ($_FILES[$field_name]['tmp_name'], $uppth2);
					//$insArrCusE[$field_name]=$fileSaveName;													
				}
		
		$queryRegis = "INSERT INTO TG_IncrementRequest				   (NIC,ServCode,SalaryScale,CurrentSalaryStep,EffectiveDate,IsApproved,UpdateBy,LastUpdate,RecordLog,SchoolID,AttachFile)
				 VALUES				   
			('$NICUser','$ServCode','$SalaryScale','$CurrentSalaryStep','$EffectiveDate','N','$NICUser','$LastUpdate','$RecordLog','$loggedSchool','$fileSaveName')";
				//$db->runMsSqlQueryInsert($queryRegis);
				$countSql="SELECT * FROM TG_IncrementRequest where NIC='$NICUser' and EffectiveDate='$EffectiveDate'";
				$isAvailable=$db->rowAvailable($countSql);
				if($isAvailable==1){
					$msg="Already exist.";
				}else{ 
			//echo $queryRegis;
					$newID=$db->runMsSqlQueryInsert($queryRegis);
					//exit();
					$msg = getApproveListOther($processType, $newID);
					
				}
				
			//}
			//$success="Your request submitted successfully.";
		}
	}else{
		$msg = "Approval process isn't assigned. Please contact your administrator.";
	}
	//exit();
    //sqlsrv_query($queryGradeSave);
}

/* 

if($isAvailablePmast!=1){
	$familiInfoMainStatus="Add";
	$sqlPers="SELECT        TeacherMast.NIC, TeacherMast.CivilStatusCode, TeacherMast.SpouseName, TeacherMast.SpouseNIC, 
                         TeacherMast.SpouseOccupationCode, CONVERT(varchar(20), 
                         TeacherMast.SpouseDOB, 121) AS SpouseDOB, TeacherMast.SpouseOfficeAddr, CD_Positions.PositionName, 
                         CD_CivilStatus.CivilStatusName
FROM            TeacherMast INNER JOIN
                         CD_Positions ON TeacherMast.SpouseOccupationCode = CD_Positions.Code INNER JOIN
                         CD_CivilStatus ON TeacherMast.CivilStatusCode = CD_CivilStatus.Code
WHERE        (TeacherMast.NIC = N'$NICUser')";

	$resA = $db->runMsSqlQuery($sqlPers);
	$rowA = sqlsrv_fetch_array($resA, SQLSRV_FETCH_ASSOC);
	$CivilStatusCode = trim($rowA['CivilStatusCode']);
	$SpouseName = $rowA['SpouseName'];
	$SpouseNIC = trim($rowA['SpouseNIC']);
	$SpouseOccupationCode = trim($rowA['SpouseOccupationCode']);
	$SpouseDOB= $rowA['SpouseDOB'];
	$SpouseOfficeAddr = $rowA['SpouseOfficeAddr'];
	$PositionName = $rowA['PositionName'];
	$CivilStatusName = $rowA['CivilStatusName'];
	
} */

//$TotaRows = $db->rowCount($countTotal);

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
	
	$sql1stAppDate="SELECT     CONVERT(varchar(10), 
							 StaffServiceHistory.AppDate, 121) AS AppDate
FROM         TeacherMast INNER JOIN
                      StaffServiceHistory ON TeacherMast.NIC = StaffServiceHistory.NIC
WHERE     (StaffServiceHistory.ServiceRecTypeCode = N'NA01') AND (TeacherMast.NIC=N'$NICUser')";
	$resAppDate = $db->runMsSqlQuery($sql1stAppDate);
	$rowAppd = sqlsrv_fetch_array($resAppDate, SQLSRV_FETCH_ASSOC);
	$AppDateFirst = $rowAppd['AppDate'];
	
	
	$sqlPerAdd="SELECT    StaffAddrHistory.Address, StaffAddrHistory.Tel, 
							 CONVERT(varchar(20),StaffAddrHistory.AppDate,121) AS AppDate, CD_DSec.DSName, CD_Districts.DistName, CD_DSec.DSCode, CD_Districts.DistCode
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
?>
<?php if($menu==''){?>
<div class="main_content_inner_block">
        <div class="mcib_middle1">
            <table width="945" cellpadding="0" cellspacing="0">
                <tr>
                  <td valign="top"><span style="color:#090; font-weight:bold;"><!--*If your qualifications are inaccurate, you can submit an update request--></span></td>
                  <td align="right" valign="top"><a href="incrementRequest-0-E.html"><img src="../cms/images/create-an-Increment-request.png" width="200" height="26" /></a></td>
                </tr>
                <tr>
                  <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;"><strong> Requests History</strong></td>
                </tr>
                <tr>
                  <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2" valign="top" bgcolor="#666666"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="2%" align="center" valign="top" bgcolor="#CCCCCC"><strong>#</strong></td>
                      <td width="18%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Designation &amp; Grade</strong></td>
                      <td width="39%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Salary Scale</strong></td>
                      <td width="19%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Current Salary</strong></td>
                      <td width="12%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Effective Date</strong></td>
                      <!--<td width="10%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Action</strong></td>-->
                    </tr>
                    <?php 
					$i=1;

					$sql="SELECT        TG_IncrementRequest.ID, TG_IncrementRequest.ServCode, TG_IncrementRequest.SalaryScale, TG_IncrementRequest.CurrentSalaryStep, CONVERT(varchar(20),TG_IncrementRequest.EffectiveDate, 121) AS EffectiveDate,  TG_IncrementRequest.IsApproved, 
                         CD_Service.ServiceName
FROM            TG_IncrementRequest INNER JOIN
                         CD_Service ON TG_IncrementRequest.ServCode = CD_Service.ServCode
						 WHERE        (TG_IncrementRequest.NIC = '$NICUser' and TG_IncrementRequest.IsApproved = 'Y')";
					$stmt = $db->runMsSqlQuery($sql);
					while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$SalaryScale=trim($row['SalaryScale']);
						$CurrentSalaryStep=$row['CurrentSalaryStep'];
						$EffectiveDate=$row['EffectiveDate'];
						$ServiceName=$row['ServiceName'];
						$Expr1=$row['ID'];
						
						?>
                    <tr>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $ServiceName ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $SalaryScale ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $CurrentSalaryStep; ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $EffectiveDate; ?></td>
                      <!--<td align="left" valign="top" bgcolor="#FFFFFF"><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','incrementRequest','TG_IncrementRequest','<?php echo "incrementRequest-0-E.html"; ?>')">Remove</a></td>-->
                    </tr>
                    <?php }?>
                  </table></td>
                </tr>
                <tr>
                    <td width="82%" valign="top">&nbsp;</td>
                    <td width="18%" valign="top">&nbsp;</td>
                </tr>
                
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </div>
</div>
<?php }?>
<?php if($menu=='E'){?>
<div class="main_content_inner_block">
    <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
    	<div class="error" style="display: none;">
            <div id="dialog" title="Error" style="display: none;">
                <p>Please fill required information.</p>
            </div>
        </div>
        
        <?php if ($msg != '' || $success!='') {//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){   ?>   
        <div class="mcib_middle1">
            <div class="mcib_middle_full">
                <div class="form_error"><?php
                    echo $msg;echo $success;
                    echo $_SESSION['success_update'];
                    $_SESSION['success_update'] = "";
                    ?><?php echo $_SESSION['fail_update'];
                    $_SESSION['fail_update'] = "";
                    ?></div>
            </div>
<?php }  ?>
<?php //if($success==''){?>
<table width="945" cellpadding="0" cellspacing="0">
                <tr>
                  <td width="82%" valign="top"><span style="color:#090; font-weight:bold;"><!--*If your Qualification not available, you can submit an update request->--></span></td>
                  <td width="18%" align="right" valign="top"><a href="incrementRequest-0.html"><img src="../cms/images/view-history.png" width="100" height="26" /></a></td>
                </tr>
                
                <tr>
                  <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;"><strong>Details of Pending Approval</strong></td>
                </tr>
                <tr>
                  <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2" valign="top" bgcolor="#666666"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="2%" align="center" valign="top" bgcolor="#CCCCCC"><strong>#</strong></td>
                      <td width="18%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Designation &amp; Grade</strong></td>
                      <td width="39%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Salary Scale</strong></td>
                      <td width="19%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Current Salary</strong></td>
                      <td width="12%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Effective Date</strong></td>
                      <td width="10%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Action</strong></td>
                    </tr>
                    <?php 
					$i=1;

					$sql="SELECT        TG_IncrementRequest.ID, TG_IncrementRequest.ServCode, TG_IncrementRequest.SalaryScale, TG_IncrementRequest.CurrentSalaryStep, CONVERT(varchar(20),TG_IncrementRequest.EffectiveDate, 121) AS EffectiveDate,  TG_IncrementRequest.IsApproved, 
                         CD_Service.ServiceName
FROM            TG_IncrementRequest INNER JOIN
                         CD_Service ON TG_IncrementRequest.ServCode = CD_Service.ServCode
						 WHERE        (TG_IncrementRequest.NIC = '$NICUser' and TG_IncrementRequest.IsApproved = 'N')";
					$stmt = $db->runMsSqlQuery($sql);
					while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$SalaryScale=trim($row['SalaryScale']);
						$CurrentSalaryStep=$row['CurrentSalaryStep'];
						$EffectiveDate=$row['EffectiveDate'];
						$ServiceName=$row['ServiceName'];
						$Expr1=$row['ID'];
						
						?>
                    <tr>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $ServiceName ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $SalaryScale ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $CurrentSalaryStep; ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $EffectiveDate; ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','incrementRequest','TG_IncrementRequest','<?php echo "incrementRequest-0-E.html"; ?>')">Remove</a></td>
                    </tr>
                    <?php }?>
                  </table></td>
                </tr>
                <tr>
                  <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <?php if($success==''){?>
                <tr>
                  <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;"><strong>Add New Request</strong></td>
                </tr>
                <tr>
                  <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2" valign="top"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td height="25"><strong>School Name</strong></td>
                      <td>:</td>
                      <td><select class="select2a" id="SchoolID" name="SchoolID">
                            <!--<option value="">School Name</option>-->
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
  where CenCode='$loggedSchool'
  order by InstitutionName";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                echo '<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
                            }
                            ?>
                      </select></td>
                    </tr>
                    <tr>
                      <td height="25"><strong>Applicant Name</strong></td>
                      <td>:</td>
                      <td><?php echo $SurnameWithInitials ?></td>
                    </tr>
                    <tr>
                      <td height="25"><strong>Personal Address</strong></td>
                      <td>:</td>
                      <td><?php echo $Address ?></td>
                    </tr>
                    <tr>
                      <td height="25"><strong>Mobile Number</strong></td>
                      <td>:</td>
                      <td><?php echo $MobileTel ?></td>
                    </tr>
                    <tr>
                      <td height="25"><strong>Date of Birth</strong></td>
                      <td>:</td>
                      <td><?php echo $DOB ?></td>
                    </tr>
                    <tr>
                      <td height="25"><strong>NIC No.</strong></td>
                      <td>:</td>
                      <td><?php echo $NICUser ?></td>
                    </tr>
                    <tr>
                      <td height="25"><strong>1st Appointment Date</strong></td>
                      <td>:</td>
                      <td><?php echo $AppDateFirst ?></td>
                    </tr>
                    <tr>
                      <td height="25"><strong>Designation & Grade <span class="form_error_sched">*</span></strong></td>
                      <td>:</td>
                      <td><select class="select2a" id="ServCode" name="ServCode" onchange="Javascript:show_salaryscale('salaryscale',this.options[this.selectedIndex].value,'');">
                            <option value="">-Select-</option>
                            <?php
                            $sql = "SELECT ServCode, ServiceName
FROM            CD_Service 
						 WHERE ServCode!=''";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                echo '<option value=' . $row['ServCode'] . '>' . $row['ServiceName'] . '</option>';
                            }
                            ?>
                      </select></td>
                    </tr>
                    
                    <tr>
                      <td valign="top"><strong>Education Qualification</strong></td>
                      <td valign="top">:</td>
                      <td bgcolor="#666666"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="2%" align="center" valign="top" bgcolor="#CCCCCC"><strong>#</strong></td>
                      <td width="15%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Qualification Title</strong></td>
                      <td width="33%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Description</strong></td>
                      <td width="28%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Subjects</strong></td>
                      <td width="12%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Effective Date</strong></td>
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
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $catTitle ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $Description ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $SubjectName; ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $EffectiveDate; ?></td>
                      </tr>
                    <?php }?>
                  </table></td>
                    </tr>
                    <tr>
                      <td><strong>Teaching Subject</strong></td>
                      <td>:</td>
                      <td height="25"><?php 
					  $sqlList="SELECT * From TG_SchoolTimetableTeachersTemp where NIC='$NICUser' order by TeacherName";
					  
  $i=1;
   $stmt = $db->runMsSqlQuery($sqlList);
                      $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
					  echo substr($row['SubjectName'], 0, -1);
					 
					  ?></td>
                    </tr>
                    <tr>
                      <td height="25"><strong>Salary Scale</strong></td>
                      <td>:</td>
                      <td height="25"><div id="txt_salaryscale"><input name="SalaryScale" type="hidden" class="input2" id="SalaryScale" />Please select "Designation & Grade"</div></td>
                    </tr>
                    <tr>
                      <td height="25"><strong>Current Salary Step <span class="form_error_sched">*</span></strong></td>
                      <td>:</td>
                      <td>
                     
                      <select class="select2a" id="CurrentSalaryStep" name="CurrentSalaryStep">
                            <option value="">-Select-</option>
                            <?php
                            $sql = "SELECT        CD_TG_SalaryScale.Description, CD_Service.ServiceName
FROM            CD_TG_SalaryScale INNER JOIN
                         CD_Service ON CD_TG_SalaryScale.ServiceID = CD_Service.ServCode";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$desScale=str_replace(" ","",$row['Description']);
								$ServiceNameCr=$row['ServiceName'];
                                echo "<option value=\"$desScale\">$ServiceNameCr [$desScale]</option>";
                            }
                            ?>
                      </select></td>
                    </tr>
                    <tr>
                      <td width="17%" height="25"><strong>Increment Date <span class="form_error_sched">*</span></strong></td>
                      <td width="1%"><strong>:</strong></td>
                      <td width="82%"><table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="7%"><input name="EffectiveDate" type="text" class="input3new" id="EffectiveDate" value="<?php //echo $DOB; ?>" size="10" style="width:100px;" readonly="readonly"/></td>
                          <td width="93%"><input name="f_trigger_2" type="image" id="f_trigger_2" src="../cms/images/calender_icon.gif" align="top" width="16" height="16"  />
                            <script type="text/javascript">
                            //2005-10-03 11:46:00 
                                Calendar.setup({
                                inputField     :    "EffectiveDate",      // id of the input field
                                ifFormat       :    "%Y-%m-%d",       // format of the input field
                                showsTime      :    false,            // will display a time selector
                                button         :    "f_trigger_2",   // trigger for the calendar (button ID)
                                singleClick    :    true,           // double-click mode
                                step           :    1                // show all years in drop-down boxes (instead of every other year as default)
                            });
                          </script></td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td height="25"><strong>Service Letter <span class="form_error_sched">*</span></strong></td>
                      <td>:</td>
                      <td><input name="AttachFile" type="file" class="input2" id="AttachFile" /></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td colspan="2" align="left" class="star_value"><strong>Fields marked with an asterisk (*) are required.</strong></td>
                </tr>
                <tr>
                  <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                    <tr>
                      <td width="22%">&nbsp;</td>
                      <td width="78%"><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                    </tr>
                  </table></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
               <?php  }?>
            </table>
            <?php //}?>
        </div>

    </form>
       <script>
//ServCode,CurrentSalaryStep,EffectiveDate,AttachFile
    $("#frmSave").submit(function(event) {
        var dialogStatus = false;//NIC, Title, SurnameWithInitials, FullName, ZoneCode
        var ServCode = trim($("#ServCode").val());
		var CurrentSalaryStep = trim($("#CurrentSalaryStep").val());
		var EffectiveDate = trim($("#EffectiveDate").val());
		var AttachFile = trim($("#AttachFile").val());

        //$("#vUserName").attr('class', 'fields_errors');
        
		if (EffectiveDate == "") {
            $("#EffectiveDate").attr('class', 'input3new_error');
            dialogStatus = true;
        }
		if (CurrentSalaryStep == "") {
            $("#CurrentSalaryStep").attr('class', 'input2_error');
            dialogStatus = true;
        }
        if (ServCode == "") {
            $("#ServCode").attr('class', 'input2_error');
            dialogStatus = true;
        }
		if (AttachFile == "") {
            $("#AttachFile").attr('class', 'input2_error');
            dialogStatus = true;
        }

        if (dialogStatus) {
            $("#dialog").dialog({
                modal: true
            });
            event.preventDefault();
        }

    });

    function numbersonly(e) {
        var unicode = e.charCode ? e.charCode : e.keyCode
        if (unicode != 8) { //if the key isn't the backspace key (which we should allow)
            if (unicode < 48 || unicode > 57) //if not a number
                return false //disable key press
        }
    }

</script>
</div>
<?php }?>