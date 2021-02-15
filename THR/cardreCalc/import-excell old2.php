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

$sqltcr1 = "CREATE TABLE #temptable1(
	[CenCode] [nchar](10) NOT NULL,";
foreach ($codearray as $code){
  $sqlt .= '['.$code. '] [int], ';
}	
$sqltcr1 .= rtrim($sqlt, ", ");
$sqltcr1 .= " ) ";

$sqltcr2 = "CREATE TABLE #temptable2(
	[CenCode] [nchar](10) NOT NULL,";
foreach ($codearray as $code){
  $sqlt1 .= '['.$code. '1] [int], ';
}	
$sqltcr2 .= rtrim($sqlt1, ", ");
$sqltcr2 .= " ) ";

$sqltcr3 = "CREATE TABLE #temptable3(
	[CenCode] [nchar](10) NOT NULL,";
foreach ($codearray as $code){
  $sqlt2 .= '['.$code. '2] [int], ';
}	
$sqltcr3 .= rtrim($sqlt2, ", ");
$sqltcr3 .= " ) ";
// print_r($sqltcr1);
$stmtcr1 = $conn->query($sqltcr1);
$stmtcr2 = $conn->query($sqltcr2);
$stmtcr3 = $conn->query($sqltcr3);
// var_dump($sqltcr1);
$sqlt="";
$sqlt1="";
$sqlt2="";
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
    $sqltemp1 .=")) AS pivot_table";

$sqltemp2 = "SELECT * INTO #temppivot2 
FROM (
  SELECT SubCode + '1' AS SubCode, CenCode ,Excess
  FROM ExcessDeficit p
) t 
PIVOT(
    Sum(Excess) 
    FOR subcode IN (";
    foreach ($codearray as $code){
      $sqlt1 .= '['.$code. '1], ';
    }
    $sqltemp2 .= rtrim($sqlt1, ", ");
    $sqltemp2 .=")) AS pivot_table2";

$sqltemp3 ="SELECT * INTO #temppivot3 
FROM (
SELECT SubCode + '2' AS SubCode, CenCode, Deficit
FROM ExcessDeficit p
) t 
PIVOT(
    Sum(Deficit) 
    FOR subcode IN (";
    foreach ($codearray as $code){
      $sqlt2 .= '['.$code. '2], ';
    }
    $sqltemp3 .= rtrim($sqlt2, ", ");
    $sqltemp3 .=")) AS pivot_table3";

$sqlp = '';
$sqlres = "SELECT tp1.CenCode, "; 
foreach ($codearray as $code){
  // $sqlp .= "'tp1.".$code."' AS '".$code."', 'tp2.".$code."' AS '".$code."1', 'tp3.".$code."' AS '".$code."2', ";
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
ON tp1.CenCode = tp3.CenCode";

print_r($sqltemp1);
// $stmtmp1 = sqlsrv_query($conn,$sqltemp1);
// $stmtmp2 = sqlsrv_query($conn,$sqltemp2);
// $stmtmp3 = sqlsrv_query($conn,$sqltemp3);
// $stmtmp1 = $db->runMsSqlQuery($sqltemp1);
// $stmtmp2 = $db->runMsSqlQuery($sqltemp2);
// $stmtmp3 = $db->runMsSqlQuery($sqltemp3);
$stmtmp1 = $conn->query($sqltemp1);
$stmtmp2 = $conn->query($sqltemp2);
$stmtmp3 = $conn->query($sqltemp3);
$stmtmp1->nextRowset();
$stmtmp2->nextRowset();
$stmtmp3->nextRowset();
// print_r($stmtmp1);
if( $stmtmp1 === false || $stmtmp2 === false || $stmtmp3 === false ) {
  die( print_r(sqlsrv_errors(), true));
}else{
  // print_r($stmtmp1);
  // $stmtres = sqlsrv_query($conn,$sqlres);
  // $stmtres = $db->runMsSqlQuery($sqlres);
  $stmtres = $conn->query($sqlres);
  // print_r($stmtres);
  $stmtres->nextRowset();
  while ( $row = $stmtres->fetch( PDO::FETCH_ASSOC )) {
    print_r( $row );
  }
  if($stmtres === false){
    die( print_r(sqlsrv_errors(), true));
  }else{
    function cleanData($str){
      $str = preg_replace("/\t/", "\\t", $str);
      $str = preg_replace("/\r?\n/", "\\n", $str);
  
      if(strstr($str, '"')){ 
        $str = '"' . str_replace('"', '""', $str) . '"';
      }
    }

    // var_dump($list2);

    $html = "";
    $html.="<html>";
    // header("Content-type: text/ms-excel");
    // $filename = date('YmdHis') . ".xls";
    // header('Content-Disposition: attachment; filename=' . $filename);
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
    $list1 = array();
    $list2 = array();
    $list3 = array();
    foreach($codearray as $code){
      array_push($list1, $code);
      $code1 = $code . '1';
      array_push($list2, $code1);
      $code2 = $code . '2';
      array_push($list3, $code1);
    }

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
    // while ( $row = $stmtres->fetch( PDO::FETCH_ASSOC )) {
    //   var_dump($row);
    //   $html .= "<tr>";
    //   $html.= "<td>" . $i . "</td>";
    //   $html.= "<td>" . $row['CenCode'] . "</td>";
    //   foreach($list1 as $l1){
    //     $html.="<td>".$row[$l1]."</td>";
    //     unset($list1[0]);
    //   }
    //   foreach($list2 as $l2){
    //     $html.="<td>".$row[$l2]."</td>";
    //     unset($list2[0]);
    //   }
    //   foreach($list3 as $l3){
    //     $html.="<td>".$row[$l3]."</td>";
    //     unset($list3[0]);
    //   }
    //   $html .= "</tr>";
    //   $i++;
    // }
    echo $html;
    exit;
  }
}