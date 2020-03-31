<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
//$nicNO='722381718V';
$approvSql="SELECT        TG_Request_Approve.RequestType, TG_Request_Approve.ApprovelUserNIC, TG_Request_Approve.ApprovedStatus, CONVERT(varchar(20),TG_TeacherQualification.ApplyDate,121) AS ApplyDate
                         ,CONVERT(varchar(20),TG_TeacherQualification.EffectiveDate,121) AS EffectiveDate, TG_TeacherQualification.Description, TG_Request_Approve.id, TG_TeacherQualification.Reference, 
                         TG_TeacherQualification.ID AS Expr1, TeacherMast.SurnameWithInitials, CD_QualificationCategory.Description AS Expr2,TG_TeacherQualification.QCode, CD_CensesNo.InstitutionName
FROM            TG_Request_Approve INNER JOIN
                         TG_TeacherQualification ON TG_Request_Approve.RequestID = TG_TeacherQualification.ID INNER JOIN
                         TeacherMast ON TG_TeacherQualification.NIC = TeacherMast.NIC INNER JOIN
                         CD_QualificationCategory ON TG_TeacherQualification.QCode = CD_QualificationCategory.Code INNER JOIN
                         CD_CensesNo ON TG_TeacherQualification.SchoolID = CD_CensesNo.CenCode
WHERE        (TG_Request_Approve.RequestType = 'TeacherQualification') AND (TG_Request_Approve.ApprovelUserNIC = N'$nicNO') AND 
                         (TG_Request_Approve.ApprovedStatus = N'P')";

$TotaRows=$db->rowCount($approvSql);
?>


<div class="main_content_inner_block">
    <form method="post" action="qualificationAction.php" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if($msg!='' || $_SESSION['success_update']!=''){//if( || $_SESSION['success_update']!=''){  ?>   
   	  <div class="mcib_middle1">
          <div class="mcib_middle_full">
          <div class="form_error"><?php echo $msg; echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
        </div>
        <?php }?> 
		<?php if($id==''){?>
        <table width="945" cellpadding="0" cellspacing="0">
       
        	<tr>
                  <td><?php echo $TotaRows ?> Record(s) found.</td>
                  <td>&nbsp;</td>
                </tr>
			  <tr>
                  <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="3%" height="25" align="center" bgcolor="#999999">#</td>
                      <td width="28%" align="center" bgcolor="#999999">Employee Details</td>
                      <td width="19%" align="center" bgcolor="#999999">Qualification Category</td>
                      <td width="11%" align="center" bgcolor="#999999">Apply Date</td>
                      <td width="11%" align="center" bgcolor="#999999">Effective Date</td>
                      <td width="9%" align="center" bgcolor="#999999">Action</td>
                    </tr>
                    <?php 
					$i=1;
					$stmt = $db->runMsSqlQuery($approvSql);
                     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						
						$RequestID=$row['Expr1'];
					?>
                    <tr>
                      <td height="20" bgcolor="#FFFFFF"><?php echo $i++; ?></td>
                      <td bgcolor="#FFFFFF"><?php echo $row['SurnameWithInitials']; ?> (<?php echo $row['InstitutionName']; ?>)</td>
                      <td bgcolor="#FFFFFF"><?php echo $row['QCode']; ?> - <?php echo $row['Expr2']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['ApplyDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['EffectiveDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><a href="teacherQualification-12--<?php echo $RequestID ?>.html"><img src="images/more_info.png" /></a></td>
                    </tr>
                   <?php }?>
                  </table></td>
          </tr>
         
                <tr>
                  <td width="56%">&nbsp;</td>
                  <td width="44%">&nbsp;</td>
                </tr>
          
              </table> <?php }?>
              
        <?php if($id!=''){
			
$countTotal="SELECT        TG_TeacherQualification.QCode, TeacherMast.SurnameWithInitials, CD_CensesNo.InstitutionName, CD_QualificationCategory.Description AS Expr1, 
                         TG_TeacherQualification.Description, CONVERT(varchar(20),TG_TeacherQualification.EffectiveDate,121) AS EffectiveDate, TG_TeacherQualification.Reference, CONVERT(varchar(20),TG_TeacherQualification.ApplyDate,121) AS ApplyDate
FROM            TG_TeacherQualification INNER JOIN
                         TeacherMast ON TG_TeacherQualification.NIC = TeacherMast.NIC INNER JOIN
                         CD_CensesNo ON TG_TeacherQualification.SchoolID = CD_CensesNo.CenCode INNER JOIN
                         CD_QualificationCategory ON TG_TeacherQualification.QCode = CD_QualificationCategory.Code
WHERE        (TG_TeacherQualification.ID = '$id')";
						 
$stmt = $db->runMsSqlQuery($countTotal);
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$ApplyDate=$row['ApplyDate'];
		$EffectiveDate=$row['EffectiveDate'];
		$InstitutionName=trim($row['InstitutionName']);
		$SurnameWithInitials=$row['SurnameWithInitials'];
		$Description=$row['Description'];
		$QCode=$row['QCode'];
		$Expr1=$row['Expr1'];
		$Reference=$row['Reference'];
}
			?>
        <table width="945" cellpadding="0" cellspacing="0">
        
			  <tr>
			    <td colspan="2" ><table width="100%" cellspacing="1" cellpadding="1">
			      <tr>
			        <td>Name</td>
			        <td>:</td>
			        <td><?php echo $SurnameWithInitials; ?></td>
			        <td>School </td>
			        <td>:</td>
			        <td><?php echo $InstitutionName; ?></td>
		          </tr>
			      <tr>
			        <td width="15%">Apply Date</td>
			        <td width="2%">:</td>
			        <td width="33%"><?php echo $ApplyDate; ?></td>
			        <td width="13%">Effective Date</td>
			        <td width="2%">:</td>
			        <td width="35%"><?php echo $EffectiveDate; ?></td>
		          </tr>
			      <tr>
			        <td>Qualification Type</td>
			        <td>:</td>
			        <td colspan="4"><?php echo "$QCode - $Expr1"; ?></td>
		          </tr>
			      <tr>
			        <td>Details</td>
			        <td>:</td>
			        <td colspan="4"><?php echo $Description; ?></td>
		          </tr>
			      <tr>
			        <td>Reference </td>
			        <td>:</td>
			        <td colspan="4">&nbsp;</td>
		          </tr>
		        </table></td>
	      </tr>
			  <tr>
			    <td colspan="2" >&nbsp;</td>
	      </tr>
          <tr>
			    <td colspan="2" ><span style="font-size:20px; font-weight:bold">Approvals</span></td>
	      </tr>
         <tr>
                        <td height="1" colspan="2" bgcolor="#CCCCCC" ></td>
                  </tr>
          <?php 
   $i=1;
   $sqlLeave="SELECT        TG_Request_Approve.id AS ReqAppID, TG_Request_Approve.RequestUserNIC, TG_Request_Approve.ApprovelUserNIC, TG_Request_Approve.ApproveUserNominatorNIC, 
                         TG_Request_Approve.ApproveProcessOrder, TG_Request_Approve.ApprovedStatus, TG_Request_Approve.DateTime, TG_Request_Approve.Remarks, 
                         TeacherMast.SurnameWithInitials, TG_ApprovalProcess.ApproveAccessRoleName
FROM            TG_Request_Approve INNER JOIN
                         TeacherMast ON TG_Request_Approve.ApprovelUserNIC = TeacherMast.NIC INNER JOIN
                         TG_ApprovalProcess ON TG_Request_Approve.ApprovalProcessID = TG_ApprovalProcess.ID
WHERE        (TG_Request_Approve.RequestType = 'TeacherQualification') AND (TG_Request_Approve.RequestID = '$id')
ORDER BY TG_Request_Approve.ApproveProcessOrder";
$TotaRows=$db->rowCount($sqlLeave);
   $stmt = $db->runMsSqlQuery($sqlLeave);
                            while ($rowas = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					  
					  //$Expr1=$row['id'];
					  $ApproveAccessRoleName=trim($rowas['ApproveAccessRoleName']);
					  $SurnameWithInitials=trim($rowas['SurnameWithInitials']);
					  $Remarks=trim($rowas['Remarks']);
					  $ApprovelUserNIC=trim($rowas['ApprovelUserNIC']);
					  $ReqAppID=$rowas['ReqAppID'];
					  $ApproveProcessOrder=$rowas['ApproveProcessOrder'];
					  $statName="";
					  
							$ApprovedStatus=trim($rowas['ApprovedStatus']);//echo "hi";
							//if($ApprovedStatus=='P')
							if($ApprovedStatus=='P' || $ApprovedStatus==''){
								$statName="Pending";
							}else if($ApprovedStatus=='A'){
								$statName="Approved";
							}else if($ApprovedStatus=='R'){
								$statName="Rejected";
							}
							
						//}//echo $statName;
					  ?>
			  <tr>
                  <td colspan="2" ><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="2%" height="30" align="center"><img src="images/re_enter.png" width="10" height="10" /></td>
                      <td colspan="6"><?php echo $ApproveAccessRoleName ?> - <?php echo $SurnameWithInitials ?></td>
                    </tr>
                    <tr>
                      <td height="20">&nbsp;</td>
                      <td width="2%">&nbsp;</td>
                      <td width="16%" valign="top">Approvel Status :<?php //echo $ApprovelUserNIC;echo $nicNO ?></td>
                      <td width="24%" valign="top"><?php 
                      if($ApprovelUserNIC==$nicNO){?>
                        <select name="ApprovedStatus" class="select2a_new" id="ApprovedStatus">
                        <option value="P">Pending</option>
                        <option value="A">Approve</option>
                        <option value="R">Reject</option>
                      </select>
                      <?php }else{
                       echo $statName;
                      }?></td>
                      <td width="9%" valign="top">Remarks :</td>
                      <td width="18%" valign="top"><?php if($ApprovelUserNIC==$nicNO){?><textarea name="Remarks" cols="45" rows="5" class="textarea1" id="Remarks"></textarea><?php }else{
                       echo $Remarks;
                      }?></td>
                      <td width="29%" valign="bottom">
                      <?php if($ApprovelUserNIC==$nicNO){?>
                      <input type="hidden" value="<?php echo $ReqAppID ?>" name="ReqAppID" id="ReqAppID" />
                      <input type="hidden" value="<?php echo $id ?>" name="approveForID" id="approveForID" />
                      <input type="hidden" value="teacherQualification" name="cat" />
                      <input type="hidden" value="<?php echo $ApproveProcessOrder ?>" name="ApproveProcessOrder" />
                      <input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/submit.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /><?php }?></td>
                    </tr>
                  </table></td>
          </tr>
          
                <tr>
                  <td width="56%">&nbsp;</td>
                  <td width="44%">&nbsp;</td>
                </tr>
                <?php }?>
              </table>        
        <?php }?>
              
    </div>
    
    </form>
</div>