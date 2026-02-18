<?php
session_start();
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
    header('Location: ../index.php#account-panel');
    exit;
}

if (!$conn instanceof mysqli) {
    header('Location: ../index.php?login_error=1#account-panel');
    exit;
}

$userTable = resolve_user_table($conn);
$userId = (int)$_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($action === 'password') {
    $newPassword = $_POST['new_password'] ?? '';
    if (strlen($newPassword) < 4) {
        header('Location: ../index.php?login_error=1#account-panel');
        exit;
    }
    $hash = password_hash($newPassword, PASSWORD_ARGON2I);
    $stmt = $conn->prepare("UPDATE {$userTable} SET password = ? WHERE id = ?");
    $stmt->bind_param('si', $hash, $userId);
    $stmt->execute();
    $stmt->close();
}

if ($action === 'email') {
    $newEmail = trim($_POST['new_email'] ?? '');
    if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
        header('Location: ../index.php?login_error=1#account-panel');
        exit;
    }
    $stmt = $conn->prepare("UPDATE {$userTable} SET email = ? WHERE id = ?");
    $stmt->bind_param('si', $newEmail, $userId);
    $stmt->execute();
    $stmt->close();
}

header('Location: ../index.php?login_success=1#account-panel');
exit;
