<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
$msg = "";
$tblNam = "TG_ApprovalProcessMain";
$countTotal = "SELECT * FROM $tblNam"; //$NICUser
$redirect_page = "approvalProcess-1.html";

$nicUpdate = $_SESSION['NIC'];
$NICUser = $id;

//$countSql = "SELECT * FROM $tblNam where ProcessType='$ProcessType' and AccessRoleID='$PositionCode' and Enable = 'Y'";
$isAvailablePerAdd = $isAvailableCurAdd = "";
$success = "";
if (isset($_POST["FrmSubmit"])) {
    
}

if ($menu == 'E' and $success == '') {
    
    $sqlCAllready = "SELECT * FROM TG_EmployeeUpdateFamilyInfo WHERE NIC='$NICUser' and IsApproved='N'";
    $stmtCAllready = $db->runMsSqlQuery($sqlCAllready);
    $rowAllready = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC);
    $TeacherMastIDA = trim($rowAllready['TeacherMastID']);

    /* address */
    $familiInfoMainStatus = "Update";
    $curAddStatus = "Update";


    $sqlpMast = "SELECT        UP_TeacherMast.NIC, UP_TeacherMast.CivilStatusCode, UP_TeacherMast.SpouseName, UP_TeacherMast.SpouseNIC, 
                         UP_TeacherMast.SpouseOccupationCode, CONVERT(varchar(20), 
                         UP_TeacherMast.SpouseDOB, 121) AS SpouseDOB, UP_TeacherMast.SpouseOfficeAddr, CD_Positions.PositionName, 
                         CD_CivilStatus.CivilStatusName
FROM            UP_TeacherMast INNER JOIN
                         CD_Positions ON UP_TeacherMast.SpouseOccupationCode = CD_Positions.Code INNER JOIN
                         CD_CivilStatus ON UP_TeacherMast.CivilStatusCode = CD_CivilStatus.Code
WHERE        (UP_TeacherMast.ID = N'$TeacherMastIDA')"; // AND (ArchiveUP_TeacherMast.RecStatus = N'0')";//538093300V

     $isAvailablePmast = $db->rowAvailable($sqlpMast);

    $resABC = $db->runMsSqlQuery($sqlpMast);
    $rowABC = sqlsrv_fetch_array($resABC, SQLSRV_FETCH_ASSOC);
    $CivilStatusCode = trim($rowABC['CivilStatusCode']);
    $SpouseName = $rowABC['SpouseName'];
    $SpouseNIC = trim($rowABC['SpouseNIC']);
    $SpouseOccupationCode = trim($rowABC['SpouseOccupationCode']);
    $SpouseDOB = $rowABC['SpouseDOB'];
    $SpouseOfficeAddr = $rowABC['SpouseOfficeAddr'];
    $PositionName = $rowABC['PositionName'];
    $CivilStatusName = $rowABC['CivilStatusName'];

    $msgof = "You already sumitted an update request with following information. If your family detail is inaccurate, you can re-submit an update request";
}
//echo $isAvailablePmast;
if ($isAvailablePmast != 1) {
    $familiInfoMainStatus = "Add";
    $sqlPers = "SELECT TeacherMast.NIC, TeacherMast.CivilStatusCode, TeacherMast.SpouseName, TeacherMast.SpouseNIC, 
                         TeacherMast.SpouseOccupationCode, CONVERT(varchar(20), 
                         TeacherMast.SpouseDOB, 121) AS SpouseDOB, TeacherMast.SpouseOfficeAddr, CD_Positions.PositionName, 
                         CD_CivilStatus.CivilStatusName
FROM            TeacherMast LEFT JOIN
                         CD_Positions ON TeacherMast.SpouseOccupationCode = CD_Positions.Code LEFT JOIN
                         CD_CivilStatus ON TeacherMast.CivilStatusCode = CD_CivilStatus.Code
WHERE        TeacherMast.NIC = N'$NICUser'";

    $resA = $db->runMsSqlQuery($sqlPers);
    $rowA = sqlsrv_fetch_array($resA, SQLSRV_FETCH_ASSOC); //print_r($rowA);

    $CivilStatusCode = trim($rowA['CivilStatusCode']);
    $SpouseName = $rowA['SpouseName'];
    $SpouseNIC = trim($rowA['SpouseNIC']);
    $SpouseOccupationCode = trim($rowA['SpouseOccupationCode']);
    $SpouseDOB = $rowA['SpouseDOB'];
    $SpouseOfficeAddr = $rowA['SpouseOfficeAddr'];
    $PositionName = $rowA['PositionName'];
    $CivilStatusName = $rowA['CivilStatusName'];

    $msgof = "You can submit update request by editing with existing information.";
}
if($SpouseDOB=='1900-01-01'){
 $SpouseDOB = "";
}
if($SpouseName==""){
 $PositionName = "";
 }
//$TotaRows = $db->rowCount($countTotal);
?>
<?php if ($menu == '') { ?>
    <div class="main_content_inner_block">
        <div class="mcib_middle1">
            <?php if ($_SESSION['success_update'] != '') {//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){    ?> 
                <div class="mcib_middle_full">
                    <div class="form_error"><?php
                echo $_SESSION['success_update'];
                $_SESSION['success_update'] = "";
                ?><?php
                        echo $_SESSION['fail_update'];
                        $_SESSION['fail_update'] = "";
                        ?></div>
                </div>
            <?php } ?>
            <table width="945" cellpadding="0" cellspacing="0">
                <!-- <tr>
                    <td valign="top"><span style="color:#090; font-weight:bold;">*If your family data record is inaccurate, you can submit an update request</span></td>
                    <td align="right" valign="top"><a href="familyInfo-2-E-<?php echo $id ?>.html"><img src="../cms/images/udate-request.png" width="170" height="26" /></a></td>
                </tr> -->
                <tr>
                    <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="26%" align="left" valign="top"><strong>Civil Status</strong></td>
                                <td width="2%" align="left" valign="top"><strong>:</strong></td>
                                <td width="72%" align="left" valign="top"><?php echo $CivilStatusName ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                            </tr>
                        </table></td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px; font-weight:bold;"><strong>Details of Spouse</strong></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td width="52%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="26%" align="left" valign="top"><strong>NIC</strong></td>
                                <td width="2%" align="left" valign="top"><strong>:</strong></td>
                                <td width="72%" align="left" valign="top"><?php echo $SpouseNIC ?></td>
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


                        </table>
                    </td>
                    <td width="48%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="25%"><strong>Occupation</strong></td>
                                <td width="3%"><strong>:</strong></td>
                                <td width="72%"><?php echo $PositionName ?></td>
                            </tr>
                            <tr>
                                <td><strong>Office Address</strong></td>
                                <td><strong>:</strong></td>
                                <td><?php echo $SpouseOfficeAddr ?></td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td align="right" valign="top"></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px; font-weight:bold;"><strong>Details of Children</strong></td>
                </tr>
                <!-- <tr>
                    <td colspan="2" align="right" valign="top" bgcolor="#FFFFFF"><a href="familyInfoChild-3-E-<?php echo $id ?>.html"><img src="../cms/images/udate-request.png" width="170" height="26" /></a></td>
                </tr> -->
                <tr>
                    <td colspan="2" align="right" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
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
                            $i = 1;
                            $sql = "SELECT        StaffChildren.ID, StaffChildren.NIC, StaffChildren.ChildName, StaffChildren.Gender, CONVERT(varchar(20),StaffChildren.DOB,121) AS DOB, StaffChildren.LastUpdate, StaffChildren.UpdateBy, 
                         StaffChildren.RecordLog, CD_Gender.[Gender Name]
FROM            StaffChildren INNER JOIN
                         CD_Gender ON StaffChildren.Gender = CD_Gender.GenderCode
WHERE        (StaffChildren.NIC = N'$NICUser') order by StaffChildren.DOB asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $ChildName = trim($row['ChildName']);
                                $DOB = $row['DOB'];
                                ?>
                                <tr>
                                    <td height="32" align="left" valign="middle" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                                    <td align="left" valign="middle" bgcolor="#FFFFFF"><?php echo $ChildName ?></td>
                                    <td align="left" valign="middle" bgcolor="#FFFFFF"><?php echo $DOB ?></td>
                                    <td align="left" valign="middle" bgcolor="#FFFFFF"><?php echo $row['Gender Name']; ?></td>
                                    <td align="left" valign="middle" bgcolor="#FFFFFF">Approved</td>
                                </tr>
                            <?php } ?>
                        </table></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px; font-weight:bold;">Pending Request(s)
                        <?php
                        $sqlCAllready = "SELECT * FROM TG_EmployeeUpdateFamilyInfo WHERE NIC='$NICUser' and IsApproved='N'";
                        $stmtCAllready = $db->runMsSqlQuery($sqlCAllready);
                        $rowAllready = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC);
                        $TeacherMastIDA = trim($rowAllready['TeacherMastID']);
                        //if($TeacherMastIDA!=''){

                        /* address */
                        $familiInfoMainStatus = "Update";
                        $curAddStatus = "Update";
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

                        $sqlpMast = "SELECT        UP_TeacherMast.NIC, UP_TeacherMast.CivilStatusCode, UP_TeacherMast.SpouseName, UP_TeacherMast.SpouseNIC, 
                         UP_TeacherMast.SpouseOccupationCode, CONVERT(varchar(20), 
                         UP_TeacherMast.SpouseDOB, 121) AS SpouseDOB, UP_TeacherMast.SpouseOfficeAddr, CD_Positions.PositionName, 
                         CD_CivilStatus.CivilStatusName
FROM            UP_TeacherMast INNER JOIN
                         CD_Positions ON UP_TeacherMast.SpouseOccupationCode = CD_Positions.Code INNER JOIN
                         CD_CivilStatus ON UP_TeacherMast.CivilStatusCode = CD_CivilStatus.Code
WHERE        (UP_TeacherMast.ID = N'$TeacherMastIDA')"; // AND (ArchiveUP_TeacherMast.RecStatus = N'0')";//538093300V

                        $isAvailablePmast = $db->rowAvailable($sqlpMast);

                        $resABC = $db->runMsSqlQuery($sqlpMast);
                        $rowABC = sqlsrv_fetch_array($resABC, SQLSRV_FETCH_ASSOC);
                        $CivilStatusCode = trim($rowABC['CivilStatusCode']);
                        $SpouseName = $rowABC['SpouseName'];
                        $SpouseNIC = trim($rowABC['SpouseNIC']);
                        $SpouseOccupationCode = trim($rowABC['SpouseOccupationCode']);
                        $SpouseDOB = $rowABC['SpouseDOB'];
                        $SpouseOfficeAddr = $rowABC['SpouseOfficeAddr'];
                        $PositionName = $rowABC['PositionName'];
                        $CivilStatusName = $rowABC['CivilStatusName'];
                        if($SpouseDOB=='1900-01-01'){
                            $SpouseDOB = "";
                        }
                        if($SpouseName==""){
                            $PositionName = "";
                        }
                        ?>

                    </td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
    <?php if ($TeacherMastIDA != '') { ?>
                    <tr>
                        <td colspan="2" valign="top" bgcolor="#FCCDD5"><table width="100%" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="14%"><strong>Civil Status</strong></td>
                                    <td width="1%"><strong>:</strong></td>
                                    <td width="37%"><?php echo $CivilStatusName ?></td>
                                    <td width="15%"><strong>Date of Birth</strong></td>
                                    <td width="1%"><strong>:</strong></td>
                                    <td width="25%"><?php echo $SpouseDOB ?></td>
                                    <td width="7%">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td><strong>NIC</strong></td>
                                    <td><strong>:</strong></td>
                                    <td><?php echo $SpouseNIC ?></td>
                                    <td><strong>Occupation</strong></td>
                                    <td><strong>:</strong></td>
                                    <td><?php echo $PositionName ?></td>
                                    <td align="center"><a href="familyInfo-2-E-<?php echo $NICUser ?>.html"><strong><img src="images/edit.png" width="32" height="32" title="edit"/></strong></a></td>
                                </tr>
                                <tr>
                                    <td><strong>Full Name</strong></td>
                                    <td><strong>:</strong></td>
                                    <td><?php echo $SpouseName ?></td>
                                    <td><strong>Office Address</strong></td>
                                    <td><strong>:</strong></td>
                                    <td colspan="2"><?php echo $SpouseOfficeAddr ?></td>
                                </tr>
                            </table></td>
                    </tr>
    <?php } ?>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" bgcolor="#FCCDD5"><table width="100%" cellspacing="1" cellpadding="1">
                            <tr>
                                <td width="3%" align="left" valign="top" bgcolor="#CCCCCC">&nbsp;</td>
                                <td width="55%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Child's Name</strong></td>
                                <td width="22%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Date of Birth</strong></td>
                                <td width="20%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Gender</strong></td>
                                <!--<td width="8%" align="center" valign="top" bgcolor="#CCCCCC"><strong>Modify</strong></td>-->
                            </tr>
                            <?php
                            $i = 1;
                            $sql = "SELECT        UP_StaffChildren.ID, UP_StaffChildren.NIC, UP_StaffChildren.ChildName, UP_StaffChildren.Gender, CONVERT(varchar(20),UP_StaffChildren.DOB,121) AS DOB, UP_StaffChildren.LastUpdate, UP_StaffChildren.UpdateBy, 
                         UP_StaffChildren.RecordLog, CD_Gender.[Gender Name]
FROM            UP_StaffChildren INNER JOIN
                         CD_Gender ON UP_StaffChildren.Gender = CD_Gender.GenderCode
WHERE        (UP_StaffChildren.NIC = N'$NICUser') order by UP_StaffChildren.DOB asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $ChildName = trim($row['ChildName']);
                                $DOB = $row['DOB'];
                                $IDchild = $row['ID'];
                                ?>
                                <tr>
                                    <td align="left" valign="middle" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                                    <td align="left" valign="middle" bgcolor="#FFFFFF"><?php echo $ChildName ?></td>
                                    <td align="left" valign="middle" bgcolor="#FFFFFF"><?php echo $DOB ?></td>
                                    <td align="left" valign="middle" bgcolor="#FFFFFF"><?php echo $row['Gender Name']; ?></td>
                                    <!--<td align="center" valign="top" bgcolor="#FFFFFF"><a href="childInfo-3-E-<?php echo $NICUser ?>-<?php echo $IDchild ?>.html"><strong><img src="images/edit.png" width="32" height="32" title="edit"/></strong></a></td>-->
                                </tr>
    <?php } ?>
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
<?php } ?>
<?php if ($menu == 'E') { ?>
    <div class="main_content_inner_block">
        <form method="post" action="save.php" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">

            <div class="mcib_middle1">
                        <?php if ($msg != '' || $success != '' || $_SESSION['success_update'] != '') {//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){   ?> 
                    <div class="mcib_middle_full">
                        <div class="form_error"><?php
                            echo $msg;
                            echo $success;
                            echo $_SESSION['success_update'];
                            $_SESSION['success_update'] = "";
                            ?><?php
                    echo $_SESSION['fail_update'];
                    $_SESSION['fail_update'] = "";
                    ?></div>
                    </div>
    <?php } ?>
    <?php if ($success == '') { ?>
                    <table width="945" cellpadding="0" cellspacing="0">
                        <tr>
                            <td valign="top"><span style="color:#090; font-weight:bold;">*<?php echo $msgof ?><?php //echo "If your family data record is inaccurate, you can submit an update request"  ?></span></td>
                            <td align="right" valign="top"><a href="familyInfo-2--<?php echo $id ?>.html"><img src="../cms/images/current-details.png" width="138" height="26" /></a></td>
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
                                                    $CodeC = trim($row['Code']);
                                                    $CivilStatusName = $row['CivilStatusName'];
                                                    $seltebr = "";
                                                    if ($CodeC == $CivilStatusCode) {
                                                        $seltebr = "selected";
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
                                        <td width="68%" align="left" valign="top"><?php //echo $NICUser  ?>
                                            <input type="hidden" name="familiInfoMainStatus" value="<?php echo $familiInfoMainStatus ?>" />
                                            <input type="hidden" name="fmRec" value="<?php echo $fm ?>" />
                                            <input type="hidden" name="menuRec" value="<?php echo $menu ?>" />
                                            <input type="hidden" name="AED" value="special" />
                                            <input type="hidden" name="cat" value="familyinfo" />
                                            <input type="hidden" name="nicSelected" value="<?php echo $id ?>" />

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
                                                                inputField: "SpouseDOB", // id of the input field
                                                                ifFormat: "%Y-%m-%d", // format of the input field
                                                                showsTime: false, // will display a time selector
                                                                button: "f_trigger_1", // trigger for the calendar (button ID)
                                                                singleClick: true, // double-click mode
                                                                step: 1                // show all years in drop-down boxes (instead of every other year as default)
                                                            });
                                                        </script>
                                                    </td>
                                                </tr>
                                            </table></td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top"><strong>Occupation<?php //echo $SpouseOccupationCode ?></strong></td>
                                        <td align="left" valign="top">:</td>
                                        <td align="left" valign="top"><select class="select2a_n" id="SpouseOccupationCode" name="SpouseOccupationCode">
                                                <!--<option value="">School Name</option>-->
                                                <?php
                                                $sql = "SELECT [Code],[PositionName] FROM CD_Positions order by Code asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $CodeP = trim($row['Code']);
                                                    $PositionName = $row['PositionName'];
                                                    $seltebr = "";
                                                    if ($CodeP == $SpouseOccupationCode) {
                                                        $seltebr = "selected";
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
    <?php } ?>
            </div>

        </form>
    </div>
<?php
}?>