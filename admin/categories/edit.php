<?php
session_start();
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../helpers.php';
require_admin();
$pdo = db();
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ?');
$stmt->execute([$id]);
$item = $stmt->fetch();
if (!$item) { http_response_code(404); die('Not found'); }
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim($_POST['name'] ?? '');
    if ($name === '') $err = 'Nama wajib diisi.';
    if (!$err) {
        $stmt = $pdo->prepare('UPDATE categories SET name=? WHERE id=?');
        $stmt->execute([$name, $id]);
        $_SESSION['flash'] = 'Kategori diperbarui.';
        redirect('/admin/categories/index.php');
    }
}
$title = 'Edit Kategori';
include __DIR__ . '/../../partials/header.php';
?>
<h1>Edit Kategori</h1>
<?php if ($err): ?><div class="alert alert-danger"><?= e($err) ?></div><?php endif; ?>
<form method="post">
  <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
  <div class="mb-3">
    <label class="form-label">Nama</label>
    <input type="text" name="name" class="form-control" value="<?= e($_POST['name'] ?? $item['name']) ?>">
  </div>
  <button class="btn btn-primary">Update</button>
  <a href="index.php" class="btn btn-secondary">Batal</a>
</form>
<?php include __DIR__ . '/../../partials/footer.php'; ?>
