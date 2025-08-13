<?php
session_start();
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../helpers.php';
require_admin();
$pdo = db();
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT * FROM photos WHERE id=?');
$stmt->execute([$id]);
$item = $stmt->fetch();
if (!$item) { http_response_code(404); die('Not found'); }
$cats = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $title = trim($_POST['title'] ?? '');
    $desc = trim($_POST['description'] ?? '');
    $cat  = (int)($_POST['category_id'] ?? 0);
    if ($title === '') $err = 'Judul wajib diisi.';
    $filename = $item['filename'];
    if (!$err && isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp'];
        if (!in_array($ext, $allowed)) $err = 'Format file tidak didukung.';
        if (!$err) {
            $newName = uniqid('img_', true) . '.' . $ext;
            $dest = UPLOAD_DIR . '/' . $newName;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $dest)) {
                // delete old
                @unlink(UPLOAD_DIR . '/' . $filename);
                $filename = $newName;
            } else {
                $err = 'Gagal menyimpan file baru.';
            }
        }
    }
    if (!$err) {
        $stmt = $pdo->prepare('UPDATE photos SET category_id=?, title=?, description=?, filename=? WHERE id=?');
        $stmt->execute([$cat ?: NULL, $title, $desc, $filename, $id]);
        $_SESSION['flash'] = 'Foto diperbarui.';
        redirect('/admin/photos/index.php');
    }
}
$title = 'Edit Foto';
include __DIR__ . '/../../partials/header.php';
?>
<h1>Edit Foto</h1>
<?php if ($err): ?><div class="alert alert-danger"><?= e($err) ?></div><?php endif; ?>
<form method="post" enctype="multipart/form-data">
  <input type="hidden" name="csrf" value="<?= csrf_token() ?>">
  <div class="mb-3">
    <label class="form-label">Judul</label>
    <input type="text" name="title" class="form-control" value="<?= e($_POST['title'] ?? $item['title']) ?>">
  </div>
  <div class="mb-3">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" class="form-control" rows="3"><?= e($_POST['description'] ?? $item['description']) ?></textarea>
  </div>
  <div class="mb-3">
    <label class="form-label">Kategori (opsional)</label>
    <select name="category_id" class="form-select">
      <option value="">-- tanpa kategori --</option>
      <?php foreach ($cats as $c): ?>
        <option value="<?= $c['id'] ?>" <?= ($item['category_id'] == $c['id']) ? 'selected' : '' ?>><?= e($c['name']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label class="form-label">Ganti Foto (opsional)</label>
    <input type="file" name="photo" class="form-control" accept="image/*">
    <div class="mt-2"><img src="<?= UPLOAD_URI . '/' . e($item['filename']) ?>" style="max-width:200px;"></div>
  </div>
  <button class="btn btn-primary">Update</button>
  <a href="index.php" class="btn btn-secondary">Batal</a>
</form>
<?php include __DIR__ . '/../../partials/footer.php'; ?>
