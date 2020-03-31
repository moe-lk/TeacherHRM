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
$cmbFirstValueID = $_POST['cmbFirstValueID'];
$cmbSecondValueID = $_POST['cmbSecondValueID'];
$sysDate = date("Y-m-d");



if (!$sortname) $sortname = 'id';
if (!$sortorder) $sortorder = 'desc';

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$start = (($page-1) * $rp);

$limit = "OFFSET $start ROWS FETCH NEXT $rp ROWS ONLY";

//$sql = "SELECT iso,name,printable_name,iso3,numcode FROM country $sort $limit";
  $sql = "SELECT DISTINCT
TeacherMast.NIC,
TeacherMast.SurnameWithInitials,
CD_CensesNo.InstitutionName
FROM
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
if ($cmbSecondValueID != "All") {
    $sql .= " AND DATEDIFF(YY,DOB,'$sysDate') between '$cmbFirstValueID' and '$cmbSecondValueID'";
}
if ($cmbFirstValueID != "All") {
    $sql .= " AND DATEDIFF(YY,DOB,'$sysDate') between '$cmbFirstValueID' and '$cmbSecondValueID'";
}
if ($cmbSchoolStatus != "") {
    $sql .= " AND CD_CensesNo.SchoolStatus = '$cmbSchoolStatus'";
}
//$sql .= " AND CD_Positions.Code = 'SP12'";
$sql.= " $sort $limit";
//echo $sql;
    $stmt = $db->runMsSqlQuery($sql);
//$result = runSQL($sql);
//$sql = "SELECT iso,name,printable_name,iso3,numcode FROM country $where $sort $limit";
  $sqlCount = "SELECT DISTINCT
TeacherMast.NIC,
TeacherMast.SurnameWithInitials,
CD_CensesNo.InstitutionName
FROM
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

if ($cmbProvince != "")
    $sqlCount .= " AND CD_Provinces.ProCode = N'$cmbProvince'";
if ($cmbSchoolType != "")
    $sqlCount .= " AND CD_CensesNo.SchoolType = N'$cmbSchoolType'";
if ($cmbDistrict != "")
    $sqlCount .= " AND CD_Districts.DistCode = N'$cmbDistrict'";
if ($cmbZone != "")
    $sqlCount .= " AND CD_CensesNo.ZoneCode = N'$cmbZone'";
if ($cmbDivision != "")
    $sqlCount .= " AND CD_CensesNo.DivisionCode = N'$cmbDivision'";
if ($cmbSchool != "")
    $sqlCount .= " AND CD_CensesNo.CenCode = N'$cmbSchool'";
if ($cmbSecondValueID != "All") {
    $sqlCount .= " AND DATEDIFF(YY,DOB,'$sysDate') between '$cmbFirstValueID' and '$cmbSecondValueID'";
}
if ($cmbFirstValueID != "All") {
    $sqlCount .= " AND DATEDIFF(YY,DOB,'$sysDate') between '$cmbFirstValueID' and '$cmbSecondValueID'";
}
if ($cmbSchoolStatus != "") {
    $sqlCount .= " AND CD_CensesNo.SchoolStatus = '$cmbSchoolStatus'";
}
//$sqlCount .= " AND CD_Positions.Code = 'SP12'";
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
	$xml .= "<cell><![CDATA[".utf8_encode($row['SurnameWithInitials'])."]]></cell>";
        $xml .= "<cell><![CDATA[".utf8_encode($row['InstitutionName'])."]]></cell>";
	$xml .= "</row>";

        $id++;
}

$xml .= "</rows>";
echo $xml;




/*
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );



header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );




header("Cache-Control: no-cache, must-revalidate" );



header("Pragma: no-cache" );



header("Content-type: text/x-json");



$json = "";



$json .= "{\n";



$json .= "page: $page,\n";



$json .= "total: $total,\n";



$json .= "rows: [";



$rc = false;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

if ($rc) $json .= ",";


$json .= "\n{";


		$json .= "id:'".$row['AccessRoleID']."',";



		$json .= "cell:['".$row['AccessRoleID']."'";



		$json .= ",'".addslashes($row['AccessRole'])."'";



		$json .= ",'".addslashes($row['UpdateBy'])."']";
              //  $json .= ",'".addslashes($edit)."']";




$json .= "}";



	$rc = true;
}





$json .= "]}";



echo $json;
 *
 */
?>
