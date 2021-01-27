<?PHP
include '../db_config/DBManager.php';
$db = new DBManager();
// include "../db_config/connectionNEW.php";
include "../db_config/connectionPDO.php";
session_start();
ini_set("memory_limit", "2048M"); 

// $medium = $_SESSION['Medium'];
// $grade = $_SESSION['GradTch'];
$zocodeu = $_SESSION['ZoneCodeU'];
$procodeu = $_SESSION['ProCodeU'];
$NIC = $_SESSION['NIC'];
$SchType = $_SESSION['SchType'];
$distcode = $_SESSION['DistCodeU'];

$sql = "SELECT CD_Zone.InstitutionName AS ZONENAME, * FROM TeacherMast 
    LEFT OUTER JOIN TeachingDetails ON TeacherMast.NIC = TeachingDetails.NIC
    INNER JOIN StaffServiceHistory ON StaffServiceHistory.ID = TeacherMast.CurServiceRef 
    INNER JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode 
    INNER JOIN CD_Zone ON CD_CensesNo.ZoneCode = CD_Zone.CenCode
    INNER JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode 
    INNER JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode 
    WHERE TeachingDetails.ID IS NULL 
    AND CD_CensesNo.SchoolType = '$SchType'";
    if($procodeu != ''){
        $sql .= " AND CD_Provinces.ProCode = '$procodeu'";
    }
    if($distcode != ''){
        $sql .= " AND CD_CensesNo.DistrictCode = '$distcode'";
    }
    if($zocodeu != ''){
        $sql .= " AND CD_CensesNo.ZoneCode = '$zocodeu'";
    }

$stmtres = $conn->query($sql);
while($stmtres->columnCount() === 0 && $stmtres->nextRowset()) {
    // Advance rowset until we get to a rowset with data
}
if($stmtres->columnCount() > 0) {
    function cleanData($str){
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
        
        if(strstr($str, '"')){ 
            $str = '"' . str_replace('"', '""', $str) . '"';
        }
    }
      
    $html = "";
    $html.="<html>";
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
    $html.="<body>";
    $html.="<div style=\"text-align:center; height:200px; width:auto;\">
        <p style=\"font-size:28px; font-weight:600;\">Ministry of Education Sri Lanka
        <br>Teacher Human Resource Management Portal - NEMIS </p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" 
        . date('Y-m-d H:i:s') . 
        "</p>
        </div>";
    if($SchType != null){
        $sql1 = "SELECT DISTINCT CD_CensesCategory.Category FROM CD_CensesNo INNER JOIN CD_CensesCategory ON CD_CensesNo.SchoolType = CD_CensesCategory.ID WHERE (CD_CensesNo.SchoolType = N'$SchType')";
        $stmt1 = $db->runMsSqlQuery($sql1);
        while ($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)) {
            $schType = $row1["Category"];
        }
    } else {
        $schType = "All";
    }
    if($procodeu != ''){
        $sql2 = "SELECT ProCode, Province FROM CD_Provinces WHERE (ProCode = N'$procodeu')";
        $stmt2 = $db->runMsSqlQuery($sql2);            while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
            $provinceName = $row2["Province"];
        }
    } else {
        $provinceName = "All";
    }
    if($distcode != ''){
        $sql3 = "SELECT DistName, DistCode FROM CD_Districts WHERE (DistCode = N'$distcode')";
        $stmt3 = $db->runMsSqlQuery($sql3);
        while ($row3 = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC)) {               
            $districtName = $row3["DistName"];
        }
    } else {
        $districtName = "All";
    }
    if($zocodeu != ''){
        $sql4 = "SELECT CenCode, InstitutionName AS zone FROM CD_Zone WHERE (CenCode = N'$zocodeu')";
        $stmt4 = $db->runMsSqlQuery($sql4);
        while ($row4 = sqlsrv_fetch_array($stmt4, SQLSRV_FETCH_ASSOC)) {
            $zoneName = $row4["zone"];
        }
    } else {
        $zoneName = "All";
    }

    $html.="<div style=\"height:135px; font-size:18px; font-weight:600; width:70%; float: left; clear: right;\">
          School Type :" . $schType . "</br> Province : " . $provinceName . "</br> District : " . $districtName . "</br> Zone : " . $zoneName . "</div>";
    $html .= "<table>";
    $i = 1;
    while ( $row = $stmtres->fetch( PDO::FETCH_ASSOC )) {
        $html .= "<tr>";
        $html .= "<td>" . $i . "</td>";
        $html .= "<td>" . $row['NIC'] . "</td>";
        $html .= "<td>" . $row['SurnameWithInitials'] . "</td>";
        $html .= "<td>" . $row['InstitutionName'] . "</td>";
        $html .= "<td>" . $row['ZONENAME'] . "</td>";
        // $html .= "<td>" . $row['NIC'] . "</td>";
        $html .= "</tr>";
        $i++;
    }
    $html .= "</table>";

    echo $html;
    exit;
    
}