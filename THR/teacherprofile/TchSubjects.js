$(document).ready(function () {
  $("#GradTch1").change(function () {
    var GradTch1 = $(this).val();

    // console.log(GradTch1);
    $.ajax({
      url: "TchSubConn.php",
      type: "POST",
      data: {
        GradTchID: GradTch1,
        type: "GradTch",
      },
      dataType: "JSON",

      success: function (result) {
        // alert(result);
        $("#SubTch1").html(result);
      },
    });
  });

  $("#GradTch2").change(function () {
    var GradTch2 = $(this).val();

    // console.log(GradTch2);
    $.ajax({
      url: "TchSubConn.php",
      type: "POST",
      data: {
        GradTchID: GradTch2,
        type: "GradTch",
      },
      dataType: "JSON",

      success: function (result) {
        // alert(result);
        $("#SubTch2").html(result);
      },
    });
  });

  $("#GradTch3").change(function () {
    var GradTch3 = $(this).val();

    // console.log(GradTch3);
    $.ajax({
      url: "TchSubConn.php",
      type: "POST",
      data: {
        GradTchID: GradTch3,
        type: "GradTch",
      },
      dataType: "JSON",

      success: function (result) {
        // alert(result);
        $("#SubTch3").html(result);
      },
    });
  });
  var x = document.getElementById("otherdiv1");
  var y = document.getElementById("otherTch1");
  var a = document.getElementById("otherdiv2");
  var b = document.getElementById("otherTch2");
  var c = document.getElementById("otherdiv3");
  var d = document.getElementById("otherTch3");

  $(document).on("change", "#SubTch1", function () {
    var SubApp_id = $(this).val();
    // console.log(SubApp_id)
    if (SubApp_id == "248" || SubApp_id == "456") {
      x.style.display = "block";
      y.style.display = "block";
    } else {
      x.style.display = "none";
      y.style.display = "none";
    }
  });

  $(document).on("change", "#SubTch2", function () {
    var SubApp_id = $(this).val();
    // console.log(SubApp_id)
    if (SubApp_id == "248" || SubApp_id == "456") {
      a.style.display = "block";
      b.style.display = "block";
    } else {
      a.style.display = "none";
      b.style.display = "none";
    }
  });

  $(document).on("change", "#SubTch3", function () {
    var SubApp_id = $(this).val();
    // console.log(SubApp_id)
    if (SubApp_id == "248" || SubApp_id == "456") {
      c.style.display = "block";
      d.style.display = "block";
    } else {
      c.style.display = "none";
      d.style.display = "none";
    }
  });
});
