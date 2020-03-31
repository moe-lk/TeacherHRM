<?php
$serverName = "SRVEMISDB\SQLEXPRESS";
$connectionInfo = array( "Database"=>"MOENational", "UID"=>"wamplogin", "PWD"=>"HOsd@0117213133" );
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn === false ) {
     die( print_r( sqlsrv_errors(), true));
}

$sql = "INSERT INTO TG_SchoolGradeMaster (SchoolID, GradeID) VALUES ('SC05428', '7')";
$params = array(1, "some data");

$stmt = sqlsrv_query( $conn, $sql, $params);
if( $stmt === false ) {
     die( print_r( sqlsrv_errors(), true));
}
echo "_______________________";
$sqlSel = "SELECT * From TG_SchoolGradeMaster";
$stmt2 = sqlsrv_query( $conn, $sqlSel, $params);
if( $stmt2 === false ) {
     die( print_r( sqlsrv_errors(), true));
}
?>
