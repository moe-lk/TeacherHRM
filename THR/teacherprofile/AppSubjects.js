var x = document.getElementById("otherdiv");
var y = document.getElementById("inputdiv");

$(document).ready(function () {
  $("#AppCat").change(function () {
    var AppCat = $(this).val();

    // console.log(AppCat);
    $.ajax({
      url: "AppSubConn.php",
      type: "POST",
      data: {
        AppCatID: AppCat,
        type: "AppCat",
      },
      dataType: "JSON",

      success: function (result) {
        // alert(result);
        $("#SubApp").html(result);
      },
    });
  });
});
