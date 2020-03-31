<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$approvetype="VacancyPrincipleNational";
$sqlChkNo = "SELECT id FROM TG_Request_Approve WHERE (ApproveUserNominatorNIC = N'$nicNO') AND (RequestType = 'VacancyPrincipleNational')";
	$totNominiRow = $db->rowCount($sqlChkNo);
	if($totNominiRow>0){
	  $tblField =  'ApproveUserNominatorNIC';
	}else{
	  $tblField = 'ApprovelUserNIC';
	}

//$nicNO='722381718V';
$approvSql="SELECT        TG_PrincipleVacancyNational.ID, TG_PrincipleVacancyNational.VacancyMasterID, TG_PrincipleVacancyNational.NIC, TG_PrincipleVacancyNational.ServiceHistoryID, 
                         CONVERT(varchar(20),TG_PrincipleVacancyNational.ApplyDate,121) AS ApplyDate, TG_PrincipleVacancyNational.ExtraActivities, TG_PrincipleVacancyNational.ReasonForTransfer, 
                         TG_PrincipleVacancyNational.IsApproved, TG_PrincipleVacancyNationalMaster.Title, CONVERT(varchar(20),TG_PrincipleVacancyNationalMaster.OpenDate,121) AS OpenDate , 
                         CONVERT(varchar(20),TG_PrincipleVacancyNationalMaster.EndDate,121) AS EndDate, TG_PrincipleVacancyNationalMaster.VacancyDescription, TG_PrincipleVacancyNationalMaster.SchoolID, 
                         CD_CensesNo.InstitutionName
FROM            TG_PrincipleVacancyNational INNER JOIN
                         TG_PrincipleVacancyNationalMaster ON TG_PrincipleVacancyNational.VacancyMasterID = TG_PrincipleVacancyNationalMaster.ID INNER JOIN
                         CD_CensesNo ON TG_PrincipleVacancyNationalMaster.SchoolID = CD_CensesNo.CenCode INNER JOIN
                         TG_Request_Approve ON TG_PrincipleVacancyNational.ID = TG_Request_Approve.RequestID
WHERE        (TG_Request_Approve.RequestType = 'VacancyPrincipleNational') AND (TG_Request_Approve.$tblField = N'$NICUser') AND 
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
                      <td width="19%" align="center" bgcolor="#999999">Vacancy</td>
                      <td width="11%" align="center" bgcolor="#999999">School</td>
                      <td width="11%" align="center" bgcolor="#999999">Open Date</td>
                      <td width="11%" align="center" bgcolor="#999999">End Date</td>
                      <td width="11%" align="center" bgcolor="#999999">Request Date</td>
                      <td width="11%" align="center" bgcolor="#999999">Status</td>
                      <td width="9%" align="center" bgcolor="#999999">Action</td>
                    </tr>
                    <?php 
					$i=1;
					$stmt = $db->runMsSqlQuery($approvSql);
                     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$RequestID=$row['ID'];
						$VacancyMasterID=$row['VacancyMasterID'];
						$Title=$row['Title'];
						$InstitutionName=$row['InstitutionName'];
						$OpenDate=$row['OpenDate'];
						$EndDate=$row['EndDate'];
						
						
					?>
                    <tr>
                      <td height="20" bgcolor="#FFFFFF"><?php echo $i++; ?></td>
                      <td bgcolor="#FFFFFF"><?php echo $row['SurnameWithInitials']; ?></td>
                      <td bgcolor="#FFFFFF"><?php echo $row['Title']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['InstitutionName']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['OpenDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['EndDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['ApplyDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php //echo $row['FromDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><a href="<?php echo $ttle ?>-<?php echo $pageid ?>--<?php echo $RequestID ?>--<?php $VacancyMasterID?>.html"><img src="images/more_info.png" /></a></td>
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
			
		$approvalListSql="SELECT        TG_PrincipleVacancyNationalMaster.ID, TG_PrincipleVacancyNationalMaster.Title, CONVERT(varchar(20),TG_PrincipleVacancyNationalMaster.OpenDate,121) AS OpenDate, CONVERT(varchar(20),TG_PrincipleVacancyNationalMaster.EndDate,121) AS EndDate, 
                         TG_PrincipleVacancyNationalMaster.VacancyDescription, CD_CensesNo.InstitutionName, CD_CensesNo.IsNationalSchool, CD_CensesNo.CenCode
FROM            TG_PrincipleVacancyNationalMaster INNER JOIN
                         CD_CensesNo ON TG_PrincipleVacancyNationalMaster.SchoolID = CD_CensesNo.CenCode where CD_CensesNo.IsNationalSchool='1' and TG_PrincipleVacancyNationalMaster.ID='$tpe'";

	$stmtApp = $db->runMsSqlQuery($approvalListSql);
	while ($rowApp = sqlsrv_fetch_array($stmtApp, SQLSRV_FETCH_ASSOC)) {
		$RequestID=$rowApp['ID'];
		$Title=$rowApp['Title'];
		$OpenDate=$rowApp['OpenDate'];
		$EndDate=$rowApp['EndDate'];
		$VacancyDescription=$rowApp['VacancyDescription'];
		$CenCodeSchool=trim($rowApp['CenCode']);
		$VacancyInstitutionName=$rowApp['InstitutionName'];
	
	}
	
	
	$sqlFormDate="SELECT        TG_PrincipleVacancyNational.IsApproved, TG_PrincipleVacancyNational.ReasonForTransfer, TG_PrincipleVacancyNational.ExtraActivities, 
                         TG_PrincipleVacancyNational.ApplyDate,  StaffServiceHistory.InstCode, TG_PrincipleVacancyNational.NIC, 
                         TG_PrincipleVacancyNational.ServiceHistoryID, TG_PrincipleVacancyNational.ID
FROM            TG_PrincipleVacancyNational INNER JOIN
                         StaffServiceHistory ON TG_PrincipleVacancyNational.ServiceHistoryID = StaffServiceHistory.ID
						 where TG_PrincipleVacancyNational.ID='$id'";
						 
	$stmtFormData = $db->runMsSqlQuery($sqlFormDate);
	while ($rowForm = sqlsrv_fetch_array($stmtFormData, SQLSRV_FETCH_ASSOC)) {
		$IsApproved=$rowForm['IsApproved'];
		$ReasonForTransfer=$rowForm['ReasonForTransfer'];
		$ExtraActivities=$rowForm['ExtraActivities'];
		$ApplyDate=$rowForm['ApplyDate'];
		$InstCode=$loggedSchool=$rowForm['InstCode'];
		$NIC=$NICUser=trim($rowForm['NIC']);
		$ServiceHistoryID=trim($rowForm['ServiceHistoryID']);
		$recID = $rowForm['ID'];
	}
		//////Start form values
		
		
			?>
        <table width="100%" cellpadding="1" cellspacing="1">
        
			  <tr>
			    <td colspan="2" ><strong>Personal Information</strong></td>
	      </tr>
			  <tr>
			    <td colspan="2" bgcolor="#FFFFFF"><?php include("../transfer/teacherPersonalDetails.php");?></td>
	      </tr>
			  <tr>
			    <td colspan="2" >&nbsp;</td>
	      </tr>
          <tr>
			    <td colspan="2" bgcolor="#FFFFFF"><strong>Career Information</strong></td>
	      </tr>
          <tr>
			    <td colspan="2" bgcolor="#FFFFFF"><?php include("../transfer/principleCareerDetails.php");?></td>
	      </tr>
          <tr>
			    <td colspan="2" >&nbsp;</td>
	      </tr>
          <tr>
			    <td colspan="2" bgcolor="#FFFFFF"><strong>Teaching Qualification</strong></td>
	      </tr>
          <tr>
			    <td colspan="2" bgcolor="#FFFFFF"><?php include("../transfer/teacherTeachingDetails.php");?></td>
	      </tr>
          <tr>
			    <td colspan="2" >&nbsp;</td>
	      </tr>
          <tr>
			    <td colspan="2" bgcolor="#FFFFFF"><strong>Current Timetable</strong></td>
	      </tr>
          <tr>
			    <td colspan="2" bgcolor="#FFFFFF"><?php include("../transfer/teacherCurrentTimeTable.php");?></td>
	      </tr>
          <tr>
			    <td colspan="2" >&nbsp;</td>
	      </tr>
          <tr>
			    <td colspan="2" bgcolor="#FFFFFF"><strong>Transfer Details</strong></td>
	      </tr>
           
			  <tr>
			    <td colspan="2" ><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="23%" bgcolor="#CFF1D1">Expect School 1:</td>
                      <td width="77%" bgcolor="#CFD7FE"><?php echo ucwords(strtolower(($requestedSchool))); ?></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#CFF1D1">Expect School 2:</td>
                      <td bgcolor="#CFD7FE"><?php echo ucwords(strtolower(($requestedSchool2))); ?></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#CFF1D1">Expect School 3:</td>
                      <td bgcolor="#CFD7FE"><?php echo ucwords(strtolower(($requestedSchool3))); ?></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#CFF1D1">Expect School 4:</td>
                      <td bgcolor="#CFD7FE"><?php echo ucwords(strtolower(($requestedSchool4))); ?></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#CFF1D1">Expect School 5:</td>
                      <td bgcolor="#CFD7FE"><?php echo ucwords(strtolower(($requestedSchool5))); ?></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#CFF1D1">Extra Activities :</td>
                      <td bgcolor="#CFD7FE"><?php echo ucwords(strtolower(($ActivityTitle))); ?></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#CFF1D1">Reason for Transfer :</td>
                      <td bgcolor="#CFD7FE"><?php echo $ReasonForTransfer ?></td>
                    </tr>
                    <tr>
                      <td bgcolor="#CFF1D1">Would like to Work Another School :</td>
                      <td bgcolor="#CFD7FE"><?php echo $workOtherSchool ?></td>
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
          <tr>
                        <td colspan="2"><?php include("../transfer/schoolInformation.php");?></td>
                  </tr>
          <?php 
   $i=1;
   $sqlLeave="SELECT        TG_Request_Approve.id AS ReqAppID, TG_Request_Approve.RequestUserNIC, TG_Request_Approve.ApprovelUserNIC, TG_Request_Approve.ApproveUserNominatorNIC, 
                         TG_Request_Approve.ApproveProcessOrder, TG_Request_Approve.ApprovedStatus, TG_Request_Approve.DateTime, TG_Request_Approve.Remarks, 
                         TeacherMast.SurnameWithInitials, TG_ApprovalProcess.ApproveAccessRoleName
FROM            TG_Request_Approve INNER JOIN
                         TeacherMast ON TG_Request_Approve.ApprovelUserNIC = TeacherMast.NIC INNER JOIN
                         TG_ApprovalProcess ON TG_Request_Approve.ApprovalProcessID = TG_ApprovalProcess.ID
WHERE        (TG_Request_Approve.RequestType = '$approvetype') AND (TG_Request_Approve.RequestID = '$id')
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
                      <td valign="top" bgcolor="#EDEEF3">
                      <input type="hidden" value="<?php echo $ReqAppID ?>" name="ReqAppID" id="ReqAppID" />
                      <input type="hidden" value="<?php echo $id ?>" name="TransferID" id="TransferID" />
                      <input type="hidden" value="<?php echo $approvetype ?>" name="cat" />
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