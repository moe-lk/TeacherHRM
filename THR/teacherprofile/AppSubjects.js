$(document).ready(function () {
  var SubApp = document.getElementById("SubApp");
  $("#AppCat").change(function () {
    var AppCat = $(this).val();

    if (SubApp.value == "Select") {
      // alert(SubApp.value);
      SubApp.required = true;
    }

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
        alert(result);
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

  var a = document.getElementById("otherdiv");
  var b = document.getElementById("inputdiv");
  var c = document.getElementById("otherSub");
  var MedApp = document.getElementById("MedApp");

  $(document).on("change", "#SubApp", function () {
    var SubApp_id = $(this).val();

    if (
      SubApp_id == "122" ||
      SubApp_id == "420" ||
      SubApp_id == "236" ||
      SubApp_id == "321"
    ) {
      a.style.display = "block";
      b.style.display = "block";
      c.required = true;
    } else {
      a.style.display = "none";
      b.style.display = "none";
    }
    // alert(MedApp.value);
    MedApp.value = "";
  });

  var x = document.getElementById("Tempotherdiv");
  var y = document.getElementById("Tempinputdiv");
  var z = document.getElementById("TempotherSub");
  var TempMedApp = document.getElementById("TempMedApp");

  $(document).on("change", "#TempSubApp", function () {
    var SubApp_id = $(this).val();

    // alert(SubApp_id);
    if (
      SubApp_id == "122" ||
      SubApp_id == "420" ||
      SubApp_id == "236" ||
      SubApp_id == "321"
    ) {
      x.style.display = "block";
      y.style.display = "block";
      z.required = true;
    } else {
      x.style.display = "none";
      y.style.display = "none";
    }
    TempMedApp.value = "";
  });
});
