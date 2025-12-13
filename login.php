<?php
require_once __DIR__ . '/includes/auth.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $errors = attempt_login($username, $password);
    if (!$errors) {
        header('Location: index.php');
        exit;
    }
}

require_once __DIR__ . '/includes/header.php';
?>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="card-title h4 mb-3">Entrar</h1>
                <?php if ($errors): ?>
                    <div class="alert alert-danger"> <?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?> </div>
                <?php endif; ?>
                <form method="post" class="row g-3">
                    <div class="col-12">
                        <label for="username" class="form-label">Usuário</label>
                        <input type="text" name="username" id="username" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <a href="register.php">Criar conta</a>
                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
