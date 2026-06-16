<?php
$why   = get_page('home-why');
$about = get_page('home-about');
$faqs  = db()->query('SELECT * FROM faqs WHERE published = 1 ORDER BY position, id LIMIT 6')->fetchAll();
$latest = db()->query('SELECT * FROM articles WHERE published = 1 ORDER BY created_at DESC LIMIT 3')->fetchAll();
$partners = ['MAGNA','MET','Energetické služby','ELGAS','MVM Slovakia','GAS Energy'];
?>

<!-- HERO -->
<section class="hero">
  <div class="container hero-inner">
    <div class="eyebrow reveal">Prvá Energetická</div>
    <h1 class="reveal"><?= e(setting('hero_title','SPOĽAHLIVÁ ENERGIA')) ?>
      <span><?= e(setting('hero_highlight','pre váš biznis, vašu obec, bytový dom')) ?></span></h1>
    <p class="reveal"><?= e(setting('hero_subtitle','Analýza trhu, odborné poradenstvo a stabilná dodávka energií na jednom mieste.')) ?></p>
    <div class="reveal">
      <a class="btn btn-accent btn-lg" href="<?= e(url('kontakt')) ?>">Konzultácia</a>
      <a class="btn btn-ghost btn-lg" href="<?= e(url('sluzby')) ?>">Naše služby</a>
    </div>
  </div>
</section>

<!-- PREČO -->
<section class="section section-alt" id="preco">
  <div class="container">
    <div class="section-head">
      <h2 class="reveal"><?= e($why['title'] ?: 'Prečo Prvá Energetická?') ?></h2>
    </div>
    <div class="prose reveal"><?= $why['body'] ?></div>
  </div>
</section>

<!-- O NÁS -->
<section class="section" id="o-nas">
  <div class="container">
    <div class="section-head">
      <div class="sub reveal"><?= e($about['subtitle']) ?></div>
      <h2 class="reveal"><?= e($about['title'] ?: 'O nás') ?></h2>
    </div>
    <div class="prose reveal"><?= $about['body'] ?></div>
  </div>
</section>

<!-- PARTNERI -->
<section class="section-sm section-alt">
  <div class="container">
    <p class="section-head reveal" style="margin-bottom:30px;">Spolupracujeme s poprednými dodávateľmi energií, aby sme vám zabezpečili výhodné podmienky a spoľahlivý servis.</p>
    <div class="partners reveal">
      <?php foreach ($partners as $p): ?>
        <span class="card" style="padding:14px 22px;font-weight:700;letter-spacing:1px;"><?= e($p) ?></span>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- UŠETRITE -->
<section class="section" id="usetrite">
  <div class="container">
    <div class="section-head">
      <h2 class="reveal">Ušetrite na energiách</h2>
      <p class="lead reveal">Naše služby sú určené pre právnické osoby, verejnú správu a štátny sektor. Pre lacnejšiu elektrinu alebo plyn nás kontaktujte – ponuky sú presné a individuálne, bez kalkulačiek a nereálnych sľubov.</p>
    </div>
    <div class="product-cards">
      <a class="product-card reveal" href="<?= e(url('plyn')) ?>">
        <h3>Lacnejší plyn</h3>
        <p>Znížte svoje náklady za dodávky plynu.</p>
        <span class="btn btn-accent" style="margin-top:14px;">Zistiť viac</span>
      </a>
      <a class="product-card reveal" href="<?= e(url('elektrina')) ?>">
        <h3>Lacnejšia elektrina</h3>
        <p>Ušetrite s novým dodávateľom elektriny.</p>
        <span class="btn btn-accent" style="margin-top:14px;">Zistiť viac</span>
      </a>
    </div>
  </div>
</section>

<!-- FAQ náhľad -->
<?php if ($faqs): ?>
<section class="section section-alt" id="faq">
  <div class="container">
    <div class="section-head">
      <h2 class="reveal">Časté otázky</h2>
      <p class="lead reveal">Nájdite odpovede na často kladené otázky o našich službách.</p>
    </div>
    <div class="prose">
      <?php foreach ($faqs as $f): ?>
      <div class="faq-item reveal">
        <button class="faq-q"><?= e($f['question']) ?><span class="chev">▾</span></button>
        <div class="faq-a"><p><?= nl2br(e($f['answer'])) ?></p></div>
      </div>
      <?php endforeach; ?>
    </div>
    <p style="text-align:center;margin-top:24px;" class="reveal"><a class="btn btn-ghost" href="<?= e(url('faq')) ?>">Všetky otázky</a></p>
  </div>
</section>
<?php endif; ?>

<!-- BLOG náhľad -->
<?php if ($latest): ?>
<section class="section" id="blog">
  <div class="container">
    <div class="section-head"><h2 class="reveal">Z nášho blogu</h2></div>
    <div class="article-grid">
      <?php foreach ($latest as $a): ?>
      <a class="article-card reveal" href="<?= e(url('blog/' . $a['slug'])) ?>">
        <?php if ($a['image']): ?><div class="thumb"><img src="<?= e(asset('uploads/' . $a['image'])) ?>" alt="<?= e($a['title']) ?>"></div><?php endif; ?>
        <div class="body">
          <span class="date"><?= e(date('d.m.Y', strtotime($a['created_at']))) ?></span>
          <h3><?= e($a['title']) ?></h3>
          <p class="footer-muted"><?= e($a['excerpt']) ?></p>
          <span class="more">Čítať viac →</span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- CTA -->
<section class="section section-alt">
  <div class="container section-head">
    <h2 class="reveal">Spojte sa s nami</h2>
    <p class="lead reveal">Máte otázky alebo potrebujete poradiť? Sme tu pre vás.</p>
    <p class="reveal"><a class="btn btn-accent btn-lg" href="<?= e(url('kontakt')) ?>">Kontaktovať</a></p>
  </div>
</section>
