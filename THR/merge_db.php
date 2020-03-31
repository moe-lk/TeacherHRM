<?php include("db_include_tg/config.php");
include("db_include_tg/class_db.php");
include("db_include_tg/common.php");
include("db_include_tg/my_functions.php");
$dbObj=new mySqlDB;
$dbObj->connect($hostname,$username,$password);
$dbObj->dBSelect($dbname);

$fieldMErge=array("SchID");//
$selDataMerge=$dbObj->querySelect("stafftb",$fieldMErge,array("SchID"),"A","NICno!=''");
$totalStaff=count($selDataMerge);
//echo "Total"; echo echo "<br>";

$fieldMErge=array("SchID","NICno","PermanantAddr","TempAddr","PrivateContactNo");//
$sqlStr="(CurPosition='Assistant Principal') or (CurPosition='Deputy Principal') or (CurPosition='Performing Principal') or (CurPosition='Principal') or (CurPosition='SectionalHead') or (CurPosition='Taacher') or (CurPosition='Teacher') or (CurPosition='Teacher (ENGLISH MED') or (CurPosition='Teacher Educator') or (CurPosition='TeacherAssistant')  or (CurPosition='TeacherLibrarian')";

//$sqlStr="NICno='918460187V'";

$selDataMerge=$dbObj->querySelect("stafftb",$fieldMErge,array("SchID"),"A",$sqlStr);
$teachersC=count($selDataMerge);
//echo "Teachers"; echo $teachersC=count($selDataMerge);echo "<br>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Merge Database</title>
</head>

<body>
<table width="100%" cellspacing="1" cellpadding="1">
  <tr>
    <td width="8%">&nbsp;</td>
    <td width="33%">Total Staff : <?php echo $totalStaff ?> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; Teachers : <?php echo $teachersC ?></td>
    <td width="8%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href="update_nothern_teacherMast_db.php" target="_blank">Teacher Master</a></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href="update_nothern_addressHis_db.php" target="_blank">Address History</a></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href="update_nothern_db_merge_address.php" target="_blank">Merge TeacherMast &amp; AddressHis</a></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href="update_nothern_service_histry_db.php" target="_blank">Service History</a></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href="update_nothern_db_merge_service_his.php" target="_blank">Merge Current Service Ref.</a></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><a href="update_nothern_db_password.php" target="_blank">Update Password</a></td>
    <td>&nbsp;</td>
  </tr>
</table>
</body>
</html>