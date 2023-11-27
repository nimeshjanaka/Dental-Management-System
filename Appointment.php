<?php
session_start();

class DatabaseConnection
{
    private $conn;

    public function __construct($hostname, $username, $password, $dbname)
    {
        $this->conn = new mysqli($hostname, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        echo "";
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function closeConnection()
    {
        if ($this->conn) {
            $this->conn->close();
            echo "";
        } else {
            echo "Connection not available";
        }
    }
}

class Appointments
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getPatients()
    {
        $patients_query = "SELECT patient_id, CONCAT(first_name, ' ', last_name) AS patient_name FROM Patients";
        $patients_result = $this->conn->query($patients_query);

        return $patients_result;
    }

    public function insertAppointment($patient_id, $appointment_date, $appointment_time)
    {
        $insert_query = "INSERT INTO Appointments (patient_id, appointment_date, appointment_time) VALUES ('$patient_id', '$appointment_date', '$appointment_time')";

        if ($this->conn->query($insert_query) === TRUE) {
            // Redirect to the same page to avoid form resubmission
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        } else {
            echo "Error: " . $insert_query . "<br>" . $this->conn->error;
        }
    }

    public function getAppointments()
    {
        $query = "SELECT * FROM Appointments";
        $result = $this->conn->query($query);

        return $result;
    }

    public function displayAppointmentsTable()
    {
        $result = $this->getAppointments();

        if (!$result) {
            echo "Error: " . $this->conn->error;
        } else {
            echo "<table border='1'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Appointment ID</th>";
            echo "<th>Patient ID</th>";
            echo "<th>Appointment Date</th>";
            echo "<th>Appointment Time</th>";
            echo "<th>Action</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['appointment_id']}</td>";
                    echo "<td>{$row['patient_id']}</td>";
                    echo "<td>{$row['appointment_date']}</td>";
                    echo "<td>{$row['appointment_time']}</td>";
                    echo "<td>";
                    echo "<a href='UpdateAppointment.php?appointment_id={$row['appointment_id']}'><button type='button' style='background-color: green; color: white;'>Update</button></a>";
                    echo "<a href='DeleteAppointment.php?appointment_id={$row['appointment_id']}' onclick=\"return confirm('Are you sure you want to delete this appointment?');\"><button type='button' style='background-color: red; gradient-color: red; color: white;'>Delete</button></a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No appointments found</td></tr>";
            }

            echo "</tbody>";
            echo "</table>";
        }
    }
}

$database = new DatabaseConnection("localhost", "root", "", "dental_management");
$conn = $database->getConnection();

$appointmentsManager = new Appointments($conn);
$patients_result = $appointmentsManager->getPatients();

// Define available channelling dates and times
$channelling_slots = [
    'Monday' => ['06.00 pm - 09.00 pm'],
    'Wednesday' => ['06.00 pm - 09.00 pm'],
    'Saturday' => ['03.00 pm - 10.00 pm'],
    'Sunday' => ['03.00 pm - 10.00 pm']
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Management - Appointments</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
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
        form {
           
           justify-content: center;
           margin-top: 10px;
           background-color: rgba(255, 255, 255, 0.8); 
           border-radius: 10px;
           color: black;
           border: 2px solid #2FE41D;
           height: 40vh;
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
<body>
    <?php include 'navbar.php'; ?>
    <h2>Appointments</h2>

    <form method="post" action="Appointment.php">
        <label for="patient_id">Select Patient:</label>
        <select name="patient_id" required>
            <?php
            // Populate dropdown list with patient IDs and names
            if ($patients_result->num_rows > 0) {
                while ($patient = $patients_result->fetch_assoc()) {
                    echo "<option value='{$patient['patient_id']}'>{$patient['patient_name']}</option>";
                }
            } else {
                echo "<option value='' disabled>No patients found</option>";
            }
            ?>
        </select>
        <br>

        <label for="appointment_date">Appointment Date:</label>
        <input type="date" name="appointment_date" required>
        <br>

        <label for="appointment_time">Appointment Time:</label>
        <select name="appointment_time" required>
            <?php
            // Dropdown list with available channelling slots
            foreach ($channelling_slots as $day => $times) {
                foreach ($times as $time) {
                    echo "<option value='{$day} - {$time}'>{$day} - {$time}</option>";
                }
            }
            ?>
        </select>
        <br>
        <br>

        <input type="submit" name="submit" value="Make Appointment">
    </form>

   

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $patient_id = isset($_POST["patient_id"]) ? $_POST["patient_id"] : "";
        $appointment_date = isset($_POST["appointment_date"]) ? $_POST["appointment_date"] : "";
        $appointment_time = isset($_POST["appointment_time"]) ? $_POST["appointment_time"] : "";

        $appointmentsManager->insertAppointment($patient_id, $appointment_date, $appointment_time);
    }

    $appointmentsManager->displayAppointmentsTable();

   
    $database->closeConnection();
    ?>
     <a href="viewappointments.php"><button type="button" style="height: 40px; background-color: #5CCDC9; justify-content: center;">View Appointments</button></a>
</body>
</html>
