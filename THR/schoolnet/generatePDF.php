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
$cmbProvince = $_REQUEST["cmbProvince"];
$cmbDistrict = $_REQUEST["cmbDistrict"];
$cmbZone = $_REQUEST["cmbZone"];
$cmbDivision = $_REQUEST["cmbDivision"];
$cmbSchool = $_REQUEST["cmbSchool"];
$txtRptHedding = $_REQUEST["txtRptHedding"];
$cmbSchoolStatus = $_REQUEST["cmbSchoolStatus"]; // Added schoolstatus variable
// var_dump($cmbSchoolStatus);
$excelStatus = "";
if (isset($_REQUEST["rExportXLS"])) {
    $excelStatus = $_REQUEST["rExportXLS"];
}
// var_dump($cmbSchoolStatus);
if (isset($_REQUEST["txtBioFeildName"])) {
    $arrBioFeildName = $_REQUEST["txtBioFeildName"];
    $arrBioItemCode = $_REQUEST["txtBioItemCode"];
    $arrGender = array();
    $arrEthnicity = array();
    $arrReligion = array();
    $arrCivilStatus = array();
    $arrHeaderLabel = array();
    for ($i = 0; $i < count($arrBioFeildName); $i++) {
        if ($arrBioFeildName[$i] == "Gender") {
            $arrGender[] = trim($arrBioItemCode[$i]);
        }
        if ($arrBioFeildName[$i] == "Ethnicity") {
            $arrEthnicity[] = trim($arrBioItemCode[$i]);
        }
        if ($arrBioFeildName[$i] == "Religion") {
            $arrReligion[] = trim($arrBioItemCode[$i]);
        }
        if ($arrBioFeildName[$i] == "Civil Status") {
            $arrCivilStatus[] = trim($arrBioItemCode[$i]);
        }
    }
}

if (isset($_REQUEST["txtQuliName"])) {
    $arrQulificationCode = $_REQUEST["txtQuliName"];
}

$resignT = $_REQUEST["resignT"];
$dissmissedT = $_REQUEST["dissmissedT"];
$retiredT = $_REQUEST["retiredT"];
$deadT = $_REQUEST["deadT"];

if (isset($_REQUEST["txtTeachType"])) {
    $arrTeachType = $_REQUEST["txtTeachType"];
    $arrTeachSubject = $_REQUEST["txtTeachSubject"];
    $arrTeachGrade = $_REQUEST["txtTeachGrade"];
    $arrTeachMedium = $_REQUEST["txtTeachMedium"];//

    $sqlTrn = "TRUNCATE TABLE TG_TeachingTemp";
    $stmt = $db->runMsSqlQuery($sqlTrn);

    for ($i = 0; $i < count($arrTeachType); $i++) {
        $sql = "SELECT 
  TeacherSubject.NIC,   
  TeacherSubject.ID,
  CD_SubjectTypes.SubTypeName,
  CD_Subject.SubjectName,
  CD_Medium.Medium,
  CD_SecGrades.GradeName
FROM TeacherSubject
LEFT OUTER JOIN CD_Medium
  ON TeacherSubject.MediumCode = CD_Medium.Code
LEFT OUTER JOIN CD_Subject
  ON TeacherSubject.SubjectCode = CD_Subject.SubCode
LEFT OUTER JOIN CD_SubjectTypes
  ON TeacherSubject.SubjectType = CD_SubjectTypes.SubType
LEFT OUTER JOIN CD_SecGrades 
  ON CD_SecGrades.GradeCode = TeacherSubject.SecGradeCode
WHERE (TeacherSubject.NIC <> N'')";
        if ($arrTeachType[$i] != "")
            $sql.=" AND (TeacherSubject.SubjectType = N'" . $arrTeachType[$i] . "')";
        if ($arrTeachSubject[$i] != "")
            $sql.=" AND (TeacherSubject.SubjectCode = N'" . $arrTeachSubject[$i] . "')";
        if ($arrTeachGrade[$i] != "")
            $sql.=" AND (TeacherSubject.SecGradeCode = N'" . $arrTeachGrade[$i] . "')";
        if($arrTeachMedium[$i] !="")
            $sql.=" AND (TeacherSubject.MediumCode = N'" . $arrTeachMedium[$i] . "')";
        $sqlInsert = "INSERT INTO TG_TeachingTemp  $sql";

        $stmt = $db->runMsSqlQuery($sqlInsert);
    }
}

// if (isset($_REQUEST["txtTeach2Type"])) {
//     $arr2TeachType = $_REQUEST["txtTeach2Type"];
//     $arr2TeachSubject = $_REQUEST["txtTeach2Subject"];
//     $arr2TeachGrade = $_REQUEST["txtTeach2Grade"];
//     $arr2TeachMedium = $_REQUEST["txtTeach2Medium"];//

//     $sqlTrn = "TRUNCATE TABLE TG_TeachingTemp";
//     $stmt = $db->runMsSqlQuery($sqlTrn);

//     var_dump($arr2TeachType,$arr2TeachSubject,$arr2TeachGrade,$arr2TeachMedium);

//     for ($i = 0; $i < count($arr2TeachType); $i++) {
// //         $sql = "SELECT 
// //   TeacherSubject.NIC,   
// //   TeacherSubject.ID,
// // --   CD_SubjectTypes.SubTypeName,
// //   CD_TeachSubjects.SubjectName,
// //   CD_Medium.Medium,
// //   CD_TeachSubCategory.GradeName
// // FROM TeacherSubject
// // LEFT OUTER JOIN CD_Medium
// //   ON TeacherSubject.MediumCode = CD_Medium.Code
// // LEFT OUTER JOIN CD_Subject
// //   ON TeacherSubject.SubjectCode = CD_TeachSubjects.Code
// // LEFT OUTER JOIN CD_SecGrades 
// //   ON CD_TeachSubCategory.GradeCode = TeacherSubject.SecGradeCode
// // WHERE (TeacherSubject.NIC <> N'')";
// //         if ($arrTeach2Subject[$i] != "")
// //             $sql.=" AND (TeacherSubject.SubjectCode = N'" . $arr2TeachSubject[$i] . "')";
// //         if ($arrTeach2Grade[$i] != "")
// //             $sql.=" AND (TeacherSubject.SecGradeCode = N'" . $arr2TeachGrade[$i] . "')";
// //         if($arrTeach2Medium[$i] !="")
// //             $sql.=" AND (TeacherSubject.MediumCode = N'" . $arr2TeachMedium[$i] . "')";
// //         $sqlInsert = "INSERT INTO TG_TeachingTemp  $sql";

// //         $stmt = $db->runMsSqlQuery($sqlInsert);
//     }
// }

if (isset($_REQUEST["txtSPosition"])) {
    $arrSerPosition = $_REQUEST["txtSPosition"];
    $arrSerType = $_REQUEST["txtSType"];

    $sqlTrn = "TRUNCATE TABLE TG_ServiceTemp";
    $stmt = $db->runMsSqlQuery($sqlTrn);

    for ($i = 0; $i < count($arrSerPosition); $i++) {


        $sql = "SELECT s.NIC, s.ID, p.PositionName, sg.ServiceName
FROM StaffServiceHistory s
INNER JOIN (
	SELECT NIC,MAX(AppDate) AS MaxDate
	FROM StaffServiceHistory
	GROUP BY NIC
) st ON s.NIC = st.NIC AND s.AppDate = st.MaxDate
LEFT OUTER JOIN CD_Service AS sg ON s.ServiceTypeCode =  sg.ServCode
LEFT OUTER JOIN CD_Positions AS p ON s.PositionCode = p.Code
WHERE (s.NIC <> N'')";
        if ($arrSerPosition[$i] != "")
            $sql.=" AND (s.PositionCode = N'" . $arrSerPosition[$i] . "')";
        if ($arrSerType[$i] != "")
            $sql.=" AND (sg.ServCode = N'" . $arrSerType[$i] . "')";

        $sqlInsert = "INSERT INTO TG_ServiceTemp  $sql";

        $stmt = $db->runMsSqlQuery($sqlInsert);
    }
}

/*
// Query save

if (isset($_REQUEST["rSaveQuery"])) {
    $radioSQ = $_REQUEST["rSaveQuery"];
    if ($radioSQ == "RSQ") {
        $qName = trim($_REQUEST["txtquerySaveName"]);
        $sql = "SELECT TOP (1)
  ID,
  SequenceID
FROM TG_QuerySave
WHERE (ID > 0)
AND (SequenceID <> N'')
ORDER BY ID DESC";
        $stmt = $db->runMsSqlQuery($sql);
        while ($row3 = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $sequenceID = $row3["SequenceID"];
            $querySaveID = $row3["ID"];
        }
        if ($sequenceID == "")
            $sequenceID = 1;
        else
            $sequenceID += 1;

        if ($qName == "")
            $qName = $sequenceID;

        $sqlInsert = "INSERT INTO TG_QuerySave VALUES('$sequenceID','$NICNo','$qName','bb')";
        $stmt = $db->runMsSqlQuery($sqlInsert);

        if (!empty($cmbSchoolType)) {
            $sqlInsert = "INSERT INTO TG_QuerySaveGeography VALUES('$sequenceID','ST','$cmbSchoolType')";
            $stmt = $db->runMsSqlQuery($sqlInsert);
        }
        if (!empty($cmbProvince)) {
            $sqlInsert = "INSERT INTO TG_QuerySaveGeography VALUES('$sequenceID','PR','$cmbProvince')";
            $stmt = $db->runMsSqlQuery($sqlInsert);
        }
        if (!empty($cmbDistrict)) {
            $sqlInsert = "INSERT INTO TG_QuerySaveGeography VALUES('$sequenceID','DI','$cmbDistrict')";
            $stmt = $db->runMsSqlQuery($sqlInsert);
        }
        if (!empty($cmbZone)) {
            $sqlInsert = "INSERT INTO TG_QuerySaveGeography VALUES('$sequenceID','ZN','$cmbZone')";
            $stmt = $db->runMsSqlQuery($sqlInsert);
        }
        if (!empty($cmbDivision)) {
            $sqlInsert = "INSERT INTO TG_QuerySaveGeography VALUES('$sequenceID','DV','$cmbDivision')";
            $stmt = $db->runMsSqlQuery($sqlInsert);
        }
        if (!empty($cmbSchool)) {
            $sqlInsert = "INSERT INTO TG_QuerySaveGeography VALUES('$sequenceID','SC','$cmbSchool')";
            $stmt = $db->runMsSqlQuery($sqlInsert);
        }


        if (isset($_REQUEST["txtBioFeildName"])) {
            $arrBioFeildName = $_REQUEST["txtBioFeildName"];
            $arrBioItemName = $_REQUEST["txtBioItemName"];
            for ($i = 0; $i < count($arrBioFeildName); $i++) {
                $sqlInsert = "INSERT INTO TG_QuerySaveBiography VALUES('$sequenceID','$arrBioFeildName[$i]','$arrBioItemName[$i]')";
                $stmt = $db->runMsSqlQuery($sqlInsert);
            }
        }

        if (isset($_REQUEST["txtQuliName"])) {
            $arrQulificationCode = $_REQUEST["txtQuliName"];
            for ($i = 0; $i < count($arrQulificationCode); $i++) {
                $sqlInsert = "INSERT INTO TG_QuerySaveQualification VALUES('$sequenceID','$arrQulificationCode[$i]')";
                $stmt = $db->runMsSqlQuery($sqlInsert);
            }
        }

        if (isset($_REQUEST["txtTeachType"])) {
            $arrTeachType = $_REQUEST["txtTeachType"];
            $arrTeachSubject = $_REQUEST["txtTeachSubject"];
            $arrTeachGrade = $_REQUEST["txtTeachGrade"];
            for ($i = 0; $i < count($arrTeachType); $i++) {
                $sqlInsert = "INSERT INTO TG_QuerySaveTeaching VALUES('$sequenceID','$arrTeachType[$i]','$arrTeachSubject[$i]','$arrTeachGrade[$i]')";
                $stmt = $db->runMsSqlQuery($sqlInsert);
            }
        }

        if (isset($_REQUEST["txtSPosition"])) {
            $arrSerPosition = $_REQUEST["txtSPosition"];
            $arrSerType = $_REQUEST["txtSType"];
            for ($i = 0; $i < count($arrSerPosition); $i++) {
                $sqlInsert = "INSERT INTO TG_QuerySaveService VALUES('$sequenceID','$arrSerPosition[$i]','$arrSerType[$i]')";
                $stmt = $db->runMsSqlQuery($sqlInsert);
            }
        }

        if (isset($_REQUEST["selectColum"])) {
            $arryColum = $_REQUEST["selectColum"];
            for ($i = 0; $i < count($arryColum); $i++) {
                $sqlInsert = "INSERT INTO TG_QuerySaveSelectedColum VALUES('$sequenceID','$arryColum[$i]')";
                $stmt = $db->runMsSqlQuery($sqlInsert);
            }
        }
    }
}
// End query save 
*/


$arrField = array();
$arrOrder = array();
$arrField[] = ("TeacherMast.NIC");
$arrField[] = ("TeacherMast.SurnameWithInitials");
$arrField[] = ("CD_CensesNo.InstitutionName AS school");
if($resignT!='' || $dissmissedT!='' || $retiredT!='' || $deadT != ''){
    $arrField[] = ("CD_ServiceRecType.Description");
    $arrField[] = ("CONVERT(varchar(20),appdate,121) AS appdate");
}
$arrOrder[] = "school";
$arryColum = array();
if (isset($_REQUEST["selectColum"])) {
    $arryColum = $_REQUEST["selectColum"];
    // var_dump($arryColum);
    foreach ($arryColum as $value) {
        if ($value == "Province") {
            $arrField[] = "CD_Provinces.Province";
            $arrOrder[] = "CD_Provinces.Province";
        }
        if ($value == "District") {
            $arrField[] = "CD_Districts.DistName";
            $arrOrder[] = "CD_Districts.DistName";
        }
        if ($value == "Zone") {
            $arrField[] = "CD_Zone.InstitutionName AS zone";
            $arrOrder[] = "zone";
        }
        if ($value == "Division") {
            $arrField[] = "CD_Division.InstitutionName AS division";
            $arrOrder[] = "division";
        }
        if($value == "Status"){
            $arrField[] = "CD_CensesNo.SchoolStatus";// Add status details here
            $arrOrder[] = "CD_CensesNo.SchoolStatus";
        }
        if($value == "Type"){
            $arrField[] = "CD_CensesCategory.Category";// Add School Type details here
            $arrOrder[] = "CD_CensesCategory.Category";
        }
        // if($value == "OutofSrv"){
        //     $arrField[] = "CD_ServiceRecType.ServiceRecTypeCode";
        //     $arrOrder[] = "CD_ServiceRecType.ServiceRecTypeCode";
        // }
        if($value == "Censes"){
            $arrField[] = "CD_CensesNo.CenCode";
            $arrOrder[] = "CD_CensesNo.CenCode";
        }
        if ($value == "Gender") {
            $arrField[] = "CD_Gender.[Gender Name] AS gender";
            $arrOrder[] = "gender";
        }
        if ($value == "Religion") {
            $arrField[] = "CD_Religion.ReligionName";
            $arrOrder[] = "CD_Religion.ReligionName";
        }
        if ($value == "Civil") {
            $arrField[] = "CD_CivilStatus.CivilStatusName";
            $arrOrder[] = "CD_CivilStatus.CivilStatusName";
        }
        if ($value == "Ethnicity") {
            $arrField[] = "CD_nEthnicity.EthnicityName";
            $arrOrder[] = "CD_nEthnicity.EthnicityName";
        }
        if ($value == "DOB") {
            $arrField[] = "CONVERT(varchar(20),TeacherMast.DOB,121) AS DOB";
            $arrOrder[] = "TeacherMast.DOB";
        }
        if ($value == "DOFA") {
            $arrField[] = "CONVERT(varchar(20),TeacherMast.DOFA,121) AS DOFA";
            $arrOrder[] = "TeacherMast.DOFA";
        }
        if ($value == "Mobile") {
            $arrField[] = "TeacherMast.MobileTel";
            $arrOrder[] = "TeacherMast.MobileTel";
        }
        if ($value == "Qualification") {
            $arrField[] = "CD_Qualif.Description";
            $arrOrder[] = "CD_Qualif.Description";
        }
        if ($value == "Category") {
            $arrField[] = "TG_TeachingTemp.SubTypeName";
            $arrOrder[] = "TG_TeachingTemp.SubTypeName";
        }
        if ($value == "Medium") {
            $arrField[] = "TG_TeachingTemp.MediumName";
            $arrOrder[] = "TG_TeachingTemp.MediumName";
        }
        if ($value == "Section") {
            $arrField[] = "TG_TeachingTemp.GradeName";
            $arrOrder[] = "TG_TeachingTemp.GradeName";
        }
        if ($value == "Subject") {
            $arrField[] = "TG_TeachingTemp.SubjectName AS Subject";
            $arrOrder[] = "TG_TeachingTemp.SubjectName";
        }

        if ($value == "Position") {
            $arrField[] = "TG_ServiceTemp.PositionName";
            $arrOrder[] = "TG_ServiceTemp.PositionName";
        }
        if ($value == "Service") {
            $arrField[] = "TG_ServiceTemp.ServiceName";
            $arrOrder[] = "TG_ServiceTemp.ServiceName";
        }
    }
}

$fields = implode(",", $arrField);
$order = implode(",", $arrOrder);

// var_dump($fields);
// var_dump($order);

$html = "";
$html.="<html>";

if ($excelStatus == "XLS") {
    //header('Content-type: application/excel');
    header("Content-type: text/ms-excel");
    $filename = date('YmdHis') . ".xls";
    header('Content-Disposition: attachment; filename=' . $filename);

    $html.= "<html xmlns:x=\"urn:schemas-microsoft-com:office:excel\">
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
    $html.="<head>";
    $html.="<link href='http://example.com/style.css' rel='stylesheet' type='text/css'>";
    $html.="</head>";
}
$html.="<body>";

$html.="<div style=\"text-align:center; height:200px; width:auto;\"><p style=\"font-size:28px; font-weight:600;\">Ministry of Education Sri Lanka<br>Teacher Human Resource Management Portal - NEMIS </p><p style=\"font-size:20px; font-weight:600;\">". $txtRptHedding ."<br>Detail Report&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . date('Y-m-d H:i:s') . "</p></div>";

if (!empty($cmbSchoolType)) {
    $sql1 = "SELECT DISTINCT CD_CensesCategory.Category FROM CD_CensesNo INNER JOIN CD_CensesCategory ON CD_CensesNo.SchoolType = CD_CensesCategory.ID WHERE (CD_CensesNo.SchoolType = N'$cmbSchoolType')";
    $stmt1 = $db->runMsSqlQuery($sql1);
    while ($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
        $schType = $row1["Category"];
    }
} 
else{
    $schType = "All";
}

if (!empty($cmbProvince)) {
    $sql2 = "SELECT ProCode, Province FROM CD_Provinces WHERE (ProCode = N'$cmbProvince')";
    $stmt2 = $db->runMsSqlQuery($sql2);
    while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
        $provinceName = $row2["Province"];
    }
} 
else {
    $provinceName = "All";
}

if (!empty($cmbDistrict)) {
    $sql3 = "SELECT DistName, DistCode FROM CD_Districts WHERE (DistCode = N'$cmbDistrict')";
    $stmt3 = $db->runMsSqlQuery($sql3);
    while ($row3 = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)) {
        $districtName = $row3["DistName"];
    }
} 
else {
    $districtName = "All";
}


if (!empty($cmbZone)) {
    $sql4 = "SELECT CenCode, InstitutionName AS zone FROM  CD_Zone WHERE (CenCode = N'$cmbZone')";
    $stmt4 = $db->runMsSqlQuery($sql4);

    while ($row4 = sqlsrv_fetch_array($stmt4, SQLSRV_FETCH_ASSOC)) {
        $zoneName = $row4["zone"];
    }
} 
else {
    $zoneName = "All";
}

if (!empty($cmbDivision)) {
    $sql5 = "SELECT CenCode, InstitutionName AS division FROM CD_Division WHERE (CenCode = N'$cmbDivision')";
    $stmt5 = $db->runMsSqlQuery($sql5);

    while ($row5 = sqlsrv_fetch_array($stmt5, SQLSRV_FETCH_ASSOC)) {
        $divisionName = $row5["division"];
    }
} 
else {
    $divisionName = "All";
}

if (!empty($cmbSchool)) {
    $sql6 = "SELECT InstitutionName FROM CD_CensesNo WHERE (CenCode = N'$cmbSchool') AND (InstType = N'SC')";
    $stmt6 = $db->runMsSqlQuery($sql6);
    while ($row6 = sqlsrv_fetch_array($stmt6, SQLSRV_FETCH_ASSOC)) {
        $schoolName = $row6["InstitutionName"];
    }
} 
else {
    $schoolName = "All";
}

//start school status function
if(!empty($cmbSchoolStatus)){
    $sql7 = "SELECT SchoolStatus FROM CD_CensesNo WHERE (SchoolStatus = '$cmbSchoolStatus')";
    $stmt7 = $db->runMsSqlQuery($sql7);

    while($row7 = sqlsrv_fetch_array($stmt7, SQLSRV_FETCH_ASSOC)){
        if($cmbSchoolStatus=="Y"){
            $schoolStatus = 'Functioning';
        }
        if($cmbSchoolStatus=="N"){
            $schoolStatus = 'Not Functioning';
        }
    }
}
else{
    $schoolStatus = "All";
}
// var_dump($sql7);

//Start Qualification

//End Qualification

//end school status function

$html.="<div style=\"height:135px; font-size:18px; font-weight:600;\">
School Type :" . $schType . "</br>Province : " . $provinceName . "</br>District : " . $districtName . "</br>Zone : " . $zoneName . "</br>Division : " . $divisionName . "</br>School : " . $schoolName . " </br>School Status : " . $schoolStatus . "
</div>";// Added school status to the end




if (!empty($arrGender)) {
    $sqlBio = "SELECT [Gender Name] AS gName FROM CD_Gender WHERE (GenderCode IN ('" . implode("','", $arrGender) . "'))";
    $stmtBio = $db->runMsSqlQuery($sqlBio);

    $html.="<div style=\"width:100%; font-size:18px; font-weight:600; margin-top:20px; margin-right:15px;\"><label>Gender : </label><div style=\"margin-left:85px; margin-top:-20px;\">";
    while ($rowBio = sqlsrv_fetch_array($stmtBio, SQLSRV_FETCH_ASSOC)) {
        $html.="<label>" . $rowBio["gName"] . "</label></br>";
    }
    $html.="</div></div>";
}

if (!empty($arrEthnicity)) {
    $sqlBio = "SELECT
  EthnicityName
FROM CD_nEthnicity
WHERE (Code IN ('" . implode("','", $arrEthnicity) . "'))";
    $stmtBio = $db->runMsSqlQuery($sqlBio);

    $html.="<div style=\"width:100%; font-size:18px; font-weight:600; margin-top:10px; margin-right:15px;\"><label>Ethnicity : </label><div style=\"margin-left:100px; margin-top:-20px;\">";
    while ($rowBio = sqlsrv_fetch_array($stmtBio, SQLSRV_FETCH_ASSOC)) {
        $html.="<label>" . $rowBio["EthnicityName"] . "</label></br>";
    }
    $html.="</div></div>";
}

if (!empty($arrReligion)) {
    $sqlBio = "SELECT
  ReligionName
FROM MOENational.dbo.CD_Religion
WHERE (Code IN ('" . implode("','", $arrReligion) . "'))";
    $stmtBio = $db->runMsSqlQuery($sqlBio);

    $html.="<div style=\"width:100%; font-size:18px; font-weight:600; margin-top:10px; margin-right:15px;\"><label>Religion : </label><div style=\"margin-left:100px; margin-top:-20px;\">";
    while ($rowBio = sqlsrv_fetch_array($stmtBio, SQLSRV_FETCH_ASSOC)) {
        $html.="<label>" . $rowBio["ReligionName"] . "</label></br>";
    }
    $html.="</div></div>";
}

if (!empty($arrCivilStatus)) {
    $sqlBio = "SELECT
  CivilStatusName
FROM MOENational.dbo.CD_CivilStatus
WHERE (Code IN ('" . implode("','", $arrCivilStatus) . "'))";
    $stmtBio = $db->runMsSqlQuery($sqlBio);

    $html.="<div style=\"width:100%; font-size:18px; font-weight:600; margin-top:10px; margin-right:15px;\"><label >Civil Status : </label><div style=\"margin-left:118px; margin-top:-20px;\">";
    while ($rowBio = sqlsrv_fetch_array($stmtBio, SQLSRV_FETCH_ASSOC)) {
        $html.="<label>" . $rowBio["CivilStatusName"] . "</label></br>";
    }
    $html.="</div></div>";
}

if (!empty($arrQulificationCode)) {
    $sqlBio = "SELECT Description AS qualification FROM CD_QualificationCategory WHERE (Code IN ('" . implode("','", $arrQulificationCode) . "'))";
    $stmtBio = $db->runMsSqlQuery($sqlBio);

    $html.="</br><div style=\"width:100%; font-size:18px; font-weight:600; margin-top:0px; margin-right:15px;\"><label  >Qualification : </label><div style=\"margin-left:130px; margin-top:-20px;\">";
    while ($rowBio = sqlsrv_fetch_array($stmtBio, SQLSRV_FETCH_ASSOC)) {
        $html.="<label>" . $rowBio["qualification"] . "</label></br>";
    }
    $html.="</div></div>";
}


if (isset($_REQUEST["txtTeachType"])) {
    $arrTeachTypeName = $_REQUEST["txtTeachTypeName"];
    $arrTeachSubjectName = $_REQUEST["txtTeachSubjectName"];
    $arrTeachGradeName = $_REQUEST["txtTeachGradeName"];
    $arrTeachMediumName = $_REQUEST["txtTeachMediumName"];

    $html.="</br><div style=\"width:100%; font-size:18px; font-weight:600; margin-top:0px; margin-right:15px;\"><label  >Teaching : </label><div style=\"margin-left:130px; margin-top:-20px;\">";
    for ($i = 0; $i < count($arrTeachTypeName); $i++) {

        $html.="<label>" . $arrTeachTypeName[$i] . " - " . $arrTeachSubjectName[$i] . " - " . $arrTeachMediumName[$i] . " - " . $arrTeachGradeName[$i] . "</label></br>";
    }
    $html.="</div></div>";
}

if (isset($_REQUEST["txtSPosition"])) {
    $arrSPositionName = $_REQUEST["txtSPositionName"];
    $arrSTypeName = $_REQUEST["txtSTypeName"];

    $html.="</br><div style=\"width:100%; font-size:18px; font-weight:600; margin-top:0px; margin-right:15px;\"><label  >Service : </label><div style=\"margin-left:130px; margin-top:-20px;\">";
    for ($i = 0; $i < count($arrSPositionName); $i++) {

        $html.="<label>" . $arrSPositionName[$i] . " - " . $arrSTypeName[$i] . "</label></br>";
    }
    $html.="</div></div>";
}

$html.="</br></br>";


$sql = "SELECT $fields
FROM TeacherMast
INNER JOIN StaffServiceHistory
  ON TeacherMast.CurServiceRef = StaffServiceHistory.ID
INNER JOIN CD_CensesNo
  ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
INNER JOIN CD_Districts
  ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
INNER JOIN CD_Zone
  ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
INNER JOIN CD_Division
  ON CD_CensesNo.DivisionCode = CD_Division.CenCode
INNER JOIN CD_Provinces
  ON CD_Districts.ProCode = CD_Provinces.ProCode
INNER JOIN CD_CensesCategory
  ON CD_CensesNo.SchoolType = CD_CensesCategory.ID
INNER JOIN CD_Gender
  ON TeacherMast.GenderCode = CD_Gender.GenderCode
INNER JOIN CD_nEthnicity
  ON TeacherMast.EthnicityCode = CD_nEthnicity.Code
INNER JOIN CD_Religion
  ON TeacherMast.ReligionCode = CD_Religion.Code
INNER JOIN CD_CivilStatus
  ON TeacherMast.CivilStatusCode = CD_CivilStatus.Code";


if (isset($_REQUEST["txtQuliName"])) {
    $sql.= " INNER JOIN StaffQualification ON TeacherMast.HQualificatinRef = StaffQualification.ID 
            INNER JOIN CD_Qualif ON StaffQualification.QCode = CD_Qualif.Qcode
            INNER JOIN CD_QualificationCategory ON CD_QualificationCategory.Code = CD_Qualif.Category";
}

if (isset($_REQUEST["txtTeachType"])) {
    $sql.= " INNER JOIN TG_TeachingTemp ON TeacherMast.NIC = TG_TeachingTemp.NIC";
}
if (isset($_REQUEST["txtSPosition"])) {
    $sql.= " INNER JOIN TG_ServiceTemp ON TeacherMast.NIC = TG_ServiceTemp.NIC";
}
if($resignT!='' || $dissmissedT!='' || $retiredT!='' || $deadT!=''){//DS03= Dismissed //RN01=Resign //RT01=Retired
    //$sql.= " WHERE (TeacherMast.NIC <> '')";
    $sql .= " INNER JOIN CD_ServiceRecType ON StaffServiceHistory.ServiceRecTypeCode = CD_ServiceRecType.DutyCode";
	
	$codeTpex="";
	if($resignT!='')$codeTpex.="'RN01',";
	if($dissmissedT!='')$codeTpex.="'DS03',";
	if($retiredT!='')$codeTpex.="'RT01',";
	if($deadT!='')$codeTpex.="'DS01',";
	
	$codeTpe=rtrim($codeTpex, ",");

    $sql.= " WHERE (TeacherMast.NIC <> '') AND (StaffServiceHistory.ServiceRecTypeCode IN ($codeTpe))";
	/* $sql.= " WHERE (TeacherMast.NIC <> '')";
	if($resignT!='' and $dissmissedT=='')$sql.=" AND (StaffServiceHistory.ServiceRecTypeCode = 'RN01')";
	if($dissmissedT!='' and $resignT=='')$sql.=" AND (StaffServiceHistory.ServiceRecTypeCode = 'DS03')";
	if($dissmissedT!='' and $resignT!='')$sql.=" AND (StaffServiceHistory.ServiceRecTypeCode = 'RN01') OR (StaffServiceHistory.ServiceRecTypeCode = 'DS03')"; */
}else{
//$sql.= " WHERE (TeacherMast.NIC <> '') AND ( StaffServiceHistory.ServiceRecTypeCode IS NULL or (StaffServiceHistory.ServiceRecTypeCode != 'DS03' AND StaffServiceHistory.ServiceRecTypeCode != 'RT01' ))";
$sql.= " WHERE (TeacherMast.NIC <> '') AND (StaffServiceHistory.ServiceRecTypeCode IS NULL or (StaffServiceHistory.ServiceRecTypeCode != 'DS03' AND StaffServiceHistory.ServiceRecTypeCode != 'RN01' AND StaffServiceHistory.ServiceRecTypeCode != 'RT01' AND StaffServiceHistory.ServiceRecTypeCode != 'DS01'))";
}
// var_dump($cmbSchoolStatus);
if ($cmbProvince != "")
    $sql.= " AND (CD_Provinces.ProCode = N'$cmbProvince')";

if ($cmbSchoolType != "")
    $sql.= " AND (CD_CensesNo.SchoolType = N'$cmbSchoolType')";
if ($cmbDistrict != "")
    $sql.= " AND (CD_Districts.DistCode = N'$cmbDistrict')";
if ($cmbZone != "")
    $sql.= " AND (CD_CensesNo.ZoneCode = N'$cmbZone')";
if ($cmbDivision != "")
    $sql.= " AND (CD_CensesNo.DivisionCode = N'$cmbDivision')";
if ($cmbSchool != "")
    $sql.= " AND (CD_CensesNo.CenCode = N'$cmbSchool')";
// Added School status features
if($cmbSchoolStatus != ""){ 
    // var_dump($cmbSchoolStatus);
    $sql .= " AND (CD_CensesNo.SchoolStatus = '$cmbSchoolStatus')";
}
// var_dump($sql1);
// var_dump($arrSPositionName);
if (!empty($arrGender))
    $sql.= " AND (TeacherMast.GenderCode IN ('" . implode("','", $arrGender) . "'))";
if (!empty($arrEthnicity))
    $sql.= " AND (TeacherMast.EthnicityCode IN('" . implode("','", $arrEthnicity) . "'))";
if (!empty($arrReligion))
    $sql.= " AND (TeacherMast.ReligionCode IN ('" . implode("','", $arrReligion) . "'))";
if (!empty($arrCivilStatus))
    $sql.= " AND (TeacherMast.CivilStatusCode IN('" . implode("','", $arrCivilStatus) . "'))";
if (!empty($arrQulificationCode))
    $sql.= " AND (CD_QualificationCategory.Code IN('" . implode("','", $arrQulificationCode) . "'))";
// if (!empty($arrSTypeName))// Change This..........
// //     $sql .= " AND (CD_Service.ServCode IN('" . implode("','", $arrSTypeName) ."'))";
//     $sql .= " AND (CD_Positions.Code IN('" . implode("','", $arrSPositionName) ."'))";

$sql.= " ORDER BY $order";
//echo $sql;
//exit();
// ini_set("xdebug.var_display_max_children", -1);
// ini_set("xdebug.var_display_max_data", -1);
// ini_set("xdebug.var_display_max_depth", -1);
// var_dump($sql);

$stmt = $db->runMsSqlQuery($sql);

$totalRecordsFound=$db->rowCount($sql);

$html.="</br><div style=\"width:100%; font-size:14px; font-weight:600; margin-top:0px; margin-right:15px;\"><label  >Total number of $totalRecordsFound record(s) found</label></div><br>";// Report structure

$no = 1;
$html.="<table width=\"1100\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">";
$html.="<tr>";
$html.="<td width=\"5%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">No</td> ";
$html.="<td width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">NIC</td> ";
$html.="<td width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">Name</td> ";
$html.="<td width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">Institute</td> ";
if($resignT!='' || $dissmissedT!='' || $retiredT!='' || $deadT!=''){
    $html.="<td width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">Service Status</td> ";
    $html.="<td width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">Date Occur</td> ";

}

if (count($arryColum) > 0) {
    foreach ($arryColum as $columns) {
        $html.="<td width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" . $columns . "</td> ";
    }
    
    // var_dump($arryColum);
}
// if(isset($_REQUEST["txtTeachType"])){
//     $html.="<td width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">Subject</td> ";
//     $html.="<td width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">Medium</td> ";
//     $html.="<td width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">Section</td> ";
//     // $html.="<td width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">Subject</td> ";
// }
$html.="</tr>";


while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $html.="<tr>";
    $html.="<td width=\5%\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" . $no++ . "</td> ";
    // var_dump($row);
    foreach ($row as $data) {
    
        $html.="<td width=\"10%\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" . $data . "</td> "; 
        
    }
    $html.="</tr>";

}

// var_dump($arrSTypeName);
$html.="</table>";
$html.="</br><div style=\"width:100%; font-size:14px; font-weight:600; margin-top:0px; margin-right:15px;\"><label>Computer generated - NEMIS</label></div><br>"; //Bottom text 

$html.="</body>";
$html.="</html>";

echo $html;


if ($excelStatus != "XLS") {
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

