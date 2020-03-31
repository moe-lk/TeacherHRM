// function loadAccordingToSCType() {
//     showPleaseWait();
//     var cmbSchoolType = $("#cmbSchoolType").val();
//     var cmbProvince = $("#cmbProvince").val();
//     var cmbDistrict = $("#cmbDistrict").val();
//     var cmbZone = $("#cmbZone").val();
//     var cmbDivision = $("#cmbDivision").val();

//     $.ajax({
//         url: "ajaxCall/teacherFilterDB.php",
//         type: "POST",
//         data: {
//             RequestType: "getDataAccordingToSCType",
//             cmbSchoolType: cmbSchoolType,
//             cmbProvince: cmbProvince,
//             cmbZone: cmbZone,
//             cmbDistrict: cmbDistrict,
//             cmbDivision: cmbDivision
//         },
//         dataType: "json",
//         async: false,
//         success: function(data) {
//             var dataSchool = data[0];
//             $('#cmbSchool').html(dataSchool);

//         }
//     });
//     hidePleaseWait();
// }

function loadAccordingToProvince() {
    showPleaseWait();
    var cmbSchoolType = $("#cmbSchoolType").val();
    var cmbProvince = $("#cmbProvince").val();
    var txtLoggedUser = $("#txtLoggedUser").val();
    var txtAccessLevel = $("#txtAccessLevel").val();

    $.ajax({
        url: "ajaxCall/teacherFilterDB.php",
        type: "POST",
        data: {
            RequestType: "getDataAccordingToProvince",
            cmbSchoolType: cmbSchoolType,
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

            $('#cmbDistrict').html(dataDistrict);
            $('#cmbZone').html(dataZone);
            $('#cmbSchool').html(dataSchool);
            $('#cmbDivision').html(dataDivision);

        }
    });
     if (cmbProvince != ""){
        document.getElementById("chkSCType").disabled = true;
        document.getElementById("chkSCType").checked = false;
     }
     else
      document.getElementById("chkSCType").disabled = false;
    hidePleaseWait();
}

function loadAccordingToDistrict() {
    showPleaseWait();
    var cmbSchoolType = $("#cmbSchoolType").val();
    var cmbProvince = $("#cmbProvince").val();
    var cmbDistrict = $("#cmbDistrict").val();
    var txtLoggedUser = $("#txtLoggedUser").val();
    var txtAccessLevel = $("#txtAccessLevel").val();

    $.ajax({
        url: "ajaxCall/teacherFilterDB.php",
        type: "POST",
        data: {
            RequestType: "getDataAccordingToDistrict",
            cmbSchoolType: cmbSchoolType,
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

            $('#cmbZone').html(dataZone);
            $('#cmbDivision').html(dataDivision);
            $('#cmbSchool').html(dataSchool);
        }
    });
    if (cmbDistrict != "") {
        document.getElementById("chkProvince").disabled = true;
        document.getElementById("chkProvince").checked = false;
        document.getElementById("chkSCType").disabled = true;
        document.getElementById("chkSCType").checked = false;
    }
    else {
        document.getElementById("chkSCType").disabled = false;
        document.getElementById("chkProvince").disabled = false;
        document.getElementById("chkDistrict").disabled = false;
    }
    hidePleaseWait();

}


function loadAccordingToZone() {
    showPleaseWait();
    var cmbSchoolType = $("#cmbSchoolType").val();
    var cmbProvince = $("#cmbProvince").val();
    var cmbDistrict = $("#cmbDistrict").val();
    var cmbZone = $("#cmbZone").val();
    var txtLoggedUser = $("#txtLoggedUser").val();
    var txtAccessLevel = $("#txtAccessLevel").val();

    $.ajax({
        url: "ajaxCall/teacherFilterDB.php",
        type: "POST",
        data: {
            RequestType: "getDataAccordingToZone",
            cmbSchoolType: cmbSchoolType,
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

            $('#cmbDivision').html(dataDivision);
            $('#cmbSchool').html(dataSchool);
        }
    });
    if (cmbZone != "") {
        document.getElementById("chkSCType").disabled = true;
        document.getElementById("chkSCType").checked = false;
        document.getElementById("chkProvince").disabled = true;
        document.getElementById("chkProvince").checked = false;
        document.getElementById("chkDistrict").disabled = true;
        document.getElementById("chkDistrict").checked = false;        
    }
    else {
        document.getElementById("chkSCType").disabled = false;
        document.getElementById("chkProvince").disabled = false;
        document.getElementById("chkDistrict").disabled = false;
        document.getElementById("chkZone").disabled = false;
    }
    hidePleaseWait();

}

function loadAccordingToDivision() {
    showPleaseWait();
    var cmbSchoolType = $("#cmbSchoolType").val();
    var cmbProvince = $("#cmbProvince").val();
    var cmbDistrict = $("#cmbDistrict").val();
    var cmbZone = $("#cmbZone").val();
    var cmbDivision = $("#cmbDivision").val();
    var txtLoggedUser = $("#txtLoggedUser").val();
    var txtAccessLevel = $("#txtAccessLevel").val();

    $.ajax({
        url: "ajaxCall/teacherFilterDB.php",
        type: "POST",
        data: {
            RequestType: "getDataAccordingToSchool",
            cmbSchoolType: cmbSchoolType,
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
            $('#cmbSchool').html(dataSchool);
        }
    });
    if (cmbDivision != "") {
        document.getElementById("chkSCType").disabled = true;
        document.getElementById("chkSCType").checked = false;
        document.getElementById("chkProvince").disabled = true;
        document.getElementById("chkProvince").checked = false;
        document.getElementById("chkDistrict").disabled = true;
        document.getElementById("chkDistrict").checked = false;
        document.getElementById("chkZone").disabled = true;
        document.getElementById("chkZone").checked = false;
    }
    else {
        document.getElementById("chkSCType").disabled = false;
        document.getElementById("chkProvince").disabled = false;
        document.getElementById("chkDistrict").disabled = false;
        document.getElementById("chkZone").disabled = false;
        document.getElementById("chkDivision").disabled = false;
    }
    hidePleaseWait();

}

function hidePleaseWait()
{
    setTimeout(function() {
        $("#divPleasewait").remove();
    }, 1000);

}

function showPleaseWait() {
//    $('body').append($('<div/>', {
//        id: 'divPleasewait',
//        class: 'modal'
//    }));

    var $div = $('<div />').appendTo('body');
    $div.attr('id', 'divPleasewait');
    $div.attr('class', 'modal');
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

    if (bioItemVal != "") {
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
                //strTxt += '<table width="100%" border="0" id="tblBioSub">';
                strTxt += '<select id="bioSubItem">';
                $.each(data, function(i, value) {
                    strTxt += '<option value=' + data[i].feildCode + '>' + data[i].feildName + '</option>';
                });
                //strTxt += '</table>';
                strTxt += '</select>';
                $("#bioDetail").show();
                $("#dioImg").show();
                $("#bioDetail").html(strTxt);
            }
        });
    }
    else {
        $("#bioDetail").hide();
        $("#dioImg").hide();
    }
}

function addValuesToTable()
{
    /*
     var tbl = document.getElementById("tblBioSub");
     var rowIndex = obj.parentNode.rowIndex;
     var bioItemText = tbl.rows[rowIndex].cells[0].innerHTML;
     var bioItemCode = tbl.rows[rowIndex].cells[1].innerHTML;
     var bioItemName = tbl.rows[rowIndex].cells[2].innerHTML;
     */


    var bioItemText = $('#bioItems option:selected').text();
    var bioSubItemVal = $("#bioSubItem").val();
    var bioSubItemText = $('#bioSubItem option:selected').text();

    var operation = "=";
    var strTxt = "";
    strTxt += '<tr height="24">' +
            '<td bgcolor="#FFFFFF">' + bioItemText + '<input type="hidden" name="txtBioFeildName[]" value="' + bioItemText + '"/></td>' +
            '<td bgcolor="#FFFFFF" align="center">' + operation + '<input type="hidden" name="txtBioOperation[]" value="' + operation + '"/></td>' +
            '<td bgcolor="#FFFFFF">' + bioSubItemText + '<input type="hidden" name="txtBioItemCode[]" value="' + bioSubItemVal + '"/><input type="hidden" name="txtBioItemName[]" value="' + bioSubItemText + '"/></td>' +
            '<td bgcolor="#FFFFFF" align="center"><img src="images/trash.png" width="14" height="14" onclick="rmvRow(this);"/></td>' +
            '<td bgcolor="#FFFFFF" align="center"><input type="checkbox" name="groupBy[]" id="" onclick="disableBioCheckbox(this);" value="' + bioItemText + '"/></td>' +
            '</tr>';

    $("#tblMainBioDetails").append(strTxt);
    $("#bioDetail").hide();
    $("#dioImg").hide();
    $("#bioItems").val("");
}
function rmvRow(obj) {
    $(obj).parent().parent().remove();
    var tblTeach = document.getElementById("tblMainTeachDetails");
    if(tblTeach.rows.length<=1){
        document.getElementById("chkTeach").disabled = true;
        document.getElementById("chkTeach").checked = false;
    }
    var tblService = document.getElementById("tblMainServiceDetails");
    if(tblService.rows.length<=1){
        document.getElementById("chkPosition").disabled = true;
        document.getElementById("chkPosition").checked = false;
    }
    
    var tblQualification = document.getElementById("tblMainQuliDetails");
    if(tblQualification.rows.length<=1){
        document.getElementById("chkQuli").disabled = true;
        document.getElementById("chkQuli").checked = false;
    }
    
}

function addRowToQulification()
{
    var qulificationVal = $("#qulificationItem").val();
    var qulificationText = $('#qulificationItem option:selected').text();

    // alert(qulificationVal);
    if (qulificationVal != "") {
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
        document.getElementById("chkQuli").disabled = false;
    }


}

// function addRowToTeachingtbl() {
//     var typeVal = $("#teachingType").val();
//     var typeText = $('#teachingType option:selected').text();
//     var subjectVal = $("#teachingSubject").val();
//     var subjectText = $('#teachingSubject option:selected').text();
//     var gradeVal = $("#teachingGrade").val();
//     var gradeText = $('#teachingGrade option:selected').text();

//     if (typeVal != "") {
//         var strTxt = "";
//         strTxt += '<tr height="24">' +
//                 '<td bgcolor="#FFFFFF">' + typeText + '<input type="hidden" name="txtTeachType[]" value="' + typeVal + '"/><input type="hidden" name="txtTeachTypeName[]" value="' + typeText + '"/></td>' +
//                 '<td bgcolor="#FFFFFF">' + subjectText + '<input type="hidden" name="txtTeachSubject[]" value="' + subjectVal + '"/><input type="hidden" name="txtTeachSubjectName[]" value="' + subjectText + '"/></td>' +
//                 '<td bgcolor="#FFFFFF">' + gradeText + '<input type="hidden" name="txtTeachGrade[]" value="' + gradeVal + '"/><input type="hidden" name="txtTeachGradeName[]" value="' + gradeText + '"/></td>' +
//                 '<td bgcolor="#FFFFFF" align="center"><img src="images/trash.png" width="14" height="14" onclick="rmvRow(this);"/></td>' +
//                 '</tr>';

//         $("#tblMainTeachDetails").append(strTxt);
//         $("#teachingType").val('');
//         $("#teachingSubject").val('');
//         $("#teachingGrade").val('');
//         document.getElementById("chkTeach").disabled = false;

//     }
//     else {
//         alert("Please select teaching type");
//     }


// }

// function addRowToServicetbl() {
//     var positionVal = $("#serviceposition").val();
//     var positionText = $('#serviceposition option:selected').text();
//     var serviceTypeVal = $("#serviceType").val();
//     var serviceTypeText = $('#serviceType option:selected').text();

//     if (positionVal != "") {
//         var strTxt = "";
//         strTxt += '<tr height="24">' +
//                 '<td bgcolor="#FFFFFF">' + positionText + '<input type="hidden" name="txtSPosition[]" value="' + positionVal + '"/><input type="hidden" name="txtSPositionName[]" value="' + positionText + '"/></td>' +
//                 '<td bgcolor="#FFFFFF">' + serviceTypeText + '<input type="hidden" name="txtSType[]" value="' + serviceTypeVal + '"/><input type="hidden" name="txtSTypeName[]" value="' + serviceTypeText + '"/></td>' +
//                 '<td bgcolor="#FFFFFF" align="center"><img src="images/trash.png" width="14" height="14" onclick="rmvRow(this);"/></td>' +
//                 '</tr>';

//         $("#tblMainServiceDetails").append(strTxt);
//         $("#serviceposition").val('');
//         $("#serviceType").val('');
//         document.getElementById("chkPosition").disabled = false;
//     }
//     else {
//         alert("Please select position");
//     }
// }

// function submitForm(type) {
//     var flag = true;
//     var reportType = $('input[name="reportT"]:checked').val();
//     var action = "";

//     if (reportType == "DR" || reportType == "SR") {
//         if (type == "mail") {
//             var emailAdd = $("#txtemailAddress").val();

//             if (emailAdd == "" || emailAdd == "N/A") {
//                 flag = false;
//                 $("#txtemailAddress").val("");
//                 $("#hiddenVal").show();
//             }
//             else {
//                 var atpos = emailAdd.indexOf("@");
//                 var dotpos = emailAdd.lastIndexOf(".");
//                 if (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= emailAdd.length)
//                 {
//                     alert("Not a valid e-mail address");
//                     return false;
//                 }
//             }
//             if (reportType == "DR") {
//                 action = "sendEmailDetailReport.php";
//             }
//             if (reportType == "SR") {
//                 action = "sendEmailSummaryReport.php";
//                 var valStatus = validateGroupBy();
//                 if (!valStatus)
//                     flag = false;
//             }
//         }
//         if (type == "report") {
//             if (reportType == "SR") {
//                 action = "generateSummaryPDF.php";
//                 var valStatus = validateGroupBy();
//                 if (!valStatus)
//                     flag = false;
//             }
//             if (reportType == "DR") {
//                 action = "generatePDF.php"
//             }
//         }
//         if (type == "Qsave") {
//             action = "saveQuery.php"
//         }



//         if (flag) {
//             document.getElementById('form1').action = action;
//             document.getElementById('form1').submit();
//             document.getElementById("form1").reset();
            
//         }

//     }
//     else {
//         alert("Please select a report type");
//     }
// }

function SaveQueryForm(qName) {
    var action = "index.php";
    document.getElementById('form1').action = action;
    $("#hidquerySave").val('QS');
    document.getElementById('hidqueryName').value = qName;

    document.getElementById('form1').submit();
    document.getElementById("form1").reset();

}
function disableCheckBox() {
    document.getElementById("chkProvince").disabled = true;
    document.getElementById("chkDistrict").disabled = true;
    document.getElementById("chkZone").disabled = true;
    document.getElementById("chkDivision").disabled = true;
}

function loadQNameDiv() {
    if ($('#rSaveQuery').is(":checked")){
        $("#hiddenQName").show();
        $("#genEmail").hide();
        $("#genPDF").hide();
        $("#divRptHedding").hide();
        $("#saveQuery").show();
    }
    else{
        document.getElementById("genPDF").value = "Print Report";
        $("#hiddenQName").hide();
        $("#genEmail").show();
        $("#genPDF").show();
        $("#saveQuery").hide();
        $("#divRptHedding").show();
    }
}

function hideEmailReport() {
    if ($('#rExportXLS').is(":checked"))
        $("#genEmail").hide();
    else
        $("#genEmail").show();
}

function unchekedBoxes() {
    document.getElementById("chkProvince").disabled = false;
    document.getElementById("chkProvince").checked = false;
    document.getElementById("chkDistrict").disabled = false;
    document.getElementById("chkDistrict").checked = false;
    document.getElementById("chkZone").disabled = false;
    document.getElementById("chkZone").checked = false;
    document.getElementById("chkDivision").disabled = false;
    document.getElementById("chkDivision").checked = false;
}

function validateGroupBy() {
    var chkBoxGroupBy = document.getElementsByName("groupBy[]");
    var chkFlag = false;
    for (var i = 0; i < chkBoxGroupBy.length; i++) {

        if (chkBoxGroupBy[i].checked) {
            chkFlag = false;
            break;
        }
        else {
            chkFlag = true;
        }

    }
    if (chkFlag) {
        alert("Please tick the tick boxes for group by.");
        return false;
    }
    else
        return true;
}

function logoutForm() {
    var action = "../login.php?request=signOut";
    document.getElementById('form1').action = action;
    document.getElementById('form1').submit();
}

function disableBioCheckbox(obj) {
    var tbl = document.getElementById('tblMainBioDetails');
    var rowIndex = obj.parentNode.parentNode.rowIndex;
    if (tbl.rows[rowIndex].cells[4].childNodes[0].checked)
    {
        for (i = 1; i < tbl.rows.length; i++) {
            if (!tbl.rows[i].cells[4].childNodes[0].checked) {
                tbl.rows[i].cells[4].childNodes[0].disabled = true;
            }
        }
    }
    else {
        for (i = 1; i < tbl.rows.length; i++) {
            tbl.rows[i].cells[4].childNodes[0].disabled = false;
        }

    }

}