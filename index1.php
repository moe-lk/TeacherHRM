<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />

<link rel="stylesheet" type="text/css" href="verticalmenu/styles.css">
<link rel="stylesheet" type="text/css" href="verticalmenu/css.css">
<script type="text/javascript" src="verticalmenu/jquery.js"></script>
<script type="text/javascript" language="javascript" charset="utf-8" src="verticalmenu/nav.js"></script>

<style type="text/css">

    /* by duminda 2015-10-07 left menu */
    .menuItemSelected{
        float:left;
        width:194px;
        padding:2px;
        font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
        font-size:12px;
        line-height:24px;
        color:#FFF;
        border-radius: 2px;
        background-color:#900;

    }
    .menuItem{
        float:left;
        width:194px;
        padding:2px;
        font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
        font-size:12px;
        line-height:24px;
        color:#FFF;
        border-radius: 2px;
        background-color:#A4B6FF;

    }
</style>

<?php
$SeeHigherLevel = $_SESSION['SeeHigherLevel'];
$SeeControlLevel = $_SESSION['SeeControlLevel'];
$AccessRoleType = $_SESSION['AccessRoleType'];



//echo $accLevel;
?>
<!--menu-->
<div class="masterFile" style="float:left;">
    <nav>
        <ul id="nav">
            <?php
            $arrPageID = array();
            $AccessRoleID = $_SESSION['AccessRoleID'];


// get all parent menu records for looged user
// 2 = approval in TG_DynMenu
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
TG_DynMenu.FOrder

FROM
TG_DynMenu
INNER JOIN TG_Privilage ON TG_DynMenu.ID = TG_Privilage.FormID
WHERE
TG_DynMenu.ParentID = 2 AND
TG_DynMenu.IsParent = 1 AND
TG_DynMenu.ShowMenu = 1 AND
TG_Privilage.AccessRoleID = $AccessRoleID";
            $stmt = $db->runMsSqlQuery($sqlDyn);
            $count = 0;
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $count++;
                $rowid = $row['ID'];
                $icon = $row['Icon'];
                $title = $row['Title'];
                $page_id = $row['PageID'];
                $url = $row['Url'];
                $parent_id = $row['ParentID'];
                $is_parent = $row['IsParent'];
                $show_menu = $row['ShowMenu'];



                // get pages for acctive class
                $sql_child = "SELECT
TG_DynMenu.ID,
TG_DynMenu.Icon,
TG_DynMenu.Title,
TG_DynMenu.PageID,
TG_DynMenu.Url,
TG_DynMenu.ParentID,
TG_DynMenu.PHPPage
FROM
TG_DynMenu
INNER JOIN TG_Privilage ON TG_DynMenu.ID = TG_Privilage.FormID
WHERE
TG_DynMenu.ParentID = $rowid AND
TG_DynMenu.IsParent = 0 AND
TG_DynMenu.ShowMenu = 1 AND
TG_Privilage.AccessRoleID = $AccessRoleID";

                $stmtChild = $db->runMsSqlQuery($sql_child);

                $block_ul = "";
                $arrPID = array();
                while ($rowCh = sqlsrv_fetch_array($stmtChild, SQLSRV_FETCH_ASSOC)) {
                    $arrPID[] = $rowCh['PageID'];
                }


                $class_active = "";

                if (in_array($pageid, $arrPID)) {
                    $block_ul = "style='display: block'";
                    $class_active = "open";
                }
                //**
                ?>

                <li>
                    <a class="<?php echo $class_active; ?>" href="#">
                        <?php echo $title; ?>

                        <?php
                        $totCount = 0;
                        $sql_count = "SELECT
TG_DynMenu.ID,
TG_DynMenu.Icon,
TG_DynMenu.Title,
TG_DynMenu.PageID,
TG_DynMenu.Url,
TG_DynMenu.ParentID,
TG_DynMenu.PHPPage
FROM
TG_DynMenu
INNER JOIN TG_Privilage ON TG_DynMenu.ID = TG_Privilage.FormID
WHERE
TG_DynMenu.ParentID = $rowid AND
TG_DynMenu.IsParent = 0 AND
TG_DynMenu.ShowMenu = 1 AND
TG_Privilage.AccessRoleID = $AccessRoleID";

                        $stmtCount = $db->runMsSqlQuery($sql_count);
                        while($rowC = sqlsrv_fetch_array($stmtCount, SQLSRV_FETCH_ASSOC)){
                            $totCount += get_records_count($rowC['PageID'], $db, $loggedSchool, $nicNO, $accLevel);
                        }

                        if($totCount>0){

                        ?>


                        <div style="width:32px; height:32px; float:right; margin-top:-5px;">
                            <img src="../cms/images/new.png" />
                        </div>
                        <?php
                        }
                        ?>
                    </a>
    <?php
    $sql_child = "SELECT
TG_DynMenu.ID,
TG_DynMenu.Icon,
TG_DynMenu.Title,
TG_DynMenu.PageID,
TG_DynMenu.Url,
TG_DynMenu.ParentID,
TG_DynMenu.PHPPage
FROM
TG_DynMenu
INNER JOIN TG_Privilage ON TG_DynMenu.ID = TG_Privilage.FormID
WHERE
TG_DynMenu.ParentID = $rowid AND
TG_DynMenu.IsParent = 0 AND
TG_DynMenu.ShowMenu = 1 AND
TG_Privilage.AccessRoleID = $AccessRoleID";

    $stmtChild = $db->runMsSqlQuery($sql_child);



    echo "<ul $block_ul>";
    while ($rowCh = sqlsrv_fetch_array($stmtChild, SQLSRV_FETCH_ASSOC)) {

        $record_count = get_records_count($rowCh['PageID'], $db, $loggedSchool, $nicNO, $accLevel);

        $iconCh = $rowCh['Icon'];
        $titleCh = $rowCh['Title'];
        $urlCh = $rowCh['Url'];
        $arrPageID[] = array($rowCh['PageID'], $rowCh['PHPPage']);
        $class_activech = "";
        if ($pageid == $rowCh['PageID']) {
            $class_activech = "activeLink";
        }
        ?>
                    <li class="<?php echo $class_activech; ?>">
                        <a href="<?php echo $urlCh; ?>">
                            <div style="width:20px; height:20px; float:left;"><img src="../cms/images/arrow.png" /></div><?php
                echo $titleCh;
                if($record_count>0){ echo " (".$record_count.")"; }
                ?>
                        </a>
                    </li>
                            <?php
                        }
                        echo "</ul>";
                        ?>
                </li>
                <?php
            }
            ?>

        </ul>
    </nav>
</div>
<!--end menu-->
<div class="main_content_inner_block" style="width:736px; height:auto; float:left; margin-left:10px; border:thick; border-color:#666; border-width:1px; border-style:solid; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; padding-left:5px; padding-right:5px;">

<?php
// include related php pages
for ($index = 0; $index < count($arrPageID); $index++) {
    if ($pageid == $arrPageID[$index][0] ) {
        include_once $arrPageID[$index][1];
    }
}
?>

</div>

<?php

function get_records_count($pageId, $db, $loggedSchool, $nicNO, $accLevel) {

    // new registration
    if ($pageId == '16') {
        $sql = "SELECT        TG_EmployeeRegister.ID, TG_EmployeeRegister.NIC, TG_EmployeeRegister.TeacherMastID, TG_EmployeeRegister.ServisHistCurrentID,
							 TG_EmployeeRegister.ServisHistFirstID, TG_EmployeeRegister.AddressHistID, CONVERT(varchar(20), TG_EmployeeRegister.dDateTime, 121) AS dDateTime,
							 TG_EmployeeRegister.IsApproved, ArchiveUP_TeacherMast.SurnameWithInitials, CD_Title.TitleName, CD_Zone.InstitutionName, CD_Districts.DistName
	FROM            ArchiveUP_TeacherMast LEFT JOIN
							 TG_EmployeeRegister ON ArchiveUP_TeacherMast.ID = TG_EmployeeRegister.TeacherMastID LEFT JOIN
							 CD_Title ON ArchiveUP_TeacherMast.Title = CD_Title.TitleCode LEFT JOIN
							 CD_Zone ON TG_EmployeeRegister.ZoneCode = CD_Zone.CenCode LEFT JOIN
							 CD_Districts ON CD_Zone.DistrictCode = CD_Districts.DistCode
							 WHERE TG_EmployeeRegister.IsApproved='N'";

        if ($_SESSION['AccessRoleType'] == 'ZN') {
            $sql .= " and TG_EmployeeRegister.ZoneCode='$loggedSchool'";
        }

        if ($_SESSION['AccessRoleType'] == 'PD') {
            $sql .= " and TG_EmployeeRegister.ZoneCode IN ( SELECT CD_Zone.CenCode
                        FROM CD_Provinces INNER JOIN CD_Districts ON CD_Provinces.ProCode = CD_Districts.ProCode
                        INNER JOIN CD_Zone ON CD_Zone.DistrictCode = CD_Districts.DistCode
                        WHERE CD_provinces.ProCode = CONCAT('P', SUBSTRING('$loggedSchool',3,2)))";
        }

        $rowCount = $db->rowCount($sql);
        if ($rowCount > 0) {
            return $rowCount;
        }
    }
    // Personal Info
    if ($pageId == '15') {

        $sql = "SELECT        TG_EmployeeUpdatePersInfo.ID, TG_EmployeeUpdatePersInfo.NIC, TG_EmployeeUpdatePersInfo.TeacherMastID, TG_EmployeeUpdatePersInfo.PermResiID,
							 TG_EmployeeUpdatePersInfo.CurrResID, CONVERT(varchar(20), TG_EmployeeUpdatePersInfo.dDateTime, 121) AS dDateTime,
							 TG_EmployeeUpdatePersInfo.IsApproved, UP_TeacherMast.SurnameWithInitials, CD_Title.TitleName, CD_Zone.InstitutionName, CD_Districts.DistName
	FROM            UP_TeacherMast INNER JOIN
							 TG_EmployeeUpdatePersInfo ON UP_TeacherMast.ID = TG_EmployeeUpdatePersInfo.TeacherMastID INNER JOIN
							 CD_Title ON UP_TeacherMast.Title = CD_Title.TitleCode INNER JOIN
							 CD_Zone ON TG_EmployeeUpdatePersInfo.ZoneCode = CD_Zone.CenCode INNER JOIN
							 CD_Districts ON CD_Zone.DistrictCode = CD_Districts.DistCode
							 WHERE TG_EmployeeUpdatePersInfo.IsApproved='N'";

        if ($_SESSION['AccessRoleType'] == 'ZN') {
            $sql .= " and TG_EmployeeUpdatePersInfo.ZoneCode='$loggedSchool'";
        }

        if ($_SESSION['AccessRoleType'] == 'PD') {
            $sql .= " and TG_EmployeeUpdatePersInfo.ZoneCode IN ( SELECT CD_Zone.CenCode
                        FROM CD_Provinces INNER JOIN CD_Districts ON CD_Provinces.ProCode = CD_Districts.ProCode
                        INNER JOIN CD_Zone ON CD_Zone.DistrictCode = CD_Districts.DistCode
                        WHERE CD_provinces.ProCode = CONCAT('P', SUBSTRING('$loggedSchool',3,2)))";
        }

        $rowCount = $db->rowCount($sql);
        if ($rowCount > 0) {
            return $rowCount;
        }
    }
    // Family Info
    if ($pageId == '17') {
        $sql = "SELECT        CD_Zone.InstitutionName
FROM            TG_EmployeeUpdateFamilyInfo INNER JOIN
                         CD_Zone ON TG_EmployeeUpdateFamilyInfo.ZoneCode = CD_Zone.CenCode INNER JOIN
                         UP_TeacherMast ON TG_EmployeeUpdateFamilyInfo.TeacherMastID = UP_TeacherMast.ID INNER JOIN
                         CD_Districts ON CD_Zone.DistrictCode = CD_Districts.DistCode INNER JOIN
                         TeacherMast ON UP_TeacherMast.NIC = TeacherMast.NIC
WHERE        (TG_EmployeeUpdateFamilyInfo.IsApproved = 'N')";

        if ($_SESSION['AccessRoleType'] == 'ZN') {
            $sql .= " and TG_EmployeeUpdateFamilyInfo.ZoneCode='$loggedSchool'";
        }

        if ($_SESSION['AccessRoleType'] == 'PD') {
            $sql .= " and TG_EmployeeUpdateFamilyInfo.ZoneCode IN ( SELECT CD_Zone.CenCode
                        FROM CD_Provinces INNER JOIN CD_Districts ON CD_Provinces.ProCode = CD_Districts.ProCode
                        INNER JOIN CD_Zone ON CD_Zone.DistrictCode = CD_Districts.DistCode
                        WHERE CD_provinces.ProCode = CONCAT('P', SUBSTRING('$loggedSchool',3,2)))";
        }

        $rowCount = $db->rowCount($sql);
        if ($rowCount > 0) {
            return $rowCount;
        }
    }
    // Child Info
    if ($pageId == '17a') {
        $sql = "SELECT TG_EmployeeUpdateChildInfo.NIC
FROM TG_EmployeeUpdateChildInfo INNER JOIN
                         CD_Zone INNER JOIN
                         CD_Districts ON CD_Zone.DistrictCode = CD_Districts.DistCode ON TG_EmployeeUpdateChildInfo.ZoneCode = CD_Zone.CenCode INNER JOIN
                         UP_StaffChildren INNER JOIN
                         TeacherMast ON UP_StaffChildren.NIC = TeacherMast.NIC ON TG_EmployeeUpdateChildInfo.StaffChildID = UP_StaffChildren.ID
WHERE (		TG_EmployeeUpdateChildInfo.IsApproved = 'N'	)";

        if ($_SESSION['AccessRoleType'] == 'ZN') {
            $sql .= " and TG_EmployeeUpdateChildInfo.ZoneCode='$loggedSchool'";
        }

        if ($_SESSION['AccessRoleType'] == 'PD') {
            $sql .= " and TG_EmployeeUpdateChildInfo.ZoneCode IN ( SELECT CD_Zone.CenCode
                        FROM CD_Provinces INNER JOIN CD_Districts ON CD_Provinces.ProCode = CD_Districts.ProCode
                        INNER JOIN CD_Zone ON CD_Zone.DistrictCode = CD_Districts.DistCode
                        WHERE CD_provinces.ProCode = CONCAT('P', SUBSTRING('$loggedSchool',3,2)))";
        }

        $rowCount = $db->rowCount($sql);
        if ($rowCount > 0) {
            return $rowCount;
        }
    }
    // Qualification
    if ($pageId == '18') {
        $sql = "SELECT TG_EmployeeUpdateQualification.ID
FROM            TG_EmployeeUpdateQualification INNER JOIN
                         TeacherMast ON TG_EmployeeUpdateQualification.NIC = TeacherMast.NIC INNER JOIN
                         CD_Zone ON TG_EmployeeUpdateQualification.ZoneCode = CD_Zone.CenCode INNER JOIN
                         CD_Districts ON CD_Zone.DistrictCode = CD_Districts.DistCode
						 WHERE        (TG_EmployeeUpdateQualification.IsApproved = 'N')";

        if ($_SESSION['AccessRoleType'] == 'ZN') {
            $sql .= " and TG_EmployeeUpdateQualification.ZoneCode='$loggedSchool'";
        }

        if ($_SESSION['AccessRoleType'] == 'PD') {
            $sql .= " and TG_EmployeeUpdateQualification.ZoneCode IN ( SELECT CD_Zone.CenCode
                        FROM CD_Provinces INNER JOIN CD_Districts ON CD_Provinces.ProCode = CD_Districts.ProCode
                        INNER JOIN CD_Zone ON CD_Zone.DistrictCode = CD_Districts.DistCode
                        WHERE CD_provinces.ProCode = CONCAT('P', SUBSTRING('$loggedSchool',3,2)))";
        }

      //  echo $sql;
        $rowCount = $db->rowCount($sql);
        if ($rowCount > 0) {
            return $rowCount;
        }
    }
    // Teaching
    if ($pageId == '19') {
        $sql = "SELECT       TG_EmployeeUpdateTeaching.ID
FROM            TG_EmployeeUpdateTeaching INNER JOIN
                         CD_Zone INNER JOIN
                         CD_Districts ON CD_Zone.DistrictCode = CD_Districts.DistCode ON TG_EmployeeUpdateTeaching.ZoneCode = CD_Zone.CenCode INNER JOIN
                         UP_TeacherSubject INNER JOIN
                         TeacherMast ON UP_TeacherSubject.NIC = TeacherMast.NIC ON TG_EmployeeUpdateTeaching.TeachingID = UP_TeacherSubject.ID
WHERE        (TG_EmployeeUpdateTeaching.IsApproved = 'N')";

        if ($_SESSION['AccessRoleType'] == 'ZN') {
            $sql .= " and TG_EmployeeUpdateTeaching.ZoneCode='$loggedSchool'";
        }

        if ($_SESSION['AccessRoleType'] == 'PD') {
            $sql .= " and TG_EmployeeUpdateTeaching.ZoneCode IN ( SELECT CD_Zone.CenCode
                        FROM CD_Provinces INNER JOIN CD_Districts ON CD_Provinces.ProCode = CD_Districts.ProCode
                        INNER JOIN CD_Zone ON CD_Zone.DistrictCode = CD_Districts.DistCode
                        WHERE CD_provinces.ProCode = CONCAT('P', SUBSTRING('$loggedSchool',3,2)))";
        }

        $rowCount = $db->rowCount($sql);
        if ($rowCount > 0) {
            return $rowCount;
        }
    }
//appointment_new from 2020-07-01
    if ($pageId == '32') {
        $sql = "SELECT       TG_EmployeeUpdateTeaching.ID
FROM            TG_EmployeeUpdateTeaching INNER JOIN
                         CD_Zone INNER JOIN
                         CD_Districts ON CD_Zone.DistrictCode = CD_Districts.DistCode ON TG_EmployeeUpdateTeaching.ZoneCode = CD_Zone.CenCode INNER JOIN
                         UP_TeacherSubject INNER JOIN
                         TeacherMast ON UP_TeacherSubject.NIC = TeacherMast.NIC ON TG_EmployeeUpdateTeaching.TeachingID = UP_TeacherSubject.ID
WHERE        (TG_EmployeeUpdateTeaching.IsApproved = 'N')";

        if ($_SESSION['AccessRoleType'] == 'ZN') {
            $sql .= " and TG_EmployeeUpdateTeaching.ZoneCode='$loggedSchool'";
        }

        if ($_SESSION['AccessRoleType'] == 'PD') {
            $sql .= " and TG_EmployeeUpdateTeaching.ZoneCode IN ( SELECT CD_Zone.CenCode
                        FROM CD_Provinces INNER JOIN CD_Districts ON CD_Provinces.ProCode = CD_Districts.ProCode
                        INNER JOIN CD_Zone ON CD_Zone.DistrictCode = CD_Districts.DistCode
                        WHERE CD_provinces.ProCode = CONCAT('P', SUBSTRING('$loggedSchool',3,2)))";
        }

        $rowCount = $db->rowCount($sql);
        if ($rowCount > 0) {
            return $rowCount;
        }
    }
// teaching new from 2020-07-01
    if ($pageId == '33') {
        $sql = "SELECT       TG_EmployeeUpdateTeaching.ID
FROM            TG_EmployeeUpdateTeaching INNER JOIN
                         CD_Zone INNER JOIN
                         CD_Districts ON CD_Zone.DistrictCode = CD_Districts.DistCode ON TG_EmployeeUpdateTeaching.ZoneCode = CD_Zone.CenCode INNER JOIN
                         UP_TeacherSubject INNER JOIN
                         TeacherMast ON UP_TeacherSubject.NIC = TeacherMast.NIC ON TG_EmployeeUpdateTeaching.TeachingID = UP_TeacherSubject.ID
WHERE        (TG_EmployeeUpdateTeaching.IsApproved = 'N')";

        if ($_SESSION['AccessRoleType'] == 'ZN') {
            $sql .= " and TG_EmployeeUpdateTeaching.ZoneCode='$loggedSchool'";
        }

        if ($_SESSION['AccessRoleType'] == 'PD') {
            $sql .= " and TG_EmployeeUpdateTeaching.ZoneCode IN ( SELECT CD_Zone.CenCode
                        FROM CD_Provinces INNER JOIN CD_Districts ON CD_Provinces.ProCode = CD_Districts.ProCode
                        INNER JOIN CD_Zone ON CD_Zone.DistrictCode = CD_Districts.DistCode
                        WHERE CD_provinces.ProCode = CONCAT('P', SUBSTRING('$loggedSchool',3,2)))";
        }

        $rowCount = $db->rowCount($sql);
        if ($rowCount > 0) {
            return $rowCount;
        }
    }

    // Services
    if ($pageId == '22') {
        $sql = "SELECT     UP_StaffServiceHistory.ID
FROM            UP_StaffServiceHistory INNER JOIN

                         CD_CensesNo ON UP_StaffServiceHistory.InstCode = CD_CensesNo.CenCode INNER JOIN

                         CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode INNER JOIN
                         CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode INNER JOIN
                         TG_Approval ON UP_StaffServiceHistory.ID = TG_Approval.RequestID";

        if ($_SESSION['AccessRoleType'] == 'ZN') {
            $sql .= " WHERE        (UP_StaffServiceHistory.NIC <> '') AND (TG_Approval.RequestType = 'ServiceUpdate') AND (TG_Approval.ApprovedStatus = N'P') AND (TG_Approval.ApproveInstCode = '$loggedSchool')"; /* //last AND added on 15th Aug 2016 */
        }

        if ($_SESSION['AccessRoleType'] == 'PD') {
            $sql .= " WHERE        (UP_StaffServiceHistory.NIC <> '') AND (TG_Approval.RequestType = 'ServiceUpdate') AND (TG_Approval.ApprovedStatus = N'P') AND (TG_Approval.ApproveInstCode IN ( SELECT CD_Zone.CenCode
                        FROM CD_Provinces INNER JOIN CD_Districts ON CD_Provinces.ProCode = CD_Districts.ProCode
                        INNER JOIN CD_Zone ON CD_Zone.DistrictCode = CD_Districts.DistCode
                        WHERE CD_provinces.ProCode = CONCAT('P', SUBSTRING('$loggedSchool',3,2))))"; /* //last AND added on 15th Aug 2016 */
        }

        if ($_SESSION['AccessRoleType'] == 'NC') {
            $sql .= " WHERE        (UP_StaffServiceHistory.NIC <> '') AND (TG_Approval.RequestType = 'ServiceUpdate') AND (TG_Approval.ApprovedStatus = N'RQ') AND (UP_StaffServiceHistory.IsApproved='N') GROUP BY UP_StaffServiceHistory.ID";
        }
        $rowCount = $db->rowCount($sql);
        if ($rowCount > 0) {
            return $rowCount;
        }
    }
    // Retirement
    if ($pageId == '1') {
        $tblField = "";
        $sqlChkNo = "SELECT id FROM TG_Request_Approve WHERE (ApproveUserNominatorNIC = N'$nicNO') AND (RequestType = 'Retirement')";
        $totNominiRow = $db->rowCount($sqlChkNo);
        if ($totNominiRow > 0) {
            $tblField = 'ApproveUserNominatorNIC';
        } else {
            $tblField = 'ApprovelUserNIC';
        }

        $retSql = "SELECT id FROM TG_Request_Approve WHERE ($tblField = N'$nicNO') AND (RequestType = 'Retirement') AND (ApprovedStatus = N'P')";
        $rowCount = $db->rowCount($retSql);
        if ($rowCount > 0) {
            return $rowCount;
        }
    }
    // Leave
    if ($pageId == '2') {

        $retSql = "SELECT        TG_Approval_Leave.RequestID
FROM            TG_Approval_Leave INNER JOIN
                         TG_StaffLeave ON TG_Approval_Leave.RequestID = TG_StaffLeave.ID INNER JOIN
                         TeacherMast ON TG_StaffLeave.NIC = TeacherMast.NIC INNER JOIN
                         CD_CensesNo ON TG_StaffLeave.SchoolID = CD_CensesNo.CenCode INNER JOIN
                         CD_LeaveType ON TG_StaffLeave.LeaveType = CD_LeaveType.LeaveCode
WHERE        (TG_Approval_Leave.ApprovedStatus = 'P')";

        if ($_SESSION['AccessRoleType'] != 'NC') {
            $sqlChkNo = "SELECT id FROM TG_Approval_Leave WHERE (ApprovedStatus = 'P') AND (ApproveDesignationCode = N'$accLevel') AND (ApproveInstCode = N'$loggedSchool') AND (RequestType = 'Leave')";
            $totNominiRow = $db->rowCount($sqlChkNo);
            if ($totNominiRow > 0) {
                $tblField = 'TG_Approval_Leave.ApproveDesignationCode';
            } else {
                $tblField = 'TG_Approval_Leave.ApproveDesignationNominiCode';
            }

            $retSql .= " AND (TG_Approval_Leave.ApproveInstCode = '$loggedSchool') AND ($tblField = N'$accLevel') AND (TG_Approval_Leave.RequestType = 'Leave')";
        }
        //Added by Dharshana -- Start
        $rowCount = $db->rowCount($retSql);
        if ($rowCount > 0) {
            return $rowCount;
        }
        // Added by Dharshana -- End
    }
    // National Principal
    if ($pageId == '7') {
        /*
          $sqlChkNo = "SELECT id FROM TG_Request_Approve WHERE (ApproveUserNominatorNIC = N'$nicNO') AND (RequestType = 'TransferPrincipleNational')";
          $totNominiRow = $db->rowCount($sqlChkNo);
          if ($totNominiRow > 0) {
          $tblField = 'ApproveUserNominatorNIC';
          } else {
          $tblField = 'ApprovelUserNIC';
          }

          $retSql = "SELECT id FROM TG_Request_Approve WHERE ($tblField = N'$nicNO') AND (RequestType = 'TransferPrincipleNational') AND (ApprovedStatus = N'P')";
          $tranPNTotal = $db->rowCount($retSql);
         *
         */
    }
    // National Teacher
    if ($pageId == '6') {
        /*
          $sqlChkNo = "SELECT id FROM TG_Request_Approve WHERE (ApproveUserNominatorNIC = N'$nicNO') AND (RequestType = 'TransferTeacherNational')";
          $totNominiRow = $db->rowCount($sqlChkNo);
          if ($totNominiRow > 0) {
          $tblField = 'ApproveUserNominatorNIC';
          } else {
          $tblField = 'ApprovelUserNIC';
          }

          $retSql = "SELECT id FROM TG_Request_Approve WHERE ($tblField = N'$nicNO') AND (RequestType = 'TransferTeacherNational') AND (ApprovedStatus = N'P')";
          $tranTNTotal = $db->rowCount($retSql);
         *
         */
    }
    // Provincial Principal
    if ($pageId == '5') {
        /*
          $sqlChkNo = "SELECT id FROM TG_Request_Approve WHERE (ApproveUserNominatorNIC = N'$nicNO') AND (RequestType = 'TransferPrincipleNormal')";
          $totNominiRow = $db->rowCount($sqlChkNo);
          if ($totNominiRow > 0) {
          $tblField = 'ApproveUserNominatorNIC';
          } else {
          $tblField = 'ApprovelUserNIC';
          }

          $retSql = "SELECT id FROM TG_Request_Approve WHERE ($tblField = N'$nicNO') AND (RequestType = 'TransferPrincipleNormal') AND (ApprovedStatus = N'P')";
          $tranPPTotal = $db->rowCount($retSql);
         *
         */
    }
    // Provincial Teacher
    if ($pageId == '10') {
        /*
          $sqlChkNo = "SELECT id FROM TG_Request_Approve WHERE (ApproveUserNominatorNIC = N'$nicNO') AND (RequestType = 'TransferTeacherNormal')";
          $totNominiRow = $db->rowCount($sqlChkNo);
          if ($totNominiRow > 0) {
          $tblField = 'ApproveUserNominatorNIC';
          } else {
          $tblField = 'ApprovelUserNIC';
          }

          $retSql = "SELECT id FROM TG_Request_Approve WHERE ($tblField = N'$nicNO') AND (RequestType = 'TransferTeacherNormal') AND (ApprovedStatus = N'P')";
          $tranTPTotal = $db->rowCount($retSql);
         *
         */
    }
    // Vacancy National Principal
    if ($pageId == '9') {
        /*
          $sqlChkNo = "SELECT id FROM TG_Request_Approve WHERE (ApproveUserNominatorNIC = N'$nicNO') AND (RequestType = 'VacancyPrincipleNational')";
          $totNominiRow = $db->rowCount($sqlChkNo);
          if ($totNominiRow > 0) {
          $tblField = 'ApproveUserNominatorNIC';
          } else {
          $tblField = 'ApprovelUserNIC';
          }

          $retSql = "SELECT id FROM TG_Request_Approve WHERE ($tblField = N'$nicNO') AND (RequestType = 'VacancyPrincipleNational') AND (ApprovedStatus = N'P')";
          $vacantPNTotal = $db->rowCount($retSql);
         *
         */
    }
    // Vacancy National Teacher
    if ($pageId == '8') {
        //return " (8)";
        /*
          $sqlChkNo = "SELECT id FROM TG_Request_Approve WHERE (ApproveUserNominatorNIC = N'$nicNO') AND (RequestType = 'VacancyTeacherNational')";
          $totNominiRow = $db->rowCount($sqlChkNo);
          if ($totNominiRow > 0) {
          $tblField = 'ApproveUserNominatorNIC';
          } else {
          $tblField = 'ApprovelUserNIC';
          }

          $retSql = "SELECT id FROM TG_Request_Approve WHERE ($tblField = N'$nicNO') AND (RequestType = 'VacancyTeacherNational') AND (ApprovedStatus = N'P')";
          $vacantTNTotal = $db->rowCount($retSql);
         *
         */
    }
    // Vacancy Provincial Principal
    if ($pageId == '11') {
        /*
          $sqlChkNo = "SELECT id FROM TG_Request_Approve WHERE (ApproveUserNominatorNIC = N'$nicNO') AND (RequestType = 'VacancyPrincipleNormal')";
          $totNominiRow = $db->rowCount($sqlChkNo);
          if ($totNominiRow > 0) {
          $tblField = 'ApproveUserNominatorNIC';
          } else {
          $tblField = 'ApprovelUserNIC';
          }

          $retSql = "SELECT id FROM TG_Request_Approve WHERE ($tblField = N'$nicNO') AND (RequestType = 'VacancyPrincipleNormal') AND (ApprovedStatus = N'P')";
          $vacantPPTotal = $db->rowCount($retSql);
         *
         */
    }

    // Vacancy Provincial Teacher
    if ($pageId == '10') {
        /*
          $sqlChkNo = "SELECT id FROM TG_Request_Approve WHERE (ApproveUserNominatorNIC = N'$nicNO') AND (RequestType = 'VacancyTeacherNormal')";
          $totNominiRow = $db->rowCount($sqlChkNo);
          if ($totNominiRow > 0) {
          $tblField = 'ApproveUserNominatorNIC';
          } else {
          $tblField = 'ApprovelUserNIC';
          }

          $retSql = "SELECT id FROM TG_Request_Approve WHERE ($tblField = N'$nicNO') AND (RequestType = 'VacancyTeacherNormal') AND (ApprovedStatus = N'P')";
          $vacantTPTotal = $db->rowCount($retSql);
         *
         */
    }

    // Training Request
    if ($pageId == '13') {
        /*
          $sqlChkNo = "SELECT id FROM TG_Request_Approve WHERE (ApproveUserNominatorNIC = N'$nicNO') AND (RequestType = 'RequestTeacherTraining')";
          $totNominiRow = $db->rowCount($sqlChkNo);
          if ($totNominiRow > 0) {
          $tblField = 'ApproveUserNominatorNIC';
          } else {
          $tblField = 'ApprovelUserNIC';
          }

          $retSql = "SELECT id FROM TG_Request_Approve WHERE ($tblField = N'$nicNO') AND (RequestType = 'RequestTeacherTraining') AND (ApprovedStatus = N'P')";
          $trainRTotal = $db->rowCount($retSql);
         *
         */
    }
    // Training Apply
    if ($pageId == '14') {
        /*
          $sqlChkNo = "SELECT id FROM TG_Request_Approve WHERE (ApproveUserNominatorNIC = N'$nicNO') AND (RequestType = 'ApplyForTraining')";
          $totNominiRow = $db->rowCount($sqlChkNo);
          if ($totNominiRow > 0) {
          $tblField = 'ApproveUserNominatorNIC';
          } else {
          $tblField = 'ApprovelUserNIC';
          }

          $retSql = "SELECT id FROM TG_Request_Approve WHERE ($tblField = N'$nicNO') AND (RequestType = 'ApplyForTraining') AND (ApprovedStatus = N'P')";
          $trainATotal = $db->rowCount($retSql);
         */
    }
    // Salary Increment Teacher
    if ($pageId == '20') {
        /*
          $sqlChkNo = "SELECT id FROM TG_Approval WHERE (ApproveDesignationCode = N'$accLevel') AND (ApproveInstCode = N'$loggedSchool') AND (RequestType = 'TeacherIncrement')";
          $totNominiRow = $db->rowCount($sqlChkNo);
          if ($totNominiRow > 0) {
          $tblField = 'ApproveDesignationCode';
          } else {
          $tblField = 'ApproveDesignationNominiCode';
          }

          $retSql = "SELECT id FROM TG_Approval WHERE ($tblField = N'$accLevel') AND (TG_Approval.ApproveInstCode = N'$loggedSchool') AND (RequestType = 'TeacherIncrement') AND (ApprovedStatus = N'P') AND (RequestID!=0)";
          $teacherSalTotal = $db->rowCount($retSql);
         */
    }
    // Salary Increment Principal
    if ($pageId == '21') {
        /*
          $sqlChkNo = "SELECT id FROM TG_Approval WHERE (ApproveDesignationCode = N'$accLevel') AND (ApproveInstCode = N'$loggedSchool') AND (RequestType = 'PrincipalIncrement')";
          $totNominiRow = $db->rowCount($sqlChkNo);
          if ($totNominiRow > 0) {
          $tblField = 'ApproveDesignationCode';
          } else {
          $tblField = 'ApproveDesignationNominiCode';
          }

          $retSql = "SELECT id FROM TG_Approval WHERE ($tblField = N'$accLevel') AND (ApproveInstCode = N'$loggedSchool') AND (RequestType = 'PrincipalIncrement') AND (ApprovedStatus = N'P')";
          $principalSalTotal = $db->rowCount($retSql);
         */
    }



    /*
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
      TG_DynMenu.FOrder

      FROM
      TG_DynMenu
      INNER JOIN TG_Privilage ON TG_DynMenu.ID = TG_Privilage.FormID
      WHERE
      TG_DynMenu.ParentID = 2 AND
      TG_DynMenu.IsParent = 1 AND
      TG_DynMenu.ShowMenu = 1 ";
      $stmt = $db->runMsSqlQuery($sqlDyn);
      $count = 0;
      while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
      echo $row['Title'];
      }

     *
     */
}
?>
