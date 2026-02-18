<?php
session_start();
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php#account-panel');
    exit;
}

if (!$conn instanceof mysqli) {
    header('Location: ../index.php?login_error=1#account-panel');
    exit;
}

$userTable = resolve_user_table($conn);
$username = trim($_POST['user_id'] ?? '');
$password = $_POST['passw'] ?? '';

if ($username === '' || $password === '') {
    header('Location: ../index.php?login_error=1#account-panel');
    exit;
}

// Database uses plain-text password (no hashing) per project requirement.
if (should_hash_password_for_table($userTable)) {
    $stmt = $conn->prepare("SELECT id, username, password, 0 AS position, suspended AS banned FROM {$userTable} WHERE username = ? LIMIT 1");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $validPassword = $user && password_verify($password, (string)$user['password']);
} else {
    $stmt = $conn->prepare("SELECT id, username, `Position` AS position, banned FROM {$userTable} WHERE username = ? AND password = ? LIMIT 1");
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $validPassword = (bool)$user;
}

if (!$validPassword || !$user || (int)($user['banned'] ?? 0) === 1) {
    header('Location: ../index.php?login_error=1#account-panel');
    exit;
}

$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$update = $conn->prepare("UPDATE {$userTable} SET last_ip = ?, current_ip = ?, lastlogin = NOW(), logincount = logincount + 1 WHERE id = ?");
$update->bind_param('ssi', $ip, $ip, $user['id']);
$update->execute();
$update->close();

$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['username'] = (string)$user['username'];
$_SESSION['position'] = (int)($user['position'] ?? 0);

header('Location: ../index.php?login_success=1#account-panel');
exit;
