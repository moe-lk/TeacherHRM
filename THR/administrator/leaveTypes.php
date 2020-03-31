<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$msg="";
$tblNam="CD_LeaveType";
$countTotal="SELECT * FROM $tblNam where LeaveCode!=''";

if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$LeaveCode=$_REQUEST['LeaveCode'];
	$Description=$_REQUEST['Description'];
	$RecordLog="Initial Record";
	$DutyCode=$_REQUEST['DutyCode'];
	
	if($LeaveCode!=''){
		$queryGradeSave="INSERT INTO $tblNam
			   (LeaveCode,Description,RecordLog,DutyCode)
		 VALUES
			   ('$LeaveCode','$Description','$RecordLog','$DutyCode')";
			   
		$countSql="SELECT * FROM $tblNam where LeaveCode='$LeaveCode'";
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
                      <td>Leave Code <span class="form_error">*</span>:</td>
                      <td><input name="LeaveCode" type="text" class="input2" id="LeaveCode"/></td>
                    </tr>
                    <tr>
                      <td>Description :</td>
                      <td><input name="Description" type="text" class="input2" id="Description" /></td>
                    </tr>
                    <tr>
                      <td>Duty Code :</td>
                      <td><input name="DutyCode" type="text" class="input2" id="DutyCode" /></td>
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
                        <td width="4%" height="25" align="center" bgcolor="#999999">#</td>
                        <td width="13%" align="center" bgcolor="#999999">Code</td>
                        <td width="48%" align="center" bgcolor="#999999">Description</td>
                        <td width="35%" align="center" bgcolor="#999999">Duty Code</td>
                       <!-- <td width="10%" align="center" bgcolor="#999999">Delete</td>-->
                      </tr>
                      <?php 
					  $sqlList="SELECT * From $tblNam where LeaveCode!=''";
					  /*$sqlList="SELECT [ID]
      ,[SchoolID]
      ,[GradeID]
  FROM [dbo].[TG_SchoolGradeMaster]
  where SchoolID='SC05428'";*/
  $i=1;
   $stmt = $db->runMsSqlQuery($sqlList);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					  $LeaveCode=$row['LeaveCode'];
					  $Description=$row['Description'];
					  $DutyCode=$row['DutyCode'];
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $LeaveCode ?></td>
                        <td bgcolor="#FFFFFF" align="center"><?php echo $Description ?></td>
                        <td bgcolor="#FFFFFF" align="center"><?php echo $DutyCode ?></td>
                        <!--<td bgcolor="#FFFFFF" align="center"><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','','<?php echo $tblNam ?>','<?php echo "$ttle-$pageid.html";?>')">Delete <?php //echo $Expr1 ?></a></td>-->
                      </tr>
                      <?php }?>
                      <tr>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                       <!-- <td bgcolor="#FFFFFF">&nbsp;</td>-->
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