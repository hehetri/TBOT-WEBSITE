<?php
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function is_logged_in(): bool
{
    return isset($_SESSION['user_id']);
}

function current_user(): ?array
{
    if (!is_logged_in()) {
        return null;
    }

    $pdo = get_db_connection();
    $stmt = $pdo->prepare('SELECT id, username, email FROM users WHERE id = ? LIMIT 1');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    return $user ?: null;
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function register_user(string $username, string $email, string $password): array
{
    $errors = [];

    if ($username === '' || $email === '' || $password === '') {
        $errors[] = 'Todos os campos são obrigatórios.';
    }

    if (!preg_match('/^[A-Za-z0-9_]{4,16}$/', $username)) {
        $errors[] = 'O nome de usuário deve ter entre 4 e 16 caracteres alfanuméricos ou underscore.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'E-mail inválido.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'A senha deve ter pelo menos 8 caracteres.';
    }

    if ($errors) {
        return $errors;
    }

    $pdo = get_db_connection();

    $existing = $pdo->prepare('SELECT 1 FROM users WHERE username = ? OR email = ? LIMIT 1');
    $existing->execute([$username, $email]);
    if ($existing->fetch()) {
        $errors[] = 'Usuário ou e-mail já cadastrado.';
        return $errors;
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare('INSERT INTO users (username, password, email, email_verified, last_ip, warnet_bonus, cash) VALUES (?, ?, ?, 0, ?, 0, 0)');
    $stmt->execute([
        $username,
        $passwordHash,
        $email,
        $_SERVER['REMOTE_ADDR'] ?? null,
    ]);

    $_SESSION['user_id'] = (int)$pdo->lastInsertId();

    return $errors;
}

function attempt_login(string $username, string $password): array
{
    $errors = [];
    $pdo = get_db_connection();

    $stmt = $pdo->prepare('SELECT id, password, suspended FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        $errors[] = 'Credenciais inválidas.';
        return $errors;
    }

    if ((int)$user['suspended'] === 1) {
        $errors[] = 'Conta suspensa. Entre em contato com o suporte.';
        return $errors;
    }

    $_SESSION['user_id'] = (int)$user['id'];

    $update = $pdo->prepare('UPDATE users SET last_ip = ? WHERE id = ?');
    $update->execute([$_SERVER['REMOTE_ADDR'] ?? null, $user['id']]);

    return $errors;
}

function logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}
