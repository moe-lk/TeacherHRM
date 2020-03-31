<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$msg="";
$tblNam="TG_ApprovalProcess";
$countTotal="SELECT        TG_ApprovalProcess.ID, TG_ApprovalProcess.ProcessType, TG_ApprovalProcess.PositionCode, TG_ApprovalProcess.ApproveOrder, 
                         TG_ApprovalProcess.ApprovePositionCode, CD_Positions.PositionName
FROM            TG_ApprovalProcess INNER JOIN
                         CD_Positions ON TG_ApprovalProcess.PositionCode = CD_Positions.Code AND TG_ApprovalProcess.ApprovePositionCode = CD_Positions.Code";//$NICUser

if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$NIC=$_REQUEST['NIC'];
	$ServiceRecRef=$_REQUEST['ServiceRecRef'];
	$LeaveType=$_REQUEST['LeaveType'];	
	$StartDate=$_REQUEST['StartDate'];
	$EndDate=$_REQUEST['EndDate'];
	$LastUpdate=date('Y-m-d H:i:s');
	$UpdateBy=$NICUser;
	$Reference="test";
	$RecordLog="Insert";
	$queryGradeSave="INSERT INTO $tblNam
           (NIC,ServiceRecRef,LeaveType,StartDate,EndDate,LastUpdate,UpdateBy,Reference,RecordLog)
     VALUES
           ('$NIC','$ServiceRecRef','$LeaveType','$StartDate','$EndDate','$LastUpdate','$UpdateBy','$Reference','$RecordLog')";
		   
	$countSql="SELECT * FROM $tblNam where NIC='$NIC' and StartDate='$StartDate' and EndDate='$EndDate'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		$msg="Already exist.";
	}else{ 
		$db->runMsSqlQuery($queryGradeSave);
		$msg="Successfully Updated.";
	}
	//sqlsrv_query($queryGradeSave);
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
                  <td width="56%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                    <tr>
                      <td>Process Type :</td>
                      <td><select name="select" class="select5" id="select">
                      <option value="">-Select-</option>
                      <option value="Leave">Leave</option>
                      <option value="Retirement">Retirement</option>
                      </select></td>
                    </tr>
                    <tr>
                      <td>Process For :</td>
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
					  $sql = "SELECT CurResRef FROM TeacherMast where NIC='$NICUser'";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $CurResRef=$row['CurResRef'];
                            }
					  ?>
                        
                        </td>
                    </tr>
                    <tr>
                      <td>Number of Levels :</td>
                      <td>&nbsp;</td>
                    </tr>
                    
                    <tr>
                      <td>&nbsp;</td>
                      <td><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                    </tr>
                    </table>
        </td>
        <td width="44%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                <tr>
                  <td width="43%" align="left" valign="top">&nbsp;</td>
                  <td width="57%">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2">&nbsp;</td>
                </tr>
          </table></td>
          </tr>
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
                        <td width="16%" align="center" bgcolor="#999999">Delete</td>
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
   $stmt = $db->runMsSqlQuery($countTotal);
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
                        <td bgcolor="#FFFFFF" align="center"><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','','<?php echo $tblNam ?>','<?php echo "$ttle-$pageid.html";?>')">Delete <?php //echo $Expr1 ?></a></td>
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