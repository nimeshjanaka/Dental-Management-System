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
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function closeConnection()
    {
        if ($this->conn) {
            $this->conn->close();
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

    public function getAppointmentById($appointmentId)
    {
        $query = "SELECT * FROM Appointments WHERE appointment_id = $appointmentId";
        $result = $this->conn->query($query);

        if (!$result) {
            echo "Error: " . $this->conn->error;
        } else {
            return $result->fetch_assoc();
        }
    }
}

$database = new DatabaseConnection("localhost", "root", "", "dental_management");
$conn = $database->getConnection();

$appointmentsManager = new Appointments($conn);

?>

<header>
    <h1>Dental Management System</h1>
</header>
<h2>Update Appointments</h2>

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

    h2 {
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
// Check if the appointment ID is provided in the URL
if (isset($_GET['appointment_id'])) {
    $appointment_id = $_GET['appointment_id'];

    // Attempt to fetch the appointment record based on the provided ID
    $appointment = $appointmentsManager->getAppointmentById($appointment_id);

    if ($appointment) {
        echo "<form method='post' action='process_update_appointment.php'>";
        echo "Patient ID: <input type='text' name='patient_id' value='{$appointment['patient_id']}'> <br>";
        echo "Appointment Date: <input type='date' name='appointment_date' value='{$appointment['appointment_date']}'> <br>";

        echo "<input type='hidden' name='appointment_id' value='{$appointment_id}'>";
        echo "<input type='submit' name='submit' value='Update'>";
        echo "</form>";
    } else {
        echo "No appointment found with the given ID.";
    }
} else {
    echo "Appointment ID not provided in the URL.";
}

// Close the connection
$database->closeConnection();

?>
