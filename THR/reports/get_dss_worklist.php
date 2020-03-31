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
$sysDate = date("Y-m-d");
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
// $cmbDurationSSID = $_POST['cmbDurationSSID'];
$svDateID = $_POST['svDateID'];
$durat = $_POST['durat'];

if($svDateID == ""){
    $svDateID = date("Y-m-d");
}


if (!$sortname) $sortname = 'id';
if (!$sortorder) $sortorder = 'desc';

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$start = (($page-1) * $rp);

$limit = "OFFSET $start ROWS FETCH NEXT $rp ROWS ONLY";

//$sql = "SELECT iso,name,printable_name,iso3,numcode FROM country $sort $limit";
$sql = "SELECT DISTINCT
TeacherMast.NIC, TeacherMast.SurnameWithInitials, CD_CensesNo.InstitutionName,
AppDate
FROM
TeacherMast
join StaffServiceHistory Sh1 on TeacherMast.NIC = Sh1.NIC
join CD_CensesNo on Sh1.InstCode = CD_CensesNo.CenCode
join CD_Positions on Sh1.PositionCode = CD_Positions.Code
JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
JOIN CD_Division ON CD_CensesNo.DivisionCode = CD_Division.CenCode
JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
LEFT JOIN CD_CensesCategory ON CD_CensesNo.SchoolType = CD_CensesCategory.ID
WHERE
TeacherMast.NIC IN ( SELECT TeacherMast.NIC FROM TeacherMast 
JOIN StaffServiceHistory Sh1 on TeacherMast.CurServiceRef = Sh1.ID
join CD_CensesNo on Sh1.InstCode = CD_CensesNo.CenCode
join CD_Positions on Sh1.PositionCode = CD_Positions.Code
JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
JOIN CD_Division ON CD_CensesNo.DivisionCode = CD_Division.CenCode
JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
LEFT JOIN CD_CensesCategory ON CD_CensesNo.SchoolType = CD_CensesCategory.ID
WHERE TeacherMast.NIC <> '' AND
(Sh1.ServiceRecTypeCode IS NULL or (Sh1.ServiceRecTypeCode != 'DS03' 
AND Sh1.ServiceRecTypeCode != 'RN01' 
AND Sh1.ServiceRecTypeCode != 'RT01' 
AND Sh1.ServiceRecTypeCode != 'DS01'))";
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
if($svDateID != ""){
    $sql .= " AND AppDate < '$svDateID'";
}
if ($cmbSchoolStatus != "") {
    $sql .= " AND CD_CensesNo.SchoolStatus = '$cmbSchoolStatus'";
}

$sql.= ") AND Sh1.AppDate = (SELECT Max(AppDate) FROM StaffServiceHistory Sh2 WHERE Sh2.NIC = Sh1.NIC AND Sh2.ServiceRecTypeCode IN('NA01','TR02','DA09'))"; 
if($durat != ""){
    $sql.="AND DATEDIFF(year, Appdate, '$svDateID') > '$durat'"; 
}
$sql.="order by NIC"; // Query to get last reord details of teachers with service code type TR01 And DA05. 
// The query gives the durations between dates and no need to order by Census Number. 
// echo $sql;
// exit();
$stmt = $db->runMsSqlQuery($sql);


$sqlCount = "SELECT DISTINCT
TeacherMast.NIC, TeacherMast.SurnameWithInitials, CD_CensesNo.InstitutionName,
AppDate
FROM
TeacherMast
join StaffServiceHistory Sh1 on TeacherMast.NIC = Sh1.NIC
join CD_CensesNo on Sh1.InstCode = CD_CensesNo.CenCode
join CD_Positions on Sh1.PositionCode = CD_Positions.Code
JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
JOIN CD_Division ON CD_CensesNo.DivisionCode = CD_Division.CenCode
JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
LEFT JOIN CD_CensesCategory ON CD_CensesNo.SchoolType = CD_CensesCategory.ID
WHERE
TeacherMast.NIC IN ( SELECT TeacherMast.NIC FROM TeacherMast 
JOIN StaffServiceHistory Sh1 on TeacherMast.CurServiceRef = Sh1.ID
join CD_CensesNo on Sh1.InstCode = CD_CensesNo.CenCode
join CD_Positions on Sh1.PositionCode = CD_Positions.Code
JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
JOIN CD_Division ON CD_CensesNo.DivisionCode = CD_Division.CenCode
JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
LEFT JOIN CD_CensesCategory ON CD_CensesNo.SchoolType = CD_CensesCategory.ID
WHERE TeacherMast.NIC <> '' AND
(Sh1.ServiceRecTypeCode IS NULL or (Sh1.ServiceRecTypeCode != 'DS03' 
AND Sh1.ServiceRecTypeCode != 'RN01' 
AND Sh1.ServiceRecTypeCode != 'RT01' 
AND Sh1.ServiceRecTypeCode != 'DS01'))";
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

if($svDateID != ""){
    $sqlCount .= " AND AppDate < '$svDateID'";
}
if ($cmbSchoolStatus != "") {
    $sqlCount .= " AND CD_CensesNo.SchoolStatus = '$cmbSchoolStatus'";
}
$sqlCount.= ") AND Sh1.AppDate = (SELECT Max(AppDate) FROM StaffServiceHistory Sh2 WHERE Sh2.NIC = Sh1.NIC  AND Sh2.ServiceRecTypeCode IN('NA01','TR02','DA09'))"; 
if($durat != ""){
    $sqlCount.="AND DATEDIFF(year, Appdate, '$svDateID') > '$durat'"; 
}
$sqlCount.="order by NIC";

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
    $AppDate = $row['AppDate']; 
    $Duration = date_diff(date_create(date("M j, Y")) ,$AppDate); // Duration of today and from date if no inputdate
    $TDuration = date_diff(date_create($svDateID), $AppDate); // Duration of input date and from date

    // $NIC = $row['TeacherMast.NIC']
    // $sql2 = "SELECT CD_CensesNo.InstitutionName From StaffServiceHistory
    // join CD_CensesNo on StaffServiceHistory.InstCode = CD_CensesNo.CenCode";

	$xml .= "<row id='".$id."'>";
	$xml .= "<cell><![CDATA[".$row['NIC']."]]></cell>";
	$xml .= "<cell><![CDATA[".utf8_encode($row['SurnameWithInitials'])."]]></cell>";
    $xml .= "<cell><![CDATA[".utf8_encode($row['InstitutionName'])."]]></cell>";
    $xml .= "<cell><![CDATA[".($row['AppDate'])->format('Y/m/d')."]]></cell>";
    if($svDateID==""){
        $xml .= "<cell><![CDATA[".$Duration->format('%y')."]]></cell>";
        $xml .= "<cell><![CDATA[".$Duration->format('%m')."]]></cell>";
        $xml .= "<cell><![CDATA[".$Duration->format('%d')."]]></cell>";
    }
    else{
        $xml .= "<cell><![CDATA[".$TDuration->format('%y')."]]></cell>";
        $xml .= "<cell><![CDATA[".$TDuration->format('%m')."]]></cell>";
        $xml .= "<cell><![CDATA[".$TDuration->format('%d')."]]></cell>";
    }
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
