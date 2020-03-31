<?php 
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();

$sqlServiceRef="SELECT [RequestID], Count([RequestID]) as TotalDownloaded
FROM TG_Approval
GROUP BY [RequestID]";
$x=0;
$stmtCAllready= $db->runMsSqlQuery($sqlServiceRef);
while ($rowABC = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC)){
	$RequestID= $rowABC['RequestID'];
	$TotalDownloaded= $rowABC['TotalDownloaded'];
	if($TotalDownloaded>3){
		echo "$RequestID - $TotalDownloaded - ";
		echo $x++;echo "<br>";
	}
}

echo "end";
echo $x;
//$rowAllready = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC);
		



?>