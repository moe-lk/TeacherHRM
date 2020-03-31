<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
 <?php
	// ***
	// check logged user is nominater
	$tblField = "";
	$nominiStatus = true;
	$sqlChkNo = "SELECT id, ApproveUserNominatorNIC
FROM TG_Request_Approve
WHERE (ApproveUserNominatorNIC = N'$nicNO') AND (RequestType = 'Retirement')";
	$totNominiRow = $db->rowCount($sqlChkNo);
	if($totNominiRow>0){
	  $tblField =  'ApproveUserNominatorNIC';
	  $nominiStatus = true;
	}
	else{
	  $tblField = 'ApprovelUserNIC';
	  $nominiStatus = false;
	}
	// **

	$sqlChk = "SELECT id
FROM TG_Request_Approve
WHERE ($tblField = N'$nicNO') AND (RequestType = 'Retirement') AND (ApprovedStatus = N'P')";
	$totRow = $db->rowCount($sqlChk);
?>
                    
    <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if ($msg != '') {//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){   ?>   
                <div class="mcib_middle_full">
                    <div class="form_error"><?php
                        echo $msg;
                        echo $_SESSION['success_update'];
                        $_SESSION['success_update'] = "";
                        ?><?php
                        echo $_SESSION['fail_update'];
                        $_SESSION['fail_update'] = "";
                        ?></div>
                </div>
            <?php } ?>
             <div style="width:738px; float:left;">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td><?php echo $totRow; ?> Record(s) found.</td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                            <tr>
                                <td width="4%" height="25" align="center" bgcolor="#999999">#</td>
                                <td width="37%" align="center" bgcolor="#999999">Employee Name</td>
                                <td width="10%" align="center" bgcolor="#999999">Request Date</td>
                                <td width="10%" align="center" bgcolor="#999999">Date to Retire </td>
                                <td width="9%" align="center" bgcolor="#999999">Approve</td>
                                <td width="5%" align="center" bgcolor="#999999">More Info</td>
                                <td width="25%" align="center" bgcolor="#999999">Remark</td>
                            </tr>
                            <?php
                            $sqlP = "SELECT fVoluntaryYear, fRetirementYear FROM TG_RetiremntParms";

                            $stmtP = $db->runMsSqlQuery($sqlP);
                            while ($rowP = sqlsrv_fetch_array($stmtP, SQLSRV_FETCH_ASSOC)) {
                                $fRetirementYear = $rowP["fRetirementYear"];
                            }


                            $sql = "SELECT CD_Title.TitleName, TeacherMast.SurnameWithInitials, CONVERT(varchar(20), TG_RetirementRequest.RequestDate, 121) AS RequestDate, CONVERT(varchar(20), TeacherMast.DOB, 121) AS DOB, TG_Request_Approve.ApprovedStatus, TG_RetirementRequest.RetirementType, TG_Request_Approve.RequestType,TG_Request_Approve.id, TG_Request_Approve.Remarks
FROM TG_Request_Approve 
INNER JOIN TeacherMast ON TG_Request_Approve.RequestUserNIC = TeacherMast.NIC 
INNER JOIN CD_Title ON TeacherMast.Title = CD_Title.TitleCode 
INNER JOIN TG_RetirementRequest ON TG_Request_Approve.RequestID = TG_RetirementRequest.id
WHERE (TG_Request_Approve.$tblField = N'$nicNO') AND (TG_Request_Approve.RequestType = 'Retirement') AND (TG_Request_Approve.ApprovedStatus = N'P')
ORDER BY RequestDate";
                            $res = $db->runMsSqlQuery($sql);

                            $no = 1;
                            while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {

                                $plusYears = '+' . $fRetirementYear . ' year';
                                $retirementDate = date('Y-m-d', strtotime($plusYears, strtotime($row['DOB'])));
                                $approveStatus = $row['ApprovedStatus'];
                                $requestApproveID = $row['id'];
                                $remarks = $row['Remarks'];
                                ?>
                                <tr>
                                    <td height="20" bgcolor="#FFFFFF"><?php echo $no++; ?></td>
                                    <td bgcolor="#FFFFFF"><?php echo $row['TitleName'] . " " . $row['SurnameWithInitials']; ?></td>
                                    <td bgcolor="#FFFFFF" align="center"><?php echo $row['RequestDate']; ?></td>
                                    <td bgcolor="#FFFFFF" align="center"><?php echo $retirementDate; ?></td>
                                    <td bgcolor="#FFFFFF" align="center">
                                        <select disabled>
                                            <?php if (trim($approveStatus) == 'P') { ?>
                                                <option value="P" selected="selected">Pending</option>
                                            <?php } ?>
                                            <?php if (trim($approveStatus) == 'A') { ?>
                                                <option value="A">Approve</option> 
                                            <?php } ?>
                                            <?php if (trim($approveStatus) == 'R') { ?>
                                                <option value="R">Reject</option>
                                            <?php } ?>
                                        </select>
                                    </td>
                                    <td bgcolor="#FFFFFF" align="center"><a href="retirementMoreDetail-3--<?php echo $nominiStatus;?>-<?php echo $approveStatus;?>-<?php echo $requestApproveID; ?>.html"><img src="images/more_info.png" /></a></td>
                                    <td bgcolor="#FFFFFF"><textarea style="width:220px;" disabled><?php echo $remarks; ?></textarea></td>
                                </tr>
                                <?php
                            }
                            ?>



                            <tr>
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
                    <td width="56%">&nbsp;</td>
                    <td width="44%">&nbsp;</td>
                </tr>
            </table>
            </div>

    </form>
<!--
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