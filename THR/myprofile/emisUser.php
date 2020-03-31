<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$msg="";
$tblNam="CD_LeaveType";
$countTotal="SELECT * FROM $tblNam where LeaveCode!=''";
if(isset($_POST["FrmSrch"])){
	$NICSrch=$_REQUEST['NICNo'];
	
	/* $srchQry="SELECT        TeacherMast.SurnameWithInitials, TeacherMast.FullName, TeacherMast.Title, TeacherMast.CurServiceRef, 
                         CD_Title.TitleName, Passwords.NICNo, Passwords.CurPassword, Passwords.AccessRole, Passwords.AccessLevel, StaffServiceHistory.InstCode, 
                         CD_CensesNo.InstitutionName, CD_CensesNo.DistrictCode, CD_Districts.DistName
FROM            TeacherMast INNER JOIN
                         CD_Title ON TeacherMast.Title = CD_Title.TitleCode INNER JOIN
                         Passwords ON TeacherMast.NIC = Passwords.NICNo INNER JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID INNER JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode INNER JOIN
                         CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
WHERE        (Passwords.NICNo = N'$NICSrch')"; */

}

if(isset($_POST["FrmSubmit"])){	
	//echo "hi";AccessLevel,CurPassword,CurPasswordRT
	$AccessLevel=$_REQUEST['AccessLevel'];
	$CurPassword=$_REQUEST['CurPassword'];echo "-";
	$CurPasswordRT=$_REQUEST['CurPasswordRT'];
	$chngepw=$_REQUEST['chngepw'];
	$insertTyp=$_REQUEST['insertTyp'];
	$NICSrch=$_REQUEST['NICNo'];
	
	//if($CurPassword!='' and $chngepw=='Y'){
		if($CurPassword!=$CurPasswordRT and $CurPassword!='' and $chngepw=='Y'){
			$msg="Password mismatch. Please try again.";
		}else{
			$sql = "SELECT AccessRole from CD_AccessRoles Where AccessRoleValue='$AccessLevel'";
            $stmt = $db->runMsSqlQuery($sql);
			$rowA = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
			$AccessRole=strtoupper($rowA['AccessRole']);
								
			$LastUpdate=date('Y-m-d H:i:s');
			   
			if($insertTyp=='E'){
				if($chngepw=='Y'){
				    $queryUpate="UPDATE Passwords SET	CurPassword='$CurPassword', LastUpdate='$LastUpdate', AccessRole='$AccessRole', AccessLevel='$AccessLevel' 
					WHERE
						   NICNo='$NICSrch'";
				}else{
					$queryUpate="UPDATE Passwords SET LastUpdate='$LastUpdate', AccessRole='$AccessRole', AccessLevel='$AccessLevel' 
					WHERE
						   NICNo='$NICSrch'";
				}
				$db->runMsSqlQuery($queryUpate);
				$msg="Record update successfully.";
			
			}else{
				$queryGradeSave="INSERT INTO Passwords
			   (NICNo,CurPassword,LastUpdate,AccessRole,AccessLevel)
		 VALUES
			   ('$NICSrch','$CurPassword','$LastUpdate','$AccessRole','$AccessLevel')";
				$db->runMsSqlQuery($queryGradeSave);
				$msg="Account create successfully.";
			}
			   
				
		}
	/* }else{
		$msg="Please enter the password.";
	} */
	/* if($LeaveCode!=''){
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
	} */
	//sqlsrv_query($queryGradeSave);
}

if($NICSrch!=''){
	$srchQry="SELECT        TeacherMast.SurnameWithInitials, TeacherMast.FullName, TeacherMast.Title, TeacherMast.CurServiceRef, 
                         CD_Title.TitleName, StaffServiceHistory.InstCode, 
                         CD_CensesNo.InstitutionName, CD_CensesNo.DistrictCode, CD_Districts.DistName
FROM            TeacherMast INNER JOIN
                         CD_Title ON TeacherMast.Title = CD_Title.TitleCode INNER JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID INNER JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode INNER JOIN
                         CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
WHERE        (TeacherMast.NIC = N'$NICSrch')";

	$stmt = $db->runMsSqlQuery($srchQry);
	$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
	$SurnameWithInitials=$row['SurnameWithInitials'];
	$FullName=$row['FullName'];
	$TitleName=$row['TitleName'];
	$InstitutionName=$row['InstitutionName'];
	$DistName=$row['DistName'];
	
	$paswrdGry="SELECT [NICNo]
      ,[CurPassword]
      ,[LastUpdate]
      ,[AccessRole]
      ,[AccessLevel]
  FROM [dbo].[Passwords]
  WHERE NICNo=N'$NICSrch'";
  	$stmtP = $db->runMsSqlQuery($paswrdGry);
	$rowP = sqlsrv_fetch_array($stmtP, SQLSRV_FETCH_ASSOC);
	$CurPassword=trim($rowP['CurPassword']);
	$AccessRole=$rowP['AccessRole'];
	$AccessLevel=trim($rowP['AccessLevel']);	
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
        <table width="945" cellpadding="0" cellspacing="0">
			  <tr>
                  <td colspan="2" valign="top"><table width="90%" cellspacing="2" cellpadding="2">
                    
                    <tr>
                      <td width="19%"><span class="form_error">*</span> Enter The NIC Number :</td>
                      <td width="18%"><input name="NICNo" type="text" class="input3" id="NICNo" value="<?php echo $NICSrch ?>"/></td>
                      <td width="63%"><input name="FrmSrch" type="submit" id="FrmSrch" style="background-image: url(../cms/images/finduser.png); width:158px; height:26px; background-color:transparent; border:none; cursor:pointer;" value="" /></td>
                    </tr>
                    </table>
        </td>
        </tr>
                
                <tr>
                  <td colspan="2" style="border-bottom:1px; border-bottom-style:solid;"><?php 
				  if($NICSrch!=''){
				  if($CurPassword==''){?><span style="color:#F00; font-weight:bold;">User account doesn't exist. Assign "Access Level" and "Password".</span><?php }else{?><span style="color:#090; font-weight:bold;">User account already exist. You can change "Access Level" and "Password".</span><?php }}?></td>
                </tr>
                <tr>
                  <td colspan="2">&nbsp;</td>
                </tr>
                <?php if($NICSrch){?>
                <tr>
                  <td width="62%"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="22%"><strong>Title</strong></td>
                      <td width="1%">:</td>
                      <td width="77%"><?php echo $TitleName ?></td>
                    </tr>
                    <tr>
                      <td><strong>Surname With Initials</strong></td>
                      <td>:</td>
                      <td><?php echo $SurnameWithInitials ?></td>
                    </tr>
                    <tr>
                      <td><strong>Full Name</strong></td>
                      <td>:</td>
                      <td><?php echo $FullName ?></td>
                    </tr>
                    
                    <tr>
                      <td><strong>District</strong></td>
                      <td>:</td>
                      <td><?php echo $DistName ?></td>
                    </tr>
                    <tr>
                      <td><strong>Institution</strong></td>
                      <td>:</td>
                      <td><?php echo $InstitutionName ?></td>
                    </tr>
                    <tr>
                      <td><strong>Access Level</strong></td>
                      <td>:</td>
                      <td><select class="select2a_n" id="AccessLevel" name="AccessLevel">
                            <!--<option value="">School Name</option>-->
                            <?php
                            $sql = "SELECT [AccessRoleType]
      ,[AccessRole]
      ,[AccessRoleValue]
  FROM [dbo].[CD_AccessRoles]
  order by AccessRole";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$AccessRoleValueDB=trim($row['AccessRoleValue']);
								$AccessRoleDB=$row['AccessRole'];

								$seltebr="";
								if($AccessRoleValueDB==$AccessLevel){
									$seltebr="selected";
								}
							   
                                echo "<option value=\"$AccessRoleValueDB\" $seltebr>$AccessRoleDB</option>";
                            }
                            ?>
                      </select></td>
                    </tr>
                    
					<?php if($CurPassword==''){?>
                    <tr>
                      <td colspan="3"><table width="100%" cellspacing="1" cellpadding="1">
                        <tr>
                          <td width="22%"><strong><span class="form_error">*</span>Password</strong></td>
                          <td width="1%">:</td>
                          <td width="77%"><input name="CurPassword" type="text" class="input3" id="CurPassword" value="<?php echo $CurPassword ?>"/></td>
                        </tr>
                        <tr>
                          <td><strong><span class="form_error">*</span>Re-type Password</strong></td>
                          <td>:</td>
                          <td><input name="CurPasswordRT" type="text" class="input3" id="CurPasswordRT" value="" /></td>
                        </tr>
                      </table></td>
                    </tr><?php }?>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                    </tr>
                  </table></td>
                  <td width="38%" valign="top"><table width="100%" cellspacing="1" cellpadding="1">
                    <?php if($CurPassword!=''){?>
                    <tr>
                      <td align="left" bgcolor="#FFFFFF"><a style="cursor:hand; cursor:pointer; border-bottom:1px; border-bottom-style:solid; color:#FFF;" onclick="Javascript:show_changepw('change_pw','','');">
                      <img src="../cms/images/change-password.png" width="150" height="26" /></a><input type="hidden" name="insertTyp" value="E" /></td>
                    </tr>
                    <?php }?>
                    <tr>
                      <td width="48%"><div id="txt_changepw"><?php if($CurPassword=='DAD'){?><table width="100%" cellspacing="1" cellpadding="1">
                        <tr>
                          <td width="39%"><strong><span class="form_error">*</span>Password</strong></td>
                          <td width="3%">:</td>
                          <td width="58%"><input name="CurPassword" type="text" class="input3" id="CurPassword" value=""/></td>
                        </tr>
                        <tr>
                          <td><strong><span class="form_error">*</span>Re-type Password</strong></td>
                          <td>:</td>
                          <td><input name="CurPasswordRT" type="text" class="input3" id="CurPasswordRT" value="" /></td>
                        </tr>
                      </table><?php }?></div></td>
                    </tr>
                  </table></td>
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