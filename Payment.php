<?php
session_start();

class DatabaseConnection
{
    private $conn;  // Encapsulation: Private property to encapsulate database connection

        // Constructor to establish a database connection
    public function __construct($hostname, $username, $password, $dbname)
    {
        $this->conn = new mysqli($hostname, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
// Getter method to access the encapsulated database connection
    public function getConnection()
    {
        return $this->conn; // Encapsulation: Private property to encapsulate database connection
    }

    // Constructor to initialize with a database connection
    public function closeConnection()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
// Class for managing payments (Abstraction)
class Payments
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }
// Method to generate payment invoice and perform related database operations
    public function generateInvoice($appointmentId, $paymentAmount, $otherExpenses)
    {
        // Initialize $appointment_cost
        $appointmentCost = 1000;

        // Fetch appointment data for receipt generation
        $appointmentQuery = "SELECT * FROM Appointments WHERE appointment_id = $appointmentId";
        $appointmentResult = $this->conn->query($appointmentQuery);

        if ($appointmentResult->num_rows > 0) {
            $appointmentData = $appointmentResult->fetch_assoc();

            // Calculate total amount with appointment cost and other expenses
            $totalAmount = $appointmentCost + $paymentAmount + $otherExpenses;

            // Display the invoice details
            echo "<h2>Payment Invoice</h2>";
            echo "<table border='1'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Appointment ID</th>";
            echo "<th>Patient ID</th>";
            echo "<th>Appointment Date</th>";
            echo "<th>Payment Amount</th>";
            echo "<th>Appointment Cost</th>";
            echo "<th>Other Expenses</th>";
            echo "<th>Total Amount</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            echo "<tr>";
            echo "<td>{$appointmentData['appointment_id']}</td>";
            echo "<td>{$appointmentData['patient_id']}</td>";
            echo "<td>{$appointmentData['appointment_date']}</td>";
            echo "<td>Rs{$paymentAmount}</td>";
            echo "<td>Rs{$appointmentCost}</td>";
            echo "<td>Rs{$otherExpenses}</td>";
            echo "<td>Rs{$totalAmount}</td>";
            echo "</tr>";

            echo "</tbody>";
            echo "</table>";

            // Perform database operations for payment record
            $insertPaymentQuery = "INSERT INTO Payments (appointment_id, payment_amount, other_expenses, total_amount) VALUES ('$appointmentId', '$paymentAmount', '$otherExpenses', '$totalAmount')";

            if ($this->conn->query($insertPaymentQuery) !== TRUE) {
                echo "Error: " . $insertPaymentQuery . "<br>" . $this->conn->error;
            }
        } else {
            echo "Error: Appointment not found";
        }
    }
}

$database = new DatabaseConnection("localhost", "root", "", "dental_management");
$conn = $database->getConnection();

$paymentsManager = new Payments($conn);

// Handle form submission for Payments
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  

    // inserting payment data into the Payments table
    $appointmentId = isset($_POST["appointment_id"]) ? $_POST["appointment_id"] : "";
    $paymentAmount = isset($_POST["payment_amount"]) ? $_POST["payment_amount"] : "";
    $otherExpenses = isset($_POST["other_expenses"]) ? $_POST["other_expenses"] : "";

    $paymentsManager->generateInvoice($appointmentId, $paymentAmount, $otherExpenses);
}

// Close the connection
$database->closeConnection();
?>
