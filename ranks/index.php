<?php
require_once __DIR__ . '/../config.php';

function queryAll(mysqli $conn, string $sql): array {
    try {
        $res = $conn->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    } catch (Throwable $e) {
        return [];
    }
}

$rankLevel = queryAll($conn, "
    SELECT c.name, c.level, c.exp
    FROM bout_characters c
    LEFT JOIN bout_users u ON u.username = c.name
    WHERE (u.position IS NULL OR u.position < 150)
      AND c.name NOT LIKE '[GM]%'
    ORDER BY c.level DESC, c.exp DESC
    LIMIT 50
");

$rankExp = queryAll($conn, "
    SELECT c.name, c.level, c.exp
    FROM bout_characters c
    LEFT JOIN bout_users u ON u.username = c.name
    WHERE (u.position IS NULL OR u.position < 150)
      AND c.name NOT LIKE '[GM]%'
    ORDER BY c.exp DESC
    LIMIT 50
");

$rankStats = queryAll($conn, "
    SELECT c.name, c.level, c.hp,
           (c.attmin + c.attmax) AS attack_power,
           c.defense
    FROM bout_characters c
    LEFT JOIN bout_users u ON u.username = c.name
    WHERE (u.position IS NULL OR u.position < 150)
      AND c.name NOT LIKE '[GM]%'
    ORDER BY attack_power DESC
    LIMIT 50
");

$rankGuilds = queryAll($conn, "
    SELECT g.Guildname, g.leader, g.total_points, g.leader_points
    FROM guilds g
    LEFT JOIN bout_users u ON u.username = g.leader
    WHERE (u.position IS NULL OR u.position < 150)
      AND g.leader NOT LIKE '[GM]%'
    ORDER BY g.total_points DESC
    LIMIT 30
");

$rankGuildMembers = queryAll($conn, "
    SELECT gm.guild, gm.player, gm.points
    FROM guildmembers gm
    LEFT JOIN bout_users u ON u.username = gm.player
    WHERE (u.position IS NULL OR u.position < 150)
      AND gm.player NOT LIKE '[GM]%'
    ORDER BY gm.points DESC
    LIMIT 50
");

function renderRows(array $rows, array $cols): void {
    if (!$rows) {
        echo '<tr><td colspan="' . count($cols) . '">Sem dados.</td></tr>';
        return;
    }
    foreach ($rows as $r) {
        echo '<tr>';
        foreach ($cols as $c) {
            echo '<td>' . htmlspecialchars((string)($r[$c] ?? '')) . '</td>';
        }
        echo '</tr>';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <title>Ranking - TBOT</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    body{background:#121212;color:#f3f3f3;font-family:Arial,sans-serif;margin:0;padding:20px}
    a{color:#f68122}
    h1,h2{margin:10px 0}
    .grid{display:grid;grid-template-columns:1fr;gap:16px}
    @media (min-width:1100px){.grid{grid-template-columns:1fr 1fr}}
    .card{background:#1c1c1c;padding:12px;border-radius:8px;overflow:auto}
    table{width:100%;border-collapse:collapse;font-size:13px}
    th,td{border-bottom:1px solid #2f2f2f;padding:6px;text-align:left;white-space:nowrap}
    th{background:#222;position:sticky;top:0}
  </style>
</head>
<body>
  <p><a href="../index.php">&larr; Voltar para Home</a> | <a href="../register.php">Registro</a></p>
  <h1>Ranking TBOT</h1>
  <div class="grid">
    <section class="card">
      <h2>Top Level</h2>
      <table><thead><tr><th>Nome</th><th>Level</th><th>Exp</th></tr></thead><tbody><?php renderRows($rankLevel,['name','level','exp']); ?></tbody></table>
    </section>
    <section class="card">
      <h2>Top Experience</h2>
      <table><thead><tr><th>Nome</th><th>Level</th><th>Exp</th></tr></thead><tbody><?php renderRows($rankExp,['name','level','exp']); ?></tbody></table>
    </section>
    <section class="card">
      <h2>Top Attack Power</h2>
      <table><thead><tr><th>Nome</th><th>Level</th><th>HP</th><th>Ataque</th><th>Defesa</th></tr></thead><tbody><?php renderRows($rankStats,['name','level','hp','attack_power','defense']); ?></tbody></table>
    </section>
    <section class="card">
      <h2>Guild Rankings</h2>
      <table><thead><tr><th>Guild</th><th>Líder</th><th>Total</th><th>Pontos Líder</th></tr></thead><tbody><?php renderRows($rankGuilds,['Guildname','leader','total_points','leader_points']); ?></tbody></table>
    </section>
    <section class="card" style="grid-column:1/-1">
      <h2>Top Guild Players</h2>
      <table><thead><tr><th>Guild</th><th>Player</th><th>Pontos</th></tr></thead><tbody><?php renderRows($rankGuildMembers,['guild','player','points']); ?></tbody></table>
    </section>
  </div>
</body>
</html>
