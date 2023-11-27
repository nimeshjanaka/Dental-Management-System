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
            echo "";
        } else {
            echo "Connection not available";
        }
    }
}

class Prescriptions
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

    public function insertPrescription($patient_id, $prescription_date, $medication_name, $dosage, $instructions)
    {
        $insert_query = "INSERT INTO Prescription (patient_id, prescription_date, medication_name, dosage, instructions) VALUES ('$patient_id', '$prescription_date', '$medication_name', '$dosage', '$instructions')";

        if ($this->conn->query($insert_query) === TRUE) {
            // Use header to redirect after successful submission
            header("Location: {$_SERVER['REQUEST_URI']}");
            exit();
        } else {
            echo "Error: " . $insert_query . "<br>" . $this->conn->error;
        }
    }

    public function getPrescriptions()
    {
        $query = "SELECT * FROM Prescription";
        $result = $this->conn->query($query);

        return $result;
    }

    public function displayPrescriptionsTable()
    {
        $result = $this->getPrescriptions();

        if (!$result) {
            echo "Error: " . $this->conn->error;
        } else {
            echo "<table border='1'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Prescription ID</th>";
            echo "<th>Patient ID</th>";
            echo "<th>Prescription Date</th>";
            echo "<th>Medication Name</th>";
            echo "<th>Dosage</th>";
            echo "<th>Instructions</th>";
            echo "<th>Action</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>{$row['prescription_id']}</td>";
                    echo "<td>{$row['patient_id']}</td>";
                    echo "<td>{$row['prescription_date']}</td>";
                    echo "<td>{$row['medication_name']}</td>";
                    echo "<td>{$row['dosage']}</td>";
                    echo "<td>{$row['instructions']}</td>";
                    echo "<td>";
                    echo "<a href='UpdatePrescription.php?prescription_id={$row['prescription_id']}'><button type='button' style='background-color: green; color: white;'>Update</button></a>";
                    echo "<a href='DeletePrescription.php?prescription_id={$row['prescription_id']}' onclick=\"return confirm('Are you sure you want to delete this prescription?');\"><button type='button' style='background-color: red; gradient-color: red; color: white;'>Delete</button></a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No prescriptions found</td></tr>";
            }

            echo "</tbody>";
            echo "</table>";
        }
    }
}

$database = new DatabaseConnection("localhost", "root", "", "dental_management");
$conn = $database->getConnection();

$prescriptionsManager = new Prescriptions($conn);
$patients_result = $prescriptionsManager->getPatients();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Management - Prescriptions</title>
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
        margin-top: 10px;
        background-color: rgba(255, 255, 255, 0.8);
        border: 2px solid #2FE41D;
        border-radius: 10px;
        color: black;
        height: 65vh;
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
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h2>Prescription Form</h2>

    <form method="post" action="Prescription.php">
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
        </select><br>

        <label for="prescription_date">Prescription Date:</label>
        <input type="date" name="prescription_date" required><br>

        <label for="medication_name">Medication Name:</label>
        <input type="text" name="medication_name" required><br>

        <label for="dosage">Dosage:</label>
        <input type="text" name="dosage" required><br>

        <label for="instructions">Instructions:</label>
        <textarea name="instructions"></textarea><br>

        <input type="submit" name="submit" value="Add Prescription">
    </form>

    <?php
    $prescriptionsManager->displayPrescriptionsTable();
    ?>

    <?php
    $database->closeConnection();
    ?>
</body>
</html>
