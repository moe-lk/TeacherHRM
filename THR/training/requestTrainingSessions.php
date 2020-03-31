<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
include_once '../approveProcessfunction.php';

$cat="requestTeacherTrainingSessions";
$tblName="TG_TeacherRequestTrainingSessions";
$approvetype="RequestTeacherTraining";

if($fm=='A'){//Add new
	
	$ApplyDate=date('Y-m-d');
	
	$trainingDet="SELECT [ID]
      ,[NIC]
      ,[SchoolID]
      ,[Title]
  FROM [dbo].[TG_TeacherRequestTraining] Where ID='$tpe'";	
  
  	$stmtApp = $db->runMsSqlQuery($trainingDet);
	while ($rowApp = sqlsrv_fetch_array($stmtApp, SQLSRV_FETCH_ASSOC)) {
		$InstCode=$loggedSchool=trim($rowApp['SchoolID']);
		$NIC=$NICUser=trim($rowApp['NIC']);
		$TitleTrainig=stripslashes($rowApp['Title']);
	}
	$checkAvai="SELECT ID from TG_Request_Approve where RequestType='RequestTeacherTraining' and RequestID='$tpe' and ApprovedStatus='A'";
	$TotaRowsProc=$db->rowCount($checkAvai);
	$editBut="Y";
	if($TotaRowsProc>0)$editBut="N";
	
	
}else if($fm==''){ //List View
	$approvalListSql="SELECT ID,Title,CONVERT(varchar(20),StartDate,121) AS StartDate, CONVERT(varchar(20),EndDate,121) AS EndDate,NoofHours,Description,MonitoredBy,MonitoredDate,MonitorRemarks  FROM $tblName where TrainingID='$tpe'";//where SchoolID='$loggedSchool'

	$TotaRows=$db->rowCount($approvalListSql);
	
	$trainingDet="SELECT [ID]
      ,[NIC]
      ,[SchoolID]
      ,[Title]
  FROM [dbo].[TG_TeacherRequestTraining] Where ID='$tpe'";	
  
  	$stmtApp = $db->runMsSqlQuery($trainingDet);
	while ($rowApp = sqlsrv_fetch_array($stmtApp, SQLSRV_FETCH_ASSOC)) {
		$InstCode=$loggedSchool=trim($rowApp['SchoolID']);
		$NIC=$NICUser=trim($rowApp['NIC']);
		$TitleTrainig=stripslashes($rowApp['Title']);
	}
	
	$sql = "SELECT NIC, SurnameWithInitials      
  FROM TeacherMast
  where NIC='$NICUser'";
	$stmt = $db->runMsSqlQuery($sql);
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$SurnameWithInitials=$row['SurnameWithInitials'];
	}
	$sqlFormDate="Select InstitutionName from CD_CensesNo where CenCode='$loggedSchool'";
	$stmtFormData = $db->runMsSqlQuery($sqlFormDate);
	while ($rowForm = sqlsrv_fetch_array($stmtFormData, SQLSRV_FETCH_ASSOC)) {
		$InstitutionName = $rowForm['InstitutionName'];
	}
					
							
	
}else /* if($fm=='E') */{
	$approvalListSql="SELECT ID,Title,CONVERT(varchar(20),StartDate,121) AS StartDate, CONVERT(varchar(20),EndDate,121) AS EndDate,NoofHours,Description,MonitoredBy,MonitoredDate,MonitorRemarks,CONVERT(varchar(20),ApplyDate,121) AS ApplyDate  FROM $tblName where ID='$id'";//where SchoolID='$loggedSchool'

	$stmtAppS = $db->runMsSqlQuery($approvalListSql);
	while ($rowApp = sqlsrv_fetch_array($stmtAppS, SQLSRV_FETCH_ASSOC)) {
		$NoofHours=trim($rowApp['NoofHours']);
		$Title=stripslashes($rowApp['Title']);
		$StartDate=stripslashes($rowApp['StartDate']);
		$EndDate=stripslashes($rowApp['EndDate']);
		$Description=stripslashes($rowApp['Description']);
		$MonitoredBy=stripslashes($rowApp['MonitoredBy']);
		$TitleTrainig=stripslashes($rowApp['MonitoredDate']);
		$MonitorRemarks=stripslashes($rowApp['MonitorRemarks']);
		$ApplyDate=stripslashes($rowApp['ApplyDate']);
	}
	
	$trainingDet="SELECT [ID]
      ,[NIC]
      ,[SchoolID]
      ,[Title]
  FROM [dbo].[TG_TeacherRequestTraining] Where ID='$tpe'";	
  
  	$stmtApp = $db->runMsSqlQuery($trainingDet);
	while ($rowApp = sqlsrv_fetch_array($stmtApp, SQLSRV_FETCH_ASSOC)) {
		$InstCode=$loggedSchool=trim($rowApp['SchoolID']);
		$NIC=$NICUser=trim($rowApp['NIC']);
		$TitleTrainig=stripslashes($rowApp['Title']);
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
        <table width="945" cellpadding="0" cellspacing="0">
        <?php if($tpe!='' && $fm==''){?>
                <tr>
                  <td>
                    <table width="100%" cellspacing="0" cellpadding="0">
                      <tr>
                        <td align="left"><?php echo $TotaRows ?> Record(s) found.</td>
                        <td align="right"><a href="requestTraining-<?php echo $pageid;?>---A-<?php echo $tpe ?>.html">Add New</a></td>
                      </tr>
                  </table></td>
                </tr>
                <tr>
                    <td bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="2%" height="25" align="center" bgcolor="#999999">#</td>
                        <td width="19%" align="center" bgcolor="#999999">Teacher Details</td>
                        <td width="14%" align="center" bgcolor="#999999">Session</td>
                        <td width="13%" align="center" bgcolor="#999999">Start Date</td>
                        <td width="9%" align="center" bgcolor="#999999">End Date</td>
                        <td width="7%" align="center" bgcolor="#999999">No of Hours</td>
                        <td width="16%" align="center" bgcolor="#999999">Review</td>
                        <td width="13%" align="center" bgcolor="#999999">Delete</td>
                      </tr>
                      <?php 
					$i=1;
					$stmt = $db->runMsSqlQuery($approvalListSql);
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$RequestID=$row['ID'];
					 
					 
					 $checkAvai="SELECT ID from TG_Request_Approve where RequestType='RequestTeacherTraining' and RequestID='$tpe' and ApprovedStatus='A'";
					$TotaRowsProc=$db->rowCount($checkAvai);
					$editBut="Y";
					if($TotaRowsProc>0)$editBut="N";
					?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $SurnameWithInitials; ?><br /><?php echo $InstitutionName ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['Title'];; ?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php echo $row['StartDate']; ?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php echo $row['EndDate']; ?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php echo $row['NoofHours']; ?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php echo $listAp ?></td>
                        <td bgcolor="#FFFFFF" align="center"><?php if($editBut=='Y'){?><a href="requestTraining-<?php echo $pageid ?>--<?php echo $RequestID ?>-E-<?php echo $tpe ?>.html">Edit</a>&nbsp;|&nbsp;<?php }?><a href="requestTraining-<?php echo $pageid;?>--<?php echo $RequestID;?>-V-<?php echo $tpe ?>.html">View</a>&nbsp;|&nbsp;<a href="javascript:aedWin('<?php echo $RequestID?>','D','','<?php echo $tblName;?>','<?php echo "$ttle-$pageid---V-$tpe.html";?>')">Delete</a></td>
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
                      <td align="left" valign="top">Training :</td>
                      <td><?php echo $TitleTrainig ?></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">Task :</td>
                      <td><input name="Title" type="text" class="input2" id="Title" value="<?php echo $Title ?>"/></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">Description :</td>
                      <td><textarea name="Description" cols="100" rows="5" class="textarea1auto" id="Description"><?php echo $Description ?></textarea></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top">No. of Hours :</td>
                      <td><input name="NoofHours" type="text" class="input4" id="NoofHours" value="<?php echo $NoofHours ?>"/></td>
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
                      <td>
                      <input type="hidden" name="ApplyDate" value="<?php echo $ApplyDate ?>" />
                      <input type="hidden" name="cat" value="<?php echo $cat ?>" />
                      <input type="hidden" name="tblName" value="<?php echo $tblName ?>" />
                      <input type="hidden" name="TrainingID" value="<?php echo $tpe ?>" />
                      <input type="hidden" name="AED" value="<?php echo $fm ?>" />
                      <input type="hidden" name="vID" value="<?php echo $id ?>" />
                      <?php if($fm!='V'){?>
                      <input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" />
                      <?php }?></td>
                    </tr>
                    </table>
        </td>
        </tr>
       
         <?php }?>
              </table>
    </div>
    
    </form>
</div>