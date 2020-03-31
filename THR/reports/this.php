<!-- <?php
include '../db_config/DBManager.php';
$db = new DBManager();

// $page = $_POST['page'];
// $rp = $_POST['rp'];
// $sortname = $_POST['sortname'];
// $sortorder = $_POST['sortorder'];

// $page = $_POST['page'];
// $rp = $_POST['rp'];
// $sortname = $_POST['sortname'];
// $sortorder = $_POST['sortorder'];
// $_POST['lastName'];
//
//var_dump($extraparam1);
// $cmbSchoolType = $_POST['cmbSchoolType'];
// $cmbProvince = $_POST['cmbProvince'];
// $cmbDistrict = $_POST['cmbDistrict'];
// $cmbZone = $_POST['cmbZone'];
// $cmbDivision = $_POST['cmbDivision'];
// $cmbSchool = $_POST['cmbSchool'];
// $cmbSchoolStatus = $_POST['cmbSchoolStatus'];
// $reportT = $_POST['reportT'];
// $svDateID = $_POST['svDateID'];


// if (!$sortname) $sortname = 'id';
// if (!$sortorder) $sortorder = 'asc';

// $sort = "ORDER BY  TeacherMast.NIC, StaffServiceHistory.AppDate, $sortname $sortorder";

// if (!$page) $page = 1;
// if (!$rp) $rp = 10;

// $start = (($page-1) * $rp);

// $limit = "OFFSET $start ROWS FETCH NEXT $rp ROWS ONLY";

//$sql = "SELECT iso,name,printable_name,iso3,numcode FROM country $sort $limit";
// $sql = "SELECT DISTINCT
// TeacherMast.NIC,
// TeacherMast.SurnameWithInitials,
// CD_Zone.InstitutionName AS ZoneName,
// CD_CensesNo.InstitutionName,
// AppDate AS FromDate,
// LEAD(Appdate,1) 
// OVER (
// 	PARTITION BY TeacherMast.NIC
// 	ORDER BY AppDate
// ) AS Todate ,
// DATEDIFF(year, Appdate, LEAD(Appdate,1) 
// OVER (
// 	PARTITION BY TeacherMast.NIC
// 	ORDER BY AppDate
// )) AS Duration 
// DATEDIFF(month, Appdate, LEAD(Appdate,1) 
// OVER (
// 	PARTITION BY TeacherMast.NIC
// 	ORDER BY AppDate
// )) %12 AS Duration2
// FROM
// TeacherMast
// join StaffServiceHistory on TeacherMast.NIC = StaffServiceHistory.NIC
// join CD_CensesNo on StaffServiceHistory.InstCode = CD_CensesNo.CenCode
// join CD_Positions on StaffServiceHistory.PositionCode = CD_Positions.Code
// JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
// JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
// JOIN CD_Division ON CD_CensesNo.DivisionCode = CD_Division.CenCode
// JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
// LEFT JOIN CD_CensesCategory ON CD_CensesNo.SchoolType = CD_CensesCategory.ID
// WHERE
// StaffServiceHistory.ServiceRecTypeCode IN('NA01','TR02')
// AND TeacherMast.NIC IN
// (SELECT TeacherMast.NIC FROM
// TeacherMast
// join StaffServiceHistory on TeacherMast.CurServiceRef = StaffServiceHistory.ID
// join CD_CensesNo on StaffServiceHistory.InstCode = CD_CensesNo.CenCode
// join CD_Positions on StaffServiceHistory.PositionCode = CD_Positions.Code
// JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
// JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
// JOIN CD_Division ON CD_CensesNo.DivisionCode = CD_Division.CenCode
// JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
// LEFT JOIN CD_CensesCategory ON CD_CensesNo.SchoolType = CD_CensesCategory.ID
// WHERE
// (StaffServiceHistory.ServiceRecTypeCode IS NULL or (StaffServiceHistory.ServiceRecTypeCode != 'DS03' AND StaffServiceHistory.ServiceRecTypeCode != 'RN01' AND StaffServiceHistory.ServiceRecTypeCode != 'RT01' AND StaffServiceHistory.ServiceRecTypeCode != 'DS01'))
// AND
// TeacherMast.NIC <> '' AND CD_CensesNo.DivisionCode = 'ED0202')";

// if ($cmbProvince != "")
//     $sql .= " AND CD_Provinces.ProCode = N'$cmbProvince'";
// if ($cmbSchoolType != "")
//     $sql .= " AND CD_CensesNo.SchoolType = N'$cmbSchoolType'";
// if ($cmbDistrict != "")
//     $sql .= " AND CD_Districts.DistCode = N'$cmbDistrict'";
// if ($cmbZone != "")
//     $sql .= " AND CD_CensesNo.ZoneCode = N'$cmbZone'";
// if ($cmbDivision != "")
//     $sql .= " AND CD_CensesNo.DivisionCode = N'$cmbDivision'";
// if ($cmbSchool != "")
//     $sql .= " AND CD_CensesNo.CenCode = N'$cmbSchool'";

// if ($cmbSchoolStatus != "") {
//     $sql .= " AND CD_CensesNo.SchoolStatus = '$cmbSchoolStatus'";
// }
// if($svDateID != ""){
//     $sql .= " AND StaffServiceHistory.AppDate < '$svDateID'";
// }
// $sql .= ")";
// $sql .= " $sort $limit";


$stmt = $db->runMsSqlQuery($sql);


// $sqlCount = "SELECT DISTINCT
// TeacherMast.NIC,
// TeacherMast.SurnameWithInitials,
// CD_Zone.InstitutionName,
// CD_CensesNo.InstitutionName,
// AppDate AS FromDate,
// LEAD(Appdate,1) 
// OVER (
// 	PARTITION BY TeacherMast.NIC
// 	ORDER BY AppDate
// ) AS Todate ,
// DATEDIFF(year, Appdate, LEAD(Appdate,1) 
// OVER (
// 	PARTITION BY TeacherMast.NIC
// 	ORDER BY AppDate
// )) AS Duration ,
// DATEDIFF(month, Appdate, LEAD(Appdate,1) 
// OVER (
// 	PARTITION BY TeacherMast.NIC
// 	ORDER BY AppDate
// )) %12 AS Duration2
// FROM
// TeacherMast
// join StaffServiceHistory on TeacherMast.NIC = StaffServiceHistory.NIC
// join CD_CensesNo on StaffServiceHistory.InstCode = CD_CensesNo.CenCode
// join CD_Positions on StaffServiceHistory.PositionCode = CD_Positions.Code
// JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
// JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
// JOIN CD_Division ON CD_CensesNo.DivisionCode = CD_Division.CenCode
// JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
// LEFT JOIN CD_CensesCategory ON CD_CensesNo.SchoolType = CD_CensesCategory.ID
// WHERE
// StaffServiceHistory.ServiceRecTypeCode IN('NA01','TR02')
// AND TeacherMast.NIC IN
// (SELECT TeacherMast.NIC FROM
// TeacherMast
// join StaffServiceHistory on TeacherMast.CurServiceRef = StaffServiceHistory.ID
// join CD_CensesNo on StaffServiceHistory.InstCode = CD_CensesNo.CenCode
// join CD_Positions on StaffServiceHistory.PositionCode = CD_Positions.Code
// JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
// JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
// JOIN CD_Division ON CD_CensesNo.DivisionCode = CD_Division.CenCode
// JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
// LEFT JOIN CD_CensesCategory ON CD_CensesNo.SchoolType = CD_CensesCategory.ID
// WHERE
// (StaffServiceHistory.ServiceRecTypeCode IS NULL or (StaffServiceHistory.ServiceRecTypeCode != 'DS03' AND StaffServiceHistory.ServiceRecTypeCode != 'RN01' AND StaffServiceHistory.ServiceRecTypeCode != 'RT01' AND StaffServiceHistory.ServiceRecTypeCode != 'DS01'))
// AND
// TeacherMast.NIC <> ''";

// if ($cmbProvince != "")
//     $sqlCount .= " AND CD_Provinces.ProCode = N'$cmbProvince'";
// if ($cmbSchoolType != "")
//     $sqlCount .= " AND CD_CensesNo.SchoolType = N'$cmbSchoolType'";
// if ($cmbDistrict != "")
//     $sqlCount .= " AND CD_Districts.DistCode = N'$cmbDistrict'";
// if ($cmbZone != "")
//     $sqlCount .= " AND CD_CensesNo.ZoneCode = N'$cmbZone'";
// if ($cmbDivision != "")
//     $sqlCount .= " AND CD_CensesNo.DivisionCode = N'$cmbDivision'";
// if ($cmbSchool != "")
//     $sqlCount .= " AND CD_CensesNo.CenCode = N'$cmbSchool'";

// if ($cmbSchoolStatus != "") {
//     $sqlCount .= " AND CD_CensesNo.SchoolStatus = '$cmbSchoolStatus'";
// }
// if($svDateID != ""){
//     $sqlCount .= " AND StaffServiceHistory.AppDate < '$svDateID'";
// }
// $sqlCount .= ")"; // This array fetches Teacher details whose Service record type is NA01 or TR05
// //$sqlCount .= " AND CD_Positions.Code = 'SP12'";
// //$sqlCount .= " AND DATEDIFF(YY,DOB,'2018-04-08') >='60'";
// $total = $db->rowCount($sqlCount);

// echo $sql;


//while ($row = mysql_fetch_array($result)) {
// $id = 1;
// while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
//     $Fromdate = $row['FromDate']; 
//     $Todate = $row['Todate'];
//     $Duration = $row['Duration']; //Duration from Query
//     $Duration2 = $row['Duration2']; 
//     $NDuration = date_diff(date_create(date("M j, Y")) ,$Fromdate); //Duration with todays date
//     $IDuration = date_diff(date_create($svDateID), $Fromdate); //Duration with input date

//     var_dump($row);
    // echo $row['NIC'].'<br>';
    // echo $row['Appdate'].'<br>';
    // echo $Duration.'<br>';
    // echo $Duration2.'<br>';
} -->