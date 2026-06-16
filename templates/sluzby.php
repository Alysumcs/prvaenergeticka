<?php $p = $static_page ?: get_page('sluzby'); ?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <?php if ($p['subtitle']): ?><div class="sub reveal"><?= e($p['subtitle']) ?></div><?php endif; ?>
      <h1 class="reveal"><?= e($p['title']) ?></h1>
    </div>
    <div class="prose reveal"><?= $p['body'] ?></div>
    <div class="section-head" style="margin-top:50px;">
      <h2 class="reveal">Máte na nás otázku?</h2>
      <p class="lead reveal">Neváhajte nás kontaktovať! Radi vám poskytneme odborné poradenstvo.</p>
      <p class="reveal"><a class="btn btn-accent btn-lg" href="<?= e(url('kontakt')) ?>">Kontakt</a></p>
    </div>
  </div>
</section>
