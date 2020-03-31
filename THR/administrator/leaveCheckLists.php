<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$msg="";
$tblNam="TG_LeaveCheckList";
$countTotal="SELECT * FROM $tblNam where ID>'0'";
$uploadpath="../leave/checklists";

if(isset($_POST["FrmSubmit"])){	
	//echo "hi";exit();
	$LeaveType=$_REQUEST['LeaveType'];
	$CheckTitle=$_REQUEST['CheckTitle'];
	
	$ShowingOrder=$_REQUEST['ShowingOrder'];
	
	$dte=date("ymdHms");
	$field_name="AttachFile";
	//echo $_FILES[$field_name]['name']; echo "hi";
	 
	if($_FILES[$field_name]['name']!='') { //save file	
		$fileSaveName=$dte.$_FILES[$field_name]['name']; 
								
		$uppth2=$uploadpath."/".$fileSaveName;	
		copy ($_FILES[$field_name]['tmp_name'], $uppth2);
		//$insArrCusE[$field_name]=$fileSaveName;													
	}
	
	if($LeaveType!=''){
		$queryGradeSave="INSERT INTO $tblNam
			   (LeaveType,CheckTitle,AttachFile,ShowingOrder)
		 VALUES
			   ('$LeaveType','$CheckTitle','$fileSaveName','$ShowingOrder')";
			   
		$countSql="SELECT * FROM $tblNam where CheckTitle='$CheckTitle' and LeaveType='$LeaveType'";
		$isAvailable=$db->rowAvailable($countSql);
		if($isAvailable==1){
			$msg="Already exist.";
		}else{ 
			$db->runMsSqlQuery($queryGradeSave);
			//$newID=$db->runMsSqlQueryInsert($queryGradeSave);
			$msg="Successfully Updated.";
		}
	}else{
		$msg="Please enter the Title..";
	}
	//sqlsrv_query($queryGradeSave);
}
$TotaRows=$db->rowCount($countTotal);
?>


<div class="main_content_inner_block">
    <form method="post" action="" name="frmSave2" id="frmSave2" enctype="multipart/form-data">
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
                      <td>Leave Type <span class="form_error">*</span>:</td>
                      <td><select class="select5" id="LeaveType" name="LeaveType">
                        <option value="">-Select-</option>
                        <?php
                            $sql = "SELECT LeaveCode,Description FROM CD_LeaveType order by Description";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                echo '<option value=' . $row['LeaveCode'] . '>' . $row['Description'] . '</option>';
                            }
                            ?>
                      </select></td>
                    </tr>
                    <tr>
                      <td>Description :</td>
                      <td><input name="CheckTitle" type="text" class="input2" id="CheckTitle" /></td>
                    </tr>
                    <tr>
                      <td>Order :</td>
                      <td><input name="ShowingOrder" type="text" class="input2" id="ShowingOrder" /></td>
                    </tr>
                    <tr>
                      <td>Attachment :</td>
                      <td><input type="file" name="AttachFile" id="AttachFile" /></td>
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
                        <td width="21%" align="center" bgcolor="#999999">Leave Type</td>
                        <td width="57%" align="center" bgcolor="#999999">Check List Title</td>
                        <td width="5%" align="center" bgcolor="#999999">Order</td>
                        <td width="6%" align="center" bgcolor="#999999">File</td>
                        <td width="8%" align="center" bgcolor="#999999">Delete</td>
                      </tr>
                      <?php 
					  $sqlList="SELECT * From $tblNam where LeaveType!=''";
					  /*$sqlList="SELECT [ID]
      ,[SchoolID]
      ,[GradeID]
  FROM [dbo].[TG_SchoolGradeMaster]
  where SchoolID='SC05428'";*/
  $i=1;
   $stmt = $db->runMsSqlQuery($sqlList);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								
								$sql = "SELECT LeaveCode,Description FROM CD_LeaveType order by Description";
								$stmtTy = $db->runMsSqlQuery($sql);
								while ($rowTy = sqlsrv_fetch_array($stmtTy, SQLSRV_FETCH_ASSOC)) {
									$leaveTitle=$rowTy['Description'];
								}
					  $LeaveType=$row['LeaveType'];
					  $CheckTitle=$row['CheckTitle'];
					  $AttachFile=$row['AttachFile'];
					  $ShowingOrder=$row['ShowingOrder'];
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $leaveTitle ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $CheckTitle ?></td>
                        <td bgcolor="#FFFFFF" align="center"><?php echo $ShowingOrder ?></td>
                        <td bgcolor="#FFFFFF" align="center"><a href="<?php echo "$uploadpath/$AttachFile"; ?>" target="_blank">View</a></td>
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