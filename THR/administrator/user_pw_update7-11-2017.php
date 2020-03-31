<?php 
	include '../db_config/DBManager.php';
	$db = new DBManager();
	
	$timezone = "Asia/Colombo";
	if(function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="css/emis.css" rel="stylesheet" type="text/css">
        <link href="../css/mStyle.css" rel="stylesheet" type="text/css" />
        <link href="css/category_tab.css" rel="stylesheet" type="text/css" />
        <link href="../cms/css/main_menu1.css" rel="stylesheet" type="text/css" />
        <link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
        <link href="../cms/css/grid_style.css" rel="stylesheet" type="text/css" />
        <link href="../cms/css/flexigrid.css" rel="stylesheet" type="text/css"/>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>User List</title>
</head>

<body>
 <?php 
  $sql="SELECT [NICNo]
      ,[CurPassword]
      ,[LastUpdate]
      ,[AccessRole]
      ,[AccessLevel]
  FROM [MOENational].[dbo].[Passwords2]";
	$stmt = $db->runMsSqlQuery($sql);
	$i=0;
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$NICNo=trim($row['NICNo']);
		$CurPassword=trim($row['CurPassword']);
		
		$passwordMD5=md5($CurPassword);
		$sqlInsertTTApp="UPDATE Passwords2 SET CurPassword='$passwordMD5' WHERE NICNo='$NICNo'";
			   
		$db->runMsSqlQuery($sqlInsertTTApp);
	}
	
	echo "Complete";exit();
  ?>
<table width="100%" cellspacing="1" cellpadding="1">
  <tr>
    <td width="3%" align="center">#</td>
    <td width="34%">NIC</td>
    <td width="63%">Password</td>
  </tr>
  <?php 
  $sql="SELECT [NICNo]
      ,[CurPassword]
      ,[LastUpdate]
      ,[AccessRole]
      ,[AccessLevel]
  FROM [MOENational].[dbo].[Passwords]";
	$stmt = $db->runMsSqlQuery($sql);
	$i=0;
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$NICNo=trim($row['NICNo']);
		$CurPassword=trim($row['CurPassword']);
		
		$passwordMD5=md5($CurPassword);
		$sqlInsertTTApp="UPDATE Passwords SET CurPassword='$passwordMD5' WHERE NICNo='$NICNo'";
			   
		$db->runMsSqlQuery($sqlInsertTTApp);
				
  ?>
  <tr>
    <td><?php echo $i++; ?></td>
    <td><?php echo $NICNo ?></td>
    <td><?php echo $CurPassword ?></td>
  </tr>
  <?php }?>
</table>

</body>
</html>