<?php
require_once __DIR__ . '/includes/auth.php';

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm) {
        $errors[] = 'As senhas não conferem.';
    } else {
        $errors = register_user($username, $email, $password);
    }

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
                <h1 class="card-title h4 mb-3">Criar conta</h1>
                <?php if ($errors): ?>
                    <div class="alert alert-danger"> <?php echo implode('<br>', array_map('htmlspecialchars', $errors)); ?> </div>
                <?php endif; ?>
                <form method="post" class="row g-3">
                    <div class="col-12">
                        <label for="username" class="form-label">Usuário</label>
                        <input type="text" name="username" id="username" class="form-control" required pattern="[A-Za-z0-9_]{4,16}">
                    </div>
                    <div class="col-12">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" name="password" id="password" class="form-control" minlength="8" required>
                    </div>
                    <div class="col-12">
                        <label for="confirm_password" class="form-label">Confirmar senha</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" minlength="8" required>
                    </div>
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <a href="login.php">Já tenho conta</a>
                        <button type="submit" class="btn btn-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
