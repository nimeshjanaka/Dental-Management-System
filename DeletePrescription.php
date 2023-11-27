
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

// Check if the prescription ID is provided in the URL
if (isset($_GET['prescription_id'])) {
    $prescription_id = $_GET['prescription_id'];

    // Attempt to delete the prescription record based on the provided ID
    $delete_query = "DELETE FROM Prescription WHERE prescription_id = $prescription_id";
    if ($conn->query($delete_query) === TRUE) {
        header("Location: Prescription.php");
        exit();
    } else {
        echo "Error deleting record: " . $conn->error;
    }
} else {
    echo "Prescription ID not provided in the URL.";
}

// Close the connection
$conn->close();

?>
