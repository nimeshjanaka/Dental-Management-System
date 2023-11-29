<?php
session_start();

// DatabaseConnection class encapsulates database-related functionality
class DatabaseConnection
{
    private $conn;

    // Constructor establishes the database connection
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

// AppointmentDeleter class encapsulates appointment deletion logic
class AppointmentDeleter
{
    private $conn;

    // Constructor initializes the class with a database connection
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    // Method to delete an appointment by ID
    public function deleteAppointment($appointmentId)
    {
        
        if (is_numeric($appointmentId)) {
            $delete_query = "DELETE FROM Appointments WHERE appointment_id = $appointmentId";

            // Perform the delete operation
            if ($this->conn->query($delete_query) === TRUE) {
                return true; // Deletion successful
            } else {
                return "Error deleting appointment: " . $this->conn->error;
            }
        } else {
            return "Invalid appointment ID";
        }
    }
}


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page
    exit();
}

include 'navbar.php';

$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "dental_management";

// Create connection
$database = new DatabaseConnection($hostname, $username, $password, $dbname);
$conn = $database->getConnection();

// Create AppointmentDeleter instance
$appointmentDeleter = new AppointmentDeleter($conn);

// Check if the appointment_id is set
if (isset($_GET['appointment_id'])) {
    $appointmentId = $_GET['appointment_id'];

    // Perform the delete operation using the AppointmentDeleter class
    $result = $appointmentDeleter->deleteAppointment($appointmentId);

    if ($result === true) {
        // Redirect back to the Appointments page after successful deletion
        header("Location: appointment.php");
        exit();
    } else {
        echo "Error: " . $result;
    }
} else {
    // Redirect to the Appointments page if appointment_id is not set
    header("Location: appointment.php");
    exit();
}

// Close the connection
$database->closeConnection();
?>

