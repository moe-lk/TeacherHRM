<?php
include '../db_config/DBManager.php';
$db = new DBManager();

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];
// $_POST['lastName'];
//
//var_dump($extraparam1);
$cmbSchoolType = $_POST['cmbSchoolType'];
$cmbProvince = $_POST['cmbProvince'];
$cmbDistrict = $_POST['cmbDistrict'];
$cmbZone = $_POST['cmbZone'];
$cmbDivision = $_POST['cmbDivision'];
$cmbSchool = $_POST['cmbSchool'];
$cmbSchoolStatus = $_POST['cmbSchoolStatus'];
$reportT = $_POST['reportT'];
$rtDateID = $_POST['rtDateID'];

var_dump($cmbDistrict);


if (!$sortname) $sortname = 'id';
if (!$sortorder) $sortorder = 'desc';

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$start = (($page-1) * $rp);

$limit = "OFFSET $start ROWS FETCH NEXT $rp ROWS ONLY";

//$sql = "SELECT iso,name,printable_name,iso3,numcode FROM country $sort $limit";
$sql = "SELECT StaffServiceHistory.NIC, TeacherMast.SurnameWithInitials AS SName, PositionName
FROM
TeacherMast
INNER JOIN StaffServiceHistory ON TeacherMast.NIC = StaffServiceHistory.NIC
INNER JOIN CD_Positions ON StaffServiceHistory.PositionCode = CD_Positions.Code
INNER JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode 
INNER JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
INNER JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
INNER JOIN CD_Division ON CD_CensesNo.DivisionCode = CD_Division.CenCode
INNER JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
LEFT JOIN CD_CensesCategory ON CD_CensesNo.SchoolType = CD_CensesCategory.ID
WHERE 
StaffServiceHistory.NIC = '845745645545'";

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
$sql .= " ORDER BY TeacherMast.NIC,TeacherMast.CurServiceRef"; 


$stmt = $db->runMsSqlQuery($sql);

$sqlCount = "SELECT StaffServiceHistory.NIC, TeacherMast.SurnameWithInitials AS SName, PositionName
FROM
TeacherMast
INNER JOIN StaffServiceHistory ON TeacherMast.NIC = StaffServiceHistory.NIC
INNER JOIN CD_Positions ON StaffServiceHistory.PositionCode = CD_Positions.Code
INNER JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode 
INNER JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
INNER JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
INNER JOIN CD_Division ON CD_CensesNo.DivisionCode = CD_Division.CenCode
INNER JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
LEFT JOIN CD_CensesCategory ON CD_CensesNo.SchoolType = CD_CensesCategory.ID
WHERE 
StaffServiceHistory.NIC = '845745645545'";

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
$sql .= " ORDER BY TeacherMast.NIC,TeacherMast.CurServiceRef";
/*
if ($cmbRetirementID != "All"){
    $sqlCount .= " AND DATEDIFF(YY,DOB,'$cmbRetirementID') >='60'";
  }
*/
// if ( ! empty($rtDateID)){
//       $sqlCount .= " AND DATEDIFF(hh,DOB,'$rtDateID') >='525960'";
// }
// if ($cmbSchoolStatus != "") {
//     $sqlCount .= " AND CD_CensesNo.SchoolStatus = '$cmbSchoolStatus'";
// }
//$sqlCount .= " AND CD_Positions.Code = 'SP12'";
//$sqlCount .= " AND DATEDIFF(YY,DOB,'2018-04-08') >='60'";

$total = $db->rowCount($sqlCount);

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache" );
header("Content-type: text/xml");
$xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
$xml .= "<rows>";
$xml .= "<page>$page</page>";
$xml .= "<total>$total</total>";
//while ($row = mysql_fetch_array($result)) {
$id = 1;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$xml .= "<row id='".$id."'>";
	$xml .= "<cell><![CDATA[".$row['NIC']."]]></cell>";
    $xml .= "<cell><![CDATA[".utf8_encode($row['SName'])."]]></cell>";
    $xml .= "<cell><![CDATA[".utf8_encode($row['PositionName'])."]]></cell>";
    // $xml .= "<cell><![CDATA[".utf8_encode($row['InstitutionName'])."]]></cell>";
    // $xml .= "<cell><![CDATA[".utf8_encode($row['Appdate'])."]]></cell>";
    // $xml .= "<cell><![CDATA[".utf8_encode($row['Todate'])."]]></cell>";
    // $xml .= "<cell><![CDATA[".utf8_encode($row['Duration'])."]]></cell>";
	// $xml .= "</row>";

        $id++;
}

$xml .= "</rows>";
echo $xml;
// var_dump($xml);

?>
