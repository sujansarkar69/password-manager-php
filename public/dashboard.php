<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../classes/Database.php";
require_once "../classes/PasswordRecord.php";

$db = (new Database())->connect();
$passwordRecord = new PasswordRecord($db);

$records = $passwordRecord->getAllByUser(
    $_SESSION["user_id"],
    $_SESSION["user_key"]
);
?>

<h2>Dashboard</h2>

<p>Welcome, <?= htmlspecialchars($_SESSION["username"]) ?>!</p>

<a href="generate.php">Generate and Save Password</a><br>
<a href="change_password.php">Change Login Password</a><br>
<a href="logout.php">Logout</a>

<h3>Saved Passwords</h3>

<table border="1" cellpadding="8">
    <tr>
        <th>Website / Program</th>
        <th>Password</th>
        <th>Date Created</th>
    </tr>

    <?php foreach ($records as $record): ?>
        <tr>
            <td><?= htmlspecialchars($record["service_name"]) ?></td>
            <td><?= htmlspecialchars($record["plain_password"]) ?></td>
            <td><?= htmlspecialchars($record["created_at"]) ?></td>
        </tr>
    <?php endforeach; ?>
</table>