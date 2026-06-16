<?php
/**
 * Pomocné funkcie – bezpečnosť, URL, obsah, autentifikácia.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Escape HTML (proti XSS). */
function e($s) {
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

/** Absolútna URL v rámci webu (rešpektuje BASE_PATH). */
function url($path = '') {
    $path = ltrim($path, '/');
    $base = rtrim(BASE_PATH, '/');
    return ($base === '' ? '' : $base) . '/' . $path;
}

/** URL k súboru v /assets. */
function asset($path) {
    return url('assets/' . ltrim($path, '/'));
}

/** Presmerovanie. */
function redirect($path) {
    header('Location: ' . url($path));
    exit;
}

/** Slug z textu (diakritika → ascii). */
function slugify($text) {
    $text = trim($text);
    $map = ['á'=>'a','ä'=>'a','č'=>'c','ď'=>'d','é'=>'e','í'=>'i','ĺ'=>'l','ľ'=>'l',
            'ň'=>'n','ó'=>'o','ô'=>'o','ŕ'=>'r','š'=>'s','ť'=>'t','ú'=>'u','ý'=>'y','ž'=>'z',
            'Á'=>'a','Ä'=>'a','Č'=>'c','Ď'=>'d','É'=>'e','Í'=>'i','Ľ'=>'l','Ň'=>'n','Ó'=>'o',
            'Ô'=>'o','Š'=>'s','Ť'=>'t','Ú'=>'u','Ý'=>'y','Ž'=>'z'];
    $text = strtr($text, $map);
    $text = function_exists('mb_strtolower') ? mb_strtolower($text, 'UTF-8') : strtolower($text);
    $text = preg_replace('~[^a-z0-9]+~', '-', $text);
    $text = trim($text, '-');
    return $text === '' ? 'clanok' : $text;
}

/* ----------------- NASTAVENIA (key/value) ----------------- */
function setting($key, $default = '') {
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        try {
            foreach (db()->query('SELECT skey, svalue FROM settings') as $row) {
                $cache[$row['skey']] = $row['svalue'];
            }
        } catch (Exception $e) { $cache = []; }
    }
    return array_key_exists($key, $cache) ? $cache[$key] : $default;
}

function set_setting($key, $value) {
    $stmt = db()->prepare(
        'INSERT INTO settings (skey, svalue) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE svalue = VALUES(svalue)'
    );
    $stmt->execute([$key, $value]);
}

/* ----------------- STRÁNKY (editovateľné texty) ----------------- */
function get_page($slug) {
    $stmt = db()->prepare('SELECT * FROM pages WHERE slug = ? LIMIT 1');
    $stmt->execute([$slug]);
    $row = $stmt->fetch();
    return $row ?: ['slug'=>$slug, 'title'=>'', 'subtitle'=>'', 'body'=>'', 'meta_description'=>''];
}

/* ----------------- CSRF ----------------- */
function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}
function csrf_field() {
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}
function csrf_verify() {
    $ok = isset($_POST['csrf']) && hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf']);
    if (!$ok) {
        http_response_code(400);
        die('Neplatný bezpečnostný token. Obnovte stránku a skúste znova.');
    }
}

/* ----------------- AUTENTIFIKÁCIA (admin) ----------------- */
function current_user() {
    return $_SESSION['user'] ?? null;
}
function require_login() {
    if (!current_user()) {
        redirect('admin/login.php');
    }
}

/* ----------------- FLASH správy ----------------- */
function flash($msg = null, $type = 'success') {
    if ($msg !== null) {
        $_SESSION['flash'][] = ['msg' => $msg, 'type' => $type];
        return;
    }
    $out = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $out;
}
