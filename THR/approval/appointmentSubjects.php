<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
// $ApprovedDate = date("Y-m-d H:i:s");
// var_dump($ApprovedDate);

$msg = "";
$success = "";
include('../activityLog.php');
// var_dump($_POST);

if (isset($_POST["FrmSubmit"])) {

        $dateU = date('Y-m-d H:i:s');
        $dateUP = date('Y-m-d');
        
        //teacher mast
        $RegID = $_REQUEST['RegID'];
        $IsApproved = $_REQUEST['IsApproved'];
        $ApproveComment = addslashes($_REQUEST['ApproveComment']);
        $msg = "";

        //get data from temp table - Start
        $reqTab = "SELECT Temp_AppoinmentDetails.ID
        ,Temp_AppoinmentDetails.NIC
        ,[SurnameWithInitials]
        ,[FullName]
        ,[AppCategory]
        ,[AppSubject]
        ,[Medium]
        ,[SchoolType]
        ,[OtherSub]
        ,[RecordStatus]
        ,Temp_AppoinmentDetails. LastUpdate
        ,Temp_AppoinmentDetails.RecordLog AS RecordLog
        FROM Temp_AppoinmentDetails
        INNER JOIN [TeacherMast] ON Temp_AppoinmentDetails.NIC = TeacherMast.NIC WHERE Temp_AppoinmentDetails.ID='$RegID'";

        $stmtE = $db->runMsSqlQuery($reqTab);
        $rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC);
        $NIC = trim($rowE['NIC']);
        $TempID = $rowE['ID'];
        $SurnameWithInitials = $rowE['SurnameWithInitials'];
        $FullName = $rowE['FullName'];
        $AppCategory = $rowE['AppCategory'];
        $AppSubject = $rowE['AppSubject'];
        $Medium = $rowE['Medium'];
        $SchoolType = $rowE['SchoolType'];
        $OtherSub = $rowE['OtherSub'];
        $RecordStatus = $rowE['RecordStatus'];
        $UpdateBy = trim($rowE['RecordLog']);

        // $LastUpdate = $rowE['LastUpdate'];
        // $RecordLog = $rowE['RecordLog'];

        
    if ($IsApproved == 'Y') {
        $RecordLog = "Approved by $NICUser";
        $ApprovedDate = date("Y-m-d H:i:s");
        // var_dump($ApprovedDate);
        // var_dump($RecordLog);

        $SQLTBL = "SELECT * FROM [MOENational].[dbo].[AppoinmentDetails] WHERE NIC = '$NIC' AND RecordStatus = '1'";
        $TotalRows = $db->rowCount($SQLTBL);

        // var_dump($TotalRows);
        if (!$TotalRows){
            $queryMainInsert = "INSERT INTO [dbo].[AppoinmentDetails]
            ([NIC]
            ,[AppCategory]
            ,[AppSubject]
            ,[Medium]
            ,[SchoolType]
            ,[OtherSub]
            ,[ApprovedBy]
            ,[RecordStatus]
            ,[ApprovedDate]
            ,[ApproveComment])
            VALUES
            ('$NIC' 
            , '$AppCategory'
            , '$AppSubject' 
            , '$Medium'
            , '$SchoolType'
            , '$OtherSub'
            , '$RecordLog'
            , '1'
            , '$ApprovedDate'
            ,'$ApproveComment')";

            $db->runMsSqlQueryInsert($queryMainInsert);


                $sqlTempUpdate = "UPDATE [dbo].[Temp_AppoinmentDetails]
                                SET [RecordStatus] = '1'
                                WHERE Temp_AppoinmentDetails.ID = '$RegID'";
            
                $db->runMsSqlQueryInsert($sqlTempUpdate);
            
                // $sqlcomment = "UPDATE [dbo].[AppoinmentDetails] SET ApproveComment = '$ApproveComment' WHERE NIC = '$NIC'";
                // $db->runMsSqlQueryInsert($sqlcomment);

                audit_trail($NIC, $_SESSION["NIC"], 'approval\appointmentSubjects.php', 'Insert', 'AppoinmentDetails', 'Approve appointment info.');

                $msg .= "Your Approve was successfully submitted.<br>";
        }else{
            $qryupdate = "UPDATE [dbo].[AppoinmentDetails]
                        SET 
                        [AppCategory] = '$AppCategory'
                        ,[AppSubject] = '$AppSubject'
                        ,[Medium] = '$Medium'
                        ,[OtherSub] = '$OtherSub'
                        ,[ApprovedBy] = '$RecordLog'
                        ,[RecordStatus] = '1'
                        ,[ApprovedDate] = '$ApprovedDate'
                        ,[ApproveComment] = '$ApproveComment'
                        WHERE NIC = '$NIC'";
            $db->runMsSqlQueryInsert($qryupdate);

            $sqlTempUpdate = "UPDATE [dbo].[Temp_AppoinmentDetails]
                                SET [RecordStatus] = '1'
                                WHERE Temp_AppoinmentDetails.ID = '$RegID'";
            $db->runMsSqlQueryInsert($sqlTempUpdate);
            // var_dump($sqlTempUpdate);
            audit_trail($NIC, $_SESSION["NIC"], 'approval\appointmentSubjects.php', 'Update', 'AppoinmentDetails', 'Approve appointment info.');

            $msg .= "Your Update was successfully submitted.<br>";

        }
    } else {

        $sqlreject = "DELETE FROM [dbo].[Temp_AppoinmentDetails] WHERE Temp_AppoinmentDetails.ID = '$RegID'";
        // $sqlcomment = "UPDATE [dbo].[Temp_AppoinmentDetails] SET ApproveComment = '$ApproveComment' WHERE NIC = '$NIC'";
        $db->runMsSqlQueryInsert($sqlreject);
        
        $msg .= "Your Reject was successffully submitted.<br>";
    }
}

if ($id != '') {
    $reqTab = "SELECT Temp_AppoinmentDetails.ID
    ,Temp_AppoinmentDetails.NIC
    ,[SurnameWithInitials]
    ,[FullName]
    ,[AppCategory]
	,CD_AppSubCategory.AppointmentName
    ,[AppSubject]
	,[SubjectName]
    ,[Medium]
    ,[SchoolType]
    ,[OtherSub]
    ,[RecordStatus]
    ,Temp_AppoinmentDetails. LastUpdate
    ,Temp_AppoinmentDetails.RecordLog
FROM Temp_AppoinmentDetails
INNER JOIN [TeacherMast] ON Temp_AppoinmentDetails.NIC = TeacherMast.NIC 
INNER JOIN CD_AppSubCategory ON AppCategory = CD_AppSubCategory.ID
INNER JOIN CD_AppSubjects ON AppSubject = CD_AppSubjects.ID
WHERE Temp_AppoinmentDetails.ID='$id'";

    $stmtE = $db->runMsSqlQuery($reqTab);
    $rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC);
    $NIC = trim($rowE['NIC']);
    $TempID = $rowE['ID'];
    $SurnameWithInitials = $rowE['SurnameWithInitials'];
    $FullName = $rowE['FullName'];
    $AppCategory = $rowE['AppCategory'];
    $AppCatName = $rowE['AppointmentName'];
    $AppSubName = $rowE['SubjectName'];
    $AppSubject = $rowE['AppSubject'];
    $Medium = $rowE['Medium'];
    $SchoolType = $rowE['SchoolType'];
    $OtherSub = $rowE['OtherSub'];
    $RecordStatus = $rowE['RecordStatus'];
    $LastUpdate = $rowE['LastUpdate'];
    $RecordLog = $rowE['RecordLog'];
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
    $num = 1;

    if ($accLevel >= '14000' && $accLevel <= '17999') {
        $approvSql = "SELECT Temp_AppoinmentDetails.ID
            ,Temp_AppoinmentDetails.NIC
            ,[SurnameWithInitials]
            ,[FullName]
            ,[AppCategory]
            ,[AppSubject]
            ,[Medium]
            ,Temp_AppoinmentDetails.SchoolType
            ,[OtherSub]
            ,[RecordStatus]
            ,Temp_AppoinmentDetails.LastUpdate
            ,Temp_AppoinmentDetails.RecordLog
            ,CD_Zone.InstitutionName
        FROM Temp_AppoinmentDetails
        INNER JOIN [TeacherMast] ON Temp_AppoinmentDetails.NIC = TeacherMast.NIC 
        INNER JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID 
        INNER JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
        INNER JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
        INNER JOIN CD_Districts ON CD_Districts.DistCode = CD_Zone.DistrictCode
        WHERE RecordStatus = '0' AND (CD_Districts.ProCode = N'$ProCodeU')";
    } 
    else {
        $approvSql = "SELECT Temp_AppoinmentDetails.ID
            ,Temp_AppoinmentDetails.NIC
            ,[SurnameWithInitials]
            ,[FullName]
            ,[AppCategory]
            ,[AppSubject]
            ,[Medium]
            ,Temp_AppoinmentDetails.SchoolType
            ,[OtherSub]
            ,[RecordStatus]
            ,Temp_AppoinmentDetails.LastUpdate
            ,Temp_AppoinmentDetails.RecordLog
            ,CD_Zone.InstitutionName
        FROM Temp_AppoinmentDetails
        INNER JOIN [TeacherMast] ON Temp_AppoinmentDetails.NIC = TeacherMast.NIC 
        INNER JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID 
        INNER JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
        INNER JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
        WHERE RecordStatus = '0'";
    }
    if ($NICSearch){
        $approvSql .= " and (Temp_AppoinmentDetails.NIC like '%$NICSearch%')";
    }         
    if ($AccessRoleType == "ZN"){
        $approvSql .= " and CD_CensesNo.ZoneCode='$loggedSchool'";
    }
if ($accLevel >= '14000' && $accLevel <= '17999') {

    $approvSql = "SELECT Temp_AppoinmentDetails.ID
    ,Temp_AppoinmentDetails.NIC
    ,[SurnameWithInitials]
    ,[FullName]
    ,[AppCategory]
    ,[AppSubject]
    ,[Medium]
    ,Temp_AppoinmentDetails.SchoolType
    ,[OtherSub]
    ,[RecordStatus]
    ,Temp_AppoinmentDetails.LastUpdate
    ,Temp_AppoinmentDetails.RecordLog
    ,CD_Zone.InstitutionName
FROM Temp_AppoinmentDetails
INNER JOIN [TeacherMast] ON Temp_AppoinmentDetails.NIC = TeacherMast.NIC 
INNER JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID 
INNER JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
INNER JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
WHERE RecordStatus = '0'";
}
    $TotaRows = $db->rowCount($approvSql);
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
<form method="post" action="appointmentSubjects-34.html" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
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
<?php // var_dump($id); ?>

        <?php if ($id == '') { ?>
            <div style="width:738px; float:left;">
            <table width="100%" cellpadding="0" cellspacing="0">

                <tr>
                    <td width="56%"><?php echo $TotaRows ?> Record(s) found. Showing <?php echo $Per_Page ?> records per page.</td>
                    <td width="44%">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#CCCCCC">
                        <table width="100%" cellspacing="1" cellpadding="1">
                            <tr>
                                <td width="5%" height="25" align="center" bgcolor="#999999">#</td>
                                <td width="35%" align="center" bgcolor="#999999">Employee Name</td>
                                <td width="25%" align="center" bgcolor="#999999">NIC</td>
                                <td width="12%" align="center" bgcolor="#999999">Request Date</td>
                                <td width="20%" align="center" bgcolor="#999999">Zone</td>
                                <!-- <td width="7%" align="center" bgcolor="#999999">Medium</td> -->
                                <td width="3%"></td>


                            </tr>
                            <tr>
                                <?php
                                //$i=1; //echo $approvSql;
                                $stmt = $db->runMsSqlQuery($approvSql);
                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                    // var_dump($row);
                                    $RequestID = $row['ID'];
                                    $ReqDate = $row["LastUpdate"]->format('Y-m-d');

                                    echo "<td>" . $num . "</td>";
                                    echo "<td>" . $row["SurnameWithInitials"] . "</td>";
                                    echo "<td>" . $row["NIC"] . "</td>";
                                    echo "<td>" . $ReqDate . "</td>";
                                    echo "<td>" . $row["InstitutionName"] . "</td>";
                                    // echo "<td>" . $row["Medium"] . "</td>";
                                    $num++; 
                                ?>
                                    <td bgcolor="#FFFFFF" align="center" height="20" >
                                        <a href="appointmentSubjects-34--<?php echo $RequestID ?>.html"><img src="images/more_info.png"/></a>
                                    </td> 
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
                    <td colspan="2" align="center" height="30px" style="font-size:16px; font-weight:bold;"><u>Update Request - Appointment Subjects</u></td>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;"><strong>&nbsp;Appointment Subjects</strong></td>
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
                                <td align="left" valign="top"><strong>Surname with Initials</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $SurnameWithInitials ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Appouintment Category</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $AppCategory." - ".$AppCatName ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Appointed Subject</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $AppSubject." - ". $AppSubName; ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Medium</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $Medium; ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Other Subjects</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $OtherSub; ?></td>
                            </tr>
                            
                        </table>
                    </td>
                    
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
                            $UpdateBy = trim($rowE['RecordLog']);
                            $LastUpdate = $rowE['LastUpdate']->format('Y-m-d');
                            // var_dump($UpdateBy);
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
                                <td width="68%">
                                    <input type="hidden" name="RegID" value="<?php echo $id ?>" />
                                    <input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" />
                                </td>
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
        <?php }?>
    </div>

</form>