<?PHP
  include '../db_config/DBManager.php';
  $db = new DBManager();
  include "../db_config/connectionNEW.php";
  
  $sql = "SELECT ExcessDeficit.CenCode
  ,CD_CensesNo.InstitutionName
    ,[SubCode]
    ,[SecCode]
    ,[Medium]
    ,[ExcDef] 
FROM ExcessDeficit 
INNER JOIN CD_CensesNo ON CD_CensesNo.CenCode = ExcessDeficit.CenCode";
  $stmt = $db->runMsSqlQuery($sql);

  function cleanData(&$str)
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
  $html.="<td width=\"5%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">No</td> ";
  $html.="<td width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">Censes No.</td> ";
  $html.="<td width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">School Name</td> ";
  $html.="<td width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">Grade Span</td> ";
  $html.="<td width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">Subject</td> ";
  $html.="<td width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">Medium</td>";
  $html.="<td width=\"10%\" align=\"center\" style=\"border-bottom: 1px solid #000000; border-left: 1px solid #000000; border-top: 1px solid #000000; border-right:1px solid #000000;\">Excess Deficit</td></tr>";
  $i = 1;
  // $flag = false;
//   var_dump($data);
//   foreach($data as $row) {
  while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    // if(!$flag) {
    //   // display field/column names as first row
    //   echo implode("\t", array_keys($row)) . "\r\n";
    //   $flag = true;
    // }
    // array_walk($row, __NAMESPACE__ . '\cleanData');
    // echo implode("\t", array_values($row)) . "\r\n";
    $cencode = $row['CenCode'];
    $SchName = $row['InstitutionName'];
    $subject = $row['SubCode'];
    $gradespan = $row['SecCode'];
    $medium = $row['Medium'];
    $Excdef = $row['ExcDef'];

    $html.= "<tr>";
    $html.= "<td>" . $i . "</td>";
    $html.= "<td>" . $cencode . "</td>";
    $html.= "<td>" . $SchName . "</td>";  
    $html.= "<td>" . $gradespan . "</td>";
    $html.= "<td>" . $subject . "</td>";
    $html.= "<td>" . $medium . "</td>";
    $html.= "<td>" . $Excdef . "</td>";
    $i++;
  }

  echo $html;
  exit;
?>