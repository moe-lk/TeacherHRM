 <?php
// phpinfo();
/* Author: Thushara Ruwan Perera
 * Create date: 02-01-2014
 */

class DBManager
{

    // private $server = "WIN-7AS41IMPNCQ\SRVEMISDB";
    //private $connectionInfo = array("UID" => "sa", "PWD" => "testnemis@UOM", "Database"=>"MOENational");

    // private $server = 'DUMINDA-PC\SQLEXPRESS';
    // private $connectionInfo = array("UID" => "sa", "PWD" => "duminda", "DATABASE" => "MOENational");


    //private $server = "10.8.103.183";
    // private $server = "WIN-1S629BHQ65T\SQLEXPRESS";

    // private $connectionInfo = array("UID" => "sa", "PWD" => "nemis9DB", "Database"=>"MOENational");

    //private $server = "221.100.10.1"; // added by me
    //private $connectionInfo = array("UID" => "sa", "PWD" => "nEmIs@*123", "Database"=>"MOENational"); //added by me
    //800093325v
    private $server = "DESKTOP-7CGB28J"; 
    private $connectionInfo = array("UID" => "sa", "PWD" => "sa1234", "Database"=>"MOENational");
    private $conn = null;


    function __construct()
    {
        $this->conn = sqlsrv_connect($this->server, $this->connectionInfo);
        if ($this->conn) {
            //echo "Connection established.<br />";
        } else {
            echo "Connection could not be established XX.<br />";
            die(print_r(sqlsrv_errors(), true));
        }
    }

    function runMsSqlQuery($sql, $param = array())
    {
        //   $this->connector();

        $result = sqlsrv_query($this->conn, $sql, $param);


        return $result;
    }

    function runMsSqlQueryIDOD($sql, $param = array())
    {
        //   $this->connector();

        $result = sqlsrv_query($this->conn, $sql, $param);

        $sql1 = "SET IDENTITY_INSERT UP_TeacherMast ON";
        $param1 = array();
        sqlsrv_query($this->conn, $sql1, $param1);

        return $result;
    }

    function runMsSqlQueryInsert($sql, $param = array())
    {
        //  $this->connector();

        $result = sqlsrv_query($this->conn, $sql, $param);

        //$sql = "SELECT @@IDENTITY AS 'id'";
        $sql = "SELECT SCOPE_IDENTITY() AS 'latestID'";
        $res = self::runMsSqlQuery($sql);
        $rowMax = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC);
        $newid = $rowMax['latestID'];
        return $newid;
    }

    /* Author: Duminda Wijewantha
     * Create date: 2014/June/09
     */

    function rowAvailable($sql)
    {
        // $this->connector();

        $result = sqlsrv_query($this->conn, $sql);
        $rows = sqlsrv_has_rows($result);


        return $rows;
    }

    function rowCount($sql)
    {
        //$this->connector();
        $params = array();
        $options = array("Scrollable" => SQLSRV_CURSOR_KEYSET);
        $result = sqlsrv_query($this->conn, $sql, $params, $options);
        $rows = sqlsrv_num_rows($result);


        return $rows;
    }

    // get result set and result set rowcount 
    function runMsSqlQueryForSP($sql1, $param = array())
    {
        $queryResult = self::runMsSqlQuery($sql1, $param);


        $sqlCount = "SELECT @@Rowcount AS 'rowCount'";

        $res = self::runMsSqlQuery($sqlCount);
        $rowC = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC);
        $rowCount = $rowC['rowCount'];
        return array('result' => $queryResult, 'count' => $rowCount);
    }
}
function redirect($url)
{
    header("Location: $url");
?>
    <script type="text/javascript">
        window.location = "<?php echo $url; ?>"
    </script>
<?php
    exit();
}

?>