<?PHP
include '../db_config/DBManager.php';
$db = new DBManager();
// include "../db_config/connectionNEW.php";
include "../db_config/connectionPDO.php";
session_start();
ini_set("memory_limit", "2048M"); 

$medium = $_SESSION['Medium'];
$grade = $_SESSION['GradTch'];
$zocodeu = $_SESSION['ZoneCodeU'];
$procodeu = $_SESSION['ProCodeU'];
$NIC = $_SESSION['NIC'];
$medium = $_SESSION['Medium'];
$grade = $_SESSION['GradTch'];
$SchType = $_SESSION['SchType'];
$distcode = $_SESSION['DistCodeU'];

$subsql = "SELECT * FROM CD_TeachSubjects Where Code = $grade";
$stmt = $db->runMsSqlQuery($subsql);
$codearray = array();

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
  $itm2 = $row['ID']; 
  array_push($codearray, $itm2);
}

$sqlt="";
$sqlt1="";
$sqlt2="";
$sqldrop = "IF object_id('#temppivot1') is not null
Begin
	DROP table #temppivot1
end;  
IF object_id('#temppivot2') is not null
Begin
	DROP table #temppivot2
end ;
IF object_id('#temppivot3') is not null
Begin
	DROP table #temppivot3
end ;";
$sqltemp1 = "SELECT * INTO #temppivot1 
FROM   
(
  SELECT SubCode, ExcDef, CenCode
  FROM ExcessDeficit p
) t 
PIVOT(
    Sum(ExcDef) 
    FOR subcode IN (";
    foreach ($codearray as $code){
      $sqlt .= '['.$code. '], ';
    }
    $sqltemp1 .= rtrim($sqlt, ", ");
    $sqltemp1 .=")) AS pivot_table; ";

$sqltemp2 = "SELECT * INTO #temppivot2 
FROM (
  SELECT SubCode AS SubCode, CenCode ,Excess
  FROM ExcessDeficit p
) t 
PIVOT(
    Sum(Excess) 
    FOR subcode IN (";
    foreach ($codearray as $code){
      $sqlt1 .= '['.$code. '], ';
    }
    $sqltemp2 .= rtrim($sqlt1, ", ");
    $sqltemp2 .=")) AS pivot_table2; ";

$sqltemp3 ="SELECT * INTO #temppivot3 
FROM (
SELECT SubCode AS SubCode, CenCode, Deficit
FROM ExcessDeficit p
) t 
PIVOT(
    Sum(Deficit) 
    FOR subcode IN (";
    foreach ($codearray as $code){
      $sqlt2 .= '['.$code. '], ';
    }
    $sqltemp3 .= rtrim($sqlt2, ", ");
    $sqltemp3 .=")) AS pivot_table3; ";

$sqlp = '';
$sqlres = "SELECT tp1.CenCode, "; 
foreach ($codearray as $code){
  $sqlp .= "tp1.[".$code."] AS S".$code.", tp2.[".$code."] AS S".$code."1, tp3.[".$code."] AS S".$code."2, ";

}
$sqlres .= rtrim($sqlp, ", ");
$sqlres .=" FROM #temppivot1 
tp1 
INNER JOIN #temppivot2 
tp2 
ON tp1.CenCode = tp2.CenCode 
INNER JOIN #temppivot3 
tp3 
ON tp1.CenCode = tp3.CenCode; ";

$sqlall = $sqldrop.$sqltemp1.$sqltemp2.$sqltemp3.$sqlres;

// print_r($sqlall);

$stmtres = $conn->query($sqlall);
while($stmtres->columnCount() === 0 && $stmtres->nextRowset()) {
  // Advance rowset until we get to a rowset with data
}
if($stmtres->columnCount() > 0) {
  // while ( $row = $stmtres->fetch( PDO::FETCH_ASSOC )) {
  // var_dump( $row );
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
    $stmt2 = $db->runMsSqlQuery($sql2);
    while ($row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)) {
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

  $html.="<table width=\"1100\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">";
  $html.="<tr>";
  $i = 1;
  $html .= "<td>No</td>";
  $html .= "<td>Censes No.</td>";
  foreach($codearray as $sub){
    $html.= "<td colspan=3>" . $sub . "</td>";
  }
  $html .= "</tr><tr>";
  $html .= "<td></td>";
  $html .= "<td></td>";
  
  foreach($codearray as $sub){
    $html.= "<td>Approved</td>";
    $html.= "<td>Available</td>";
    $html.= "<td>ExcessDiff</td>";
  }
  $html .= "</tr>";
  while ( $row = $stmtres->fetch( PDO::FETCH_ASSOC )) {
    // var_dump($row);
    $html .= "<tr>";
    $html.= "<td>" . $i . "</td>";
    $html.= "<td>" . $row['CenCode'] . "</td>";
    foreach($codearray as $code){
      $html .= "<td>". $row['S'.$code.'1']."</td>";
      $html .= "<td>". $row['S'.$code.'2']."</td>";
      $html .= "<td>". $row['S'.$code]."</td>";
    } 
    $html .= "</tr>";
    $i++;
  }
  echo $html;
  exit;
}