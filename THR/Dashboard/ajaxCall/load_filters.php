<?php

include '../../db_config/DBManager.php';
$db = new DBManager();


$cat = $_REQUEST['cat'];





if ($cat == 'natureofwork') {
    $html = "";
    $html .= "<div>";
    $html .= "<label  class=\"labelTxt\" style=\"margin-left:22px;\"><strong>Subject :</strong></label>";
    $html .= "<label  class=\"labelTxt\" style=\"margin-left:22px;\"><strong>Position :</strong></label>";
    $html .= "</div>";


    // start main div
    $html .= "<div>";
    $html .= "<div class=\"divSimple\" style=\"margin-left:22px;\">";
    $html .= "<select style=\"width:260px;\" id=\"cmbSubject\" name=\"cmbSubject\" >";
    $html .= "<option value=\"\">All</option>";
    $sql = "SELECT
CD_Subject.SubCode,
CD_Subject.SubjectName
FROM
CD_Subject";
    $stmt = $db->runMsSqlQuery($sql);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $SubCode = $row['SubCode'];
        $SubjectName = $row['SubjectName'];
        $html .= "<option value=\"$SubCode\">$SubjectName</option>";
    }


    $html .= "</select>";

    $html .= "</div>";
    
    
    $html .= "<div class=\"divSimple\" style=\"margin-left:22px;\">";
    $html .= "<select style=\"width:260px;\" id=\"cmbPosition\" name=\"cmbPosition\" >";
    $html .= "<option value=\"\">All</option>";
    $sql = "SELECT
CD_Positions.Code,
CD_Positions.PositionName
FROM
CD_Positions";
    $stmt = $db->runMsSqlQuery($sql);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $poCode = $row['Code'];
        $PositionName = $row['PositionName'];
        $html .= "<option value=\"$poCode\">$PositionName</option>";
    }


    $html .= "</select>";

    $html .= "</div>";
    
    // end main div
    $html .= "</div>";


  //  $html .= "<input type=\"button\" class=\"report\" name=\"genPDF\" id=\"genPDF\" value=\"Print Report\" onClick=\"submitForm('report');\"/>";

    echo $html;
}
?>