<?php
session_start();
require_once __DIR__ . '/config.php';

$message = '';
$type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $re_password = $_POST['re_password'] ?? '';
    $last_ip = $_SERVER['REMOTE_ADDR'] ?? null;

    if ($username === '' || $email === '' || $password === '' || $re_password === '') {
        $type = 'error';
        $message = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $type = 'error';
        $message = 'Invalid email format.';
    } elseif ($password !== $re_password) {
        $type = 'error';
        $message = 'Passwords do not match.';
    } else {
        $check = $conn->prepare('SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1');
        $check->bind_param('ss', $username, $email);
        $check->execute();
        $exists = $check->get_result()->fetch_assoc();
        $check->close();

        if ($exists) {
            $type = 'error';
            $message = 'Username or email already exists.';
        } else {
            $hashed_password = password_hash($password, PASSWORD_ARGON2I);
            $stmt = $conn->prepare('INSERT INTO users (username, password, email, last_ip) VALUES (?, ?, ?, ?)');
            $stmt->bind_param('ssss', $username, $hashed_password, $email, $last_ip);
            $stmt->execute();
            $stmt->close();

            $type = 'success';
            $message = 'Registration successful! You can now login on the main page.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastro - TBOT</title>
  <style>
    body{font-family:Arial,sans-serif;background:#111;color:#eee;margin:0;padding:30px}
    .box{max-width:520px;margin:0 auto;background:#1a1a1a;padding:20px;border-radius:8px}
    input{width:100%;padding:10px;margin:8px 0;border:1px solid #333;background:#222;color:#fff}
    button{padding:10px 16px;background:#f68122;border:0;color:#fff;cursor:pointer}
    .error{color:#ff6f6f}.success{color:#7dff99}a{color:#f6b06f}
  </style>
</head>
<body>
  <div class="box">
    <h1>Registro TBOT</h1>
    <?php if ($message): ?>
      <p class="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>
    <form method="post" action="register.php" autocomplete="off">
      <input type="text" name="username" placeholder="Username" required>
      <input type="email" name="email" placeholder="Email" required>
      <input type="password" name="password" placeholder="Password" required>
      <input type="password" name="re_password" placeholder="Repeat Password" required>
      <button type="submit">Registrar</button>
    </form>
    <p><a href="index.php">← Voltar para página principal</a></p>
  </div>
</body>
</html>
