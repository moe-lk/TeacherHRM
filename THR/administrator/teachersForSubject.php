<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$msg="";
$tblNam="TG_SchoolTeachersForSubject";
$countTotal="SELECT * FROM $tblNam where SchoolID='$loggedSchool'";

if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$SchoolID=$_REQUEST['SchoolID'];
	$SubjectID=$_REQUEST['SubjectID'];
	$NoofTeachingTeachers=$_REQUEST['NoofTeachingTeachers'];
	$NoofAppointTeachers=$_REQUEST['NoofAppointTeachers'];
	//TeachingTeacher,AppointTeacher
	$queryGradeSave="INSERT INTO $tblNam
           (SchoolID,SubjectID,NoofTeachingTeachers,NoofAppointTeachers)
     VALUES
           ('$SchoolID','$SubjectID','$NoofTeachingTeachers','$NoofAppointTeachers')";
		   
	$countSql="SELECT * FROM $tblNam where SchoolID='$SchoolID' and SubjectID='$SubjectID'";
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
                  <td width="87%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                    <tr>
                      <td width="28%">School :</td>
                      <td width="72%"> <select class="select2a_n" id="SchoolID" name="SchoolID">
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
                      <td>Subject <span class="form_error">*</span>:</td>
                      <td><input type="hidden" name="cat" value="<?php echo $cat; ?>" />
                          <input type="hidden" name="AED" value="<?php echo $AED; ?>" />
				                  <input type="hidden" name="id" value="<?php echo $id; ?>" />
				                  <input type="hidden" name="tblName" value="<?php echo $tablename; ?>" />
				                  <input type="hidden" name="redirect_page" value="<?php echo $redirect_page ?>" />
                          <input type="hidden" name="vID" value="<?php echo $id; ?>" />
                          <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
                          <input type="hidden" name="mainID" value="<?php echo $primaryid; ?>" />
                      <select class="select2a" id="SubjectID" name="SubjectID">
                            <option value="">-Select-</option>
                            <?php
                            $sql = "SELECT [SubCode]
      ,[SubjectName]
      ,[RecordLog]
  FROM [dbo].[CD_Subject] order by SubjectName";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                echo '<option value=' . $row['SubCode'] . '>' . $row['SubjectName'] . '</option>';
                            }
                            ?>
                      </select>
                      
                       </td>
                    </tr>
                    <tr>
                      <td>No. of Teachers (Teaching) :</td>
                      <td><input name="NoofTeachingTeachers" type="text" class="input2_n2" id="NoofTeachingTeachers" /></td>
                    </tr>
                    <tr>
                      <td>No. of Teachers (Appoint) :</td>
                      <td><input name="NoofAppointTeachers" type="text" class="input2_n2" id="NoofAppointTeachers" /></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                    </tr>
                    </table>
        </td>
        <td width="13%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
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
                        <td width="2%" rowspan="2" align="center" bgcolor="#999999">#</td>
                        <td width="22%" rowspan="2" align="center" bgcolor="#999999">School</td>
                        <td width="26%" rowspan="2" align="center" bgcolor="#999999">Subject</td>
                        <td height="25" colspan="2" align="center" bgcolor="#999999">Working</td>
                        <td colspan="2" align="center" bgcolor="#999999">In the System</td>
                        <td width="8%" rowspan="2" align="center" bgcolor="#999999">Status</td>
                        <td width="6%" rowspan="2" align="center" bgcolor="#999999">Delete</td>
                      </tr>
                      <tr>
                        <td width="9%" height="25" align="center" bgcolor="#999999">Teaching</td>
                        <td width="9%" align="center" bgcolor="#999999">Appoint</td>
                        <td width="9%" align="center" bgcolor="#999999">Teaching</td>
                        <td width="9%" align="center" bgcolor="#999999">Appoint</td>
                      </tr>
                      <?php 
					  $sqlList="SELECT        TG_SchoolTeachersForSubject.ID, TG_SchoolTeachersForSubject.SchoolID, TG_SchoolTeachersForSubject.SubjectID, TG_SchoolTeachersForSubject.NoofTeachingTeachers, 
                         TG_SchoolTeachersForSubject.NoofAppointTeachers, TG_SchoolTeachersForSubject.AddedBy, CD_Subject.SubjectName, CD_CensesNo.InstitutionName
FROM            TG_SchoolTeachersForSubject INNER JOIN
                         CD_Subject ON TG_SchoolTeachersForSubject.SubjectID = CD_Subject.SubCode INNER JOIN
                         CD_CensesNo ON TG_SchoolTeachersForSubject.SchoolID = CD_CensesNo.CenCode
WHERE        (TG_SchoolTeachersForSubject.SchoolID = '$loggedSchool')";
					  /*$sqlList="SELECT [ID]
      ,[SchoolID]
      ,[GradeID]
  FROM [dbo].[TG_SchoolGradeMaster]
  where SchoolID='SC05428'";*/
  $i=1;
   $stmt = $db->runMsSqlQuery($sqlList);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					  $InstitutionName=$row['InstitutionName'];
					  $Expr1=$row['ID'];
					  $SubjectName=$row['SubjectName'];
					  $NoofTeachingTeachers=$row['NoofTeachingTeachers'];
					  $NoofAppointTeachers=$row['NoofAppointTeachers'];
					  
					  
					  $balanceTeachOver=$balanceTeachUnder=0;
					  $balanceTeachUnder=$TeacherNeed-$TeacherAvailable;
					  if($balanceTeachOver<0)$balanceTeachOver=$TeacherAvailable-$TeacherNeed;
                            
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $InstitutionName ?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php echo $SubjectName ?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php echo $NoofTeachingTeachers ?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php echo $NoofAppointTeachers ?></td>
                        <td align="center" bgcolor="#FFFFFF">&nbsp;</td>
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