<?php
require_once __DIR__ . '/auth.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_TITLE; ?> - Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #0b1021;
            color: #e4e7f1;
        }
        .navbar, .footer {
            background: #151b3a;
        }
        a, a:hover { color: #8ad4ff; }
        .card { background: #1b2347; border: 1px solid #28305c; }
        .btn-primary { background: #8ad4ff; border: none; color: #0b1021; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><?php echo SITE_TITLE; ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample" aria-controls="navbarsExample" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarsExample">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="rankings.php">Ranking</a></li>
                <li class="nav-item"><a class="nav-link" href="guilds.php">Guilds</a></li>
            </ul>
            <div class="d-flex">
                <?php if (is_logged_in()): ?>
                    <span class="navbar-text me-3">Olá, <?php echo htmlspecialchars(current_user()['username'] ?? ''); ?></span>
                    <a class="btn btn-sm btn-outline-light" href="logout.php">Sair</a>
                <?php else: ?>
                    <a class="btn btn-sm btn-primary me-2" href="login.php">Entrar</a>
                    <a class="btn btn-sm btn-outline-light" href="register.php">Criar conta</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
<div class="container mb-5">
