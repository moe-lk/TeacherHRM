<?php

//$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
include "../connectionPDO.php";


$AppCatID = $_POST["AppCatID"];
$option = "<option>Select</option>";

// echo "<script>alert('".$AppCat."')</script>";
$sql = "SELECT * FROM CD_AppSubjects WHERE Category = '$AppCatID'";
foreach ($conn->query($sql) as $row) {
    $option .= "<option value=" . $row['ID'] . ">" . $row['SubjectName'] . "</option>";
}

echo json_encode($option);
?>