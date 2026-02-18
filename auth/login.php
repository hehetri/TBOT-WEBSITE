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

$user = null;
$validPassword = false;

$authStrategies = [
    ['table' => 'bout_users', 'mode' => 'plain'],
    ['table' => 'users', 'mode' => 'hash'],
];

foreach ($authStrategies as $strategy) {
    try {
        if ($strategy['mode'] === 'plain') {
            $stmt = $conn->prepare("SELECT id, username, `Position` AS position, banned FROM {$strategy['table']} WHERE username = ? AND password = ? LIMIT 1");
            $stmt->bind_param('ss', $username, $password);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($row) {
                $user = $row;
                $validPassword = true;
                break;
            }
        } else {
            $stmt = $conn->prepare("SELECT id, username, password, 0 AS position, suspended AS banned FROM {$strategy['table']} WHERE username = ? LIMIT 1");
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($row && password_verify($password, (string)$row['password'])) {
                $user = $row;
                $validPassword = true;
                break;
            }
        }
    } catch (Throwable $e) {
        // Try next compatible auth strategy.
    }
}

if (!$validPassword || !$user || (int)($user['banned'] ?? 0) === 1) {
    header('Location: ../index.php?login_error=1#account-panel');
    exit;
}

$userTable = 'bout_users';
if (isset($user['password'])) {
    $userTable = 'users';
}

$ip = $_SERVER['REMOTE_ADDR'] ?? '';
try {
    $update = $conn->prepare("UPDATE {$userTable} SET last_ip = ?, current_ip = ?, lastlogin = NOW(), logincount = logincount + 1 WHERE id = ?");
    $update->bind_param('ssi', $ip, $ip, $user['id']);
    $update->execute();
    $update->close();
} catch (Throwable $e) {
    // Some schemas may not contain all legacy audit columns.
}

$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['username'] = (string)$user['username'];
$_SESSION['position'] = (int)($user['position'] ?? 0);

header('Location: ../index.php?login_success=1#account-panel');
exit;
