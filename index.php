<?php
/**
 * Front controller / router.
 */
require __DIR__ . '/config.php';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

// Zisti route z URL (rešpektuje BASE_PATH).
$uri  = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$base = rtrim(BASE_PATH, '/');
if ($base !== '' && strncmp($uri, $base, strlen($base)) === 0) {
    $uri = substr($uri, strlen($base));
}
$route = trim($uri, '/');

// Predvolené meta hodnoty (šablóna ich môže prepísať).
$meta_title = 'Prvá Energetická – Energetické poradenstvo';
$meta_description = 'Energetické poradenstvo, výber dodávateľa energií, lacnejší plyn a lacnejšia elektrina pre firmy, verejnú správu a bytové domy.';

// Mapovanie routy → šablóna.
$template     = null;
$article      = null;
$static_page  = null;

// Pomocník na nastavenie meta z DB stránky.
$set_meta_from_page = function ($slug) use (&$meta_title, &$meta_description) {
    $p = get_page($slug);
    if (!empty($p['title'])) {
        $meta_title = $p['title'] . ' – Prvá Energetická';
    }
    if (!empty($p['meta_description'])) {
        $meta_description = $p['meta_description'];
    }
    return $p;
};

switch (true) {
    case $route === '':
        $template = 'home';
        break;
    case $route === 'sluzby':
        $template = 'sluzby';
        $static_page = $set_meta_from_page('sluzby');
        break;
    case $route === 'o-nas':
        $template = 'o-nas';
        $static_page = $set_meta_from_page('home-about');
        $meta_title = 'O nás – Prvá Energetická';
        break;
    case $route === 'plyn':
        $template = 'plyn';
        $static_page = $set_meta_from_page('plyn');
        break;
    case $route === 'elektrina':
        $template = 'elektrina';
        $static_page = $set_meta_from_page('elektrina');
        break;
    case $route === 'faq':
        $template = 'faq';
        $meta_title = 'Časté otázky (FAQ) – Prvá Energetická';
        $meta_description = 'Odpovede na najčastejšie otázky o zmene dodávateľa energií, cenách elektriny a plynu.';
        break;
    case $route === 'kontakt':
        $template = 'kontakt';
        $meta_title = 'Kontakt – Prvá Energetická';
        $meta_description = 'Spojte sa s nami. Radi vám poradíme s optimalizáciou nákladov na energie.';
        break;
    case $route === 'blog':
        $template = 'blog';
        $meta_title = 'Blog – Prvá Energetická';
        $meta_description = 'Novinky zo sveta energetiky, vývoj cien a tipy na optimalizáciu nákladov.';
        break;
    case strncmp($route, 'blog/', 5) === 0:
        $slug = substr($route, 5);
        $stmt = db()->prepare('SELECT * FROM articles WHERE slug = ? AND published = 1 LIMIT 1');
        $stmt->execute([$slug]);
        $article = $stmt->fetch();
        $template = $article ? 'article' : '404';
        if ($article) {
            $meta_title = $article['title'] . ' – Prvá Energetická';
            $meta_description = $article['excerpt'] ?: $meta_description;
        }
        break;
    case $route === 'zasady-ochrany-osobnych-udajov':
    case $route === 'cookies':
        $template = 'page';
        $static_page = $set_meta_from_page($route);
        if (empty($static_page['title'])) { $template = '404'; }
        break;
    default:
        $template = '404';
}

if ($template === '404') {
    http_response_code(404);
}

$tpl_file = __DIR__ . '/templates/' . $template . '.php';
if (!file_exists($tpl_file)) {
    http_response_code(404);
    $tpl_file = __DIR__ . '/templates/404.php';
}

require __DIR__ . '/includes/header.php';
require $tpl_file;
require __DIR__ . '/includes/footer.php';
