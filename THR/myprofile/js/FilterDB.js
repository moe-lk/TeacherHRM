function filterClass() {
    showPleaseWait();
    var GradeID = $("#GradeID").val();
	var SchoolID = $("#SchoolID").val();
 
    $.ajax({
     
        url: "ajaxCall/FilterDB.php",
        type: "POST",
        data: {			
            RequestType: "getClassData",
            GradeID: GradeID,
            SchoolID: SchoolID
        },
        dataType: "json",
        async: false,
        success: function(data) {
            var dataSchool = data[0];
            $('#ClassID').html(dataSchool);

        }
    });
    hidePleaseWait();
}

function showPleaseWait() {
    var $div = $('<div />').appendTo('body');
    $div.attr('id', 'divPleasewait');
    $div.attr('class', 'modal');
}

function hidePleaseWait()
{
    setTimeout(function() {
        $("#divPleasewait").remove();
    }, 1000);

}
function logoutForm() {
    var action = "../login.php?request=signOut";
    document.getElementById('form1').action = action;
    document.getElementById('form1').submit();
}