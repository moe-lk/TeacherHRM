<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
include('js/common.js.php'); 
include('js/ajaxloadpage.js.php'); 
include('myfunction.php');
?>
<?php
/* $array = array( 'a', 'a', 'a', 'a', 'b', 'b', 'c' );
print_r($array);
$count = count(array_keys($array, 'b', true));
echo "Found $count letter a's.";
echo "<br><br>";
$value="SB1101,SB1205,SB1205,SB1205,SB1501,SB1601,SB1401,SB1101,SB1101,SB1401,SB1101,SB1501,SB1501,SB1601,SB1401,SB1501,SB1401,SB1501,SB1401,SB1205,SB1601,SB1101,SB1205,SB1205,SB1601,SB1401,SB1401,SB1101,SB1501,SB1212,SB1401,SB1101,SB1601,SB1501,SB1501,SB1212,SB1212,SB1601,SB1205,SB1101";
$arrddd=explode(',',$value);
print_r($arrddd);
echo $count = count(array_keys($arrddd, 'SB1101', true));
echo "<br><br>"; */

        ?>
<?php 

$msg="";
$tblNam="TG_SchoolGenerateTT_Temp";
if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$SchoolID=$_REQUEST['SchoolID'];
	$ClassID=$_REQUEST['ClassID'];
	$GradeID=$_REQUEST['GradeID'];
        /* $TeacherSubject=$_REQUEST['TeacherSubject'];
        if($TeacherSubject=='T')$TeacherSubjectSP='TCH';
        if($TeacherSubject=='C')$TeacherSubjectSP='CAP';
        if($TeacherSubject=='A')$TeacherSubjectSP='APP'; */
        $sqlNoOfPeriod="SELECT        TG_SchoolGrade.GradeTitle, TG_SchoolGrade.NumberOfPeriods, TG_SchoolGradeMaster.ID
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolGradeMaster ON TG_SchoolGrade.ID = TG_SchoolGradeMaster.GradeID
						 WHERE TG_SchoolGradeMaster.ID='$GradeID'";
        $stmt = $db->runMsSqlQuery($sqlNoOfPeriod);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $NumberOfPeriods=$row['NumberOfPeriods'];            
        }
       
$totalRows=5*$NumberOfPeriods;	

//$sqlSubject="SELECT SubjectID,PeriodsPerWeek from TG_SchoolSubjectMaster where SchoolID='$SchoolID' and GradeID='$GradeID'";
/*$sqlSubject="SELECT        TG_SchoolSubjectMaster.SubjectID, CD_Subject.SubjectName, TG_SchoolSubjectMaster.PeriodsPerWeek
FROM            TG_SchoolSubjectMaster INNER JOIN
                         CD_Subject ON TG_SchoolSubjectMaster.SubjectID = CD_Subject.SubCode
where TG_SchoolSubjectMaster.SchoolID='$SchoolID' and TG_SchoolSubjectMaster.GradeID='$GradeID'";

$stmtSub = $db->runMsSqlQuery($sqlSubject);
$subArry=array("default");
while ($row = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
   $SubjectID=$row['SubjectID'];  
   $PeriodsPerWeek=$row['PeriodsPerWeek'];
   for($t=0;$t<$PeriodsPerWeek;$t++){
       $subArry[]=$SubjectID;
    }
}*/

//print_r($subArry);

function custom_shuffle($my_array = array()) {
  $copy = array();
  $newArry=array("default");
  while (count($my_array)) {
      unset($my_array[0]);
    // takes a rand array elements by its key
    $element = array_rand($my_array);//echo "<br>";
    if($element!=0){
        $currentKey=count($newArry);
        $previous2K=$currentKey-1;
        if($newArry[$currentKey]==$my_array[$element] and $newArry[$previous2K]==$my_array[$element]){
            
        }else{
        $newArry[]=$my_array[$element];
        unset($my_array[$element]);
        }
    }
    // assign the array and its value to an another array
    //$copy[$element] = $my_array[$element];
    //delete the element from source array
    
  }
  return $newArry;
}

//$newArraySuffled=custom_shuffle($subArry);        
        
}
if(isset($_POST["SaveTT"])){
	$SchoolID=$_REQUEST['SchoolID'];
	$ClassID=$_REQUEST['ClassID'];
	$GradeID=$_REQUEST['GradeID'];
        	
        $sqlNoOfPeriod="SELECT        TG_SchoolGrade.GradeTitle, TG_SchoolGrade.NumberOfPeriods, TG_SchoolGradeMaster.ID
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolGradeMaster ON TG_SchoolGrade.ID = TG_SchoolGradeMaster.GradeID
						 WHERE TG_SchoolGradeMaster.ID='$GradeID'";
        $stmt = $db->runMsSqlQuery($sqlNoOfPeriod);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $NumberOfPeriods=$row['NumberOfPeriods'];            
        }
        
	$totalRows=5*$NumberOfPeriods;
	
	for($i=1;$i<$totalRows+1;$i++){
		$fieldTag="TT".$i;
		//echo $_REQUEST['TT1'];
		$ttValue=$_REQUEST[$fieldTag];
		
		$sqlInsertTT="INSERT INTO TG_SchoolTimeTable
           (SchoolID,GradeID,ClassID,SubjectID,FieldID,TeacherID)
     VALUES
           ('$SchoolID','$GradeID','$ClassID','$ttValue','$fieldTag','')";
		$db->runMsSqlQuery($sqlInsertTT);
	}	
	
}

$params1 = array(
	array($GradeID, SQLSRV_PARAM_IN),
	array($SchoolID, SQLSRV_PARAM_IN)
);

$params2 = array(
	array($SchoolID, SQLSRV_PARAM_IN)
);

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
                      <td>Grade :</td>
                      <td><select class="select2a_new" id="GradeID" name="GradeID" onchange="filterClass();">
                            <option value="">-Select-</option>
                            <?php
                            $sql = "SELECT        TG_SchoolGrade.GradeTitle, TG_SchoolGrade.NumberOfPeriods, TG_SchoolGradeMaster.ID
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolGradeMaster ON TG_SchoolGrade.ID = TG_SchoolGradeMaster.GradeID
						 WHERE TG_SchoolGradeMaster.SchoolID='$loggedSchool'";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $selectd="";
                                $GradeIDThis=$row['ID'];
                                $GradeTitle=$row['GradeTitle'];
                                if($GradeID==$GradeIDThis)$selectd="selected";
                                echo "<option value=\"$GradeIDThis\" $selectd>$GradeTitle</option>";
                            }
                            ?>
                        </select>
                        <input type="hidden" name="cat" value="<?php echo $cat; ?>" />
                        <input type="hidden" name="AED" value="<?php echo $AED; ?>" />
                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <input type="hidden" name="tblName" value="<?php echo $tablename; ?>" />
                        <input type="hidden" name="redirect_page" value="<?php echo $redirect_page ?>" />
                        <input type="hidden" name="vID" value="<?php echo $id; ?>" />
                        <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
                        <input type="hidden" name="mainID" value="<?php echo $primaryid; ?>" /></td>
                    </tr>
                     <tr>
                      <td>Class :</td>
                      <td><?php //print_r($params1);//echo $ClassID;//
					 // if($GradeID!=''){
					  ?>
                      <select id="ClassID" name="ClassID" class="select2a_new">
                        
                        <?php echo $sql = "{call SP_TG_GetClassOfGrade( ?, ?)}";
    $dataSchool = "<option value=\"\">-Select-</option>";
    $stmt = $db->runMsSqlQuery($sql, $params1);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$ClassIDOp=$row['ClassID'];
		$IDop=$row['ID'];
		
		$selTExt="";
		if($IDop==$ClassID)$selTExt="selected";
        echo $dataSchool= "<option value=\"$IDop\" $selTExt>$ClassIDOp</option>";
    }?>
                        </select><?php //}else{echo "Select Grade";}?>
                      </td>
                    </tr>
                    <?php if($fm=='DAD'){?>
                    <tr>
                      <td>Teacher Subject Type :</td>
                       <td><table width="100%" cellspacing="1" cellpadding="1">
                         <tr>
                             <td width="8%"><input type="radio" name="TeacherSubject" id="TeacherSubject" value="A" <?php if($TeacherSubject=='A'){?>checked=""<?php }?>/></td>
                           <td width="20%">Appointment </td>
                           <td width="8%"><input type="radio" name="TeacherSubject" id="TeacherSubject" value="T" <?php if($TeacherSubject=='T'){?>checked=""<?php }?>/></td>
                           <td width="20%">Teaching</td>
                           <td width="8%"><input type="radio" name="TeacherSubject" id="TeacherSubject" value="C" <?php if($TeacherSubject=='C'){?>checked=""<?php }?>/></td>
                           <td width="36%">Capable</td>
                         </tr>
                       </table></td>
                    </tr>
                   <?php }?>
                    <tr>
                      <td>&nbsp;</td>
                      <td><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/submit.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" />
                     <!-- <input name="SaveTT" type="submit" id="SaveTT" style="background-image: url(../cms/images/complete.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" />--></td>
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
                    <td colspan="2"><table width="100%" cellspacing="2" cellpadding="2">
                <tr>
                  <td width="44%" align="left" valign="top">&nbsp;</td>
                  <td width="4%" style="background-color:#33FFCC;border:1px solid #CCC; ">&nbsp;</td>
                  <td width="14%">Teacher Available</td>
                  <td width="4%" style="background-color:#F8C7D0;border:1px solid #CCC; ">&nbsp;</td>
                  <td width="34%">Teacher Occupied with Another Class</td>
                </tr>
                
          </table></td>
                  
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#999999"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="15%" height="30" bgcolor="#CCCCCC">&nbsp;</td>
                        <td width="17%" align="center" bgcolor="#CCCCCC"><strong>Monday</strong></td>
                        <td width="17%" align="center" bgcolor="#CCCCCC"><strong>Tuesday</strong></td>
                        <td width="17%" align="center" bgcolor="#CCCCCC"><strong>Wednesday</strong></td>
                        <td width="17%" align="center" bgcolor="#CCCCCC"><strong>Thursday</strong></td>
                        <td width="17%" align="center" bgcolor="#CCCCCC"><strong>Friday</strong>
                            <input type="hidden" id="test" name="test" value="">
                            <input type="hidden" id="test2" name="test" value="">
                            <input type="hidden" id="subArr" name="subArr" value="<?php echo $newArraySuffled?>"></td>
                      </tr>
                      <?php 
                    	
                      $subjectFirst=array();
                      for($i=1;$i<$NumberOfPeriods+1;$i++){
						  $PeriodNumberM=$i%$NumberOfPeriods;
						  if($PeriodNumberM==0)$PeriodNumberM=$NumberOfPeriods;
						  ?>
                      <tr>
                        <td bgcolor="#CCCCCC"><strong>&nbsp;&nbsp;Period <?php echo $i ?></strong></td>
                        <td height="30" align="center" valign="top" bgcolor="#FFFFFF"><span style="color:#DC8F87;">
                    <?php 
					$FieldID="TT".$i; 
					$subjectCode=getSubjectCodeOnly($SchoolID,$GradeID,$ClassID,$FieldID);
					$subjectCodeLoad="";
					if(!in_array($subjectCode,$subjectFirst)){
						$subjectFirst[]=$subjectCode;
						$subjectCodeLoad=$subjectCode;
					}
					
					if($subjectCodeLoad!='')echo "<img src=\"../cms/images/star.png\" width=\"10\" height=\"10\" alt=\"timetable\" /> ";
                    echo $subjectName=getSubjectNameOnly($SchoolID,$GradeID,$ClassID,$FieldID);
					
					$getTTID=getTTID($SchoolID,$GradeID,$ClassID,$FieldID); 
					$alreadyInTT=getAlreadyInTeacher($SchoolID,$GradeID,$ClassID,$FieldID,$getTTID);//,$PeriodNumberM,"Monday"
					$exNIC=explode("-",$alreadyInTT);
					$dropClass="select2a_new";
					if(count($exNIC)=='2'){
						$dropClass="select2a_red";
						$alreadyInTT=$exNIC[0];
					}//echo print_r($exNIC);
						?></span><br />
                    <input type="hidden" name="F<?php echo $FieldID ?>" value="<?php echo $subjectCode ?>" />
                    <select name="<?php echo $FieldID ?>" class="<?php echo $dropClass ?>" id="<?php echo $FieldID ?>" onchange="checkTeacherAvailability('<?php echo $SchoolID ?>','<?php echo $GradeID ?>','<?php echo $ClassID ?>','<?php echo $FieldID ?>','<?php echo $PeriodNumberM ?>','Monday','<?php echo $subjectCodeLoad ?>')">
                                <option value="">-Select-</option>
                                <?php
                                $valueField=$i;								
							    $sql22 = "{call SP_TG_LoadSencesTeachers( ?)}";
                                $stmt22 = $db->runMsSqlQuery($sql22, $params2);                          
                                while ($row2 = sqlsrv_fetch_array($stmt22, SQLSRV_FETCH_ASSOC)) {
                                   $NIC=$row2['NIC'];
                                   $TeacherName=$row2['TeacherName'];
                                   $SubjectName=$row2['SubjectName'];
                                   $TeachingType=$row2['TeachingType'];
                                   $selectedOk="";
                                  if($NIC==$alreadyInTT)$selectedOk="selected";
                                   echo "<option value=\"$NIC\" $selectedOk>$TeacherName ($SubjectName)  </option>";
                                }
                                ?>
                        </select>
                        <?php  
							//2014 Sept 12 modification DAD
							$groupCheck=getGroupSubjects($SchoolID,$GradeID,$subjectCode);
							if($groupCheck!=''){
								$groupCode=explode(",",$groupCheck);
								for($p=0;$p<count($groupCode);$p++){
									$codeGroup=$groupCode[$p];
									if($codeGroup!=''){
										$groupFid=$FieldID."_".$p;
										
										$subjectCodeLoad="";
										if(!in_array($codeGroup,$subjectFirst)){
											$subjectFirst[]=$codeGroup;
											$subjectCodeLoad=$codeGroup;
										}
										
										echo "<br>";
										if($subjectCodeLoad!='')echo "<img src=\"../cms/images/star.png\" width=\"10\" height=\"10\" alt=\"timetable\" /> ";
										echo $groupCubName=getSubjectNameCommon($codeGroup);
										
										$getTTIDGr=getTTID($SchoolID,$GradeID,$ClassID,$groupFid); 
										$alreadyInTTGr=getAlreadyInTeacher($SchoolID,$GradeID,$ClassID,$groupFid,$getTTIDGr);
										$exNIC=explode("-",$alreadyInTTGr);
										$dropClass="select2a_new";
										if(count($exNIC)=='2'){
											$dropClass="select2a_red";
											$alreadyInTTGr=$exNIC[0];
										}
								?>
                            <br />
                             <select name="<?php echo $groupFid; ?>" class="<?php echo $dropClass ?>" id="<?php echo $groupFid; ?>" onchange="checkTeacherAvailability('<?php echo $SchoolID ?>','<?php echo $GradeID ?>','<?php echo $ClassID ?>','<?php echo $groupFid ?>','<?php echo $PeriodNumberM ?>','Monday','<?php echo $subjectCodeLoad ?>')">
                         <option value="">-Select-</option>
                                <?php
                               // $valueField=$i+1*$NumberOfPeriods;
                              
								$sql22 = "{call SP_TG_LoadSencesTeachers( ?)}";
                                $stmt22 = $db->runMsSqlQuery($sql22, $params2);                          
                                while ($row2 = sqlsrv_fetch_array($stmt22, SQLSRV_FETCH_ASSOC)) {
                                   $NIC=$row2['NIC'];
                                   $TeacherName=$row2['TeacherName'];
                                   $SubjectName=$row2['SubjectName'];
                                   $TeachingType=$row2['TeachingType'];
                                   $selectedOk="";
                                  if($NIC==$alreadyInTTGr)$selectedOk="selected";
                                   echo "<option value=\"$NIC\" $selectedOk>$TeacherName ($SubjectName)- $alreadyInTTGR </option>";}
                                ?>
                            </select>
                            <?php }}}?>
                        </td>
                        <td align="center" valign="top" bgcolor="#FFFFFF"><span style="color:#DC8F87;">
						<?php 
						$dd=$i+$NumberOfPeriods;
						$FieldID="TT".$dd;
						$subjectCode=getSubjectCodeOnly($SchoolID,$GradeID,$ClassID,$FieldID);
						$subjectCodeLoad="";
						if(!in_array($subjectCode,$subjectFirst)){
							$subjectFirst[]=$subjectCode;
							$subjectCodeLoad=$subjectCode;
						}
						
					$getTTID=getTTID($SchoolID,$GradeID,$ClassID,$FieldID); 
					if($subjectCodeLoad!='')echo "<img src=\"../cms/images/star.png\" width=\"10\" height=\"10\" alt=\"timetable\" /> ";
                    echo $subjectName=getSubjectNameOnly($SchoolID,$GradeID,$ClassID,$FieldID);
					$alreadyInTT=getAlreadyInTeacher($SchoolID,$GradeID,$ClassID,$FieldID,$getTTID);
					
					$exNIC=explode("-",$alreadyInTT);
					$dropClass="select2a_new";
					if(count($exNIC)=='2'){
						$dropClass="select2a_red";
						$alreadyInTT=$exNIC[0];
					}?></span><br /> 
                         <input type="hidden" name="F<?php echo $FieldID ?>" value="<?php echo $subjectCode ?>" />
                        <select name="<?php echo $FieldID; ?>" class="<?php echo $dropClass ?>" id="<?php echo $FieldID; ?>" onchange="checkTeacherAvailability('<?php echo $SchoolID ?>','<?php echo $GradeID ?>','<?php echo $ClassID ?>','<?php echo $FieldID ?>','<?php echo $PeriodNumberM ?>','Tuesday','<?php echo $subjectCodeLoad ?>')">
                         <option value="">-Select-</option>
                                <?php
                                $valueField=$i+1*$NumberOfPeriods;
                                /* $stmtSub = $db->runMsSqlQuery($sqlSubject);
                                $thisSubCode=$newArraySuffled[$valueField];
                                while ($row = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
                                   $SubjectID=$row['SubjectID'];
                                   $SubjectName=$row['SubjectName'];
                                   $selectedOk="";
                                   if($SubjectID==$thisSubCode)$selectedOk="selected";
                                   echo "<option value=\"$SubjectID\" $selectedOk>$SubjectName - $SubjectID</option>";
                                } */
								$sql22 = "{call SP_TG_LoadSencesTeachers( ?)}";
                                $stmt22 = $db->runMsSqlQuery($sql22, $params2);                          
                                while ($row2 = sqlsrv_fetch_array($stmt22, SQLSRV_FETCH_ASSOC)) {
                                   $NIC=$row2['NIC'];
                                   $TeacherName=$row2['TeacherName'];
                                   $SubjectName=$row2['SubjectName'];
                                   $TeachingType=$row2['TeachingType'];
                                   $selectedOk="";
                                  if($NIC==$alreadyInTT)$selectedOk="selected";
                                   echo "<option value=\"$NIC\" $selectedOk>$TeacherName ($SubjectName) </option>";}
                                ?>
                            </select>
                            <?php 
							//2014 Sept 12 modification DAD
							$groupCheck=getGroupSubjects($SchoolID,$GradeID,$subjectCode);
							if($groupCheck!=''){
								$groupCode=explode(",",$groupCheck);
								for($p=0;$p<count($groupCode);$p++){
									$codeGroup=$groupCode[$p];
									if($codeGroup!=''){
										$groupFid=$FieldID."_".$p;
										$subjectCodeLoad="";
										if(!in_array($codeGroup,$subjectFirst)){
											$subjectFirst[]=$codeGroup;
											$subjectCodeLoad=$codeGroup;
										}
										
										echo "<br>";
										if($subjectCodeLoad!='')echo "<img src=\"../cms/images/star.png\" width=\"10\" height=\"10\" alt=\"timetable\" /> ";
										echo $groupCubName=getSubjectNameCommon($codeGroup);
										$getTTIDGr=getTTID($SchoolID,$GradeID,$ClassID,$groupFid); 
										$alreadyInTTGr=getAlreadyInTeacher($SchoolID,$GradeID,$ClassID,$groupFid,$getTTIDGr);
										$exNIC=explode("-",$alreadyInTTGr);
										$dropClass="select2a_new";
										if(count($exNIC)=='2'){
											$dropClass="select2a_red";
											$alreadyInTTGr=$exNIC[0];
										}
								?>
                            <br />
                             <select name="<?php echo $groupFid; ?>" class="<?php echo $dropClass ?>" id="<?php echo $groupFid; ?>" onchange="checkTeacherAvailability('<?php echo $SchoolID ?>','<?php echo $GradeID ?>','<?php echo $ClassID ?>','<?php echo $groupFid ?>','<?php echo $PeriodNumberM ?>','Tuesday','<?php echo $subjectCodeLoad ?>')">
                         <option value="">-Select-</option>
                                <?php
                                $valueField=$i+1*$NumberOfPeriods;
                              
								$sql22 = "{call SP_TG_LoadSencesTeachers( ?)}";
                                $stmt22 = $db->runMsSqlQuery($sql22, $params2);                          
                                while ($row2 = sqlsrv_fetch_array($stmt22, SQLSRV_FETCH_ASSOC)) {
                                   $NIC=$row2['NIC'];
                                   $TeacherName=$row2['TeacherName'];
                                   $SubjectName=$row2['SubjectName'];
                                   $TeachingType=$row2['TeachingType'];
                                   $selectedOk="";
                                  if($NIC==$alreadyInTTGr)$selectedOk="selected";
                                   echo "<option value=\"$NIC\" $selectedOk>$TeacherName ($SubjectName) </option>";}
                                ?>
                            </select>
                            <?php }}}?>
                            </td>
                        <td align="center" valign="top" bgcolor="#FFFFFF"><span style="color:#DC8F87;"><?php $dd=$i+$NumberOfPeriods*2;$FieldID="TT".$dd;
						
						$subjectCode=getSubjectCodeOnly($SchoolID,$GradeID,$ClassID,$FieldID);
						$getTTID=getTTID($SchoolID,$GradeID,$ClassID,$FieldID); 
						
						$subjectCodeLoad="";
						if(!in_array($subjectCode,$subjectFirst)){
							$subjectFirst[]=$subjectCode;
							$subjectCodeLoad=$subjectCode;
						}
					
					if($subjectCodeLoad!='')echo "<img src=\"../cms/images/star.png\" width=\"10\" height=\"10\" alt=\"timetable\" /> ";
                    echo $subjectName=getSubjectNameOnly($SchoolID,$GradeID,$ClassID,$FieldID);
					$alreadyInTT=getAlreadyInTeacher($SchoolID,$GradeID,$ClassID,$FieldID,$getTTID);
					$exNIC=explode("-",$alreadyInTT);
					$dropClass="select2a_new";
					if(count($exNIC)=='2'){
						$dropClass="select2a_red";
						$alreadyInTT=$exNIC[0];
					} ?></span><br />
                         <input type="hidden" name="F<?php echo $FieldID ?>" value="<?php echo $subjectCode ?>" />
                        <select name="TT<?php echo $i+2*$NumberOfPeriods; ?>" class="<?php echo $dropClass ?>" id="TT<?php echo $i+2*$NumberOfPeriods; ?>" onchange="checkTeacherAvailability('<?php echo $SchoolID ?>','<?php echo $GradeID ?>','<?php echo $ClassID ?>','<?php echo $FieldID ?>','<?php echo $PeriodNumberM ?>','Wednesday','<?php echo $subjectCodeLoad ?>')">
                         <option value="">-Select-</option>
                                <?php
                                $valueField=$i+2*$NumberOfPeriods;
                               /*  $stmtSub = $db->runMsSqlQuery($sqlSubject);
                                $thisSubCode=$newArraySuffled[$valueField];
                                while ($row = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
                                   $SubjectID=$row['SubjectID'];
                                   $SubjectName=$row['SubjectName'];
                                   $selectedOk="";
                                   if($SubjectID==$thisSubCode)$selectedOk="selected";
                                   echo "<option value=\"$SubjectID\" $selectedOk>$SubjectName - $SubjectID</option>";
                                } */
								$sql22 = "{call SP_TG_LoadSencesTeachers( ?)}";
                                $stmt22 = $db->runMsSqlQuery($sql22, $params2);                          
                                while ($row2 = sqlsrv_fetch_array($stmt22, SQLSRV_FETCH_ASSOC)) {
                                   $NIC=$row2['NIC'];
                                   $TeacherName=$row2['TeacherName'];
                                   $SubjectName=$row2['SubjectName'];
                                   $TeachingType=$row2['TeachingType'];
                                   $selectedOk="";
                                  if($NIC==$alreadyInTT)$selectedOk="selected";
                                   echo "<option value=\"$NIC\" $selectedOk>$TeacherName ($SubjectName) </option>";
								}
                                ?>
                            </select>
                            <?php 
							//2014 Sept 12 modification DAD
							$groupCheck=getGroupSubjects($SchoolID,$GradeID,$subjectCode);
							if($groupCheck!=''){
								$groupCode=explode(",",$groupCheck);
								for($p=0;$p<count($groupCode);$p++){
									$codeGroup=$groupCode[$p];
									if($codeGroup!=''){
										$groupFid=$FieldID."_".$p;
										
										$subjectCodeLoad="";
										if(!in_array($codeGroup,$subjectFirst)){
											$subjectFirst[]=$codeGroup;
											$subjectCodeLoad=$codeGroup;
										}
										
										echo "<br>";
										if($subjectCodeLoad!='')echo "<img src=\"../cms/images/star.png\" width=\"10\" height=\"10\" alt=\"timetable\" /> ";
										echo $groupCubName=getSubjectNameCommon($codeGroup);
										$getTTIDGr=getTTID($SchoolID,$GradeID,$ClassID,$groupFid); 
										$alreadyInTTGr=getAlreadyInTeacher($SchoolID,$GradeID,$ClassID,$groupFid,$getTTIDGr);
										$exNIC=explode("-",$alreadyInTTGr);
										$dropClass="select2a_new";
										if(count($exNIC)=='2'){
											$dropClass="select2a_red";
											$alreadyInTTGr=$exNIC[0];
										}
								?>
                            <br />
                             <select name="<?php echo $groupFid; ?>" class="<?php echo $dropClass ?>" id="<?php echo $groupFid; ?>" onchange="checkTeacherAvailability('<?php echo $SchoolID ?>','<?php echo $GradeID ?>','<?php echo $ClassID ?>','<?php echo $groupFid ?>','<?php echo $PeriodNumberM ?>','Wednesday','<?php echo $subjectCodeLoad ?>')">
                         <option value="">-Select-</option>
                                <?php
                                $valueField=$i+1*$NumberOfPeriods;
                              
								$sql22 = "{call SP_TG_LoadSencesTeachers( ?)}";
                                $stmt22 = $db->runMsSqlQuery($sql22, $params2);                          
                                while ($row2 = sqlsrv_fetch_array($stmt22, SQLSRV_FETCH_ASSOC)) {
                                   $NIC=$row2['NIC'];
                                   $TeacherName=$row2['TeacherName'];
                                   $SubjectName=$row2['SubjectName'];
                                   $TeachingType=$row2['TeachingType'];
                                   $selectedOk="";
                                  if($NIC==$alreadyInTTGr)$selectedOk="selected";
                                   echo "<option value=\"$NIC\" $selectedOk>$TeacherName ($SubjectName) </option>";}
                                ?>
                            </select>
                        <?php }}}?></td>
                        <td align="center" valign="top" bgcolor="#FFFFFF"><span style="color:#DC8F87;"><?php $dd=$i+$NumberOfPeriods*3;$FieldID="TT".$dd;
						$subjectCode=getSubjectCodeOnly($SchoolID,$GradeID,$ClassID,$FieldID);
						$subjectCodeLoad="";
						if(!in_array($subjectCode,$subjectFirst)){
							$subjectFirst[]=$subjectCode;
							$subjectCodeLoad=$subjectCode;
						}
						
					$getTTID=getTTID($SchoolID,$GradeID,$ClassID,$FieldID); 
					if($subjectCodeLoad!='')echo "<img src=\"../cms/images/star.png\" width=\"10\" height=\"10\" alt=\"timetable\" /> ";
                    echo $subjectName=getSubjectNameOnly($SchoolID,$GradeID,$ClassID,$FieldID);
					$alreadyInTT=getAlreadyInTeacher($SchoolID,$GradeID,$ClassID,$FieldID,$getTTID);
					$exNIC=explode("-",$alreadyInTT);
					$dropClass="select2a_new";
					if(count($exNIC)=='2'){
						$dropClass="select2a_red";
						$alreadyInTT=$exNIC[0];
					}?></span><br />
                         <input type="hidden" name="F<?php echo $FieldID ?>" value="<?php echo $subjectCode ?>" />
                        <select name="TT<?php echo $i+3*$NumberOfPeriods; ?>" class="<?php echo $dropClass ?>" id="TT<?php echo $i+3*$NumberOfPeriods; ?>" onchange="checkTeacherAvailability('<?php echo $SchoolID ?>','<?php echo $GradeID ?>','<?php echo $ClassID ?>','<?php echo $FieldID ?>','<?php echo $PeriodNumberM ?>','Thursday','<?php echo $subjectCodeLoad ?>')">
                         <option value="">-Select-</option>
                                <?php
                                $valueField=$i+3*$NumberOfPeriods;
                                /* $stmtSub = $db->runMsSqlQuery($sqlSubject);
                                $thisSubCode=$newArraySuffled[$valueField];
                                while ($row = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
                                   $SubjectID=$row['SubjectID'];
                                   $SubjectName=$row['SubjectName'];
                                   $selectedOk="";
                                   if($SubjectID==$thisSubCode)$selectedOk="selected";
                                   echo "<option value=\"$SubjectID\" $selectedOk>$SubjectName - $SubjectID</option>";
                                } */
								$sql22 = "{call SP_TG_LoadSencesTeachers( ?)}";
                                $stmt22 = $db->runMsSqlQuery($sql22, $params2);                          
                                while ($row2 = sqlsrv_fetch_array($stmt22, SQLSRV_FETCH_ASSOC)) {
                                   $NIC=$row2['NIC'];
                                   $TeacherName=$row2['TeacherName'];
                                   $SubjectName=$row2['SubjectName'];
                                   $TeachingType=$row2['TeachingType'];
                                   $selectedOk="";
                                  if($NIC==$alreadyInTT)$selectedOk="selected";
                                   echo "<option value=\"$NIC\" $selectedOk>$TeacherName ($SubjectName) </option>";
								   $getTTIDGr=getTTID($SchoolID,$GradeID,$ClassID,$groupFid); 
										$alreadyInTTGr=getAlreadyInTeacher($SchoolID,$GradeID,$ClassID,$groupFid,$getTTIDGr);
										$exNIC=explode("-",$alreadyInTTGr);
										$dropClass="select2a_new";
										if(count($exNIC)=='2'){
											$dropClass="select2a_red";
											$alreadyInTTGr=$exNIC[0];
										}
								}
                                ?>
                            </select>
                            <?php 
							//2014 Sept 12 modification DAD
							$groupCheck=getGroupSubjects($SchoolID,$GradeID,$subjectCode);
							if($groupCheck!=''){
								$groupCode=explode(",",$groupCheck);
								for($p=0;$p<count($groupCode);$p++){
									$codeGroup=$groupCode[$p];
									if($codeGroup!=''){
										$groupFid=$FieldID."_".$p;
										
										$subjectCodeLoad="";
										if(!in_array($codeGroup,$subjectFirst)){
											$subjectFirst[]=$codeGroup;
											$subjectCodeLoad=$codeGroup;
										}
										
										echo "<br>";
										if($subjectCodeLoad!='')echo "<img src=\"../cms/images/star.png\" width=\"10\" height=\"10\" alt=\"timetable\" /> ";
										echo $groupCubName=getSubjectNameCommon($codeGroup);
										$getTTIDGr=getTTID($SchoolID,$GradeID,$ClassID,$groupFid); 
										$alreadyInTTGr=getAlreadyInTeacher($SchoolID,$GradeID,$ClassID,$groupFid,$getTTIDGr);
										$exNIC=explode("-",$alreadyInTTGr);
										$dropClass="select2a_new";
										if(count($exNIC)=='2'){
											$dropClass="select2a_red";
											$alreadyInTTGr=$exNIC[0];
										}
								?>
                            <br />
                             <select name="<?php echo $groupFid; ?>" class="<?php echo $dropClass ?>" id="<?php echo $groupFid; ?>" onchange="checkTeacherAvailability('<?php echo $SchoolID ?>','<?php echo $GradeID ?>','<?php echo $ClassID ?>','<?php echo $groupFid ?>','<?php echo $PeriodNumberM ?>','Thursday','<?php echo $subjectCodeLoad ?>')">
                         <option value="">-Select-</option>
                                <?php
                                $valueField=$i+1*$NumberOfPeriods;
                              
								$sql22 = "{call SP_TG_LoadSencesTeachers( ?)}";
                                $stmt22 = $db->runMsSqlQuery($sql22, $params2);                          
                                while ($row2 = sqlsrv_fetch_array($stmt22, SQLSRV_FETCH_ASSOC)) {
                                   $NIC=$row2['NIC'];
                                   $TeacherName=$row2['TeacherName'];
                                   $SubjectName=$row2['SubjectName'];
                                   $TeachingType=$row2['TeachingType'];
                                   $selectedOk="";
                                  if($NIC==$alreadyInTTGr)$selectedOk="selected";
                                   echo "<option value=\"$NIC\" $selectedOk>$TeacherName ($SubjectName) </option>";}
                                ?>
                            </select>
                        <?php }}}?></td>
                        <td align="center" valign="top" bgcolor="#FFFFFF"><span style="color:#DC8F87;"><?php $dd=$i+$NumberOfPeriods*4;$FieldID="TT".$dd;
						$subjectCode=getSubjectCodeOnly($SchoolID,$GradeID,$ClassID,$FieldID);
						$subjectCodeLoad="";
						if(!in_array($subjectCode,$subjectFirst)){
							$subjectFirst[]=$subjectCode;
							$subjectCodeLoad=$subjectCode;
						}
						
					$getTTID=getTTID($SchoolID,$GradeID,$ClassID,$FieldID); 
					if($subjectCodeLoad!='')echo "<img src=\"../cms/images/star.png\" width=\"10\" height=\"10\" alt=\"timetable\" /> ";
                    echo $subjectName=getSubjectNameOnly($SchoolID,$GradeID,$ClassID,$FieldID);
					$alreadyInTT=getAlreadyInTeacher($SchoolID,$GradeID,$ClassID,$FieldID,$getTTID);
					$exNIC=explode("-",$alreadyInTT);
					$dropClass="select2a_new";
					if(count($exNIC)=='2'){
						$dropClass="select2a_red";
						$alreadyInTT=$exNIC[0];
					} ?></span><br />
                         <input type="hidden" name="F<?php echo $FieldID ?>" value="<?php echo $subjectCode ?>" />
                        <select name="TT<?php echo $i+4*$NumberOfPeriods; ?>" class="<?php echo $dropClass ?>" id="TT<?php echo $i+4*$NumberOfPeriods; ?>" onchange="checkTeacherAvailability('<?php echo $SchoolID ?>','<?php echo $GradeID ?>','<?php echo $ClassID ?>','<?php echo $FieldID ?>','<?php echo $PeriodNumberM ?>','Friday','<?php echo $subjectCodeLoad ?>')">
                         <option value="">-Select-</option>
                                <?php
                                $valueField=$i+4*$NumberOfPeriods;
                                /* $stmtSub = $db->runMsSqlQuery($sqlSubject);
                                $thisSubCode=$newArraySuffled[$valueField];
                                while ($row = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
                                   $SubjectID=$row['SubjectID'];
                                   $SubjectName=$row['SubjectName'];
                                   $selectedOk="";
                                   if($SubjectID==$thisSubCode)$selectedOk="selected";
                                   echo "<option value=\"$SubjectID\" $selectedOk>$SubjectName - $SubjectID</option>";
                                } */
								$sql22 = "{call SP_TG_LoadSencesTeachers( ?)}";
                                $stmt22 = $db->runMsSqlQuery($sql22, $params2);                          
                                while ($row2 = sqlsrv_fetch_array($stmt22, SQLSRV_FETCH_ASSOC)) {
                                   $NIC=$row2['NIC'];
                                   $TeacherName=$row2['TeacherName'];
                                   $SubjectName=$row2['SubjectName'];
                                   $TeachingType=$row2['TeachingType'];
                                   $selectedOk="";
                                 if($NIC==$alreadyInTT)$selectedOk="selected";
                                   echo "<option value=\"$NIC\" $selectedOk>$TeacherName ($SubjectName) </option>";
								}
                                ?>
                            </select>
                            <?php 
							//2014 Sept 12 modification DAD
							$groupCheck=getGroupSubjects($SchoolID,$GradeID,$subjectCode);
							if($groupCheck!=''){
								$groupCode=explode(",",$groupCheck);
								for($p=0;$p<count($groupCode);$p++){
									$codeGroup=$groupCode[$p];
									if($codeGroup!=''){
										$groupFid=$FieldID."_".$p;
										$subjectCodeLoad="";
										if(!in_array($codeGroup,$subjectFirst)){
											$subjectFirst[]=$codeGroup;
											$subjectCodeLoad=$codeGroup;
										}
										
										echo "<br>";
										if($subjectCodeLoad!='')echo " <img src=\"../cms/images/star.png\" width=\"10\" height=\"10\" alt=\"timetable\" /> ";
										echo $groupCubName=getSubjectNameCommon($codeGroup);
										$getTTIDGr=getTTID($SchoolID,$GradeID,$ClassID,$groupFid); 
										$alreadyInTTGr=getAlreadyInTeacher($SchoolID,$GradeID,$ClassID,$groupFid,$getTTIDGr);
										$exNIC=explode("-",$alreadyInTTGr);
										$dropClass="select2a_new";
										if(count($exNIC)=='2'){
											$dropClass="select2a_red";
											$alreadyInTTGr=$exNIC[0];
										}
								?>
                            <br />
                             <select name="<?php echo $groupFid; ?>" class="<?php echo $dropClass ?>" id="<?php echo $groupFid; ?>" onchange="checkTeacherAvailability('<?php echo $SchoolID ?>','<?php echo $GradeID ?>','<?php echo $ClassID ?>','<?php echo $groupFid ?>','<?php echo $PeriodNumberM ?>','Friday','<?php echo $subjectCodeLoad ?>')">
                         <option value="">-Select-</option>
                                <?php
                                $valueField=$i+1*$NumberOfPeriods;
                              
								$sql22 = "{call SP_TG_LoadSencesTeachers( ?)}";
                                $stmt22 = $db->runMsSqlQuery($sql22, $params2);                          
                                while ($row2 = sqlsrv_fetch_array($stmt22, SQLSRV_FETCH_ASSOC)) {
                                   $NIC=$row2['NIC'];
                                   $TeacherName=$row2['TeacherName'];
                                   $SubjectName=$row2['SubjectName'];
                                   $TeachingType=$row2['TeachingType'];
                                   $selectedOk="";
                                  if($NIC==$alreadyInTTGr)$selectedOk="selected";
                                   echo "<option value=\"$NIC\" $selectedOk>$TeacherName ($SubjectName) </option>";}
                                ?>
                            </select>
                        <?php }}}?></td>
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