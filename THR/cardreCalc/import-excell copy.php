<?PHP
  include '../db_config/DBManager.php';
  $db = new DBManager();
  include "../db_config/connectionNEW.php";
  session_start();
  ini_set("memory_limit", "2048M"); 

  $medium = $_SESSION['Medium'];
  $grade = $_SESSION['GradTch'];
  $zocodeu = $_SESSION['ZoneCodeU'];
  $procodeu = $_SESSION['ProCodeU'];
  $NIC = $_SESSION['NIC'];
  $medium = $_SESSION['Medium'];
  $grade = $_SESSION['GradTch'];

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
$sqltemp1 = "SELECT * INTO #temppivot1 
FROM   
(
    SELECT 
        SubCode, ExcDef, CenCode
    FROM 
        ExcessDeficit p
) t 
PIVOT(
    Sum(ExcDef) 
    FOR subcode IN (
        [201] , [202] , [203] , [204] , [205] , [206] , 
		[207] , [208] , [209] , [210] , [211] , [212] , 
		[213] , [214] , [215] , [216] , [217] , [218] , 
		[219] , [220] , [221] , [222] , [223] , [224] , 
		[225] , [226] , [227] , [228] , [229] , [230] , 
		[231] , [232] , [233] , [234] , [235] , [236] , 
		[237] , [238] , [239] , [240] , [241] , [242] , 
		[243] , [244] , [245] , [246] , [247] , [248] , 
		[249] , [250] , [251]
		)
) AS pivot_table";

$sqltemp2 = "SELECT * INTO #temppivot2 
FROM   
(
    SELECT 
        SubCode, CenCode ,Excess
    FROM 
        ExcessDeficit p
) t 
PIVOT(
    Sum(Excess) 
    FOR subcode IN (
        [201] , [202] , [203] , [204] , [205] , [206] , 
		[207] , [208] , [209] , [210] , [211] , [212] , 
		[213] , [214] , [215] , [216] , [217] , [218] , 
		[219] , [220] , [221] , [222] , [223] , [224] , 
		[225] , [226] , [227] , [228] , [229] , [230] , 
		[231] , [232] , [233] , [234] , [235] , [236] , 
		[237] , [238] , [239] , [240] , [241] , [242] , 
		[243] , [244] , [245] , [246] , [247] , [248] , 
		[249] , [250] , [251]
		)
) AS pivot_table2";

$sqltemp3 ="SELECT * INTO #temppivot3 
FROM   
(
   SELECT 
       SubCode, CenCode, Deficit
   FROM 
       ExcessDeficit p
) t 
PIVOT(
   Sum(Deficit) 
   FOR subcode IN (
       [201] , [202] , [203] , [204] , [205] , [206] , 
   [207] , [208] , [209] , [210] , [211] , [212] , 
   [213] , [214] , [215] , [216] , [217] , [218] , 
   [219] , [220] , [221] , [222] , [223] , [224] , 
   [225] , [226] , [227] , [228] , [229] , [230] , 
   [231] , [232] , [233] , [234] , [235] , [236] , 
   [237] , [238] , [239] , [240] , [241] , [242] , 
   [243] , [244] , [245] , [246] , [247] , [248] , 
   [249] , [250] , [251]
   )
) AS pivot_table3";

$sqlres = "SELECT * FROM #temppivot1 
tp1 
INNER JOIN #temppivot2 
tp2 
ON tp1.CenCode = tp2.CenCode 
INNER JOIN #temppivot3 
tp3 
ON tp1.CenCode = tp3.CenCode";

$stmtmp1 = sqlsrv_query($conn,$sqltemp1);
$stmtmp2 = sqlsrv_query($conn,$sqltemp2);
$stmtmp3 = sqlsrv_query($conn,$sqltemp3);

// var_dump($stmtmp2);
if( $stmtmp1 === false || $stmtmp2 === false || $stmtmp3 === false ) {
  // var_dump($conn);
  die( print_r( sqlsrv_errors(), true));
}else{
  $stmtres = $db->runMsSqlQuery($sqlres);


$sqlsr ="";
foreach ($codearray as $sub){
  $sqlsr .= $sub.", ";
};
$sqlsr = rtrim($sqlsr, ", ");
// var_dump($sqlsr);

  
  function cleanData($str)
  {
    $str = preg_replace("/\t/", "\\t", $str);
    $str = preg_replace("/\r?\n/", "\\n", $str);
  
    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
  }
  
  $html = "";
  $html.="<html>";
  // filename for download
  header("Content-type: text/ms-excel");
  $filename = date('YmdHis') . ".xls";
  header('Content-Disposition: attachment; filename=' . $filename);
// print_r($sql1);
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
while ($row = sqlsrv_fetch_array($stmtres, SQLSRV_FETCH_ASSOC)) {
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
}
?>0772985867