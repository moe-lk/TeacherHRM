<?php 
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();
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
$zSql="SELECT [CenCode]
      ,[InstitutionName]
      ,[DistrictCode]
  FROM [MOENational].[dbo].[CD_Zone]";
  
  $stmtZ= $db->runMsSqlQuery($zSql);
  while ($rowZ = sqlsrv_fetch_array($stmtZ, SQLSRV_FETCH_ASSOC)){
  		$Zone = trim($rowZ['CenCode']);
		$InstitutionName = trim($rowZ['InstitutionName']);
		$DistrictCode = trim($rowZ['DistrictCode']);
		
		//$reqTab="SELECT [ID],[NIC] FROM [dbo].[TG_EmployeeRegister] WHERE TeacherMastID<'10000' and ZoneCode='$Zone' and IsApproved='N'";
		$reqTab="SELECT [ID],[NIC] FROM [dbo].[TG_EmployeeRegister] WHERE IsApproved='R'";
		$stmtE= $db->runMsSqlQuery($reqTab);
		$i=0;
		while ($rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC)){		
			$i++; $j++;
			$NIC = $rowE['NIC'];
			
			$sqlDist="SELECT [DistCode]
				  ,[DistName]      
			  FROM [MOENational].[dbo].[CD_Districts] WHERE DistCode='$DistrictCode'";
			  $stmtDist= $db->runMsSqlQuery($sqlDist);
			  $rowD = sqlsrv_fetch_array($stmtDist, SQLSRV_FETCH_ASSOC);
			  $DistName=$rowD['DistName'];
			
			$queryTmpDel = "DELETE FROM ArchiveUP_TeacherMast WHERE NIC='$NIC'";
			$db->runMsSqlQuery($queryTmpDel);
			
			$queryTmpDel = "DELETE FROM ArchiveUP_StaffAddrHistory WHERE NIC='$NIC'";
			$db->runMsSqlQuery($queryTmpDel);
			
			$queryTmpDel = "DELETE FROM ArchiveUP_StaffServiceHistory WHERE NIC='$NIC'";
			$db->runMsSqlQuery($queryTmpDel);
			
			$queryTmpDel = "DELETE FROM ArchiveUP_StaffAssignDetails WHERE NIC='$NIC'";
			$db->runMsSqlQuery($queryTmpDel);
			
			$queryTmpDel = "DELETE FROM TG_EmployeeRegister WHERE NIC='$NIC'";
			$db->runMsSqlQuery($queryTmpDel); ?>
		
  <tr>
    <td><?php echo $j;?></td>
    <td><?php echo $i;?></td>
    <td><?php echo $NIC ?></td>
    <td><?php echo $InstitutionName?> - <?php echo $DistName?></td>
    <td>&nbsp;</td>
  </tr>


		<?php }
  }


?>
</table>