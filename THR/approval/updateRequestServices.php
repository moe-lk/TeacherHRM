<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
$msg = "";
$success = "";
//echo "<span class=\"form_error_sched\">Service temporary unavailable due to urgent maintenance work</span>";exit();
$AccessRoleType = $_SESSION['AccessRoleType'];
if ($id != '') {
    $sqlPmast = "SELECT        UP_StaffServiceHistory.ID, UP_StaffServiceHistory.NIC, CONVERT(varchar(20), UP_StaffServiceHistory.AppDate, 121) AS AppDate, UP_StaffServiceHistory.InstCode, UP_StaffServiceHistory.ServiceRecTypeCode,
                         CD_SecGrades.GradeName, CD_Service.ServiceName, CD_Positions.PositionName, CD_CAT2003.Cat2003Name, CD_ServiceRecType.Description, UP_StaffServiceHistory.SecGRCode, UP_StaffServiceHistory.Reference,
                         UP_StaffServiceHistory.ServiceTypeCode, UP_StaffServiceHistory.PositionCode, UP_StaffServiceHistory.Cat2003Code, UP_StaffServiceHistory.UpdateBy, CONVERT(varchar(20), UP_StaffServiceHistory.LastUpdate, 121) AS LastUpdate
FROM            UP_StaffServiceHistory INNER JOIN
                         CD_SecGrades ON UP_StaffServiceHistory.SecGRCode = CD_SecGrades.GradeCode INNER JOIN
                         CD_Service ON UP_StaffServiceHistory.ServiceTypeCode = CD_Service.ServCode INNER JOIN
                         CD_CAT2003 ON UP_StaffServiceHistory.Cat2003Code = CD_CAT2003.Cat2003Code INNER JOIN
                         CD_ServiceRecType ON UP_StaffServiceHistory.ServiceRecTypeCode = CD_ServiceRecType.DutyCode INNER JOIN
                         CD_Positions ON UP_StaffServiceHistory.PositionCode = CD_Positions.Code
WHERE        (UP_StaffServiceHistory.ID = '$id') ORDER BY AppDate ASC"; // and StaffServiceHistory.ID='588449'

    $resABC = $db->runMsSqlQuery($sqlPmast);
}

if ($id == '') {

    $Per_Page = 30;  /* // Per Page

      //Get the page number */

    $Page = 1;

    /* //Determine if it is the first page  */

    /* if(isset($_GET["Page"]))
      {
      $Page=(int)$_GET["Page"];
      if ($Page < 1)
      $Page = 1;
      } */

    if ($menu) {
        $Page = (int) $menu;
        if ($Page < 1)
            $Page = 1;
    }

    $Page_Start = (($Per_Page * $Page) - $Per_Page) + 1;
    $Page_End = $Page_Start + $Per_Page - 1;

    $NICSearch = "";
    if (isset($_POST["FrmSrch"])) {
        $NICSearch = $_REQUEST['NICSearch'];
    }
    /* $approvSql="SELECT        CD_CensesNo.InstitutionName, TeacherMast.SurnameWithInitials, CD_CensesNo.ZoneCode, CONVERT(varchar(20), UP_StaffServiceHistory.LastUpdate, 121) AS LastUpdate, TeacherMast.NIC, UP_StaffServiceHistory.ID,
      CD_Districts.DistName, CD_Zone.InstitutionName AS Expr1
      FROM            StaffServiceHistory INNER JOIN
      UP_StaffServiceHistory ON StaffServiceHistory.NIC = UP_StaffServiceHistory.NIC INNER JOIN
      CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode INNER JOIN
      TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef INNER JOIN
      CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode INNER JOIN
      CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
      WHERE        (UP_StaffServiceHistory.NIC <> '')"; */
    $approvSql = "WITH LIMIT AS(SELECT CD_CensesNo.InstitutionName, TeacherMast.SurnameWithInitials, CD_CensesNo.ZoneCode, CONVERT(varchar(20), UP_StaffServiceHistory.LastUpdate, 121) AS LastUpdate, TeacherMast.NIC, UP_StaffServiceHistory.ID,
                        CD_Districts.DistName, CD_Zone.InstitutionName AS Expr1, TG_Approval.RequestType, TG_Approval.ApprovedByNIC, ROW_NUMBER() OVER (ORDER BY UP_StaffServiceHistory.ID ASC) AS 'RowNumber'
                        FROM UP_StaffServiceHistory INNER JOIN
                        CD_CensesNo ON UP_StaffServiceHistory.InstCode = CD_CensesNo.CenCode INNER JOIN
						TeacherMast ON UP_StaffServiceHistory.NIC = TeacherMast.NIC INNER JOIN
                        CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode INNER JOIN
                        CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode INNER JOIN
                        TG_Approval ON UP_StaffServiceHistory.ID = TG_Approval.RequestID";



    if ($AccessRoleType != "NC") {
        $approvSql .= " WHERE (UP_StaffServiceHistory.NIC <> '') AND (TG_Approval.RequestType = 'ServiceUpdate') AND (TG_Approval.ApprovedStatus = N'P') AND (TG_Approval.ApproveInstCode = '$loggedSchool')"; /* //last AND added on 15th Aug 2016 */
    }
    if ($AccessRoleType == "NC") {
        $approvSql .= " WHERE (UP_StaffServiceHistory.NIC <> '') AND (TG_Approval.RequestType = 'ServiceUpdate') AND (TG_Approval.ApprovedStatus = N'RQ') AND (UP_StaffServiceHistory.IsApproved='N')";
    }
    if ($NICSearch)
        $approvSql .= " and (UP_StaffServiceHistory.NIC like '%$NICSearch%')";


    /* if($accLevel=='11050' || $accLevel=='11000')$approvSql.=" and CD_CensesNo.ZoneCode='$loggedSchool'";
      if($accLevel=='3000')$approvSql.=" and CD_CensesNo.CenCode='$loggedSchool'"; */ /* //Commented on 15th Aug 2016

      //$approvSql.=" Order By UP_StaffServiceHistory.LastUpdate DESC";//echo $approvSql; */
    $approvSql .= ")
	select * from LIMIT WHERE RowNumber BETWEEN $Page_Start AND $Page_End";

    //echo $approvSql;


    $countTotal = "SELECT  UP_StaffServiceHistory.ID
                    FROM UP_StaffServiceHistory INNER JOIN
                    CD_CensesNo ON UP_StaffServiceHistory.InstCode = CD_CensesNo.CenCode INNER JOIN
                    CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode INNER JOIN
                    CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode INNER JOIN
                    TG_Approval ON UP_StaffServiceHistory.ID = TG_Approval.RequestID";

    /* $countTotal="SELECT  UP_StaffServiceHistory.ID
      FROM            StaffServiceHistory INNER JOIN
      UP_StaffServiceHistory ON StaffServiceHistory.NIC = UP_StaffServiceHistory.NIC INNER JOIN
      CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode INNER JOIN
      TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef INNER JOIN
      CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode INNER JOIN
      CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode INNER JOIN
      TG_Approval ON UP_StaffServiceHistory.ID = TG_Approval.RequestID"; */

    if ($AccessRoleType != "NC") {
        $countTotal .= " WHERE        (UP_StaffServiceHistory.NIC <> '') AND (TG_Approval.RequestType = 'ServiceUpdate') AND (TG_Approval.ApprovedStatus = N'P') AND (TG_Approval.ApproveInstCode = '$loggedSchool')"; /* //last AND added on 15th Aug 2016 */
    }
    if ($AccessRoleType == "NC") {
        $countTotal .= " WHERE        (UP_StaffServiceHistory.NIC <> '') AND (TG_Approval.RequestType = 'ServiceUpdate') AND (TG_Approval.ApprovedStatus = N'RQ') AND (UP_StaffServiceHistory.IsApproved='N')";
    }

    if ($NICSearch)
        $countTotal .= " and (UP_StaffServiceHistory.NIC like '%$NICSearch%')";
    /* if($accLevel=='99999'){
      $countTotal.=" GROUP BY UP_StaffServiceHistory.ID";
      } */
    /* if($accLevel=='11050' || $accLevel=='11000')$approvSql.=" and CD_CensesNo.ZoneCode='$loggedSchool'";
      if($accLevel=='3000')$approvSql.=" and CD_CensesNo.CenCode='$loggedSchool'"; */ /* //Commented on 15th Aug 2016 */
//echo $countTotal;
    $TotaRows = $db->rowCount($countTotal);
    if (!$TotaRows)
        $TotaRows = 0;

    /* //Declare previous/next page row guide  */

    $Prev_Page = $Page - 1;
    $Next_Page = $Page + 1;

    if ($TotaRows <= $Per_Page) {
        $Num_Pages = 1;
    } else if (($TotaRows % $Per_Page) == 0) {
        $Num_Pages = ($TotaRows / $Per_Page);
    } else {
        $Num_Pages = ($TotaRows / $Per_Page) + 1;
        $Num_Pages = (int) $Num_Pages;
    }

    /* 	//Determine where the page will end  */

    $Page_End = $Per_Page * $Page;
    if ($Page_End > $TotaRows) {
        $Page_End = $TotaRows;
    }
//echo $approvSql;
    /* //echo $loggedSchool; */
}/* //echo $approvSql; */
?>

<?php if ($id == '') { ?>
    <div style="width:738px; margin-top:10px;"><form method="post" action="" name="frmSrch" id="frmSrch"><table width="100%" cellspacing="1" cellpadding="1">
                <tr>
                    <td width="19%">Search by NIC <?php //echo $accLevel       ?></td>
                    <td width="27%"><input name="NICSearch" type="text" class="input2_n" id="NICSearch" value="" placeholder="NIC"/></td>
                    <td width="11%"><input name="FrmSrch" type="submit" id="FrmSrch" style="background-image: url(../cms/images/searchN.png); width:84px; height:26px; background-color:transparent; border:none; cursor:pointer;" value="" /></td>
                    <td width="43%"><div id="txt_available" style="font-weight:bold;"></div></td>
                </tr>
                <tr>
                    <td colspan="4" style="border-bottom:1px; border-bottom-style:solid;">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
            </table>
        </form>
    </div>
<?php } ?>
<form method="post" action="updateRequestAction.php" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
    <?php if ($msg != '' || $success != '') {/* if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  */ ?>


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
    <div style="width:738px; float:left;">
        <?php if ($id == '') { ?>
            <table width="100%" cellpadding="0" cellspacing="0">

                <tr>
                    <td width="56%"><?php echo $TotaRows ?> Record(s) found. Showing <?php echo $Per_Page ?> records per page.</td>
                    <td width="44%">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                            <tr>
                                <td width="5%" height="25" align="center" bgcolor="#999999">#</td>
                                <td width="32%" align="center" bgcolor="#999999">Employee</td>
                                <td width="43%" align="center" bgcolor="#999999">School</td>
                                <td width="13%" align="center" bgcolor="#999999">Request Date</td>
                                <td width="7%" align="center" bgcolor="#999999">Action</td>
                            </tr>
                            <?php
                            $i = 1;
                            // echo $approvSql;
                            $stmt = $db->runMsSqlQuery($approvSql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $RequestID = $row['ID'];
                                $InstitutionName = $row['InstitutionName'];
                                $DistName = $row['DistName'];
                                $zoneName = $row['Expr1'];
                                $RowNumber = $row['RowNumber'];
                                ?>
                                <tr>
                                    <td height="20" bgcolor="#FFFFFF"><?php echo $RowNumber; ?></td>
                                    <td align="left" bgcolor="#FFFFFF"><?php echo $row['SurnameWithInitials']; ?> [<?php echo trim($row['NIC']); ?>]</td>
                                    <td align="left" bgcolor="#FFFFFF"><?php echo ucwords(strtolower("$InstitutionName ( $DistName - $zoneName)")); ?></td>
                                    <td bgcolor="#FFFFFF" align="center"><?php echo substr($row['LastUpdate'], 0, 10); ?></td>
                                    <td bgcolor="#FFFFFF" align="center"><a href="updateRequestServices-22--<?php echo $RequestID ?>.html"><img src="images/more_info.png" /></a></td>
                                </tr>
                            <?php } ?>
                        </table></td>
                </tr>

                <tr>
                    <td colspan="2"><table width="100%" cellspacing="1" cellpadding="1">
                            <tr>
                                <td width="67%">Page <?php echo $Page ?> of <?php echo $Num_Pages ?></td>
                                <td width="20%" align="right"><?php
                                    /* //Previous page  */

                                    if ($Prev_Page) {
                                        echo " <a href='$ttle-$pageid-$Prev_Page.html?Page=$Prev_Page#related'><< Previous</a> ";
                                    }

                                    /* //Display total pages

                                      //for($i=1; $i<=$Num_Pages; $i++){ */


                                    /* for($i=1; $i<=5; $i++){
                                      if($i != $Page)
                                      {
                                      echo "<a href='$_SERVER[SCRIPT_NAME]?id=$id&Page=$i#related'>$i</a>&nbsp;";
                                      }
                                      else
                                      {
                                      echo "<b> $i </b>";
                                      }
                                      } */
                                    ?></td>
                                <td width="2%" align="center"><?php if ($Prev_Page and $Page != $Num_Pages) { ?> | <?php } ?></td>
                                <td width="11%" align="left"><?php
                                    /* //Create next page link  */

                                    if ($Page != $Num_Pages) {
                                        /*  //echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Next_Page#related'>Next>></a> ";  */
                                        echo " <a href ='$ttle-$pageid-$Next_Page.html?Page=$Next_Page#related'>Next>></a> ";
                                    }
                                    ?></td>
                            </tr>
                        </table></td>
                </tr>

            </table> <?php } else { ?>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td colspan="2" align="center" height="30px" style="font-size:16px; font-weight:bold;"><u>Update Request - Service Information</u>
                        <?php
                        $rowABC = sqlsrv_fetch_array($resABC, SQLSRV_FETCH_ASSOC);
                        $Description = $rowABC['Description'];
                        $AppDate = $rowABC['AppDate'];
                        $InstCode = trim($rowABC['InstCode']);
                        $GradeName = $rowABC['GradeName'];
                        $ServiceName = $rowABC['ServiceName'];
                        $PositionName = $rowABC['PositionName'];
                        $Cat2003Name = trim($rowABC['Cat2003Name']);
                        $StaffServiceHistoryID = trim($rowABC['ID']);
                        $Reference = trim($rowABC['Reference']);
                        $NIC = trim($rowABC['NIC']);
                        $UpdateBy = trim($rowABC['UpdateBy']);
                        $LastUpdate = $rowABC['LastUpdate'];

                        $sqlTName = "SELECT SurnameWithInitials FROM TeacherMast where NIC='$NIC'";
                        $stmtTn = $db->runMsSqlQuery($sqlTName);
                        $rowTn = sqlsrv_fetch_array($stmtTn, SQLSRV_FETCH_ASSOC);
                        $SurnameWithInitialsT = $rowTn['SurnameWithInitials'];

                        $sqlTName = "SELECT SurnameWithInitials FROM TeacherMast where NIC='$UpdateBy'";
                        $stmtTn = $db->runMsSqlQuery($sqlTName);
                        $rowTn = sqlsrv_fetch_array($stmtTn, SQLSRV_FETCH_ASSOC);
                        $SurnameWithInitialsU = $rowTn['SurnameWithInitials'];


                        $sqlCenseQ = "SELECT        CD_CensesNo.InstitutionName, CD_Districts.DistName, CD_Provinces.Province, CD_Zone.InstitutionName AS ZoneN, CD_Division.InstitutionName AS DivisionN
	FROM            CD_Division INNER JOIN
							 CD_Provinces INNER JOIN
							 CD_CensesNo INNER JOIN
							 CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode ON CD_Provinces.ProCode = CD_Districts.ProCode INNER JOIN
							 CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode ON CD_Division.CenCode = CD_CensesNo.DivisionCode
	WHERE        (CD_CensesNo.CenCode = '$InstCode')";

                        $resABCq = $db->runMsSqlQuery($sqlCenseQ);
                        $rowABCq = sqlsrv_fetch_array($resABCq, SQLSRV_FETCH_ASSOC);
                        $InstitutionName = $rowABCq['InstitutionName'];
                        $DistName = trim($rowABCq['DistName']);
                        $Province = $rowABCq['Province'];
                        $ZoneN = $rowABCq['ZoneN'];
                        $DivisionN = $rowABCq['DivisionN'];

                        $link = "teacherTransferLetter.php?RecId=$StaffServiceHistoryID";
                        ?>
                    </td>
                <tr>
                    <td colspan="2" align="right" valign="top"><a href="#" onclick="return popitup('<?php echo $link ?>')">Print</a></td>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;"><strong>Name : <?php echo $SurnameWithInitialsT ?> [<?php echo $NIC ?>]</strong></td>
                <tr>
                    <td width="50%" valign="top">&nbsp;</td>
                    <td width="50%" align="right" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td align="left" valign="top"><strong>Employment Basis</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $Description ?></td>
                                <td width="22%" align="left" valign="top"><strong>Reference Number</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $Reference ?></td>
                                <td width="4%" rowspan="6" align="left" valign="top">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="15%" align="left" valign="top"><strong> Province</strong></td>
                                <td width="2%" align="left" valign="top"><strong>:</strong></td>
                                <td width="28%" align="left" valign="top"><?php echo $Province ?></td>
                                <td align="left" valign="top"><strong>Section</strong></td>
                                <td width="2%" align="left" valign="top"><strong>:</strong></td>
                                <td width="27%" align="left" valign="top"><?php echo $GradeName ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>District</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td width="28%" align="left" valign="top"><?php echo $DistName ?></td>
                                <td align="left" valign="top"><strong>Position</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $PositionName ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Zone</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td width="28%" align="left" valign="top"><?php echo $ZoneN ?></td>
                                <td align="left" valign="top"><strong>Effective Date</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $AppDate ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong> Division</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td width="28%" align="left" valign="top"><?php echo $DivisionN ?> </td>
                                <td align="left" valign="top"><strong>Service Grade</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $ServiceName ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>School/ Institution</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $InstitutionName ?></td>
                                <td align="left" valign="top"><strong>1/2016 Circular Category</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $Cat2003Name ?></td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;">&nbsp;</td>
                </tr>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr bgcolor="#3399FF">
                    <td height="30" colspan="2" valign="middle" style="border-bottom: 1px; border-bottom-style: solid; font-size: 14px; color: #FFFFFF;">&nbsp;&nbsp;<strong>Take an Action</strong></td>
                </tr>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" align="left" valign="top"><strong>&nbsp;Request By</strong> <?php echo $SurnameWithInitialsU ?> on <?php echo $LastUpdate ?></td>
                </tr>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                </tr>

                <?php
                $sqlApp = "SELECT     TG_Approval.ID, TG_Approval.RequestType, TG_Approval.RequestID, TG_Approval.ApproveInstCode, TG_Approval.ApproveDesignationCode, TG_Approval.ApproveDesignationNominiCode,
                         TG_Approval.ApprovedStatus, TG_Approval.ApprovedByNIC, TG_Approval.DateTime, TG_Approval.Remarks
FROM            TG_Approval
						 WHERE TG_Approval.RequestID='$id' and TG_Approval.ApprovedStatus!='RQ' ORDER BY TG_Approval.ID ASC";

                $resABC = $db->runMsSqlQuery($sqlApp);


                $ApID = "";
                $approvedPre = "Y";
                $endPoint = "";
                $form_submit = FALSE;
                while ($rowABC = sqlsrv_fetch_array($resABC, SQLSRV_FETCH_ASSOC)) {
                    //$AccessRole= $rowABC['AccessRole'];
                    //$InstitutionName= $rowABC['InstitutionName'];
                    $ApproveInstCode = trim($rowABC['ApproveInstCode']); //echo $loggedSchool;echo "_";
                    $ApproveDesignationCode = trim($rowABC['ApproveDesignationCode']);
                    $ApproveDesignationNominiCode = trim($rowABC['ApproveDesignationNominiCode']);
                    $ApprovedStatus = trim($rowABC['ApprovedStatus']);
                    $IDApp = trim($rowABC['ID']);
                    $Remarks = trim($rowABC['Remarks']);
                    $ApprovedByNIC = trim($rowABC['ApprovedByNIC']);

                    $reqTabMobAc = "SELECT InstitutionName FROM CD_CensesNo where CenCode='$ApproveInstCode'";
                    $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
                    $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
                    $InstitutionName = trim($rowMobAc['InstitutionName']);

                    $AccessRole = "N/A";
                    $SurnameWithInitialsApproved = "N/A";


                    if ($ApprovedStatus != 'P') {
                        $sqlUserAcc = "SELECT   Passwords.AccessRole,TeacherMast.SurnameWithInitials
						FROM            TeacherMast INNER JOIN
												 Passwords ON TeacherMast.NIC = Passwords.NICNo
												 WHERE (TeacherMast.NIC='$ApprovedByNIC')";
                        $resuacc = $db->runMsSqlQuery($sqlUserAcc);
                        $rowuacc = sqlsrv_fetch_array($resuacc, SQLSRV_FETCH_ASSOC);
                        $AccessRole = $rowuacc['AccessRole'];
                        $SurnameWithInitialsApproved = $rowuacc['SurnameWithInitials'];
                    }

                    $action_block = TRUE;
                    $txt_block = TRUE;


                   // echo "<br>";
                    if ($ApproveInstCode == $loggedSchool  and ($approvedPre=='Y' || $approvedPre=='A')) {
                        $AccessRole = $loggedPositionName;
                        $SurnameWithInitialsApproved = $_SESSION["fullName"];
                        $ApID = $IDApp;
                        $action_block = FALSE;
                        $txt_block = FALSE;
                        $form_submit = TRUE;
                    } else {
                        $txt_block = TRUE;
                        $action_block = TRUE;
                    }
                 //   echo $ApproveInstCode . '---' . $loggedSchool . '---' . $ApprovedStatus . '---' . $form_submit;



                    $sqlEmpDes = "SELECT        TeacherMast.ID, TeacherMast.NIC, TeacherMast.SurnameWithInitials, CD_AccessRoles.AccessRoleValue, CD_CensesNo.InstitutionName, CD_CensesNo.CenCode, CD_AccessRoles.AccessRole
FROM            CD_CensesNo INNER JOIN
                         StaffServiceHistory ON CD_CensesNo.CenCode = StaffServiceHistory.InstCode INNER JOIN
                         TeacherMast INNER JOIN
                         Passwords ON TeacherMast.NIC = Passwords.NICNo INNER JOIN
                         CD_AccessRoles ON Passwords.AccessLevel = CD_AccessRoles.AccessRoleValue ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
WHERE        (CD_AccessRoles.AccessRoleValue = '$ApproveDesignationCode') AND (CD_CensesNo.CenCode = N'$ApproveInstCode') AND (StaffServiceHistory.ServiceRecTypeCode != 'RT01' or StaffServiceHistory.ServiceRecTypeCode IS NULL)";
                    /* // || CD_AccessRoles.AccessRoleValue = '$ApproveDesignationNominiCode' */

                    $resED = $db->runMsSqlQuery($sqlEmpDes);
                    $SurnameWithInitialsED = "";
                    while ($rowED = sqlsrv_fetch_array($resED, SQLSRV_FETCH_ASSOC)) {
                        /* //$rowED = sqlsrv_fetch_array($resED, SQLSRV_FETCH_ASSOC); */
                        $SurnameWithInitialsED .= $rowED['SurnameWithInitials'] . " / ";
                    }

                    if ($SurnameWithInitialsED == '') {
                        $sqlEmpDes = "SELECT        TeacherMast.ID, TeacherMast.NIC, TeacherMast.SurnameWithInitials, CD_AccessRoles.AccessRoleValue, CD_CensesNo.InstitutionName, CD_CensesNo.CenCode, CD_AccessRoles.AccessRole
	FROM            CD_CensesNo INNER JOIN
							 StaffServiceHistory ON CD_CensesNo.CenCode = StaffServiceHistory.InstCode INNER JOIN
							 TeacherMast INNER JOIN
							 Passwords ON TeacherMast.NIC = Passwords.NICNo INNER JOIN
							 CD_AccessRoles ON Passwords.AccessLevel = CD_AccessRoles.AccessRoleValue ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
	WHERE        (CD_AccessRoles.AccessRoleValue = '$ApproveDesignationNominiCode') AND (CD_CensesNo.CenCode = N'$ApproveInstCode') AND (StaffServiceHistory.ServiceRecTypeCode != 'RT01' or StaffServiceHistory.ServiceRecTypeCode IS NULL)";
                        /* // || CD_AccessRoles.AccessRoleValue = '$ApproveDesignationNominiCode' */
                        $resED = $db->runMsSqlQuery($sqlEmpDes);

                        while ($rowED = sqlsrv_fetch_array($resED, SQLSRV_FETCH_ASSOC)) {
                            /* //$rowED = sqlsrv_fetch_array($resED, SQLSRV_FETCH_ASSOC); */
                            $SurnameWithInitialsED .= $rowED['SurnameWithInitials'] . " / ";
                        }

                        /* //$rowED = sqlsrv_fetch_array($resED, SQLSRV_FETCH_ASSOC);
                          //$SurnameWithInitialsED= $rowED['SurnameWithInitials']; */
                    }

                    /* //$SurnameWithInitialsED="abc / cde/ / "; */
                    $SurnameWithInitialsED = substr($SurnameWithInitialsED, 0, -3);
                    ?>
                    <?php if ($endPoint == '') { ?>
                        <tr>
                            <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                    <tr>
                                        <td width="15%" style="font-weight: bold">Officer Name<?php
                                            //echo "-$ApproveInstCode-";//echo "<br>";
                                            //echo "-$loggedSchool-";echo "<br>";
                                            ?><?php //echo "-$ApproveDesignationCode-"; echo "<br>"; echo "-$accLevel-";  ?></td>
                                        <td width="1%">:</td>
                                        <td width="34%"><?php echo $SurnameWithInitialsApproved; ?></td>
                                        <td width="16%" style="font-weight: bold">Comment</td>
                                        <td width="1%">:</td>
                                        <td width="33%" rowspan="3"><textarea name="ApproveComment" id="ApproveComment" cols="35" rows="5" <?php if ($txt_block == TRUE) { ?>readonly<?php } ?>><?php echo $Remarks ?></textarea></td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold">Designation</td>
                                        <td>:</td>
                                        <td><?php echo $AccessRole; ?> [<?php echo $InstitutionName ?>]</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight: bold">Action</td>
                                        <td>:</td>
                                        <td>
                                            <?php if ($ApproveDesignationCode != '3000') { ?>
                                                <select class="select2a_n" id="ApprovedStatus" name="ApprovedStatus" <?php if ($action_block == TRUE) { ?>disabled="disabled"<?php } ?>>
                                                    <option value="" <?php if ($ApprovedStatus == '') { ?> selected="selected"<?php } ?>>Not approved from previous user</option>
                                                    <option value="P" <?php if ($ApprovedStatus == 'P') { ?> selected="selected"<?php } ?>>Pending</option>
                                                    <option value="A" <?php if ($ApprovedStatus == 'A') { ?> selected="selected"<?php } ?>>Approve</option>
                                                    <option value="R" <?php if ($ApprovedStatus == 'R') { ?> selected="selected"<?php } ?>>Reject</option>
                                                </select>
                                            <?php } else { ?>
                                                <select class="select2a_n" id="ApprovedStatus" name="ApprovedStatus" disabled>
                                                    <option value="P" <?php if ($ApprovedStatus == 'P') { ?> selected="selected"<?php } ?>>Pending</option>
                                                    <option value="Y" <?php if ($ApprovedStatus == 'Y') { ?> selected="selected"<?php } ?>>System Approved</option>
                                                    <option value="RR" <?php if ($ApprovedStatus == 'RR') { ?> selected="selected"<?php } ?>>Release with replacement</option>
                                                    <option value="RN" <?php if ($ApprovedStatus == 'RN') { ?> selected="selected"<?php } ?>>Release without replacement</option>
                                                    <option value="CR" <?php if ($ApprovedStatus == 'CR') { ?> selected="selected"<?php } ?>>Cannot Release</option>
                                                </select>
                                            <?php } ?>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table></td>
                        </tr>

                        <tr>
                            <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td valign="top">&nbsp;</td>
                            <td valign="top">&nbsp;</td>
                        </tr>
                        <?php
                        if ($ApproveInstCode == $loggedSchool)
                            $endPoint = "Y";
                        $approvedPre = $ApprovedStatus;
                    }
                }
                ?>
                <?php if ($form_submit == TRUE) { ?>
                    <tr>
                        <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                                <tr>
                                    <td width="32%">&nbsp;</td>
                                    <td width="68%"><input type="hidden" name="ApID" value="<?php echo $ApID ?>" />
                                        <input type="hidden" name="RequestID" value="<?php echo $id ?>" />
                                        <input type="hidden" name="RequestType" value="ServiceUpdate" />
                                        <input type="hidden" name="cat" value="services" /><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                                </tr>
                            </table></td>
                        <td valign="top">&nbsp;</td>
                    </tr>
                <?php } ?>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
            </table>
        <?php } ?>
    </div>

</form>
