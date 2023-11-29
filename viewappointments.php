
<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page
    exit();
}
?>

<?php include 'navbar.php';?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Management - View Appointments</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <h2>View Appointments</h2>
    <div>
    <form method="post" action="viewappointments.php">
        <label for="filter_date">Filter by Date:</label>
        <input type="date" name="filter_date" required>
        <input type="submit" value="Filter">
    </form>

    <!-- Search by Appointment ID Form -->
    <form method="post" action="viewappointments.php">
        <label for="search_appointment_id">Search by Appointment ID:</label>
        <input type="text" name="search_appointment_id" required>
        <input type="submit" value="Search">
    </form>
    </div>
    <style>
     body {
            margin: 0;
            padding: 0;
           
            background: url('https://img.freepik.com/free-vector/dentist-medical-background-with-3d-tooth-design_1017-26095.jpg?size=626&ext=jpg&ga=GA1.1.1880011253.1700265600&semt=ais') center/cover no-repeat fixed;
            background-color: #3498db;
            height: 100vh; 
            
        }

        h2{
            color: #AD0274;
            font-style: italic;
           
        }
        div {
            display: flex;
            
        }
        form {
           
           
           margin-top: 10px;
           background-color: rgba(255, 255, 255, 0.8); 
           border-radius: 10px;
           color: black;
           border: 2px solid #2FE41D;
           height: 40vh;
           width: 100vh;
       }
       table {
           margin: 50px;
           padding: 20px;
           background-color: #5CCDC9;
           border-radius: 10px;
           color: black;
           width: 90%;
           height: 5px;
           text-align: center;
           border: 1px solid black;
       }
</style>
</body>
</html>
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

// Handle date filter
$dateFilter = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filter_date'])) {
    $dateFilter = $_POST['filter_date'];
}

// Handle Appointment ID search
$appointmentIdSearch = '';
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search_appointment_id'])) {
    $appointmentIdSearch = $_POST['search_appointment_id'];
}

// Fetch appointments based on date filter and/or Appointment ID search

$query = "SELECT * FROM Appointments";

// Check if filter conditions are provided
if (!empty($dateFilter) || !empty($appointmentIdSearch)) {
    $query .= " WHERE ";
    
    if (!empty($dateFilter)) {
        $query .= "appointment_date = '$dateFilter' AND ";
    }
    if (!empty($appointmentIdSearch)) {
        $query .= "appointment_id = '$appointmentIdSearch' AND ";
    }

    // Remove the trailing "AND" from the query
    $query = rtrim($query, "AND ");
}

$result = $conn->query($query);


// Display Appointments Table with Update and Delete Buttons
if (!$result) {
    echo "Error: " . $conn->error;
} else {
    echo "<table border='1'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Appointment ID</th>";
    echo "<th>Patient ID</th>";
    echo "<th>Appointment Date</th>";
    echo "<th>Appointment Time</th>";
    echo "</thead>";
    echo "<tbody>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['appointment_id']}</td>";
            echo "<td>{$row['patient_id']}</td>";
            echo "<td>{$row['appointment_date']}</td>";
            echo "<td>{$row['appointment_time']}</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No appointments found</td></tr>";
    }

    echo "</tbody>";
    echo "</table>";
}

// Close the connection
$conn->close();
?>


