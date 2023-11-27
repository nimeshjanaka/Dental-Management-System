
<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page
    exit();
}
?>

<?php include 'navbar.php'; ?>

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

// Initialize $appointment_cost
$appointment_cost = 1000;

// Handle form submission for Payments
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    

    // inserting payment data into the Payments table
    $appointment_id = isset($_POST["appointment_id"]) ? $_POST["appointment_id"] : "";
    $payment_amount = isset($_POST["payment_amount"]) ? $_POST["payment_amount"] : "";
    $other_expenses = isset($_POST["other_expenses"]) ? $_POST["other_expenses"] : "";

    // Fetch appointment data for receipt generation
    $appointment_query = "SELECT * FROM Appointments WHERE appointment_id = $appointment_id";
    $appointment_result = $conn->query($appointment_query);

    if ($appointment_result->num_rows > 0) {
        $appointment_data = $appointment_result->fetch_assoc();

        // Fetch treatment data for the appointment
        $treatment_query = "SELECT * FROM treatment WHERE appointment_id = $appointment_id";
        $treatment_result = $conn->query($treatment_query);

        if ($treatment_result->num_rows > 0) {
            $treatment_data = $treatment_result->fetch_assoc();

            // Calculate total amount with treatment cost, additional Rs1000 per appointment, and other expenses
            $total_amount = $appointment_cost + $treatment_data['cost'] + $payment_amount + $other_expenses;

            // Display receipt or perform other actions
            echo "Payment Receipt for Appointment ID: {$appointment_id}<br>";
            echo "Patient ID: {$appointment_data['patient_id']}<br>";
            echo "Appointment Date: {$appointment_data['appointment_date']}<br>";
            echo "Treatment Name: {$treatment_data['procedure_name']}<br>";
            echo "Appointment Cost: Rs{$appointment_cost}<br>";
            echo "Treatment Cost: Rs{$treatment_data['cost']}<br>";
            echo "Payment Amount: Rs{$payment_amount}<br>";
            echo "Other Expenses: Rs{$other_expenses}<br>";
            echo "Total Amount: Rs{$total_amount}<br>";

            
           
            $insert_payment_query = "INSERT INTO Payments (appointment_id, payment_amount, other_expenses, total_amount) VALUES ('$appointment_id', '$payment_amount', '$other_expenses', '$total_amount')";

            if ($conn->query($insert_payment_query) !== TRUE) {
                echo "Error: " . $insert_payment_query . "<br>" . $conn->error;
            }
        } else {
            echo "Error: Treatment data not found for the appointment";
        }
    } else {
        echo "Error: Appointment not found";
    }
}

// Display Appointments Table
$query = "SELECT * FROM Appointments";
$result = $conn->query($query);

if (!$result) {
    echo "Error: " . $conn->error;
} else {
    echo "<h2>Appointments Table</h2>";
    echo "<table border='1'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Appointment ID</th>";
    echo "<th>Patient ID</th>";
    echo "<th>Appointment Date</th>";
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
            echo "<td>";
            echo "<form method='post' action='Payment.php'>";
            echo "<input type='hidden' name='appointment_id' value='{$row['appointment_id']}'>";
            echo "<label for='payment_amount'>Payment Amount:</label>";
            echo "<input type='number' name='payment_amount' required><br>";
            echo "Appointment Cost: Rs{$appointment_cost}<br>";
            echo "<label for='other_expenses'>Other Expenses:</label>";
            echo "<input type='number' name='other_expenses'><br>";
            echo "<input type='submit' name='submit' value='Make Payment'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No appointments found</td></tr>";
    }

    echo "</tbody>";
    echo "</table>";
}

// Display Treatment Table
$treatment_query = "SELECT * FROM Treatment";
$treatment_result = $conn->query($treatment_query);

if (!$treatment_result) {
    echo "Error: " . $conn->error;
} else {
    echo "<h2>Treatment Payments Table</h2>";
    echo "<table border='1'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Treatment ID</th>";
    echo "<th>Patient ID</th>";
    echo "<th>Treatment Type</th>";
    echo "<th>Cost</th>";
    echo "<th>Action</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    if ($treatment_result->num_rows > 0) {
        while ($treatment_row = $treatment_result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$treatment_row['treatment_id']}</td>";
            echo "<td>{$treatment_row['patient_id']}</td>";
            echo "<td>{$treatment_row['procedure_name']}</td>";
            echo "<td>{$treatment_row['cost']}</td>";
            echo "<td><a href='generate_invoice.php?treatment_id={$treatment_row['treatment_id']}' target='_blank'>Generate Invoice</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No treatments found</td></tr>";
    }

    echo "</tbody>";
    echo "</table>";
}

// Close the connection
$conn->close();
?>
