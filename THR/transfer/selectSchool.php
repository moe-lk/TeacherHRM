<table width="100%" cellspacing="1" cellpadding="1">
  <tr>
    <td bgcolor="#CFF1D1" width="22%">Province :</td>
    <td bgcolor="#CFD7FE" width="78%"><select name="cmbProvince" class="input2" id="cmbProvince" onchange="loadAccordingToProvince();">

        <?php
//Province
        if ($ProCode == "")
            echo "<option value=\"\">All</option>";
       // $sql = "{call SP_GetProvinceFor_LoggedUser( ?, ?, ? )}";
        //$stmt = $db->runMsSqlQuery($sql, $params);
        $sql="SELECT [ProCode]
,[Province]
FROM [dbo].[CD_Provinces]";
        $stmt = $db->runMsSqlQuery($sql);
       
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            if ($sqProvince == trim($row['ProCode']))
                echo '<option selected="selected" value=' . $row['ProCode'] . '>' . $row['Province'] . '</option>';
            else
                echo '<option value=' . $row['ProCode'] . '>' . $row['Province'] . '</option>';
        }
        ?>
    </select></td>
</tr>
 <tr>
   <td bgcolor="#CFF1D1">District :</td>
    <td bgcolor="#CFD7FE"><select name="cmbDistrict" class="input2" id="cmbDistrict" onchange="loadAccordingToDistrict();">

        <?php
//District
//$sql = "SELECT DistCode,DistName FROM [CD_Districts] WHERE (DistCode != '')";
        if ($ProCode == "")
            echo "<option value=\"\">All</option>";
        //$sql = "{call SP_TG_GetDistrictFor_LoggedUser( ?, ?, ?)}";

        //$stmt = $db->runMsSqlQuery($sql, $params3);
         $sql="SELECT [DistCode]
,[DistName]
,[ProCode]
,[RecordLog]
FROM [dbo].[CD_Districts]";
         $stmt = $db->runMsSqlQuery($sql);
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            if ($sqDistrict == trim($row['DistCode']))
                echo '<option selected="selected" value=' . $row['DistCode'] . '>' . $row['DistName'] . '</option>';
            else
                echo '<option value=' . $row['DistCode'] . '>' . $row['DistName'] . '</option>';
        }
        ?>
   </select></td>
</tr>
 <tr>
   <td bgcolor="#CFF1D1">Zone :</td>
    <td bgcolor="#CFD7FE"><select name="cmbZone" class="input2" id="cmbZone" onchange="loadAccordingToZone();">
                                                        <option value="">All</option>
                                                        <?php
//Zone
//$sql = "SELECT CenCode,CONCAT(CenCode,'- ',InstitutionName) AS Zone FROM [CD_CensesNo] WHERE (InstType = 'ZN')";
                                                        $sql = "{call SP_TG_GetZonesFor_LooggedUser( ?, ?, ? ,?)}";

                                                        $stmt = $db->runMsSqlQuery($sql, $params4);
                                                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                            if ($sqZone == trim($row['CenCode']))
                                                                echo '<option selected="selected" value=' . $row['CenCode'] . '>' . $row['Zone'] . '</option>';
                                                            else
                                                                echo '<option value=' . $row['CenCode'] . '>' . $row['Zone'] . '</option>';
                                                        }
                                                        ?>
   </select></td>
</tr>
 <tr>
   <td bgcolor="#CFF1D1">Division :</td>
    <td bgcolor="#CFD7FE"><select name="cmbDivision" class="input2" id="cmbDivision" onchange="loadAccordingToDivision();">
                                                        <option value="">All</option>
                                                        <?php
//Division
//$sql = "SELECT CenCode,CONCAT(CenCode,'- ',InstitutionName) AS Division FROM [CD_CensesNo] WHERE (InstType = 'ED')";
                                                        $sql = "{call SP_TG_GetDivisionFor_LooggedUser( ?, ?, ? , ?, ?)}";

                                                        $stmt = $db->runMsSqlQuery($sql, $params1);
// $stmt = $db->runMsSqlQuery($sql);
                                                        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                                            if ($sqDivision == trim($row['CenCode']))
                                                                echo '<option selected="selected" value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
                                                            else
                                                                echo '<option value=' . $row['CenCode'] . '>' . $row['InstitutionName'] . '</option>';
                                                        }
                                                        ?>
   </select></td>
</tr>
 <tr>
      <td bgcolor="#CFF1D1">Expect School 1 :</td>
      <td bgcolor="#CFD7FE"><select class="select2a_n" id="ExpectSchool" name="ExpectSchool">
            
      </select></td>
  </tr>
    <tr>
      <td valign="top" bgcolor="#CFF1D1">Expect School 2 :</td>
      <td bgcolor="#CFD7FE"><select class="select2a_n" id="ExpectSchool2" name="ExpectSchool2">
            <?php
            /* $sql = "SELECT [InstType]
,[CenCode]
,[InstitutionName]
,[DistrictCode]
,[RecordLog]
,[ZoneCode]
,[DivisionCode]
,[IsNationalSchool]
,[SchoolType]
FROM [dbo].[CD_CensesNo]
Where IsNationalSchool='1'
order by InstitutionName";
            $stmt = $db->runMsSqlQuery($sql);
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $ex1school=trim($row['CenCode']);
                $sclName=$row['InstitutionName'];
                $selTxt="";
                if($ExpectSchool2==$ex1school)$selTxt="selected";
                echo "<option value=\"$ex1school\" $selTxt>$sclName</option>";
            } */
            ?>
      </select></td>
  </tr>
    <tr>
      <td valign="top" bgcolor="#CFF1D1">Expect School 3 :</td>
      <td bgcolor="#CFD7FE"><select class="select2a_n" id="ExpectSchool3" name="ExpectSchool3">
            <?php
            /* $sql = "SELECT [InstType]
,[CenCode]
,[InstitutionName]
,[DistrictCode]
,[RecordLog]
,[ZoneCode]
,[DivisionCode]
,[IsNationalSchool]
,[SchoolType]
FROM [dbo].[CD_CensesNo]
Where IsNationalSchool='1'
order by InstitutionName";
            $stmt = $db->runMsSqlQuery($sql);
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $ex1school=trim($row['CenCode']);
                $sclName=$row['InstitutionName'];
                $selTxt="";
                if($ExpectSchool3==$ex1school)$selTxt="selected";
                echo "<option value=\"$ex1school\" $selTxt>$sclName</option>";
            } */
            ?>
      </select></td>
  </tr>
    <tr>
      <td valign="top" bgcolor="#CFF1D1">Expect School 4 :</td>
      <td bgcolor="#CFD7FE"><select class="select2a_n" id="ExpectSchool4" name="ExpectSchool4">
            <?php
            /* $sql = "SELECT [InstType]
,[CenCode]
,[InstitutionName]
,[DistrictCode]
,[RecordLog]
,[ZoneCode]
,[DivisionCode]
,[IsNationalSchool]
,[SchoolType]
FROM [dbo].[CD_CensesNo]
Where IsNationalSchool='1'
order by InstitutionName";
            $stmt = $db->runMsSqlQuery($sql);
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $ex1school=trim($row['CenCode']);
                $sclName=$row['InstitutionName'];
                $selTxt="";
                if($ExpectSchool4==$ex1school)$selTxt="selected";
                echo "<option value=\"$ex1school\" $selTxt>$sclName</option>";
            } */
            ?>
      </select></td>
  </tr>
    <tr>
      <td valign="top" bgcolor="#CFF1D1">Expect School 5 :</td>
      <td bgcolor="#CFD7FE"><select class="select2a_n" id="ExpectSchool5" name="ExpectSchool5">
            <?php
            /* $sql = "SELECT [InstType]
,[CenCode]
,[InstitutionName]
,[DistrictCode]
,[RecordLog]
,[ZoneCode]
,[DivisionCode]
,[IsNationalSchool]
,[SchoolType]
FROM [dbo].[CD_CensesNo]
Where IsNationalSchool='1'
order by InstitutionName";
            $stmt = $db->runMsSqlQuery($sql);
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $ex1school=trim($row['CenCode']);
                $sclName=$row['InstitutionName'];
                $selTxt="";
                if($ExpectSchool5==$ex1school)$selTxt="selected";
                echo "<option value=\"$ex1school\" $selTxt>$sclName</option>";
            } */
            ?>
      </select></td>
  </tr>
</table>
