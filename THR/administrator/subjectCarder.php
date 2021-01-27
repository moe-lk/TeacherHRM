<!----><link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
include "../db_config/connectionNEW.php";
$msg="";
if(isset($_POST["FrmSrch"])){
	$CenCodeSrc=trim($_REQUEST['CenCodeSrc']);
	$InstitutionNameSrc=$_REQUEST['InstitutionNameSrc'];
	$sqlSrch="SELECT * FROM CD_CensesNo where InstitutionName!=''";  
	if($CenCodeSrc)$sqlSrch.=" and CenCode='$CenCodeSrc'";
	if($InstitutionNameSrc)$sqlSrch.=" and InstitutionName like '%$InstitutionNameSrc%'";
	$stmtP = $db->runMsSqlQuery($sqlSrch);
	$TotaRows=$db->rowCount($sqlSrch);
	//if($TotaRows==0)$fm="A";
	//$rowP = sqlsrv_fetch_array($stmtP, SQLSRV_FETCH_ASSOC);
	 //echo $TotaRows=$db->rowCount($stmtP);echo $sqlSrch;
}
if($fm=='E'){
	$sqlSrch="SELECT * FROM CD_CensesNo where CenCode='$id'";  
	$stmtE= $db->runMsSqlQuery($sqlSrch);
	$rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC);
	$InstType = $rowE['InstType'];
	$CenCode = trim($rowE['CenCode']);
	$InstitutionName = $rowE['InstitutionName'];
	$DistrictCode = trim($rowE['DistrictCode']);
	$ZoneCode = trim($rowE['ZoneCode']);
	$DivisionCode = trim($rowE['DivisionCode']);
	$SchoolType = trim($rowE['SchoolType']);
	$SchoolStatus= trim($rowE['SchoolStatus']);
}

if(isset($_POST["FrmSubmit"])){	
  //echo "hi";
  $CenCode = $_REQUEST['CenCode'];
  $GradeCode = $_REQUEST['SubjCatCode'];
  $SubCode = $_REQUEST['subcode'];
  $medCode = $_REQUEST['Medium'];
  
  $sqlEx = "INSERT INTO [dbo].[ExcessDeficit]([CenCode],[SubCode],[SecCode],[Medium]) VALUES (?, ?, ?, ?)";
  $params1 = array($CenCode, $GradeCode, $SubCode, $medCode);
  $stmt1 = sqlsrv_query( $conn, $sqlEx, $params1 );

  $sqlAv = "INSERT INTO [dbo].[AvailableTeachers]([CenCode],[SubCode],[SecCode],[Medium]) VALUES (?, ?, ?, ?)";
  $params2 = array($CenCode, $GradeCode, $SubCode, $medCode);
  $stmt2 = sqlsrv_query( $conn, $sqlAv, $params2 );

  $sqlapp = "INSERT INTO [dbo].[ApprovedCardre]([CenCode],[SubCode],[SecCode],[Medium]) VALUES (?, ?, ?, ?)";
  $params3 = array($CenCode, $GradeCode, $SubCode, $medCode);
  $stmt3 = sqlsrv_query( $conn, $sqlapp, $params3 );

  if($stmt1 && $stmt2 && $stmt3){
    sqlsrv_commit($conn);
    echo ("<script LANGUAGE='JavaScript'>
    window.alert('Succesfully Updated');
    </script>");
} else {
    sqlsrv_rollback($conn);
    echo "Updates rolled back.<br />";
    echo ("<script LANGUAGE='JavaScript'>
    window.alert('Update Failed!, Please try again.');
    </script>");
}
}

?>
<form method="post" action="<?php echo $ttle ?>-11-<?php echo $menu ?>.html" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if($msg!=''){//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
   	  
  <div class="mcib_middle1" style="width:700px;">
    <div class="mcib_middle_full">
          <div class="form_error"><?php echo $msg; echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
    </div>
    <?php }?>
<table width="100%" cellpadding="0" cellspacing="0">
			  <tr>
			    <td valign="top" style="border-bottom:1px; border-bottom-style:solid;"><table width="100%" cellspacing="2" cellpadding="2">
                    
                    <tr>
                      <td width="7%">Code :</td>
                      <td width="15%"><input name="CenCodeSrc" type="text" class="input3" id="CenCodeSrc" style="width:90px;" value="<?php echo $CenCodeSrc ?>"/></td>
                      <!-- <td width="8%">Name :</td>
                      <td width="32%"><input name="InstitutionNameSrc" type="text" class="input2_n" id="InstitutionNameSrc" value="<?php echo $InstitutionNameSrc ?>" style="width:200px;"/></td> -->
                      <td width="13%"><input name="FrmSrch" type="submit" id="FrmSrch" style="background-image: url(../cms/images/searchN.png); width:84px; height:26px; background-color:transparent; border:none; cursor:pointer;" value="" /></td> 
                      <td width="14%" style="padding-top:7px;"><a href="masterFile-11-<?php echo $menu ?>--A.html"><img src="../cms/images/addnew.png" alt="" width="90" height="26" /></a></td>
                      <td width="11%" align="right" valign="middle" style="padding-top:7px;"><a href="masterFile-11-<?php echo $menu ?>.html"><img src="../cms/images/clearN.png" alt="" width="80" height="26" /></a></td>
                    </tr>
                    </table></td> 
      </tr>
			  <tr>
			    <td valign="top"><span style="color:#090; font-weight:bold;"><?php if($fm=='A')echo "Insert the data"; if($fm=='E') echo "Modify the existing details";?></span>&nbsp;</td>
      </tr>
                  <td width="56%" valign="top">
                  <?php if($fm=='E' || $fm=='A'){?>
                  <table width="100%" cellspacing="2" cellpadding="2">
                    <!-- <tr>
                      <td width="20%">Institute Type</td>
                      <td width="1%">:</td>
                      <td width="79%"><select class="select5" id="InstType" name="InstType">
                            <option value="">-Select-</option>
                            <option value="CL" <?php //if($InstType=='CL') echo "selected";?>>CL</option>
                            <option value="CR" <?php //if($InstType=='CR') echo "selected";?>>CR</option>
                            <option value="CS" <?php //if($InstType=='CS') echo "selected";?>>CS</option>
                            <option value="DE" <?php //if($InstType=='DE') echo "selected";?>>DE</option>
                            <option value="DE" <?php //if($InstType=='DI') echo "selected";?>>DI</option>
                            <option value="ED" <?php //if($InstType=='ED') echo "selected";?>>ED</option>
                            <option value="EP" <?php //if($InstType=='EP') echo "selected";?>>EP</option>
                            <option value="EX" <?php //if($InstType=='EX') echo "selected";?>>EX</option>
                            <option value="IT" <?php //if($InstType=='IT') echo "selected";?>>IT</option>
                            <option value="ME" <?php //if($InstType=='ME') echo "selected";?>>ME</option>
                            <option value="EM" <?php //if($InstType=='EM') echo "selected";?>>MOE</option>
                            <option value="NE" <?php //if($InstType=='NE') echo "selected";?>>NE</option>
                            <option value="NC" <?php //if($InstType=='NC') echo "selected";?>>NC</option>
                            <option value="P" <?php //if($InstType=='P') echo "selected";?>>P</option>
                            <option value="PD" <?php //if($InstType=='PD') echo "selected";?>>PD</option>
                            <option value="PT" <?php //if($InstType=='PT') echo "selected";?>>PT</option>
                            <option value="PV" <?php //if($InstType=='PV') echo "selected";?>>PV</option>
                            <option value="RE" <?php //if($InstType=='RE') echo "selected";?>>RE</option>
                            <option value="RS" <?php //if($InstType=='RS') echo "selected";?>>RS</option>
                            <option value="SC" <?php //if($InstType=='SC') echo "selected";?>>SC</option>
                            <option value="TC" <?php //if($InstType=='TC') echo "selected";?>>TC</option>
                            <option value="TT" <?php //if($InstType=='TT') echo "selected";?>>TT</option>
                            <option value="ZE" <?php //if($InstType=='ZE') echo "selected";?>>ZE</option>
                            <option value="ZN" <?php //if($InstType=='ZN') echo "selected";?>>ZN</option>
                      </select></td>
                    </tr>
                    <tr>
                      <td>School Type</td>
                      <td>:</td>
                      <td><select class="select5" id="SchoolType" name="SchoolType">
                        <option value="">-Select-</option>
                        <?php
                //             $sql = "SELECT ID,Category FROM CD_CensesCategory";
                //             $stmt = $db->runMsSqlQuery($sql);
                //             while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								// $typID=$row['ID'];
								// $Category=$row['Category'];
								// $seltebr="";
								// if($typID==$SchoolType){
								// 	$seltebr="selected";
								// }
                //                 echo "<option value=\"$typID\" $seltebr>$Category</option>";
                //             }
                            ?>
                      </select></td>
                    </tr> -->
                    <tr>
                      <td>Code <span class="form_error_sched">*</span></td>
                      <td>:</td>
                      <td>
                      <input name="CenCode" type="text" class="input3" id="CenCode" value="<?php echo $CenCode ?>" <?php if($fm=='E'){?>readonly="readonly"<?php }?>/>
                      <!-- <input type="hidden" name="cat" value="<?php //echo $cat; ?>" />
                      <input type="hidden" name="AED" value="<?php //echo $fm; ?>" />
                      <input type="hidden" name="id" value="<?php //echo $id; ?>" />
                      <input type="hidden" name="tblName" value="<?php //echo $tablename; ?>" />
                      <input type="hidden" name="redirect_page" value="<?php //echo $redirect_page ?>" />
                      <input type="hidden" name="vID" value="<?php //echo $id; ?>" />
                      <input type="hidden" name="mode" value="<?php //echo $mode; ?>" />
                      <input type="hidden" name="mainID" value="<?php //echo $primaryid; ?>" /></td> -->
                    </tr>
                    
                    <tr>
                    <td>Grade </td>
                    <td>:</td>
                    <td><select class="select2a_n" id="SubjCatCode" name="SubjCatCode">
                      <!--<option value="">School Name</option>-->
                      <?php
                        $sql = "SELECT GradeCode,CategoryName FROM CD_TeachSubCategory";
                        $stmt = $db->runMsSqlQuery($sql);
                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                          $GradeCode=trim($row['GradeCode']);
                          $GradeName=$row['CategoryName'];
                          echo "<option value=\"$GradeCode\" >$GradeName</option>";
                        }
                      ?>
                      </select></td>
                    </tr>
                    <tr>
                      <td>Subject</td>
                      <td>:</td>
                      <td><div id="txt_zone"><select class="select2a_n" id="subcode" name="subcode">
                                  <!--<option value="">School Name</option>-->
                                  <?php
                            $sql = "SELECT Code, SubjectName FROM CD_TeachSubjects";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								              $SubCode=$row['Code'];
                              $SubName=$row['SubjectName'];
                            
                                echo "<option value=\"$SubCode\">$SubName</option>";
                            }
                            ?>
                              </select></div></td>
                    </tr>
                    <tr>
                      <td>Medium</td>
                      <td>:</td>
                      <td><div id="txt_division"><select class="select2a_n" id="Medium" name="Medium">
                                  <!--<option value="">School Name</option>-->
                                  <?php
                            $sql = "SELECT * FROM CD_Medium";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            $medCode=trim($row['Code']);
                            $medium=$row['Medium'];
								
                                echo "<option value=\"$medCode\">$medium</option>";
                            }
                            ?>
                        </select></div></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                    </tr>
                    </table>
                    <?php }?>
        </td>
        </tr>
        <?php if(isset($_POST["FrmSrch"]) and $fm==''){ ?>
                <tr>
                  <td><?php echo $TotaRows ?> Record(s) found.</td>
                </tr>
                <tr>
                    <td bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="7%" height="25" align="center" bgcolor="#999999">#</td>
                        <td width="17%" align="center" bgcolor="#999999">Code</td>
                        <td width="56%" align="center" bgcolor="#999999">Institute Name</td>
                        <td width="20%" align="center" bgcolor="#999999">Modify</td>
                      </tr>
                      <?php 
					  $i=1;
                      while ($rowP = sqlsrv_fetch_array($stmtP, SQLSRV_FETCH_ASSOC)) {
							$CenCode=trim($rowP['CenCode']);
							$InstitutionName=trim($rowP['InstitutionName']);
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $CenCode ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $InstitutionName ?></td>
                        <td bgcolor="#FFFFFF" align="center"><a href="<?php echo "$ttle-$pageid-$menu-$CenCode-E.html";?>">Click</a></td>
                      </tr>
                      <?php }?>
                    </table></td>
          </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
               <?php }?>
          </table>
           </div>
    
    </form>