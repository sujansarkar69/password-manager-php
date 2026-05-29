<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "../classes/PasswordGenerator.php";

$generatedPassword = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $lowercase = (int) $_POST["lowercase"];
    $uppercase = (int) $_POST["uppercase"];
    $numbers = (int) $_POST["numbers"];
    $specials = (int) $_POST["specials"];

    $generator = new PasswordGenerator();

    $generatedPassword = $generator->generate(
        $lowercase,
        $uppercase,
        $numbers,
        $specials
    );
}
?>

<h2>Generate Password</h2>

<form method="POST">
    <label>Lowercase quantity:</label><br>
    <input type="number" name="lowercase" min="0" value="2"><br><br>

    <label>Uppercase quantity:</label><br>
    <input type="number" name="uppercase" min="0" value="3"><br><br>

    <label>Numbers quantity:</label><br>
    <input type="number" name="numbers" min="0" value="2"><br><br>

    <label>Special characters quantity:</label><br>
    <input type="number" name="specials" min="0" value="2"><br><br>

    <button type="submit">Generate</button>
</form>

<?php if ($generatedPassword): ?>
    <h3>Generated Password:</h3>
    <p><strong><?= htmlspecialchars($generatedPassword) ?></strong></p>

    <h3>Save Password</h3>

    <form method="POST" action="save_password.php">
        <label>Website / Program Name:</label><br>
        <input type="text" name="service_name" required><br><br>

        <label>Password:</label><br>
        <input type="text" name="password" value="<?= htmlspecialchars($generatedPassword) ?>" required><br><br>

        <button type="submit">Save Password</button>
    </form>
<?php endif; ?>

<br>
<a href="dashboard.php">Back to Dashboard</a>