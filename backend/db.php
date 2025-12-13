<?php
declare(strict_types=1);

use PDO;
use PDOException;

/**
 * Create a reusable PDO connection configured for the T-BOT database.
 * The connection details are sourced from configs/db.json (first entry).
 */
function tbot_pdo(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $configPath = __DIR__ . '/../configs/db.json';
    $configRaw = @file_get_contents($configPath);
    if ($configRaw === false) {
        throw new RuntimeException('Unable to read database configuration.');
    }

    $config = json_decode($configRaw, true, flags: JSON_THROW_ON_ERROR);
    if (!is_array($config) || !isset($config[0])) {
        throw new RuntimeException('Database configuration is invalid or missing.');
    }

    $db = $config[0];
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8mb4', $db['host'], $db['db_name']);

    try {
        $pdo = new PDO($dsn, $db['login'], $db['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $e) {
        throw new RuntimeException('Failed to connect to the T-BOT database.', 0, $e);
    }

    return $pdo;
}
