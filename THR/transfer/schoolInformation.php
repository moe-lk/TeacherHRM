<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />

<table width="100%" cellspacing="2" cellpadding="2">
                    
                  <tr>
                    <td width="100%" ><strong>Current Working School Details</strong></td>
                  </tr>
                  <tr>
                    <td ><table width="100%" cellspacing="1" cellpadding="1">
                      <?php //if($ApproveProcessOrder=='1'){
						 
					 $countTotal="SELECT        TG_SchoolSummary.ID, TG_SchoolSummary.SchoolID, TG_SchoolSummary.TotalNoofStudents, TG_SchoolSummary.Grade1t5Classes, 
                         TG_SchoolSummary.Grade6t11Classes, TG_SchoolSummary.ScienceClasses, TG_SchoolSummary.CommerceClasses, TG_SchoolSummary.ArtClasses, 
                         TG_SchoolSummary.Grade1t5Students, TG_SchoolSummary.Grade6t11Students, TG_SchoolSummary.ScienceStudents, TG_SchoolSummary.CommerceStudents, 
                         TG_SchoolSummary.ArtStudents, TG_SchoolSummary.GradeFrom, TG_SchoolSummary.GradeTo, CD_CensesNo.InstitutionName
FROM            TG_SchoolSummary INNER JOIN
                         CD_CensesNo ON TG_SchoolSummary.SchoolID = CD_CensesNo.CenCode
WHERE        (TG_SchoolSummary.SchoolID = '$loggedSchool')";

					$stmtTG = $db->runMsSqlQuery($countTotal);
					while ($row = sqlsrv_fetch_array($stmtTG, SQLSRV_FETCH_ASSOC)) {
						$InstitutionName=$row['InstitutionName'];
						$TotalNoofStudents=$row['TotalNoofStudents'];
						$Grade1t5Classes=$row['Grade1t5Classes'];
						$Grade6t11Classes=$row['Grade6t11Classes'];
						$ScienceClasses=$row['ScienceClasses'];
						$CommerceClasses=$row['CommerceClasses'];
						$ArtClasses=$row['ArtClasses'];
						$Grade1t5Students=$row['Grade1t5Students'];
						$Grade6t11Students=$row['Grade6t11Students'];
						$ScienceStudents=$row['ScienceStudents'];
						$CommerceStudents=$row['CommerceStudents'];
						$ArtStudents=$row['ArtStudents'];
						$GradeFrom=$row['GradeFrom'];
						$GradeTo=$row['GradeTo'];
					}
						 ?>
                    <tr>
                      <td width="1%" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                       <td width="20%" height="20" valign="top" bgcolor="#F7E2DD">Grade :</td>
                       <td width="79%" align="left" valign="top" bgcolor="#EDEEF3">From <?php echo $GradeFrom ?> To <?php echo $GradeTo ?></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      <td height="20" valign="top" bgcolor="#F7E2DD">Number of Students :</td>
                      <td align="left" valign="top" bgcolor="#EDEEF3"><?php echo $TotalNoofStudents ?></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      <td height="20" valign="top" bgcolor="#F7E2DD">Teachers Summary :</td>
                      <td align="left" valign="top" bgcolor="#EDEEF3"><table width="100%" cellspacing="1" cellpadding="1">
                        <tr>
                          <td width="46%" bgcolor="#CCCCCC">Type</td>
                          <td width="15%" align="center" bgcolor="#CCCCCC">Need</td>
                          <td width="15%" align="center" bgcolor="#CCCCCC">Available</td>
                          <td width="12%" align="center" bgcolor="#CCCCCC">Less</td>
                          <td width="12%" align="center" bgcolor="#CCCCCC">More</td>
                        </tr>
                        <?php 
						$sqlFA = "SELECT        TG_SchoolTeacherTypeWise.ID, TG_SchoolTeacherTypeWise.SchoolID, TG_SchoolTeacherTypeWise.TeacherNeed, TG_SchoolTeacherTypeWise.TeacherAvailable, 
                         TG_TeachersType.Title
FROM            TG_SchoolTeacherTypeWise INNER JOIN
                         TG_TeachersType ON TG_SchoolTeacherTypeWise.TeacherTypeID = TG_TeachersType.ID
WHERE        (TG_SchoolTeacherTypeWise.SchoolID = '$loggedSchool')";
						$stmtFA = $db->runMsSqlQuery($sqlFA);
						
							while ($rowas = sqlsrv_fetch_array($stmtFA, SQLSRV_FETCH_ASSOC)) {
						//$Expr1=$row['id'];
							$Title=trim($rowas['Title']);
							$TeacherNeed=trim($rowas['TeacherNeed']);
							$TeacherAvailable=trim($rowas['TeacherAvailable']);
							
							$balanceTeachOver=$balanceTeachUnder=0;
					 		$balanceTeachUnder=$TeacherNeed-$TeacherAvailable;
					  		if($balanceTeachOver<0)$balanceTeachOver=$TeacherAvailable-$TeacherNeed;
					  ?>
                        <tr>
                          <td bgcolor="#FFFFFF"><?php echo $Title ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php echo $TeacherNeed ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php echo $TeacherAvailable ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php echo $balanceTeachUnder ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php echo $balanceTeachOver ?></td>
                        </tr>
                        <?php }?>
                      </table></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                      <td height="20" valign="top" bgcolor="#F7E2DD">School Summary :</td>
                      <td align="left" valign="top" bgcolor="#EDEEF3"><table width="100%" cellspacing="1" cellpadding="1">
                        <tr>
                          <td bgcolor="#CCCCCC">Grade</td>
                          <td bgcolor="#CCCCCC">No. of Classes</td>
                          <td bgcolor="#CCCCCC">No. of Students</td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFFFFF">Grade 1-5</td>
                          <td bgcolor="#FFFFFF"><?php echo $Grade1t5Classes ?></td>
                          <td bgcolor="#FFFFFF"><?php echo $Grade1t5Students ?></td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFFFFF">Grade 6-11</td>
                          <td bgcolor="#FFFFFF"><?php echo $Grade6t11Classes ?></td>
                          <td bgcolor="#FFFFFF"><?php echo $Grade6t11Students ?></td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFFFFF">Science</td>
                          <td bgcolor="#FFFFFF"><?php echo $ScienceClasses ?></td>
                          <td bgcolor="#FFFFFF"><?php echo $ScienceStudents ?></td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFFFFF">Commerce</td>
                          <td bgcolor="#FFFFFF"><?php echo $CommerceClasses ?></td>
                          <td bgcolor="#FFFFFF"><?php echo $CommerceStudents ?></td>
                        </tr>
                        <tr>
                          <td bgcolor="#FFFFFF">Art</td>
                          <td bgcolor="#FFFFFF"><?php echo $ArtClasses ?></td>
                          <td bgcolor="#FFFFFF"><?php echo $ArtStudents ?></td>
                        </tr>
                      </table></td>
                    </tr>
                    <?php //}?>
                    </table></td>
                  </tr>
                  <tr>
                    <td >&nbsp;</td>
                  </tr>
          
                    </table>