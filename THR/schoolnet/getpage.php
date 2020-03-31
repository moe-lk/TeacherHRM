<?php 
require_once '../error_handle.php';
set_error_handler("errorHandler");
register_shutdown_function("shutdownHandler");
session_start();
include '../db_config/DBManager.php';
$db = new DBManager();
//header("Cache-control: private"); //IE 6 Fix
$r = $_POST['r'];
$x = $_POST['x'];
$q = $_REQUEST['q'];
$iCID = $_REQUEST['iCID'];
$iCIK = $_REQUEST['iCIK'];
$srch = $_REQUEST['srch'];
$ctgry = $_REQUEST['ctgry'];
$brnd = $_REQUEST['brnd'];
$usr = $_REQUEST['usr'];
$id1=$_SESSION['uID_cp'];
// $Division = $_REQUEST['cmbDivision'];
$replace_data_new=array("'","/","!","&","*"," ","-","@",'"',"?",":","�","�",".");



if($q=='districtList'){
	$details="<select  style=\"width:260px;\" id=\"cmbDistrict\" name=\"cmbDistrict\" onchange=\"Javascript:show_zone('zonelist', this.options[this.selectedIndex].value,'');\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT DistCode,DistName FROM CD_Districts where ProCode='$iCID' order by DistName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$CenCode=trim($row['DistCode']);
			$InstitutionName=$row['DistName'];
			$seltebr="";
			
			$details.="<option value=\"$CenCode\" $seltebr>$InstitutionName</option>";
		}
		
		echo $details.="</select><input type=\"checkbox\" name=\"groupBy[]\" id=\"chkDistrict\" value=\"DIS\"></div>";
		
}

if($q=='zonelist'){
	$details="<select style=\"width:260px;\" id=\"cmbZone\" name=\"cmbZone\" onchange=\"Javascript:show_division('divisionList', this.options[this.selectedIndex].value,'$iCID');\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT CenCode,InstitutionName FROM CD_Zone where DistrictCode='$iCID' order by InstitutionName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$CenCode=trim($row['CenCode']);
			$InstitutionName=$row['InstitutionName'];
			$seltebr="";
			
			$details.="<option value=\"$CenCode\" $seltebr>$InstitutionName</option>";
		}
		
		echo $details.="</select><input type=\"checkbox\" name=\"groupBy[]\" id=\"chkZone\" value=\"ZON\"></div>";
}
if($q=='divisionList'){
	$details="<select style=\"width:260px;\" id=\"cmbDivision\" name=\"cmbDivision\" onchange=\"Javascript:show_cences('censesList', this.options[this.selectedIndex].value,'$iCID');\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT CenCode,InstitutionName FROM CD_Division where ZoneCode='$iCID' order by InstitutionName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$CenCode=trim($row['CenCode']);
			$InstitutionName=$row['InstitutionName'];
			$seltebr="";
			
			$details.="<option value=\"$CenCode\" $seltebr>$InstitutionName</option>";
		}
		
		echo $details.="</select><input type=\"checkbox\" name=\"groupBy[]\" id=\"chkDivision\" value=\"DIV\"></div>";
}

if($q=='censesList'){
	
	$params1 = array(
        array($iCID, SQLSRV_PARAM_IN)
    );
	$sql = "{call SP_TG_GetSchoolsFor_SelectedDivision(?)}";
    $details="<select style=\"width:894px;\" id=\"cmbSchool\" name=\"cmbSchool\">
			  <option value=\"\">-Select-</option>";
	
    $stmt = $db->runMsSqlQuery($sql, $params1);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$CenCode=trim($row['CenCode']);
		$InstitutionName=$row['InstitutionName'];
		
        $details.="<option value=\"$CenCode\" $seltebr>$InstitutionName [$CenCode]</option>";
    }  
	echo $details.="</select><input type=\"checkbox\" name=\"groupBy[]\" id=\"chkSchool\" value=\"SCH\">";
	
	$divi = $iCID;
}


if($q=='divisionlst'){
	$details="<select id=\"cmbDivision\" name=\"cmbDivision\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT DSCode,DSName FROM CD_DSec where DistName='$iCID' order by DSName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$DSCoded=trim($row['DSCode']);
			$DSNamed=$row['DSName'];
			$seltebr="";
			if($DSCoded==$DSCode){
				$seltebr="selected";
			}
			$details.="<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
		}
		
		  echo $details.="</select>";

}

if($q=='divisionlstCurrent'){
	$details="<select class=\"select2a_n1\" id=\"DSCodeC\" name=\"DSCodeC\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT DSCode,DSName FROM CD_DSec where DistName='$iCID' order by DSName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$DSCoded=trim($row['DSCode']);
			$DSNamed=$row['DSName'];
			$seltebr="";
			if($DSCoded==$DSCode){
				$seltebr="selected";
			}
			$details.="<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
		}
		
		echo $details.="</select>";
	
}
if($q=='change_pw'){
	echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"1\">
                        <tr>
                          <td width=\"39%\"><strong><span class=\"form_error\">*</span>Password</strong></td>
                          <td width=\"3%\">:</td>
                          <td width=\"58%\"><input name=\"CurPassword\" type=\"text\" class=\"input3\" id=\"CurPassword\" value=\"\"/></td>
                        </tr>
                        <tr>
                          <td><strong><span class=\"form_error\">*</span>Re-type Password</strong></td>
                          <td>:</td>
                          <td><input name=\"CurPasswordRT\" type=\"text\" class=\"input3\" id=\"CurPasswordRT\" value=\"\" /><input type=\"hidden\" name=\"chngepw\" value=\"Y\"/></td>
                        </tr>
                      </table>";
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
// var_dump($divi);
if($q =='censesStatusList'){
	$params1 = array(
        array($iCIK, SQLSRV_PARAM_IN)
	);
	// var_dump($divi);
	$sql = "SELECT CenCode ,InstitutionName
	FROM   CD_CensesNo where SchoolStatus='$iCIK' AND DivisionCode = '$divi'";



    $details="<select style=\"width:853px;\" id=\"cmbSchool\" name=\"cmbSchool\">
			  <option value=\"\">-Select-</option>";
	
    $stmt = $db->runMsSqlQuery($sql);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
		$CenCode=trim($row['CenCode']);
		$InstitutionName=$row['InstitutionName'];
		
        $details.="<option value=\"$CenCode\" $seltebr>$InstitutionName [$CenCode]</option>";
    }  
	echo $details.="</select><input type=\"checkbox\" name=\"groupBy[]\" id=\"chkSchool\" value=\"SCH\">";
		
	
}

if ($e == 'getDataAccordingToSCType') {
    $cmbSchoolType = $_POST["cmbSchoolType"];
    $cmbProvince = $_POST["cmbProvince"];
    $cmbDistrict = $_POST["cmbDistrict"];
    $cmbZone = $_POST["cmbZone"];
    $cmbDivision = $_POST["cmbDivision"];
    $cmbSchoolStatus = $_POST["cmbSchoolStatus"];
    // echo "This";
    

    if ($cmbProvince == "")
        $cmbProvince = null;
    if ($cmbDistrict == "")
        $cmbDistrict = null;
    if ($cmbZone == "")
        $cmbZone = null;
    if ($cmbDivision == "")
        $cmbDivision = null;
    if ($cmbSchoolType == "")
        $cmbSchoolType = null;
    if ($cmbSchoolStatus == "")
        $cmbSchoolStatus = null;

    $LOGGEDUSERID = $_POST["txtLoggedUser"];
    // $nicNO; // 172839946V
    $ACCESSLEVEL = $_POST["txtAccessLevel"];
    // $accLevel; // 



    $params5 = array(
        array($LOGGEDUSERID, SQLSRV_PARAM_IN),
        array($ACCESSLEVEL, SQLSRV_PARAM_IN),
        array($cmbSchoolType, SQLSRV_PARAM_IN),
        array($cmbProvince, SQLSRV_PARAM_IN),
        array($cmbDistrict, SQLSRV_PARAM_IN),
        array($cmbZone, SQLSRV_PARAM_IN),
        array($cmbDivision, SQLSRV_PARAM_IN),
        array($cmbSchoolStatus, SQLSRV_PARAM_IN)
    );
    
    $sql = "{call SP_TG_GetCensesFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?, ?)}";
    $dataSchool = "<option value=\"\">All</option>";
    $stmt = $db->runMsSqlQuery($sql, $params5);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $dataSchool.= '<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
    }



    $result = array();

    $result[0] = $dataSchool;
    echo json_encode($result);
}

if ($r == 'getDataAccordingToStatus') {
    $cmbSchoolType = $_POST["cmbSchoolType"];
    $cmbProvince = $_POST["cmbProvince"];
    $cmbDistrict = $_POST["cmbDistrict"];
    $cmbZone = $_POST["cmbZone"];
    $cmbDivision = $_POST["cmbDivision"];
    $cmbSchoolStatus = $_POST["cmbSchoolStatus"];
    // echo "This";
    

    if ($cmbProvince == "")
        $cmbProvince = null;
    if ($cmbDistrict == "")
        $cmbDistrict = null;
    if ($cmbZone == "")
        $cmbZone = null;
    if ($cmbDivision == "")
        $cmbDivision = null;
    if ($cmbSchoolType == "")
        $cmbSchoolType = null;
    if ($cmbSchoolStatus == "")
        $cmbSchoolStatus = null;

    $LOGGEDUSERID = $nicNO; // 172839946V
    $ACCESSLEVEL = $accLevel; // 



    $params4 = array(
        array($LOGGEDUSERID, SQLSRV_PARAM_IN),
        array($ACCESSLEVEL, SQLSRV_PARAM_IN),
        array($cmbSchoolType, SQLSRV_PARAM_IN),
        array($cmbProvince, SQLSRV_PARAM_IN),
        array($cmbDistrict, SQLSRV_PARAM_IN),
        array($cmbZone, SQLSRV_PARAM_IN),
        array($cmbDivision, SQLSRV_PARAM_IN),
        array($cmbSchoolStatus, SQLSRV_PARAM_IN)
    );

    
    $sql = "{call SP_TG_GetCensesFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?, ?)}";
    $dataSchool = "<option value=\"\">All</option>";
    $stmt = $db->runMsSqlQuery($sql, $params4);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $dataSchool.= '<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
    }



    $result = array();

    $result[0] = $dataSchool;
    echo json_encode($result);
}
// echo json_encode($r);

if ($x == "ioi") {
    $cmbSchoolType = $_POST["cmbSchoolType"];
    $cmbProvince = $_POST["cmbProvince"];
    $cmbDistrict = $_POST["cmbDistrict"];
    $cmbZone = $_POST["cmbZone"];
    $cmbDivision = $_POST["cmbDivision"];
    // $cmbSchoolStatus = $_POST["cmbSchoolStatus"];
    $LOGGEDUSERID = $nicNO; // 172839946V
    $ACCESSLEVEL = $accLevel; //     3000
   
    if ($cmbProvince == "")
        $cmbProvince = null;
    if ($cmbDistrict == "")
        $cmbDistrict = null;
    if ($cmbZone == "")
        $cmbZone = null;
    if ($cmbDivision == "")
        $cmbDivision = null;
    if ($cmbSchoolType == "")
        $cmbSchoolType = null;

    $params1 = array(
        array($LOGGEDUSERID, SQLSRV_PARAM_IN),
        array($ACCESSLEVEL, SQLSRV_PARAM_IN),
        array($cmbSchoolType, SQLSRV_PARAM_IN),
        array($cmbProvince, SQLSRV_PARAM_IN),
        array($cmbDistrict, SQLSRV_PARAM_IN),
        array($cmbZone, SQLSRV_PARAM_IN),
        array($cmbDivision, SQLSRV_PARAM_IN)
    );

    $sql = "{call SP_TG_GetCensesFor_LooggedUser( ?, ?, ?, ?, ?, ?, ?)}";
    $dataSchool = "<option value=\"\">All</option>";
    $stmt = $db->runMsSqlQuery($sql, $params1);
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $dataSchool.= '<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
    }
    // echo $dataSchool;
	$result = array();
	var_dump($result);
    $result[0] = $dataSchool;
	echo json_encode($result);
	
}
// echo json_encode($x);
?> 
