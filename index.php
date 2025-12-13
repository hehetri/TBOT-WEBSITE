<?php require_once __DIR__ . '/includes/header.php'; ?>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="card-title h3 mb-3">Bem-vindo ao <?php echo SITE_TITLE; ?></h1>
                <p class="card-text">Portal atualizado para o universo do T-BOT. Faça login, crie sua conta e acompanhe o desempenho de jogadores e guilds diretamente da base do jogo.</p>
                <ul>
                    <li>Login e cadastro conectados à tabela <code>users</code> do T-BOT</li>
                    <li>Rankings baseados nos personagens em <code>characters</code></li>
                    <li>Guilds sincronizadas com <code>guilds</code> e <code>guild_members</code></li>
                </ul>
                <?php if (!is_logged_in()): ?>
                    <a class="btn btn-primary" href="register.php">Criar conta</a>
                    <a class="btn btn-outline-light ms-2" href="login.php">Entrar</a>
                <?php else: ?>
                    <a class="btn btn-primary" href="rankings.php">Ver ranking</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
