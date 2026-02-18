<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host = '127.0.0.1';
$user = 'bout';
$pass = '202040pp';
$db   = 'bout_evolution';

$conn = null;
$db_error = null;

function table_exists(mysqli $conn, string $table): bool {
    $stmt = $conn->prepare("SHOW TABLES LIKE ?");
    $stmt->bind_param('s', $table);
    $stmt->execute();
    $exists = (bool)$stmt->get_result()->fetch_row();
    $stmt->close();
    return $exists;
}

function table_has_column(mysqli $conn, string $table, string $column): bool {
    $stmt = $conn->prepare(
        "SELECT 1 FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ? LIMIT 1"
    );
    $stmt->bind_param('ss', $table, $column);
    $stmt->execute();
    $exists = (bool)$stmt->get_result()->fetch_row();
    $stmt->close();
    return $exists;
}

function resolve_user_table(mysqli $conn): string {
    if (table_exists($conn, 'bout_users')) {
        return 'bout_users';
    }

    return 'users';
}

function resolve_character_table(mysqli $conn): string {
    if (table_exists($conn, 'bout_characters')) {
        return 'bout_characters';
    }

    return 'characters';
}

function resolve_user_cash_column(mysqli $conn, string $userTable): string {
    if (table_has_column($conn, $userTable, 'coins')) {
        return 'coins';
    }

    return 'cash';
}

function should_hash_password_for_table(string $userTable): bool {
    return $userTable === 'users';
}

try {
    $conn = new mysqli($host, $user, $pass, $db);
    $conn->set_charset('latin1');

    $sanityTable = table_exists($conn, 'bout_users') ? 'bout_users' : 'users';
    $result = $conn->query("SELECT 1 FROM `{$sanityTable}` LIMIT 1");
    if ($result) {
        $result->free();
    }
} catch (Throwable $e) {
    $db_error = $e->getMessage();
}
