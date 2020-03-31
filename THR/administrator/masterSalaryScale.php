<!----><link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$msg=""; 
if(isset($_POST["FrmSrch"]) || $fm==''){
	//CD_TG_SalaryScale SET Description='$Description',RecordLog='$RecordLog' WHERE ServiceID='$ServiceID'
	$ServiceIDSrc=$_REQUEST['ServiceIDSrc'];
	$sqlSrch="SELECT * FROM CD_TG_SalaryScale where Description!='' ORDER BY ID ASC";  
	if($ServiceIDSrc)$sqlSrch.=" and ServiceID='$ServiceIDSrc'";
	$stmtP = $db->runMsSqlQuery($sqlSrch);
	$TotaRows=$db->rowCount($sqlSrch);
	//if($TotaRows==0)$fm="A";
	//$rowP = sqlsrv_fetch_array($stmtP, SQLSRV_FETCH_ASSOC);
	 //echo $TotaRows=$db->rowCount($stmtP);echo $sqlSrch;
}
if($fm=='E'){
	$sqlSrch="SELECT * FROM CD_TG_SalaryScale where ID='$id'";  
	$stmtE= $db->runMsSqlQuery($sqlSrch);
	$rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC);
	$ServiceID = trim($rowE['ServiceID']);
	$Description = trim($rowE['Description']);
}

if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$addEdit=$_REQUEST['AED'];
	$ServiceID=$_REQUEST['ServiceID'];
	$Description=trim($_REQUEST['Description']);
	$dateU=date('Y-m-d H:i:s');
	if($addEdit=="A")$RecordLog="Add by $NICUser on $dateU";
	if($addEdit=="E")$RecordLog="Edit by $NICUser on $dateU";
	
    if ($Description == "") {
        $msg.= "Please enter the Salary Scale.<br>";
    }
	if($msg==''){ 
		if($addEdit=='A'){
				$countSql="SELECT * FROM CD_TG_SalaryScale where ServiceID='$ServiceID'";
				$isAvailable=$db->rowAvailable($countSql);
				if($isAvailable==1){
					$msg.= "Duplicate Salary Scale.<br>";
				}else{
				$queryMainSave = "INSERT INTO CD_TG_SalaryScale
				   (ServiceID,Description,RecordLog)
			 VALUES
				   ('$ServiceID','$Description','$RecordLog')";
				$db->runMsSqlQuery($queryMainSave);
				}
		}else if($addEdit=='E'){
			$queryMainUpdate = "UPDATE CD_TG_SalaryScale SET Description='$Description',RecordLog='$RecordLog' WHERE ServiceID='$ServiceID'"; //ServiceID='$ServiceID',
			   
			$db->runMsSqlQuery($queryMainUpdate);
		}
	}
	$fm="";
	$sqlSrch="SELECT * FROM CD_TG_SalaryScale where ServiceID!='' ORDER BY ID ASC";  
	$stmtP = $db->runMsSqlQuery($sqlSrch);
}

?>
<form method="post" action="<?php echo $ttle ?>-11-<?php echo $menu ?>.html" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
        <?php if($msg!=''){//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
   	  
  <div class="mcib_middle1" style="width:700px;">
    <div class="mcib_middle_full">
          <div class="form_error"><?php echo $msg; echo $_SESSION['success_update'];$_SESSION['success_update']="";?><?php echo $_SESSION['fail_update'];$_SESSION['fail_update']="";?></div>
    </div>
    <?php }?>
<table width="100%" cellpadding="0" cellspacing="0">
			  <tr>
			    <td valign="top" style="border-bottom:1px; border-bottom-style:solid;"><table width="100%" cellspacing="2" cellpadding="2">
                    
                    <tr>
                      <td width="24%">Service Type :</td>
                      <td width="36%"><select class="select5" id="ServiceIDSrc" name="ServiceIDSrc">
                        <option value="">-Select-</option>
                        <?php
                            $sql = "SELECT ServCode, ServiceName
FROM            CD_Service 
						 WHERE ServCode!=''";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                echo '<option value=' . $row['ServCode'] . '>' . $row['ServiceName'] . '</option>';
                            }
                            ?>
                      </select></td>
                      <td width="13%"><input name="FrmSrch" type="submit" id="FrmSrch" style="background-image: url(../cms/images/searchN.png); width:84px; height:26px; background-color:transparent; border:none; cursor:pointer;" value="" /></td>
                      <td width="14%" align="right" valign="middle" style="padding-top:7px;"><a href="masterFile-11-<?php echo $menu ?>--A.html"><img src="../cms/images/addnew.png" alt="" width="90" height="26" /></a></td>
                      <td width="13%" align="right" valign="middle" style="padding-top:7px;"><a href="masterFile-11-<?php echo $menu ?>.html"><img src="../cms/images/clearN.png" alt="" width="80" height="26" /></a></td>
                    </tr>
                    </table></td>
      </tr>
			  <tr>
			    <td valign="top"><span style="color:#090; font-weight:bold;"><?php if($fm=='A')echo "Insert the data"; if($fm=='E') echo "Modify the existing details";?></span>&nbsp;</td>
      </tr>
     
	  <tr>
                  <td width="56%" valign="top">
                  <?php if($fm=='E' || $fm=='A'){?>
                  <table width="100%" cellspacing="2" cellpadding="2">
                    <tr>
                      <td width="25%">Service Type <span class="form_error_sched">*</span></td>
                      <td width="2%">:</td>
                      <td width="73%"><?php //echo $ServiceID ?>
                      <select class="select5" id="ServiceID" name="ServiceID" <?php if($fm=='E'){?> disabled="disabled" <?php }?>>
                            <option value="">-Select-</option>
                            <?php
                            $sql = "SELECT ServCode, ServiceName
FROM            CD_Service 
						 WHERE ServCode!=''";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$ServCode=trim($row['ServCode']);
								$ServiceName=$row['ServiceName'];
								
								$seltebr="";
								if($ServCode==$ServiceID){
									$seltebr="selected";
								}
								echo "<option value=\"$ServCode\" $seltebr>$ServiceName</option>";
                               // echo '<option value=' . $row['ServCode'] . '>' . $row['ServiceName'] . '</option>';
                            }
                            ?>
                        </select>
                      <?php if($fm=='E'){?>  
                      <input type="hidden" name="ServiceID" value="<?php echo $ServiceID; ?>" />
                      <?php }?>
                      <input type="hidden" name="cat" value="<?php echo $cat; ?>" />
                      <input type="hidden" name="AED" value="<?php echo $fm; ?>" />
                      <input type="hidden" name="id" value="<?php echo $id; ?>" />
                      <input type="hidden" name="tblName" value="<?php echo $tablename; ?>" />
                      <input type="hidden" name="redirect_page" value="<?php echo $redirect_page ?>" />
                      <input type="hidden" name="vID" value="<?php echo $id; ?>" />
                      <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
                      <input type="hidden" name="mainID" value="<?php echo $primaryid; ?>" /></td>
                    </tr>
                    <tr>
                      <td>Salary Scale <span class="form_error_sched">*</span></td>
                      <td>:</td>
                      <td><input name="Description" type="text" class="input2" id="Description" value="<?php echo $Description ?>" size="1000"/></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td><input name="FrmSubmit" type="submit" id="FrmSubmit" style="background-image: url(../cms/images/saveform.jpg); width:98px; height:26px; background-color:transparent; border:none;" value="" /></td>
                    </tr>
                    </table>
                    <?php }?>
        </td>
        </tr>
        <?php if(isset($_POST["FrmSrch"]) || $fm==''){ ?>
                <tr>
                  <td><?php echo $TotaRows ?> Record(s) found.</td>
                </tr>
                <tr>
                    <td bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                      <tr>
                        <td width="5%" height="25" align="center" bgcolor="#999999">#</td>
                        <td width="34%" align="center" bgcolor="#999999">Type</td>
                        <td width="52%" align="center" bgcolor="#999999">Scale</td>
                        <td width="9%" align="center" bgcolor="#999999">Modify</td>
                      </tr>
                      <?php 
					  $i=1;
                      while ($rowP = sqlsrv_fetch_array($stmtP, SQLSRV_FETCH_ASSOC)) {
							$ServiceID=trim($rowP['ServiceID']);
							$Description=trim($rowP['Description']);
							$IdRec=$rowP['ID'];
							
							$sql = "SELECT ServiceName
FROM            CD_Service 
						 WHERE ServCode='$ServiceID'";
                            $stmt = $db->runMsSqlQuery($sql);
                            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);						
                            $ServiceName=$row['ServiceName'];
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $ServiceName ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $Description ?></td>
                        <td bgcolor="#FFFFFF" align="center"><a href="<?php echo "$ttle-$pageid-$menu-$IdRec-E.html";?>">Click</a></td>
                      </tr>
                      <?php }?>
                    </table></td>
          </tr>
                <tr>
                  <td>&nbsp;</td>
                </tr>
               <?php }?>
          </table>
           </div>
    
    </form>