<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
include('js/common.js.php'); 
include('js/ajaxloadpage.js.php'); 
include('myfunction.php');

$params2 = array(
	array($loggedSchool, SQLSRV_PARAM_IN)
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
                  <td width="59%" valign="top"><a href="printTimeTableClass-7.html"><img src="../cms/images/class.jpg" width="98" height="26" /></a>&nbsp;<img src="../cms/images/teacher-active.png" width="98" height="26" /></td>
        <td width="41%" valign="top">&nbsp;</td>
          </tr>
                
          <tr>
                    <td colspan="2" bgcolor="#999999"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="9%" bgcolor="#CCCCCC">#</td>
                        <td width="57%" bgcolor="#CCCCCC">Teacher</td>
                        <td width="34%" align="center" bgcolor="#CCCCCC">Print</td>
                      </tr>
                      <?php 
					  $i=1;
					 								
						$sql22 = "{call SP_TG_LoadSencesTeachers( ?)}";
						$stmt22 = $db->runMsSqlQuery($sql22, $params2);                          
						while ($row2 = sqlsrv_fetch_array($stmt22, SQLSRV_FETCH_ASSOC)) {
						   $NIC=$row2['NIC'];
						   $TeacherName=$row2['TeacherName'];
						   $SubjectName=$row2['SubjectName'];
						   $TeachingType=$row2['TeachingType'];
						   $selectedOk="";
						                               
						//$printTT="Not Generate";
						//$printTT="ok";
					  ?>
                      <tr>
                        <td bgcolor="#FFFFFF"><?php echo $i++; ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $TeacherName; ?></td>
                        <td align="center" bgcolor="#FFFFFF">
                        <?php //if($TotaRows>0){?>
                        <a href="printTimeTableTeacherPrint.php?TeacherID=<?php echo $NIC ?>" target="_blank"><!--<img src="../cms/images/print_tt.jpg" width="32" height="32" />-->Print</a>
                        <?php //}else{
                        //echo "Not Generate";
                       // }?></td>
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