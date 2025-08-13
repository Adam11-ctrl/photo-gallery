<?php
session_start();
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../helpers.php';
require_admin();

$pdo = db();

// 1. Tentukan jumlah item per halaman
$limit = 5; // jumlah foto per halaman
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// 2. Hitung total data
$total_stmt = $pdo->query('SELECT COUNT(*) FROM photos');
$total_items = $total_stmt->fetchColumn();
$total_pages = ceil($total_items / $limit);

// 3. Ambil data sesuai halaman
$stmt = $pdo->prepare('SELECT p.*, c.name AS category_name, u.name AS user_name 
    FROM photos p
    LEFT JOIN categories c ON c.id = p.category_id
    LEFT JOIN users u ON u.id = p.user_id
    ORDER BY p.created_at DESC
    LIMIT :limit OFFSET :offset');
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$items = $stmt->fetchAll();

$title = 'Foto';
include __DIR__ . '/../../partials/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1>Foto</h1>
  <a href="create.php" class="btn btn-primary">Tambah</a>
</div>

<table class="table table-striped">
  <thead>
    <tr>
      <th>#</th><th>Preview</th><th>Judul</th><th>Kategori</th><th>Pengunggah</th><th>Dibuat</th><th>Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($items as $i): ?>
      <tr>
        <td><?= (int)$i['id'] ?></td>
        <td style="width:120px"><img src="<?= UPLOAD_URI . '/' . e($i['filename']) ?>" class="img-fluid"></td>
        <td><?= e($i['title']) ?></td>
        <td><?= e($i['category_name']) ?></td>
        <td><?= e($i['user_name']) ?></td>
        <td><?= e($i['created_at']) ?></td>
        <td>
          <a class="btn btn-sm btn-warning" href="edit.php?id=<?= $i['id'] ?>">Edit</a>
          <a class="btn btn-sm btn-danger" href="delete.php?id=<?= $i['id'] ?>" onclick="return confirm('Hapus foto ini?')">Hapus</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<!-- 4. Navigasi Paginasi -->
<nav>
  <ul class="pagination">
    <?php if ($page > 1): ?>
      <li class="page-item"><a class="page-link" href="?page=1">&laquo; First</a></li>
      <li class="page-item"><a class="page-link" href="?page=<?= $page - 1 ?>">Prev</a></li>
    <?php endif; ?>

    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
      <li class="page-item <?= $i == $page ? 'active' : '' ?>">
        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>

    <?php if ($page < $total_pages): ?>
      <li class="page-item"><a class="page-link" href="?page=<?= $page + 1 ?>">Next</a></li>
      <li class="page-item"><a class="page-link" href="?page=<?= $total_pages ?>">Last &raquo;</a></li>
    <?php endif; ?>
  </ul>
</nav>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
