<!----><link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
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
	$addEdit=$_REQUEST['AED'];
	$InstType=$_REQUEST['InstType'];
	$CenCode=trim($_REQUEST['CenCode']);
	$InstitutionName=$_REQUEST['InstitutionName'];
	$DistrictCode=$_REQUEST['DistrictCode'];
	$ZoneCode=$_REQUEST['ZoneCode'];
	$DivisionCode=$_REQUEST['DivisionCode'];
	$SchoolType=$_REQUEST['SchoolType'];
	$SchoolStatus=$_REQUEST['SchoolStatus'];
	
	$dateU=date('Y-m-d H:i:s');
	if($addEdit=="A")$RecordLog="Add by $NICUser on $dateU";
	if($addEdit=="E")$RecordLog="Edit by $NICUser on $dateU";
	$IsNationalSchool=0;
	if($SchoolType==1)$IsNationalSchool=1;
	
	if ($CenCode == "") {
        $msg.= "Please enter the Code.<br>";
    }
    if ($InstitutionName == "") {
        $msg.= "Please enter the Institution Name.<br>";
    }
	if($msg==''){
		if($addEdit=='A'){
				$countSql="SELECT * FROM CD_CensesNo where CenCode='$CenCode'";
				$isAvailable=$db->rowAvailable($countSql);
				if($isAvailable==1){
					$msg.= "Duplicate Censes Code.<br>";
				}else{
					$queryMainSave = "INSERT INTO CD_CensesNo
					   (InstType,CenCode,InstitutionName,DistrictCode,ZoneCode,DivisionCode,SchoolType,RecordLog,IsNationalSchool,SchoolStatus)
				 VALUES
					   ('$InstType','$CenCode','$InstitutionName','$DistrictCode','$ZoneCode','$DivisionCode','$SchoolType','$RecordLog','$IsNationalSchool','$SchoolStatus')";
					$db->runMsSqlQuery($queryMainSave);	
				}
		}else if($addEdit=='E'){
			$queryMainUpdate = "UPDATE CD_CensesNo SET InstType='$InstType',CenCode='$CenCode',InstitutionName='$InstitutionName',DistrictCode='$DistrictCode',ZoneCode='$ZoneCode',DivisionCode='$DivisionCode',SchoolType='$SchoolType',RecordLog='$RecordLog',IsNationalSchool='$IsNationalSchool',SchoolStatus='$SchoolStatus' WHERE CenCode='$CenCode'";
			   
			$db->runMsSqlQuery($queryMainUpdate);
		}
	}
		
	/* $queryGradeSave="INSERT INTO $tblNam
           (SchoolID,GradeID)
     VALUES
           ('$SchoolID','$GradeID')";
		   
	$countSql="SELECT * FROM $tblNam where SchoolID='$SchoolID' and GradeID='$GradeID'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		$msg="Already exist.";
	}else{ 
		$db->runMsSqlQuery($queryGradeSave);
		$msg="Successfully Updated.";
	} */
	//sqlsrv_query($queryGradeSave);
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
                      <td width="8%">Name :</td>
                      <td width="32%"><input name="InstitutionNameSrc" type="text" class="input2_n" id="InstitutionNameSrc" value="<?php echo $InstitutionNameSrc ?>" style="width:200px;"/></td>
                      <td width="13%"><input name="FrmSrch" type="submit" id="FrmSrch" style="background-image: url(../cms/images/searchN.png); width:84px; height:26px; background-color:transparent; border:none; cursor:pointer;" value="" /></td>
                      <td width="14%" style="padding-top:7px;"><a href="masterFile-11-<?php echo $menu ?>--A.html"><img src="../cms/images/addnew.png" alt="" width="90" height="26" /></a></td>
                      <td width="11%" align="right" valign="middle" style="padding-top:7px;"><a href="masterFile-11-<?php echo $menu ?>.html"><img src="../cms/images/clearN.png" alt="" width="80" height="26" /></a></td>
                    </tr>
                    </table></td>
      </tr>
			  <tr>
			    <td valign="top"><span style="color:#090; font-weight:bold;"><?php if($fm=='A')echo "Insert the data"; if($fm=='E') echo "Modify the existing details";?></span>&nbsp;</td>
      </tr>
      <?php if(isset($_ROST["FrmSrchxxx"])){?>
			  <tr>
			    <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
			      <tr>
			        <td width="20%">Institute Type</td>
			        <td width="1%">:</td>
			        <td width="79%"><select class="select5" id="InstType2" name="InstType2">
			          <option value="">-Select-</option>
			          <option value="SC">School</option>
                      <option value="MOE">Ministry of Education</option>
			          </select></td>
		          </tr>
			      <tr>
			        <td>School Type</td>
			        <td>:</td>
			        <td><select class="select5" id="SchoolType2" name="SchoolType2">
			          <option value="">-Select-</option>
			          <?php
                            $sql = "SELECT ID,Category FROM CD_CensesCategory";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                echo '<option value=' . $row['ID'] . '>' . $row['Category'] . '</option>';
                            }
                            ?>
			          </select></td>
		          </tr>
			      <tr>
			        <td>Code <span class="form_error_sched">*</span></td>
			        <td>:</td>
			        <td><input name="CenCode" type="text" class="input3" id="CenCode" value="<?php echo $CenCode ?>" <?php if($fm=='E'){?>readonly="readonly"<?php }?>/>
			          <input type="hidden" name="cat" value="<?php echo $cat; ?>" />
			          <input type="hidden" name="AED" value="<?php echo $fm; ?>" />
			          <input type="hidden" name="id" value="<?php echo $id; ?>" />
			          <input type="hidden" name="tblName" value="<?php echo $tablename; ?>" />
			          <input type="hidden" name="redirect_page" value="<?php echo $redirect_page ?>" />
			          <input type="hidden" name="vID" value="<?php echo $id; ?>" />
			          <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
			          <input type="hidden" name="mainID" value="<?php echo $primaryid; ?>" /></td>
		          </tr>
			      <tr>
			        <td>Institution Name <span class="form_error_sched">*</span></td>
			        <td>:</td>
			        <td><input name="InstitutionName2" type="text" class="input2" id="InstitutionName2" value="<?php echo $InstitutionName ?>" readonly/></td>
		          </tr>
			      <tr>
			        <td>District</td>
			        <td>:</td>
			        <td><select class="select2a_n" id="DistrictCode2" name="DistrictCode2" onchange="Javascript:show_zone('zonelist', this.options[this.selectedIndex].value, '');">
			          <!--<option value="">School Name</option>-->
			          <?php
                            $sql = "SELECT DistCode,DistName FROM CD_Districts order by DistName asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$DistCoded=trim($row['DistCode']);
								$DistNamed=$row['DistName'];
								$seltebr="";
								if($DistCoded==$DistrictCode){
									$seltebr="selected";
								}
                                echo "<option value=\"$DistCoded\" $seltebr>$DistNamed</option>";
                            }
                            ?>
			          </select></td>
		          </tr>
			      <tr>
			        <td>Zone</td>
			        <td>:</td>
			        <td><div id="txt_zone2">
			          <select class="select2a_n" id="ZoneCode2" name="ZoneCode2" onchange="Javascript:show_division('divisionlst', this.options[this.selectedIndex].value, document.frmSave.DistrictCode.value);">
			            <!--<option value="">School Name</option>-->
			            <?php
                            $sql = "SELECT CenCode,InstitutionName FROM CD_Zone where DistrictCode='$DistrictCode' order by InstitutionName asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$DSCoded=trim($row['CenCode']);
								$DSNamed=$row['InstitutionName'];
								$seltebr="";
								if($DSCoded==$ZoneCode){
									$seltebr="selected";
								}
                                echo "<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
                            }
                            ?>
		              </select>
			          </div></td>
		          </tr>
			      <tr>
			        <td>Division</td>
			        <td>:</td>
			        <td><div id="txt_division">
			          <select class="select2a_n" id="DivisionCode" name="DivisionCode">
			            <!--<option value="">School Name</option>-->
			            <?php
                            $sql = "SELECT CenCode,InstitutionName FROM CD_Division where DistrictCode='$DistrictCode' and ZoneCode='$ZoneCode' order by InstitutionName asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$DSCoded=trim($row['CenCode']);
								$DSNamed=$row['InstitutionName'];
								$seltebr="";
								if($DSCoded==$DivisionCode){
									$seltebr="selected";
								}
                                echo "<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
                            }
                            ?>
		              </select>
			          </div></td>
		          </tr>
			      <tr>
			        <td>Status</td>
			        <td>:</td>
			        <td><select class="select5" id="SchoolStatus" name="SchoolStatus">
			          <option value="Y" <?php if($SchoolStatus=='Y'){?>selected="selected"<?php }?>>Functioning</option>
                      <option value="N" <?php if($SchoolStatus=='N'){?>selected="selected"<?php }?>>Not Functioning</option>
		            </select></td>
		          </tr>
			      <tr>
			        <td>&nbsp;</td>
			        <td>&nbsp;</td>
			        <td><img src="../cms/images/edit.png" width="80" height="26" /></td>
		          </tr>
		        </table></td>
      </tr>
      <?php }?>
			  <tr>
                  <td width="56%" valign="top">
                  <?php if($fm=='E' || $fm=='A'){?>
                  <table width="100%" cellspacing="2" cellpadding="2">
                    <tr>
                      <td width="20%">Institute Type</td>
                      <td width="1%">:</td>
                      <td width="79%"><select class="select5" id="InstType" name="InstType">
                            <option value="">-Select-</option>
                            <option value="CL" <?php if($InstType=='CL') echo "selected";?>>CL</option>
                            <option value="CR" <?php if($InstType=='CR') echo "selected";?>>CR</option>
                            <option value="CS" <?php if($InstType=='CS') echo "selected";?>>CS</option>
                            <option value="DE" <?php if($InstType=='DE') echo "selected";?>>DE</option>
                            <option value="DE" <?php if($InstType=='DI') echo "selected";?>>DI</option>
                            <option value="ED" <?php if($InstType=='ED') echo "selected";?>>ED</option>
                            <option value="EP" <?php if($InstType=='EP') echo "selected";?>>EP</option>
                            <option value="EX" <?php if($InstType=='EX') echo "selected";?>>EX</option>
                            <option value="IT" <?php if($InstType=='IT') echo "selected";?>>IT</option>
                            <option value="ME" <?php if($InstType=='ME') echo "selected";?>>ME</option>
                            <option value="EM" <?php if($InstType=='EM') echo "selected";?>>MOE</option>
                            <option value="NE" <?php if($InstType=='NE') echo "selected";?>>NE</option>
                            <option value="NC" <?php if($InstType=='NC') echo "selected";?>>NC</option>
                            <option value="P" <?php if($InstType=='P') echo "selected";?>>P</option>
                            <option value="PD" <?php if($InstType=='PD') echo "selected";?>>PD</option>
                            <option value="PT" <?php if($InstType=='PT') echo "selected";?>>PT</option>
                            <option value="PV" <?php if($InstType=='PV') echo "selected";?>>PV</option>
                            <option value="RE" <?php if($InstType=='RE') echo "selected";?>>RE</option>
                            <option value="RS" <?php if($InstType=='RS') echo "selected";?>>RS</option>
                            <option value="SC" <?php if($InstType=='SC') echo "selected";?>>SC</option>
                            <option value="TC" <?php if($InstType=='TC') echo "selected";?>>TC</option>
                            <option value="TT" <?php if($InstType=='TT') echo "selected";?>>TT</option>
                            <option value="ZE" <?php if($InstType=='ZE') echo "selected";?>>ZE</option>
                            <option value="ZN" <?php if($InstType=='ZN') echo "selected";?>>ZN</option>
                      </select></td>
                    </tr>
                    <tr>
                      <td>School Type</td>
                      <td>:</td>
                      <td><select class="select5" id="SchoolType" name="SchoolType">
                        <option value="">-Select-</option>
                        <?php
                            $sql = "SELECT ID,Category FROM CD_CensesCategory";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$typID=$row['ID'];
								$Category=$row['Category'];
								$seltebr="";
								if($typID==$SchoolType){
									$seltebr="selected";
								}
                                echo "<option value=\"$typID\" $seltebr>$Category</option>";
                            }
                            ?>
                      </select></td>
                    </tr>
                    <tr>
                      <td>Code <span class="form_error_sched">*</span></td>
                      <td>:</td>
                      <td>
                      <input name="CenCode" type="text" class="input3" id="CenCode" value="<?php echo $CenCode ?>" <?php if($fm=='E'){?>readonly="readonly"<?php }?>/>
                      <input type="hidden" name="cat" value="<?php echo $cat; ?>" />
                      <input type="hidden" name="AED" value="<?php echo $fm; ?>" />
                      <input type="hidden" name="id" value="<?php echo $id; ?>" />
                      <input type="hidden" name="tblName" value="<?php echo $tablename; ?>" />
                      <input type="hidden" name="redirect_page" value="<?php echo $redirect_page ?>" />
                      <input type="hidden" name="vID" value="<?php echo $id; ?>" />
                      <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
                      <input type="hidden" name="mainID" value="<?php echo $primaryid; ?>" /></td>
                    </tr>
                    <tr>
                      <td>Institution Name <span class="form_error_sched">*</span></td>
                      <td>:</td>
                      <td><input name="InstitutionName" type="text" class="input2" id="InstitutionName" value="<?php echo $InstitutionName ?>"/></td>
                    </tr>
                    <tr>
                      <td>District</td>
                      <td>:</td>
                      <td><select class="select2a_n" id="DistrictCode" name="DistrictCode" onchange="Javascript:show_zone('zonelist', this.options[this.selectedIndex].value, '');">
                                  <!--<option value="">School Name</option>-->
                                  <?php
                            $sql = "SELECT DistCode,DistName FROM CD_Districts order by DistName asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$DistCoded=trim($row['DistCode']);
								$DistNamed=$row['DistName'];
								$seltebr="";
								if($DistCoded==$DistrictCode){
									$seltebr="selected";
								}
                                echo "<option value=\"$DistCoded\" $seltebr>$DistNamed</option>";
                            }
                            ?>
                              </select></td>
                    </tr>
                    <tr>
                      <td>Zone</td>
                      <td>:</td>
                      <td><div id="txt_zone"><select class="select2a_n" id="ZoneCode" name="ZoneCode" onchange="Javascript:show_division('divisionlst', this.options[this.selectedIndex].value, document.frmSave.DistrictCode.value);">
                                  <!--<option value="">School Name</option>-->
                                  <?php
                            $sql = "SELECT CenCode,InstitutionName FROM CD_Zone where DistrictCode='$DistrictCode' order by InstitutionName asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$DSCoded=trim($row['CenCode']);
								$DSNamed=$row['InstitutionName'];
								$seltebr="";
								if($DSCoded==$ZoneCode){
									$seltebr="selected";
								}
                                echo "<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
                            }
                            ?>
                              </select></div></td>
                    </tr>
                    <tr>
                      <td>Division</td>
                      <td>:</td>
                      <td><div id="txt_division"><select class="select2a_n" id="DivisionCode" name="DivisionCode">
                                  <!--<option value="">School Name</option>-->
                                  <?php
                            $sql = "SELECT CenCode,InstitutionName FROM CD_Division where DistrictCode='$DistrictCode' and ZoneCode='$ZoneCode' order by InstitutionName asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$DSCoded=trim($row['CenCode']);
								$DSNamed=$row['InstitutionName'];
								$seltebr="";
								if($DSCoded==$DivisionCode){
									$seltebr="selected";
								}
                                echo "<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
                            }
                            ?>
                        </select></div></td>
                    </tr>
                    <tr>
			        <td>Status</td>
			        <td>:</td>
			        <td><select class="select5" id="SchoolStatus" name="SchoolStatus">
			          <option value="Y" <?php if($SchoolStatus=='Y'){?>selected="selected"<?php }?>>Functioning</option>
                      <option value="N" <?php if($SchoolStatus=='N'){?>selected="selected"<?php }?>>Not Functioning</option>
		            </select></td>
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