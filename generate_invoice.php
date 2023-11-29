
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
<head >
    <div class="head">
    <h1 class="h3">Dental Management System</h1>
    </div>
</head>
    <style>
        
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            margin: 0;
            padding: 0;
            background: url('https://img.freepik.com/free-vector/dentist-medical-background-with-3d-tooth-design_1017-26095.jpg?size=626&ext=jpg&ga=GA1.1.1880011253.1700265600&semt=ais') center/cover no-repeat fixed;
            background-color: #3498db;
            height: 100vh; 
        

        }
        .head {
            background-color: gray;
            height: 100px;
        }
        .h3 {
            text-align: center;
            
        }

        .invoice-container {
            max-width: 600px;
            margin: 0 auto;
            border: 3px solid #bbb;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.8);
            margin-top: 60px;
            background-color: rgba(255,255,235, 0.8);
            
            
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
            text: bold;
            font-weight: bold;
            font-size: 18px;
            text-decoration: underline;
            
        }
        .h1{
            color: #AD0274;
            font-weight: bold;
        }
        p{
            color: #AD0274;
            font: italic;
            font-size: 13px;
            margin-left: 35%;
        }
    </style>

<body>
<div class="invoice-container">
        <div class="invoice-header">
            <h1 class="h1">Tooth Care Dental</h1>
            <h2>Invoice</h2>
        </div>

        <?php
        // Retrieve treatment_id from the URL parameter
        $treatment_id = isset($_GET['treatment_id']) ? $_GET['treatment_id'] : '';

        // Initialize variables
        $appointment_cost = 1000;  
        $payment_amount = 0;       
        $other_expenses = 0;       

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
        <p class="p">Thank you come again!</p>
    </div>
</body>
</html>
