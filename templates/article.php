<?php /** @var array $article */ ?>
<article class="section">
  <div class="container article-single">
    <a href="<?= e(url('blog')) ?>" class="reveal" style="color:var(--muted);">← Späť na blog</a>
    <div class="section-head" style="text-align:left;margin:20px 0 0;max-width:none;">
      <span class="date reveal" style="color:var(--accent);font-weight:700;letter-spacing:1px;text-transform:uppercase;font-size:.85rem;">
        <?= e(date('d.m.Y', strtotime($article['created_at']))) ?>
      </span>
      <h1 class="reveal" style="text-transform:none;"><?= e($article['title']) ?></h1>
    </div>
    <?php if ($article['image']): ?>
      <img class="cover reveal" src="<?= e(asset('uploads/' . $article['image'])) ?>" alt="<?= e($article['title']) ?>">
    <?php endif; ?>
    <div class="prose reveal" style="margin-top:24px;"><?= $article['body'] ?></div>

    <div style="margin-top:50px;" class="reveal">
      <a class="btn btn-accent" href="<?= e(url('kontakt')) ?>">Máte otázku? Kontaktujte nás</a>
    </div>
  </div>
</article>
