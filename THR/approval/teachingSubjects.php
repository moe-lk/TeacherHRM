<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
$msg = "";
$success = "";
include('../activityLog.php');
if (isset($_POST["FrmSubmit"])) {

    $dateU = date('Y-m-d H:i:s');
    $dateUP = date('Y-m-d');
    $UpdateBy = "Add by $NICUser";
    //teacher mast
    $RegID = $_REQUEST['RegID'];
    $IsApproved = $_REQUEST['IsApproved'];
    $ApproveComment = addslashes($_REQUEST['ApproveComment']);
    $msg = "";

    //get data from temp table - Start
    $reqTab = "SELECT [ID]
      ,[NIC]
      ,[TeacherMastID]
      ,[PermResiID]
      ,[CurrResID]
      ,[dDateTime]
      ,[IsApproved]
      ,[ApproveComment]
      ,[ApproveDate]
      ,[ApprovedBy]
      ,[UpdateBy]
  FROM [dbo].[TG_EmployeeUpdatePersInfo] WHERE ID='$RegID'";

    $stmtE = $db->runMsSqlQuery($reqTab);
    $rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC);
    $NIC = trim($rowE['NIC']);
    $TeacherMastID = trim($rowE['TeacherMastID']);
    $PermResiID = trim($rowE['PermResiID']);
    $CurrResID = trim($rowE['CurrResID']);
    if ($IsApproved == 'Y') {
        //update data into master table - Start    

        $selectFromTmp = "SELECT NIC,Title,SurnameWithInitials,FullName,CONVERT(varchar(20),DOB,121) AS DOB,UpdateBy,emailaddr,EthnicityCode,GenderCode,ReligionCode,MobileTel From  UP_TeacherMast where ID='$TeacherMastID'";

        $resABC = $db->runMsSqlQuery($selectFromTmp);
        $rowABC = sqlsrv_fetch_array($resABC, SQLSRV_FETCH_ASSOC);
        $NIC = trim($rowABC['NIC']);
        $Title = trim($rowABC['Title']);
        $SurnameWithInitials = trim($rowABC['SurnameWithInitials']);
        $FullName = trim($rowABC['FullName']);
        $DOB = $rowABC['DOB'];
        $UpdateBy = trim($rowABC['UpdateBy']);
        $emailaddr = trim($rowABC['emailaddr']);
        $EthnicityCode = trim($rowABC['EthnicityCode']);
        $GenderCode = trim($rowABC['GenderCode']);
        $ReligionCode = trim($rowABC['ReligionCode']);
        $MobileTel = trim($rowABC['MobileTel']);
        $RecordLog = "Approved by $NICUser";

        $queryMainUpdate = "UPDATE TeacherMast SET Title='$Title',SurnameWithInitials='$SurnameWithInitials',FullName='$FullName',DOB='$DOB',LastUpdate='$dateU',UpdateBy='$UpdateBy', RecordLog='$RecordLog', emailaddr='$emailaddr', EthnicityCode='$EthnicityCode', GenderCode='$GenderCode', ReligionCode='$ReligionCode', MobileTel='$MobileTel' WHERE NIC='$NIC'";

        $db->runMsSqlQueryInsert($queryMainUpdate);
        //update data into master table - End
        //update data into master table - Start
        $sqlCopyMaster = "INSERT INTO StaffAddrHistory			   (NIC,AddrType,Address,DSCode,DISTCode,Tel,AppDate,UpdateBy,LastUpdate,RecordLog,GSDivision)
	SELECT NIC,AddrType,Address,DSCode,DISTCode,Tel,AppDate,UpdateBy,LastUpdate,RecordLog,GSDivision FROM UP_StaffAddrHistory where ID='$CurrResID'";
        $curAddressID = $db->runMsSqlQueryInsert($sqlCopyMaster);

        //update data into master table - End
        //update data into master table - Start
        $sqlCopyMaster = "INSERT INTO StaffAddrHistory			   (NIC,AddrType,Address,DSCode,DISTCode,Tel,AppDate,UpdateBy,LastUpdate,RecordLog,GSDivision)
	SELECT NIC,AddrType,Address,DSCode,DISTCode,Tel,AppDate,UpdateBy,LastUpdate,RecordLog,GSDivision FROM UP_StaffAddrHistory where ID='$PermResiID'";
        $perAddressID = $db->runMsSqlQueryInsert($sqlCopyMaster);
        //update data into master table - End

        $sqlDelete = "DELETE FROM StaffAddrHistory WHERE (NIC='$NIC') AND (ID!='$curAddressID') AND (ID!='$perAddressID')";
        $db->runMsSqlQuery($sqlDelete);


        //update TeacherMaster
        $queryMainUpdate = "UPDATE TeacherMast SET PerResRef='$perAddressID', CurResRef='$curAddressID' WHERE NIC='$NIC'";
        $db->runMsSqlQuery($queryMainUpdate);


        $queryMainUpdate = "UPDATE TG_EmployeeUpdatePersInfo SET IsApproved='Y',ApproveDate='$dateU',ApprovedBy='$NICUser', ApproveComment='$ApproveComment' WHERE id='$RegID'";
        $db->runMsSqlQuery($queryMainUpdate);


        //make it approved
        $sqlUpdateUp = "UPDATE UP_TeacherMast SET IsApproved='Y' WHERE ID='$TeacherMastID'";
        $db->runMsSqlQuery($sqlUpdateUp);

        $sqlUpdateUp1 = "UPDATE UP_StaffAddrHistory SET IsApproved='Y' WHERE ID='$PermResiID'";
        $db->runMsSqlQuery($sqlUpdateUp1);

        $sqlUpdateUp2 = "UPDATE UP_StaffAddrHistory SET IsApproved='Y' WHERE ID='$CurrResID'";
        $db->runMsSqlQuery($sqlUpdateUp2);


        //Delete temp record
        /* $queryTmpDel = "DELETE FROM UP_TeacherMast WHERE ID='$TeacherMastID'";
          $db->runMsSqlQuery($queryTmpDel);

          $queryTmpDel = "DELETE FROM UP_StaffAddrHistory WHERE ID='$PermResiID'";
          $db->runMsSqlQuery($queryTmpDel);

          $queryTmpDel = "DELETE FROM UP_StaffAddrHistory WHERE ID='$CurrResID'";
          $db->runMsSqlQuery($queryTmpDel); */

        audit_trail($NIC, $_SESSION["NIC"], 'approval\updateRequestPersonalInfo.php', 'Insert', 'TeacherMast,StaffAddrHistory', 'Approve personal info.');

        $msg .= "Your action(Approve) was successffully submitted.<br>";
    } else {

        $queryTmpDel = "DELETE FROM UP_TeacherMast WHERE ID='$TeacherMastID'";
        $db->runMsSqlQuery($queryTmpDel);

        $queryTmpDel = "DELETE FROM UP_StaffAddrHistory WHERE ID='$PermResiID'";
        $db->runMsSqlQuery($queryTmpDel);

        $queryTmpDel = "DELETE FROM UP_StaffAddrHistory WHERE ID='$CurrResID'";
        $db->runMsSqlQuery($queryTmpDel);

        $queryTG = "DELETE FROM TG_EmployeeUpdatePersInfo WHERE ID='$RegID'";
        $db->runMsSqlQuery($queryTG);

        audit_trail($NIC, $_SESSION["NIC"], 'approval\updateRequestPersonalInfo.php', 'Delete', 'UP_StaffAddrHistory,TG_EmployeeUpdatePersInfo', 'Reject personal info.');

        //$queryMainUpdate = "UPDATE TG_EmployeeUpdatePersInfo SET IsApproved='R',ApproveDate='$dateU',ApprovedBy='$NICUser', ApproveComment='$ApproveComment' WHERE id='$RegID'";
        // $db->runMsSqlQuery($queryMainUpdate);
        $msg .= "Your action(Reject) was successffully submitted.<br>";
    }
}

if ($id != '') {
    $reqTab = "SELECT [ID]
      ,[NIC]
      ,[TeacherMastID]
      ,[PermResiID]
      ,[CurrResID]
      ,CONVERT(varchar(20), TG_EmployeeUpdatePersInfo.dDateTime, 121) AS dDateTime
      ,[IsApproved]
      ,[ApproveComment]
      ,[ApproveDate]
      ,[ApprovedBy]
      ,[UpdateBy]
  FROM [dbo].[TG_EmployeeUpdatePersInfo] WHERE ID='$id'";

    $stmtE = $db->runMsSqlQuery($reqTab);
    $rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC);
    $NIC = trim($rowE['NIC']);
    $TeacherMastID = trim($rowE['TeacherMastID']);
    $PermResiID = trim($rowE['PermResiID']);
    $CurrResID = trim($rowE['CurrResID']);
    $UpdateBy = trim($rowE['UpdateBy']);
    $LastUpdate = $rowE['dDateTime'];

    $sqlteachrMst = "SELECT        UP_TeacherMast.ID, UP_TeacherMast.NIC, UP_TeacherMast.SurnameWithInitials, UP_TeacherMast.FullName, 
                         UP_TeacherMast.MobileTel, CONVERT(varchar(20), UP_TeacherMast.DOB, 121) AS DOB, UP_TeacherMast.emailaddr, CD_Title.TitleName, 
                         CD_Gender.[Gender Name], CD_nEthnicity.EthnicityName, CD_Religion.ReligionName
FROM            CD_Religion LEFT JOIN
                         CD_Gender LEFT JOIN
                         UP_TeacherMast LEFT JOIN
                         CD_Title ON UP_TeacherMast.Title = CD_Title.TitleCode ON CD_Gender.GenderCode = UP_TeacherMast.GenderCode LEFT JOIN
                         CD_nEthnicity ON UP_TeacherMast.EthnicityCode = CD_nEthnicity.Code ON CD_Religion.Code = UP_TeacherMast.ReligionCode
WHERE        (UP_TeacherMast.ID = '$TeacherMastID')"; //(UP_TeacherMast.NIC = '850263230V')

    /* if($accLevel=='99999'){
      echo $sqlteachrMst;
      } */

    $stmtTM = $db->runMsSqlQuery($sqlteachrMst);
    $rowTM = sqlsrv_fetch_array($stmtTM, SQLSRV_FETCH_ASSOC);
    $NIC = trim($rowTM['NIC']);
    $SurnameWithInitials = trim($rowTM['SurnameWithInitials']);
    $FullName = trim($rowTM['FullName']);
    $MobileTel = trim($rowTM['MobileTel']);
    $emailaddr = trim($rowTM['emailaddr']);
    $DOB = trim($rowTM['DOB']);
    $TitleName = trim($rowTM['TitleName']);
    $GenderName = trim($rowTM['Gender Name']);
    $EthnicityName = trim($rowTM['EthnicityName']);
    $ReligionName = trim($rowTM['ReligionName']);

    $sqlContactInfo = "SELECT        CD_Districts.DistName, UP_StaffAddrHistory.Address, UP_StaffAddrHistory.Tel, UP_StaffAddrHistory.ID, CONVERT(varchar(20), UP_StaffAddrHistory.AppDate, 121) AS AppDate, 
                         CD_DSec.DSName,UP_StaffAddrHistory.GSDivision
FROM            UP_StaffAddrHistory LEFT JOIN
                         CD_Districts ON UP_StaffAddrHistory.DISTCode = CD_Districts.DistCode LEFT JOIN
                         CD_DSec ON UP_StaffAddrHistory.DSCode = CD_DSec.DSCode
WHERE        (UP_StaffAddrHistory.ID = '$PermResiID')";
    $stmtCI = $db->runMsSqlQuery($sqlContactInfo);
    $rowCI = sqlsrv_fetch_array($stmtCI, SQLSRV_FETCH_ASSOC);

    $Address = trim($rowCI['Address']);
    $Tel = trim($rowCI['Tel']);
    $AppDateCI = trim($rowCI['AppDate']);
    $DSName = trim($rowCI['DSName']);
    $DistName = trim($rowCI['DistName']);
    $GSDivision = trim($rowCI['GSDivision']);

    $sqlContactInfoC = "SELECT        CD_Districts.DistName, UP_StaffAddrHistory.Address, UP_StaffAddrHistory.Tel, UP_StaffAddrHistory.ID, CONVERT(varchar(20), UP_StaffAddrHistory.AppDate, 121) AS AppDate, 
                         CD_DSec.DSName, UP_StaffAddrHistory.GSDivision
FROM            UP_StaffAddrHistory LEFT JOIN
                         CD_Districts ON UP_StaffAddrHistory.DISTCode = CD_Districts.DistCode LEFT JOIN
                         CD_DSec ON UP_StaffAddrHistory.DSCode = CD_DSec.DSCode
WHERE        (UP_StaffAddrHistory.ID = '$CurrResID')";
    $stmtCIC = $db->runMsSqlQuery($sqlContactInfoC);
    $rowCIC = sqlsrv_fetch_array($stmtCIC, SQLSRV_FETCH_ASSOC);

    $AddressC = trim($rowCIC['Address']);
    $TelC = trim($rowCIC['Tel']);
    $AppDateCIC = trim($rowCIC['AppDate']);
    $DSNameC = trim($rowCIC['DSName']);
    $DistNameC = trim($rowCIC['DistName']);
    $GSDivisionC = trim($rowCIC['GSDivision']);
}

if ($id == '') {
    $Per_Page = 30;  // Per Page 
    //Get the page number 

    $Page = 1;

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


    //if ($accLevel == '14000' || $accLevel == '17000') {
    //     if ($accLevel >= '14000' && $accLevel <= '17999') {
    //         $approvSql = "WITH LIMIT AS(SELECT        TG_EmployeeUpdatePersInfo.ID, TG_EmployeeUpdatePersInfo.NIC, TG_EmployeeUpdatePersInfo.TeacherMastID, TG_EmployeeUpdatePersInfo.PermResiID, 
    // 							 TG_EmployeeUpdatePersInfo.CurrResID, CONVERT(varchar(20), TG_EmployeeUpdatePersInfo.dDateTime, 121) AS dDateTime, 
    // 							 TG_EmployeeUpdatePersInfo.IsApproved, UP_TeacherMast.SurnameWithInitials, CD_Title.TitleName, CD_Zone.InstitutionName, CD_Districts.DistName, ROW_NUMBER() OVER (ORDER BY TG_EmployeeUpdatePersInfo.ID ASC) AS 'RowNumber'
    // 	FROM            UP_TeacherMast INNER JOIN
    // 							 TG_EmployeeUpdatePersInfo ON UP_TeacherMast.ID = TG_EmployeeUpdatePersInfo.TeacherMastID INNER JOIN
    // 							 CD_Title ON UP_TeacherMast.Title = CD_Title.TitleCode INNER JOIN
    // 							 CD_Zone ON TG_EmployeeUpdatePersInfo.ZoneCode = CD_Zone.CenCode INNER JOIN
    // 							 CD_Districts ON CD_Zone.DistrictCode = CD_Districts.DistCode ON TeacherMast.NIC = UP_TeacherMast.NIC INNER JOIN
    //                       CD_Service ON StaffServiceHistory.ServiceTypeCode = CD_Service.ServCode
    // WHERE     (TG_EmployeeUpdatePersInfo.IsApproved = 'N') AND (CD_Service.ServiceName LIKE '%SLEAS%') AND (CD_Districts.ProCode = N'$ProCodeU')";
    //     } else {
    //         $approvSql = "WITH LIMIT AS(SELECT        TG_EmployeeUpdatePersInfo.ID, TG_EmployeeUpdatePersInfo.NIC, TG_EmployeeUpdatePersInfo.TeacherMastID, TG_EmployeeUpdatePersInfo.PermResiID, 
    // 							 TG_EmployeeUpdatePersInfo.CurrResID, CONVERT(varchar(20), TG_EmployeeUpdatePersInfo.dDateTime, 121) AS dDateTime, 
    // 							 TG_EmployeeUpdatePersInfo.IsApproved, UP_TeacherMast.SurnameWithInitials, CD_Title.TitleName, CD_Zone.InstitutionName, CD_Districts.DistName, ROW_NUMBER() OVER (ORDER BY TG_EmployeeUpdatePersInfo.ID ASC) AS 'RowNumber'
    // 	FROM            UP_TeacherMast INNER JOIN
    // 							 TG_EmployeeUpdatePersInfo ON UP_TeacherMast.ID = TG_EmployeeUpdatePersInfo.TeacherMastID INNER JOIN
    // 							 CD_Title ON UP_TeacherMast.Title = CD_Title.TitleCode INNER JOIN
    // 							 CD_Zone ON TG_EmployeeUpdatePersInfo.ZoneCode = CD_Zone.CenCode INNER JOIN
    // 							 CD_Districts ON CD_Zone.DistrictCode = CD_Districts.DistCode
    // 							 WHERE TG_EmployeeUpdatePersInfo.IsApproved='N'";
    //     }
    //     if ($NICSearch)
    //         $approvSql .= " and (TG_EmployeeUpdatePersInfo.NIC like '%$NICSearch%')";
    //     // if ($accLevel == '11050' || $accLevel == '11000' || $accLevel == '10000')
    //     if ($AccessRoleType == "ZN")
    //         $approvSql .= " and TG_EmployeeUpdatePersInfo.ZoneCode='$loggedSchool'";

    //     $approvSql .= ")
    // 	select * from LIMIT WHERE RowNumber BETWEEN $Page_Start AND $Page_End";

    //     $countTotal = "SELECT        TG_EmployeeUpdatePersInfo.ID
    // 	FROM            UP_TeacherMast INNER JOIN
    // 							 TG_EmployeeUpdatePersInfo ON UP_TeacherMast.ID = TG_EmployeeUpdatePersInfo.TeacherMastID INNER JOIN
    // 							 CD_Title ON UP_TeacherMast.Title = CD_Title.TitleCode INNER JOIN
    // 							 CD_Zone ON TG_EmployeeUpdatePersInfo.ZoneCode = CD_Zone.CenCode INNER JOIN
    // 							 CD_Districts ON CD_Zone.DistrictCode = CD_Districts.DistCode
    // 							 WHERE TG_EmployeeUpdatePersInfo.IsApproved='N'";

    //     if ($NICSearch)
    //         $countTotal .= " and (TG_EmployeeUpdatePersInfo.NIC like '%$NICSearch%')";
    //     //if ($accLevel == '11050' || $accLevel == '11000' || $accLevel == '10000')
    //     if ($AccessRoleType == "ZN")
    //         $countTotal .= " and TG_EmployeeUpdatePersInfo.ZoneCode='$loggedSchool'";
    //     //if($NICSearch){echo $countTotal;}
    //     //if ($accLevel == '14000' || $accLevel == '17000') {
    //     if ($accLevel >= '14000' && $accLevel <= '17999') {
    //         $countTotal = "SELECT     TG_EmployeeUpdatePersInfo.ID
    // FROM         TeacherMast INNER JOIN
    //                       StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID INNER JOIN
    //                       UP_TeacherMast INNER JOIN
    //                       TG_EmployeeUpdatePersInfo ON UP_TeacherMast.ID = TG_EmployeeUpdatePersInfo.TeacherMastID INNER JOIN
    //                       CD_Title ON UP_TeacherMast.Title = CD_Title.TitleCode INNER JOIN
    //                       CD_Zone ON TG_EmployeeUpdatePersInfo.ZoneCode = CD_Zone.CenCode INNER JOIN
    //                       CD_Districts ON CD_Zone.DistrictCode = CD_Districts.DistCode ON TeacherMast.NIC = UP_TeacherMast.NIC INNER JOIN
    //                       CD_Service ON StaffServiceHistory.ServiceTypeCode = CD_Service.ServCode
    // WHERE     (TG_EmployeeUpdatePersInfo.IsApproved = 'N') AND (CD_Service.ServiceName LIKE '%SLEAS%') AND (CD_Districts.ProCode = N'$ProCodeU')";
    //     }
    $approvSql = "SELECT TempTeachingDetailsTemp.ID
    ,TempTeachingDetailsTemp.NIC
    ,[SurnameWithInitials]
    ,[FullName]
    ,[TchSubject1]
    ,[TchSubject2]
    ,[TchSubject3]
    ,[Medium1]
    ,[Medium2]
    ,[Medium3]
    ,[GradeCode1]
    ,[GradeCode2]
    ,[GradeCode3]
    ,[SchoolType]
    ,TempTeachingDetailsTemp.RecStatus
    ,TempTeachingDetailsTemp.RecordLog
    ,TempTeachingDetailsTemp.LastUpdate
FROM [MOENational].[dbo].[TempTeachingDetailsTemp] INNER JOIN [TeacherMast] ON TempTeachingDetailsTemp.NIC = TeacherMast.NIC";

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
    <div style="width:738px; margin-top:10px;">
        <form method="post" action="" name="frmSrch" id="frmSrch">
            <table width="100%" cellspacing="1" cellpadding="1">
                <tr>
                    <td width="19%">Search by NIC</td>
                    <td width="27%"><input name="NICSearch" type="text" class="input2_n" id="NICSearch" value="" placeholder="NIC" /></td>
                    <td width="11%"><input name="FrmSrch" type="submit" id="FrmSrch" style="background-image: url(../cms/images/searchN.png); width:84px; height:26px; background-color:transparent; border:none; cursor:pointer;" value="" /></td>
                    <td width="43%">
                        <div id="txt_available" style="font-weight:bold;"></div>
                    </td>
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
<form method="post" action="updateRequestPersonalInfo-15.html" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
    <?php if ($msg != '' || $success != '') { //if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){    
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
    <div style="width:738px; float:left;">
        <?php if ($id == '') { ?>
            <table width="100%" cellpadding="0" cellspacing="0">

                <tr>
                    <td width="56%"><?php echo $TotaRows ?> Record(s) found. Showing <?php echo $Per_Page ?> records per page.</td>
                    <td width="44%">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#CCCCCC">
                        <table width="100%" cellspacing="1" cellpadding="1">
                            <tr>
                                <td width="4%" height="25" align="center" bgcolor="#999999">#</td>
                                <td width="25%" align="center" bgcolor="#999999">Employee Name</td>
                                <td width="8%" align="center" bgcolor="#999999">NIC</td>
                                <td width="10%" align="center" bgcolor="#999999">Teaching subject for most hours</td>
                                <td width="5%" align="center" bgcolor="#999999">Medium</td>
                                <td width="5%" align="center" bgcolor="#999999">Gradespan</td>
                                <td width="10%" align="center" bgcolor="#999999">Teaching subject for seecond most hours</td>
                                <td width="5%" align="center" bgcolor="#999999">Medium</td>
                                <td width="5%" align="center" bgcolor="#999999">Gradespan</td>
                                <td width="10%" align="center" bgcolor="#999999">Capable subject</td>
                                <td width="5%" align="center" bgcolor="#999999">Medium</td>
                                <td width="5%" align="center" bgcolor="#999999">Gradespan</td>
                                <td width="3%"></td>


                            </tr>
                            <tr>
                                <?php
                                //$i=1; //echo $approvSql;
                                $stmt = $db->runMsSqlQuery($approvSql);
                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                    echo "<td>" . $row["ID"] . "</td>";
                                    echo "<td>" . $row["SurnameWithInitials"] . "</td>";
                                    echo "<td>" . $row["TempTeachingDetailsTemp.NIC"] . "</td>";
                                    echo "<td>" . $row["TchSubject1"] . "</td>";
                                    echo "<td>" . $row["Medium1"] . "</td>";
                                    echo "<td>" . $row["GradeCode1"] . "</td>";
                                    echo "<td>" . $row["TchSubject2"] . "</td>";
                                    echo "<td>" . $row["Medium2"] . "</td>";
                                    echo "<td>" . $row["GradeCode2"] . "</td>";
                                    echo "<td>" . $row["TchSubject3"] . "</td>";
                                    echo "<td>" . $row["Medium3"] . "</td>";
                                    echo "<td>" . $row["GradeCode3"] . "</td>";
                                ?>
                                    <td bgcolor="#FFFFFF" align="center"><a href="updateRequestPersonalInfo-15--<?php echo $RequestID ?>.html"><img src="images/more_info.png" /></a></td>
                            </tr>
                        <?php } ?>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <table width="100%" cellspacing="1" cellpadding="1">
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
                        </table>
                    </td>
                </tr>

            </table> <?php } else { ?>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td colspan="2" align="center" height="30px" style="font-size:16px; font-weight:bold;"><u>Update Request - Personal Information</u></td>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;"><strong>&nbsp;Personal Information</strong></td>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td align="right" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td width="50%" valign="top">
                        <table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="30%" align="left" valign="top"><strong>NIC</strong></td>
                                <td width="3%" align="left" valign="top"><strong>:</strong></td>
                                <td width="67%" align="left" valign="top"><?php echo $NIC ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Title</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $TitleName ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Surname with Initials</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $SurnameWithInitials ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Full Name</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $FullName ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Date of Birth</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $DOB; ?></td>
                            </tr>

                        </table>
                    </td>
                    <td width="50%" valign="top">
                        <table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="38%" align="left" valign="top"><strong>Ethinicity</strong></td>
                                <td width="3%" align="left" valign="top"><strong>:</strong></td>
                                <td width="59%" align="left" valign="top"><?php echo $EthnicityName ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Gender</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $GenderName ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Religion</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $ReligionName ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top" style="font-weight: bold">Email Address</td>
                                <td align="left" valign="top" style="font-weight: bold">:</td>
                                <td align="left" valign="top"><?php echo $emailaddr ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top" style="font-weight: bold">Mobile Number</td>
                                <td align="left" valign="top" style="font-weight: bold">:</td>
                                <td align="left" valign="top"><?php echo $MobileTel ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;"><strong>Permanant Residance Details</strong></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">
                        <table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="15%" align="left" valign="top"><strong>Address</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" rowspan="5" align="left" valign="top"><?php echo $Address ?></td>
                                <td width="19%" align="left" valign="top"><strong>District</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="30%" align="left" valign="top"><?php echo $DistName ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top"><strong>DS Division</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $DSName ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top"><strong>GS Division</strong></td>
                                <td align="left" valign="top">:</td>
                                <td align="left" valign="top"><?php echo $GSDivision ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top"><strong>Telephone</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $Tel ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top"><strong>Effective Date</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $AppDateCI ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;"><strong>Current Residance Details</strong></td>
                </tr>

                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">
                        <table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="15%" align="left" valign="top"><strong>Address</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" rowspan="5" align="left" valign="top"><?php echo $AddressC ?></td>
                                <td width="19%" align="left" valign="top"><strong>District</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="30%" align="left" valign="top"><?php echo $DistNameC ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top"><strong>DS Division</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $DSNameC ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top"><strong>GS Division</strong></td>
                                <td align="left" valign="top">:</td>
                                <td align="left" valign="top"><?php echo $GSDivisionC ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top"><strong>Telephone</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $TelC ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top">&nbsp;</td>
                                <td align="left" valign="top"><strong>Effective Date</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $AppDateCIC ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
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
                    <td colspan="2" align="left" valign="top"><strong>Request By
                            <?php
                            $sqlTName = "SELECT SurnameWithInitials FROM TeacherMast where NIC='$UpdateBy'";
                            $stmtTn = $db->runMsSqlQuery($sqlTName);
                            $rowTn = sqlsrv_fetch_array($stmtTn, SQLSRV_FETCH_ASSOC);
                            $SurnameWithInitialsU = $rowTn['SurnameWithInitials'];
                            ?>
                        </strong> <?php echo $SurnameWithInitialsU ?> on <?php echo $LastUpdate ?></td>
                </tr>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                </tr>

                <tr>
                    <td colspan="2" valign="top">
                        <table width="100%" cellspacing="2" cellpadding="2">
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
                        </table>
                    </td>
                </tr>
                <tr>
                    <td valign="top">
                        <table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="32%">&nbsp;</td>
                                <td width="68%"><input type="hidden" name="RegID" value="<?php echo $id ?>" /><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                            </tr>
                        </table>
                    </td>
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