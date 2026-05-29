<?php

require_once "../classes/Database.php";
require_once "../classes/User.php";

$db = (new Database())->connect();
$user = new User($db);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if ($user->register($username, $password)) {
        $message = "Registration successful. You can now log in.";
    } else {
        $message = "Registration failed.";
    }
}
?>

<h2>Register</h2>

<p><?= htmlspecialchars($message) ?></p>

<form method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Register</button>
</form>

<br>
<a href="login.php">Login</a>