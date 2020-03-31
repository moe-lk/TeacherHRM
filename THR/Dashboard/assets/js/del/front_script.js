
$(function() {
    $(".input11").click(function() {
        //alert("hi");
        changeTextBoxCssFront(this,'input11');
    });

    $(".input11").keyup(function() {
        changeTextBoxCssFront(this,'input11');
    });
    
    $(".input12").click(function() {
        changeTextBoxCssFront(this,'input12');
    });

    $(".input12").keyup(function() {
        changeTextBoxCssFront(this,'input12');
    });
    
    $(".input9").click(function() {
        changeTextBoxCssFront(this,'input9');
    });

    $(".input9").keyup(function() {
        changeTextBoxCssFront(this,'input9');
    });
    
    $(".input8").click(function() {
        changeTextBoxCssFront(this,'input8');
    });

    $(".input8").keyup(function() {
        changeTextBoxCssFront(this,'input8');
    });
    
    $(".input10").click(function() {
        changeTextBoxCssFront(this,'input10');
    });

    $(".input10").keyup(function() {
        changeTextBoxCssFront(this,'input10');
    });
    
    $(".input3").click(function() {
        changeTextBoxCssFront(this,'input3');
    });

    $(".input3").keyup(function() {
        changeTextBoxCssFront(this,'input3');
    });
    
    $(".textarea3").click(function() {
        changeTextBoxCssFront(this,'textarea3');
    });

    $(".textarea3").keyup(function() {
        changeTextBoxCssFront(this,'textarea3');
    });
    
    
    
// drop down
    $(".select2").change(function() {
        changeComBoBoxCssFront(this,'select2');
    });

    $(".select2").keyup(function() {
        changeComBoBoxCssFront(this,'select2');
    });

    $(".select3").change(function() {
        changeComBoBoxCssFront(this,'select3');
    });

    $(".select3").keyup(function() {
        changeComBoBoxCssFront(this,'select3');
    });
    
    $(".select4").change(function() {
        changeComBoBoxCssFront(this,'select3');
    });

    $(".select4").keyup(function() {
        changeComBoBoxCssFront(this,'select3');
    });
    
    $(".select5").change(function() {
        changeComBoBoxCssFront(this,'select5');
    });

    $(".select5").keyup(function() {
        changeComBoBoxCssFront(this,'select5');
    });


});

function changeTextBoxCssFront(obj,cssclass) {
    var currentId = $(obj).attr('id');
    $("#" + currentId).attr('class', cssclass);
}
function changeComBoBoxCssFront(obj,cssclass) {
    var currentId = $(obj).attr('id');
    $("#" + currentId).attr('class', cssclass);
}

function isPhoneNumber(evt)
{   
   evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;    
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}