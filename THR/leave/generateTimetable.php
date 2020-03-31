<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
include('js/common.js.php'); 
include('js/ajaxloadpage.js.php'); 
?>
<?php
$array = array( 'a', 'a', 'a', 'a', 'b', 'b', 'c' );
print_r($array);
$count = count(array_keys($array, 'b', true));
echo "Found $count letter a's.";
echo "<br><br>";
$value="SB1101,SB1205,SB1205,SB1205,SB1501,SB1601,SB1401,SB1101,SB1101,SB1401,SB1101,SB1501,SB1501,SB1601,SB1401,SB1501,SB1401,SB1501,SB1401,SB1205,SB1601,SB1101,SB1205,SB1205,SB1601,SB1401,SB1401,SB1101,SB1501,SB1212,SB1401,SB1101,SB1601,SB1501,SB1501,SB1212,SB1212,SB1601,SB1205,SB1101";
$arrddd=explode(',',$value);
print_r($arrddd);
echo $count = count(array_keys($arrddd, 'SB1101', true));
echo "<br><br>";

        ?>
<?php 

$msg="";
$tblNam="TG_SchoolGenerateTT_Temp";
if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$SchoolID=$_REQUEST['SchoolID'];
	$ClassID=$_REQUEST['ClassID'];
	$GradeID=$_REQUEST['GradeID'];
        	
        $sqlNoOfPeriod="SELECT [ID]
      ,[GradeTitle]
      ,[NumberOfPeriods]
  FROM [dbo].[TG_SchoolGrade]
  WHERE ID='$GradeID'";
        $stmt = $db->runMsSqlQuery($sqlNoOfPeriod);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $NumberOfPeriods=$row['NumberOfPeriods'];            
        }
        
$totalRows=5*$NumberOfPeriods;	

//$sqlSubject="SELECT SubjectID,PeriodsPerWeek from TG_SchoolSubjectMaster where SchoolID='$SchoolID' and GradeID='$GradeID'";
$sqlSubject="SELECT        TG_SchoolSubjectMaster.SubjectID, CD_Subject.SubjectName, TG_SchoolSubjectMaster.PeriodsPerWeek
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
}

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
        if($newArry[$currentKey]==$my_array[$element] and $newArry[$currentKey]==$my_array[$element]){
            
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
//echo "<br>";
//print_r(custom_shuffle($subArry));
$newArraySuffled=custom_shuffle($subArry);
print_r($newArraySuffled);

/*for($i=1;$i<$totalRows;$i++){
    
    //echo $SubjectID;echo "<br>"; 
    $queryGradeSave="INSERT INTO $tblNam
           (SchoolID,GradeID,ClassID,SubjectID,FieldID)
     VALUES
           ('$SchoolID','$GradeID','$ClassID','$SubjectID','1')";
    //$db->runMsSqlQuery($queryGradeSave);
}*/

        
        
}
$params1 = array(
	array($GradeID, SQLSRV_PARAM_IN),
	array($SchoolID, SQLSRV_PARAM_IN)
);
$params2 = array(
    array($SchoolID, SQLSRV_PARAM_IN),
    array($GradeID, SQLSRV_PARAM_IN),
    array($ClassID, SQLSRV_PARAM_IN),
    array($NumberOfPeriods, SQLSRV_PARAM_IN)
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
                      <td>Grade :</td>
                      <td><select class="select2a_new" id="GradeID" name="GradeID" onchange="filterClass();">
                            <option value="">-Select-</option>
                            <?php
                            $sql = "SELECT ID,GradeTitle FROM TG_SchoolGrade";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $selectd="";
                                $GradeIDThis=$row['ID'];
                                $GradeTitle=$row['GradeTitle'];
                                if($GradeID==$row['ID'])$selectd="selected";
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
                      <td>
                      <select id="ClassID" name="ClassID" class="select2a_new">
                        
                        <?php $sql = "{call SP_TG_GetClassOfGrade( ?, ?)}";
    $dataSchool = "<option value=\"\">-Select-</option>";
    $stmt = $db->runMsSqlQuery($sql, $params1);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $dataSchool.= '<option value=' . $row['ID'] . '>' . $row['ClassID'] . '</option>';
    }?>
                        </select>
                      </td>
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
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
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
                            <input type="text" id="test" name="test" value="">
                            <input type="text" id="test2" name="test" value="">
                            <input type="hidden" id="subArr" name="subArr" value="<?php echo $newArraySuffled?>"></td>
                      </tr>
                      <?php 
                      $sql = "{call SP_TG_TimeTableTemp( ?, ?, ? ,?)}";
                      $stmt = $db->runMsSqlQuery($sql, $params2);
                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            //if ($sqZone == trim($row['CenCode']))
                                //echo '<option selected="selected" value=' . $row['CenCode'] . '>' . $row['Zone'] . '</option>';
                           // else
                               // echo '<option value=' . $row['CenCode'] . '>' . $row['Zone'] . '</option>';
                        }
                      
                      for($i=1;$i<$NumberOfPeriods+1;$i++){?>
                      <tr>
                        <td height="30" bgcolor="#CCCCCC"><strong>Period <?php echo $i ?></strong></td>
                        <td bgcolor="#FFFFFF"><?php //echo $i ?>
                            <select name="<?php echo $i ?>" class="select2a_new" id="TT<?php echo $i ?>" onchange="checkOverload(<?php echo $totalRows?>)">
                                <option value="">-Select-</option>
                                <?php
                                $valueField=$i;
                                $stmtSub = $db->runMsSqlQuery($sqlSubject);
                                $thisSubCode=$newArraySuffled[$valueField];
                                while ($row = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
                                   $SubjectID=$row['SubjectID'];
                                   $SubjectName=$row['SubjectName'];
                                   $selectedOk="";
                                   if($SubjectID==$thisSubCode)$selectedOk="selected";
                                   echo "<option value=\"$SubjectID\" $selectedOk>$SubjectName - $SubjectID</option>";
                                }
                                ?>
                        </select></td>
                        <td bgcolor="#FFFFFF"><?php //echo $i+1*$NumberOfPeriods; ?>
                            <select name="<?php echo $i+$NumberOfPeriods; ?>" class="select2a_new" id="TT<?php echo $i+$NumberOfPeriods; ?>" onchange="checkOverload(<?php echo $totalRows?>)">
                         <option value="">-Select-</option>
                                <?php
                                $valueField=$i+1*$NumberOfPeriods;
                                $stmtSub = $db->runMsSqlQuery($sqlSubject);
                                $thisSubCode=$newArraySuffled[$valueField];
                                while ($row = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
                                   $SubjectID=$row['SubjectID'];
                                   $SubjectName=$row['SubjectName'];
                                   $selectedOk="";
                                   if($SubjectID==$thisSubCode)$selectedOk="selected";
                                   echo "<option value=\"$SubjectID\" $selectedOk>$SubjectName - $SubjectID</option>";
                                }
                                ?>
                            </select></td>
                        <td bgcolor="#FFFFFF"><?php //echo $i+2*$NumberOfPeriods; ?>
                            <select name="<?php echo $i+2*$NumberOfPeriods; ?>" class="select2a_new" id="TT<?php echo $i+2*$NumberOfPeriods; ?>" onchange="checkOverload(<?php echo $totalRows?>)">
                         <option value="">-Select-</option>
                                <?php
                                $valueField=$i+2*$NumberOfPeriods;
                                $stmtSub = $db->runMsSqlQuery($sqlSubject);
                                $thisSubCode=$newArraySuffled[$valueField];
                                while ($row = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
                                   $SubjectID=$row['SubjectID'];
                                   $SubjectName=$row['SubjectName'];
                                   $selectedOk="";
                                   if($SubjectID==$thisSubCode)$selectedOk="selected";
                                   echo "<option value=\"$SubjectID\" $selectedOk>$SubjectName - $SubjectID</option>";
                                }
                                ?>
                            </select></td>
                        <td bgcolor="#FFFFFF"><?php //echo $i+3*$NumberOfPeriods; ?>
                            <select name="<?php echo $i+3*$NumberOfPeriods; ?>" class="select2a_new" id="TT<?php echo $i+3*$NumberOfPeriods; ?>" onchange="checkOverload(<?php echo $totalRows?>)">
                         <option value="">-Select-</option>
                                <?php
                                $valueField=$i+3*$NumberOfPeriods;
                                $stmtSub = $db->runMsSqlQuery($sqlSubject);
                                $thisSubCode=$newArraySuffled[$valueField];
                                while ($row = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
                                   $SubjectID=$row['SubjectID'];
                                   $SubjectName=$row['SubjectName'];
                                   $selectedOk="";
                                   if($SubjectID==$thisSubCode)$selectedOk="selected";
                                   echo "<option value=\"$SubjectID\" $selectedOk>$SubjectName - $SubjectID</option>";
                                }
                                ?>
                            </select></td>
                        <td bgcolor="#FFFFFF"><?php //echo $i+4*$NumberOfPeriods; ?>
                            <select name="<?php echo $i+4*$NumberOfPeriods; ?>" class="select2a_new" id="TT<?php echo $i+4*$NumberOfPeriods; ?>" onchange="checkOverload(<?php echo $totalRows?>)">
                         <option value="">-Select-</option>
                                <?php
                                $valueField=$i+4*$NumberOfPeriods;
                                $stmtSub = $db->runMsSqlQuery($sqlSubject);
                                $thisSubCode=$newArraySuffled[$valueField];
                                while ($row = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
                                   $SubjectID=$row['SubjectID'];
                                   $SubjectName=$row['SubjectName'];
                                   $selectedOk="";
                                   if($SubjectID==$thisSubCode)$selectedOk="selected";
                                   echo "<option value=\"$SubjectID\" $selectedOk>$SubjectName - $SubjectID</option>";
                                }
                                ?>
                            </select></td>
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