<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function check_form(form_name) {
  if (submitted == true) {
    alert("This form has already been submitted. Please press Ok and wait for this process to be completed.");
    return false;
  }

  error = false;
  form = form_name;
  error_message = "Please enter following details:\n";
  
  //check_input("subject", 2, "Feedback Subject.");
  //check_input("captInput", 2, "Verification Code.");
  check_select("GradeID", "", "Grade.");
 
  if (error == true) {
    alert(error_message);
    return false;
  } else {
    submitted = true;
    return true;
  }
}
//--></script><?php 
$msg="";


/*
if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$SchoolID=$_REQUEST['SchoolID'];
	$GradeID=$_REQUEST['GradeID'];
	if($SchoolID!='' and $GradeID!=''){
	$queryGradeSave="INSERT INTO $tblNam
           (SchoolID,GradeID)
     VALUES
           ('$SchoolID','$GradeID')";
		   
	$countSql="SELECT * FROM $tblNam where SchoolID='$SchoolID' and GradeID='$GradeID'";
	$isAvailable=$db->rowAvailable($countSql);
	if($isAvailable==1){
		$msg="Already exist.";
	}else{ 
		$db->runMsSqlQuery($queryGradeSave);
		$msg="Successfully Updated.";
	}
	}
	//sqlsrv_query($queryGradeSave);
}*/

/* $sqlCAllready = "SELECT SurnameWithInitials FROM TeacherMast WHERE NIC='$id'";
$stmtCAllready= $db->runMsSqlQuery($sqlCAllready);
$rowAllready = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC);
$SurnameWithInitials=trim($rowAllready['SurnameWithInitials']); */

$sqlCAllready = "SELECT        TeacherMast.ID, TeacherMast.NIC, TeacherMast.SurnameWithInitials, Passwords.AccessRole
FROM            TeacherMast INNER JOIN
                         Passwords ON TeacherMast.NIC = Passwords.NICNo
WHERE        (TeacherMast.NIC = N'$id')";
$stmtCAllready= $db->runMsSqlQuery($sqlCAllready);
$rowAllready = sqlsrv_fetch_array($stmtCAllready, SQLSRV_FETCH_ASSOC);
$SurnameWithInitials=trim($rowAllready['SurnameWithInitials']);
$AccessRole=trim($rowAllready['AccessRole']);

	
$sqlList="SELECT        TG_IncrementRequest.ID AS Expr1, CONVERT(varchar(20),TG_IncrementRequest.EffectiveDate, 121) AS EffectiveDate, CONVERT(varchar(20),TG_IncrementRequest.LastUpdate, 121) AS LastUpdate, TG_IncrementRequest.IsApproved, CD_Service.ServiceName, CD_CensesNo.InstitutionName, TG_IncrementRequest.AttachFile,
                         TG_IncrementRequest.NIC
FROM            TG_IncrementRequest INNER JOIN
                         CD_Service ON TG_IncrementRequest.ServCode = CD_Service.ServCode INNER JOIN
                         CD_CensesNo ON TG_IncrementRequest.SchoolID = CD_CensesNo.CenCode
WHERE        (TG_IncrementRequest.NIC = '$id')";

$TotaRows=$db->rowCount($sqlList);

/* $sqlQry="SELECT COUNT(*) FROM StaffServiceHistory";
echo $stmt = $db->runMsSqlQuery($sqlQry);
print_r($stmt);
echo $TotaRows=$db->rowCount($sqlQry);
 */
?>


<div class="main_content_inner_block">
    <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if($msg!=''){//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
   	  <div class="mcib_middle1">
          <div class="mcib_middle_full">
          <div class="form_error"><?php echo $msg; echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
        </div>
        <?php }?>
        <table width="100%" cellpadding="0" cellspacing="0">
			  <tr>
			    <td colspan="2" align="center" valign="top"><u>Increment List of <?php echo $SurnameWithInitials ?> [<?php echo $id ?>] (<?php echo $AccessRole ?>)</u></td>
	      </tr>
			  <tr>
                  <td width="56%" valign="top">&nbsp;</td>
        <td width="44%" valign="top">&nbsp;</td>
          </tr>
                <tr>
                  <td><?php echo $TotaRows ?> Record(s) found.</td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="2%" height="25" align="center" bgcolor="#999999">#</td>
                         <td width="18%" height="25" align="center" bgcolor="#999999">School Name</td>
                        <td width="30%" align="center" bgcolor="#999999">Designation &amp; Grade</td>
                        <td width="10%" align="center" bgcolor="#999999">Service Letter</td>
                        <td width="11%" align="center" bgcolor="#999999">Effective Date</td>
                        <td width="14%" align="center" bgcolor="#999999">Requested Date</td>
                        <td width="8%" align="center" bgcolor="#999999">Status</td>
                        <td width="7%" align="center" bgcolor="#999999">Print</td>
                      </tr>
                      <?php 
					   
					  /*$sqlList="SELECT [ID]
      ,[SchoolID]$loggedSchool
      ,[GradeID]
  FROM [dbo].[TG_SchoolGradeMaster]
  where SchoolID='SC05428'";*/
  $i=1;
   $stmt = $db->runMsSqlQuery($sqlList); 
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					  $InstitutionName=$row['InstitutionName'];
					  $Expr1=trim($row['Expr1']);
					  $ServiceName=$row['ServiceName'];
					  $AttachFile=$row['AttachFile'];
					  $IsApproved=$row['IsApproved'];
					  $EffectiveDate=$row['EffectiveDate'];
					  $LastUpdate=substr($row['LastUpdate'], 0, -1);
					  
					  $approve="Pending";
					  if($IsApproved=='Y')$approve="Approved";
					 
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td height="20" align="left" bgcolor="#FFFFFF"><?php echo $InstitutionName; ?></td>
                        <td align="left" bgcolor="#FFFFFF"><?php echo $ServiceName ?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php if($AttachFile){?><a href="<?php echo "incrementattachments/$AttachFile"; ?>" target="_blank">View</a><?php }else{echo "Not Available";}?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php echo $EffectiveDate; ?></td>
                        <td align="center" bgcolor="#FFFFFF"><?php echo $LastUpdate; ?></td>
                        <td bgcolor="#FFFFFF" align="center"><?php echo $approve ?></td>
                        <td bgcolor="#FFFFFF" align="center"><a href="incrementRequestPrint.php?recID=<?php echo $Expr1 ?>" target="_blank">Print <?php //echo $Expr1 ?></a></td>
                      </tr>
                      <?php }?>
                      <tr>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                        <td bgcolor="#FFFFFF">&nbsp;</td>
                      </tr>
                    </table></td>
          </tr>
                <tr>
                  <td>&nbsp;</td>
                  <td>&nbsp;</td>
                </tr>
              </table>
    </div>
    
    </form>
</div><!--
<div style="width:945px; width: auto; float: left;">
    <div style="width: 150px; float: left; margin-left: 50px;">
        School
    </div>
    <div style="width: 745px; float: left;">
        <select name="teachingSubject" class="select2a_n" id="teachingSubject" style="width: auto;" onchange="">
            <option value="">School Name</option>
           
        </select>
    </div>
    <div style="width: 150px; float: left;margin-left: 50px;">
        Grade
    </div>
    <div style="width: 745px; float: left;">
        <select name="teachingSubject" class="select2a_n" id="teachingSubject" style="width: auto;" onchange="">
            <option value="">Grade</option>
           
        </select>
    </div>
    <div style="width: 200px; float: left;margin-left: 50px;">
        
    </div>
    
</div>-->