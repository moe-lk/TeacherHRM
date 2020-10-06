<?php

/* Author: Thushara Ruwan Perera
 * Create date: 02-01-2014
 */

class DBManager {

    private $server = "DESKTOP-L8I7S0M";
    private $connectionInfo = array("UID" => "wamplogin", "PWD" => "sa1234", "Database"=>"MOENational");
   // private $server = 'DUMINDA-PC\SQLEXPRESS';
   // private $connectionInfo = array("UID" => "sa", "PWD" => "duminda", "DATABASE" => "MOENational");
    private $conn = null;

    function connector() {
        $this->conn = sqlsrv_connect($this->server, $this->connectionInfo);
        if ($this->conn) {
            // echo "Connection established.<br />";
        } else {
            echo "Connection could not be established.<br />";
            die(print_r(sqlsrv_errors(), true));
        }
    }

    function runMsSqlQuery($sql, $param = array()) {
        $this->connector();
        
        $result = sqlsrv_query($this->conn, $sql, $param);


       
        return $result;
    }
function runMsSqlQueryInsert($sql,$param = array()) {
        $this->connector();
        
        $result = sqlsrv_query($this->conn, $sql,$param);


       
        return $result;
    }
	
	
	/* Author: Duminda Wijewantha
 * Create date: 2014/June/09
 */
	function rowAvailable($sql) {
        $this->connector();
        
        $result = sqlsrv_query($this->conn, $sql);
		$rows = sqlsrv_has_rows( $result );

       
        return $rows;
    }
	function rowCount($sql) {
        $this->connector();
        $params = array();
		$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
        $result = sqlsrv_query($this->conn, $sql,$params,$options);
		$rows = sqlsrv_num_rows( $result );

       
        return $rows;
    }

}

?>
