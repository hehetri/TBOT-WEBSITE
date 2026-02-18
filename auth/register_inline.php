<?php
session_start();
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php#register-center');
    exit;
}

if (!$conn instanceof mysqli) {
    header('Location: ../index.php?register_error=' . urlencode('Falha na conexão com banco.') . '#register-center');
    exit;
}

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$re_password = $_POST['re_password'] ?? '';
$last_ip = $_SERVER['REMOTE_ADDR'] ?? null;

if ($username === '' || $email === '' || $password === '' || $re_password === '') {
    header('Location: ../index.php?register_error=' . urlencode('Preencha todos os campos.') . '#register-center');
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../index.php?register_error=' . urlencode('Email inválido.') . '#register-center');
    exit;
}

if ($password !== $re_password) {
    header('Location: ../index.php?register_error=' . urlencode('Senhas não conferem.') . '#register-center');
    exit;
}

$check = $conn->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
$check->bind_param('ss', $username, $email);
$check->execute();
$exists = $check->get_result()->fetch_assoc();
$check->close();

if ($exists) {
    header('Location: ../index.php?register_error=' . urlencode('Usuário ou email já existe.') . '#register-center');
    exit;
}

$hashed_password = password_hash($password, PASSWORD_ARGON2I);
$stmt = $conn->prepare('INSERT INTO users (username, password, email, last_ip) VALUES (?, ?, ?, ?)');
$stmt->bind_param('ssss', $username, $hashed_password, $email, $last_ip);
$stmt->execute();
$stmt->close();

header('Location: ../index.php?register_success=1#register-center');
exit;
