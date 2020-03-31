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

<body onload="javascript:print();">

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
$loggedSchool=$_SESSION['loggedSchool']
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
                      <td width="50%" align="center" style="font-size:14px; text-decoration:underline;">Compliance Report (Class) - <?php echo date('Y'); ?></td>
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
                        <td width="18%" align="center" bgcolor="#CCCCCC" style="font-size:12px;"><strong>Grade</strong></td>
                        <td width="60%" bgcolor="#CCCCCC" style="font-size:12px;"><strong>Subject</strong></td>
                        <td width="18%" align="center" bgcolor="#CCCCCC" style="font-size:12px;"><strong>No. of Periods</strong></td>
                      </tr>
                      <?php 
					  $gradeSql="SELECT        TG_SchoolGrade.GradeTitle, TG_SchoolGradeMaster.ID, TG_SchoolClassStructure.ClassID, TG_SchoolClassStructure.ID AS Expr1
FROM            TG_SchoolGradeMaster INNER JOIN
                         TG_SchoolGrade ON TG_SchoolGradeMaster.GradeID = TG_SchoolGrade.ID INNER JOIN
                         TG_SchoolClassStructure ON TG_SchoolGradeMaster.SchoolID = TG_SchoolClassStructure.SchoolID AND 
                         TG_SchoolGradeMaster.ID = TG_SchoolClassStructure.GradeID
WHERE        (TG_SchoolGradeMaster.SchoolID = '$loggedSchool')
ORDER BY TG_SchoolGrade.GradeTitle";
					  $i=1;
					  $stmtd = $db->runMsSqlQuery($gradeSql);
						while ($rowd = sqlsrv_fetch_array($stmtd, SQLSRV_FETCH_ASSOC)) {
							$GradeTitle=$rowd['GradeTitle'];
							$gardeID=$rowd['ID'];  
							$ClassID=$rowd['ClassID'];
							$ClassStructID=$rowd['Expr1'];          
						
					  ?>
                      <tr>
                        <td align="center" valign="top" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td align="center" valign="top" bgcolor="#FFFFFF"><?php echo "$GradeTitle - $ClassID";?></td>
                        <td colspan="2" bgcolor="#808080"><table width="100%" cellspacing="1" cellpadding="0" style="font-family: Tahoma, Geneva, sans-serif; font-size: 12px; padding: 0; margin: 0;">
                          <?php 
						  $subSql="SELECT        CD_Subject.SubjectName, CD_Subject.SubCode
FROM            TG_SchoolSubjectMaster INNER JOIN
                         CD_Subject ON TG_SchoolSubjectMaster.SubjectID = CD_Subject.SubCode
						 Where TG_SchoolSubjectMaster.SchoolID='$loggedSchool' and TG_SchoolSubjectMaster.GradeID='$gardeID' order by CD_Subject.SubjectName";
						  $stmtSub = $db->runMsSqlQuery($subSql);
						while ($rowSub= sqlsrv_fetch_array($stmtSub, SQLSRV_FETCH_ASSOC)) {
							
							$SubCode=$rowSub['SubCode'];
							
							$totSql="SELECT * From TG_SchoolTimeTable where SchoolID='$loggedSchool' and GradeID='$gardeID' and ClassID='$ClassStructID' and SubjectID='$SubCode'";
							
							$TotaRows=$db->rowCount($totSql);
							
						  ?>
                          <tr>
                            <td width="77%" bgcolor="#FFFFFF">&nbsp;&nbsp;<?php echo $SubjectName=$rowSub['SubjectName']; ?></td>
                            <td width="23%" align="right" bgcolor="#FFFFFF">&nbsp;&nbsp;<?php echo $TotaRows; ?>&nbsp;&nbsp;</td>
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