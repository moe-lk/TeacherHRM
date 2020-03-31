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
$tblNam="TG_SchoolSubjectTeacher";
$countTotal="SELECT * FROM $tblNam where SchoolID='$loggedSchool'";

/* $getClassValue="SELECT        TG_SchoolClassStructure.ID, TG_SchoolClassStructure.SchoolID, TG_SchoolClassStructure.GradeID, CD_CensesNo.InstitutionName, TG_SchoolClassStructure.ClassID, 
                         TG_SchoolGrade.GradeTitle
FROM            TG_SchoolClassStructure INNER JOIN
                         CD_CensesNo ON TG_SchoolClassStructure.SchoolID = CD_CensesNo.CenCode INNER JOIN
                         TG_SchoolGrade ON TG_SchoolClassStructure.GradeID = TG_SchoolGrade.ID
  Where TG_SchoolClassStructure.ID='$id'"; */
  $getClassValue="SELECT     TG_SchoolClassStructure.ID, TG_SchoolClassStructure.SchoolID, TG_SchoolClassStructure.GradeID, CD_CensesNo.InstitutionName, 
                      TG_SchoolClassStructure.ClassID, TG_SchoolGrade.GradeTitle, TG_SchoolGradeMaster.GradeID AS Expr1
FROM         TG_SchoolClassStructure INNER JOIN
                      CD_CensesNo ON TG_SchoolClassStructure.SchoolID = CD_CensesNo.CenCode INNER JOIN
                      TG_SchoolGradeMaster ON TG_SchoolClassStructure.SchoolID = TG_SchoolGradeMaster.SchoolID AND 
                      TG_SchoolClassStructure.GradeID = TG_SchoolGradeMaster.ID INNER JOIN
                      TG_SchoolGrade ON TG_SchoolGradeMaster.GradeID = TG_SchoolGrade.ID
WHERE     (TG_SchoolClassStructure.ID = '$id')";
 $stmtGetv = $db->runMsSqlQuery($getClassValue);

while ($rowGetv = sqlsrv_fetch_array($stmtGetv, SQLSRV_FETCH_ASSOC)) {
	$SchoolIDCS=trim($rowGetv['SchoolID']);
	$GradeIDCS=trim($rowGetv['GradeID']);
	$ClassIDCS=$rowGetv['ClassID'];
	$InstitutionName=$rowGetv['InstitutionName'];
	$GradeTitle=$rowGetv['GradeTitle'];
}

if($fm=='A'){
// Update TG_SchoolSubjectTeacher Start

	$updateSchool="SELECT        TG_SchoolClassStructure.ID, TG_SchoolClassStructure.SchoolID, TG_SchoolClassStructure.ClassID, TG_SchoolClassStructure.GradeID, 
							 TG_SchoolClassStructure.LearningPointID, TG_SchoolClassStructure.TeacherInChargeID, TG_SchoolSubjectMaster.GradeID AS Expr1, TG_SchoolSubjectMaster.GroupSubject, TG_SchoolSubjectMaster.IsNeedSupportTeacher, 
							 TG_SchoolSubjectMaster.SubjectID
	FROM            TG_SchoolClassStructure INNER JOIN
							 TG_SchoolSubjectMaster ON TG_SchoolClassStructure.GradeID = TG_SchoolSubjectMaster.GradeID
	  where TG_SchoolClassStructure.ID='$id'";
	  
	$stmtUpd = $db->runMsSqlQuery($updateSchool);
	
	while ($rowUpd = sqlsrv_fetch_array($stmtUpd, SQLSRV_FETCH_ASSOC)) {
		
		$SchoolID=$rowUpd['SchoolID'];
		$ClassID=$rowUpd['ID'];//Class Structure ID
		$GradeID=$rowUpd['GradeID'];
		$SubjectID=$rowUpd['SubjectID'];
		$GroupSubject=trim($rowUpd['GroupSubject']);
		//TeacherID
		
			$queryGradeSave="INSERT INTO $tblNam
			   (SchoolID,GradeID,ClassID,SubjectID)
			 VALUES
				   ('$SchoolID','$GradeID','$ClassID','$SubjectID')";
				   
			$countSql="SELECT * FROM $tblNam where SchoolID='$SchoolID' and GradeID='$GradeID' and ClassID='$ClassID' and SubjectID='$SubjectID'";
			$isAvailable=$db->rowAvailable($countSql);
			if($isAvailable==1){
				$msg="Already exist.";
			}else{ 
				$db->runMsSqlQuery($queryGradeSave);
				$msg="Successfully Updated.";
			}
			
			//Group Subjects
			$GroupSubjectz=explode(",",$GroupSubject);
			for($f=0;$f<count($GroupSubjectz);$f++){
			  $sCode=trim($GroupSubjectz[$f]);
			  if($sCode){
				  /* $sqlsg = "SELECT SubjectName FROM CD_Subject where SubCode='$sCode'";
				  $stmtsg = $db->runMsSqlQuery($sqlsg);
				while ($rowsg = sqlsrv_fetch_array($stmtsg, SQLSRV_FETCH_ASSOC)) {
					
					
				} */
				
				$queryGradeSaveG="INSERT INTO $tblNam (SchoolID,GradeID,ClassID,SubjectID)
				 VALUES ('$SchoolID','$GradeID','$ClassID','$sCode')";
					   
				$countSqlG="SELECT * FROM $tblNam where SchoolID='$SchoolID' and GradeID='$GradeID' and ClassID='$ClassID' and SubjectID='$sCode'";
				$isAvailableG=$db->rowAvailable($countSqlG);
				if($isAvailableG==1){
					$msg="Already exist.";
				}else{ 
					$db->runMsSqlQuery($queryGradeSaveG);
					$msg="Successfully Updated.";
				}
			  }
			}
			
			
	}
// Update TG_SchoolSubjectTeacher End			
  
}


if(isset($_POST["FrmSubmit"])){ 
	$thisSubCodeArr=$_REQUEST['thisSubCode'];
	//print_r($thisSubCodeArr);
	for($x=0;$x<count($thisSubCodeArr);$x++){
		$codeSub=$thisSubCodeArr[$x];
		
		if($codeSub!=''){
			$teacherField="TeacherID".$codeSub;
			$teacherFieldSupport="TeacherSupportID".$codeSub;
			
			
			$TechCode=$_REQUEST[$teacherField];
			$TechCodeSupport=$_REQUEST[$teacherFieldSupport];
		//echo "hi"; //exit();
			$queryUpate="UPDATE $tblNam
			SET		   TeacherID='$TechCode', TeacherSupportID='$TechCodeSupport'
			WHERE
				   ID='$codeSub'";
		    $db->runMsSqlQuery($queryUpate);
		}
			   
	}
	
}
$TotaRows=$db->rowCount($countTotal);
?>


<div class="main_content_inner_block">
    <form method="post" action="subject_teachers-11--<?php echo $id ?>.html" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if($msg!=''){//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
   	  <div class="mcib_middle1">
          <div class="mcib_middle_full">
          <div class="form_error"><?php echo $msg; echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
        </div>
        <?php }?>
        <table width="100%" cellpadding="0" cellspacing="0">
			  <tr>
			    <td valign="top"><?php echo $InstitutionName ?> : <?php echo "$GradeTitle-$ClassIDCS" ?> [Assign Teachers]</td>
			    <td valign="top">&nbsp;</td>
	      </tr>
			  <tr>
                  <td width="56%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                  
                  <?php
				  $thisSubCode=array();
                            $sqlsub = "SELECT        TG_SchoolSubjectTeacher.ID, TG_SchoolSubjectTeacher.TeacherID, CD_Subject.SubjectName, CD_Subject.SubCode , TG_SchoolSubjectTeacher.TeacherSupportID
FROM            TG_SchoolSubjectTeacher INNER JOIN
                         CD_Subject ON TG_SchoolSubjectTeacher.SubjectID = CD_Subject.SubCode
  where TG_SchoolSubjectTeacher.SchoolID='$SchoolIDCS' and TG_SchoolSubjectTeacher.GradeID='$GradeIDCS' and TG_SchoolSubjectTeacher.ClassID='$id'";
                            $stmtSub = $db->runMsSqlQuery($sqlsub);
                            while ($rowSub = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
                               
                           		$IDCS=$rowSub['ID'];
								$TeacherIDCS=trim($rowSub['TeacherID']);
								//$SchoolIDTS=trim($rowSub['SchoolID']);
								//echo $GradeIDTS=trim($rowSub['GradeID']);
								$SubCode=trim($rowSub['SubCode']);
								$TeacherSupportIDTS=trim($rowSub['TeacherSupportID']);
								//$thisSubCode[]=$SubCode;
								//$dropName="TeacherID_".$SchoolIDTS."_".$GradeIDTS;
								 
								
								$checkSupportAvailable="SELECT IsNeedSupportTeacher FROM TG_SchoolSubjectMaster where SchoolID='$SchoolIDCS' and GradeID='$GradeIDCS' and SubjectID='$SubCode'";
								$stmtSubcheckSupportAvailable = $db->runMsSqlQuery($checkSupportAvailable);
								$rowcheckSupportAvailable = sqlsrv_fetch_array($stmtSubcheckSupportAvailable, SQLSRV_FETCH_ASSOC);
								$IsNeedSupportTeacher=trim($rowcheckSupportAvailable['IsNeedSupportTeacher']);
                            ?>
                    <tr>
                      <td> <?php echo $rowSub['SubjectName']; ?> :<input type="hidden" name="thisSubCode[]" value="<?php echo $IDCS ?>"></td>
                      <td><select class="select2a" id="TeacherID<?php echo $IDCS ?>" name="TeacherID<?php echo $IDCS ?>">
                            <option value="">-Select Teacher-</option>
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
								$SurnameWithInitials=$row['SurnameWithInitials'];
								$NICT=trim($row['NIC']);
								
								$selText="";		
								if ($NICT==$TeacherIDCS) { //array search
								$selText="selected";
								} else {
								   $selText="";
								} 
							
                                echo "<option value=\"$NICT\" $selText>$SurnameWithInitials</option>";
                            }
                            ?>
                      </select></td>
                    </tr>
                   <?php if($IsNeedSupportTeacher=='Y'){?>
                    <tr>
                      <td bgcolor="#CCFF99"><strong>Support Teacher :</strong></td>
                      <td bgcolor="#CCFF99"><select class="select2a" id="TeacherSupportID<?php echo $IDCS ?>" name="TeacherSupportID<?php echo $IDCS ?>">
                            <option value="">-Select Teacher-</option>
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
								$SurnameWithInitials=$row['SurnameWithInitials'];
								$NICT=trim($row['NIC']);
								
								$selText="";		
								if ($NICT==$TeacherSupportIDTS) { //array search
								$selText="selected";
								} else {
								   $selText="";
								} 
							
                                echo "<option value=\"$NICT\" $selText>$SurnameWithInitials</option>";
                            }
                            ?>
                      </select></td>
                    </tr>
                    <?php }?>
                    <?php }?>
                    <?php if($fm=='DAD'){
					for($f=0;$f<count($GroupSubjectz);$f++){
						  $sCode=$GroupSubjectz[$f];
						  if($sCode){
							  $sqlsg = "SELECT SubjectName FROM CD_Subject where SubCode='$sCode'";
                              $stmtsg = $db->runMsSqlQuery($sqlsg);
                            while ($rowsg = sqlsrv_fetch_array($stmtsg, SQLSRV_FETCH_ASSOC)) {
								
						   // $thisSubCode[]=$sCode;
							echo '<input type="hidden" name="thisSubCode[]" value="'. $sCode. '">';
					  
					?>
                    <tr>
                      <td> <?php echo $rowsg['SubjectName']; ?> :</td>
                      <td><select class="select2a" id="TeacherID<?php echo $SubCode ?>" name="TeacherID<?php echo $SubCode ?>">
                            <option value="">-Select Teacher-</option>
                            <?php
                            
  $sql="SELECT        TeacherMast.CurResRef, StaffServiceHistory.InstCode, CD_CensesNo.InstitutionName, TeacherMast.ID, TeacherMast.SurnameWithInitials, 
                         TeacherMast.FullName, TeacherMast.NIC
FROM            TeacherMast INNER JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID INNER JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
						 where (StaffServiceHistory.InstCode='$loggedSchool') AND (StaffServiceHistory.ServiceRecTypeCode != 'RT01' or StaffServiceHistory.ServiceRecTypeCode IS NULL)
						 order by TeacherMast.SurnameWithInitials";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                echo '<option value=' . $row['NIC'] . '>' . $row['SurnameWithInitials'] . '</option>';
                            }
                            ?>
                      </select></td>
                    </tr>
                    <?php  }
						  }
					  }
					  
					}?>
                    
                    <tr>
                      <td><?php 
					  
					  $sql="SELECT        TeacherMast.CurResRef, StaffServiceHistory.InstCode, CD_CensesNo.InstitutionName, TeacherMast.ID, TeacherMast.SurnameWithInitials, 
                         TeacherMast.FullName, TeacherMast.NIC
FROM            TeacherMast INNER JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID INNER JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
						 where (StaffServiceHistory.InstCode='$loggedSchool') AND (StaffServiceHistory.ServiceRecTypeCode != 'RT01' or StaffServiceHistory.ServiceRecTypeCode IS NULL)
						 order by TeacherMast.SurnameWithInitials";
						 
						echo  $TotaRows=$db->rowCount($sql);echo "_";
						
						$sql2="SELECT        TeacherMast.CurResRef, StaffServiceHistory.InstCode, CD_CensesNo.InstitutionName, TeacherMast.ID, TeacherMast.SurnameWithInitials, 
                         TeacherMast.FullName, TeacherMast.NIC
FROM            TeacherMast INNER JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID INNER JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
						 where (StaffServiceHistory.InstCode='$loggedSchool') AND (StaffServiceHistory.ServiceRecTypeCode != 'RT01' or StaffServiceHistory.ServiceRecTypeCode IS NULL)
						 order by TeacherMast.SurnameWithInitials";
						 
						 echo  $TotaRows=$db->rowCount($sql2);echo "_";
						 ?></td>
                      <td><input type="hidden" name="cat" value="<?php echo $cat; ?>" />
                <input type="hidden" name="AED" value="<?php echo $AED; ?>" />
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
				<input type="hidden" name="tblName" value="<?php echo $tablename; ?>" />
				<input type="hidden" name="redirect_page" value="<?php echo $redirect_page ?>" />
                <input type="hidden" name="vID" value="<?php echo $id; ?>" />
                <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
                <input type="hidden" name="mainID" value="<?php echo $primaryid; ?>" />
                
                     
                      
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