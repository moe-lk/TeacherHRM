<?php $tpe="PDF";
if($tpe=='PDF') {
	//$month=$_REQUEST['rpt_month'];
	require_once("../PDF/dompdf_config.inc.php");	
	/* $file="printProfile2.php";
	$fp = fopen($file, "r");	
	while ($line = fread($fp, 8192)) {	
		$html_content.= $line;		
	} */ 
	$html_content="test";
	//fclose($fp);	
	$dompdf = new DOMPDF();
	$dompdf->load_html($html_content);
	$dompdf->set_paper("a4", "landscape");
	$dompdf->render();
	$flnme=$main_head."pdf";
	$dompdf->stream($flnme);
	
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>test
</body>
</html>