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

class TreatmentDeleter
{
    private $conn;

    // Constructor to initialize the class with a database connection
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Method to delete a treatment by ID
    public function deleteTreatment($treatmentId)
    {
        // Check if the treatmentId is numeric
        if (is_numeric($treatmentId)) {
            $delete_query = "DELETE FROM Treatment WHERE treatment_id = $treatmentId";

            // Perform the delete operation
            if ($this->conn->query($delete_query) === TRUE) {
                return true; // Deletion successful
            } else {
                return "Error deleting record: " . $this->conn->error;
            }
        } else {
            return "Invalid treatment ID";
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

// Create TreatmentDeleter instance
$treatmentDeleter = new TreatmentDeleter($conn);

// Handle treatment deletion
if (isset($_GET['treatment_id'])) {
    $treatmentId = $_GET['treatment_id'];

    // Perform the delete operation using the TreatmentDeleter class
    $result = $treatmentDeleter->deleteTreatment($treatmentId);

    if ($result === true) {
        // Redirect to Treatment.php after successful deletion
        header("Location: Treatment.php");
        exit();
    } else {
        echo "Error: " . $result;
    }
} else {
    echo "Treatment ID not provided in the URL.";
}

// Close the connection
$database->closeConnection();
?>
