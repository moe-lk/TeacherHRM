<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$msg="";
$tblNam="TG_Employee_Inquiry";
$countTotal="SELECT * FROM $tblNam where NIC='$NICUser'";

if(isset($_POST["FrmSubmit"])){	
	//echo "hi"; NIC, SurnameWithInitials, MobileTel, ,InqType, InqDescription      
	$NIC=$_REQUEST['NIC'];
	$SurnameWithInitials=$_REQUEST['SurnameWithInitials'];
	$CenCode=$_REQUEST['CenCode'];
	$MobileTel=$_REQUEST['MobileTel'];
	$InqType=$_REQUEST['InqType'];
	$InqDescription=addslashes($_REQUEST['InqDescription']);
	$dDateTime=date('Y-m-d H:i:s');
	
	$sqlServiceRef=" SELECT        TeacherMast.CurServiceRef, CD_CensesNo.ZoneCode
FROM            StaffServiceHistory INNER JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode INNER JOIN
                         TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
WHERE        (TeacherMast.NIC = '$NIC')";
	$stmtCAllready= $db->runMsSqlQuery($sqlServiceRef);
	$rowAllready = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC);
	$CurServiceRef=trim($rowAllready['CurServiceRef']);
	$ZoneCode=trim($rowAllready['ZoneCode']);
	

	$RecordLog="Initial Record";
	
	if($InqDescription!=''){
		$queryGradeSave="INSERT INTO TG_Employee_Inquiry
			   (NIC,SurnameWithInitials,CenCode,MobileTel,InqType,InqDescription,dDateTime,RecordLog,IsAnswered,Answer,AnsweredDate,AnswerBy,ZoneCode)
		 VALUES
			   ('$NIC','$SurnameWithInitials','$CenCode','$MobileTel','$InqType','$InqDescription','$dDateTime','$RecordLog','N','','$AnsweredDate','$AnswerBy','$ZoneCode')";
			   
			$db->runMsSqlQuery($queryGradeSave);
			//$newID=$db->runMsSqlQueryInsert($queryGradeSave);
			$msg="Successfully Updated.";
	}else{
		$msg="Please enter the Inquiry Description.";
	}
	//sqlsrv_query($queryGradeSave);
}
if($menu==''){
	$sqlInq="SELECT        TeacherMast.NIC, TeacherMast.SurnameWithInitials, CD_CensesNo.InstitutionName, CD_CensesNo.CenCode, TeacherMast.MobileTel
FROM            TeacherMast INNER JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID INNER JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
WHERE        (TeacherMast.NIC = N'$NICUser')";	
	$stmtTyp = $db->runMsSqlQuery($sqlInq);
	$row = sqlsrv_fetch_array($stmtTyp, SQLSRV_FETCH_ASSOC);
    $SurnameWithInitials=trim($row['SurnameWithInitials']);
	$InstitutionName=trim($row['InstitutionName']);
	$CenCode=trim($row['CenCode']);
	$MobileTel=trim($row['MobileTel']);
}else{
	$sqlView="SELECT        TG_Employee_Inquiry.SurnameWithInitials, TG_Employee_Inquiry.CenCode, TG_Employee_Inquiry.MobileTel, TG_Employee_Inquiry.InqType, 
                         TG_Employee_Inquiry.InqDescription, CONVERT(varchar(20), TG_Employee_Inquiry.dDateTime, 121) AS dDateTime, TG_Employee_Inquiry.RecordLog, 
                         TG_Employee_Inquiry.IsAnswered, TG_Employee_Inquiry.Answer, CONVERT(varchar(20), TG_Employee_Inquiry.AnsweredDate, 121) AS AnsweredDate, 
                         TG_Employee_Inquiry.AnswerBy, CD_CensesNo.InstitutionName
FROM            TG_Employee_Inquiry INNER JOIN
                         CD_CensesNo ON TG_Employee_Inquiry.CenCode = CD_CensesNo.CenCode
						 WHERE TG_Employee_Inquiry.ID='$menu'";
	$stmtTyp = $db->runMsSqlQuery($sqlView);
	$row = sqlsrv_fetch_array($stmtTyp, SQLSRV_FETCH_ASSOC);
    $SurnameWithInitials=trim($row['SurnameWithInitials']);
	$InstitutionName=trim($row['InstitutionName']);
	$MobileTel=trim($row['MobileTel']);
	$InqType=trim($row['InqType']);
	$InqDescription=trim($row['InqDescription']);
	$dDateTime=trim($row['dDateTime']);
	$IsAnswered=trim($row['IsAnswered']);
	$AnsweredDate=trim($row['AnsweredDate']);
	$AnswerBy=trim($row['AnswerBy']);
	$CenCode=trim($row['CenCode']);
	
	$IsAnsweredTxt="No";
	$answerDate=$answer=$SurnameWithInitialsAnswer="-";
	if($IsAnswered=='Y')$IsAnsweredTxt="Yes";
	if($AnswerBy!=''){
		$sqlAns="Select SurnameWithInitials from TeacherMast where NIC='$AnswerBy'";
		$stmtTyp = $db->runMsSqlQuery($sqlAns);
		$row = sqlsrv_fetch_array($stmtTyp, SQLSRV_FETCH_ASSOC);
    	$SurnameWithInitialsAnswer=trim($row['SurnameWithInitials']);
	}
}
	
$TotaRows=$db->rowCount($countTotal);
?>


<div class="main_content_inner_block">
    <form method="post" action="Inquiry-7.html" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if($msg!=''){//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
   	  <div class="mcib_middle1">
          <div class="mcib_middle_full">
          <div class="form_error"><?php echo $msg; echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
        </div>
        <?php }?>
        <table width="100%" cellpadding="0" cellspacing="0">
       <?php  if($menu!='' || $id!=''){?>
			  <tr>
			    <td valign="top">&nbsp;</td>
			    <td valign="top">&nbsp;</td>
	      </tr>
			  <tr>
                  <td width="50%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                   
                    <tr>
                      <td>NIC Number<span class="form_error"> *</span>:</td>
                      <td><input name="NIC" type="text" class="input2" id="NIC" value="<?php echo $NICUser ?>" readonly="readonly"/></td>
                    </tr>
                    <tr>
                      <td>Name with initials :</td>
                      <td><input name="SurnameWithInitials" type="text" class="input2" id="SurnameWithInitials" value="<?php echo $SurnameWithInitials ?>" readonly="readonly"/></td>
                    </tr>
                    <tr>
                      <td>Contact Number :</td>
                      <td><input name="MobileTel" type="text" class="input2" id="MobileTel" value="<?php echo $MobileTel ?>"/></td>
                    </tr>
                    <tr>
                      <td>Institution Name</td>
                      <td><input name="InstitutionName" type="text" class="input2" id="InstitutionName" value="<?php echo $InstitutionName ?>" readonly="readonly"/><input type="hidden" name="CenCode" value="<?php echo $CenCode ?>" /></td>
                    </tr>
                    <?php if($menu==''){?>
                    <tr>
                      <td>&nbsp;</td>
                      <td><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                    </tr>
                    <?php }?>
                    </table>
        </td>
        <td width="50%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                <tr>
                  <td width="25%" align="left" valign="top">Type :</td>
                  <td width="75%" valign="top"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="7%"><input type="radio" name="InqType" id="radio" value="B" <?php if($InqType=='B'){?>checked="checked" <?php }?>/></td>
                      <td width="42%">Bug</td>
                      <td width="7%"><input type="radio" name="InqType" id="radio2" value="I" <?php if($InqType=='I'){?>checked="checked" <?php }?>/></td>
                      <td width="44%">Issue</td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td>Description :</td>
                  <td width="75%" rowspan="3" valign="top"><textarea name="InqDescription" cols="55" rows="4" class="textarea1auto" id="InqDescription"><?php echo $InqDescription ?></textarea></td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
          </table></td>
          </tr>
          <?php if($menu!=''){?>
			  <tr>
			    <td colspan="2" valign="top" style="border-bottom: 1px; border-bottom-style: solid; font-size: 14px; font-weight: bold;">Feedback</td>
	      </tr>
			  <tr>
			    <td valign="top"><table width="100%" cellspacing="1" cellpadding="1">
			      <tr>
			        <td width="25%">Is Answered :</td>
			        <td width="75%"><?php echo $IsAnsweredTxt ?></td>
		          </tr>
			      <tr>
			        <td>Answer By :</td>
			        <td><?php echo $SurnameWithInitialsAnswer ?></td>
		          </tr>
			      <tr>
			        <td>Answer Date :</td>
			        <td><?php echo $answerDate ?></td>
		          </tr>
		        </table></td>
			    <td valign="top"><table width="100%" cellspacing="1" cellpadding="1">
			      <tr>
			        <td width="26%">Answer :</td>
			        <td width="74%"><?php echo $answer ?></td>
		          </tr>
		        </table></td>
	      </tr>
			<?php }?>  
			  <tr>
			    <td valign="top">&nbsp;</td>
			    <td valign="top">&nbsp;</td>
	      </tr>
          <?php }?>
          <?php  if($menu=='' and $id==''){?>
                <tr>
                  <td><?php echo $TotaRows ?> Record(s) found.</td>
                  <td align="right"><a href="Inquiry-7--A.html"><img src="../cms/images/addnew.png" width="90" height="26" alt="addnew" /></a></td>
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="2%" height="25" align="center" bgcolor="#999999">#</td>
                        <td width="55%" align="center" bgcolor="#999999">Description</td>
                        <td width="18%" align="center" bgcolor="#999999">Created Date</td>
                        <td width="13%" align="center" bgcolor="#999999">Answer</td>
                        <td width="12%" align="center" bgcolor="#999999">Delete</td>
                      </tr>
                      <?php 
					 $sqlList="SELECT ID, IsAnswered, InqDescription,CONVERT(varchar(20), dDateTime, 121) AS dDateTime From TG_Employee_Inquiry where NIC='$NICUser'";
					  
  $i=1;
   $stmt = $db->runMsSqlQuery($sqlList);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					  $InqDescription=stripslashes($row['InqDescription']);
					  $dDateTime=$row['dDateTime'];
					  $IsAnswered=$row['IsAnswered'];
					  $Expr1=$row['ID'];
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF" align="left"><?php echo $InqDescription ?></td>
                        <td bgcolor="#FFFFFF" align="left"><?php echo $dDateTime ?></td>
                        <td bgcolor="#FFFFFF" align="center"><a href="<?php echo "$ttle-$pageid-$Expr1.html";?>">View</a></td>
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
          <?php }?>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table>
    </div>
    
    </form>
</div>