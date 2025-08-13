<?php
session_start();
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../helpers.php';
require_admin();
$pdo = db();
$items = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$title = 'Kategori';
include __DIR__ . '/../../partials/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>Kategori</h1>
  <a href="create.php" class="btn btn-primary">Tambah</a>
</div>
<table class="table table-striped">
  <thead><tr><th>#</th><th>Nama</th><th>Dibuat</th><th>Aksi</th></tr></thead>
  <tbody>
    <?php foreach ($items as $i): ?>
      <tr>
        <td><?= (int)$i['id'] ?></td>
        <td><?= e($i['name']) ?></td>
        <td><?= e($i['created_at']) ?></td>
        <td>
          <a class="btn btn-sm btn-warning" href="edit.php?id=<?= $i['id'] ?>">Edit</a>
          <a class="btn btn-sm btn-danger" href="delete.php?id=<?= $i['id'] ?>" onclick="return confirm('Hapus kategori ini?')">Hapus</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php include __DIR__ . '/../../partials/footer.php'; ?>
