<link href="../cms/css/screen.css" rel="stylesheet" type="text/css" />
<script src="js/teacherFilter.js"  language="javascript"></script>

<style>




    /* Create two equal columns that floats next to each other */
    .column {
        float: left;
        width: 50%;
        height: 50px;
    }

    .column2 {
        float: right;
        width: 50%;
        height: 50px;
    }


    .selectRpt{
        width:250px;
        min-width:150px;
        padding:2px;
        font-family:"Trebuchet MS", Arial, Helvetica, sans-serif;
        font-size:12px;
        line-height:24px;
        color:#666666;
        border-radius: 2px;
        border:1px solid #CCC;
    }


</style>
<?php
$nicNO = $_SESSION["NIC"];
$LOGGEDUSERID = $nicNO;

$search = $_POST['search'];
if ($search == "Y") {

    $_SESSION['svDate'] = $_POST['svDate'];
    $svDateID = $_SESSION['svDate'];

    $_SESSION['durat'] = $_POST['durat'];
    $durat = $_SESSION['durat'];
}
// var_dump($svDateID);
// $search = $_POST['search'];
// if ($search == "Y") {
//     $cmbDurationSSID = trim($_POST['cmbDurationSS']);
// }
//exit();
?>

<div class="main_content_inner_block">
    <div id="geographical" class="contenttab">


        <form id="frmReport" name="frmReport" action="" method="post" enctype="multipart/form-data" >
            <div class="productsItemBoxText">

                <div style="height: 51px;">

                    <div style="float: left;width: 279px; height:40px"><label for="username" class="labelTxt" style="margin-left:22px;"><strong>Duration in the Same School</strong></label></div>

                    <div style="float: right; height:40px">
                        <input style="margin-top:0px !important" type="button" class="report" name="" id="" onclick="submitForm();" value="Generate"  style=""/>
                    </div>
                    <div style="float: right; height:40px;margin-right: 25px;padding-top: 7px;">
                        <input type="radio" name="rExportXLS" id="rExportXLS" value="PDF" >Export to PDF
                        <input type="radio" name="rExportXLS" id="rExportXLS" value="XLS" >Export to Excel



                    </div>

                    <input type="hidden" name="cmbSchoolType" value="<?php echo $_SESSION["cmbSchoolType"] ?>">
                    <input type="hidden" name="cmbProvince" value="<?php echo $_SESSION["cmbProvince"] ?>">
                    <input type="hidden" name="cmbDistrict" value="<?php echo $_SESSION["cmbDistrict"] ?>">
                    <input type="hidden" name="cmbZone" value="<?php echo $_SESSION["cmbZone"] ?>">
                    <input type="hidden" name="cmbDivision" value="<?php echo $_SESSION["cmbDivision"] ?>">
                    <input type="hidden" name="cmbSchool" value="<?php echo $_SESSION["cmbSchool"] ?>">
                    <input type="hidden" name="cmbSchoolStatus" value="<?php echo $_SESSION["cmbSchoolStatus"] ?>">
                    <input type="hidden" name="reportT" value="<?php echo $_SESSION["reportT"] ?>">


                </div>
                <br>

                <!-- <div class="row">
                    <div class="column" >
                      <div style="width: 35%;float: left;margin-top: 5px; margin-left: 22px;">Duration :</div>
                      <div  style="margin-left:22px;">
                        <?php
                        // echo "<select name=\"cmbDurationSS\" class=\"selectRpt\" style=\"width:260px\">";
                        // $durationSS = array(All => 'All',3 => 'Greater than 3 years', 5 => 'Greater than 5 years', 7 => 'Greater than 7 years', 10 => 'Greater than 10 years', 15 => 'Greater than 15 years', 20 => 'Greater than 20 years', 25 => 'Greater than 25 years');
                        // foreach ($durationSS as $key => $value) {
                        //   if ($key == $_POST['cmbDurationSS']){
                        //     $selected = "selected=\"selected\""; } else { $selected = ""; }
                        //     echo "<option value=\"$key\" $selected>$value</option>";
                        //   }
                        //   echo "</select>";
                          ?>
                      </div>
                    </div>

                    <div class="column2">
                        <div class="">
                        <input type="hidden" name="search" id="search" value="Y" />
                        <input style="margin-top:0px !important;background-color: #92495C;font-size: 12px;
                               color: #fff;padding: 4px; margin-left: 20px;
                               border: none; width: 100px; font-weight: bold;" type="button" onclick="search_f_submit();" name="srchBtt" id="srchBtt" value="Search"/>
                        </div>
                    </div>

                </div> -->
                <?php ?>
                <div class="row">
                    <div class="column" >
                      <div style="width: 35%;float: left;margin-top: 5px; margin-left: 22px;">To Date :</div>
                      <div  style="margin-left:22px;">

                      <input type="date" class="input3" value="<?php echo isset($_SESSION['svDate']) ? $_SESSION['svDate'] : ''; ?>" name="svDate" />

                   </div>
                   
                </div>
                <div class="column2">
                <div style="width: 45%;float: left;margin-top: 5px; margin-left: 12px;">Duration greater than in year(s) :</div>
                      <div  style="margin-left:22px;">

                      <input type="text" class="input3" style="width:30px;" value="<?php echo isset($_SESSION['durat']) ? $_SESSION['durat'] : ''; ?>" name="durat" />

                   </div>
                    <div >
                        <input type="hidden" name="search" id="search" value="Y" />
                            <input style="margin-top:0px !important;background-color: #92495C;font-size: 12px;
                                          color: #fff;padding: 4px; margin-left: 20px;
                                          border: none; width: 80px; font-weight: bold;" type="button" onclick="search_f_submit();" name="srchBtt" id="srchBtt" value="Search"/>
                            </div>
                        </div>
                    <div>
                <div>
                    <div style="height: 15px;">&nbsp;<br/><br/></div>
                </div>
                


                <div>





                    <div style="height: 15px;">&nbsp;<br/><br/></div>


                </div>
            </div>
        </form>
    </div>

</div>



<div style="padding-left: 32px;">
    <table id="flex1" style="display:none"></table>
</div>


<script type="text/javascript">
    $("#flex1").flexigrid
            (
                    {
                        url: 'get_dss_worklist.php',
                        colModel: [
                            {display: 'NIC', name: 'NIC', width: 80, sortable: true, align: 'left'},
                            {display: 'SurnameWithInitials', name: 'SurnameWithInitials', width: 200, sortable: true, align: 'left'},
                            {display: 'InstitutionName', name: 'InstitutionName', width: 300, sortable: true, align: 'left'},
                            {display: 'FromDate', name: 'FromDate', width: 90, sortable: true, align: 'left'},
                            {display: 'Dur(Y)', name: 'Dur(Y)', width: 45, sortable: true, align: 'left'},
                            {display: 'Dur(M)', name: 'Dur(M)', width: 45, sortable: true, align: 'left'},
                            {display: 'Dur(D)', name: 'Dur(D)', width: 45, sortable: true, align: 'left'}
                        ],

                        sortname: "CD_CensesNo.InstitutionName",
                        sortorder: "asc",
                        params: [{name: 'cmbSchoolType', value: '<?php echo $_SESSION["cmbSchoolType"] ?>'}, {name: 'cmbProvince', value: '<?php echo $_SESSION["cmbProvince"] ?>'}, {name: 'cmbDistrict', value: '<?php echo $_SESSION["cmbDistrict"] ?>'}, {name: 'cmbZone', value: '<?php echo $_SESSION["cmbZone"] ?>'}, {name: 'cmbDivision', value: '<?php echo $_SESSION["cmbDivision"] ?>'}, {name: 'cmbSchool', value: '<?php echo $_SESSION["cmbSchool"] ?>'}, {name: 'cmbSchoolStatus', value: '<?php echo $_SESSION["cmbSchoolStatus"] ?>'}, {name: 'reportT', value: '<?php echo $_SESSION["reportT"] ?>'}, {name: 'durat', value: '<?php echo $durat ?>'}, {name: 'svDateID', value: '<?php echo $svDateID ?>'}],
                        usepager: true,
                        title: 'Duration in the Same School',
                        useRp: true,
                        rp: 20,
                        showTableToggleBtn: true,
                        width: 900,
                        height: 300
                    }
            );




    //This function adds paramaters to the post of flexigrid. You can add a verification as well by return to false if you don't want flexigrid to submit
    function addFormData()
    {
        //passing a form object to serializeArray will get the valid data from all the objects, but, if the you pass a non-form object, you have to specify the input elements that the data will come from
        var dt = $('#sform').serializeArray();
        $("#flex1").flexOptions({params: dt});
        return true;
    }


    function submitForm() {
        if (!$("input[name='rExportXLS']").is(':checked')) {
            alert("Please select a report download format");
            return false;
        } else {
            document.getElementById('frmReport').action = 'generateDurationintheSameSchool.php';
            document.getElementById('frmReport').submit();
        }
    }

    function search_f_submit() {
        document.getElementById('frmReport').action = '';
        document.getElementById('frmReport').submit();
        //  document.getElementById("frmReport").reset();
    }

</script>
