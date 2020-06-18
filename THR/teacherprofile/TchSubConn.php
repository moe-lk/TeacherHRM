<?php

//$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn = new PDO("sqlsrv:Server= DESKTOP-OESJB7N\SQLEXPRESS;Database=MOENational", "sa", "na1234");


$GradTchID = $_POST["GradTchID"];
$option = null;

// echo "<script>alert('".$AppCat."')</script>";
$sql = "SELECT * FROM CD_TeachSubjects WHERE Code = '$GradTchID'";
foreach ($conn->query($sql) as $row) {
    $option .= "<option value=" . $row['ID'] . ">" . $row['SubjectName'] . "</option>";
}

echo json_encode($option);
?>