<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
include_once '../approveProcessfunction.php';

$cat="teacherQualification";
$tblName="TG_TeacherQualification";
$TransferType="TVANO";//teacher vacancy national school
$approvetype="TeacherQualification";

if($fm=='' || $fm=='A'){
	$countTotal="SELECT ID,CONVERT(varchar(20),EffectiveDate,121) AS EffectiveDate, CONVERT(varchar(20),ApplyDate,121) AS ApplyDate,Description  FROM $tblName where NIC='$NICUser'";//where SchoolID='$loggedSchool'

	$TotaRows=$db->rowCount($countTotal);
	
	//echo $loggedSchool;
	
	$ApplyDate=date('Y-m-d');
	
	$checkAvai="SELECT ID from TG_Request_Approve where RequestType='TeacherQualification' and RequestID='$id' and ApprovedStatus='A'";
	$TotaRowsProc=$db->rowCount($checkAvai);
	$editBut="Y";
	if($TotaRowsProc>0)$editBut="N";
	
	
}else if($fm=='V'){
	$approvalListSql="SELECT        NIC, QCode, CONVERT(varchar(20),EffectiveDate,121) AS EffectiveDate, CONVERT(varchar(20),ApplyDate,121) AS ApplyDate, 
                         Description, SchoolID, IsApproved
FROM            TG_TeacherQualification where ID='$id'";

	$stmtApp = $db->runMsSqlQuery($approvalListSql);
	while ($rowApp = sqlsrv_fetch_array($stmtApp, SQLSRV_FETCH_ASSOC)) {
		$InstCode=$loggedSchool=trim($rowApp['SchoolID']);
		$NIC=$NICUser=trim($rowApp['NIC']);
		$QCode=$rowApp['QCode'];
		$EffectiveDate=$rowApp['EffectiveDate'];
		$ApplyDate=$rowApp['ApplyDate'];
		$Description=stripslashes($rowApp['Description']);
		$IsApproved=$rowApp['IsApproved'];
	
	}
	
	$checkAvai="SELECT ID from TG_Request_Approve where RequestType='TeacherQualification' and RequestID='$id' and ApprovedStatus='A'";
	$TotaRowsProc=$db->rowCount($checkAvai);
	$editBut="Y";
	if($TotaRowsProc>0)$editBut="N";
	
						 
}
?>


<div class="main_content_inner_block">
    <form method="post" action="save.php" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
   	  <div class="mcib_middle1">
          <div class="mcib_middle_full">
          <div class="form_error"><?php echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
        </div>
        <?php }?>
        <table width="945" cellpadding="0" cellspacing="0">
        <?php if($id=='' && $fm==''){?>
                <tr>
                  <td>
                    <table width="100%" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="left"><?php echo $TotaRows ?> Record(s) found.</td>
                        <td align="right"><a href="teacherQualification-1---A.html">Add New</a></td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                    <td bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="3%" height="25" align="center" bgcolor="#999999">#</td>
                        <td width="23%" align="center" bgcolor="#999999">Teacher Details</td>
                        <td width="17%" align="center" bgcolor="#999999">Qualification Category</td>
                        <td width="18%" align="center" bgcolor="#999999">Description</td>
                        <td width="9%" align="center" bgcolor="#999999">Apply Date</td>
                        <td width="9%" align="center" bgcolor="#999999">Effective Date</td>
                        <td width="19%" align="center" bgcolor="#999999">Status</td>
                        <td width="11%" align="center" bgcolor="#999999">Delete</td>
                      </tr>
                      <?php 
					$i=1;
					$stmt = $db->runMsSqlQuery($countTotal);
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$RequestID=$row['ID'];
						$listAp="";
					$approvalListSql="SELECT TG_Request_Approve.id, TG_Request_Approve.ApprovelUserNIC, TG_Request_Approve.ApproveUserNominatorNIC, 
                         TG_Request_Approve.ApprovedStatus, TG_ApprovalProcess.ApproveAccessRoleName
FROM            TG_Request_Approve INNER JOIN
                         TG_ApprovalProcess ON TG_Request_Approve.ApprovalProcessID = TG_ApprovalProcess.ID
WHERE        (TG_Request_Approve.RequestID = '$RequestID') AND (TG_Request_Approve.RequestType = '$approvetype')
			Order By TG_Request_Approve.ID";

					$stmtApp = $db->runMsSqlQuery($approvalListSql);
                     while ($rowApp = sqlsrv_fetch_array($stmtApp, SQLSRV_FETCH_ASSOC)) {
						$ApproveAccessRoleName=$rowApp['ApproveAccessRoleName'];
						$ApprovedStatus=$rowApp['ApprovedStatus'];
						$statusTitle="Pending";
						if($ApprovedStatus=='A')$statusTitle="Approved";
						if($ApprovedStatus=='R')$statusTitle="Rejected";
						
						$listAp.="$ApproveAccessRoleName ($statusTitle) > ";
					 }
					 
					$sqlDetails="SELECT        TG_TeacherQualification.QCode, TeacherMast.SurnameWithInitials, CD_CensesNo.InstitutionName, CD_QualificationCategory.Description AS Expr1
FROM            TG_TeacherQualification INNER JOIN
                         TeacherMast ON TG_TeacherQualification.NIC = TeacherMast.NIC INNER JOIN
                         CD_CensesNo ON TG_TeacherQualification.SchoolID = CD_CensesNo.CenCode INNER JOIN
                         CD_QualificationCategory ON TG_TeacherQualification.QCode = CD_QualificationCategory.Code
WHERE        (TG_TeacherQualification.ID = '$RequestID')";
					 $stmtApp = $db->runMsSqlQuery($sqlDetails);
                     while ($rowApp = sqlsrv_fetch_array($stmtApp, SQLSRV_FETCH_ASSOC)) {
						$SurnameWithInitials=$rowApp['SurnameWithInitials'];
						$InstitutionName=$rowApp['InstitutionName'];
						$catDes=$rowApp['Expr1'];
						$QCode=$rowApp['QCode'];
						//$ApprovedStatus=$rowApp['ApprovedStatus'];
						//$ApprovedStatus=$rowApp['ApprovedStatus'];
					 }
					 
					$checkAvai="SELECT ID from TG_Request_Approve where RequestType='TeacherQualification' and RequestID='$RequestID' and ApprovedStatus='A'";
					$TotaRowsProc=$db->rowCount($checkAvai);
					$editBut="Y";
					if($TotaRowsProc>0)$editBut="N";
					?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $SurnameWithInitials; ?><br /><?php echo $InstitutionName ?></td>
                        <td bgcolor="#FFFFFF"><?php echo "$QCode - $catDes"; ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['Description']; ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['EffectiveDate']; ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['ApplyDate']; ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $listAp ?></td>
                        <td bgcolor="#FFFFFF" align="center"><?php if($editBut=='Y'){?><a href="teacherQualification-<?php echo $pageid ?>--<?php echo $RequestID ?>-E.html">Edit&nbsp;|&nbsp;<?php }?><a href="teacherQualification-<?php echo $pageid ?>--<?php echo $RequestID ?>-V.html">View&nbsp;|&nbsp;<a href="javascript:aedWin('<?php echo $RequestID ?>','D','','<?php echo $tblName ?>','<?php echo "$ttle-$pageid.html";?>')">Delete</a></td>
                      </tr>
                      <?php }?>
                      <tr>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                    </table></td>
          </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <?php }else{?>
			  <tr>
                  <td><table width="100%" cellspacing="2" cellpadding="2">
                    <tr>
                      <td width="18%" align="left" valign="top">Teacher :</td>
                      <td width="82%"><?php 
					  $sql = "SELECT NIC, SurnameWithInitials      
  FROM TeacherMast
  where NIC='$NICUser'";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                echo $row['SurnameWithInitials'];
                            }
							
							//echo $NICUser ?>
                        </td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">Working School :</td>
                      <td><?php $sqlFormDate="Select InstitutionName from CD_CensesNo where CenCode='$loggedSchool'";
					$stmtFormData = $db->runMsSqlQuery($sqlFormDate);
					while ($rowForm = sqlsrv_fetch_array($stmtFormData, SQLSRV_FETCH_ASSOC)) {
						echo $InstitutionName = $rowForm['InstitutionName'];
					} ?></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">Qualification Type :</td>
                      <td><select class="select5" id="QCode" name="QCode">
                        <option value="" >-Select-</option>
                        <?php
                            $sql = "SELECT Code,Description FROM CD_QualificationCategory order by Level";
                            $stmt = $db->runMsSqlQuery($sql);
                            /* while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$code=$row['Code'];
								$Description=$row['Description'];
								
                                echo "<option value=\"$code\">$Description</option>";
                            } */
							while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								if ($QCode == trim($row['Code']))
									echo '<option selected="selected" value=' . $row['Code'] . '>' . $row['Description'] . '</option>';
								else
									echo '<option value=' . $row['Code'] . '>' . $row['Description'] . '</option>';
							}
		
                            ?>
                      </select></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">Description :</td>
                      <td><textarea name="Description" cols="100" rows="5" class="textarea1auto" id="Description"><?php echo $Description ?></textarea></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">Reference :</td>
                      <td><input type="file" name="Reference" id="Reference" /></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">Effective Date :</td>
                      <td>
                        <table width="100%" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="7%"><input name="EffectiveDate" type="text" class="input3new" id="EffectiveDate" value="<?php echo $EffectiveDate; ?>" size="10" style="height:20px; line-height:20px;" readonly/>
                      </td>
                            <td width="93%">
                      <input name="f_trigger_1" type="image" id="f_trigger_1" src="../cms/images/calender_icon.gif" align="top" width="16" height="16"  />
                  <script type="text/javascript">
                            //2005-10-03 11:46:00 
                                Calendar.setup({
                                inputField     :    "EffectiveDate",      // id of the input field
                                ifFormat       :    "%Y-%m-%d",       // format of the input field
                                showsTime      :    false,            // will display a time selector
                                button         :    "f_trigger_1",   // trigger for the calendar (button ID)
                                singleClick    :    true,           // double-click mode
                                step           :    1                // show all years in drop-down boxes (instead of every other year as default)
                            });
                          </script>
                </td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">Apply Date :</td>
                      <td><table width="100%" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="13%"><input name="ApplyDate" type="text" class="input3new" id="ApplyDate" value="<?php echo $ApplyDate; ?>" size="10" style="height:20px; line-height:20px;" readonly/>
                      </td>
                            <td width="87%"> </td>
                          </tr>
                      </table></td>
                    </tr>
                    
                    <tr>
                      <td>&nbsp;</td>
                      <td><input type="hidden" name="cat" value="<?php echo $cat ?>" />
                      <input type="hidden" name="tblName" value="<?php echo $tblName ?>" />
                      <input type="hidden" name="NIC" value="<?php echo $NICUser ?>" />
                      <input type="hidden" name="SchoolID" value="<?php echo $loggedSchool ?>" />
                      <input type="hidden" name="AED" value="<?php echo $fm ?>" />
                      <input type="hidden" name="vID" value="<?php echo $id ?>" />
                      <?php if($fm!='V'){?>
                      <input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" />
                      <?php }?></td>
                    </tr>
                    </table>
        </td>
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
                      if($ApprovelUserNIC==$nicNO){}else{?>
                    
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
         <?php }?>
              </table>
    </div>
    
    </form>
</div>