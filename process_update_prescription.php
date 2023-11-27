
<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page
    exit();
}
?>
<header>
    <h1>Dental Management System</h1>
</header>
<h2>Update Patient</h2>
<style>
         body {
            margin: 0;
            padding: 0;
           
            background: url('https://img.freepik.com/free-vector/dentist-medical-background-with-3d-tooth-design_1017-26095.jpg?size=626&ext=jpg&ga=GA1.1.1880011253.1700265600&semt=ais') center/cover no-repeat fixed;
            background-color: #3498db;
            height: 100vh; 
            
        }
        header {
            background-color: #333;
            padding: 10px;
            text-align: center;
        }
        h2{
            color: #AD0274;
            font-style: italic;
            margin-left: 10%;
           
        }

       
        form {
           
           justify-content: center;
           margin-top: 40px;
           background-color: rgba(255, 255, 255, 0.8); 
           border-radius: 10px;
           color: black;
           border: 2px solid #2FE41D;
           height: 45vh;
           width: 40vh;
           margin-left: 40%;
           
           
       }
</style>


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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the updated prescription data from the form
    $prescription_id = isset($_POST['prescription_id']) ? $_POST['prescription_id'] : '';

    // Validate and sanitize user input to prevent SQL injection
    $patient_id = isset($_POST['patient_id']) ? $conn->real_escape_string($_POST['patient_id']) : '';
    $prescription_date = isset($_POST['prescription_date']) ? $_POST['prescription_date'] : '';
    $medication_name = isset($_POST['medication_name']) ? $_POST['medication_name'] : '';
    $dosage = isset($_POST['dosage']) ? $_POST['dosage'] : '';
    $instructions = isset($_POST['instructions']) ? $_POST['instructions'] : '';

    // Check if prescription_id is not empty
    if (!empty($prescription_id)) {
        // Update the prescription record in the database
        $update_query = "UPDATE Prescription SET patient_id='$patient_id', prescription_date='$prescription_date', medication_name='$medication_name', dosage='$dosage', instructions='$instructions' WHERE prescription_id=$prescription_id";

        if ($conn->query($update_query) === TRUE) {
            header("Location: Prescription.php");
            exit();
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Invalid prescription ID";
    }
} else {
    echo "Form not submitted.";
}

// Close the connection
$conn->close();

?>
