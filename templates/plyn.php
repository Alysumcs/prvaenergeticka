<?php $p = $static_page ?: get_page('plyn'); ?>
<section class="section">
  <div class="container">
    <div class="section-head">
      <?php if ($p['subtitle']): ?><div class="sub reveal"><?= e($p['subtitle']) ?></div><?php endif; ?>
      <h1 class="reveal"><?= e($p['title']) ?></h1>
    </div>
    <div class="prose reveal" style="margin-bottom:40px;"><?= $p['body'] ?></div>

    <?php foreach (flash() as $f): ?>
      <div class="alert alert-<?= $f['type']==='err'?'err':'ok' ?>" style="max-width:680px;margin:0 auto 16px;"><?= e($f['msg']) ?></div>
    <?php endforeach; ?>

    <form class="form reveal" method="post" action="<?= e(url('submit.php')) ?>">
      <?= csrf_field() ?>
      <input type="hidden" name="form_type" value="plyn">
      <input type="hidden" name="redirect" value="plyn">
      <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off">

      <h3 style="margin-top:0;">Vyplňte formulár a ušetrite na plyne</h3>
      <p class="footer-muted">Polia označené * sú povinné.</p>

      <label>Meno / firma *</label>
      <input type="text" name="name" required>

      <label>E-mail *</label>
      <input type="email" name="email" required>

      <label>Telefón</label>
      <input type="tel" name="phone">

      <label>Odberateľ *</label>
      <select name="odberatel" required>
        <option value="do 633 MWh ročne">do 633 MWh ročne</option>
        <option value="viac ako 633 MWh ročne">viac ako 633 MWh ročne</option>
      </select>

      <label>Spôsob ponuky *</label>
      <select name="sposob_ponuky" required>
        <option>Najvýhodnejšia ponuka</option>
        <option>2 najnižšie</option>
        <option>Všetky ponuky</option>
        <option>Konzultant</option>
      </select>

      <label>Jednotka spotreby</label>
      <select name="jednotka">
        <option>MWh</option>
        <option>m³</option>
      </select>

      <label>Ročná spotreba</label>
      <input type="text" name="spotreba" placeholder="napr. 250">

      <label>Poznámka</label>
      <textarea name="message" placeholder="Zúčtovaciu faktúru môžete poslať e-mailom na info@prvaenergeticka.sk"></textarea>

      <button type="submit" class="btn btn-accent btn-lg" style="margin-top:20px;">Odoslať dopyt</button>
    </form>
  </div>
</section>
