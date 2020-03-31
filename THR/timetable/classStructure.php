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
  check_input("ClassID", 1, "Class.");
  check_select("LearningPointID", "", "Learning Point.");
  check_select("TeacherInChargeID", "", "Teacher Incharge.");
  //check_input_num_validate("PeriodsPerWeek",1,"Periods Per Week");
  if (error == true) {
    alert(error_message);
    return false;
  } else {
    submitted = true;
    return true;
  }
}
//--></script>

<?php 
$msg="";
$tblNam="TG_SchoolClassStructure";
$countTotal="SELECT * FROM $tblNam where SchoolID='$loggedSchool'";
/* $sqlList="SELECT        TG_SchoolGrade.GradeTitle, CD_CensesNo.CenCode, CD_CensesNo.InstitutionName, TeacherMast.NIC, TeacherMast.SurnameWithInitials, 
                         TG_SchoolClassStructure.ID, TG_SchoolLearningPoints.LearningPointName, TG_SchoolClassStructure.ClassID
FROM            CD_CensesNo INNER JOIN
                         TG_SchoolClassStructure ON CD_CensesNo.CenCode = TG_SchoolClassStructure.SchoolID INNER JOIN
                         TeacherMast ON TG_SchoolClassStructure.TeacherInChargeID = TeacherMast.NIC INNER JOIN
                         TG_SchoolLearningPoints ON TG_SchoolClassStructure.LearningPointID = TG_SchoolLearningPoints.ID INNER JOIN
                         TG_SchoolGradeMaster ON TG_SchoolClassStructure.GradeID = TG_SchoolGradeMaster.ID INNER JOIN
                         TG_SchoolGrade ON TG_SchoolGradeMaster.GradeID = TG_SchoolGrade.ID
where TG_SchoolClassStructure.SchoolID='$loggedSchool'
						 ORDER BY TG_SchoolGrade.GradeTitle"; */

if(isset($_POST["FrmSubmit"])){	
	if($menu==''){
	//echo "hi";
		$SchoolID=$_REQUEST['SchoolID'];
		$ClassID=$_REQUEST['ClassID'];
		$GradeID=$_REQUEST['GradeID'];
		$LearningPointID=$_REQUEST['LearningPointID'];
		$TeacherInChargeID=$_REQUEST['TeacherInChargeID'];
		
		if($SchoolID!='' and $GradeID!='' and $ClassID!='' and $LearningPointID!='' and $TeacherInChargeID!=''){
			
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
	}else{
		$ClassIDx=$_REQUEST['ClassID'];
		$GradeIDx=$_REQUEST['GradeID'];
		$LearningPointIDx=$_REQUEST['LearningPointID'];
		$TeacherInChargeIDx=$_REQUEST['TeacherInChargeID'];
		$LocationTypex=$_REQUEST['LocationType'];
		$vID=$_REQUEST['vID'];
		$canUpdate=$_REQUEST['canUpdate'];
				
		if($canUpdate=='Y'){
			$queryMainUpdate = "UPDATE $tblNam SET ClassID='$ClassIDx',GradeID='$GradeIDx',LearningPointID='$LearningPointIDx',TeacherInChargeID='$TeacherInChargeIDx' WHERE ID='$vID'";
		}else{
			$queryMainUpdate = "UPDATE $tblNam SET LearningPointID='$LearningPointIDx',TeacherInChargeID='$TeacherInChargeIDx' WHERE ID='$vID'";			
		}//NIC='$NICUser' and AddrType='PER'";
		$db->runMsSqlQuery($queryMainUpdate);
		$newHisID=$fmRec;
		$msg = "Form submitted successfully.";
		$menu="";
		//redirect("subject-2.html");
		header("Location:$ttle-$pageid.html");
     	exit() ;
	}
}
$TotaRows=$db->rowCount($countTotal);

if($menu=='E'){
	//echo "hiiiiiiiii";
	$editRecords="SELECT * FROM $tblNam where ID='$id'";
	$stmt = $db->runMsSqlQuery($editRecords);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
	$SchoolID=trim($row['SchoolID']);
	$ClassID=trim($row['ClassID']);
	$GradeID=trim($row['GradeID']);
	$LearningPointID=trim($row['LearningPointID']);
	$TeacherInChargeID=trim($row['TeacherInChargeID']);
	
	$countTotalGr="SELECT SubjectID from TG_SchoolSubjectGroup where (SchoolID='$SchoolID') and (ClassGrouped like'%,$id,%')";
	$TotaRowsGr=$db->rowCount($countTotalGr);	
					  
	$countTotalGrrr="SELECT ClassID from TG_SchoolTimeTable where (SchoolID='$SchoolID') and (ClassID='$id')";
	$TotaRowsGrrr=$db->rowCount($countTotalGrrr);	
	
	$canUpdate="Y";
	if($TotaRowsGrrr>0 || $TotaRowsGr>0)$canUpdate="N";
} 

?>


<div class="main_content_inner_block">
    <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if($msg!=''){//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
   	  <div class="mcib_middle1">
          <div class="mcib_middle_full">
          <div class="form_error"><?php echo $msg;echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
        </div>
        <?php }//}?>
        <table width="100%" cellpadding="0" cellspacing="0">
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
                      <td>Grade<span class="form_error">*</span> :</td>
                      <td><select class="select5" id="GradeID" name="GradeID" <?php if($canUpdate=='N'){?> disabled="disabled"<?php }?>>
                            <option value="">-Select-</option>
                            <?php
                            $sql = "SELECT        TG_SchoolGrade.GradeTitle, TG_SchoolGrade.NumberOfPeriods, TG_SchoolGradeMaster.ID
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolGradeMaster ON TG_SchoolGrade.ID = TG_SchoolGradeMaster.GradeID
						 WHERE TG_SchoolGradeMaster.SchoolID='$loggedSchool' Order by GradeTitle ASC";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$IDg=trim($row['ID']);
								$GradeTitle=$row['GradeTitle'];
								$seltebr="";
								if($IDg==$GradeID){
								   $seltebr="selected=\"selected\"";
								   //$disble="disabled=\"disabled\"";
								}
							    echo "<option value=\"$IDg\" $seltebr>$GradeTitle</option>";
                               // echo '<option value=' . $row['ID'] . '>' . $row['GradeTitle'] . '</option>';
                            }
                            ?>
                        </select></td>
                    </tr>
                     <tr>
                      <td>Class<span class="form_error">*</span> :</td>
                      <td><input name="ClassID" type="text" class="input3" id="ClassID" value="<?php echo $ClassID ?>" <?php if($canUpdate=='N'){?> readonly="readonly"<?php }?>/></td>
                    </tr>
                    <tr>
                      <td>Learning Location ID<span class="form_error">*</span> :</td>
                      <td><input type="hidden" name="cat" value="<?php echo $cat; ?>" />
                        <input type="hidden" name="AED" value="<?php echo $AED; ?>" />
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="hidden" name="tblName" value="<?php echo $tablename; ?>" />
                        <input type="hidden" name="redirect_page" value="<?php echo $redirect_page ?>" />
                        <input type="hidden" name="vID" value="<?php echo $id; ?>" />
                        <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
                        <input type="hidden" name="canUpdate" value="<?php echo $canUpdate; ?>" />
                        <input type="hidden" name="mainID" value="<?php echo $primaryid; ?>" />
                        <select class="select2a" id="LearningPointID" name="LearningPointID" onchange="Javascript:show_incharge('show_incharge',this.options[this.selectedIndex].value,document.frmSave.SchoolID.value);">
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
								$IDl=trim($row['ID']);
								$LearningPointName=$row['LearningPointName'];
								$seltebr="";
								if($IDl==$LearningPointID){
								   $seltebr="selected=\"selected\"";
								   //$disble="disabled=\"disabled\"";
								}
							    echo "<option value=\"$IDl\" $seltebr>$LearningPointName</option>";
                               // echo '<option value=' . $row['ID'] . '>' . $row['LearningPointName'] . '</option>';
                            }
                            ?>
                      </select></td>
                    </tr>
                    <tr>
                      <td>Teacher in Charge<span class="form_error">*</span> :</td>
                      <td><div id="txt_incharge"><select class="select2a" id="TeacherInChargeID" name="TeacherInChargeID">
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
								$NICi=trim($row['NIC']);
								$SurnameWithInitials=$row['SurnameWithInitials'];
								$seltebr="";
								if($NICi==$TeacherInChargeID){
								   $seltebr="selected=\"selected\"";
								   //$disble="disabled=\"disabled\"";
								}
							    echo "<option value=\"$NICi\" $seltebr>$SurnameWithInitials</option>";
                                //echo '<option value=' . $row['NIC'] . '>' . $row['SurnameWithInitials'] . '</option>';
                            }
                            ?>
                      </select></div></td>
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
                  <td><strong>Not Allow :-</strong> This record used by another module(s)</td>
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="2%" height="25" align="center" bgcolor="#999999"><strong>#</strong></td>
                        <td width="15%" align="center" bgcolor="#999999"><strong>School</strong></td>
                        <td width="5%" align="center" bgcolor="#999999"><strong>Grade</strong></td>
                        <td width="7%" align="center" bgcolor="#999999"><strong>Class</strong></td>
                        <td width="15%" align="center" bgcolor="#999999"><strong>Learning Point</strong></td>
                        <td width="25%" align="center" bgcolor="#999999"><strong>Teacher in Charge</strong></td>
                        <td width="11%" align="center" bgcolor="#999999">Other Teachers</td>
                        <td width="10%" align="center" bgcolor="#999999">Edit</td>
                        <td width="10%" align="center" bgcolor="#999999"><strong>Delete</strong></td>
                      </tr>
                      <?php 
					  
					  $sqlList="SELECT        TG_SchoolGrade.GradeTitle, CD_CensesNo.CenCode, CD_CensesNo.InstitutionName, TeacherMast.NIC, TeacherMast.SurnameWithInitials, 
                         TG_SchoolClassStructure.ID, TG_SchoolLearningPoints.LearningPointName, TG_SchoolClassStructure.ClassID
FROM            CD_CensesNo INNER JOIN
                         TG_SchoolClassStructure ON CD_CensesNo.CenCode = TG_SchoolClassStructure.SchoolID INNER JOIN
                         TeacherMast ON TG_SchoolClassStructure.TeacherInChargeID = TeacherMast.NIC INNER JOIN
                         TG_SchoolLearningPoints ON TG_SchoolClassStructure.LearningPointID = TG_SchoolLearningPoints.ID INNER JOIN
                         TG_SchoolGradeMaster ON TG_SchoolClassStructure.GradeID = TG_SchoolGradeMaster.ID INNER JOIN
                         TG_SchoolGrade ON TG_SchoolGradeMaster.GradeID = TG_SchoolGrade.ID
where TG_SchoolClassStructure.SchoolID='$loggedSchool'
						 ORDER BY TG_SchoolGrade.GradeTitle";
						
					
  $i=1;
   $stmt = $db->runMsSqlQuery($sqlList);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					  
					  $Expr1=$row['ID'];
					  $CenCode=trim($row['CenCode']);
					  
					  
					  $countTotalGr="SELECT SubjectID from TG_SchoolSubjectGroup where (SchoolID='$CenCode') and (ClassGrouped like'%,$Expr1,%')";
					  $TotaRowsGr=$db->rowCount($countTotalGr);	
					  
					  $countTotalGrrr="SELECT ClassID from TG_SchoolTimeTable where (SchoolID='$CenCode') and (ClassID='$Expr1')";
					  $TotaRowsGrrr=$db->rowCount($countTotalGrrr);	
					  
					  $deletble="Y";
					  if($TotaRowsGr>0 || $TotaRowsGrrr>0){
						  $deletble="N";
					  }
					  
					  $editLink="<a href=\"$ttle-$pageid-E-$Expr1.html\">Edit</a>";
					 // if($deletble=='N')$editLink="Not Allow";
					  
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['InstitutionName']; ?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php echo $row['GradeTitle']; ?></td>
                        <td bgcolor="#FFFFFF" align="center"><?php echo $row['ClassID']; ?></td>
                        <td bgcolor="#FFFFFF" align="left"><?php echo $row['LearningPointName']; ?></td>
                        <td bgcolor="#FFFFFF" align="left"><?php echo $row['SurnameWithInitials']; ?></td>
                        <td bgcolor="#FFFFFF" align="center"><a href="subject_teachers-11--<?php echo $Expr1 ?>-A.html" target="_blank">Assign</a></td>
                        <td bgcolor="#FFFFFF" align="center"><?php echo $editLink ?></td>
                        <td bgcolor="#FFFFFF" align="center"><?php if($deletble=='Y'){?><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','','<?php echo $tblNam ?>','<?php echo "$ttle-$pageid.html";?>')">Delete <?php //echo $Expr1 ?></a><?php }else{echo "Not Allow";}?></td>
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