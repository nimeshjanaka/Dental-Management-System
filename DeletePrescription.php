<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page
    exit();
}

class DatabaseConnection
{
    private $conn;

    // Constructor to establish the database connection
    public function __construct($hostname, $username, $password, $dbname)
    {
        $this->conn = new mysqli($hostname, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // Getter method to obtain the database connection
    public function getConnection()
    {
        return $this->conn;
    }

    // Method to close the database connection
    public function closeConnection()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}

class PrescriptionDeleter
{
    private $conn;

    // Constructor to initialize the class with a database connection
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Method to delete a prescription by ID
    public function deletePrescription($prescriptionId)
    {
        // Check if the prescriptionId is numeric
        if (is_numeric($prescriptionId)) {
            $delete_query = "DELETE FROM Prescription WHERE prescription_id = $prescriptionId";

            // Perform the delete operation
            if ($this->conn->query($delete_query) === TRUE) {
                return true; // Deletion successful
            } else {
                return "Error deleting record: " . $this->conn->error;
            }
        } else {
            return "Invalid prescription ID";
        }
    }
}

$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "dental_management";

// Create connection
$database = new DatabaseConnection($hostname, $username, $password, $dbname);
$conn = $database->getConnection();

// Create PrescriptionDeleter instance
$prescriptionDeleter = new PrescriptionDeleter($conn);

// Handle prescription deletion
if (isset($_GET['prescription_id'])) {
    $prescriptionId = $_GET['prescription_id'];

    // Perform the delete operation using the PrescriptionDeleter class
    $result = $prescriptionDeleter->deletePrescription($prescriptionId);

    if ($result === true) {
        // Redirect to Prescription.php after successful deletion
        header("Location: Prescription.php");
        exit();
    } else {
        echo "Error: " . $result;
    }
} else {
    echo "Prescription ID not provided in the URL.";
}

// Close the connection
$database->closeConnection();
?>
