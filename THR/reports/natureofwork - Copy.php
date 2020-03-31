<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<script src="js/teacherFilter.js"  language="javascript"></script>
<?php
$nicNO = $_SESSION["NIC"];
$LOGGEDUSERID = $nicNO;
?>
<form id="formNatureReport" name="formNatureReport" action="generateNatureofWork.php" method="post" enctype="multipart/form-data">
    <div class="main_content_inner_block">
        <div id="geographical" class="contenttab">


            <div class="productsItemBoxText">
                <div>
                    <label for="username" class="labelTxt" style="margin-left:22px;"><strong>Subject :</strong></label>
                    <label for="username" class="labelTxt"><strong>Position :</strong></label>
                </div>

                <div>
                    <div class="divSimple" style="margin-left:22px;">
                        <select style="width:260px;" id="cmbSubject" name="cmbSubject" >
                            <option value="">All</option>
                            <?php
                            $sql = "SELECT
CD_Subject.SubCode,
CD_Subject.SubjectName
FROM
CD_Subject";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $SubCode = $row['SubCode'];
                                $SubjectName = $row['SubjectName'];
                                echo '<option value=' . $SubCode . '>' . $SubjectName . '</option>';
                            }
                            ?>
                        </select>


                    </div>
                    <div class="divSimple">

                        <select style="width:260px;" id="cmbSubject" name="cmbSubject" >
                            <option value="">All</option>
                            <?php
                            $sql = "SELECT
CD_Positions.Code,
CD_Positions.PositionName
FROM
CD_Positions";
                            $stmt = $db->runMsSqlQuery($sql);
                            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                                $poCode = $row['Code'];
                                $PositionName = $row['PositionName'];
                                echo '<option value=' . $poCode . '>' . $PositionName . '</option>';
                            }
                            ?>
                        </select>

                    </div>

                </div>





            </div>


        </div>

    </div>


    <div style="height: 80px;"></div>


    <div class="containerHeaderTwo" style="width: 291px; margin-top: 0px;">

        <div class="labelTxt" style="margin-left:32px; margin-bottom: 10px;"><strong>Select a Report Format :</strong></div>

        <div style="margin-left:42px;">
            <input type="radio" name="rExportXLS" id="rExportXLS" value="PDF" >Export to PDF<br>
            <input type="radio" name="rExportXLS" id="rExportXLS" value="XLS" >Export to Excel<br>
 <!--           <input type="radio" name="rExportXLS" id="rExportXLS" value="HTML" >View<br>-->

        </div>       

        <input type="submit" class="report" name="genPDF" id="genPDF" value="Generate"  style="margin-right: -593px;"/>

        <input type="hidden" name="cmbSchoolType" value="<?php echo $_SESSION["cmbSchoolType"] ?>">
        <input type="hidden" name="cmbProvince" value="<?php echo $_SESSION["cmbProvince"] ?>">
        <input type="hidden" name="cmbDistrict" value="<?php echo $_SESSION["cmbDistrict"] ?>">
        <input type="hidden" name="cmbZone" value="<?php echo $_SESSION["cmbZone"] ?>">
        <input type="hidden" name="cmbDivision" value="<?php echo $_SESSION["cmbDivision"] ?>">
        <input type="hidden" name="cmbSchool" value="<?php echo $_SESSION["cmbSchool"] ?>">
        <input type="hidden" name="cmbSchoolStatus" value="<?php echo $_SESSION["cmbSchoolStatus"] ?>">
        <input type="hidden" name="reportT" value="<?php echo $_SESSION["reportT"] ?>">



    </div>






</form>





