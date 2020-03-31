<?php
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php 
$sqlD="SELECT        TeacherMast.ID, TeacherMast.SurnameWithInitials, TeacherMast.Title, TeacherMast.MobileTel, TeacherMast.GenderCode, CD_CensesNo.InstitutionName, 
                         TeacherSubject.SubjectCode, CD_Districts.DistName
FROM            TeacherMast INNER JOIN
                         TeacherSubject ON TeacherMast.ID = TeacherSubject.ID INNER JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID INNER JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode INNER JOIN
                         CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
WHERE        (TeacherSubject.SubjectCode = 'SB1905' and TeacherMast.MobileTel!='' and TeacherMast.MobileTel!='N/A' and CD_Districts.DistCode='D09') order by CD_CensesNo.InstitutionName";


?>
<table width="960" cellspacing="0" cellpadding="0">
  <tr>
    <td bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
  <tr>
    <td width="8%" align="center" bgcolor="#999999">#</td>
    <td width="28%" align="center" bgcolor="#999999">Name</td>
    <td width="26%" align="center" bgcolor="#999999">School</td>
    <td width="16%" align="center" bgcolor="#999999">District</td>
    <td width="15%" align="center" bgcolor="#999999">Mobile</td>
    <td width="7%" align="center" bgcolor="#999999">Gender</td>
  </tr>
  <?php 
  $stmt = $db->runMsSqlQuery($sqlD);
  $i=0;
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$i++;	
  ?>
  <tr>
    <td bgcolor="#FFFFFF"><?php echo $i; ?></td>
    <td bgcolor="#FFFFFF"><?php echo $row['SurnameWithInitials'] ?></td>
    <td bgcolor="#FFFFFF"><?php echo $row['InstitutionName'] ?></td>
    <td bgcolor="#FFFFFF"><?php echo $row['DistName'] ?></td>
    <td bgcolor="#FFFFFF"><?php echo $row['MobileTel'] ?></td>
    <td bgcolor="#FFFFFF"><?php echo $row['GenderCode'] ?></td>
  </tr>
 <?php }?>
</table></td>
  </tr>
</table>

</body>
</html>