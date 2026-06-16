<?php
require __DIR__ . '/inc/bootstrap.php';
require_login();
$admin_title = 'Prehľad';
$cArticles = db()->query('SELECT COUNT(*) FROM articles')->fetchColumn();
$cFaqs     = db()->query('SELECT COUNT(*) FROM faqs')->fetchColumn();
$cMsgUnread= db()->query('SELECT COUNT(*) FROM messages WHERE is_read = 0')->fetchColumn();
$cMsg      = db()->query('SELECT COUNT(*) FROM messages')->fetchColumn();
require __DIR__ . '/inc/header.php';
?>
<h1>Prehľad</h1>
<div class="cards">
  <a class="stat" href="<?= e(url('admin/articles.php')) ?>"><span class="num"><?= (int)$cArticles ?></span><span>Články</span></a>
  <a class="stat" href="<?= e(url('admin/faq.php')) ?>"><span class="num"><?= (int)$cFaqs ?></span><span>FAQ otázky</span></a>
  <a class="stat" href="<?= e(url('admin/messages.php')) ?>"><span class="num"><?= (int)$cMsgUnread ?> / <?= (int)$cMsg ?></span><span>Neprečítané správy</span></a>
</div>

<div class="panel">
  <h2>Rýchle akcie</h2>
  <p>
    <a class="btn-accent" href="<?= e(url('admin/articles.php?action=new')) ?>">+ Nový článok</a>
    <a class="btn-ghost" href="<?= e(url('admin/faq.php')) ?>">Upraviť FAQ</a>
    <a class="btn-ghost" href="<?= e(url('admin/pages.php')) ?>">Upraviť texty stránok</a>
  </p>
  <p class="muted" style="margin-top:18px;">Tip: po prihlásení si zmeňte heslo a uistite sa, že súbor <code>install.php</code> je zmazaný zo servera.</p>
</div>
<?php require __DIR__ . '/inc/footer.php'; ?>
