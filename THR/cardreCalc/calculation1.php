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
.img-box{
    text-align: center;
    padding: 50px; 
}
</style>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<body onload="move()">

<!-- <div class="img-box"> 
    <img src="images/unnamed.gif";>
<div> -->

<!-- <h1>Processing...</h1>

<div id="myProgress">
  <div id="myBar"></div>
</div> -->

<script>
// var i = 0;
// function move() {
//   if (i == 0) {
//     i = 1;
//     var elem = document.getElementById("myBar");
//     var width = 1;
//     var id = setInterval(frame, 10);
//     function frame() {
//       if (width >= 100) {
//         clearInterval(id);
//         i = 0;
//       } else {
//         width++;
//         elem.style.width = width + "%";
//       }
//     }
//   }
// }
</script>

</body>
</html>
<?php
    session_start();
    include '../db_config/connectionNEW.php';
    $SchType = $_REQUEST['SchType'];
    $NICUser = $_REQUEST['NICUser'];
    $accLevel = $_REQUEST["accLevel"];
    $loggedPositionName = $_REQUEST['loggedPositionName'];
    $accessRoleType = $_REQUEST['accessRoleType'];
    $ProCode = $_REQUEST['ProCode'];
    $District = $_REQUEST['District'];
    $ZONECODE = $_REQUEST['ZONECODE'];
    $_SESSION['SchType'] = $SchType;
    // var_dump($_SESSION);

    $sqlcount1 = "SELECT Count(TeacherMast.NIC) AS Count1 FROM TeacherMast 
    LEFT OUTER JOIN TeachingDetails ON TeacherMast.NIC = TeachingDetails.NIC
    INNER JOIN StaffServiceHistory ON StaffServiceHistory.ID = TeacherMast.CurServiceRef 
    INNER JOIN CD_CensesNo ON StaffServiceHistory.InstCode = CD_CensesNo.CenCode 
    INNER JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode 
    INNER JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode 
    WHERE TeachingDetails.ID IS NULL 
    AND CD_CensesNo.SchoolType = '$SchType'";
    if($ProCode != ''){
        $sqlcount1 .= " AND CD_Provinces.ProCode = '$ProCode'";
    }
    if($District != ''){
        $sqlcount1 .= " AND CD_CensesNo.DistrictCode = '$District'";
    }
    if($ZONECODE != ''){
        $sqlcount1 .= " AND CD_CensesNo.ZoneCode = '$ZONECODE'";
    }

    $stmtcount1 = sqlsrv_query($conn, $sqlcount1);
    // $stmtcount2 = sqlsrv_query($conn, $sqlcount2);

    // print_r($stmtcount1);
    // print_r($stmtcount2);
    $rowcount1 = sqlsrv_fetch_array($stmtcount1, SQLSRV_FETCH_ASSOC);
    // $rowcount2 = sqlsrv_fetch_array($stmtcount2, SQLSRV_FETCH_ASSOC);
    // var_dump($rowcount1['Count1']);
    // var_dump($rowcount2['Count2']);
    $count1 = $rowcount1['Count1'];
    // $count2 = $rowcount2['Count2']; 
    
    if($count1 > 0){
        // print_r($sqlcount1);
        echo "<script LANGUAGE='JavaScript'>
                window.alert('Incomplete Data!');
                window.location.href='teacherlist.php';
                </script>";
    }
    else{
        $sql = "SELECT CenCode, TchSubject1, Medium1, Count(TeachingDetails.NIC) AS AvailableTCH
        INTO #Table2$NICUser
        FROM TeachingDetails 
        INNER JOIN TeacherMast ON TeachingDetails.NIC = TeacherMast.NIC
        INNER JOIN StaffServiceHistory ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
        INNER JOIN CD_CensesNo ON CD_CensesNo.CenCode = StaffServiceHistory.InstCode
        INNER JOIN CD_Districts ON CD_CensesNo.DistrictCode = CD_Districts.DistCode
        INNER JOIN CD_Provinces ON CD_Districts.ProCode = CD_Provinces.ProCode
        WHERE CD_CensesNo.SchoolType = '$SchType'";
        if($ProCode != ''){
            $sql .= " AND CD_Provinces.ProCode = '$ProCode'";
        }
        if($District != ''){
            $sql .= " AND CD_CensesNo.DistrictCode = '$District'";
        }
        if($ZONECODE != ''){
            $sql .= " AND CD_CensesNo.ZoneCode = '$ZONECODE'";
        }
        $sql .=" GROUP BY CenCode, TchSubject1, Medium1";


        $sqlu = "UPDATE AvailableTeachers 
        SET 
        AvailableTeachers.AvailableTch = p.AvailableTCH,
        AvailableTeachers.RecordStatus = 1,
        AvailableTeachers.RecordLog = '$NICUser',
        CalculatedDate = GETDATE()
        FROM AvailableTeachers av
        INNER JOIN #Table2$NICUser p
        ON av.CenCode = p.CenCode 
        Inner Join #Table2$NICUser q
        ON av.SubCode = q.TchSubject1
        Inner JOIN #Table2$NICUser r
        ON av.Medium = r.Medium1";

        // var_dump($conn);
        $stmt1 = sqlsrv_query($conn, $sql);
        $stmt2 = sqlsrv_query($conn, $sqlu);

        if( $stmt1 === false || $stmt2 === false) {
            die( print_r( sqlsrv_errors(), true) );
        }
        else{
            $sqld = "DROP TABLE  #Table2$NICUser";
            $stmtd = sqlsrv_query($conn, $sqld);
            if( $stmtd === false) {
                // var_dump($conn);
                die( print_r( sqlsrv_errors(), true) );
            }
            else{
                echo "<script LANGUAGE='JavaScript'>
                window.alert('Succesfully Updated');
                window.location.href='index.php';
                </script>"; 
            }
        } 
    }
?>