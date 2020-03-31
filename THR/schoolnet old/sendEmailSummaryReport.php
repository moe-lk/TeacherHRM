<?php
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
ini_set('max_execution_time', -1);

include '../db_config/DBManager.php';
require '../PHPMailer/sendMailsCommon.php';


$db = new DBManager();
date_default_timezone_set("Asia/Colombo");
$NICNo = $_SESSION["NIC"];

$emailAddress = $_POST["txtemailAddress"];


$toAddress = array(
    $emailAddress
);
$toName = "Thushara";

$fullName = $_SESSION["fullName"];
$body = "Dear " . $fullName . "<br><br> This is test mail";
$emailSubject = "Test mail";

if (!empty($emailAddress)) {
    ob_start();


    $cmbSchoolType = $_REQUEST["cmbSchoolType"];
    $cmbProvince = $_REQUEST["cmbProvince"];
    $cmbDistrict = $_REQUEST["cmbDistrict"];
    $cmbZone = $_REQUEST["cmbZone"];
    $cmbDivision = $_REQUEST["cmbDivision"];
    $cmbSchool = $_REQUEST["cmbSchool"];
    $txtRptHedding = $_REQUEST["txtRptHedding"];

    if (isset($_REQUEST["txtBioFeildName"])) {
        $arrBioFeildName = $_REQUEST["txtBioFeildName"];
        $arrBioItemCode = $_REQUEST["txtBioItemCode"];
        $arrGender = array();
        $arrEthnicity = array();
        $arrReligion = array();
        $arrCivilStatus = array();
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

    if (isset($_REQUEST["txtTeachType"])) {
        $arrTeachType = $_REQUEST["txtTeachType"];
        $arrTeachSubject = $_REQUEST["txtTeachSubject"];
        $arrTeachGrade = $_REQUEST["txtTeachGrade"];

        $sqlTrn = "TRUNCATE TABLE TG_TeachingTemp";
        $stmt = $db->runMsSqlQuery($sqlTrn);

        for ($i = 0; $i < count($arrTeachType); $i++) {
            $sql = "SELECT 
  TeacherSubject.NIC,
  TeacherSubject.ID,
  CD_SubjectTypes.SubTypeName,
  CD_Subject.SubjectName,
  CD_SecGrades.GradeName
FROM TeacherSubject
LEFT OUTER JOIN CD_SecGrades
  ON TeacherSubject.SecGradeCode = CD_SecGrades.GradeCode
LEFT OUTER JOIN CD_Subject
  ON TeacherSubject.SubjectCode = CD_Subject.SubCode
LEFT OUTER JOIN CD_SubjectTypes
  ON TeacherSubject.SubjectType = CD_SubjectTypes.SubType
WHERE (TeacherSubject.NIC <> N'')";
            if ($arrTeachType[$i] != "")
                $sql.=" AND (TeacherSubject.SubjectType = N'" . $arrTeachType[$i] . "')";
            if ($arrTeachSubject[$i] != "")
                $sql.=" AND (TeacherSubject.SubjectCode = N'" . $arrTeachSubject[$i] . "')";
            if ($arrTeachGrade[$i] != "")
                $sql.=" AND (TeacherSubject.SecGradeCode = N'" . $arrTeachGrade[$i] . "')";

            $sqlInsert = "INSERT INTO TG_TeachingTemp  $sql";

            $stmt = $db->runMsSqlQuery($sqlInsert);
        }
    }

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
     * 
     * 
     * 
     */



// Group By
    $arrField = array();
    $arrGroupField = array();
    $arrHeader = array();
    if (isset($_REQUEST["groupBy"])) {
        $arryCheck = $_REQUEST["groupBy"];

        foreach ($arryCheck as $value) {
            if ($value == "SCT") {
                $arrField[] = "CD_CensesCategory.Decription";
                $arrGroupField[] = "CD_CensesCategory.Decription";
                $arrHeader[] = "School Type";
            }
            if ($value == "PRO") {
                $arrField[] = "CD_Provinces.Province";
                $arrGroupField[] = "CD_Provinces.Province";
                $arrHeader[] = "Province";
            }
            if ($value == "DIS") {
                $arrField[] = "CD_Districts.DistName";
                $arrGroupField[] = "CD_Districts.DistName";
                $arrHeader[] = "District";
            }
            if ($value == "ZON") {
                $arrField[] = "CD_Zone.InstitutionName AS zone";
                $arrGroupField[] = "CD_Zone.InstitutionName";
                $arrHeader[] = "Zone";
            }
            if ($value == "DIV") {
                $arrField[] = "CD_Division.InstitutionName AS division";
                $arrGroupField[] = "CD_Division.InstitutionName";
                $arrHeader[] = "Division";
            }
            if ($value == "SCH") {
                $arrField[] = "CD_CensesNo.InstitutionName AS school";
                $arrGroupField[] = "CD_CensesNo.InstitutionName";
                $arrHeader[] = "Institution";
            }
            if ($value == "Ethnicity") {
                $arrField[] = "CD_CensesNo.InstitutionName AS school";
                $arrGroupField[] = "CD_CensesNo.InstitutionName";
                $arrHeader[] = "Institution";
            }
            if ($value == "Gender") {
                $arrField[] = "CD_Gender.[Gender Name] AS gender";
                $arrGroupField[] = "CD_Gender.[Gender Name]";
                $arrHeader[] = "Male";
            }
        }
    }

    $fields = implode(",", $arrField);
    $grupfields = implode(",", $arrGroupField);


// end group by





    $html = "";
    $html.="<head>";
    $html.="<link href='http://example.com/style.css' rel='stylesheet' type='text/css'>";
    $html.="</head>";

    $html.="<body>";

    $html.="<div style=\"text-align:center; height:200px; width:auto;\"><p style=\"font-size:28px; font-weight:600;\">Ministry of Education Sri Lanka<br>Education Management Information System </p><p style=\"font-size:20px; font-weight:600;\">". $txtRptHedding ."<br>Summary Report&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . date('Y-m-d H:i:s') . "</p></div>";


    $arrFilter = array();
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
WHERE (CenCode = N'$cmbSchool')
AND (InstType = N'SC')";
        $stmt6 = $db->runMsSqlQuery($sql6);

        while ($row6 = sqlsrv_fetch_array($stmt6, SQLSRV_FETCH_ASSOC)) {
            $schoolName = $row6["InstitutionName"];
        }
    } else {
        $schoolName = "All";
    }

    $html.="<div style=\"height:150px;\"><b>
 School Type :" . $schType . "</br>Province : " . $provinceName . "</br>District : " . $districtName . "</br>Zone : " . $zoneName . "</br>Division : " . $divisionName . "</br>School : " . $schoolName . "</b></div>";





    if (!empty($arrGender)) {
        $sqlBio = "SELECT
  [Gender Name] AS gName 
FROM CD_Gender
WHERE (GenderCode IN ('" . implode("','", $arrGender) . "'))";
        $stmtBio = $db->runMsSqlQuery($sqlBio);

        $html.="<div style=\"width:100%; font-size:18px; font-weight:600; margin-top:20px; margin-right:15px;\"><label>Gender : </label><div style=\"margin-left:85px; margin-top:-20px;\">";
        while ($rowBio = sqlsrv_fetch_array($stmtBio, SQLSRV_FETCH_ASSOC)) {
            //$html.=$rowBio["gName"]."</br>";
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
            //$html.=$rowBio["gName"]."</br>";
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
            //$html.=$rowBio["gName"]."</br>";
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
            //$html.=$rowBio["gName"]."</br>";
            $html.="<label>" . $rowBio["CivilStatusName"] . "</label></br>";
        }
        $html.="</div></div>";
    }

    if (!empty($arrQulificationCode)) {
        $sqlBio = "SELECT
  Description AS qualification
FROM MOENational.dbo.CD_Qualif
WHERE (Qcode IN ('" . implode("','", $arrQulificationCode) . "'))";
        $stmtBio = $db->runMsSqlQuery($sqlBio);

        $html.="</br><div style=\"width:100%; font-size:18px; font-weight:600; margin-top:0px; margin-right:15px;\"><label  >Qualification : </label><div style=\"margin-left:130px; margin-top:-20px;\">";
        while ($rowBio = sqlsrv_fetch_array($stmtBio, SQLSRV_FETCH_ASSOC)) {
            //$html.=$rowBio["gName"]."</br>";
            $html.="<label>" . $rowBio["qualification"] . "</label></br>";
        }
        $html.="</div></div>";
    }

    if (isset($_REQUEST["txtTeachType"])) {
        $arrTeachTypeName = $_REQUEST["txtTeachTypeName"];
        $arrTeachSubjectName = $_REQUEST["txtTeachSubjectName"];
        $arrTeachGradeName = $_REQUEST["txtTeachGradeName"];

        $html.="</br><div style=\"width:100%; font-size:18px; font-weight:600; margin-top:0px; margin-right:15px;\"><label  >Teaching : </label><div style=\"margin-left:130px; margin-top:-20px;\">";
        for ($i = 0; $i < count($arrTeachTypeName); $i++) {

            $html.="<label>" . $arrTeachTypeName[$i] . " - " . $arrTeachSubjectName[$i] . " - " . $arrTeachGradeName[$i] . "</label></br>";
        }
        $html.="</div></div>";
    }

    if (isset($_REQUEST["txtSPosition"])) {
        $arrSPositionName = $_REQUEST["txtSPositionName"];
        $arrSTypeName = $_REQUEST["txtSTypeName"];

        $html.="</br><div style=\"width:100%; font-size:18px; font-weight:600; margin-top:0px; margin-right:15px;\"><label  >Service : </label><div style=\"margin-left:130px; margin-top:-20px;\">";
        for ($i = 0; $i < count($arrTeachTypeName); $i++) {

            $html.="<label>" . $arrSPositionName[$i] . " - " . $arrSTypeName[$i] . "</label></br>";
        }
        $html.="</div></div>";
    }





    $sql = "SELECT $fields,COUNT(TeacherMast.FullName) AS noOfTeachers
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
    $sql.= " INNER JOIN StaffQualification
  ON TeacherMast.HQualificatinRef = StaffQualification.ID
INNER JOIN CD_Qualif
  ON StaffQualification.QCode = CD_Qualif.Qcode";
}
    
    if (isset($_REQUEST["txtTeachType"])) {
        $sql.= " INNER JOIN TG_TeachingTemp
  ON TeacherMast.NIC = TG_TeachingTemp.NIC";
    }
    if (isset($_REQUEST["txtSPosition"])) {
        $sql.= " INNER JOIN TG_ServiceTemp
  ON TeacherMast.NIC = TG_ServiceTemp.NIC";
    }
	
if($resignT!='' || $dissmissedT!='' || $retiredT!=''){//DS03= Dismissed //RN01=Resign //RT01=Retired
	//$sql.= " WHERE (TeacherMast.NIC <> '')";
	
	$codeTpex="";
	if($resignT!='')$codeTpex.="'RN01',";
	if($dissmissedT!='')$codeTpex.="'DS03',";
	if($retiredT!='')$codeTpex.="'RT01',";
	
	$codeTpe=rtrim($codeTpex, ",");

$sql.= " WHERE (TeacherMast.NIC <> '') AND (StaffServiceHistory.ServiceRecTypeCode IN ($codeTpe))";

	/* if($resignT!='' and $dissmissedT=='')$sql.=" AND (StaffServiceHistory.ServiceRecTypeCode = 'RN01')";
	if($dissmissedT!='' and $resignT=='')$sql.=" AND (StaffServiceHistory.ServiceRecTypeCode = 'DS03')";
	if($dissmissedT!='' and $resignT!='')$sql.=" AND (StaffServiceHistory.ServiceRecTypeCode = 'RN01') OR (StaffServiceHistory.ServiceRecTypeCode = 'DS03')"; */
}else{
//$sql.= " WHERE (TeacherMast.NIC <> '') AND (StaffServiceHistory.ServiceRecTypeCode IS NULL or (StaffServiceHistory.ServiceRecTypeCode != 'DS03' AND StaffServiceHistory.ServiceRecTypeCode != 'RN01' AND StaffServiceHistory.ServiceRecTypeCode != 'RT01')";

$sql.= " WHERE (TeacherMast.NIC <> '') AND (StaffServiceHistory.ServiceRecTypeCode IS NULL or (StaffServiceHistory.ServiceRecTypeCode != 'DS03' AND StaffServiceHistory.ServiceRecTypeCode != 'RN01' AND StaffServiceHistory.ServiceRecTypeCode != 'RT01' AND StaffServiceHistory.ServiceRecTypeCode != 'DS01'))";

}

/* if($resignT!='' || $dissmissedT!=''){//DS03= Dismissed
	$sql.= " WHERE (TeacherMast.NIC <> '') AND (StaffServiceHistory.ServiceRecTypeCode = 'DS03')";
}else{
    $sql.= " WHERE (TeacherMast.NIC <> '') AND (StaffServiceHistory.ServiceRecTypeCode != 'DS03')  AND (StaffServiceHistory.ServiceRecTypeCode != 'RT01' or StaffServiceHistory.ServiceRecTypeCode IS NULL)";
} */
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


    if (!empty($arrGender))
        $sql.= " AND (TeacherMast.GenderCode IN ('" . implode("','", $arrGender) . "'))";
    if (!empty($arrEthnicity))
        $sql.= " AND (TeacherMast.EthnicityCode IN('" . implode("','", $arrEthnicity) . "'))";
    if (!empty($arrReligion))
        $sql.= " AND (TeacherMast.ReligionCode IN ('" . implode("','", $arrReligion) . "'))";
    if (!empty($arrCivilStatus))
        $sql.= " AND (TeacherMast.CivilStatusCode IN('" . implode("','", $arrCivilStatus) . "'))";

    if (!empty($arrQulificationCode))
        $sql.= " AND (StaffQualification.QCode IN('" . implode("','", $arrQulificationCode) . "'))";


    $sql.= " GROUP BY $grupfields ORDER BY $grupfields";

//echo $sql;
    $stmt = $db->runMsSqlQuery($sql);
	
	$totalRecordsFound=$db->rowCount($sql);

	$html.="</br><div style=\"width:100%; font-size:14px; font-weight:600; margin-top:0px; margin-right:15px;\"><label  >Total number of $totalRecordsFound record(s) found</label></div><br>";


    $html.="<table width=\"1100\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">";
    $html.="<tr>";

    foreach ($arrHeader as $columns) {
        $html.="<td width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\"><b>" . $columns . "</b></td> ";
    }
    $html.="<td width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; border-top: 1px solid #000000;\"><b>No of Staff</b></td> ";
    $html.="</tr>";

    $total = 0;
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $html.="<tr>";
        foreach ($row as $key => $data) {
            if ($key != "noOfTeachers") {
                $html.="<td width=\"10%\"  style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" . $data . "</td> ";
            } else {
                $html.="<td width=\"10%\" align=\"right\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" . number_format($data) . "</td> ";
                $total += $data;
            }
        }
        $html.="</tr>";
    }
    $html.="<tr>";
    $html.="<td width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-right: 1px solid #000000; border-top: 1px solid #000000;\"><b>Total</b></td><td colspan=\"".count($arrHeader)."\" width=\"10%\" align=\"right\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">" . number_format($total) . "</td> ";
    $html.="</tr>";
    $html.="</table>";

	$html.="</br><div style=\"width:100%; font-size:14px; font-weight:600; margin-top:0px; margin-right:15px;\"><label  >Computer generated - NEMIS</label></div><br>";
	
    $html.="</body>";
    $html.="</html>";

    echo $html;

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


    $sendmailStatus = sendEmails($toAddress, $toName, $body, $emailSubject, $filename);

    $redirect_page = "index.php";
    header("Location: $redirect_page");
}
?>

