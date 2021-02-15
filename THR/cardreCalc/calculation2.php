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
    session_start();
    $medium = $_REQUEST['Medium'];
    $grade = $_REQUEST['GradTch'];

    $_SESSION['Medium'] = $medium;
    $_SESSION['GradTch'] = $grade;
    

    include '../db_config/connectionNEW.php';
    $NICUser = $_REQUEST['NICUser2'];

    $sqlc2 = "UPDATE ExcessDeficit 
    SET Excess = a.ApprCardre
	FROM ExcessDeficit ed
	INNER JOIN ApprovedCardre a ON ed.CenCode = a.CenCode 
	INNER JOIN ApprovedCardre b ON ed.SubCode = b.SubCode
	INNER JOIN ApprovedCardre c ON ed.Medium = c.Medium
    WHERE ed.Medium = '$medium' AND ed.SecCode = '$grade'";
    
    $sqlc1 = "UPDATE ExcessDeficit 
    SET Deficit = a.AvailableTch
	FROM ExcessDeficit ed
	INNER JOIN AvailableTeachers a ON ed.CenCode = a.CenCode 
	INNER JOIN AvailableTeachers b ON ed.SubCode = b.SubCode
	INNER JOIN AvailableTeachers c ON ed.Medium = c.Medium
    WHERE ed.Medium = '$medium' AND ed.SecCode = '$grade'";
    
    $sqlqry = "UPDATE ExcessDeficit 
	SET ExcDef = Excess-Deficit
	Where Medium = '$medium' and SecCode = '$grade'";

    $sqlc2 = sqlsrv_query($conn, $sqlc2);
    $sqlc1 = sqlsrv_query($conn, $sqlc1);
    $sqlqry = sqlsrv_query($conn, $sqlqry);

    if( $sqlc2 === false || $sqlc1 === false || $sqlqry === false ) {
        // var_dump($conn);
        die( print_r( sqlsrv_errors(), true));
    }else{
        echo "<script LANGUAGE='JavaScript'>
        window.alert('Succesfully Updated');
        window.location.href='result.php';
        </script>"; 
        // var_dump($_SESSION['GradTch']);
    }

    
    // $sqld = "DROP TABLE  #Table2$NICUser";
    // $stmtd = sqlsrv_query($conn, $sqld);
    // if( $stmtd === false) {
    //     // var_dump($conn);
    //     die( print_r( sqlsrv_errors(), true) );
    // }



?>