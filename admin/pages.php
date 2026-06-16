<?php
require __DIR__ . '/inc/bootstrap.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $do = $_POST['do'] ?? '';
    if ($do === 'settings') {
        $keys = ['site_title','contact_email','contact_company','contact_address','contact_ico','contact_dic',
                 'facebook_url','linkedin_url','hero_title','hero_highlight','hero_subtitle'];
        foreach ($keys as $k) {
            if (isset($_POST[$k])) { set_setting($k, trim($_POST[$k])); }
        }
        flash('Nastavenia uložené.');
        redirect('admin/pages.php');
    } elseif ($do === 'page') {
        $slug = $_POST['slug'] ?? '';
        $stmt = db()->prepare('UPDATE pages SET title=?, subtitle=?, body=?, meta_description=? WHERE slug=?');
        $stmt->execute([
            trim($_POST['title'] ?? ''),
            trim($_POST['subtitle'] ?? ''),
            $_POST['body'] ?? '',
            trim($_POST['meta_description'] ?? ''),
            $slug
        ]);
        flash('Stránka uložená.');
        redirect('admin/pages.php?edit=' . urlencode($slug));
    }
}

$admin_title = 'Texty stránok';
$editSlug = $_GET['edit'] ?? '';

$pageLabels = [
    'home-why'   => 'Domov – sekcia „Prečo Prvá Energetická"',
    'home-about' => 'Domov / O nás – text',
    'sluzby'     => 'Služby',
    'plyn'       => 'Plyn',
    'elektrina'  => 'Elektrina',
    'zasady-ochrany-osobnych-udajov' => 'Zásady ochrany osobných údajov (GDPR)',
    'cookies'    => 'Zásady cookies',
];

if ($editSlug && isset($pageLabels[$editSlug])) {
    $use_editor = true;
    $p = get_page($editSlug);
    require __DIR__ . '/inc/header.php'; ?>
    <h1>Upraviť: <?= e($pageLabels[$editSlug]) ?></h1>
    <form method="post" class="panel form-admin">
      <?= csrf_field() ?>
      <input type="hidden" name="do" value="page">
      <input type="hidden" name="slug" value="<?= e($editSlug) ?>">
      <label>Nadpis</label>
      <input type="text" name="title" value="<?= e($p['title']) ?>">
      <label>Podnadpis</label>
      <input type="text" name="subtitle" value="<?= e($p['subtitle']) ?>">
      <label>Obsah</label>
      <textarea name="body" class="wysiwyg"><?= e($p['body']) ?></textarea>
      <label>Meta popis (SEO) <span class="muted">– krátky popis pre Google</span></label>
      <input type="text" name="meta_description" value="<?= e($p['meta_description']) ?>" maxlength="300">
      <div style="margin-top:18px;">
        <button class="btn-accent" type="submit">Uložiť</button>
        <a class="btn-ghost" href="<?= e(url('admin/pages.php')) ?>">Späť</a>
      </div>
    </form>
    <?php require __DIR__ . '/inc/footer.php'; exit;
}

require __DIR__ . '/inc/header.php';
?>
<h1>Texty stránok a nastavenia</h1>

<div class="panel">
  <h2>Hlavné nastavenia</h2>
  <form method="post" class="form-admin">
    <?= csrf_field() ?>
    <input type="hidden" name="do" value="settings">
    <div class="row2">
      <span><label>Názov webu</label><input type="text" name="site_title" value="<?= e(setting('site_title')) ?>"></span>
      <span><label>Kontaktný e-mail</label><input type="text" name="contact_email" value="<?= e(setting('contact_email')) ?>"></span>
    </div>
    <div class="row2">
      <span><label>Firma</label><input type="text" name="contact_company" value="<?= e(setting('contact_company')) ?>"></span>
      <span><label>Adresa</label><input type="text" name="contact_address" value="<?= e(setting('contact_address')) ?>"></span>
    </div>
    <div class="row2">
      <span><label>IČO</label><input type="text" name="contact_ico" value="<?= e(setting('contact_ico')) ?>"></span>
      <span><label>DIČ</label><input type="text" name="contact_dic" value="<?= e(setting('contact_dic')) ?>"></span>
    </div>
    <div class="row2">
      <span><label>Facebook URL</label><input type="text" name="facebook_url" value="<?= e(setting('facebook_url')) ?>"></span>
      <span><label>LinkedIn URL</label><input type="text" name="linkedin_url" value="<?= e(setting('linkedin_url')) ?>"></span>
    </div>
    <hr>
    <h3>Úvodný banner (hero)</h3>
    <label>Hlavný nadpis</label>
    <input type="text" name="hero_title" value="<?= e(setting('hero_title')) ?>">
    <label>Zvýraznený podtext</label>
    <input type="text" name="hero_highlight" value="<?= e(setting('hero_highlight')) ?>">
    <label>Podnadpis</label>
    <input type="text" name="hero_subtitle" value="<?= e(setting('hero_subtitle')) ?>">
    <div style="margin-top:16px;"><button class="btn-accent" type="submit">Uložiť nastavenia</button></div>
  </form>
</div>

<div class="panel">
  <h2>Texty podstránok</h2>
  <table class="table">
    <tbody>
    <?php foreach ($pageLabels as $slug => $label): ?>
      <tr>
        <td><strong><?= e($label) ?></strong></td>
        <td class="actions"><a class="btn-sm" href="<?= e(url('admin/pages.php?edit=' . urlencode($slug))) ?>">Upraviť</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php require __DIR__ . '/inc/footer.php'; ?>
