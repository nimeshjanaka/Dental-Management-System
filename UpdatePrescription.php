
<?php
session_start();


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
           
           margin-top: 40px;
           background-color: rgba(255, 255, 255, 0.8); 
           border-radius: 10px;
           color: black;
           border: 2px solid #2FE41D;
           height: auto;
           width: 50vh;
           padding: 20px;
           box-sizing: border-box;
           margin-left: 40%;
           font-size: 20px;
          }
   
          label {
           display: block;
           margin-top: 10px;
           margin-left: 10px;
       }
   
       input,
       textarea {
           width: calc(100% - 20px); 
           padding: 8px;
           box-sizing: border-box;
           margin-top: 5px;
           margin-left: 10px;
       }
   
       input[type="date"] {
           width: calc(100% - 20px); 
           
       }
   
       input[type="submit"] {
           input[type="submit"] {
           background-color: #4CAF50;
           color: white;
           padding: 10px 15px;
           border: none;
           border-radius: 5px;
           cursor: pointer;
           margin-top: 10px; 
           margin-left: 10px;
       }
   
       input[type="submit"]:hover {
           background-color: #45a049;
       }
</style>

<?php

$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "dental_management";

// Create connection
$conn = new mysqli($hostname, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_GET['prescription_id'])) {
    $prescription_id = $_GET['prescription_id'];

    // Attempt to fetch the prescription record based on the provided ID
    $query = "SELECT * FROM Prescription WHERE prescription_id = $prescription_id";
    $result = $conn->query($query);

    
    if (!$result) {
        echo "Error: " . $conn->error;
    } else {
        
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
