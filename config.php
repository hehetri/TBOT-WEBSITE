<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = '127.0.0.1';
$user = 'bout';
$pass = '202040pp';
$db   = 'bout_evolution';

$conn = null;
$db_error = null;

function resolve_user_table(mysqli $conn): string {
    // Per project requirement, the user table is fixed.
    return 'bout_users';
}

try {
    $conn = new mysqli($host, $user, $pass, $db);
    $conn->set_charset('latin1');

    // Optional sanity check with a normal SELECT (portable in MariaDB/MySQL).
    $result = $conn->query("SELECT 1 FROM `bout_users` LIMIT 1");
    if ($result) {
        $result->free();
    }
} catch (Throwable $e) {
    $db_error = $e->getMessage();
}
