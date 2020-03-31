<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
include_once '../approveProcessfunction.php';
//include('js/common.js.php'); 
include('js/ajaxloadpage.js.php'); 

$cat="transferTeacher";
$tblName="TG_TeacherTransfer";
$TransferType="TTR";
$approvetype="TransferTeacherNormal";

if($fm==''){
	$approvSql="SELECT        TG_TeacherTransfer.ID, TG_TeacherTransfer.TransferRequestType, TG_TeacherTransfer.TransferRequestTypeID, TG_TeacherTransfer.ExpectSchool, 
                         TG_TeacherTransfer.LikeToOtherSchool, TG_TeacherTransfer.ReasonForTransfer, TG_TeacherTransfer.ExtraActivities, CONVERT(varchar(20),TG_TeacherTransfer.RequestedDate,121) AS RequestedDate, 
                         TG_TeacherTransfer.IsApproved, CD_CensesNo.InstitutionName, TG_TeacherTransfer.TransferType, TG_TeacherTransfer.NIC
FROM            TG_TeacherTransfer INNER JOIN
                         CD_CensesNo ON TG_TeacherTransfer.SchoolID = CD_CensesNo.CenCode
WHERE        TG_TeacherTransfer.NIC ='$NICUser'";//(TG_TeacherTransfer.IsApproved = 'N') AND (

$TotaRows=$db->rowCount($approvSql);

	$checkAvai="SELECT ID from TG_Request_Approve where RequestType='$approvetype' and RequestID='$id' and ApprovedStatus='A'";
	$TotaRowsProc=$db->rowCount($checkAvai);
	$editBut="Y";
	if($TotaRowsProc>0)$editBut="N";

}else if($fm=='V'){
	$sqlTransf="SELECT        TransferType, TransferRequestType, ExpectSchool, LikeToOtherSchool, ReasonForTransfer, ExtraActivities, RequestedDate, IsApproved, SchoolID, NIC, ExpectSchool2, ExpectSchool3, ExpectSchool4, ExpectSchool5,ServiceHistoryID
FROM            $tblName
WHERE        (ID = '$id')";

		$stmtTr = $db->runMsSqlQuery($sqlTransf);
		while ($row = sqlsrv_fetch_array($stmtTr, SQLSRV_FETCH_ASSOC)) {
			$TransferType=$row['TransferType'];
			$TransferRequestType=trim($row['TransferRequestType']);
			$ExpectSchool=trim($row['ExpectSchool']);
			$LikeToOtherSchool=trim($row['LikeToOtherSchool']);
			$ReasonForTransfer=$row['ReasonForTransfer'];
			$ExtraActivities=$row['ExtraActivities'];
			$RequestedDate=$row['RequestedDate'];
			$IsApproved=$row['IsApproved'];
			$SchoolID=$loggedSchool=$row['SchoolID'];
			$NIC=$NICUser=trim($row['NIC']);
			$ExpectSchool2=$row['ExpectSchool2'];
			$ExpectSchool3=$row['ExpectSchool3'];
			$ExpectSchool4=$row['ExpectSchool4'];
			$ExpectSchool5=$row['ExpectSchool5'];
			$ServiceHistoryID=trim($row['ServiceHistoryID']);
		}
		$workOtherSchool="No";
		if($LikeToOtherSchool=='Y')$workOtherSchool="Yes";
		
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
         <table width="945" cellpadding="0" cellspacing="0">
       
        	<tr>
              <td><?php echo $TotaRows ?> Record(s) found.</td>
                  <td align="right"><a href="teacherRequest-1---A.html"><img src="../cms/images/addnew.png" width="90" height="26" alt="addnew" /></a></td>
           </tr>
			  <tr>
                  <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="5%" height="25" align="center" bgcolor="#999999">#</td>
                      <td width="28%" align="center" bgcolor="#999999">Working School</td>
                      <td width="14%" align="center" bgcolor="#999999">Request Date</td>
                      <td width="38%" align="center" bgcolor="#999999">Status</td>
                      <td width="15%" align="center" bgcolor="#999999">Action</td>
                    </tr>
                    <?php 
					$i=1;
					$stmt = $db->runMsSqlQuery($approvSql);
                     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$RequestID=$row['ID'];
						$listAp="";
					$approvalListSql="SELECT        TOP (1000) TG_Request_Approve.id, TG_Request_Approve.ApprovelUserNIC, TG_Request_Approve.ApproveUserNominatorNIC, 
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
					?>
                    <tr>
                      <td height="20" bgcolor="#FFFFFF"><?php echo $i++; ?></td>
                      <td bgcolor="#FFFFFF"><?php echo $row['InstitutionName']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['RequestedDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $listAp ?></td>
                      <td bgcolor="#FFFFFF" align="center">
					  <?php if($editBut=='Y'){?><a href="<?php echo $ttle ?>-<?php echo $pageid ?>--<?php echo $RequestID ?>-E.html">Edit</a>&nbsp;|&nbsp;<?php }?>
                      <a href="<?php echo $ttle ?>-<?php echo $pageid ?>--<?php echo $RequestID ?>-V.html">View</a>&nbsp;|&nbsp;
                      <a href="javascript:aedWin('<?php echo $RequestID ?>','D','','<?php echo $tblName ?>','<?php echo "$ttle-$pageid.html";?>')">Delete </a><?php //echo $Expr1 ?></a></td>
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
        	    <td bgcolor="#FFFFFF"><strong>Personal Information</strong></td>
      	    </tr>
        	  <tr>
        	    <td><?php include("teacherPersonalDetails.php");?></td>
      	    </tr>
        	  <tr>
        	    <td>&nbsp;</td>
      	    </tr>
        	  <tr>
        	    <td><strong>Career Information</strong></td>
      	    </tr>
        	  <tr>
        	    <td><?php include("teacherCareerDetails.php");?></td>
      	    </tr>
        	  <tr>
        	    <td>&nbsp;</td>
      	    </tr>
        	  <tr>
        	    <td><strong>Teaching Qualification</strong></td>
   	      </tr>
        	  <tr>
        	    <td><?php include("teacherTeachingDetails.php");?></td>
      	    </tr>
        	  <tr>
        	    <td>&nbsp;</td>
      	    </tr>
        	  <tr>
        	    <td bgcolor="#FFFFFF"><strong>Current Timetable</strong></td>
      	    </tr>
        	  <tr>
        	    <td bgcolor="#EDEEF3"><?php include("teacherCurrentTimeTable.php");?></td>
      	    </tr>
        	  <tr>
        	    <td>&nbsp;</td>
      	    </tr>
        	  <tr>
        	    <td><strong>Transfer Details</strong></td>
      	    </tr>
        	  <tr>
        	    <td><table width="100%" cellspacing="1" cellpadding="1">
               	 <tr>
        	        <td colspan="2" bgcolor="#FFFFFF"><?php include("selectSchool.php");?></td>
       	          </tr>
                  <tr>
                      <td bgcolor="#CFF1D1"><div id="zoneSchoolLable">Current Zone :</div></td>
                      <td bgcolor="#CFD7FE"><div id="zoneSchool"><?php echo ucwords(strtolower(($zonename))); ?>
                    <input type="hidden" name="TransferRequestTypeID" value="<?php echo $ZoneCode ?>" /></div></td>
                  </tr>
                   <?php if($fm=='DAD'){?>
        	      <tr>
        	        <td width="23%" bgcolor="#CFF1D1">Request Type :</td>
        	        <td width="77%" bgcolor="#CFD7FE"><select name="TransferRequestType" class="select2a_n" id="TransferRequestType" onchange="showSchoolTransferDet()">
                      <option value="WZ" <?php if($TransferRequestType=='WZ'){?>selected="selected"<?php }?>>Within the zone</option>
                      <option value="OZ" <?php if($TransferRequestType=='OZ'){?>selected="selected"<?php }?>>Other zone</option>
                      <option value="OP" <?php if($TransferRequestType=='OP'){?>selected="selected"<?php }?>>Other province</option>
                      <option value="NS" <?php if($TransferRequestType=='NS'){?>selected="selected"<?php }?>>National school</option>
                    </select></td>
       	          </tr>
        	     
                    
                  <?php }?>
                  	 <tr>
                      <td valign="top" bgcolor="#CFF1D1">Extra Activities :</td>
                      <td bgcolor="#CFD7FE"><div class="noofCharactor" >Select Multiple Activities by clicking with the &quot;Ctrl&quot; key .</div><select name="ExtraActivities[]" size="5" multiple="multiple" class="textarea1" id="ExtraActivities[]">
           <?php
            $iAvailTolArr = explode(',',$ExtraActivities);
			$sqlMS = "SELECT * FROM TG_TeacherExtraActivity where ActivityTitle!=''";
			$stmtMS = $db->runMsSqlQuery($sqlMS);
			while ($row = sqlsrv_fetch_array($stmtMS, SQLSRV_FETCH_ASSOC)) {
				$ActivityTitle=$row['ActivityTitle'];
				$ActivityID=$row['ID'];?>
				
			<option value="<?php echo $ActivityID ?>"
            <?php
				for($n=0;$n<count($iAvailTolArr);$n++){ 
					$SelectedKeywordca=trim($iAvailTolArr[$n]);
						if($ActivityID==$SelectedKeywordca){
							echo 'selected="selected"';
						}
						else{
							echo "";
						}
				}
            ?>
            ><?php echo $ActivityTitle; ?></option>
            <?php }?>

            </select></td>
                  </tr>
                    <tr>
                      <td valign="top" bgcolor="#CFF1D1" width="22%">Reason for Transfer :</td>
                      <td bgcolor="#CFD7FE" width="78%"><textarea name="ReasonForTransfer" cols="85" rows="5" class="textarea1auto" id="ReasonForTransfer"><?php echo $ReasonForTransfer ?></textarea></td>
                  </tr>
                    <tr>
                      <td bgcolor="#CFF1D1">Would like to Work Another School :</td>
                      <td bgcolor="#CFD7FE"><table width="30%" cellspacing="1" cellpadding="1">
                        <tr>
                          <td width="11%"><input type="radio" name="LikeToOtherSchool" id="radio" value="Y" <?php if($LikeToOtherSchool=='Y' || $fm=='' || $fm=='A'){?>checked="checked"<?php }?>/></td>
                          <td width="29%">Yes</td>
                          <td width="11%"><input type="radio" name="LikeToOtherSchool" id="radio2" value="N" <?php if($LikeToOtherSchool=='N'){?>checked="checked"<?php }?>/></td>
                          <td width="49%">No</td>
                        </tr>
                      </table></td>
                  </tr>
       	        </table></td>
      	    </tr>
        	  <tr>
        	    <td>&nbsp;</td>
      	    </tr>
            <tr>
        	    <td><table width="100%" cellspacing="1" cellpadding="1">
        	      <tr>
        	        <td width="23%">&nbsp;</td>
        	        <td width="77%"><input type="hidden" name="cat" value="<?php echo $cat ?>" />
                      <input type="hidden" name="tblName" value="<?php echo $tblName ?>" />
                      <input type="hidden" name="TransferType" value="<?php echo $TransferType ?>" />
                      <input type="hidden" name="AED" value="<?php echo $fm ?>" />
                      <input type="hidden" name="vID" value="<?php echo $id ?>" />
                      <?php if($fm!='V'){
						  if($fm=='A'){
								$histryID = "SELECT ID FROM StaffServiceHistory where NIC='$NICUser' order by ID Asc";
								$stmtMainhis = $db->runMsSqlQuery($histryID);
								while ($rowhis = sqlsrv_fetch_array($stmtMainhis, SQLSRV_FETCH_ASSOC)) {
									$ServiceHistoryID = $rowhis['ID'];
								}?>
                                <input type="hidden" name="ServiceHistoryID" value="<?php echo $ServiceHistoryID ?>" />
						  <?php }					  
						  ?> <input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value=""/><?php }?></td>
      	        </tr>
      	      </table></td>
      	    </tr>
        
        
		  <tr>
                  <td width="56%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                    <?php if($id!=''){?>
                    <tr>
                        <td colspan="2" ><span style="font-size:20px; font-weight:bold">Approvals</span></td>
                  </tr>
                      <tr>
                        <td height="1" colspan="2" bgcolor="#CCCCCC" ></td>
                  </tr>
                  <tr>
                        <td colspan="2"><?php include("schoolInformation.php");?></td>
                  </tr>
          <?php 
   $i=1;
   $sqlLeave="SELECT        TG_Request_Approve.id AS ReqAppID, TG_Request_Approve.RequestUserNIC, TG_Request_Approve.ApprovelUserNIC, TG_Request_Approve.ApproveUserNominatorNIC, 
                         TG_Request_Approve.ApproveProcessOrder, TG_Request_Approve.ApprovedStatus, TG_Request_Approve.DateTime, TG_Request_Approve.Remarks, 
                         TeacherMast.SurnameWithInitials, TG_ApprovalProcess.ApproveAccessRoleName
FROM            TG_Request_Approve INNER JOIN
                         TeacherMast ON TG_Request_Approve.ApprovelUserNIC = TeacherMast.NIC INNER JOIN
                         TG_ApprovalProcess ON TG_Request_Approve.ApprovalProcessID = TG_ApprovalProcess.ID
WHERE        (TG_Request_Approve.RequestType = 'TransferTeacher') AND (TG_Request_Approve.RequestID = '$id')
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
                  <td width="22%">&nbsp;</td>
                  <td width="79%">&nbsp;</td>
                </tr>
                <?php }?>
                
                <?php }?>
                    </table>
        </td>
        </tr>
          
              </table>
        <?php }?>
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