
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
<h2>Update Treatement</h2>
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

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_GET['treatment_id'])) {
    $treatment_id = $_GET['treatment_id'];

    // Attempt to fetch the treatment record based on the provided ID
    $query = "SELECT * FROM Treatment WHERE treatment_id = $treatment_id";
    $result = $conn->query($query);

    
    if (!$result) {
        echo "Error: " . $conn->error;
    } else {
        // Check if a treatment was found with the given ID
        if ($result->num_rows > 0) {
            // Fetch the treatment data
            $treatment = $result->fetch_assoc();
            
            
            echo "<form method='post' action='process_update_treatment.php'>";
            echo "Patient ID: <input type='text' name='patient_id' value='{$treatment['patient_id']}' style='margin-top: 10px;' > <br>";
            echo "Treatment Date: <input type='date' name='treatment_date' value='{$treatment['treatment_date']}> <br>"; 
            echo "Procedure Name: <input type='text' name='procedure_name' value='{$treatment['procedure_name']}' readonly > <br>";
            echo "Cost: <input type='text' name='cost' value='{$treatment['cost']}' readonly > <br>";
            echo "Notes: <textarea name='notes'>{$treatment['notes']}</textarea > <br>";
            
            echo "<input type='hidden' name='treatment_id' value='{$treatment_id}' >";
            echo "<input type='submit' name='submit' value='Update' >";
            echo "</form>";
        } else {
            echo "No treatment found with the given ID.";
        }
    }
} else {
    echo "Treatment ID not provided in the URL.";
}

// Close the connection
$conn->close();

?>
