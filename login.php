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

// Check if the user is already logged in, redirect to the dashboard if true
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // For simplicity, this example uses a hardcoded username and password
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
    <!-- Your head content here -->
</head>
<style>
    <!-- Your styles here -->
</style>
<body>
<header>
    <h1>Dental Management System</h1>
</header>
<form method="post" action="">

    <h2>Login</h2>

    <?php
    // Display error message if authentication failed
    if (isset($error_message)) {
        echo "<p>{$error_message}</p>";
    }
    ?>

    <label for="username">Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label for="password">Password:</label><br>
    <input type="password" name="password" required><br><br>

    <input type="submit" value="Login">
</form>
</body>
</html>
