<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="TchSubjects.js"></script>
<?php
$msg = "";
$nicUpdate = $_SESSION['NIC'];
$NICUser = $id;

$isAvailablePerAdd = $isAvailableCurAdd = "";
$success = "";
if (isset($_POST["FrmSubmit"])) {
    include('../activityLog.php');
    $SubjectType = $_REQUEST['SubjectType'];
    $SubjectCode = $_REQUEST['SubjectCode'];
    $MediumCode = $_REQUEST['MediumCode'];
    $SecGradeCode = $_REQUEST['SecGradeCode'];
    $LastUpdate = date('Y-m-d H:i:s');

    $msg = "";
    $sqlServiceRef = " SELECT        TeacherMast.CurServiceRef, CD_CensesNo.ZoneCode
FROM            StaffServiceHistory INNER JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode INNER JOIN
                         TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
WHERE        (TeacherMast.NIC = '$NICUser')";
    $stmtCAllready = $db->runMsSqlQuery($sqlServiceRef);
    $rowAllready = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC);
    $CurServiceRef = trim($rowAllready['CurServiceRef']);
    $ZoneCode = trim($rowAllready['ZoneCode']);

    $TeachingIDA = "";
    if ($SubjectType == "") {
        $msg .= "Please select subject type.<br>";
    }
    if ($SubjectCode == "") {
        $msg .= "Please select subject code.<br>";
    }
    if ($MediumCode == "") {
        $msg .= "Please select medium.<br>";
    }

    // if ($msg == '') {
    //     $familiChildStatus = "Add";
    //     if ($TeachingIDA == '') { //$familiChildStatus=='Add'){
    //         $queryMainSave = "INSERT INTO UP_TeacherSubject
    // 		   (NIC,SubjectType,SubjectCode,MediumCode,SecGradeCode,Grade,LastUpdate,UpdatedBy,RecordLog)
    // 	 VALUES
    // 		   ('$NICUser','$SubjectType','$SubjectCode','$MediumCode','$SecGradeCode','$Grade','$LastUpdate','$nicUpdate','First change')";
    //         $db->runMsSqlQuery($queryMainSave);

    //         $reqTabMobAc = "SELECT ID FROM UP_TeacherSubject where NIC='$NICUser' and SubjectType='$SubjectType' and SubjectCode='$SubjectCode'  ORDER BY ID DESC";
    //         $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
    //         $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
    //         $TeachingID = trim($rowMobAc['ID']);
    //     }
    // }

    if ($msg == '') {
        // $queryRegis = "INSERT INTO TG_EmployeeUpdateTeaching (NIC,TeachingID,dDateTime,ZoneCode,IsApproved,ApproveDate,ApprovedBy,UpdateBy)
		// 	 VALUES				   
		// ('$NICUser','$TeachingID','$LastUpdate','$ZoneCode','N','','','$nicUpdate')";
        // $db->runMsSqlQuery($queryRegis);

        audit_trail($NICUser, $_SESSION["NIC"], 'teacherprofile\teaching.php', 'Insert', 'UP_TeacherSubject,TG_EmployeeUpdateTeaching', 'Insert user teaching info.');

        $success = "Your update request submitted successfully. Data will be displaying after the approvals.";
    }
}
$pageid = $_GET["pageid"];
$menu = $_GET['menu'];
$tpe = $_GET['tpe'];
$id = $_GET['id'];

$SQL1 = "SELECT TOP(1) * FROM TeacherMast
join StaffServiceHistory on TeacherMast.CurServiceRef = StaffServiceHistory.ID
join CD_CensesNo on StaffServiceHistory.InstCode = CD_CensesNo.CenCode 
WHERE StaffServiceHistory.NIC = '$id' ORDER BY StaffServiceHistory.AppDate DESC";

$stmt1 = $db->runMsSqlQuery($SQL1);
while ($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
    $SchType = Trim($row1['SchoolType']);
    // var_dump($SchType);
}

$TbLD=1;

$SQLTBL = "SELECT TeachingDetails.ID
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
,trim([GradeCode1]) AS GradeCode1
,trim([GradeCode2]) AS GradeCode2
,[GradeCode3]
,[OtherSpecial]
,[SchoolType]
,[RecStatus]
,[ApprovedBy]
,[ApprovedDate]
,[ApproveComment]
,TC1.SubjectName AS Subj1
,TC2.SubjectName AS Subj2
,TC3.SubjectName AS Subj3
,TM1.Medium AS Med1
,TM2.Medium AS Med2
,TM3.Medium AS Med3
,trim(TSC1.CategoryName) AS Tcat1
,trim(TSC2.CategoryName) AS Tcat2
,TSC3.CategoryName AS Tcat3
,TC4.SubjectName AS Other
FROM TeachingDetails 
LEFT JOIN CD_TeachSubjects AS TC1 ON TeachingDetails.TchSubject1 = TC1.ID
LEFT JOIN CD_TeachSubjects AS TC2 ON TeachingDetails.TchSubject2 = TC2.ID
LEFT JOIN CD_TeachSubjects AS TC3 ON TeachingDetails.TchSubject3 = TC3.ID
LEFT JOIN CD_Medium AS TM1 ON TeachingDetails.Medium1 = TM1.Code
LEFT JOIN CD_Medium AS TM2 ON TeachingDetails.Medium2 = TM2.Code
LEFT JOIN CD_Medium AS TM3 ON TeachingDetails.Medium3 = TM3.Code
LEFT JOIN CD_TeachSubCategory AS TSC1 ON TeachingDetails.GradeCode1 = TSC1.ID
LEFT JOIN CD_TeachSubCategory AS TSC2 ON TeachingDetails.GradeCode2 = TSC2.ID
LEFT JOIN CD_TeachSubCategory AS TSC3 ON TeachingDetails.GradeCode3 = TSC3.ID
LEFT JOIN CD_TeachSubjects AS TC4 ON TeachingDetails.OtherSpecial = TC4.ID
WHERE NIC = '$id' AND RecStatus = '1'";
$stmtTBL = $db->runMsSqlQuery($SQLTBL);
while($rowTBL = sqlsrv_fetch_array($stmtTBL, SQLSRV_FETCH_ASSOC)){
    $Subj1 = $rowTBL['Subj1'];
    $Subj2 = $rowTBL['Subj2'];
    $Subj3 = $rowTBL['Subj3'];
    if($rowTBL['Med1'] == 'Not Specified'){
        $Med1 = "";
    }else{
        $Med1 = $rowTBL['Med1'];
    }
    if($rowTBL['Med2'] == 'Not Specified'){
        $Med2 = "";
    }else{
        $Med2 = $rowTBL['Med2'];
    }
    if($rowTBL['Med3'] == 'Not Specified'){
        $Med3 = "";
    }else{
        $Med3 = $rowTBL['Med3'];
    }
    $Tcat1 = $rowTBL['Tcat1'];
    $Tcat2 = $rowTBL['Tcat2'];
    $Tcat3 = $rowTBL['Tcat3'];
    $TchSubject1 = $rowTBL['TchSubject1'];
    $Medium1 = $rowTBL['Medium1'];
    $GradeCode1 = trim($rowTBL['GradeCode1']);
    $TchSubject2 = $rowTBL['TchSubject2'];
    $Medium2 = $rowTBL['Medium2'];
    $GradeCode2 = $rowTBL['GradeCode2'];
    $TchSubject3 = $rowTBL['TchSubject3'];
    $Medium3 = $rowTBL['Medium3'];
    $GradeCode3 = $rowTBL['GradeCode3'];
    $OtherSpecial = $rowTBL['OtherSpecial'];
    $Other = $rowTBL['Other'];
}
?>

<style>
    input[type=text],
    select {
        width: 100%;
        padding: 6px 10px;
        margin: 4px 0;
        display: inline-block;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type=submit] {
        width: 50%;
        background-color: #92495C;
        color: white;
        padding: 14px 20px;
        margin: 8px 0;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    #Tblrecord {
        border-collapse: collapse;       
        border: 1px solid black;
        padding: 5px;
    }

    /* #Tblrecord, td, th {
        border: 1px solid black;
        padding: 5px;
    } */
    #headtbl{
        background-color: #CCCCCC; 
        /* color: white; */
        font-weight: 700;
        text-align: center;
        padding-left: 15px;
        padding-right: 15px;
        padding: 10px;
        width:100%;
    }
</style>
<div class="main_content_inner_block">
    <div class="mcib_middle1">
    <table name="Tblrecord" id="Tblrecord" border = "1px" style="width:100%; display: block;">
                <tr id="headtbl">
                    <td colspan="3" style="padding: 5px; padding-left: 10px; padding-right: 10px;">
                        Hightest number of teaching periods
                    </td>
                    <td colspan="3" style="padding: 5px; padding-left: 10px; padding-right: 10px;">
                        Second Highest number of teaching periods
                    </td>
                    <td colspan="3" style="padding: 5px; padding-left: 10px; padding-right: 10px;">
                        Other capable subjects of teaching
                    </td>
                    <td style="padding: 5px; padding-left: 10px; padding-right: 10px;">
                        Other Special Duties
                    </td>
                    <td style="padding: 5px; padding-left: 10px; padding-right: 10px;">
                        Action
                    </td>
                </tr>
                <tr id="headtbl">
                    <td style="padding: 5px;">Grade Span</td>
                    <td style="padding: 5px;">Subject</td>
                    <td style="padding: 5px;">Medium</td>
                    <td style="padding: 5px;">Grade Span</td>
                    <td style="padding: 5px;">Subject</td>
                    <td style="padding: 5px;">Medium</td>
                    <td style="padding: 5px;">Grade Span</td>
                    <td style="padding: 5px;">Subject</td>
                    <td style="padding: 5px;">Medium</td>
                    
                    <td style="padding: 5px;">&nbsp;</td>
                    <td style="padding: 5px;">&nbsp;</td>
                </tr>
                <tr>
                <?php 
                    $TotalRows = $db->rowCount($SQLTBL);
                    // var_dump($TotaRows);
                    if (!$TotalRows){
                        // var_dump($TotaRows)
                        $TbLD = 0;
                    }
                    // sqlsrv_fetch_array($stmtTBL, SQLSRV_FETCH_ASSOC);
                    // var_dump($rowTBL);
                    // if(is_null($rowTBL)){
                    //     $TbLD = 0;
                    // }
                    
                        // else{
                            echo "<td style='padding: 5px;'>".$GradeCode1."-".$Tcat1."</td>";
                            echo "<td style='padding: 5px;'>".$TchSubject1."-".$Subj1."</td>";
                            echo "<td style='padding: 5px;'>".$Medium1."-".$Med1."</td>";
                            echo "<td style='padding: 5px;'>".$GradeCode2."-".$Tcat2."</td>";
                            echo "<td style='padding: 5px;'>".$TchSubject2."-".$Subj2."</td>";
                            echo "<td style='padding: 5px;'>".$Medium2."-".$Med2."</td>";
                            echo "<td style='padding: 5px;'>".$GradeCode3."-".$Tcat3."</td>";
                            echo "<td style='padding: 5px;'>".$TchSubject3."-".$Subj3."</td>";
                            echo "<td style='padding: 5px;'>".$Medium3."-".$Med3."</td>";
                            echo "<td style='padding: 5px;'>".$OtherSpecial."-".$Other."</td>";
                            echo "<td style='padding: 5px;' style='text-align:center'><input type='button' value='Edit' onclick='showForm()'></td>";
                        // } 
                    // var_dump($GradeCode3);    // echo "<td>&nbsp</td>";

                        
                ?>

                </tr>
            </table>
            <form method="POST" name="frmTchDetails" id="frmTchDetails" action="TchSubmit.php" style="display:none; padding-top: 50px;">
            <table>
                <tr>
                    <td colspan="2" style="text-align: center; font-weight: bold;" class="box">
                        <h3>Hightest number of teaching periods</h3>
                    </td>
                </tr>

                
                <tr>
                    <td class="box">Grade Span</td>
                    <td class="box">
                        <select id="GradTch1" name="GradTch1">
                            <?php 
                            if($TbLD == 0 || $GradeCode1 == ''){
                                echo "<option>Select</option>";
                            }
                            
                            $sql = "SELECT * FROM CD_TeachSubCategory WHERE ID IS NOT NULL AND ID != '9'";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchGrade = $row['CategoryName'];
                                $TchGradeCode = $row['ID'];
                                $seltebr = "";
                                if($TchGradeCode == $GradeCode1){
                                    $seltebr = "selected";
                                }
                                echo "<option value=" . $TchGradeCode . " $seltebr>" . $TchGrade . "</option>";
                            }
                            ?>
                        </select>
                        <script>
                            var category = document.getElementById("GradTch1");
                        </script>
                    </td>
                </tr>
                <tr>
                    <td class="box">Subject</td>
                    <td class="box">
                        <select id="SubTch1" name="SubTch1">
                            <?php
                            if($TbLD == 0 || $TchSubject1 == ''){
                                echo "<option>Select</option>";
                            } 
                            $sql = "SELECT * FROM CD_TeachSubjects WHERE ID IS NOT NULL AND Code != '9'";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchSub = $row['SubjectName'];
                                $TchSubCode = $row['ID'];
                                $seltebr = "";
                                if($TchSubCode == $TchSubject1){
                                    $seltebr = "selected";
                                }
                                echo "<option value=" . $TchSubCode . " $seltebr>" . $TchSub . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <!-- <tr>
                    <td><button type="button" onclick="show_otherdiv1()">Other</button></td>
                </tr> -->
                <tr>
                    <td class="box">
                        <div style="display :none" id="otherdiv1">If Other Please Specify: </div>
                    </td>
                    <td class="box">
                        <input type="text" name="otherTch1" id="otherTch1" style="display:none">
                    </td>
                </tr>
                <tr>
                    <td class="box">Medium</td>
                    <td class="box">
                        <select id="MedTch1" name="MedTch1">
                            <!-- <option>Select</option> -->
                            <?php // for meium combo box
                            if($TbLD == 0 || $Medium1 == ''){
                                echo "<option>Select</option>";
                            }
                            $sql = "SELECT * FROM CD_Medium WHERE Code != ''";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchMedium = $row['Medium'];
                                $TchMediumCode = $row['Code'];
                                $seltebr = "";
                                if($TchMediumCode == $Medium1){
                                    $seltebr = "selected";
                                }
                                echo "<option value=" . $TchMediumCode . " $seltebr>" . $TchMedium . "</option>";
                            }
                            ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr>
                    </td>
                </tr>
                
                <td colspan="2" style="text-align: center; font-weight: bold;" ;>
                    <h3>Second Highest number of teaching periods</h3>
                </td>
                
                <tr>

                    <td class="box">Grade Span</td>
                    <td class="box">
                        <select id="GradTch2" name="GradTch2">
                            <?php
                            if($TbLD == 0 || $GradeCode2 == ''){
                                echo "<option>Select</option>";
                            } // for meium combo box
                            $sql = "SELECT * FROM CD_TeachSubCategory WHERE ID IS NOT NULL AND ID != '9'";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchGrade = $row['CategoryName'];
                                $TchGradeCode = $row['ID'];
                                $seltebr = "";
                                if($TchGradeCode == $GradeCode2){
                                    $seltebr = "selected";
                                }
                                echo "<option value=" . $TchGradeCode . "$seltebr>" . $TchGrade . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="box">Subject</td>
                    <td class="box">
                        <select id="SubTch2" name="SubTch2">
                            <!-- <option>Select</option> -->
                            <?php
                            if($TbLD == 0 || $TchSubject2 == ''){
                                echo "<option>Select</option>";
                            }
                            $sql = "SELECT * FROM CD_TeachSubjects WHERE ID IS NOT NULL AND Code != '9'";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchSub = $row['SubjectName'];
                                $TchSubCode = $row['ID'];
                                $seltebr = "";
                                if($TchSubCode == $TchSubject2){
                                    $seltebr = "selected";
                                }
                                echo "<option value=" . $TchSubCode . " $seltebr>" . $TchSub . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <!-- <tr>
                    <td><button type="button" onclick="show_otherdiv2()">Other</button></td>
                </tr> -->
                <tr>
                    <td class="box">
                        <div style="display :none" id="otherdiv2">If Other Please Specify: </div>
                    </td>
                    <td class="box">
                        <input type="text" name="otherTch2" id="otherTch2" style="display :none">
                    </td>
                </tr>
                <tr>
                    <td class="box">Medium</td>
                    <td class="box">
                        <select id="MedTch2" name="MedTch2">
                            <!-- <option>Select</option> -->
                            <?php // for meium combo box
                            if($TbLD == 0 || $Medium2 == ''){
                                echo "<option>Select</option>";
                            }
                            $sql = "SELECT * FROM CD_Medium WHERE Code != ''";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchMedium = $row['Medium'];
                                $TchMediumCode = $row['Code'];
                                $seltebr = "";
                                if($TchMediumCode == $Medium2){
                                    $seltebr = "selected";
                                }
                                echo "<option value=" . $TchMediumCode . "$seltebr>" . $TchMedium . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center; font-weight: bold;" ;>
                        <h3>Other capable subjects of teaching</h3>
                    </td>
                </tr>
                
                <tr>
                    <td class="box">Grade Span</td>
                    <td class="box">
                        <select id="GradTch3" name="GradTch3">

                            <!-- <option>Select</option> -->
                            <?php // for meium combo box
                            if($TbLD == 0 || $GradeCode3 == ''){
                                echo "<option>Select</option>";
                            } 
                            $sql = "SELECT * FROM CD_TeachSubCategory WHERE ID IS NOT NULL AND ID != '9'";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchGrade = $row['CategoryName'];
                                $TchGradeCode = $row['ID'];
                                $seltebr = "";
                                if($TchGradeCode == $GradeCode3){
                                    $seltebr = "selected";
                                }
                                echo "<option value=" . $TchGradeCode . "$seltebr>" . $TchGrade . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="box">Subject </td>
                    <td class="box">
                        <select id="SubTch3" name="SubTch3">
                            <!-- <option>Select</option> -->
                            <?php
                            if($TbLD == 0 || $TchSubject3 == ''){
                                echo "<option>Select</option>";
                            }
                            $sql = "SELECT * FROM CD_TeachSubjects WHERE ID IS NOT NULL AND Code != '9'";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchSub = $row['SubjectName'];
                                $TchSubCode = $row['ID'];
                                $seltebr = "";
                                if($TchSubCode == $TchSubject3){
                                    $seltebr = "selected";
                                }
                                echo "<option value=" . $TchSubCode . " $seltebr>" . $TchSub . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <!-- <tr>
                    <td><button type="button" onclick="show_otherdiv3()">Other</button></td>
                </tr> -->
                <tr>
                    <td class="box">
                        <div style="display :none" id="otherdiv3">If Other Please Specify: </div>
                    </td>
                    <td class="box">
                        <input type="text" name="otherTch3" id="otherTch3" style="display :none">
                    </td>
                </tr>
                <tr>
                    <td class="box">Medium</td>
                    <td class="box">
                        <select id="MedTch3" name="MedTch3">
                            <!-- <option>Select</option> -->
                            <?php // for meium combo box
                            if($TbLD == 0 || $Medium3 == ''){
                                echo "<option>Select</option>";
                            }
                            $sql = "SELECT * FROM CD_Medium WHERE Code != ''";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchMedium = $row['Medium'];
                                $TchMediumCode = $row['Code'];
                                $seltebr = "";
                                if($TchMediumCode == $Medium3){
                                    $seltebr = "selected";
                                }
                                echo "<option value=" . $TchMediumCode . " $seltebr>" . $TchMedium . "</option>";
                            }
                            ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td  class="box" style="padding-right:100px">Other Special Duties</td>
                    <td class="box">
                        <select id="otherspecial" name="otherspecial">
                            <!-- <option>Select</option> -->
                            
                            <?php
                            if($TbLD == 0 || $OtherSpecial == ''){
                                echo "<option>Select</option>";
                            }
                            $sql = "SELECT * FROM CD_TeachSubjects WHERE Code = '9'";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchGrade = $row['SubjectName'];
                                $TchGradeCode = $row['ID'];
                                $seltebr = "";
                                if($TchGradeCode  == $OtherSpecial){
                                    $seltebr = "selected";
                                }
                                echo "<option value=" . $TchGradeCode . " $seltebr>" . $TchGrade . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr> 
                    <td colspan="2">
                        <div>
                            <input type="submit" name="TchFrmSubmit" id="TchFrmSubmit">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="box"><input type="hidden" name="id" id="id" value="<?php echo $id ?>"></td>
                <tr>
            </table>
            <?php
            // var_dump($POST);
            // if (isset($_POST['Submit'])) {
            $MedTch1 = $_POST['MedTch1'];
            $GradTch1 = $_POST["GradTch1"];
            $SubTch1 = $_POST["SubTch1"];
            $otherTch1 = $_POST["otherTch1"];
            $MedTch2 = $_POST["MedTch2"];
            $GradTch2 = $_POST["GradTch2"];
            $SubTch2 = $_POST["SubTch2"];
            $otherTch2 = $_POST["otherTch2"];
            $MedTch3 = $_POST["MedTch3"];
            $GradTch3 = $_POST["GradTch3"];
            $SubTch3 = $_POST["SubTch3"];
            $otherTch3 = $_POST["otherTch3"];
            $otherspecial = $_POST["otherspecial"];

            // }
            // var_dump($_SESSION['id']);

            ?>
        </form>
    </div>
</div>

<script>
    var Tbldata = <?php echo $TbLD; ?>;
    // console.log(Tbldata);
    var tbl = document.getElementById("frmTchDetails")
    var itbl = document.getElementById("Tblrecord");
    // console.log(itbl.style.display)
    if (itbl.style.display === "block" && Tbldata==0) {
        itbl.style.display = "none";
        tbl.style.display = "block";
    }
    // var i = document.getElementById("Tblrecord")
    // // console.log(Tbldata);
    // if(Tbldata = 0){
    //     i.style.display = "block";
    // }
    
    // var thisone  = <?php // echo $rowTBL['Medium1'] ?>
    // console.log(thisone)
    // var MedTch1 = document.getElementById("MedTch1");
    // var MedTch2 = document.getElementById("MedTch2");
    // var MedTch3 = document.getElementById("MedTch3");
    // var GradTch1 = document.getElementById("GradTch1");
    // var GradTch2 = document.getElementById("GradTch2");
    // var GradTch3 = document.getElementById("GradTch3");
    // var SubTch1 = document.getElementById("SubTch1");
    // var SubTch2 = document.getElementById("SubTch2");
    // var SubTch3 = document.getElementById("SubTch3");
    // var otherspecial = document.getElementById("otherspecial");

    // var otherspecial = <?php  //echo $OtherSpecial; ?>   
    // console.log(otherspecial);

    // selectElement('leaveCode', '11')

function selectElement(id, valueToSelect) {    
    let element = document.getElementById(id);
    element.value = valueToSelect;
}
    function showForm(){
        if (tbl.style.display === "none" ) {
            tbl.style.display = "block";
        }
    }
    // var schType = "<?php echo $SchType; ?>";
    // var i;
    // if(schType == 6){
    //     i = '6';
    // }else{
    //     i = '1';
    // }
    
    var x = document.getElementById("otherdiv1");
    var y = document.getElementById("otherTch1");
    var a = document.getElementById("otherdiv2");
    var b = document.getElementById("otherTch2");
    var c = document.getElementById("otherdiv3");
    var d = document.getElementById("otherTch3");

    
    $(document).on("change", "#SubTch1", function () {
        var SubApp_id = $(this).val()
        // console.log(SubApp_id)
        if (SubApp_id == "248" || SubApp_id == "456") {
        x.style.display = "block";
        y.style.display = "block";
        } else {
        x.style.display = "none";
        y.style.display = "none";
        }
    });

    $(document).on("change", "#SubTch2", function () {
        var SubApp_id = $(this).val()
        // console.log(SubApp_id)
        if (SubApp_id == "248" || SubApp_id == "456") {
        a.style.display = "block";
        b.style.display = "block";
        } else {
        a.style.display = "none";
        b.style.display = "none";
        }
    });
    
    $(document).on("change", "#SubTch3", function () {
        var SubApp_id = $(this).val()
        // console.log(SubApp_id)
        if (SubApp_id == "248" || SubApp_id == "456") {
        c.style.display = "block";
        d.style.display = "block";
        } else {
        c.style.display = "none";
        d.style.display = "none";
        }
    });


</script>