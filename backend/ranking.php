<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

/**
 * View-only stub for character rankings: returns an empty list without database access.
 */
function tbot_top_characters(int $limit = 50): array
{
    return [];
}

/**
 * View-only stub for guild rankings: returns an empty list without database access.
 */
function tbot_top_guilds(int $limit = 50): array
{
    return [];
}
