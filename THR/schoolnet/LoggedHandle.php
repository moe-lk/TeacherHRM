<?php
if ($AccessRoleType == "ZN") {
    $restZone = substr($CenCodex, -4, 4);
    $zoneCodeLoged = "ZN" . $restZone;
    $detailSql = "SELECT CD_CensesNo.CenCode, CD_CensesNo.DistrictCode, CD_CensesNo.ZoneCode, CD_CensesNo.DivisionCode, CD_Districts.ProCode FROM CD_CensesNo INNER JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode WHERE (CD_CensesNo.CenCode = N'$CenCodex')";
    $stmt = $db->runMsSqlQuery($detailSql);
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $ProCodex = trim($row['ProCode']);
    $DistrictCodex = trim($row['DistrictCode']);
    $ZoneCodex = $zoneCodeLoged;
    $CenCodex = "";
    $disaTxt = "disabled";
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
		
        $details.="<option value=\"$CenCode\" $seltebr>$InstitutionName [$CenCode]</option>";
    }  
	echo $details.="</select>";

}
?>
