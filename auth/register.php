<?php
session_start();
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

$pdo = db();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $errors[] = 'Semua field wajib diisi.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email tidak valid.';
    }
    if ($password !== $confirm) {
        $errors[] = 'Konfirmasi password tidak cocok.';
    }
    // Check existing email
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $errors[] = 'Email sudah terdaftar.';
    }

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO users (name, email, password_hash, role, created_at) VALUES (?, ?, ?, "user", NOW())');
        $stmt->execute([$name, $email, $hash]);
        $_SESSION['flash'] = 'Registrasi berhasil. Silakan login.';
        redirect('/auth/login.php');
    }
}

$title = 'Register';
include __DIR__ . '/../partials/header.php';
?>
<h1>Register</h1>
<?php foreach ($errors as $e): ?>
  <div class="alert alert-danger"><?= e($e) ?></div>
<?php endforeach; ?>
<form method="post">
  <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
  <div class="mb-3">
    <label class="form-label">Nama</label>
    <input type="text" name="name" class="form-control" value="<?= e($_POST['name'] ?? '') ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Email</label>
    <input type="email" name="email" class="form-control" value="<?= e($_POST['email'] ?? '') ?>">
  </div>
  <div class="row">
    <div class="col-md-6 mb-3">
      <label class="form-label">Password</label>
      <input type="password" name="password" class="form-control">
    </div>
    <div class="col-md-6 mb-3">
      <label class="form-label">Konfirmasi Password</label>
      <input type="password" name="confirm" class="form-control">
    </div>
  </div>
  <button class="btn btn-primary">Daftar</button>
  <a href="<?= BASE_URL ?>/auth/login.php" class="btn btn-link">Sudah punya akun? Login</a>
</form>
<?php include __DIR__ . '/../partials/footer.php'; ?>
