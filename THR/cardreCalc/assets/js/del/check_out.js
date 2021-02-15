$(function() {
        $("input[name='check_in_type']").change(function() {
            var check_in_type = $("input[name=check_in_type]:checked").val();
            if(check_in_type=="guest"){
                $("#already_register").hide();
                $("#bill_info_register").hide();
                $("#bill_info_guest").show();
            }else{
                $("#already_register").show();
            }
        });
    });