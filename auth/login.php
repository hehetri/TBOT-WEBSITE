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

$username = trim($_POST['user_id'] ?? '');
$password = $_POST['passw'] ?? '';

if ($username === '' || $password === '') {
    header('Location: ../index.php?login_error=1#account-panel');
    exit;
}

$stmt = $conn->prepare('SELECT id, username, password, position FROM users WHERE username = ? LIMIT 1');
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    header('Location: ../index.php?login_error=1#account-panel');
    exit;
}

$valid = password_verify($password, $user['password']) || hash_equals((string)$user['password'], (string)$password);
if (!$valid) {
    header('Location: ../index.php?login_error=1#account-panel');
    exit;
}

$ip = $_SERVER['REMOTE_ADDR'] ?? null;
$update = $conn->prepare('UPDATE users SET last_ip = ? WHERE id = ?');
$update->bind_param('si', $ip, $user['id']);
$update->execute();
$update->close();

$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['username'] = $user['username'];
$_SESSION['position'] = (int)($user['position'] ?? 0);

header('Location: ../index.php?login_success=1#account-panel');
exit;
