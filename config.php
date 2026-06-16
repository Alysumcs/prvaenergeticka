<?php
/**
 * Konfigurácia – vyplň podľa údajov z Hostingera.
 * (Hostinger → Databázy → MySQL: nájdeš názov DB, používateľa a heslo.)
 */

// ---- Databáza (MySQL) ----
define('DB_HOST', 'localhost');          // na Hostingeri zvyčajne "localhost"
define('DB_NAME', 'TVOJA_DATABAZA');     // názov databázy
define('DB_USER', 'TVOJ_POUZIVATEL');    // používateľské meno DB
define('DB_PASS', 'TVOJE_HESLO');        // heslo DB
define('DB_CHARSET', 'utf8mb4');

// ---- Web ----
// Necháj prázdne ak je web v koreňovom priečinku domény (napr. prvaenergeticka.sk).
// Ak je v podpriečinku (napr. /web), zadaj '/web'.
define('BASE_PATH', '');

// E-mail, na ktorý chodia správy z kontaktného formulára.
define('CONTACT_EMAIL', 'info@prvaenergeticka.sk');
define('MAIL_FROM', 'web@prvaenergeticka.sk');

// Firemná farba (akcent).
define('ACCENT_COLOR', '#d12911');

// Tajný kľúč pre bezpečnosť (zmeň na náhodný reťazec).
define('APP_SECRET', 'ZMEN_MA_na_nahodny_dlhy_retazec_123456');

// Zobrazovať PHP chyby? Na ostro nechaj false.
define('APP_DEBUG', false);

// ---- Nemeniť pod týmto riadkom ----
date_default_timezone_set('Europe/Bratislava');
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}
