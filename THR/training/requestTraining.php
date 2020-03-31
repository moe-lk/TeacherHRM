<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
include_once '../approveProcessfunction.php';

$cat="requestTeacherTraining";
$tblName="TG_TeacherRequestTraining";
$approvetype="RequestTeacherTraining";
$uploadPath="trainingrequestfiles/";

if($fm=='' || $fm=='A'){
	$countTotal="SELECT ID,Title,CONVERT(varchar(20),StartDate,121) AS StartDate, CONVERT(varchar(20),EndDate,121) AS EndDate,Venue,Description,NoofSessions  FROM $tblName where NIC='$NICUser'";//where SchoolID='$loggedSchool'

	$TotaRows=$db->rowCount($countTotal);
	
	//echo $loggedSchool;
	
	
	$ApplyDate=date('Y-m-d');
	
	$checkAvai="SELECT ID from TG_Request_Approve where RequestType='$approvetype' and RequestID='$id' and ApprovedStatus='A'";
	$TotaRowsProc=$db->rowCount($checkAvai);
	$editBut="Y";
	if($TotaRowsProc>0)$editBut="N";
	
	
}else if($fm=='V' || $fm=='E'){
	$approvalListSql="SELECT ID,Title,CONVERT(varchar(20),StartDate,121) AS StartDate, CONVERT(varchar(20),EndDate,121) AS EndDate,CONVERT(varchar(20),ApplyDate,121) AS ApplyDate,Venue,Description,NoofSessions,NIC,SchoolID  FROM $tblName where ID='$id'";

	$stmtApp = $db->runMsSqlQuery($approvalListSql);
	while ($rowApp = sqlsrv_fetch_array($stmtApp, SQLSRV_FETCH_ASSOC)) {
		$InstCode=$loggedSchool=trim($rowApp['SchoolID']);
		$NIC=$NICUser=trim($rowApp['NIC']);
		$Title=stripslashes($rowApp['Title']);
		$StartDate=$rowApp['StartDate'];
		$EndDate=$rowApp['EndDate'];
		$Venue=stripslashes($rowApp['Venue']);
		$Description=stripslashes($rowApp['Description']);
		$NoofSessions=$rowApp['NoofSessions'];
		$IsApproved=$rowApp['IsApproved'];
		$ApplyDate=$rowApp['ApplyDate'];
	}
	
	$checkAvai="SELECT ID from TG_Request_Approve where RequestType='$approvetype' and RequestID='$id' and ApprovedStatus='A'";
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
        <table width="100%" cellpadding="0" cellspacing="0">
        <?php if($id=='' && $fm==''){?>
                <tr>
                  <td>
                    <table width="100%" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="left"><?php echo $TotaRows ?> Record(s) found.</td>
                        <td align="right"><a href="requestTraining-2---A.html"><img src="../cms/images/addnew.png" width="90" height="26" alt="addnew" /></a></td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                    <td bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="2%" height="25" align="center" bgcolor="#999999">#</td>
                        <td width="19%" align="center" bgcolor="#999999">Teacher Details</td>
                        <td width="14%" align="center" bgcolor="#999999">Training</td>
                        <td width="13%" align="center" bgcolor="#999999">Start Date</td>
                        <td width="9%" align="center" bgcolor="#999999">End Date</td>
                        <td width="7%" align="center" bgcolor="#999999">Venue</td>
                        <td width="7%" align="center" bgcolor="#999999">Sessions</td>
                        <td width="16%" align="center" bgcolor="#999999">Status</td>
                        <td width="13%" align="center" bgcolor="#999999">Delete</td>
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
					 
					$sqlDetails="SELECT        TeacherMast.SurnameWithInitials, CD_CensesNo.InstitutionName, TG_TeacherRequestTraining.ID
FROM            TeacherMast INNER JOIN
                         TG_TeacherRequestTraining ON TeacherMast.NIC = TG_TeacherRequestTraining.NIC INNER JOIN
                         CD_CensesNo ON TG_TeacherRequestTraining.SchoolID = CD_CensesNo.CenCode
WHERE        (TG_TeacherRequestTraining.ID = '$RequestID')";
					 $stmtApp = $db->runMsSqlQuery($sqlDetails);
                     while ($rowApp = sqlsrv_fetch_array($stmtApp, SQLSRV_FETCH_ASSOC)) {
						$SurnameWithInitials=$rowApp['SurnameWithInitials'];
						$InstitutionName=$rowApp['InstitutionName'];
						//$ApprovedStatus=$rowApp['ApprovedStatus'];
						//$ApprovedStatus=$rowApp['ApprovedStatus'];
					 }
					 
					$checkAvai="SELECT ID from TG_Request_Approve where RequestType='$approvetype' and RequestID='$RequestID' and ApprovedStatus='A'";
					$TotaRowsProc=$db->rowCount($checkAvai);
					$editBut="Y";
					if($TotaRowsProc>0)$editBut="N";
					?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $SurnameWithInitials; ?><br /><?php echo $InstitutionName ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['Title'];; ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['StartDate']; ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['EndDate']; ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['Venue']; ?></td>
                        <td bgcolor="#FFFFFF"><a href="requestTrainingSessions-2a----<?php echo $RequestID ?>.html"><?php echo $row['NoofSessions']; ?>&nbsp;(View)</a></td>
                        <td bgcolor="#FFFFFF"><?php echo $listAp ?></td>
                        <td bgcolor="#FFFFFF" align="center"><?php if($editBut=='Y'){?><a href="requestTraining-<?php echo $pageid ?>--<?php echo $RequestID ?>-E.html">Edit</a>&nbsp;|&nbsp;<?php }?><a href="requestTraining-<?php echo $pageid ?>--<?php echo $RequestID ?>-V.html">View</a>&nbsp;|&nbsp;<a href="javascript:aedWin('<?php echo $RequestID ?>','D','','<?php echo $tblName ?>','<?php echo "$ttle-$pageid.html";?>')">Delete</a></td>
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
                      <td align="left" valign="top">Training :</td>
                      <td><input name="Title" type="text" class="input2" id="Title" value="<?php echo $Title ?>"/></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">Description :</td>
                      <td><textarea name="Description" cols="100" rows="5" class="textarea1auto" id="Description"><?php echo $Description ?></textarea></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">Venue :</td>
                      <td><input name="Venue" type="text" class="input2" id="Venue" value="<?php echo $Venue ?>"/></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">No. of Sessions :</td>
                      <td><input name="NoofSessions" type="text" class="input4" id="NoofSessions" value="<?php echo $NoofSessions ?>"/></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">Reference :</td>
                      <td><input type="file" name="Reference" id="Reference"/><?php if($Reference!=''){?><a href="<?php echo $uploadPath."".$Reference; ?>" target="_blank">View File</a><?php }else{ echo "File not found.";} ?></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">Start Date :</td>
                      <td>
                        <table width="100%" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="7%"><input name="StartDate" type="text" class="input3new" id="StartDate" value="<?php echo $StartDate; ?>" size="10" style="height:20px; line-height:20px;" readonly/>
                      </td>
                            <td width="93%">
                      <input name="f_trigger_1" type="image" id="f_trigger_1" src="../cms/images/calender_icon.gif" align="top" width="16" height="16"  />
                  <script type="text/javascript">
                            //2005-10-03 11:46:00 
                                Calendar.setup({
                                inputField     :    "StartDate",      // id of the input field
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
                      <td align="left" valign="top">End Date :</td>
                      <td><table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                          <td width="7%"><input name="EndDate" type="text" class="input3new" id="EndDate" value="<?php echo $EndDate; ?>" size="10" style="height:20px; line-height:20px;" readonly="readonly"/></td>
                          <td width="93%"><input name="f_trigger_2" type="image" id="f_trigger_2" src="../cms/images/calender_icon.gif" align="top" width="16" height="16"  />
                            <script type="text/javascript">
                            //2005-10-03 11:46:00 
                                Calendar.setup({
                                inputField     :    "EndDate",      // id of the input field
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
                      <td>Apply Date :</td>
                      <td><?php echo $ApplyDate ?></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td><input type="hidden" name="ApplyDate" value="<?php echo $ApplyDate ?>" />
                      <input type="hidden" name="cat" value="<?php echo $cat ?>" />
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