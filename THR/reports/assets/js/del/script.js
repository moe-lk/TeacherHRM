function emailValidate(email) {
    var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    //var reg = /^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/
    if (reg.test(email) == false) {
        return false;
    }
    return true;
}

function trim(str) {
    return str.replace(/^\s+|\s+$/g, '');

}

$(function() {
    $(".input2").click(function() {
        changeTextBoxCss(this);
    });

    $(".input2").keyup(function() {
        changeTextBoxCss(this);
    });

    $(".select1").change(function() {
        changeComBoBoxCss(this);
    });

    $(".select1").keyup(function() {
        changeComBoBoxCss(this);
    });
    $(".textarea2").click(function() {
        changeTextAreaCss(this);
    });

    $(".textarea2").keyup(function() {
        changeTextAreaCss(this);
    });


});

function changeTextBoxCss(obj) {
    var currentId = $(obj).attr('id');
    $("#" + currentId).attr('class', 'input2');
}
function changeComBoBoxCss(obj) {
    var currentId = $(obj).attr('id');
    $("#" + currentId).attr('class', 'select1');
}
function changeTextAreaCss(obj) {
    var currentId = $(obj).attr('id');
    $("#" + currentId).attr('class', 'textarea2');
}

function numericFilter(txb) {
    txb.value = txb.value.replace(/[^\0-9]/ig, "");
}