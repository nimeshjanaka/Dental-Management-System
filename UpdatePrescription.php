
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
<h2>Update Prescription</h2>
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
           height: 40vh;
           width: 50vh;
           margin-left: 40%;
           gap: 10px;
           
           
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

// Check if the prescription ID is provided in the URL
if (isset($_GET['prescription_id'])) {
    $prescription_id = $_GET['prescription_id'];

    // Attempt to fetch the prescription record based on the provided ID
    $query = "SELECT * FROM Prescription WHERE prescription_id = $prescription_id";
    $result = $conn->query($query);

    // Check if the query was successful
    if (!$result) {
        echo "Error: " . $conn->error;
    } else {
        // Check if a prescription was found with the given ID
        if ($result->num_rows > 0) {
            // Fetch the prescription data
            $prescription = $result->fetch_assoc();
            
            
            echo "<form method='post' action='process_update_prescription.php'>";
            echo "Patient ID: <input type='text' name='patient_id' value='{$prescription['patient_id']}'> <br>";
            echo "Prescription Date: <input type='date' name='prescription_date' value='{$prescription['prescription_date']}'> <br>";
            echo "Medication Name: <input type='text' name='medication_name' value='{$prescription['medication_name']}'> <br>";
            echo "Dosage: <input type='text' name='dosage' value='{$prescription['dosage']}'> <br>";
            echo "Instructions: <textarea name='instructions'>{$prescription['instructions']}</textarea> <br>";

            // Add the prescription_id as a hidden input field
            echo "<input type='hidden' name='prescription_id' value='{$prescription_id}'>";
            
            echo "<input type='submit' name='submit' value='Update Prescription'>";
            echo "</form>";
        } else {
            echo "No prescription found with the given ID.";
        }
    }
} else {
    echo "Prescription ID not provided in the URL.";
}

// Close the connection
$conn->close();

?>
