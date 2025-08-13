<?php
session_start();
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';
require_admin();

$pdo = db();
$counts = [
    'users' => $pdo->query('SELECT COUNT(*) AS c FROM users')->fetch()['c'] ?? 0,
    'categories' => $pdo->query('SELECT COUNT(*) AS c FROM categories')->fetch()['c'] ?? 0,
    'photos' => $pdo->query('SELECT COUNT(*) AS c FROM photos')->fetch()['c'] ?? 0,
];
$title = 'Admin Dashboard';
include __DIR__ . '/../partials/header.php';
?>
<h1 class="mb-4">Admin Dashboard</h1>
<div class="row g-3">
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Users</h5>
        <p class="display-6"><?= (int)$counts['users'] ?></p>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Kategori</h5>
        <p class="display-6"><?= (int)$counts['categories'] ?></p>
        <a href="<?= BASE_URL ?>/admin/categories/index.php" class="btn btn-sm btn-primary">Kelola</a>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Foto</h5>
        <p class="display-6"><?= (int)$counts['photos'] ?></p>
        <a href="<?= BASE_URL ?>/admin/photos/index.php" class="btn btn-sm btn-primary">Kelola</a>
      </div>
    </div>
  </div>

  <div class="col-md-4">
  <div class="card">
    <div class="card-body">
      <h5 class="card-title">Settings</h5>
      <p class="display-6">
        <i class="bi bi-gear"></i> <!-- ikon opsional -->
      </p>
      <a href="<?= BASE_URL ?>/admin/settings.php" class="btn btn-sm btn-primary">Kelola</a>
    </div>
  </div>
</div>

</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
