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
  $subarray = array();
  $codearray = array();
  while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $itm = $row['SubjectName']."-". $row['ID']; 
    array_push($subarray, $itm);
    $itm2 = $row['ID']; 
    array_push($codearray, $itm2);
  }

// print_r($codearray);
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
WHERE A1.SecCode = '$grade'
AND A2.Medium = '$medium'";

$stmt2 = $db->runMsSqlQuery($sqltemp);

while ($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)){
  // echo $row;
}

  $sqlsr ="";
  $sqlsr1 ="";
  $sqlsr2 ="";

$sql = "SELECT
*
FROM
(
  SELECT 
   school_id,
   subject_id,
   subject_id+'(1)' As subject_id1,
   subject_id+'(2)' As subject_id2,
   AvailableTch, 
   ApprCardre, 
   excess_dificit
  FROM #tempcardre1$NIC
 ) AS P
 PIVOT
 (
    SUM(apprcardre) FOR subject_id IN (";
    foreach($codearray as $sub){
      // $sub .= '2';
      $sqlsr .= " [$sub] ,";
      
    }
    $sql .= rtrim($sqlsr, ',');
    $sql .= ")
 ) AS pv1
 PIVOT
 (
    SUM(availableTch) FOR subject_id1 IN (";
    foreach($codearray as $sub){
      $sub .= '1';
      $sqlsr1 .= " [$sub] ,";
      
    }
    $sql .= rtrim($sqlsr1, ',');
    $sql .= ")
 ) AS pv2
 PIVOT
 (
    SUM(excess_dificit) FOR subject_id2 IN (";
    foreach($codearray as $sub){
      $sub .= '2';
      $sqlsr2 .= " [$sub] ,";
      
    }
    $sql .= rtrim($sqlsr2, ',');
     $sql .= ")
 ) AS pv3";

  
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

  
  // $sqlpr = array();
  // $sqlpr1 = array();
  // $sqlpr2 = array();
  // foreach($subarray as $sub){
  //   $sub .= '1';
  //   array_push($sqlpr, $sub);
  // }
  // foreach($subarray as $sub){
  //   $sub .= '2';
  //   array_push($sqlpr1, $sub);
  // } 
  // foreach($subarray as $sub){
  //   $sub .= '3';
  //   array_push($sqlpr2, $sub);
  // }
  // var_dump($srarr);
  // var_dump($srarr1);
  // var_dump($srarr2);

  
  $html = "";
  $html.="<html>";
  // filename for download
  header("Content-type: text/ms-excel");
  $filename = date('YmdHis') . ".xls";
  header('Content-Disposition: attachment; filename=' . $filename);
// print_r($sql);
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
  // $html .= "<td>School Name</td>";
  // $html .= "<td>Subject No.</td>";
  foreach($subarray as $sub)
  {
    $html.= "<td colspan=3>" . $sub . "</td>";
  }
  $html .= "</tr><tr>";
  $html .= "<td></td>";
  $html .= "<td></td>";
  // $html .= "<td></td>";
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
    // $html.= "<td>" . $row['school_name'] . "</td>";  

    foreach($codearray as $arrsub)
    {
      $html.= "<td>" . $row[$arrsub] . "</td>";    
    }
    foreach($codearray as $arrsub)
    {
      $html.= "<td>" . $row[$arrsub] . "</td>";
    }
    foreach($codearray as $arrsub)
    {
      $html.= "<td>" . $row[$arrsub] . "</td>";
    }
    $html .= "</tr>";

    // $html.= "<td>" . $row['Primary Common'] . "</td>";
    // $html.= "<td>" . $row['Agro & Food Technology'] . "</td>";
    // $html.= "<td>" . $row['Practical technical skills'] . "</td>";


    if(!$flag) {
      // display field/column names as first row
      echo implode("\t", array_keys($row)) . "\r\n";
      $flag = true;
    }
    array_walk($row, __NAMESPACE__ . '\cleanData');
    echo implode("\t", array_values($row)) . "\r\n";
    $i++;
  }

  $sqlclean = "DROP TABLE #tempcardre1$NIC"; 
  $stmtclean = $db->runMsSqlQuery($sqlclean);
  sqlsrv_fetch_array($stmtclean, SQLSRV_FETCH_ASSOC);

  echo $html;
  exit;

?>