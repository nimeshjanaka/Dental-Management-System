
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
           background-color: rgba(255, 255, 210, 025); 
           border-radius: 10px;
           color: black;
           border: 2px solid #2FE41D;
           height: 70vh;
           width: 75vh;
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

// Check if the patient ID is provided in the URL
if (isset($_GET['patient_id'])) {
    $patient_id = $_GET['patient_id'];

    // Attempt to fetch the patient record based on the provided ID
    $query = "SELECT * FROM Patients WHERE patient_id = $patient_id";
    $result = $conn->query($query);

    // Check if the query was successful
    if (!$result) {
        echo "Error: " . $conn->error;
    } else {
        // Check if a patient was found with the given ID
        if ($result->num_rows > 0) {
            // Fetch the patient data
            $patient = $result->fetch_assoc();

            // Your HTML form for updating patient data goes here
            echo "<html lang='en'>";
            echo "<head>";
            echo "<meta charset='UTF-8'>";
            echo "<meta name='viewport' content='width=device-width, initial-scale=1.0'>";
            echo "<title>Update Patient</title>";
            echo "<link rel='stylesheet' type='text/css' href='styles.css'>";
            echo "</head>";
            echo "<body>";
            echo "<form method='post' action='process_update.php'>";
            echo "<input type='hidden' name='patient_id' value='{$patient['patient_id']}'>";
            echo "First Name: <input type='text' name='first_name' value='{$patient['first_name']}' required> <br>";
            echo "Last Name: <input type='text' name='last_name' value='{$patient['last_name']}' required> <br>";
            echo "Date of Birth: <input type='date' name='date_of_birth' value='{$patient['date_of_birth']}'> <br>";
            echo "Contact Number: <input type='text' name='contact_number' value='{$patient['contact_number']}'> <br>";
            echo "Email: <input type='email' name='email' value='{$patient['email']}'> <br>";
            echo "Address: <textarea name='address'>{$patient['address']}</textarea> <br>";
            // Add the rest of the form fields for other patient attributes

            echo "<input type='submit' name='submit' value='Update'>";
            echo "</form>";
            echo "</body>";
            echo "</html>";
        } else {
            echo "No patient found with the given ID.";
        }
    }
} else {
    echo "Patient ID not provided in the URL.";
}

// Close the connection
$conn->close();

?>
