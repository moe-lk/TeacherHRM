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
  check_input("LearningPointID", 1, "Learning Point ID.");
  check_input("LearningPointName", 1, "Learning Point Name.");
  check_input("PhysicalRef", 1, "Physical Referance .");
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
$tblNam="TG_SchoolLearningPoints";
$countTotal="SELECT * FROM $tblNam where SchoolID='$loggedSchool'";

if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	if($menu==''){
		$SchoolID=$_REQUEST['SchoolID'];
		$LearningPointID=$_REQUEST['LearningPointID'];
		$LearningPointName=$_REQUEST['LearningPointName'];
		$PhysicalRef=$_REQUEST['PhysicalRef'];
		$TeacherInChargeID=$_REQUEST['TeacherInChargeID'];
		$LocationType=$_REQUEST['LocationType'];
		
		if($SchoolID!='' and $LearningPointID!='' and $LearningPointName!='' and $PhysicalRef!='' and $TeacherInChargeID!=''){
			
		$queryGradeSave="INSERT INTO $tblNam
			   (SchoolID,LearningPointID,LearningPointName,PhysicalRef,TeacherInChargeID,LocationType)
		 VALUES
			   ('$SchoolID','$LearningPointID','$LearningPointName','$PhysicalRef','$TeacherInChargeID','$LocationType')";
			   
		$countSql="SELECT * FROM $tblNam where SchoolID='$SchoolID' and LearningPointID='$LearningPointID'";
		$isAvailable=$db->rowAvailable($countSql);
		if($isAvailable==1){
			$msg="Already exist.";
		}else{ 
			$msg="Successfully Updated.";
			$db->runMsSqlQuery($queryGradeSave);
		}
		}
		
	}else{
		$LearningPointIDx=$_REQUEST['LearningPointID'];
		$LearningPointNamex=$_REQUEST['LearningPointName'];
		$PhysicalRefx=$_REQUEST['PhysicalRef'];
		$TeacherInChargeIDx=$_REQUEST['TeacherInChargeID'];
		$LocationTypex=$_REQUEST['LocationType'];
		$vID=$_REQUEST['vID'];
				
		$queryMainUpdate = "UPDATE $tblNam SET LearningPointID='$LearningPointIDx',LearningPointName='$LearningPointNamex',PhysicalRef='$PhysicalRefx',TeacherInChargeID='$TeacherInChargeIDx',LocationType='$LocationTypex' WHERE ID='$vID'";//NIC='$NICUser' and AddrType='PER'";
		$db->runMsSqlQuery($queryMainUpdate);
		$newHisID=$fmRec;
		$msg = "Form submitted successfully.";
		$menu="";
		//redirect("subject-2.html");
		header("Location:learningPoints-3.html");
     	exit() ;
		
	}
}
$TotaRows=$db->rowCount($countTotal);

if($menu=='E'){
	//echo "hiiiiiiiii";
	$editRecords="SELECT * FROM $tblNam where ID='$id'";
	$stmt = $db->runMsSqlQuery($editRecords);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
	$LearningPointID=$row['LearningPointID'];
	$LearningPointName=trim($row['LearningPointName']);
	$PhysicalRef=$row['PhysicalRef'];
	$TeacherInChargeID=trim($row['TeacherInChargeID']);
	$LocationType=trim($row['LocationType']);
} 
?>


<div class="main_content_inner_block">
    <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if($msg!=''){//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
   	  <div class="mcib_middle1">
          <div class="mcib_middle_full">
          <div class="form_error"><?php echo $msg;echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
        </div>
        <?php }?>
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
                      <td>Learning Location ID <span class="form_error">*</span>:</td>
                      <td><input type="hidden" name="cat" value="<?php echo $cat; ?>" />
                        <input type="hidden" name="AED" value="<?php echo $AED; ?>" />
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="hidden" name="tblName" value="<?php echo $tablename; ?>" />
                        <input type="hidden" name="redirect_page" value="<?php echo $redirect_page ?>" />
                        <input type="hidden" name="vID" value="<?php echo $id; ?>" />
                        <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
                        <input type="hidden" name="mainID" value="<?php echo $primaryid; ?>" />
                        <input name="LearningPointID" type="text" class="input2" id="LearningPointID" value="<?php echo $LearningPointID ?>"/></td>
                    </tr>
                    <tr>
                      <td>Learning Location Name <span class="form_error">*</span>:</td>
                      <td><input name="LearningPointName" type="text" class="input2" id="LearningPointName" value="<?php echo $LearningPointName ?>"/></td>
                    </tr>
                    <tr>
                      <td>Physical Referance <span class="form_error">*</span>:</td>
                      <td><input name="PhysicalRef" type="text" class="input2" id="PhysicalRef" value="<?php echo $PhysicalRef ?>"/></td>
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
								$NIC=trim($row['NIC']);
								$SurnameWithInitials=$row['SurnameWithInitials'];
								$seltebr="";
								if($NIC==$TeacherInChargeID){
								   $seltebr="selected=\"selected\"";
								   //$disble="disabled=\"disabled\"";
								}
							    echo "<option value=\"$NIC\" $seltebr>$SurnameWithInitials</option>";
                                //echo '<option value=' . $row['NIC'] . '>' . $row['SurnameWithInitials'] . '</option>';
                            }
                            ?>
                      </select></td>
                    </tr>
                    <tr>
                      <td>Location Type : <?php if($menu=='')$LocationType="U";?></td>
                      <td><table width="100%" cellspacing="1" cellpadding="1">
                        <tr>
                          <td width="7%"><input name="LocationType" type="radio" id="radio" value="U" <?php if($LocationType=='U'){?>checked="checked"<?php }?> /></td>
                          <td width="26%">Unique</td>
                          <td width="7%"><input type="radio" name="LocationType" id="radio2" value="C" <?php if($LocationType=='C'){?>checked="checked"<?php }?>/></td>
                          <td width="60%">Common</td>
                        </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>
                        <table width="100%" cellspacing="1" cellpadding="2">
                          <tr>
                            <td width="34%"><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                            <td width="66%"><?php if($menu!=''){?><a href="<?php echo $ttle ?>-<?php echo $pageid ?>.html"><u>View List</u></a><?php }?></td>
                          </tr>
                      </table></td>
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
                        <td width="2%" height="25" align="center" bgcolor="#999999">#</td>
                        <td width="19%" align="center" bgcolor="#999999">School</td>
                        <td width="10%" align="center" bgcolor="#999999">Learning Point ID</td>
                        <td width="18%" align="center" bgcolor="#999999">Learning Point Name</td>
                        <td width="12%" align="center" bgcolor="#999999">Physical Referance.</td>
                        <td width="16%" align="center" bgcolor="#999999">Teacher in Charge</td>
                        <td width="5%" align="center" bgcolor="#999999">Type</td>
                        <td width="9%" align="center" bgcolor="#999999">Edit</td>
                        <td width="9%" align="center" bgcolor="#999999">Delete</td>
                      </tr>
                      <?php 								 
						
					$sqlList="SELECT        TeacherMast.NIC, TeacherMast.SurnameWithInitials, CD_CensesNo.CenCode, CD_CensesNo.InstitutionName, TG_SchoolLearningPoints.PhysicalRef, 
                         TG_SchoolLearningPoints.LearningPointName, TG_SchoolLearningPoints.LearningPointID, TG_SchoolLearningPoints.ID,TG_SchoolLearningPoints.LocationType
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
					  $LocationType=trim($row['LocationType']);
					  $LocationTypeN="Unique";
					  if($LocationType=='C')$LocationTypeN="Common";
					  
					  $countTotalGr="SELECT LearningPointID from TG_SchoolClassStructure where LearningPointID='$Expr1'";
					  $TotaRowsGr=$db->rowCount($countTotalGr);	
					  
					  $deletble="Y";
					  if($TotaRowsGr>0){//|| $TotaRowsGrr>0 || $TotaRowsGrrr>0
						  $deletble="N";
					  }
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $InstitutionName ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['LearningPointID']; ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['LearningPointName']; ?></td>
                        <td bgcolor="#FFFFFF" align="left"><?php echo $row['PhysicalRef']; ?></td>
                        <td bgcolor="#FFFFFF" align="left"><?php echo $row['SurnameWithInitials']; ?></td>
                        <td bgcolor="#FFFFFF" align="left"><?php echo $LocationTypeN ?></td>
                        <td bgcolor="#FFFFFF" align="center"><a href="learningPoints-3-E-<?php echo $Expr1 ?>.html">Edit</a></td>
                        <td bgcolor="#FFFFFF" align="center"><?php if($deletble=='Y'){?><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','','<?php echo $tblNam ?>','<?php echo "$ttle-$pageid.html";?>')">Delete <?php //echo $Expr1 ?></a><?php }else{echo "Not Allow";}?></td>
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