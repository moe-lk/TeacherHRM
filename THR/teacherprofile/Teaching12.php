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

$TbLD=1;

$SQLTBL = "SELECT * FROM TeachingDetails WHERE NIC = '$id' AND RecStatus = '1'";
$stmtTBL = $db->runMsSqlQuery($SQLTBL);
while($rowTBL = sqlsrv_fetch_array($stmtTBL, SQLSRV_FETCH_ASSOC)){
    $TchSubject1 = $rowTBL['TchSubject1'];
    $Medium1 = $rowTBL['Medium1'];
    $GradeCode1 = $rowTBL['GradeCode1'];
    $TchSubject2 = $rowTBL['TchSubject2'];
    $Medium2 = $rowTBL['Medium2'];
    $GradeCode2 = $rowTBL['GradeCode2'];
    $TchSubject3 = $rowTBL['TchSubject3'];
    $Medium3 = $rowTBL['Medium3'];
    $GradeCode3 = $rowTBL['GradeCode3'];
    $OtherSpecial = $rowTBL['OtherSpecial'];
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
        /* padding: 5px; */
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
</style>
<div class="main_content_inner_block">
    <div class="mcib_middle1">
    <table name="Tblrecord" id="Tblrecord" border = "1px" style="width:100%; display: block;">
                <tr id="headtbl">
                    <td colspan="3">
                        Hightest number of teaching periods
                    </td>
                    <td colspan="3">
                        Second Highest number of teaching periods
                    </td>
                    <td colspan="3">
                        Other capable subjects of teaching
                    </td>
                    <td>
                        Other Special Duties
                    </td>
                    <td>
                        Action
                    </td>
                </tr>
                <tr id="headtbl">
                    <td>Subject</td>
                    <td>Medium</td>
                    <td>Grade Span</td>
                    <td>Subject</td>
                    <td>Medium</td>
                    <td>Grade Span</td>
                    <td>Subject</td>
                    <td>Medium</td>
                    <td>Grade Span</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
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
                            echo "<td>".$TchSubject1."</td>";
                            echo "<td>".$Medium1."</td>";
                            echo "<td>".$GradeCode1."</td>";
                            echo "<td>".$TchSubject2."</td>";
                            echo "<td>".$Medium2."</td>";
                            echo "<td>".$GradeCode2."</td>";
                            echo "<td>".$TchSubject3."</td>";
                            echo "<td>".$Medium3."</td>";
                            echo "<td>".$GradeCode3."</td>";
                            echo "<td>".$OtherSpecial."</td>";
                            echo "<td style='text-align:center'><input type='button' value='Edit' onclick='showForm()'></td>";
                        // } 
                    // var_dump($TbLD);    // echo "<td>&nbsp</td>";

                        
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
                            $sql = "SELECT * FROM CD_TeachSubCategory WHERE ID IS NOT NULL";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchGrade = $row['CategoryName'];
                                $TchGradeCode = $row['ID'];
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
                            // if ($SchType == '6') {

                            //     $sql = "SELECT * FROM CD_PV_TeachSubjects";
                            //     $stmt = $db->runMsSqlQuery($sql);
                            //     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            //         $TchSubject = $row['SubjectName'];
                            //         $TchSubCode = $row['ID'];
                            //         echo "<option value=" . $TchSubCode  . ">" . $TchSubCode . " - " . $TchSubject . "</option>";
                            //     }
                            // } else {
                            //     $sql = "SELECT * FROM CD_TeachSubjects";
                            //     $stmt = $db->runMsSqlQuery($sql);
                            //     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            //         $TchSubject = $row['SubjectName'];
                            //         $TchSubCode = $row['ID'];
                            //         echo "<option value=" . $TchSubCode  . ">" . $TchSubCode . " - " . $TchSubject . "</option>";
                            //     }
                            // }
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
                    <td colspan="2">
                        <hr>
                    </td>
                </tr>
                <td colspan="2" style="text-align: center; font-weight: bold;" ;>
                    <h3>Second Highest number of teaching periods</h3>
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
                            $sql = "SELECT * FROM CD_TeachSubCategory WHERE ID IS NOT NULL";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchGrade = $row['CategoryName'];
                                $TchGradeCode = $row['ID'];
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
                            // if ($SchType == '6') {

                            //     $sql = "SELECT * FROM CD_PV_TeachSubjects";
                            //     $stmt = $db->runMsSqlQuery($sql);
                            //     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            //         $TchSubject = $row['SubjectName'];
                            //         $TchSubCode = $row['ID'];
                            //         echo "<option value=" . $TchSubCode  . ">" . $TchSubCode . " - " . $TchSubject . "</option>";
                            //     }
                            // } else {
                            //     $sql = "SELECT * FROM CD_TeachSubjects";
                            //     $stmt = $db->runMsSqlQuery($sql);
                            //     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            //         $TchSubject = $row['SubjectName'];
                            //         $TchSubCode = $row['ID'];
                            //         echo "<option value=" . $TchSubCode  . ">" . $TchSubCode . " - " . $TchSubject . "</option>";
                            //     }
                            // }
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
                            $sql = "SELECT * FROM CD_TeachSubCategory WHERE ID IS NOT NULL";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchGrade = $row['CategoryName'];
                                $TchGradeCode = $row['ID'];
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
                            // if ($SchType == '6') {

                            //     $sql = "SELECT * FROM CD_PV_TeachSubjects";
                            //     $stmt = $db->runMsSqlQuery($sql);
                            //     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            //         $TchSubject = $row['SubjectName'];
                            //         $TchSubCode = $row['ID'];
                            //         echo "<option value=" . $TchSubCode  . ">" . $TchSubCode . " - " . $TchSubject . "</option>";
                            //     }
                            // } else {
                            //     $sql = "SELECT * FROM CD_TeachSubjects";
                            //     $stmt = $db->runMsSqlQuery($sql);
                            //     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                            //         $TchSubject = $row['SubjectName'];
                            //         $TchSubCode = $row['ID'];
                            //         echo "<option value=" . $TchSubCode  . ">" . $TchSubCode . " - " . $TchSubject . "</option>";
                            //     }
                            // }
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
                    <td colspan="2">
                        <hr>
                    </td>
                </tr>
                <tr>
                    <td  class="box" style="padding-right:100px">Other Special Duties</td>
                    <td class="box">
                        <select id="otherspecial" name="otherspecial">
                            <option>Select</option>
                            
                            <?php
                            $sql = "SELECT * FROM CD_TeachSubjects WHERE Code = '9'";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $TchGrade = $row['SubjectName'];
                                $TchGradeCode = $row['ID'];
                                echo "<option value=" . $TchGradeCode . ">" . $TchGrade . "</option>";
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
    var MedTch1 = document.getElementById("MedTch1");
    var MedTch2 = document.getElementById("MedTch2");
    var MedTch3 = document.getElementById("MedTch3");
    var GradTch1 = document.getElementById("GradTch1");
    var GradTch2 = document.getElementById("GradTch2");
    var GradTch3 = document.getElementById("GradTch3");
    var SubTch1 = document.getElementById("SubTch1");
    var SubTch2 = document.getElementById("SubTch2");
    var SubTch3 = document.getElementById("SubTch3");
    var otherspecial = document.getElementById("otherspecial");

    // var otherspecial = </script><?php // echo $OtherSpecial ?><script>    
    // console.log(otherspecial);

    function showForm(){
        if (tbl.style.display === "none" ) {
            tbl.style.display = "block";
            
            var MedTch1 = "<?php echo $Medium1; ?>";
            var MedTch2 = "<?php echo $Medium2; ?>";
            var MedTch3 = "<?php echo $Medium3; ?>";
            var GradTch1 =  "<?php echo $GradeCode1; ?>";
            var GradTch2 =  "<?php echo $GradeCode2; ?>";
            var GradTch3 =  "<?php echo $GradeCode3; ?>";
            var SubTch1 = "<?php echo $TchSubject1; ?>";
            var SubTch2 = "<?php echo $TchSubject2; ?>";
            var SubTch3 = "<?php echo $TchSubject3; ?>";
            var otherspecial = "<?php echo $OtherSpecial; ?>";
            
            console.log(MedTch1);
        }
    }
    var schType = "<?php echo $SchType; ?>";
    var i;
    if(schType == 6){
        i = '6';
    }else{
        i = '1';
    }
    
    var x = document.getElementById("otherdiv1");
    var y = document.getElementById("otherTch1");
    var a = document.getElementById("otherdiv2");
    var b = document.getElementById("otherTch2");
    var c = document.getElementById("otherdiv3");
    var d = document.getElementById("otherTch3");
    
    
    // $(document).ready(function(){
    // // console.log(i);
    
    //     load_json_data1('GradTch1');
    
    //     function load_json_data1(id, category){
    //         var html_code = '';
            
    //         $.getJSON('TchSubject.json',function(data){
    //             html_code += '<option value = "">'+id+'</option>';
    //             $.each(data, function(key, value){
    //                 if(id == 'GradTch1'){
                        
    //                     if(value.category == '0'){
    //                         if(value.schtype == i){
                                
    //                             html_code += '<option value="'+value.id+'">'+value.name+'</option>';
    //                         }
    //                     }
    //                 } 
    //                 else{
    //                     if(value.category == category){
    //                         if(value.schtype == i){
    //                             // console.log(value.schtype);
    //                             // console.log(i);
    //                             html_code += '<option value="'+value.id+'">'+value.name+'THIS2</option>';
    //                         }                           
    //                     }
    //                 }
    //             });
    //             $('#'+id).html(html_code);
    //         }); 
    //     }
    //     $(document).on('change','#GradTch1',function(){
    //         var GradTch1_id = $(this).val();
    //         if(GradTch1_id != ''){
    //             // console.log(GradTch1_id);
    //             load_json_data('SubTch1',GradTch1_id);
    //         }
    //         else{
    //             $('#SubTch1').html('<option value="">Select</option>');
    //         }
    //     });
    //     $(document).on('change','#SubTch1',function(){
    //         var SubApp_id = $(this).val();

    //         if(SubApp_id == '12' || SubApp_id =='11'){
    //             x.style.display = "block";
    //             y.style.display = "block";
    //         }else{
    //             x.style.display = "none";
    //             y.style.display = "none"; 
    //         }
    //     });

    //     load_json_data2('GradTch2');

    //     function load_json_data2(id, category){
    //         var html_code = '';
            
    //         $.getJSON('TchSubject.json',function(data){
    //             html_code += '<option value = "">'+id+'</option>';
    //             $.each(data, function(key, value){
    //                 if(id == 'GradTch2'){
    //                     if(value.category == '0'){
    //                         if(value.schtype == i){
    //                             html_code += '<option value="'+value.id+'">'+value.name+'</option>';
    //                         }
    //                     }
    //                 } 
    //                 else{
    //                     if(value.category == category){
    //                         if(value.schtype == i){
    //                             html_code += '<option value="'+value.id+'">'+value.name+'</option>';
    //                         }                            
    //                     }
    //                 }
    //             });
    //             $('#'+id).html(html_code);
    //         }); 
    //     }
    //     $(document).on('change','#GradTch2',function(){
    //         var GradTch2_id = $(this).val();
    //         if(GradTch2_id != ''){
    //             // console.log(GradTch2_id);
    //             load_json_data('SubTch2',GradTch2_id);
    //         }
    //         else{
    //             $('#SubTch2').html('<option value="">Select</option>');
    //         }
    //     });
    //     $(document).on('change','#SubTch2',function(){
    //         var SubApp_id = $(this).val();

    //         if(SubApp_id == '12' || SubApp_id =='11'){
    //             a.style.display = "block";
    //             b.style.display = "block";
    //         }else{
    //             a.style.display = "none";
    //             b.style.display = "none"; 
    //         }
    //     });

    //     load_json_data('GradTch3');

    //     function load_json_data(id, category){
    //         var html_code = '';
            
    //         $.getJSON('TchSubject.json',function(data){
    //             html_code += '<option value = "">'+id+'</option>';
    //             $.each(data, function(key, value){
    //                 if(id == 'GradTch3'){
    //                     if(value.category == '0'){
    //                         if(value.schtype == i){
    //                             html_code += '<option value="'+value.id+'">'+value.name+'</option>';
    //                         }
    //                     }
    //                 } 
    //                 else{
    //                     if(value.category == category){
    //                         if(value.schtype == i){
    //                             html_code += '<option value="'+value.id+'">'+value.name+'</option>';
    //                         }                            
    //                     }
    //                 }
    //             });
    //             $('#'+id).html(html_code);
    //         }); 
    //     }
    //     $(document).on('change','#GradTch3',function(){
    //         var GradTch3_id = $(this).val();
    //         if(GradTch3_id != ''){
    //             // console.log(GradTch3_id);
    //             load_json_data('SubTch3',GradTch3_id);
    //         }
    //         else{
    //             $('#SubTch3').html('<option value="">Select</option>');
    //         }
    //     });
    //     $(document).on('change','#SubTch3',function(){
    //         var SubApp_id = $(this).val();

    //         if(SubApp_id == '12' || SubApp_id =='11'){
    //             c.style.display = "block";
    //             d.style.display = "block";
    //         }else{
    //             c.style.display = "none";
    //             d.style.display = "none"; 
    //         }
    //     });
    // });


</script>