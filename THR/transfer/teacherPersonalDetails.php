<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<?php 
if($fm=='' || $fm=='A'){
			$sql = "SELECT SurnameWithInitials,FullName,GenderCode,CivilStatusCode,SpouseName,SpouseOccupationCode, SpouseOfficeAddr,CurServiceRef,CONVERT(varchar(20),DOB,121) AS dateofBirth FROM TeacherMast where NIC='$NICUser'";
		
		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$SurnameWithInitials=$row['SurnameWithInitials'];
			$FullName=$row['FullName'];
			$GenderCode=$row['GenderCode'];
			$CivilStatusCode=$row['CivilStatusCode'];
			$SpouseName=$row['SpouseName'];
			$SpouseOccupationCode=$row['SpouseOccupationCode'];
			$SpouseOfficeAddr=$row['SpouseOfficeAddr'];
			$CurServiceRef=$row['CurServiceRef'];
			$DOB=$row['dateofBirth'];
		}
		
		$sqlSPos = "SELECT PositionName FROM CD_Positions where Code='$SpouseOccupationCode'";
		$stmtSpos = $db->runMsSqlQuery($sqlSPos);
		while ($row = sqlsrv_fetch_array($stmtSpos, SQLSRV_FETCH_ASSOC)) {
			$PositionName=$row['PositionName'];
		}
		
		$sqlAdd = "SELECT * FROM StaffAddrHistory where NIC='$NICUser' and AddrType='PER'";
		$stmtAdd = $db->runMsSqlQuery($sqlAdd);
		while ($row = sqlsrv_fetch_array($stmtAdd, SQLSRV_FETCH_ASSOC)) {
			$Address=$row['Address'];
		}
		
		$sqlMS = "SELECT * FROM CD_CivilStatus where Code='$CivilStatusCode'";
		$stmtMS = $db->runMsSqlQuery($sqlMS);
		while ($row = sqlsrv_fetch_array($stmtMS, SQLSRV_FETCH_ASSOC)) {
			$CivilStatusName=$row['CivilStatusName'];
		}
		
		$sqlCS="SELECT        CD_CensesNo.InstitutionName, StaffServiceHistory.WorkStatusCode, StaffServiceHistory.ServiceTypeCode, StaffServiceHistory.SecGRCode, StaffServiceHistory.PositionCode
		FROM            StaffServiceHistory INNER JOIN
								 CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode where StaffServiceHistory.ID='$CurServiceRef'";
		$stmtCS = $db->runMsSqlQuery($sqlCS);
		while ($row = sqlsrv_fetch_array($stmtCS, SQLSRV_FETCH_ASSOC)) {
			$InstitutionName=$row['InstitutionName'];
			$WorkStatusCode=$row['WorkStatusCode'];
			$ServiceTypeCode=$row['ServiceTypeCode'];
			$SecGRCode=$row['SecGRCode'];
			$PositionCode=$row['PositionCode'];
		}
		$sqlTG = "SELECT * FROM CD_Service where ServCode='$ServiceTypeCode'";
		$stmtTG = $db->runMsSqlQuery($sqlTG);
		while ($row = sqlsrv_fetch_array($stmtTG, SQLSRV_FETCH_ASSOC)) {
			$ServiceName=$row['ServiceName'];
		}
		
	}else{
		//////Start form values
		
		$sql = "SELECT SurnameWithInitials,FullName,GenderCode,CivilStatusCode,SpouseName,SpouseOccupationCode, SpouseOfficeAddr,CurServiceRef,CONVERT(varchar(20),DOB,121) AS dateofBirth FROM TeacherMast where NIC='$NIC'";

		$stmt = $db->runMsSqlQuery($sql);
		while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
			$SurnameWithInitials=$row['SurnameWithInitials'];
			$FullName=$row['FullName'];
			$GenderCode=$row['GenderCode'];
			$CivilStatusCode=$row['CivilStatusCode'];
			$SpouseName=$row['SpouseName'];
			$SpouseOccupationCode=$row['SpouseOccupationCode'];
			$SpouseOfficeAddr=$row['SpouseOfficeAddr'];
			//$CurServiceRef=$row['CurServiceRef'];
			$DOB=$row['dateofBirth'];
		}
		
		$sqlSPos = "SELECT PositionName FROM CD_Positions where Code='$SpouseOccupationCode'";
		$stmtSpos = $db->runMsSqlQuery($sqlSPos);
		while ($row = sqlsrv_fetch_array($stmtSpos, SQLSRV_FETCH_ASSOC)) {
			$PositionName=$row['PositionName'];
		}
		
		$sqlAdd = "SELECT * FROM StaffAddrHistory where NIC='$NIC' and AddrType='PER'";
		$stmtAdd = $db->runMsSqlQuery($sqlAdd);
		while ($row = sqlsrv_fetch_array($stmtAdd, SQLSRV_FETCH_ASSOC)) {
			$Address=$row['Address'];
		}
		
		$sqlMS = "SELECT * FROM CD_CivilStatus where Code='$CivilStatusCode'";
		$stmtMS = $db->runMsSqlQuery($sqlMS);
		while ($row = sqlsrv_fetch_array($stmtMS, SQLSRV_FETCH_ASSOC)) {
			$CivilStatusName=$row['CivilStatusName'];
		}
		
		$sqlCS="SELECT        CD_CensesNo.InstitutionName, StaffServiceHistory.WorkStatusCode, StaffServiceHistory.ServiceTypeCode, StaffServiceHistory.SecGRCode, StaffServiceHistory.PositionCode
		FROM            StaffServiceHistory INNER JOIN
								 CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode where StaffServiceHistory.ID='$ServiceHistoryID'";
		$stmtCS = $db->runMsSqlQuery($sqlCS);
		while ($row = sqlsrv_fetch_array($stmtCS, SQLSRV_FETCH_ASSOC)) {
			$InstitutionName=$row['InstitutionName'];
			$WorkStatusCode=$row['WorkStatusCode'];
			$ServiceTypeCode=$row['ServiceTypeCode'];
			$SecGRCode=$row['SecGRCode'];
			$PositionCode=$row['PositionCode'];
		}
		$sqlTG = "SELECT * FROM CD_Service where ServCode='$ServiceTypeCode'";
		$stmtTG = $db->runMsSqlQuery($sqlTG);
		while ($row = sqlsrv_fetch_array($stmtTG, SQLSRV_FETCH_ASSOC)) {
			$ServiceName=$row['ServiceName'];
		}
		
	}
	
?>

<table width="945" cellpadding="0" cellspacing="0">
        	  <tr>
        	    <td><table width="100%" cellspacing="1" cellpadding="1">
        	      <tr>
        	        <td width="23%" bgcolor="#F7E2DD">Full Name:</td>
        	        <td colspan="3" bgcolor="#EDEEF3"><?php echo ucwords(strtolower(($FullName))); ?>
                      </td>
       	          </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Name With Initials :</td>
        	        <td width="46%" bgcolor="#EDEEF3"><?php echo ucwords(strtolower(($SurnameWithInitials))); ?></td>
        	        <td width="14%" bgcolor="#F7E2DD">NIC :</td>
        	        <td width="17%" bgcolor="#EDEEF3"><?php echo $NICUser ?><input type="hidden" name="NIC" value="<?php echo $NICUser ?>"/></td>
      	        </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Gender :</td>
        	        <td bgcolor="#EDEEF3"><?php if($GenderCode==1){echo "Male";}else if($GenderCode==2){echo "Female";}else {echo "N/A";}?></td>
        	        <td bgcolor="#F7E2DD">Marital Status :</td>
        	        <td bgcolor="#EDEEF3">&nbsp;</td>
      	        </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Date of Birth :</td>
        	        <td bgcolor="#EDEEF3"><?php echo $DOB; ?></td>
        	        <td bgcolor="#F7E2DD">Age up to <?php echo $todate=date('Y-12-31'); ?> :</td>
        	        <td bgcolor="#EDEEF3"><?php echo calculateCurrentAge($DOB,$todate); ?></td>
      	        </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Permanent Address :</td>
        	        <td colspan="3" bgcolor="#EDEEF3"><?php echo ucwords(strtolower(($Address))); ?></td>
       	          </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Spouse Occupation :</td>
        	        <td colspan="3" bgcolor="#EDEEF3"><?php echo ucwords(strtolower(($PositionName))); ?></td>
       	          </tr>
        	      <tr>
        	        <td bgcolor="#F7E2DD">Spouse Occupation Address :</td>
        	        <td colspan="3" bgcolor="#EDEEF3"><?php echo ucwords(strtolower(($SpouseOfficeAddr))); ?></td>
       	          </tr>
        	      <tr>
        	        <td valign="top" bgcolor="#F7E2DD">Chiildren Below 5 Years :</td>
        	        <td colspan="3" bgcolor="#EDEEF3"><?php 
					  $sqlFA = "SELECT ChildName,CONVERT(varchar(20),DOB,121) AS ChildDOB FROM StaffChildren where NIC='$NICUser'";
$stmtFA = $db->runMsSqlQuery($sqlFA);


					  ?><table width="100%" cellspacing="1" cellpadding="1">
                        <tr>
                          <td width="54%" bgcolor="#CCCCCC"><strong>Name</strong></td>
                          <td width="46%" bgcolor="#CCCCCC"><strong>DOB</strong></td>
                        </tr>
                        <?php 
						$c=0;
						$todate=date('Y-m-d');
						while ($row = sqlsrv_fetch_array($stmtFA, SQLSRV_FETCH_ASSOC)) {
								$ChildName=$row['ChildName'];
								$ChildDOB=$row['ChildDOB'];
								$ageChild=calculateCurrentAge($ChildDOB,$todate);
								if($ageChild<=5){
									$c=1;
							?>
                        <tr>
                          <td bgcolor="#FFFFFF"><?php echo $ChildName ?></td>
                          <td bgcolor="#FFFFFF"><?php echo $ChildDOB ?> - <?php echo $ageChild; ?></td>
                        </tr>
                        
                        <?php }}?>
                        <?php if($c==0){?>
                        <tr>
                          <td bgcolor="#FFFFFF">N/A</td>
                          <td bgcolor="#FFFFFF">N/A</td>
                        </tr>
                        <?php }?>
                      </table></td>
       	          </tr>
      	      </table></td>
      	    </tr>
              </table>