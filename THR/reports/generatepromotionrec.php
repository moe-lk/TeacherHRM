<?php

require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
ini_set('max_execution_time', -1);
// include 'get_rt_worklist2.php';
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

$svDateID = $_REQUEST['svDate'];
$excelStatus = $_REQUEST["rExportXLS"];
$reportT = $_REQUEST["reportT"];
$cmbSchoolStatus = $_REQUEST["cmbSchoolStatus"];

// var_dump($_REQUEST['svDate']);
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


$html .= "<div style=\"text-align:center; height:170px; width:auto;\"><p style=\"font-size:28px; font-weight:600; margin-left:130px;\">Ministry of Education Sri Lanka<br> Teacher Human Resource Management Portal - NEMIS </p><p style=\"font-size:20px; font-weight:600;\">" . $txtRptHedding . "</div>";


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
FROM CD_Division
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

// var_dump($svDate);

$html.="<div style=\"height:135px; font-size:18px; font-weight:600; width:70%;float: left;\">
School Type :" . $schType . "</br>Province : " . $provinceName . "</br>District : " . $districtName . "</br>Zone : " . $zoneName . "</br>Division : " . $divisionName ."</div>";

if(is_null($svDateID)){
    $html.="<div style=\"height:235px; font-size:18px; font-weight:600; float : right;width:30%\">
    School Status : " . $schoolStatus . "</br>School : ". $schoolName ."</br>To Date :  ". date("Y/m/d") ."</div>";    
}else{
    $html.="<div style=\"height:235px; font-size:18px; font-weight:600; float : right;width:30%\">
    School Status : " . $schoolStatus . "</br>School : ". $schoolName ."</br>To Date :  ". $svDateID ."</div>";    
}
$html .= "</br><div style=\"width:100%; font-size:14px; font-weight:600; margin-top:0px; margin-right:15px;\"><label> Generated by -  " . $_SESSION["fullName"] ." on ".date('Y-m-d H:i:s') . "</label></div><br>";

$no = 1;
$html .= "<table width=\"1100\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">";
$html .= "<tr>";
$html .= "<td class=\"thHeading\" width=\"5%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">No</td> ";
$html .= "<td class=\"thHeading\" width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">NIC Number</td> ";
$html .= "<td class=\"thHeading\" width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">Name</td> ";
$html .= "<td class=\"thHeading\" width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">Service Grade</td> ";
$html .= "<td class=\"thHeading\" width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">Institute</td> ";
$html .= "<td class=\"thHeading\" width=\"5%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">From Date</td> ";
$html .= "<td class=\"thHeading\" width=\"5%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">To Date</td> ";
$html .= "<td class=\"thHeading\" width=\"5%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">Duration</td> ";

$html .= "</tr>";


$sql = "SELECT
TeacherMast.NIC,
TeacherMast.SurnameWithInitials,
ServiceName,
CD_CensesNo.InstitutionName,
AppDate AS FromDate,
LEAD(Appdate,1) 
OVER (
	PARTITION BY TeacherMast.NIC
	ORDER BY AppDate
) AS Todate ,
DATEDIFF(year, Appdate, LEAD(Appdate,1) 
OVER (
	PARTITION BY TeacherMast.NIC
	ORDER BY AppDate
)) AS Duration 
FROM
TeacherMast
join StaffServiceHistory on TeacherMast.NIC = StaffServiceHistory.NIC
join CD_Service on CD_Service.ServCode = StaffServiceHistory.ServiceTypeCode
join CD_CensesNo on StaffServiceHistory.InstCode = CD_CensesNo.CenCode
join CD_Positions on StaffServiceHistory.PositionCode = CD_Positions.Code
JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
JOIN CD_Division ON CD_CensesNo.DivisionCode = CD_Division.CenCode
JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
LEFT JOIN CD_CensesCategory ON CD_CensesNo.SchoolType = CD_CensesCategory.ID
WHERE
StaffServiceHistory.ServiceRecTypeCode IN('NA01','DA05')
AND TeacherMast.NIC IN
(SELECT TeacherMast.NIC FROM
TeacherMast
join StaffServiceHistory on TeacherMast.CurServiceRef = StaffServiceHistory.ID

join CD_CensesNo on StaffServiceHistory.InstCode = CD_CensesNo.CenCode
join CD_Positions on StaffServiceHistory.PositionCode = CD_Positions.Code
JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
JOIN CD_Division ON CD_CensesNo.DivisionCode = CD_Division.CenCode
JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
LEFT JOIN CD_CensesCategory ON CD_CensesNo.SchoolType = CD_CensesCategory.ID
WHERE
(StaffServiceHistory.ServiceRecTypeCode IS NULL or (StaffServiceHistory.ServiceRecTypeCode != 'DS03' AND StaffServiceHistory.ServiceRecTypeCode != 'RN01' AND StaffServiceHistory.ServiceRecTypeCode != 'RT01' AND StaffServiceHistory.ServiceRecTypeCode != 'DS01'))
AND
TeacherMast.NIC <> ''";

if ($cmbProvince != ""){
    $sql .= " AND CD_Provinces.ProCode = N'$cmbProvince'";
}
if ($cmbSchoolType != ""){
    $sql .= " AND CD_CensesNo.SchoolType = N'$cmbSchoolType'";
}
if ($cmbDistrict != ""){
    $sql .= " AND CD_Districts.DistCode = N'$cmbDistrict'";
}
if ($cmbZone != ""){
    $sql .= " AND CD_CensesNo.ZoneCode = N'$cmbZone'";
}
if ($cmbDivision != ""){
    $sql .= " AND CD_CensesNo.DivisionCode = N'$cmbDivision'";
}
if ($cmbSchool != ""){
    $sql .= " AND CD_CensesNo.CenCode = N'$cmbSchool'";
}
if ($cmbSchoolStatus != "") {
    $sql .= " AND CD_CensesNo.SchoolStatus = '$cmbSchoolStatus'";
}
$sql .= ") ORDER BY TeacherMast.NIC,TeacherMast.CurServiceRef"; 

$stmt = $db->runMsSqlQuery($sql);

// $IDuration = date_diff(date_create($svDateID), $Fromdate);
// var_dump($IDuration);

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $Appdate = $row['FromDate'];
    $Todate = $row['Todate'];
    $Duration = $row['Duration'];
    $NDuration = date_diff(date_create(date("M j, Y")) ,$Appdate);
    $IDuration = date_diff(date_create($svDateID), $Appdate);

    $html .= "<tr>";
    $html .= "<td width=\5%\" style=\"text-align: center; border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" . $no++ . "</td> ";
    $html .= "<td width=\"10%\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" . $row['NIC'] . "</td> ";
    $html .= "<td width=\"10%\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" . $row['SurnameWithInitials'] . "</td> ";

    $html .= "<td width=\"10%\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" . $row['ServiceName'] . "</td> ";
    $html .= "<td width=\"10%\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" . $row['InstitutionName'] . "</td> ";
    $html .= "<td width=\"5%\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" . $Appdate->format('Y-m-d') . "</td> ";
    
    if(is_null($Todate)){
        if($svDateID == ""){
            $html .= "<td width=\"5%\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" . date("Y/m/d") . "</td> ";
        }else{
            $html .= "<td width=\"5%\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" . $svDateID . "</td> ";
        }
    }else{
        $html .= "<td width=\"5%\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" . $Todate->format('Y-m-d') . "</td> ";
    }
    if(is_null($Todate)){
        if($svDateID == ""){
            $html .= "<td width=\"5%\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" .$NDuration->format('%y yrs'). "</td> ";
        }else{
            $html .= "<td width=\"5%\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" .$IDuration->format('%y yrs'). "</td> ";
        }
    }
    else{
        $html .= "<td width=\"5%\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" .$Duration." yrs</td> ";
    }

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
