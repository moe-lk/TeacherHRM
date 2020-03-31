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
$replace_data_new=array("'","/","!","&","*"," ","-","@",'"',"?",":","�","�",".");

/*start zone list view */
//echo $q;exit();




if($q=='divisionlstDivision'){/// onchange=\"Javascript:show_division('divisionlst', this.options[this.selectedIndex].value, '$iCID');\"
	$details="<select class=\"select2a_n\" id=\"DivisionCode\" name=\"DivisionCode\" onchange=\"Javascript:show_cences('censesList', this.options[this.selectedIndex].value,'$iCID');\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT CenCode,InstitutionName FROM CD_Division where DistrictCode='$srch' and ZoneCode='$iCID' order by InstitutionName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$DSCoded=trim($row['CenCode']);
			$DSNamed=$row['InstitutionName'];
			$seltebr="";
			if($DSCoded==$DivisionCode){
				$seltebr="selected";
			}
			$details.="<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
		}
		
		
							
		  echo $details.="</select>";
	
}


if($q=='districtListUser'){
	//echo "hi";
	$details="<select class=\"select2a_n\" id=\"DistrictCode\" name=\"DistrictCode\" onchange=\"Javascript:show_zone('zonelistUser', this.options[this.selectedIndex].value,'');\">
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

if($q=='zonelistUser'){
                
	$details="<select class=\"select2a_n\" id=\"InstCode\" name=\"InstCode\">
			  <option value=\"\">-Select-</option>";
			  
		 $sql = "SELECT [InstType]
      ,[CenCode]
      ,[InstitutionName]
      
  FROM [dbo].[CD_CensesNo] where CenCode LIKE '%ZN%' and DistrictCode='$iCID'
  order by InstitutionName";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$CenCode=trim($row['CenCode']);
			$InstitutionName=addslashes(str_replace("EDUCATION RESOURCE CENTRE ","",$row['InstitutionName']));
			$details.="<option value=\"$CenCode\">$InstitutionName</option>";
		}
							
		  echo $details.="</select>";
}
/*end zone list view */

if($q=='districtlistForDevi'){ //echo $iCID;echo "hiiii";
	$details="<select class=\"select2a_n\" id=\"DistrictCode\" name=\"DistrictCode\" onchange=\"Javascript:show_zone_div('zonelistForDevi', this.options[this.selectedIndex].value,'');\">
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
/*strat division list view */
if($q=='zonelistForDevi'){//echo $iCID;
	$details="<select class=\"select2a_n\" id=\"ZoneCode\" name=\"ZoneCode\" onchange=\"Javascript:show_division('divisionListUser', this.options[this.selectedIndex].value, '$iCID');\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT CenCode,InstitutionName FROM CD_Zone where DistrictCode='$iCID' order by InstitutionName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$DSCoded=trim($row['CenCode']);
			$DSNamed=$row['InstitutionName'];
			$seltebr="";
			if($DSCoded==$ZoneCode){
				$seltebr="selected";
			}
			$details.="<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
		}
		
		  echo $details.="</select>";
}
if($q=='divisionListUser'){
       // echo $iCID;        
	$details="<select class=\"select2a_n\" id=\"InstCode\" name=\"InstCode\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT [InstType]
      ,[CenCode]
      ,[InstitutionName]
      
  FROM [dbo].[CD_CensesNo] where CenCode LIKE '%ED%' and ZoneCode='$iCID'
  order by InstitutionName";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$CenCode=trim($row['CenCode']);
			$InstitutionName=addslashes(str_replace("EDUCATION RESOURCE CENTRE ","",$row['InstitutionName']));
			$details.="<option value=\"$CenCode\">$InstitutionName</option>";
		}
							
		  echo $details.="</select>";
}
/*end division list view */

if($q=='districtList'){ //echo $iCID;
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
	$details="<select class=\"select2a_n\" id=\"ZoneCode\" name=\"ZoneCode\" onchange=\"Javascript:show_division('divisionlst', this.options[this.selectedIndex].value, '$iCID');\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT CenCode,InstitutionName FROM CD_Zone where DistrictCode='$iCID' order by InstitutionName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$DSCoded=trim($row['CenCode']);
			$DSNamed=$row['InstitutionName'];
			$seltebr="";
			if($DSCoded==$ZoneCode){
				$seltebr="selected";
			}
			$details.="<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
		}
		
		
							
		  echo $details.="</select>";
	
}

if($q=='divisionlst'){/// onchange=\"Javascript:show_division('divisionlst', this.options[this.selectedIndex].value, '$iCID');\"
	$details="<select class=\"select2a_n\" id=\"DivisionCode\" name=\"DivisionCode\" onchange=\"Javascript:show_cences('censesList', this.options[this.selectedIndex].value,'$iCID');\">
			  <option value=\"\">-Select-</option>";
			  
		$sql = "SELECT CenCode,InstitutionName FROM CD_Division where DistrictCode='$srch' and ZoneCode='$iCID' order by InstitutionName asc";
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$DSCoded=trim($row['CenCode']);
			$DSNamed=$row['InstitutionName'];
			$seltebr="";
			if($DSCoded==$DivisionCode){
				$seltebr="selected";
			}
			$details.="<option value=\"$DSCoded\" $seltebr>$DSNamed</option>";
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

if($q=='approsteps'){
	for($i=1;$i<$iCID+1;$i++){
		$fldName="ApprovePositionCode".$i;
		$fldName1="ApprovePositionNominiCode".$i;
        //$fldName2 = "appProcessName".$i;
		$details="<table width=\"100%\" cellspacing=\"1\" cellpadding=\"1\">
                        <tr>
                          <td width=\"25%\">Step $i :</td>                           
                          <td width=\"34%\"><select class=\"select2a_n\" id=\"$fldName\" name=\"$fldName\">";
                            
                            $sql = "SELECT AccessRole, AccessRoleID
FROM CD_AccessRoles ORDER BY AccessRoleID";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$Code=$row['AccessRoleID'];
								$PositionName=$row['AccessRole'];
                                $details.="<option value=\"$Code\">$PositionName</option>";
                            }
                           
                     $details.=" </select></td>
					 	 <td width=\"10%\">Nominator : </td>
                         <td width=\"31%\"><select class=\"select2a_n\" id=\"$fldName1\" name=\"$fldName1\">
						 <option value=\"\">- Nominator -</option>";
                            
                            $sql = "SELECT AccessRole, AccessRoleID
FROM CD_AccessRoles ORDER BY AccessRoleID";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
								$Code=$row['AccessRoleID'];
								$PositionName=$row['AccessRole'];
                                $details.="<option value=\"$Code\">$PositionName</option>";
                            }
                           
                     $details.=" </select></td>
                        </tr>
                      </table>";
					  echo $details;
					  //<input type=\"text\" class=\"input2_n\" placeholder=\"Position\" value=\"\" id=\"$fldName2\" name=\"$fldName2\">
	}
	
}
if($q=='change_pw'){
	echo "<table width=\"100%\" cellspacing=\"1\" cellpadding=\"1\">
                        <tr>
                          <td width=\"39%\"><strong><span class=\"form_error\">*</span>Password</strong></td>
                          <td width=\"3%\">:</td>
                          <td width=\"58%\"><input name=\"CurPassword\" type=\"password\" class=\"input3\" id=\"CurPassword\" value=\"\"/></td>
                        </tr>
                        <tr>
                          <td><strong><span class=\"form_error\">*</span>Re-type Password 1</strong></td>
                          <td>:</td>
                          <td><input name=\"CurPasswordRT\" type=\"password\" class=\"input3\" id=\"CurPasswordRT\" value=\"\" /><input type=\"hidden\" name=\"chngepw\" value=\"Y\"/></td>
                        </tr>
                      </table>";
}

if($q=='change_nic'){
	echo "<table>
	<tr>
		<td><h4>Change NIC number:</h4></td>
	</tr>
	<tr> 
		<td>Enter new NIC Number:</td>
		<td><input type=\"text\" id=\"newnic\" size=\"20\" value=\"\" name=\"newnic\" required></td>
	</tr>
	<tr>
		<td>Confirm NIC Number:</td>
		<td><input type=\"text\" id=\"connic\" size=\"20\" value=\"\" name=\"connic\" required></td>
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
?> 