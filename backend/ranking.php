<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

function tbot_top_characters(int $limit = 50): array
{
    $pdo = tbot_pdo();
    $limit = max(1, min($limit, 100));

    $stmt = $pdo->prepare(
        'SELECT c.id, c.name, c.level, c.experience, c.rank, c.rank_exp, u.username
         FROM characters c
         LEFT JOIN users u ON u.id = c.user_id
         ORDER BY c.level DESC, c.experience DESC
         LIMIT :limit'
    );
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}

function tbot_top_guilds(int $limit = 50): array
{
    $pdo = tbot_pdo();
    $limit = max(1, min($limit, 100));

    $stmt = $pdo->prepare(
        'SELECT g.id, g.name, g.notice, g.created, COUNT(gm.id) AS members
         FROM guilds g
         LEFT JOIN guild_members gm ON gm.guild_id = g.id
         GROUP BY g.id, g.name, g.notice, g.created
         ORDER BY members DESC, g.created ASC
         LIMIT :limit'
    );
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}
