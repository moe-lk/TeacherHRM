<?php


// echo "Calculating Please wait!";
// // function calculation1() {
//     $sqld = "DROP TABLE  #Table2";
//     $stmtd = sqlsrv_query($conn, $sqld);
//     if( $stmtd === false) {
//         var_dump($conn);
//         die( print_r( sqlsrv_errors(), true) );
//     }


//     $sql = "SELECT CenCode, TchSubject1, Medium1, Count(TeachingDetails.NIC) AS AvailableTCH
//     INTO #Table2
//     FROM TeachingDetails 
//     INNER JOIN TeacherMast ON TeachingDetails.NIC = TeacherMast.NIC
//     INNER JOIN StaffServiceHistory ON StaffServiceHistory.ID = TeacherMast.CurServiceRef
//     INNER JOIN CD_CensesNo ON CD_CensesNo.CenCode = StaffServiceHistory.InstCode
//     GROUP BY CenCode, TchSubject1, Medium1";

//     // var_dump($conn);
//     $stmt = sqlsrv_query($conn, $sql);
//     if( $stmt === false) {
//         die( print_r( sqlsrv_errors(), true) );
//     }
//     else{
//         echo "<script LANGUAGE='JavaScript'>window.alert('Succesfully Updated');</script>";
//     }
// }
?>