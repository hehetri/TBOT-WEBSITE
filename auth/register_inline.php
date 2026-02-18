<?php
session_start();
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php#register-center');
    exit;
}

if (!$conn instanceof mysqli) {
    header('Location: ../index.php?register_error=' . urlencode('Database connection failed.') . '#register-center');
    exit;
}

$userTable = resolve_user_table($conn);
$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$re_password = $_POST['re_password'] ?? '';
$last_ip = $_SERVER['REMOTE_ADDR'] ?? null;

if ($username === '' || $email === '' || $password === '' || $re_password === '') {
    header('Location: ../index.php?register_error=' . urlencode('All fields are required.') . '#register-center');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../index.php?register_error=' . urlencode('Invalid email address.') . '#register-center');
    exit;
}

if ($password !== $re_password) {
    header('Location: ../index.php?register_error=' . urlencode('Passwords do not match.') . '#register-center');
    exit;
}

$check = $conn->prepare("SELECT id FROM {$userTable} WHERE username = ? OR email = ? LIMIT 1");
$check->bind_param('ss', $username, $email);
$check->execute();
$exists = $check->get_result()->fetch_assoc();
$check->close();

if ($exists) {
    header('Location: ../index.php?register_error=' . urlencode('Username or email already exists.') . '#register-center');
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_ARGON2I);
$stmt = $conn->prepare("INSERT INTO {$userTable} (username, password, email, last_ip) VALUES (?, ?, ?, ?)");
$stmt->bind_param('ssss', $username, $hashedPassword, $email, $last_ip);
$stmt->execute();
$stmt->close();

header('Location: ../index.php?register_success=1#register-center');
exit;
