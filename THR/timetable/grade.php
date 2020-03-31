<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function check_form(form_name) {
  if (submitted == true) {
    alert("This form has already been submitted. Please press Ok and wait for this process to be completed.");
    return false;
  }

  error = false;
  form = form_name;
  error_message = "Please enter following details:\n";
  
  //check_input("subject", 2, "Feedback Subject.");
  //check_input("captInput", 2, "Verification Code.");
  check_select("GradeID", "", "Grade.");
 
  if (error == true) {
    alert(error_message);
    return false;
  } else {
    submitted = true;
    return true;
  }
}
//--></script><?php 
$msg="";
$tblNam="TG_SchoolGradeMaster";
$countTotal="SELECT * FROM $tblNam where SchoolID='$loggedSchool'";

if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$SchoolID=$_REQUEST['SchoolID'];
	$GradeID=$_REQUEST['GradeID'];
	if($SchoolID!='' and $GradeID!=''){
	$queryGradeSave="INSERT INTO $tblNam
           (SchoolID,GradeID)
     VALUES
           ('$SchoolID','$GradeID')";
		   
	$countSql="SELECT * FROM $tblNam where SchoolID='$SchoolID' and GradeID='$GradeID'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		$msg="Already exist.";
	}else{ 
		$db->runMsSqlQuery($queryGradeSave);
		$msg="Successfully Updated.";
	}
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
        <table width="100%" cellpadding="0" cellspacing="0">
			  <tr>
                  <td width="56%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                    <tr>
                      <td>School :</td>
                      <td> <select class="select2a_n" id="SchoolID" name="SchoolID">
                            <!--<option value="">School Name</option>-->
                            <?php
                            $sql = "SELECT [InstType]
      ,[CenCode]
      ,[InstitutionName]
      ,[DistrictCode]
      ,[RecordLog]
      ,[ZoneCode]
      ,[DivisionCode]
      ,[IsNationalSchool]
      ,[SchoolType]
  FROM [dbo].[CD_CensesNo]
  where CenCode='$loggedSchool'
  order by InstitutionName";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                echo '<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
                            }
                            ?>
                      </select>
                      </td>
                    </tr>
                    <tr>
                      <td>Grade <span class="form_error">*</span>:</td>
                      <td><input type="hidden" name="cat" value="<?php echo $cat; ?>" />
                <input type="hidden" name="AED" value="<?php echo $AED; ?>" />
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
				<input type="hidden" name="tblName" value="<?php echo $tablename; ?>" />
				<input type="hidden" name="redirect_page" value="<?php echo $redirect_page ?>" />
                <input type="hidden" name="vID" value="<?php echo $id; ?>" />
                <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
                <input type="hidden" name="mainID" value="<?php echo $primaryid; ?>" />
                      <select class="select5" id="GradeID" name="GradeID">
                            <option value="">-Select-</option>
                            <?php
                            $sql = "SELECT ID,GradeTitle FROM TG_SchoolGrade Order by GradeTitle ASC";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                echo '<option value=' . $row['ID'] . '>' . $row['GradeTitle'] . '</option>';
                            }
                            ?>
                        </select>
                      
                       </td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                    </tr>
                    </table>
        </td>
        <td width="44%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                <tr>
                  <td align="left" valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
          </table></td>
          </tr>
                <tr>
                  <td><?php echo $TotaRows ?> Record(s) found.</td>
                  <td><strong>Not Allow :-</strong> This grade used by another module(s)</td>
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="6%" height="25" align="center" bgcolor="#999999">#</td>
                         <td width="6%" height="25" align="center" bgcolor="#999999">ID</td>
                        <td width="46%" align="center" bgcolor="#999999">School</td>
                        <td width="20%" align="center" bgcolor="#999999">Grade</td>
                        <td width="22%" align="center" bgcolor="#999999">Delete</td>
                      </tr>
                      <?php 
					  $sqlList="SELECT        CD_CensesNo.InstitutionName, CD_CensesNo.CenCode, TG_SchoolGrade.ID, TG_SchoolGrade.GradeTitle, TG_SchoolGradeMaster.ID AS Expr1
FROM            TG_SchoolGradeMaster INNER JOIN
                         TG_SchoolGrade ON TG_SchoolGradeMaster.GradeID = TG_SchoolGrade.ID INNER JOIN
                         CD_CensesNo ON TG_SchoolGradeMaster.SchoolID = CD_CensesNo.CenCode 
						 where TG_SchoolGradeMaster.SchoolID='$loggedSchool'
						 ORDER BY TG_SchoolGrade.GradeTitle";
					  /*$sqlList="SELECT [ID]
      ,[SchoolID]
      ,[GradeID]
  FROM [dbo].[TG_SchoolGradeMaster]
  where SchoolID='SC05428'";*/
  $i=1;
   $stmt = $db->runMsSqlQuery($sqlList);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					  $InstitutionName=$row['InstitutionName'];
					  $Expr1=$row['Expr1'];
					  
					  $countTotalGr="SELECT GradeID from TG_SchoolSubjectMaster where GradeID='$Expr1'";
					  $TotaRowsGr=$db->rowCount($countTotalGr);					  
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $Expr1; ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $InstitutionName ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['GradeTitle']; ?></td>
                        <td bgcolor="#FFFFFF" align="center"><?php if($TotaRowsGr>0){echo "Not Allow";}else{?><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','','<?php echo $tblNam ?>','<?php echo "$ttle-$pageid.html";?>')">Delete <?php //echo $Expr1 ?></a><?php }?></td>
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