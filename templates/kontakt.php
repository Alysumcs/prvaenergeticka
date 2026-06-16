<section class="section">
  <div class="container">
    <div class="section-head">
      <div class="sub reveal">Kontakt</div>
      <h1 class="reveal">Spojte sa s nami</h1>
      <p class="lead reveal">Máte otázky alebo potrebujete poradiť? Sme tu pre vás.</p>
    </div>

    <div class="grid grid-2" style="align-items:start;">
      <div class="reveal">
        <h3>Kontaktné a fakturačné informácie</h3>
        <p class="footer-muted" style="line-height:2;">
          <strong><?= e(setting('contact_company','Prvá Energetická s.r.o.')) ?></strong><br>
          <?= e(setting('contact_address','Hattalova 12A, 831 03 Bratislava, Slovensko')) ?><br>
          IČO: <?= e(setting('contact_ico','46 426 639')) ?><br>
          DIČ: <?= e(setting('contact_dic','2023402326')) ?><br>
          E-mail: <a href="mailto:<?= e(setting('contact_email')) ?>" style="color:var(--accent);"><?= e(setting('contact_email','info@prvaenergeticka.sk')) ?></a>
        </p>
      </div>

      <div>
        <?php foreach (flash() as $f): ?>
          <div class="alert alert-<?= $f['type']==='err'?'err':'ok' ?>"><?= e($f['msg']) ?></div>
        <?php endforeach; ?>

        <form class="form reveal" method="post" action="<?= e(url('submit.php')) ?>" style="margin:0;">
          <?= csrf_field() ?>
          <input type="hidden" name="form_type" value="kontakt">
          <input type="hidden" name="redirect" value="kontakt">
          <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off">

          <label>Meno / firma *</label>
          <input type="text" name="name" required>

          <label>E-mail *</label>
          <input type="email" name="email" required>

          <label>Telefón</label>
          <input type="tel" name="phone">

          <label>Správa *</label>
          <textarea name="message" required></textarea>

          <button type="submit" class="btn btn-accent btn-lg" style="margin-top:18px;">Odoslať</button>
        </form>
      </div>
    </div>
  </div>
</section>
