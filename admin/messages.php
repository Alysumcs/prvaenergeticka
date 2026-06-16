<?php
require __DIR__ . '/inc/bootstrap.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify();
    $do = $_POST['do'] ?? '';
    $id = (int)($_POST['id'] ?? 0);
    if ($do === 'read')   { db()->prepare('UPDATE messages SET is_read=1 WHERE id=?')->execute([$id]); }
    if ($do === 'unread') { db()->prepare('UPDATE messages SET is_read=0 WHERE id=?')->execute([$id]); }
    if ($do === 'delete') { db()->prepare('DELETE FROM messages WHERE id=?')->execute([$id]); flash('Správa zmazaná.'); }
    redirect('admin/messages.php');
}

$admin_title = 'Správy';
$messages = db()->query('SELECT * FROM messages ORDER BY created_at DESC')->fetchAll();
require __DIR__ . '/inc/header.php';
?>
<h1>Prijaté správy</h1>

<div class="panel">
  <?php if (!$messages): ?>
    <p class="muted">Zatiaľ žiadne správy.</p>
  <?php else: foreach ($messages as $m):
    $meta = json_decode($m['meta'] ?? '', true) ?: [];
  ?>
    <div class="msg-card <?= $m['is_read'] ? '' : 'unread' ?>">
      <div class="msg-head">
        <div>
          <strong><?= e($m['subject']) ?></strong>
          <?php if (!$m['is_read']): ?><span class="badge ok">nové</span><?php endif; ?>
          <br>
          <span class="muted"><?= e($m['name']) ?> · <a href="mailto:<?= e($m['email']) ?>"><?= e($m['email']) ?></a><?= $m['phone'] ? ' · ' . e($m['phone']) : '' ?></span>
        </div>
        <span class="muted"><?= e(date('d.m.Y H:i', strtotime($m['created_at']))) ?></span>
      </div>
      <?php if ($m['body']): ?><p><?= nl2br(e($m['body'])) ?></p><?php endif; ?>
      <?php if ($meta): ?>
        <ul class="msg-meta">
          <?php foreach ($meta as $k => $v): ?><li><strong><?= e($k) ?>:</strong> <?= e($v) ?></li><?php endforeach; ?>
        </ul>
      <?php endif; ?>
      <div class="actions">
        <form method="post" style="display:inline;"><?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
          <button class="btn-sm" name="do" value="<?= $m['is_read'] ? 'unread' : 'read' ?>"><?= $m['is_read'] ? 'Označiť ako neprečítané' : 'Označiť ako prečítané' ?></button>
        </form>
        <form method="post" style="display:inline;" onsubmit="return confirm('Zmazať správu?');"><?= csrf_field() ?>
          <input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
          <button class="btn-sm danger" name="do" value="delete">Zmazať</button>
        </form>
      </div>
    </div>
  <?php endforeach; endif; ?>
</div>
<?php require __DIR__ . '/inc/footer.php'; ?>
