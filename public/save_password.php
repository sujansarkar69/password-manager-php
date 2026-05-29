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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $serviceName = trim($_POST["service_name"]);
    $password = $_POST["password"];

    $passwordRecord->save(
        $_SESSION["user_id"],
        $serviceName,
        $password,
        $_SESSION["user_key"]
    );
}

header("Location: dashboard.php");
exit;