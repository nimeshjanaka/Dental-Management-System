<?php
session_start();
    // OOP Concept: Encapsulation
class User
{
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']);
    }

    public static function logout()
    {
        session_destroy();
        header("Location: login.php");
        exit();
    }
}
// Dashboard class for rendering the dashboard
class Dashboard
{
    // OOP Concept: Abstraction - Public method to render the dashboard
    public function render()
    {
        $this->checkLogin();

        include 'navbar.php';

        echo '<h2>Welcome Tooth Care Hospital</h2>';
        
        // Logout button
        echo '<form method="post" action="">';
        echo '<button type="submit" name="logout">Logout</button>';
        echo '</form>';
    }
// OOP Concept: Encapsulation - Private method to check login status
    private function checkLogin()
    {
        if (!User::isLoggedIn()) {
            header("Location: login.php");
            exit();
        }
    }
}
// Create an instance of the Dashboard class
$dashboard = new Dashboard();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    // OOP Concept: Polymorphism - Call the static logout method of the User class
    User::logout();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Dental Management System</title>
</head>
<style>
    body {
        margin: 0;
        padding: 0;
        background: url('https://img.freepik.com/free-vector/dentist-medical-background-with-3d-tooth-design_1017-26095.jpg?size=626&ext=jpg&ga=GA1.1.1880011253.1700265600&semt=ais') center/cover no-repeat fixed;
        background-color: #3498db; /* Blue overlay color */
        height: 100vh; /* 100% of the viewport height */
        align-items: center;
        justify-content: center;
        text-align: center;
    }

    h2 {
        color: #AD0274;
        font-style: italic;
    }

    form {
        margin-top: 20px;
    }

    button {
        background-color: #3498db; 
        color: white;
        border: none;
        padding: 10px 20px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #2980b9; 
    }
</style>
<body>
    <?php $dashboard->render(); ?>
</body>
</html>
