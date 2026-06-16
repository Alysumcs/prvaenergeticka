# Prvá Energetická – nový web (PHP + MySQL)

Vlastný web s administráciou článkov, FAQ, textov stránok, GDPR cookie lištou,
podstránkami a GSAP animáciami (v štýle vectrfl.com). Bez WordPressu.

## Čo web obsahuje

**Verejná časť** (podstránky):
Domov, Služby, O nás, Plyn (s dopytovým formulárom), Elektrina (s formulárom),
Blog + detail článku, FAQ, Kontakt, Zásady ochrany osobných údajov, Cookies.

**Administrácia** (`/admin`):
prihlásenie, prehľad, správa článkov (vrátane obrázkov), správa FAQ,
úprava textov všetkých podstránok + nastavení, prijaté správy z formulárov.

**Funkcie:** GSAP animácie (reveal, stagger, smooth scroll, parallax, progress bar),
GDPR cookie súhlas so 4 kategóriami, responzívny dizajn, zachovaná firemná
červená `#d12911` a tmavá téma.

---

## Nasadenie na Hostinger – krok za krokom

### 1. Vytvor MySQL databázu
V hPanel → **Databázy → Správa MySQL databáz**:
- vytvor novú databázu a používateľa,
- zapíš si **názov DB, používateľa a heslo**.

### 2. Nahraj súbory
V hPanel → **Správca súborov** (alebo cez FTP) nahraj **obsah** priečinka
`prvaenergeticka-web` do `public_html` (alebo do priečinka domény).
> Nahrávaj obsah priečinka, nie priečinok samotný – `index.php` musí byť priamo
> v `public_html`.

### 3. Vyplň `config.php`
Otvor `config.php` a doplň údaje z kroku 1:
```php
define('DB_NAME', 'nazov_databazy');
define('DB_USER', 'pouzivatel');
define('DB_PASS', 'heslo');
```
Zmeň aj `APP_SECRET` na náhodný dlhý reťazec a skontroluj `CONTACT_EMAIL`.
Ak je web v podpriečinku (napr. `domena.sk/web`), nastav `BASE_PATH` na `'/web'`.

### 4. Spusti inštaláciu
V prehliadači otvor `https://tvojadomena.sk/install.php`,
zadaj prihlasovacie meno a heslo administrátora a klikni **Spustiť inštaláciu**.
Vytvoria sa tabuľky a naplní sa obsah prevzatý z pôvodného webu.

### 5. ⚠️ Zmaž `install.php`
Z bezpečnostných dôvodov po inštalácii **vymaž súbor `install.php`** zo servera.

### 6. Nahraj logo
Do priečinka `assets/img/` nahraj logo ako **`logo.png`**
(ideálne biele na priehľadnom pozadí). Tvoje pôvodné logo:
`https://prvaenergeticka.sk/wp-content/uploads/2025/02/PE-logo-W-1024x214.webp`

Hotovo. Web beží na `https://tvojadomena.sk`, administrácia na
`https://tvojadomena.sk/admin`.

---

## Práca s administráciou

- **Články** – pridávanie/úprava/mazanie, obrázok, koncept/publikované, WYSIWYG editor.
- **FAQ** – pridávanie otázok, poradie, skrytie/zobrazenie.
- **Texty stránok** – úprava obsahu každej podstránky + hero banner a kontaktné údaje.
- **Správy** – všetky odoslané formuláre (kontakt, plyn, elektrina) na jednom mieste.

---

## Dôležité poznámky

- **Bezpečnosť:** používajú sa prípravené SQL dotazy (proti SQL injection),
  CSRF tokeny vo formulároch, hashované heslá, ochrana proti spusteniu skriptov
  v priečinku `uploads`. Po inštalácii zmeň `APP_SECRET` a zmaž `install.php`.
- **PHP verzia:** odporúčaná PHP 8.0+ (na Hostingeri nastaviteľná v hPanel).
- **E-maily:** formuláre posielajú e-mail cez PHP `mail()`. Ak by maily nechodili,
  na Hostingeri nastav SMTP, prípadne to vieme prepojiť cez SMTP knižnicu.
- **GSAP** sa načítava z CDN – netreba nič nahrávať.
- **Pôvodný web bol napadnutý spamom** (odkazy na kasína vo FAQ a podstránkach).
  Tento nový web spam neobsahuje – obsah som prebral a očistil.

## Štruktúra projektu
```
index.php            – router
config.php           – nastavenia (DB, e-mail)
install.php          – inštalátor (po použití zmazať)
submit.php           – spracovanie formulárov
includes/            – db, helpers, hlavička, pätička
templates/           – šablóny podstránok
admin/               – administrácia
assets/css|js|img|uploads
sql/schema.sql       – štruktúra databázy
```
