<?php
$route = $route ?? '';
function nav_active($r, $current) { return $r === $current ? ' class="active"' : ''; }
?>
<!DOCTYPE html>
<html lang="sk">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($meta_title ?? setting('site_title', 'Prvá Energetická')) ?></title>
<meta name="description" content="<?= e($meta_description ?? '') ?>">
<meta name="theme-color" content="#1E2125">
<meta property="og:title" content="<?= e($meta_title ?? '') ?>">
<meta property="og:description" content="<?= e($meta_description ?? '') ?>">
<meta property="og:type" content="website">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e(asset('css/style.css')) ?>">
<style>:root{--accent: <?= e(ACCENT_COLOR) ?>;}</style>
<script>
/* Anti-FOUC pre GSAP reveal */
(function(){try{
  if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
  var d=document.documentElement; d.classList.add('pe-anim-on');
  setTimeout(function(){ if(!window.__peReady){ d.classList.remove('pe-anim-on'); } },3000);
}catch(e){}})();
</script>
</head>
<body data-page="<?= e($route === '' ? 'home' : $route) ?>">
<div id="pe-progress" aria-hidden="true"></div>

<header class="site-header">
  <div class="topbar">
    <div class="container topbar-inner">
      <div class="topbar-contact">
        <a href="mailto:<?= e(setting('contact_email','info@prvaenergeticka.sk')) ?>"><?= e(setting('contact_email','info@prvaenergeticka.sk')) ?></a>
        <span class="sep">•</span>
        <span><?= e(setting('contact_address','Hattalova 12A, 831 03 Bratislava')) ?></span>
      </div>
      <div class="topbar-social">
        <?php if (setting('facebook_url')): ?><a href="<?= e(setting('facebook_url')) ?>" target="_blank" rel="noopener">Facebook</a><?php endif; ?>
        <?php if (setting('linkedin_url')): ?><a href="<?= e(setting('linkedin_url')) ?>" target="_blank" rel="noopener">LinkedIn</a><?php endif; ?>
      </div>
    </div>
  </div>

  <div class="navbar">
    <div class="container navbar-inner">
      <a class="logo" href="<?= e(url('')) ?>">
        <img src="<?= e(asset('img/logo.png')) ?>" alt="Prvá Energetická"
             onerror="this.style.display='none';this.nextElementSibling.style.display='inline';">
        <span class="logo-text" style="display:none;">PRVÁ ENERGETICKÁ</span>
      </a>

      <button class="nav-toggle" aria-label="Menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>

      <nav class="main-nav">
        <a href="<?= e(url('')) ?>"<?= nav_active('', $route) ?>>Domov</a>
        <a href="<?= e(url('sluzby')) ?>"<?= nav_active('sluzby', $route) ?>>Služby</a>
        <a href="<?= e(url('o-nas')) ?>"<?= nav_active('o-nas', $route) ?>>O nás</a>
        <a href="<?= e(url('blog')) ?>"<?= nav_active('blog', $route) ?>>Blog</a>
        <a href="<?= e(url('faq')) ?>"<?= nav_active('faq', $route) ?>>FAQ</a>
        <a href="<?= e(url('kontakt')) ?>"<?= nav_active('kontakt', $route) ?>>Kontakt</a>
        <a class="btn btn-accent nav-cta" href="<?= e(url('kontakt')) ?>">Ušetrite</a>
      </nav>
    </div>
  </div>
</header>

<main id="content">
