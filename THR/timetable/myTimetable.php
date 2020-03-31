<?php  
include('myfunction.php');
$sqlSchool="SELECT        InstitutionName
FROM            CD_CensesNo WHERE CenCode='$loggedSchool'";
$stmt = $db->runMsSqlQuery($sqlSchool);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
             $InstitutionName=$row['InstitutionName'];            
        }
		
	$sqlTeacher="SELECT        SurnameWithInitials
FROM           TeacherMast WHERE NIC='$nicNO'";
$stmtd = $db->runMsSqlQuery($sqlTeacher);
        while ($rowd = sqlsrv_fetch_array($stmtd, SQLSRV_FETCH_ASSOC)) {
            $SurnameWithInitials=$rowd['SurnameWithInitials'];            
        }
		
/* 	$sqlNoOfPeriod="SELECT [ID]
      ,[GradeTitle]
      ,[NumberOfPeriods]
  FROM [dbo].[TG_SchoolGrade]
  WHERE ID='$GradeID'";
        $stmt = $db->runMsSqlQuery($sqlNoOfPeriod);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $NumberOfPeriods=$row['NumberOfPeriods'];            
        } */
		$NumberOfPeriods=8;
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
                      <td align="center" style="font-size:14px; text-decoration:underline;">Teacher Timetable - <?php echo date('Y'); ?></td>
                    </tr>
                    <tr>
                      <td style="font-size:14px;"><?php echo $InstitutionName ?></td>
                      </tr>
                    <tr>
                      <td style="font-size:14px;"><?php echo $SurnameWithInitials ?></td>
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
                      <?php //exit();
                      for($i=1;$i<$NumberOfPeriods+1;$i++){
						  $PeriodNumberM=$i%$NumberOfPeriods;
						  if($PeriodNumberM==0)$PeriodNumberM=$NumberOfPeriods;
						  ?>
                      <tr>
                        <td bgcolor="#CCCCCC" style="font-size:12px;"><strong>&nbsp;Period <?php echo $i ?></strong></td>
                        <td height="30" align="center" bgcolor="#FFFFFF">
                    <?php $FieldID="TT".$i;//echo $PeriodNumberM; echo "<br>"; //echo "$SchoolID,$NIC,$PeriodNumberM,'Monday',$FieldID";
                    echo $teachingDetails=getTeachingDetails($SchoolID,$NIC,$PeriodNumberM,'Monday',$FieldID);
						?></td>
                        <td align="center" bgcolor="#FFFFFF">
						<?php $dd=$i+$NumberOfPeriods;$FieldID="TT".$dd;
                    echo $teachingDetails=getTeachingDetails($SchoolID,$NIC,$PeriodNumberM,'Tuesday',$FieldID);
					?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php 
						$dd=$i+$NumberOfPeriods*2;$FieldID="TT".$dd;
                    echo $teachingDetails=getTeachingDetails($SchoolID,$NIC,$PeriodNumberM,'Wednesday',$FieldID);
					 ?></td>
                        <td align="center" bgcolor="#FFFFFF">
						<?php 
						$dd=$i+$NumberOfPeriods*3;$FieldID="TT".$dd;
                    echo $teachingDetails=getTeachingDetails($SchoolID,$NIC,$PeriodNumberM,'Thursday',$FieldID);
					?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php 
						$dd=$i+$NumberOfPeriods*4;$FieldID="TT".$dd;						
                    echo $teachingDetails=getTeachingDetails($SchoolID,$NIC,$PeriodNumberM,'Friday',$FieldID);
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