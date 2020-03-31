<!-- <link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
// $msg = "";
// $nicUpdate = $_SESSION['NIC'];
// $NICUser = $id;

// $isAvailablePerAdd = $isAvailableCurAdd = "";
// $success = "";
// if (isset($_POST["FrmSubmit"])) {
//     include('../activityLog.php');
//     $SubjectType = $_REQUEST['SubjectType'];
//     $SubjectCode = $_REQUEST['SubjectCode'];
//     $MediumCode = $_REQUEST['MediumCode'];
//     $SecGradeCode = $_REQUEST['SecGradeCode'];
//     $LastUpdate = date('Y-m-d H:i:s');

//     $msg = "";
//     $sqlServiceRef = " SELECT        TeacherMast.CurServiceRef, CD_CensesNo.ZoneCode
// FROM            StaffServiceHistory INNER JOIN
//                          CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode INNER JOIN
//                          TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
// WHERE        (TeacherMast.NIC = '$NICUser')";
//     $stmtCAllready = $db->runMsSqlQuery($sqlServiceRef);
//     $rowAllready = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC);
//     $CurServiceRef = trim($rowAllready['CurServiceRef']);
//     $ZoneCode = trim($rowAllready['ZoneCode']);

//     $TeachingIDA = "";
//     if ($SubjectType == "") {
//         $msg .= "Please select subject type.<br>";
//     }
//     if ($SubjectCode == "") {
//         $msg .= "Please select subject code.<br>";
//     }
//     if ($MediumCode == "") {
//         $msg .= "Please select medium.<br>";
//     }

//     if ($msg == '') {
//         $familiChildStatus = "Add";
//         if ($TeachingIDA == '') {//$familiChildStatus=='Add'){
//             $queryMainSave = "INSERT INTO UP_TeacherSubject
// 			   (NIC,SubjectType,SubjectCode,MediumCode,SecGradeCode,Grade,LastUpdate,UpdatedBy,RecordLog)
// 		 VALUES
// 			   ('$NICUser','$SubjectType','$SubjectCode','$MediumCode','$SecGradeCode','$Grade','$LastUpdate','$nicUpdate','First change')";
//             $db->runMsSqlQuery($queryMainSave);

//             $reqTabMobAc = "SELECT ID FROM UP_TeacherSubject where NIC='$NICUser' and SubjectType='$SubjectType' and SubjectCode='$SubjectCode'  ORDER BY ID DESC";
//             $stmtMobAc = $db->runMsSqlQuery($reqTabMobAc);
//             $rowMobAc = sqlsrv_fetch_array($stmtMobAc, SQLSRV_FETCH_ASSOC);
//             $TeachingID = trim($rowMobAc['ID']);
//         }
//     }

//     if ($msg == '') {
//         $queryRegis = "INSERT INTO TG_EmployeeUpdateTeaching				   (NIC,TeachingID,dDateTime,ZoneCode,IsApproved,ApproveDate,ApprovedBy,UpdateBy)
// 			 VALUES				   
// 		('$NICUser','$TeachingID','$LastUpdate','$ZoneCode','N','','','$nicUpdate')";
//         $db->runMsSqlQuery($queryRegis);
        
//         audit_trail($NICUser, $_SESSION["NIC"], 'teacherprofile\teaching.php', 'Insert', 'UP_TeacherSubject,TG_EmployeeUpdateTeaching', 'Insert user teaching info.');

//         $success = "Your update request submitted successfully. Data will be displaying after the approvals.";
//     }
// }
?>
<div class="main_content_inner_block">
    <div class="mcib_middle1">
        <table>
            <tr>
                <td>Appointment category: </td>
                <td>
                    <select id="AppCat">
                         
                    </select>
                </td>
            </tr>
            <tr>
                <td>Subject Appointed: </td>
                <td>
                    <select id="SubApp">
                         
                    </select>
                </td>
            </tr>
            <tr>
                <td>Appointed Medium: </td>
                <td>
                    <select id="MedApp">
                         
                    </select>
                </td>
            </tr>
            <tr>
                <td>If Other Please Specify: </td>
                <td>
                    <input type="text" name="otherSub"> 
                </td>
            </tr>
            <tr>
                <td colspan="2">Teaching</td>
            </tr>
            <tr>
                <td>Subject</td>
            </tr>
        </table>
    </div>
</div> -->