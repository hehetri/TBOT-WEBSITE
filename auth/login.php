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

$stmt = $conn->prepare("SELECT id, username, password, `Position` AS position, banned, logincount FROM {$userTable} WHERE username = ? LIMIT 1");
$stmt->bind_param('s', $username);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user || (int)($user['banned'] ?? 0) === 1) {
    header('Location: ../index.php?login_error=1#account-panel');
    exit;
}

$dbPassword = (string)($user['password'] ?? '');
$valid = hash_equals($dbPassword, $password)
    || hash_equals($dbPassword, sha1($password))
    || hash_equals($dbPassword, md5($password));

if (!$valid) {
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
