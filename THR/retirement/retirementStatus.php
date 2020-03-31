<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$msg="";


?>


<div class="main_content_inner_block">
    <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
       
        <table width="945" cellpadding="0" cellspacing="0">
			  <tr>
                  <td colspan="2" ><table width="100%" cellspacing="1" cellpadding="1">
                    <?php 
                    $sql = "SELECT TG_Request_Approve.RequestType, TG_Request_Approve.RequestUserNIC, TG_Request_Approve.ApprovelUserNIC, TG_Request_Approve.ApproveProcessOrder, TG_Request_Approve.ApprovedStatus, TG_Request_Approve.DateTime, TG_Request_Approve.Remarks, TG_ApprovalProcess.ApproveAccessRoleName, CD_Title.TitleName, TeacherMast.SurnameWithInitials
FROM CD_Title INNER JOIN
 TeacherMast ON CD_Title.TitleCode = TeacherMast.Title RIGHT OUTER JOIN
 TG_Request_Approve INNER JOIN
 TG_ApprovalProcess ON TG_Request_Approve.ApprovalProcessID = TG_ApprovalProcess.ID ON TeacherMast.NIC = TG_Request_Approve.ApprovelUserNIC
WHERE (TG_Request_Approve.RequestUserNIC = N'$nicNO')
ORDER BY TG_Request_Approve.ApproveProcessOrder";
                    $stmt = $db->runMsSqlQuery($sql);
                    while ($row = sqlsrv_fetch_array($stmt)) {
                        $approveStatus = trim($row["ApprovedStatus"]);
                        $approveStatusLabel = "";
                        if($approveStatus=='P' || $approveStatus=="")
                            $approveStatusLabel = "Pending";
                        else if($approveStatus=="A")
                            $approveStatusLabel = "Approved";
                        $remarks = trim($row["Remarks"]);
                        if($remarks=="")
                            $remarks = "--";
                    
                    ?>
                    <tr>
                      <td width="3%" height="30" align="center"><img src="images/re_enter.png" width="10" height="10" /></td>
                      <td colspan="5"><?php echo $row["ApproveAccessRoleName"]; ?> - <?php echo $row["TitleName"]." ".$row["SurnameWithInitials"]; ?></td>
                    </tr>
                    <tr>
                      <td height="20">&nbsp;</td>
                      <td width="3%">&nbsp;</td>
                      <td width="11%" valign="top">Approval Status :</td>
                      <td width="19%" valign="top"><?php echo $approveStatusLabel; ?></td>
                      <td width="8%" valign="top">Remarks :</td>
                      <td width="56%" valign="top"><?php echo $remarks; ?></td>
                    </tr>
                    
                    <?php
                    }
                    ?>
                  </table></td>
          </tr>
                <tr>
                  <td width="56%">&nbsp;</td>
                  <td width="44%">&nbsp;</td>
                </tr>
              </table>
    
    
    </form>
</div>