<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page
    exit();
}
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
            echo "Connection closed";
        } else {
            echo "Connection not available";
        }
    }
}

class PatientManager
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function insertPatient($first_name, $last_name, $date_of_birth, $contact_number, $email, $address)
    {
        $insert_query = "INSERT INTO Patients (first_name, last_name, date_of_birth, contact_number, email, address) VALUES ('$first_name', '$last_name', '$date_of_birth', '$contact_number', '$email', '$address')";

        if ($this->conn->query($insert_query) === TRUE) {
            header("Location: Patient.php");
            exit();
        } else {
            echo "Error: " . $insert_query . "<br>" . $this->conn->error;
        }
    }

    public function getPatients()
    {
        $query = "SELECT * FROM Patients";
        $result = $this->conn->query($query);

        return $result;
    }

    public function displayPatientsTable()
    {
        $result = $this->getPatients();

        if (!$result) {
            echo "Error: " . $this->conn->error;
        } else {
            echo "<table border='1'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>First Name</th>";
            echo "<th>Last Name</th>";
            echo "<th>Date of Birth</th>";
            echo "<th>Contact Number</th>";
            echo "<th>Email</th>";
            echo "<th>Address</th>";
            echo "<th>Action</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['first_name']}</td>";
                    echo "<td>{$row['last_name']}</td>";
                    echo "<td>{$row['date_of_birth']}</td>";
                    echo "<td>{$row['contact_number']}</td>";
                    echo "<td>{$row['email']}</td>";
                    echo "<td>{$row['address']}</td>";
                    echo "<td>";
                    echo "<a href='UpdatePatient.php?patient_id={$row['patient_id']}'><button type='button' style='background-color: green; color: white;'>Update</button></a>";
                    echo "<a href='DeletePatient.php?patient_id={$row['patient_id']}' onclick=\"return confirm('Are you sure you want to delete this patient?');\"><button type='button' style='background-color: red; color: white;'>Delete</button></a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No patients found</td></tr>";
            }

            echo "</tbody>";
            echo "</table>";
        }
    }
}

$database = new DatabaseConnection("localhost", "root", "", "dental_management");
$conn = $database->getConnection();

$patientManager = new PatientManager($conn);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Management</title>
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

    h2 {
        color: #AD0274;
        font-style: italic;
    }

    form {
        justify-content: center;
        margin-top: 30px;
        border: 2px solid #2FE41D;
        border-radius: 10px;
        color: black;
    }

    table {
        margin: 50px;
        padding: 20px;
        background-color: #5CCDC9;
        border-radius: 10px;
        color: black;
        width: 90%;
        height: 5px;
    }
</style>

<body>
    <?php include 'navbar.php'; ?>

    <h2>Add patient form</h2>

    <form method="post" action="Patient.php">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" required><br>

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" required><br>

        <label for="date_of_birth">Date of Birth:</label>
        <input type="date" name="date_of_birth"><br>

        <label for="contact_number">Contact Number:</label>
        <input type="text" name="contact_number"><br>

        <label for="email">Email:</label>
        <input type="email" name="email"><br>

        <label for="address">Address:</label>
        <textarea name="address"></textarea><br>

        <input type="submit" name="submit" value="Submit">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $first_name = isset($_POST["first_name"]) ? $_POST["first_name"] : "";
        $last_name = isset($_POST["last_name"]) ? $_POST["last_name"] : "";
        $date_of_birth = isset($_POST["date_of_birth"]) ? $_POST["date_of_birth"] : "";
        $contact_number = isset($_POST["contact_number"]) ? $_POST["contact_number"] : "";
        $email = isset($_POST["email"]) ? $_POST["email"] : "";
        $address = isset($_POST["address"]) ? $_POST["address"] : "";

        $patientManager->insertPatient($first_name, $last_name, $date_of_birth, $contact_number, $email, $address);
    }

    $patientManager->displayPatientsTable();
    ?>

</body>

</html>
