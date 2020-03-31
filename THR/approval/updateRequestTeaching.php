<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
$msg = "";
$success = "";

$AccessRoleType = $_SESSION['AccessRoleType'];

if (isset($_POST["FrmSubmit"])) {
    include('../activityLog.php');
    $dateU = date('Y-m-d H:i:s');
    $dateUP = date('Y-m-d');
    $UpdateBy = "Add by $NICUser";
    //teacher mast
    $RegID = $_REQUEST['RegID'];
    $IsApproved = $_REQUEST['IsApproved'];
    $ApproveComment = addslashes($_REQUEST['ApproveComment']);
    $msg = "";

    $reqTab = "SELECT [ID]
      ,[NIC]
      ,[TeachingID]
  FROM [dbo].[TG_EmployeeUpdateTeaching] WHERE ID='$RegID'";

    $stmtE = $db->runMsSqlQuery($reqTab);
    $rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC);
    $NIC = trim($rowE['NIC']);
    $TeachingID = trim($rowE['TeachingID']);


    if ($IsApproved == 'Y') {

        //update data into master table - Start
        $sqlCopyMaster = "INSERT INTO TeacherSubject	(NIC,SubjectType,SubjectCode,MediumCode,SecGradeCode,Grade,LastUpdate,UpdatedBy,RecordLog)
SELECT NIC,SubjectType,SubjectCode,MediumCode,SecGradeCode,Grade,LastUpdate,UpdatedBy,RecordLog FROM UP_TeacherSubject where ID='$TeachingID'";

        $db->runMsSqlQuery($sqlCopyMaster);
        //update data into master table - End
        //
        //TeacherMastID='$TeacherMastID',PermResiID='$PermResiID',CurrResID='$CurrResID',
        $queryMainUpdate = "UPDATE TG_EmployeeUpdateTeaching SET IsApproved='Y',ApproveDate='$dateU',ApprovedBy='$NICUser', ApproveComment='$ApproveComment' WHERE id='$RegID'";
        $db->runMsSqlQuery($queryMainUpdate);

        //Delete temp record
        $queryTmpDel = "DELETE FROM UP_TeacherSubject WHERE ID='$TeachingID'";
        $db->runMsSqlQuery($queryTmpDel);

        audit_trail($NIC, $_SESSION["NIC"], 'approval\updateRequestTeaching.php', 'Insert', 'TeacherSubject', 'Approve teaching info.');

        $msg .= "Your action(Approve) was successffully submitted.<br>";
    } else {
        //$queryMainUpdate = "UPDATE TG_EmployeeUpdateTeaching SET IsApproved='R',ApproveDate='$dateU',ApprovedBy='$NICUser', ApproveComment='$ApproveComment' WHERE id='$RegID'";        
        //$db->runMsSqlQuery($queryMainUpdate);

        $queryTmpDel = "DELETE FROM UP_TeacherSubject WHERE ID='$TeachingID'";
        $db->runMsSqlQuery($queryTmpDel);

        //$sqlDel2 = "DELETE FROM TG_EmployeeUpdateTeaching WHERE ID=$RegID";
       // $db->runMsSqlQuery($sqlDel2);
        
        audit_trail($NIC, $_SESSION["NIC"], 'approval\updateRequestTeaching.php', 'Delete', 'UP_TeacherSubject,TG_EmployeeUpdateTeaching', 'Reject teaching info.');

        $msg .= "Your action(Reject) was successffully submitted.<br>";
    }
}

if ($id != '') {
    /* $reqTab="SELECT [ID]
      ,[NIC]
      ,[TeacherMastID]
      ,[dDateTime]
      ,[IsApproved]
      ,[ApproveComment]
      ,[ApproveDate]
      ,[ApprovedBy]
      ,[UpdateBy]
      FROM [dbo].[TG_EmployeeUpdateFamilyInfo] WHERE ID='$id'";

      $stmtE= $db->runMsSqlQuery($reqTab);
      $rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC);
      $NIC = trim($rowE['NIC']);
      $TeacherMastID = trim($rowE['TeacherMastID']); */

    $sqlteachrMst = "SELECT        TeacherMast.SurnameWithInitials, CONVERT(varchar(20), TG_EmployeeUpdateTeaching.dDateTime, 121) AS dDateTime,
        TG_EmployeeUpdateTeaching.NIC, CD_SubjectTypes.SubTypeName, CD_Subject.SubjectName, 
                         CD_Subject.SubCode, CD_Medium.Medium, CD_SecGrades.GradeName
FROM            TeacherMast INNER JOIN
                         TG_EmployeeUpdateTeaching ON TeacherMast.NIC = TG_EmployeeUpdateTeaching.NIC INNER JOIN
                         UP_TeacherSubject ON TG_EmployeeUpdateTeaching.TeachingID = UP_TeacherSubject.ID INNER JOIN
                         CD_SubjectTypes ON UP_TeacherSubject.SubjectType = CD_SubjectTypes.SubType INNER JOIN
                         CD_Subject ON UP_TeacherSubject.SubjectCode = CD_Subject.SubCode INNER JOIN
                         CD_Medium ON UP_TeacherSubject.MediumCode = CD_Medium.Code INNER JOIN
                         CD_SecGrades ON UP_TeacherSubject.SecGradeCode = CD_SecGrades.GradeCode
WHERE        (TG_EmployeeUpdateTeaching.ID = '$id')"; //(ArchiveUP_TeacherMast.NIC = '850263230V')

    $stmtTM = $db->runMsSqlQuery($sqlteachrMst);
    $rowTM = sqlsrv_fetch_array($stmtTM, SQLSRV_FETCH_ASSOC);
    $SurnameWithInitials = trim($rowTM['SurnameWithInitials']);
    $SubTypeName = trim($rowTM['SubTypeName']);
    $SubjectName = trim($rowTM['SubjectName']);
    $SubCode = trim($rowTM['SubCode']);
    $Medium = trim($rowTM['Medium']);
    $GradeName = trim($rowTM['GradeName']);
    $NICParent = trim($rowTM['NIC']);
}

if ($id == '') {

    $Per_Page = 30;  // Per Page 
    //Get the page number 

    $Page = 1;

    //Determine if it is the first page 

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

    $approvSql = "WITH LIMIT AS(SELECT        CD_Zone.InstitutionName, CD_Districts.DistName, TeacherMast.SurnameWithInitials,  CONVERT(varchar(20), TG_EmployeeUpdateTeaching.dDateTime, 121) AS dDateTime, TG_EmployeeUpdateTeaching.ID, TG_EmployeeUpdateTeaching.NIC, ROW_NUMBER() OVER (ORDER BY TG_EmployeeUpdateTeaching.ID ASC) AS 'RowNumber'
                        
FROM            TG_EmployeeUpdateTeaching INNER JOIN
                         CD_Zone INNER JOIN
                         CD_Districts ON CD_Zone.DistrictCode = CD_Districts.DistCode ON TG_EmployeeUpdateTeaching.ZoneCode = CD_Zone.CenCode INNER JOIN
                         UP_TeacherSubject INNER JOIN
                         TeacherMast ON UP_TeacherSubject.NIC = TeacherMast.NIC ON TG_EmployeeUpdateTeaching.TeachingID = UP_TeacherSubject.ID
WHERE        (TG_EmployeeUpdateTeaching.IsApproved = 'N')";
    if ($NICSearch)
        $approvSql .= " and (TG_EmployeeUpdateTeaching.NIC like '%$NICSearch%')";
    //if ($accLevel == '11050' || $accLevel == '11000')
    if ($AccessRoleType == "ZN")
        $approvSql .= " and TG_EmployeeUpdateTeaching.ZoneCode='$loggedSchool'";

    $approvSql .= ")
	select * from LIMIT WHERE RowNumber BETWEEN $Page_Start AND $Page_End";

    $countTotal = "SELECT       TG_EmployeeUpdateTeaching.ID
FROM            TG_EmployeeUpdateTeaching INNER JOIN
                         CD_Zone INNER JOIN
                         CD_Districts ON CD_Zone.DistrictCode = CD_Districts.DistCode ON TG_EmployeeUpdateTeaching.ZoneCode = CD_Zone.CenCode INNER JOIN
                         UP_TeacherSubject INNER JOIN
                         TeacherMast ON UP_TeacherSubject.NIC = TeacherMast.NIC ON TG_EmployeeUpdateTeaching.TeachingID = UP_TeacherSubject.ID
WHERE        (TG_EmployeeUpdateTeaching.IsApproved = 'N')";
    if ($NICSearch)
        $countTotal .= " and (TG_EmployeeUpdateTeaching.NIC like '%$NICSearch%')";
    //if ($accLevel == '11050' || $accLevel == '11000')
    if ($AccessRoleType == "ZN")
        $countTotal .= " and TG_EmployeeUpdateTeaching.ZoneCode='$loggedSchool'";
    $TotaRows = $db->rowCount($countTotal);
    if (!$TotaRows)
        $TotaRows = 0;

    //Declare previous/next page row guide 

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

    //Determine where the page will end 

    $Page_End = $Per_Page * $Page;
    if ($Page_End > $TotaRows) {
        $Page_End = $TotaRows;
    }
}
?>

<?php if ($id == '') { ?>
    <div style="width:738px; margin-top:10px;"><form method="post" action="" name="frmSrch" id="frmSrch"><table width="100%" cellspacing="1" cellpadding="1">
                <tr>
                    <td width="19%">Search by NIC</td>
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
            </table></form>
    </div>
<?php } ?>
<form method="post" action="updateRequestTeaching-19.html" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">

    <?php if ($msg != '' || $success != '') {//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){     ?> 
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
                                <td width="4%" height="25" align="center" bgcolor="#999999">#</td>
                                <td width="37%" align="center" bgcolor="#999999">Employee Name</td>
                                <td width="17%" align="center" bgcolor="#999999">NIC</td>
                                <td width="13%" align="center" bgcolor="#999999">Request Date</td>
                                <td width="23%" align="center" bgcolor="#999999">Zone</td>
                                <td width="6%" align="center" bgcolor="#999999">Action</td>
                            </tr>
                            <?php
                            $i = 1;
                            $stmt = $db->runMsSqlQuery($approvSql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $RequestID = $row['ID'];
                                $InstitutionName = $row['InstitutionName'];
                                $DistName = $row['DistName'];
                                ?>
                                <tr>
                                    <td height="20" bgcolor="#FFFFFF"><?php echo $i++; ?></td>
                                    <td bgcolor="#FFFFFF"><?php echo $row['SurnameWithInitials']; ?></td>
                                    <td bgcolor="#FFFFFF"><?php echo $row['NIC']; ?></td>
                                    <td bgcolor="#FFFFFF" align="center"><?php echo substr($row['dDateTime'], 0, 10); ?></td>
                                    <td bgcolor="#FFFFFF" align="center"><?php echo "$InstitutionName ($DistName)"; ?></td>
                                    <td bgcolor="#FFFFFF" align="center"><a href="updateRequestTeaching-19--<?php echo $RequestID ?>.html"><img src="images/more_info.png" /></a></td>
                                </tr>
                            <?php } ?>
                        </table></td>
                </tr>

                <tr>
                    <td colspan="2"><table width="100%" cellspacing="1" cellpadding="1">
                            <tr>
                                <td width="67%">Page <?php echo $Page ?> of <?php echo $Num_Pages ?></td>
                                <td width="20%" align="right"><?php
//Previous page 

                            if ($Prev_Page) {
                                echo " <a href='$ttle-$pageid-$Prev_Page.html?Page=$Prev_Page#related'><< Previous</a> ";
                            }

//Display total pages 
//for($i=1; $i<=$Num_Pages; $i++){ 


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
                                //Create next page link 

                                if ($Page != $Num_Pages) {
                                    //echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Next_Page#related'>Next>></a> "; 
                                    echo " <a href ='$ttle-$pageid-$Next_Page.html?Page=$Next_Page#related'>Next>></a> ";
                                }
                            ?></td>
                            </tr>
                        </table></td>
                </tr>

            </table> <?php } else { ?>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td colspan="2" align="center" height="30px" style="font-size:16px; font-weight:bold;"><u>Update Request - Teaching</u></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top"><strong>Employee Name&nbsp;:&nbsp;</strong><?php echo $SurnameWithInitials ?> [<?php echo $NICParent ?>]</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;"><strong>Details of Teaching</strong></td>
                </tr>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td align="right" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td width="75%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="20%" align="left" valign="top"><strong>Category</strong></td>
                                <td width="2%" align="left" valign="top"><strong>:</strong></td>
                                <td width="78%" align="left" valign="top"><?php echo $SubTypeName ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Subject</strong></td>
                                <td align="left" valign="top">:</td>
                                <td align="left" valign="top"><?php echo "$SubjectName [$SubCode]"; ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><span style="font-weight: bold">Medium</span></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $Medium ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top" style="font-weight: bold">Section/Grade</td>
                                <td align="left" valign="top">:</td>
                                <td align="left" valign="top"><?php echo $GradeName ?></td>
                            </tr>

                        </table>
                    </td>
                    <td width="25%" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="38%" align="left" valign="top">&nbsp;</td>
                                <td width="3%" align="left" valign="top">&nbsp;</td>
                                <td width="59%" align="left" valign="top">&nbsp;</td>
                            </tr>
                            <tr>
                                <td align="left" valign="top" style="font-weight: bold">&nbsp;</td>
                                <td align="left" valign="top" style="font-weight: bold">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                            </tr>
                        </table></td>
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
                    <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="15%" style="font-weight: bold">Officer Name</td>
                                <td width="1%">:</td>
                                <td width="34%"><?php echo $_SESSION["fullName"]; ?></td>
                                <td width="16%" style="font-weight: bold">Comment</td>
                                <td width="1%">:</td>
                                <td width="33%" rowspan="3"><textarea name="ApproveComment" id="ApproveComment" cols="35" rows="5"></textarea></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold">Designation</td>
                                <td>:</td>
                                <td><?php echo $loggedPositionName; ?></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold">Action</td>
                                <td>:</td>
                                <td><select class="select2a_n" id="IsApproved" name="IsApproved">
                                        <option value="Y">Approve</option>
                                        <option value="R">Reject</option>
                                    </select></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        </table></td>
                </tr>
                <tr>
                    <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="20%">&nbsp;</td>
                                <td width="80%"><input type="hidden" name="RegID" value="<?php echo $id ?>" /><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
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