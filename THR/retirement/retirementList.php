<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
    function check_form(form_name) {
        if (submitted == true) {
            alert("This form has already been submitted. Please press Ok and wait for this process to be completed.");
            return false;
        }

        error = false;
        form = form_name;
        error_message = "Please enter following details:\n";

        //check_input("subject", 2, "Feedback Subject.");
        //check_input("captInput", 2, "Verification Code.");
        check_select("GradeID", "", "Grade.");

        if (error == true) {
            alert(error_message);
            return false;
        } else {
            submitted = true;
            return true;
        }
    }
//--></script><?php
$msg = "";
$checkInst = $id[0];

$sqlP = "SELECT fVoluntaryYear, fRetirementYear
FROM TG_RetiremntParms";

$stmtP = $db->runMsSqlQuery($sqlP);
while ($rowP = sqlsrv_fetch_array($stmtP, SQLSRV_FETCH_ASSOC)) {
    $fVoluntaryYear = $rowP["fVoluntaryYear"];
    $fRetirementYear = $rowP["fRetirementYear"];
}
$p = $fRetirementYear - 6;
$checkAge = strtotime(date("Y-m-d", strtotime($currDate)) . " -$p month");
$checkAge = date("Y-m-d", $checkAge);

$Per_Page = 30;  // Per Page 
//Get the page number 

$Page = 1;

//Determine if it is the first page 

/* if(isset($_GET["Page"]))
  {
  $Page=(int)$_GET["Page"];
  if ($Page < 1)
  $Page = 1;
  } */

if ($menu) {
    $Page = (int) $menu;
    if ($Page < 1)
        $Page = 1;
}

$Page_Start = (($Per_Page * $Page) - $Per_Page) + 1;
$Page_End = $Page_Start + $Per_Page - 1;

if ($id == '') {
    $SearchHead = "All Island";
    $query = "WITH LIMIT AS( SELECT  TeacherMast.ID, TeacherMast.NIC, TeacherMast.SurnameWithInitials, TeacherMast.FullName, TeacherMast.Title, CONVERT(varchar(20),DOB,121) AS DOB, TeacherMast.GenderCode, TeacherMast.Province, 
							 TeacherMast.CurServiceRef, StaffServiceHistory.AppDate, ROW_NUMBER() OVER (ORDER BY TeacherMast.DOB ASC) AS 'RowNumber' FROM TeacherMast INNER JOIN
							 StaffServiceHistory ON TeacherMast.NIC = StaffServiceHistory.NIC WHERE (TeacherMast.NIC <> '') AND (StaffServiceHistory.ServiceRecTypeCode IS NULL OR
							 StaffServiceHistory.ServiceRecTypeCode <> 'DS03' AND StaffServiceHistory.ServiceRecTypeCode <> 'RT01' AND StaffServiceHistory.ServiceRecTypeCode <> 'RN01')
							 AND StaffServiceHistory.ServiceRecTypeCode = 'NA01' AND TeacherMast.DOB<'$checkAge')
	select * from LIMIT WHERE RowNumber BETWEEN $Page_Start AND $Page_End";
    //$checkAge="2010-01-01";
    /* echo $query = "WITH LIMIT AS(SELECT        TeacherMast.ID, TeacherMast.NIC, TeacherMast.SurnameWithInitials, TeacherMast.FullName, TeacherMast.Title, CONVERT(varchar(20), TeacherMast.DOB, 121) AS DOB, TeacherMast.GenderCode, 
      TeacherMast.Province, TeacherMast.CurServiceRef, StaffServiceHistory.AppDate, ROW_NUMBER() OVER (ORDER BY TeacherMast.ID ASC) AS 'RowNumber', CD_CensesNo.CenCode, CD_CensesNo.InstitutionName
      FROM            CD_CensesNo LEFT JOIN
      StaffServiceHistory ON CD_CensesNo.CenCode = StaffServiceHistory.InstCode LEFT JOIN
      TeacherMast ON StaffServiceHistory.NIC = TeacherMast.NIC
      WHERE        (TeacherMast.NIC <> '') AND (StaffServiceHistory.ServiceRecTypeCode IS NULL OR
      StaffServiceHistory.ServiceRecTypeCode <> 'DS03' AND StaffServiceHistory.ServiceRecTypeCode <> 'RT01' AND StaffServiceHistory.ServiceRecTypeCode <> 'RN01') AND
      (StaffServiceHistory.ServiceRecTypeCode = 'NA01') AND TeacherMast.DOB<'$checkAge' AND (StaffServiceHistory.InstCode IS NOT NULL))
      select * from LIMIT WHERE RowNumber BETWEEN $Page_Start AND $Page_End";exit(); */
    $stmt = $db->runMsSqlQuery($query);


    $countTotal = "SELECT        TeacherMast.ID
	FROM            TeacherMast INNER JOIN
							 StaffServiceHistory ON TeacherMast.NIC = StaffServiceHistory.NIC
	WHERE        (TeacherMast.NIC <> '') AND (StaffServiceHistory.ServiceRecTypeCode IS NULL OR
							 StaffServiceHistory.ServiceRecTypeCode <> 'DS03' AND StaffServiceHistory.ServiceRecTypeCode <> 'RT01' AND StaffServiceHistory.ServiceRecTypeCode <> 'RN01')
							 AND StaffServiceHistory.ServiceRecTypeCode = 'NA01' AND TeacherMast.DOB<'$checkAge'";
    $TotaRows = $db->rowCount($countTotal);
}

if ($checkInst == 'P') {
    $sql5 = "SELECT Province FROM CD_Provinces WHERE (ProCode = N'$id')";
    $stmt5 = $db->runMsSqlQuery($sql5);
    $row5 = sqlsrv_fetch_array($stmt5, SQLSRV_FETCH_ASSOC);
    $ProvinceN = trim($row5["Province"]);

    $SearchHead = $ProvinceN . " Province";

    $query = "WITH LIMIT AS(  SELECT        TeacherMast.ID, TeacherMast.NIC, TeacherMast.SurnameWithInitials, TeacherMast.FullName, TeacherMast.Title, CONVERT(varchar(20), TeacherMast.DOB, 121) AS DOB, TeacherMast.GenderCode, 
                         TeacherMast.Province, TeacherMast.CurServiceRef, StaffServiceHistory.AppDate, CD_CensesNo.InstitutionName, ROW_NUMBER() OVER (ORDER BY TeacherMast.DOB ASC) AS 'RowNumber'
FROM            TeacherMast LEFT JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID LEFT JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
WHERE        (TeacherMast.NIC <> '') AND (StaffServiceHistory.ServiceRecTypeCode IS NULL OR
                         StaffServiceHistory.ServiceRecTypeCode <> 'DS03' AND StaffServiceHistory.ServiceRecTypeCode <> 'RT01' AND StaffServiceHistory.ServiceRecTypeCode <> 'RN01') AND (TeacherMast.DOB<'$checkAge') AND TeacherMast.Province='$ProvinceN')
	select * from LIMIT WHERE RowNumber BETWEEN $Page_Start AND $Page_End";
    $stmt = $db->runMsSqlQuery($query);


    $countTotal = "SELECT        TeacherMast.ID
	FROM            TeacherMast LEFT JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID LEFT JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
WHERE        (TeacherMast.NIC <> '') AND (StaffServiceHistory.ServiceRecTypeCode IS NULL OR
                         StaffServiceHistory.ServiceRecTypeCode <> 'DS03' AND StaffServiceHistory.ServiceRecTypeCode <> 'RT01' AND StaffServiceHistory.ServiceRecTypeCode <> 'RN01') AND (TeacherMast.DOB<'$checkAge') AND (TeacherMast.Province='$ProvinceN')";
    $TotaRows = $db->rowCount($countTotal);
} else if ($checkInst == 'D') {
    $sql5 = "SELECT DistName FROM CD_Districts WHERE (DistCode = N'$id')";
    $stmt5 = $db->runMsSqlQuery($sql5);
    $row5 = sqlsrv_fetch_array($stmt5, SQLSRV_FETCH_ASSOC);
    $DistName = trim($row5["DistName"]);

    $SearchHead = $DistName . " District";

    $query = "WITH LIMIT AS(  SELECT        TeacherMast.ID, TeacherMast.NIC, TeacherMast.SurnameWithInitials, TeacherMast.FullName, TeacherMast.Title, CONVERT(varchar(20), TeacherMast.DOB, 121) AS DOB, TeacherMast.GenderCode, 
                         TeacherMast.Province, TeacherMast.CurServiceRef, StaffServiceHistory.AppDate, CD_CensesNo.InstitutionName, ROW_NUMBER() OVER (ORDER BY TeacherMast.DOB ASC) AS 'RowNumber'
FROM            TeacherMast LEFT JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID LEFT JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
WHERE        (TeacherMast.NIC <> '') AND (StaffServiceHistory.ServiceRecTypeCode IS NULL OR
                         StaffServiceHistory.ServiceRecTypeCode <> 'DS03' AND StaffServiceHistory.ServiceRecTypeCode <> 'RT01' AND StaffServiceHistory.ServiceRecTypeCode <> 'RN01') AND (TeacherMast.DOB<'$checkAge') AND CD_CensesNo.DistrictCode='$id')
	select * from LIMIT WHERE RowNumber BETWEEN $Page_Start AND $Page_End";
    $stmt = $db->runMsSqlQuery($query);


    $countTotal = "SELECT        TeacherMast.ID
	FROM            TeacherMast LEFT JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID LEFT JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
WHERE        (TeacherMast.NIC <> '') AND (StaffServiceHistory.ServiceRecTypeCode IS NULL OR
                         StaffServiceHistory.ServiceRecTypeCode <> 'DS03' AND StaffServiceHistory.ServiceRecTypeCode <> 'RT01' AND StaffServiceHistory.ServiceRecTypeCode <> 'RN01') AND (TeacherMast.DOB<'$checkAge') AND (CD_CensesNo.DistrictCode='$id')";
    $TotaRows = $db->rowCount($countTotal);
} else if ($checkInst == 'Z') {
    $sql5 = "SELECT InstitutionName FROM CD_Zone WHERE (CenCode = N'$id')";
    $stmt5 = $db->runMsSqlQuery($sql5);
    $row5 = sqlsrv_fetch_array($stmt5, SQLSRV_FETCH_ASSOC);
    $ZoneName = trim($row5["InstitutionName"]);

    $SearchHead = $ZoneName . " Zone";

    $query = "WITH LIMIT AS(  SELECT        TeacherMast.ID, TeacherMast.NIC, TeacherMast.SurnameWithInitials, TeacherMast.FullName, TeacherMast.Title, CONVERT(varchar(20), TeacherMast.DOB, 121) AS DOB, TeacherMast.GenderCode, 
                         TeacherMast.Province, TeacherMast.CurServiceRef, StaffServiceHistory.AppDate, CD_CensesNo.InstitutionName, ROW_NUMBER() OVER (ORDER BY TeacherMast.DOB ASC) AS 'RowNumber'
FROM            TeacherMast LEFT JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID LEFT JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
WHERE        (TeacherMast.NIC <> '') AND (StaffServiceHistory.ServiceRecTypeCode IS NULL OR
                         StaffServiceHistory.ServiceRecTypeCode <> 'DS03' AND StaffServiceHistory.ServiceRecTypeCode <> 'RT01' AND StaffServiceHistory.ServiceRecTypeCode <> 'RN01') AND (TeacherMast.DOB<'$checkAge') AND CD_CensesNo.ZoneCode='$id')
	select * from LIMIT WHERE RowNumber BETWEEN $Page_Start AND $Page_End";
    $stmt = $db->runMsSqlQuery($query);


    $countTotal = "SELECT        TeacherMast.ID
	FROM            TeacherMast LEFT JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID LEFT JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
WHERE        (TeacherMast.NIC <> '') AND (StaffServiceHistory.ServiceRecTypeCode IS NULL OR
                         StaffServiceHistory.ServiceRecTypeCode <> 'DS03' AND StaffServiceHistory.ServiceRecTypeCode <> 'RT01' AND StaffServiceHistory.ServiceRecTypeCode <> 'RN01') AND (TeacherMast.DOB<'$checkAge') AND (CD_CensesNo.ZoneCode='$id')";
    $TotaRows = $db->rowCount($countTotal);
} else if ($checkInst == 'E') {
    $sql5 = "SELECT InstitutionName FROM CD_Division WHERE (CenCode = N'$id')";
    $stmt5 = $db->runMsSqlQuery($sql5);
    $row5 = sqlsrv_fetch_array($stmt5, SQLSRV_FETCH_ASSOC);
    $DivisionName = trim($row5["InstitutionName"]);

    $SearchHead = $DivisionName . " Division";

    $query = "WITH LIMIT AS(  SELECT        TeacherMast.ID, TeacherMast.NIC, TeacherMast.SurnameWithInitials, TeacherMast.FullName, TeacherMast.Title, CONVERT(varchar(20), TeacherMast.DOB, 121) AS DOB, TeacherMast.GenderCode, 
                         TeacherMast.Province, TeacherMast.CurServiceRef, StaffServiceHistory.AppDate, CD_CensesNo.InstitutionName, ROW_NUMBER() OVER (ORDER BY TeacherMast.DOB ASC) AS 'RowNumber'
FROM            TeacherMast LEFT JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID LEFT JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
WHERE        (TeacherMast.NIC <> '') AND (StaffServiceHistory.ServiceRecTypeCode IS NULL OR
                         StaffServiceHistory.ServiceRecTypeCode <> 'DS03' AND StaffServiceHistory.ServiceRecTypeCode <> 'RT01' AND StaffServiceHistory.ServiceRecTypeCode <> 'RN01') AND (TeacherMast.DOB<'$checkAge') AND CD_CensesNo.DivisionCode='$id')
	select * from LIMIT WHERE RowNumber BETWEEN $Page_Start AND $Page_End";
    $stmt = $db->runMsSqlQuery($query);


    $countTotal = "SELECT        TeacherMast.ID
	FROM            TeacherMast LEFT JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID LEFT JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
WHERE        (TeacherMast.NIC <> '') AND (StaffServiceHistory.ServiceRecTypeCode IS NULL OR
                         StaffServiceHistory.ServiceRecTypeCode <> 'DS03' AND StaffServiceHistory.ServiceRecTypeCode <> 'RT01' AND StaffServiceHistory.ServiceRecTypeCode <> 'RN01') AND (TeacherMast.DOB<'$checkAge') AND (CD_CensesNo.DivisionCode='$id')";
    $TotaRows = $db->rowCount($countTotal);
} else if ($checkInst == 'S') {
    $sql5 = "SELECT InstitutionName FROM CD_CensesNo WHERE (CenCode = N'$id')";
    $stmt5 = $db->runMsSqlQuery($sql5);
    $row5 = sqlsrv_fetch_array($stmt5, SQLSRV_FETCH_ASSOC);
    $SchoolName = trim($row5["InstitutionName"]);

    $SearchHead = $SchoolName . " School";

    $query = "WITH LIMIT AS(  SELECT        TeacherMast.ID, TeacherMast.NIC, TeacherMast.SurnameWithInitials, TeacherMast.FullName, TeacherMast.Title, CONVERT(varchar(20), TeacherMast.DOB, 121) AS DOB, TeacherMast.GenderCode, 
                         TeacherMast.Province, TeacherMast.CurServiceRef, StaffServiceHistory.AppDate, CD_CensesNo.InstitutionName, ROW_NUMBER() OVER (ORDER BY TeacherMast.DOB ASC) AS 'RowNumber'
FROM            TeacherMast LEFT JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID LEFT JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
WHERE        (TeacherMast.NIC <> '') AND (StaffServiceHistory.ServiceRecTypeCode IS NULL OR
                         StaffServiceHistory.ServiceRecTypeCode <> 'DS03' AND StaffServiceHistory.ServiceRecTypeCode <> 'RT01' AND StaffServiceHistory.ServiceRecTypeCode <> 'RN01') AND (TeacherMast.DOB<'$checkAge') AND CD_CensesNo.CenCode='$id')
	select * from LIMIT WHERE RowNumber BETWEEN $Page_Start AND $Page_End";
    $stmt = $db->runMsSqlQuery($query);


    $countTotal = "SELECT        TeacherMast.ID
	FROM            TeacherMast LEFT JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID LEFT JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
WHERE        (TeacherMast.NIC <> '') AND (StaffServiceHistory.ServiceRecTypeCode IS NULL OR
                         StaffServiceHistory.ServiceRecTypeCode <> 'DS03' AND StaffServiceHistory.ServiceRecTypeCode <> 'RT01' AND StaffServiceHistory.ServiceRecTypeCode <> 'RN01') AND (TeacherMast.DOB<'$checkAge') AND (CD_CensesNo.CenCode='$id')";
    $TotaRows = $db->rowCount($countTotal);
} else {

    $SearchHead = "an Employee";
    $query = "WITH LIMIT AS(  SELECT        TeacherMast.ID, TeacherMast.NIC, TeacherMast.SurnameWithInitials, TeacherMast.FullName, TeacherMast.Title, CONVERT(varchar(20), TeacherMast.DOB, 121) AS DOB, TeacherMast.GenderCode, 
                         TeacherMast.Province, TeacherMast.CurServiceRef, StaffServiceHistory.AppDate, CD_CensesNo.InstitutionName, ROW_NUMBER() OVER (ORDER BY TeacherMast.DOB ASC) AS 'RowNumber'
FROM            TeacherMast LEFT JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID LEFT JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
WHERE        (TeacherMast.NIC = '$id') AND (StaffServiceHistory.ServiceRecTypeCode IS NULL OR
                         StaffServiceHistory.ServiceRecTypeCode <> 'DS03' AND StaffServiceHistory.ServiceRecTypeCode <> 'RT01' AND StaffServiceHistory.ServiceRecTypeCode <> 'RN01') AND (TeacherMast.DOB<'$checkAge'))
	select * from LIMIT WHERE RowNumber BETWEEN $Page_Start AND $Page_End";
    $stmt = $db->runMsSqlQuery($query);


    $countTotal = "SELECT        TeacherMast.ID
	FROM            TeacherMast LEFT JOIN
                         StaffServiceHistory ON TeacherMast.CurServiceRef = StaffServiceHistory.ID LEFT JOIN
                         CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode
WHERE        (TeacherMast.NIC = '$id') AND (StaffServiceHistory.ServiceRecTypeCode IS NULL OR
                         StaffServiceHistory.ServiceRecTypeCode <> 'DS03' AND StaffServiceHistory.ServiceRecTypeCode <> 'RT01' AND StaffServiceHistory.ServiceRecTypeCode <> 'RN01') AND (TeacherMast.DOB<'$checkAge')";
    $TotaRows = $db->rowCount($countTotal);
}




//Declare previous/next page row guide 

$Prev_Page = $Page - 1;
$Next_Page = $Page + 1;

if ($TotaRows <= $Per_Page) {
    $Num_Pages = 1;
} else if (($TotaRows % $Per_Page) == 0) {
    $Num_Pages = ($TotaRows / $Per_Page);
} else {
    $Num_Pages = ($TotaRows / $Per_Page) + 1;
    $Num_Pages = (int) $Num_Pages;
}

//Determine where the page will end 

$Page_End = $Per_Page * $Page;
if ($Page_End > $TotaRows) {
    $Page_End = $TotaRows;
}
?>


<div class="main_content_inner_block">
    <form method="post" action="" name="frmSave" id="frmSave" enctype="multipart/form-data" onSubmit="return check_form(frmSave);">
<?php if ($msg != '') {//if($_SESSION['success_update']!='' || $_SESSION['success_update']!=''){  ?>   
            <div class="mcib_middle1">
                <div class="mcib_middle_full">
                    <div class="form_error"><?php echo $msg;
    echo $_SESSION['success_update'];
    $_SESSION['success_update'] = ""; ?><?php echo $_SESSION['fail_update'];
    $_SESSION['fail_update'] = ""; ?></div>
                </div>
<?php } ?>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td colspan="2" align="center" valign="top"><u>Retirement List of <?php echo $SearchHead ?></u></td>
                </tr>
                <tr>
                    <td width="67%" valign="top">&nbsp;</td>
                    <td width="33%" valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td><?php echo $TotaRows ?> Record(s) found. Showing <?php echo $Per_Page ?> records per page.</td>
                    <td align="right"><a href="retirementSearch-4.html"><< Go back to search page</a></td>
                </tr>
                <tr>
                    <td colspan="2" bgcolor="#CCCCCC"><table width="100%" cellspacing="1" cellpadding="1">
                            <tr>
                                <td width="4%" height="25" align="center" bgcolor="#999999">#</td>
                                <td width="12%" height="25" align="center" bgcolor="#999999">NIC</td>
                                <td width="36%" align="center" bgcolor="#999999">Name</td>
                                <td width="25%" align="center" bgcolor="#999999">Institute</td>
                                <td width="12%" align="center" bgcolor="#999999">Date Of Birth</td>
                                <td width="11%" align="center" bgcolor="#999999">View More</td>
                            </tr>
<?php
/* $sqlList="SELECT [ID]
  ,[SchoolID]$loggedSchool
  ,[GradeID]
  FROM [dbo].[TG_SchoolGradeMaster]
  where SchoolID='SC05428'"; */
$i = 1;
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    //echo $row['NIC'] . " : " . $row['SurnameWithInitials'] . "<br />";

    $NIC = trim($row['NIC']);
    $DOB = trim($row['DOB']);
    $SurnameWithInitials = $row['SurnameWithInitials'];
    $RowNumber = $row['RowNumber'];
    $InstitutionName = $row['InstitutionName'];

    /*  $sqlDesign="SELECT [NICNo]
      ,[CurPassword]
      ,[LastUpdate]
      ,[AccessRole]
      ,[AccessLevel]
      FROM [MOENational].[dbo].[Passwords]
      where NICNo='$Expr1'";
      $stmtDes = $db->runMsSqlQuery($sqlDesign);
      $rowDes = sqlsrv_fetch_array($stmtDes, SQLSRV_FETCH_ASSOC); */
    ?>
                                <tr>
                                    <td height="20" bgcolor="#FFFFFF"><?php echo $RowNumber ?></td>
                                    <td height="20" bgcolor="#FFFFFF"><?php echo $NIC; ?></td>
                                    <td bgcolor="#FFFFFF"><?php echo $SurnameWithInitials ?></td>
                                    <td bgcolor="#FFFFFF"><?php echo $InstitutionName; ?></td>
                                    <td bgcolor="#FFFFFF"><?php echo $DOB; ?></td>
                                    <td bgcolor="#FFFFFF" align="center"><a href="retirementType-3--<?php echo $NIC ?>.html" target="_blank">Click <?php //echo $Expr1 ?></a></td>
                                </tr>
                            <?php } ?>

                        </table></td>
                </tr>
                <tr>
                    <td colspan="2"><table width="100%" cellspacing="1" cellpadding="1">
                            <tr>
                                <td width="67%">Page <?php echo $Page ?> of <?php echo $Num_Pages ?></td>
                                <td width="20%" align="right"><?php
//Previous page 

                            if ($Prev_Page) {
                                echo " <a href='retirementList-5-$Prev_Page-$id.html?Page=$Prev_Page#related'><< Previous</a> ";
                            }

//Display total pages 
//for($i=1; $i<=$Num_Pages; $i++){ 


                            /* for($i=1; $i<=5; $i++){ 
                              if($i != $Page)
                              {
                              echo "<a href='$_SERVER[SCRIPT_NAME]?id=$id&Page=$i#related'>$i</a>&nbsp;";
                              }
                              else
                              {
                              echo "<b> $i </b>";
                              }
                              } */
                            ?></td>
                                <td width="2%" align="center"><?php if ($Prev_Page and $Page != $Num_Pages) { ?> | <?php } ?></td>
                                <td width="11%" align="left"><?php
                                    //Create next page link 

                                    if ($Page != $Num_Pages) {
                                        //echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Next_Page#related'>Next>></a> "; 
                                        echo " <a href ='retirementList-5-$Next_Page-$id.html?Page=$Next_Page#related'>Next>></a> ";
                                    }
                                    ?></td>
                            </tr>
                        </table></td>
                </tr>
            </table>
        </div>

    </form>
</div>