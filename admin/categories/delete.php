<?php
session_start();
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../helpers.php';
require_admin();
$pdo = db();
$id = (int)($_GET['id'] ?? 0);
// Optionally prevent delete if used by photos
$stmt = $pdo->prepare('SELECT COUNT(*) AS c FROM photos WHERE category_id=?');
$stmt->execute([$id]);
if (($stmt->fetch()['c'] ?? 0) > 0) {
    $_SESSION['flash'] = 'Tidak bisa hapus: kategori dipakai foto.';
    redirect('/admin/categories/index.php');
}
$stmt = $pdo->prepare('DELETE FROM categories WHERE id=?');
$stmt->execute([$id]);
$_SESSION['flash'] = 'Kategori dihapus.';
redirect('/admin/categories/index.php');
