<?php 
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();
$SchoolID=12345;
$GradeID=6;
$ClassID=9;
$SubjectID=6;
$FieldID=6;
$params1 = array(
	array($SchoolID, SQLSRV_PARAM_IN),
    array($GradeID, SQLSRV_PARAM_IN),
    array($ClassID, SQLSRV_PARAM_IN),
    array($SubjectID, SQLSRV_PARAM_IN)	
);

$sql = "{call SP_TG_Test( ?, ?, ?, ?)}";
                      $stmt = $db->runMsSqlQuery($sql, $params1);

?>