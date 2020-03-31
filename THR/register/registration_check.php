<?php 
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();
?>

<table width="100%" border="1" cellpadding="0" cellspacing="0">
  <tr>
    <td width="6%">ID</td>
    <td width="14%">NIC</td>
    <td colspan="2">TeacherMast</td>
    <td colspan="2">Service Current</td>
    <td colspan="2">Service First</td>
    <td colspan="2">Address His</td>
    <td width="8%">Zone</td>
    <td width="5%">Sts</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td width="8%" align="center">T</td>
    <td width="9%" align="center">O</td>
    <td width="9%" align="center">T</td>
    <td width="10%" align="center">O</td>
    <td width="8%" align="center">T</td>
    <td width="7%" align="center">O</td>
    <td width="8%" align="center">T</td>
    <td width="8%" align="center">O</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
<?php
$regQuerry="SELECT * FROM TG_EmployeeRegister where ID>0 ORDER BY IsApproved DESC";
//ID, NIC, TeacherMastID, ServisHistCurrentID, ServisHistFirstID, AddressHistID, dDateTime, ZoneCode, IsApproved, ApproveComment, ApproveDate, ApprovedBy, UpdateBy 
$stmt = $db->runMsSqlQuery($regQuerry);
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$IDR=trim($row['ID']);
	$NIC=trim($row['NIC']);
	$TeacherMastID=trim($row['TeacherMastID']);
	$ServisHistCurrentID=trim($row['ServisHistCurrentID']);
	$ServisHistFirstID=trim($row['ServisHistFirstID']);
	$AddressHistID=trim($row['AddressHistID']);
	$ZoneCode=trim($row['ZoneCode']);
	$IsApproved=trim($row['IsApproved']);
	$ApproveComment=trim($row['ApproveComment']);
	
	
	$tmast="SELECT ID FROM TeacherMast where NIC='$NIC'";
	$stmt2 = $db->runMsSqlQuery($tmast);
	$row2 = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC);
	$IDTmast=trim($row2['ID']);
	
	$TotaRows2=$db->rowCount($tmast);
	
	$shist="SELECT ID FROM StaffServiceHistory where NIC='$NIC' and ServiceRecTypeCode='NA01'";
	$stmt3 = $db->runMsSqlQuery($shist);
	$row3 = sqlsrv_fetch_array($stmt3, SQLSRV_FETCH_ASSOC);
	$IDShistF=trim($row3['ID']);
	
	$TotaRows3=$db->rowCount($shist);
	
	$chist="SELECT ID FROM StaffServiceHistory where NIC='$NIC' and ServiceRecTypeCode!='NA01'";
	$stmt4 = $db->runMsSqlQuery($chist);
	$row4 = sqlsrv_fetch_array($stmt4, SQLSRV_FETCH_ASSOC);
	$IDShistC=trim($row4['ID']);
	
	$TotaRows4=$db->rowCount($chist);
	
	$ahist="SELECT ID FROM StaffAddrHistory where NIC='$NIC'";
	$stmt5 = $db->runMsSqlQuery($ahist);
	$row5 = sqlsrv_fetch_array($stmt5, SQLSRV_FETCH_ASSOC);
	$IDShistA=trim($row5['ID']);
	
	$TotaRows5=$db->rowCount($ahist);
	
	?>
	
  <tr>
    <td><?php echo $IDR ?></td>
    <td><?php echo $NIC ?></td>
    <td><?php echo $TeacherMastID ?></td>
    <td><?php echo $IDTmast ?>(<?php echo $TotaRows2 ?>)</td>
    <td><?php echo $ServisHistCurrentID ?></td>
    <td><?php echo $IDShistC ?>(<?php echo $TotaRows4 ?>)</td>
    <td><?php echo $ServisHistFirstID ?></td>
    <td><?php echo $IDShistF ?>(<?php echo $TotaRows3 ?>)</td>
    <td><?php echo $AddressHistID ?></td>
    <td><?php echo $IDShistA ?>(<?php echo $TotaRows5 ?>)</td>
    <td><?php echo $ZoneCode ?></td>
    <td><?php echo $IsApproved ?></td>
  </tr>
 <?php }?>
</table>

	
	
