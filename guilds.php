<?php
require_once __DIR__ . '/includes/header.php';

$pdo = get_db_connection();
$guildStmt = $pdo->query('SELECT g.id, g.name, g.notice, g.created, g.max_members, c.name AS leader_name, COUNT(m.id) AS members, COALESCE(SUM(m.points), 0) AS total_points
    FROM guilds g
    LEFT JOIN characters c ON g.leader_character_id = c.id
    LEFT JOIN guild_members m ON g.id = m.guild_id AND m.applying = 0
    GROUP BY g.id, g.name, g.notice, g.created, g.max_members, leader_name
    ORDER BY total_points DESC, members DESC, g.name ASC');
$guilds = $guildStmt->fetchAll();
?>
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="card-title h4 mb-3">Guilds</h1>
                <?php if (!$guilds): ?>
                    <p class="text-muted">Nenhuma guild encontrada.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-dark table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Guild</th>
                                    <th>Líder</th>
                                    <th>Membros</th>
                                    <th>Pontos</th>
                                    <th>Criada em</th>
                                    <th>Mensagem</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($guilds as $idx => $guild): ?>
                                    <tr>
                                        <td><?php echo $idx + 1; ?></td>
                                        <td><?php echo htmlspecialchars($guild['name']); ?></td>
                                        <td><?php echo htmlspecialchars($guild['leader_name'] ?? 'Indefinido'); ?></td>
                                        <td><?php echo (int)$guild['members'] . ' / ' . (int)$guild['max_members']; ?></td>
                                        <td><?php echo (int)$guild['total_points']; ?></td>
                                        <td><?php echo $guild['created'] ? htmlspecialchars($guild['created']) : '---'; ?></td>
                                        <td><?php echo htmlspecialchars($guild['notice'] ?? ''); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
