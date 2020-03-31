/* 
 * Author : Thushara Perera
 * Date : 03-04-2014
 */

function loadAccordingToProvince() {
    showPleaseWait();
    var cmbProvince = $("#cmbProvince").val();
    var txtLoggedUser = $("#txtLoggedUser").val();
    var txtAccessLevel = $("#txtAccessLevel").val();
    $('#cmbDistrict').html("");
    $('#cmbZone').html("");
    $('#cmbDivision').html("");
    $('#cmbSchool').html("");
    $.ajax({
        url: "ajaxCall/teacherFilterDB.php",
        type: "POST",
        data: {
            RequestType: "getDataAccordingToProvince",
            cmbProvince: cmbProvince,
            txtLoggedUser: txtLoggedUser,
            txtAccessLevel: txtAccessLevel
        },
        dataType: "json",
        async: false,
        success: function(data) {
            var dataDistrict = data[0];
            var dataZone = data[1];
            var dataSchool = data[2];
            var dataDivision = data[3];
            $('#cmbDistrict').append(dataDistrict);
            $('#cmbZone').append(dataZone);
            $('#cmbSchool').append(dataSchool);
            $('#cmbDivision').append(dataDivision);
        }
    });
    hidePleaseWait();
}


function loadAccordingToDistrict() {
    showPleaseWait();
    var cmbProvince = $("#cmbProvince").val();
    var cmbDistrict = $("#cmbDistrict").val();
    var txtLoggedUser = $("#txtLoggedUser").val();
    var txtAccessLevel = $("#txtAccessLevel").val();    
    
    document.getElementById('cmbDivision').options.length = 0;
    document.getElementById('cmbZone').options.length = 0;
    document.getElementById('cmbSchool').options.length = 0;
    
    $.ajax({
        url: "ajaxCall/teacherFilterDB.php",
        type: "POST",
        data: {
            RequestType: "getDataAccordingToDistrict",
            cmbProvince: cmbProvince,
            cmbDistrict: cmbDistrict,
            txtLoggedUser: txtLoggedUser,
            txtAccessLevel: txtAccessLevel
        },
        dataType: "json",
        async: false,
        success: function(data) {
            var dataDivision = data[0];
            var dataSchool = data[1];
            var dataZone = data[2];
            // var dataDivision = data[3];
            // $('#cmbDistrict').append(dataDistrict);

            $('#cmbZone').append(dataZone);
            $('#cmbDivision').append(dataDivision);
            $('#cmbSchool').append(dataSchool);
        }
    });
 
    
    hidePleaseWait();
}


function loadAccordingToZone() {
    showPleaseWait();
    var cmbProvince = $("#cmbProvince").val();
    var cmbDistrict = $("#cmbDistrict").val();
    var cmbZone = $("#cmbZone").val();
    var txtLoggedUser = $("#txtLoggedUser").val();
    var txtAccessLevel = $("#txtAccessLevel").val();

    //$('#cmbZone').html("");
    $('#cmbDivision').html("");
    $('#cmbSchool').html("");
    $.ajax({
        url: "ajaxCall/teacherFilterDB.php",
        type: "POST",
        data: {
            RequestType: "getDataAccordingToZone",
            cmbProvince: cmbProvince,
            cmbZone: cmbZone,
            cmbDistrict: cmbDistrict,
            txtLoggedUser: txtLoggedUser,
            txtAccessLevel: txtAccessLevel
        },
        dataType: "json",
        async: false,
        success: function(data) {
            var dataDivision = data[0];
            var dataSchool = data[1];

            $('#cmbDivision').append(dataDivision);
            $('#cmbSchool').append(dataSchool);
        }
    });
    hidePleaseWait();
}

function loadAccordingToDivision() {
    showPleaseWait();
    var cmbProvince = $("#cmbProvince").val();
    var cmbDistrict = $("#cmbDistrict").val();
    var cmbZone = $("#cmbZone").val();
    var cmbDivision = $("#cmbDivision").val();
    var txtLoggedUser = $("#txtLoggedUser").val();
    var txtAccessLevel = $("#txtAccessLevel").val();

    $('#cmbSchool').html("");
    $.ajax({
        url: "ajaxCall/teacherFilterDB.php",
        type: "POST",
        data: {
            RequestType: "getDataAccordingToSchool",
            cmbProvince: cmbProvince,
            cmbZone: cmbZone,
            cmbDistrict: cmbDistrict,
            cmbDivision: cmbDivision,
            txtLoggedUser: txtLoggedUser,
            txtAccessLevel: txtAccessLevel
        },
        dataType: "json",
        async: false,
        success: function(data) {
            var dataSchool = data[0];
            $('#cmbSchool').append(dataSchool);
        }
    });
    hidePleaseWait();
}





function hidePleaseWait()
{
    try
    {
        var box = document.getElementById('divPleasewait');
        box.parentNode.removeChild(box);
    }
    catch (err)
    {
    }
}

function showPleaseWait() {
    $('body').append($('<div/>', {
        id: 'divPleasewait',
        class: 'modal'
    }));
}

function generatePDF() {
    /*showPleaseWait();
     
     $.ajax({
     url: "ajaxCall/generateTeacherPDFReport.php",
     type: "POST",
     data: $("#form1").serialize(),
     dataType: "html",
     async: false,
     success: function() {
     }
     });
     hidePleaseWait();
     */
    document.getElementById('form1').submit();

    //var reportLink = "ajaxCall/generateTeacherPDFReport.php?";
    //window.open(reportLink);
}

function loadBioDetails() {
    var bioItemVal = $("#bioItems").val();
    var bioItemText = $('#bioItems option:selected').text();

    $.ajax({
        url: "ajaxCall/teacherFilterDB.php",
        type: "POST",
        data: {
            RequestType: "getBiographicalDetail",
            bioItemVal: bioItemVal
        },
        dataType: "json",
        async: false,
        success: function(data) {
            var strTxt = "";
            strTxt += '<table width="100%" border="0" id="tblBioSub">';
            $.each(data, function(i, value) {


                strTxt += '<tr height="24">' +
                        '<td style="display:none;">' + bioItemText + '</td>' +
                        '<td>' + data[i].feildCode + '</td>' +
                        '<td onclick="addValuesToTable(this);">' + data[i].feildName + '</td>' +
                        '</tr>';
            });
            strTxt += '</table>';

            $("#bioDetail").html(strTxt);
        }
    });
}

function addValuesToTable(obj)
{
    var tbl = document.getElementById("tblBioSub");
    var rowIndex = obj.parentNode.rowIndex;
    var bioItemText = tbl.rows[rowIndex].cells[0].innerHTML;
    var bioItemCode = tbl.rows[rowIndex].cells[1].innerHTML;
    var bioItemName = tbl.rows[rowIndex].cells[2].innerHTML;
    var operation = "=";
    var strTxt = "";
    strTxt += '<tr height="24">' +
            '<td bgcolor="#FFFFFF">' + bioItemText + '<input type="hidden" name="txtBioFeildName[]" value="' + bioItemText + '"/></td>' +
            '<td bgcolor="#FFFFFF" align="center">' + operation + '<input type="hidden" name="txtBioOperation[]" value="' + operation + '"/></td>' +
            '<td bgcolor="#FFFFFF">' + bioItemName + '<input type="hidden" name="txtBioItemCode[]" value="' + bioItemCode + '"/></td>' +
            '<td bgcolor="#FFFFFF" align="center"><img src="images/trash.png" width="14" height="14" onclick="rmvRow(this);"/></td>' +
            '</tr>';

    $("#tblMainBioDetails").append(strTxt);
}
function rmvRow(obj) {
    $(obj).parent().parent().remove();
}

function addRowToQulification()
{
    var qulificationVal = $("#qulificationItem").val();
    var qulificationText = $('#qulificationItem option:selected').text();

    var txtQlification = document.getElementsByName("txtQuliName[]");
    for (var i = 0; i < txtQlification.length; i++) {

        if (txtQlification[i].value == qulificationVal) {
            alert("Record already exit.");
            return false;
        }

    }

    var strTxt = "";
    strTxt += '<tr height="24">' +
            '<td bgcolor="#FFFFFF">' + qulificationText + '</td>' +
            '<td bgcolor="#FFFFFF" align="center"><img src="images/trash.png" width="14" height="14" onclick="rmvRow(this);"/><input type="hidden" name="txtQuliName[]" value="' + qulificationVal + '"/></td>' +
            '</tr>';

    $("#tblMainQuliDetails").append(strTxt);
    $("#qulificationItem").val('');


}

function addRowToTeachingtbl() {
    var typeVal = $("#teachingType").val();
    var typeText = $('#teachingType option:selected').text();
    var subjectVal = $("#teachingSubject").val();
    var subjectText = $('#teachingSubject option:selected').text();
    var gradeVal = $("#teachingGrade").val();
    var gradeText = $('#teachingGrade option:selected').text();


    var strTxt = "";
    strTxt += '<tr height="24">' +
            '<td bgcolor="#FFFFFF">' + typeText + '<input type="hidden" name="txtTeachType[]" value="' + typeVal + '"/></td>' +
            '<td bgcolor="#FFFFFF">' + subjectText + '<input type="hidden" name="txtTeachSubject[]" value="' + subjectVal + '"/></td>' +
            '<td bgcolor="#FFFFFF">' + gradeText + '<input type="hidden" name="txtTeachGrade[]" value="' + gradeVal + '"/></td>' +
            '<td bgcolor="#FFFFFF" align="center"><img src="images/trash.png" width="14" height="14" onclick="rmvRow(this);"/></td>' +
            '</tr>';

    $("#tblMainTeachDetails").append(strTxt);
    $("#teachingType").val('');
    $("#teachingSubject").val('');
    $("#teachingGrade").val('');


}

function addRowToServicetbl(){
    var positionVal = $("#serviceposition").val();
    var positionText = $('#serviceposition option:selected').text();
    var serviceTypeVal = $("#serviceType").val();
    var serviceTypeText = $('#serviceType option:selected').text();


    var strTxt = "";
    strTxt += '<tr height="24">' +
            '<td bgcolor="#FFFFFF">' + positionText + '<input type="hidden" name="txtTeachType[]" value="' + positionVal + '"/></td>' +
            '<td bgcolor="#FFFFFF">' + serviceTypeText + '<input type="hidden" name="txtTeachSubject[]" value="' + serviceTypeVal + '"/></td>' +
            '<td bgcolor="#FFFFFF" align="center"><img src="images/trash.png" width="14" height="14" onclick="rmvRow(this);"/></td>' +
            '</tr>';

    $("#tblMainServiceDetails").append(strTxt);
    $("#serviceposition").val('');
    $("#serviceType").val('');
}