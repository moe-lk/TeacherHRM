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

$SQL1 = "SELECT TOP(1)
*
FROM
TeacherMast
join StaffServiceHistory on TeacherMast.CurServiceRef = StaffServiceHistory.ID
join CD_CensesNo on StaffServiceHistory.InstCode = CD_CensesNo.CenCode 
WHERE StaffServiceHistory.NIC = '$id' ORDER BY StaffServiceHistory.AppDate DESC";

$stmt1 = $db->runMsSqlQuery($SQL1);
while ($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
    $SchType = Trim($row1['SchoolType']);
}
// $dateNow = date("Y/m/d");
// echo $dateNow;
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
        <?php // var_dump($SchType); 
        ?>
        <form method="POST" name="AppFrmDetails" id="AppFrmDetails" action="AppSubmit.php">
            <table>
                <tr>
                    <td colspan="2" style="text-align: center; font-weight: bold;" ;>
                        Appointment
                    </td>
                </tr>
                <tr>
                    <td>Appointment category: </td>
                    <td>
                        <select id="AppCat" name="AppCat">
                            <option>Select</option>
                            <?php // for apponment category combo box
                            if ($SchType == '6') {
                                $sql = "SELECT ID, AppointmentName FROM CD_PV_AppSubCategory WHERE ID IS NOT NULL";
                                $stmt = $db->runMsSqlQuery($sql);
                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                    $AppId = $row['ID'];
                                    $AppName = $row['AppointmentName'];
                                    echo "<option value=" . $AppId . ">" . $AppId . "- " . $AppName . "</option>";
                                }
                            } else {
                                $sql = "SELECT ID, AppointmentName FROM CD_AppSubCategory WHERE ID IS NOT NULL";
                                $stmt = $db->runMsSqlQuery($sql);
                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                    $AppId = $row['ID'];
                                    $AppName = $row['AppointmentName'];
                                    echo "<option value=" . $AppId . ">" . $AppId . "- " . $AppName . "</option>";
                                }
                            }
                            ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Appointed Medium: </td>
                    <td>
                        <select id="MedApp" name="MedApp">
                            <option>Select</option>
                            <?php // for meium combo box
                            $sql = "SELECT Medium FROM CD_Medium WHERE Code != ''";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $AppMeduim = $row['Medium'];
                                echo "<option value=" . $AppMeduim . ">" . $AppMeduim . "</option>";
                            }
                            ?>

                        </select>
                    </td>
                </tr>
                <?php //var_dump($AppId); 
                ?>
                <tr>
                    <td>Subject / Degree Appointed: </td>
                    <td>
                        <div id="SubAppDiv">
                            <select id="SubApp" onchange="show_otherdiv()" name="SubApp">
                                <option>Select</option>
                                <?php
                                if ($SchType == '6') {
                                    if ($AppId != '') {
                                        $sql = "SELECT * FROM CD_PV_TeachSubjects";
                                        $stmt = $db->runMsSqlQuery($sql);
                                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                            $AppSubjectID = $row['ID'];
                                            $AppSubject = $row['SubjectName'];
                                            echo "<option value=" . $AppSubjectID . ">" . $AppSubjectID . "-" . $AppSubject . "</option>";
                                        }
                                    }
                                } else {
                                    if ($AppId != '') {
                                        $sql = "SELECT * FROM CD_AppSubjects";
                                        $stmt = $db->runMsSqlQuery($sql);
                                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                            $AppSubjectID = $row['ID'];
                                            $AppSubject = $row['SubjectName'];
                                            echo "<option value=" . $AppSubjectID . ">" . $AppSubjectID . "-" . $AppSubject . "</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </td>
                </tr>
                <!-- <tr>
                    <td><button onclick="show_otherdiv()">Other</button></td>
                </tr> -->
                <tr>
                    <div id="otherdiv" style="display: none">
                        <td>If Other Please Specify: </td>
                        <td>

                            <input type="text" name="otherSub" id="otherSub">

                        </td>
                    </div>
                </tr>
                </tr>
                <td colspan="2">
                    <div>
                        <input type="submit" name="AppFrmSubmit" id="AppFrmSubmit">
                    </div>
                </td>
                </tr>
                <tr>
                    <td class="box"><input type="hidden" name="id" id="id" value="<?php echo $id ?>"></td>
                <tr>
            </table>
        </form>
    </div>
</div>
<script>
    function show_otherdiv() {
        var x = document.getElementById("otherdiv");

        if (x.style.display === "none") {
            console.log(x);
            x.style.display = "block";
        } else {
            x.style.display = "none";
        }
    }
</script>
<?php
$AppCat = $_POST["AppCat"];
$MedApp = $_POST["MedApp"];
$SubApp = $_POST["SubApp"];
$otherSub = $_POST["otherSub"];
?>