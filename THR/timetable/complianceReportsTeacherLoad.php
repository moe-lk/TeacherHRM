<?php 
$tpePage=$_REQUEST['tpep'];
//$tpePage="Print";
if($tpePage=='Print'){
?>
<title>Print Compliance Reports</title>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>

<body onLoad="javascript:print();">

<?php 
//if($tpePage=='Print'){
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();

include('js/common.js.php'); 
include('js/ajaxloadpage.js.php'); 
include('myfunction.php');
}
$loggedSchool=$_SESSION['loggedSchool'];
$params2 = array(
	array($loggedSchool, SQLSRV_PARAM_IN)
);
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="center" valign="top"><br />
      <table width="652" border="0" cellspacing="1" cellpadding="0" bgcolor="#666666">
        <tr>
          <td bgcolor="#FFFFFF"><table width="650" border="0" cellspacing="2" cellpadding="0">
            <tr>
              <td bgcolor="#FFFFFF"><table width="648" border="0" cellspacing="2" cellpadding="6" bgcolor="#666666">
                <tr>
                  <td bgcolor="#FFFFFF"><table width="640" border="0" align="center" cellpadding="0" cellspacing="0" style="font-family: Tahoma, Geneva, sans-serif; font-size: 10px; padding: 0; margin: 0;">
                   
                <tr>
                  <td colspan="2"><table width="100%" cellspacing="1" cellpadding="1" style="font-family: Tahoma, Geneva, sans-serif; font-size: 10px; padding: 0; margin: 0;">
                    <tr>
                      <td width="50%" align="center" style="font-size:14px; text-decoration:underline;">Compliance Report (Teacher) - <?php echo date('Y'); ?></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                    <td colspan="2">&nbsp;</td>
                  
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#999999"><table width="100%" cellspacing="1" cellpadding="0" style="font-family: Tahoma, Geneva, sans-serif; font-size: 12px; padding: 0; margin: 0;">
                      <tr>
                        <td width="4%" align="center" bgcolor="#CCCCCC" style="font-size:12px;"><strong>#</strong></td>
                        <td width="35%" align="center" bgcolor="#CCCCCC" style="font-size:12px;"><strong>Teacher Name </strong></td>
                        <td width="43%" bgcolor="#CCCCCC" style="font-size:12px;"><strong>Subject</strong></td>
                        <td width="18%" align="center" bgcolor="#CCCCCC" style="font-size:12px;"><strong>No. of Periods</strong></td>
                      </tr>
                      <?php 
					  $i=1;
					 								
						$sql22 = "{call SP_TG_LoadSencesTeachers( ?)}";
						$stmt22 = $db->runMsSqlQuery($sql22, $params2);                          
						while ($row2 = sqlsrv_fetch_array($stmt22, SQLSRV_FETCH_ASSOC)) {
						   $NIC=$row2['NIC'];
						   $TeacherName=$row2['TeacherName'];
						   $SubjectName=$row2['SubjectName'];
						   $TeachingType=$row2['TeachingType'];     
						
					  ?>
                      <tr>
                        <td align="center" valign="top" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td align="left" valign="top" bgcolor="#FFFFFF">&nbsp;&nbsp;<?php echo $TeacherName;?></td>
                        <td colspan="2" bgcolor="#808080"><table width="100%" cellspacing="1" cellpadding="0" style="font-family: Tahoma, Geneva, sans-serif; font-size: 12px; padding: 0; margin: 0;">
                          <?php 
						  $subSql="SELECT        DISTINCT(TG_SchoolTimeTable.SubjectID)
FROM            TG_SchoolTimeTable INNER JOIN
                         TeacherMast ON TG_SchoolTimeTable.TeacherID = TeacherMast.NIC
WHERE        (TG_SchoolTimeTable.SchoolID = '$loggedSchool') AND (TG_SchoolTimeTable.TeacherID = '$NIC')";
						  $stmtSub = $db->runMsSqlQuery($subSql);
						  $TotaRowsSub=$db->rowCount($subSql);
						while ($rowSub= sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
							
							$SubCode=$rowSub['SubjectID'];
							$subjName=getFieldActValue("SubjectName","CD_Subject","SubCode",$SubCode);
							if($subjName){
							$totSql="SELECT FieldID From TG_SchoolTimeTable where SchoolID='$loggedSchool' and TeacherID='$NIC' and SubjectID='$SubCode' GROUP BY FieldID";//PeriodNumber";
							
							$TotaRows=$db->rowCount($totSql);
							
						  ?>
                          <tr>
                            <td width="71%" bgcolor="#FFFFFF">&nbsp;&nbsp;<?php echo $subjName; ?></td>
                            <td width="29%" align="right" bgcolor="#FFFFFF">&nbsp;&nbsp;<?php echo $TotaRows; ?>&nbsp;&nbsp;</td>
                          </tr>
                          <?php }}?>
                          <?php if($TotaRowsSub==0){?>
                          <tr>
                            <td width="71%" bgcolor="#FFFFFF">&nbsp;&nbsp;</td>
                            <td width="29%" align="right" bgcolor="#FFFFFF">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                          </tr>
                         <?php }?>
                        </table></td>
                        </tr>
                      <?php }?>
                    </table></td>
          </tr>
                <tr>
                  <td width="59%">&nbsp;</td>
                  <td width="41%">&nbsp;</td>
                </tr>
              
                  </table></td>
                </tr>
              </table></td>
            </tr>
          </table></td>
        </tr>
    </table></td>
  </tr>
</table>