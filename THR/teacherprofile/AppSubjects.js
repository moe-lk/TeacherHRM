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

  $(document).on("change", "#SubApp", function () {
    var SubApp_id = $(this).val();

    alert(SubApp_id);
    if (SubApp_id == "4") {
      x.style.display = "block";
      y.style.display = "block";
    } else {
      x.style.display = "none";
      y.style.display = "none";
    }
  });
});
