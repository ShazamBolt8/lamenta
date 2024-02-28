<?php
class DatabaseConnection
{
    private $server_name = "localhost";
    private $server_username = "root";
    private $server_password = "";
    private $database_name = "lamenta";

    private $conn;
    public function __construct()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $this->conn = new mysqli(
            $this->server_name,
            $this->server_username,
            $this->server_password,
            $this->database_name
        );
        if ($this->conn->connect_error) {
            die("Connection failed: {$this->conn->connect_error}");
        }
    }
    public function getConnection()
    {
        return $this->conn;
    }
    public function closeConnection()
    {
        $this->conn->close();
    }
}