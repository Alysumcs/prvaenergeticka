  </main>
</div>
<?php if (!empty($use_editor)): ?>
<!-- Jednoduchý WYSIWYG editor (TinyMCE z CDN, bez API kľúča) -->
<script src="https://cdn.jsdelivr.net/npm/tinymce@7.6.0/tinymce.min.js"></script>
<script>
if (window.tinymce) {
  tinymce.init({
    selector: 'textarea.wysiwyg',
    height: 420,
    menubar: false,
    plugins: 'lists link image code table autolink',
    toolbar: 'undo redo | blocks | bold italic | bullist numlist | link image | code',
    branding: false,
    promotion: false,
    license_key: 'gpl'
  });
}
</script>
<?php endif; ?>
</body>
</html>
