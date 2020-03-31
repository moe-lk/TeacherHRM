<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
include_once '../approveProcessfunction.php';

$msg="";
$uploadpath="checklists";

$countTotal="SELECT        TG_LeaveCheckList.ID, TG_LeaveCheckList.LeaveType, TG_LeaveCheckList.CheckTitle, TG_LeaveCheckList.AttachFile, TG_LeaveCheckList.ShowingOrder, 
                         CD_LeaveType.Description
FROM            TG_LeaveCheckList INNER JOIN
                         CD_LeaveType ON TG_LeaveCheckList.LeaveType = CD_LeaveType.LeaveCode";//$NICUser


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
			    <td colspan="2" valign="top">&nbsp;</td>
	      </tr>
			  <tr>
			    <td colspan="2" valign="top">
                <?php 
				$leaveTypeSql="SELECT [LeaveCode]
      ,[Description]
      ,[RecordLog]
      ,[DutyCode]
  FROM [dbo].[CD_LeaveType]";
   				$stmt = $db->runMsSqlQuery($leaveTypeSql);
					while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			  		$Description=$row['Description'];
					$LeaveCode=$row['LeaveCode'];
				?>
                <table width="100%" cellspacing="0" cellpadding="0">
			      <tr>
			        <td height="40" colspan="2" bgcolor="#CCCCCC">&nbsp;&nbsp;<?php echo $Description ?></td>
		          </tr>
                  <?php 
				  $sqlCheckList="SELECT [ID]
      ,[LeaveType]
      ,[CheckTitle]
      ,[AttachFile]
      ,[ShowingOrder]
  FROM [dbo].[TG_LeaveCheckList] where LeaveType='$LeaveCode' order by ShowingOrder Asc";
  $stmCLt = $db->runMsSqlQuery($sqlCheckList);
					while ($rowCL = sqlsrv_fetch_array($stmCLt, SQLSRV_FETCH_ASSOC)) {
			  		$CheckTitle=$rowCL['CheckTitle'];
					$AttachFile=$rowCL['AttachFile'];
					
				  ?>
			      <tr>
			        <td width="7%" align="right"><img src="../images/arrow.gif" width="10" height="9" /></td>
			        <td width="93%"><?php echo $CheckTitle ?>&nbsp;&nbsp;[<a href="<?php echo "$uploadpath/$AttachFile"; ?>" target="_blank">File</a>]</td>
                     <?php }?>
			      <tr>
			        <td>&nbsp;</td>
			        <td>&nbsp;</td>
		          </tr>
                 
		        </table>
                <?php }?>
                </td>
	      </tr>
			  <tr>
                  <td colspan="2" valign="top">&nbsp;</td>
        </tr>
          <?php if($fm=='DAD'){?>
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