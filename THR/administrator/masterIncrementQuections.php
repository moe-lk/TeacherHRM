<!----><link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
$msg=""; 
if(isset($_POST["FrmSrch"]) || $fm==''){
	//$QuestionTypeSrc=$_REQUEST['QuestionType'];
	$sqlSrch="SELECT * FROM CD_TG_IncrementQuestions where QuestionType!=''";  
//	if($QuestionTypeSrc)$sqlSrch.=" and QuestionType='$QuestionTypeSrc'";
	$stmtP = $db->runMsSqlQuery($sqlSrch);
	$TotaRows=$db->rowCount($sqlSrch);
	//if($TotaRows==0)$fm="A";
	//$rowP = sqlsrv_fetch_array($stmtP, SQLSRV_FETCH_ASSOC);
	 //echo $TotaRows=$db->rowCount($stmtP);echo $sqlSrch;
}
if($fm=='E'){
	$sqlSrch="SELECT * FROM CD_TG_IncrementQuestions where ID='$id'";  
	$stmtE= $db->runMsSqlQuery($sqlSrch);
	$rowE = sqlsrv_fetch_array($stmtE, SQLSRV_FETCH_ASSOC);
	$QuestionType = trim($rowE['QuestionType']);
	$QuestionInc = trim($rowE['QuestionInc']);
	$OrderID = trim($rowE['OrderID']);
}

if(isset($_POST["FrmSubmit"])){	
	//echo "hi";
	$addEdit=$_REQUEST['AED'];
	$recID=$_REQUEST['id'];
	$QuestionType=$_REQUEST['QuestionType'];
	$QuestionInc=trim($_REQUEST['QuestionInc']);
	$OrderID=trim($_REQUEST['OrderID']);
	$dateU=date('Y-m-d H:i:s');
	if($addEdit=="A")$RecordLog="Add by $NICUser on $dateU";
	if($addEdit=="E")$RecordLog="Edit by $NICUser on $dateU";
	
    if ($QuestionInc == "") {
        $msg.= "Please enter the Question.<br>";
    }
	if($msg==''){
		if($addEdit=='A'){
				$countSql="SELECT * FROM CD_TG_IncrementQuestions where QuestionInc='$QuestionInc'";
				$isAvailable=$db->rowAvailable($countSql);
				if($isAvailable==1){
					$msg.= "Duplicate Question.<br>";
				}else{
				$queryMainSave = "INSERT INTO CD_TG_IncrementQuestions
				   (QuestionType,QuestionInc,OrderID,RecordLog)
			 VALUES
				   ('$QuestionType','$QuestionInc','$OrderID','$RecordLog')";
				$db->runMsSqlQuery($queryMainSave);
				}
		}else if($addEdit=='E'){
			 $queryMainUpdate = "UPDATE CD_TG_IncrementQuestions SET QuestionType='$QuestionType',QuestionInc='$QuestionInc',OrderID='$OrderID',RecordLog='$RecordLog' WHERE ID='$recID'";
			   
			$db->runMsSqlQuery($queryMainUpdate);
		}
	}
	$fm="";
	$sqlSrch="SELECT * FROM CD_TG_IncrementQuestions where QuestionType!=''";  
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
                      <td width="24%">&nbsp;</td>
                      <td width="36%">&nbsp;</td>
                      <td width="13%"></td>
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
                      <td width="25%">Question Type <span class="form_error_sched">*</span></td>
                      <td width="2%">:</td>
                      <td width="73%"><select class="select5" id="QuestionType" name="QuestionType">
                        <option value="Teacher" <?php if($QuestionType=='Teacher') echo "selected";?>>Teacher</option>
                        <option value="Principal" <?php if($QuestionType=='Principal') echo "selected";?>>Principal</option>
                            
                      </select>
                      <input type="hidden" name="cat" value="<?php echo $cat; ?>" />
                      <input type="hidden" name="AED" value="<?php echo $fm; ?>" />
                      <input type="hidden" name="id" value="<?php echo $id; ?>" />
                      <input type="hidden" name="tblName" value="<?php echo $tablename; ?>" />
                      <input type="hidden" name="redirect_page" value="<?php echo $redirect_page ?>" />
                      <input type="hidden" name="vID" value="<?php echo $id; ?>" />
                      <input type="hidden" name="mode" value="<?php echo $mode; ?>" />
                      <input type="hidden" name="mainID" value="<?php echo $primaryid; ?>" />
                      </td>
                    </tr>
                    <tr>
                      <td>Question<span class="form_error_sched">*</span></td>
                      <td>:</td>
                      <td><input name="QuestionInc" type="text" class="input2" id="QuestionInc" value="<?php echo $QuestionInc ?>"/></td>
                    </tr>
                    <tr>
                      <td>Order<span class="form_error_sched">*</span></td>
                      <td>:</td>
                      <td><input name="OrderID" type="text" class="input4" id="OrderID" value="<?php echo $OrderID ?>"/></td>
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
                        <td width="12%" align="center" bgcolor="#999999">Type</td>
                        <td width="50%" align="center" bgcolor="#999999">Question</td>
                        <td width="9%" align="center" bgcolor="#999999">Order</td>
                        <td width="13%" align="center" bgcolor="#999999">Status</td>
                        <td width="11%" align="center" bgcolor="#999999">Modify</td>
                      </tr>
                      <?php 
					  $i=1;
                      while ($rowP = sqlsrv_fetch_array($stmtP, SQLSRV_FETCH_ASSOC)) {
							$QuestionType=trim($rowP['QuestionType']);
							$QuestionInc=trim($rowP['QuestionInc']);
							$OrderID=trim($rowP['OrderID']);
							$IdRec=$rowP['ID'];
							$StatusOf=$rowP['StatusOf'];
							
							if($StatusOf=='Y') { $status_r="Active"; } else { $status_r="Deactive"; }
	
							if($status_r=='NULL' || $status_r=='') $status_r="Deactive";
							
							if($status_r=="Deactive") $sw_enable="<a href=\"javascript:aedWin1('$IdRec','ED','irq','$status_r','CD_TG_IncrementQuestions','ID','$ttle-$pageid-$menu.html')\">Deactive</a>";	
							if($status_r=="Active") $sw_enable="<a href=\"javascript:aedWin1('$IdRec','ED','irq','$status_r','CD_TG_IncrementQuestions','ID','$ttle-$pageid-$menu.html')\">Active</a>";
	
					  ?>
                      <tr>
                        <td height="20" bgcolor="#FFFFFF"><?php echo $i++ ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $QuestionType ?></td>
                        <td bgcolor="#FFFFFF"><?php echo $QuestionInc ?></td>
                        <td bgcolor="#FFFFFF" align="center"><?php echo $OrderID ?></td>
                        <td bgcolor="#FFFFFF" align="center"><?php echo $sw_enable ?></td>
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