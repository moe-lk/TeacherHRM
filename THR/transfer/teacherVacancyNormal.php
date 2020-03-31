<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
include_once '../approveProcessfunction.php';
//include('js/common.js.php'); 
include('js/ajaxloadpage.js.php'); 

$cat="teacherVacancyNormal";
$tblName="TG_TeacherVacancyNormal";
$TransferType="TVANO";//teacher vacancy national school
$approvetype="VacancyTeacherNormal";

if($fm=='' || $fm=='A'){
	$countTotal="SELECT * FROM TG_TeacherVacancyNormal";//where SchoolID='$loggedSchool'

	$TotaRows=$db->rowCount($countTotal);

		$approvalListSql="SELECT        TG_TeacherVacancyNormalMaster.ID, TG_TeacherVacancyNormalMaster.Title, CONVERT(varchar(20),TG_TeacherVacancyNormalMaster.OpenDate,121) AS OpenDate, CONVERT(varchar(20),TG_TeacherVacancyNormalMaster.EndDate,121) AS EndDate, 
							 TG_TeacherVacancyNormalMaster.VacancyDescription, CD_CensesNo.InstitutionName, CD_CensesNo.IsNationalSchool, CD_CensesNo.CenCode
	FROM            TG_TeacherVacancyNormalMaster INNER JOIN
							 CD_CensesNo ON TG_TeacherVacancyNormalMaster.SchoolID = CD_CensesNo.CenCode where CD_CensesNo.IsNationalSchool='1' and TG_TeacherVacancyNormalMaster.ID='$tpe'";
	
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
			
	$histryID = "SELECT ID FROM StaffServiceHistory where NIC='$NICUser' order by ID Asc";
	$stmtMainhis = $db->runMsSqlQuery($histryID);
	while ($rowhis = sqlsrv_fetch_array($stmtMainhis, SQLSRV_FETCH_ASSOC)) {
		$ServiceHistoryID = $rowhis['ID'];
	}
}


if($fm==''){
	
}else if($fm=='V'){
	$approvalListSql="SELECT        TG_TeacherVacancyNormalMaster.ID, TG_TeacherVacancyNormalMaster.Title, CONVERT(varchar(20),TG_TeacherVacancyNormalMaster.OpenDate,121) AS OpenDate, CONVERT(varchar(20),TG_TeacherVacancyNormalMaster.EndDate,121) AS EndDate, 
                         TG_TeacherVacancyNormalMaster.VacancyDescription, CD_CensesNo.InstitutionName, CD_CensesNo.IsNationalSchool, CD_CensesNo.CenCode
FROM            TG_TeacherVacancyNormalMaster INNER JOIN
                         CD_CensesNo ON TG_TeacherVacancyNormalMaster.SchoolID = CD_CensesNo.CenCode where CD_CensesNo.IsNationalSchool='1' and TG_TeacherVacancyNormalMaster.ID='$tpe'";

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
	
	
	$sqlFormDate="SELECT        TG_TeacherVacancyNormal.IsApproved, TG_TeacherVacancyNormal.ReasonForTransfer, TG_TeacherVacancyNormal.ExtraActivities, 
                         TG_TeacherVacancyNormal.ApplyDate,  StaffServiceHistory.InstCode, TG_TeacherVacancyNormal.NIC, 
                         TG_TeacherVacancyNormal.ServiceHistoryID, TG_TeacherVacancyNormal.ID
FROM            TG_TeacherVacancyNormal INNER JOIN
                         StaffServiceHistory ON TG_TeacherVacancyNormal.ServiceHistoryID = StaffServiceHistory.ID
						 where TG_TeacherVacancyNormal.ID='$id'";
						 
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
                      <td width="2%" height="25" align="center" bgcolor="#999999">#</td>
                      <td width="15%" align="center" bgcolor="#999999">Vacancy</td>
                      <td width="11%" align="center" bgcolor="#999999">Open Date</td>
                      <td width="11%" align="center" bgcolor="#999999">End Date</td>
                      <td width="27%" align="center" bgcolor="#999999">School</td>
                      <td width="21%" align="center" bgcolor="#999999">Description</td>
                      <td width="13%" align="center" bgcolor="#999999">Action</td>
                    </tr>
                    <?php 
					$i=1;
				
					$approvalListSql="SELECT        TG_TeacherVacancyNormalMaster.ID, TG_TeacherVacancyNormalMaster.Title, CONVERT(varchar(20),TG_TeacherVacancyNormalMaster.OpenDate,121) AS OpenDate, CONVERT(varchar(20),TG_TeacherVacancyNormalMaster.EndDate,121) AS EndDate, 
                         TG_TeacherVacancyNormalMaster.VacancyDescription, CD_CensesNo.InstitutionName, CD_CensesNo.IsNationalSchool
FROM            TG_TeacherVacancyNormalMaster INNER JOIN
                         CD_CensesNo ON TG_TeacherVacancyNormalMaster.SchoolID = CD_CensesNo.CenCode where CD_CensesNo.IsNationalSchool='1'";

					$stmtApp = $db->runMsSqlQuery($approvalListSql);
                     while ($rowApp = sqlsrv_fetch_array($stmtApp, SQLSRV_FETCH_ASSOC)) {
						$RequestID=$rowApp['ID'];
						$VacancyTitle=$rowApp['Title'];
						$VacancyOpenDate=$rowApp['OpenDate'];
						$VacancyEndDate=$rowApp['EndDate'];
						$VacancyDescription=$rowApp['VacancyDescription'];
						$VacancyInstitutionName=$rowApp['InstitutionName'];
						
						$countTotal = "SELECT * FROM TG_TeacherVacancyNormal where VacancyMasterID='$RequestID' and NIC='$NICUser'";
						$TotaRowsaval = $db->rowCount($countTotal);
						$statFM="V";
						$stmtSpos = $db->runMsSqlQuery($countTotal);
						while ($row = sqlsrv_fetch_array($stmtSpos, SQLSRV_FETCH_ASSOC)) {
							$vacancyApplyID=$row['ID'];
						}
		
						if($TotaRowsaval==0){
							$statFM="A";
							$vacancyApplyID="";
						}
					?>
                    <tr>
                      <td height="20" bgcolor="#FFFFFF"><?php echo $i++; ?></td>
                      <td bgcolor="#FFFFFF"><?php echo $VacancyTitle; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $VacancyOpenDate; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $VacancyEndDate ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $VacancyInstitutionName ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $VacancyDescription ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php //if($editBut=='Y'){?><a href="teacherRequestNational-<?php echo $pageid ?>--<?php echo $vacancyApplyID ?>-<?php echo $statFM ?>-<?php echo $RequestID ?>.html">Apply&nbsp;/&nbsp;View</a><?php //}?><?php if($editBut=='DAD'){?><a href="teacherRequestNational-<?php echo $pageid ?>--<?php echo $vacancyApplyID ?>-V-<?php echo $RequestID ?>.html">View&nbsp;|&nbsp;<a href="javascript:aedWin('<?php echo $RequestID ?>','D','','TG_TeacherTransferNational','<?php echo "teacherRequestNational-$pageid.html";?>')">Delete <?php //echo $Expr1 ?></a><?php }?></td>
                    </tr>
                   <?php }?>
                  </table></td>
          </tr>
         
                <tr>
                  <td width="56%">&nbsp;</td>
                  <td width="44%">&nbsp;</td>
                </tr>
          
              </table>
        <?php }else{
			
			?>
        <table width="100%" cellpadding="0" cellspacing="0">
        	  
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
        	    <td bgcolor="#FFFFFF"><strong>Vacancy Information</strong></td>
      	    </tr>
        	  <tr>
        	    <td bgcolor="#FFFFFF"><table width="100%" cellspacing="1" cellpadding="1">
        	      <tr>
        	        <td width="23%" bgcolor="#F7E2DD">Title :<input type="hidden" name="VacancyMasterID" value="<?php echo $RequestID ?>" /><input type="hidden" name="ServiceHistoryID" value="<?php echo $ServiceHistoryID ?>" /></td>
        	        <td width="77%" bgcolor="#EDEEF3"><?php echo $Title ?></td>
      	        </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Description :</td>
        	        <td bgcolor="#EDEEF3"><?php echo $VacancyDescription ?></td>
      	        </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">School :</td>
        	        <td bgcolor="#EDEEF3"><?php echo ucwords(strtolower(($VacancyInstitutionName))); ?></td>
      	        </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Vacancy Open Date :</td>
        	        <td bgcolor="#EDEEF3"><?php echo $OpenDate ?></td>
      	        </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Vacancy Closing Date :</td>
        	        <td bgcolor="#EDEEF3"><?php echo $EndDate ?></td>
      	        </tr>
      	      </table></td>
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
                      <td width="23%" valign="top" bgcolor="#CFF1D1">Extra Activities :</td>
                      <td width="77%" bgcolor="#CFD7FE"><div class="noofCharactor" >Select Multiple Activities by clicking with the &quot;Ctrl&quot; key .</div><select name="ExtraActivities[]" size="5" multiple="multiple" class="textarea1" id="ExtraActivities[]" <?php if($id>0){?>readonly="readonly" disabled="disabled"<?php }?>>
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
                      <td valign="top" bgcolor="#CFF1D1">Reason for Transfer :</td>
                      <td bgcolor="#CFD7FE"><textarea name="ReasonForTransfer" cols="85" rows="5" class="textarea1auto" id="ReasonForTransfer" <?php if($id>0){?>readonly="readonly" disabled="disabled"<?php }?>><?php echo $ReasonForTransfer ?></textarea></td>
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
                      <?php if($id==''){?>
						  <input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value=""/><?php }?></td>
      	        </tr>
      	      </table></td>
      	    </tr>
            <?php if($id>0){?>
        	  <tr>
                  <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                    
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
   $i=1;// echo $RequestID;
   $sqlLeave="SELECT        TG_Request_Approve.id AS ReqAppID, TG_Request_Approve.RequestUserNIC, TG_Request_Approve.ApprovelUserNIC, TG_Request_Approve.ApproveUserNominatorNIC, 
                         TG_Request_Approve.ApproveProcessOrder, TG_Request_Approve.ApprovedStatus, TG_Request_Approve.DateTime, TG_Request_Approve.Remarks, 
                         TeacherMast.SurnameWithInitials, TG_ApprovalProcess.ApproveAccessRoleName
FROM            TG_Request_Approve INNER JOIN
                         TeacherMast ON TG_Request_Approve.ApprovelUserNIC = TeacherMast.NIC INNER JOIN
                         TG_ApprovalProcess ON TG_Request_Approve.ApprovalProcessID = TG_ApprovalProcess.ID
WHERE        (TG_Request_Approve.RequestType = '$approvetype') AND (TG_Request_Approve.RequestID = '$recID')
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
                      <td valign="top" bgcolor="#F7E2DD">Release Option :</td>
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
                  <td width="27%">&nbsp;</td>
                  <td width="73%">&nbsp;</td>
                </tr>
                <?php }?>
                
                <?php }?>
                    </table></td>
        	  </tr>
              <?php }?>
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