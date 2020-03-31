<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
//$nicNO='722381718V';
$sqlChkNo = "SELECT id FROM TG_Request_Approve WHERE (ApproveUserNominatorNIC = N'$nicNO') AND (RequestType = 'RequestTeacherTraining')";
	$totNominiRow = $db->rowCount($sqlChkNo);
	if($totNominiRow>0){
	  $tblField =  'ApproveUserNominatorNIC';
	}else{
	  $tblField = 'ApprovelUserNIC';
	}
$approvSql="SELECT        TG_Request_Approve.id AS ReqAppID, TG_Request_Approve.RequestID, TG_Request_Approve.RequestType, TG_Request_Approve.RequestUserNIC, 
                         TG_Request_Approve.ApprovelUserNIC, TG_Request_Approve.ApproveUserNominatorNIC, TG_Request_Approve.ApproveProcessOrder, 
                         TG_Request_Approve.ApprovalProcessID, TG_Request_Approve.ApprovedStatus, TG_Request_Approve.DateTime, TG_Request_Approve.Remarks, 
                         TeacherMast.SurnameWithInitials, CD_CensesNo.InstitutionName, TG_TeacherRequestTraining.Title, TG_TeacherRequestTraining.Description, 
                         CONVERT(varchar(20),TG_TeacherRequestTraining.StartDate,121) AS StartDate,CONVERT(varchar(20),TG_TeacherRequestTraining.EndDate,121) AS EndDate, TG_TeacherRequestTraining.Venue, TG_TeacherRequestTraining.NoofSessions
FROM            TG_Request_Approve INNER JOIN
                         TG_TeacherRequestTraining ON TG_Request_Approve.RequestID = TG_TeacherRequestTraining.ID INNER JOIN
                         TeacherMast ON TG_TeacherRequestTraining.NIC = TeacherMast.NIC INNER JOIN
                         CD_CensesNo ON TG_TeacherRequestTraining.SchoolID = CD_CensesNo.CenCode
WHERE        (TG_Request_Approve.RequestType = 'RequestTeacherTraining') AND (TG_Request_Approve.$tblField = N'$nicNO') AND (TG_Request_Approve.ApprovedStatus = N'P')";

$TotaRows=$db->rowCount($approvSql);

$uploadPath="../approval/trainingrequestfiles/";

?>

    <form method="post" action="trainingAction.php" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if($msg!='' || $_SESSION['success_update']!=''){//if( || $_SESSION['success_update']!=''){  ?>   
          <div class="mcib_middle_full">
          <div class="form_error"><?php echo $msg; echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
        </div>
        <?php }?> 
        <div style="width:738px; float:left;">
		<?php if($id==''){?>
        <table width="100%" cellpadding="0" cellspacing="0">
       
        	<tr>
                  <td><?php echo $TotaRows ?> Record(s) found.</td>
                  <td>&nbsp;</td>
                </tr>
			  <tr>
                  <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="3%" height="25" align="center" bgcolor="#999999">#</td>
                      <td width="28%" align="center" bgcolor="#999999">Teacher Name</td>
                      <td width="19%" align="center" bgcolor="#999999">Training</td>
                      <td width="11%" align="center" bgcolor="#999999">Strat Date</td>
                      <td width="11%" align="center" bgcolor="#999999">End Date</td>
                      <td width="9%" align="center" bgcolor="#999999">Action</td>
                    </tr>
                    <?php 
					$i=1;
					$stmt = $db->runMsSqlQuery($approvSql);
                     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						
						$RequestID=$row['RequestID'];//SurnameWithInitials,InstitutionName,EndDate,StartDate,Description,Title
					?>
                    <tr>
                      <td height="20" bgcolor="#FFFFFF"><?php echo $i++; ?></td>
                      <td bgcolor="#FFFFFF"><?php echo $row['SurnameWithInitials']; ?><br /><?php echo $row['InstitutionName']; ?></td>
                      <td bgcolor="#FFFFFF"><?php echo $row['Title']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['StartDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['EndDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><a href="<?php echo $ttle ?>-<?php echo $pageid ?>--<?php echo $RequestID ?>.html"><img src="images/more_info.png" /></a></td>
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
			
$countTotal="SELECT        TeacherMast.SurnameWithInitials, CD_CensesNo.InstitutionName, TG_TeacherRequestTraining.Title, TG_TeacherRequestTraining.Description, 
                         TG_TeacherRequestTraining.StartDate, TG_TeacherRequestTraining.EndDate, TG_TeacherRequestTraining.Venue, TG_TeacherRequestTraining.NoofSessions, TG_TeacherRequestTraining.Reference, TG_TeacherRequestTraining.ApplyDate
FROM            TG_TeacherRequestTraining INNER JOIN
                         TeacherMast ON TG_TeacherRequestTraining.NIC = TeacherMast.NIC INNER JOIN
                         CD_CensesNo ON TG_TeacherRequestTraining.SchoolID = CD_CensesNo.CenCode
						 where TG_TeacherRequestTraining.ID='$id'";//$NICUser
						 
$stmt = $db->runMsSqlQuery($countTotal);
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$SurnameWithInitials=addslashes($row['SurnameWithInitials']);
		$InstitutionName=addslashes($row['InstitutionName']);
		$Title=addslashes($row['Title']);
		$Description=addslashes($row['Description']);
		$StartDate=$row['StartDate'];
		$EndDate=$row['EndDate'];
		$Venue=addslashes($row['Venue']);
		$Reference=$row['Reference'];
		$ApplyDate=$row['ApplyDate'];
	}
			
			?>
        <table width="100%" cellpadding="0" cellspacing="0">
        
			  <tr>
			    <td colspan="2" ><table width="100%" cellspacing="1" cellpadding="1">
			      <tr>
			        <td>Teacher Name</td>
			        <td>:</td>
			        <td colspan="4"><?php echo $SurnameWithInitials ?></td>
		          </tr>
			      <tr>
			        <td>Working School</td>
			        <td>:</td>
			        <td colspan="4"><?php echo $InstitutionName ?></td>
		          </tr>
			      <tr>
			        <td>Training</td>
			        <td>:</td>
			        <td colspan="4"><?php echo $Title ?></td>
		          </tr>
			      <tr>
			        <td>Venue</td>
			        <td>:</td>
			        <td colspan="4"><?php echo $Venue ?></td>
		          </tr>
			      <tr>
			        <td width="15%">Start Date</td>
			        <td width="2%">:</td>
			        <td width="33%"><?php echo $StartDate ?></td>
			        <td width="13%">End Date</td>
			        <td width="2%">:</td>
			        <td width="35%"><?php echo $EndDate ?></td>
		          </tr>
			      <tr>
			        <td>Reference :</td>
			        <td>:</td>
			        <td><?php if($Reference!=''){?><a href="<?php echo $uploadPath."".$Reference; ?>" target="_blank">View File</a><?php }else{ echo "File not found.";} ?></td>
			        <td>Apply Date</td>
			        <td>:</td>
			        <td><?php echo $ApplyDate ?></td>
		          </tr>
			      <tr>
			        <td>Details</td>
			        <td>:</td>
			        <td colspan="4"><?php echo $Description ?></td>
		          </tr>
			      <tr>
			        <td align="left" valign="top">Sessions </td>
			        <td valign="top">:</td>
			        <td colspan="4"><table width="100%" cellspacing="1" cellpadding="1">
			          <tr>
			            <td width="2%" align="center">#</td>
			            <td width="30%" align="center">Task</td>
			            <td width="17%" align="center">Description</td>
			            <td width="17%" align="center">No. of Hours</td>
			            <td width="17%" align="center">Strat Date</td>
			            <td width="17%" align="center">End Date</td>
		              </tr>
                      <?php 
					$i=1;
					$approvalListSql="SELECT ID,Title,CONVERT(varchar(20),StartDate,121) AS StartDate, CONVERT(varchar(20),EndDate,121) AS EndDate,NoofHours,Description,MonitoredBy,MonitoredDate,MonitorRemarks,CONVERT(varchar(20),ApplyDate,121) AS ApplyDate  FROM $tblName where ID='$id'";//where SchoolID='$loggedSchool'
					
					$stmtAppS = $db->runMsSqlQuery($approvalListSql);
					while ($rowApp = sqlsrv_fetch_array($stmtAppS, SQLSRV_FETCH_ASSOC)) {
						$NoofHours=trim($rowApp['NoofHours']);
						$Title=stripslashes($rowApp['Title']);
						$StartDate=stripslashes($rowApp['StartDate']);
						$EndDate=stripslashes($rowApp['EndDate']);
						$Description=stripslashes($rowApp['Description']);
						$MonitoredBy=stripslashes($rowApp['MonitoredBy']);
						$TitleTrainig=stripslashes($rowApp['MonitoredDate']);
						$MonitorRemarks=stripslashes($rowApp['MonitorRemarks']);
						$ApplyDate=stripslashes($rowApp['ApplyDate']);
					
					  ?>
			          <tr>
			            <td><?php echo $i++; ?></td>
			            <td><?php echo $Title ?></td>
			            <td><?php echo $Description ?></td>
			            <td><?php echo $NoofHours ?></td>
			            <td><?php echo $StartDate ?></td>
			            <td><?php echo $EndDate ?></td>
		              </tr>
                      <?php }?>
		            </table></td>
		          </tr>
		        </table></td>
	      </tr>
			  <tr>
			    <td colspan="2" >&nbsp;</td>
	      </tr>
          <?php 
   $i=1;
   $sqlLeave="SELECT        TG_Request_Approve.id AS ReqAppID, TG_Request_Approve.RequestUserNIC, TG_Request_Approve.ApprovelUserNIC, TG_Request_Approve.ApproveUserNominatorNIC, 
                         TG_Request_Approve.ApproveProcessOrder, TG_Request_Approve.ApprovedStatus, TG_Request_Approve.DateTime, TG_Request_Approve.Remarks, 
                         TeacherMast.SurnameWithInitials, TG_ApprovalProcess.ApproveAccessRoleName
FROM            TG_Request_Approve INNER JOIN
                         TeacherMast ON TG_Request_Approve.ApprovelUserNIC = TeacherMast.NIC INNER JOIN
                         TG_ApprovalProcess ON TG_Request_Approve.ApprovalProcessID = TG_ApprovalProcess.ID
WHERE        (TG_Request_Approve.RequestType = 'RequestTeacherTraining') AND (TG_Request_Approve.RequestID = '$id')
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
                      <input type="hidden" value="requestTeacherTraining" name="cat" />
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