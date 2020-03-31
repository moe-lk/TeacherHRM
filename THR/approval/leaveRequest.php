<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php
$uploadpath="/leave/leaveattachments";
//$nicNO='722381718V';
/* echo $approvSql="SELECT        TG_Request_Approve.id AS ReqAppID, TG_Request_Approve.RequestID, TG_Request_Approve.RequestType, TG_Request_Approve.RequestUserNIC,
                         TG_Request_Approve.ApprovelUserNIC, TG_Request_Approve.ApproveUserNominatorNIC, TG_Request_Approve.ApproveProcessOrder,
                         TG_Request_Approve.ApprovalProcessID, TG_Request_Approve.ApprovedStatus, TG_Request_Approve.DateTime, TG_Request_Approve.Remarks,
                         TeacherMast.SurnameWithInitials, TG_StaffLeave.LeaveType AS Expr1, CD_LeaveType.LeaveCode
FROM            TG_Request_Approve INNER JOIN
                         TeacherMast ON TG_Request_Approve.RequestUserNIC = TeacherMast.NIC INNER JOIN
                         TG_StaffLeave ON TG_Request_Approve.RequestID = TG_StaffLeave.ID INNER JOIN
                         CD_LeaveType ON TG_StaffLeave.LeaveType = CD_LeaveType.LeaveCode
WHERE         (TG_Request_Approve.ApprovelUserNIC = N'$nicNO') AND (TG_Request_Approve.ApprovedStatus = N'P')"; */
//commented on 20160908
/* $leaveTypeSql="SELECT [LeaveCode]
      ,[Description]
      ,[RecordLog]
      ,[DutyCode]
  FROM [dbo].[CD_LeaveType]";
$stmt = $db->runMsSqlQuery($leaveTypeSql);
$leaveCodeArr=array();
$leaveCodeComma="";
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
	$Description=$row['Description'];
	$LeaveCode=$row['LeaveCode'];
	$leaveCodeComma.="'".$LeaveCode."',";
	if($LeaveCode)$leaveCodeArr[]=$LeaveCode;
}
$leaveCodeComma = substr($leaveCodeComma, 0, -1);
//print_r($leaveCodeArr);

$sqlChkNo = "SELECT id FROM TG_Request_Approve WHERE (ApproveUserNominatorNIC = N'$nicNO') AND (RequestType IN ($leaveCodeComma))";
	$totNominiRow = $db->rowCount($sqlChkNo);
	if($totNominiRow>0){
	  $tblField =  'ApproveUserNominatorNIC';
	}else{
	  $tblField = 'ApprovelUserNIC';
	}

$approvSql="SELECT        TG_Request_Approve.id AS ReqAppID, TG_Request_Approve.RequestID, TG_Request_Approve.RequestType, TG_Request_Approve.RequestUserNIC,
                         TG_Request_Approve.ApprovelUserNIC, TG_Request_Approve.ApproveUserNominatorNIC, TG_Request_Approve.ApproveProcessOrder,
                         TG_Request_Approve.ApprovalProcessID, TG_Request_Approve.ApprovedStatus, TG_Request_Approve.DateTime, TG_Request_Approve.Remarks,
                         TeacherMast.SurnameWithInitials, CD_LeaveType.Description,
						 CONVERT(varchar(20),TG_StaffLeave.StartDate,121) AS FromDate,CONVERT(varchar(20),TG_StaffLeave.EndDate,121) AS ToDate, CONVERT(varchar(20),TG_StaffLeave.LastUpdate,121) AS LastUpdate
FROM            TG_Request_Approve INNER JOIN
                         TeacherMast ON TG_Request_Approve.RequestUserNIC = TeacherMast.NIC INNER JOIN
                         TG_StaffLeave ON TG_Request_Approve.RequestID = TG_StaffLeave.ID INNER JOIN
                         CD_LeaveType ON TG_StaffLeave.LeaveType = CD_LeaveType.LeaveCode
WHERE        (TG_Request_Approve.$tblField = N'$NICUser') AND (TG_Request_Approve.ApprovedStatus = N'P') AND (TG_Request_Approve.RequestType IN ($leaveCodeComma))"; *///621830198V


//end commented on 20160908
if($id==''){
	$Per_Page = 30;  // Per Page

	//Get the page number

	$Page = 1;

	//Determine if it is the first page

	/* if(isset($_GET["Page"]))
	{
		$Page=(int)$_GET["Page"];
			if ($Page < 1)
				$Page = 1;
	}  */

	if($menu)
	{
		$Page=(int)$menu;
			if ($Page < 1)
				$Page = 1;
	}

	$Page_Start = (($Per_Page*$Page)-$Per_Page)+1;
	$Page_End = $Page_Start + $Per_Page-1;


	$NICSearch="";
	if (isset($_POST["FrmSrch"])) {
		$NICSearch=$_REQUEST['NICSearch'];
	}

	$approvSql="WITH LIMIT AS(SELECT        TG_Approval_Leave.RequestID, TeacherMast.NIC AS Expr1, TeacherMast.SurnameWithInitials, CD_CensesNo.InstitutionName, TG_Approval_Leave.ID, CD_LeaveType.Description, CONVERT(varchar(20),TG_StaffLeave.StartDate,121) AS FromDate,
                         CONVERT(varchar(20),TG_StaffLeave.EndDate,121) AS ToDate,
						 CONVERT(varchar(20),TG_StaffLeave.LastUpdate,121) AS LastUpdate, ROW_NUMBER() OVER (ORDER BY TG_StaffLeave.ID ASC) AS 'RowNumber'
FROM            TG_Approval_Leave INNER JOIN
                         TG_StaffLeave ON TG_Approval_Leave.RequestID = TG_StaffLeave.ID INNER JOIN
                         TeacherMast ON TG_StaffLeave.NIC = TeacherMast.NIC INNER JOIN
                         CD_CensesNo ON TG_StaffLeave.SchoolID = CD_CensesNo.CenCode INNER JOIN
                         CD_LeaveType ON TG_StaffLeave.LeaveType = CD_LeaveType.LeaveCode
WHERE        (TG_Approval_Leave.ApprovedStatus = 'P')";

	if($NICSearch)$approvSql.=" and (TG_StaffLeave.NIC like '%$NICSearch%')";

	if($accLevel!='99999'){

		//$approvSql.=" and (TG_Approval_Leave.ApproveInstCode = '$loggedSchool')";//last AND added on 15th Aug 2016
		$sqlChkNo = "SELECT id FROM TG_Approval_Leave WHERE (ApprovedStatus = 'P') AND (ApproveDesignationCode = N'$accLevel') AND (ApproveInstCode = N'$loggedSchool') AND (RequestType = 'Leave')";
		$totNominiRow = $db->rowCount($sqlChkNo);
		if($totNominiRow>0){
		  $tblField =  'TG_Approval_Leave.ApproveDesignationCode';
		}else{
		  $tblField = 'TG_Approval_Leave.ApproveDesignationNominiCode';
		}

		$approvSql.=" AND (TG_Approval_Leave.ApproveInstCode = '$loggedSchool') AND ($tblField = N'$accLevel') AND (TG_Approval_Leave.RequestType = 'Leave')";

	}

//echo $approvSql;
	$approvSql.=")
	select * from LIMIT WHERE RowNumber BETWEEN $Page_Start AND $Page_End";

	$countTotal="SELECT        TG_Approval_Leave.RequestID
FROM            TG_Approval_Leave INNER JOIN
                         TG_StaffLeave ON TG_Approval_Leave.RequestID = TG_StaffLeave.ID INNER JOIN
                         TeacherMast ON TG_StaffLeave.NIC = TeacherMast.NIC INNER JOIN
                         CD_CensesNo ON TG_StaffLeave.SchoolID = CD_CensesNo.CenCode INNER JOIN
                         CD_LeaveType ON TG_StaffLeave.LeaveType = CD_LeaveType.LeaveCode
WHERE        (TG_Approval_Leave.ApprovedStatus = 'P')";
	if($NICSearch)$countTotal.=" and (TG_StaffLeave.NIC like '%$NICSearch%')";
	if($accLevel!='99999')$countTotal.=" and (TG_Approval_Leave.ApproveInstCode = '$loggedSchool')";
	$TotaRows=$db->rowCount($countTotal);
	if(!$TotaRows)$TotaRows=0;

	//Declare previous/next page row guide

	$Prev_Page = $Page-1;
	$Next_Page = $Page+1;

	if($TotaRows<=$Per_Page)
	{
		$Num_Pages =1;
	}
	else if(($TotaRows % $Per_Page)==0)
	{
		$Num_Pages =($TotaRows/$Per_Page) ;
	}
	else
	{
		$Num_Pages =($TotaRows/$Per_Page)+1;
		$Num_Pages = (int)$Num_Pages;
	}

	//Determine where the page will end

	$Page_End = $Per_Page * $Page;
	if($Page_End > $TotaRows)
	{
		$Page_End = $TotaRows;
	}
}
?>
<?php if($id==''){?>
	<div style="width:738px; margin-top:10px;"><form method="post" action="" name="frmSrch" id="frmSrch"><table width="100%" cellspacing="1" cellpadding="1">
  <tr>
    <td width="19%">Search by NIC</td>
    <td width="27%"><input name="NICSearch" type="text" class="input2_n" id="NICSearch" value="" placeholder="NIC"/></td>
    <td width="11%"><input name="FrmSrch" type="submit" id="FrmSrch" style="background-image: url(../cms/images/searchN.png); width:84px; height:26px; background-color:transparent; border:none; cursor:pointer;" value="" /></td>
    <td width="43%"><div id="txt_available" style="font-weight:bold;"></div></td>
  </tr>
  <tr>
    <td colspan="4" style="border-bottom:1px; border-bottom-style:solid;">&nbsp;</td>
    </tr>
    <tr>
    <td colspan="4">&nbsp;</td>
    </tr>
</table></form>
</div>
<?php }?>
<form method="post" action="leaveAction.php" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if($msg!='' || $_SESSION['success_update']!=''){//if( || $_SESSION['success_update']!=''){  ?>

          <div class="mcib_middle_full" style="float:left;">
          <div class="form_error"><?php echo $msg; echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
        </div>
        <?php }?>
         <div style="width:738px; float:left;">
		<?php if($id==''){?>
        <table width="100%" cellpadding="0" cellspacing="0">

        	<tr>
                  <td width="56%"><?php echo $TotaRows ?> Record(s) found. Showing <?php echo $Per_Page ?> records per page.</td>
                  <td width="44%">&nbsp;</td>
                </tr>
			  <tr>
                  <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="3%" height="25" align="center" bgcolor="#999999">#</td>
                      <td width="28%" align="center" bgcolor="#999999">Employee Name</td>
                      <td width="19%" align="center" bgcolor="#999999">Leave Type</td>
                      <td width="11%" align="center" bgcolor="#999999">Request Date</td>
                      <td width="11%" align="center" bgcolor="#999999">From Date</td>
                      <td width="10%" align="center" bgcolor="#999999">To Date</td>
                      <td width="9%" align="center" bgcolor="#999999">Action</td>
                    </tr>
                    <?php
					$i=1;
					$stmt = $db->runMsSqlQuery($approvSql);
                     while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {

						$RequestID=$row['RequestID'];
						$RowNumber=$row['RowNumber'];
					?>
                    <tr>
                      <td height="20" bgcolor="#FFFFFF"><?php echo $RowNumber; ?></td>
                      <td bgcolor="#FFFFFF"><?php echo $row['SurnameWithInitials']; ?></td>
                      <td bgcolor="#FFFFFF"><?php echo $row['Description']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['LastUpdate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['FromDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><?php echo $row['ToDate']; ?></td>
                      <td bgcolor="#FFFFFF" align="center"><a href="leaveRequest-2--<?php echo $RequestID ?>.html"><img src="images/more_info.png" /></a></td>
                    </tr>
                   <?php }?>
                  </table></td>
          </tr>

                <tr>
                  <td colspan="2"><table width="100%" cellspacing="1" cellpadding="1">
                    <tr>
                      <td width="67%">Page <?php echo $Page ?> of <?php echo $Num_Pages ?></td>
                      <td width="20%" align="right"><?php
//Previous page

if($Prev_Page)
{
    echo " <a href='$ttle-$pageid-$Prev_Page.html?Page=$Prev_Page#related'><< Previous</a> ";
}

//Display total pages

//for($i=1; $i<=$Num_Pages; $i++){


/* for($i=1; $i<=5; $i++){
    if($i != $Page)
    {
        echo "<a href='$_SERVER[SCRIPT_NAME]?id=$id&Page=$i#related'>$i</a>&nbsp;";
    }
    else
    {
        echo "<b> $i </b>";
    }
}  */
					  ?></td>
                      <td width="2%" align="center"><?php if($Prev_Page and $Page!=$Num_Pages){?> | <?php }?></td>
                      <td width="11%" align="left"><?php //Create next page link

if($Page!=$Num_Pages)
{
    //echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Next_Page#related'>Next>></a> ";
	echo " <a href ='$ttle-$pageid-$Next_Page.html?Page=$Next_Page#related'>Next>></a> ";
} ?></td>
                    </tr>
                  </table></td>
                </tr>

              </table> <?php }?>

        <?php if($id!=''){

$countTotal="SELECT        TG_StaffLeave.ID, TG_StaffLeave.NIC, CONVERT(varchar(20),TG_StaffLeave.StartDate,121) AS FromDate,CONVERT(varchar(20),TG_StaffLeave.EndDate,121) AS ToDate,CONVERT(varchar(20),TG_StaffLeave.LastUpdate,121) AS LastUpdate, TG_StaffLeave.UpdateBy, TG_StaffLeave.NoofDays,TG_StaffLeave.LeaveType,
                         TG_StaffLeave.AttachFile,TG_StaffLeave.Reference, TG_StaffLeave.RecordLog, TeacherMast.SurnameWithInitials, CD_LeaveType.Description, CD_CensesNo.CenCode,
                         CD_CensesNo.InstitutionName
FROM            TG_StaffLeave INNER JOIN
                         StaffServiceHistory ON TG_StaffLeave.ServiceRecRef = StaffServiceHistory.ID INNER JOIN
                         TeacherMast ON TG_StaffLeave.NIC = TeacherMast.NIC INNER JOIN
                         CD_LeaveType ON TG_StaffLeave.LeaveType = CD_LeaveType.LeaveCode INNER JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
						 where TG_StaffLeave.ID='$id'";//$NICUser

$stmt = $db->runMsSqlQuery($countTotal);
	while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$FromDate=$row['FromDate'];
		$ToDate=$row['ToDate'];
		$LastUpdate=trim($row['LastUpdate']);
		$SurnameWithInitials=$row['SurnameWithInitials'];
		$Description=$row['Description'];
		$LeaveType=$row['LeaveType'];
		$NoofDays=$row['NoofDays'];
		$AttachFile=$row['AttachFile'];
		$Reference=$row['Reference'];
		$NICLeave=$row['NIC'];
}

$sqlFA = "SELECT CONVERT(varchar(20),AppDate,121) AS firstAppDate FROM StaffServiceHistory where NIC='$NICLeave' and ServiceRecTypeCode='NA01'";

	$stmtFA = $db->runMsSqlQuery($sqlFA);
    $rowSFA = sqlsrv_fetch_array($stmtFA, SQLSRV_FETCH_ASSOC);
    $firstAppDate = $rowSFA['firstAppDate'];

$checkAccessRol="SELECT        TeacherMast.NIC, StaffServiceHistory.PositionCode, StaffServiceHistory.InstCode, TeacherMast.CurServiceRef, TeacherMast.SurnameWithInitials,
                         Passwords.AccessRole
FROM            TeacherMast INNER JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID INNER JOIN
                         Passwords ON TeacherMast.NIC = Passwords.NICNo
                         where TeacherMast.NIC='$NICLeave'";
$stmt = $db->runMsSqlQuery($checkAccessRol);
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        //$loggedSchoolID=trim($row['InstCode']);
        $designationLeave=trim($row['AccessRole']);
    }
			?>
<table width="100%" cellpadding="0" cellspacing="0">

			  <tr>
			    <td colspan="2" ><table width="100%" cellspacing="1" cellpadding="1">
			      <tr>
			        <td>Name</td>
			        <td>:</td>
			        <td><?php echo $SurnameWithInitials ?></td>
			        <td>Designation</td>
			        <td>:</td>
			        <td><?php echo ucfirst(strtolower($designationLeave ));?></td>
		          </tr>
			      <tr>
			        <td>Leave Type </td>
			        <td>:</td>
			        <td><?php echo $Description ?></td>
			        <td>1st Appoinment Date :</td>
			        <td>:</td>
			        <td><?php echo $firstAppDate ?></td>
		          </tr>
			      <tr>
			        <td>Number of days</td>
			        <td>:</td>
			        <td><?php echo $NoofDays ?></td>
			        <td>Attachment</td>
			        <td>:</td>
			        <td><a href="<?php echo "$uploadpath/$AttachFile"; ?>" target="_blank">View</a></td>
		          </tr>
			      <tr>
			        <td width="15%">From Date</td>
			        <td width="2%">:</td>
			        <td width="30%"><?php echo $FromDate ?></td>
			        <td width="16%">Request Date</td>
			        <td width="2%">:</td>
			        <td width="35%"><?php echo $LastUpdate ?></td>
		          </tr>
			      <tr>
			        <td>To Date</td>
			        <td>:</td>
			        <td><?php echo $ToDate ?></td>
			        <td>Remarks</td>
			        <td>:</td>
			        <td><?php echo $Reference ?></td>
		          </tr>
		        </table></td>
	      </tr>
			  <tr>
			    <td colspan="2" >&nbsp;</td>
	      </tr>
          <tr bgcolor="#3399FF">
              <td height="30" colspan="2" valign="middle" style="border-bottom: 1px; border-bottom-style: solid; font-size: 14px; color: #FFFFFF;">&nbsp;&nbsp;<strong>Take an Action</strong></td>
          </tr>
            <tr>
              <td valign="top">&nbsp;</td>
              <td valign="top">&nbsp;</td>
            </tr>

        <?php $sqlApp="SELECT        TG_Approval_Leave.ID, TG_Approval_Leave.RequestType, TG_Approval_Leave.RequestID, TG_Approval_Leave.ApproveInstCode, TG_Approval_Leave.ApproveDesignationCode, TG_Approval_Leave.ApproveDesignationNominiCode,
                         TG_Approval_Leave.ApprovedStatus, TG_Approval_Leave.ApprovedByNIC, TG_Approval_Leave.DateTime, TG_Approval_Leave.Remarks, CD_CensesNo.InstitutionName, CD_AccessRoles.AccessRole
FROM            TG_Approval_Leave INNER JOIN
                         CD_CensesNo ON TG_Approval_Leave.ApproveInstCode = CD_CensesNo.CenCode INNER JOIN
                         CD_AccessRoles ON TG_Approval_Leave.ApproveDesignationCode = CD_AccessRoles.AccessRoleValue
						 WHERE TG_Approval_Leave.RequestID='$id'";

					$resABC = $db->runMsSqlQuery($sqlApp);

				$saveOk="N";
				$ApID="";
				while ($rowABC = sqlsrv_fetch_array($resABC, SQLSRV_FETCH_ASSOC)){
					$AccessRole= $rowABC['AccessRole'];
					$InstitutionName= $rowABC['InstitutionName'];
					$ApproveInstCode= trim($rowABC['ApproveInstCode']);
					$ApproveDesignationCode= trim($rowABC['ApproveDesignationCode']);
					$ApproveDesignationNominiCode= trim($rowABC['ApproveDesignationNominiCode']);
					$ApprovedStatus= trim($rowABC['ApprovedStatus']);
					$IDApp= trim($rowABC['ID']);
					$Remarks= trim($rowABC['Remarks']);
					//echo $accLevel;
					$activate="N";

					//echo "-$ApproveInstCode-";echo "<br>";
					//echo "-$loggedSchool-";echo "<br>";
					//echo $loggedSchool;echo "<br>";
					if($ApproveInstCode==$loggedSchool and ($ApproveDesignationCode==$accLevel || $ApproveDesignationNominiCode==$accLevel)){
						$saveOk="Y";
						$activate="Y";
						$ApID=$IDApp;
					}

					$sqlEmpDes="SELECT        TeacherMast.ID, TeacherMast.NIC, TeacherMast.SurnameWithInitials, CD_AccessRoles.AccessRoleValue, CD_CensesNo.InstitutionName, CD_CensesNo.CenCode, CD_AccessRoles.AccessRole
FROM            CD_CensesNo INNER JOIN
                         StaffServiceHistory ON CD_CensesNo.CenCode = StaffServiceHistory.InstCode INNER JOIN
                         TeacherMast INNER JOIN
                         Passwords ON TeacherMast.NIC = Passwords.NICNo INNER JOIN
                         CD_AccessRoles ON Passwords.AccessLevel = CD_AccessRoles.AccessRoleValue ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
WHERE        (CD_AccessRoles.AccessRoleValue = '$ApproveDesignationCode') AND (CD_CensesNo.CenCode = N'$ApproveInstCode') AND (StaffServiceHistory.ServiceRecTypeCode != 'RT01' or StaffServiceHistory.ServiceRecTypeCode IS NULL)";
					$resED = $db->runMsSqlQuery($sqlEmpDes);
					$rowED = sqlsrv_fetch_array($resED, SQLSRV_FETCH_ASSOC);
					$SurnameWithInitialsED= $rowED['SurnameWithInitials'];

						 ?>
            <tr>
              <td colspan="2" valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                <tr>
                  <td width="15%" style="font-weight: bold">Officer Name<?php //echo "-$ApproveInstCode-";//echo "<br>";
					//echo "-$loggedSchool-";echo "<br>";?><?php //echo "-$ApproveDesignationCode-"; echo "<br>"; echo "-$accLevel-";?></td>
                  <td width="1%">:</td>
                  <td width="34%"><?php echo $SurnameWithInitialsED; ?></td>
                  <td width="16%" style="font-weight: bold">Comment</td>
                  <td width="1%">:</td>
                  <td width="33%" rowspan="3"><textarea name="ApproveComment" id="ApproveComment" cols="35" rows="5" <?php if($activate=='N'){?>disabled="disabled"<?php }?>><?php echo $Remarks ?></textarea></td>
                </tr>
                <tr>
                  <td style="font-weight: bold">Designation</td>
                  <td>:</td>
                  <td><?php echo $AccessRole; ?> [<?php echo $InstitutionName ?>]</td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td style="font-weight: bold">Action</td>
                  <td>:</td>
                  <td>

                  <select class="select2a_n" id="ApprovedStatus" name="ApprovedStatus" <?php if($activate=='N'){?>disabled="disabled"<?php }?>>
                  	  <option value="" <?php if($ApprovedStatus==''){?> selected="selected"<?php }?>>Not approved from previous user</option>
                  	  <option value="P" <?php if($ApprovedStatus=='P'){?> selected="selected"<?php }?>>Pending</option>
                   	  <option value="A" <?php if($ApprovedStatus=='A'){?> selected="selected"<?php }?>>Approve</option>
                      <option value="R" <?php if($ApprovedStatus=='R'){?> selected="selected"<?php }?>>Reject</option>
                  </select>

                  </td>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table></td>
            </tr>

            <tr>
              <td colspan="2" valign="top" style="border-bottom:1px; border-bottom-style:solid; font-size:14px;">&nbsp;</td>
            </tr>
            <tr>
              <td valign="top">&nbsp;</td>
              <td valign="top">&nbsp;</td>
            </tr>
            <?php }?>
              <?php if($saveOk=="Y"){?>
            <tr>
              <td valign="top"><table width="100%" cellspacing="2" cellpadding="2">
                <tr>
                  <td width="32%">&nbsp;</td>
                  <td width="68%"><input type="hidden" name="ApID" value="<?php echo $ApID ?>" />
                  <input type="hidden" name="RequestID" value="<?php echo $id ?>" />
                  <input type="hidden" name="RequestType" value="Leave" />
                  <input type="hidden" name="cat" value="leave" /><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                </tr>
              </table></td>
              <td valign="top">&nbsp;</td>
            </tr>
              <?php }?>

              </table>
        <?php }?>

    </div>

    </form>
    </div>
