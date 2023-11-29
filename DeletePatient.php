
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

class PatientDeleter
{
    private $conn;

    // Constructor to initialize the class with a database connection
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Method to delete a patient by ID
    public function deletePatient($patientId)
    {
        // Check if the patientId is numeric
        if (is_numeric($patientId)) {
            $delete_query = "DELETE FROM Patients WHERE patient_id = $patientId";

            // Perform the delete operation
            if ($this->conn->query($delete_query) === TRUE) {
                return true; // Deletion successful
            } else {
                return "Error deleting record: " . $this->conn->error;
            }
        } else {
            return "Invalid patient ID";
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

// Create PatientDeleter instance
$patientDeleter = new PatientDeleter($conn);

// Handle patient deletion
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['patient_id'])) {
    $patientId = $_GET['patient_id'];

    // Perform the delete operation using the PatientDeleter class
    $result = $patientDeleter->deletePatient($patientId);

    if ($result === true) {
        // Redirect to Patient.php after successful deletion
        header("Location: Patient.php");
        exit();
    } else {
        echo "Error: " . $result;
    }
}

// Close the connection
$database->closeConnection();
?>
