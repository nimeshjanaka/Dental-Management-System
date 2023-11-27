
<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page
    exit();
}
?>
<?php
include 'navbar.php';

$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "dental_management";

// Create connection
$conn = new mysqli($hostname, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the appointment_id is set and a numeric value
if (isset($_GET['appointment_id']) && is_numeric($_GET['appointment_id'])) {
    // Get the appointment_id from the query parameters
    $appointment_id = $_GET['appointment_id'];

    // Perform the delete operation
    $delete_query = "DELETE FROM Appointments WHERE appointment_id = $appointment_id";

    if ($conn->query($delete_query) === TRUE) {
        // Redirect back to the Appointments page after successful deletion
        header("Location: appointment.php");
        exit();
    } else {
        echo "Error deleting appointment: " . $conn->error;
    }
} else {
    // Redirect to the Appointments page if appointment_id is not set or not a numeric value
    header("Location: appointment.php");
    exit();
}

// Close the connection
$conn->close();
?>
