<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
include_once '../approveProcessfunction.php';

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
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode";//$NICUser

$whereC=$countTotal." where TG_StaffLeave.NIC='$NICUser' and TG_StaffLeave.ApplyBy='OPERATOR'";

if($id!=''){
	$whereC=$countTotal." where TG_StaffLeave.NIC='$NICUser' and TG_StaffLeave.ID='$id'";
	$stmtThis = $db->runMsSqlQuery($whereC);
	$rowThis = sqlsrv_fetch_array($stmtThis, SQLSRV_FETCH_ASSOC);
    $FromDate = $rowThis['FromDate'];
	$ToDate = $rowThis['ToDate'];
	$LeaveType = $rowThis['LeaveType'];
	$Reference = $rowThis['Reference'];
	$NIC = $rowThis['NIC'];
	$NoofDays = $rowThis['NoofDays'];
	/* $FromDate = $rowThis['FromDate']; */
	if($FromDate=='1900-01-01')$FromDate="";
	if($ToDate=='1900-01-01')$ToDate="";
	
}
//echo $whereC;
if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$vID=$_REQUEST['vID'];
	$NIC=$_REQUEST['NIC'];
	$SchoolID=$loggedSchool;
	$ServiceRecRef=$_REQUEST['ServiceRecRef'];
	$LeaveType=trim($_REQUEST['LeaveType']);	
	$StartDate=$_REQUEST['StartDate'];
	$EndDate=$_REQUEST['EndDate'];
	$LastUpdate=date('Y-m-d H:i:s');
	$UpdateBy=$NICUser;
	$Reference=$_REQUEST['Reference'];
	$NoofDays=$_REQUEST['NoofDays'];
	$RecordLog="Re-submit by employee on $LastUpdate";
	$IsApproved="N";
	$ApplyBy="SELF";
	
	$countProcx=checkApprovalAvailable($LeaveType);
	if($countProcx!='0'){
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
	
	//exit();
	$queryGradeSave="UPDATE $tblNam
           SET LeaveType='$LeaveType', StartDate='$StartDate', EndDate='$EndDate', LastUpdate='$LastUpdate', Reference='$Reference', NoofDays='$NoofDays', AttachFile='$fileSaveName', ApplyBy='$ApplyBy' where ID='$vID'";
		
		$db->runMsSqlQuery($queryGradeSave);
		$processType = $LeaveType;
		$msg = getApproveList($processType, $vID);
		
		
		
	}else{
		$msg = "Approval process isn't assigned. Please contact your administrator.";
	}
	//sqlsrv_query($queryGradeSave);
}


$TotaRows=$db->rowCount($whereC);

	$sqlFA = "SELECT CONVERT(varchar(20),AppDate,121) AS firstAppDate FROM StaffServiceHistory where NIC='$NICUser' and ServiceRecTypeCode='NA01'";

	$stmtFA = $db->runMsSqlQuery($sqlFA);
    $rowSFA = sqlsrv_fetch_array($stmtFA, SQLSRV_FETCH_ASSOC);
    $firstAppDate = $rowSFA['firstAppDate'];
	
?>

<div class="main_content_inner_block">
    <form method="post" action="" name="frmSavel" id="frmSavel" enctype="multipart/form-data" onSubmit="return check_form(frmSavel);">
        <?php if($msg!=''){//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
   	  <div class="mcib_middle1">
          <div class="mcib_middle_full">
          <div class="form_error"><?php echo $msg; echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
        </div>
        <?php }?>
        <table width="945" cellpadding="0" cellspacing="0">
        	  <?php if($id!=''){?>
			  <tr>
                  <td width="56%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                    <tr>
                      <td>Name :</td>
                      <td><?php //echo $NICUser ?><select class="select2a_n" id="NIC" name="NIC">
                            <?php
                            $sql = "SELECT NIC, SurnameWithInitials      
  FROM TeacherMast
  where NIC='$NICUser'";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                echo '<option value=' . $row['NIC'] . '>' . $row['SurnameWithInitials'] . '</option>';
                            }
                            ?>
                      </select>
                      <?php 
					  $sql = "SELECT CurServiceRef FROM TeacherMast where NIC='$NICUser'";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $CurResRef=$row['CurServiceRef'];
                            }
					  ?>
                        <input type="hidden" name="ServiceRecRef" value="<?php echo $CurResRef; ?>" />
                        </td>
                    </tr>
                    <tr>
                      <td>Leave Type :</td>
                      <td><select class="select5" id="LeaveType" name="LeaveType">
                        <option value="">-Select-</option>
                        <?php
                            $sql = "SELECT LeaveCode,Description FROM CD_LeaveType order by Description";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$LeaveCodec=$row['LeaveCode'];
								$Descriptionc=$row['Description'];
								 $seltebr="";
							  	 if($LeaveCodec==$LeaveType){
								   echo $seltebr="selected";
							   	 }
                                echo "<option value=\"$LeaveCodec\" $seltebr>$Descriptionc</option>";
                            }
                            ?>
                      </select></td>
                    </tr>
                    <tr>
                      <td>Number of Days :</td>
                      <td><input name="NoofDays" type="text" class="input3new" id="NoofDays" value="<?php echo $NoofDays; ?>"/></td>
                    </tr>
                    <tr>
                      <td>Strat Date :</td>
                      <td>
                        <table width="100%" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="13%"><input name="StartDate" type="text" class="input3new" id="StartDate" value="<?php echo $FromDate; ?>" size="10" style="height:20px; line-height:20px;" readonly/>
                      </td>
                            <td width="87%">
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
                      <td>End Date :</td>
                      <td><table width="100%" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="13%"><input name="EndDate" type="text" class="input3new" id="EndDate" value="<?php echo $ToDate; ?>" size="10" style="height:20px; line-height:20px;" readonly/>
                      </td>
                            <td width="87%">
                      <input name="f_trigger_2" type="image" id="f_trigger_2" src="../cms/images/calender_icon.gif" align="top" width="16" height="16"  />
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
                      <td>&nbsp;</td>
                      <td><input type="hidden" name="vID" value="<?php echo $id ?>" />
                      <input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                    </tr>
                    </table>
        </td>
        <td width="44%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                <tr>
                  <td width="43%" align="left" valign="top">Designation :</td>
                  <td width="57%"><input name="textfield2" type="text" class="input2_n" id="textfield2" readonly="readonly" value="<?php echo $loggedPositionName;?>"/></td>
                </tr>
                <tr>
                  <td align="left" valign="top">1st Appoinment Date :</td>
                  <td><input name="textfield3" type="text" class="input3new" id="textfield3" readonly="readonly" value="<?php echo $firstAppDate ?>"/></td>
                </tr>
                <tr>
                  <td align="left" valign="top">Remarks :</td>
                  <td rowspan="4" valign="top"><textarea name="Reference" cols="45" rows="5" class="textarea1auto" id="Reference"><?php echo $Reference ?></textarea></td>
                </tr>
                <tr>
                  <td align="left" valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left" valign="top">Attachment :</td>
                  <td valign="top"><input type="file" name="AttachFile" id="AttachFile" /></td>
                </tr>
                </table></td>
          </tr>
          <?php }?>
          <?php if($id==''){?>
                <tr>
                  <td><?php echo $TotaRows ?> Record(s) found.</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="3%" height="25" align="center" bgcolor="#999999">#</td>
                        <td width="24%" align="center" bgcolor="#999999">Work Place</td>
                        <td width="24%" align="center" bgcolor="#999999">Leave Type</td>
                        <td width="10%" align="center" bgcolor="#999999">From Date</td>
                        <td width="9%" align="center" bgcolor="#999999">To Date</td>
                        <td width="14%" align="center" bgcolor="#999999">Status</td>
                        <td width="16%" align="center" bgcolor="#999999">Re-submit</td>
                      </tr>
                      <?php 
					 /* $sqlList="SELECT        StaffLeaveDetail.ID, StaffLeaveDetail.NIC, CONVERT(varchar(20),StaffLeaveDetail.StartDate,121) AS FromDate,CONVERT(varchar(20),StaffLeaveDetail.EndDate,121) AS ToDate, StaffLeaveDetail.LastUpdate, StaffLeaveDetail.UpdateBy, 
                         StaffLeaveDetail.Reference, StaffLeaveDetail.RecordLog, TeacherMast.SurnameWithInitials, CD_LeaveType.Description, CD_CensesNo.CenCode, 
                         CD_CensesNo.InstitutionName
FROM            StaffLeaveDetail INNER JOIN
                         StaffServiceHistory ON StaffLeaveDetail.ServiceRecRef = StaffServiceHistory.ID INNER JOIN
                         TeacherMast ON StaffLeaveDetail.NIC = TeacherMast.NIC INNER JOIN
                         CD_LeaveType ON StaffLeaveDetail.LeaveType = CD_LeaveType.LeaveCode INNER JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
						 where StaffLeaveDetail.NIC='640830646v'";*///$NICUser
					  /*$sqlList="SELECT [ID]
      ,[SchoolID]
      ,[GradeID]
  FROM [dbo].[TG_SchoolGradeMaster]
  where SchoolID='SC05428'";*/
  $i=1;
  
   $stmt = $db->runMsSqlQuery($whereC);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					  
					  $Expr1=$row['ID'];
					  
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['InstitutionName']; ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['Description']; ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['FromDate']; ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['ToDate']; ?></td>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                        <td bgcolor="#FFFFFF" align="center"><a href="leaveApplyByOperatorEmplpyeeView-4--<?php echo $Expr1 ?>.html">View <?php //echo $Expr1 ?></a></td>
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
                      </tr>
                    </table></td>
          </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <?php }?>
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