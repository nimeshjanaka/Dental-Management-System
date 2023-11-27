
<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page
    exit();
}
?>
<?php
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

// Handle patient deletion
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];

    $delete_query = "DELETE FROM Patients WHERE patient_id = $patient_id";

    if ($conn->query($delete_query) === TRUE) {
        // Redirect to patients.php after successful deletion
        header("Location: Patient.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Close the connection
$conn->close();
?>
