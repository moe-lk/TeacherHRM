<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="AppSubjects.js" ></script>
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

$TbLD=1;
// var_dump($id);
$SQLTBL = "SELECT AppoinmentDetails.ID 
,[NIC]
,[AppCategory]
,[AppSubject]
,AppoinmentDetails.Medium AS MEDCode
,[SchoolType]
,[OtherSub]
,[ApprovedBy]
,[RecordStatus]
,[ApprovedDate]
,[ApproveComment] 
,[AppointmentName]
,CD_Medium.Medium AS Medium
,[SubjectName] FROM [MOENational].[dbo].[AppoinmentDetails]  
INNER JOIN CD_AppSubCategory ON AppCategory = CD_AppSubCategory.ID
INNER JOIN CD_AppSubjects ON AppSubject = CD_AppSubjects.ID
INNER JOIN CD_Medium ON AppoinmentDetails.Medium = CD_Medium.Code 
WHERE NIC = '$id' AND RecordStatus = '1'";
$stmtTBL = $db->runMsSqlQuery($SQLTBL);
while($rowTBL = sqlsrv_fetch_array($stmtTBL, SQLSRV_FETCH_ASSOC)){
    // echo "Yes";
    $AppCategory = trim($rowTBL['AppCategory']);
    $AppSubject = $rowTBL['AppSubject'];
    $MEDCode = $rowTBL['MEDCode'];
    $AppointmentName = $rowTBL['AppointmentName'];
    $SubjectName = $rowTBL['SubjectName'];
    $Medium = $rowTBL['Medium'];
}

$TempSQLTBL = "SELECT Temp_AppoinmentDetails.ID 
,[NIC]
,[AppCategory]
,[AppSubject]
,AppoinmentDetails.Medium AS MEDCode
,[SchoolType]
,[OtherSub]
,[ApprovedBy]
,[RecordStatus]
,[ApprovedDate]
,[ApproveComment] 
,[AppointmentName]
,CD_Medium.Medium AS Medium
,[SubjectName] FROM [MOENational].[dbo].[AppoinmentDetails]  
INNER JOIN CD_AppSubCategory ON AppCategory = CD_AppSubCategory.ID
INNER JOIN CD_AppSubjects ON AppSubject = CD_AppSubjects.ID
INNER JOIN CD_Medium ON AppoinmentDetails.Medium = CD_Medium.Code 
WHERE NIC = '$id' AND RecordStatus = '1'";
$TempstmtTBL = $db->runMsSqlQuery($TempSQLTBL);
while($TemprowTBL = sqlsrv_fetch_array($TempstmtTBL, SQLSRV_FETCH_ASSOC)){
    // echo "Yes";
    $TempAppCategory = trim($TemprowTBL['AppCategory']);
    $TempAppSubject = $TemprowTBL['AppSubject'];
    $TempMEDCode = trim($TemprowTBL['MEDCode']);
    $TempAppointmentName = $TemprowTBL['AppointmentName'];
    $TempSubjectName = $TemprowTBL['SubjectName'];
    $TempMedium = $TemprowTBL['Medium'];
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
    #Tblrecord {
        border-collapse: collapse;       
        /* border: 1px solid black;
        padding: 5px; */
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
        padding-left: 10px;
        padding-right: 10px;
        width:100%;
    }
    /* #otherdiv{
        display: none !important;
    } */
</style>
<div class="main_content_inner_block">
    <div class="mcib_middle1">
        <?php // var_dump($SchType); 
        ?>
        <!-- <div> -->
            <table name="Tblrecord" id="Tblrecord" border = "1px" style="width:100%; display: block;">
                <tr id="headtbl">
                    <td style="padding: 5px; padding-left: 10px; padding-right: 150px;">
                        Appointment Category
                    </td>
                    <td style="padding: 5px; padding-left: 10px; padding-right: 150px;">
                        Appointment subject
                    </td>
                    <td style="padding: 5px; padding-left: 10px; padding-right: 50px;">
                        Appointment Medium
                    </td>
                    <!-- <td>
                        Effective date
                    </td> -->
                    <td style="padding: 5px; padding-left: 10px; padding-right: 10px;">
                        Action
                    </td>
                <tr>
                <?php 
                    // var_dump($AppointmentName);
                    $TotaRows = $db->rowCount($SQLTBL);
                    // var_dump($TotaRows);
                    if (!$TotaRows){
                        // var_dump($TotaRows)
                        $TbLD = 0;
                    }

                    // $rowTBL = sqlsrv_fetch_array($stmtTBL, SQLSRV_FETCH_ASSOC);
                        echo "<td style='padding: 5px;'>".$AppCategory." - ".$AppointmentName."</td>";
                        echo "<td style='padding: 5px;'>".$AppSubject." - ".$SubjectName."</td>";
                        echo "<td style='padding: 5px;'>".$MEDCode." - ".$Medium."</td>";
                        echo "<td style='text-align:center'><input type='button' value='Edit' onclick='showForm()'></td>";
                ?>

                </tr>
            </table>
        <!-- </div> -->
        <form method="POST" name="AppFrmDetails" id="AppFrmDetails" action="AppSubmit.php" style="display:none; padding-top: 50px;">
            <table>
                <tr>
                    <td colspan="2" style="text-align: center; font-weight: bold;" ;>
                        Appointment
                    </td>
                </tr>
                <?php
                    // var_dump($AppCategory);
                ?>
                <tr>
                    <td>Appointment category: </td>
                    <td>
                        <select id="AppCat" name="AppCat">
                        <?php
                            if($TbLD == 0 || $AppCategory == ''){
                                echo "<option>Select</option>";
                            }
                            
                            $sql = "SELECT ID, AppointmentName FROM CD_AppSubCategory WHERE ID IS NOT NULL";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $AppId = trim($row['ID']);
                                $AppName = $row['AppointmentName'];
                                $seltebr = "";
                                if($AppId == $AppCategory){
                                    $seltebr = "selected";
                                }
                                echo "<option value=" . $AppId . " $seltebr>". $AppName ."</option>";
                            }
                        ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="padding-right: 50px">Subject / Degree Appointed: </td>
                    <td>
                        <div id="SubAppDiv">
                            <select id="SubApp" name="SubApp">
                            <?php
                                if($TbLD == 0 || $AppSubject == ''){
                                    echo "<option>Select</option>";
                                }
                                $sql = "SELECT * FROM CD_AppSubjects WHERE ID IS NOT NULL";
                                $stmt = $db->runMsSqlQuery($sql);
                                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                    $AppSubId = trim($row['ID']);
                                    $AppSubName = $row['SubjectName'];
                                    $seltebr = "";
                                    var_dump($AppSubId);
                                    if($AppSubId == $AppSubject){
                                        $seltebr = "selected";
                                    }
                                    echo "<option value=" . $AppSubId . " $seltebr>". $AppSubName ."</option>";
                                }
                            ?>
                            </select>
                        </div>
                    </td>
                </tr>
                <tr>
                    <div>
                        <td><div style="display :none" id="otherdiv">If Other Please Specify: </div></td>
                        <td>
                        <div style="display :none" id="inputdiv">
                            <input type="text" name="otherSub" id="otherSub">
                            </div>
                        </td>
                    </div>
                </tr>
                <tr>
                    <td>Appointed Medium: </td>
                    <td>
                        <select id="MedApp" name="MedApp">
                            
                            <?php // for meium combo box
                            if($TbLD == 0 || $MEDCode == ''){
                                echo "<option>Select</option>";
                            }
                            $sql = "SELECT * FROM CD_Medium WHERE Code != ''";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $MedID = trim($row['Code']);
                                $AppMeduim = $row['Medium'];
                                $seltebr = "";
                                if($MedID == $MEDCode){
                                    $seltebr = "selected";
                                }
                                echo "<option value=" . $MedID . " $seltebr>" . $AppMeduim . "</option>";
                            }
                            ?>

                        </select>
                    </td>
                </tr>
                <!-- <tr>
                    <td><button type="button" onclick="show_otherdiv()">Other</button></td>
                </tr> -->
                
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

    var Tbldata = <?php echo $TbLD; ?>;
    console.log(Tbldata);
    var tbl = document.getElementById("AppFrmDetails")
    var itbl = document.getElementById("Tblrecord");

    if (itbl.style.display === "block" && Tbldata==0) {
        itbl.style.display = "none";
        tbl.style.display = "block";
    }

    
    function showForm(){
        if (tbl.style.display === "none" ) {
            tbl.style.display = "block";
        }
    }
    var schType = "<?php echo $SchType; ?>";
    var i;
    if(schType == 6){
        i = '6';
    }else{
        i = '1';
    }
    // console.log(i);
    
    // $(document).on("change", "#MedApp", function () {
    //     var MedApp = $(this).val();
    //     console.log(MedApp)
    // });
</script>
<?php
$AppCat = $_POST["AppCat"];
$MedApp = $_POST["MedApp"];
$SubApp = $_POST["SubApp"];
$otherSub = $_POST["otherSub"];
?>