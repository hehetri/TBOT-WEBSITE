<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

/**
 * View-only stub for registration: no database writes are performed.
 */
function tbot_register(string $username, string $email, string $password, string $ip): array
{
    return [
        'success' => false,
        'message' => 'Registro desativado: o site está em modo somente visualização sem conexão ao banco.',
    ];
}

/**
 * View-only stub for login: always fails gracefully without hitting the database.
 */
function tbot_login(string $username, string $password, string $ip): array
{
    return [
        'success' => false,
        'message' => 'Login desativado: o site está em modo somente visualização sem conexão ao banco.',
    ];
}
