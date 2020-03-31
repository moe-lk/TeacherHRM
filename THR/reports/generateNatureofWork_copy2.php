<?php

require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
ini_set('max_execution_time', -1);

include '../db_config/DBManager.php';
$db = new DBManager();
date_default_timezone_set("Asia/Colombo");

$NICNo = $_SESSION["NIC"];

ob_start();


$cmbSchoolType = $_REQUEST["cmbSchoolType"];
$cmbProvince = $_POST["cmbProvince"];
$cmbDistrict = $_REQUEST["cmbDistrict"];
$cmbZone = $_REQUEST["cmbZone"];
$cmbDivision = $_REQUEST["cmbDivision"];
$cmbSchool = $_REQUEST["cmbSchool"];

$cmbSubject = $_REQUEST["cmbSubject"];
$cmbPosition = $_REQUEST["cmbPosition"];
$excelStatus = $_REQUEST["rExportXLS"];
$reportT = $_REQUEST["reportT"];
$cmbSchoolStatus = $_REQUEST["cmbSchoolStatus"];

//exit();
$txtRptHedding = "";

$sql2 = "SELECT
TG_QuickReport.ID,
TG_QuickReport.ReportName
FROM
TG_QuickReport
WHERE
TG_QuickReport.Enable = 'Y' AND TG_QuickReport.ID= '$reportT'";
    $stmt2 = $db->runMsSqlQuery($sql2);

    while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
        $txtRptHedding = $row2["ReportName"];
    }


$html = "";
$html .= "<html>";
if ($excelStatus == "XLS") {
    //header('Content-type: application/excel');
    header("Content-type: text/ms-excel");
    $filename = date('YmdHis') . ".xls";
    header('Content-Disposition: attachment; filename=' . $filename);

    $html .= "<html xmlns:x=\"urn:schemas-microsoft-com:office:excel\">
<head>
    <!--[if gte mso 9]>
    <xml>
        <x:ExcelWorkbook>
            <x:ExcelWorksheets>
                <x:ExcelWorksheet>
                    <x:Name>Sheet 1</x:Name>
                    <x:WorksheetOptions>
                        <x:Print>
                            <x:ValidPrinterInfo/>
                        </x:Print>
                    </x:WorksheetOptions>
                </x:ExcelWorksheet>
            </x:ExcelWorksheets>
        </x:ExcelWorkbook>
    </xml>
    <![endif]-->
</head>";
} else {
    $html .= "<head>";
    if ($excelStatus == "HTML") {
        $html.="<link href='../PDFGenerater/tempFile/emis_report.css' rel='stylesheet' type='text/css'>";
    }

    $html .= "</head>";
}
$html .= "<body>";


$totalRecordsFound = 0;


$html .= "<div style=\"text-align:center; height:170px; width:auto;\"><p style=\"font-size:28px; font-weight:600; margin-left:130px;\">Ministry of Education Sri Lanka<br>National Education Management Information System </p><p style=\"font-size:20px; font-weight:600;\">" . $txtRptHedding . "</div>";


if (!empty($cmbSchoolType)) {

    $sql1 = "SELECT DISTINCT
  CD_CensesCategory.Category
FROM CD_CensesNo
INNER JOIN CD_CensesCategory
  ON CD_CensesNo.SchoolType = CD_CensesCategory.ID
WHERE (CD_CensesNo.SchoolType = N'$cmbSchoolType')";
    $stmt1 = $db->runMsSqlQuery($sql1);

    while ($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
        $schType = $row1["Category"];
    }
} else {
    $schType = "All";
}

if (!empty($cmbProvince)) {
    $sql2 = "SELECT        ProCode, Province
FROM            CD_Provinces
WHERE        (ProCode = N'$cmbProvince')";
    $stmt2 = $db->runMsSqlQuery($sql2);

    while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
        $provinceName = $row2["Province"];
    }
} else {
    $provinceName = "All";
}

if (!empty($cmbDistrict)) {
    $sql3 = "SELECT        DistName, DistCode
FROM            CD_Districts
WHERE        (DistCode = N'$cmbDistrict')";
    $stmt3 = $db->runMsSqlQuery($sql3);

    while ($row3 = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)) {
        $districtName = $row3["DistName"];
    }
} else {
    $districtName = "All";
}


if (!empty($cmbZone)) {
    $sql4 = "SELECT        CenCode, InstitutionName AS zone
FROM            CD_Zone
WHERE        (CenCode = N'$cmbZone')";
    $stmt4 = $db->runMsSqlQuery($sql4);

    while ($row4 = sqlsrv_fetch_array($stmt4, SQLSRV_FETCH_ASSOC)) {
        $zoneName = $row4["zone"];
    }
} else {
    $zoneName = "All";
}

if (!empty($cmbDivision)) {
    $sql5 = "SELECT        CenCode, InstitutionName AS division
FROM            CD_Division
WHERE        (CenCode = N'$cmbDivision')";
    $stmt5 = $db->runMsSqlQuery($sql5);

    while ($row5 = sqlsrv_fetch_array($stmt5, SQLSRV_FETCH_ASSOC)) {
        $divisionName = $row5["division"];
    }
} else {
    $divisionName = "All";
}

if (!empty($cmbSchool)) {
    $sql6 = "SELECT
  InstitutionName
FROM CD_CensesNo
WHERE (CenCode = N'$cmbSchool')";
    $stmt6 = $db->runMsSqlQuery($sql6);

    while ($row6 = sqlsrv_fetch_array($stmt6, SQLSRV_FETCH_ASSOC)) {
        $schoolName = $row6["InstitutionName"];
    }
} else {
    $schoolName = "All";
}


if (!empty($cmbSubject)) {
    $sql6 = "SELECT
CD_Subject.SubCode,
CD_Subject.SubjectName
FROM
CD_Subject
WHERE
CD_Subject.SubCode = '$cmbSubject'";
    $stmt6 = $db->runMsSqlQuery($sql6);

    while ($row6 = sqlsrv_fetch_array($stmt6, SQLSRV_FETCH_ASSOC)) {
        $subjectName = $row6["SubjectName"];
    }
} else {
    $subjectName = "All";
}


if (!empty($cmbPosition)) {
    $sql6 = "SELECT
CD_Positions.Code,
CD_Positions.PositionName
FROM
CD_Positions
WHERE CD_Positions.Code = '$cmbPosition'";
    $stmt6 = $db->runMsSqlQuery($sql6);

    while ($row6 = sqlsrv_fetch_array($stmt6, SQLSRV_FETCH_ASSOC)) {
        $PositionName = $row6["PositionName"];
    }
} else {
    $PositionName = "All";
}


if(!empty($cmbSchoolStatus)){
    if($cmbSchoolStatus=="Y"){
        $schoolStatus = 'Functioning';
    }
    if($cmbSchoolStatus=="N"){
        $schoolStatus = 'Not Functioning';
    }
}else{
    $schoolStatus = 'All';
}

$html.="<div style=\"height:135px; font-size:18px; font-weight:600; width:70%;float: left;\">
School Type :" . $schType . "</br>Province : " . $provinceName . "</br>District : " . $districtName . "</br>Zone : " . $zoneName . "</br>Division : " . $divisionName ."</div>";

$html.="<div style=\"height:235px; font-size:18px; font-weight:600; float : right;width:30%\">
School Status : " . $schoolStatus . "</br>School : ". $schoolName ."</br>Subject :  ". $subjectName ."</br>Position :  ". $PositionName ."</div>";

$html .= "</br><div style=\"width:100%; font-size:14px; font-weight:600; margin-top:0px; margin-right:15px;\"><label> Generated by -  " . $_SESSION["fullName"] ." on ".date('Y-m-d H:i:s') . "</label></div><br>";

$no = 1;
$html .= "<table width=\"1100\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">";
$html .= "<tr>";
$html .= "<td class=\"thHeading\" width=\"5%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">No</td> ";
$html .= "<td class=\"thHeading\" width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">NIC Number</td> ";
$html .= "<td class=\"thHeading\" width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">Name</td> ";
$html .= "<td class=\"thHeading\" width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">Institute</td> ";

$html .= "</tr>";


  $sql = "SELECT DISTINCT
TeacherMast.NIC,
TeacherMast.SurnameWithInitials,
CD_CensesNo.InstitutionName
FROM
TeacherMast
INNER JOIN StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID
INNER JOIN CD_Positions ON StaffServiceHistory.PositionCode = CD_Positions.Code
LEFT JOIN TeacherSubject ON TeacherMast.NIC = TeacherSubject.NIC
LEFT JOIN CD_Subject ON TeacherSubject.SubjectCode = CD_Subject.SubCode
INNER JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
INNER JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
INNER JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
INNER JOIN CD_Division ON CD_CensesNo.DivisionCode = CD_Division.CenCode
INNER JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
LEFT JOIN CD_CensesCategory ON CD_CensesNo.SchoolType = CD_CensesCategory.ID
WHERE
TeacherMast.NIC <> ''";
if ($cmbProvince != "")
    $sql .= " AND CD_Provinces.ProCode = N'$cmbProvince'";
if ($cmbSchoolType != "")
    $sql .= " AND CD_CensesNo.SchoolType = N'$cmbSchoolType'";
if ($cmbDistrict != "")
    $sql .= " AND CD_Districts.DistCode = N'$cmbDistrict'";
if ($cmbZone != "")
    $sql .= " AND CD_CensesNo.ZoneCode = N'$cmbZone'";
if ($cmbDivision != "")
    $sql .= " AND CD_CensesNo.DivisionCode = N'$cmbDivision'";
if ($cmbSchool != "")
    $sql .= " AND CD_CensesNo.CenCode = N'$cmbSchool'";
if ($cmbPosition != "All") {
    $sql .= " AND StaffServiceHistory.PositionCode = '$cmbPosition'";
}
if ($cmbSubject != "All") {
    $sql .= " AND TeacherSubject.SubjectCode = '$cmbSubject'";
}
if ($cmbSchoolStatus != "") {
    $sql .= " AND CD_CensesNo.SchoolStatus = '$cmbSchoolStatus'";
}
 $sql .= " ORDER BY CD_CensesNo.InstitutionName";
//echo $sql;
//exit();
$stmt = $db->runMsSqlQuery($sql);

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

    $html .= "<tr>";
    $html .= "<td width=\5%\" style=\"text-align: center; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" . $no++ . "</td> ";
    $html .= "<td width=\"10%\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" . $row['NIC'] . "</td> ";
    $html .= "<td width=\"10%\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" . $row['SurnameWithInitials'] . "</td> ";
    $html .= "<td width=\"10%\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" . $row['InstitutionName'] . "</td> ";



    $html .= "</tr>";
}

if($no<=1){
   $html .= "<tr>";
    $html .= "<td colspan=\"5\" style=\"text-align: center; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">No Records Found.</td> ";



    $html .= "</tr>";
}

$html .= "</table>";


$html .= "</body>";
$html .= "</html>";

echo $html;
//exit();
if ($excelStatus == "HTML") {
        $html = ob_get_contents();
        $filename = date('YmdHis');
        file_put_contents("../PDFGenerater/tempFile/{$filename}.html", $html);
        ob_end_clean();

        echo "<script type=\"text/javascript\">  window.open('../PDFGenerater/tempFile/{$filename}.html', '_blank')  </script>";

        header("Location: ../reports");
      // echo '<script>window.open("../PDFGenerater/tempFile/{$filename}.html");</script>';
    exit();
}
if ($excelStatus == "PDF") {
//return the contents of the output buffer
    $html = ob_get_contents();
    $filename = date('YmdHis');

//save the html page in tmp folder
//file_put_contents("D:/downPDF/{$filename}.html", $html);
    file_put_contents("../PDFGenerater/tempFile/{$filename}.html", $html);

//Clean the output buffer and turn off output buffering
    ob_end_clean();

//convert HTML to PDF
//shell_exec("D:\WKPDF\wkhtmltopdf\wkhtmltopdf.exe -q D:/downPDF/{$filename}.html D:/downPDF/{$filename}.pdf");
    shell_exec("..\PDFGenerater\wkhtmltopdf\wkhtmltopdf.exe -q ../PDFGenerater/tempFile/{$filename}.html ../PDFGenerater/tempFile/{$filename}.pdf");

//if (file_exists("D:/downPDF/{$filename}.pdf")) {
    if (file_exists("../PDFGenerater/tempFile/{$filename}.pdf")) {
        header("Content-type:application/pdf");
        header("Content-Disposition:attachment;filename={$filename}.pdf");
        //echo file_get_contents("D:/downPDF/{$filename}.pdf");
        echo file_get_contents("../PDFGenerater/tempFile/{$filename}.pdf");
    } else {
        exit;
    }
}
?>
