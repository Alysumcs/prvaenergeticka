<?php
require_login();
$current = basename($_SERVER['PHP_SELF']);
function admin_active($f, $c){ return $f === $c ? ' class="active"' : ''; }
?>
<!DOCTYPE html>
<html lang="sk">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($admin_title ?? 'Administrácia') ?> – Prvá Energetická</title>
<link rel="stylesheet" href="<?= e(asset('css/admin.css')) ?>">
</head>
<body class="admin">
<header class="admin-top">
  <div class="admin-top-inner">
    <strong>⚡ Prvá Energetická</strong>
    <div class="admin-top-right">
      <a href="<?= e(url('')) ?>" target="_blank">Zobraziť web ↗</a>
      <span class="muted">·</span>
      <span class="muted"><?= e(current_user()['username']) ?></span>
      <a href="<?= e(url('admin/logout.php')) ?>" class="logout">Odhlásiť</a>
    </div>
  </div>
</header>
<div class="admin-wrap">
  <nav class="admin-nav">
    <a href="<?= e(url('admin/index.php')) ?>"<?= admin_active('index.php',$current) ?>>📊 Prehľad</a>
    <a href="<?= e(url('admin/articles.php')) ?>"<?= admin_active('articles.php',$current) ?>>📝 Články</a>
    <a href="<?= e(url('admin/faq.php')) ?>"<?= admin_active('faq.php',$current) ?>>❓ FAQ</a>
    <a href="<?= e(url('admin/pages.php')) ?>"<?= admin_active('pages.php',$current) ?>>📄 Texty stránok</a>
    <a href="<?= e(url('admin/messages.php')) ?>"<?= admin_active('messages.php',$current) ?>>✉️ Správy</a>
  </nav>
  <main class="admin-main">
    <?php foreach (flash() as $f): ?>
      <div class="alert alert-<?= $f['type']==='err'?'err':'ok' ?>"><?= e($f['msg']) ?></div>
    <?php endforeach; ?>
