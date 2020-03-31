<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
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

    if ($msg == '') {
        $familiChildStatus = "Add";
        if ($TeachingIDA == '') {//$familiChildStatus=='Add'){
            $queryMainSave = "INSERT INTO UP_TeacherSubject
			   (NIC,SubjectType,SubjectCode,MediumCode,SecGradeCode,Grade,LastUpdate,UpdatedBy,RecordLog)
		 VALUES
			   ('$NICUser','$SubjectType','$SubjectCode','$MediumCode','$SecGradeCode','$Grade','$LastUpdate','$nicUpdate','First change')";
            $db->runMsSqlQuery($queryMainSave);

            $reqTabMobAc = "SELECT ID FROM UP_TeacherSubject where NIC='$NICUser' and SubjectType='$SubjectType' and SubjectCode='$SubjectCode'  ORDER BY ID DESC";
            $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
            $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
            $TeachingID = trim($rowMobAc['ID']);
        }
    }

    if ($msg == '') {
        $queryRegis = "INSERT INTO TG_EmployeeUpdateTeaching (NIC,TeachingID,dDateTime,ZoneCode,IsApproved,ApproveDate,ApprovedBy,UpdateBy)
			 VALUES				   
		('$NICUser','$TeachingID','$LastUpdate','$ZoneCode','N','','','$nicUpdate')";
        $db->runMsSqlQuery($queryRegis);
        
        audit_trail($NICUser, $_SESSION["NIC"], 'teacherprofile\teaching.php', 'Insert', 'UP_TeacherSubject,TG_EmployeeUpdateTeaching', 'Insert user teaching info.');

        $success = "Your update request submitted successfully. Data will be displaying after the approvals.";
    }
}

?><script>var subjects = [];</script><?php
$sql = "SELECT * FROM CD_TeachSubjects";
$stmt = $db->runMsSqlQuery($sql);
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
    // echo $row['ID'];
    ?>
     <script>
    // var ID = "<?php //echo $row['ID'] ?>";
    // var Category = "<?php //echo $row['Category'] ?>";
    // var SubjectName = "<?php //echo $row['SubjectName'] ?>";
    var subj = JSON.parse('{"ID":"<?php echo $row['ID'] ?>", "Category":"<?php echo $row['Category'] ?>", "SubjectName":"<?php echo $row['SubjectName'] ?>"}'); 
    // JSON.stringify(subj);
    
    subjects.push(subj);

    // console.log(subjects[1]);
    </script>
    <?php
}            
?>
<?php
     $pageid=$_GET["pageid"];
     $menu=$_GET['menu'];
     $tpe=$_GET['tpe'];
     $id=$_GET['id'];
     
?>
<div class="main_content_inner_block">
    <div class="mcib_middle1">
    <form method="POST" name="frmTchDetails" id="frmTchDetails" action="TchSubmit.php">
        <table>
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold;";>
                Teaching Subject for most Hours
                </td>
            </tr>
            
            <tr>
                <td>Medium</td>
                <td>
                    <select id="MedTch1" onchange="console.log(MedTch1.value);">
                    <option>Select</option>
                    <?php // for meium combo box
                    $sql = "SELECT * FROM CD_Medium WHERE Code != ''";
                    $stmt = $db->runMsSqlQuery($sql);
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $TchMedium = $row['Medium'];
                        $TchMediumCode = $row['Code'];
                        echo "<option value=".$TchMediumCode.">".$TchMedium."</option>";
                    }
                    ?>
                        
                    </select>
                </td>
            </tr>
            <tr>
                <td>Grade Span</td>
                <td>
                    <select id="GradTch1">

                        <option>Select</option>
                        <?php // for meium combo box
                    $sql = "SELECT * FROM CD_Sections WHERE GradeCode IS NOT NULL";
                    $stmt = $db->runMsSqlQuery($sql);
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $TchGrade = $row['GradeName'];
                        $TchGradeCode = $row['GradeCode'];
                        echo "<option value=".$TchGradeCode.">".$TchGrade."</option>";
                    }
                    ?>
                    </select>
                    <script> 
                        var category = document.getElementById("GradTch1"); 
                    </script>
                </td>
            </tr>
            <tr>
                <td>Subject</td>
                <td>
                    <select id="SubTch1">
                        <option>Select</option>
                        <script>
                        // console.log(typeof subjects[1].Category);
                        // console.log(subjects[1].Category);
                        text = "";
                        for(i = 0; i < subjects.length; i++){
                            // if(subjects[i].Category == '2'){
                                text += "<option>"+subjects[i].SubjectName+"</option>";
                            // }
                            // console.log(subjects[i]);
                            
                        }
                        document.getElementById("SubTch1").innerHTML = text;  
                        </script>
                    </select>
                </td>
            </tr>
            <tr>
                <td>If Other Please Specify: </td>
                <td>
                    <input type="text" name="otherTch1" id = "otherTch1"> 
                </td>
            </tr>
            <tr><td colspan="2"><hr></td></tr>
            <td colspan="2" style="text-align: center; font-weight: bold;";>
            Teaching Subject for Second most hours
            </td>
            <tr>
                <td>Medium</td>
                <td>
                    <select id="MedTch2">
                    <option>Select</option>
                    <?php // for meium combo box
                    $sql = "SELECT * FROM CD_Medium WHERE Code != ''";
                    $stmt = $db->runMsSqlQuery($sql);
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $TchMedium = $row['Medium'];
                        $TchMediumCode = $row['Code'];
                        echo "<option value=".$TchMediumCode.">".$TchMedium."</option>";
                    }
                    ?> 
                    </select>
                </td>
            </tr>
            <tr>

                <td>Grade Span</td>
                <td>
                    <select id="GradTch2">

                        <option>Select</option>
                        <?php // for meium combo box
                    $sql = "SELECT * FROM CD_Sections WHERE GradeCode IS NOT NULL";
                    $stmt = $db->runMsSqlQuery($sql);
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $TchGrade = $row['GradeName'];
                        $TchGradeCode = $row['GradeCode'];
                        echo "<option value=".$TchGradeCode.">".$TchGrade."</option>";
                    }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Subject</td>
                <td>
                    <select id="SubTch2">
                        <option>Select</option>
                        <script>
                        // console.log(typeof subjects[1].Category);
                        // console.log(subjects[1].Category);
                        text = "";
                        for(i = 0; i < subjects.length; i++){
                            // if(subjects[i].Category == '2'){
                                text += "<option>"+subjects[i].SubjectName+"</option>";
                            // }
                            // console.log(subjects[i]);
                            
                        }
                        document.getElementById("SubTch2").innerHTML = text;  
                        </script>
                    </select>
                </td>
            </tr>
            <tr>
                <td>If Other Please Specify: </td>
                <td>
                    <input type="text" name="otherTch2"> 
                </td>
            </tr>
            <tr><td colspan="2"><hr></td></tr>
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold;";>
                Capable Teaching Subject
                </td>
            </tr>
            <tr>
                <td>Medium</td>
                <td>
                    <select id="MedTch3">
                    <option>Select</option>
                    <?php // for meium combo box
                    $sql = "SELECT * FROM CD_Medium WHERE Code != ''";
                    $stmt = $db->runMsSqlQuery($sql);
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $TchMedium = $row['Medium'];
                        $TchMediumCode = $row['Code'];
                        echo "<option value=".$TchMediumCode.">".$TchMedium."</option>";
                    }
                    ?> 
                        
                    </select>
                </td>
            </tr>
            <tr>
                <td>Grade Span</td>
                <td>
                    <select id="GradTch3">

                        <option>Select</option>
                        <?php // for meium combo box
                    $sql = "SELECT * FROM CD_Sections WHERE GradeCode IS NOT NULL";
                    $stmt = $db->runMsSqlQuery($sql);
                    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
                        $TchGrade = $row['GradeName'];
                        $TchGradeCode = $row['GradeCode'];
                        echo "<option value=".$TchGradeCode.">".$TchGrade."</option>";
                    }
                    ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>Subject </td>
                <td>
                    <select id="SubTch3">
                        <option>Select</option>
                        <script>
                        // console.log(typeof subjects[1].Category);
                        // console.log(subjects[1].Category);
                        text = "";
                        for(j = 0; j < subjects.length; j++){
                            // if(subjects[i].Category == '2'){
                                text += "<option>"+subjects[j].SubjectName+"</option>";
                            // }
                            // console.log(subjects[i]);
                            
                        }
                        document.getElementById("SubTch3").innerHTML = text;  
                        </script>
                    </select>
                </td>
            </tr>
            <tr>
                <td>If Other Please Specify: </td>
                <td>
                    <input type="text" name="otherTch3"> 
                </td>
            </tr>
            <tr>
            <td colspan="2">
            <div>
                <input type = "submit" name = "TchFrmSubmit" id = "TchFrmSubmit"> 
            </div>
            </td>
            </tr>
            <tr>
                <td><input type="hidden" name="id" id = "id"value="<?php echo $id ?>"></td>
            <tr>
        </table>
        <?php
        var_dump($POST);
        // if (isset($_POST['Submit'])) {
            $MedTch1 = $_POST['MedTch1'];
            $GradTch1 = $_POST["GradTch1"];
            $SubTch1 = $_POST["SubTch1"];

            $MedTch2 = $_POST["MedTch2"];
            $GradTch2 = $_POST["GradTch2"];
            $SubTch2 = $_POST["SubTch2"];

            $MedTch3 = $_POST["MedTch3"];
            $GradTch3 = $_POST["GradTch3"];
            $SubTch3 = $_POST["SubTch3"];
            
        // }
        // var_dump($_SESSION['id']);
        ?>
        </form>
        
        <script>
            // var id = document.getElementById("MedTch1");
            // console.log(id.value);
        </script>
    </div>
</div>
<script>

</script>