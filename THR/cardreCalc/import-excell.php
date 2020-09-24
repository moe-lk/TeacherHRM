<?PHP
  include '../db_config/DBManager.php';
  $db = new DBManager();
  include "../db_config/connectionNEW.php";
  session_start();
  ini_set("memory_limit", "2048M"); 
  $medium = $_REQUEST['Medium'];
  $grade = $_REQUEST['GradTch'];

  $zocodeu = $_SESSION['ZoneCodeU'];
  $procodeu = $_SESSION['ProCodeU'];
  $NIC = $_SESSION['NIC'];

  $subsql = "SELECT * FROM CD_TeachSubjects Where Code = $grade";
  $stmt = $db->runMsSqlQuery($subsql);
  // var_dump($grade);

  // var_dump($_SESSION);
  $subarray =array();
  while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $itm = $row['SubjectName']."-". $row['ID']; 
    array_push($subarray, $itm);
  }
// print_r($subarray);
  // var_dump($_REQUEST);
$sqltemp = "SELECT A1.[ID] ,
A1.[CenCode] AS school_id ,
A1.[SubCode] AS subject_id ,
A1.[SecCode] ,A1.[Medium] ,
A1.[AvailableTch] ,
A2.[ApprCardre] ,
E1.[ExcDef] AS excess_dificit
INTO #tempcardre1$NIC
FROM AvailableTeachers AS A1
INNER JOIN ExcessDeficit AS E1
ON A1.SubCode = E1.SubCode
INNER JOIN ApprovedCardre AS A2 ON A1.SubCode = A2.SubCode
WHERE A1.[Medium] = '$medium'";

$stmt2 = $db->runMsSqlQuery($sqltemp);

while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)){
  // echo $row;
}

  $sqlsr ="";
  $sqlsr1 ="";
  $sqlsr2 ="";

$sql = "select * FROM
(
SELECT rc.school_id, rc.school_name, rc.subject_name, rc.ZoneCode, rc.SchoolType,
rc.subject_name+'(1)' AS subject_name1,
rc.subject_name+'(2)' AS subject_name2,
rd.excess_dificit,
rd.AvailableTch,
rd.ApprCardre
FROM
(
SELECT sch.CenCode AS school_id,
sch.InstitutionName AS school_name,
sch.ZoneCode AS ZoneCode,
sub.id AS subject_id,
sub.SubjectName AS subject_name,
sch.SchoolType AS SchoolType
FROM CD_CensesNo sch
CROSS JOIN CD_TeachSubjects sub ) rc
LEFT JOIN ( SELECT * FROM #tempcardre1$NIC
)
rd ON rc.school_id = rc.school_id AND rc.subject_id = rd.subject_id
INNER JOIN CD_Zone ON CD_Zone.CenCode = rc.ZoneCode
INNER JOIN CD_Districts ON CD_Zone.DistrictCode = CD_Districts.DistCode
INNER JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
WHERE rc.SchoolType = '1'";

if($_SESSION['ProCodeU'] != ''){
  $sql .= " AND CD_Provinces.Procode = '$procodeu' ";
}

if($_SESSION['ProCodeU'] != ''){
  $sql .= " AND rc.ZoneCode = '$zocodeu'";
}

$sql .= "
)
report
PIVOT (COUNT(excess_dificit)
FOR subject_name IN(";
  foreach($subarray as $sub){
    $sub .= '1';
    $sqlsr .= " [$sub] ,";
    
  }
  $sql .= rtrim($sqlsr, ',');
  $sql .= "))
AS Pivot_tbl

PIVOT (COUNT(AvailableTch)
FOR subject_name1 IN(";
  foreach($subarray as $sub){
    $sub .= '2';
    $sqlsr1 .= " [$sub] ,";
    
  }
  $sql .= rtrim($sqlsr1, ',');

  $sql .= "))
AS Pivot_tbl2

PIVOT (COUNT(ApprCardre)
FOR subject_name2 IN(";
foreach($subarray as $sub){
  $sub .= '3';
  $sqlsr2 .= " [$sub] ,";
  
}
$sql .= rtrim($sqlsr2, ',');

$sql .= "))
AS Pivot_tbl3";

// foreach($subarray as $sub){
//   $sub .= '1';
//   $sqlsr1 .= " [$sub] ,";

// }
// foreach($subarray as $sub){
//   $sub .= '2';
//   $sqlsr2 .= " [$sub] ,";

// }
  
  //stored procedure
  // $sql = "{call SP_TG_GetCardreFor_LooggedUser()}";

  // $result = $db->runMsSqlQueryForSP($sql, $params);
  // print_r($sql);
  $stmt = $db->runMsSqlQuery($sql);


  function cleanData($str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
  
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }
  $sqlpr = array();
  $sqlpr1 = array();
  $sqlpr2 = array();
  foreach($subarray as $sub){
    $sub .= '1';
    array_push($sqlpr, $sub);
  }
  foreach($subarray as $sub){
    $sub .= '2';
    array_push($sqlpr1, $sub);
  } 
  foreach($subarray as $sub){
    $sub .= '3';
    array_push($sqlpr2, $sub);
  }
  // var_dump($srarr);
  // var_dump($srarr1);
  // var_dump($srarr2);

  
  $html = "";
  $html.="<html>";
  // filename for download
  header("Content-type: text/ms-excel");
  $filename = date('YmdHis') . ".xls";
  header('Content-Disposition: attachment; filename=' . $filename);
  // var_dump($sql);
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
  $html.="<table width=\"1100\" border=\"0\" cellpadding=\"5\" cellspacing=\"0\">";
  $html.="<tr>";
  $i = 1;

  $html .= "<td>No</td>";
  $html .= "<td>Censes No.</td>";
  $html .= "<td>School Name</td>";
  // $html .= "<td>Subject No.</td>";
  foreach($subarray as $sub)
  {
    $html.= "<td colspan=3>" . $sub . "</td>";
  }
  $html .= "</tr><tr>";
  $html .= "<td></td>";
  $html .= "<td></td>";
  $html .= "<td></td>";
  // $html .= "<td></td>";
  foreach($subarray as $sub){

    $html.= "<td>Approved</td>";
    $html.= "<td>Available</td>";
    $html.= "<td>ExcessDiff</td>";
    // $html.= "<td>Deffecit</td>";

  }
  $html .= "</tr>";
  // $flag = false;
// print_r($sql);
//   fore(ach($data as $row) {
  while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $html.= "<tr>";
    $html.= "<td>" . $i . "</td>";
    $html.= "<td>" . $row['school_id'] . "</td>";
    $html.= "<td>" . $row['school_name'] . "</td>";  

    foreach($sqlpr as $arrsub)
    {
      $html.= "<td>" . $row[$arrsub] . "</td>";    
    }
    foreach($sqlpr1 as $arrsub)
    {
      $html.= "<td>" . $row[$arrsub] . "</td>";
    }
    foreach($sqlpr2 as $arrsub)
    {
      $html.= "<td>" . $row[$arrsub] . "</td>";
    }
    $html .= "<tr>";

    // $html.= "<td>" . $row['Primary Common'] . "</td>";
    // $html.= "<td>" . $row['Agro & Food Technology'] . "</td>";
    // $html.= "<td>" . $row['Practical technical skills'] . "</td>";


    // if(!$flag) {
    //   // display field/column names as first row
    //   echo implode("\t", array_keys($row)) . "\r\n";
    //   $flag = true;
    // }
    // array_walk($row, __NAMESPACE__ . '\cleanData');
    // echo implode("\t", array_values($row)) . "\r\n";
    $i++;
  }
  $sqlclean = "DROP TABLE #tempcardre1$NIC"; 
  $stmtclean = $db->runMsSqlQuery($sqlclean);
  sqlsrv_fetch_array($stmtclean, SQLSRV_FETCH_ASSOC);

  echo $html;
  exit;

?>