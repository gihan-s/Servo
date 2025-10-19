<?php
class Database {
    private $conn;

    public function __construct() {
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if ($this->conn->connect_error) {
            die("Database connection failed: " . $this->conn->connect_error);
        }
    }

    public function query($sql) {
        $result = $this->conn->query($sql);
        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }
        return $result;
    }

    public function fetchAll($sql) {
        $result = $this->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function __destruct() {
        $this->conn->close();
    }
}
