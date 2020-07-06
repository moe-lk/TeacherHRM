<!DOCTYPE html>
<html>
<style>
#myProgress {
  width: 100%;
  background-color: #ddd;
}

#myBar {
  width: 1%;
  height: 30px;
  background-color: #4CAF50;
}
</style>
<body onload="move()">

<h1>Processing...</h1>

<div id="myProgress">
  <div id="myBar"></div>
</div>

<!-- <br>
<button onclick="move()">Click Me</button>  -->

<script>
var i = 0;
function move() {
  if (i == 0) {
    i = 1;
    var elem = document.getElementById("myBar");
    var width = 1;
    var id = setInterval(frame, 10);
    function frame() {
      if (width >= 100) {
        clearInterval(id);
        i = 0;
      } else {
        width++;
        elem.style.width = width + "%";
      }
    }
  }
}
</script>

</body>
</html>
<?php
    include '../db_config/connectionNEW.php';
    $NICUser = $_REQUEST['NICUser2'];

    $sqlc2 = "UPDATE ExcessDeposit
    SET ExcDep = a.ApprCardre - p.AvailableTch
    FROM ExcessDeposit ed
    INNER JOIN AvailableTeachers p
    ON ed.CenCode = p.CenCode 
    Inner Join AvailableTeachers q
    ON ed.SubCode = q.SubCode
    Inner JOIN AvailableTeachers r
    ON ed.Medium = r.Medium
    Inner Join ApprovedCardre a
    ON ed.CenCode = a.CenCode
    INNER JOIN ApprovedCardre b
    ON ed.SubCode = b.SubCode
    INNER JOIN ApprovedCardre c
    ON ed.Medium = c.Medium";
    $sqlc2 = sqlsrv_query($conn, $sqlc2);
    if( $sqlc2 === false) {
        // var_dump($conn);
        die( print_r( sqlsrv_errors(), true));
    }else{
        echo "<script LANGUAGE='JavaScript'>
        window.alert('Succesfully Updated');
        window.location.href='index.php';
        </script>"; 
    }

    // $sqld = "DROP TABLE  #Table2$NICUser";
    // $stmtd = sqlsrv_query($conn, $sqld);
    // if( $stmtd === false) {
    //     // var_dump($conn);
    //     die( print_r( sqlsrv_errors(), true) );
    // }


?>