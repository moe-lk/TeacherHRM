<?php 
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();

$sql="UPDATE TG_EmployeeUpdatePersInfo SET TeacherMastID='50829' WHERE ID='5626'";
$db->runMsSqlQuery($sql); echo "hi";
?>
<table width="900" cellspacing="1" cellpadding="1">
  <tr>
    <td>#</td>
    <td>#</td>
    <td>NIC</td>
    <td>Zone</td>
    <td>Remark</td>
  </tr>

<?php
$j=0;
$arrNIC=array(); 
$zSql="SELECT     TG_EmployeeUpdatePersInfo.ID, TG_EmployeeUpdatePersInfo.NIC, TG_EmployeeUpdatePersInfo.TeacherMastID, TG_EmployeeUpdatePersInfo.PermResiID, 
                      TG_EmployeeUpdatePersInfo.CurrResID, TG_EmployeeUpdatePersInfo.dDateTime, TG_EmployeeUpdatePersInfo.ZoneCode, 
                      TG_EmployeeUpdatePersInfo.IsApproved, TG_EmployeeUpdatePersInfo.ApproveComment, TG_EmployeeUpdatePersInfo.ApproveDate, 
                      TG_EmployeeUpdatePersInfo.ApprovedBy, TG_EmployeeUpdatePersInfo.UpdateBy, UP_TeacherMast.ID AS Expr1
FROM         TG_EmployeeUpdatePersInfo INNER JOIN
                      UP_TeacherMast ON TG_EmployeeUpdatePersInfo.NIC = UP_TeacherMast.NIC
WHERE     (TG_EmployeeUpdatePersInfo.TeacherMastID > '60000') AND (TG_EmployeeUpdatePersInfo.IsApproved <> 'Y')";
  
  $stmtZ= $db->runMsSqlQuery($zSql);
  while ($rowZ = sqlsrv_fetch_array($stmtZ, SQLSRV_FETCH_ASSOC)){
	  $j++;
  		$NIC = trim($rowZ['NIC']);
		$Expr1 = trim($rowZ['Expr1']);
		$idF = trim($rowZ['ID']);
		$arrNIC[]=$NIC;
		//$sql = "UPDATE TG_EmployeeUpdatePersInfo SET TeacherMastID='$Expr1' WHERE ID='$idF'";//NIC='$NICUser' and 
       // $db->runMsSqlQuery($sql); echo "hi";
		 ?>
		
  <!--<tr>
    <td><?php echo $j;?></td>
    <td><?php echo $i;?></td>
    <td><?php echo $NIC ?></td>
    <td><?php echo $InstitutionName?> - <?php echo $DistName?></td>
    <td>&nbsp;</td>
  </tr>-->


		<?php //exit();
  }

print_r($arrNIC);

for($i=0;$i<count($arrNIC);$i++){
	echo $NICus=$arrNIC[$i];echo "<br>";
	$zSql="SELECT ID FROM UP_TeacherMast WHERE NIC='$NICus'";
 	$stmtZ= $db->runMsSqlQuery($zSql);
	$rowZ = sqlsrv_fetch_array($stmtZ, SQLSRV_FETCH_ASSOC);
	$Expr1 = trim($rowZ['ID']);
 	$sql = "UPDATE TG_EmployeeUpdatePersInfo SET TeacherMastID='$Expr1' WHERE NIC='$NICus'";//NIC='$NICUser' and 
    $db->runMsSqlQuery($sql);
	//exit();
}


?>
</table>