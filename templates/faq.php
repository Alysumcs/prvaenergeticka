<?php
$faqs = db()->query('SELECT * FROM faqs WHERE published = 1 ORDER BY position, id')->fetchAll();
?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <div class="sub reveal">FAQ</div>
      <h1 class="reveal">Časté otázky</h1>
      <p class="lead reveal">Nájdite odpovede na často kladené otázky o našich produktoch a službách.</p>
    </div>

    <div class="prose">
      <?php if (!$faqs): ?>
        <p class="reveal">Zatiaľ tu nie sú žiadne otázky.</p>
      <?php else: foreach ($faqs as $f): ?>
        <div class="faq-item reveal">
          <button class="faq-q"><?= e($f['question']) ?><span class="chev">▾</span></button>
          <div class="faq-a"><p><?= nl2br(e($f['answer'])) ?></p></div>
        </div>
      <?php endforeach; endif; ?>
    </div>

    <div class="section-head" style="margin-top:50px;">
      <p class="reveal"><a class="btn btn-accent btn-lg" href="<?= e(url('kontakt')) ?>">Nenašli ste odpoveď? Napíšte nám</a></p>
    </div>
  </div>
</section>
