<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

/**
 * Register a new T-BOT user.
 */
function tbot_register(string $username, string $email, string $password, string $ip): array
{
    $pdo = tbot_pdo();

    if ($username === '' || $email === '' || $password === '') {
        return ['success' => false, 'message' => 'Username, email, and password are required.'];
    }

    $pdo->beginTransaction();
    try {
        $exists = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = :username OR email = :email');
        $exists->execute([':username' => $username, ':email' => $email]);
        if ((int) $exists->fetchColumn() > 0) {
            $pdo->rollBack();
            return ['success' => false, 'message' => 'An account with this username or email already exists.'];
        }

        $hash = password_hash($password, PASSWORD_ARGON2ID);
        $insert = $pdo->prepare(
            'INSERT INTO users (suspended, username, password, email, email_verified, last_ip, warnet_bonus, cash)
             VALUES (0, :username, :password, :email, 0, :ip, 0, 0)'
        );
        $insert->execute([
            ':username' => $username,
            ':password' => $hash,
            ':email'    => $email,
            ':ip'       => $ip,
        ]);

        $userId = (int) $pdo->lastInsertId();
        $pdo->commit();

        return ['success' => true, 'message' => 'Account created successfully.', 'user_id' => $userId];
    } catch (Throwable $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        return ['success' => false, 'message' => 'Failed to create account.'];
    }
}

/**
 * Authenticate an existing T-BOT user.
 */
function tbot_login(string $username, string $password, string $ip): array
{
    $pdo = tbot_pdo();

    $select = $pdo->prepare('SELECT id, suspended, password FROM users WHERE username = :username');
    $select->execute([':username' => $username]);
    $user = $select->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        return ['success' => false, 'message' => 'Invalid username or password.'];
    }

    if ((int) $user['suspended'] === 1) {
        return ['success' => false, 'message' => 'This account is suspended.'];
    }

    $update = $pdo->prepare('UPDATE users SET last_ip = :ip WHERE id = :id');
    $update->execute([':ip' => $ip, ':id' => $user['id']]);

    return ['success' => true, 'message' => 'Login successful.', 'user_id' => (int) $user['id']];
}
