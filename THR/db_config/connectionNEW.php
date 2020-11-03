<?php
$serverName = "TYRFING";
$connectionInfo = array( "Database"=>"MOENational", "UID"=>"na", "PWD"=>"na1234");
$conn = sqlsrv_connect( $serverName, $connectionInfo);
if( $conn === false ) {
    die( print_r( sqlsrv_errors(), true ));
    // echo "<script LANGUAGE='JavaScript'>window.alert('Nope');</script>";
}
else{
    // echo "<script LANGUAGE='JavaScript'>window.alert('Succesfully Connected');</script>";
}

?>