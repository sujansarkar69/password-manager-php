<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../classes/Database.php";
require_once "../classes/User.php";

$db = (new Database())->connect();
$user = new User($db);

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $oldPassword = $_POST["old_password"];
    $newPassword = $_POST["new_password"];

    if ($user->changePassword($_SESSION["user_id"], $oldPassword, $newPassword)) {
        session_destroy();
        $message = "Password changed successfully. Please log in again with your new password.";
    } else {
        $message = "Password change failed. Old password is incorrect.";
    }
}
?>

<h2>Change Login Password</h2>

<p><?= htmlspecialchars($message) ?></p>

<form method="POST">
    <label>Old Password:</label><br>
    <input type="password" name="old_password" required><br><br>

    <label>New Password:</label><br>
    <input type="password" name="new_password" required><br><br>

    <button type="submit">Change Password</button>
</form>

<br>
<a href="login.php">Login</a><br>
<a href="dashboard.php">Back to Dashboard</a>