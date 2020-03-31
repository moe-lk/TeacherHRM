<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
$msg = "";

// ***
// save user privilages
if (isset($_POST["FrmSubmit"])) {
    $AccessRoleID = $_REQUEST['AccessRoleID'];
    $arrprivilages = $_REQUEST['privilages'];
    
    if(count($arrprivilages)>0){
     $query = "DELETE FROM TG_Privilage WHERE AccessRoleID='$AccessRoleID'";
    $db->runMsSqlQuery($query);
    }
    
    
    for ($t = 0; $t < count($arrprivilages); $t++) {
        
        $queryIn = "INSERT INTO TG_Privilage (AccessRoleID,FormID,Privilage)
			 VALUES ('$AccessRoleID','$arrprivilages[$t]','1,2,3')";
        $db->runMsSqlQuery($queryIn);
    } //echo $tblName;exit();    
}

$sqlSrch = "SELECT * FROM CD_AccessRoles where AccessRoleID='$menu'";
$stmtP = $db->runMsSqlQuery($sqlSrch);
$rowP = sqlsrv_fetch_array($stmtP, SQLSRV_FETCH_ASSOC);
$AccessRoleValue = trim($rowP['AccessRoleValue']);
$AccessRole = trim($rowP['AccessRole']);
$AccessRoleID = trim($rowP['AccessRoleID']);
// **
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
                    <td width="93%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">

                            <tr>
                                <td width="15%">Access Role <span class="form_error">*</span>:</td>
                                <td width="1%" valign="top">:</td>
                                <td width="84%"><?php echo $AccessRole ?>
                                    <input type="hidden" name="AccessRoleID" value="<?php echo $menu ?>" />
                            </tr>
                            <tr>
                                <td>Code</td>
                                <td valign="top">:</td>
                                <td><?php echo $AccessRoleValue ?></td>
                            </tr>
                            <tr>
                                <td valign="top">Privileges</td>
                                <td valign="top">:</td>
                                <td><table width="100%" cellspacing="1" cellpadding="1">
                                        <tr style="background-color: #AFAFAF;">
                                            <td width="4%" style="padding-left: 8px; padding-top: 5px; padding-bottom: 5px;">#</td>
                                            <td width="89%" style="padding-left: 8px; padding-top: 5px; padding-bottom: 5px;" align="center"><b>Form Name</b></td>
                                            <td width="7%" style="padding-left: 8px; padding-top: 5px; padding-bottom: 5px;">&nbsp;</td>
                                        </tr>

                                        <?php
                                        // get privliages for selected user type
                                        $sqlPr = "SELECT TG_Privilage.ID, TG_Privilage.FormID, TG_Privilage.Privilage, TG_Privilage.AccessRoleID FROM TG_Privilage WHERE TG_Privilage.AccessRoleID = $AccessRoleID";
                                        $stmt = $db->runMsSqlQuery($sqlPr);
                                        $arrFormID = array();
                                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                            $arrFormID[] = $row['FormID'];
                                        }

                                        // get dynamic menu 
                                        $sqlDyn = "SELECT
TG_DynMenu.ID,
TG_DynMenu.Icon,
TG_DynMenu.Title,
TG_DynMenu.PageID,
TG_DynMenu.Url,
TG_DynMenu.ParentID,
TG_DynMenu.IsParent,
TG_DynMenu.ShowMenu,
TG_DynMenu.ParentOrder,
TG_DynMenu.ChildOrder,
TG_DynMenu.FOrder,
TG_DynMenu.IsSubParent
FROM
TG_DynMenu
WHERE
TG_DynMenu.ShowMenu = 1
ORDER BY
TG_DynMenu.FOrder ASC";
                                        $stmt = $db->runMsSqlQuery($sqlDyn);
                                        $count = 1;
                                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                            if($count%2==0){
                                                $color = '';
                                            }else{
                                                $color = '#F6F6F6';
                                            }
                                            ?>
                                        <tr style="background-color: <?php echo $color; ?>;">
                                            <td style="padding-left: 8px; padding-top: 5px; padding-bottom: 5px;"><?php echo $count++; ?></td>
                                                <td style="padding-left: 8px; padding-top: 5px; padding-bottom: 5px;"><?php
                                        if ($row['ParentID'] != 0) {
                                            if($row['IsSubParent'] == 1){
                                                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $row['Title'];
                                            }else{
                                                echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $row['Title'];
                                            }
                                            
                                        } else {
                                            if($row['IsSubParent'] == 1){
                                                echo '&nbsp;&nbsp;&nbsp;' . $row['Title'];
                                            }else{
                                                echo $row['Title'];
                                            }
                                        }
                                            ?></td>
                                                <td style="padding-left: 8px; padding-top: 5px; padding-bottom: 5px;"><input type="checkbox" value="<?php echo $row['ID']; ?>" name="privilages[]" <?php if (in_array($row['ID'], $arrFormID)) { ?> checked="check" <?php } ?>></td>
                                            </tr>
    <?php
}
?>
                                        <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </table></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                            </tr>
                        </table>
                    </td>
                    <td width="7%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="43%" align="left" valign="top">&nbsp;</td>
                                <td width="57%">&nbsp;</td>
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
</div>