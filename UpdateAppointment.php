<?php
session_start();
// DatabaseConnection class for encapsulating database connection
class DatabaseConnection
{
    private $conn;
// Constructor to establish the database connection
    public function __construct($hostname, $username, $password, $dbname)
    {
        // OOP Concept: Encapsulation - Private property to encapsulate database connection
        $this->conn = new mysqli($hostname, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
// OOP Concept: Encapsulation - Getter method to obtain the database connection
    public function getConnection()
    {
        return $this->conn;
    }
// OOP Concept: Encapsulation - Method to close the database connection
    public function closeConnection()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
// Appointments class for managing appointment-related operations
class Appointments
{
    private $conn;
// Constructor to initialize the class with a database connection
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
// Usage of DatabaseConnection to establish a connection
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
