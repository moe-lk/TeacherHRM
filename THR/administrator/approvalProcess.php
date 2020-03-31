<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
$msg = "";
$tblNam = "TG_ApprovalProcessMain";
$countTotal = "SELECT * FROM $tblNam"; //$NICUser
$redirect_page = "approvalProcess-1.html";

//$countSql = "SELECT * FROM $tblNam where ProcessType='$ProcessType' and AccessRoleID='$PositionCode' and Enable = 'Y'";

if (isset($_POST["FrmSubmit"])) {
    $noofSteps = $_REQUEST['noofSteps'];
    $ProcessType = $_REQUEST['ProcessType'];
    $PositionCode = $_REQUEST['PositionCode'];
    $ServiceType = $_REQUEST['ServiceType'];
    $Enable = "Y";

    if ($ProcessType == "") {
        $msg .= "Please select a process type.<br>";
    }
    if ($noofSteps == "") {
        $msg .= "Please select number of steps.";
    }
    if ($ProcessType != "" || $noofSteps != "") {
        //header("Location:$redirect_page");
        // exit();
        $countSql = "SELECT * FROM $tblNam where ProcessType='$ProcessType' and AccessRoleID='$PositionCode' and ServiceType='$ServiceType' and Enable = 'Y'";
        $isAvailable = $db->rowAvailable($countSql);
        if ($isAvailable == 1) {
            $msg = "Record already exist.";
        } else {
            $sqlA = "SELECT AccessRoleValue
FROM MOENational.dbo.CD_AccessRoles
WHERE (AccessRoleID = $PositionCode)";

            $resA = $db->runMsSqlQuery($sqlA);
            $rowA = sqlsrv_fetch_array($resA, SQLSRV_FETCH_ASSOC);
            $accLevel = $rowA['AccessRoleValue'];

            $queryMainSave = "INSERT INTO TG_ApprovalProcessMain
			   (ProcessType,AccessRoleValue,Enable,AccessRoleID,ServiceType)
		 VALUES
			   ('$ProcessType','$accLevel','$Enable','$PositionCode','$ServiceType')";
            // $db->runMsSqlQuery($queryMainSave);
            $IDMain = $db->runMsSqlQueryInsert($queryMainSave);

            /* $procMainID = "SELECT ID FROM TG_ApprovalProcessMain where ProcessType='$ProcessType' and AccessRoleID='$PositionCode'";
              $stmtMain = $db->runMsSqlQuery($procMainID);
              while ($row = sqlsrv_fetch_array($stmtMain, SQLSRV_FETCH_ASSOC)) {
              $IDMain = $row['ID'];
              } */

            for ($i = 1; $i < $noofSteps + 1; $i++) {
                $fldName = "ApprovePositionCode" . $i;
                $fldName1 = "ApprovePositionNominiCode" . $i;
                $fldName2 = "appProcessName" . $i;
                $ApprovePositionCode = $_REQUEST[$fldName];
                $ApprovePositionNominiCode = $_REQUEST[$fldName1];
                // $ApprovePositionName = $_REQUEST[$fldName2];
                $ApproveOrder = $i;

                $sqlA = "SELECT AccessRoleValue,AccessRole
FROM MOENational.dbo.CD_AccessRoles
WHERE (AccessRoleID = $ApprovePositionCode)";

                $resA = $db->runMsSqlQuery($sqlA);
                $rowA = sqlsrv_fetch_array($resA, SQLSRV_FETCH_ASSOC);
                $approveAccLevel = $rowA['AccessRoleValue'];
                $ApprovePositionName = $rowA['AccessRole'];

                $sqlAN = "SELECT AccessRoleValue,AccessRole
FROM MOENational.dbo.CD_AccessRoles
WHERE (AccessRoleID = $ApprovePositionNominiCode)";

                $resAN = $db->runMsSqlQuery($sqlAN);
                $rowAN = sqlsrv_fetch_array($resAN, SQLSRV_FETCH_ASSOC);
                $approveAccLevelN = $rowAN['AccessRoleValue'];
                $ApprovePositionNameN = $rowAN['AccessRole'];

                $queryGradeSave = "INSERT INTO TG_ApprovalProcess
			   (ApprovalProcMainID,ApproveOrder,ApproveAccessRoleValue,ApproveAccessRoleName,Enable,ApproveAccessRoleID,ApproveAccessRoleNominiValue,ApproveAccessRoleNominiName)
		 VALUES
			   ('$IDMain','$ApproveOrder','$approveAccLevel','$ApprovePositionName','$Enable','$ApprovePositionCode','$approveAccLevelN','$ApprovePositionNameN')";

                $res = $db->runMsSqlQuery($queryGradeSave);
                if ($res)
                    $msg = "Successfully Updated.";
            }
        }
    }


    //sqlsrv_query($queryGradeSave);
}
$TotaRows = $db->rowCount($countTotal);
?>


<div class="main_content_inner_block">
    <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if ($msg != '') {//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){   ?>   
            <div class="mcib_middle1">
                <div class="mcib_middle_full">
                    <div class="form_error"><?php
                        echo $msg;
                        echo $_SESSION['success_update'];
                        $_SESSION['success_update'] = "";
                        ?><?php
                        echo $_SESSION['fail_update'];
                        $_SESSION['fail_update'] = "";
                        ?></div>
                </div>
<?php } ?>
            <table width="945" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="77%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="25%">Process Type :</td>
                                <td width="75%"><select name="ProcessType" class="select5" id="ProcessType">
                                        <option value="">-Select-</option>
                                        <!--<option value="Leave">Leave</option>-->
                                        <?php
                                        $sql = "SELECT LeaveCode,Description FROM CD_LeaveType order by Description";
                                        $stmt = $db->runMsSqlQuery($sql);
                                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                            echo '<option value=' . $row['LeaveCode'] . '>' . $row['Description'] . '</option>';
                                        }
                                        ?>
                                        <option value="NewRegistration">New Registration</option>
                                        <option value="Retirement">Retirement</option>
                                        <option value="TransferTeacherNormal">Transfer Teacher Normal</option>
                                        <option value="TransferTeacherNational">Transfer Teacher National</option>
                                        <option value="TransferPrincipleNormal">Transfer Principle Normal</option>
                                        <option value="TransferPrincipleNational">Transfer Principle National</option>

                                        <option value="VacancyTeacherNormal">Vacancy Teacher Normal</option>
                                        <option value="VacancyPrincipleNormal">Vacancy Principle Normal</option>
                                        <option value="VacancyTeacherNational">Vacancy Teacher National</option>
                                        <option value="VacancyPrincipleNational">Vacancy Principle National</option>

                                        <option value="TeacherQualification">Teacher Qualification</option>
                                        <option value="RequestTeacherTraining">Request Teacher Training</option>

                                        <option value="ApplyForTraining">Apply For Training</option>

                                        <option value="TeacherIncrement">Teacher Increment Request</option>
                                        <option value="PrincipalIncrement">Principal Increment Request</option>

                                    </select></td>
                            </tr>
                            <tr>
                                <td>Process For :</td>
                                <td><?php //echo $NICUser ?><select class="select2a_n" id="PositionCode" name="PositionCode">
                                        <?php
                                        $sql = "SELECT AccessRole, AccessRoleID,AccessRoleValue
FROM CD_AccessRoles ORDER BY AccessRoleID";
                                        $stmt = $db->runMsSqlQuery($sql);
                                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                            $AccessRoleValue = trim($row['AccessRoleValue']);
                                            if ($AccessRoleValue == '1000' || $AccessRoleValue == '3000') {
                                                echo '<option value=' . $row['AccessRoleID'] . '>' . $row['AccessRole'] . '</option>';
                                            }
                                        }
                                        ?>
                                    </select>


                                </td>
                            </tr>
                            <tr>
                                <td>Service Type :</td>
                                <td><select name="ServiceType" class="select5" id="ServiceType">
                                        <option value="">-Select-</option>
                                        <!--<option value="Leave">Leave</option>-->
                                        <?php
                                        $sql = "SELECT ServCode,ServiceName FROM CD_Service order by ServCode";
                                        $stmt = $db->runMsSqlQuery($sql);
                                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                            echo '<option value=' . $row['ServCode'] . '>' . $row['ServiceName'] . '</option>';
                                        }
                                        ?>

                                    </select></td>
                            </tr>
                            <tr>
                                <td>Number of Steps :</td>
                                <td><select name="noofSteps" class="select2" id="noofSteps"  onchange="Javascript:show_steps('approsteps', this.options[this.selectedIndex].value, '<?php echo $id ?>');">
                                        <option value="">-Select-</option>
                                        <?php
                                        for ($n = 1; $n < 11; $n++) {
                                            $seltebr = "";
                                            if ($iApoProLevels == $n)
                                                $seltebr = "selected";
                                            ?>
                                            <option value="<?php echo $n ?>" <?php echo $seltebr ?> ><?php echo $n ?></option>
<?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div id="txt_applevel">
                                        <table width="100%" cellspacing="1" cellpadding="1">
                                            <tr>
                                                <td width="25%">Step :</td>
                                                <td width="75%"> Select the number of steps.

                                                    <!--comment start 19-06-2014 Thushara   -->
                                                    <?php //if ($fm == 'DAD') {  ?>
                                                            <!--<select class="select2a_n" id="PositionCode" name="PositionCode">-->
                                                    <?php
                                                    /* $sql = "SELECT Code, PositionName      
                                                      FROM CD_Positions order by PositionName";
                                                      $stmt = $db->runMsSqlQuery($sql);
                                                      while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                      echo '<option value=' . $row['Code'] . '>' . $row['PositionName'] . '</option>';
                                                      } */
                                                    ?>
                                                    <!--</select>-->
<?php //}   ?>
                                                    <!-- comment end  -->
                                                </td>
                                            </tr>
                                        </table>
                                    </div></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                            </tr>
                        </table>
                    </td>
                    <td width="23%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="43%" align="left" valign="top">&nbsp;</td>
                                <td width="57%">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2">&nbsp;</td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td><?php echo $TotaRows ?> Record(s) found.</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                            <tr>
                                <td width="5%" height="25" align="center" bgcolor="#999999">#</td>
                                <td width="18%" align="center" bgcolor="#999999">Process Type</td>
                                <td width="24%" align="center" bgcolor="#999999">Process For</td>
                                <td width="44%" align="center" bgcolor="#999999">Approval Process</td>
                                <td width="9%" align="center" bgcolor="#999999">Delete</td>
                            </tr>
                            <?php
                            $sqlList = "SELECT TG_ApprovalProcessMain.ID, TG_ApprovalProcessMain.ProcessType, TG_ApprovalProcessMain.ServiceType, CD_AccessRoles.AccessRole, TG_ApprovalProcessMain.AccessRoleID
FROM TG_ApprovalProcessMain 
INNER JOIN CD_AccessRoles ON TG_ApprovalProcessMain.AccessRoleID = CD_AccessRoles.AccessRoleID
WHERE (TG_ApprovalProcessMain.Enable = 'Y')
ORDER BY TG_ApprovalProcessMain.ID";

                            $i = 1;
                            $stmtList = $db->runMsSqlQuery($sqlList);
                            while ($row = sqlsrv_fetch_array($stmtList, SQLSRV_FETCH_ASSOC)) {

                                $Expr1 = $row['ID'];
                                $ServiceType = trim($row['ServiceType']);
                                $sql = "SELECT ServCode,ServiceName FROM CD_Service Where ServCode='$ServiceType'";
                                $stmt = $db->runMsSqlQuery($sql);
                                $rowSt = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

                                $LeaveCoded = trim($row['ProcessType']);
                                $sqld = "SELECT Description FROM CD_LeaveType Where LeaveCode='$LeaveCoded'";
                                $stmtd = $db->runMsSqlQuery($sqld);
                                $rowStd = sqlsrv_fetch_array($stmtd, SQLSRV_FETCH_ASSOC);
                                $Description = $rowStd['Description'];
                                ?>
                                <tr>
                                    <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                                    <td bgcolor="#FFFFFF"><?php echo $row['ProcessType'];
                                if ($Description != '') {
                                    echo " - $Description";
                                } ?></td>
                                    <td bgcolor="#FFFFFF"><?php echo $row['AccessRole']; ?> [<?php echo $rowSt['ServiceName']; ?>]</td>
                                    <td bgcolor="#FFFFFF">
                                        <?php
                                        $stepSQL = "SELECT TG_ApprovalProcess.ApproveAccessRoleName
FROM TG_ApprovalProcess
WHERE (TG_ApprovalProcess.ApprovalProcMainID = $Expr1)
ORDER BY TG_ApprovalProcess.ApproveOrder";
                                        $stepsProc = "";

                                        $rowCount = $db->rowCount($stepSQL);
                                        $stmt2 = $db->runMsSqlQuery($stepSQL);
                                        $a = 1;
                                        while ($row1 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
                                            $stepsProc .= $row1['ApproveAccessRoleName'];
                                            if ($a < $rowCount) {
                                                $stepsProc .= " > ";
                                            }
                                            $a++;
                                        }echo $stepsProc;
                                        ?>

                                    </td>
                                    <td bgcolor="#FFFFFF" align="center"><a href="javascript:aedWin('<?php echo $Expr1 ?>','D','Approval','<?php echo $tblNam ?>','<?php echo "approvalProcess-1.html"; ?>')">Delete
                                <?php //echo $Expr1    ?>
                                        </a></td>
                                </tr>
<?php } ?>
                            <tr>
                                <td bgcolor="#FFFFFF">&nbsp;</td>
                                <td bgcolor="#FFFFFF">&nbsp;</td>
                                <td bgcolor="#FFFFFF">&nbsp;</td>
                                <td bgcolor="#FFFFFF">&nbsp;</td>
                                <td bgcolor="#FFFFFF">&nbsp;</td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
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