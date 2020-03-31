<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Print Class Timetable</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
</head>

<body onload="javascript:print();">

<?php  
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();
include('js/common.js.php'); 
include('js/ajaxloadpage.js.php'); 
include('myfunction.php');
$SchoolID=$_REQUEST['SchoolID'];
	$ClassID=$_REQUEST['ClassIDT'];
	$GradeID=$_REQUEST['GradeIDT'];
	
	
	/* echo $sqlTTDetails="SELECT        TG_SchoolTimeTable.ID, TG_SchoolTimeTable.SchoolID, CD_CensesNo.InstitutionName, TG_SchoolGrade.GradeTitle, TeacherMast.SurnameWithInitials, 
                         TeacherMast.NIC, TG_SchoolClassStructure.ClassID
FROM            TeacherMast INNER JOIN
                         TG_SchoolClassStructure ON TeacherMast.NIC = TG_SchoolClassStructure.TeacherInChargeID INNER JOIN
                         TG_SchoolTimeTable INNER JOIN
                         CD_CensesNo ON TG_SchoolTimeTable.SchoolID = CD_CensesNo.CenCode INNER JOIN
                         TG_SchoolGrade ON TG_SchoolTimeTable.GradeID = TG_SchoolGrade.ID ON TG_SchoolClassStructure.ID = TG_SchoolTimeTable.ClassID AND 
                         TG_SchoolClassStructure.SchoolID = TG_SchoolTimeTable.SchoolID where TG_SchoolTimeTable.SchoolID='$SchoolID' and TG_SchoolTimeTable.GradeID='$GradeID' and TG_SchoolTimeTable.ClassID='$ClassID'";
		$stmt = $db->runMsSqlQuery($sqlTTDetails);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $InstitutionName=$row['InstitutionName']; 
			$SurnameWithInitials=$row['SurnameWithInitials']; 
			$ClassIDSt=$row['ClassID']; 
			$GradeTitle=$row['GradeTitle'];            
        } */
		
		$sqlTTDetails="SELECT        TG_SchoolClassStructure.ID, TG_SchoolClassStructure.ClassID, TG_SchoolGrade.GradeTitle, TeacherMast.SurnameWithInitials, CD_CensesNo.InstitutionName
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolGradeMaster ON TG_SchoolGrade.ID = TG_SchoolGradeMaster.GradeID INNER JOIN
                         TG_SchoolClassStructure ON TG_SchoolGradeMaster.ID = TG_SchoolClassStructure.GradeID INNER JOIN
                         TeacherMast ON TG_SchoolClassStructure.TeacherInChargeID = TeacherMast.NIC INNER JOIN
                         CD_CensesNo ON TG_SchoolClassStructure.SchoolID = CD_CensesNo.CenCode
WHERE        (TG_SchoolClassStructure.ID = '$ClassID')";

		$stmt = $db->runMsSqlQuery($sqlTTDetails);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $InstitutionName=$row['InstitutionName']; 
			$SurnameWithInitials=$row['SurnameWithInitials']; 
			$ClassIDSt=$row['ClassID']; 
			$GradeTitle=$row['GradeTitle'];            
        }
		
						 
						 
	$sqlNoOfPeriod="SELECT        TG_SchoolGrade.ID, TG_SchoolGrade.GradeTitle, TG_SchoolGrade.NumberOfPeriods, TG_SchoolGradeMaster.ID AS Expr1
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolGradeMaster ON TG_SchoolGrade.ID = TG_SchoolGradeMaster.GradeID
WHERE        (TG_SchoolGradeMaster.ID = '$GradeID')";
        $stmt = $db->runMsSqlQuery($sqlNoOfPeriod);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            echo $NumberOfPeriods=$row['NumberOfPeriods'];            
        }
		?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top"><br />
      <table width="652" border="0" cellspacing="1" cellpadding="0" bgcolor="#666666">
        <tr>
          <td bgcolor="#FFFFFF"><table width="650" border="0" cellspacing="2" cellpadding="0">
            <tr>
              <td bgcolor="#FFFFFF"><table width="648" border="0" cellspacing="2" cellpadding="6" bgcolor="#666666">
                <tr>
                  <td bgcolor="#FFFFFF"><table width="640" border="0" align="center" cellpadding="0" cellspacing="0" style="font-family: Tahoma, Geneva, sans-serif; font-size: 10px; padding: 0; margin: 0;">
                   <tr>
                  <td colspan="2"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td colspan="2" align="center" style="font-size:14px; text-decoration:underline;">Class Timetable - <?php echo date('Y'); ?></td>
                    </tr>
                    <tr>
                      <td colspan="2" style="font-size:14px;">SCHOOL :- <?php echo $InstitutionName ?></td>
                      </tr>
                    <tr>
                      <td width="15%" style="font-size:14px;">GRADE :- <?php echo $GradeTitle ?> - <?php echo $ClassIDSt ?></td>
                      <td width="35%">&nbsp;</td>
                    </tr>
                    <tr>
                      <td colspan="2" style="font-size:14px;">INCHARGE :- <?php echo $SurnameWithInitials ?></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                  
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#999999"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="10%" height="30" bgcolor="#CCCCCC">&nbsp;</td>
                        <td width="19%" align="center" bgcolor="#CCCCCC" style="font-size:12px;"><strong>Monday</strong></td>
                        <td width="19%" align="center" bgcolor="#CCCCCC" style="font-size:12px;"><strong>Tuesday</strong></td>
                        <td width="18%" align="center" bgcolor="#CCCCCC" style="font-size:12px;"><strong>Wednesday</strong></td>
                        <td width="17%" align="center" bgcolor="#CCCCCC" style="font-size:12px;"><strong>Thursday</strong></td>
                        <td width="17%" align="center" bgcolor="#CCCCCC" style="font-size:12px;"><strong>Friday</strong></td>
                      </tr>
                      <?php 
                      for($i=1;$i<$NumberOfPeriods+1;$i++){
						  $PeriodNumberM=$i%$NumberOfPeriods;
						  if($PeriodNumberM==0)$PeriodNumberM=$NumberOfPeriods;
						  ?>
                      <tr>
                        <td bgcolor="#CCCCCC" style="font-size:12px;"><strong>&nbsp;Period <?php echo $i ?></strong></td>
                        <td height="30" align="center" bgcolor="#FFFFFF">
                    <?php $FieldID="TT".$i; 
                    echo $subjectName=getSubjectNameOnly($SchoolID,$GradeID,$ClassID,$FieldID);
						?></td>
                        <td align="center" bgcolor="#FFFFFF">
						<?php $dd=$i+$NumberOfPeriods;$FieldID="TT".$dd;
                    echo $subjectName=getSubjectNameOnly($SchoolID,$GradeID,$ClassID,$FieldID);
					?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php 
						$dd=$i+$NumberOfPeriods*2;$FieldID="TT".$dd;
                    echo $subjectName=getSubjectNameOnly($SchoolID,$GradeID,$ClassID,$FieldID);
					 ?></td>
                        <td align="center" bgcolor="#FFFFFF">
						<?php 
						$dd=$i+$NumberOfPeriods*3;$FieldID="TT".$dd;
                    echo $subjectName=getSubjectNameOnly($SchoolID,$GradeID,$ClassID,$FieldID);
					?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php 
						$dd=$i+$NumberOfPeriods*4;$FieldID="TT".$dd;						
                    echo $subjectName=getSubjectNameOnly($SchoolID,$GradeID,$ClassID,$FieldID);
					 ?></td>
                      </tr>
                      <?php }?>
                  </table></td>
          </tr>
                <tr>
                  <td width="59%">&nbsp;</td>
                  <td width="41%">&nbsp;</td>
                </tr>
               
              
                  </table></td>
                </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
    </table></td>
  </tr>
</table>


</body>
</html>