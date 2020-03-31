<!----><link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$msg="";
if(isset($_POST["FrmSrch"]) || $fm==''){
	$DescriptionSrc=$_REQUEST['DescriptionSrc'];
	$sqlSrch="SELECT * FROM CD_Qualif where Description!=''";  
	if($DescriptionSrc)$sqlSrch.=" and Description like '%$DescriptionSrc%'";
	$sqlSrch.=" order by Description asc";
	$stmtP = $db->runMsSqlQuery($sqlSrch);
	$TotaRows=$db->rowCount($sqlSrch);
	//if($TotaRows==0)$fm="A";
	//$rowP = sqlsrv_fetch_array($stmtP, SQLSRV_FETCH_ASSOC);
	 //echo $TotaRows=$db->rowCount($stmtP);echo $sqlSrch;
}
if($fm=='E'){
	$sqlSrch="SELECT * FROM CD_Qualif where Qcode='$id'";  
	$stmtE= $db->runMsSqlQuery($sqlSrch);
	$rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC);
	$Qcode = trim($rowE['Qcode']);
	$Description = trim($rowE['Description']);
	$Category= trim($rowE['Category']);
}

if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$addEdit=$_REQUEST['AED'];
	$Qcode=$_REQUEST['Qcode'];//exit();
	$Description=trim($_REQUEST['Description']);
	$Category=trim($_REQUEST['Category']);
	$dateU=date('Y-m-d H:i:s');
	if($addEdit=="A")$RecordLog="Add by $NICUser on $dateU";
	if($addEdit=="E")$RecordLog="Edit by $NICUser on $dateU";
	
	$sqlQCat="Select Level from CD_QualificationCategory where Code='$Category'";
	$stmtQc= $db->runMsSqlQuery($sqlQCat);
	$rowQc = sqlsrv_fetch_array($stmtQc, SQLSRV_FETCH_ASSOC);
	$Level = trim($rowQc['Level']);
	
    if ($Description == "") {
        $msg.= "Please enter the Qualification Name.<br>";
    }
	if($msg==''){
		if($addEdit=='A'){
			$countSql="SELECT * FROM CD_Qualif where Qcode='$Qcode'";
			$isAvailable=$db->rowAvailable($countSql);
			if($isAvailable==1){
				$msg.= "Duplicate Qualification Code.<br>";
			}else{
				$queryMainSave = "INSERT INTO CD_Qualif
				   (Qcode,Description,Category,Level,RecordLog)
			 VALUES
				   ('$Qcode','$Description','$Category','$Level','$RecordLog')";
				$db->runMsSqlQuery($queryMainSave);	
			}
		}else if($addEdit=='E'){
			$queryMainUpdate = "UPDATE CD_Qualif SET Qcode='$Qcode',Description='$Description', Category='$Category',Level='$Level',RecordLog='$RecordLog' WHERE Qcode='$Qcode'";
			   
			$db->runMsSqlQuery($queryMainUpdate);
		}
	}
	$fm="";
	$sqlSrch="SELECT * FROM CD_Qualif where Description!=''";  
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
                      <td width="24%">Qualification Name :</td>
                      <td width="36%"><input name="DescriptionSrc" type="text" class="input2_n" id="DescriptionSrc" value="<?php echo $DescriptionSrc ?>"/></td>
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
                      <td width="25%">Code <span class="form_error_sched">*</span></td>
                      <td width="2%">:</td>
                      <td width="73%">
                      <input name="Qcode" type="text" class="input3" id="Qcode" value="<?php echo $Qcode ?>" <?php if($fm=='E'){?>readonly="readonly"<?php }?>/>
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
                      <td>Qualification Name<span class="form_error_sched"> *</span></td>
                      <td>:</td>
                      <td><input name="Description" type="text" class="input2" id="Description" value="<?php echo $Description ?>"/></td>
                    </tr>
                    <tr>
                      <td>Category</td>
                      <td>:</td>
                      <td><select class="select2a_n" id="Category" name="Category">
                        <!--<option value="">School Name</option>-->
                        <?php
                            $sql = "SELECT Code,Description FROM CD_QualificationCategory order by Description asc";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$DistCoded=trim($row['Code']);
								$DistNamed=$row['Description'];
								$seltebr="";
								if($DistCoded==$Category){
									$seltebr="selected";
								}
                                echo "<option value=\"$DistCoded\" $seltebr>$DistNamed</option>";
                            }
                            ?>
                      </select></td>
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
                        <td width="7%" height="25" align="center" bgcolor="#999999">#</td>
                        <td width="17%" align="center" bgcolor="#999999">Code</td>
                        <td width="56%" align="center" bgcolor="#999999">Qualification Name</td>
                        <td width="20%" align="center" bgcolor="#999999">Modify</td>
                      </tr>
                      <?php 
					  $i=1;
                      while ($rowP = sqlsrv_fetch_array($stmtP, SQLSRV_FETCH_ASSOC)) {
							$Qcode=trim($rowP['Qcode']);
							$Description=trim($rowP['Description']);
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $Qcode ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $Description ?></td>
                        <td bgcolor="#FFFFFF" align="center"><a href="<?php echo "$ttle-$pageid-$menu-$Qcode-E.html";?>">Click</a></td>
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