<?php

class DatabaseConnection
{
    private $hostname;
    private $username;
    private $password;
    private $dbname;
    private $conn;

    public function __construct($hostname, $username, $password, $dbname)
    {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        $this->dbname = $dbname;

        $this->connect();
    }

    private function connect()
    {
        $this->conn = new mysqli($this->hostname, $this->username, $this->password, $this->dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        echo "Connected successfully";
    }

    public function close()
    {
        if ($this->conn) {
            $this->conn->close();
            echo "Connection closed";
        }
    }

    
}

$database = new DatabaseConnection("localhost", "root", "", "dental_management");


// Close the connection (optional, as PHP will automatically close it when the script ends)
$database->close();

?>
