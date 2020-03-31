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
  check_select("SubjectID", "", "Subject.");
 check_input_num_validate("PeriodsPerWeek",1,"Periods Per Week");
  if (error == true) {
    alert(error_message);
    return false;
  } else {
    submitted = true;
    return true;
  }
}
//--></script>
<link rel="stylesheet" href="css/bootstrap.min.css" />
<!--<link rel="stylesheet" href="css/bootstrap-theme.min.css" />-->
<!--<link rel="stylesheet" href="lib/google-code-prettify/prettify.css" />-->
<?php 
$msg=""; 
$finalGrade="";
$msg="";
$tblNam="TG_SchoolSubjectMaster";

$countTotal="SELECT * FROM $tblNam where SchoolID='$loggedSchool'";

if(isset($_POST["FrmSubmit"])){
	if($menu==''){
		
	//echo "hi";
	$SchoolID=$_REQUEST['SchoolID'];
	$GradeID=$_REQUEST['GradeID'];
	$SubjectID=$_REQUEST['SubjectID'];
	$PeriodsPerWeek=$_REQUEST['PeriodsPerWeek'];
	$MaxNoPerDay=$_REQUEST['MaxNoPerDay'];
	$IsMainSubject=$_REQUEST['IsMainSubject'];
	$IsNeedSupportTeacher=$_REQUEST['IsNeedSupportTeacher'];
	$finalGrade=$GradeID;
	
	$vComTypeID="";
	for($i=0;$i<count($_REQUEST['GroupSubject']);$i++) {
		$vComTypeID.=$_REQUEST['GroupSubject'][$i].",";
	}
	
	if($SchoolID!='' and $GradeID!='' and $SubjectID!='' and $PeriodsPerWeek!=''){
		
		$queryGradeSave="INSERT INTO $tblNam
			   (SchoolID,GradeID,SubjectID,PeriodsPerWeek,GroupSubject,MaxNoPerDay,IsMainSubject,IsNeedSupportTeacher)
		 VALUES
			   ('$SchoolID','$GradeID','$SubjectID','$PeriodsPerWeek','$vComTypeID','$MaxNoPerDay','$IsMainSubject','$IsNeedSupportTeacher')";
			   
		$countSql="SELECT * FROM $tblNam where SchoolID='$SchoolID' and GradeID='$GradeID' and SubjectID='$SubjectID'";
		$isAvailable=$db->rowAvailable($countSql);
		if($isAvailable==1){
			$msg="Already exist.";
		}else{ 
			$msg="Successfully Updated.";
			$db->runMsSqlQuery($queryGradeSave);
		}
	}
	//sqlsrv_query($queryGradeSave);
	}else{
		$PeriodsPerWeekx=$_REQUEST['PeriodsPerWeek'];
		$IsMainSubjectx=$_REQUEST['IsMainSubject'];
		$IsNeedSupportTeacherx=$_REQUEST['IsNeedSupportTeacher'];
		$MaxNoPerDayx=$_REQUEST['MaxNoPerDay'];
		$vID=$_REQUEST['vID'];
		$vComTypeID="";
		
		for($i=0;$i<count($_REQUEST['GroupSubject']);$i++) {
			$vComTypeID.=$_REQUEST['GroupSubject'][$i].",";
		}
		
		
		$queryMainUpdate = "UPDATE $tblNam SET PeriodsPerWeek='$PeriodsPerWeekx',IsMainSubject='$IsMainSubjectx',IsNeedSupportTeacher='$IsNeedSupportTeacherx', MaxNoPerDay='$MaxNoPerDayx', GroupSubject='$vComTypeID' WHERE ID='$vID'";//NIC='$NICUser' and AddrType='PER'";
		$db->runMsSqlQuery($queryMainUpdate);
		$newHisID=$fmRec;
		$msg = "Form submitted successfully.";
		$menu="";
		//redirect("subject-2.html");
		header("Location:subject-2.html");
     exit() ;
		
	}
}
$TotaRows=$db->rowCount($countTotal);

if($menu=='E'){
	//echo "hiiiiiiiii";
	$editRecords="SELECT * FROM $tblNam where ID='$id'";
	$stmt = $db->runMsSqlQuery($editRecords);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
	$GradeID=$row['GradeID'];
	$SubjectID=trim($row['SubjectID']);
	$PeriodsPerWeek=$row['PeriodsPerWeek'];
	$GroupSubject=$row['GroupSubject'];
	$MaxNoPerDay=$row['MaxNoPerDay'];
	$IsMainSubject=trim($row['IsMainSubject']);
	$IsNeedSupportTeacher=trim($row['IsNeedSupportTeacher']);
} 
?>


<div class="main_content_inner_block">
    <form method="post" action="" name="frmSavex" id="frmSavex" enctype="multipart/form-data" onSubmit="return check_form(frmSavex);">
        <?php if($msg!=''){//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
   	  <div class="mcib_middle1">
          <div class="mcib_middle_full">
          <div class="form_error"><?php echo $msg;echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
        </div>
        <?php }?>
        <table width="100%" cellpadding="0" cellspacing="0">
			  <tr>
                  <td width="54%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                    <tr>
                      <td>School :<?php /* $totSql="SELECT SUM(PeriodsPerWeek) AS 'PeriodsPerWeek' from TG_SchoolSubjectMaster where SchoolID='SC05428' and GradeID='11'";		  
					  
					  
		$stmt = $db->runMsSqlQuery($totSql);
		 while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			 echo $row['PeriodsPerWeek'];
		 } */?></td>
                      <td> <select class="select2a" id="SchoolID" name="SchoolID" <?php if($menu=='E'){?>disabled="disabled"<?php }?>>
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
                      <td>Grade <span class="form_error">*</span>:</td>
                      <td><input type="hidden" name="cat" value="<?php echo $cat; ?>" />
                <input type="hidden" name="AED" value="<?php echo $AED; ?>" />
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
				<input type="hidden" name="tblName" value="<?php echo $tablename; ?>" />
				<input type="hidden" name="redirect_page" value="<?php echo $redirect_page ?>" />
                <input type="hidden" name="vID" value="<?php echo $id; ?>" />
                <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
                <input type="hidden" name="mainID" value="<?php echo $primaryid; ?>" />
                <input type="hidden" name="menu" value="<?php echo $menu; ?>" />
                      <select class="select5" id="GradeID" name="GradeID" onchange="Javascript:show_periodCount('periodCount',this.options[this.selectedIndex].value,document.frmSavex.SchoolID.value);" <?php if($menu=='E'){?>disabled="disabled"<?php }?>>
                            <option value="">-Select-</option>
                            <?php
                            $sql = "SELECT        TG_SchoolGrade.GradeTitle, TG_SchoolGrade.NumberOfPeriods, TG_SchoolGradeMaster.ID
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolGradeMaster ON TG_SchoolGrade.ID = TG_SchoolGradeMaster.GradeID
						 WHERE TG_SchoolGradeMaster.SchoolID='$loggedSchool'";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$grID=$row['ID'];
								$GradeTitle=$row['GradeTitle'];
								$seltebr="";
								if($grID==$GradeID){
								   $seltebr="selected=\"selected\"";
								   //$disble="disabled=\"disabled\"";
								}
							    echo "<option value=\"$grID\" $seltebr>$GradeTitle</option>";
                                //echo '<option value=' . $row['ID'] .''.$seltebr.'>' . $row['GradeTitle'] . '</option>';
                            }
                            ?>
                        </select>
                      
                       </td>
                    </tr>
                    
                    <tr>
                      <td>Subject <span class="form_error">*</span> :</td>
                      <td><select class="select2a" id="SubjectID" name="SubjectID" <?php if($menu=='E'){?>disabled="disabled"<?php }?>>
                            <option value="">-Select-</option>
                            <?php
                            $sql = "SELECT [SubCode]
      ,[SubjectName]
      ,[RecordLog]
  FROM [dbo].[CD_Subject] order by SubjectName";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$grID=trim($row['SubCode']);
								$SubjectName=$row['SubjectName'];
								$seltebr="";
								if($grID==$SubjectID and $menu=='E'){
								   $seltebr="selected=\"selected\"";
								   //$disble="disabled=\"disabled\"";
								}
							    echo "<option value=\"$grID\" $seltebr>$SubjectName</option>";
                                //echo '<option value=' . $row['SubCode'] . '>' . $row['SubjectName'] . '</option>';
                            }
                            ?>
                      </select></td>
                    </tr>
                    <tr>
                      <td>Is Main Subject :</td>
                      <td><input name="IsMainSubject" type="checkbox" id="IsMainSubject" value="Y" <?php if($IsMainSubject=='Y'){?>checked="checked"<?php }?>/></td>
                    </tr>
                    
                    
                    <tr>
                      <td>Need Support Teacher :</td>
                      <td align="left"><input name="IsNeedSupportTeacher" type="checkbox" id="IsNeedSupportTeacher" value="Y" <?php if($IsNeedSupportTeacher=='Y'){?>checked="checked"<?php }?>/></td>
                    </tr>
                    <tr>
                      <td>Group Subjects :</td>
                      <td align="right">
                      </td>
                    </tr>
                    </table>
        </td>
        <td width="46%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                <tr>
                  <td colspan="2" align="left" valign="top"><div id="txt_periodCount">
                        <table width="100%" cellspacing="1" cellpadding="1">
                          <tr>
                            <td width="35%">Already Inserted Periods :</td>
                            <td width="65%"><?php if($menu==''){echo "Please Select a Grade";}else{$totSql="SELECT SUM(PeriodsPerWeek) AS 'PeriodsPerWeek' from TG_SchoolSubjectMaster where SchoolID='$loggedSchool' and GradeID='$GradeID'";
	$stmt = $db->runMsSqlQuery($totSql);
	 $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
		echo  $row['PeriodsPerWeek'];}?></td>
                          </tr>
                        </table>
                      </div></td>
                </tr>
                <tr>
                  <td align="left" valign="top">Periods Per Week <span class="form_error">*</span>:</td>
                  <td align="left"><input name="PeriodsPerWeek" type="text" class="input3" id="PeriodsPerWeek" value="<?php echo $PeriodsPerWeek ?>"/></td>
                </tr>
                <tr>
                  <td align="left" valign="top">Max. Period per Day :</td>
                  <td><input name="MaxNoPerDay" type="text" class="input3" id="MaxNoPerDay" value="<?php echo $MaxNoPerDay ?>"/></td>
                </tr>
                <tr>
                  <td width="36%" align="left" valign="top">&nbsp;</td>
                  <td width="64%">&nbsp;</td>
                </tr>
                <!--
                <tr>
                  <td width="27%" align="left" valign="top">Group Subjects :</td>
                  <td width="73%"><select name="GroupSubjectx[]" size="12" multiple="multiple" class="input_d1x" id="GroupSubjectx[]" <?php if($menu=='E'){?>disabled="disabled"<?php }?>>
                    <?php
			$iAvailTolArr = explode(',',$GroupSubject);
			
			$sql = "SELECT [SubCode]
      ,[SubjectName]
      ,[RecordLog]
  FROM [dbo].[CD_Subject] order by SubjectName";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                //echo '<option value=' . $row['SubCode'] . '>' . $row['SubjectName'] . '</option>';
                            //}

			//for($jj=0;$jj<count($selData11);$jj++){
				$SubCode=trim($row['SubCode']);
				$SubjectName=$row['SubjectName'];
			?>
                    <option value="<?php echo $SubCode ?>" 
			<?php 
						for($n=0;$n<count($iAvailTolArr);$n++){  
							$SelectedKeywordca=trim($iAvailTolArr[$n]);
								if($SubCode==$SelectedKeywordca and $menu=='E'){
									echo 'selected="selected"';
								}
								else{
									echo "";
								}
						}
			?>

			><?php echo "$SubjectName"; ?></option>
                    <?php }?>
                  </select></td>
                </tr>
                -->
                <tr>
                  <td colspan="2">&nbsp;</td>
                </tr>
                </table></td>
          </tr>
         
                <tr>
                  <td align="center">All subjects</td>
                  <td align="center">Selected subject(s)</td>
                </tr>
                <tr>
                  <td colspan="2"><div id="demo" class="container">
            <div class="row">
              <div class="col-xs-5">
                    <select name="from[]" id="undo_redo" class="form-control" size="14" multiple="multiple" <?php if($menu=='EE'){?>disabled="disabled"<?php }?>>
                     <?php
			$iAvailTolArr = explode(',',$GroupSubject);
			
			$sql = "SELECT [SubCode]
      ,[SubjectName]
      ,[RecordLog]
  FROM [dbo].[CD_Subject] order by SubjectName";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                //echo '<option value=' . $row['SubCode'] . '>' . $row['SubjectName'] . '</option>';
                            //}

			//for($jj=0;$jj<count($selData11);$jj++){
				$SubCode=trim($row['SubCode']);
				$SubjectName=$row['SubjectName'];
			?>
                        <option value="<?php echo $SubCode ?>" style="font-size:13px;"><?php echo $SubjectName ?></option>
                       <?php } ?>
                    </select>
                </div>
                
                <div class="col-xs-1">
                    <button type="button" id="undo_redo_undo" class="btn btn-primary btn-block" <?php if($menu=='E'){?>disabled="disabled"<?php }?>>undo</button>
                    <button type="button" id="undo_redo_rightAll" class="btn btn-default btn-block" <?php if($menu=='E'){?>disabled="disabled"<?php }?>><i class="glyphicon glyphicon-forward"></i></button>
                    <button type="button" id="undo_redo_rightSelected" class="btn btn-default btn-block" <?php if($menu=='EE'){?>disabled="disabled"<?php }?>><i class="glyphicon glyphicon-chevron-right"></i></button>
                    <button type="button" id="undo_redo_leftSelected" class="btn btn-default btn-block" <?php if($menu=='EE'){?>disabled="disabled"<?php }?>><i class="glyphicon glyphicon-chevron-left"></i></button>
                    <button type="button" id="undo_redo_leftAll" class="btn btn-default btn-block" <?php if($menu=='E'){?>disabled="disabled"<?php }?>><i class="glyphicon glyphicon-backward"></i></button>
                    <button type="button" id="undo_redo_redo" class="btn btn-warning btn-block" <?php if($menu=='E'){?>disabled="disabled"<?php }?>>redo</button>
                </div>
                
                <div class="col-xs-5"><?php $iAvailTolArr = explode(',',$GroupSubject);
				$newSubCode="";
				for($n=0;$n<count($iAvailTolArr);$n++){  
					$SelectedKeywordca=trim($iAvailTolArr[$n]);
					if($SelectedKeywordca!=''){
					$newSubCode.="'".$SelectedKeywordca."',";
					}
				}
				$newSubCode=substr($newSubCode, 0, -1);
				//$newSubCode=$newSubCode."'";
				//$iAvailTolArr[]="SB1905";
			
		$sql = "SELECT [SubCode]
      ,[SubjectName]
      ,[RecordLog]
  FROM [dbo].[CD_Subject] where SubCode IN($newSubCode) order by SubjectName";
                            $stmt = $db->runMsSqlQuery($sql);?>
                    <select name="GroupSubject[]" id="undo_redo_to" class="form-control" size="14" multiple="multiple" <?php if($menu=='EE'){?>disabled="disabled"<?php }?>>
                     <?php
			
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                //echo '<option value=' . $row['SubCode'] . '>' . $row['SubjectName'] . '</option>';
                            //}

			//for($jj=0;$jj<count($selData11);$jj++){
				$SubCode=trim($row['SubCode']);
				$SubjectName=$row['SubjectName'];
				
				/* for($n=0;$n<count($iAvailTolArr);$n++){  
					$SelectedKeywordca=trim($iAvailTolArr[$n]);
						if($SubCode==$SelectedKeywordca and $menu=='E'){
							echo 'selected="selected"';
						}
						else{
							echo "";
						}
				} */
			?>
                        <option value="<?php echo $SubCode ?>" style="font-size:13px;"><?php echo $SubjectName ?></option>
                       <?php } ?>
                    </select>
                </div>
            </div>
</div></td>
                </tr>
                 
                <tr>
                   <td>&nbsp;</td>
                   <td>&nbsp;</td>
          </tr>
                <tr>
                   <td colspan="2" align="center"><table width="30%" cellspacing="1" cellpadding="2">
                        <tr>
                          <td width="35%"><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                          <td width="65%" valign="bottom"><?php if($menu!=''){?><a href="subject-2.html"><u>View List</u></a><?php }?></td>
                        </tr>
                  </table></td>
          </tr>
          <?php if($menu==''){?>
                <tr>
                   <td>&nbsp;</td>
                   <td>&nbsp;</td>
          </tr>
                <tr>
                  <td><?php echo $TotaRows ?> Record(s) found.</td>
                  <td><strong>Not Allow :-</strong> This record used by another module(s)</td>
                </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td><strong>Can't Modify :-</strong> This record used by another module(s)</td>
                </tr>
                
                <tr>
                  <td colspan="2"><?php 
				  $sqlGrd="SELECT GradeID,COUNT(*) AS 'total' from TG_SchoolSubjectMaster where SchoolID='$loggedSchool' group by GradeID";
				  $stmtGR = $db->runMsSqlQuery($sqlGrd);
	 while ($rowG = sqlsrv_fetch_array($stmtGR, SQLSRV_FETCH_ASSOC)) {
		$GradeIDG=$rowG['GradeID'];
		 
		 $TotalG=$rowG['total'];
		// $sqlGradeTitle="SELECT GradeTitle as Title from TG_SchoolGrade where ID='$GradeIDG'";
		 
		 $sqlGradeTitle="SELECT     TG_SchoolGradeMaster.ID, TG_SchoolGradeMaster.SchoolID, TG_SchoolGradeMaster.GradeID, TG_SchoolGrade.GradeTitle
FROM         TG_SchoolGradeMaster INNER JOIN
                      TG_SchoolGrade ON TG_SchoolGradeMaster.GradeID = TG_SchoolGrade.ID
WHERE     (TG_SchoolGradeMaster.SchoolID = '$loggedSchool') AND (TG_SchoolGradeMaster.ID = '$GradeIDG')";
		 
		 $stmtT = $db->runMsSqlQuery($sqlGradeTitle);
		 while ($rowT = sqlsrv_fetch_array($stmtT, SQLSRV_FETCH_ASSOC)) {
			 $GradeTitle=$rowT['GradeTitle'];
		 }
	?><div style="width:auto; float:left; background-color:#D2CBFE; margin:2px;">Grade <?php echo "$GradeTitle ($TotalG)"; ?></div><?php }?></td>
                </tr>
                               
                <tr>
                    <td colspan="2" bgcolor="#999999"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="4%" height="25" align="center" bgcolor="#CCCCCC">#</td>
                        <td width="17%" align="center" bgcolor="#CCCCCC">School</td>
                        <td width="7%" align="center" bgcolor="#CCCCCC">Grade</td>
                        <td width="18%" align="center" bgcolor="#CCCCCC">Subject</td>
                        <td width="28%" align="center" bgcolor="#CCCCCC">Group Subject</td>
                        <td width="7%" align="center" bgcolor="#CCCCCC">Periods Per Week</td>
                        <td width="10%" align="center" bgcolor="#CCCCCC">Edit</td>
                        <td width="9%" align="center" bgcolor="#CCCCCC">Delete</td>
                      </tr>
                      <?php 
					  
					  $sqlList="SELECT        CD_CensesNo.CenCode, CD_CensesNo.InstitutionName, TG_SchoolGrade.GradeTitle, CD_Subject.SubjectName, TG_SchoolSubjectMaster.PeriodsPerWeek, TG_SchoolSubjectMaster.GroupSubject, TG_SchoolSubjectMaster.SubjectID,
                         TG_SchoolSubjectMaster.ID AS Expr1, TG_SchoolGradeMaster.ID AS Expr2
FROM            TG_SchoolGradeMaster INNER JOIN
                         TG_SchoolSubjectMaster INNER JOIN
                         CD_CensesNo ON TG_SchoolSubjectMaster.SchoolID = CD_CensesNo.CenCode INNER JOIN
                         CD_Subject ON TG_SchoolSubjectMaster.SubjectID = CD_Subject.SubCode ON TG_SchoolGradeMaster.ID = TG_SchoolSubjectMaster.GradeID INNER JOIN
                         TG_SchoolGrade ON TG_SchoolGradeMaster.GradeID = TG_SchoolGrade.ID
where TG_SchoolSubjectMaster.SchoolID='$loggedSchool'
						 ORDER BY TG_SchoolGrade.GradeTitle";
  $i=1;
   							$stmt = $db->runMsSqlQuery($sqlList);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					  $InstitutionName=$row['InstitutionName'];
					  $Expr1=$row['Expr1'];
					  $SubjectID=trim($row['SubjectID']);
					  $CenCode=trim($row['CenCode']);
					  $GradeID=trim($row['Expr2']);
					  $GroupSubjectz=explode(",",$row['GroupSubject']);
					  $SubjectNameGroup="";
					  for($f=0;$f<count($GroupSubjectz);$f++){
						  $sCode=$GroupSubjectz[$f];
						  if($sCode){
							  $sqlsg = "SELECT SubjectName FROM CD_Subject where SubCode='$sCode'";
                              $stmtsg = $db->runMsSqlQuery($sqlsg);
                            while ($rowsg = sqlsrv_fetch_array($stmtsg, SQLSRV_FETCH_ASSOC)) {
								$SubjectNameGroup.=$rowsg['SubjectName'].",";
						    }
						  }
					  }
					  
					  $countTotalGr="SELECT SubjectID from TG_SchoolSubjectTeacher where (SubjectID='$SubjectID') and (SchoolID='$CenCode') and (GradeID='$GradeID')";
					  $TotaRowsGr=$db->rowCount($countTotalGr);	
					  
					  $countTotalGrr="SELECT SubjectID from TG_SchoolSubjectGroup where (SubjectID='$SubjectID') and (SchoolID='$CenCode') and (GradeID='$GradeID')";
					  $TotaRowsGrr=$db->rowCount($countTotalGrr);
					  
					  $countTotalGrrr="SELECT SubjectID from TG_SchoolTimeTable where (SubjectID='$SubjectID') and (SchoolID='$CenCode') and (GradeID='$GradeID')";
					  $TotaRowsGrrr=$db->rowCount($countTotalGrrr);	
					  
					  $deletble="Y";
					  if($TotaRowsGr>0 || $TotaRowsGrr>0 || $TotaRowsGrrr>0){
						  $deletble="N";
					  }
					 
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $InstitutionName ?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php echo $row['GradeTitle']; ?></td>
                        <td bgcolor="#FFFFFF" align="left"><?php echo $row['SubjectName']; ?></td>
                        <td bgcolor="#FFFFFF" align="center"><?php echo $SubjectNameGroup; ?></td>
                        <td bgcolor="#FFFFFF" align="center"><?php echo $row['PeriodsPerWeek']; ?></td>
                        <td bgcolor="#FFFFFF" align="center"><?php if($TotaRowsGrrr>0){echo "Can't Modify";}else{?><a href="subject-2-E-<?php echo $Expr1 ?>.html">Edit</a><?php }?></td>
                        <td bgcolor="#FFFFFF" align="center"><?php if($deletble=='Y'){?><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','','<?php echo $tblNam ?>','<?php echo "$ttle-$pageid.html";?>')">Delete <?php //echo $Expr1 ?></a><?php }else{echo "Not Allow";}?> <?php //echo "$TotaRowsGr-$TotaRowsGrr-$TotaRowsGrrr";?></td>
                      </tr>
                      <?php }?>
                      
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
<script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/prettify.min.js"></script>
    <script type="text/javascript" src="js/multiselect.min.js"></script>
    

    
    <script type="text/javascript">
    $(document).ready(function() {
        // make code pretty
        window.prettyPrint && prettyPrint();
        
        if ( window.location.hash ) {
            scrollTo(window.location.hash);
        }
        
        $('.nav').on('click', 'a', function(e) {
            scrollTo($(this).attr('href'));
        });

        $('#multiselect').multiselect();

        $('#search').multiselect({
            search: {
                left: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
                right: '<input type="text" name="q" class="form-control" placeholder="Search..." />',
            }
        });

        $('.multiselect').multiselect();
        $('.js-multiselect').multiselect({
            right: '#js_multiselect_to_1',
            rightAll: '#js_right_All_1',
            rightSelected: '#js_right_Selected_1',
            leftSelected: '#js_left_Selected_1',
            leftAll: '#js_left_All_1'
        });

        $('#keepRenderingSort').multiselect({
            keepRenderingSort: true
        });

        $('#undo_redo').multiselect();

        $('#multi_d').multiselect({
            right: '#multi_d_to, #multi_d_to_2',
            rightSelected: '#multi_d_rightSelected, #multi_d_rightSelected_2',
            leftSelected: '#multi_d_leftSelected, #multi_d_leftSelected_2',
            rightAll: '#multi_d_rightAll, #multi_d_rightAll_2',
            leftAll: '#multi_d_leftAll, #multi_d_leftAll_2',

            search: {
                left: '<input type="text" name="q" class="form-control" placeholder="Search..." />'
            },

            moveToRight: function(Multiselect, $options, event, silent, skipStack) {
                var button = $(event.currentTarget).attr('id');

                if (button == 'multi_d_rightSelected') {
                    var $left_options = Multiselect.$left.find('> option:selected');
                    Multiselect.$right.eq(0).append($left_options);

                    if ( typeof Multiselect.callbacks.sort == 'function' && !silent ) {
                        Multiselect.$right.eq(0).find('> option').sort(Multiselect.callbacks.sort).appendTo(Multiselect.$right.eq(0));
                    }
                } else if (button == 'multi_d_rightAll') {
                    var $left_options = Multiselect.$left.children(':visible');
                    Multiselect.$right.eq(0).append($left_options);

                    if ( typeof Multiselect.callbacks.sort == 'function' && !silent ) {
                        Multiselect.$right.eq(0).find('> option').sort(Multiselect.callbacks.sort).appendTo(Multiselect.$right.eq(0));
                    }
                } else if (button == 'multi_d_rightSelected_2') {
                    var $left_options = Multiselect.$left.find('> option:selected');
                    Multiselect.$right.eq(1).append($left_options);

                    if ( typeof Multiselect.callbacks.sort == 'function' && !silent ) {
                        Multiselect.$right.eq(1).find('> option').sort(Multiselect.callbacks.sort).appendTo(Multiselect.$right.eq(1));
                    }
                } else if (button == 'multi_d_rightAll_2') {
                    var $left_options = Multiselect.$left.children(':visible');
                    Multiselect.$right.eq(1).append($left_options);

                    if ( typeof Multiselect.callbacks.sort == 'function' && !silent ) {
                        Multiselect.$right.eq(1).eq(1).find('> option').sort(Multiselect.callbacks.sort).appendTo(Multiselect.$right.eq(1));
                    }
                }
            },

            moveToLeft: function(Multiselect, $options, event, silent, skipStack) {
                var button = $(event.currentTarget).attr('id');

                if (button == 'multi_d_leftSelected') {
                    var $right_options = Multiselect.$right.eq(0).find('> option:selected');
                    Multiselect.$left.append($right_options);

                    if ( typeof Multiselect.callbacks.sort == 'function' && !silent ) {
                        Multiselect.$left.find('> option').sort(Multiselect.callbacks.sort).appendTo(Multiselect.$left);
                    }
                } else if (button == 'multi_d_leftAll') {
                    var $right_options = Multiselect.$right.eq(0).children(':visible');
                    Multiselect.$left.append($right_options);

                    if ( typeof Multiselect.callbacks.sort == 'function' && !silent ) {
                        Multiselect.$left.find('> option').sort(Multiselect.callbacks.sort).appendTo(Multiselect.$left);
                    }
                } else if (button == 'multi_d_leftSelected_2') {
                    var $right_options = Multiselect.$right.eq(1).find('> option:selected');
                    Multiselect.$left.append($right_options);

                    if ( typeof Multiselect.callbacks.sort == 'function' && !silent ) {
                        Multiselect.$left.find('> option').sort(Multiselect.callbacks.sort).appendTo(Multiselect.$left);
                    }
                } else if (button == 'multi_d_leftAll_2') {
                    var $right_options = Multiselect.$right.eq(1).children(':visible');
                    Multiselect.$left.append($right_options);

                    if ( typeof Multiselect.callbacks.sort == 'function' && !silent ) {
                        Multiselect.$left.find('> option').sort(Multiselect.callbacks.sort).appendTo(Multiselect.$left);
                    }
                }
            }
        });

        $("#optgroup").multiselect();
    });
    
    function scrollTo( id ) {
        if ( $(id).length ) {
            $('html,body').animate({scrollTop: $(id).offset().top - 60},'slow');
        }
    }
    </script>
    <!--
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