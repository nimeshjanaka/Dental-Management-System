<?php
session_start();

class UserAuthentication
{
    private $validUsername;
    private $validPassword;

    public function __construct($validUsername, $validPassword)
    {
        $this->validUsername = $validUsername;
        $this->validPassword = $validPassword;
    }

    public function authenticate($username, $password)
    {
        if ($username === $this->validUsername && $password === $this->validPassword) {
            // Authentication successful, set session variables
            $_SESSION['user_id'] = 1;
            return true;
        } else {
            // Authentication failed
            return false;
        }
    }
}


if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $validUsername = 'admin';
    $validPassword = 'password';

    $authenticator = new UserAuthentication($validUsername, $validPassword);

    if ($authenticator->authenticate($username, $password)) {
        // Authentication successful, set session variables
        header("Location: dashboard.php");
        exit();
    } else {
        // Authentication failed, show an error message
        $error_message = "Invalid username or password";
    }
}
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
        height: 50vh;
        width: 40vh;
        margin-left: 35%;
    }
</style>
<body>
<header>
    <h1>Dental Management System</h1>
</header>
<h2>Login Page </h2>
<form method="post" action="">

    <h2>Login</h2>

    <?php
    // Display error message if authentication failed
    if (isset($error_message)) {
        echo "<p>{$error_message}</p>";
    }
    ?>

    <label for="username">Username:</label><br>
    <input type="text" name="username" required style="width:35vh";><br>

    <label for="password">Password:</label><br>
    <input type="password" name="password" required style="width:35vh";>
    <br>
    <br>

    <input type="submit" value="Login">
</form>
</body>
</html>
