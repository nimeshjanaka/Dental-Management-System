<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page
    exit();
}

// Include navbar
include 'navbar.php';


class DatabaseConnection
{
    private $conn;

    public function __construct($hostname, $username, $password, $dbname)
    {
        $this->conn = new mysqli($hostname, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }

        echo "Connected successfully";
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

// Database connection parameters
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "dental_management";

// Creating a new instance of the DatabaseConnection class
$db = new DatabaseConnection($hostname, $username, $password, $dbname);
$conn = $db->getConnection();

// Fetch patient IDs and names from the Patients table
$patients_query = "SELECT patient_id, CONCAT(first_name, ' ', last_name) AS patient_name FROM Patients";
$patients_result = $conn->query($patients_query);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = isset($_POST["patient_id"]) ? $_POST["patient_id"] : "";
    $treatment_date = isset($_POST["treatment_date"]) ? $_POST["treatment_date"] : "";
    $procedure_name = isset($_POST["procedure_name"]) ? $_POST["procedure_name"] : "";
    $cost = getCost($procedure_name);
    $notes = isset($_POST["notes"]) ? $_POST["notes"] : "";

    // Use prepared statements to prevent SQL injection
    $insert_query = $conn->prepare("INSERT INTO treatment (patient_id, treatment_date, procedure_name, cost, notes) VALUES (?, ?, ?, ?, ?)");
    $insert_query->bind_param("isiss", $patient_id, $treatment_date, $procedure_name, $cost, $notes);

    if ($insert_query->execute()) {
        // Redirect to prevent form resubmission
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    } else {
        echo "Error: " . $insert_query->error;
    }
}


function getCost($procedure_name)
{
    switch ($procedure_name) {
        case "Cleanings":
            return 2500;
        case "Whitening":
            return 2500;
        case "Filling":
            return 5500;
        case "Nerve Filling":
            return 1500;
        case "Root Canal Therapy":
            return 7500;
        default:
            return 0;
    }
}

// Display Treatments Table with Update and Delete Buttons
$query = "SELECT * FROM treatment";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dental Management - Treatments</title>
    <link rel="stylesheet" type="text/css" href="styles.css">

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
            border-radius: 10px;
            color: black;
            border: 2px solid #2FE41D;
            height: 65vh;
            padding: 20px;
            box-sizing: border-box;
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
    <h2>Treatment</h2>
    <form method="post" action="Treatment.php">
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

        <label for="treatment_date">Treatment Date:</label>
        <input type="date" name="treatment_date" required><br>

        <label for="procedure_name">Procedure Name:</label>
        <select name="procedure_name" id="procedure_name" onchange="updateCost()">
            <option value="Cleanings">Cleanings</option>
            <option value="Whitening">Whitening</option>
            <option value="Filling">Filling</option>
            <option value="Nerve Filling">Nerve Filling</option>
            <option value="Root Canal Therapy">Root Canal Therapy</option>
        </select><br>

        <label for="cost">Cost:</label>
        <input type="text" name="cost" id="cost" readonly><br>

        <label for="notes">Notes:</label>
        <textarea name="notes"></textarea><br>

        <input type="submit" name="submit" value="Add Treatment">
    </form>

    <script>
        // Function to update the cost field based on the selected procedure
        function updateCost() {
            var procedureName = document.getElementById("procedure_name").value;
            var costField = document.getElementById("cost");

            // Get the cost based on the selected procedure
            var cost = <?php echo json_encode(getCost("")); ?>;
            switch (procedureName) {
                case "Cleanings":
                    cost = <?php echo json_encode(getCost("Cleanings")); ?>;
                    break;
                case "Whitening":
                    cost = <?php echo json_encode(getCost("Whitening")); ?>;
                    break;
                case "Filling":
                    cost = <?php echo json_encode(getCost("Filling")); ?>;
                    break;
                case "Nerve Filling":
                    cost = <?php echo json_encode(getCost("Nerve Filling")); ?>;
                    break;
                case "Root Canal Therapy":
                    cost = <?php echo json_encode(getCost("Root Canal Therapy")); ?>;
                    break;
                default:
                    cost = 0;
                    break;
            }

            // Update the cost field
            costField.value = cost;
        }
    </script>

    <?php
    // Display Treatments Table with Update and Delete Buttons
    echo "<table border='1'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Treatment ID</th>";
    echo "<th>Patient ID</th>";
    echo "<th>Treatment Date</th>";
    echo "<th>Procedure Name</th>";
    echo "<th>Cost</th>";
    echo "<th>Notes</th>";
    echo "<th>Action</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$row['treatment_id']}</td>";
            echo "<td>{$row['patient_id']}</td>";
            echo "<td>{$row['treatment_date']}</td>";
            echo "<td>{$row['procedure_name']}</td>";
            echo "<td>{$row['cost']}</td>";
            echo "<td>{$row['notes']}</td>";
            echo "<td>";
            echo "<a href='UpdateTreatment.php?treatment_id={$row['treatment_id']}'><button type='button' style='background-color: green; color: white;'>Update</button></a>";
            echo "<a href='DeleteTreatment.php?treatment_id={$row['treatment_id']}' onclick=\"return confirm('Are you sure you want to delete this treatment?');\"><button type='button' style='background-color: red; gradient-color: red; color: white;'>Delete</button></a>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No treatments found</td></tr>";
    }

    echo "</tbody>";
    echo "</table>";

    // Close the connection
    $db->close();
    ?>
</body>
</html>
