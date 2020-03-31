<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
//$nicNO='722381718V';
	$sqlChkNo = "SELECT id FROM TG_Request_Approve WHERE (ApproveUserNominatorNIC = N'$nicNO') AND (RequestType = 'TransferTeacherNational')";
	$totNominiRow = $db->rowCount($sqlChkNo);
	if($totNominiRow>0){
	  $tblField =  'ApproveUserNominatorNIC';
	}else{
	  $tblField = 'ApprovelUserNIC';
	}
	
$approvSql="SELECT        TG_TeacherTransferNational.TransferType, TG_TeacherTransferNational.ID, TG_TeacherTransferNational.TransferRequestType, TG_TeacherTransferNational.ExpectSchool, 
                         TG_TeacherTransferNational.LikeToOtherSchool, TG_TeacherTransferNational.ReasonForTransfer, TG_TeacherTransferNational.ExtraActivities, CONVERT(varchar(20),TG_TeacherTransferNational.RequestedDate,121) AS RequestedDate, 
                         TG_TeacherTransferNational.IsApproved, TG_Request_Approve.RequestType, TG_Request_Approve.ApprovelUserNIC, TG_Request_Approve.ApproveUserNominatorNIC, 
                         TeacherMast.SurnameWithInitials, CD_CensesNo.InstitutionName, TG_Request_Approve.ApprovedStatus
FROM            TG_TeacherTransferNational INNER JOIN
                         TG_Request_Approve ON TG_TeacherTransferNational.ID = TG_Request_Approve.RequestID INNER JOIN
                         TeacherMast ON TG_TeacherTransferNational.NIC = TeacherMast.NIC INNER JOIN
                         CD_CensesNo ON TG_TeacherTransferNational.SchoolID = CD_CensesNo.CenCode
WHERE        (TG_Request_Approve.RequestType = 'TransferTeacherNational') AND (TG_Request_Approve.$tblField = N'$NICUser') AND 
                         (TG_Request_Approve.ApprovedStatus = N'P')";

$TotaRows=$db->rowCount($approvSql);
?>

    <form method="post" action="transferAction.php" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
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
                      <td width="28%" align="center" bgcolor="#999999">Employee Name</td>
                      <td width="19%" align="center" bgcolor="#999999">School</td>
                      <td width="11%" align="center" bgcolor="#999999">Request Date</td>
                      <td width="11%" align="center" bgcolor="#999999">Status</td>
                      <td width="9%" align="center" bgcolor="#999999">Action</td>
                    </tr>
                    <?php 
					$i=1;
					$stmt = $db->runMsSqlQuery($approvSql);
                     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
						$RequestID=$row['ID'];
					?>
                    <tr>
                      <td height="20" bgcolor="#FFFFFF"><?php echo $i++; ?></td>
                      <td bgcolor="#FFFFFF"><?php echo $row['SurnameWithInitials']; ?></td>
                      <td bgcolor="#FFFFFF"><?php echo $row['InstitutionName']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['RequestedDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php //echo $row['FromDate']; ?></td>
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
			
		$sqlTransf="SELECT        TransferType, TransferRequestType, ExpectSchool, LikeToOtherSchool, ReasonForTransfer, ExtraActivities, RequestedDate, IsApproved, SchoolID, NIC, ExpectSchool2, ExpectSchool3, ExpectSchool4, ExpectSchool5,ServiceHistoryID
FROM            TG_TeacherTransferNational
WHERE        (ID = '$id')";

			$stmtTr = $db->runMsSqlQuery($sqlTransf);
			while ($row = sqlsrv_fetch_array($stmtTr, SQLSRV_FETCH_ASSOC)) {
				$TransferType=$row['TransferType'];
				$TransferRequestType=trim($row['TransferRequestType']);
				$ExpectSchool=trim($row['ExpectSchool']);
				$LikeToOtherSchool=trim($row['LikeToOtherSchool']);
				$ReasonForTransfer=$row['ReasonForTransfer'];
				$ExtraActivities=$row['ExtraActivities'];
				$RequestedDate=$row['RequestedDate'];
				$IsApproved=$row['IsApproved'];
				$SchoolID=$loggedSchool=$row['SchoolID'];
				$NIC=$NICUser=trim($row['NIC']);
				$ExpectSchool2=$row['ExpectSchool2'];
				$ExpectSchool3=$row['ExpectSchool3'];
				$ExpectSchool4=$row['ExpectSchool4'];
				$ExpectSchool5=$row['ExpectSchool5'];
				$ServiceHistoryID=trim($row['ServiceHistoryID']);
			}
			
			$sql = "Select InstitutionName from CD_CensesNo Where CenCode='$ExpectSchool'";
            $stmt = $db->runMsSqlQuery($sql);
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				$requestedSchool=trim($row['InstitutionName']);
			}
			$sql = "Select InstitutionName from CD_CensesNo Where CenCode='$ExpectSchool2'";
            $stmt = $db->runMsSqlQuery($sql);
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				$requestedSchool2=trim($row['InstitutionName']);
			}
			$sql = "Select InstitutionName from CD_CensesNo Where CenCode='$ExpectSchool3'";
            $stmt = $db->runMsSqlQuery($sql);
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				$requestedSchool3=trim($row['InstitutionName']);
			}
			$sql = "Select InstitutionName from CD_CensesNo Where CenCode='$ExpectSchool4'";
            $stmt = $db->runMsSqlQuery($sql);
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				$requestedSchool4=trim($row['InstitutionName']);
			}
			$sql = "Select InstitutionName from CD_CensesNo Where CenCode='$ExpectSchool5'";
            $stmt = $db->runMsSqlQuery($sql);
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
				$requestedSchool5=trim($row['InstitutionName']);
			}
			
			$iAvailTolArr = explode(',',$ExtraActivities);
			$ActivityTitle="";
			$sqlMS = "SELECT * FROM TG_TeacherExtraActivity where ActivityTitle!=''";
			$stmtMS = $db->runMsSqlQuery($sqlMS);
			while ($row = sqlsrv_fetch_array($stmtMS, SQLSRV_FETCH_ASSOC)) {
				$ActivityID=$row['ID'];
				if(in_array($ActivityID,$iAvailTolArr)){
					$ActivityTitle.=$row['ActivityTitle'].", ";
				}
				
			}
			
			$workOtherSchool="No";
			if($LikeToOtherSchool=='Y')$workOtherSchool="Yes";
			
		//////Start form values
		
		
			?>
        <table width="100%" cellpadding="1" cellspacing="1">
        
			  <tr>
			    <td colspan="2" ><strong>Personal Information</strong></td>
	      </tr>
			  <tr>
			    <td colspan="2" bgcolor="#FFFFFF"><?php include("../transfer/teacherPersonalDetails.php");?></td>
	      </tr>
			  <tr>
			    <td colspan="2" >&nbsp;</td>
	      </tr>
          <tr>
			    <td colspan="2" bgcolor="#FFFFFF"><strong>Career Information</strong></td>
	      </tr>
          <tr>
			    <td colspan="2" bgcolor="#FFFFFF"><?php include("../transfer/teacherCareerDetails.php");?></td>
	      </tr>
          <tr>
			    <td colspan="2" >&nbsp;</td>
	      </tr>
          <tr>
			    <td colspan="2" bgcolor="#FFFFFF"><strong>Teaching Qualification</strong></td>
	      </tr>
          <tr>
			    <td colspan="2" bgcolor="#FFFFFF"><?php include("../transfer/teacherTeachingDetails.php");?></td>
	      </tr>
          <tr>
			    <td colspan="2" >&nbsp;</td>
	      </tr>
          <tr>
			    <td colspan="2" bgcolor="#FFFFFF"><strong>Current Timetable</strong></td>
	      </tr>
          <tr>
			    <td colspan="2" bgcolor="#FFFFFF"><?php include("../transfer/teacherCurrentTimeTable.php");?></td>
	      </tr>
          <tr>
			    <td colspan="2" >&nbsp;</td>
	      </tr>
          <tr>
			    <td colspan="2" bgcolor="#FFFFFF"><strong>Transfer Details</strong></td>
	      </tr>
           
			  <tr>
			    <td colspan="2" ><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="23%" bgcolor="#CFF1D1">Expect School 1:</td>
                      <td width="77%" bgcolor="#CFD7FE"><?php echo ucwords(strtolower(($requestedSchool))); ?></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#CFF1D1">Expect School 2:</td>
                      <td bgcolor="#CFD7FE"><?php echo ucwords(strtolower(($requestedSchool2))); ?></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#CFF1D1">Expect School 3:</td>
                      <td bgcolor="#CFD7FE"><?php echo ucwords(strtolower(($requestedSchool3))); ?></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#CFF1D1">Expect School 4:</td>
                      <td bgcolor="#CFD7FE"><?php echo ucwords(strtolower(($requestedSchool4))); ?></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#CFF1D1">Expect School 5:</td>
                      <td bgcolor="#CFD7FE"><?php echo ucwords(strtolower(($requestedSchool5))); ?></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#CFF1D1">Extra Activities :</td>
                      <td bgcolor="#CFD7FE"><?php echo ucwords(strtolower(($ActivityTitle))); ?></td>
                    </tr>
                    <tr>
                      <td valign="top" bgcolor="#CFF1D1">Reason for Transfer :</td>
                      <td bgcolor="#CFD7FE"><?php echo $ReasonForTransfer ?></td>
                    </tr>
                    <tr>
                      <td bgcolor="#CFF1D1">Would like to Work Another School :</td>
                      <td bgcolor="#CFD7FE"><?php echo $workOtherSchool ?></td>
                    </tr>
                    <!--
                    <tr>
                      <td valign="top" bgcolor="#F7E2DD">&nbsp;</td>
                      <td bgcolor="#EDEEF3">&nbsp;</td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td><input type="hidden" name="cat" value="transferTeacher" />
                      <input type="hidden" name="tblName" value="TG_TeacherTransfer" />
                      <input type="hidden" name="TransferType" value="TTR" />
                      <input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value=""/></td>
                    </tr>
                    -->
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
          <tr>
                        <td colspan="2"><?php include("../transfer/schoolInformation.php");?></td>
                  </tr>
          <?php 
   $i=1;
   $sqlLeave="SELECT        TG_Request_Approve.id AS ReqAppID, TG_Request_Approve.RequestUserNIC, TG_Request_Approve.ApprovelUserNIC, TG_Request_Approve.ApproveUserNominatorNIC, 
                         TG_Request_Approve.ApproveProcessOrder, TG_Request_Approve.ApprovedStatus, TG_Request_Approve.DateTime, TG_Request_Approve.Remarks, 
                         TeacherMast.SurnameWithInitials, TG_ApprovalProcess.ApproveAccessRoleName
FROM            TG_Request_Approve INNER JOIN
                         TeacherMast ON TG_Request_Approve.ApprovelUserNIC = TeacherMast.NIC INNER JOIN
                         TG_ApprovalProcess ON TG_Request_Approve.ApprovalProcessID = TG_ApprovalProcess.ID
WHERE        (TG_Request_Approve.RequestType = 'TransferTeacherNational') AND (TG_Request_Approve.RequestID = '$id')
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
							
						
					  ?>
			  <tr>
                  <td colspan="2" ><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="1%" height="30" align="center"><img src="images/re_enter.png" width="10" height="10" /></td>
                      <td colspan="2"><?php echo $ApproveAccessRoleName ?> - <?php echo $SurnameWithInitials ?></td>
                    </tr>
                     
                    <?php 
                      if($ApprovelUserNIC==$nicNO){?>
                    <tr>
                      <td height="20">&nbsp;</td>
                      <td valign="top" bgcolor="#F7E2DD">Release :</td>
                      <td valign="top" bgcolor="#EDEEF3"><select name="Remarks" class="select2a_new" id="Remarks">
                        <option value="P">With Replacement</option>
                        <option value="A">Without Replacement</option>
                      </select></td>
                    </tr>
                    <tr>
                      <td height="20" width="1%">&nbsp;</td>
                      <td width="20%" valign="top" bgcolor="#F7E2DD">Approvel Status :<?php //echo $ApprovelUserNIC;echo "_";echo $nicNO;echo "_"; ?></td>
                      <td valign="top" bgcolor="#EDEEF3">
                        <select name="ApprovedStatus" class="select2a_new" id="ApprovedStatus">
                        <option value="P">Pending</option>
                        <option value="A">Approve</option>
                        <option value="R">Reject</option>
                      </select>
                      </td>
                    </tr>
                    <tr>
                      <td height="20">&nbsp;</td>
                      <td valign="top" bgcolor="#F7E2DD">&nbsp;</td>
                      <td valign="top" bgcolor="#EDEEF3"><input type="hidden" value="<?php echo $ReqAppID ?>" name="ReqAppID" id="ReqAppID" />
                      <input type="hidden" value="<?php echo $id ?>" name="TransferID" id="TransferID" />
                      <input type="hidden" value="TransferTeacherNational" name="cat" />
                      <input type="hidden" value="<?php echo $ApproveProcessOrder ?>" name="ApproveProcessOrder" />
                      <input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/submit.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                    </tr>
                    
                   
					<?php }else{?>
                    <tr>
                      <td height="20">&nbsp;</td>
                      <td valign="top" bgcolor="#F7E2DD">Release :</td>
                      <td align="left" valign="top" bgcolor="#EDEEF3"><?php echo $Remarks;?></td>
                    </tr>
                    <tr>
                      <td height="20" width="1%">&nbsp;</td>
                      <td width="20%" valign="top" bgcolor="#F7E2DD">Approvel Status :</td>
                      <td align="left" valign="top" bgcolor="#EDEEF3"><?php  echo $statName;?></td>
                    </tr>
                    
                    <?php }?>
                    
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