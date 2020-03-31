<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$msg="";
$tblNam="TG_SchoolTimetableTeachersTemp";

if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$sqlDel="DELETE FROM $tblNam WHERE SchoolID='$loggedSchool'";
	$db->runMsSqlQuery($sqlDel);
	
	$sqlSetTeach="SELECT        TeacherMast.SurnameWithInitials, TeacherMast.NIC
FROM            StaffServiceHistory INNER JOIN
                         TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
						 where (StaffServiceHistory.InstCode='$loggedSchool') AND (StaffServiceHistory.ServiceRecTypeCode != 'RT01' or StaffServiceHistory.ServiceRecTypeCode IS NULL)";
						 
	$stmt = $db->runMsSqlQuery($sqlSetTeach);
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		//$SubjectType=$row['SubjectType'];
		
		$SurnameWithInitials=$row['SurnameWithInitials'];
		
		$NIC=$row['NIC'];//echo "<br>";
		
		$subCode=array();
		$subName=array();
		
		/* $sqlSubList="SELECT        TeacherSubject.SubjectType, TeacherSubject.SubjectCode, CD_SubjectTypes.SubTypeName, CD_Subject.SubjectName
FROM            TeacherSubject INNER JOIN
                         CD_SubjectTypes ON TeacherSubject.SubjectType = CD_SubjectTypes.SubType INNER JOIN
                         CD_Subject ON TeacherSubject.SubjectCode = CD_Subject.SubCode
						 where TeacherSubject.NIC='$NIC' order by CD_Subject.SubjectName"; */
			$sqlSubList="SELECT   TeacherSubject.SubjectCode,CD_Subject.SubjectName
FROM            TeacherSubject INNER JOIN
                         CD_Subject ON TeacherSubject.SubjectCode = CD_Subject.SubCode
						 where TeacherSubject.NIC='$NIC' order by CD_Subject.SubjectName";
			$stmt2 = $db->runMsSqlQuery($sqlSubList);
			$detailSubCode=$detailSub="";
			while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
				$SubjectCode=trim($row2['SubjectCode']);
				$SubjectName=trim($row2['SubjectName']);
				
				if(!in_array($SubjectCode,$subCode)){
					$subCode[]=$SubjectCode;
					$subName[]=$SubjectName;
					$detailSubCode.=$SubjectCode.",";
				}
				
			}
			for($x=0;$x<count($subCode);$x++){
				$techSubjectCode=$subCode[$x];
				$detailSubType="";
				
				if($techSubjectCode!=''){
					$sqlSubType="SELECT  CD_SubjectTypes.SubTypeName
FROM            TeacherSubject INNER JOIN
                         CD_SubjectTypes ON TeacherSubject.SubjectType = CD_SubjectTypes.SubType
						 WHERE TeacherSubject.NIC='$NIC' and TeacherSubject.SubjectCode='$techSubjectCode'";
					$stmt3 = $db->runMsSqlQuery($sqlSubType);
					while ($row3 = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)) {
						$detailSubType.=trim($row3['SubTypeName']).",";
				
					}
					
				$techSubjectName=$subName[$x];
				$detailSubType=substr($detailSubType, 0, -1);
				$detailSub.=$techSubjectName."(".$detailSubType.") ,";	
					
				}
			}
			
		$detailSub=substr($detailSub, 0, -1);
		$detailSub=str_replace("Teaching","Tch",$detailSub);
		$detailSub=str_replace("Appointment","App",$detailSub);
		$detailSub=str_replace("Capable","Cap",$detailSub);
		$queryGradeSave="INSERT INTO $tblNam
           (SchoolID,NIC,SubjectID,SubjectName,TeachingType,TeacherName)
     VALUES
           ('$loggedSchool','$NIC','$detailSubCode','$detailSub','$SubTypeName','$SurnameWithInitials')";
		
		$db->runMsSqlQuery($queryGradeSave);
	} 
						 
						 
	/* $selQry="SELECT        TeacherSubject.SubjectType, TeacherSubject.SubjectCode, TeacherMast.SurnameWithInitials, CD_SubjectTypes.SubTypeName, 
                         CD_Subject.SubjectName, TeacherMast.NIC
FROM            TeacherSubject INNER JOIN
                         StaffServiceHistory ON TeacherSubject.NIC = StaffServiceHistory.NIC INNER JOIN
                         CD_SubjectTypes ON TeacherSubject.SubjectType = CD_SubjectTypes.SubType INNER JOIN
                         TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurResRef INNER JOIN
                         CD_Subject ON TeacherSubject.SubjectCode = CD_Subject.SubCode where StaffServiceHistory.InstCode='$loggedSchool'";
						 
	$stmt = $db->runMsSqlQuery($selQry);
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		//$SubjectType=$row['SubjectType'];
		$SubjectCode=$row['SubjectCode'];
		$SurnameWithInitials=$row['SurnameWithInitials'];
		$SubTypeName=$row['SubTypeName'];
		$SubjectName=$row['SubjectName'];
		$NIC=$row['NIC'];
		
		$queryGradeSave="INSERT INTO $tblNam
           (SchoolID,NIC,SubjectID,SubjectName,TeachingType,TeacherName)
     VALUES
           ('$loggedSchool','$NIC','$SubjectCode','$SubjectName','$SubTypeName','$SurnameWithInitials')";
		
		$db->runMsSqlQuery($queryGradeSave);
	}  */
						 
	
	
	
}
$countTotal="SELECT * FROM $tblNam where SchoolID='$loggedSchool'";
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
                      <td>School :</td>
                      <td> <select class="select2a_n" id="SchoolID" name="SchoolID">
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
                  <td><?php echo $TotaRows ?> Record(s) found.</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="6%" height="25" align="center" bgcolor="#999999">#</td>
                        <td width="29%" align="center" bgcolor="#999999">Teacher</td>
                        <td width="65%" align="center" bgcolor="#999999">Subject</td>
                      </tr>
                      <?php 
					  $sqlList="SELECT * From $tblNam where SchoolID='$loggedSchool' order by TeacherName";
					  
  $i=1;
   $stmt = $db->runMsSqlQuery($sqlList);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					 // $InstitutionName=$row['SubjectName'];
					 
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['TeacherName']; ?></td>
                        <td align="left" bgcolor="#FFFFFF"><?php echo substr($row['SubjectName'], 0, -1); ?></td>
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