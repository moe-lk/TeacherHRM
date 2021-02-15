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
$msg = "";


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
  } */
//   var_dump($loggedSchool);

$sqlList = "SELECT        TeacherMast.SurnameWithInitials, TeacherMast.NIC AS Expr1, TeacherMast.MobileTel, TeacherMast.CurServiceRef, StaffServiceHistory.InstCode
FROM            StaffServiceHistory INNER JOIN
                         TeacherMast ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
WHERE        (StaffServiceHistory.InstCode = N'$loggedSchool') AND (StaffServiceHistory.ServiceRecTypeCode IS NULL or (StaffServiceHistory.ServiceRecTypeCode != 'DS03' AND StaffServiceHistory.ServiceRecTypeCode != 'RN01' AND StaffServiceHistory.ServiceRecTypeCode != 'RT01' AND StaffServiceHistory.ServiceRecTypeCode != 'DS01')) ORDER BY TeacherMast.SurnameWithInitials";
////(StaffServiceHistory.ServiceRecTypeCode != 'RT01' or StaffServiceHistory.ServiceRecTypeCode IS NULL)
$TotaRows = $db->rowCount($sqlList);

/* $sqlQry="SELECT COUNT(*) FROM StaffServiceHistory";
  echo $stmt = $db->runMsSqlQuery($sqlQry);
  print_r($stmt);
  echo $TotaRows=$db->rowCount($sqlQry);
 */
?>


<div class="main_content_inner_block">
    <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if ($msg != '') {//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
            <div class="mcib_middle1">
                <div class="mcib_middle_full">
                    <div class="form_error"><?php echo $msg;
        echo $_SESSION['success_update'];
        $_SESSION['success_update'] = ""; ?><?php echo $_SESSION['fail_update'];
            $_SESSION['fail_update'] = ""; ?></div>
                </div>
<?php } ?>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td colspan="2" align="center" valign="top"><u>Teacher List of <?php echo $InstitutionName ?></u></td>
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
                                <td width="4%" height="25" align="center" bgcolor="#999999">#</td>
                                <td width="12%" height="25" align="center" bgcolor="#999999">NIC</td>
                                <td width="37%" align="center" bgcolor="#999999">Name</td>
                                <td width="19%" align="center" bgcolor="#999999">Designation</td>
                                <td width="16%" align="center" bgcolor="#999999">TP</td>
                                <td width="12%" align="center" bgcolor="#999999">Update Profile</td>
                            </tr>
                            <?php
                            /* $sqlList="SELECT [ID]
                              ,[SchoolID]$loggedSchool
                              ,[GradeID]
                              FROM [dbo].[TG_SchoolGradeMaster]
                              where SchoolID='SC05428'"; */
                            $i = 1;
                            $stmt = $db->runMsSqlQuery($sqlList);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $InstitutionName = $row['SurnameWithInitials'];
                                $Expr1 = trim($row['Expr1']);
                                $MobileTel = $row['MobileTel'];

                                $sqlDesign = "SELECT [NICNo]
      ,[CurPassword]
      ,[LastUpdate]
      ,[AccessRole]
      ,[AccessLevel]
  FROM [MOENational].[dbo].[Passwords]
  where NICNo='$Expr1'";
                                $stmtDes = $db->runMsSqlQuery($sqlDesign);
                                $rowDes = sqlsrv_fetch_array($stmtDes, SQLSRV_FETCH_ASSOC);
                                ?>
                                <tr>
                                    <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                                    <td height="20" bgcolor="#FFFFFF"><?php echo $Expr1; ?></td>
                                    <td bgcolor="#FFFFFF"><?php echo $InstitutionName ?></td>
                                    <td bgcolor="#FFFFFF"><?php echo $rowDes['AccessRole']; ?></td>
                                    <td bgcolor="#FFFFFF"><?php echo $MobileTel; ?></td>
                                    <td bgcolor="#FFFFFF" align="center"><a href="services-8--<?php echo $Expr1 ?>.html" target="_blank">Click <?php //echo $Expr1  ?></a></td>
                                </tr>
<?php } ?>
                            <tr>
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