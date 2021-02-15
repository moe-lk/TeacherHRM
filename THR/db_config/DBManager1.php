<?php 
$server = "DESKTOP-OESJB7N\SQLEXPRESS"; 
$connectionInfo = array("UID" => "sa", "PWD" => "sa1234", "Database"=>"MOENational");
$conn= sqlsrv_connect($server, $connectionInfo);
?>