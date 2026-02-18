<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = '127.0.0.1';
$user = 'bout';
$pass = '202040pp';
$db   = 'bout_evolution';

$conn = null;
$db_error = null;

function resolve_user_table(mysqli $conn): string {
    $candidates = ['bout_users', 'users'];

    foreach ($candidates as $table) {
        try {
            $result = $conn->query("SELECT 1 FROM `{$table}` LIMIT 1");
            if ($result) {
                $result->free();
                return $table;
            }
        } catch (Throwable $e) {
            // Try next compatible table.
        }
    }

    return 'bout_users';
}

function should_hash_password_for_table(string $userTable): bool {
    return $userTable === 'users';
}

try {
    $conn = new mysqli($host, $user, $pass, $db);
    $conn->set_charset('latin1');

    $sanityTable = resolve_user_table($conn);
    $result = $conn->query("SELECT 1 FROM `{$sanityTable}` LIMIT 1");
    if ($result) {
        $result->free();
    }
} catch (Throwable $e) {
    $db_error = $e->getMessage();
}
