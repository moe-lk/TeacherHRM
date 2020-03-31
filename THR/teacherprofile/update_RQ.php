<?php 
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();

/* $sql="UPDATE TG_EmployeeUpdatePersInfo SET TeacherMastID='50829' WHERE ID='5626'";
$db->runMsSqlQuery($sql); */ echo "hi";
$j=0;
$zSql="SELECT ID
  FROM UP_StaffServiceHistory
  order by LastUpdate ASC";
  
  $stmtZ= $db->runMsSqlQuery($zSql);
  while ($rowZ = sqlsrv_fetch_array($stmtZ, SQLSRV_FETCH_ASSOC)){
	  
  		echo $ID = trim($rowZ['ID']);
		echo "__";
		
		$countTotal="SELECT ID FROM TG_Approval where RequestType='ServiceUpdate' and RequestID='$ID'";
		$TotaRows=$db->rowCount($countTotal);
		if($TotaRows>0){
			echo $TotaRows;
			 $j++;
			 
			 $DateTime=date('Y-m-d H:i:s');
			$queryMainSave = "INSERT INTO TG_Approval			   (RequestType,RequestID,ApproveInstCode,ApproveDesignationCode,ApproveDesignationNominiCode,ApprovedStatus,ApprovedByNIC,DateTime,Remarks)
				 VALUES
					   ('ServiceUpdate','$ID','','','','RQ','','$DateTime','')";
					//$db->runMsSqlQuery($queryMainSave);	
			 $db->runMsSqlQuery($queryMainSave);
			 
		}
		echo "<br>";

  }
  
  echo $j;
?>