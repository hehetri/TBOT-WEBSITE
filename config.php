<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = '127.0.0.1';
$user = 'bout';
$pass = '202040pp';
$db   = 'bout_evolution';

$conn = null;
$db_error = null;

function resolve_user_table(mysqli $conn): string {
    $candidates = ['bout_users', 'boot_users', 'users'];

    foreach ($candidates as $table) {
        $stmt = $conn->prepare('SHOW TABLES LIKE ?');
        $stmt->bind_param('s', $table);
        $stmt->execute();
        $exists = (bool)$stmt->get_result()->fetch_row();
        $stmt->close();

        if ($exists) {
            return $table;
        }
    }

    return 'bout_users';
}

try {
    $conn = new mysqli($host, $user, $pass, $db);
    $conn->set_charset('utf8mb4');
} catch (Throwable $e) {
    $db_error = $e->getMessage();
}
