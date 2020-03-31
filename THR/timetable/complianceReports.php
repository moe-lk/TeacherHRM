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
        <table width="100%" cellpadding="0" cellspacing="0">
			  <tr>
                  <td width="80%" valign="top">
                  <?php 
				  $cButton="class.jpg";
				  $tButton="teacher.jpg";
				  if($menu=='C'){
					  $cButton="class-active.png";
					  $printBut="<a href=\"complianceReportsClassLoad.php?tpep=Print\" target=\"_blank\"><img src=\"../cms/images/print_tt.jpg\" width=\"32\" height=\"32\" /></a>";
				  }
				  if($menu=='T'){
					  $tButton="teacher-active.png";
					  $printBut="<a href=\"complianceReportsTeacherLoad.php?tpep=Print\" target=\"_blank\"><img src=\"../cms/images/print_tt.jpg\" width=\"32\" height=\"32\" /></a>";
				  }
				  ?>
                  <a href="complianceReportsClassLoad-9-C.html"><img src="../cms/images/<?php echo $cButton ?>" width="98" height="26" /></a>&nbsp;<a href="complianceReportsTeacherLoad-9-T.html"><img src="../cms/images/<?php echo $tButton ?>" width="98" height="26" /></a></td>
        <td width="20%" valign="top"><?php echo $printBut;?></a></td>
          </tr>
                
          <tr>
                    <td colspan="2" >
                    <?php if($menu=='C')include('complianceReportsClassLoad.php');
					if($menu=='T')include('complianceReportsTeacherLoad.php');?>
                    </td>
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