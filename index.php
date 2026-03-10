<?php
$phone_number = '+494662891898';
$phone_display = '04662 891898';
$route_url = 'https://maps.google.com/?q=Schafmarkt+2';
$prices_path = __DIR__ . '/data/prices.json';
$default_prices = [
  'categories' => [
    [
      'title' => 'Damen',
      'items' => [
        ['label' => 'Trockenhaarschnitt', 'price' => 'ab 32 €'],
        ['label' => 'Waschen / Schneiden', 'price' => '38 €'],
        ['label' => 'Waschen / Föhnen', 'price' => 'ab 25 €'],
        ['label' => 'Hochstecken', 'price' => 'ab 50 €'],
      ],
    ],
    [
      'title' => 'Herren',
      'items' => [
        ['label' => 'Trocken', 'price' => '24 €'],
        ['label' => 'Waschen / Schneiden / Föhnen', 'price' => '27 €'],
        ['label' => 'Maschinenschnitt', 'price' => '20 €'],
        ['label' => 'Bartschnitt', 'price' => 'ab 5 €'],
      ],
    ],
    [
      'title' => 'Kinder',
      'items' => [
        ['label' => '0-3 Jahre', 'price' => '14 €'],
        ['label' => '4-8 Jahre', 'price' => '16 €'],
        ['label' => '9-13 Jahre', 'price' => '18 €'],
        ['label' => '14-16 Jahre', 'price' => '20 €'],
      ],
    ],
    [
      'title' => 'Farbe & Glanz',
      'items' => [
        ['label' => 'Coloration', 'price' => 'ab 40 €'],
        ['label' => 'Balayage / Freihand', 'price' => 'nach Aufwand'],
        ['label' => 'Glossing / Tönung', 'price' => 'auf Anfrage'],
        ['label' => 'Pflege-Booster', 'price' => '+12 €'],
      ],
    ],
    [
      'title' => 'Dauerwelle',
      'items' => [
        ['label' => 'Soft Waves', 'price' => 'ab 50 €'],
        ['label' => 'Teil-Dauerwelle', 'price' => 'auf Anfrage'],
        ['label' => 'Pflegepaket', 'price' => '+15 €'],
        ['label' => 'Styling-Tipps', 'price' => 'inkl.'],
      ],
    ],
    [
      'title' => 'Extras',
      'items' => [
        ['label' => 'Kopfhaut Spa', 'price' => '18 €'],
        ['label' => 'Brautstyling', 'price' => 'ab 120 €'],
        ['label' => 'Make-up Touch-up', 'price' => '35 €'],
        ['label' => 'Blumen-Accessoires', 'price' => 'auf Anfrage'],
      ],
    ],
  ],
];
$prices_data = null;
if (is_file($prices_path)) {
  $raw_prices = file_get_contents($prices_path);
  $decoded_prices = json_decode($raw_prices, true);
  if (is_array($decoded_prices) && isset($decoded_prices['categories'])) {
    $prices_data = $decoded_prices;
  }
}
if ($prices_data === null) {
  $prices_data = $default_prices;
}

$content_path = __DIR__ . '/data/content.json';
$default_content = require __DIR__ . '/content-defaults.php';
$content_data = $default_content;
if (is_file($content_path)) {
  $raw_content = file_get_contents($content_path);
  $decoded_content = json_decode($raw_content, true);
  if (is_array($decoded_content)) {
    $content_data = array_replace_recursive($default_content, $decoded_content);
  }
}

function esc($value)
{
  return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo esc($content_data['hero']['title'] ?? 'Lyv\'s Haarstudio'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Carattere&family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    <style>
      :root {
        --beige: #f5e9dd;
        --beige-light: #fff7f0;
        --brown: #5b3a29;
        --accent: #8b5e3c;
        --coffee: #71523f;
        --heading-font: "Carattere", "Playfair Display", serif;
        --body-font: "Poppins", "Helvetica Neue", Arial, sans-serif;
        --max-width: 1200px;
      }

      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }

      body {
        font-family: var(--body-font);
        font-weight: 500;
        background: var(--beige);
        color: var(--brown);
        line-height: 1.65;
        scroll-behavior: smooth;
        position: relative;
      }

      a {
        color: var(--coffee);
        text-decoration: none;
        transition: color 0.2s ease;
      }

      a:not(.btn):hover,
      a:not(.btn):focus-visible {
        color: var(--accent);
      }

      header {
        position: sticky;
        top: 0;
        z-index: 999;
        background: rgba(245, 233, 221, 0.95);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(91, 58, 41, 0.12);
      }

      .nav-container {
        max-width: var(--max-width);
        margin: 0 auto;
        padding: 0.9rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        gap: 1rem;
      }

      .logo {
        font-family: var(--heading-font);
        font-size: clamp(1.6rem, 4vw, 2.4rem);
        letter-spacing: 1px;
        color: var(--brown);
      }

      nav {
        margin-left: 1rem;
        position: relative;
      }

      nav ul {
        list-style: none;
        display: flex;
        gap: 1.5rem;
        font-size: 0.95rem;
      }

      .nav-link {
        position: relative;
        padding-bottom: 0.2rem;
        color: var(--brown);
        font-weight: 500;
        transition: color 0.2s ease;
      }

      .nav-link::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: 0;
        width: 100%;
        height: 2px;
        background: var(--accent);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.3s ease;
      }

      .nav-link:hover::after,
      .nav-link:focus-visible::after {
        transform: scaleX(1);
      }

      .nav-toggle {
        display: none;
        background: none;
        border: 1px solid rgba(91, 58, 41, 0.2);
        padding: 0.35rem 0.5rem;
        border-radius: 8px;
        cursor: pointer;
        margin-left: auto;
      }

      .nav-toggle span {
        display: block;
        width: 24px;
        height: 2px;
        background: var(--brown);
        margin: 4px 0;
        transition: transform 0.3s ease;
      }

      main {
        overflow: hidden;
      }

      .hero {
        position: relative;
        padding: 6rem 1.5rem 5rem;
        background: linear-gradient(130deg, rgba(91, 58, 41, 0.08), rgba(139, 94, 60, 0.15));
        overflow: hidden;
      }

      .hero::before,
      .hero::after {
        content: "";
        position: absolute;
        width: 200px;
        height: 200px;
        background-image: url("data:image/svg+xml,%3Csvg width='180' height='180' viewBox='0 0 180 180' xmlns='http://www.w3.org/2000/svg'%3E%3Cg stroke='%238B5E3C' stroke-width='1.2' stroke-opacity='0.4' fill='none'%3E%3Cpath d='M90 10 C80 40 110 50 90 80 C70 50 100 40 90 10 Z'/%3E%3Cpath d='M30 120 C40 90 60 100 70 120 C80 100 100 90 120 110 C100 140 60 150 30 120 Z'/%3E%3C/g%3E%3C/svg%3E");
        opacity: 0.4;
        animation: sway 18s linear infinite;
        z-index: 0;
      }

      .hero::before {
        top: -20px;
        left: -40px;
      }

      .hero::after {
        bottom: -60px;
        right: -10px;
        animation-delay: -6s;
      }

      .hero-inner {
        max-width: var(--max-width);
        margin: 0 auto;
        display: grid;
        gap: 2.5rem;
        position: relative;
        z-index: 1;
      }

      .hero-copy h1 {
        font-family: var(--heading-font);
        font-size: clamp(2.8rem, 6vw, 4.5rem);
        margin-bottom: 1rem;
      }

      .hero-copy p {
        max-width: 580px;
      }

      .tagline {
        text-transform: uppercase;
        letter-spacing: 0.2em;
        font-size: 0.85rem;
        color: var(--accent);
        margin-bottom: 1rem;
      }

      .hero-cta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1.8rem;
      }

      .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.95rem 2.4rem;
        border-radius: 999px;
        border: none;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        text-align: center;
        box-shadow: 0 10px 25px rgba(139, 94, 60, 0.25);
      }

      .btn-primary {
        background: var(--accent);
        color: #fff;
      }

      .btn-secondary {
        background: rgba(245, 233, 221, 0.35);
        color: var(--brown);
        border: 1px solid rgba(91, 58, 41, 0.2);
        box-shadow: none;
      }

      .btn:hover,
      .btn:focus-visible {
        transform: translateY(-3px);
      }

      .hero-image {
        min-height: 340px;
        border-radius: 28px 28px 80px 28px;
        background-image: linear-gradient(rgba(91, 58, 41, 0.15), rgba(91, 58, 41, 0.35)),
          url("images/hero.jpg");
        background-size: cover;
        background-position: center;
        position: relative;
        animation: float 10s ease-in-out infinite;
      }

      .section {
        max-width: var(--max-width);
        margin: 0 auto;
        padding: 4rem 1.5rem;
        position: relative;
      }

      .section::before {
        content: "";
        position: absolute;
        width: 120px;
        height: 120px;
        background-image: url("data:image/svg+xml,%3Csvg width='120' height='120' viewBox='0 0 120 120' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M10 90 C30 40 90 40 110 90' fill='none' stroke='%235B3A29' stroke-width='1.2' stroke-opacity='0.25' stroke-linecap='round'/%3E%3Cpath d='M25 70 C50 30 70 30 95 70' fill='none' stroke='%238B5E3C' stroke-width='1' stroke-opacity='0.25'/%3E%3C/svg%3E");
        top: 1rem;
        right: 1rem;
        opacity: 0.3;
        pointer-events: none;
      }

      .section h2 {
        font-family: var(--heading-font);
        font-size: clamp(2.2rem, 5vw, 3rem);
        margin-bottom: 1.2rem;
      }

      .about-content {
        display: grid;
        gap: 2.5rem;
        align-items: center;
      }

      .about-image {
        border-radius: 32px;
        min-height: 320px;
        background: url("images/salon.jpg") center / cover;
        background-color: rgba(91, 58, 41, 0.08);
        position: relative;
      }

      .team-grid {
        display: grid;
        gap: 1.5rem;
        margin-top: 2rem;
      }

      .team-card {
        background: var(--beige-light);
        border-radius: 24px;
        padding: 2rem 1.5rem;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 30px rgba(91, 58, 41, 0.1);
      }

      .team-card::before {
        content: "";
        position: absolute;
        width: 160px;
        height: 160px;
        background: rgba(139, 94, 60, 0.08);
        border-radius: 50%;
        top: -60px;
        right: -60px;
      }

      .team-card img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 5px solid var(--beige);
        margin: 0 auto 1rem;
      }

      .team-card h3 {
        font-family: var(--heading-font);
        font-size: 1.8rem;
      }

      .services-grid {
        display: grid;
        gap: 1.5rem;
        margin-top: 2rem;
      }

      .service-card {
        background: #fff;
        border-radius: 28px;
        padding: 2rem;
        border: 1px solid rgba(91, 58, 41, 0.08);
        box-shadow: 0 20px 40px rgba(91, 58, 41, 0.08);
        position: relative;
        overflow: hidden;
      }

      .service-card::before {
        content: "";
        position: absolute;
        width: 110%;
        height: 110%;
        background: linear-gradient(135deg, rgba(245, 233, 221, 0.6), rgba(139, 94, 60, 0.08));
        top: -40%;
        right: -40%;
        transform: rotate(15deg);
        opacity: 0.3;
        pointer-events: none;
      }

      .service-card h3 {
        font-family: var(--heading-font);
        font-size: 2rem;
        margin-bottom: 0.5rem;
      }

      .price-list {
        list-style: none;
        margin-top: 1rem;
        position: relative;
        z-index: 1;
      }

      .price-list li {
        display: flex;
        justify-content: space-between;
        padding: 0.4rem 0;
        border-bottom: 1px dashed rgba(91, 58, 41, 0.2);
        font-size: 0.95rem;
      }

      .price-list li:last-child {
        border-bottom: none;
      }

      .appointment-section {
        background: var(--beige-light);
        border-radius: 32px;
        padding: 3rem 1.5rem;
        text-align: center;
        box-shadow: 0 25px 45px rgba(91, 58, 41, 0.12);
      }

      .contact-grid {
        display: grid;
        gap: 1.5rem;
        margin-top: 2rem;
      }

      .contact-card,
      .hours-card {
        background: #fff;
        border-radius: 24px;
        padding: 2rem;
        border: 1px solid rgba(91, 58, 41, 0.08);
        box-shadow: 0 15px 35px rgba(91, 58, 41, 0.07);
      }

      .map-placeholder {
        margin-top: 1.5rem;
        border-radius: 20px;
        border: 2px dashed rgba(91, 58, 41, 0.25);
        height: 240px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: var(--accent);
      }

      table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 1.2rem;
      }

      td {
        padding: 0.45rem 0;
        border-bottom: 1px dashed rgba(91, 58, 41, 0.18);
      }

      footer {
        background: #f0dfcf;
        padding: 2.5rem 1.5rem 4.5rem;
        text-align: center;
        margin-top: 2rem;
      }

      footer .footer-links {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        justify-content: center;
        margin: 1rem 0;
      }

      .footer-credit {
        font-size: 0.78rem;
        color: rgba(91, 58, 41, 0.65);
        margin-top: 0.6rem;
      }

      .footer-credit a {
        color: inherit;
      }

      .reveal {
        opacity: 0;
        transform: translateY(35px);
        transition: opacity 0.7s ease, transform 0.7s ease;
      }

      .reveal.visible {
        opacity: 1;
        transform: translateY(0);
      }

      .delay-1 {
        transition-delay: 0.15s;
      }

      .delay-2 {
        transition-delay: 0.3s;
      }

      @keyframes float {
        0%,
        100% {
          transform: translateY(0px);
        }
        50% {
          transform: translateY(-10px);
        }
      }

      @keyframes sway {
        0% {
          transform: rotate(0deg);
        }
        50% {
          transform: rotate(3deg);
        }
        100% {
          transform: rotate(0deg);
        }
      }

      @media (max-width: 1024px) {
        nav ul {
          position: absolute;
          top: 70px;
          right: 1.5rem;
          background: var(--beige);
          flex-direction: column;
          gap: 0.8rem;
          padding: 1rem 1.5rem;
          border-radius: 16px;
          box-shadow: 0 20px 40px rgba(91, 58, 41, 0.2);
          opacity: 0;
          pointer-events: none;
          transform: translateY(-10px);
          transition: opacity 0.3s ease, transform 0.3s ease;
        }

        nav ul.open {
          opacity: 1;
          pointer-events: auto;
          transform: translateY(0);
        }

        .nav-toggle {
          display: block;
        }
      }

      @media (min-width: 640px) {
        .hero-inner {
          grid-template-columns: repeat(2, minmax(0, 1fr));
          align-items: center;
        }

        .about-content {
          grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .team-grid {
          grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .services-grid {
          grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .contact-grid {
          grid-template-columns: repeat(2, minmax(0, 1fr));
        }
      }

      @media (min-width: 1000px) {
        .team-grid {
          grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .services-grid {
          grid-template-columns: repeat(3, minmax(0, 1fr));
        }
      }

      @media (max-width: 768px) {
        .hero {
          padding-top: 5rem;
        }
      }

      @media (prefers-reduced-motion: reduce) {
        *,
        *::before,
        *::after {
          animation: none !important;
          transition: none !important;
        }
      }
    </style>
  </head>
  <body>
    <header>
      <div class="nav-container">
        <div class="logo"><?php echo esc($content_data['hero']['title'] ?? 'Lyv\'s Haarstudio'); ?></div>
        <button class="nav-toggle" aria-label="Navigation öffnen" aria-expanded="false">
          <span></span>
          <span></span>
          <span></span>
        </button>
        <nav>
          <ul>
            <li><a class="nav-link" href="#home">Home</a></li>
            <li><a class="nav-link" href="#about">Über uns</a></li>
            <li><a class="nav-link" href="#team">Team</a></li>
            <li><a class="nav-link" href="#services">Leistungen</a></li>
            <li><a class="nav-link" href="#appointment">Termin</a></li>
            <li><a class="nav-link" href="#contact">Kontakt</a></li>
          </ul>
        </nav>
      </div>
    </header>

    <main>
      <section class="hero" id="home">
        <div class="hero-inner">
          <div class="hero-copy reveal">
            <p class="tagline"><?php echo esc($content_data['hero']['tagline'] ?? ''); ?></p>
            <h1><?php echo esc($content_data['hero']['title'] ?? 'Lyv\'s Haarstudio'); ?></h1>
            <p><?php echo nl2br(esc($content_data['hero']['text'] ?? '')); ?></p>
            <div class="hero-cta">
              <a class="btn btn-primary" href="tel:<?php echo $phone_number; ?>">Rufe jetzt an</a>
              <a class="btn btn-secondary" href="<?php echo $route_url; ?>" target="_blank" rel="noreferrer"
                >Route anzeigen</a
              >
            </div>
          </div>
          <div class="hero-image reveal delay-1" aria-label="Impression aus dem Salon"></div>
        </div>
      </section>

      <section class="section" id="about">
        <h2><?php echo esc($content_data['about']['title'] ?? ''); ?></h2>
        <div class="about-content">
          <div class="reveal">
            <?php
            $about_paragraphs = $content_data['about']['paragraphs'] ?? [];
            if (!is_array($about_paragraphs)) {
              $about_paragraphs = [];
            }
            ?>
            <?php foreach ($about_paragraphs as $paragraph) : ?>
              <p><?php echo nl2br(esc($paragraph)); ?></p>
            <?php endforeach; ?>
          </div>
          <div class="about-image reveal delay-1" role="img"></div>
        </div>
      </section>

      <section class="section" id="team">
        <h2><?php echo esc($content_data['team']['title'] ?? ''); ?></h2>
        <p class="reveal"><?php echo nl2br(esc($content_data['team']['intro'] ?? '')); ?></p>
        <?php
        $team_images = [
          'images/team-lyv.jpg',
          'images/team-nova.jpg',
          'images/team-mika.jpg',
          'images/team-ella.jpg',
        ];
        $team_placeholder = 'data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27240%27 height=%27240%27 viewBox=%270 0 240 240%27%3E%3Crect width=%27240%27 height=%27240%27 fill=%27%23f5e9dd%27/%3E%3Ccircle cx=%27120%27 cy=%2795%27 r=%2750%27 fill=%27%23d8c3b2%27/%3E%3Crect x=%2755%27 y=%27150%27 width=%27130%27 height=%2750%27 rx=%2725%27 fill=%27%23d8c3b2%27/%3E%3Ctext x=%27120%27 y=%27220%27 font-family=%27Poppins,Arial,sans-serif%27 font-size=%2718%27 fill=%27%235b3a29%27 text-anchor=%27middle%27%3ETeam%3C/text%3E%3C/svg%3E';
        $team_members = $content_data['team']['members'] ?? [];
        if (!is_array($team_members)) {
          $team_members = [];
        }
        ?>
        <div class="team-grid">
          <?php foreach ($team_members as $index => $member) : ?>
            <?php
            $delay = $index % 3 === 1 ? ' delay-1' : ($index % 3 === 2 ? ' delay-2' : '');
            $member_name = $member['name'] ?? 'Teammitglied';
            $image = $team_images[$index] ?? $team_placeholder;
            ?>
            <article class="team-card reveal<?php echo $delay; ?>">
              <img src="<?php echo esc($image); ?>" alt="<?php echo esc($member_name); ?>" />
              <h3><?php echo esc($member_name); ?></h3>
              <p><?php echo esc($member['role'] ?? ''); ?></p>
              <p><?php echo nl2br(esc($member['bio'] ?? '')); ?></p>
            </article>
          <?php endforeach; ?>
        </div>
      </section>

      <section class="section" id="services">
        <h2><?php echo esc($content_data['services']['title'] ?? ''); ?></h2>
        <p class="reveal"><?php echo nl2br(esc($content_data['services']['intro'] ?? '')); ?></p>
        <div class="services-grid">
          <?php foreach ($prices_data['categories'] as $index => $category) : ?>
            <div class="service-card reveal<?php echo $index % 3 === 1 ? ' delay-1' : ($index % 3 === 2 ? ' delay-2' : ''); ?>">
              <h3><?php echo esc($category['title'] ?? ''); ?></h3>
              <ul class="price-list">
                <?php foreach (($category['items'] ?? []) as $item) : ?>
                  <li>
                    <span><?php echo esc($item['label'] ?? ''); ?></span>
                    <span><?php echo esc($item['price'] ?? ''); ?></span>
                  </li>
                <?php endforeach; ?>
              </ul>
            </div>
          <?php endforeach; ?>
        </div>
      </section>

      <section class="section" id="appointment">
        <div class="appointment-section reveal">
          <h2><?php echo esc($content_data['appointment']['title'] ?? ''); ?></h2>
          <p><?php echo nl2br(esc($content_data['appointment']['text'] ?? '')); ?></p>
          <a class="btn btn-primary" href="tel:<?php echo $phone_number; ?>">Rufe jetzt an</a>
          <p style="margin-top: 1rem"><?php echo nl2br(esc($content_data['appointment']['note'] ?? '')); ?></p>
        </div>
      </section>

      <section class="section" id="contact">
        <h2><?php echo esc($content_data['contact']['title'] ?? ''); ?></h2>
        <div class="contact-grid">
          <div class="contact-card reveal">
            <h3><?php echo esc($content_data['contact']['studio_name'] ?? 'Lyv\'s Haarstudio'); ?></h3>
            <p><?php echo esc($content_data['contact']['address_line'] ?? ''); ?></p>
            <p>Telefon: <a href="tel:<?php echo $phone_number; ?>"><?php echo $phone_display; ?></a></p>
            <p>
              Instagram:
              <a
                href="https://www.instagram.com/lyvs_haarstudio?igsh=N28yOHVqZ2dlM3I="
                target="_blank"
                rel="noreferrer"
                >@lyvs_haarstudio</a
              >
            </p>
            <div class="map-placeholder"><?php echo esc($content_data['contact']['map_label'] ?? ''); ?></div>
          </div>
          <div class="hours-card reveal delay-1">
            <h3>Öffnungszeiten</h3>
            <table>
              <tbody>
                <tr>
                  <td>Montag</td>
                  <td>09:00 – 18:00 Uhr</td>
                </tr>
                <tr>
                  <td>Dienstag</td>
                  <td>09:00 – 18:00 Uhr</td>
                </tr>
                <tr>
                  <td>Mittwoch</td>
                  <td>09:00 – 18:00 Uhr</td>
                </tr>
                <tr>
                  <td>Donnerstag</td>
                  <td>09:00 – 18:00 Uhr</td>
                </tr>
                <tr>
                  <td>Freitag</td>
                  <td>09:00 – 18:00 Uhr</td>
                </tr>
                <tr>
                  <td>Samstag</td>
                  <td>Geschlossen</td>
                </tr>
                <tr>
                  <td>Sonntag</td>
                  <td>Geschlossen</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>
    </main>

    <footer>
      <p>Telefon: <a href="tel:<?php echo $phone_number; ?>"><?php echo $phone_display; ?></a></p>
      <div class="footer-links">
        <a href="<?php echo $route_url; ?>" target="_blank" rel="noreferrer">Route</a>
        <a href="impressum.php">Impressum</a>
        <a href="datenschutz.php">Datenschutz</a>
      </div>
      <p>
        &copy; <?php echo date('Y'); ?> Lyv's Haarstudio · Alle Rechte vorbehalten ·
        <a
          href="https://www.instagram.com/lyvs_haarstudio?igsh=N28yOHVqZ2dlM3I="
          target="_blank"
          rel="noreferrer"
          >Instagram</a
        >
      </p>
      <p class="footer-credit">
        <a href="https://flora-fl.de/jason" target="_blank" rel="noreferrer"><?php echo esc($content_data['footer']['credit'] ?? ''); ?></a>
      </p>
    </footer>

    <script>
      const navToggle = document.querySelector(".nav-toggle");
      const navList = document.querySelector("nav ul");
      const reveals = document.querySelectorAll(".reveal");

      navToggle.addEventListener("click", () => {
        const expanded = navToggle.getAttribute("aria-expanded") === "true";
        navToggle.setAttribute("aria-expanded", (!expanded).toString());
        navList.classList.toggle("open");
      });

      navList.addEventListener("click", (event) => {
        if (event.target.matches(".nav-link")) {
          navList.classList.remove("open");
          navToggle.setAttribute("aria-expanded", "false");
        }
      });

      const observer = new IntersectionObserver(
        (entries) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              entry.target.classList.add("visible");
            }
          });
        },
        { threshold: 0.1 }
      );

      reveals.forEach((el) => observer.observe(el));
    </script>
  </body>
</html>
