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
<?php 
$msg=""; 
$finalGrade="";
$tblNam="TG_SchoolSubjectGroup";
$countTotal="SELECT * FROM $tblNam where SchoolID='$loggedSchool'";

if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$SchoolID=$_REQUEST['SchoolID'];
	$GradeID=$_REQUEST['GradeID'];
	$SubjectID=$_REQUEST['SubjectID'];
	$ClassGrouped=$_REQUEST['ClassGrouped'];
	$classesG=",";
	$availbleC="N"; //echo count($ClassGrouped);
	for($c=0;$c<count($ClassGrouped);$c++){
		$classIDValue=$ClassGrouped[$c];
		if($classIDValue!=''){
			$classesG.=$classIDValue.",";
			$availbleC="Y";
			
		}
	}
	//$exNowClass=explode(",",$classesG);
	//echo $classesG;
	$alreadyID="";
	if($SchoolID!='' and $GradeID!='' and $SubjectID!='' and $availbleC!='N'){
		
		$queryGradeSave="INSERT INTO $tblNam
			   (SchoolID,GradeID,SubjectID,ClassGrouped)
		 VALUES
			   ('$SchoolID','$GradeID','$SubjectID','$classesG')";
			   
		$countSql="SELECT * FROM $tblNam where SchoolID='$SchoolID' and GradeID='$GradeID' and SubjectID='$SubjectID'";
		$stmt = $db->runMsSqlQuery($countSql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$ClassGroupedAv=$row['ClassGrouped'];
			$exThisCla=explode(",",$ClassGroupedAv);
			for($x=0;$x<count($exThisCla);$x++){
				$clsID=$exThisCla[$x];
				if($clsID!=''){
					if (in_array($clsID, $ClassGrouped)) {
						$alreadyID=	"Y";
					}
				}
			}
			
		}
		
		$isAvailable=$db->rowAvailable($countSql);
		if($isAvailable==1 and $alreadyID=='Y'){
			$msg="Already exist.";
		}else{ 
			$msg="Successfully Updated.";
			$db->runMsSqlQuery($queryGradeSave);
		}
	}
	//sqlsrv_query($queryGradeSave);
}
$TotaRows=$db->rowCount($countTotal);
?>


<div class="main_content_inner_block">
    <form method="post" action="" name="frmSavex" id="frmSavex" enctype="multipart/form-data" onSubmit="return check_form(frmSavex);">
        <?php if($msg!=''){//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
   	  <div class="mcib_middle1">
          <div class="mcib_middle_full">
          <div class="form_error">
            
            <?php echo $msg;echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
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
                      <td>Grade <span class="form_error">*</span>:</td>
                      <td><select class="select5" id="GradeID" name="GradeID" onchange="Javascript:show_classes('classList',this.options[this.selectedIndex].value,document.frmSavex.SchoolID.value);">
              <option value="">-Select-</option>
              <?php
                            $sql = "SELECT        TG_SchoolGrade.GradeTitle, TG_SchoolGrade.NumberOfPeriods, TG_SchoolGradeMaster.ID
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolGradeMaster ON TG_SchoolGrade.ID = TG_SchoolGradeMaster.GradeID
						 WHERE TG_SchoolGradeMaster.SchoolID='$loggedSchool' Order by GradeTitle ASC";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                echo '<option value=' . $row['ID'] . '>' . $row['GradeTitle'] . '</option>';
                            }
                            ?>
            </select><input type="hidden" name="cat" value="<?php echo $cat; ?>" />
                <input type="hidden" name="AED" value="<?php echo $AED; ?>" />
				<input type="hidden" name="id" value="<?php echo $id; ?>" />
				<input type="hidden" name="tblName" value="<?php echo $tablename; ?>" />
				<input type="hidden" name="redirect_page" value="<?php echo $redirect_page ?>" />
                <input type="hidden" name="vID" value="<?php echo $id; ?>" />
                <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
                <input type="hidden" name="mainID" value="<?php echo $primaryid; ?>" /></td>
                    </tr>
                    
                    <tr>
                      <td>Subject <span class="form_error">*</span> :</td>
                      <td><select class="select2a" id="SubjectID" name="SubjectID">
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
                      </select></td>
                    </tr>
                    <tr>
                      <td colspan="2"><div id="txt_periodCount">
                        <!--<table width="100%" cellspacing="1" cellpadding="1">
                          <tr>
                            <td width="31%">Already Inserted Periods :</td>
                            <td width="69%">&nbsp;</td>
                          </tr>
                        </table>-->
                      </div></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td align="right"><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                    </tr>
                    </table>
        </td>
        <td width="46%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
       
                <tr>
                  <td width="27%" align="left" valign="top">Class Group :</td>
                  <td width="73%"><div id="txt_classes">Select a Grade<!--<table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="8%"><input type="checkbox" name="ClassGrouped[]" id="ClassGrouped[]" value=""/></td>
                      <td width="92%">A</td>
                    </tr>
                  </table>--></div>
                    </td>
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
		 $sqlGradeTitle="SELECT GradeTitle as Title from TG_SchoolGrade where ID='$GradeIDG'";
		 
		 $stmtT = $db->runMsSqlQuery($sqlGradeTitle);
		 while ($rowT = sqlsrv_fetch_array($stmtT, SQLSRV_FETCH_ASSOC)) {
			 $Title=$rowT['Title'];
		 }
	?><div style="width:75px; float:left; background-color:#D2CBFE; margin:2px;">Grade <?php echo "$Title ($TotalG)"; ?></div><?php }?></td>
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#999999"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="2%" height="25" align="center" bgcolor="#CCCCCC">#</td>
                        <td width="17%" align="center" bgcolor="#CCCCCC">School</td>
                        <td width="19%" align="center" bgcolor="#CCCCCC">Subject</td>
                        <td width="6%" align="center" bgcolor="#CCCCCC">Grade</td>
                        <td width="21%" align="center" bgcolor="#CCCCCC">Group Classes</td>
                        <td width="7%" align="center" bgcolor="#CCCCCC">Edit</td>
                        <td width="7%" align="center" bgcolor="#CCCCCC">Delete</td>
                      </tr>
                      <?php 
					  
					  $sqlList="SELECT     TG_SchoolSubjectGroup.ID, TG_SchoolSubjectGroup.ClassGrouped, CD_CensesNo.InstitutionName, CD_Subject.SubjectName, TG_SchoolGrade.GradeTitle, 
                      CD_CensesNo.CenCode
FROM         TG_SchoolSubjectGroup INNER JOIN
                      CD_CensesNo ON TG_SchoolSubjectGroup.SchoolID = CD_CensesNo.CenCode INNER JOIN
                      CD_Subject ON TG_SchoolSubjectGroup.SubjectID = CD_Subject.SubCode INNER JOIN
                      TG_SchoolGradeMaster ON TG_SchoolSubjectGroup.GradeID = TG_SchoolGradeMaster.ID INNER JOIN
                      TG_SchoolGrade ON TG_SchoolGradeMaster.GradeID = TG_SchoolGrade.ID
WHERE        (TG_SchoolSubjectGroup.SchoolID = '$loggedSchool')";
  					  
					  $i=1;
   					  $stmt = $db->runMsSqlQuery($sqlList);
					  
                      while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						  $deletble="Y";
						  $InstitutionName=$row['InstitutionName'];
						  $SubjectName=$row['SubjectName'];
						  $GradeTitle=$row['GradeTitle'];
						  $Expr1=$row['ID'];
						  $CenCode=trim($row['CenCode']);
						   
						  $ClassGroupedz=explode(",",$row['ClassGrouped']);
						  $SubjectNameGroup="";
						  
						  for($f=0;$f<count($ClassGroupedz);$f++){
							  $sCode=$ClassGroupedz[$f];
							  if($sCode){
								  $countTotalGrrr="SELECT ClassID from TG_SchoolTimeTable where (SchoolID='$CenCode') and (ClassID='$sCode')";
					  			  $TotaRowsGrrr=$db->rowCount($countTotalGrrr);	
								  if($TotaRowsGrrr>0){
									  $deletble="N";
								  }
								  
								  $sqlsg = "SELECT ClassID FROM TG_SchoolClassStructure where ID='$sCode'";
								  $stmtsg = $db->runMsSqlQuery($sqlsg);
								while ($rowsg = sqlsrv_fetch_array($stmtsg, SQLSRV_FETCH_ASSOC)) {
									$SubjectNameGroup.=$rowsg['ClassID'].",";
								}
							  }
						  }
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $InstitutionName ?></td>
                        <td bgcolor="#FFFFFF" align="left"><?php echo $row['SubjectName']; ?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php echo $row['GradeTitle']; ?></td>
                        <td bgcolor="#FFFFFF" align="center"><?php echo $SubjectNameGroup; ?></td>
                        <td bgcolor="#FFFFFF" align="center"><?php if($deletble=='N'){echo "Can't Modify";}else{?><a href="<?php echo $ttle ?>-<?php echo $pageid ?>-E-<?php echo $Expr1 ?>.html">Edit</a><?php }?></td>
                        <td bgcolor="#FFFFFF" align="center"><?php if($deletble=='Y'){?><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','','<?php echo $tblNam ?>','<?php echo "$ttle-$pageid.html";?>')">Delete<?php //echo $Expr1 ?></a><?php }else{echo "Not Allow";}?></td>
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