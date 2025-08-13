<?php
session_start();
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

$pdo = db();

// Fetch categories
$cats = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();

// Fetch photos with optional category filter and search
$where = '1=1';
$params = [];
if (!empty($_GET['category'])) {
    $where .= ' AND p.category_id = :cat';
    $params[':cat'] = (int)$_GET['category'];
}
if (!empty($_GET['q'])) {
    $where .= ' AND (p.title LIKE :q OR p.description LIKE :q)';
    $params[':q'] = '%' . $_GET['q'] . '%';
}

$stmt = $pdo->prepare("
    SELECT p.*, c.name AS category_name, u.name AS user_name
    FROM photos p
    LEFT JOIN categories c ON c.id = p.category_id
    LEFT JOIN users u ON u.id = p.user_id
    WHERE $where
    ORDER BY p.created_at DESC
");
$stmt->execute($params);
$photos = $stmt->fetchAll();
$title = 'Beranda Galeri';
include __DIR__ . '/../partials/header.php';
?>
<h1 class="mb-3">Galeri Foto</h1>

<form class="row g-2 mb-4" method="get">
  <div class="col-md-4">
    <select class="form-select" name="category">
      <option value="">Semua Kategori</option>
      <?php foreach ($cats as $c): ?>
        <option value="<?= $c['id'] ?>" <?= (($_GET['category'] ?? '') == $c['id']) ? 'selected' : '' ?>>
          <?= e($c['name']) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-5">
    <input type="text" name="q" class="form-control" placeholder="Cari judul/deskripsi..." value="<?= e($_GET['q'] ?? '') ?>">
  </div>
  <div class="col-md-3 d-grid">
    <button class="btn btn-primary">Filter</button>
  </div>
</form>

<?php if (empty($photos)): ?>
  <div class="alert alert-secondary">Belum ada foto.</div>
<?php else: ?>
  <div class="row g-3">
    <?php foreach ($photos as $p): ?>
      <div class="col-md-3">
        <div class="card h-100">
          <img src="<?= UPLOAD_URI . '/' . e($p['filename']) ?>" class="card-img-top" alt="<?= e($p['title']) ?>">
          <div class="card-body">
            <h5 class="card-title"><?= e($p['title']) ?></h5>
            <p class="card-text small text-muted mb-1"><?= e($p['category_name'] ?? 'Tanpa Kategori') ?></p>
            <p class="card-text"><?= e($p['description']) ?></p>
          </div>
          <div class="card-footer">
            <small class="text-muted">Diunggah oleh <?= e($p['user_name'] ?? 'Unknown') ?> • <?= e($p['created_at']) ?></small>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php include __DIR__ . '/../partials/footer.php'; ?>
