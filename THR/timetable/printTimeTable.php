<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
include('js/common.js.php'); 
include('js/ajaxloadpage.js.php'); 
include('myfunction.php');
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
                  <td width="59%" valign="top"><img src="../cms/images/class-active.png" width="98" height="26" />&nbsp;<a href="printTimeTableTeacher-8.html"><img src="../cms/images/teacher.jpg" width="98" height="26" /></a></td>
        <td width="41%" valign="top">&nbsp;</td>
          </tr>
                
          <tr>
                    <td colspan="2" bgcolor="#999999"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="4%" bgcolor="#CCCCCC">#</td>
                        <td width="31%" bgcolor="#CCCCCC">Grade</td>
                        <td width="14%" bgcolor="#CCCCCC">Class</td>
                        <td width="32%" bgcolor="#CCCCCC">Teacher incharge</td>
                        <td width="19%" align="center" bgcolor="#CCCCCC">Print</td>
                      </tr>
                      <?php 
					  $i=1;
					  $sqlTT="SELECT        TG_SchoolGradeMaster.SchoolID, TG_SchoolGrade.GradeTitle, TG_SchoolGrade.NumberOfPeriods, TG_SchoolClassStructure.ClassID, 
                         TeacherMast.SurnameWithInitials, TG_SchoolClassStructure.ID AS Expr1, TG_SchoolGradeMaster.ID
FROM            TeacherMast INNER JOIN
                         TG_SchoolClassStructure ON TeacherMast.NIC = TG_SchoolClassStructure.TeacherInChargeID INNER JOIN
                         TG_SchoolGradeMaster INNER JOIN
                         TG_SchoolGrade ON TG_SchoolGradeMaster.GradeID = TG_SchoolGrade.ID ON TG_SchoolClassStructure.GradeID = TG_SchoolGradeMaster.ID where TG_SchoolClassStructure.SchoolID='$loggedSchool' order by TG_SchoolGrade.GradeTitle";
					  $stmt = $db->runMsSqlQuery($sqlTT);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								
								$ClassIDT=$row['Expr1'];
								$GradeIDT=$row['ID'];
						$countTotal="SELECT * FROM TG_SchoolTimeTable where SchoolID='$loggedSchool' and GradeID='$GradeIDT' and ClassID='$ClassIDT'";	
						$TotaRows=$db->rowCount($countTotal);
						//$printTT="Not Generate";
						//$printTT="ok";
					  ?>
                      <tr>
                        <td bgcolor="#FFFFFF"><?php echo $i++; ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['GradeTitle']; ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['ClassID']; ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $row['SurnameWithInitials']; ?></td>
                        <td align="center" bgcolor="#FFFFFF">
                        <?php if($TotaRows>0){?>
                        <a href="printTimeTablePrint.php?ClassIDT=<?php echo $ClassIDT ?>&GradeIDT=<?php echo $GradeIDT ?>&SchoolID=<?php echo $loggedSchool ?>" target="_blank"><!--<img src="../cms/images/print_tt.jpg" width="32" height="32" />-->Print</a>
                        <?php }else{
                        echo "Not Generate";
                        }?></td>
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