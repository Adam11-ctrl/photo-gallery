<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<?php require_once __DIR__ . '/../db.php'; ?>
<?php require_once __DIR__ . '/../helpers.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($title ?? 'Galeri Foto'); ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>/assets/style.css" rel="stylesheet">
</head>
<body>
<?php include __DIR__ . '/navbar.php'; ?>
<div class="container py-4">
<?php if (!empty($_SESSION['flash'])): ?>
  <div class="alert alert-info"><?= e($_SESSION['flash']); unset($_SESSION['flash']); ?></div>
<?php endif; ?>
