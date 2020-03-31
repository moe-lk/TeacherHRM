<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$msg="";
$tblNam="TG_SchoolClassStructure";
if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$SchoolID=$_REQUEST['SchoolID'];
	$ClassID=$_REQUEST['ClassID'];
	$GradeID=$_REQUEST['GradeID'];
	$LearningPointID=$_REQUEST['LearningPointID'];
	$TeacherInChargeID=$_REQUEST['TeacherInChargeID'];
	$queryGradeSave="INSERT INTO $tblNam
           (SchoolID,ClassID,GradeID,LearningPointID,TeacherInChargeID)
     VALUES
           ('$SchoolID','$ClassID','$GradeID','$LearningPointID','$TeacherInChargeID')";
		   
	$countSql="SELECT * FROM $tblNam where SchoolID='$SchoolID' and ClassID='$ClassID' and GradeID='$GradeID' and LearningPointID='$LearningPointID'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		$msg="Already exist.";
	}else{ 
		$msg="Successfully Updated.";
		$db->runMsSqlQuery($queryGradeSave);
	}
}

$params1 = array(
	array($GradeID, SQLSRV_PARAM_IN),
	array($SchoolID, SQLSRV_PARAM_IN)
);
?>


<div class="main_content_inner_block">
    <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if($msg!=''){//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
   	  <div class="mcib_middle1">
          <div class="mcib_middle_full">
          <div class="form_error"><?php echo $msg;echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
        </div>
        <?php }//}?>
        <table width="945" cellpadding="0" cellspacing="0">
			  <tr>
                  <td width="59%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                    <tr>
                      <td>School :</td>
                      <td> <select class="select2a" id="SchoolID" name="SchoolID">
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
                      <td>Grade :</td>
                      <td><select class="select2a_new" id="GradeID" name="GradeID" onchange="filterClass();">
                            <option value="">-Select-</option>
                            <?php
                            $sql = "SELECT ID,GradeTitle FROM TG_SchoolGrade";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                echo '<option value=' . $row['ID'] . '>' . $row['GradeTitle'] . '</option>';
                            }
                            ?>
                        </select>
                        <input type="hidden" name="cat" value="<?php echo $cat; ?>" />
                        <input type="hidden" name="AED" value="<?php echo $AED; ?>" />
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="hidden" name="tblName" value="<?php echo $tablename; ?>" />
                        <input type="hidden" name="redirect_page" value="<?php echo $redirect_page ?>" />
                        <input type="hidden" name="vID" value="<?php echo $id; ?>" />
                        <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
                        <input type="hidden" name="mainID" value="<?php echo $primaryid; ?>" /></td>
                    </tr>
                     <tr>
                      <td>Class :</td>
                      <td>
                      <select id="ClassID" name="ClassID" class="select2a_new">
                        <option value="">-Select-</option>
                        <?php $sql = "{call SP_TG_GetClassOfGrade( ?, ?)}";
    $dataSchool = "<option value=\"\">All</option>";
    $stmt = $db->runMsSqlQuery($sql, $params1);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $dataSchool.= '<option value=' . $row['ID'] . '>' . $row['ClassID'] . '</option>';
    }?>
                        </select>
                      </td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                    </tr>
                    </table>
        </td>
        <td width="41%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
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
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#999999"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="15%" height="30" bgcolor="#CCCCCC">&nbsp;</td>
                        <td width="17%" align="center" bgcolor="#CCCCCC"><strong>Monday</strong></td>
                        <td width="17%" align="center" bgcolor="#CCCCCC"><strong>Tuesday</strong></td>
                        <td width="17%" align="center" bgcolor="#CCCCCC"><strong>Wednesday</strong></td>
                        <td width="17%" align="center" bgcolor="#CCCCCC"><strong>Thursday</strong></td>
                        <td width="17%" align="center" bgcolor="#CCCCCC"><strong>Friday</strong></td>
                      </tr>
                      <?php for($i=1;$i<9;$i++){?>
                      <tr>
                        <td rowspan="2" bgcolor="#CCCCCC"><strong>Period <?php echo $i ?></strong></td>
                        <td height="30" bgcolor="#FFFFFF">Subject</td>
                        <td bgcolor="#FFFFFF">Subject</td>
                        <td bgcolor="#FFFFFF">Subject</td>
                        <td bgcolor="#FFFFFF">Subject</td>
                        <td bgcolor="#FFFFFF">Subject</td>
                      </tr>
                      <tr>
                        <td height="30" bgcolor="#FFFFFF"><select name="MO<?php echo $i ?>" class="select2a_new" id="select">
                        </select></td>
                        <td bgcolor="#FFFFFF"><select name="TU<?php echo $i ?>" class="select2a_new" id="select2">
                        </select></td>
                        <td bgcolor="#FFFFFF"><select name="WE<?php echo $i ?>" class="select2a_new" id="select3">
                        </select></td>
                        <td bgcolor="#FFFFFF"><select name="TH<?php echo $i ?>" class="select2a_new" id="select4">
                        </select></td>
                        <td bgcolor="#FFFFFF"><select name="FR<?php echo $i ?>" class="select2a_new" id="select5">
                        </select></td>
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