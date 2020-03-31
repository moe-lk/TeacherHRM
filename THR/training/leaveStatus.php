<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
include_once '../approveProcessfunction.php';
//echo $NICUser;
$msg="";
$tblNam="StaffLeaveDetail";
$countTotal="SELECT        StaffLeaveDetail.ID, StaffLeaveDetail.NIC, CONVERT(varchar(20),StaffLeaveDetail.StartDate,121) AS FromDate,CONVERT(varchar(20),StaffLeaveDetail.EndDate,121) AS ToDate,CONVERT(varchar(20),StaffLeaveDetail.LastUpdate,121) AS LastUpdate, StaffLeaveDetail.UpdateBy, 
                         StaffLeaveDetail.Reference, StaffLeaveDetail.RecordLog, TeacherMast.SurnameWithInitials, CD_LeaveType.Description, CD_CensesNo.CenCode, 
                         CD_CensesNo.InstitutionName
FROM            StaffLeaveDetail INNER JOIN
                         StaffServiceHistory ON StaffLeaveDetail.ServiceRecRef = StaffServiceHistory.ID INNER JOIN
                         TeacherMast ON StaffLeaveDetail.NIC = TeacherMast.NIC INNER JOIN
                         CD_LeaveType ON StaffLeaveDetail.LeaveType = CD_LeaveType.LeaveCode INNER JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
						 where StaffLeaveDetail.ID='$id'";//$NICUser
						 
$stmt = $db->runMsSqlQuery($countTotal);
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$FromDate=$row['FromDate'];
		$ToDate=$row['ToDate'];
		$LastUpdate=trim($row['LastUpdate']);
		$SurnameWithInitials=$row['SurnameWithInitials'];
		$Description=$row['Description'];
}

$TotaRows=$db->rowCount($countTotal);
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
			        <td>Leave Type </td>
			        <td>:</td>
			        <td><?php echo $Description ?></td>
		          </tr>
			      <tr>
			        <td width="15%">From Date</td>
			        <td width="2%">:</td>
			        <td width="33%"><?php echo $FromDate ?></td>
			        <td width="13%">Request Date</td>
			        <td width="2%">:</td>
			        <td width="35%"><?php echo $LastUpdate ?></td>
		          </tr>
			      <tr>
			        <td>To Date</td>
			        <td>:</td>
			        <td><?php echo $ToDate ?></td>
			        <td>&nbsp;</td>
			        <td>&nbsp;</td>
			        <td>&nbsp;</td>
		          </tr>
		        </table></td>
	      </tr>
			  <tr>
			    <td colspan="2" >&nbsp;</td>
	      </tr>
          <?php 
   $i=1;
   $sqlLeave="SELECT        TG_Request_Approve.id, TG_Request_Approve.RequestUserNIC, TG_Request_Approve.ApprovelUserNIC, TG_Request_Approve.ApproveUserNominatorNIC, 
                         TG_Request_Approve.ApproveProcessOrder, TG_Request_Approve.ApprovedStatus, TG_Request_Approve.DateTime, TG_Request_Approve.Remarks, 
                         TeacherMast.SurnameWithInitials, TG_ApprovalProcess.ApproveAccessRoleName
FROM            TG_Request_Approve INNER JOIN
                         TeacherMast ON TG_Request_Approve.ApprovelUserNIC = TeacherMast.NIC INNER JOIN
                         TG_ApprovalProcess ON TG_Request_Approve.ApprovalProcessID = TG_ApprovalProcess.ID
WHERE        (TG_Request_Approve.RequestType = 'Leave') AND (TG_Request_Approve.RequestID = '$id')
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
                  <td colspan="2" ><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="3%" height="30" align="center"><img src="images/re_enter.png" width="10" height="10" /></td>
                      <td colspan="5"><?php echo $ApproveAccessRoleName ?> - <?php echo $SurnameWithInitials ?></td>
                    </tr>
                    <tr>
                      <td height="20">&nbsp;</td>
                      <td width="3%">&nbsp;</td>
                      <td width="23%" valign="top">Approvel Status :</td>
                      <td width="20%" valign="top"><?php echo $statName ?></td>
                      <td width="7%" valign="top">Remarks :</td>
                      <td width="44%" valign="top"><?php echo $Remarks ?></td>
                    </tr>
                  </table></td>
          </tr>
          
                <tr>
                  <td width="56%">&nbsp;</td>
                  <td width="44%">&nbsp;</td>
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