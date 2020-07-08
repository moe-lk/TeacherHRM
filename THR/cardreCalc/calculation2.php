<!DOCTYPE html>
<html>
<style>
.img-box{
    text-align: center;
    padding: 50px; 
}
</style>
<body onload="move()">

<div class="img-box"> 
    <img src="images/unnamed.gif";>
<div>

</body>
</html>
<?php
    include '../db_config/connectionNEW.php';
    $NICUser = $_REQUEST['NICUser2'];

    $sqlc2 = "UPDATE ExcessDeficit
    SET ExcDef = a.ApprCardre - p.AvailableTch
    FROM ExcessDeficit ed
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
        window.location.href='result.php';
        </script>"; 
    }

    // $sqld = "DROP TABLE  #Table2$NICUser";
    // $stmtd = sqlsrv_query($conn, $sqld);
    // if( $stmtd === false) {
    //     // var_dump($conn);
    //     die( print_r( sqlsrv_errors(), true) );
    // }


?>