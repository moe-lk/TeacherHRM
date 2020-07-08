<?php
$html = ob_get_contents();
    $filename = date('YmdHis');

//save the html page in tmp folder
//file_put_contents("D:/downPDF/{$filename}.html", $html);
    file_put_contents("../PDFGenerater/tempFile/{$filename}.html", $html);

//Clean the output buffer and turn off output buffering
    ob_end_clean();

//convert HTML to PDF
//shell_exec("D:\WKPDF\wkhtmltopdf\wkhtmltopdf.exe -q D:/downPDF/{$filename}.html D:/downPDF/{$filename}.pdf");
    shell_exec("..\PDFGenerater\wkhtmltopdf\wkhtmltopdf.exe -q ../PDFGenerater/tempFile/{$filename}.html ../PDFGenerater/tempFile/{$filename}.pdf");

//if (file_exists("D:/downPDF/{$filename}.pdf")) {
    if (file_exists("../PDFGenerater/tempFile/{$filename}.pdf")) {
        header("Content-type:application/pdf");
        header("Content-Disposition:attachment;filename={$filename}.pdf");
        //echo file_get_contents("D:/downPDF/{$filename}.pdf");
        echo file_get_contents("../PDFGenerater/tempFile/{$filename}.pdf");
    } else {
        exit;
    }

?>