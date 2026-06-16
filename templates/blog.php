<?php
$articles = db()->query('SELECT * FROM articles WHERE published = 1 ORDER BY created_at DESC')->fetchAll();
?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <div class="sub reveal">Blog</div>
      <h1 class="reveal">Novinky zo sveta energetiky</h1>
    </div>

    <?php if (!$articles): ?>
      <p class="lead reveal" style="text-align:center;">Zatiaľ tu nie sú žiadne články. Čoskoro pridáme prvé novinky.</p>
    <?php else: ?>
    <div class="article-grid">
      <?php foreach ($articles as $a): ?>
      <a class="article-card reveal" href="<?= e(url('blog/' . $a['slug'])) ?>">
        <?php if ($a['image']): ?>
          <div class="thumb"><img src="<?= e(asset('uploads/' . $a['image'])) ?>" alt="<?= e($a['title']) ?>"></div>
        <?php endif; ?>
        <div class="body">
          <span class="date"><?= e(date('d.m.Y', strtotime($a['created_at']))) ?></span>
          <h3><?= e($a['title']) ?></h3>
          <p class="footer-muted"><?= e($a['excerpt']) ?></p>
          <span class="more">Čítať viac →</span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>
