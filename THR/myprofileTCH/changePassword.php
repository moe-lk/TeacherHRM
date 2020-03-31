<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$msg="";
$hide="N";
$changed="N";
if(isset($_POST["FrmSubmit"])){	
	//passwordCur,passwordNew,passwordNew2
	$NIC=$_REQUEST['NICNo'];
	$passwordCur=$_REQUEST['passwordCur'];
	$passwordMD5=md5($passwordCur);
	
	$passwordNew=$_REQUEST['passwordNew'];
	$passwordNew2=$_REQUEST['passwordNew2'];
	
	$LastUpdate=date('Y-m-d H:i:s');
	$hide="N";
	$changed="N";
	$countSql="SELECT * FROM Passwords where NICNo='$NIC' and CurPassword='$passwordMD5'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		if($passwordNew!=$passwordNew2  && ($passwordNew2!='')){
			$msg="Re-typed password mismatch. Please try again.";
		}else{
			$passwordMD5New=md5($passwordNew);
			
			$queryUpate="UPDATE Passwords SET CurPassword='$passwordMD5New', LastUpdate='$LastUpdate',IsnewPW='N' WHERE NICNo='$NIC'";
			$db->runMsSqlQuery($queryUpate);
			$msg="<br><br><br>You have successfully changed your password.<br><span class=\"link1\" onClick=\"logoutForm('mail');\">Please re-login to the NEMIS</span>";
			$hide="Y";
			$changed="Y";
		}
		
	}else{
		$msg="Current password mismatch. Please try again.";
	}
	
	
	//sqlsrv_query($queryGradeSave);
}

?>


<div class="main_content_inner_block">
    <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if($msg!=''){//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
   	  <div class="mcib_middle1">
          <div class="mcib_middle_full">
          <div class="form_error"><?php echo $msg; echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
        </div>
        <?php }?>
        <table width="100%" cellpadding="0" cellspacing="0">
       <?php  if($hide=='N'){?>
			  <tr>
			    <td valign="top" class="star_value"><?php if($fm=='C' and $changed=='N')echo "You are using system generated password. Please use your own password.";?></td>
			    <td valign="top">&nbsp;</td>
	      </tr>
          
			  <tr>
                  <td width="71%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                    <tr>
                      <td>Current Password</td>
                      <td>:</td>
                      <td><input name="passwordCur" type="text" class="input2" id="passwordCur" value=""/><input type="hidden" name="NICNo" value="<?php echo $id ?>" /></td>
                    </tr>
                    <tr>
                      <td>New Password</td>
                      <td>:</td>
                      <td><input name="passwordNew" type="password" class="input2" id="passwordNew" value=""/></td>
                    </tr>
                    <tr>
                      <td>Re-type New Password</td>
                      <td>:</td>
                      <td><input name="passwordNew2" type="password" class="input2" id="passwordNew2" value=""/></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                    </tr>
                    </table>
        </td>
        <td width="29%" valign="top">&nbsp;</td>
          </tr>
          
			  <tr>
			    <td valign="top">&nbsp;</td>
			    <td valign="top">&nbsp;</td>
	      </tr>
          <?php }else{			  
			$_SESSION['NIC']="";  
		  }?>
          
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table>
    </div>
    
    </form>
</div>