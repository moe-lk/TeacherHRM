<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
echo md5("HOsd@0117213133");
$msg="";
$tblNam="TG_CoordinatorsList";
$countTotal="SELECT * FROM $tblNam where Location!=''";

if(isset($_POST["FrmSrch"])){
	$NICSrch=$_REQUEST['NICNo'];
	
	$sql = "SELECT        TeacherMast.CurServiceRef, CD_CensesNo.DistrictCode, CD_CensesNo.ZoneCode, CD_CensesNo.DivisionCode, CD_CensesNo.InstitutionName, CD_Districts.ProCode, CD_CensesNo.CenCode
FROM            TeacherMast INNER JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID INNER JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode INNER JOIN
                         CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
WHERE        (TeacherMast.NIC = N'$NICSrch')";
	$stmt = $db->runMsSqlQuery($sql);
	$row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
	$ProCode=trim($row['ProCode']);
	$DistrictCode=trim($row['DistrictCode']);
	$ZoneCode=trim($row['ZoneCode']);
	$DivisionCode=trim($row['DivisionCode']);
	$CenCode=trim($row['CenCode']);

}


if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$Location=$_REQUEST['Location'];
	$Title=$_REQUEST['Title'];
	$NameWithInitials=$_REQUEST['NameWithInitials'];
	$Designation=$_REQUEST['Designation'];
	$Address1=$_REQUEST['Address1'];
	$Address2=$_REQUEST['Address2'];
	$TpNumber=$_REQUEST['TpNumber'];
	$EmailAdd=$_REQUEST['EmailAdd'];
	$OrderNumber=$_REQUEST['OrderNumber'];

	$RecordLog="Initial Record";
	
	if($Location!=''){
		$queryGradeSave="INSERT INTO $tblNam
			   (Location,NameWithInitials,Designation,Title,Address1,Address2,TpNumber,EmailAdd,OrderNumber)
		 VALUES
			   ('$Location','$NameWithInitials','$Designation','$Title','$Address1','$Address2','$TpNumber','$EmailAdd','$OrderNumber')";
			   
			$db->runMsSqlQuery($queryGradeSave);
			//$newID=$db->runMsSqlQueryInsert($queryGradeSave);
			$msg="Successfully Updated.";
	}else{
		$msg="Please enter the Location..";
	}
	//sqlsrv_query($queryGradeSave);
}
$TotaRows=$db->rowCount($countTotal);
?>


<div class="main_content_inner_block">
    <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if($msg!=''){//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
   	  <div class="mcib_middle1">
          <div class="mcib_middle_full">
          <div class="form_error"><?php echo $msg; echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
        </div>
        <?php }?>
        <table width="945" cellpadding="0" cellspacing="0">
			  <tr>
			    <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                    
                    <tr>
                      <td width="19%"><span class="form_error">*</span> Enter The NIC Number :</td>
                      <td width="18%"><input name="NICNo" type="text" class="input3" id="NICNo" value="<?php echo $NICSrch ?>"/></td>
                      <td width="63%"><input name="FrmSrch" type="submit" id="FrmSrch" style="background-image: url(../cms/images/finduser.png); width:158px; height:26px; background-color:transparent; border:none; cursor:pointer;" value="" /></td>
                    </tr>
                    
                </table></td>
	      </tr>
          <tr>
                  <td colspan="2" style="border-bottom:1px; border-bottom-style:solid;"><?php 
				  if($NICSrch!=''){
				  if($CurPassword==''){?><span style="color:#F00; font-weight:bold;">User account doesn't exist. Assign "Access Level" and "Password".</span><?php }else{?><span style="color:#090; font-weight:bold;">User account already exist. You can change "Access Level" and "Password".</span><?php }}?></td>
          </tr>
			  <tr valign="middle">
			    <td width="50%" height="40">Available Details</td>
			    <td width="50%"><strong>Correct Details</strong></td>
	      </tr>
			  <tr>
			    <td align="right" valign="top"><table width="96%" cellspacing="1" cellpadding="1">
			      <tr>
			        <td width="23%">Province</td>
			        <td width="3%">:</td>
			        <td width="74%"><select class="select2a_n" id="ProCode2" name="ProCode2" disabled="disabled">
			          <!--<option value="">Select Province</option>-->
			          <?php
                            $sql = "SELECT ProCode,Province FROM CD_Provinces order by Province asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$DistCoded=trim($row['ProCode']);
								$DistNamed=$row['Province'];
								$seltebr="";
								if($DistCoded==$ProCodex){
									$seltebr="selected";
								}
                                echo "<option value=\"$DistCoded\" $seltebr>$DistNamed</option>";
                            }
                            ?>
			          </select></td>
		          </tr>
			      <tr>
			        <td>District</td>
			        <td>:</td>
			        <td><div id="txt_district2">
			          <select class="select2a_n" id="DistrictCode2" name="DistrictCode2" disabled="disabled">
			            <option value="">District Name</option>
			            <?php
                            $sql = "SELECT DistCode,DistName FROM CD_Districts where ProCode='$ProCodex' order by DistName asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$DistCoded=trim($row['DistCode']);
								$DistNamed=$row['DistName'];
								$seltebr="";
								if($DistCoded==$DistrictCodex){
									$seltebr="selected";
								}
                                echo "<option value=\"$DistCoded\" $seltebr>$DistNamed</option>";
                            }
                            ?>
		              </select>
			          </div></td>
		          </tr>
			      <tr>
			        <td>Zone</td>
			        <td>:</td>
			        <td><div id="txt_zone2">
			          <select class="select2a_n" id="ZoneCode2" name="ZoneCode2" disabled="disabled">
			            <option value="">Zone Name</option>
			            <?php
                            $sql = "SELECT CenCode,InstitutionName FROM CD_Zone where DistrictCode='$DistrictCodex' order by InstitutionName asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$DSCoded=trim($row['CenCode']);
								$DSNamed=$row['InstitutionName'];
								$seltebr="";
								if($DSCoded==$ZoneCodex){
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
			        <td><div id="txt_division2">
			          <select class="select2a_n" id="DivisionCode2" name="DivisionCode2" disabled="disabled">
			            <option value="">Division Name</option>
			            <?php
                            $sql = "SELECT CenCode,InstitutionName FROM CD_Division where DistrictCode='$DistrictCodex' and ZoneCode='$ZoneCodex' order by InstitutionName asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$DSCoded=trim($row['CenCode']);
								$DSNamed=$row['InstitutionName'];
								$seltebr="";
								if($DSCoded==$DivisionCodex){
									$seltebr="selected";
								}
                                echo "<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
                            }
                            ?>
		              </select>
			          </div></td>
		          </tr>
			      <tr>
			        <td>School</td>
			        <td>:</td>
			        <td><div id="txt_showInstitute2">
			          <select class="select2a" id="InstCode2" name="InstCode2" disabled="disabled">
			            <option value="">School Name</option>
			            <?php $DivisionCode="abc";
                            $sql = "SELECT [InstType]
      ,[CenCode]
      ,[InstitutionName]
      ,[DistrictCode]
      ,[RecordLog]
      ,[ZoneCode]
      ,[DivisionCode]
      ,[IsNationalSchool]
      ,[SchoolType]
  FROM [dbo].[CD_CensesNo] where CenCode='$CenCodex'
  order by InstitutionName";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$CenCode=trim($row['CenCode']);
								$InstitutionName=addslashes($row['InstitutionName']);
								$seltebr="";
								if($CenCode==$CenCodex){
									$seltebr="selected";
								}
                                echo "<option value=\"$CenCode\" $seltebr>$InstitutionName $CenCode</option>";
                            }
                            ?>
		              </select>
			          </div></td>
		          </tr>
			      <tr>
			        <td>&nbsp;</td>
			        <td>&nbsp;</td>
			        <td>&nbsp;</td>
		          </tr>
		        </table></td>
			    <td align="right" valign="top"><table width="96%" cellspacing="1" cellpadding="1">
			      <tr>
			        <td width="27%"><strong>Province</strong></td>
			        <td width="2%"><strong>:</strong></td>
			        <td width="71%"><strong>
			          <select class="select2a_n" id="ProCode" name="ProCode" onchange="Javascript:show_district('districtList', this.options[this.selectedIndex].value, '');" <?php echo $disaTxt ?>>
			            <!--<option value="">Select Province</option>-->
			            <?php
                            $sql = "SELECT ProCode,Province FROM CD_Provinces order by Province asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$DistCoded=trim($row['ProCode']);
								$DistNamed=$row['Province'];
								$seltebr="";
								if($DistCoded==$ProCodex){
									$seltebr="selected";
								}
                                echo "<option value=\"$DistCoded\" $seltebr>$DistNamed</option>";
                            }
                            ?>
		            </select>
			        </strong></td>
		          </tr>
			      <tr>
			        <td><strong>District</strong></td>
			        <td><strong>:</strong></td>
			        <td><div id="txt_district">
			          <strong>
			          <select class="select2a_n" id="DistrictCode" name="DistrictCode" onchange="Javascript:show_zone('zonelist', this.options[this.selectedIndex].value, '');" <?php echo $disaTxt ?>>
			            <option value="">District Name</option>
			            <?php
                            $sql = "SELECT DistCode,DistName FROM CD_Districts where ProCode='$ProCodex' order by DistName asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$DistCoded=trim($row['DistCode']);
								$DistNamed=$row['DistName'];
								$seltebr="";
								if($DistCoded==$DistrictCodex){
									$seltebr="selected";
								}
                                echo "<option value=\"$DistCoded\" $seltebr>$DistNamed</option>";
                            }
                            ?>
		              </select>
			          </strong></div></td>
		          </tr>
			      <tr>
			        <td><strong>Zone</strong></td>
			        <td><strong>:</strong></td>
			        <td><div id="txt_zone">
			          <strong>
			          <select class="select2a_n" id="ZoneCode" name="ZoneCode" onchange="Javascript:show_division('divisionList', this.options[this.selectedIndex].value, document.frmSave.DistrictCode.value);" <?php echo $disaTxt ?>>
			            <option value="">Zone Name</option>
			            <?php
                            $sql = "SELECT CenCode,InstitutionName FROM CD_Zone where DistrictCode='$DistrictCodex' order by InstitutionName asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$DSCoded=trim($row['CenCode']);
								$DSNamed=$row['InstitutionName'];
								$seltebr="";
								if($DSCoded==$ZoneCodex){
									$seltebr="selected";
								}
                                echo "<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
                            }
                            ?>
		              </select>
			          </strong></div></td>
		          </tr>
			      <tr>
			        <td><strong>Division</strong></td>
			        <td><strong>:</strong></td>
			        <td><div id="txt_division">
			          <strong>
			          <select class="select2a_n" id="DivisionCode" name="DivisionCode" onchange="Javascript:show_cences('censesList', this.options[this.selectedIndex].value, document.frmSave.DistrictCode.value);" <?php echo $disaTxt ?>>
			            <option value="">Division Name</option>
			            <?php
                            $sql = "SELECT CenCode,InstitutionName FROM CD_Division where DistrictCode='$DistrictCodex' and ZoneCode='$ZoneCodex' order by InstitutionName asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$DSCoded=trim($row['CenCode']);
								$DSNamed=$row['InstitutionName'];
								$seltebr="";
								if($DSCoded==$DivisionCodex){
									$seltebr="selected";
								}
                                echo "<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
                            }
                            ?>
		              </select>
			          </strong></div></td>
		          </tr>
			      <tr>
			        <td><strong>School</strong></td>
			        <td><strong>:</strong></td>
			        <td><div id="txt_showInstitute">
			          <strong>
			          <select class="select2a" id="InstCode" name="InstCode">
			            <option value="">School Name</option>
			            <?php $DivisionCode="abc";
                            $sql = "SELECT [InstType]
      ,[CenCode]
      ,[InstitutionName]
      ,[DistrictCode]
      ,[RecordLog]
      ,[ZoneCode]
      ,[DivisionCode]
      ,[IsNationalSchool]
      ,[SchoolType]
  FROM [dbo].[CD_CensesNo] where CenCode='$CenCodex'
  order by InstitutionName";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$CenCode=trim($row['CenCode']);
								$InstitutionName=addslashes($row['InstitutionName']);
								$seltebr="";
								if($CenCode==$CenCodex){
									$seltebr="selected";
								}
                                echo "<option value=\"$CenCode\" $seltebr>$InstitutionName $CenCode</option>";
                            }
                            ?>
		              </select>
			          </strong></div></td>
		          </tr>
			      <tr>
			        <td>&nbsp;</td>
			        <td>&nbsp;</td>
			        <td><strong>
		            <input name="FrmSubmit2" type="submit" id="FrmSubmit2" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" />
			        </strong></td>
		          </tr>
		        </table></td>
	      </tr>
			  <tr>
			    <td valign="top">&nbsp;</td>
			    <td valign="top">&nbsp;</td>
	      </tr>
              </table>
    </div>
    
    </form>
</div>