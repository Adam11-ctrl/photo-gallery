<?php
session_start();
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../helpers.php';
require_admin();
$pdo = db();
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    if ($name === '') $err = 'Nama wajib diisi.';
    if (!$err) {
        $stmt = $pdo->prepare('INSERT INTO categories (name, created_at) VALUES (?, NOW())');
        $stmt->execute([$name]);
        $_SESSION['flash'] = 'Kategori ditambahkan.';
        redirect('/admin/categories/index.php');
    }
}
$title = 'Tambah Kategori';
include __DIR__ . '/../../partials/header.php';
?>
<h1>Tambah Kategori</h1>
<?php if ($err): ?><div class="alert alert-danger"><?= e($err) ?></div><?php endif; ?>
<form method="post">
  <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
  <div class="mb-3">
    <label class="form-label">Nama</label>
    <input type="text" name="name" class="form-control" value="<?= e($_POST['name'] ?? '') ?>">
  </div>
  <button class="btn btn-primary">Simpan</button>
  <a href="index.php" class="btn btn-secondary">Batal</a>
</form>
<?php include __DIR__ . '/../../partials/footer.php'; ?>
