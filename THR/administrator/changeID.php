<?php
include ('../db_config/DBManager.php');

include 'changeIDPage.php';

// include ('emisUser.php');
// session_destroy();
// if (isset($_POST["FrmSrch"])) {
//     $NICSrch = $_REQUEST['NICNo'];
// }
// var_dump($NICSrch);
$db = new DBManager();
// session_start();
// $request = $_REQUEST['request'];
var_dump($NICSrch);

$newnic = trim($_POST['newnic']);
$connic = trim($_POST['connic']);
// $oldnic = trim($_POST['oldnic']);
$error_msg = "";

$sqlnic='SELECT NIC FROM TeacherMast';
$server = "DESKTOP-OESJB7N\SQLEXPRESS"; 
$connectionInfo = array("UID" => "sa", "PWD" => "na1234", "Database"=>"MOENational");
$conn= sqlsrv_connect($server, $connectionInfo);

    if($newnic=='' || $connic==''){
        ?>
            <script type="text/javascript">
            alert("Complete all the fileds");
            window.location.href = "index.php";
            </script>
        <?php
        }
        
        elseif($newnic == $connic){
            $nicLength = strlen($newnic);
            if ($nicLength < 10){
            ?>
                <script type="text/javascript">
                alert("Enter NIC of correct length");
                window.location.href = "index.php";
                </script>
            <?php
            }  
            if ($nicLength == 11){
                // $error_msg = "Enter NIC of correct length";
            ?>
                <script type="text/javascript">
                alert("Enter NIC of correct length");
                window.location.href = "index.php";
                </script>
            <?php
            }        
            if ($nicLength > 12){
                // $error_msg = "Enter NIC of correct length";
            ?>
                <script type="text/javascript">
                alert("Enter NIC of correct length");
                window.location.href = "index.php";
                </script>
            <?php
            }
            
            if (strlen($newnic) == 10) {   
                //used algorithm is 11 - (N1*3 + N2*2 + N3*7 + N4*6 + N5*5 + N6*4 + N7*3 + N8*2) % 11
                $result = 11 - ($newnic[0] * 3 + $newnic[1] * 2 + $newnic[2] * 7 + $newnic[3] * 6 + $newnic[4] * 5 + $newnic[5] * 4 + $newnic[6] * 3 + $newnic[7] * 2) % 11;
            
                if ($result == '11') {
                    $result = '0';
                } 
                if ($result == '10') {
                    $result = '0';
                }
                if (($result == $newnic[8]) && (($newnic[9] == 'v') || ($newnic[9] == 'x') || ($newnic[9] == 'V')||($newnic[9] == 'X'))) { // compare with check digit at 9th position and V or X in 10th position
                    // At this point, we have a valid NIC
                    ?>
                    <script>
                    var txt;
                    var r = confirm("This Action will be changed the NIC number in the system.");
                    if(r == true){

                    }else{
                        window.location.href = "index.php";
                    }
                    </script>
                    <?php
                    // if($db->runMsSqlQuery($sql1) && $db->runMsSqlQuery($sql2) && $db->runMsSqlQuery($sql3) && $db->runMsSqlQuery($sql4) && $db->runMsSqlQuery($sql5) && $db->runMsSqlQuery($sql6) && $db->runMsSqlQuery($sql7)){
                    //     echo "Record updated successfully";
                    // }
                    // else {
                    //     die(print_r(sqlsrv_errors(), true));
                    // }
                    if ( sqlsrv_begin_transaction( $conn ) === false ) {
                        die( print_r( sqlsrv_errors(), true ));
                    }
                    $sql1 = "UPDATE TEACHERMAST SET NIC = '$_POST[newnic]' WHERE NIC= '$NICSrch'";
                    $params1 = array($newnic);
                    $stmt1 = sqlsrv_query( $conn, $sql1, $params1 );

                    $sql2 = "UPDATE StaffAddrHistory SET NIC = '$_POST[newnic]' WHERE NIC= '$NICSrch'";
                    $params2 = array($newnic);
                    $stmt2 = sqlsrv_query( $conn, $sql2, $params2 );

                    $sql3 = "UPDATE StaffQualification SET NIC = '$_POST[newnic]' WHERE NIC= '$NICSrch'";
                    $params3 = array($newnic);
                    $stmt3 = sqlsrv_query( $conn, $sql3, $params3 );

                    $sql4 = "UPDATE StaffServiceHistory SET NIC = '$_POST[newnic]' WHERE NIC= '$NICSrch'";
                    $params4 = array($newnic);
                    $stmt4 = sqlsrv_query( $conn, $sql4, $params4 );

                    $sql5 = "UPDATE Passwords SET NICNo = '$_POST[newnic]' WHERE NICNo= '$NICSrch'";
                    $params5 = array($newnic);
                    $stmt5 = sqlsrv_query( $conn, $sql5, $params5 );

                    $sql6 = "UPDATE TeacherMedium SET NIC = '$_POST[newnic]' WHERE NIC= '$NICSrch'";
                    $params6 = array($newnic);
                    $stmt6 = sqlsrv_query( $conn, $sql6, $params6 );

                    $sql7 = "UPDATE TeacherSubject SET NIC = '$_POST[newnic]' WHERE NIC= '$NICSrch'";
                    $params7 = array($newnic);
                    $stmt7 = sqlsrv_query( $conn, $sql7, $params7 );
                    if($stmt1 && $stmt2 && $stmt3 && $stmt4 && $stmt5 && $stmt6 && $stmt7) {
                        sqlsrv_commit( $conn );
                        echo "Transaction committed.<br />";
                   } else {
                        sqlsrv_rollback( $conn );
                        echo "Transaction rolled back.<br />";
                   }
                   

                }
                else {
                ?>
                    <script type="text/javascript">
                    alert("NIC you have entered is not valid.");
                    window.location.href = "index.php";
                    </script>
                <?php  
                }
            }
            elseif (strlen($newnic) == 12) {
                //used algorithm is 11 - (N1*8 + N2*4 + N3*3 + N4*2 + N5*7 + N6*6 + N7*5 + N8*8 + N9*4 + N10*3 + N11*2) % 11
                $result = 11 - ($newnic[0] * 8 + $newnic[1] * 4 + $newnic[2] * 3 + $newnic[3] * 2 + $newnic[4] * 7 + $newnic[5] * 6 + $newnic[6] * 5 + $newnic[7] * 8 + $newnic[8] * 4 + $newnic[9] * 3 + $newnic[10] * 2) % 11;
             
                if ($result == '11') {
                    $result = '0';
                }
             
                if ($result == '10') {
                    $result = '0';
                }
                else {
                ?>
                    <script type="text/javascript">
                    alert("NIC you have entered is not valid.");
                    window.location.href = "index.php";
                    </script>
                <?php 
                }
                
            }
        }


// session_destroy();
?>