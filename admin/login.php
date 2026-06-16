<?php
require __DIR__ . '/inc/bootstrap.php';

if (current_user()) { redirect('admin/index.php'); }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    try {
        $stmt = db()->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
        $stmt->execute([$u]);
        $row = $stmt->fetch();
        if ($row && password_verify($p, $row['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = ['id' => $row['id'], 'username' => $row['username']];
            redirect('admin/index.php');
        } else {
            $error = 'Nesprávne meno alebo heslo.';
        }
    } catch (Exception $e) {
        $error = 'Chyba databázy. Skontrolujte inštaláciu.';
    }
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Prihlásenie – Administrácia</title>
<link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body class="login-page">
<form class="login-box" method="post">
  <h1>Administrácia</h1>
  <p class="muted">Prvá Energetická</p>
  <?php if ($error): ?><div class="alert alert-err"><?= e($error) ?></div><?php endif; ?>
  <?= csrf_field() ?>
  <label>Používateľské meno</label>
  <input type="text" name="username" required autofocus>
  <label>Heslo</label>
  <input type="password" name="password" required>
  <button type="submit" class="btn-accent">Prihlásiť sa</button>
  <p class="muted" style="margin-top:18px;"><a href="<?= e(url('')) ?>">← Späť na web</a></p>
</form>
</body>
</html>
