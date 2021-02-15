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
  $("#TempAppCat").change(function () {
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
        $("#TempSubApp").html(result);
      },
    });
  });

  var x = document.getElementById("otherdiv");
  var y = document.getElementById("inputdiv");
  var MedApp = document.getElementById("MedApp");

  $(document).on("change", "#SubApp", function () {
    var SubApp_id = $(this).val();

    // alert(SubApp_id);
    if (SubApp_id == "122" || SubApp_id == "420") {
      x.style.display = "block";
      y.style.display = "block";
    } else {
      x.style.display = "none";
      y.style.display = "none";
    }
    // alert(MedApp.value);
    MedApp.value = "";
  });

  var x = document.getElementById("Tempotherdiv");
  var y = document.getElementById("Tempinputdiv");
  var TempMedApp = document.getElementById("TempMedApp");

  $(document).on("change", "#TempSubApp", function () {
    var SubApp_id = $(this).val();

    // alert(SubApp_id);
    if (SubApp_id == "122" || SubApp_id == "420") {
      x.style.display = "block";
      y.style.display = "block";
    } else {
      x.style.display = "none";
      y.style.display = "none";
    }
    TempMedApp.value = "";
  });
});
