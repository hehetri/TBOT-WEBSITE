<?php
/**
 * Simplified configuration loader for the T-BOT website.
 *
 * The original file was obfuscated and triggered parse errors when evaluated.
 * This clean version exposes the database credentials from configs/db.json and
 * loads the legacy MSSQL compatibility layer so older code paths can run
 * against MySQL without visual changes.
 */
declare(strict_types=1);

require_once __DIR__ . '/../backend/mssql_compat.php';

$configFile = __DIR__ . '/db.json';
$configData = json_decode((string) file_get_contents($configFile), true);
$db = is_array($configData) && isset($configData[0]) ? $configData[0] : [];

// Fallback-safe defaults to avoid undefined index notices.
$dbHost = $db['host'] ?? '127.0.0.1';
$dbUser = $db['login'] ?? 'root';
$dbPass = $db['pass'] ?? '';
$dbName = $db['db_name'] ?? '';

// Expose credentials in globals/constants for legacy includes.
define('DB_HOST', $dbHost);
define('DB_USER', $dbUser);
define('DB_PASS', $dbPass);
define('DB_NAME', $dbName);

$GLOBALS['tbot_db_config'] = [
    'host' => $dbHost,
    'user' => $dbUser,
    'pass' => $dbPass,
    'name' => $dbName,
];
