
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
    // Retrieve the updated patient data from the form
    $patient_id = isset($_POST['patient_id']) ? $_POST['patient_id'] : '';

    // Validate and sanitize user input to prevent SQL injection
    $first_name = isset($_POST['first_name']) ? $conn->real_escape_string($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? $conn->real_escape_string($_POST['last_name']) : '';
    $date_of_birth = isset($_POST['date_of_birth']) ? $_POST['date_of_birth'] : '';
    $contact_number = isset($_POST['contact_number']) ? $_POST['contact_number'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $address = isset($_POST['address']) ? $_POST['address'] : '';

    // Check if patient_id is not empty
    if (!empty($patient_id)) {
        // Update the patient record in the database
        $update_query = "UPDATE Patients SET first_name='$first_name', last_name='$last_name', date_of_birth='$date_of_birth', contact_number='$contact_number', email='$email', address='$address' WHERE patient_id=$patient_id";

        if ($conn->query($update_query) === TRUE) {
            // Redirect back to Patient.php and refresh the page
            echo "<script>
                    alert('Record updated successfully');
                    window.location.href = 'Patient.php';
                 </script>";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "Invalid patient ID";
    }
} else {
    echo "Form not submitted.";
}

// Close the connection
$conn->close();

?>
