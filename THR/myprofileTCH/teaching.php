<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
$msg = "";
$nicUpdate = $_SESSION['NIC'];
$NICUser = $id;

$isAvailablePerAdd = $isAvailableCurAdd = "";
$success = "";
if (isset($_POST["FrmSubmit"])) {
    include('../activityLog.php');
    $SubjectType = $_REQUEST['SubjectType'];
    $SubjectCode = $_REQUEST['SubjectCode'];
    $MediumCode = $_REQUEST['MediumCode'];
    $SecGradeCode = $_REQUEST['SecGradeCode'];
    $LastUpdate = date('Y-m-d H:i:s');

    $msg = "";
    $sqlServiceRef = " SELECT        TeacherMast.CurServiceRef, CD_CensesNo.ZoneCode
FROM            StaffServiceHistory INNER JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode INNER JOIN
                         TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
WHERE        (TeacherMast.NIC = '$NICUser')";
    $stmtCAllready = $db->runMsSqlQuery($sqlServiceRef);
    $rowAllready = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC);
    $CurServiceRef = trim($rowAllready['CurServiceRef']);
    $ZoneCode = trim($rowAllready['ZoneCode']);

    $TeachingIDA = "";
    if ($SubjectType == "") {
        $msg .= "Please select subject type.<br>";
    }
    if ($SubjectCode == "") {
        $msg .= "Please select subject code.<br>";
    }
    if ($MediumCode == "") {
        $msg .= "Please select medium.<br>";
    }

    if ($msg == '') {
        $familiChildStatus = "Add";
        if ($TeachingIDA == '') {//$familiChildStatus=='Add'){
            $queryMainSave = "INSERT INTO UP_TeacherSubject
			   (NIC,SubjectType,SubjectCode,MediumCode,SecGradeCode,Grade,LastUpdate,UpdatedBy,RecordLog)
		 VALUES
			   ('$NICUser','$SubjectType','$SubjectCode','$MediumCode','$SecGradeCode','$Grade','$LastUpdate','$nicUpdate','First change')";
            $db->runMsSqlQuery($queryMainSave);

            $reqTabMobAc = "SELECT ID FROM UP_TeacherSubject where NIC='$NICUser' and SubjectType='$SubjectType' and SubjectCode='$SubjectCode'  ORDER BY ID DESC";
            $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
            $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
            $TeachingID = trim($rowMobAc['ID']);
        }
    }

    if ($msg == '') {
        $queryRegis = "INSERT INTO TG_EmployeeUpdateTeaching				   (NIC,TeachingID,dDateTime,ZoneCode,IsApproved,ApproveDate,ApprovedBy,UpdateBy)
			 VALUES				   
		('$NICUser','$TeachingID','$LastUpdate','$ZoneCode','N','','','$nicUpdate')";
        $db->runMsSqlQuery($queryRegis);
        
        audit_trail($NICUser, $_SESSION["NIC"], 'teacherprofile\teaching.php', 'Insert', 'UP_TeacherSubject,TG_EmployeeUpdateTeaching', 'Insert user teaching info.');

        $success = "Your update request submitted successfully. Data will be displaying after the approvals.";
    }
    //exit();
    //sqlsrv_query($queryGradeSave);
}
?>
<?php if ($menu == '') { ?>
    <div class="main_content_inner_block">
        <div class="mcib_middle1">
            <table width="100%" cellpadding="0" cellspacing="0">
                <!-- <tr>
                    <td valign="top"><span style="color:#090; font-weight:bold;">*If your teaching data is inaccurate, you can submit an update request</span></td>
                    <td align="right" valign="top"><a href="teaching-5-E-<?php //echo $id ?>.html"><img src="../cms/images/udate-request.png" width="170" height="26" /></a></td>
                </tr> -->
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;"><strong>Details of Existing Teaching</strong></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" bgcolor="#666666"><table width="100%" cellspacing="1" cellpadding="1">
                            <tr>
                                <td width="2%" align="center" valign="top" bgcolor="#CCCCCC">#</td>
                                <td width="15%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Category</strong></td>
                                <td width="33%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Subject Name</strong></td>
                                <td width="18%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Medium</strong></td>
                                <td width="22%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Section</strong></td>
                                <!-- <td width="10%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Action</strong></td> -->
                            </tr>
                            <?php
                            $i = 1;
                            //$NICUser="592770830V";
                            $sql = "SELECT        TeacherSubject.ID, TeacherSubject.NIC, TeacherSubject.SubjectType, TeacherSubject.SubjectCode, 
                         TeacherSubject.MediumCode, TeacherSubject.SecGradeCode, CD_SubjectTypes.SubTypeName, CD_Subject.SubjectName, 
                         CD_Medium.Medium, CD_SecGrades.GradeName
FROM            TeacherSubject INNER JOIN
                         CD_SubjectTypes ON TeacherSubject.SubjectType = CD_SubjectTypes.SubType INNER JOIN
                         CD_Subject ON TeacherSubject.SubjectCode = CD_Subject.SubCode INNER JOIN
                         CD_Medium ON TeacherSubject.MediumCode = CD_Medium.Code INNER JOIN
                         CD_SecGrades ON TeacherSubject.SecGradeCode = CD_SecGrades.GradeCode
WHERE        (TeacherSubject.NIC = N'$NICUser')
";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $SubjectName = trim($row['SubjectName']);
                                $SubTypeName = $row['SubTypeName'];
                                $Medium = $row['Medium'];
                                $GradeName = $row['GradeName'];
                                $Expr1 = $row['ID'];
                                ?>
                                <tr>
                                    <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                                    <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $SubTypeName ?></td>
                                    <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $SubjectName ?></td>
                                    <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $Medium; ?></td>
                                    <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $GradeName; ?></td>
                                    <!-- <td align="left" valign="top" bgcolor="#FFFFFF"><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','Teaching','TeacherSubject','<?php echo "teaching-5-E-$id.html"; ?>')">Remove</a></td> -->
                                </tr>
                            <?php } ?>
                        </table></td>
                </tr>
                <tr>
                    <td width="82%" valign="top">&nbsp;</td>
                    <td width="18%" valign="top">&nbsp;</td>
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
        <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
            <?php if ($msg != '' || $success != '' || $_SESSION['success_update'] != '') {//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){    ?>   
                <div class="mcib_middle1">
                    <div class="mcib_middle_full">
                        <div class="form_error"><?php
                            echo $msg;
                            echo $success;
                            echo $_SESSION['success_update'];
                            $_SESSION['success_update'] = "";
                            ?><?php
                            echo $_SESSION['fail_update'];
                            $_SESSION['fail_update'] = "";
                            ?>
                        </div>
                    </div>
                <?php } ?>
                <?php //if($success==''){ ?>
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="82%" valign="top"><span style="color:#090; font-weight:bold;">*If your teaching data not available, you can submit an update request</span></td>
                        <td width="18%" align="right" valign="top"><a href="teaching-5--<?php echo $id ?>.html"><img src="../cms/images/current-details.png" width="138" height="26" /></a></td>
                    </tr>
                    <tr>
                        <td valign="top">&nbsp;</td>
                        <td valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;"><strong>Details of Pending Approval Teaching</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top" bgcolor="#666666"><table width="100%" cellspacing="1" cellpadding="1">
                                <tr>
                                    <td width="2%" align="center" valign="top" bgcolor="#CCCCCC"><strong>#</strong></td>
                                    <td width="15%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Category</strong></td>
                                    <td width="33%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Subject Name</strong></td>
                                    <td width="18%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Medium</strong></td>
                                    <td width="22%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Section</strong></td>
                                    <td width="10%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Action</strong></td>
                                </tr>
                                <?php
                                $i = 1;
                                //$NICUser="592770830V";
                                $sql = "SELECT        UP_TeacherSubject.ID, UP_TeacherSubject.NIC, UP_TeacherSubject.SubjectType, UP_TeacherSubject.SubjectCode, 
                         UP_TeacherSubject.MediumCode, UP_TeacherSubject.SecGradeCode, CD_SubjectTypes.SubTypeName, CD_Subject.SubjectName, 
                         CD_Medium.Medium, CD_SecGrades.GradeName
FROM            UP_TeacherSubject INNER JOIN
                         CD_SubjectTypes ON UP_TeacherSubject.SubjectType = CD_SubjectTypes.SubType INNER JOIN
                         CD_Subject ON UP_TeacherSubject.SubjectCode = CD_Subject.SubCode INNER JOIN
                         CD_Medium ON UP_TeacherSubject.MediumCode = CD_Medium.Code INNER JOIN
                         CD_SecGrades ON UP_TeacherSubject.SecGradeCode = CD_SecGrades.GradeCode
WHERE        (UP_TeacherSubject.NIC = N'$NICUser')
";
                                $stmt = $db->runMsSqlQuery($sql);
                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                    $SubjectName = trim($row['SubjectName']);
                                    $SubTypeName = $row['SubTypeName'];
                                    $Medium = $row['Medium'];
                                    $GradeName = $row['GradeName'];
                                    $Expr1 = $row['ID'];
                                    ?>
                                    <tr>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $SubTypeName ?></td>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $SubjectName ?></td>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $Medium; ?></td>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $GradeName; ?></td>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','TeachingTmp','UP_TeacherSubject','<?php echo "teaching-5-E-$id.html"; ?>')">Remove</a></td>
                                    </tr>
                                <?php } ?>
                            </table></td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;"><strong>Add New Qualification</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top"><table width="100%" cellspacing="1" cellpadding="1">
                                <tr>
                                    <td width="17%"><strong>Category <span class="form_error_sched">*</span></strong></td>
                                    <td width="1%"><strong>:</strong></td>
                                    <td width="82%"><select class="select2a_n" id="SubjectType" name="SubjectType">
                                            <!--<option value="">School Name</option>-->
                                            <?php
                                            $sql = "SELECT [SubType],[SubTypeName] FROM CD_SubjectTypes order by SubType asc";
                                            $stmt = $db->runMsSqlQuery($sql);
                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                $SubType = trim($row['SubType']);
                                                $SubTypeName = $row['SubTypeName'];
                                                $seltebr = "";
                                                /* if($GenderCoded==$Gender){
                                                  $seltebr="selected";
                                                  } */
                                                echo "<option value=\"$SubType\" $seltebr>$SubTypeName</option>";
                                            }
                                            ?>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Subjects <span class="form_error_sched">*</span></strong></td>
                                    <td valign="top"><strong>:</strong></td>
                                    <td><select class="select2a_n" id="SubjectCode" name="SubjectCode">
                                            <!--<option value="">School Name</option>-->
                                            <?php
                                            $sql = "SELECT [SubCode],[SubjectName] FROM CD_Subject order by SubjectName asc";
                                            $stmt = $db->runMsSqlQuery($sql);
                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                $SubCode = trim($row['SubCode']);
                                                $SubjectName = $row['SubjectName'];
                                                $seltebr = "";
                                                /* if($GenderCoded==$Gender){
                                                  $seltebr="selected";
                                                  } */
                                                echo "<option value=\"$SubCode\" $seltebr>$SubjectName</option>";
                                            }
                                            ?>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Medium <span class="form_error_sched">*</span></strong></td>
                                    <td valign="top"><strong>:</strong></td>
                                    <td><select class="select2a_n" id="MediumCode" name="MediumCode">
                                            <!--<option value="">School Name</option>-->
                                            <?php
                                            $sql = "SELECT [Code],[Medium] FROM CD_Medium order by Code asc";
                                            $stmt = $db->runMsSqlQuery($sql);
                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                $CodeM = trim($row['Code']);
                                                $Medium = $row['Medium'];
                                                $seltebr = "";
                                                /* if($GenderCoded==$Gender){
                                                  $seltebr="selected";
                                                  } */
                                                echo "<option value=\"$CodeM\" $seltebr>$Medium</option>";
                                            }
                                            ?>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Section</strong></td>
                                    <td valign="top"><strong>:</strong></td>
                                    <td><select class="select2a_n" id="SecGradeCode" name="SecGradeCode">
                                            <!--<option value="">School Name</option>-->
                                            <?php
                                            $sql = "SELECT [GradeCode],[GradeName] FROM CD_SecGrades order by GradeCode asc";
                                            $stmt = $db->runMsSqlQuery($sql);
                                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                $GradeCode = trim($row['GradeCode']);
                                                $GradeName = $row['GradeName'];
                                                $seltebr = "";
                                                /* if($GenderCoded==$Gender){
                                                  $seltebr="selected";
                                                  } */
                                                echo "<option value=\"$GradeCode\" $seltebr>$GradeName</option>";
                                            }
                                            ?>
                                        </select></td>
                                </tr>
                            </table></td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                        <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="22%">&nbsp;</td>
                                    <td width="78%"><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                                </tr>
                            </table></td>
                        <td valign="top">&nbsp;</td>
                    </tr>

                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
                <?php //}  ?>
            </div>

        </form>
    </div>
    <?php
}?>