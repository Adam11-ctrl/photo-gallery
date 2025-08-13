<?php
session_start();
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

$pdo = db();
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT id, name, email, password_hash, role FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];
        $_SESSION['flash'] = 'Selamat datang, ' . $user['name'] . '!';
        redirect('/public/index.php');
    } else {
        $error = 'Email atau password salah.';
    }
}

$title = 'Login';
include __DIR__ . '/../partials/header.php';
?>
<h1>Login</h1>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post">
  <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="<?= e($_POST['email'] ?? '') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Password</label>
    <input type="password" name="password" class="form-control">
  </div>
  <button class="btn btn-primary">Masuk</button>
  <a href="<?= BASE_URL ?>/auth/register.php" class="btn btn-link">Belum punya akun? Register</a>
</form>
<?php include __DIR__ . '/../partials/footer.php'; ?>
