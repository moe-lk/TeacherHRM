<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
$msg = "";
$success = "";
include('../activityLog.php');
// var_dump($_POST);
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
    $reqTab = "SELECT Temp_TeachingDetails.ID
    ,Temp_TeachingDetails.NIC
    ,[TchSubject1]
    ,[TchSubject2]
    ,[TchSubject3]
    ,[Other1]
    ,[Other2]
    ,[Other3]
    ,[Medium1]
    ,[Medium2]
    ,[Medium3]
    ,[GradeCode1]
    ,[GradeCode2]
    ,[GradeCode3]
    ,[OtherSpecial]
    ,[SchoolType]
    ,Temp_TeachingDetails.RecStatus
    ,Temp_TeachingDetails.RecordLog
    ,Temp_TeachingDetails.LastUpdate
FROM [MOENational].[dbo].[Temp_TeachingDetails] 
INNER JOIN [TeacherMast] ON Temp_TeachingDetails.NIC = TeacherMast.NIC
WHERE Temp_TeachingDetails.ID = '$RegID'";

// printf($reqTab);
    $stmtE = $db->runMsSqlQuery($reqTab);
    $rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC);
    $NIC = trim($rowE['NIC']);
    $SurnameWithInitials = $rowE['SurnameWithInitials'];
    $TchSubject1 = $rowE['TchSubject1'];
    $TchSubject2 = $rowE['TchSubject2'];
    $TchSubject3 = $rowE['TchSubject3'];
    $Other1 = $rowE['Other1'];
    $Other2 = $rowE['Other2'];
    $Other3 = $rowE['Other3'];
    $Medium1 = $rowE['Medium1'];
    $Medium2 = $rowE['Medium2'];
    $Medium3 = $rowE['Medium3'];
    $GradeCode1 = $rowE['GradeCode1'];
    $GradeCode2 = $rowE['GradeCode2'];
    $GradeCode3 = $rowE['GradeCode3'];
    $OtherSpecial = $rowE['OtherSpecial'];
    $SchoolType = $rowE['SchoolType'];

    $TeacherMastID = trim($rowE['TeacherMastID']);
    $PermResiID = trim($rowE['PermResiID']);
    $CurrResID = trim($rowE['CurrResID']);

    if ($IsApproved == 'Y') {

        $RecordLog = "Approved by $NICUser";
        $ApprovedDate = date("Y-m-d H:i:s");

        $SQLTBL = "SELECT * FROM [MOENational].[dbo].[TeachingDetails] WHERE NIC = '$NIC' AND RecStatus = '1'";
        $TotalRows = $db->rowCount($SQLTBL);
        
        // var_dump($TotalRows);
        // echo "YES";
        if (!$TotalRows){
            $queryMainInsert = "INSERT INTO [dbo].[TeachingDetails]
            ([NIC]
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
            ,[RecStatus]
            ,[ApprovedBy]
            ,[ApprovedDate]
            ,[ApproveComment])
            VALUES
            ('$NIC'
            ,'$TchSubject1'
            ,'$TchSubject2'
            ,'$TchSubject3'
            ,'$Other1'
            ,'$Other2'
            ,'$Other3'
            ,'$Medium1'
            ,'$Medium2'
            ,'$Medium3'
            ,'$GradeCode1'
            ,'$GradeCode2'
            ,'$GradeCode3'
            ,'$OtherSpecial'
            ,'$SchoolType'
            ,'1'
            ,'$RecordLog'
            ,'$ApprovedDate'
            ,'$ApproveComment')";

            $db->runMsSqlQueryInsert($queryMainInsert);

            //update data into master table - End
            //update data into master table - Start
            $sqlTempUpdate = "UPDATE [dbo].[Temp_TeachingDetails]
                                SET [RecStatus] = '1'
                                WHERE NIC = '$NIC'";
            
            $db->runMsSqlQueryInsert($sqlTempUpdate);


            audit_trail($NIC, $_SESSION["NIC"], 'approval\teachingSubjects.php', 'Insert', 'TeachingDetails', 'Approve Teaching details.');

            $msg .= "Your Approve was successfully submitted.<br>";
        
        }else{
            $sqlupdate = "UPDATE [dbo].[TeachingDetails]
            SET [TchSubject1] = '$TchSubject1'
               ,[TchSubject2] = '$TchSubject2'
               ,[TchSubject3] = '$TchSubject3'
               ,[Other1] = '$Other1'
               ,[Other2] = '$Other2'
               ,[Other3] = '$Other3'
               ,[Medium1] = '$Medium1'
               ,[Medium2] = '$Medium2'
               ,[Medium3] = '$Medium3'
               ,[GradeCode1] = '$GradeCode1'
               ,[GradeCode2] = '$GradeCode2'
               ,[GradeCode3] = '$GradeCode3'
               ,[OtherSpecial] = '$OtherSpecial'
               ,[RecStatus] = '1'
               ,[ApprovedBy] = '$RecordLog'
               ,[ApprovedDate] = '$ApprovedDate'
               ,[ApproveComment] = '$ApproveComment'
          WHERE NIC = '$NIC'";
          $db->runMsSqlQueryInsert($sqlupdate);

          $sqlTempUpdate = "UPDATE [dbo].[Temp_TeachingDetails]
            SET [RecStatus] = '1'
            WHERE Temp_TeachingDetails.NIC = '$NIC'";
            
            $db->runMsSqlQueryInsert($sqlTempUpdate);


            audit_trail($NIC, $_SESSION["NIC"], 'approval\teachingSubjects.php', 'Update', 'TeachingDetails', 'Approve Teaching details.');

            $msg .= "Your Update was successfully submitted.<br>";
        }
    } 
    else {
        $sqlreject = "DELETE FROM [dbo].[Temp_TeachingDetails] WHERE NIC = '$NIC'";
        $db->runMsSqlQueryInsert($sqlreject);
        
        $msg .= "Your Reject was successffully submitted.<br>";
    }
}

// var_dump($_SESSION['NIC']);
$NICAPP = $_SESSION['NIC']; 
// var_dump($id);
if ($id != '') {
    $reqTab = "SELECT Temp_TeachingDetails.ID
    ,Temp_TeachingDetails.NIC
    ,[TchSubject1]
    ,[TchSubject2]
    ,[TchSubject3]
    ,[Other1]
    ,[Other2]
    ,[Other3]
    ,[Medium1]
    ,[Medium2]
    ,[Medium3]
    ,[GradeCode1]
    ,[GradeCode2]
    ,[GradeCode3]
    ,[OtherSpecial]
    ,[SchoolType]
    ,Temp_TeachingDetails.RecStatus
    ,Temp_TeachingDetails.RecordLog
    ,Temp_TeachingDetails.LastUpdate
FROM [MOENational].[dbo].[Temp_TeachingDetails] 
INNER JOIN [TeacherMast] ON Temp_TeachingDetails.NIC = TeacherMast.NIC
WHERE Temp_TeachingDetails.ID = '$id'";

    $stmtE = $db->runMsSqlQuery($reqTab);
    $rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC);
    // var_dump($rowE['NIC']); 
    $NIC = trim($rowE['NIC']);
    $TeacherMastID = trim($rowE['ID']);
    // $PermResiID = trim($rowE['PermResiID']);
    // $CurrResID = trim($rowE['CurrResID']);
    $UpdateBy = trim($rowE['RecordLog']);
    $LastUpdateE = $rowE['LastUpdate'];
    $TchSubject1E = $rowE["TchSubject1"];
    $TchSubject2E = $rowE["TchSubject2"];
    $TchSubject3E = $rowE["TchSubject3"];
    $Other1 = $rowE['Other1'];
    $Other2 = $rowE['Other2'];
    $Other3 = $rowE['Other3'];
    $Medium1E = $rowE['Medium1'];
    $Medium2E = $rowE['Medium2'];
    $Medium3E = $rowE['Medium3'];
    $GradeCode1E = $rowE["GradeCode1"];
    $GradeCode2E = $rowE["GradeCode2"];
    $GradeCode3E = $rowE["GradeCode3"];
    $OtherSpecial = $rowE['OtherSpecial'];

    // var_dump($OtherSpecial);
    //Edit this as necessary----------------------------------------------------------------------------------------------------------------------------------------------------------
    $sqlteachrMst = "SELECT TOP (1000) [ID]
      ,[NIC]
      ,[TchSubject1]
      ,[TchSubject2]
      ,[TchSubject3]
      ,[Other1]
      ,[Other2]
      ,[Other3]
      ,[Medium1]
      ,[Medium2]
      ,[Medium3]
      ,[GradeCode1]
      ,[GradeCode2]
      ,[GradeCode3]
      ,[OtherSpecial]
      ,[SchoolType]
      ,[RecStatus]
      ,[RecordLog]
      ,[LastUpdate]
  FROM [MOENational].[dbo].[Temp_TeachingDetails] 
INNER JOIN [TeacherMast] ON Temp_TeachingDetails.NIC = TeacherMast.NIC
WHERE (Temp_TeachingDetails.ID = '$TeacherMastID')"; //(UP_TeacherMast.NIC = '850263230V')

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

    $countTotal = "SELECT Temp_TeachingDetails.ID
    ,Temp_TeachingDetails.NIC
    ,[SurnameWithInitials]
    ,[FullName]
    ,[TchSubject1]
    ,[TchSubject2]
    ,[TchSubject3]
    ,[Other1]
    ,[Other2]
    ,[Other3]
    ,[Medium1]
    ,[Medium2]
    ,[Medium3]
    ,[GradeCode1]
    ,[GradeCode2]
    ,[GradeCode3]
    ,[OtherSpecial]
    ,Temp_TeachingDetails.SchoolType
    ,Temp_TeachingDetails.RecStatus
    ,Temp_TeachingDetails.RecordLog
    ,Temp_TeachingDetails.LastUpdate
    ,CD_Zone.InstitutionName
FROM [MOENational].[dbo].[Temp_TeachingDetails] 
INNER JOIN [TeacherMast] ON Temp_TeachingDetails.NIC = TeacherMast.NIC
INNER JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID 
INNER JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
INNER JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
WHERE Temp_TeachingDetails.RecStatus = '0'";

if ($accLevel >= '14000' && $accLevel <= '17999') {
    // Adding data to tables
    $approvSql = "SELECT Temp_TeachingDetails.ID
    ,Temp_TeachingDetails.NIC
    ,[SurnameWithInitials]
    ,[FullName]
    ,[TchSubject1]
    ,[TchSubject2]
    ,[TchSubject3]
    ,[Other1]
    ,[Other2]
    ,[Other3]
    ,[Medium1]
    ,[Medium2]
    ,[Medium3]
    ,[GradeCode1]
    ,[GradeCode2]
    ,[GradeCode3]
    ,[OtherSpecial]
    ,Temp_TeachingDetails.SchoolType
    ,Temp_TeachingDetails.RecStatus
    ,Temp_TeachingDetails.RecordLog
    ,Temp_TeachingDetails.LastUpdate
    ,CD_Zone.InstitutionName
FROM [MOENational].[dbo].[Temp_TeachingDetails] 
INNER JOIN [TeacherMast] ON Temp_TeachingDetails.NIC = TeacherMast.NIC
INNER JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID 
INNER JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
INNER JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
INNER JOIN CD_Districts ON CD_Districts.DistCode = CD_Zone.DistrictCode
WHERE Temp_TeachingDetails.RecStatus = '0' AND (CD_Districts.ProCode = N'$ProCodeU')";
} 
else {
    $approvSql = "SELECT Temp_TeachingDetails.ID
    ,Temp_TeachingDetails.NIC
    ,[SurnameWithInitials]
    ,[FullName]
    ,[TchSubject1]
    ,[TchSubject2]
    ,[TchSubject3]
    ,[Other1]
    ,[Other2]
    ,[Other3]
    ,[Medium1]
    ,[Medium2]
    ,[Medium3]
    ,[GradeCode1]
    ,[GradeCode2]
    ,[GradeCode3]
    ,[OtherSpecial]
    ,Temp_TeachingDetails.SchoolType
    ,Temp_TeachingDetails.RecStatus
    ,Temp_TeachingDetails.RecordLog
    ,Temp_TeachingDetails.LastUpdate
    ,CD_Zone.InstitutionName
FROM [MOENational].[dbo].[Temp_TeachingDetails] 
INNER JOIN [TeacherMast] ON Temp_TeachingDetails.NIC = TeacherMast.NIC
INNER JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID 
INNER JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
INNER JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
WHERE Temp_TeachingDetails.RecStatus = '0'";
}
if ($NICSearch){
    $approvSql .= " and (Temp_AppoinmentDetails.NIC like '%$NICSearch%')";
}         
if ($AccessRoleType == "ZN"){
    $approvSql .= " and CD_CensesNo.ZoneCode='$loggedSchool'";
}
if ($accLevel >= '14000' && $accLevel <= '17999') {
    $approvSql = "SELECT Temp_TeachingDetails.ID
    ,Temp_TeachingDetails.NIC
    ,[SurnameWithInitials]
    ,[FullName]
    ,[TchSubject1]
    ,[TchSubject2]
    ,[TchSubject3]
    ,[Other1]
    ,[Other2]
    ,[Other3]
    ,[Medium1]
    ,[Medium2]
    ,[Medium3]
    ,[GradeCode1]
    ,[GradeCode2]
    ,[GradeCode3]
    ,[OtherSpecial]
    ,Temp_TeachingDetails.SchoolType
    ,Temp_TeachingDetails.RecStatus
    ,Temp_TeachingDetails.RecordLog
    ,Temp_TeachingDetails.LastUpdate
    ,CD_Zone.InstitutionName
FROM [MOENational].[dbo].[Temp_TeachingDetails] 
INNER JOIN [TeacherMast] ON Temp_TeachingDetails.NIC = TeacherMast.NIC
INNER JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID 
INNER JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
INNER JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
WHERE Temp_TeachingDetails.RecStatus = '0'";
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
<?php // var_dump($id); ?>
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
<form method="post" action="teachingSubjects-23.html" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
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
    <?php
        $ApprovedBy = $NICAPP;
        $ApprovedDate = date("Y-m-d h:i:sa");
        $RecStatus = '1';
    ?>
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
                                <td width="35%" align="center" bgcolor="#999999">Employee Name</td>
                                <td width="25%" align="center" bgcolor="#999999">NIC</td>
                                <td width="12%" align="center" bgcolor="#999999">Request Date</td>
                                <td width="20%" align="center" bgcolor="#999999">Zone</td>
                                <td width="5%"></td>


                            </tr>
                            <?php
                                $sqlmed = "SELECT * FROM CD_Medium ";
                                $sqlGrade = "SELECT * FROM CD_SecGrades";
                            ?>
                            
                            <form method = "POST" action="TchApprove.php">
                                <?php
                                //$i=1; //echo $approvSql;
                                $stmt = $db->runMsSqlQuery($approvSql);
                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                    $RequestID = $row['ID'];
                                    $NICRow = $row['NIC'];
                                    $TchSubject1 = $row["TchSubject1"];
                                    $TchSubject2 = $row["TchSubject2"];
                                    $TchSubject3 = $row["TchSubject3"];
                                    $Other1 = $row['Other1'];
                                    $Other2 = $row['Other2'];
                                    $Other3 = $row['Other3'];
                                    $Medium1 = $row['Medium1'];
                                    $Medium2 = $row['Medium2'];
                                    $Medium3 = $row['Medium3'];
                                    $GradeCode1 = $row["GradeCode1"];
                                    $GradeCode2 = $row["GradeCode2"];
                                    $GradeCode3 = $row["GradeCode3"];

                                    $ReqDate = $row["LastUpdate"]->format('Y-m-d');
                                    echo "<tr>";
                                    echo "<td>" . $num . "</td>";
                                    echo "<td>" . $row["SurnameWithInitials"] . "</td>";
                                    echo "<td>" . $row["NIC"] . "</td>";
                                    echo "<td>" . $ReqDate . "</td>";
                                    echo "<td>" . $row["InstitutionName"] . "</td>";
                                    $num++;
                                ?>
                                    <!-- <td bgcolor="#FFFFFF" align="center"> -->
                                        <!-- <input type="submit" name="tchApprove" id="tchApprove" value="Approve" > -->
                                        <td bgcolor="#FFFFFF" align="center" height="20" >
                                            <a href="teachingSubjects-33--<?php echo $RequestID ?>.html"><img src="images/more_info.png"/></a>
                                        </td>
                                        <!-- <button onclick="appFunction()">Approve</button> -->
                                        <!-- teachingSubjects-23.html -->
                                    <!-- </td>  -->
                                    <?php
                                        // if(isset($_POST['submit'])){
                                            
                                        //     echo "<script>alert('Submit Works')</script>";

                                        //     $NICRow = $_POST['TempTeachingDetailsTemp.NIC'];
                                        //     $TchSubject1 = $_POST["TchSubject1"];
                                        //     $TchSubject2 = $_POST["TchSubject2"];
                                        //     $TchSubject3 = $_POST["TchSubject3"];
                                        //     $Medium1 = $_POST['Medium1'];
                                        //     $Medium2 = $_POST['Medium2'];
                                        //     $Medium3 = $_POST['Medium3'];
                                        //     $GradeCode1 = $_POST["GradeCode1"];
                                        //     $GradeCode2 = $_POST["GradeCode2"];
                                        //     $GradeCode3 = $_POST["GradeCode3"];
                                        //     $NICAPP = $_POST['NICAPP'];
                                        // }
                            ?>
                            </form>
                            

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

                </table> 
                <?php 
                } else { 
                    
                    // if($TchSubject1E != ''){
                        $sql1 = "SELECT [SubjectName] FROM [MOENational].[dbo].[CD_TeachSubjects] WHERE ID = '$TchSubject1E'";
                        $stmt1 = $db->runMsSqlQuery($sql1);
                        $row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC);
                    // }
                    $sql1 = "SELECT [SubjectName] FROM [MOENational].[dbo].[CD_TeachSubjects] WHERE ID = '$TchSubject1E'";
                    $stmt1 = $db->runMsSqlQuery($sql1);
                    $row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC);

                    $sql2 = "SELECT [SubjectName] FROM [MOENational].[dbo].[CD_TeachSubjects] WHERE ID = '$TchSubject2E'";
                    $stmt2 = $db->runMsSqlQuery($sql2);
                    $row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
                    
                    $sql3 = "SELECT [SubjectName] FROM [MOENational].[dbo].[CD_TeachSubjects] WHERE ID = '$TchSubject3E'";
                    $stmt3 = $db->runMsSqlQuery($sql3);
                    $row3 = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC);

                    $sql4 = "SELECT [Medium] FROM [MOENational].[dbo].[CD_Medium] WHERE Code = '$Medium1E' AND Code != ''";
                    $stmt4 = $db->runMsSqlQuery($sql4);
                    $row4 = sqlsrv_fetch_array($stmt4, SQLSRV_FETCH_ASSOC);
                    
                    $sql5 = "SELECT [Medium] FROM [MOENational].[dbo].[CD_Medium] WHERE Code = '$Medium2E' AND Code != ''";
                    $stmt5 = $db->runMsSqlQuery($sql5);
                    $row5 = sqlsrv_fetch_array($stmt5, SQLSRV_FETCH_ASSOC);
                    
                    $sql6 = "SELECT [Medium] FROM [MOENational].[dbo].[CD_Medium] WHERE Code = '$Medium3E' AND Code != ''";
                    $stmt6 = $db->runMsSqlQuery($sql6);
                    $row6 = sqlsrv_fetch_array($stmt6, SQLSRV_FETCH_ASSOC);
                    
                    $sql7 = "SELECT CategoryName FROM CD_TeachSubCategory WHERE ID = '$GradeCode1E'";
                    $stmt7 = $db->runMsSqlQuery($sql7);
                    $row7 = sqlsrv_fetch_array($stmt7, SQLSRV_FETCH_ASSOC);
                    
                    $sql8 = "SELECT CategoryName FROM CD_TeachSubCategory WHERE ID = '$GradeCode2E'";
                    $stmt8 = $db->runMsSqlQuery($sql8);
                    $row8 = sqlsrv_fetch_array($stmt8, SQLSRV_FETCH_ASSOC);
                    
                    $sql9 = "SELECT CategoryName FROM CD_TeachSubCategory WHERE ID = '$GradeCode3E'";
                    $stmt9 = $db->runMsSqlQuery($sql9);
                    $row9 = sqlsrv_fetch_array($stmt9, SQLSRV_FETCH_ASSOC);

                    $sql10 = "SELECT SubjectName FROM CD_TeachSubjects WHERE ID = '$OtherSpecial'";
                    $stmt10 = $db->runMsSqlQuery($sql10);
                    $row10 = sqlsrv_fetch_array($stmt10, SQLSRV_FETCH_ASSOC);
                ?>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td colspan="2" align="center" height="30px" style="font-size:16px; font-weight:bold;"><u>Update Request - Teaching Subjects</u></td>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;"><strong>&nbsp;Teaching Subjects</strong></td>
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td align="right" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td width="50%" valign="top">
                        <table width="100%" cellspacing="2" cellpadding="2">
                            <? //var_dump($NICRow); ?>
                            <tr>
                                <td width="30%" align="left" valign="top"><strong>NIC</strong></td>
                                <td width="3%" align="left" valign="top"><strong>:</strong></td>
                                <td width="67%" align="left" valign="top"><?php echo $NIC ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top"><strong>Surname with Initials</strong></td>
                                <td align="left" valign="top"><strong>:</strong></td>
                                <td align="left" valign="top"><?php echo $rowE["SurnameWithInitials"]; ?></td>
                            </tr>

                        </table>
                    </td>
                </tr>
                <!-- Most subject Start -->
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;"><strong>Teaching Subject for most Hours</strong></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">
                        <table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="15%" align="left" valign="top"><strong>Subject</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top">
                                    <?php echo $rowE['TchSubject1']." - ". $row1['SubjectName']; ?>
                                </td>
                            </tr>
                            <tr>
                                <td width="15%" align="left" valign="top"><strong>Medium</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $rowE['Medium1']." - ". $row4['Medium']; ?></td>
                            </tr>
                            <tr>
                                <td width="15%" align="left" valign="top"><strong>Grade Span</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $rowE['GradeCode1']." - ". $row7['CategoryName']; ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- Second Most subject start -->
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;"><strong>Teaching Subject for Second most Hours</strong></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">
                        <table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="15%" align="left" valign="top"><strong>Subject</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $rowE['TchSubject2']." - ". $row2['SubjectName']; ?></td>
                            </tr>
                            <tr>
                                <td width="15%" align="left" valign="top"><strong>Medium</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $rowE['Medium2']." - ". $row5['Medium'];; ?></td>
                            </tr>
                            <tr>
                                <td width="15%" align="left" valign="top"><strong>Grade Span</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $rowE['GradeCode2']." - ". $row8['CategoryName']; ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- capable subject start -->
                <tr>
                    <td valign="top">&nbsp;</td>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;"><strong>Teaching Subject for most Hours</strong></td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">
                        <table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="15%" align="left" valign="top"><strong>Subject</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $rowE['TchSubject3']." - ". $row3['SubjectName']; ?></td>
                            </tr>
                            <tr>
                                <td width="15%" align="left" valign="top"><strong>Medium</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $rowE['Medium3']." - ". $row6['Medium'];; ?></td>
                            </tr>
                            <tr>
                                <td width="15%" align="left" valign="top"><strong>Grade Span</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $rowE['GradeCode3']." - ". $row9['CategoryName']; ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" valign="top">
                        <table width="100%" cellspacing="2" cellpadding="2">
                            <tr>
                                <td width="15%" align="left" valign="top"><strong>Other Special Duties</strong></td>
                                <td width="1%" align="left" valign="top"><strong>:</strong></td>
                                <td width="34%" align="left" valign="top"><?php echo $rowE['OtherSpecial']." - ". $row10['SubjectName']; ?></td>
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
<script>
    // function appFunction(){
    //     var xhttp = new XMLHttpRequest();

    // }
</script>
<?php
// } 
?>