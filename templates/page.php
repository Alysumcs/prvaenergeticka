<?php $p = $static_page ?: ['title'=>'Stránka','subtitle'=>'','body'=>'']; ?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <?php if ($p['subtitle']): ?><div class="sub reveal"><?= e($p['subtitle']) ?></div><?php endif; ?>
      <h1 class="reveal" style="text-transform:none;"><?= e($p['title']) ?></h1>
    </div>
    <div class="prose reveal"><?= $p['body'] ?></div>
  </div>
</section>
