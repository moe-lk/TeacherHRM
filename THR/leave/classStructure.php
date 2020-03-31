<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$msg="";
$tblNam="TG_SchoolClassStructure";
$countTotal="SELECT * FROM $tblNam where SchoolID='$loggedSchool'";

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
$TotaRows=$db->rowCount($countTotal);
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
                      <td><select class="select5" id="GradeID" name="GradeID">
                            <option value="">-Select-</option>
                            <?php
                            $sql = "SELECT ID,GradeTitle FROM TG_SchoolGrade";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                echo '<option value=' . $row['ID'] . '>' . $row['GradeTitle'] . '</option>';
                            }
                            ?>
                        </select></td>
                    </tr>
                     <tr>
                      <td>Class :</td>
                      <td><input name="ClassID" type="text" class="input4" id="ClassID" /></td>
                    </tr>
                    <tr>
                      <td>Learning Point ID :</td>
                      <td><input type="hidden" name="cat" value="<?php echo $cat; ?>" />
                        <input type="hidden" name="AED" value="<?php echo $AED; ?>" />
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="hidden" name="tblName" value="<?php echo $tablename; ?>" />
                        <input type="hidden" name="redirect_page" value="<?php echo $redirect_page ?>" />
                        <input type="hidden" name="vID" value="<?php echo $id; ?>" />
                        <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
                        <input type="hidden" name="mainID" value="<?php echo $primaryid; ?>" />
                        <select class="select2a" id="LearningPointID" name="LearningPointID">
                            <option value="">-Select-</option>
                            <?php
                            $sql = "SELECT [ID]
      ,[SchoolID]
      ,[LearningPointID]
      ,[LearningPointName]
      ,[PhysicalRef]
      ,[TeacherInChargeID]
  FROM [dbo].[TG_SchoolLearningPoints]
  where SchoolID='$loggedSchool'
  order by LearningPointName";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                echo '<option value=' . $row['ID'] . '>' . $row['LearningPointName'] . '</option>';
                            }
                            ?>
                      </select></td>
                    </tr>
                    <tr>
                      <td>Teacher in Charge<span class="form_error">*</span> :</td>
                      <td><select class="select2a" id="TeacherInChargeID" name="TeacherInChargeID">
                            <option value="">-Select-</option>
                            <?php
                            
  $sql="SELECT        TeacherMast.CurResRef, StaffServiceHistory.InstCode, CD_CensesNo.InstitutionName, TeacherMast.ID, TeacherMast.SurnameWithInitials, 
                         TeacherMast.FullName, TeacherMast.NIC
FROM            TeacherMast INNER JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID INNER JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
						 where StaffServiceHistory.InstCode='$loggedSchool'
						 order by TeacherMast.SurnameWithInitials";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                echo '<option value=' . $row['NIC'] . '>' . $row['SurnameWithInitials'] . '</option>';
                            }
                            ?>
                      </select></td>
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
                  <td><?php echo $TotaRows ?> Record(s) found.</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="3%" height="25" align="center" bgcolor="#999999"><strong>#</strong></td>
                        <td width="31%" align="center" bgcolor="#999999"><strong>School</strong></td>
                        <td width="11%" align="center" bgcolor="#999999"><strong>Grade</strong></td>
                        <td width="20%" align="center" bgcolor="#999999"><strong>Class</strong></td>
                        <td width="14%" align="center" bgcolor="#999999"><strong>Learning Point</strong></td>
                        <td width="15%" align="center" bgcolor="#999999"><strong>Teacher in Charge</strong></td>
                        <td width="6%" align="center" bgcolor="#999999"><strong>Delete</strong></td>
                      </tr>
                      <?php 
					  
					  $sqlList="SELECT        TG_SchoolGrade.GradeTitle, CD_CensesNo.CenCode, CD_CensesNo.InstitutionName, TeacherMast.NIC, TeacherMast.SurnameWithInitials, 
                        TG_SchoolClassStructure.ID, TG_SchoolLearningPoints.LearningPointName, TG_SchoolClassStructure.ClassID
FROM TG_SchoolGrade INNER JOIN
                         TG_SchoolClassStructure ON TG_SchoolGrade.ID = TG_SchoolClassStructure.GradeID INNER JOIN
                         CD_CensesNo ON TG_SchoolClassStructure.SchoolID = CD_CensesNo.CenCode INNER JOIN
                         TeacherMast ON TG_SchoolClassStructure.TeacherInChargeID = TeacherMast.NIC INNER JOIN
                         TG_SchoolLearningPoints ON TG_SchoolClassStructure.LearningPointID = TG_SchoolLearningPoints.ID
where TG_SchoolClassStructure.SchoolID='$loggedSchool'
						 ORDER BY TG_SchoolGrade.GradeTitle";
						
					
  $i=1;
   $stmt = $db->runMsSqlQuery($sqlList);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					  
					  $Expr1=$row['ID'];
					  
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['InstitutionName']; ?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php echo $row['GradeTitle']; ?></td>
                        <td bgcolor="#FFFFFF" align="center"><?php echo $row['ClassID']; ?></td>
                        <td bgcolor="#FFFFFF" align="left"><?php echo $row['LearningPointName']; ?></td>
                        <td bgcolor="#FFFFFF" align="left"><?php echo $row['SurnameWithInitials']; ?></td>
                        <td bgcolor="#FFFFFF" align="center"><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','','<?php echo $tblNam ?>','<?php echo "$ttle-$pageid.html";?>')">Delete <?php //echo $Expr1 ?></a></td>
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