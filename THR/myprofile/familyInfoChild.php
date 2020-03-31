<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
$nicUpdate = $_SESSION['NIC'];
$NICUser = $id;
$msg = "";
$isAvailablePerAdd = $isAvailableCurAdd = "";
$success = "";
if (isset($_POST["FrmSubmit"])) {
include('../activityLog.php');
    $_SESSION['success_update'] = "";
    $_SESSION['fail_update'] = "";

    $ChildName = $_REQUEST['ChildName'];
    $Gender = $_REQUEST['Gender'];
    $DOB = $_REQUEST['DOB'];
    $LastUpdate = date('Y-m-d H:i:s');
    $msg = "";
    $StaffChildIDA = "";


    $sqlServiceRef = " SELECT        TeacherMast.CurServiceRef, CD_CensesNo.ZoneCode
FROM            StaffServiceHistory INNER JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode INNER JOIN
                         TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
WHERE        (TeacherMast.NIC = '$NICUser')";
    $stmtCAllready = $db->runMsSqlQuery($sqlServiceRef);
    $rowAllready = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC);
    $CurServiceRef = trim($rowAllready['CurServiceRef']);
    $ZoneCode = trim($rowAllready['ZoneCode']);


    if ($ChildName == "") {
        $msg .= "Please enter child name.<br>";
    }
    if ($Gender == "") {
        $msg .= "Please select child gender.<br>";
    }
    if ($DOB == "") {
        $msg .= "Please select child DOB.<br>";
    }

    if ($msg == '') {
        $familiChildStatus = "Add";
        if ($StaffChildIDA == '') {
            $queryMainSave = "INSERT INTO UP_StaffChildren
			   (NIC,ChildName,Gender,DOB,LastUpdate,UpdateBy,RecordLog,IsApproved)
		 VALUES
			   ('$NICUser','$ChildName','$Gender','$DOB','$LastUpdate','$nicUpdate','change','N')";
            //$db->runMsSqlQuery($queryMainSave);	
            $db->runMsSqlQuery($queryMainSave);

            $reqTabMobAc = "SELECT ID FROM UP_StaffChildren where NIC='$NICUser'  ORDER BY ID DESC";
            $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
            $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
            $StaffChildID = trim($rowMobAc['ID']);
        } else {
            $queryMainUpdate = "UPDATE UP_StaffChildren SET NIC='$NICUser',ChildName='$ChildName',Gender='$Gender',DOB='$DOB',LastUpdate='$LastUpdate',UpdateBy='$nicUpdate',RecordLog='Edit record',IsApproved='N' WHERE ID='$StaffChildIDA'"; //NIC='$NICUser' and AddrType='PER'";

            $db->runMsSqlQuery($queryMainUpdate);
            $StaffChildID = $StaffChildIDA;
            $isAvailable = 1;
        }
    }

    if ($msg == '') {

        if ($isAvailable == 1) {

            $queryMainUpdate = "UPDATE TG_EmployeeUpdateChildInfo SET StaffChildID='$StaffChildID',dDateTime='$LastUpdate',IsApproved='N',ApproveDate='',ApprovedBy='',UpdateBy='$nicUpdate' WHERE NIC='$NICUser' and IsApproved='N'";
            $db->runMsSqlQuery($queryMainUpdate);
            
            audit_trail($NICUser, $_SESSION["NIC"], 'teacherprofile\familyInfoChild.php', 'Update', 'UP_StaffChildren', 'Update family child info.');
        } else {

            $queryRegis = "INSERT INTO TG_EmployeeUpdateChildInfo				   (NIC,StaffChildID,dDateTime,ZoneCode,IsApproved,ApproveComment,ApproveDate,ApprovedBy,UpdateBy)
			 VALUES				   
		('$NICUser','$StaffChildID','$LastUpdate','$ZoneCode','N','','','','$nicUpdate')";
            $db->runMsSqlQuery($queryRegis);
            
            audit_trail($NICUser, $_SESSION["NIC"], 'teacherprofile\familyInfoChild.php', 'Insert', 'UP_StaffChildren', 'Insert family child info.');
        }
        $success = "Your update request submitted successfully. Data will be displaying after the approvals.<br><a href=\"familyInfoChild-3-E-$NICUser.html\">Add More</a>";
    }
}

?>
<?php if ($menu == '') { ?>
    <div class="main_content_inner_block">
        <div class="mcib_middle1">
            <table width="945" cellpadding="0" cellspacing="0">
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" bgcolor="#999999">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>

                <tr>
                    <td width="82%">&nbsp;</td>
                    <td width="18%">&nbsp;</td>
                </tr>
            </table>
        </div>
    </div>
<?php } ?>
<?php if ($menu == 'E') { ?>
    <div class="main_content_inner_block"> <div class="mcib_middle1">
            <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">


                <?php if ($msg != '' || $success != '') {
                    ?>  
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
                            <td width="82%" valign="top"><span style="color:#090; font-weight:bold;">*If your family data record is inaccurate, you can submit an update request</span></td>
                            <td width="18%" align="right" valign="top"><a href="familyInfo-2--<?php echo $id ?>.html"><img src="../cms/images/current-details.png" width="138" height="26" /></a></td>
                        </tr>
                        <tr>
                            <td valign="top">&nbsp;</td>
                            <td valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;"><strong>Details of Existing Children</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top" bgcolor="#666666"><table width="100%" cellspacing="1" cellpadding="1">
                                    <tr>
                                        <td width="3%" align="left" valign="top" bgcolor="#CCCCCC">&nbsp;</td>
                                        <td width="47%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Child's Name</strong></td>
                                        <td width="22%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Date of Birth</strong></td>
                                        <td width="20%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Gender</strong></td>
                                        <td width="8%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Action</strong></td>
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
                                        $Expr1 = $row['ID'];
                                        ?>
                                        <tr>
                                            <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                                            <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $ChildName ?></td>
                                            <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $DOB ?></td>
                                            <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $row['Gender Name']; ?></td>
                                            <td align="left" valign="top" bgcolor="#FFFFFF"><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','Children','StaffChildren','<?php echo "familyInfoChild-3-E-$id.html"; ?>')">Remove</a></td>
                                        </tr>
                                    <?php } ?>
                                </table></td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;"><strong>Pending Approvals</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top" bgcolor="#666666"><table width="100%" cellspacing="1" cellpadding="1">
                                    <tr>
                                        <td width="3%" align="left" valign="top" bgcolor="#CCCCCC">&nbsp;</td>
                                        <td width="47%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Child's Name</strong></td>
                                        <td width="22%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Date of Birth</strong></td>
                                        <td width="20%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Gender</strong></td>
                                        <td width="8%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Action</strong></td>
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
                                        $Expr1 = $row['ID'];
                                        ?>
                                        <tr>
                                            <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                                            <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $ChildName ?></td>
                                            <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $DOB ?></td>
                                            <td align="left" valign="top" bgcolor="#FFFFFF"><?php echo $row['Gender Name']; ?></td>
                                            <td align="left" valign="top" bgcolor="#FFFFFF"><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','ChildrenTemp','UP_StaffChildren','<?php echo "familyInfoChild-3-E-$id.html"; ?>')">Remove</a></td>
                                        </tr>
                                    <?php } ?>
                                </table></td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid;"><strong>Add New Children</strong></td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top"><table width="100%" cellspacing="1" cellpadding="1">
                                    <tr>
                                        <td width="3%" align="left" valign="top" bgcolor="#CCCCCC">&nbsp;</td>
                                        <td width="47%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Child's Name</strong></td>
                                        <td width="22%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Date of Birth</strong></td>
                                        <td width="20%" align="left" valign="top" bgcolor="#CCCCCC"><strong>Gender</strong></td>
                                        <td width="8%" align="left" valign="top" bgcolor="#CCCCCC">&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="left" valign="top" bgcolor="#FFFFFF">1</td>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><input name="ChildName" type="text" class="input2" id="ChildName" value="<?php //echo $ChildName   ?>"/></td>
                                        <td width="22%" align="left" valign="top" bgcolor="#FFFFFF"><table width="100%" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td width="13%"><input name="DOB" type="text" class="input3new" id="DOB" value="<?php //echo $DOB;   ?>" size="10" style="height:20px; line-height:20px;" readonly="readonly"/></td>
                                                    <td width="87%"><input name="f_trigger_2" type="image" id="f_trigger_2" src="../cms/images/calender_icon.gif" align="top" width="16" height="16"  />
                                                        <script type="text/javascript">
                                                            //2005-10-03 11:46:00 
                                                            Calendar.setup({
                                                                inputField: "DOB", // id of the input field
                                                                ifFormat: "%Y-%m-%d", // format of the input field
                                                                showsTime: false, // will display a time selector
                                                                button: "f_trigger_2", // trigger for the calendar (button ID)
                                                                singleClick: true, // double-click mode
                                                                step: 1                // show all years in drop-down boxes (instead of every other year as default)
                                                            });
                                                        </script></td>
                                                </tr>
                                            </table></td>
                                        <td align="left" valign="top" bgcolor="#FFFFFF"><select class="select2a_n" id="Gender" name="Gender">
                                                <!--<option value="">School Name</option>-->
                                                <?php
                                                $sql = "SELECT [GenderCode],[Gender Name] FROM CD_Gender order by GenderCode asc";
                                                $stmt = $db->runMsSqlQuery($sql);
                                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                    $GenderCoded = trim($row['GenderCode']);
                                                    $GenderName = $row['Gender Name'];
                                                    $seltebr = "";
                                                    /* if($GenderCoded==$Gender){
                                                      $seltebr="selected";
                                                      } */
                                                    echo "<option value=\"$GenderCoded\" $seltebr>$GenderName</option>";
                                                }
                                                ?>
                                            </select></td>
                                        <td align="left" valign="top" bgcolor="#FFFFFF">&nbsp;</td>
                                    </tr>
                                </table></td>
                        </tr>
                        <tr>
                            <td colspan="2" valign="top">&nbsp;</td>
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
            </form> </div>
    </div>
    <?php
}?>