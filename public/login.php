<?php
session_start();

require_once "../classes/Database.php";
require_once "../classes/User.php";

$db = (new Database())->connect();
$user = new User($db);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $loggedUser = $user->login($username, $password);

    if ($loggedUser) {
        $_SESSION["user_id"] = $loggedUser["id"];
        $_SESSION["username"] = $loggedUser["username"];
        $_SESSION["user_key"] = $loggedUser["user_key"];

        header("Location: dashboard.php");
        exit;
    } else {
        $message = "Invalid username or password.";
    }
}
?>

<h2>Login</h2>

<p><?= htmlspecialchars($message) ?></p>

<form method="POST">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>

<br>
<a href="register.php">Register</a>