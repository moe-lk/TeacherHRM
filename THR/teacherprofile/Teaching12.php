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
        $queryRegis = "INSERT INTO TG_EmployeeUpdateTeaching (NIC,TeachingID,dDateTime,ZoneCode,IsApproved,ApproveDate,ApprovedBy,UpdateBy)
			 VALUES				   
		('$NICUser','$TeachingID','$LastUpdate','$ZoneCode','N','','','$nicUpdate')";
        $db->runMsSqlQuery($queryRegis);

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
</style>
<div class="main_content_inner_block">
    <div class="mcib_middle1">
        <form method="POST" name="frmTchDetails" id="frmTchDetails" action="TchSubmit.php">
            <table>
                <tr>
                    <td colspan="2" style="text-align: center; font-weight: bold;" class="box">
                        <h3>Teaching Subject for most Hours</h3>
                    </td>
                </tr>

                <tr>
                    <td class="box">Medium</td>
                    <td class="box">
                        <select id="MedTch1" name="MedTch1">
                            <option>Select</option>
                            <?php // for meium combo box
                            $sql = "SELECT * FROM CD_Medium WHERE Code != ''";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchMedium = $row['Medium'];
                                $TchMediumCode = $row['Code'];
                                echo "<option value=" . $TchMediumCode . ">" . $TchMedium . "</option>";
                            }
                            ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="box">Grade Span</td>
                    <td class="box">
                        <select id="GradTch1" name="GradTch1">

                            <option>Select</option>
                            <?php // for meium combo box
                            $sql = "SELECT * FROM CD_SecGrades WHERE GradeCode IS NOT NULL";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchGrade = $row['GradeName'];
                                $TchGradeCode = $row['GradeCode'];
                                echo "<option value=" . $TchGradeCode . ">" . $TchGrade . "</option>";
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
                            <option>Select</option>
                            <?php
                            $sql = "SELECT * FROM CD_TeachSubjects";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchSubject = $row['SubjectName'];
                                $TchSubCode = $row['ID'];
                                echo "<option value=" . $TchSubCode  . ">" . $TchSubject . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="box">If Other Please Specify: </td>
                    <td class="box">
                        <input type="text" name="otherTch1" id="otherTch1">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr>
                    </td>
                </tr>
                <td colspan="2" style="text-align: center; font-weight: bold;" ;>
                    <h3>Teaching Subject for Second most hours</h3>
                </td>
                <tr>
                    <td class="box">Medium</td>
                    <td class="box">
                        <select id="MedTch2" name="MedTch2">
                            <option>Select</option>
                            <?php // for meium combo box
                            $sql = "SELECT * FROM CD_Medium WHERE Code != ''";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchMedium = $row['Medium'];
                                $TchMediumCode = $row['Code'];
                                echo "<option value=" . $TchMediumCode . ">" . $TchMedium . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>

                    <td class="box">Grade Span</td>
                    <td class="box">
                        <select id="GradTch2" name="GradTch2">

                            <option>Select</option>
                            <?php // for meium combo box
                            $sql = "SELECT * FROM CD_SecGrades WHERE GradeCode IS NOT NULL";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchGrade = $row['GradeName'];
                                $TchGradeCode = $row['GradeCode'];
                                echo "<option value=" . $TchGradeCode . ">" . $TchGrade . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="box">Subject</td>
                    <td class="box">
                        <select id="SubTch2" name="SubTch2">
                            <option>Select</option>
                            <?php
                            $sql = "SELECT * FROM CD_TeachSubjects";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchSubject = $row['SubjectName'];
                                $TchSubCode = $row['ID'];
                                echo "<option value=" . $TchSubCode  . ">" . $TchSubject . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="box">If Other Please Specify: </td>
                    <td class="box">
                        <input type="text" name="otherTch2">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center; font-weight: bold;" ;>
                        <h3>Capable Teaching Subject</h3>
                    </td>
                </tr>
                <tr>
                    <td class="box">Medium</td>
                    <td class="box">
                        <select id="MedTch3" name="MedTch3">
                            <option>Select</option>
                            <?php // for meium combo box
                            $sql = "SELECT * FROM CD_Medium WHERE Code != ''";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchMedium = $row['Medium'];
                                $TchMediumCode = $row['Code'];
                                echo "<option value=" . $TchMediumCode . ">" . $TchMedium . "</option>";
                            }
                            ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="box">Grade Span</td>
                    <td class="box">
                        <select id="GradTch3" name="GradTch3">

                            <option>Select</option>
                            <?php // for meium combo box
                            $sql = "SELECT * FROM CD_SecGrades WHERE GradeCode IS NOT NULL";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchGrade = $row['GradeName'];
                                $TchGradeCode = $row['GradeCode'];
                                echo "<option value=" . $TchGradeCode . ">" . $TchGrade . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="box">Subject </td>
                    <td class="box">
                        <select id="SubTch3" name="SubTch3">
                            <option>Select</option>
                            <?php
                            $sql = "SELECT * FROM CD_TeachSubjects";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchSubject = $row['SubjectName'];
                                $TchSubCode = $row['ID'];
                                echo "<option value=" . $TchSubCode  . ">" . $TchSubject . "</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="box">If Other Please Specify: </td>
                    <td class="box">
                        <input type="text" name="otherTch3">
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
    </div>
</div>
<script>

</script>