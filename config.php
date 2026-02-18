<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = '127.0.0.1';
$user = 'bout';
$pass = '202040pp';
$db   = 'bout_evolution';

$conn = null;
$db_error = null;

function resolve_user_table(mysqli $conn): string {
    $preferred = 'boot_users';
    $fallback = 'users';

    $stmt = $conn->prepare('SHOW TABLES LIKE ?');
    $stmt->bind_param('s', $preferred);
    $stmt->execute();
    $hasPreferred = (bool)$stmt->get_result()->fetch_row();
    $stmt->close();

    if ($hasPreferred) {
        return $preferred;
    }

    $stmt = $conn->prepare('SHOW TABLES LIKE ?');
    $stmt->bind_param('s', $fallback);
    $stmt->execute();
    $hasFallback = (bool)$stmt->get_result()->fetch_row();
    $stmt->close();

    return $hasFallback ? $fallback : $preferred;
}

try {
    $conn = new mysqli($host, $user, $pass, $db);
    $conn->set_charset('utf8mb4');
} catch (Throwable $e) {
    $db_error = $e->getMessage();
}
