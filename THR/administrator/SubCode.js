$(document).ready(function () {
  var subcode = document.getElementById("subcode");
  $("#SubjCatCode").change(function () {
    var SubjCatCode = $(this).val();
    // alert(SubjCatCode);
    // if (subcode.value == "Select") {
    //   // alert(subcode.value);
    //   subcode.required = true;
    // }

    // console.log(SubjCatCode);

    $.ajax({
      url: "SubCodeConn.php",
      type: "POST",
      data: {
        SubjCatCodeID: SubjCatCode,
        type: "SubjCatCode",
      },
      dataType: "JSON",

      success: function (result) {
        // alert(result);
        $("#subcode").html(result);
      },
    });
  });
});
