<?php
session_start();
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../helpers.php';
require_admin();
$pdo = db();
$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare('SELECT filename FROM photos WHERE id=?');
$stmt->execute([$id]);
$file = $stmt->fetch();
$stmt = $pdo->prepare('DELETE FROM photos WHERE id=?');
$stmt->execute([$id]);
if ($file && !empty($file['filename'])) {
    @unlink(UPLOAD_DIR . '/' . $file['filename']);
}
$_SESSION['flash'] = 'Foto dihapus.';
redirect('/admin/photos/index.php');
