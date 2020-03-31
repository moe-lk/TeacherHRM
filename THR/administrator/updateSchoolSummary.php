<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$msg="";
$tblNam="TG_SchoolSummary";

if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$TotalNoofStudents=$_REQUEST['TotalNoofStudents'];
	$Grade1t5Classes=$_REQUEST['Grade1t5Classes'];
	$Grade6t11Classes=$_REQUEST['Grade6t11Classes'];
	$ScienceClasses=$_REQUEST['ScienceClasses'];
	$CommerceClasses=$_REQUEST['CommerceClasses'];
	$ArtClasses=$_REQUEST['ArtClasses'];
	$Grade1t5Students=$_REQUEST['Grade1t5Students'];
	$Grade6t11Students=$_REQUEST['Grade6t11Students'];
	$ScienceStudents=$_REQUEST['ScienceStudents'];
	$CommerceStudents=$_REQUEST['CommerceStudents'];
	$ArtStudents=$_REQUEST['ArtStudents'];
	$GradeFrom=$_REQUEST['GradeFrom'];
	$GradeTo=$_REQUEST['GradeTo'];
	$TeacherRequired=$_REQUEST['TeacherRequired'];
	
	if($loggedSchool!=''){
		$queryGradeSave="INSERT INTO $tblNam
			   (SchoolID,TotalNoofStudents,Grade1t5Classes,Grade6t11Classes,ScienceClasses,CommerceClasses,ArtClasses,Grade1t5Students,Grade6t11Students,ScienceStudents,CommerceStudents,ArtStudents,GradeFrom,GradeTo,TeacherRequired)
		 VALUES
			   ('$loggedSchool','$TotalNoofStudents','$Grade1t5Classes','$Grade6t11Classes','$ScienceClasses','$CommerceClasses','$ArtClasses','$Grade1t5Students','$Grade6t11Students','$ScienceStudents','$CommerceStudents','$ArtStudents','$GradeFrom','$GradeTo','$TeacherRequired')";
			   
		$queryUpate="UPDATE $tblNam
		SET		   TotalNoofStudents='$TotalNoofStudents',
		Grade1t5Classes='$Grade1t5Classes',
		Grade6t11Classes='$Grade6t11Classes',
		ScienceClasses='$ScienceClasses',
		CommerceClasses='$CommerceClasses',
		ArtClasses='$ArtClasses',
		Grade1t5Students='$Grade1t5Students',
		Grade6t11Students='$Grade6t11Students',
		ScienceStudents='$ScienceStudents',
		CommerceStudents='$CommerceStudents',
		ArtStudents='$ArtStudents',
		GradeFrom='$GradeFrom',
		GradeTo='$GradeTo',
		TeacherRequired='$TeacherRequired'
		
			   WHERE
			   SchoolID='$loggedSchool'";
			   
		$countSql="SELECT * FROM $tblNam where SchoolID='$loggedSchool'";
		$isAvailable=$db->rowAvailable($countSql);
		if($isAvailable==1){
			//$msg="Already exist.";
			$db->runMsSqlQuery($queryUpate);
		}else{ 
			$db->runMsSqlQuery($queryGradeSave);
			//$newID=$db->runMsSqlQueryInsert($queryGradeSave);
			$msg="Successfully Updated.";
		}
	}else{
		$msg="Please enter the Title..";
	}
	//sqlsrv_query($queryGradeSave);
}

$countTotal="SELECT        TG_SchoolSummary.ID, TG_SchoolSummary.SchoolID, TG_SchoolSummary.TotalNoofStudents, TG_SchoolSummary.Grade1t5Classes, 
                         TG_SchoolSummary.Grade6t11Classes, TG_SchoolSummary.ScienceClasses, TG_SchoolSummary.CommerceClasses, TG_SchoolSummary.ArtClasses, 
                         TG_SchoolSummary.Grade1t5Students, TG_SchoolSummary.Grade6t11Students, TG_SchoolSummary.ScienceStudents, TG_SchoolSummary.CommerceStudents, 
                         TG_SchoolSummary.ArtStudents, TG_SchoolSummary.GradeFrom, TG_SchoolSummary.GradeTo, CD_CensesNo.InstitutionName
FROM            TG_SchoolSummary INNER JOIN
                         CD_CensesNo ON TG_SchoolSummary.SchoolID = CD_CensesNo.CenCode
WHERE        (TG_SchoolSummary.SchoolID = '$loggedSchool')";

$stmtTG = $db->runMsSqlQuery($countTotal);
while ($row = sqlsrv_fetch_array($stmtTG, SQLSRV_FETCH_ASSOC)) {
	$InstitutionName=$row['InstitutionName'];
	$TotalNoofStudents=$row['TotalNoofStudents'];
	$Grade1t5Classes=$row['Grade1t5Classes'];
	$Grade6t11Classes=$row['Grade6t11Classes'];
	$ScienceClasses=$row['ScienceClasses'];
	$CommerceClasses=$row['CommerceClasses'];
	$ArtClasses=$row['ArtClasses'];
	$Grade1t5Students=$row['Grade1t5Students'];
	$Grade6t11Students=$row['Grade6t11Students'];
	$ScienceStudents=$row['ScienceStudents'];
	$CommerceStudents=$row['CommerceStudents'];
	$ArtStudents=$row['ArtStudents'];
	$GradeFrom=$row['GradeFrom'];
	$GradeTo=$row['GradeTo'];
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
                      <td>School Name :</td>
                      <td><?php echo $InstitutionName ?></td>
                    </tr>
                    <tr>
                      <td>Total Students :</td>
                      <td><input name="TotalNoofStudents" type="text" class="input3new" id="TotalNoofStudents" value="<?php echo $TotalNoofStudents ?>"/></td>
                    </tr>
                    <tr>
                      <td>Teacher Required :</td>
                      <td><input name="TeacherRequired" type="text" class="input3new" id="TeacherRequired" value="<?php echo $TeacherRequired ?>"/></td>
                    </tr>
                    <tr>
                      <td valign="top">Grade From :</td>
                      <td><input name="GradeFrom" type="text" class="input3new" id="GradeFrom" value="<?php echo $GradeFrom ?>"/></td>
                    </tr>
                    <tr>
                      <td valign="top">Grade To :</td>
                      <td><input name="GradeTo" type="text" class="input3new" id="GradeTo" value="<?php echo $GradeTo ?>"/></td>
                    </tr>
                    <tr>
                      <td valign="top">Summary :</td>
                      <td><table width="100%" cellspacing="1" cellpadding="1">
                        <tr>
                          <td width="31%">&nbsp;</td>
                          <td width="33%">No. of Classes</td>
                          <td width="36%">No. of Students</td>
                        </tr>
                        <tr>
                          <td>Grade 1-5</td>
                          <td><input name="Grade1t5Classes" type="text" class="input3new" id="Grade1t5Classes" value="<?php echo $Grade1t5Classes ?>"/></td>
                          <td><input name="Grade1t5Students" type="text" class="input3new" id="Grade1t5Students" value="<?php echo $Grade1t5Students ?>"/></td>
                        </tr>
                        <tr>
                          <td>Grade 6-11</td>
                          <td><input name="Grade6t11Classes" type="text" class="input3new" id="Grade6t11Classes" value="<?php echo $Grade6t11Classes ?>"/></td>
                          <td><input name="Grade6t11Students" type="text" class="input3new" id="Grade6t11Students" value="<?php echo $Grade6t11Students ?>"/></td>
                        </tr>
                        <tr>
                          <td>Science</td>
                          <td><input name="ScienceClasses" type="text" class="input3new" id="ScienceClasses" value="<?php echo $ScienceClasses ?>"/></td>
                          <td><input name="ScienceStudents" type="text" class="input3new" id="ScienceStudents" value="<?php echo $ScienceStudents ?>"/></td>
                        </tr>
                        <tr>
                          <td>Commerce</td>
                          <td><input name="CommerceClasses" type="text" class="input3new" id="CommerceClasses" value="<?php echo $CommerceClasses ?>"/></td>
                          <td><input name="CommerceStudents" type="text" class="input3new" id="CommerceStudents" value="<?php echo $CommerceStudents ?>"/></td>
                        </tr>
                        <tr>
                          <td>Art</td>
                          <td><input name="ArtClasses" type="text" class="input3new" id="ArtClasses" value="<?php echo $ArtClasses ?>"/></td>
                          <td><input name="ArtStudents" type="text" class="input3new" id="ArtStudents" value="<?php echo $ArtStudents ?>"/></td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                    </tr>
                    </table>
        </td>
        <td width="44%" valign="top">&nbsp;</td>
          </tr>
                
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table>
    </div>
    
    </form>
</div><!--
