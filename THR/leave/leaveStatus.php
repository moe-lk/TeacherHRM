<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
include_once '../approveProcessfunction.php';
//echo $NICUser;
$msg="";
$uploadpath="leaveattachments";
$tblNam="TG_StaffLeave";
$countTotal="SELECT        TG_StaffLeave.ID, TG_StaffLeave.NIC, CONVERT(varchar(20),TG_StaffLeave.StartDate,121) AS FromDate,CONVERT(varchar(20),TG_StaffLeave.EndDate,121) AS ToDate, CONVERT(varchar(20),TG_StaffLeave.LastUpdate,121) AS LastUpdate, TG_StaffLeave.UpdateBy, TG_StaffLeave.LeaveType,TG_StaffLeave.AttachFile,
                         TG_StaffLeave.Reference, TG_StaffLeave.NoofDays, TeacherMast.SurnameWithInitials, CD_LeaveType.Description, CD_CensesNo.CenCode, 
                         CD_CensesNo.InstitutionName
FROM            TG_StaffLeave INNER JOIN
                         StaffServiceHistory ON TG_StaffLeave.ServiceRecRef = StaffServiceHistory.ID INNER JOIN
                         TeacherMast ON TG_StaffLeave.NIC = TeacherMast.NIC INNER JOIN
                         CD_LeaveType ON TG_StaffLeave.LeaveType = CD_LeaveType.LeaveCode INNER JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
						 where TG_StaffLeave.ID='$id'";//$NICUser
						 
$stmt = $db->runMsSqlQuery($countTotal);
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$FromDate=$row['FromDate'];
		$ToDate=$row['ToDate'];
		$LastUpdate=trim($row['LastUpdate']);
		$SurnameWithInitials=$row['SurnameWithInitials'];
		$Description=$row['Description'];
		$LeaveType=$row['LeaveType'];
		$NoofDays=$row['NoofDays'];
		$AttachFile=$row['AttachFile'];
		$Reference=$row['Reference'];
		$NICLeave=$row['NIC'];
}

$TotaRows=$db->rowCount($countTotal);

$sqlFA = "SELECT CONVERT(varchar(20),AppDate,121) AS firstAppDate FROM StaffServiceHistory where NIC='$NICLeave' and ServiceRecTypeCode='NA01'";

	$stmtFA = $db->runMsSqlQuery($sqlFA);
    $rowSFA = sqlsrv_fetch_array($stmtFA, SQLSRV_FETCH_ASSOC);
    $firstAppDate = $rowSFA['firstAppDate'];
	
$checkAccessRol="SELECT        TeacherMast.NIC, StaffServiceHistory.PositionCode, StaffServiceHistory.InstCode, TeacherMast.CurServiceRef, TeacherMast.SurnameWithInitials,
                         Passwords.AccessRole
FROM            TeacherMast INNER JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID INNER JOIN
                         Passwords ON TeacherMast.NIC = Passwords.NICNo
                         where TeacherMast.NIC='$NICLeave'";
$stmt = $db->runMsSqlQuery($checkAccessRol);
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        //$loggedSchoolID=trim($row['InstCode']);
        $designationLeave=trim($row['AccessRole']);
    }
?>


<div class="main_content_inner_block">
    <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if($msg!=''){//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
   	  <div class="mcib_middle1">
          <div class="mcib_middle_full">
          <div class="form_error"><?php echo $msg; echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
        </div>
        <?php }?>
        <table width="945" cellpadding="0" cellspacing="0">
        
			  <tr>
			    <td colspan="2" ><table width="100%" cellspacing="1" cellpadding="1">
			      <tr>
			        <td>Name</td>
			        <td>:</td>
			        <td><?php echo $SurnameWithInitials ?></td>
			        <td>Designation</td>
			        <td>:</td>
			        <td><?php echo ucfirst(strtolower($designationLeave ));?></td>
		          </tr>
			      <tr>
			        <td>Leave Type </td>
			        <td>:</td>
			        <td><?php echo $Description ?></td>
			        <td>1st Appoinment Date :</td>
			        <td>:</td>
			        <td><?php echo $firstAppDate ?></td>
		          </tr>
			      <tr>
			        <td>Number of days</td>
			        <td>:</td>
			        <td><?php echo $NoofDays ?></td>
			        <td>Attachment</td>
			        <td>:</td>
			        <td><a href="<?php echo "$uploadpath/$AttachFile"; ?>" target="_blank">View</a></td>
		          </tr>
			      <tr>
			        <td width="15%">From Date</td>
			        <td width="2%">:</td>
			        <td width="30%"><?php echo $FromDate ?></td>
			        <td width="16%">Request Date</td>
			        <td width="2%">:</td>
			        <td width="35%"><?php echo $LastUpdate ?></td>
		          </tr>
			      <tr>
			        <td>To Date</td>
			        <td>:</td>
			        <td><?php echo $ToDate ?></td>
			        <td>Remarks</td>
			        <td>:</td>
			        <td><?php echo $Reference ?></td>
		          </tr>
		        </table></td>
	      </tr>
			  <tr>
			    <td colspan="2" >&nbsp;</td>
	      </tr>
          <tr bgcolor="#3399FF">
              <td height="30" colspan="2" valign="middle" style="border-bottom: 1px; border-bottom-style: solid; font-size: 14px; color: #FFFFFF;">&nbsp;&nbsp;<strong>Approval Status</strong></td>
          </tr>
          <?php $sqlApp="SELECT        TG_Approval_Leave.ID, TG_Approval_Leave.RequestType, TG_Approval_Leave.RequestID, TG_Approval_Leave.ApproveInstCode, TG_Approval_Leave.ApproveDesignationCode, TG_Approval_Leave.ApproveDesignationNominiCode, 
                         TG_Approval_Leave.ApprovedStatus, TG_Approval_Leave.ApprovedByNIC, TG_Approval_Leave.DateTime, TG_Approval_Leave.Remarks, CD_CensesNo.InstitutionName, CD_AccessRoles.AccessRole
FROM            TG_Approval_Leave INNER JOIN
                         CD_CensesNo ON TG_Approval_Leave.ApproveInstCode = CD_CensesNo.CenCode INNER JOIN
                         CD_AccessRoles ON TG_Approval_Leave.ApproveDesignationCode = CD_AccessRoles.AccessRoleValue 
						 WHERE TG_Approval_Leave.RequestID='$id'";
						 
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
					//echo $accLevel;
					$activate="N";
					
					//echo "-$ApproveInstCode-";echo "<br>";
					//echo "-$loggedSchool-";echo "<br>";
					//echo $loggedSchool;echo "<br>";
					/* if($ApproveInstCode==$loggedSchool and ($ApproveDesignationCode==$accLevel || $ApproveDesignationNominiCode==$accLevel)){
						$saveOk="Y";
						$activate="Y";
						$ApID=$IDApp;
					} */
					
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
            <tr>
              <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                <tr>
                  <td width="15%" style="font-weight: bold">Officer Name<?php //echo "-$ApproveInstCode-";//echo "<br>";
					//echo "-$loggedSchool-";echo "<br>";?><?php //echo "-$ApproveDesignationCode-"; echo "<br>"; echo "-$accLevel-";?></td>
                  <td width="1%">:</td>
                  <td width="34%"><?php echo $SurnameWithInitialsED; ?></td>
                  <td width="16%" style="font-weight: bold">Comment</td>
                  <td width="1%">:</td>
                  <td width="33%" rowspan="3"><textarea name="ApproveComment" id="ApproveComment" cols="35" rows="5" <?php if($activate=='N'){?>disabled="disabled"<?php }?>><?php echo $Remarks ?></textarea></td>
                </tr>
                <tr>
                  <td style="font-weight: bold">Designation</td>
                  <td>:</td>
                  <td><?php echo $AccessRole; ?> [<?php echo $InstitutionName ?>]</td>
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
          
                <tr>
                  <td width="56%">&nbsp;</td>
                  <td width="44%">&nbsp;</td>
                </tr>
                <?php //}?>
              </table>
    </div>
    
    </form>
</div><!--
<div style="width:945px; width: auto; float: left;">
    <div style="width: 150px; float: left; margin-left: 50px;">
        School
    </div>
    <div style="width: 745px; float: left;">
        <select name="teachingSubject" class="select2a_n" id="teachingSubject" style="width: auto;" onchange="">
            <option value="">School Name</option>
           
        </select>
    </div>
    <div style="width: 150px; float: left;margin-left: 50px;">
        Grade
    </div>
    <div style="width: 745px; float: left;">
        <select name="teachingSubject" class="select2a_n" id="teachingSubject" style="width: auto;" onchange="">
            <option value="">Grade</option>
           
        </select>
    </div>
    <div style="width: 200px; float: left;margin-left: 50px;">
        
    </div>
    
</div>-->