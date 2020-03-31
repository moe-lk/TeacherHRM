<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$msg="";

?>
<div class="main_content_inner_block">
        <div class="mcib_middle1" style="width: 500px; margin-left: 220px; font-weight: bold;">
        	<form method="post" action="teacherList-16a.html" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                  <td height="30" colspan="2" align="center" style="border-bottom: 1px; border-bottom-style: solid; font-size: 14px;"><strong>Search School</strong></td>
                </tr>
                <tr>
                  <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2" valign="top"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="27%">Province</td>
                      <td width="2%">:</td>
                      <td width="71%"><select class="select2a_n" id="ProCode" name="ProCode" onchange="Javascript:show_district('districtList', this.options[this.selectedIndex].value, '');" <?php echo $disaTxt ?>>
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
                      <td><div id="txt_district"><select class="select2a_n" id="DistrictCode" name="DistrictCode" onchange="Javascript:show_zone('zonelist', this.options[this.selectedIndex].value, '');" <?php echo $disaTxt ?>>
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
                      </select></div></td>
                    </tr>
                    <tr>
                      <td>Zone</td>
                      <td>:</td>
                      <td><div id="txt_zone">
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
                      </div></td>
                    </tr>
                    <tr>
                      <td>Division</td>
                      <td>:</td>
                      <td><div id="txt_division">
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
                      </div></td>
                    </tr>
                    <tr>
                      <td>School</td>
                      <td>:</td>
                      <td><div id="txt_showInstitute">
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
                      </div></td>
                    </tr>
                    <tr>
                      <td>NIC No.</td>
                      <td>:</td>
                      <td><input name="NICNo" type="text" class="input3" id="NICNo" value="<?php echo $NICNo ?>" <?php if($fm=='E'){?>readonly="readonly"<?php }?>/></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                    <td width="50%" valign="top">&nbsp;</td>
                    <td width="50%" valign="top">&nbsp;</td>
                </tr>
            </table>
            </form>
            <script>

    $("#frmSave").submit(function(event) {
        var dialogStatus = false;//NIC, Title, SurnameWithInitials, FullName, ZoneCode
        var InstCode = trim($("#InstCode").val());
      
        //$("#vUserName").attr('class', 'fields_errors');
        if (InstCode == "") {
            $("#InstCode").attr('class', 'input2_error');
            dialogStatus = true;
        }

        if (dialogStatus) {
            $("#dialog").dialog({
                modal: true
            });
            event.preventDefault();
        }

    });

    function numbersonly(e) {
        var unicode = e.charCode ? e.charCode : e.keyCode
        if (unicode != 8) { //if the key isn't the backspace key (which we should allow)
            if (unicode < 48 || unicode > 57) //if not a number
                return false //disable key press
        }
    }

</script>
        </div>
</div>