<?php 
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();

//header("Cache-control: private"); //IE 6 Fix
$q = $_REQUEST['q'];
$iCID = $_REQUEST['iCID'];
$srch = $_REQUEST['srch'];
$ctgry = $_REQUEST['ctgry'];
$brnd = $_REQUEST['brnd'];
$usr = $_REQUEST['usr'];
$id1=$_SESSION['uID_cp'];
$replace_data_new=array("'","/","!","&","*"," ","-","@",'"',"?",":","“","”",".");

if($q=='classList'){
	
	$details="<table width=\"100%\" cellspacing=\"1\" cellpadding=\"1\">";
	 
	$totSql="SELECT ID,ClassID from TG_SchoolClassStructure where SchoolID='$srch' and GradeID='$iCID'";
	$stmt = $db->runMsSqlQuery($totSql);
	 while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		 $ID=$row['ID'];
		 $ClassID=$row['ClassID'];
	 
	 //echo $PeriodsPerWeek;	
	
	    $details.="<tr>
		  <td width=\"8%\"><input type=\"checkbox\" name=\"ClassGrouped[]\" id=\"ClassGrouped[]\" value=\"$ID\"/></td>
		  <td width=\"92%\">$ClassID</td>
		</tr>";
	 }	
	echo $details.="</table>";
}
if($q=='periodCount'){
	$totSql="SELECT SUM(PeriodsPerWeek) AS 'PeriodsPerWeek' from TG_SchoolSubjectMaster where SchoolID='$srch' and GradeID='$iCID'";
	$stmt = $db->runMsSqlQuery($totSql);
	 while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		 $PeriodsPerWeek=$row['PeriodsPerWeek'];
	 }
	 //echo $PeriodsPerWeek;	
	 echo $details="<table width=\"100%\" cellspacing=\"1\" cellpadding=\"1\">
                          <tr>
                            <td width=\"100%\">Already Inserted Periods : $PeriodsPerWeek</td>
                            
                          </tr>
                        </table>";			
	//$TotaRows=$db->rowCount($totSql);
	
}

if($q=='show_incharge'){
	//echo "hi";
	$totSql="SELECT TeacherInChargeID from TG_SchoolLearningPoints where SchoolID='$srch' and ID='$iCID'";
	$stmtw = $db->runMsSqlQuery($totSql);
	 while ($roww = sqlsrv_fetch_array($stmtw, SQLSRV_FETCH_ASSOC)) {
		 $TeacherInChargeID=$roww['TeacherInChargeID'];
	 }
	 //echo $TeacherInChargeID;
	 $details="<select class=\"select2a\" id=\"TeacherInChargeID\" name=\"TeacherInChargeID\">
				<option value=\"\">-Select-</option>";
				
				$sql = "SELECT        TeacherMast.CurResRef, StaffServiceHistory.InstCode, CD_CensesNo.InstitutionName, TeacherMast.ID, TeacherMast.SurnameWithInitials, 
			 TeacherMast.FullName,TeacherMast.NIC
	FROM            TeacherMast INNER JOIN
			 StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID INNER JOIN
			 CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
			 where StaffServiceHistory.InstCode='$srch'
			 order by TeacherMast.SurnameWithInitials";
				$stmt = $db->runMsSqlQuery($sql);
				while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
					$NIC=trim($row['NIC']);
					$SurnameWithInitials=$row['SurnameWithInitials'];
					$selText="";
					if($NIC==$TeacherInChargeID)$selText="selected";
					$details.="<option value=\"$NIC\" $selText>$SurnameWithInitials</option>";
				}
				
		  echo $details.=" </select>";
}

if($q=='districtList'){
	$details="<select class=\"select2a_n\" id=\"DistrictCode\" name=\"DistrictCode\" onchange=\"Javascript:show_zone('zonelist', this.options[this.selectedIndex].value,'');\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT DistCode,DistName FROM CD_Districts where ProCode='$iCID' order by DistName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$CenCode=trim($row['DistCode']);
			$InstitutionName=$row['DistName'];
			$seltebr="";
			
			$details.="<option value=\"$CenCode\" $seltebr>$InstitutionName</option>";
		}
		
		  echo $details.="</select>";
}

if($q=='zonelist'){
	$details="<select class=\"select2a_n\" id=\"ZoneCode\" name=\"ZoneCode\" onchange=\"Javascript:show_division('divisionList', this.options[this.selectedIndex].value,'$iCID');\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT CenCode,InstitutionName FROM CD_Zone where DistrictCode='$iCID' order by InstitutionName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$CenCode=trim($row['CenCode']);
			$InstitutionName=$row['InstitutionName'];
			$seltebr="";
			
			$details.="<option value=\"$CenCode\" $seltebr>$InstitutionName</option>";
		}
		
		  echo $details.="</select>";
}
if($q=='divisionList'){
	$details="<select class=\"select2a_n\" id=\"DivisionCode\" name=\"DivisionCode\" onchange=\"Javascript:show_cences('censesList', this.options[this.selectedIndex].value,'$iCID');\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT CenCode,InstitutionName FROM CD_Division where ZoneCode='$iCID' order by InstitutionName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$CenCode=trim($row['CenCode']);
			$InstitutionName=$row['InstitutionName'];
			$seltebr="";
			
			$details.="<option value=\"$CenCode\" $seltebr>$InstitutionName</option>";
		}
		
		  echo $details.="</select>";
}
if($q=='censesList'){
	$params1 = array(
        array($iCID, SQLSRV_PARAM_IN)
    );
	$sql = "{call SP_TG_GetSchoolsFor_SelectedDivision(?)}";
    $details="<select class=\"select2a_n\" id=\"InstCode\" name=\"InstCode\">
			  <option value=\"\">-Select-</option>";
	
    $stmt = $db->runMsSqlQuery($sql, $params1);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$CenCode=trim($row['CenCode']);
		$InstitutionName=$row['InstitutionName'];
		
        $details.="<option value=\"$CenCode\" $seltebr>$InstitutionName</option>";
    }  
	echo $details.="</select>";

}
if($q=='captha'){
	function genRandomString() {
		$length = 8;
		$characters = "0123456789abcdefghijklmnopqrstuvwxyz";
	   	for ($p = 0; $p < $length; $p++) {
			//echo $p;
			$string .= $characters[mt_rand(0, strlen($characters))];
		}	
		return $string;
		//echo $string;
	}
	$iRand=genRandomString();
	echo $val=chunk_split($iRand);
	echo "<input type=\"hidden\" name=\"captch\" value=\"$iRand\">";	
}
if($q=='dealerDis'){
 echo  $dealer="<label for=\"input3\" class=\"sideholderlbl\" style=\"width:230px;\">Old Password</label>
  <input name=\"vPasswordOld\" type=\"password\" id=\"vPasswordOld\" class=\"sideholderinput\" />
  <label for=\"input3\" class=\"sideholderlbl\" style=\"width:230px;\">New Password </label>
  <input name=\"vPassword1\" type=\"password\" id=\"vPassword1\" class=\"sideholderinput\" />
  <label for=\"input3\" class=\"sideholderlbl\" style=\"width:230px;\">Re-type Password </label>
  <input name=\"vPassword2\" type=\"password\" id=\"vPassword2\" class=\"sideholderinput\" /><br><br><br><br>";
  echo "<input type=\"hidden\" name=\"changedPasswordYes\" value=\"Y\" >";
}
?> 