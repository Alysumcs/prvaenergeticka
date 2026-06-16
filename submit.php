<?php
/**
 * Spracovanie formulárov (kontakt, plyn, elektrina).
 */
require __DIR__ . '/config.php';
require __DIR__ . '/includes/db.php';
require __DIR__ . '/includes/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('');
}
csrf_verify();

// Honeypot proti spamu – ak je vyplnené, tvár sa že OK.
if (!empty($_POST['website'])) {
    flash('Ďakujeme, správa bola odoslaná.');
    redirect($_POST['redirect'] ?? '');
}

$type     = $_POST['form_type'] ?? 'kontakt';
$redirect = $_POST['redirect'] ?? '';
$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$phone    = trim($_POST['phone'] ?? '');
$message  = trim($_POST['message'] ?? '');

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    flash('Zadajte platnú e-mailovú adresu.', 'err');
    redirect($redirect);
}

// Doplnkové polia (plyn/elektrina) – uložíme do meta.
$extraKeys = ['odberatel','distribucia','sposob_ponuky','jednotka','viazanost','spotreba'];
$meta = [];
foreach ($extraKeys as $k) {
    if (isset($_POST[$k]) && $_POST[$k] !== '') { $meta[$k] = trim($_POST[$k]); }
}

$subjectMap = [
    'kontakt'   => 'Nová správa z kontaktného formulára',
    'plyn'      => 'Dopyt: Lacnejší plyn',
    'elektrina' => 'Dopyt: Lacnejšia elektrina',
];
$subject = $subjectMap[$type] ?? 'Nová správa z webu';

// Ulož do DB.
try {
    $stmt = db()->prepare('INSERT INTO messages (name, email, phone, subject, body, meta)
                           VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$name, $email, $phone, $subject, $message, json_encode($meta, JSON_UNESCAPED_UNICODE)]);
} catch (Exception $e) {
    // Pokračujeme aj keď DB zlyhá – aspoň pošleme e-mail.
}

// Pošli e-mail.
$lines = [];
$lines[] = 'Typ formulára: ' . $type;
$lines[] = 'Meno: ' . $name;
$lines[] = 'E-mail: ' . $email;
$lines[] = 'Telefón: ' . $phone;
if ($meta) {
    foreach ($meta as $k => $v) { $lines[] = ucfirst($k) . ': ' . $v; }
}
if ($message !== '') { $lines[] = "\nSpráva:\n" . $message; }
$bodyTxt = implode("\n", $lines);

$headers = 'From: ' . MAIL_FROM . "\r\n" .
           'Reply-To: ' . $email . "\r\n" .
           'Content-Type: text/plain; charset=UTF-8';
@mail(CONTACT_EMAIL, $subject, $bodyTxt, $headers);

flash('Ďakujeme! Vaša správa bola úspešne odoslaná. Ozveme sa vám čo najskôr.');
redirect($redirect);
