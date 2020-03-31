<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
$msg = "";
$tblNam = "TG_ApprovalProcessMain";
$countTotal = "SELECT * FROM $tblNam"; //$NICUser
$redirect_page = "approvalProcess-1.html";

//$countSql = "SELECT * FROM $tblNam where ProcessType='$ProcessType' and AccessRoleID='$PositionCode' and Enable = 'Y'";
$isAvailablePerAdd=$isAvailableCurAdd="";
$success="";
if (isset($_POST["FrmSubmit"])) {
    $familiInfoMainStatus = $_REQUEST['familiInfoMainStatus'];
	$curAddStatus = $_REQUEST['curAddStatus'];
	//CivilStatusCode , SpouseNIC, SpouseName, SpouseDOB, SpouseOccupationCode, SpouseOfficeAddr
	//$AddrType = $_REQUEST['AddrType'];
    $CivilStatusCode = $_REQUEST['CivilStatusCode'];
    $SpouseNIC = $_REQUEST['SpouseNIC'];
	$SpouseName = $_REQUEST['SpouseName'];
	$SpouseDOB = $_REQUEST['SpouseDOB'];
	$SpouseOccupationCode = $_REQUEST['SpouseOccupationCode'];
	$SpouseOfficeAddr = $_REQUEST['SpouseOfficeAddr'];
	$AppDate = date('Y-m-d H:i:s');
	$LastUpdate = date('Y-m-d H:i:s');
	//$UpdateBy = $_REQUEST['DSCode'];
	//$RecordLog = $_REQUEST['DSCode'];
	$msg="";
	
	$sqlServiceRef=" SELECT        TeacherMast.CurServiceRef, CD_CensesNo.ZoneCode
FROM            StaffServiceHistory INNER JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode INNER JOIN
                         TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
WHERE        (TeacherMast.NIC = '$NICUser')";
	$stmtCAllready= $db->runMsSqlQuery($sqlServiceRef);
	$rowAllready = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC);
	$CurServiceRef=trim($rowAllready['CurServiceRef']);
	$ZoneCode=trim($rowAllready['ZoneCode']);
	
	
	$sqlCAllready = "SELECT * FROM TG_EmployeeUpdateFamilyInfo WHERE NIC='$NICUser' and IsApproved='N'";
	$stmtCAllready= $db->runMsSqlQuery($sqlCAllready);
	$rowAllready = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC);
	$TeacherMastIDA=trim($rowAllready['TeacherMastID']);
	
    if ($CivilStatusCode == "") {
        $msg.= "Please select the civil status.<br>";
    }
    if ($SpouseNIC == "") {
        $msg.= "Please enter the NIC.<br>";
    }
	if ($SpouseName == "") {
        $msg.= "Please enter the full name.<br>";
    }
	
	if($msg==''){
		if($TeacherMastIDA==''){//$familiInfoMainStatus=='Add'){
			$queryMainSave = "INSERT INTO ArchiveUP_TeacherMast
			   (NIC,CivilStatusCode,SpouseName,SpouseNIC,SpouseOccupationCode,SpouseDOB,SpouseOfficeAddr,LastUpdate,UpdateBy,RecordLog)
		 VALUES
			   ('$NICUser','$CivilStatusCode','$SpouseName','$SpouseNIC','$SpouseOccupationCode','$SpouseDOB','$SpouseOfficeAddr','$LastUpdate','$NICUser','First change')";
            //$db->runMsSqlQuery($queryMainSave);	
			$TeacherMastID=$db->runMsSqlQueryInsert($queryMainSave);
		}else {//if($familiInfoMainStatus=='Update'){
			$queryMainUpdate = "UPDATE ArchiveUP_TeacherMast SET CivilStatusCode='$CivilStatusCode',SpouseName='$SpouseName',SpouseNIC='$SpouseNIC',SpouseOccupationCode='$SpouseOccupationCode',SpouseDOB='$SpouseDOB',SpouseOfficeAddr='$SpouseOfficeAddr',LastUpdate='$LastUpdate',UpdateBy='$NICUser',RecordLog='Edit record' WHERE ID='$TeacherMastIDA'";
			   
            $db->runMsSqlQuery($queryMainUpdate);
			/* $sqlCA = "SELECT ID FROM ArchiveUP_TeacherMast WHERE NIC='$NICUser' and RecStatus='0'";
            $stmtCA = $db->runMsSqlQuery($sqlCA);
            $row = sqlsrv_fetch_array($stmtCA, SQLSRV_FETCH_ASSOC);
			$TeacherMastID=trim($row['ID']); */
			$TeacherMastID=$TeacherMastIDA;
		}
	}
   
	if($msg==''){
		$isAvailable=$db->rowAvailable($sqlCAllready);
		if($isAvailable==1){
			
			$queryMainUpdate = "UPDATE TG_EmployeeUpdateFamilyInfo SET TeacherMastID='$TeacherMastID',dDateTime='$LastUpdate',ZoneCode='$ZoneCode',IsApproved='N',ApproveDate='',ApprovedBy='',UpdateBy='$NICUser' WHERE NIC='$NICUser' and IsApproved='N'";
			$db->runMsSqlQuery($queryMainUpdate);
			
		}else{
			
			$queryRegis = "INSERT INTO TG_EmployeeUpdateFamilyInfo				   (NIC,TeacherMastID,dDateTime,ZoneCode,IsApproved,ApproveDate,ApprovedBy,UpdateBy)
			 VALUES				   
		('$NICUser','$TeacherMastID','$LastUpdate','$ZoneCode','N','','','$NICUser')";
			$db->runMsSqlQuery($queryRegis);
			
		}
		
		$success="Your update request submitted successfully. Data will be displaying after the approvals.";
			
	}
	//exit();
    //sqlsrv_query($queryGradeSave);
}

if($menu=='E' and $success==''){
	$sqlCAllready = "SELECT * FROM TG_EmployeeUpdateFamilyInfo WHERE NIC='$NICUser' and IsApproved='N'";
	$stmtCAllready= $db->runMsSqlQuery($sqlCAllready);
	$rowAllready = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC);
	$TeacherMastIDA=trim($rowAllready['TeacherMastID']);
	
	/* address */
	$familiInfoMainStatus="Update";
	$curAddStatus="Update";
	/* $sqlPerAdd="SELECT    ArchiveUP_StaffAddrHistory.Address, ArchiveUP_StaffAddrHistory.Tel, 
							 CONVERT(varchar(20),ArchiveUP_StaffAddrHistory.AppDate,121) AS AppDate, CD_DSec.DSName, CD_Districts.DistName, CD_DSec.DSCode, CD_Districts.DistCode
	FROM            ArchiveUP_StaffAddrHistory INNER JOIN
							 CD_DSec ON ArchiveUP_StaffAddrHistory.DSCode = CD_DSec.DSCode INNER JOIN
							 CD_Districts ON ArchiveUP_StaffAddrHistory.DISTCode = CD_Districts.DistCode
	WHERE        (ArchiveUP_StaffAddrHistory.NIC = '$NICUser') AND (ArchiveUP_StaffAddrHistory.AddrType = N'PER')";
	
	$isAvailablePerAdd=$db->rowAvailable($sqlPerAdd);
	$resAB = $db->runMsSqlQuery($sqlPerAdd);
	$rowAB = sqlsrv_fetch_array($resAB, SQLSRV_FETCH_ASSOC);
	$Address = $rowAB['Address'];
	$Tel = $rowAB['Tel'];
	$AppDate = $rowAB['AppDate'];
	$DSName = $rowAB['DSName'];
	$DistName = $rowAB['DistName'];
	$DSCode = trim($rowAB['DSCode']);
	$DistCode = trim($rowAB['DistCode']); */
	
	$sqlpMast="SELECT        ArchiveUP_TeacherMast.NIC, ArchiveUP_TeacherMast.CivilStatusCode, ArchiveUP_TeacherMast.SpouseName, ArchiveUP_TeacherMast.SpouseNIC, 
                         ArchiveUP_TeacherMast.SpouseOccupationCode, CONVERT(varchar(20), 
                         ArchiveUP_TeacherMast.SpouseDOB, 121) AS SpouseDOB, ArchiveUP_TeacherMast.SpouseOfficeAddr, CD_Positions.PositionName, 
                         CD_CivilStatus.CivilStatusName
FROM            ArchiveUP_TeacherMast INNER JOIN
                         CD_Positions ON ArchiveUP_TeacherMast.SpouseOccupationCode = CD_Positions.Code INNER JOIN
                         CD_CivilStatus ON ArchiveUP_TeacherMast.CivilStatusCode = CD_CivilStatus.Code
WHERE        (ArchiveUP_TeacherMast.ID = N'$TeacherMastIDA')";// AND (ArchiveUP_TeacherMast.RecStatus = N'0')";//538093300V
	
	$isAvailablePmast=$db->rowAvailable($sqlpMast);
	$resABC = $db->runMsSqlQuery($sqlpMast);
	$rowABC = sqlsrv_fetch_array($resABC, SQLSRV_FETCH_ASSOC);
	$CivilStatusCode = trim($rowABC['CivilStatusCode']);
	$SpouseName = $rowABC['SpouseName'];
	$SpouseNIC = trim($rowABC['SpouseNIC']);
	$SpouseOccupationCode = trim($rowABC['SpouseOccupationCode']);
	$SpouseDOB= $rowABC['SpouseDOB'];
	$SpouseOfficeAddr = $rowABC['SpouseOfficeAddr'];
	$PositionName = $rowABC['PositionName'];
	$CivilStatusName = $rowABC['CivilStatusName'];
}
//echo $isAvailablePmast;
if($isAvailablePmast!=1){
	$familiInfoMainStatus="Add";
	$sqlPers="SELECT        TeacherMast.NIC, TeacherMast.CivilStatusCode, TeacherMast.SpouseName, TeacherMast.SpouseNIC, 
                         TeacherMast.SpouseOccupationCode, CONVERT(varchar(20), 
                         TeacherMast.SpouseDOB, 121) AS SpouseDOB, TeacherMast.SpouseOfficeAddr, CD_Positions.PositionName, 
                         CD_CivilStatus.CivilStatusName
FROM            TeacherMast INNER JOIN
                         CD_Positions ON TeacherMast.SpouseOccupationCode = CD_Positions.Code INNER JOIN
                         CD_CivilStatus ON TeacherMast.CivilStatusCode = CD_CivilStatus.Code
WHERE        TeacherMast.NIC = N'$NICUser'";

	$resA = $db->runMsSqlQuery($sqlPers);
	$rowA = sqlsrv_fetch_array($resA, SQLSRV_FETCH_ASSOC);//print_r($rowA);
	
	$CivilStatusCode = trim($rowA['CivilStatusCode']);
	$SpouseName = $rowA['SpouseName'];
	$SpouseNIC = trim($rowA['SpouseNIC']);
	$SpouseOccupationCode = trim($rowA['SpouseOccupationCode']);
	$SpouseDOB= $rowA['SpouseDOB'];
	$SpouseOfficeAddr = $rowA['SpouseOfficeAddr'];
	$PositionName = $rowA['PositionName'];
	$CivilStatusName = $rowA['CivilStatusName'];
	
}

//$TotaRows = $db->rowCount($countTotal);
?>
<?php if($menu==''){?>
<div class="main_content_inner_block">
        <div class="mcib_middle1">
            <table width="945" cellpadding="0" cellspacing="0">
                <tr>
                  <td valign="top"><span style="color:#090; font-weight:bold;">*If your family data record is inaccurate, you can submit an update request</span></td>
                  <td align="right" valign="top"><a href="familyInfo-2-E.html"><img src="../cms/images/udate-request.png" width="170" height="26" /></a></td>
                </tr>
                <tr>
                  <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                    <tr>
                      <td width="30%" align="left" valign="top"><strong>Civil Status</strong></td>
                      <td width="3%" align="left" valign="top"><strong>:</strong></td>
                      <td width="67%" align="left" valign="top"><?php echo $CivilStatusName ?></td>
                    </tr>
                  </table></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;"><strong>Details of Spouse</strong></td>
                </tr>
                <tr>
                    <td width="82%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="30%" align="left" valign="top"><strong>NIC</strong></td>
                                <td width="3%" align="left" valign="top"><strong>:</strong></td>
                              <td width="67%" align="left" valign="top"><?php echo $SpouseNIC ?></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top"><strong>Full Name</strong></td>
                              <td align="left" valign="top"><strong>:</strong></td>
                              <td align="left" valign="top"><?php echo $SpouseName ?></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top"><strong>Date of Birth</strong></td>
                              <td align="left" valign="top"><strong>:</strong></td>
                              <td align="left" valign="top"><?php echo $SpouseDOB ?></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top"><strong>Occupation</strong></td>
                              <td align="left" valign="top"><strong>:</strong></td>
                              <td align="left" valign="top"><?php echo $PositionName ?></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top"><strong>Office Address</strong></td>
                              <td align="left" valign="top"><strong>:</strong></td>
                              <td align="left" valign="top"><?php echo $SpouseOfficeAddr ?></td>
                            </tr>
                            
                            </table>
                    </td>
                    <td width="18%" valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top">&nbsp;</td>
                  <td valign="top"><a href="familyInfoChild-3-E.html"><img src="../cms/images/udate-request.png" width="170" height="26" /></a></td>
                </tr>
                <tr>
                  <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;"><strong>Details of Children</strong></td>
                </tr>
                <tr>
                  <td colspan="2" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="2" valign="top" bgcolor="#999999"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="3%" align="left" valign="top" bgcolor="#CCCCCC">&nbsp;</td>
                      <td width="47%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Child's Name</strong></td>
                      <td width="22%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Date of Birth</strong></td>
                      <td width="20%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Gender</strong></td>
                      <td width="8%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Status</strong></td>
                    </tr>
                    <?php 
					$i=1;
					$sql = "SELECT        StaffChildren.ID, StaffChildren.NIC, StaffChildren.ChildName, StaffChildren.Gender, CONVERT(varchar(20),StaffChildren.DOB,121) AS DOB, StaffChildren.LastUpdate, StaffChildren.UpdateBy, 
                         StaffChildren.RecordLog, CD_Gender.[Gender Name]
FROM            StaffChildren INNER JOIN
                         CD_Gender ON StaffChildren.Gender = CD_Gender.GenderCode
WHERE        (StaffChildren.NIC = N'$NICUser') order by StaffChildren.DOB asc";
					$stmt = $db->runMsSqlQuery($sql);
					while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$ChildName=trim($row['ChildName']);
						$DOB=$row['DOB'];?>
                    <tr>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $ChildName ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $DOB ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $row['Gender Name']; ?></td>
                      <td align="left" valign="top" bgcolor="#FFFFFF">Approved</td>
                    </tr>
                   <?php }?>
                  </table></td>
                </tr>
                <tr>
                  <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </div>
</div>
<?php }?>
<?php if($menu=='E'){?>
<div class="main_content_inner_block">
    <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if ($msg != '' || $success!='') {//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){   ?>   
        <div class="mcib_middle1">
            <div class="mcib_middle_full">
                <div class="form_error"><?php
                    echo $msg;echo $success;
                    echo $_SESSION['success_update'];
                    $_SESSION['success_update'] = "";
                    ?><?php echo $_SESSION['fail_update'];
                    $_SESSION['fail_update'] = "";
                    ?></div>
            </div>
<?php }  ?>
<?php if($success==''){?>
            <table width="945" cellpadding="0" cellspacing="0">
                <tr>
                  <td valign="top"><span style="color:#090; font-weight:bold;">*If your family data record is inaccurate, you can submit an update request</span></td>
                  <td align="right" valign="top"><a href="familyInfo-2.html"><img src="../cms/images/current-details.png" width="138" height="26" /></a></td>
                </tr>
                <tr>
                  <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                    <tr>
                      <td width="30%" align="left" valign="top"><strong>Civil Status</strong></td>
                      <td width="3%" align="left" valign="top"><strong>:</strong></td>
                      <td width="67%" align="left" valign="top"><select class="select2a_n" id="CivilStatusCode" name="CivilStatusCode">
                        <!--<option value="">School Name</option>-->
                        <?php
                            $sql = "SELECT [Code],[CivilStatusName] FROM CD_CivilStatus order by Code asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$CodeC=trim($row['Code']);
								$CivilStatusName=$row['CivilStatusName'];
								$seltebr="";
								if($CodeC==$CivilStatusCode){
									$seltebr="selected";
								}
                                echo "<option value=\"$CodeC\" $seltebr>$CivilStatusName</option>";
                            }
                            ?>
                      </select></td>
                    </tr>
                  </table></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                  <td valign="top" style="border-bottom:1px; border-bottom-style:solid;"><strong>Details of Spouse</strong></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td width="82%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="30%" align="left" valign="top"><strong>NIC</strong></td>
                                <td width="2%" align="left" valign="top"><strong>:</strong></td>
                              <td width="68%" align="left" valign="top"><?php //echo $NICUser ?>
                              <input type="hidden" name="familiInfoMainStatus" value="<?php echo $familiInfoMainStatus ?>" />
                              <input name="SpouseNIC" type="text" class="input2_n" id="SpouseNIC" value="<?php echo $SpouseNIC ?>"/></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top"><strong>Full Name</strong></td>
                              <td align="left" valign="top"><strong>:</strong></td>
                              <td align="left" valign="top"><input name="SpouseName" type="text" class="input2" id="SpouseName" value="<?php echo $SpouseName ?>"/></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top"><strong>Date of Birth</strong></td>
                              <td align="left" valign="top"><strong>:</strong></td>
                              <td align="left" valign="top"><table width="100%" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="13%"><input name="SpouseDOB" type="text" class="input3new" id="SpouseDOB" value="<?php echo $SpouseDOB; ?>" size="10" style="height:20px; line-height:20px;" readonly/>
                      </td>
                            <td width="87%">
                      <input name="f_trigger_1" type="image" id="f_trigger_1" src="../cms/images/calender_icon.gif" align="top" width="16" height="16"  />
                  <script type="text/javascript">
                            //2005-10-03 11:46:00 
                                Calendar.setup({
                                inputField     :    "SpouseDOB",      // id of the input field
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
                              <td align="left" valign="top"><strong>Occupation</strong></td>
                              <td align="left" valign="top">:</td>
                              <td align="left" valign="top"><select class="select2a_n" id="SpouseOccupationCode" name="SpouseOccupationCode">
                                <!--<option value="">School Name</option>-->
                                <?php
                            $sql = "SELECT [Code],[PositionName] FROM CD_Positions order by Code asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$CodeP=trim($row['Code']);
								$PositionName=$row['PositionName'];
								$seltebr="";
								if($CodeP==$SpouseOccupationCode){
									$seltebr="selected";
								}
                                echo "<option value=\"$CodeP\" $seltebr>$PositionName</option>";
                            }
                            ?>
                        </select></td>
                      </tr>
                            <tr>
                              <td align="left" valign="top"><strong>Office Address</strong></td>
                              <td align="left" valign="top">:</td>
                              <td align="left" valign="top"><textarea name="SpouseOfficeAddr" cols="45" rows="4" class="textarea1auto" id="SpouseOfficeAddr"><?php echo $SpouseOfficeAddr ?></textarea></td>
                            </tr>
                            
                            </table>
                    </td>
                    <td width="18%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="38%" align="left" valign="top">&nbsp;</td>
                                <td width="3%" align="left" valign="top">&nbsp;</td>
                              <td width="59%" align="left" valign="top">&nbsp;</td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                  <td valign="top">&nbsp;</td>
                  <td valign="top">&nbsp;</td>
                </tr>
               
                <tr>
                  <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                    <tr>
                      <td width="32%">&nbsp;</td>
                      <td width="68%"><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                    </tr>
                  </table></td>
                  <td valign="top">&nbsp;</td>
                </tr>
                
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
            <?php }?>
        </div>

    </form>
</div>
<?php }?>