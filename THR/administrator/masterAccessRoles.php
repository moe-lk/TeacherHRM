<!----><link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
$msg = "";
if (isset($_POST["FrmSrch"]) || $fm == '') {
    $AccessRoleSrc = $_REQUEST['AccessRoleSrc'];
    $sqlSrch = "SELECT * FROM CD_AccessRoles where AccessRole!=''";
    if ($AccessRoleSrc)
        $sqlSrch .= " and AccessRole like '%$AccessRoleSrc%'";
    $stmtP = $db->runMsSqlQuery($sqlSrch);
    $TotaRows = $db->rowCount($sqlSrch);
    if ($TotaRows == 0)
        $fm = "A";
    //echo $sqlSrch;
    //$rowP = sqlsrv_fetch_array($stmtP, SQLSRV_FETCH_ASSOC);
    //echo $TotaRows=$db->rowCount($stmtP);echo $sqlSrch;
}
if ($fm == 'E') {
    $sqlSrch = "SELECT * FROM CD_AccessRoles where AccessRoleID='$id'";
    $stmtE = $db->runMsSqlQuery($sqlSrch);
    $rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC);
    $AccessRoleValue = $rowE['AccessRoleValue'];
    $AccessRole = trim($rowE['AccessRole']);
    $AccessRoleType = trim($rowE['AccessRoleType']);
    $HigherLevel = trim($rowE['HigherLevel']);
    $ControlLevel = trim($rowE['ControlLevel']);
}

if (isset($_POST["FrmSubmit"])) {
    //echo "hi";
    $addEdit = $_REQUEST['AED'];
    $vID = $_REQUEST['vID'];
    $AccessRoleValue = $_REQUEST['AccessRoleValue'];
    $AccessRole = trim($_REQUEST['AccessRole']);
    $AccessRoleType = trim($_REQUEST['AccessRoleType']);
    $HigherLevel = $_REQUEST['HigherLevel'];
    $ControlLevel = $_REQUEST['ControlLevel'];
    
    
    

    $HighL = implode(',', $HigherLevel);
   
    //$HighL .= "," . $matches . ",";
    $ConL = implode(',', $ControlLevel);
    
    //$ConL .= "," . $matchesCon . ",";

    $dateU = date('Y-m-d H:i:s');
    if ($addEdit == "A")
        $UpdateBy = "Add by $NICUser";
    if ($addEdit == "E")
        $UpdateBy = "Edit by $NICUser";

    if ($AccessRole == "") {
        $msg .= "Please enter the Access Role Name.<br>";
    }
    if ($msg == '') {

        if ($addEdit == 'A') {
            $countSql = "SELECT * FROM CD_AccessRoles where AccessRoleValue='$AccessRoleValue'";
            $isAvailable = $db->rowAvailable($countSql);
            if ($isAvailable == 1) {
                $msg .= "Duplicate Access Role Code.<br>";
            } else {
                $queryMainSave = "INSERT INTO CD_AccessRoles
				   (AccessRoleValue,AccessRole,AccessRoleType,NoOfNominators,UpdateBy,LastUpdate,HigherLevel,ControlLevel)
			 VALUES
				   ('$AccessRoleValue','$AccessRole','$AccessRoleType','0','$UpdateBy','$dateU','$HighL','$ConL')";
                $db->runMsSqlQuery($queryMainSave);
            }
        } else if ($addEdit == 'E') {
            $queryMainUpdate = "UPDATE CD_AccessRoles SET AccessRoleValue='$AccessRoleValue',AccessRole='$AccessRole',AccessRoleType='$AccessRoleType',NoOfNominators='0', UpdateBy='$UpdateBy', LastUpdate='$dateU',HigherLevel='$HighL',ControlLevel='$ConL' WHERE AccessRoleID='$vID'";

            $db->runMsSqlQuery($queryMainUpdate);
        }
    }
    $fm = "";
    $sqlSrch = "SELECT * FROM CD_AccessRoles where AccessRole!=''";
    $stmtP = $db->runMsSqlQuery($sqlSrch);
}
?>
<form method="post" action="<?php echo $ttle ?>-11-<?php echo $menu ?>.html" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
    <?php if ($msg != '') {//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){    ?>   

        <div class="mcib_middle1" style="width:700px;">
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
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td valign="top" style="border-bottom:1px; border-bottom-style:solid;"><table width="100%" cellspacing="2" cellpadding="2">

                        <tr>
                            <td width="24%">Access Role Name :</td>
                            <td width="36%"><input name="AccessRoleSrc" type="text" class="input2_n" id="AccessRoleSrc" value="<?php echo $AccessRoleSrc ?>"/></td>
                            <td width="4%"><input name="FrmSrch" type="submit" id="FrmSrch" style="background-image: url(../cms/images/searchN.png); width:84px; height:26px; background-color:transparent; border:none; cursor:pointer;" value="" /></td>
<!--                            <td width="14%" align="right" valign="middle" style="padding-top:7px;"><a href="masterFile-11-<?php echo $menu ?>--A.html"><img src="../cms/images/addnew.png" alt="" width="90" height="26" /></a></td>-->
                            <td width="4%" align="right" valign="middle" style="padding-top:5px;"><a href="masterFile-11-<?php echo $menu ?>.html"><img src="../cms/images/clearN.png" alt="" width="80" height="26" /></a></td>
                        </tr>
                    </table></td>
            </tr>
            <tr>
                <td valign="top"><span style="color:#090; font-weight:bold;"><?php
                        if ($fm == 'A')
                            echo "Insert the data";
                        if ($fm == 'E')
                            echo "Modify the existing details";
                        ?></span>&nbsp;</td>
            </tr>

            <tr>
                <td width="56%" valign="top">
                    <?php if ($fm == 'E' || $fm == 'A') { ?>
                        <table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="25%">Code <span class="form_error_sched">*</span></td>
                                <td width="2%">:</td>
                                <td width="73%">
                                    <input name="AccessRoleValue" type="text" class="input3" id="AccessRoleValue" value="<?php echo $AccessRoleValue ?>" readonly="readonly"/>
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
                                <td>Access Role Name<span class="form_error_sched">*</span></td>
                                <td>:</td>
                                <td><input name="AccessRole" type="text" class="input2" id="AccessRole" value="<?php echo $AccessRole ?>"/></td>
                            </tr>
                            <tr>
                                <td>Access Role Type</td>
                                <td>:</td>
                                <td>
                                    <input name="AccessRoleType" type="text" class="input3" id="AccessRoleType" value="<?php echo $AccessRoleType ?>" readonly="readonly"/>
                                    
<!--                                    <select class="select5" id="AccessRoleType" name="AccessRoleType" >
                                        <option disabled="disabled" value="">-Select-</option>
                                        <option disabled="disabled" value="ED" <?php if ($AccessRoleType == 'ED') echo "selected"; ?>>ED</option>

                                        <option disabled="disabled" value="DN" <?php if ($AccessRoleType == 'DN') echo "selected"; ?>>DN</option>
                                        <option disabled="disabled" value="MO" <?php if ($AccessRoleType == 'MO') echo "selected"; ?>>MO</option>
                                        <option disabled="disabled" value="NC" <?php if ($AccessRoleType == 'NC') echo "selected"; ?>>NC</option>
                                        <option disabled="disabled" value="PD" <?php if ($AccessRoleType == 'PD') echo "selected"; ?>>PD</option>
                                        <option disabled="disabled" value="SC" <?php if ($AccessRoleType == 'SC') echo "selected"; ?>>SC</option>
                                        <option disabled="disabled" value="ZN" <?php if ($AccessRoleType == 'ZN') echo "selected"; ?>>ZN</option>


                                    </select>-->
                                
                                </td>
                            </tr>


                            <tr>
                                <td>Control Users</td>
                                <td>:</td>
                                <td>
                                    <select style=" color:#666666; width:327px;" name="ControlLevel[]" size="11" multiple="multiple" class="input_d1" id="ControlLevel">

                                        <?php
                                        $sqlDyn = "SELECT
CD_AccessRoles.AccessRoleID,
CD_AccessRoles.AccessRole
FROM
dbo.CD_AccessRoles";
                                        $stmt = $db->runMsSqlQuery($sqlDyn);
                                         $arrControlLevel = explode(',', $ControlLevel);
                                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                            ?>

                                            <option value="<?php echo $row['AccessRoleID']; ?>" 
                                            <?php
                                            for ($n = 0; $n < count($arrControlLevel); $n++) {
                                                $SelectedCL = trim($arrControlLevel[$n]);
                                                if ($row['AccessRoleID'] == $SelectedCL) {
                                                    echo 'selected="selected"';
                                                } else {
                                                    echo "";
                                                }
                                            }
                                            ?>
                                                    ><?php echo $row['AccessRole']; ?></option>
                                                    <?php
                                                }
                                                ?>

                                    </select>
                                </td>
                            </tr>

                            <!--<tr>
                                <td>Higher Levels</td>
                                <td>:</td>
                                <td>
                                    <select style=" color:#666666; width:327px;" name="HigherLevel[]" size="11" multiple="multiple" class="input_d1" id="HigherLevel">

                                        <?php
                                        $sqlDyn = "SELECT
CD_AccessRoles.AccessRoleID,
CD_AccessRoles.AccessRole
FROM
dbo.CD_AccessRoles";
                                        $stmt = $db->runMsSqlQuery($sqlDyn);

                                        // $HigherLevel
                                        $arrHigherLevel = explode(',', $HigherLevel);
                                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                            ?>

                                            <option value="<?php echo $row['AccessRoleID']; ?>" 
                                            <?php
                                            for ($n = 0; $n < count($arrHigherLevel); $n++) {
                                                $SelectedHL = trim($arrHigherLevel[$n]);
                                                if ($row['AccessRoleID'] == $SelectedHL) {
                                                    echo 'selected="selected"';
                                                } else {
                                                    echo "";
                                                }
                                            }
                                            ?>
                                                    ><?php echo $row['AccessRole']; ?></option>
                                                    <?php
                                                }
                                                ?>

                                    </select>
                                </td>
                            </tr>-->
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                            </tr>
                        </table>
                    <?php } ?>
                </td>
            </tr>
            <?php if (isset($_POST["FrmSrch"]) || $fm == '') { ?>
                <tr>
                    <td><?php echo $TotaRows ?> Record(s) found.</td>
                </tr>
                <tr>
                    <td bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                            <tr>
                                <td width="7%" height="25" align="center" bgcolor="#999999">#</td>
                                <td width="17%" align="center" bgcolor="#999999">Code</td>
                                <td width="56%" align="center" bgcolor="#999999">Access Role Name</td>
                                <td width="20%" align="center" bgcolor="#999999">Privileges</td>
                                <td width="20%" align="center" bgcolor="#999999">Modify</td>
                            </tr>
                            <?php
                            $i = 1;
                            while ($rowP = sqlsrv_fetch_array($stmtP, SQLSRV_FETCH_ASSOC)) {
                                $AccessRoleValue = trim($rowP['AccessRoleValue']);
                                $AccessRole = trim($rowP['AccessRole']);
                                $AccessRoleID = trim($rowP['AccessRoleID']);
                                ?>
                                <tr>
                                    <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                                    <td bgcolor="#FFFFFF"><?php echo $AccessRoleValue ?></td>
                                    <td bgcolor="#FFFFFF"><?php echo $AccessRole ?></td>
                                    <td bgcolor="#FFFFFF" align="center"><a href="userPrivilages-13-<?php echo $AccessRoleID ?>.html" target="_blank">Click</a></td>
                                    <td bgcolor="#FFFFFF" align="center"><a href="<?php echo "$ttle-$pageid-$menu-$AccessRoleID-E.html"; ?>">Click</a></td>
                                </tr>
                            <?php } ?>
                        </table></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            <?php } ?>
        </table>
    </div>

</form>