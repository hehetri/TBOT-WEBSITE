<?php
require_once __DIR__ . '/includes/header.php';

$pdo = get_db_connection();
$charactersStmt = $pdo->query('SELECT c.name, c.level, c.experience, c.rank, u.username FROM characters c JOIN users u ON c.user_id = u.id ORDER BY c.level DESC, c.experience DESC LIMIT 50');
$characters = $charactersStmt->fetchAll();
?>
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="card-title h4 mb-3">Ranking de Jogadores</h1>
                <?php if (!$characters): ?>
                    <p class="text-muted">Nenhum personagem cadastrado ainda.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-dark table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Personagem</th>
                                    <th>Conta</th>
                                    <th>Nível</th>
                                    <th>Experiência</th>
                                    <th>Patente</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($characters as $index => $char): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td><?php echo htmlspecialchars($char['name']); ?></td>
                                        <td><?php echo htmlspecialchars($char['username']); ?></td>
                                        <td><?php echo (int)$char['level']; ?></td>
                                        <td><?php echo number_format((int)$char['experience']); ?></td>
                                        <td><?php echo (int)$char['rank']; ?></td>
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
