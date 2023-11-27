
<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Redirect to the login page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Generation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .invoice-container {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #ccc;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .invoice-details label {
            font-weight: bold;
        }

        .invoice-items {
            margin-top: 20px;
        }

        .invoice-items table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .invoice-items th, .invoice-items td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .invoice-total {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>
<body>
<div class="invoice-container">
        <div class="invoice-header">
            <h2>Invoice</h2>
        </div>

        <?php
        // Retrieve treatment_id from the URL parameter
        $treatment_id = isset($_GET['treatment_id']) ? $_GET['treatment_id'] : '';

        // Initialize variables
        $appointment_cost = 1000;  // Replace with the actual appointment cost
        $payment_amount = 0;       // Replace with the actual payment amount
        $other_expenses = 0;       // Replace with the actual other expenses

        // Fetch treatment data for the invoice
        $hostname = "localhost";
        $username = "root";
        $password = "";
        $dbname = "dental_management";
        $conn = new mysqli($hostname, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $treatment_query = "SELECT * FROM Treatment WHERE treatment_id = $treatment_id";
        $treatment_result = $conn->query($treatment_query);

        if ($treatment_result && $treatment_result->num_rows > 0) {
            $treatment_data = $treatment_result->fetch_assoc();

            // Display invoice details
            echo "<div class='invoice-details'>";
            echo "<div><label>Treatment ID:</label> {$treatment_data['treatment_id']}</div>";
            echo "<div><label>Patient ID:</label> {$treatment_data['patient_id']}</div>";
            echo "<div><label>Treatment Type:</label> {$treatment_data['procedure_name']}</div>";
            echo "<div><label>Cost:</label> Rs{$treatment_data['cost']}</div>";
            echo "</div>";

            // Display invoice items
            echo "<div class='invoice-items'>";
            echo "<table>";
            echo "<thead><tr><th>Description</th><th>Amount</th></tr></thead>";
            echo "<tbody>";
            echo "<tr><td>Appointment Cost</td><td>Rs{$appointment_cost}</td></tr>";
            echo "<tr><td>Treatment Cost</td><td>Rs{$treatment_data['cost']}</td></tr>";
            echo "<tr><td>Payment Amount</td><td>Rs{$payment_amount}</td></tr>";
            echo "<tr><td>Other Expenses</td><td>Rs{$other_expenses}</td></tr>";
            echo "</tbody>";
            echo "</table>";
            echo "</div>";

            // Calculate and display total amount
            $total_amount = $appointment_cost + $treatment_data['cost'] + $payment_amount + $other_expenses;
            echo "<div class='invoice-total'>Total Amount: Rs{$total_amount}</div>";
        } else {
            echo "<p>Error: Treatment data not found for the invoice</p>";
        }

        // Close the connection
        $conn->close();
        ?>
    </div>
</body>
</html>
