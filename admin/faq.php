<?php
require __DIR__ . '/inc/bootstrap.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $do = $_POST['do'] ?? '';
    if ($do === 'add') {
        $q = trim($_POST['question'] ?? '');
        $a = trim($_POST['answer'] ?? '');
        if ($q !== '') {
            $pos = (int)db()->query('SELECT COALESCE(MAX(position),0)+1 FROM faqs')->fetchColumn();
            db()->prepare('INSERT INTO faqs (question, answer, position, published) VALUES (?,?,?,1)')->execute([$q,$a,$pos]);
            flash('Otázka pridaná.');
        }
    } elseif ($do === 'update') {
        $id = (int)$_POST['id'];
        $q  = trim($_POST['question'] ?? '');
        $a  = trim($_POST['answer'] ?? '');
        $pos= (int)($_POST['position'] ?? 0);
        $pub= isset($_POST['published']) ? 1 : 0;
        db()->prepare('UPDATE faqs SET question=?, answer=?, position=?, published=? WHERE id=?')->execute([$q,$a,$pos,$pub,$id]);
        flash('Otázka uložená.');
    } elseif ($do === 'delete') {
        db()->prepare('DELETE FROM faqs WHERE id=?')->execute([(int)$_POST['id']]);
        flash('Otázka zmazaná.');
    }
    redirect('admin/faq.php');
}

$admin_title = 'FAQ';
$faqs = db()->query('SELECT * FROM faqs ORDER BY position, id')->fetchAll();
require __DIR__ . '/inc/header.php';
?>
<h1>Časté otázky (FAQ)</h1>

<div class="panel">
  <h2>Pridať novú otázku</h2>
  <form method="post" class="form-admin">
    <?= csrf_field() ?>
    <input type="hidden" name="do" value="add">
    <label>Otázka</label>
    <input type="text" name="question" required>
    <label>Odpoveď</label>
    <textarea name="answer" rows="3"></textarea>
    <button class="btn-accent" type="submit">Pridať</button>
  </form>
</div>

<div class="panel">
  <h2>Existujúce otázky</h2>
  <?php if (!$faqs): ?>
    <p class="muted">Žiadne otázky.</p>
  <?php else: foreach ($faqs as $f): ?>
    <form method="post" class="faq-edit form-admin">
      <?= csrf_field() ?>
      <input type="hidden" name="do" value="update">
      <input type="hidden" name="id" value="<?= (int)$f['id'] ?>">
      <label>Otázka</label>
      <input type="text" name="question" value="<?= e($f['question']) ?>">
      <label>Odpoveď</label>
      <textarea name="answer" rows="3"><?= e($f['answer']) ?></textarea>
      <div class="row">
        <span><label>Poradie</label><input type="number" name="position" value="<?= (int)$f['position'] ?>" style="width:90px;"></span>
        <label class="checkbox"><input type="checkbox" name="published" <?= $f['published']?'checked':'' ?>> Zobraziť</label>
      </div>
      <div style="margin-top:10px;">
        <button class="btn-sm" type="submit">Uložiť</button>
        <button class="btn-sm danger" type="submit" name="do" value="delete" onclick="return confirm('Zmazať otázku?');">Zmazať</button>
      </div>
    </form>
  <?php endforeach; endif; ?>
</div>
<?php require __DIR__ . '/inc/footer.php'; ?>
