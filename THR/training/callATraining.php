<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
include_once '../approveProcessfunction.php';
//include('js/common.js.php'); 
include('js/ajaxloadpage.js.php'); 

$cat="callATraining";
$tblName="TG_TeacherTrainingCall";
$uploadPath="trainingcallapplication/";

/* $countTotal="SELECT * FROM $tblName";//where SchoolID='$loggedSchool'

$TotaRows=$db->rowCount($countTotal); */
//echo $NICUser;
if($fm==''){
	$sqlList="Select ID,Title,TrainingCode,TrainingFor,CONVERT(varchar(20),StartDate,121) AS StartDate, CONVERT(varchar(20),EndDate,121) AS EndDate,CONVERT(varchar(20),ClosingDate,121) AS ClosingDate,TrainingDescription From $tblName where Title!='' order by ID desc";
	
	//echo $sqlListApply="Select ID,Title,Remarks,IsApproved,CONVERT(varchar(20),ApplyDate,121) AS ApplyDate,ApplicationDoc From TG_TeacherTrainingCall where ApplyNIC='$NICUser'";
	
	/*  */
	$TotaRows=$db->rowCount($sqlList);
	$editBut='Y';
	
}else if($fm=='E' || $fm=='V'){
	$sqlListApply="Select ID,TrainingCallID,Remarks,IsApproved,CONVERT(varchar(20),ApplyDate,121) AS ApplyDate,ApplicationDoc From TG_TeacherTrainingCallApply where id='$id'";
	$stmtApl = $db->runMsSqlQuery($sqlListApply);
		$rowApl= sqlsrv_fetch_array($stmtApl, SQLSRV_FETCH_ASSOC);
		$ApplyDate=trim($rowApl['ApplyDate']);
		$Remarks=trim($rowApl['Remarks']);
		$ApplicationDoc=trim($rowApl['ApplicationDoc']);
		$TrainingCallID=$rowApl['TrainingCallID'];
		
	$approvalListSql="Select ID,Title,TrainingCode,TrainingFor,CONVERT(varchar(20),StartDate,121) AS StartDate, CONVERT(varchar(20),EndDate,121) AS EndDate,CONVERT(varchar(20),ClosingDate,121) AS ClosingDate,CONVERT(varchar(20),ApplyDate,121) AS ApplyDate,TrainingDescription,GenerateFrom,Reference From TG_TeacherTrainingCall where id='$TrainingCallID'";

	$stmtApp = $db->runMsSqlQuery($approvalListSql);
	while ($rowApp = sqlsrv_fetch_array($stmtApp, SQLSRV_FETCH_ASSOC)) {
		$TrainingFor=trim($rowApp['TrainingFor']);
		$TrainingCode=trim($rowApp['TrainingCode']);
		$StartDate=$rowApp['StartDate'];
		$EndDate=$rowApp['EndDate'];
		$ApplyDate=$rowApp['ApplyDate'];
		$TrainingDescription=stripslashes($rowApp['TrainingDescription']);
		$Title=stripslashes($rowApp['Title']);
		$ClosingDate=$rowApp['ClosingDate'];
		$GenerateFrom=trim($rowApp['GenerateFrom']);
		$Reference=$rowApp['Reference'];
	}
	
	
		
	
}else{
	$ApplyDate=date('Y-m-d');
	$GenerateFrom=$NICUser;
}

?>


<div class="main_content_inner_block">

    <form method="post" action="save.php" name="frmSaveT" id="frmSaveT" enctype="multipart/form-data" onSubmit="return check_form(frmSaveT);">
        <?php if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
   	  <div class="mcib_middle1">
          <div class="mcib_middle_full">
          <div class="form_error"><?php echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
        </div>
        <?php }?>
        <?php if($id=='' && $fm==''){?>
         <table width="100%" cellpadding="0" cellspacing="0">
       
        	<tr>
              <td><?php echo $TotaRows ?> Record(s) found.</td>
                  <td align="right"><a href="<?php echo $ttle ?>-<?php echo $pageid ?>---A.html"><img src="../cms/images/addnew.png" width="90" height="26" alt="addnew" /></a></td>
           </tr>
			  <tr>
                  <td colspan="2" bgcolor="#CCCCCC">             
                  
                  <table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="5%" height="25" align="center" bgcolor="#999999">#</td>
                      <td width="30%" align="center" bgcolor="#999999">Training</td>
                      <td width="10%" align="center" bgcolor="#999999">Start Date</td>
                      <td width="9%" align="center" bgcolor="#999999">End Date</td>
                      <td width="10%" align="center" bgcolor="#999999">Closing Date</td>
                      <td width="26%" align="center" bgcolor="#999999">Training For</td>
                      <td width="10%" align="center" bgcolor="#999999">Action</td>
                    </tr>
                    <?php 
					$j=1;
					$stmt = $db->runMsSqlQuery($sqlList);
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$RequestID=$row['ID'];
						$trainingText="";
						$trainingArr=explode(",",$row['TrainingFor']);
						for($i=0;$i<count($trainingArr);$i++){
							$tainin=$trainingArr[$i];
							if($tainin=='NT')$trainingText.="National School Teachers, ";
							if($tainin=='NP')$trainingText.="National School Principal, ";
							if($tainin=='PT')$trainingText.="Provincial School Teachers, ";
							if($tainin=='PP')$trainingText.="Provincial School Principal";
						}
					?>
                    <tr>
                      <td height="20" bgcolor="#FFFFFF"><?php echo $j++; ?></td>
                      <td valign="top" bgcolor="#FFFFFF"><?php echo $row['TrainingCode']; ?>&nbsp;|&nbsp;<?php echo $row['Title']; ?></td>
                      <td align="center" valign="top" bgcolor="#FFFFFF"><?php echo $row['StartDate']; ?></td>
                      <td align="center" valign="top" bgcolor="#FFFFFF"><?php echo $row['EndDate']; ?></td>
                      <td align="center" valign="top" bgcolor="#FFFFFF"><?php echo $row['ClosingDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $trainingText; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><a href="javascript:aedWin('<?php echo $RequestID ?>','D','','<?php echo $tblName ?>','<?php echo "$ttle-$pageid.html";?>')">Delete </a><?php //echo $Expr1 ?></a></td>
                    </tr>
                   <?php }?>
                  </table>
                </td>
          </tr>
         
                <tr>
                  <td width="56%">&nbsp;</td>
                  <td width="44%">&nbsp;</td>
                </tr>
          
              </table>
        <?php }else{?>
        <table width="945" cellpadding="0" cellspacing="0">
        	 
       	  <tr>
        	    <td align="right"><a href="<?php echo $ttle ?>-<?php echo $pageid ?>.html"><img src="../cms/images/viewList.png" width="90" height="26" alt="viewlist" /></a></td>
      	    </tr>
        	  <tr>
        	    <td><table width="100%" cellspacing="1" cellpadding="1">
        	      
                    <tr>
                      <td bgcolor="#F7E2DD">Title :</td>
                      <td bgcolor="#EDEEF3"><input name="Title" type="text" class="input2" id="Title" value="<?php echo $Title ?>"/></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top" bgcolor="#F7E2DD">Training Code :</td>
                      <td bgcolor="#EDEEF3"><input name="TrainingCode" type="text" class="input2" id="TrainingCode" value="<?php echo $TrainingCode ?>"/></td>
                    </tr>
                    <tr>
                      <td width="23%" align="left" valign="top" bgcolor="#F7E2DD">Training For  :<?php $valuePri=explode(",",$TrainingFor);?></td>
                      <td width="77%" bgcolor="#EDEEF3"><table width="100%" cellspacing="1" cellpadding="1">
                        <tr>
                          <td width="4%"><input name="T1" type="checkbox" class="check1" id="T1" value="NT" <?php if(in_array("NT",$valuePri)){ echo "checked";}?>/></td>
                          <td width="96%">National School Teachers</td>
                        </tr>
                        <tr>
                          <td><input name="T2" type="checkbox" class="check1" id="T2" value="NP" <?php if(in_array("NP",$valuePri)){ echo "checked";}?>/></td>
                          <td>National School Principlas</td>
                        </tr>
                        <tr>
                          <td><input name="T3" type="checkbox" class="check1" id="T3" value="PT" <?php if(in_array("PT",$valuePri)){ echo "checked";}?>/></td>
                          <td>Provincial School Teachers</td>
                        </tr>
                        <tr>
                          <td><input name="T4" type="checkbox" class="check1" id="T4" value="PP" <?php if(in_array("PP",$valuePri)){ echo "checked";}?>/></td>
                          <td>Provincial School Principlas</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#F7E2DD">Start Date :</td>
                      <td bgcolor="#EDEEF3"><table width="100%" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="7%"><input name="StartDate" type="text" class="input3new" id="StartDate" value="<?php echo $StartDate; ?>" size="10" style="height:20px; line-height:20px;" readonly/>
                      </td>
                            <td width="93%"><input name="f_trigger_3" type="image" id="f_trigger_3" src="../cms/images/calender_icon.gif" align="top" width="16" height="16"  />
                  <script type="text/javascript">
                            //2005-10-03 11:46:00 
                                Calendar.setup({
                                inputField     :    "StartDate",      // id of the input field
                                ifFormat       :    "%Y-%m-%d",       // format of the input field
                                showsTime      :    false,            // will display a time selector
                                button         :    "f_trigger_3",   // trigger for the calendar (button ID)
                                singleClick    :    true,           // double-click mode
                                step           :    1                // show all years in drop-down boxes (instead of every other year as default)
                            });
                          </script>
                      
                </td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#F7E2DD">End Date :</td>
                      <td bgcolor="#EDEEF3"><table width="100%" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="7%"><input name="EndDate" type="text" class="input3new" id="EndDate" value="<?php echo $EndDate; ?>" size="10" style="height:20px; line-height:20px;" readonly/>
                      </td>
                            <td width="93%">
                      <input name="f_trigger_1" type="image" id="f_trigger_2" src="../cms/images/calender_icon.gif" align="top" width="16" height="16"  />
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
                          </script>
                </td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#F7E2DD">Closing Date :</td>
                      <td bgcolor="#EDEEF3"><table width="100%" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="7%"><input name="ClosingDate" type="text" class="input3new" id="ClosingDate" value="<?php echo $ClosingDate; ?>" size="10" style="height:20px; line-height:20px;" readonly/>
                      </td>
                            <td width="93%">
                      <input name="f_trigger_1" type="image" id="f_trigger_1" src="../cms/images/calender_icon.gif" align="top" width="16" height="16"  />
                  <script type="text/javascript">
                            //2005-10-03 11:46:00 
                                Calendar.setup({
                                inputField     :    "ClosingDate",      // id of the input field
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
                      <td valign="top" bgcolor="#F7E2DD">Description :</td>
                      <td bgcolor="#EDEEF3"><textarea name="TrainingDescription" cols="85" rows="5" class="textarea1auto" id="TrainingDescription"><?php echo $TrainingDescription ?></textarea></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top" bgcolor="#F7E2DD">Reference :</td>
                      <td bgcolor="#EDEEF3"><input type="file" name="Reference" id="Reference" />
				    <?php if($Reference!=''){?><a href="<?php echo $uploadPath."".$Reference; ?>" target="_blank">View File</a><?php }else{ echo "File not found.";} ?></td>
                  </tr>
                    <tr>
                      <td align="left" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      <td bgcolor="#FFFFFF"><input type="hidden" name="cat" value="<?php echo $cat ?>" />
                      <input type="hidden" name="tblName" value="<?php echo $tblName ?>" />
                      <input type="hidden" name="NIC" value="<?php echo $NICUser ?>" />
                      <input type="hidden" name="SchoolID" value="<?php echo $loggedSchool ?>" />
                      <input type="hidden" name="AED" value="<?php echo $fm ?>" />
                      <input type="hidden" name="vID" value="<?php echo $id ?>" />
                      <input type="hidden" name="ApplyDate" value="<?php echo $ApplyDate ?>" />
                      <input type="hidden" name="GenerateFrom" value="<?php echo $GenerateFrom ?>" />
                      <?php if($fm!='V'){?>
                      <input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" />
                      <?php }?></td>
                  </tr>
       	        </table></td>
      	    </tr>
        	  <tr>
        	    <td>&nbsp;</td>
      	    </tr>
        	  <tr>
        	    <td>&nbsp;</td>
      	    </tr>
        	  <tr>
        	    <td>&nbsp;</td>
      	    </tr>
            <?php 
   $i=1;
   $sqlLeave="SELECT        TG_Request_Approve.id, TG_Request_Approve.RequestUserNIC, TG_Request_Approve.ApprovelUserNIC, TG_Request_Approve.ApproveUserNominatorNIC, 
                         TG_Request_Approve.ApproveProcessOrder, TG_Request_Approve.ApprovedStatus, TG_Request_Approve.DateTime, TG_Request_Approve.Remarks, 
                         TeacherMast.SurnameWithInitials, TG_ApprovalProcess.ApproveAccessRoleName
FROM            TG_Request_Approve INNER JOIN
                         TeacherMast ON TG_Request_Approve.ApprovelUserNIC = TeacherMast.NIC INNER JOIN
                         TG_ApprovalProcess ON TG_Request_Approve.ApprovalProcessID = TG_ApprovalProcess.ID
WHERE        (TG_Request_Approve.RequestType = 'ApplyForTraining') AND (TG_Request_Approve.RequestID = '$id')
ORDER BY TG_Request_Approve.ApproveProcessOrder";
$TotaRows=$db->rowCount($sqlLeave);
   $stmt = $db->runMsSqlQuery($sqlLeave);
                            while ($rowas = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					  
					  //$Expr1=$row['id'];
					  $ApproveAccessRoleName=trim($rowas['ApproveAccessRoleName']);
					  $SurnameWithInitials=trim($rowas['SurnameWithInitials']);
					  $Remarks=trim($rowas['Remarks']);
					  
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
        	    <td></td>
      	    </tr>
        	  <tr>
        	    <td>&nbsp;</td>
      	    </tr>
            <?php }?>
              </table>
              <?php }?>
    </div>
    
    </form>
</div>