<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$msg="";
$tblNam="TG_SchoolTeacherTypeWise";
$countTotal="SELECT * FROM $tblNam where SchoolID='$loggedSchool'";

if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$SchoolID=$_REQUEST['SchoolID'];
	$TeacherTypeID=$_REQUEST['TeacherTypeID'];
	$TeacherNeed=$_REQUEST['TeacherNeed'];
	$TeacherAvailable=$_REQUEST['TeacherAvailable'];
	
	$queryGradeSave="INSERT INTO $tblNam
           (SchoolID,TeacherTypeID,TeacherNeed,TeacherAvailable)
     VALUES
           ('$SchoolID','$TeacherTypeID','$TeacherNeed','$TeacherAvailable')";
		   
	$countSql="SELECT * FROM $tblNam where SchoolID='$SchoolID' and TeacherTypeID='$TeacherTypeID'";
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
                      </select></td>
                    </tr>
                    <tr>
                      <td>Teacher Type <span class="form_error">*</span>:</td>
                      <td><input type="hidden" name="cat" value="<?php echo $cat; ?>" />
                <input type="hidden" name="AED" value="<?php echo $AED; ?>" />
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
				<input type="hidden" name="tblName" value="<?php echo $tablename; ?>" />
				<input type="hidden" name="redirect_page" value="<?php echo $redirect_page ?>" />
                <input type="hidden" name="vID" value="<?php echo $id; ?>" />
                <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
                <input type="hidden" name="mainID" value="<?php echo $primaryid; ?>" />
                      <select class="select5" id="TeacherTypeID" name="TeacherTypeID">
                            <option value="">-Select-</option>
                            <?php
                            $sql = "SELECT Cat2003Code,Cat2003Name FROM CD_CAT2003";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                echo '<option value=' . $row['Cat2003Code'] . '>' . $row['Cat2003Name'] . '</option>';
                            }
                            ?>
                        </select>
                      
                       </td>
                    </tr>
                    <tr>
                      <td>Teacher Need :</td>
                      <td><input name="TeacherNeed" type="text" class="input2_n2" id="TeacherNeed" /></td>
                    </tr>
                    <tr>
                      <td>Teacher Available :</td>
                      <td><input name="TeacherAvailable" type="text" class="input2_n2" id="TeacherAvailable" /></td>
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
                        <td width="25%" align="center" bgcolor="#999999">Teacher Type</td>
                        <td width="14%" align="center" bgcolor="#999999">Need</td>
                        <td width="14%" align="center" bgcolor="#999999">Available</td>
                        <td width="14%" align="center" bgcolor="#999999">Overlimit</td>
                        <td width="14%" align="center" bgcolor="#999999">Under Limit</td>
                        <td width="16%" align="center" bgcolor="#999999">Delete</td>
                      </tr>
                      <?php 
					  $sqlList="SELECT        TG_SchoolTeacherTypeWise.TeacherNeed, TG_SchoolTeacherTypeWise.TeacherAvailable, TG_TeachersType.Title, TG_SchoolTeacherTypeWise.ID
FROM            TG_SchoolTeacherTypeWise INNER JOIN
                         TG_TeachersType ON TG_SchoolTeacherTypeWise.TeacherTypeID = TG_TeachersType.ID 
						 where TG_SchoolTeacherTypeWise.SchoolID='$loggedSchool'
						 ORDER BY TG_TeachersType.Title";
					  /*$sqlList="SELECT [ID]
      ,[SchoolID]
      ,[GradeID]
  FROM [dbo].[TG_SchoolGradeMaster]
  where SchoolID='SC05428'";*/
  $i=1;
   $stmt = $db->runMsSqlQuery($sqlList);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					  $TypeTitle=$row['Title'];
					  $Expr1=$row['ID'];
					  $TeacherNeed=$row['TeacherNeed'];
					  $TeacherAvailable=$row['TeacherAvailable'];
					  
					  $balanceTeachOver=$balanceTeachUnder=0;
					  $balanceTeachUnder=$TeacherNeed-$TeacherAvailable;
					  if($balanceTeachOver<0)$balanceTeachOver=$TeacherAvailable-$TeacherNeed;
                            
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $TypeTitle ?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php echo $TeacherNeed ?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php echo $TeacherAvailable ?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php echo $balanceTeachOver ?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php echo $balanceTeachUnder ?></td>
                        <td bgcolor="#FFFFFF" align="center"><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','','<?php echo $tblNam ?>','<?php echo "$ttle-$pageid.html";?>')">Delete
                          <?php //echo $Expr1 ?>
                        </a></td>
                      </tr>
                      <?php }?>
                     
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