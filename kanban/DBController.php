<?php
class DBController {
    private $host = "localhost";
    private $user = "fit2101_project";
    private $password = "fit2101";
    private $database = "fit2101_draganddrop";
    private $conn;

    function __construct() {
        $this->conn = $this->connectDB();
    }    

    function connectDB() {
        $conn = mysqli_connect($this->host, $this->user, $this->password, $this->database);

        // Check connection
        if ($conn === false) {
            die("ERROR: Could not connect. " . mysqli_connect_error());
        }

        return $conn;
    }

    function runBaseQuery($query) {
        $result = $this->conn->query($query);    
        $resultset = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $resultset[] = $row;
            }
        }

        return $resultset;
    }

    function runQuery($query, $param_type, $param_value_array) {
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            die("ERROR: Could not prepare statement. " . $this->conn->error);
        }

        $this->bindQueryParams($stmt, $param_type, $param_value_array);
        $stmt->execute();
        $result = $stmt->get_result();
        $resultset = [];

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $resultset[] = $row;
            }
        }

        return $resultset;
    }

    function bindQueryParams($stmt, $param_type, $param_value_array) {
        $param_value_reference = [$param_type];
        foreach ($param_value_array as $value) {
            $param_value_reference[] = $value;
        }
        call_user_func_array([$stmt, 'bind_param'], $this->refValues($param_value_reference));
    }

    function insert($query, $param_type, $param_value_array) {
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            die("ERROR: Could not prepare statement. " . $this->conn->error);
        }

        $this->bindQueryParams($stmt, $param_type, $param_value_array);
        $stmt->execute();
    }

    function update($query, $param_type, $param_value_array) {
        $stmt = $this->conn->prepare($query);
        if ($stmt === false) {
            die("ERROR: Could not prepare statement. " . $this->conn->error);
        }

        $this->bindQueryParams($stmt, $param_type, $param_value_array);
        $stmt->execute();
    }

    private function refValues($arr) {
        $refs = [];
        foreach ($arr as $key => $value) {
            $refs[$key] = &$arr[$key];
        }
        return $refs;
    }
}
?>
