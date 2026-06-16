<?php $p = $static_page ?: get_page('home-about'); ?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <?php if ($p['subtitle']): ?><div class="sub reveal"><?= e($p['subtitle']) ?></div><?php endif; ?>
      <h1 class="reveal">O nás</h1>
    </div>
    <div class="prose reveal"><?= $p['body'] ?></div>
    <div class="section-head" style="margin-top:50px;">
      <p class="reveal"><a class="btn btn-accent btn-lg" href="<?= e(url('kontakt')) ?>">Spojte sa s nami</a></p>
    </div>
  </div>
</section>
