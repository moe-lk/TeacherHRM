<?php

//$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
include "../db_config/connectionPDO.php";


$SubjCatCodeID = $_POST["SubjCatCodeID"];
$option = "<option>Select</option>";

// echo "<script>alert('".$SubjCatCode."')</script>";
$sql = "SELECT * FROM CD_TeachSubjects WHERE Code = '$SubjCatCodeID'";
foreach ($conn->query($sql) as $row) {
    $option .= "<option value=" . $row['ID'] . ">" . $row['SubjectName'] . "</option>";
}
echo json_encode($option);
?>