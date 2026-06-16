<?php
/**
 * Inštalátor – spusti raz po nahraní na hosting a vyplnení config.php.
 * Vytvorí tabuľky, naplní obsah a vytvorí administrátorský účet.
 * PO INŠTALÁCII TENTO SÚBOR ZMAŽ!
 */
require __DIR__ . '/config.php';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

$done = false;
$error = '';

// Je už nainštalované?
$installed = false;
try {
    $cnt = db()->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $installed = $cnt > 0;
} catch (Exception $e) {
    $installed = false; // tabuľky ešte neexistujú
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';
    $pass2 = $_POST['password2'] ?? '';

    if ($user === '' || strlen($user) < 3) {
        $error = 'Používateľské meno musí mať aspoň 3 znaky.';
    } elseif (strlen($pass) < 8) {
        $error = 'Heslo musí mať aspoň 8 znakov.';
    } elseif ($pass !== $pass2) {
        $error = 'Heslá sa nezhodujú.';
    } else {
        try {
            // 1) Tabuľky
            $sql = file_get_contents(__DIR__ . '/sql/schema.sql');
            foreach (array_filter(array_map('trim', explode(';', $sql))) as $stmt) {
                if ($stmt !== '') { db()->exec($stmt); }
            }

            // 2) Obsah
            seed_content();

            // 3) Admin
            $h = password_hash($pass, PASSWORD_DEFAULT);
            $ins = db()->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)
                                  ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash)');
            $ins->execute([$user, $h]);

            $done = true;
        } catch (Exception $e) {
            $error = 'Chyba pri inštalácii: ' . $e->getMessage();
        }
    }
}

/** Naplní nastavenia, stránky a FAQ obsahom z pôvodného webu. */
function seed_content() {
    // ---- Nastavenia ----
    $settings = [
        'site_title'       => 'Prvá Energetická',
        'contact_email'    => 'info@prvaenergeticka.sk',
        'contact_company'  => 'Prvá Energetická s.r.o.',
        'contact_address'  => 'Hattalova 12A, 831 03 Bratislava, Slovensko',
        'contact_ico'      => '46 426 639',
        'contact_dic'      => '2023402326',
        'facebook_url'     => 'https://www.facebook.com/share/1Gp1kmkQUr/?mibextid=wwXIfr',
        'linkedin_url'     => 'https://www.linkedin.com/company/prva-energeticka/',
        'hero_title'       => 'SPOĽAHLIVÁ ENERGIA',
        'hero_highlight'   => 'pre váš biznis, vašu obec, bytový dom',
        'hero_subtitle'    => 'Analýza trhu, odborné poradenstvo a stabilná dodávka energií na jednom mieste.',
    ];
    foreach ($settings as $k => $v) { set_setting($k, $v); }

    // ---- Stránky ----
    $why = <<<'HTML'
<p>V <strong>Prvej Energetickej</strong> sa už vyše 14 rokov špecializujeme na poskytovanie komplexných energetických služieb pre právnické osoby, verejnú správu a štátny sektor. Naším hlavným cieľom je optimalizovať Vaše náklady na energie a zabezpečiť stabilnú a výhodnú dodávku elektriny a plynu. Vďaka dlhoročným skúsenostiam a spolupráci s poprednými dodávateľmi na trhu Vám pomáhame nájsť najlepšie riešenia presne podľa vašich potrieb.</p>
<h3>Výhody spolupráce s nami:</h3>
<ul>
<li><strong>Individuálny prístup:</strong> Každému klientovi poskytujeme riešenia šité na mieru.</li>
<li><strong>Všetko na jednom mieste:</strong> Od analýzy trhu, energetického auditu, výberu dodávateľa, poradenstva pri dotáciách, či OZE.</li>
<li><strong>Overení dodávatelia:</strong> Spolupracujeme len s overenými a stabilnými dodávateľmi energií a služieb.</li>
<li><strong>Dlhodobá starostlivosť:</strong> Poskytujeme nadštandardnú starostlivosť počas celej doby spolupráce.</li>
</ul>
HTML;

    $about = <<<'HTML'
<p><strong>Prvá Energetická s.r.o.</strong> je renomovaná poradenská spoločnosť v oblasti energetiky, ktorá sa špecializuje na optimalizáciu nákladov na elektrickú energiu a zemný plyn. Na trhu pôsobíme od roku <strong>2011</strong>, pričom naším hlavným cieľom je pomôcť firmám, verejnej správe a štátnym organizáciám získať <strong>najvýhodnejšie podmienky pri odbere energií</strong>.</p>
<p>Vďaka dlhoročným skúsenostiam a spolupráci s <strong>overenými dodávateľmi</strong> vám dokážeme ponúknuť <strong>transparentné riešenia</strong> šité na mieru, ktoré vám zabezpečia nielen finančné úspory, ale aj <strong>stabilnú a bezproblémovú dodávku energií</strong>.</p>
<h3>Naša misia</h3>
<p>Energetický trh je neustále sa meniace prostredie, v ktorom je dôležité mať <strong>spoľahlivého partnera</strong>, ktorý sleduje trendy, reaguje na legislatívne zmeny a pomáha klientom dosiahnuť maximálne úspory. Našou misiou je uľahčiť firmám a organizáciám orientáciu v energetike a zabezpečiť im výhodnejšie podmienky.</p>
<h3>Kto sú naši klienti?</h3>
<ul>
<li><strong>Firmy</strong> – Pomáhame znížiť prevádzkové náklady na energie a optimalizovať zmluvné podmienky.</li>
<li><strong>Verejná správa a štátne inštitúcie</strong> – Poskytujeme odborné poradenstvo pri verejnom obstarávaní dodávok energií.</li>
<li><strong>Bytové domy a správcovské spoločnosti</strong> – Hľadáme efektívne riešenia na zníženie nákladov na spoločné odbery energií.</li>
</ul>
HTML;

    $sluzby = <<<'HTML'
<p><strong>Optimalizujte náklady a zvýšte efektivitu svojich energií</strong> s našimi profesionálnymi službami v oblasti energetiky. Postaráme sa o kompletnú správu, technickú podporu a administratívu spojenú s dodávkami elektriny a plynu.</p>

<h3>Monitorovanie a správa energií</h3>
<ul>
<li>Sledovanie vývoja trhových cien</li>
<li>Pravidelná mesačná kontrola faktúr za elektrinu a plyn</li>
<li>Analýza fakturačných údajov a porovnanie s dátami PDS</li>
<li>Evidencia spotreby energií (hodnoty RK, DMM, účinník, jalová energia)</li>
<li>Kvartálne/mesačné reporty a odporúčania na optimalizáciu nákladov</li>
<li>Sledovanie a vyhodnocovanie nákladov na energie</li>
</ul>

<h3>Administratíva a komunikácia s dodávateľmi</h3>
<ul>
<li>Zastupovanie odberateľa pri komunikácii s PDS</li>
<li>Správa dokumentácie pri pripájaní, odpájaní a zmene parametrov odberného miesta (ZoP, RK, MRK)</li>
<li>Kompletná administratíva a zmluvná agenda</li>
</ul>

<h3>Technické a revízne služby</h3>
<ul>
<li>Energetické audity a analýza siete</li>
<li>Inštalácia priebehových meraní a kompenzačných rozvádzačov</li>
<li>Posúdenie kvality dodávky elektriny v sieti odberateľa</li>
</ul>

<h3>Optimalizácia vykurovania a chladenia</h3>
<ul>
<li>Meranie a hodnotenie účinnosti vykurovacej sústavy</li>
<li>Kontrola kompresorovej sústavy, identifikácia únikov a analýza účinnosti</li>
<li>Návrh a realizácia efektívnych vykurovacích systémov (kondenzačné kotly, plynové infražiariče)</li>
<li>Komplexné riešenia chladenia pre podniky a priemysel</li>
</ul>

<h3>Obnoviteľné zdroje a osvetlenie</h3>
<ul>
<li>Návrh a realizácia fotovoltických elektrární</li>
<li>Systémy termických kolektorov na vykurovanie a ohrev TÚV</li>
<li>Efektívne osvetľovacie systémy pre firmy a domácnosti</li>
</ul>

<h3>Grantové poradenstvo a náhradná dodávka energií</h3>
<ul>
<li>Konzultácie pre získanie finančnej podpory z EU grantov</li>
<li>Zabezpečenie náhradnej dodávky elektrickej energie</li>
</ul>

<p><strong>Znížte náklady, zefektívnite správu energií a získajte odbornú podporu.</strong> Kontaktujte nás a pripravíme vám individuálnu ponuku presne podľa vašich potrieb.</p>
HTML;

    $plyn = <<<'HTML'
<p><strong>Získajte výhodnejší plyn pre vaše podnikanie, firmu alebo verejnú správu – rýchlo a bez starostí!</strong></p>
<p>Prvá Energetická s.r.o. za vás osloví dodávateľov energií a na základe vašej spotreby a požiadaviek vyberieme najlepšiu cenovú ponuku. Stačí vyplniť jednoduchý formulár a o všetko ostatné sa postaráme my.</p>
<p>Pre presnejšiu ponuku môžete pripojiť oskenovanú zúčtovaciu faktúru alebo iný dokument, ktorý nám pomôže lepšie prispôsobiť cenové podmienky.</p>
<p>Vaša dôvera je pre nás prioritou. Všetky poskytnuté informácie sú prísne dôverné a slúžia výhradne na vypracovanie cenovej ponuky pre spoločnosť Prvá Energetická s.r.o.</p>
HTML;

    $elektrina = <<<'HTML'
<p><strong>Výhodnejšia elektrina pre vaše podnikanie – rýchlo a bez starostí!</strong></p>
<p>Hľadáte nižšiu cenu elektrickej energie? Prvá Energetická s.r.o. osloví dodávateľov za vás a na základe vašej spotreby a požiadaviek vyberieme najlepšiu ponuku.</p>
<p>Stačí vyplniť krátky formulár a o všetko ostatné sa postaráme my. Pre presnejší výpočet môžete priložiť oskenovanú zúčtovaciu faktúru alebo iný relevantný dokument. Pri spotrebe nad <strong>1 000 MWh ročne</strong> odporúčame pridať štvrťhodinový diagram spotreby, vďaka ktorému získate ešte výhodnejšie podmienky.</p>
<p>🔒 <strong>Vaše údaje sú v bezpečí</strong> – všetky informácie spracovávame dôverne a používame výhradne na prípravu cenovej ponuky.</p>
HTML;

    $privacy = <<<'HTML'
<p>Tieto zásady ochrany osobných údajov vysvetľujú, ako spoločnosť <strong>Prvá Energetická s.r.o.</strong>, so sídlom Hattalova 12A, 831 03 Bratislava, IČO: 46 426 639 (ďalej len „prevádzkovateľ"), spracúva vaše osobné údaje v súlade s Nariadením Európskeho parlamentu a Rady (EÚ) 2016/679 (GDPR) a zákonom č. 18/2018 Z. z. o ochrane osobných údajov.</p>

<h3>1. Aké údaje spracúvame</h3>
<ul>
<li>Identifikačné a kontaktné údaje, ktoré nám poskytnete cez formuláre (meno, e-mail, telefón, názov firmy).</li>
<li>Údaje o spotrebe energií a fakturačné údaje, ktoré nám zašlete za účelom prípravy cenovej ponuky.</li>
<li>Technické údaje (IP adresa, typ prehliadača, cookies) – pozri <a href="/cookies">Zásady používania cookies</a>.</li>
</ul>

<h3>2. Účel a právny základ spracúvania</h3>
<ul>
<li>Vypracovanie cenovej ponuky a poskytnutie služieb – plnenie zmluvy, resp. opatrenia pred uzavretím zmluvy (čl. 6 ods. 1 písm. b GDPR).</li>
<li>Odpovedanie na otázky z kontaktného formulára – oprávnený záujem (čl. 6 ods. 1 písm. f GDPR).</li>
<li>Plnenie zákonných povinností (napr. účtovníctvo) – čl. 6 ods. 1 písm. c GDPR.</li>
<li>Marketing a štatistiky – iba na základe vášho súhlasu (čl. 6 ods. 1 písm. a GDPR).</li>
</ul>

<h3>3. Doba uchovávania</h3>
<p>Osobné údaje uchovávame len po dobu nevyhnutnú na splnenie účelu, prípadne po dobu vyžadovanú právnymi predpismi.</p>

<h3>4. Príjemcovia údajov</h3>
<p>Vaše údaje môžu byť poskytnuté overeným dodávateľom energií za účelom prípravy ponuky, a poskytovateľom IT a účtovných služieb, ktorí pre nás spracúvajú údaje na základe zmluvy.</p>

<h3>5. Vaše práva</h3>
<p>Máte právo na prístup k údajom, ich opravu, vymazanie, obmedzenie spracúvania, prenosnosť, namietať proti spracúvaniu a kedykoľvek odvolať súhlas. Máte tiež právo podať sťažnosť na Úrad na ochranu osobných údajov SR.</p>

<h3>6. Kontakt</h3>
<p>Vo veciach ochrany osobných údajov nás kontaktujte na <a href="mailto:info@prvaenergeticka.sk">info@prvaenergeticka.sk</a>.</p>

<p><em>Tento dokument je vzorová šablóna. Pred ostrým nasadením odporúčame nechať si znenie skontrolovať odborníkom na ochranu osobných údajov.</em></p>
HTML;

    $cookies = <<<'HTML'
<p>Táto webová stránka používa súbory cookies, aby sme vám poskytli čo najlepší zážitok z prehliadania a mohli analyzovať návštevnosť.</p>

<h3>Čo sú cookies</h3>
<p>Cookies sú malé textové súbory, ktoré sa ukladajú vo vašom zariadení pri návšteve webovej stránky. Pomáhajú stránke zapamätať si vaše nastavenia a preferencie.</p>

<h3>Aké kategórie cookies používame</h3>
<ul>
<li><strong>Nevyhnutné</strong> – potrebné pre základné fungovanie webu. Nedajú sa vypnúť.</li>
<li><strong>Predvoľby</strong> – ukladajú vaše nastavenia (napr. jazyk).</li>
<li><strong>Štatistiky</strong> – pomáhajú nám pochopiť, ako návštevníci používajú web (anonymne).</li>
<li><strong>Marketing</strong> – slúžia na zobrazovanie relevantnej reklamy.</li>
</ul>

<h3>Správa súhlasu</h3>
<p>Pri prvej návšteve vás požiadame o súhlas s používaním cookies. Svoje rozhodnutie môžete kedykoľvek zmeniť kliknutím na tlačidlo „Nastavenia cookies" v päte stránky.</p>
HTML;

    $pages = [
        ['home-why', 'PREČO PRVÁ ENERGETICKÁ?', '', $why, ''],
        ['home-about', 'O NÁS', 'Spoľahlivý partner v energetike', $about, ''],
        ['sluzby', 'ENERGETICKÉ SLUŽBY A RIEŠENIA NA MIERU', 'Kompletná správa, technická podpora a administratíva', $sluzby, 'Energetické služby na mieru – monitorovanie spotreby, audity, fotovoltika, optimalizácia vykurovania a grantové poradenstvo.'],
        ['plyn', 'LACNEJŠÍ PLYN', 'Znížte svoje náklady za dodávky plynu', $plyn, 'Získajte výhodnejší plyn pre firmu, podnikanie alebo verejnú správu. Oslovíme dodávateľov za vás.'],
        ['elektrina', 'LACNEJŠIA ELEKTRINA', 'Ušetrite s novým dodávateľom elektriny', $elektrina, 'Výhodnejšia elektrina pre vaše podnikanie. Vyberieme najlepšiu ponuku na základe vašej spotreby.'],
        ['zasady-ochrany-osobnych-udajov', 'Zásady ochrany osobných údajov', '', $privacy, 'Zásady ochrany osobných údajov spoločnosti Prvá Energetická s.r.o. v súlade s GDPR.'],
        ['cookies', 'Zásady používania cookies', '', $cookies, 'Informácie o používaní súborov cookies na webe Prvá Energetická.'],
    ];
    $stmt = db()->prepare('INSERT INTO pages (slug, title, subtitle, body, meta_description)
                           VALUES (?, ?, ?, ?, ?)
                           ON DUPLICATE KEY UPDATE title=VALUES(title), subtitle=VALUES(subtitle),
                           body=VALUES(body), meta_description=VALUES(meta_description)');
    foreach ($pages as $p) { $stmt->execute($p); }

    // ---- FAQ ----
    $faqs = [
        ['Pre koho sú určené služby Prvej Energetickej?', 'Naše služby poskytujeme všetkým právnickým osobám, verejnej a štátnej správe. Na základe špecifík vášho odberného miesta vám pripravíme ponuku šitú na mieru. Od analýzy trhu, výberu dodávateľa, energetických služieb a komplexného poradenstva, s nami máte energie v správnych rukách.'],
        ['Je zmena dodávateľa energií zložitá?', 'Nie, celý postup zabezpečí Prvá Energetická spolu s vaším novým dodávateľom tak, aby ste boli zaťažení čo najmenej a zmenu si všimli iba na nižších nákladoch za energie. Proces sa dá zhrnúť do podpisu niekoľkých dokumentov a odoslania dvoch obálok.'],
        ['Ako je možné, že sa cena energií od rôznych dodávateľov môže líšiť?', 'Deregulácia trhu s energiami umožnila vznik konkurenčného prostredia, ktoré núti energetické spoločnosti ponúkať lepšie ceny pre koncového odberateľa. Naši dodávatelia sú vždy pripravení ponúknuť atraktívnejšiu ponuku ako tradičný dodávateľ.'],
        ['Dokedy mám vypovedať zmluvu súčasnému dodávateľovi?', 'Zmluvu je možné vypovedať najskôr 6 mesiacov pred výročím zmluvy, najneskôr 3, u niektorých dodávateľov 1 mesiac pred výročím. Zmluvné podmienky sa môžu líšiť, preto je potrebné nás osloviť čo najskôr, aby sme výpovede pripravili v dostatočnom predstihu.'],
        ['Staráte sa o klientov aj po zmene dodávateľa?', 'Áno, poskytujeme dlhodobú starostlivosť. Pravidelne sledujeme trh a informujeme vás o možnostiach ďalšej optimalizácie. Pomáhame riešiť prípadné problémy s dodávateľom a poskytujeme poradenstvo v oblasti energetickej efektívnosti.'],
        ['Z čoho sa skladá koncová cena elektrickej energie?', 'Koncová cena sa skladá z viacerých zložiek: cena komodity (cca 47 %, jediná ovplyvniteľná zložka), distribúcia a prenos, straty pri distribúcii, tarifa za prevádzkovanie systému, systémové služby a Národný jadrový fond.'],
        ['Koho kontaktovať v prípade výpadku elektrickej energie?', 'Za siete zodpovedajú distribučné spoločnosti. V prípade výpadku kontaktujte priamo distribučnú spoločnosť pre vašu oblasť: Stredoslovenská distribučná 0800 159 000, Východoslovenská distribučná 0800 123 332, Západoslovenská distribučná 0800 111 567, SPP Distribúcia 0850 111 727.'],
    ];
    $fs = db()->prepare('INSERT INTO faqs (question, answer, position) VALUES (?, ?, ?)');
    // Vlož len ak je tabuľka prázdna (aby sa pri opakovanej inštalácii neduplikovalo).
    if ((int)db()->query('SELECT COUNT(*) FROM faqs')->fetchColumn() === 0) {
        $i = 0;
        foreach ($faqs as $f) { $fs->execute([$f[0], $f[1], $i++]); }
    }

    // ---- Ukážkový článok ----
    if ((int)db()->query('SELECT COUNT(*) FROM articles')->fetchColumn() === 0) {
        $a = db()->prepare('INSERT INTO articles (slug, title, excerpt, body, published) VALUES (?, ?, ?, ?, 1)');
        $a->execute([
            'vitajte-na-novom-webe',
            'Vitajte na našom novom webe',
            'Spustili sme nový web s blogom, kde vás budeme informovať o novinkách na trhu s energiami.',
            '<p>Vitajte na novom webe spoločnosti <strong>Prvá Energetická</strong>. V tejto sekcii blogu vás budeme pravidelne informovať o vývoji cien energií, legislatívnych zmenách a tipoch, ako optimalizovať náklady na elektrinu a plyn.</p><p>Ak máte otázku, neváhajte nás <a href="/kontakt">kontaktovať</a>.</p>',
        ]);
    }
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Inštalácia – Prvá Energetická</title>
<style>
  body{font-family:system-ui,Arial,sans-serif;background:#1E2125;color:#fff;display:flex;min-height:100vh;align-items:center;justify-content:center;margin:0;padding:20px;}
  .box{background:#2b2f34;padding:32px;border-radius:14px;max-width:440px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.4);}
  h1{margin-top:0;font-size:22px;}
  label{display:block;margin:14px 0 6px;font-size:14px;}
  input{width:100%;padding:11px;border-radius:8px;border:1px solid #444;background:#1E2125;color:#fff;box-sizing:border-box;}
  button{margin-top:20px;width:100%;padding:13px;border:0;border-radius:8px;background:#d12911;color:#fff;font-weight:700;cursor:pointer;font-size:15px;}
  .msg{padding:12px;border-radius:8px;margin-bottom:14px;font-size:14px;}
  .err{background:#5a1d14;}
  .ok{background:#1d4a2a;}
  code{background:#1E2125;padding:2px 6px;border-radius:4px;}
  a{color:#ff7a5c;}
</style>
</head>
<body>
<div class="box">
<?php if ($done): ?>
  <h1>✅ Inštalácia dokončená</h1>
  <div class="msg ok">Web je pripravený. Administrátorský účet bol vytvorený.</div>
  <p><strong>Dôležité – z bezpečnostných dôvodov teraz zmaž súbor <code>install.php</code></strong> zo servera.</p>
  <p>➡️ <a href="<?= e(url('')) ?>">Otvoriť web</a><br>
     ➡️ <a href="<?= e(url('admin/login.php')) ?>">Prihlásiť sa do administrácie</a></p>
<?php elseif ($installed): ?>
  <h1>Web je už nainštalovaný</h1>
  <div class="msg ok">Databáza už obsahuje účet. Ak chceš spustiť inštaláciu nanovo, najprv vyprázdni tabuľky.</div>
  <p>Z bezpečnostných dôvodov <strong>zmaž súbor <code>install.php</code></strong>.</p>
  <p>➡️ <a href="<?= e(url('admin/login.php')) ?>">Prihlásenie do administrácie</a></p>
<?php else: ?>
  <h1>Inštalácia webu</h1>
  <p style="font-size:14px;color:#afb8c2;">Vytvor administrátorský účet. Pred spustením musí byť vyplnený <code>config.php</code>.</p>
  <?php if ($error): ?><div class="msg err"><?= e($error) ?></div><?php endif; ?>
  <form method="post">
    <label>Používateľské meno</label>
    <input type="text" name="username" value="<?= e($_POST['username'] ?? 'admin') ?>" required>
    <label>Heslo (min. 8 znakov)</label>
    <input type="password" name="password" required>
    <label>Heslo znova</label>
    <input type="password" name="password2" required>
    <button type="submit">Spustiť inštaláciu</button>
  </form>
<?php endif; ?>
</div>
</body>
</html>
