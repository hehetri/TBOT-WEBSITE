<?php
declare(strict_types=1);

/**
 * Database access is disabled in view-only mode.
 * This stub prevents any SQL connections from being opened.
 */
function tbot_pdo(): void
{
    throw new RuntimeException('Database connections are disabled in view-only mode.');
}
