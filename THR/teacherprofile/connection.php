<?php
$conn = new PDO("sqlsrv:Server= DESKTOP-OESJB7N\SQLEXPRESS;Database=MOENational", "sa", "na1234");

if( $conn === false ) {
    die( print_r( sqlsrv_errors(), true ));
}
?>