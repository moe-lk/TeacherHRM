<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

$SQLTBL = "SELECT * FROM [MOENational].[dbo].[AppoinmentDetails] WHERE NIC = '$id' AND RecordStatus = '1'";
$stmtTBL = $db->runMsSqlQuery($SQLTBL);

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
                    <td colspan="3">
                        Appointment Category
                    </td>
                    <td colspan="3">
                        Appointment Medium
                    </td>
                    <td colspan="3">
                        Appointment subject
                    </td>
                    <!-- <td>
                        Effective date
                    </td> -->
                    <td>
                        Action
                    </td>
                <!-- </tr>
                <tr id="headtbl">
                    <td>Subject</td>
                    <td>Medium</td>
                    <td>Grade Span</td>
                    <td>Subject</td>
                    <td>Medium</td>
                    <td>Grade Span</td>
                    <td>Subject</td>
                    <td>Medium</td>
                    <td>Grade Span</td> -->
                    <!-- <td>&nbsp;</td> -->
                    <!-- <td>&nbsp;</td>
                </tr> -->
                <tr>
                <?php 
                    $TotaRows = $db->rowCount($SQLTBL);
                    // var_dump($TotaRows);
                    if (!$TotaRows){
                        // var_dump($TotaRows)
                        $TbLD = 0;
                    }
                    // sqlsrv_fetch_array($stmtTBL, SQLSRV_FETCH_ASSOC);
                    // var_dump($rowTBL);
                    // if(is_null($rowTBL)){
                    //     $TbLD = 0;
                    // }
                    while($rowTBL = sqlsrv_fetch_array($stmtTBL, SQLSRV_FETCH_ASSOC)){ 
                        // else{
                            echo "<td>".$rowTBL['TchSubject1']."</td>";
                            echo "<td>".$rowTBL['Medium1']."</td>";
                            echo "<td>".$rowTBL['GradeCode1']."</td>";
                            echo "<td>".$rowTBL['TchSubject2']."</td>";
                            echo "<td>".$rowTBL['Medium2']."</td>";
                            echo "<td>".$rowTBL['GradeCode2']."</td>";
                            echo "<td>".$rowTBL['TchSubject3']."</td>";
                            echo "<td>".$rowTBL['Medium3']."</td>";
                            echo "<td>".$rowTBL['GradeCode3']."</td>";
                            echo "<td style='text-align:center'><input type='button' value='Edit' onclick='showForm()'></td>";
                        // } 
                    } 
                    // var_dump($TbLD);    // echo "<td>&nbsp</td>";

                        
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
                <tr>
                    <td>Appointment category: </td>
                    <td>
                        <select id="AppCat" name="AppCat">
                            <option value="">Select</option>
                            <?php // for apponment category combo box
                            // if ($SchType == '6') {
                            //     $sql = "SELECT ID, AppointmentName FROM CD_PV_AppSubCategory WHERE ID IS NOT NULL";
                            //     $stmt = $db->runMsSqlQuery($sql);
                            //     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            //         $AppId = $row['ID'];
                            //         $AppName = $row['AppointmentName'];
                            //         echo "<option value=" . $AppId . ">" . $AppId . "- " . $AppName . "</option>";
                            //     }
                            // } else {
                            //     $sql = "SELECT ID, AppointmentName FROM CD_AppSubCategory WHERE ID IS NOT NULL";
                            //     $stmt = $db->runMsSqlQuery($sql);
                            //     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            //         $AppId = $row['ID'];
                            //         $AppName = $row['AppointmentName'];
                            //         echo "<option value=" . $AppId . ">" . $AppId . "- " . $AppName . "</option>";
                            //     }
                            // }
                            ?>

                        </select>
                    </td>
                </tr>
                <tr>
                    <td style="padding-right: 50px">Subject / Degree Appointed: </td>
                    <td>
                        <div id="SubAppDiv">
                            <select id="SubApp" name="SubApp">
                                <option value="">Select</option>
                                <?php
                                // if ($SchType == '6') {
                                //     if ($AppId != '') {
                                //         $sql = "SELECT * FROM CD_PV_TeachSubjects";
                                //         $stmt = $db->runMsSqlQuery($sql);
                                //         while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                //             $AppSubjectID = $row['ID'];
                                //             $AppSubject = $row['SubjectName'];
                                //             echo "<option value=" . $AppSubjectID . ">" . $AppSubjectID . "-" . $AppSubject . "</option>";
                                //         }
                                //     }
                                // } else {
                                //     if ($AppId != '') {
                                //         $sql = "SELECT * FROM CD_AppSubjects";
                                //         $stmt = $db->runMsSqlQuery($sql);
                                //         while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                //             $AppSubjectID = $row['ID'];
                                //             $AppSubject = $row['SubjectName'];
                                //             echo "<option value=" . $AppSubjectID . ">" . $AppSubjectID . "-" . $AppSubject . "</option>";
                                //         }
                                //     }
                                // }
                                ?>
                            </select>
                        </div>
                    </td>
                </tr>
                <!-- <tr>
                    <td><button type="button" onclick="show_otherdiv()">Other</button></td>
                </tr> -->
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
    
    var x = document.getElementById("otherdiv");
    var y = document.getElementById("inputdiv");

    $(document).ready(function(){

        load_json_data('AppCat');
        // console.log('AppCat');
        function load_json_data(id, category){
            var html_code = '';

            $.getJSON('AppSubject.json',function(data){
                html_code += '<option value = "">'+id+'</option>';
                $.each(data, function(key, value){
                    if(id == 'AppCat'){
                        if(value.category == '0'){
                            html_code += '<option value="'+value.id+'">'+value.name+'</option>';
                        }
                    } 
                    else{
                        if(value.category == category){
                            if(value.schtype == i){
                                html_code += '<option value="'+value.id+'">'+value.name+'</option>';
                            }                            
                        }
                    }
                });
             $('#'+id).html(html_code);
            }); 
        }
        // console.log(html_code);
        
        $(document).on('change','#AppCat',function(){
            var AppCat_id = $(this).val();
            // alert(AppCat_id);

            if(AppCat_id != ''){
                // console.log(AppCat_id);
                    load_json_data('SubApp',AppCat_id);
            }
            else{
                $('#SubApp').html('<option value="">Select</option>');
            }
        });
        
        $(document).on('change','#SubApp',function(){
            var SubApp_id = $(this).val();

            if(SubApp_id == '12' || SubApp_id =='11'){
                x.style.display = "block";
                y.style.display = "block";
            }else{
                x.style.display = "none";
                y.style.display = "none"; 
            }
        });
    });
</script>
<?php
$AppCat = $_POST["AppCat"];
$MedApp = $_POST["MedApp"];
$SubApp = $_POST["SubApp"];
$otherSub = $_POST["otherSub"];
?>