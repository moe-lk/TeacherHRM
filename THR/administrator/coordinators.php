<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$msg="";
$tblNam="TG_CoordinatorsList";
$countTotal="SELECT * FROM $tblNam where Location!=''";

if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$Location=$_REQUEST['Location'];
	$Title=$_REQUEST['Title'];
	$NameWithInitials=$_REQUEST['NameWithInitials'];
	$Designation=$_REQUEST['Designation'];
	$Address1=$_REQUEST['Address1'];
	$Address2=$_REQUEST['Address2'];
	$TpNumber=$_REQUEST['TpNumber'];
	$EmailAdd=$_REQUEST['EmailAdd'];
	$OrderNumber=$_REQUEST['OrderNumber'];

	$RecordLog="Initial Record";
	
	if($Location!=''){
		$queryGradeSave="INSERT INTO $tblNam
			   (Location,NameWithInitials,Designation,Title,Address1,Address2,TpNumber,EmailAdd,OrderNumber)
		 VALUES
			   ('$Location','$NameWithInitials','$Designation','$Title','$Address1','$Address2','$TpNumber','$EmailAdd','$OrderNumber')";
			   
			$db->runMsSqlQuery($queryGradeSave);
			//$newID=$db->runMsSqlQueryInsert($queryGradeSave);
			$msg="Successfully Updated.";
	}else{
		$msg="Please enter the Location..";
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
			    <td valign="top">&nbsp;</td>
			    <td valign="top">&nbsp;</td>
	      </tr>
			  <tr>
			    <td valign="top">&nbsp;</td>
			    <td valign="top">&nbsp;</td>
	      </tr>
			  <tr>
			    <td valign="top">&nbsp;</td>
			    <td valign="top">&nbsp;</td>
	      </tr>
			  <tr>
                  <td width="50%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                    
                    <tr>
                      <td>Location <span class="form_error">*</span>:</td>
                      <td><input name="Location" type="text" class="input2" id="Location"/></td>
                    </tr>
                    <tr>
                      <td>Title :</td>
                      <td><input name="Title" type="text" class="input2" id="Title" /></td>
                    </tr>
                    <tr>
                      <td>Name with initials :</td>
                      <td><input name="NameWithInitials" type="text" class="input2" id="NameWithInitials" /></td>
                    </tr>
                    <tr>
                      <td>Designation</td>
                      <td><input name="Designation" type="text" class="input2" id="Designation" /></td>
                    </tr>
                    <tr>
                      <td>Order :</td>
                      <td><input name="OrderNumber" type="text" class="input3" id="OrderNumber" /></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                    </tr>
                    </table>
        </td>
        <td width="50%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                <tr>
                  <td width="43%" align="left" valign="top">Address line 1 :</td>
                  <td width="57%"><input name="Address1" type="text" class="input2" id="Address1" /></td>
                </tr>
                <tr>
                  <td>Address line 2 :</td>
                  <td><input name="Address2" type="text" class="input2" id="Address2" /></td>
                </tr>
                <tr>
                  <td>Telephone :</td>
                  <td><input name="TpNumber" type="text" class="input2" id="TpNumber" /></td>
                </tr>
                <tr>
                  <td>Email :</td>
                  <td><input name="EmailAdd" type="text" class="input2" id="EmailAdd" /></td>
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
                        <td width="2%" height="25" align="center" bgcolor="#999999">#</td>
                        <td width="9%" align="center" bgcolor="#999999">Order</td>
                        <td width="51%" align="center" bgcolor="#999999">Name</td>
                        <td width="21%" align="center" bgcolor="#999999">Location</td>
                        <td width="17%" align="center" bgcolor="#999999">Delete</td>
                      </tr>
                      <?php 
					  $sqlList="SELECT * From $tblNam where Location!=''";
					  
  $i=1;
   $stmt = $db->runMsSqlQuery($sqlList);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					  $OrderNumber=$row['OrderNumber'];
					  $NameWithInitials=$row['NameWithInitials'];
					  $Location=$row['Location'];
					  $Expr1=$row['ID'];
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td align="left" bgcolor="#FFFFFF"><?php echo $OrderNumber ?></td>
                        <td bgcolor="#FFFFFF" align="left"><?php echo $NameWithInitials ?></td>
                        <td bgcolor="#FFFFFF" align="left"><?php echo $Location ?></td>
                        <td bgcolor="#FFFFFF" align="center"><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','','<?php echo $tblNam ?>','<?php echo "$ttle-$pageid.html";?>')">Delete <?php //echo $Expr1 ?></a></td>
                      </tr>
                      <?php }?>
                      <tr>
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