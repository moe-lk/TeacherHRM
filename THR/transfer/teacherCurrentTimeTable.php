<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
if($fm=='' || $fm=='A'){
	
}else{}
	
?>

<table width="945" cellpadding="0" cellspacing="0">
        	  <tr>
        	    <td bgcolor="#EDEEF3"><table width="100%" cellspacing="1" cellpadding="1">
                        <tr>
                          <td width="36%" bgcolor="#CCCCCC"><strong>Subject\Grade</strong></td>
                          <td width="8%" align="center" bgcolor="#CCCCCC"><strong>6</strong></td>
                          <td width="8%" align="center" bgcolor="#CCCCCC"><strong>7</strong></td>
                          <td width="8%" align="center" bgcolor="#CCCCCC"><strong>8</strong></td>
                          <td width="8%" align="center" bgcolor="#CCCCCC"><strong>9</strong></td>
                          <td width="8%" align="center" bgcolor="#CCCCCC"><strong>10</strong></td>
                          <td width="8%" align="center" bgcolor="#CCCCCC"><strong>11</strong></td>
                          <td width="8%" align="center" bgcolor="#CCCCCC"><strong>12</strong></td>
                          <td width="8%" align="center" bgcolor="#CCCCCC"><strong>13</strong></td>
                        </tr>
                        <?php 
						$grandTotal6=$grandTotal7=$grandTotal8=$grandTotal9=$grandTotal10=$grandTotal11=$grandTotal12=$grandTotal13=0;
  						$sqlTeachSub = "SELECT [SubjectID] FROM [MOENational].[dbo].[TG_SchoolTimeTable] where TeacherID='$NICUser' and SchoolID='$loggedSchool' GROUP BY SubjectID";
						$stmtAdd = $db->runMsSqlQuery($sqlTeachSub);
						while ($row = sqlsrv_fetch_array($stmtAdd, SQLSRV_FETCH_ASSOC)) {
							$SubjectID=$row['SubjectID'];
							
						$sqlSub = "SELECT SubjectName FROM CD_Subject where SubCode='$SubjectID'";
						$stmtSub = $db->runMsSqlQuery($sqlSub);
						while ($rowSub = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
							$SubjectName=$rowSub['SubjectName'];
						}
							
						?>
                        <tr>
                          <td bgcolor="#FFFFFF"><?php echo $SubjectName ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NICUser' and TG_SchoolTimeTable.SchoolID='$loggedSchool' and TG_SchoolGrade.GradeTitle='6' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal6+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NICUser' and TG_SchoolTimeTable.SchoolID='$loggedSchool' and TG_SchoolGrade.GradeTitle='7' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal7+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NICUser' and TG_SchoolTimeTable.SchoolID='$loggedSchool' and TG_SchoolGrade.GradeTitle='8' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal8+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NICUser' and TG_SchoolTimeTable.SchoolID='$loggedSchool' and TG_SchoolGrade.GradeTitle='9' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal9+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NICUser' and TG_SchoolTimeTable.SchoolID='$loggedSchool' and TG_SchoolGrade.GradeTitle='10' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal10+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NICUser' and TG_SchoolTimeTable.SchoolID='$loggedSchool' and TG_SchoolGrade.GradeTitle='11' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal11+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NICUser' and TG_SchoolTimeTable.SchoolID='$loggedSchool' and TG_SchoolGrade.GradeTitle='12' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal12+=$TotaRows;
						  ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php 
						  $sqlCountSubject="SELECT TG_SchoolTimeTable.ID    
FROM            TG_SchoolGrade INNER JOIN
                         TG_SchoolTimeTable ON TG_SchoolGrade.ID = TG_SchoolTimeTable.GradeID where TG_SchoolTimeTable.TeacherID='$NICUser' and TG_SchoolTimeTable.SchoolID='$loggedSchool' and TG_SchoolGrade.GradeTitle='13' and TG_SchoolTimeTable.SubjectID='$SubjectID'";
						echo $TotaRows=$db->rowCount($sqlCountSubject);
						$grandTotal13+=$TotaRows;
						  ?></td>
                        </tr>
                        <?php }?>
                        <tr>
                          <td bgcolor="#CCCCCC"><strong>Total</strong></td>
                          <td align="center" bgcolor="#CCCCCC"><strong><?php echo $grandTotal6 ?></strong></td>
                          <td align="center" bgcolor="#CCCCCC"><strong><?php echo $grandTotal7 ?></strong></td>
                          <td align="center" bgcolor="#CCCCCC"><strong><?php echo $grandTotal8 ?></strong></td>
                          <td align="center" bgcolor="#CCCCCC"><strong><?php echo $grandTotal9 ?></strong></td>
                          <td align="center" bgcolor="#CCCCCC"><strong><?php echo $grandTotal10 ?></strong></td>
                          <td align="center" bgcolor="#CCCCCC"><strong><?php echo $grandTotal11 ?></strong></td>
                          <td align="center" bgcolor="#CCCCCC"><strong><?php echo $grandTotal12 ?></strong></td>
                          <td align="center" bgcolor="#CCCCCC"><strong><?php echo $grandTotal13 ?></strong></td>
                        </tr>
                </table></td>
      	    </tr>
        	  </table>