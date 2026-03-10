<?php
/* ── Static contact constants (edit here or override via admin) ── */
$phone_number  = '+494662891898';
$phone_display = '04662 891898';
$email_address = 'lyvs-haarstudio@gmx.de';
$route_url     = 'https://maps.google.com/?q=Schafmarkt+2,+25917+Leck';

/* ── Load prices ── */
$prices_path   = __DIR__ . '/data/prices.json';
$default_prices = [
  'categories' => [
    ['title' => 'Damen', 'items' => [
      ['label' => 'Trockenhaarschnitt', 'price' => 'ab 32 €'],
      ['label' => 'Waschen / Schneiden', 'price' => '38 €'],
      ['label' => 'Waschen / Föhnen', 'price' => 'ab 25 €'],
      ['label' => 'Hochstecken', 'price' => 'ab 50 €'],
    ]],
    ['title' => 'Herren', 'items' => [
      ['label' => 'Trocken', 'price' => '24 €'],
      ['label' => 'Waschen / Schneiden / Föhnen', 'price' => '27 €'],
      ['label' => 'Maschinenschnitt', 'price' => '20 €'],
      ['label' => 'Bartschnitt', 'price' => 'ab 5 €'],
    ]],
    ['title' => 'Kinder', 'items' => [
      ['label' => '0–3 Jahre', 'price' => '14 €'],
      ['label' => '4–8 Jahre', 'price' => '16 €'],
      ['label' => '9–13 Jahre', 'price' => '18 €'],
      ['label' => '14–16 Jahre', 'price' => '20 €'],
    ]],
    ['title' => 'Farbe & Glanz', 'items' => [
      ['label' => 'Coloration', 'price' => 'ab 40 €'],
      ['label' => 'Balayage / Freihand', 'price' => 'nach Aufwand'],
      ['label' => 'Strähnen', 'price' => 'nach Aufwand'],
      ['label' => 'Glossing / Tönung', 'price' => 'auf Anfrage'],
    ]],
    ['title' => 'Dauerwelle', 'items' => [
      ['label' => 'Soft Waves', 'price' => 'ab 50 €'],
      ['label' => 'Teil-Dauerwelle', 'price' => 'auf Anfrage'],
      ['label' => 'Pflegepaket', 'price' => '+15 €'],
      ['label' => 'Styling-Tipps', 'price' => 'inkl.'],
    ]],
    ['title' => 'Extras', 'items' => [
      ['label' => 'Kopfhaut Spa', 'price' => '18 €'],
      ['label' => 'Brautstyling', 'price' => 'ab 120 €'],
      ['label' => 'Make-up Touch-up', 'price' => '35 €'],
      ['label' => 'Blumen-Accessoires', 'price' => 'auf Anfrage'],
    ]],
  ],
];
$prices_data = null;
if (is_file($prices_path)) {
  $raw = file_get_contents($prices_path);
  $dec = json_decode($raw, true);
  if (is_array($dec) && isset($dec['categories'])) {
    $prices_data = $dec;
  }
}
if ($prices_data === null) {
  $prices_data = $default_prices;
}

/* ── Load content ── */
$content_path    = __DIR__ . '/data/content.json';
$default_content = require __DIR__ . '/content-defaults.php';
$content_data    = $default_content;
if (is_file($content_path)) {
  $raw = file_get_contents($content_path);
  $dec = json_decode($raw, true);
  if (is_array($dec)) {
    $content_data = array_replace_recursive($default_content, $dec);
  }
}

/* ── Derived variables (with fallbacks to hardcoded constants) ── */
$contact_phone   = trim($content_data['contact']['phone']   ?? '') ?: $phone_display;
$contact_email   = trim($content_data['contact']['email']   ?? '') ?: $email_address;
$contact_city    = trim($content_data['contact']['address_city'] ?? '') ?: '25917 Leck';
$instagram_url   = trim($content_data['social']['instagram'] ?? '') ?: 'https://www.instagram.com/lyvs_haarstudio?igsh=N28yOHVqZ2dlM3I=';
$instagram_handle = trim($content_data['social']['instagram_handle'] ?? '') ?: '@lyvs_haarstudio';
$facebook_url    = trim($content_data['social']['facebook']  ?? '');
$hours_days      = $content_data['hours']['days'] ?? $default_content['hours']['days'];
if (!is_array($hours_days)) {
  $hours_days = $default_content['hours']['days'];
}
$why_items = $content_data['why_us']['items'] ?? $default_content['why_us']['items'];
if (!is_array($why_items)) {
  $why_items = $default_content['why_us']['items'];
}
$about_paragraphs = $content_data['about']['paragraphs'] ?? [];
if (!is_array($about_paragraphs)) {
  $about_paragraphs = [];
}
$team_members = $content_data['team']['members'] ?? [];
if (!is_array($team_members)) {
  $team_members = [];
}

/* ── Image helpers ── */
function esc($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

function img_src($file) {
  return is_file(__DIR__ . '/' . $file) ? esc($file) : null;
}

/* SVG placeholders (data URIs) */
$placeholder_hero = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='800' height='540' viewBox='0 0 800 540'%3E%3Crect width='800' height='540' fill='%23f5e9dd'/%3E%3Crect x='240' y='140' width='320' height='240' rx='24' fill='%23e0cfc3'/%3E%3Ccircle cx='400' cy='210' r='55' fill='%23d0b9a8'/%3E%3Crect x='280' y='270' width='240' height='80' rx='20' fill='%23d0b9a8'/%3E%3Ctext x='400' y='420' font-family='Poppins,Arial,sans-serif' font-size='20' fill='%235b3a29' text-anchor='middle' opacity='0.6'%3EHero-Bild folgt%3C/text%3E%3C/svg%3E";
$placeholder_about = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='600' height='460' viewBox='0 0 600 460'%3E%3Crect width='600' height='460' fill='%23f5e9dd'/%3E%3Crect x='120' y='100' width='360' height='260' rx='20' fill='%23e0cfc3'/%3E%3Ccircle cx='300' cy='180' r='50' fill='%23d0b9a8'/%3E%3Crect x='160' y='245' width='280' height='90' rx='16' fill='%23d0b9a8'/%3E%3Ctext x='300' y='395' font-family='Poppins,Arial,sans-serif' font-size='18' fill='%235b3a29' text-anchor='middle' opacity='0.6'%3ESalon-Bild folgt%3C/text%3E%3C/svg%3E";
$placeholder_team = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='240' height='240' viewBox='0 0 240 240'%3E%3Crect width='240' height='240' fill='%23f5e9dd'/%3E%3Ccircle cx='120' cy='90' r='48' fill='%23d8c3b2'/%3E%3Crect x='55' y='148' width='130' height='58' rx='26' fill='%23d8c3b2'/%3E%3C/svg%3E";
$placeholder_gallery = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='400' height='300' viewBox='0 0 400 300'%3E%3Crect width='400' height='300' fill='%23f5e9dd'/%3E%3Crect x='90' y='80' width='220' height='160' rx='16' fill='%23e0cfc3'/%3E%3Ccircle cx='200' cy='155' r='38' fill='%23d0b9a8'/%3E%3Ccircle cx='200' cy='155' r='22' fill='%23c4a990'/%3E%3Crect x='155' y='84' width='32' height='22' rx='6' fill='%23d0b9a8'/%3E%3Ctext x='200' y='275' font-family='Poppins,Arial,sans-serif' font-size='16' fill='%235b3a29' text-anchor='middle' opacity='0.55'%3EBild folgt%3C/text%3E%3C/svg%3E";

$team_image_slots = [
  'images/team-lyv.jpg',
  'images/team-nova.jpg',
  'images/team-mika.jpg',
  'images/team-ella.jpg',
];
$gallery_slots = array_map(fn($n) => "images/gallery-{$n}.jpg", range(1, 6));

$page_title = esc($content_data['hero']['title'] ?? "Lyv's Haarstudio");
$meta_desc  = 'Lyv\'s Haarstudio in Leck – Friseurmeisterin Lyv Jensen. Spezialisiert auf Balayage, Strähnen und moderne Haarschnitte. Persönliche Beratung, warme Atmosphäre.';
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $page_title; ?> · Friseur in Leck</title>
    <meta name="description" content="<?php echo esc($meta_desc); ?>" />
    <meta name="robots" content="index, follow" />
    <link rel="canonical" href="https://lyvs-haarstudio.de/" />

    <!-- Open Graph -->
    <meta property="og:type"        content="website" />
    <meta property="og:title"       content="<?php echo $page_title; ?> – Friseur in Leck" />
    <meta property="og:description" content="<?php echo esc($meta_desc); ?>" />
    <meta property="og:locale"      content="de_DE" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Carattere&family=Poppins:ital,wght@0,400;0,500;0,600;1,400&display=swap" rel="stylesheet" />

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "HairSalon",
      "name": "Lyv's Haarstudio",
      "description": <?php echo json_encode($meta_desc, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>,
      "address": {
        "@type": "PostalAddress",
        "streetAddress": "<?php echo esc($content_data['contact']['address_line'] ?? 'Schafmarkt 2'); ?>",
        "addressLocality": "Leck",
        "postalCode": "25917",
        "addressCountry": "DE"
      },
      "telephone": "<?php echo esc($contact_phone); ?>",
      "email": "<?php echo esc($contact_email); ?>",
      "openingHoursSpecification": [
        {"@type":"OpeningHoursSpecification","dayOfWeek":["Monday","Tuesday","Wednesday","Thursday","Friday"],"opens":"09:00","closes":"18:00"}
      ],
      "sameAs": ["<?php echo esc($instagram_url); ?>"]
    }
    </script>

    <style>
      /* ── Design tokens ── */
      :root {
        --beige:       #f5e9dd;
        --beige-light: #fff7f0;
        --beige-mid:   #eedfd0;
        --brown:       #5b3a29;
        --accent:      #8b5e3c;
        --coffee:      #71523f;
        --heading-font: "Carattere", "Playfair Display", serif;
        --body-font:    "Poppins", "Helvetica Neue", Arial, sans-serif;
        --max-width:    1200px;
        --radius-sm:    12px;
        --radius-md:    20px;
        --radius-lg:    32px;
        --shadow-soft:  0 12px 30px rgba(91,58,41,.09);
        --shadow-card:  0 20px 45px rgba(91,58,41,.10);
        --transition:   0.28s ease;
      }

      *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

      html { scroll-behavior: smooth; }

      body {
        font-family: var(--body-font);
        font-weight: 400;
        background: var(--beige);
        color: var(--brown);
        line-height: 1.7;
        position: relative;
      }

      a {
        color: var(--coffee);
        text-decoration: none;
        transition: color var(--transition);
      }
      a:not(.btn):hover,
      a:not(.btn):focus-visible { color: var(--accent); }

      /* ── Sticky header ── */
      header {
        position: sticky;
        top: 0;
        z-index: 999;
        background: rgba(245,233,221,.96);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
        border-bottom: 1px solid rgba(91,58,41,.10);
        transition: box-shadow var(--transition);
      }
      header.scrolled { box-shadow: 0 4px 20px rgba(91,58,41,.12); }

      .nav-container {
        max-width: var(--max-width);
        margin: 0 auto;
        padding: .8rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
      }

      .logo {
        font-family: var(--heading-font);
        font-size: clamp(1.55rem, 3.5vw, 2.2rem);
        letter-spacing: 1px;
        color: var(--brown);
        flex-shrink: 0;
      }

      nav { margin-left: auto; position: relative; }

      nav ul {
        list-style: none;
        display: flex;
        gap: 1.4rem;
        font-size: .9rem;
        font-weight: 500;
      }

      .nav-link {
        position: relative;
        padding-bottom: .18rem;
        color: var(--brown);
        transition: color var(--transition);
      }
      .nav-link::after {
        content: "";
        position: absolute;
        left: 0; bottom: 0;
        width: 100%; height: 2px;
        background: var(--accent);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .3s ease;
      }
      .nav-link:hover::after,
      .nav-link:focus-visible::after { transform: scaleX(1); }

      .nav-cta {
        margin-left: .8rem;
        padding: .45rem 1.1rem;
        border-radius: 999px;
        background: var(--accent);
        color: #fff !important;
        font-size: .85rem;
        font-weight: 600;
        transition: transform var(--transition), box-shadow var(--transition);
        box-shadow: 0 6px 18px rgba(139,94,60,.28);
      }
      .nav-cta:hover { transform: translateY(-2px); box-shadow: 0 10px 24px rgba(139,94,60,.35); }

      .nav-toggle {
        display: none;
        background: none;
        border: 1.5px solid rgba(91,58,41,.22);
        padding: .32rem .48rem;
        border-radius: 8px;
        cursor: pointer;
        margin-left: auto;
      }
      .nav-toggle span {
        display: block;
        width: 22px; height: 2px;
        background: var(--brown);
        margin: 4px 0;
        transition: transform .3s ease, opacity .3s ease;
      }

      main { overflow: hidden; }

      /* ── Buttons ── */
      .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: .45rem;
        padding: .9rem 2.2rem;
        border-radius: 999px;
        border: none;
        font-weight: 600;
        font-family: var(--body-font);
        font-size: .95rem;
        cursor: pointer;
        text-decoration: none;
        transition: transform var(--transition), box-shadow var(--transition), background var(--transition);
        white-space: nowrap;
      }
      .btn-primary {
        background: var(--accent);
        color: #fff;
        box-shadow: 0 10px 28px rgba(139,94,60,.28);
      }
      .btn-primary:hover,
      .btn-primary:focus-visible {
        transform: translateY(-3px);
        box-shadow: 0 16px 36px rgba(139,94,60,.38);
        color: #fff;
      }
      .btn-secondary {
        background: rgba(255,255,255,.65);
        color: var(--brown);
        border: 1.5px solid rgba(91,58,41,.2);
        box-shadow: none;
      }
      .btn-secondary:hover,
      .btn-secondary:focus-visible {
        transform: translateY(-3px);
        background: rgba(255,255,255,.9);
        color: var(--brown);
      }
      .btn-outline {
        background: transparent;
        color: var(--accent);
        border: 1.5px solid var(--accent);
        box-shadow: none;
      }
      .btn-outline:hover,
      .btn-outline:focus-visible {
        background: var(--accent);
        color: #fff;
        transform: translateY(-3px);
      }

      /* ── Section base ── */
      .section-wrap {
        max-width: var(--max-width);
        margin: 0 auto;
        padding: 5rem 1.5rem;
      }

      .section-label {
        text-transform: uppercase;
        letter-spacing: .22em;
        font-size: .75rem;
        color: var(--accent);
        font-weight: 600;
        margin-bottom: .7rem;
        display: block;
      }

      .section-title {
        font-family: var(--heading-font);
        font-size: clamp(2rem, 5vw, 3rem);
        line-height: 1.15;
        margin-bottom: 1.1rem;
      }

      .section-intro {
        max-width: 620px;
        color: var(--coffee);
        font-size: .97rem;
        margin-bottom: 2.5rem;
        line-height: 1.75;
      }

      /* ── Divider ornament ── */
      .ornament {
        display: flex;
        align-items: center;
        gap: .9rem;
        margin-bottom: 1.2rem;
        color: var(--accent);
        font-size: .8rem;
        opacity: .7;
      }
      .ornament::before, .ornament::after {
        content: "";
        flex: 1;
        height: 1px;
        background: currentColor;
        opacity: .4;
      }

      /* ── Hero ── */
      .hero {
        position: relative;
        background: linear-gradient(140deg, rgba(91,58,41,.06), rgba(139,94,60,.13));
        padding: 7rem 1.5rem 5.5rem;
        overflow: hidden;
      }
      /* Decorative blobs */
      .hero::before {
        content: "";
        position: absolute;
        width: 420px; height: 420px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(139,94,60,.12), transparent 70%);
        top: -120px; right: -100px;
        pointer-events: none;
      }
      .hero::after {
        content: "";
        position: absolute;
        width: 280px; height: 280px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(91,58,41,.08), transparent 70%);
        bottom: -80px; left: -60px;
        pointer-events: none;
      }

      .hero-inner {
        max-width: var(--max-width);
        margin: 0 auto;
        display: grid;
        gap: 3rem;
        align-items: center;
        position: relative;
        z-index: 1;
      }

      .hero-copy .tagline {
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: .22em;
        font-size: .8rem;
        color: var(--accent);
        font-weight: 600;
        border: 1px solid rgba(139,94,60,.3);
        border-radius: 999px;
        padding: .3rem .9rem;
        margin-bottom: 1.3rem;
      }

      .hero-copy h1 {
        font-family: var(--heading-font);
        font-size: clamp(2.8rem, 7vw, 4.8rem);
        line-height: 1.1;
        margin-bottom: 1.2rem;
      }

      .hero-copy .hero-text {
        font-size: 1.05rem;
        max-width: 520px;
        color: var(--coffee);
        line-height: 1.75;
        margin-bottom: 2rem;
      }

      .hero-cta {
        display: flex;
        flex-wrap: wrap;
        gap: .9rem;
        align-items: center;
      }

      .hero-image {
        position: relative;
        border-radius: 36px 36px 96px 36px;
        overflow: hidden;
        min-height: 380px;
        background: var(--beige-mid);
        box-shadow: 0 30px 60px rgba(91,58,41,.14);
      }
      .hero-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        position: absolute;
        inset: 0;
      }
      .hero-image .placeholder-wrap {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: var(--beige-mid);
      }
      .hero-image .placeholder-wrap svg { opacity: .5; }
      .hero-image .placeholder-label {
        margin-top: .7rem;
        font-size: .8rem;
        color: var(--coffee);
        opacity: .7;
        text-align: center;
      }

      /* Floating badge */
      .hero-badge {
        position: absolute;
        bottom: 2rem;
        left: 2rem;
        background: rgba(255,255,255,.9);
        backdrop-filter: blur(8px);
        border-radius: var(--radius-md);
        padding: .9rem 1.2rem;
        box-shadow: 0 12px 28px rgba(91,58,41,.12);
        display: flex;
        align-items: center;
        gap: .8rem;
        z-index: 2;
      }
      .hero-badge-icon { font-size: 1.5rem; }
      .hero-badge-text { font-size: .78rem; line-height: 1.4; }
      .hero-badge-text strong { display: block; font-size: .9rem; }

      /* ── Section backgrounds ── */
      .bg-white { background: #fff; }
      .bg-beige  { background: var(--beige); }
      .bg-mid    { background: var(--beige-mid); }
      .bg-dark   { background: var(--brown); color: var(--beige-light); }
      .bg-dark .section-title { color: var(--beige-light); }
      .bg-dark .section-intro { color: rgba(255,247,240,.75); }
      .bg-dark .section-label { color: rgba(255,247,240,.6); }

      /* ── About ── */
      .about-layout {
        display: grid;
        gap: 3.5rem;
        align-items: center;
      }
      .about-image {
        border-radius: 36px 36px 36px 96px;
        overflow: hidden;
        min-height: 380px;
        background: var(--beige-mid);
        position: relative;
        box-shadow: var(--shadow-card);
      }
      .about-image img {
        width: 100%; height: 100%;
        object-fit: cover;
        position: absolute;
        inset: 0;
      }
      .about-image .placeholder-wrap {
        position: absolute; inset: 0;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        background: var(--beige-mid);
      }
      .about-image .placeholder-wrap svg { opacity: .45; }
      .about-image .placeholder-label {
        margin-top: .7rem;
        font-size: .8rem;
        color: var(--coffee);
        opacity: .65;
        text-align: center;
      }
      .about-copy p { color: var(--coffee); margin-bottom: 1rem; line-height: 1.8; }
      .about-copy p:last-of-type { margin-bottom: 1.6rem; }

      /* Trust badges */
      .trust-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1.6rem;
      }
      .trust-badge {
        display: flex;
        align-items: center;
        gap: .45rem;
        padding: .45rem .9rem;
        border-radius: 999px;
        background: var(--beige);
        border: 1px solid rgba(91,58,41,.12);
        font-size: .82rem;
        font-weight: 500;
      }
      .trust-badge .icon { font-size: 1rem; }

      /* ── Why Us ── */
      .why-grid {
        display: grid;
        gap: 1.5rem;
      }
      .why-card {
        background: #fff;
        border-radius: var(--radius-md);
        padding: 2rem 1.6rem;
        border: 1px solid rgba(91,58,41,.07);
        box-shadow: var(--shadow-soft);
        transition: transform var(--transition), box-shadow var(--transition);
      }
      .why-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-card);
      }
      .why-card .why-icon {
        font-size: 2rem;
        margin-bottom: .9rem;
        display: block;
      }
      .why-card h3 {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: .5rem;
        color: var(--brown);
      }
      .why-card p {
        font-size: .88rem;
        color: var(--coffee);
        line-height: 1.7;
      }

      /* ── Services ── */
      .services-grid {
        display: grid;
        gap: 1.4rem;
        margin-top: 2rem;
      }
      .service-card {
        background: #fff;
        border-radius: var(--radius-md);
        padding: 1.8rem;
        border: 1px solid rgba(91,58,41,.07);
        box-shadow: var(--shadow-soft);
        transition: transform var(--transition), box-shadow var(--transition);
        position: relative;
        overflow: hidden;
      }
      .service-card::before {
        content: "";
        position: absolute;
        top: 0; left: 0;
        width: 4px; height: 100%;
        background: var(--accent);
        opacity: 0;
        transition: opacity var(--transition);
      }
      .service-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-card); }
      .service-card:hover::before { opacity: 1; }
      .service-card h3 {
        font-family: var(--heading-font);
        font-size: 1.7rem;
        margin-bottom: .6rem;
        color: var(--brown);
      }
      .price-list {
        list-style: none;
        margin-top: .8rem;
      }
      .price-list li {
        display: flex;
        justify-content: space-between;
        padding: .38rem 0;
        border-bottom: 1px solid rgba(91,58,41,.09);
        font-size: .88rem;
        color: var(--coffee);
        gap: .5rem;
      }
      .price-list li:last-child { border-bottom: none; }
      .price-list .price-val {
        font-weight: 600;
        color: var(--brown);
        white-space: nowrap;
      }
      .service-featured { border-color: rgba(139,94,60,.18); }
      .service-featured::after {
        content: "Highlight";
        position: absolute;
        top: 1rem; right: 1rem;
        background: var(--accent);
        color: #fff;
        font-size: .68rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .12em;
        padding: .2rem .55rem;
        border-radius: 999px;
      }

      /* ── Gallery ── */
      .gallery-grid {
        display: grid;
        gap: 1rem;
        margin-top: 2rem;
      }
      .gallery-item {
        border-radius: var(--radius-md);
        overflow: hidden;
        background: var(--beige-mid);
        aspect-ratio: 4/3;
        position: relative;
        box-shadow: var(--shadow-soft);
        transition: transform var(--transition), box-shadow var(--transition);
      }
      .gallery-item:hover {
        transform: scale(1.02);
        box-shadow: var(--shadow-card);
        z-index: 2;
      }
      .gallery-item img {
        width: 100%; height: 100%;
        object-fit: cover;
        position: absolute;
        inset: 0;
        transition: transform .5s ease;
      }
      .gallery-item:hover img { transform: scale(1.05); }
      .gallery-item .placeholder-wrap {
        position: absolute; inset: 0;
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        background: var(--beige-mid);
      }
      .gallery-item .placeholder-wrap svg { opacity: .5; }
      .gallery-item .placeholder-label {
        margin-top: .5rem;
        font-size: .75rem;
        color: var(--coffee);
        opacity: .55;
      }

      /* ── Team ── */
      .team-grid {
        display: grid;
        gap: 1.4rem;
        margin-top: 2rem;
      }
      .team-card {
        background: var(--beige-light);
        border-radius: var(--radius-md);
        padding: 2.2rem 1.4rem;
        text-align: center;
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(91,58,41,.07);
        transition: transform var(--transition), box-shadow var(--transition);
        position: relative;
        overflow: hidden;
      }
      .team-card:hover { transform: translateY(-5px); box-shadow: var(--shadow-card); }
      .team-card .team-photo {
        width: 110px; height: 110px;
        border-radius: 50%;
        overflow: hidden;
        margin: 0 auto 1.1rem;
        border: 4px solid var(--beige);
        background: var(--beige-mid);
        box-shadow: 0 8px 20px rgba(91,58,41,.12);
      }
      .team-card .team-photo img {
        width: 100%; height: 100%;
        object-fit: cover;
        display: block;
      }
      .team-card .team-photo .placeholder-wrap {
        width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center;
        background: var(--beige-mid);
      }
      .team-card h3 {
        font-family: var(--heading-font);
        font-size: 1.65rem;
        margin-bottom: .2rem;
      }
      .team-card .team-role {
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .14em;
        color: var(--accent);
        font-weight: 600;
        margin-bottom: .7rem;
      }
      .team-card .team-bio {
        font-size: .87rem;
        color: var(--coffee);
        line-height: 1.7;
      }

      /* ── Appointment CTA ── */
      .appt-section {
        border-radius: var(--radius-lg);
        padding: 4rem 2rem;
        text-align: center;
        background: linear-gradient(135deg, rgba(139,94,60,.08), rgba(91,58,41,.14));
        border: 1px solid rgba(91,58,41,.1);
        box-shadow: var(--shadow-card);
      }
      .appt-section .section-title { margin-bottom: .9rem; }
      .appt-section p { color: var(--coffee); max-width: 520px; margin: 0 auto .5rem; }
      .appt-cta-row {
        display: flex;
        flex-wrap: wrap;
        gap: .9rem;
        justify-content: center;
        margin-top: 2rem;
      }
      .appt-note {
        margin-top: 1.2rem !important;
        font-size: .85rem;
        opacity: .75;
      }

      /* ── Contact / Map ── */
      .contact-layout {
        display: grid;
        gap: 2rem;
        margin-top: 2rem;
      }
      .contact-card {
        background: #fff;
        border-radius: var(--radius-md);
        padding: 2.2rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(91,58,41,.07);
      }
      .contact-card h3 {
        font-family: var(--heading-font);
        font-size: 1.7rem;
        margin-bottom: 1.2rem;
      }
      .contact-item {
        display: flex;
        align-items: flex-start;
        gap: .75rem;
        margin-bottom: .9rem;
        font-size: .92rem;
      }
      .contact-item .ci-icon {
        font-size: 1.1rem;
        flex-shrink: 0;
        margin-top: .1rem;
      }
      .contact-item a { color: var(--coffee); }
      .contact-item a:hover { color: var(--accent); }

      .cta-map-row {
        display: flex;
        flex-wrap: wrap;
        gap: .8rem;
        margin-top: 1.5rem;
      }

      /* Map consent / embed */
      .map-wrap {
        margin-top: 1.6rem;
        border-radius: var(--radius-md);
        overflow: hidden;
        background: var(--beige-mid);
        min-height: 240px;
        position: relative;
      }
      .map-consent {
        position: absolute; inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        padding: 1.5rem;
        text-align: center;
        background: var(--beige-mid);
        z-index: 2;
      }
      .map-consent.hidden { display: none; }
      .map-consent p {
        font-size: .83rem;
        color: var(--coffee);
        max-width: 300px;
      }
      .map-iframe {
        display: none;
        width: 100%;
        height: 280px;
        border: none;
      }
      .map-iframe.loaded { display: block; }

      /* Hours card */
      .hours-card {
        background: #fff;
        border-radius: var(--radius-md);
        padding: 2.2rem;
        box-shadow: var(--shadow-soft);
        border: 1px solid rgba(91,58,41,.07);
      }
      .hours-card h3 {
        font-family: var(--heading-font);
        font-size: 1.7rem;
        margin-bottom: 1.2rem;
      }
      .hours-table {
        width: 100%;
        border-collapse: collapse;
      }
      .hours-table tr { transition: background var(--transition); }
      .hours-table tr:hover { background: var(--beige); }
      .hours-table td {
        padding: .5rem .3rem;
        border-bottom: 1px solid rgba(91,58,41,.08);
        font-size: .9rem;
        color: var(--coffee);
      }
      .hours-table td:last-child { text-align: right; font-weight: 500; }
      .hours-table tr:last-child td { border-bottom: none; }
      .closed-row td { opacity: .5; }

      /* ── Footer ── */
      footer {
        background: var(--brown);
        color: rgba(255,247,240,.82);
        padding: 3.5rem 1.5rem 2rem;
        margin-top: 0;
      }
      .footer-inner {
        max-width: var(--max-width);
        margin: 0 auto;
      }
      .footer-grid {
        display: grid;
        gap: 2.5rem;
        margin-bottom: 2.5rem;
      }
      .footer-logo {
        font-family: var(--heading-font);
        font-size: 2rem;
        color: var(--beige-light);
        display: block;
        margin-bottom: .7rem;
      }
      .footer-tagline {
        font-size: .83rem;
        opacity: .65;
        margin-bottom: 1.2rem;
      }
      .footer-social {
        display: flex;
        gap: .8rem;
        flex-wrap: wrap;
      }
      .footer-social a {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        padding: .38rem .8rem;
        border-radius: 999px;
        border: 1px solid rgba(255,247,240,.2);
        color: rgba(255,247,240,.8);
        font-size: .8rem;
        transition: border-color var(--transition), color var(--transition);
      }
      .footer-social a:hover { border-color: rgba(255,247,240,.5); color: #fff; }

      .footer-col h4 {
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .16em;
        color: rgba(255,247,240,.5);
        margin-bottom: .9rem;
      }
      .footer-col a, .footer-col p {
        display: block;
        color: rgba(255,247,240,.72);
        font-size: .88rem;
        margin-bottom: .4rem;
        transition: color var(--transition);
      }
      .footer-col a:hover { color: var(--beige-light); }

      .footer-bottom {
        border-top: 1px solid rgba(255,247,240,.1);
        padding-top: 1.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: .8rem;
        justify-content: space-between;
        align-items: center;
        font-size: .8rem;
        color: rgba(255,247,240,.45);
      }
      .footer-bottom a { color: rgba(255,247,240,.45); }
      .footer-bottom a:hover { color: rgba(255,247,240,.75); }
      .footer-legal { display: flex; flex-wrap: wrap; gap: 1rem; }

      /* ── Reveal animations ── */
      .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity .65s ease, transform .65s ease;
      }
      .reveal.visible {
        opacity: 1;
        transform: translateY(0);
      }
      .delay-1 { transition-delay: .12s; }
      .delay-2 { transition-delay: .24s; }
      .delay-3 { transition-delay: .36s; }

      /* ── Responsive ── */
      @media (min-width: 640px) {
        .hero-inner       { grid-template-columns: 1fr 1fr; }
        .about-layout     { grid-template-columns: 1fr 1fr; }
        .why-grid         { grid-template-columns: 1fr 1fr; }
        .team-grid        { grid-template-columns: 1fr 1fr; }
        .services-grid    { grid-template-columns: 1fr 1fr; }
        .gallery-grid     { grid-template-columns: 1fr 1fr; }
        .contact-layout   { grid-template-columns: 1fr 1fr; }
        .footer-grid      { grid-template-columns: 1.5fr 1fr 1fr; }
      }
      @media (min-width: 1000px) {
        .team-grid        { grid-template-columns: repeat(4, 1fr); }
        .services-grid    { grid-template-columns: repeat(3, 1fr); }
        .gallery-grid     { grid-template-columns: repeat(3, 1fr); }
        .why-grid         { grid-template-columns: repeat(4, 1fr); }
      }
      @media (max-width: 1024px) {
        nav ul {
          position: absolute;
          top: calc(100% + .5rem);
          right: 1.5rem;
          background: var(--beige-light);
          flex-direction: column;
          gap: .6rem;
          padding: 1.2rem 1.6rem;
          border-radius: var(--radius-md);
          box-shadow: 0 20px 50px rgba(91,58,41,.22);
          border: 1px solid rgba(91,58,41,.1);
          opacity: 0;
          pointer-events: none;
          transform: translateY(-8px);
          transition: opacity .3s ease, transform .3s ease;
          min-width: 200px;
        }
        nav ul.open {
          opacity: 1;
          pointer-events: auto;
          transform: translateY(0);
        }
        .nav-cta { display: none; }
        .nav-toggle { display: block; }
        nav { margin-left: 0; }
      }
      @media (max-width: 640px) {
        .hero { padding: 5rem 1.5rem 4rem; }
        .section-wrap { padding: 3.5rem 1.5rem; }
        .hero-badge { bottom: 1rem; left: 1rem; }
        .appt-section { padding: 2.5rem 1.5rem; }
        .gallery-grid { grid-template-columns: 1fr 1fr; }
      }

      @keyframes float {
        0%, 100% { transform: translateY(0); }
        50%       { transform: translateY(-8px); }
      }

      @media (prefers-reduced-motion: reduce) {
        *, *::before, *::after {
          animation: none !important;
          transition-duration: .01ms !important;
        }
      }
    </style>
  </head>
  <body>

    <!-- ══ HEADER ══════════════════════════════════════════════════ -->
    <header id="site-header">
      <div class="nav-container">
        <a class="logo" href="#home"><?php echo esc($content_data['hero']['title'] ?? "Lyv's Haarstudio"); ?></a>
        <button class="nav-toggle" aria-label="Navigation öffnen" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>
        <nav aria-label="Hauptnavigation">
          <ul>
            <li><a class="nav-link" href="#about">Über uns</a></li>
            <li><a class="nav-link" href="#services">Leistungen</a></li>
            <li><a class="nav-link" href="#gallery">Impressionen</a></li>
            <li><a class="nav-link" href="#contact">Kontakt</a></li>
            <li><a class="nav-link nav-cta" href="tel:<?php echo $phone_number; ?>">📞 Anrufen</a></li>
          </ul>
        </nav>
      </div>
    </header>

    <main>

      <!-- ══ HERO ═══════════════════════════════════════════════════ -->
      <section class="hero" id="home" aria-labelledby="hero-heading">
        <div class="hero-inner">
          <!-- Copy -->
          <div class="hero-copy reveal">
            <span class="tagline"><?php echo esc($content_data['hero']['tagline'] ?? ''); ?></span>
            <h1 id="hero-heading"><?php echo esc($content_data['hero']['title'] ?? "Lyv's Haarstudio"); ?></h1>
            <p class="hero-text"><?php echo nl2br(esc($content_data['hero']['text'] ?? '')); ?></p>
            <div class="hero-cta">
              <a class="btn btn-primary" href="#appointment">Termin anfragen</a>
              <a class="btn btn-secondary" href="tel:<?php echo $phone_number; ?>">📞 <?php echo esc($contact_phone); ?></a>
              <a class="btn btn-outline" href="<?php echo esc($route_url); ?>" target="_blank" rel="noreferrer noopener">🗺 Route</a>
            </div>
          </div>

          <!-- Image -->
          <div class="hero-image reveal delay-2" role="img" aria-label="Impression aus dem Salon">
            <?php $hi = img_src('images/hero.jpg'); ?>
            <?php if ($hi): ?>
              <img src="<?php echo $hi; ?>" alt="Lyv's Haarstudio – Salon Impression" loading="eager" />
            <?php else: ?>
              <div class="placeholder-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" width="120" height="80" viewBox="0 0 120 80" fill="none">
                  <rect x="10" y="20" width="100" height="50" rx="10" fill="#d0b9a8"/>
                  <circle cx="60" cy="42" r="18" fill="#c4a990"/>
                  <circle cx="60" cy="42" r="10" fill="#b89880"/>
                  <rect x="40" y="22" width="18" height="12" rx="4" fill="#c4a990"/>
                </svg>
                <span class="placeholder-label">Hero-Foto folgt</span>
              </div>
            <?php endif; ?>

            <!-- Floating badge -->
            <div class="hero-badge">
              <span class="hero-badge-icon">⭐</span>
              <div class="hero-badge-text">
                <strong>Strähnen & Balayage</strong>
                Spezialistinnen in Leck
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ══ ABOUT ═══════════════════════════════════════════════════ -->
      <section class="bg-white" id="about" aria-labelledby="about-heading">
        <div class="section-wrap">
          <div class="about-layout">
            <!-- Image -->
            <div class="about-image reveal">
              <?php $si = img_src('images/salon.jpg'); ?>
              <?php if ($si): ?>
                <img src="<?php echo $si; ?>" alt="Salon Impression" loading="lazy" />
              <?php else: ?>
                <div class="placeholder-wrap">
                  <svg xmlns="http://www.w3.org/2000/svg" width="110" height="80" viewBox="0 0 110 80" fill="none">
                    <rect x="8" y="15" width="94" height="55" rx="9" fill="#d8c3b2"/>
                    <circle cx="55" cy="38" r="16" fill="#c4a990"/>
                    <rect x="22" y="52" width="66" height="12" rx="5" fill="#c4a990"/>
                  </svg>
                  <span class="placeholder-label">Salon-Foto folgt</span>
                </div>
              <?php endif; ?>
            </div>

            <!-- Copy -->
            <div class="about-copy reveal delay-1">
              <span class="section-label">Unsere Geschichte</span>
              <h2 class="section-title" id="about-heading"><?php echo esc($content_data['about']['title'] ?? 'Unsere Geschichte'); ?></h2>
              <?php foreach ($about_paragraphs as $p): ?>
                <p><?php echo nl2br(esc($p)); ?></p>
              <?php endforeach; ?>
              <div class="trust-row">
                <span class="trust-badge"><span class="icon">🏆</span> Friseurmeisterin</span>
                <span class="trust-badge"><span class="icon">📍</span> Seit Jahren in Leck</span>
                <span class="trust-badge"><span class="icon">✂️</span> Ehem. Salon Jutta</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- ══ WHY US ═══════════════════════════════════════════════════ -->
      <section class="bg-beige" id="why" aria-labelledby="why-heading">
        <div class="section-wrap">
          <div style="text-align:center; max-width:600px; margin:0 auto 3rem;">
            <span class="section-label reveal">Warum wir</span>
            <h2 class="section-title reveal delay-1" id="why-heading"><?php echo esc($content_data['why_us']['title'] ?? "Warum Lyv's Haarstudio?"); ?></h2>
          </div>
          <div class="why-grid">
            <?php foreach ($why_items as $idx => $item): ?>
              <?php $delay = $idx === 0 ? '' : ($idx === 1 ? ' delay-1' : ($idx === 2 ? ' delay-2' : ' delay-3')); ?>
              <div class="why-card reveal<?php echo $delay; ?>">
                <span class="why-icon" role="img" aria-label="<?php echo esc($item['title'] ?? ''); ?>"><?php echo esc($item['icon'] ?? '✨'); ?></span>
                <h3><?php echo esc($item['title'] ?? ''); ?></h3>
                <p><?php echo nl2br(esc($item['text'] ?? '')); ?></p>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <!-- ══ SERVICES ═══════════════════════════════════════════════════ -->
      <section class="bg-white" id="services" aria-labelledby="services-heading">
        <div class="section-wrap">
          <span class="section-label reveal">Unsere Leistungen</span>
          <h2 class="section-title reveal delay-1" id="services-heading"><?php echo esc($content_data['services']['title'] ?? 'Leistungen & Preise'); ?></h2>
          <p class="section-intro reveal delay-2"><?php echo nl2br(esc($content_data['services']['intro'] ?? '')); ?></p>
          <div class="services-grid">
            <?php foreach ($prices_data['categories'] as $idx => $cat): ?>
              <?php
              $delay = $idx % 3 === 1 ? ' delay-1' : ($idx % 3 === 2 ? ' delay-2' : '');
              $featured = in_array($cat['title'] ?? '', ['Farbe & Glanz'], true);
              ?>
              <article class="service-card reveal<?php echo $delay; ?><?php echo $featured ? ' service-featured' : ''; ?>">
                <h3><?php echo esc($cat['title'] ?? ''); ?></h3>
                <ul class="price-list">
                  <?php foreach (($cat['items'] ?? []) as $item): ?>
                    <li>
                      <span><?php echo esc($item['label'] ?? ''); ?></span>
                      <span class="price-val"><?php echo esc($item['price'] ?? ''); ?></span>
                    </li>
                  <?php endforeach; ?>
                </ul>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <!-- ══ GALLERY ═══════════════════════════════════════════════════ -->
      <section class="bg-mid" id="gallery" aria-labelledby="gallery-heading">
        <div class="section-wrap">
          <div style="text-align:center; max-width:600px; margin:0 auto 2rem;">
            <span class="section-label reveal">Galerie</span>
            <h2 class="section-title reveal delay-1" id="gallery-heading"><?php echo esc($content_data['gallery']['title'] ?? 'Impressionen'); ?></h2>
            <p class="section-intro reveal delay-2" style="margin:0 auto"><?php echo nl2br(esc($content_data['gallery']['intro'] ?? '')); ?></p>
          </div>
          <div class="gallery-grid">
            <?php foreach ($gallery_slots as $gi => $gslot): ?>
              <?php
              $gdelay = $gi % 3 === 1 ? ' delay-1' : ($gi % 3 === 2 ? ' delay-2' : '');
              $gsrc = img_src($gslot);
              ?>
              <div class="gallery-item reveal<?php echo $gdelay; ?>">
                <?php if ($gsrc): ?>
                  <img src="<?php echo $gsrc; ?>" alt="Salon Impression <?php echo $gi + 1; ?>" loading="lazy" />
                <?php else: ?>
                  <div class="placeholder-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="56" viewBox="0 0 72 56" fill="none">
                      <rect x="4" y="10" width="64" height="40" rx="7" fill="#d0b9a8"/>
                      <circle cx="36" cy="29" r="11" fill="#c4a990"/>
                      <circle cx="36" cy="29" r="6"  fill="#b89880"/>
                      <rect x="22" y="11" width="10" height="7" rx="2.5" fill="#c4a990"/>
                    </svg>
                    <span class="placeholder-label">Bild <?php echo $gi + 1; ?> folgt</span>
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <!-- ══ TEAM ═══════════════════════════════════════════════════ -->
      <section class="bg-white" id="team" aria-labelledby="team-heading">
        <div class="section-wrap">
          <span class="section-label reveal">Unser Team</span>
          <h2 class="section-title reveal delay-1" id="team-heading"><?php echo esc($content_data['team']['title'] ?? 'Das Team'); ?></h2>
          <p class="section-intro reveal delay-2"><?php echo nl2br(esc($content_data['team']['intro'] ?? '')); ?></p>
          <div class="team-grid">
            <?php foreach ($team_members as $ti => $member): ?>
              <?php
              $tdelay = $ti % 4 === 1 ? ' delay-1' : ($ti % 4 === 2 ? ' delay-2' : ($ti % 4 === 3 ? ' delay-3' : ''));
              $timg = img_src($team_image_slots[$ti] ?? '');
              $tname = $member['name'] ?? 'Teammitglied';
              ?>
              <article class="team-card reveal<?php echo $tdelay; ?>">
                <div class="team-photo">
                  <?php if ($timg): ?>
                    <img src="<?php echo $timg; ?>" alt="<?php echo esc($tname); ?>" loading="lazy" />
                  <?php else: ?>
                    <div class="placeholder-wrap">
                      <svg xmlns="http://www.w3.org/2000/svg" width="58" height="58" viewBox="0 0 58 58" fill="none">
                        <circle cx="29" cy="22" r="14" fill="#d8c3b2"/>
                        <rect x="8" y="40" width="42" height="14" rx="7" fill="#d8c3b2"/>
                      </svg>
                    </div>
                  <?php endif; ?>
                </div>
                <h3><?php echo esc($tname); ?></h3>
                <p class="team-role"><?php echo esc($member['role'] ?? ''); ?></p>
                <p class="team-bio"><?php echo nl2br(esc($member['bio'] ?? '')); ?></p>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      </section>

      <!-- ══ APPOINTMENT CTA ════════════════════════════════════════ -->
      <section class="bg-beige" id="appointment" aria-labelledby="appt-heading">
        <div class="section-wrap">
          <div class="appt-section reveal">
            <span class="section-label">Jetzt buchen</span>
            <h2 class="section-title" id="appt-heading"><?php echo esc($content_data['appointment']['title'] ?? 'Termin vereinbaren'); ?></h2>
            <p><?php echo nl2br(esc($content_data['appointment']['text'] ?? '')); ?></p>
            <div class="appt-cta-row">
              <a class="btn btn-primary" href="tel:<?php echo $phone_number; ?>">📞 Jetzt anrufen</a>
              <a class="btn btn-secondary" href="mailto:<?php echo esc($contact_email); ?>">✉️ E-Mail schreiben</a>
              <a class="btn btn-outline" href="<?php echo esc($instagram_url); ?>" target="_blank" rel="noreferrer noopener">📸 Instagram</a>
            </div>
            <p class="appt-note"><?php echo nl2br(esc($content_data['appointment']['note'] ?? '')); ?></p>
          </div>
        </div>
      </section>

      <!-- ══ CONTACT ════════════════════════════════════════════════ -->
      <section class="bg-white" id="contact" aria-labelledby="contact-heading">
        <div class="section-wrap">
          <span class="section-label reveal">Besuche uns</span>
          <h2 class="section-title reveal delay-1" id="contact-heading"><?php echo esc($content_data['contact']['title'] ?? 'Kontakt & Anfahrt'); ?></h2>
          <div class="contact-layout">

            <!-- Contact info + map -->
            <div class="contact-card reveal">
              <h3><?php echo esc($content_data['contact']['studio_name'] ?? "Lyv's Haarstudio"); ?></h3>

              <div class="contact-item">
                <span class="ci-icon" aria-hidden="true">📍</span>
                <div>
                  <?php echo esc($content_data['contact']['address_line'] ?? 'Schafmarkt 2'); ?><br />
                  <?php echo esc($contact_city); ?>
                </div>
              </div>
              <div class="contact-item">
                <span class="ci-icon" aria-hidden="true">📞</span>
                <a href="tel:<?php echo $phone_number; ?>"><?php echo esc($contact_phone); ?></a>
              </div>
              <div class="contact-item">
                <span class="ci-icon" aria-hidden="true">✉️</span>
                <a href="mailto:<?php echo esc($contact_email); ?>"><?php echo esc($contact_email); ?></a>
              </div>
              <?php if ($instagram_url): ?>
              <div class="contact-item">
                <span class="ci-icon" aria-hidden="true">📸</span>
                <a href="<?php echo esc($instagram_url); ?>" target="_blank" rel="noreferrer noopener"><?php echo esc($instagram_handle); ?></a>
              </div>
              <?php endif; ?>

              <div class="cta-map-row">
                <a class="btn btn-primary" href="tel:<?php echo $phone_number; ?>">Anrufen</a>
                <a class="btn btn-outline" href="<?php echo esc($route_url); ?>" target="_blank" rel="noreferrer noopener">🗺 Route planen</a>
              </div>

              <!-- Map with DSGVO consent -->
              <div class="map-wrap" id="map-wrap">
                <div class="map-consent" id="map-consent">
                  <span style="font-size:2rem">🗺</span>
                  <p>Die Karte wird über OpenStreetMap geladen. Dabei wird deine IP-Adresse an openstreetmap.org übertragen.</p>
                  <button class="btn btn-outline" id="map-load-btn">Karte laden</button>
                </div>
                <iframe
                  id="osm-iframe"
                  class="map-iframe"
                  title="Karte: Lyv's Haarstudio, Schafmarkt 2, 25917 Leck"
                  loading="lazy"
                  allowfullscreen
                  referrerpolicy="no-referrer"
                  aria-label="OpenStreetMap Karte"
                ></iframe>
              </div>
            </div>

            <!-- Opening hours -->
            <div class="hours-card reveal delay-1">
              <h3><?php echo esc($content_data['hours']['title'] ?? 'Öffnungszeiten'); ?></h3>
              <table class="hours-table">
                <tbody>
                  <?php foreach ($hours_days as $hday): ?>
                    <?php
                    $hclosed = stripos((string)($hday['hours'] ?? ''), 'geschlossen') !== false;
                    ?>
                    <tr<?php echo $hclosed ? ' class="closed-row"' : ''; ?>>
                      <td><?php echo esc($hday['day'] ?? ''); ?></td>
                      <td><?php echo esc($hday['hours'] ?? ''); ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>

              <div style="margin-top:1.8rem; padding-top:1.4rem; border-top:1px solid rgba(91,58,41,.08);">
                <p style="font-size:.82rem; color:var(--coffee); margin-bottom:.9rem;">Terminbuchung bevorzugt – walk-ins willkommen, wenn verfügbar.</p>
                <a class="btn btn-primary" href="tel:<?php echo $phone_number; ?>" style="width:100%; justify-content:center;">📞 Termin vereinbaren</a>
              </div>
            </div>

          </div>
        </div>
      </section>

    </main>

    <!-- ══ FOOTER ══════════════════════════════════════════════════ -->
    <footer aria-label="Seitenfooter">
      <div class="footer-inner">
        <div class="footer-grid">
          <!-- Brand -->
          <div>
            <span class="footer-logo"><?php echo esc($content_data['hero']['title'] ?? "Lyv's Haarstudio"); ?></span>
            <p class="footer-tagline">Frischer Wind · Vertraute Atmosphäre · Leck</p>
            <div class="footer-social">
              <?php if ($instagram_url): ?>
                <a href="<?php echo esc($instagram_url); ?>" target="_blank" rel="noreferrer noopener" aria-label="Instagram">
                  📸 <?php echo esc($instagram_handle); ?>
                </a>
              <?php endif; ?>
              <?php if ($facebook_url): ?>
                <a href="<?php echo esc($facebook_url); ?>" target="_blank" rel="noreferrer noopener" aria-label="Facebook">
                  👤 Facebook
                </a>
              <?php endif; ?>
            </div>
          </div>

          <!-- Contact -->
          <div class="footer-col">
            <h4>Kontakt</h4>
            <p><?php echo esc($content_data['contact']['address_line'] ?? 'Schafmarkt 2'); ?></p>
            <p><?php echo esc($contact_city); ?></p>
            <a href="tel:<?php echo $phone_number; ?>"><?php echo esc($contact_phone); ?></a>
            <a href="mailto:<?php echo esc($contact_email); ?>"><?php echo esc($contact_email); ?></a>
          </div>

          <!-- Hours -->
          <div class="footer-col">
            <h4>Öffnungszeiten</h4>
            <?php
            // Show a compact summary
            $open_days = [];
            $closed_days = [];
            foreach ($hours_days as $hd) {
              $cl = stripos((string)($hd['hours'] ?? ''), 'geschlossen') !== false;
              if (!$cl) $open_days[] = $hd['day'];
              else $closed_days[] = $hd['day'];
            }
            if (!empty($open_days)) {
              $first = $open_days[0];
              $last  = end($open_days);
              $sample = $hours_days[0]['hours'] ?? '';
              echo '<p>' . esc("{$first} – {$last}") . '</p>';
              echo '<p>' . esc($sample) . '</p>';
            }
            ?>
            <?php if (!empty($closed_days)): ?>
              <p>Sa–So: Geschlossen</p>
            <?php endif; ?>
          </div>
        </div>

        <div class="footer-bottom">
          <span>&copy; <?php echo date('Y'); ?> Lyv's Haarstudio · Alle Rechte vorbehalten</span>
          <nav class="footer-legal" aria-label="Rechtliche Links">
            <a href="impressum.php">Impressum</a>
            <a href="datenschutz.php">Datenschutz</a>
            <a href="admin.php" aria-label="Admin-Bereich">Admin</a>
          </nav>
        </div>
        <?php if (!empty($content_data['footer']['credit'])): ?>
          <p style="text-align:center; font-size:.72rem; color:rgba(255,247,240,.28); margin-top:1rem;">
            <a href="https://flora-fl.de/jason" target="_blank" rel="noreferrer" style="color:inherit;"><?php echo esc($content_data['footer']['credit']); ?></a>
          </p>
        <?php endif; ?>
      </div>
    </footer>

    <script>
      /* ── Sticky header shadow ── */
      const header = document.getElementById('site-header');
      window.addEventListener('scroll', () => {
        header.classList.toggle('scrolled', window.scrollY > 20);
      }, { passive: true });

      /* ── Mobile nav toggle ── */
      const navToggle = document.querySelector('.nav-toggle');
      const navList   = document.querySelector('nav ul');
      if (navToggle && navList) {
        navToggle.addEventListener('click', () => {
          const expanded = navToggle.getAttribute('aria-expanded') === 'true';
          navToggle.setAttribute('aria-expanded', String(!expanded));
          navList.classList.toggle('open');
        });
        navList.addEventListener('click', e => {
          if (e.target.matches('.nav-link')) {
            navList.classList.remove('open');
            navToggle.setAttribute('aria-expanded', 'false');
          }
        });
        document.addEventListener('click', e => {
          if (!navToggle.contains(e.target) && !navList.contains(e.target)) {
            navList.classList.remove('open');
            navToggle.setAttribute('aria-expanded', 'false');
          }
        });
      }

      /* ── Intersection Observer for reveal ── */
      const reveals = document.querySelectorAll('.reveal');
      if ('IntersectionObserver' in window) {
        const io = new IntersectionObserver(entries => {
          entries.forEach(entry => {
            if (entry.isIntersecting) {
              entry.target.classList.add('visible');
              io.unobserve(entry.target);
            }
          });
        }, { threshold: 0.08 });
        reveals.forEach(el => io.observe(el));
      } else {
        reveals.forEach(el => el.classList.add('visible'));
      }

      /* ── OSM map consent ── */
      // Approximate bounding box and marker for Schafmarkt 2, 25917 Leck (Schleswig-Holstein)
      const OSM_MAP_URL = 'https://www.openstreetmap.org/export/embed.html'
        + '?bbox=9.078%2C54.761%2C9.094%2C54.769'
        + '&layer=mapnik'
        + '&marker=54.7648%2C9.0860';

      const mapLoadBtn = document.getElementById('map-load-btn');
      if (mapLoadBtn) {
        mapLoadBtn.addEventListener('click', () => {
          const consent = document.getElementById('map-consent');
          const iframe  = document.getElementById('osm-iframe');
          if (!consent || !iframe) return;
          iframe.src = OSM_MAP_URL;
          consent.classList.add('hidden');
          iframe.classList.add('loaded');
        });
      }
    </script>

  </body>
</html>
