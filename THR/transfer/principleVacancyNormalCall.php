<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
include_once '../approveProcessfunction.php';
//include('js/common.js.php'); 
include('js/ajaxloadpage.js.php'); 

$cat="principleVacancyNormalCall";
$tblName="TG_PrincipleVacancyNormalMaster";
$TransferType="PVAN";//teacher vacancy national school

$countTotal="SELECT * FROM $tblName";//where SchoolID='$loggedSchool'

$TotaRows=$db->rowCount($countTotal);

if($fm=='V' || $fm=='E'){
	$approvalListSql="SELECT        TG_PrincipleVacancyNormalMaster.ID, TG_PrincipleVacancyNormalMaster.Title, CONVERT(varchar(20),TG_PrincipleVacancyNormalMaster.OpenDate,121) AS OpenDate, CONVERT(varchar(20),TG_PrincipleVacancyNormalMaster.EndDate,121) AS EndDate, 
                         TG_PrincipleVacancyNormalMaster.VacancyDescription, CD_CensesNo.InstitutionName, CD_CensesNo.IsNationalSchool, CD_CensesNo.CenCode
FROM            TG_PrincipleVacancyNormalMaster INNER JOIN
                         CD_CensesNo ON TG_PrincipleVacancyNormalMaster.SchoolID = CD_CensesNo.CenCode where CD_CensesNo.IsNationalSchool='1' and TG_PrincipleVacancyNormalMaster.ID='$id'";

	$stmtApp = $db->runMsSqlQuery($approvalListSql);
	while ($rowApp = sqlsrv_fetch_array($stmtApp, SQLSRV_FETCH_ASSOC)) {
		$RequestID=$rowApp['ID'];
		$Title=$rowApp['Title'];
		$OpenDate=$rowApp['OpenDate'];
		$EndDate=$rowApp['EndDate'];
		$VacancyDescription=$rowApp['VacancyDescription'];
		$CenCodeSchool=trim($rowApp['CenCode']);
	
	}
}

?>


<div class="main_content_inner_block">

    <form method="post" action="save.php" name="frmSaveT" id="frmSaveT" enctype="multipart/form-data" onSubmit="return check_form(frmSaveT);">
        <?php if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
   	  <div class="mcib_middle1">
          <div class="mcib_middle_full">
          <div class="form_error"><?php echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
        </div>
        <?php }?>
        <?php if($id=='' && $fm==''){?>
         <table width="945" cellpadding="0" cellspacing="0">
       
        	<tr>
              <td><?php echo $TotaRows ?> Record(s) found.</td>
                  <td align="right"><a href="<?php echo $ttle ?>-<?php echo $pageid ?>---A.html">Add New</a></td>
           </tr>
			  <tr>
                  <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="2%" height="25" align="center" bgcolor="#999999">#</td>
                      <td width="15%" align="center" bgcolor="#999999">Vacancy</td>
                      <td width="11%" align="center" bgcolor="#999999">Open Date</td>
                      <td width="11%" align="center" bgcolor="#999999">End Date</td>
                      <td width="27%" align="center" bgcolor="#999999">School</td>
                      <td width="21%" align="center" bgcolor="#999999">Description</td>
                      <td width="13%" align="center" bgcolor="#999999">Action</td>
                    </tr>
                    <?php 
					$i=1;
				
					$approvalListSql="SELECT        TG_PrincipleVacancyNormalMaster.ID, TG_PrincipleVacancyNormalMaster.Title, CONVERT(varchar(20),TG_PrincipleVacancyNormalMaster.OpenDate,121) AS OpenDate, CONVERT(varchar(20),TG_PrincipleVacancyNormalMaster.EndDate,121) AS EndDate, 
                         TG_PrincipleVacancyNormalMaster.VacancyDescription, CD_CensesNo.InstitutionName, CD_CensesNo.IsNationalSchool
FROM            TG_PrincipleVacancyNormalMaster INNER JOIN
                         CD_CensesNo ON TG_PrincipleVacancyNormalMaster.SchoolID = CD_CensesNo.CenCode where CD_CensesNo.IsNationalSchool='1'";

					$stmtApp = $db->runMsSqlQuery($approvalListSql);
                     while ($rowApp = sqlsrv_fetch_array($stmtApp, SQLSRV_FETCH_ASSOC)) {
						$RequestID=$rowApp['ID'];
						$VacancyTitle=$rowApp['Title'];
						$VacancyOpenDate=$rowApp['OpenDate'];
						$VacancyEndDate=$rowApp['EndDate'];
						$VacancyDescription=$rowApp['VacancyDescription'];
						$VacancyInstitutionName=$rowApp['InstitutionName'];
						
					
					?>
                    <tr>
                      <td height="20" bgcolor="#FFFFFF"><?php echo $i++; ?></td>
                      <td bgcolor="#FFFFFF"><?php echo $VacancyTitle; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $VacancyOpenDate; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $VacancyEndDate ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $VacancyInstitutionName ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $VacancyDescription ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php if($editBut=='Y'){?><a href="<?php echo $ttle ?>-<?php echo $pageid ?>--<?php echo $RequestID ?>-E.html">Apply&nbsp;|&nbsp;<?php }?><a href="<?php echo $ttle ?>-<?php echo $pageid ?>--<?php echo $RequestID ?>-V.html">View&nbsp;|&nbsp;<a href="javascript:aedWin('<?php echo $RequestID ?>','D','','<?php echo $tblName ?>','<?php echo "$ttle-$pageid.html";?>')">Delete <?php //echo $Expr1 ?></a></td>
                    </tr>
                   <?php }?>
                  </table></td>
          </tr>
         
                <tr>
                  <td width="56%">&nbsp;</td>
                  <td width="44%">&nbsp;</td>
                </tr>
          
              </table>
        <?php }else{?>
        <table width="945" cellpadding="0" cellspacing="0">
        	 
       	  <tr>
        	    <td align="right"><a href="<?php echo $ttle ?>-<?php echo $pageid ?>.html">View List</a></td>
      	    </tr>
        	  <tr>
        	    <td><table width="100%" cellspacing="1" cellpadding="1">
        	      
                    <tr>
                      <td bgcolor="#F7E2DD">Title :</td>
                      <td bgcolor="#EDEEF3"><input name="Title" type="text" class="input2" id="Title" value="<?php echo $Title ?>"/></td>
                    </tr>
                    <tr>
                      <td width="23%" bgcolor="#F7E2DD">School  :</td>
                      <td width="77%" bgcolor="#EDEEF3"><div id="changeSchool"><select class="select2a_n" id="SchoolID" name="SchoolID">
                            <?php
                            $sql = "SELECT [InstType]
      ,[CenCode]
      ,[InstitutionName]
      
  FROM [dbo].[CD_CensesNo]
  Where IsNationalSchool='1'
  order by InstitutionName";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$ex1school=trim($row['CenCode']);
								$sclName=$row['InstitutionName'];
								$selTxt="";
								if($CenCodeSchool==$ex1school)$selTxt="selected";
                                echo "<option value=\"$ex1school\" $selTxt>$sclName</option>";
                            }
                            ?>
                      </select></div></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#F7E2DD">Start Date :</td>
                      <td bgcolor="#EDEEF3"><table width="100%" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="7%"><input name="OpenDate" type="text" class="input3new" id="OpenDate" value="<?php echo $OpenDate; ?>" size="10" style="height:20px; line-height:20px;" readonly/>
                      </td>
                            <td width="93%">
                      <input name="f_trigger_1" type="image" id="f_trigger_1" src="../cms/images/calender_icon.gif" align="top" width="16" height="16"  />
                  <script type="text/javascript">
                            //2005-10-03 11:46:00 
                                Calendar.setup({
                                inputField     :    "OpenDate",      // id of the input field
                                ifFormat       :    "%Y-%m-%d",       // format of the input field
                                showsTime      :    false,            // will display a time selector
                                button         :    "f_trigger_1",   // trigger for the calendar (button ID)
                                singleClick    :    true,           // double-click mode
                                step           :    1                // show all years in drop-down boxes (instead of every other year as default)
                            });
                          </script>
                </td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#F7E2DD">End Date :</td>
                      <td bgcolor="#EDEEF3"><table width="100%" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="7%"><input name="EndDate" type="text" class="input3new" id="EndDate" value="<?php echo $EndDate; ?>" size="10" style="height:20px; line-height:20px;" readonly/>
                      </td>
                            <td width="93%">
                      <input name="f_trigger_2" type="image" id="f_trigger_2" src="../cms/images/calender_icon.gif" align="top" width="16" height="16"  />
                  <script type="text/javascript">
                            //2005-10-03 11:46:00 
                                Calendar.setup({
                                inputField     :    "EndDate",      // id of the input field
                                ifFormat       :    "%Y-%m-%d",       // format of the input field
                                showsTime      :    false,            // will display a time selector
                                button         :    "f_trigger_2",   // trigger for the calendar (button ID)
                                singleClick    :    true,           // double-click mode
                                step           :    1                // show all years in drop-down boxes (instead of every other year as default)
                            });
                          </script>
                </td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#F7E2DD">Description :</td>
                      <td bgcolor="#EDEEF3"><textarea name="VacancyDescription" cols="85" rows="5" class="textarea1auto" id="VacancyDescription"><?php echo $VacancyDescription ?></textarea></td>
                    </tr>
       	        </table></td>
      	    </tr>
        	  <tr>
        	    <td>&nbsp;</td>
      	    </tr>
        	  <tr>
        	    <td><table width="100%" cellspacing="1" cellpadding="1">
        	      <tr>
        	        <td width="23%">&nbsp;</td>
        	        <td width="77%"><input type="hidden" name="cat" value="<?php echo $cat ?>" />
                      <input type="hidden" name="tblName" value="<?php echo $tblName ?>" />
                      <input type="hidden" name="TransferType" value="<?php echo $TransferType ?>" />
                      <input type="hidden" name="AED" value="<?php echo $fm ?>" />
                      <input type="hidden" name="vID" value="<?php echo $id ?>" />
                      <?php if($fm!='V'){?> <input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value=""/><?php }?></td>
      	        </tr>
      	      </table></td>
      	    </tr>
        	  
              </table>
              <?php }?>
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