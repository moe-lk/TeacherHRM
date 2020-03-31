<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
include_once '../approveProcessfunction.php';
//include('js/common.js.php'); 
include('js/ajaxloadpage.js.php'); 

$cat="applyForTraining";
$tblName="TG_TeacherTrainingCall";
$uploadPath="trainingcallapplication/";
$uploadPathApplication="teachertrainingapplication/";
//echo $loggedPositionName;
if($loggedPositionName=='TEACHER')$type1="T"; 
if($loggedPositionName=='PRINCIPAL' || $loggedPositionName=='Principle')$type1="P"; 
//echo $teacherType=$schoolType."".$type1;
/* $countTotal="SELECT * FROM $tblName";//where SchoolID='$loggedSchool'

$TotaRows=$db->rowCount($countTotal); */

if($fm==''){
	$sqlList="Select ID,Title,TrainingCode,TrainingFor,CONVERT(varchar(20),StartDate,121) AS StartDate, CONVERT(varchar(20),EndDate,121) AS EndDate,CONVERT(varchar(20),ClosingDate,121) AS ClosingDate,TrainingDescription From $tblName where TrainingFor like '%,$teacherType,%'";
	$TotaRows=$db->rowCount($sqlList);
	$editBut='N';
	
}else if($fm=='E' || $fm=='V'){
	$approvalListSql="Select ID,Title,TrainingCode,TrainingFor,CONVERT(varchar(20),StartDate,121) AS StartDate, CONVERT(varchar(20),EndDate,121) AS EndDate,CONVERT(varchar(20),ClosingDate,121) AS ClosingDate,CONVERT(varchar(20),ApplyDate,121) AS ApplyDate,TrainingDescription,GenerateFrom,Reference From $tblName where ID='$tpe'";

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
                  <td align="right"></td>
           </tr>
			  <tr>
                  <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="5%" height="25" align="center" bgcolor="#999999">#</td>
                      <td width="30%" align="center" bgcolor="#999999">Training</td>
                      <td width="9%" align="center" bgcolor="#999999">Start Date</td>
                      <td width="10%" align="center" bgcolor="#999999">End Date</td>
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
                      <td bgcolor="#FFFFFF"><?php echo $row['TrainingCode']; ?>&nbsp;|&nbsp;<?php echo $row['Title']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['StartDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['EndDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['ClosingDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $trainingText; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php if($editBut=='Y'){?><a href="<?php echo $ttle ?>-<?php echo $pageid ?>--<?php echo $RequestID ?>-E.html">Edit</a>&nbsp;|&nbsp;<?php }?><a href="<?php echo $ttle ?>-<?php echo $pageid ?>---V-<?php echo $RequestID ?>.html">View/Apply</a><!--&nbsp;|&nbsp;<a href="javascript:aedWin('<?php echo $RequestID ?>','D','','<?php echo $tblName ?>','<?php echo "$ttle-$pageid.html";?>')">Delete </a><?php //echo $Expr1 ?></a>--></td>
                    </tr>
                   <?php }?>
                  </table></td>
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
                      <td bgcolor="#EDEEF3"><input name="Title" type="text" class="input2" id="Title" disabled="disabled" value="<?php echo $Title ?>"/></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top" bgcolor="#F7E2DD">Training Code :</td>
                      <td bgcolor="#EDEEF3"><input name="TrainingCode" type="text" class="input2" id="TrainingCode" disabled="disabled" value="<?php echo $TrainingCode ?>"/></td>
                    </tr>
                    <tr>
                      <td width="23%" align="left" valign="top" bgcolor="#F7E2DD">Training For  :<?php $valuePri=explode(",",$TrainingFor);?></td>
                      <td width="77%" bgcolor="#EDEEF3"><table width="100%" cellspacing="1" cellpadding="1">
                        <tr>
                          <td width="4%"><input name="T1" type="checkbox" class="check1" id="T1" value="NT" disabled="disabled" <?php if(in_array("NT",$valuePri)){ echo "checked";}?>/></td>
                          <td width="96%">National School Teachers</td>
                        </tr>
                        <tr>
                          <td><input name="T2" type="checkbox" class="check1" id="T2" value="NP" disabled="disabled" <?php if(in_array("NP",$valuePri)){ echo "checked";}?>/></td>
                          <td>National School Principlas</td>
                        </tr>
                        <tr>
                          <td><input name="T3" type="checkbox" class="check1" id="T3" value="PT" disabled="disabled" <?php if(in_array("PT",$valuePri)){ echo "checked";}?>/></td>
                          <td>Provincial School Teachers</td>
                        </tr>
                        <tr>
                          <td><input name="T4" type="checkbox" class="check1" id="T4" value="PP" disabled="disabled" <?php if(in_array("PP",$valuePri)){ echo "checked";}?>/></td>
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
                            <td width="93%">
                      
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
                      
                </td>
                          </tr>
                      </table></td>
                    </tr>
                    
                    <tr>
                      <td valign="top" bgcolor="#F7E2DD">Description :</td>
                      <td bgcolor="#EDEEF3"><textarea name="TrainingDescription" cols="85" rows="5" class="textarea1auto" disabled="disabled" id="TrainingDescription"><?php echo $TrainingDescription ?></textarea></td>
                    </tr>
                    <tr>
                      <td align="left" valign="top" bgcolor="#F7E2DD">Reference :</td>
                      <td bgcolor="#EDEEF3">
					  <?php if($Reference!=''){?><a href="<?php echo $uploadPath."".$Reference; ?>" target="_blank">View File</a><?php }else{ echo "File not found.";} ?></td>
                  </tr>
                    <tr>
                      <td valign="top" bgcolor="#F7E2DD">Create Date :</td>
                      <td bgcolor="#EDEEF3"><?php echo $ApplyDate ?></td>
                    </tr>
       	        </table></td>
      	    </tr>
        	  <tr>
        	    <td>&nbsp;</td>
      	    </tr>
        	  <tr>
        	    <td><table width="100%" cellspacing="1" cellpadding="1">
        	      <tr>
        	        <td bgcolor="#CFF1D1">Application :</td>
        	        <td bgcolor="#CFD7FE"><input type="file" name="ApplicationDoc" id="ApplicationDoc" />
				    <?php if($ApplicationDoc!=''){?><a href="<?php echo $uploadPath."".$ApplicationDoc; ?>" target="_blank">View File</a><?php }else{ echo "File not found.";} ?></td>
      	        </tr>
        	      <tr>
        	        <td bgcolor="#CFF1D1" valign="top">Remark :</td>
        	        <td bgcolor="#CFD7FE"><textarea name="Remarks" cols="85" rows="5" class="textarea1auto" id="Remarks"><?php echo $Remarks ?></textarea></td>
      	        </tr>
        	      <tr>
        	        <td width="23%">&nbsp;</td>
        	        <td width="77%"><input type="hidden" name="cat" value="<?php echo $cat ?>" />
                      <input type="hidden" name="tblName" value="<?php echo $tblName ?>" />
                      <input type="hidden" name="TrainingCallID" value="<?php echo $tpe ?>" />
                      <input type="hidden" name="AED" value="<?php echo $fm ?>" />
                      <input type="hidden" name="vID" value="<?php echo $id ?>" />
                      <input type="hidden" name="GenerateFrom" value="<?php echo $GenerateFrom ?>" />
                      <input type="hidden" name="ApplyNIC" value="<?php echo $NICUser ?>" />
                      <?php /* if($fm!='V'){ */?> <input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value=""/><?php /* } */?></td>
      	        </tr>
      	      </table></td>
      	    </tr>
        	  <tr>
        	    <td><table width="100%" cellspacing="2" cellpadding="2">
                    
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
              </table>
              <?php }?>
    </div>
    
    </form>
</div>