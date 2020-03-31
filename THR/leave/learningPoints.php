<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$msg="";
$tblNam="TG_SchoolLearningPoints";
$countTotal="SELECT * FROM $tblNam where SchoolID='$loggedSchool'";

if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$SchoolID=$_REQUEST['SchoolID'];
	$LearningPointID=$_REQUEST['LearningPointID'];
	$LearningPointName=$_REQUEST['LearningPointName'];
	$PhysicalRef=$_REQUEST['PhysicalRef'];
	$TeacherInChargeID=$_REQUEST['TeacherInChargeID'];
	$queryGradeSave="INSERT INTO $tblNam
           (SchoolID,LearningPointID,LearningPointName,PhysicalRef,TeacherInChargeID)
     VALUES
           ('$SchoolID','$LearningPointID','$LearningPointName','$PhysicalRef','$TeacherInChargeID')";
		   
	$countSql="SELECT * FROM $tblNam where SchoolID='$SchoolID' and LearningPointID='$LearningPointID'";
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
        <?php }?>
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
                      <td>Learning Point ID :</td>
                      <td><input type="hidden" name="cat" value="<?php echo $cat; ?>" />
                        <input type="hidden" name="AED" value="<?php echo $AED; ?>" />
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="hidden" name="tblName" value="<?php echo $tablename; ?>" />
                        <input type="hidden" name="redirect_page" value="<?php echo $redirect_page ?>" />
                        <input type="hidden" name="vID" value="<?php echo $id; ?>" />
                        <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
                        <input type="hidden" name="mainID" value="<?php echo $primaryid; ?>" />
                        <input name="LearningPointID" type="text" class="input2" id="LearningPointID" /></td>
                    </tr>
                    <tr>
                      <td>Learning Point Name :</td>
                      <td><input name="LearningPointName" type="text" class="input2" id="LearningPointName" /></td>
                    </tr>
                    <tr>
                      <td>Physical Referance :</td>
                      <td><input name="PhysicalRef" type="text" class="input2" id="PhysicalRef" /></td>
                    </tr>
                    <tr>
                      <td>Teacher in Charge<span class="form_error">*</span> :</td>
                      <td><select class="select2a" id="TeacherInChargeID" name="TeacherInChargeID">
                            <option value="">-Select-</option>
                            <?php
                            $sql = "SELECT        TeacherMast.CurResRef, StaffServiceHistory.InstCode, CD_CensesNo.InstitutionName, TeacherMast.ID, TeacherMast.SurnameWithInitials, 
                         TeacherMast.FullName,TeacherMast.NIC
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
                        <td width="3%" height="25" align="center" bgcolor="#999999">#</td>
                        <td width="31%" align="center" bgcolor="#999999">School</td>
                        <td width="14%" align="center" bgcolor="#999999">Learning Point ID</td>
                        <td width="17%" align="center" bgcolor="#999999">Learning Point Name</td>
                        <td width="14%" align="center" bgcolor="#999999">Physical Referance.</td>
                        <td width="15%" align="center" bgcolor="#999999">Teacher in Charge</td>
                        <td width="6%" align="center" bgcolor="#999999">Delete</td>
                      </tr>
                      <?php 								 
						
					$sqlList="SELECT        TeacherMast.NIC, TeacherMast.SurnameWithInitials, CD_CensesNo.CenCode, CD_CensesNo.InstitutionName, TG_SchoolLearningPoints.PhysicalRef, 
                         TG_SchoolLearningPoints.LearningPointName, TG_SchoolLearningPoints.LearningPointID, TG_SchoolLearningPoints.ID
FROM            TeacherMast INNER JOIN
                         TG_SchoolLearningPoints ON TeacherMast.NIC = TG_SchoolLearningPoints.TeacherInChargeID INNER JOIN
                         CD_CensesNo ON TG_SchoolLearningPoints.SchoolID = CD_CensesNo.CenCode
						 where TG_SchoolLearningPoints.SchoolID='$loggedSchool'
						 ORDER BY TG_SchoolLearningPoints.LearningPointName";
  $i=1;
   $stmt = $db->runMsSqlQuery($sqlList);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					  $InstitutionName=$row['InstitutionName'];
					  $Expr1=$row['ID'];
					  
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $InstitutionName ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['LearningPointID']; ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['LearningPointName']; ?></td>
                        <td bgcolor="#FFFFFF" align="left"><?php echo $row['PhysicalRef']; ?></td>
                        <td bgcolor="#FFFFFF" align="left"><?php echo $row['SurnameWithInitials']; ?></td>
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