<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
$msg = "";
$NICUserUpdate = $NICUser;
$NICUser = $id;

$isAvailablePerAdd = $isAvailableCurAdd = "";
$success = "";
if (isset($_POST["FrmSubmit"])) {
    include('../activityLog.php');
    $SubCode = $_REQUEST['SubCode'];

    $QCode = $_REQUEST['QCode'];
    $EffectiveDate = $_REQUEST['EffectiveDate'];
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

    $QualificationIDA = "";


    if ($QCode == "") {
        $msg .= "Please select qualifications.<br>";
    }
    if ($EffectiveDate == "") {
        $msg .= "Please select effective date.<br>";
    }
    if ($SubCode == "") {
        $msg .= "Please select subject.<br>";
    }

    if ($msg == '') {
        $familiChildStatus = "Add";
        if ($QualificationIDA == '') {//$familiChildStatus=='Add'){
            $queryMainSave = "INSERT INTO UP_StaffQualification
			   (NIC,QCode,EffectiveDate,Reference,LastUpdate,UpdateBy,RecordLog)
		 VALUES
			   ('$NICUser','$QCode','$EffectiveDate','','$LastUpdate','$NICUserUpdate','First change')";
            $QualificationID = $db->runMsSqlQuery($queryMainSave);

            $reqTabMobAc = "SELECT ID FROM UP_StaffQualification where NIC='$NICUser'  ORDER BY ID DESC";
            $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
            $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
            $QualificationID = trim($rowMobAc['ID']);
        }

        for ($i = 0; $i < count($SubCode); $i++) {
            $SubjectCode = $SubCode[$i];
            if ($SubjectCode != '') {
                $queryMainSaveSubj = "INSERT INTO UP_QualificationSubjects
				   (QualificationID,NIC,SubjectCode,RecordLog)
			 VALUES
				   ('$QualificationID','$NICUser','$SubjectCode','Updated by $NICUserUpdate')";
                $db->runMsSqlQuery($queryMainSaveSubj);
            }
        }
    }

    if ($msg == '') {
        $queryRegis = "INSERT INTO TG_EmployeeUpdateQualification				   (NIC,QualificationID,dDateTime,ZoneCode,IsApproved,ApproveComment,ApproveDate,ApprovedBy,UpdateBy)
			 VALUES				   
		('$NICUser','$QualificationID','$LastUpdate','$ZoneCode','N','','','','$NICUserUpdate')";
        $db->runMsSqlQuery($queryRegis);

        audit_trail($NICUser, $_SESSION["NIC"], 'teacherprofile\qualification.php', 'Insert', 'UP_StaffQualification,UP_QualificationSubjects,TG_EmployeeUpdateQualification', 'Insert user qualification.');

        $success = "Your update request submitted successfully. Data will be displaying after the approvals.";
    }
    //exit();
    //sqlsrv_query($queryGradeSave);
}
?>
<?php if ($menu == '') { ?>
    <div class="main_content_inner_block">
        <div class="mcib_middle1">
            <table width="945" cellpadding="0" cellspacing="0">
                <tr>
                    <td valign="top"><span style="color:#090; font-weight:bold;">*If your qualifications are inaccurate, you can submit an update request</span></td>
                    <td align="right" valign="top"><a href="qualifications-4-E-<?php echo $id ?>.html"><img src="../cms/images/udate-request.png" width="170" height="26" /></a></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;"><strong>Details of Existing Qualifications</strong></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" bgcolor="#666666"><table width="100%" cellspacing="1" cellpadding="1">
                            <tr>
                                <td width="2%" align="center" valign="top" bgcolor="#CCCCCC"><strong>#</strong></td>
                                <td width="15%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Qualification Title</strong></td>
                                <td width="33%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Description</strong></td>
                                <td width="28%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Subjects</strong></td>
                                <td width="12%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Effective Date</strong></td>
                                <td width="10%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Action</strong></td>
                            </tr>
                            <?php
                            $i = 1;
                            $sql = "SELECT        StaffQualification.ID, StaffQualification.NIC, StaffQualification.QCode, CONVERT(varchar(20),StaffQualification.EffectiveDate, 121) AS EffectiveDate, StaffQualification.Reference, StaffQualification.LastUpdate, 
                         StaffQualification.UpdateBy, StaffQualification.RecordLog, CD_Qualif.Description, CD_QualificationCategory.Description AS Expr1
FROM            StaffQualification INNER JOIN
                         CD_Qualif ON StaffQualification.QCode = CD_Qualif.Qcode INNER JOIN
                         CD_QualificationCategory ON CD_Qualif.Category = CD_QualificationCategory.Code
WHERE        (StaffQualification.NIC = '$NICUser')
";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $catTitle = trim($row['Expr1']);
                                $Description = $row['Description'];
                                $EffectiveDate = $row['EffectiveDate'];
                                $Expr1 = $row['ID'];

                                $SubjectName = "";
                                $sqlSub = "SELECT CD_Subject.SubjectName
FROM            QualificationSubjects INNER JOIN
                         CD_Subject ON QualificationSubjects.SubjectCode = CD_Subject.SubCode
WHERE        (QualificationSubjects.QualificationID = '$Expr1')";
                                $stmtSub = $db->runMsSqlQuery($sqlSub);
                                while ($rowSub = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
                                    $SubjectName .= trim($rowSub['SubjectName']) . ",";
                                }
                                ?>
                                <tr>
                                    <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                                    <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $catTitle ?></td>
                                    <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $Description ?></td>
                                    <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $SubjectName; ?></td>
                                    <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $EffectiveDate; ?></td>
                                    <td align="left" valign="top" bgcolor="#FFFFFF"><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','Qualification','StaffQualification','<?php echo "qualifications-4-E-$id.html"; ?>')">Remove</a></td>
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
<?php if ($menu == 'E') {
    ?>
    <div class="main_content_inner_block">
        <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
    <?php if ($msg != '' || $success != '') {//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){    ?>   
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
                            ?></div>
                    </div>
                        <?php } ?>
                <?php //if($success==''){ ?>
                <table width="945" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="82%" valign="top"><span style="color:#090; font-weight:bold;">*If your Qualification not available, you can submit an update request</span></td>
                        <td width="18%" align="right" valign="top"><a href="qualifications-4--<?php echo $id ?>.html"><img src="../cms/images/current-details.png" width="138" height="26" /></a></td>
                    </tr>
                    <tr>
                        <td valign="top">&nbsp;</td>
                        <td valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;"><strong>Details of Pending Approval Qualifications</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top" bgcolor="#666666"><table width="100%" cellspacing="1" cellpadding="1">
                                <tr>
                                    <td width="2%" align="center" valign="top" bgcolor="#CCCCCC"><strong>#</strong></td>
                                    <td width="13%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Qualification Title</strong></td>
                                    <td width="25%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Description</strong></td>
                                    <td width="20%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Subjects</strong></td>
                                    <td width="8%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Effective Date</strong></td>
                                    <td width="7%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Action</strong></td>
                                </tr>
    <?php
    $i = 1;

    $reqTab = "SELECT [ID]
					  ,[NIC]
					  ,[QualificationID]
					  ,[dDateTime]
					  ,[IsApproved]
					  ,[ApproveComment]
					  ,[ApproveDate]
					  ,[ApprovedBy]
					  ,[UpdateBy]
				  FROM [dbo].[TG_EmployeeUpdateQualification] WHERE NIC='$NICUser' and IsApproved='N'";

    $stmtE = $db->runMsSqlQuery($reqTab);
    while ($rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC)) {
        //$rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC);
        $NIC = trim($rowE['NIC']);
        $QualificationID = trim($rowE['QualificationID']);
        $IsApproved = trim($rowE['IsApproved']);
        $ApproveComment = trim($rowE['ApproveComment']);

        if ($IsApproved == 'R')
            $statsComm = "Rejected ($ApproveComment)";
        if ($IsApproved == 'N')
            $statsComm = "Pending";


        $sql = "SELECT        UP_StaffQualification.ID, UP_StaffQualification.NIC, UP_StaffQualification.QCode, CONVERT(varchar(20),UP_StaffQualification.EffectiveDate, 121) AS EffectiveDate, UP_StaffQualification.Reference, UP_StaffQualification.LastUpdate, 
                         UP_StaffQualification.UpdateBy, UP_StaffQualification.RecordLog, CD_Qualif.Description, CD_QualificationCategory.Description AS Expr1
FROM            UP_StaffQualification INNER JOIN
                         CD_Qualif ON UP_StaffQualification.QCode = CD_Qualif.Qcode INNER JOIN
                         CD_QualificationCategory ON CD_Qualif.Category = CD_QualificationCategory.Code
WHERE        (UP_StaffQualification.ID = '$QualificationID')
";
        $stmt = $db->runMsSqlQuery($sql);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $catTitle = trim($row['Expr1']);
        $Description = $row['Description'];
        $EffectiveDate = $row['EffectiveDate'];
        $Expr1 = $row['ID'];

        $SubjectName = "";
        $sqlSub = "SELECT CD_Subject.SubjectName
FROM            UP_QualificationSubjects INNER JOIN
                         CD_Subject ON UP_QualificationSubjects.SubjectCode = CD_Subject.SubCode
WHERE        (UP_QualificationSubjects.QualificationID = '$Expr1')";
        $stmtSub = $db->runMsSqlQuery($sqlSub);
        while ($rowSub = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
            $SubjectName .= trim($rowSub['SubjectName']) . ",";
        }
        ?>
                                    <tr>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $catTitle ?></td>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $Description ?></td>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $SubjectName; ?></td>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $EffectiveDate; ?></td>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','QualificationTmp','UP_StaffQualification','<?php echo "qualifications-4-E-$id.html"; ?>')">Remove</a></td>
                                    </tr>
    <?php } ?>
                            </table></td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top" >&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;"><strong>Details of Rejected Qualifications</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top" bgcolor="#666666"><table width="100%" cellspacing="1" cellpadding="1">
                                <tr>
                                    <td width="2%" align="center" valign="top" bgcolor="#CCCCCC"><strong>#</strong></td>
                                    <td width="13%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Qualification Title</strong></td>
                                    <td width="25%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Description</strong></td>
                                    <td width="20%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Subjects</strong></td>
                                    <td width="8%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Effective Date</strong></td>
                                    <td width="25%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Status</strong></td>
                                    <td width="7%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Action</strong></td>
                                </tr>
    <?php
    $i = 1;

    echo $reqTab = "SELECT [ID]
					  ,[NIC]
					  ,[QualificationID]
					  ,[dDateTime]
					  ,[IsApproved]
					  ,[ApproveComment]
					  ,[ApproveDate]
					  ,[ApprovedBy]
					  ,[UpdateBy]
				  FROM [dbo].[TG_EmployeeUpdateQualification] WHERE NIC='$NICUser' and IsApproved='R'";

    $stmtE = $db->runMsSqlQuery($reqTab);
    while ($rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC)) {
        //$rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC);
        $NIC = trim($rowE['NIC']);
        $QualificationID = trim($rowE['QualificationID']);
        $IsApproved = trim($rowE['IsApproved']);
        $ApproveComment = trim($rowE['ApproveComment']);

        if ($IsApproved == 'R')
            $statsComm = "Rejected ($ApproveComment)";
        if ($IsApproved == 'N')
            $statsComm = "Pending";


        $sql = "SELECT        UP_StaffQualification.ID, UP_StaffQualification.NIC, UP_StaffQualification.QCode, CONVERT(varchar(20),UP_StaffQualification.EffectiveDate, 121) AS EffectiveDate, UP_StaffQualification.Reference, UP_StaffQualification.LastUpdate, 
                         UP_StaffQualification.UpdateBy, UP_StaffQualification.RecordLog, CD_Qualif.Description, CD_QualificationCategory.Description AS Expr1
FROM            UP_StaffQualification INNER JOIN
                         CD_Qualif ON UP_StaffQualification.QCode = CD_Qualif.Qcode INNER JOIN
                         CD_QualificationCategory ON CD_Qualif.Category = CD_QualificationCategory.Code
WHERE        (UP_StaffQualification.ID = '$QualificationID')
";
        $stmt = $db->runMsSqlQuery($sql);
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        $catTitle = trim($row['Expr1']);
        $Description = $row['Description'];
        $EffectiveDate = $row['EffectiveDate'];
        $Expr1 = $row['ID'];

        $SubjectName = "";
        $sqlSub = "SELECT CD_Subject.SubjectName
FROM            UP_QualificationSubjects INNER JOIN
                         CD_Subject ON UP_QualificationSubjects.SubjectCode = CD_Subject.SubCode
WHERE        (UP_QualificationSubjects.QualificationID = '$Expr1')";
        $stmtSub = $db->runMsSqlQuery($sqlSub);
        while ($rowSub = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
            $SubjectName .= trim($rowSub['SubjectName']) . ",";
        }
        ?>
                                    <tr>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $catTitle ?></td>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $Description ?></td>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $SubjectName; ?></td>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $EffectiveDate; ?></td>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $statsComm ?></td>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','QualificationTmp','UP_StaffQualification','<?php echo "qualifications-4-E-$id.html"; ?>')">Remove</a></td>
                                    </tr>
    <?php } ?>
                            </table></td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top">&nbsp;</td>
                    </tr>
    <?php //if($success==''){  ?>
                    <tr>
                        <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;"><strong>Add New Qualification</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" valign="top"><table width="100%" cellspacing="1" cellpadding="1">
                                <tr>
                                    <td width="17%"><strong>Effective Date <span class="form_error_sched">*</span></strong></td>
                                    <td width="1%"><strong>:</strong></td>
                                    <td width="82%"><table width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td width="7%"><input name="EffectiveDate" type="text" class="input3new" id="EffectiveDate" value="<?php //echo $DOB;    ?>" size="10" style="height:20px; line-height:20px;" readonly="readonly"/></td>
                                                <td width="93%"><input name="f_trigger_2" type="image" id="f_trigger_2" src="../cms/images/calender_icon.gif" align="top" width="16" height="16"  />
                                                    <script type="text/javascript">
                                                        //2005-10-03 11:46:00 
                                                        Calendar.setup({
                                                            inputField: "EffectiveDate", // id of the input field
                                                            ifFormat: "%Y-%m-%d", // format of the input field
                                                            showsTime: false, // will display a time selector
                                                            button: "f_trigger_2", // trigger for the calendar (button ID)
                                                            singleClick: true, // double-click mode
                                                            step: 1                // show all years in drop-down boxes (instead of every other year as default)
                                                        });
                                                    </script></td>
                                            </tr>
                                        </table></td>
                                </tr>
                                <tr>
                                    <td><strong>Qualifications <span class="form_error_sched">*</span></strong></td>
                                    <td><strong>:</strong></td>
                                    <td><select class="select2a_n" id="QCode" name="QCode">
                                            <!--<option value="">School Name</option>-->
    <?php
    $sql = "SELECT [Qcode],[Description] FROM CD_Qualif order by Qcode asc";
    $stmt = $db->runMsSqlQuery($sql);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $GenderCoded = trim($row['Qcode']);
        $GenderName = $row['Description'];
        $seltebr = "";
        /* if($GenderCoded==$Gender){
          $seltebr="selected";
          } */
        echo "<option value=\"$GenderCoded\" $seltebr>$GenderName</option>";
    }
    ?>
                                        </select></td>
                                </tr>
                                <tr>
                                    <td valign="top"><strong>Subjects <span class="form_error_sched">*</span></strong></td>
                                    <td valign="top"><strong>:</strong></td>
                                    <td><pre wrap="on" style="border: 1px inset ; border-color: #C5C8BD; margin: 0px; padding: 2px; overflow: scroll; width:500px; height: 430px; background-color:#FFFFFF;"><table width="100%" cellspacing="1" cellpadding="1">
    <?php
    $sqlSub = "SELECT [SubCode]
					,[SubjectName]
					,[RecordLog]
					FROM [dbo].[CD_Subject] order by SubjectName asc";
    $stmtSub = $db->runMsSqlQuery($sqlSub);
    while ($rowS = sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
        $SubCode = trim($rowS['SubCode']);
        $SubjectName = $rowS['SubjectName'];
        ?>
                                                <tr>
                                                  <td width="7%">&nbsp;<input name="SubCode[]" type="checkbox" value="<?php echo $SubCode ?>" /></td>
                                                  <td width="93%"><?php echo $SubjectName ?></td>
                                                  </tr>
                                                    <?php } ?>
                                  </table></pre></td>
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
    <?php //}  ?>
                </table>
                    <?php //} ?>
            </div>

        </form>
    </div>
    <?php
}?>