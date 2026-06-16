<?php
require __DIR__ . '/inc/bootstrap.php';
require_login();

$uploadDir = __DIR__ . '/../assets/uploads/';
$action = $_GET['action'] ?? 'list';

/* ---------- Uloženie (nový / úprava) ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['do'] ?? '') === 'save') {
    csrf_verify();
    $id      = (int)($_POST['id'] ?? 0);
    $title   = trim($_POST['title'] ?? '');
    $slug    = trim($_POST['slug'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $body    = $_POST['body'] ?? '';
    $pub     = isset($_POST['published']) ? 1 : 0;

    if ($title === '') { flash('Názov je povinný.', 'err'); redirect('admin/articles.php?action=' . ($id ? 'edit&id=' . $id : 'new')); }
    if ($slug === '') { $slug = slugify($title); } else { $slug = slugify($slug); }

    // Unikátnosť slugu.
    $chk = db()->prepare('SELECT id FROM articles WHERE slug = ? AND id <> ?');
    $chk->execute([$slug, $id]);
    if ($chk->fetch()) { $slug .= '-' . substr(uniqid(), -4); }

    // Obrázok.
    $image = $_POST['existing_image'] ?? '';
    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $f = $_FILES['image'];
        $allowed = ['jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png','webp'=>'image/webp','gif'=>'image/gif'];
        $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
        $finfo = function_exists('finfo_open') ? finfo_file(finfo_open(FILEINFO_MIME_TYPE), $f['tmp_name']) : ($allowed[$ext] ?? '');
        if (isset($allowed[$ext]) && in_array($finfo, $allowed, true) && $f['size'] <= 5*1024*1024) {
            if (!is_dir($uploadDir)) { @mkdir($uploadDir, 0755, true); }
            $newName = $slug . '-' . substr(bin2hex(random_bytes(4)), 0, 8) . '.' . $ext;
            if (move_uploaded_file($f['tmp_name'], $uploadDir . $newName)) { $image = $newName; }
        } else {
            flash('Obrázok musí byť JPG/PNG/WEBP/GIF do 5 MB.', 'err');
        }
    }

    if ($id) {
        $stmt = db()->prepare('UPDATE articles SET title=?, slug=?, excerpt=?, body=?, image=?, published=? WHERE id=?');
        $stmt->execute([$title, $slug, $excerpt, $body, $image, $pub, $id]);
        flash('Článok bol uložený.');
    } else {
        $stmt = db()->prepare('INSERT INTO articles (title, slug, excerpt, body, image, published) VALUES (?,?,?,?,?,?)');
        $stmt->execute([$title, $slug, $excerpt, $body, $image, $pub]);
        flash('Článok bol vytvorený.');
    }
    redirect('admin/articles.php');
}

/* ---------- Zmazanie ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['do'] ?? '') === 'delete') {
    csrf_verify();
    $id = (int)($_POST['id'] ?? 0);
    $row = db()->prepare('SELECT image FROM articles WHERE id=?'); $row->execute([$id]); $r = $row->fetch();
    db()->prepare('DELETE FROM articles WHERE id=?')->execute([$id]);
    if ($r && $r['image'] && is_file($uploadDir . $r['image'])) { @unlink($uploadDir . $r['image']); }
    flash('Článok bol zmazaný.');
    redirect('admin/articles.php');
}

$admin_title = 'Články';
$use_editor = ($action === 'new' || $action === 'edit');

/* ---------- Formulár (nový/úprava) ---------- */
if ($action === 'new' || $action === 'edit') {
    $art = ['id'=>0,'title'=>'','slug'=>'','excerpt'=>'','body'=>'','image'=>'','published'=>1];
    if ($action === 'edit') {
        $s = db()->prepare('SELECT * FROM articles WHERE id=?'); $s->execute([(int)($_GET['id'] ?? 0)]);
        $art = $s->fetch() ?: $art;
    }
    require __DIR__ . '/inc/header.php'; ?>
    <h1><?= $art['id'] ? 'Upraviť článok' : 'Nový článok' ?></h1>
    <form method="post" enctype="multipart/form-data" class="panel form-admin">
      <?= csrf_field() ?>
      <input type="hidden" name="do" value="save">
      <input type="hidden" name="id" value="<?= (int)$art['id'] ?>">
      <input type="hidden" name="existing_image" value="<?= e($art['image']) ?>">

      <label>Názov *</label>
      <input type="text" name="title" value="<?= e($art['title']) ?>" required>

      <label>URL adresa (slug) <span class="muted">– nechaj prázdne pre automatické vygenerovanie</span></label>
      <input type="text" name="slug" value="<?= e($art['slug']) ?>" placeholder="napr. novinky-na-trhu-s-energiami">

      <label>Krátky popis (excerpt)</label>
      <textarea name="excerpt" rows="2"><?= e($art['excerpt']) ?></textarea>

      <label>Obsah článku</label>
      <textarea name="body" class="wysiwyg"><?= e($art['body']) ?></textarea>

      <label>Hlavný obrázok</label>
      <?php if ($art['image']): ?>
        <div class="thumb-preview"><img src="<?= e(asset('uploads/' . $art['image'])) ?>" alt=""></div>
      <?php endif; ?>
      <input type="file" name="image" accept="image/*">

      <label class="checkbox"><input type="checkbox" name="published" <?= $art['published'] ? 'checked' : '' ?>> Publikované</label>

      <div style="margin-top:18px;">
        <button type="submit" class="btn-accent">Uložiť</button>
        <a class="btn-ghost" href="<?= e(url('admin/articles.php')) ?>">Zrušiť</a>
      </div>
    </form>
    <?php
    require __DIR__ . '/inc/footer.php';
    exit;
}

/* ---------- Zoznam ---------- */
$articles = db()->query('SELECT * FROM articles ORDER BY created_at DESC')->fetchAll();
require __DIR__ . '/inc/header.php';
?>
<div class="page-head">
  <h1>Články</h1>
  <a class="btn-accent" href="<?= e(url('admin/articles.php?action=new')) ?>">+ Nový článok</a>
</div>

<div class="panel">
  <?php if (!$articles): ?>
    <p class="muted">Zatiaľ žiadne články.</p>
  <?php else: ?>
  <table class="table">
    <thead><tr><th>Názov</th><th>Stav</th><th>Dátum</th><th></th></tr></thead>
    <tbody>
    <?php foreach ($articles as $a): ?>
      <tr>
        <td><strong><?= e($a['title']) ?></strong><br><span class="muted">/blog/<?= e($a['slug']) ?></span></td>
        <td><?= $a['published'] ? '<span class="badge ok">Publikované</span>' : '<span class="badge">Koncept</span>' ?></td>
        <td class="muted"><?= e(date('d.m.Y', strtotime($a['created_at']))) ?></td>
        <td class="actions">
          <a class="btn-sm" href="<?= e(url('admin/articles.php?action=edit&id=' . $a['id'])) ?>">Upraviť</a>
          <form method="post" onsubmit="return confirm('Naozaj zmazať tento článok?');" style="display:inline;">
            <?= csrf_field() ?>
            <input type="hidden" name="do" value="delete">
            <input type="hidden" name="id" value="<?= (int)$a['id'] ?>">
            <button class="btn-sm danger" type="submit">Zmazať</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>
<?php require __DIR__ . '/inc/footer.php'; ?>
