<?php
session_start();
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
    header('Location: ../index.php#account-panel');
    exit;
}

$center = ($_POST['center'] ?? '') === 'password' ? 'password' : '';
$centerSuffix = $center !== '' ? '&center=password#register-center' : '#account-panel';

if (!$conn instanceof mysqli) {
    header('Location: ../index.php?login_error=1' . $centerSuffix);
    exit;
}

$userTable = resolve_user_table($conn);
$userId = (int)$_SESSION['user_id'];
$action = $_POST['action'] ?? '';

if ($action === 'password') {
    $newPassword = $_POST['new_password'] ?? '';
    if (strlen($newPassword) < 4) {
        header('Location: ../index.php?login_error=1' . $centerSuffix);
        exit;
    }
    $stmt = $conn->prepare("UPDATE {$userTable} SET password = ? WHERE id = ?");
    $stmt->bind_param('si', $newPassword, $userId);
    $stmt->execute();
    $stmt->close();
}


header('Location: ../index.php?login_success=1' . $centerSuffix);
exit;
