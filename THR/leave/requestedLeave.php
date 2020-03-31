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
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
						 where TG_StaffLeave.NIC='$NICUser' order by TG_StaffLeave.ID desc";//$NICUser


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
                  <td><?php echo $TotaRows; ?> Record(s) found.</td>
                  <td>&nbsp;</td>
                </tr>
			  <tr>
                  <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="3%" height="25" align="center" bgcolor="#999999">#</td>
                      <td width="36%" align="center" bgcolor="#999999">Leave Type</td>
                      <td width="10%" align="center" bgcolor="#999999">From Date </td>
                      <td width="10%" align="center" bgcolor="#999999">To Date </td>
                      <td width="21%" align="center" bgcolor="#999999">Status</td>
                      <td width="11%" align="center" bgcolor="#999999">Details</td>
                      <td width="9%" align="center" bgcolor="#999999">Delete</td>
                    </tr>
                    <?php 
   $i=1;
   $stmt = $db->runMsSqlQuery($countTotal);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					  
					  $Expr1=$row['ID'];
					  $LeaveType=$row['LeaveType'];
					  
					  $statName="";
					  $sqlStatus="SELECT ApprovedStatus From TG_Approval_Leave where RequestID='$Expr1' and RequestType='Leave' order by ID desc";
					   $stmtstat = $db->runMsSqlQuery($sqlStatus);
					    while ($rowas = sqlsrv_fetch_array($stmtstat, SQLSRV_FETCH_ASSOC)) {
							$ApprovedStatus=trim($rowas['ApprovedStatus']);//echo "hi";
							//if($ApprovedStatus=='P')
							if($ApprovedStatus=='P' || $ApprovedStatus==''){
								$statName="Pending";
							}else if($ApprovedStatus=='A'){
								$statName="Approved";
							}else if($ApprovedStatus=='R'){
								$statName="Rejected";
							}
							
						}//echo $statName;
						//$statName="Approved";
					  ?>
                    <tr>
                      <td height="20" bgcolor="#FFFFFF"><?php echo $i++; ?></td>
                      <td bgcolor="#FFFFFF"><?php echo $row['Description']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['FromDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['ToDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $statName;//substr($statName, 0, -2); ?></td>
                      <td bgcolor="#FFFFFF" align="center"><a href="leaveStatus-8--<?php echo $Expr1 ?>.html" target="_blank"><img src="images/review_comment.png" /></a></td>
                      <td bgcolor="#FFFFFF" align="center"><?php if($statName=='Approved'){echo "Not Allow";}else{?><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','Leave','StaffLeaveDetail','<?php echo "$ttle-$pageid.html";?>')">Delete <?php //echo $Expr1 ?></a><?php }?></td>
                    </tr>
                    <?php }?>
                  </table></td>
          </tr>
                <tr>
                  <td width="56%">&nbsp;</td>
                  <td width="44%">&nbsp;</td>
                </tr>
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