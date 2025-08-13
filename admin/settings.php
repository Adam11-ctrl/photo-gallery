<?php
session_start();
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';
require_admin();

$title = 'Pengaturan';
include __DIR__ . '/../partials/header.php';
?>

<h1>Pengaturan Website</h1>
<form method="post" action="">
  <div class="mb-3">
    <label for="site_name" class="form-label">Nama Website</label>
    <input type="text" class="form-control" id="site_name" name="site_name" value="">
  </div>
  <div class="mb-3">
    <label for="site_description" class="form-label">Deskripsi</label>
    <textarea class="form-control" id="site_description" name="site_description"></textarea>
  </div>
  <button type="submit" class="btn btn-primary">Simpan</button>
</form>

<?php include __DIR__ . '/../partials/footer.php'; ?>
