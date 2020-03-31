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
//ajaxCall/FilterDB.php?RequestType=checkDuplicateTeacher&GradeID=6&SchoolID=SC00449&ClassID=1007&totalRows=40&currentTT=SB1101,SB1501,SB1212,SB2014,SB1212,SB1501,SB1101,SB1205,SB1601,SB1206,SB1101,SB1601,SB2101,SB2101,SB1401,SB1801,SB1205,SB1401,SB1904,SB2014,SB1601,SB1401,SB2001,SB1801,SB1401,SB2301,SB1212,SB1401,SB1501,SB1801,SB1205,SB1904,SB2014,SB1501,SB1206,SB1212,SB1501,SB1904,SB1205,SB1205&resultAsJson=1

/* $GradeID = 6;
	$ClassID = 1007;
    $SchoolID = "SC00449";
    $totalRows=40;
    $currentTT="SB1101,SB1501,SB1212,SB2014,SB1212,SB1501,SB1101,SB1205,SB1601,SB1206,SB1101,SB1601,SB2101,SB2101,SB1401,SB1801,SB1205,SB1401,SB1904,SB2014,SB1601,SB1401,SB2001,SB1801,SB1401,SB2301,SB1212,SB1401,SB1501,SB1801,SB1205,SB1904,SB2014,SB1501,SB1206,SB1212,SB1501,SB1904,SB1205,SB1205";
    $currentto=explode(',',$currentTT);
	
	$failTT=$successTT="";
	echo count($currentto);
	for($x=0;$x<count($currentto);$x++){
		echo $subjCode=$currentto[$x];echo "__<br>";
		$d=$x+1;
		$fieldIDTT="TT".$d;
		
		$sql="SELECT TeacherID FROM TG_SchoolSubjectTeacher where SchoolID='$SchoolID' and GradeID='$GradeID' and ClassID='$ClassID' and SubjectID='$subjCode'";
		$stmt = $db->runMsSqlQuery($sql);
		$subjField="";
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$TeacherID=trim($row['TeacherID']);
			
			if($TeacherID!=''){// first period of each sumbect
				$countTotal="SELECT * FROM TG_SchoolTimeTable where SchoolID='$SchoolID' and FieldID='$fieldIDTT' and TeacherID='$TeacherID'  and ClassID!='$ClassID'";//and GradeID='$GradeID' and ClassID='$ClassID' 
				$TotaRows=$db->rowCount($countTotal);
				if($TotaRows>=1){
					//$StatusTT='Err';
					echo $failTT.=$fieldIDTT.",";
				}else{ 
					//$StatusTT='Suc';
					$successTT.=$fieldIDTT.",";
				}
			}
			
		}
	}
	
	exit(); */

        ?>
<?php 

$msg="";
//$tblNam="TG_SchoolGenerateTT_Temp";
if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$SchoolID=$_REQUEST['SchoolID'];
	$ClassID=$_REQUEST['ClassID'];
	$GradeID=$_REQUEST['GradeID'];
	$newArraySuffled=array();
	$countTotal="SELECT * FROM TG_SchoolTimeTable where SchoolID='$SchoolID' and ClassID='$ClassID' and GradeID='$GradeID'";
	$TotaRows=$db->rowCount($countTotal);//exit();
    if($TotaRows==0){ //echo "hi";   	
        $sqlNoOfPeriod="SELECT        TG_SchoolGrade.GradeTitle, TG_SchoolGrade.NumberOfPeriods, TG_SchoolGradeMaster.ID
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolGradeMaster ON TG_SchoolGrade.ID = TG_SchoolGradeMaster.GradeID
						 WHERE TG_SchoolGradeMaster.ID='$GradeID'";//TG_SchoolGradeMaster.SchoolID='$loggedSchool' and 
        $stmt = $db->runMsSqlQuery($sqlNoOfPeriod);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $NumberOfPeriods=$row['NumberOfPeriods'];            
        }
        
$totalRows=5*$NumberOfPeriods;	

//$sqlSubject="SELECT SubjectID,PeriodsPerWeek from TG_SchoolSubjectMaster where SchoolID='$SchoolID' and GradeID='$GradeID'";
$sqlSubject="SELECT        TG_SchoolSubjectMaster.SubjectID, CD_Subject.SubjectName, TG_SchoolSubjectMaster.PeriodsPerWeek ,TG_SchoolSubjectMaster.IsMainSubject ,TG_SchoolSubjectMaster.MaxNoPerDay ,TG_SchoolSubjectMaster.IsNeedSupportTeacher
FROM            TG_SchoolSubjectMaster INNER JOIN
                         CD_Subject ON TG_SchoolSubjectMaster.SubjectID = CD_Subject.SubCode
where TG_SchoolSubjectMaster.GradeID='$GradeID'";//TG_SchoolSubjectMaster.SchoolID='$SchoolID' and 

$stmtSub = $db->runMsSqlQuery($sqlSubject);
$subArry=array("default");
$subjectArry=array("default");
$timetabelArray=array();
$alreadyAdded=0;
$m=1;

while ($row = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
   $SubjectID=trim($row['SubjectID']);  //echo "__";
   $PeriodsPerWeek=$row['PeriodsPerWeek'];//echo "<br>";
   $MaxNoPerDay=$row['MaxNoPerDay'];
   $IsMainSubject=trim($row['IsMainSubject']);
   
   $subjectArry[]=$SubjectID;
   $alreadyAdded=0;
   if($IsMainSubject=='Y'){
	   $timetabelArray[$m]=$SubjectID;
	   $alreadyAdded++;
	   $newMaxP=$MaxNoPerDay-1;
	   $keyMain=$m;
	  // print_r($timetabelArray);
	
	   if($MaxNoPerDay>0 || $MaxNoPerDay!=''){
		   for($n=1;$n<$MaxNoPerDay;$n++){
				$timetabelArray[$m+$n]=$SubjectID;
				$alreadyAdded++;
		   }
	   }
	   
	  $m=$m+8; 
   }
  /* if($IsMainSubject=='Y'){
		 echo $m=$m+8;
	} */
   
   
   for($t=0;$t<$PeriodsPerWeek-$alreadyAdded;$t++){
       $subArry[]=$SubjectID;
   }
	//print_r($subArry);echo "<br>";
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
	//echo "<br>";
	//print_r(custom_shuffle($subArry));
$newArraySuffled=custom_shuffle($subArry);

//print_r($newArraySuffled);echo "XXXXXXXXXX<br><br>";

//print_r($timetabelArray);echo "TT<br><br>";
$x=0;
for($i=0;$i<count($newArraySuffled)+1;$i++){
	$keyValueSub=$newArraySuffled[$i];
	
	if (array_key_exists($i,$timetabelArray)){
		$valueOFSubArray=$newArraySuffled[$i];
		
		$nextTTarrayKey=count($newArraySuffled)+$x;
		if($valueOFSubArray)$timetabelArray[$nextTTarrayKey]=$valueOFSubArray;
		
		$x++;
	}else{
		if($keyValueSub)$timetabelArray[$i]=$keyValueSub;
	}
}

ksort($timetabelArray);



//$arr3 = $timetabelArray + $subArry;
//$arrKey=array_flip($timetabelArray);

//print_r($subjectArry);echo "<br>---------<br>";
//print_r($newArraySuffled);echo "<br><br>";
//print_r($timetabelArray);echo "YY<br><br>";
//print_r(array_keys($timetabelArray));echo "<br><br>";
//print_r($arr3);echo "<br>";
//exit();

$newArraySuffled=$timetabelArray;




	//print_r($newArraySuffled);
	//echo "<br>...";echo "<br>";
	// /*******************Strat Load pre schedule subject for group class*******************/
	$sqlGrp="SELECT ClassGrouped,SubjectID FROM TG_SchoolSubjectGroup where SchoolID='$SchoolID' and GradeID='$GradeID' and ClassGrouped LIKE ('%,$ClassID,%')";
		$stmtGrp = $db->runMsSqlQuery($sqlGrp);
		$subjField="";
		$subArryG=array();//"default"
		$currentTTKey=array();
		$wrongsubjectInplace=array();
		while ($row = sqlsrv_fetch_array($stmtGrp, SQLSRV_FETCH_ASSOC)) {
			$ClassGrouped=$row['ClassGrouped'];//echo "<br>";
			$SubjectID=trim($row['SubjectID']);
			$currentTTKey=array_keys($newArraySuffled, $SubjectID);
			/* $a=array("Volvo"=>"XC90","BMW"=>"X5","Toyota"=>"Highlander");
print_r(array_keys($a,"Highlander")); */

			//print_r($currentTTKey);
			$classIDs=explode(",",$ClassGrouped);
			for($x=0;$x<count($classIDs);$x++){
				$classIDG=$classIDs[$x];
				if($classIDG!=$ClassID and $classIDG!=''){//echo "hi"; echo $classIDG;
					$sqlTimeTG="SELECT * FROM TG_SchoolTimeTable where SchoolID='$SchoolID' and ClassID='$classIDG' and GradeID='$GradeID' and SubjectID='$SubjectID' order by ID ASC";
					 
					$stmtG = $db->runMsSqlQuery($sqlTimeTG);
					while ($row = sqlsrv_fetch_array($stmtG, SQLSRV_FETCH_ASSOC)) {
						$FieldIDG=$row['FieldID']; 
						$FieldIDGNumber=substr($FieldIDG, 2);//echo "__";
						//if(!in_array($FieldIDGNumber,$currentTTKey)){
							$subArryG[]=$FieldIDGNumber;
							$currentTTValue=$newArraySuffled[$FieldIDGNumber];//echo "<br>";
							if($currentTTValue!=$SubjectID){ //echo "hi";
								$wrongsubjectInplace[]=$currentTTValue;
							}else{ //echo "hi";
								if (($key = array_search($FieldIDGNumber, $currentTTKey)) !== false) {
									//echo $key;
									unset($currentTTKey[$key]);
									
									$currentTTKey = array_merge($currentTTKey);
								}
							}
						//DAD
							$newArraySuffled[$FieldIDGNumber] = $SubjectID;
							    
						//}
					}
					
				}
			}
		}
		//echo "<br>";
		//print_r($newArraySuffled);
		//echo "<br>";echo "<br>";print_r($subArryG);
	     for($y=0;$y<count($subArryG);$y++){
			$currentKey=$currentTTKey[$y];
			$keySubj=$wrongsubjectInplace[$y];
			if($currentKey)$newArraySuffled[$currentKey] = $keySubj;
		} 
		/*echo "previous class";print_r($subArryG);
		echo "<br>";echo "<br>";
		print_r($newArraySuffled);
		echo "<br>";echo "<br>";
		echo "current class";print_r($currentTTKey);*/
		
		//echo "<br>";echo "<br>";
		//print_r($wrongsubjectInplace);
		//print_r(array_keys($newArraySuffled, "SB1904"));
		//print_r(array_filter($newArraySuffled, "SB1904"));
	
	// /*******************End Load pre schedule subject for group class********************/
	
//print_r($subArry);

//print_r($newArraySuffled);

/*for($i=1;$i<$totalRows;$i++){
    
    //echo $SubjectID;echo "<br>"; 
    $queryGradeSave="INSERT INTO $tblNam
           (SchoolID,GradeID,ClassID,SubjectID,FieldID)
     VALUES
           ('$SchoolID','$GradeID','$ClassID','$SubjectID','1')";
    //$db->runMsSqlQuery($queryGradeSave);
}*/

        
	}else{// load generated timetable
		 $sqlNoOfPeriod="SELECT        TG_SchoolGrade.GradeTitle, TG_SchoolGrade.NumberOfPeriods, TG_SchoolGradeMaster.ID
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolGradeMaster ON TG_SchoolGrade.ID = TG_SchoolGradeMaster.GradeID
						 WHERE TG_SchoolGradeMaster.ID='$GradeID'";//TG_SchoolGradeMaster.SchoolID='$loggedSchool' and 
        $stmt = $db->runMsSqlQuery($sqlNoOfPeriod);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $NumberOfPeriods=$row['NumberOfPeriods'];            
        }
        
$totalRows=5*$NumberOfPeriods;

$sqlSubject="SELECT        TG_SchoolSubjectMaster.SubjectID, CD_Subject.SubjectName, TG_SchoolSubjectMaster.PeriodsPerWeek
FROM            TG_SchoolSubjectMaster INNER JOIN
                         CD_Subject ON TG_SchoolSubjectMaster.SubjectID = CD_Subject.SubCode
where TG_SchoolSubjectMaster.GradeID='$GradeID'";

		 //echo "hi";   	
		 $subArry=array("default");
		 $sqlTimeT="SELECT * FROM TG_SchoolTimeTable where SchoolID='$SchoolID' and ClassID='$ClassID' and GradeID='$GradeID' order by ID ASC";
		 
		$stmt = $db->runMsSqlQuery($sqlTimeT);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $SubjectID=trim($row['SubjectID']); 
			$FieldID=$row['FieldID']; 
			if (strpos($FieldID,'_') == false) {
				$subArry[]=$SubjectID;    
			}
        }
		
	//echo "<br>";
	//print_r(custom_shuffle($subArry));
		$newArraySuffled=$subArry;
	
	
		
	}
}
if(isset($_POST["SaveTT"]) || isset($_POST["ModifyTT"])){ 
	$SchoolID=$_REQUEST['SchoolID'];
	$ClassID=$_REQUEST['ClassID'];
	$GradeID=$_REQUEST['GradeID'];
	
	if(isset($_POST["ModifyTT"])){
		$sqlDeleteExist="DELETE FROM TG_SchoolTimeTable WHERE SchoolID='$SchoolID' and GradeID='$GradeID' and ClassID='$ClassID'";
		$db->runMsSqlQuery($sqlDeleteExist);
	}
        	//exit();
        $sqlNoOfPeriod="SELECT        TG_SchoolGrade.GradeTitle, TG_SchoolGrade.NumberOfPeriods, TG_SchoolGradeMaster.ID
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolGradeMaster ON TG_SchoolGrade.ID = TG_SchoolGradeMaster.GradeID
						 WHERE TG_SchoolGradeMaster.SchoolID='$loggedSchool' and TG_SchoolGradeMaster.ID='$GradeID'";
        $stmt = $db->runMsSqlQuery($sqlNoOfPeriod);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $NumberOfPeriods=$row['NumberOfPeriods'];            
        }
        
	$totalRows=5*$NumberOfPeriods;
	$Monday=$NumberOfPeriods;
	$Tuesday=$NumberOfPeriods*2;
	$Wednesday=$NumberOfPeriods*3;
	$Thursday=$NumberOfPeriods*4;
	$Friday=$NumberOfPeriods*5;
	
	for($i=1;$i<$totalRows+1;$i++){
		$fieldTag="TT".$i;
		
		if($i<=$Monday)$day="Monday";
		if($i<=$Tuesday and $i>$Monday)$day="Tuesday";
		if($i<=$Wednesday and $i>$Tuesday)$day="Wednesday";
		if($i<=$Thursday and $i>$Wednesday)$day="Thursday";
		if($i<=$Friday and $i>$Thursday)$day="Friday";
		//echo $_REQUEST['TT1'];
		$PeriodNumberM=$i%$NumberOfPeriods;
		if($PeriodNumberM==0)$PeriodNumberM=$NumberOfPeriods;
		$ttValue=$_REQUEST[$fieldTag];
		
		$teacheNIC=getTeacherNIC($SchoolID,$GradeID,$ClassID,$ttValue);
		
		$sqlInsertTT="INSERT INTO TG_SchoolTimeTable
           (SchoolID,GradeID,ClassID,SubjectID,FieldID,TeacherID,PeriodNumber,Day)
     VALUES 
           ('$SchoolID','$GradeID','$ClassID','$ttValue','$fieldTag','$teacheNIC','$PeriodNumberM','$day')";
		$db->runMsSqlQuery($sqlInsertTT);
		
		$groupCheck=getGroupSubjects($SchoolID,$GradeID,$ttValue);
		$groupCode=explode(",",$groupCheck);
			for($p=0;$p<count($groupCode);$p++){
				$codeGroup=$groupCode[$p];
				if($codeGroup!=''){
					$groubSub=$fieldTag."_".$p;//echo "<br>";
					//$ttValueGroup=$_REQUEST[$groubSub];
					$teacheNICG=getTeacherNIC($SchoolID,$GradeID,$ClassID,$codeGroup);
					$sqlInsertTT="INSERT INTO TG_SchoolTimeTable
           (SchoolID,GradeID,ClassID,SubjectID,FieldID,TeacherID,PeriodNumber,Day)
     VALUES
           ('$SchoolID','$GradeID','$ClassID','$codeGroup','$groubSub','$teacheNICG','$PeriodNumberM','$day')";
		$db->runMsSqlQuery($sqlInsertTT);
					//echo "<br>*";echo $groupCubName=getSubjectNameCommon($codeGroup);
					//echo "<input type=\"hidden\" id=\"$groubSub\" name=\"$groubSub\" value=\"$codeGroup\">";
				}
			}
		
		
	}	
	
	$ClassID="";
	$GradeID="";
	$NumberOfPeriods=0;
	$TotaRows==0;
	
}

$params1 = array(
	array($GradeID, SQLSRV_PARAM_IN),
	array($SchoolID, SQLSRV_PARAM_IN)
);

//$SubjectID=1;
$params2 = array(
    array($SchoolID, SQLSRV_PARAM_IN),
    array($GradeID, SQLSRV_PARAM_IN),
    array($ClassID, SQLSRV_PARAM_IN),
    array($SubjectID, SQLSRV_PARAM_IN)
);
 /* $sql = "{call SP_TG_TimeTableTemp( ?, ?, ? ,? ,?)}";
                      $stmt = $db->runMsSqlQuery($sql, $params2); */
					  //exit();
//print_r($params2);
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
                  <td width="59%" valign="top"><!--<img src="../cms/images/class-active.png" width="98" height="26" />&nbsp;<a href="generateTimetableLearningLocation-10.html"><img src="../cms/images/learning-location.jpg" width="98" height="26" /></a>-->&nbsp;</td>
        <td width="41%" valign="top">&nbsp;</td>
          </tr>
			  <tr>
                  <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
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
                      <td><?php //echo $ClassID;//print_r($params1);?>
                        <select id="ClassID" name="ClassID" class="select2a_new">
                          <?php $sql = "{call SP_TG_GetClassOfGrade( ?, ?)}";
    $dataSchool = "<option value=\"\">-Select-</option>";
    $stmt = $db->runMsSqlQuery($sql, $params1);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$ClassIDOp=$row['ClassID'];
		$IDop=$row['ID'];
		$selTExt="";
		if($IDop==$ClassID)$selTExt="selected";
        echo $dataSchool= "<option value=\"$IDop\" $selTExt>$ClassIDOp</option>";
    }?>
                       </select></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td style="color:#C00; font-weight:bold;"><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/generate.jpg); width:98px; height:26px; background-color:transparent; border:none; cursor:pointer;" value="" />
                     <?php if($TotaRows==0){ ?> <input name="SaveTT" type="submit" id="SaveTT" style="background-image: url(../cms/images/complete.jpg); width:98px; height:26px; background-color:transparent; border:none; cursor:pointer;" value="" /><?php }?>
                     <?php if($TotaRows>0){echo "Already generated !"; ?> <input name="ModifyTT" type="submit" id="ModifyTT" style="background-image: url(../cms/images/edit.png); width:80px; height:26px; background-color:transparent; border:none; cursor:pointer;" value="" /><?php }?>
                     </td>
                    </tr>
                    </table>
        </td>
        <td width="41%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                <tr>
                  <td width="43%" align="left" valign="top">&nbsp;</td>
                  <td width="57%">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2">&nbsp;<?php //print_r($newArraySuffled);?></td>
                </tr>
          </table></td>
          </tr>
                <tr>
                    <td colspan="2"><table width="100%" cellspacing="2" cellpadding="2">
                <tr>
                  <td width="24%" align="left" valign="top" style="color:#C00; font-weight:bold;"><?php //if($TotaRows!=0){ echo "Already generated !"; } ?></td>
                  <td width="3%" align="left" valign="top" style="background-color:#FFFF00;border:1px solid #CCC; ">&nbsp;</td>
                  <td width="17%" align="left" valign="top">Group subject</td>
                  <td width="3%" style="background-color:#9975F7;border:1px solid #CCC; ">&nbsp;</td>
                  <td width="17%">Teacher duplicate</td>
                  <td width="3%" style="background-color:#060;border:1px solid #CCC; ">&nbsp;</td>
                  <td width="17%">Less than the limit</td>
                  <td width="3%" style="background-color:#900;border:1px solid #CCC; ">&nbsp;</td>
                  <td width="13%">Over the limit</td>
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
					// echo "<br>";print_r($newArraySuffled);echo $NumberOfPeriods;
                    /*  $sql = "{call SP_TG_TimeTableTemp( ?, ?, ? , ?)}";
                      $stmt = $db->runMsSqlQuery($sql, $params2); 
                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            //if ($sqZone == trim($row['CenCode']))
                                //echo '<option selected="selected" value=' . $row['CenCode'] . '>' . $row['Zone'] . '</option>';
                           // else
                               // echo '<option value=' . $row['CenCode'] . '>' . $row['Zone'] . '</option>';
                        } */
						
						//echo "$SchoolID,$GradeID,$ClassID";
                      $subjectFirst=array();
                      for($i=1;$i<$NumberOfPeriods+1;$i++){
						  $PeriodNumberM=$i%$NumberOfPeriods;
						  if($PeriodNumberM==0)$PeriodNumberM=$NumberOfPeriods;
						  ?>
                      <tr>
                        <td height="30" bgcolor="#CCCCCC"><strong>&nbsp;&nbsp;Period <?php echo $i ?></strong><input type="hidden" name="PeriodNumber<?php echo $i ?>" value="<?php echo $i ?>" /></td>
                        <td bgcolor="#FFFFFF"><?php //echo $i ?><input type="hidden" name="" value="" /> 
                         <?php 
						    $valueField=$i;
							$thisSubCode=$newArraySuffled[$valueField];
							$FieldID="TT".$i; 
							
							//echo $subjectCode=getSubjectCodeOnly($SchoolID,$GradeID,$ClassID,$FieldID);
							$subjectCode=$thisSubCode;
							$subjectCodeLoad="";
							/* if(!in_array($subjectCode,$subjectFirst)){
								$subjectFirst[]=$subjectCode;
								$subjectCodeLoad=$subjectCode;
							} */
							
							//if($subjectCodeLoad!='')echo "<img src=\"../cms/images/star.png\" width=\"10\" height=\"10\" alt=\"timetable\" /> ";		
							$TeacherIDAlready=getTeacherNIC($SchoolID,$GradeID,$ClassID,$subjectCode);
							$countTotal="SELECT * FROM TG_SchoolTimeTable where SchoolID='$SchoolID' and FieldID='$FieldID' and TeacherID='$TeacherIDAlready' and ClassID!='$ClassID'";//GradeID!='$GradeID' and 
							$TotaRows=$db->rowCount($countTotal);
							$sqlGrp1="SELECT ClassGrouped,SubjectID FROM TG_SchoolSubjectGroup where SchoolID='$SchoolID' and GradeID='$GradeID' and SubjectID='$subjectCode' and ClassGrouped LIKE ('%,$ClassID,%')";
							$TotaRows1=$db->rowCount($sqlGrp1);
							if($TotaRows>=1){
								if($TotaRows1>0){
									$dropClass="select2a_group";
								}else{
									$dropClass="select2a_red_teacher";	
								}
							}else{ 
								$dropClass="select2a_new";
							}
		
					//echo $dropClass;
						   ?>
                            <select name="<?php echo $FieldID; ?>" class="<?php echo $dropClass ?>" id="<?php echo $FieldID ?>" onchange="checkGroupSub(<?php echo $totalRows?>,this.options[this.selectedIndex].value,'<?php echo $SchoolID ?>','<?php echo $GradeID ?>','<?php echo $ClassID ?>','<?php echo $valueField ?>','<?php echo $FieldID ?>','<?php echo $PeriodNumberM ?>','<?php echo $subjectCodeLoad ?>','Monday')">
                                <option value="">-Select-</option>
                                <?php
                               
                                $stmtSub = $db->runMsSqlQuery($sqlSubject);
                                
                                while ($row = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
                                   $SubjectID=trim($row['SubjectID']);
                                   $SubjectName=$row['SubjectName'];
								   
								   $teacheName=getTeacherName($SchoolID,$GradeID,$ClassID,$SubjectID);
                                   $selectedOk="";
                                   if($SubjectID==$thisSubCode)$selectedOk="selected";
                                   echo "<option value=\"$SubjectID\" $selectedOk>$teacheName [$SubjectName - $SubjectID]</option>";
                                }
                                ?>
                        </select>
                        <div id="txt_group_TT_<?php echo $valueField ?>">
                       
                        <?php //echo $thisSubCode;
						    $groupCheck=getGroupSubjects($SchoolID,$GradeID,$thisSubCode);
							
							$groupCode=explode(",",$groupCheck);
								for($p=0;$p<count($groupCode);$p++){
									$codeGroup=trim($groupCode[$p]);
									if($codeGroup!=''){
										$groubSub="TT".$valueField."_".$p;
										$teacheName=getTeacherName($SchoolID,$GradeID,$ClassID,$codeGroup);
										$groupCubName=getSubjectNameCommon($codeGroup);
										//echo "$teacheName [$groupCubName]";
									//echo "<input type=\"hidden\" id=\"$groubSub\" name=\"$groubSub\" value=\"$codeGroup\">";
							echo "<br>";		
						?>
                        <select name="<?php echo $groubSub ?>" class="select2a_group" id="<?php echo $groubSub ?>" onchange="checkOverload(<?php echo $totalRows?>)">
                        <option value="<?php echo $codeGroup ?>"><?php echo "$teacheName [$groupCubName]";?></option>
                        </select>
                        <?php }
								} ?>
                        </div>
                        </td>
                        <td bgcolor="#FFFFFF"><?php 
						$valueField=$i+1*$NumberOfPeriods;
						$FieldID="TT".$valueField;
						$thisSubCode=$newArraySuffled[$valueField];
						
						$subjectCode=$thisSubCode;
						$subjectCodeLoad="";
						/* if(!in_array($subjectCode,$subjectFirst)){
							$subjectFirst[]=$subjectCode;
							$subjectCodeLoad=$subjectCode;
						} */
						
						///if($subjectCodeLoad!='')echo "<img src=\"../cms/images/star.png\" width=\"10\" height=\"10\" alt=\"timetable\" /> ";
						$TeacherIDAlready=getTeacherNIC($SchoolID,$GradeID,$ClassID,$subjectCode);
							$countTotal="SELECT * FROM TG_SchoolTimeTable where SchoolID='$SchoolID' and FieldID='$FieldID' and TeacherID='$TeacherIDAlready' and ClassID!='$ClassID'";//and GradeID!='$GradeID'
							$TotaRows=$db->rowCount($countTotal);
							
							$sqlGrp2="SELECT ClassGrouped,SubjectID FROM TG_SchoolSubjectGroup where SchoolID='$SchoolID' and GradeID='$GradeID' and SubjectID='$subjectCode' and ClassGrouped LIKE ('%,$ClassID,%')";
							$TotaRows2=$db->rowCount($sqlGrp2);
							if($TotaRows>=1){
								if($TotaRows2>0){
									$dropClass="select2a_group";
								}else{
									$dropClass="select2a_red_teacher";	
								}
							}else{ 
								$dropClass="select2a_new";
							}
							
						 ?>
                            <select name="<?php echo $FieldID; ?>" class="<?php echo $dropClass ?>" id="<?php echo $FieldID; ?>" onchange="checkGroupSub(<?php echo $totalRows?>,this.options[this.selectedIndex].value,'<?php echo $SchoolID ?>','<?php echo $GradeID ?>','<?php echo $ClassID ?>','<?php echo $valueField ?>','<?php echo $FieldID ?>','<?php echo $PeriodNumberM ?>','<?php echo $subjectCodeLoad ?>','Tuesday')">
                         <option value="">-Select-</option>
                                <?php
                                $stmtSub = $db->runMsSqlQuery($sqlSubject);
                                while ($row = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
                                   $SubjectID=trim($row['SubjectID']);
                                   $SubjectName=$row['SubjectName'];
								   
								   $teacheName=getTeacherName($SchoolID,$GradeID,$ClassID,$SubjectID);
                                   $selectedOk="";
                                   if($SubjectID==$thisSubCode)$selectedOk="selected";
                                   echo "<option value=\"$SubjectID\" $selectedOk>$teacheName [$SubjectName - $SubjectID]</option>";
								   
                                }
                                ?>
                            </select>
                            <div id="txt_group_TT_<?php echo $valueField ?>">
                       
                        <?php //echo $thisSubCode;
						    $groupCheck=getGroupSubjects($SchoolID,$GradeID,$thisSubCode);
							
							$groupCode=explode(",",$groupCheck);
								for($p=0;$p<count($groupCode);$p++){
									$codeGroup=trim($groupCode[$p]);
									if($codeGroup!=''){
										$groubSub="TT".$valueField."_".$p;
										$teacheName=getTeacherName($SchoolID,$GradeID,$ClassID,$codeGroup);
										$groupCubName=getSubjectNameCommon($codeGroup);
										//echo "$teacheName [$groupCubName]";
									//echo "<input type=\"hidden\" id=\"$groubSub\" name=\"$groubSub\" value=\"$codeGroup\">";
							echo "<br>";		
						?>
                        <select name="<?php echo $groubSub ?>" class="select2a_group" id="<?php echo $groubSub ?>" onchange="checkOverload(<?php echo $totalRows?>)">
                        <option value="<?php echo $codeGroup ?>"><?php echo "$teacheName [$groupCubName]";?></option>
                        </select>
                        <?php }
								} ?>
                        </div>
                            <?php 
						    /* $groupCheck=getGroupSubjects($SchoolID,$GradeID,$thisSubCode);
							$groupCode=explode(",",$groupCheck);
								for($p=0;$p<count($groupCode);$p++){
									$codeGroup=$groupCode[$p];
									if($codeGroup!=''){
										$groubSub="TT".$valueField."_".$p;
										echo "<br>*";echo $groupCubName=getSubjectNameCommon($codeGroup);
									echo "<input type=\"hidden\" id=\"$groubSub\" name=\"$groubSub\" value=\"$codeGroup\">";
									}
								} */
						?></td>
                        <td bgcolor="#FFFFFF"><?php //echo $i+2*$NumberOfPeriods;
						$valueField=$i+2*$NumberOfPeriods;
						$thisSubCode=$newArraySuffled[$valueField];
						
						$FieldID="TT".$valueField;
						$subjectCode=$thisSubCode;
						$subjectCodeLoad="";
						/* if(!in_array($subjectCode,$subjectFirst)){
							$subjectFirst[]=$subjectCode;
							$subjectCodeLoad=$subjectCode;
						} */
						
						//if($subjectCodeLoad!='')echo "<img src=\"../cms/images/star.png\" width=\"10\" height=\"10\" alt=\"timetable\" /> ";
						$TeacherIDAlready=getTeacherNIC($SchoolID,$GradeID,$ClassID,$subjectCode);
							$countTotal="SELECT * FROM TG_SchoolTimeTable where SchoolID='$SchoolID' and FieldID='$FieldID' and TeacherID='$TeacherIDAlready' and ClassID!='$ClassID'";// and GradeID!='$GradeID'
							$TotaRows=$db->rowCount($countTotal);
							$sqlGrp3="SELECT ClassGrouped,SubjectID FROM TG_SchoolSubjectGroup where SchoolID='$SchoolID' and GradeID='$GradeID' and SubjectID='$subjectCode' and ClassGrouped LIKE ('%,$ClassID,%')";
							$TotaRows3=$db->rowCount($sqlGrp3);
							if($TotaRows>=1){
								if($TotaRows3>0){
									$dropClass="select2a_group";
								}else{
									$dropClass="select2a_red_teacher";	
								}
							}else{ 
								$dropClass="select2a_new";
							}
							
						 ?>
                            <select name="<?php echo $FieldID; ?>" class="<?php echo $dropClass ?>" id="<?php echo $FieldID; ?>" onchange="checkGroupSub(<?php echo $totalRows?>,this.options[this.selectedIndex].value,'<?php echo $SchoolID ?>','<?php echo $GradeID ?>','<?php echo $ClassID ?>','<?php echo $valueField ?>','<?php echo $FieldID ?>','<?php echo $PeriodNumberM ?>','<?php echo $subjectCodeLoad ?>','Wednesday')">
                         <option value="">-Select-</option>
                                <?php
                                $stmtSub = $db->runMsSqlQuery($sqlSubject);
                               
                                while ($row = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
                                   $SubjectID=trim($row['SubjectID']);
                                   $SubjectName=$row['SubjectName'];
                                   $selectedOk="";
								   
                                   $teacheName=getTeacherName($SchoolID,$GradeID,$ClassID,$SubjectID);
                                   $selectedOk="";
                                   if($SubjectID==$thisSubCode)$selectedOk="selected";
                                   echo "<option value=\"$SubjectID\" $selectedOk>$teacheName [$SubjectName - $SubjectID]</option>";
                                }
                                ?>
                            </select>
                            <div id="txt_group_TT_<?php echo $valueField ?>">
                       
                        <?php //echo $thisSubCode;
						    $groupCheck=getGroupSubjects($SchoolID,$GradeID,$thisSubCode);
							
							$groupCode=explode(",",$groupCheck);
								for($p=0;$p<count($groupCode);$p++){
									$codeGroup=trim($groupCode[$p]);
									if($codeGroup!=''){
										$groubSub="TT".$valueField."_".$p;
										$teacheName=getTeacherName($SchoolID,$GradeID,$ClassID,$codeGroup);
										$groupCubName=getSubjectNameCommon($codeGroup);
										//echo "$teacheName [$groupCubName]";
									//echo "<input type=\"hidden\" id=\"$groubSub\" name=\"$groubSub\" value=\"$codeGroup\">";
							echo "<br>";		
						?>
                        <select name="<?php echo $groubSub ?>" class="select2a_group" id="<?php echo $groubSub ?>" onchange="checkOverload(<?php echo $totalRows?>)">
                        <option value="<?php echo $codeGroup ?>"><?php echo "$teacheName [$groupCubName]";?></option>
                        </select>
                        <?php }
								} ?>
                        </div>
                            <?php 
						    /* $groupCheck=getGroupSubjects($SchoolID,$GradeID,$thisSubCode);
							$groupCode=explode(",",$groupCheck);
								for($p=0;$p<count($groupCode);$p++){
									$codeGroup=$groupCode[$p];
									if($codeGroup!=''){
										$groubSub="TT".$valueField."_".$p;
										echo "<br>*";echo $groupCubName=getSubjectNameCommon($codeGroup);
									echo "<input type=\"hidden\" id=\"$groubSub\" name=\"$groubSub\" value=\"$codeGroup\">";
									}
								} */
						?></td>
                        <td bgcolor="#FFFFFF"><?php //echo $i+3*$NumberOfPeriods; 
						 $valueField=$i+3*$NumberOfPeriods;
						 $thisSubCode=$newArraySuffled[$valueField];
						 
						 $FieldID="TT".$valueField;
						 $subjectCode=$thisSubCode;
						 $subjectCodeLoad="";
						 /* if(!in_array($subjectCode,$subjectFirst)){
							$subjectFirst[]=$subjectCode;
							$subjectCodeLoad=$subjectCode;
						 } */
						
						 //if($subjectCodeLoad!='')echo "<img src=\"../cms/images/star.png\" width=\"10\" height=\"10\" alt=\"timetable\" /> ";
						$TeacherIDAlready=getTeacherNIC($SchoolID,$GradeID,$ClassID,$subjectCode);
							$countTotal="SELECT * FROM TG_SchoolTimeTable where SchoolID='$SchoolID' and FieldID='$FieldID' and TeacherID='$TeacherIDAlready' and ClassID!='$ClassID'";// and GradeID!='$GradeID'
							$TotaRows=$db->rowCount($countTotal);
							$sqlGrp4="SELECT ClassGrouped,SubjectID FROM TG_SchoolSubjectGroup where SchoolID='$SchoolID' and GradeID='$GradeID' and SubjectID='$subjectCode' and ClassGrouped LIKE ('%,$ClassID,%')";
							$TotaRows4=$db->rowCount($sqlGrp4);
							if($TotaRows>=1){
								if($TotaRows4>0){
									$dropClass="select2a_group";
								}else{
									$dropClass="select2a_red_teacher";	
								}
							}else{ 
								$dropClass="select2a_new";
							}
						?>
                            <select name="<?php echo $FieldID; ?>" class="<?php echo $dropClass ?>" id="<?php echo $FieldID; ?>" onchange="checkGroupSub(<?php echo $totalRows?>,this.options[this.selectedIndex].value,'<?php echo $SchoolID ?>','<?php echo $GradeID ?>','<?php echo $ClassID ?>','<?php echo $valueField ?>','<?php echo $FieldID ?>','<?php echo $PeriodNumberM ?>','<?php echo $subjectCodeLoad ?>','Thursday')">
                         <option value="">-Select-</option>
                                <?php
                                
                                $stmtSub = $db->runMsSqlQuery($sqlSubject);
                                while ($row = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
                                   $SubjectID=trim($row['SubjectID']);
                                   $SubjectName=$row['SubjectName'];
								   
                                   $teacheName=getTeacherName($SchoolID,$GradeID,$ClassID,$SubjectID);
                                   $selectedOk="";
                                   if($SubjectID==$thisSubCode)$selectedOk="selected";
                                   echo "<option value=\"$SubjectID\" $selectedOk>$teacheName [$SubjectName - $SubjectID]</option>";
                                }
                                ?>
                            </select>
                            <div id="txt_group_TT_<?php echo $valueField ?>">
                       
                        <?php //echo $thisSubCode;
						    $groupCheck=getGroupSubjects($SchoolID,$GradeID,$thisSubCode);
							
							$groupCode=explode(",",$groupCheck);
								for($p=0;$p<count($groupCode);$p++){
									$codeGroup=trim($groupCode[$p]);
									if($codeGroup!=''){
										$groubSub="TT".$valueField."_".$p;
										$teacheName=getTeacherName($SchoolID,$GradeID,$ClassID,$codeGroup);
										$groupCubName=getSubjectNameCommon($codeGroup);
										//echo "$teacheName [$groupCubName]";
									//echo "<input type=\"hidden\" id=\"$groubSub\" name=\"$groubSub\" value=\"$codeGroup\">";
							echo "<br>";		
						?>
                        <select name="<?php echo $groubSub ?>" class="select2a_group" id="<?php echo $groubSub ?>" onchange="checkOverload(<?php echo $totalRows?>)">
                        <option value="<?php echo $codeGroup ?>"><?php echo "$teacheName [$groupCubName]";?></option>
                        </select>
                        <?php }
								} ?>
                      </div>
                            <?php 
						    /* $groupCheck=getGroupSubjects($SchoolID,$GradeID,$thisSubCode);
							$groupCode=explode(",",$groupCheck);
								for($p=0;$p<count($groupCode);$p++){
									$codeGroup=$groupCode[$p];
									if($codeGroup!=''){
										$groubSub="TT".$valueField."_".$p;
										echo "<br>*";echo $groupCubName=getSubjectNameCommon($codeGroup);
									echo "<input type=\"hidden\" id=\"$groubSub\" name=\"$groubSub\" value=\"$codeGroup\">";
									}
								} */
						?></td>
                        <td bgcolor="#FFFFFF"><?php //echo $i+4*$NumberOfPeriods;
						$valueField=$i+4*$NumberOfPeriods;
						$thisSubCode=$newArraySuffled[$valueField];
						
						$FieldID="TT".$valueField;
						$subjectCode=$thisSubCode;
						$subjectCodeLoad="";
						/* if(!in_array($subjectCode,$subjectFirst)){
							$subjectFirst[]=$subjectCode;
							$subjectCodeLoad=$subjectCode;
						} */
						
						//if($subjectCodeLoad!='')echo "<img src=\"../cms/images/star.png\" width=\"10\" height=\"10\" alt=\"timetable\" /> ";
						$TeacherIDAlready=getTeacherNIC($SchoolID,$GradeID,$ClassID,$subjectCode);
							$countTotal="SELECT * FROM TG_SchoolTimeTable where SchoolID='$SchoolID' and FieldID='$FieldID' and TeacherID='$TeacherIDAlready' and ClassID!='$ClassID'";// and GradeID!='$GradeID'
							$TotaRows=$db->rowCount($countTotal);
							$sqlGrp5="SELECT ClassGrouped,SubjectID FROM TG_SchoolSubjectGroup where SchoolID='$SchoolID' and GradeID='$GradeID' and SubjectID='$subjectCode' and ClassGrouped LIKE ('%,$ClassID,%')";
							$TotaRows5=$db->rowCount($sqlGrp5);
							if($TotaRows>=1){
								if($TotaRows5>0){
									$dropClass="select2a_group";
								}else{
									$dropClass="select2a_red_teacher";	
								}
							}else{ 
								$dropClass="select2a_new";
							}
						 ?>
                            <select name="<?php echo $FieldID; ?>" class="<?php echo $dropClass ?>" id="<?php echo $FieldID; ?>" onchange="checkGroupSub(<?php echo $totalRows?>,this.options[this.selectedIndex].value,'<?php echo $SchoolID ?>','<?php echo $GradeID ?>','<?php echo $ClassID ?>','<?php echo $valueField ?>','<?php echo $FieldID ?>','<?php echo $PeriodNumberM ?>','<?php echo $subjectCodeLoad ?>','Friday')">
                         <option value="">-Select-</option>
                                <?php
                                
                                $stmtSub = $db->runMsSqlQuery($sqlSubject);
                                while ($row = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
                                   $SubjectID=trim($row['SubjectID']);
                                   $SubjectName=$row['SubjectName'];
								   
                                   $teacheName=getTeacherName($SchoolID,$GradeID,$ClassID,$SubjectID);
                                   $selectedOk="";
                                   if($SubjectID==$thisSubCode)$selectedOk="selected";
                                   echo "<option value=\"$SubjectID\" $selectedOk>$teacheName [$SubjectName - $SubjectID]</option>";
                                }
                                ?>
                            </select>
                            <div id="txt_group_TT_<?php echo $valueField ?>">
                       
                        <?php //echo $thisSubCode;
						    $groupCheck=getGroupSubjects($SchoolID,$GradeID,$thisSubCode);
							
							$groupCode=explode(",",$groupCheck);
								for($p=0;$p<count($groupCode);$p++){
									$codeGroup=trim($groupCode[$p]);
									if($codeGroup!=''){
										$groubSub="TT".$valueField."_".$p;
										$teacheName=getTeacherName($SchoolID,$GradeID,$ClassID,$codeGroup);
										$groupCubName=getSubjectNameCommon($codeGroup);
										//echo "$teacheName [$groupCubName]";
									//echo "<input type=\"hidden\" id=\"$groubSub\" name=\"$groubSub\" value=\"$codeGroup\">";
							echo "<br>";		
						?>
                        <select name="<?php echo $groubSub ?>" class="select2a_group" id="<?php echo $groubSub ?>" onchange="checkOverload(<?php echo $totalRows?>)">
                        <option value="<?php echo $codeGroup ?>"><?php echo "$teacheName [$groupCubName]";?></option>
                        </select>
                        <?php }
								} ?>
                      </div>
                            <?php 
						    /* $groupCheck=getGroupSubjects($SchoolID,$GradeID,$thisSubCode);
							$groupCode=explode(",",$groupCheck);
								for($p=0;$p<count($groupCode);$p++){
									$codeGroup=$groupCode[$p];
									if($codeGroup!=''){
										$groubSub="TT".$valueField."_".$p;
										echo "<br>*";echo $groupCubName=getSubjectNameCommon($codeGroup);
									echo "<input type=\"hidden\" id=\"$groubSub\" name=\"$groubSub\" value=\"$codeGroup\">";
									}
								} */
						?>
                        </td>
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
</div>