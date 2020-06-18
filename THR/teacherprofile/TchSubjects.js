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
});
