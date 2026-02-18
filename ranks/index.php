<?php
require_once __DIR__ . '/../config.php';

function queryAll(mysqli $conn = null, string $sql): array {
    if (!$conn) {
        return [];
    }
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
    WHERE (u.Position IS NULL OR u.Position < 150)
      AND c.name NOT LIKE '[GM]%'
    ORDER BY c.level DESC, c.exp DESC
    LIMIT 50
");

$rankExp = queryAll($conn, "
    SELECT c.name, c.level, c.exp
    FROM bout_characters c
    LEFT JOIN bout_users u ON u.username = c.name
    WHERE (u.Position IS NULL OR u.Position < 150)
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
    WHERE (u.Position IS NULL OR u.Position < 150)
      AND c.name NOT LIKE '[GM]%'
    ORDER BY attack_power DESC
    LIMIT 50
");

$rankGuilds = queryAll($conn, "
    SELECT g.Guildname, g.leader, g.total_points, g.leader_points
    FROM guilds g
    LEFT JOIN bout_users u ON u.username = g.leader
    WHERE (u.Position IS NULL OR u.Position < 150)
      AND g.leader NOT LIKE '[GM]%'
    ORDER BY g.total_points DESC
    LIMIT 30
");

$rankGuildMembers = queryAll($conn, "
    SELECT gm.guild, gm.player, gm.points
    FROM guildmembers gm
    LEFT JOIN bout_users u ON u.username = gm.player
    WHERE (u.Position IS NULL OR u.Position < 150)
      AND gm.player NOT LIKE '[GM]%'
    ORDER BY gm.points DESC
    LIMIT 50
");

function renderRows(array $rows, array $cols): void {
    if (!$rows) {
        echo '<tr><td colspan="' . count($cols) . '">No data.</td></tr>';
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
<!DOCTYPE html><html><head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8">
<title>Rank - T-Bot Online</title>
<link href="../images/favicon.ico" rel="shortcut icon">
<link href="../css/main.css" media="screen" rel="stylesheet" type="text/css">
<link href="../css/toolbar.css" media="screen" rel="stylesheet" type="text/css">
<link href="../css/cbt2.css" media="screen" rel="stylesheet" type="text/css">
<style>
.rank-container{width:960px;margin:0 auto 20px auto;background:#f3f3f3;border:2px solid #b6b6b6;border-radius:8px;padding:12px;box-shadow:0 2px 8px rgba(0,0,0,.25)}
.rank-title{font-family:Arial;font-size:22px;font-weight:bold;color:#8a0e0e;margin:4px 0 10px 0}
.rank-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.rank-box{background:#fff;border:1px solid #d0d0d0;border-radius:6px;padding:8px;overflow:auto}
.rank-box.full{grid-column:1/-1}
.rank-box h2{margin:0 0 6px 0;font-size:16px;color:#333;font-family:Arial}
.rank-table{width:100%;border-collapse:collapse;font-family:Arial;font-size:12px}
.rank-table th,.rank-table td{border-bottom:1px solid #e5e5e5;padding:5px;text-align:left;white-space:nowrap}
.rank-table th{background:#efefef;color:#7a0000}
</style>
</head>
<body class="tundra" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div style="background-image: url(../images/cbt/header_back.jpg); background-repeat: no-repeat; background-position: center top; width: 100%; min-height:100vh; position:relative;display: table;">
  <div style="width:960px;margin:20px auto 0 auto; font-family:Arial; font-size:12px;"><a href="../index.php" style="display:inline-block;background:#b42822;color:#fff;text-decoration:none;padding:8px 12px;border-radius:4px;font-weight:bold;">&larr; BACK HOME</a></div>
  <div class="rank-container" id="rank-legacy-panel">
    <div class="rank-title">TBOT Rankings</div>
    <div class="rank-grid">
      <section class="rank-box">
        <h2>Top Level</h2>
        <table class="rank-table"><thead><tr><th>Name</th><th>Level</th><th>Exp</th></tr></thead><tbody><?php renderRows($rankLevel,['name','level','exp']); ?></tbody></table>
      </section>
      <section class="rank-box">
        <h2>Top Experience</h2>
        <table class="rank-table"><thead><tr><th>Name</th><th>Level</th><th>Exp</th></tr></thead><tbody><?php renderRows($rankExp,['name','level','exp']); ?></tbody></table>
      </section>
      <section class="rank-box">
        <h2>Top Attack Power</h2>
        <table class="rank-table"><thead><tr><th>Name</th><th>Level</th><th>HP</th><th>Attack</th><th>Defense</th></tr></thead><tbody><?php renderRows($rankStats,['name','level','hp','attack_power','defense']); ?></tbody></table>
      </section>
      <section class="rank-box">
        <h2>Guild Rankings</h2>
        <table class="rank-table"><thead><tr><th>Guild</th><th>Leader</th><th>Total</th><th>Leader Points</th></tr></thead><tbody><?php renderRows($rankGuilds,['Guildname','leader','total_points','leader_points']); ?></tbody></table>
      </section>
      <section class="rank-box full">
        <h2>Top Guild Players</h2>
        <table class="rank-table"><thead><tr><th>Guild</th><th>Player</th><th>Points</th></tr></thead><tbody><?php renderRows($rankGuildMembers,['guild','player','points']); ?></tbody></table>
      </section>
    </div>
  </div>
</div>
</body></html>
