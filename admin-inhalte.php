<?php
require __DIR__ . '/admin-helpers.php';

$content_path    = __DIR__ . '/data/content.json';
$default_content = require __DIR__ . '/content-defaults.php';

function load_content($path, $defaults) {
  if (!is_file($path)) return $defaults;
  $raw     = file_get_contents($path);
  $decoded = json_decode($raw, true);
  if (!is_array($decoded)) return $defaults;
  return array_replace_recursive($defaults, $decoded);
}

function save_content($path, $data, &$err) {
  $dir = dirname($path);
  if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
    $err = 'Ordner konnte nicht erstellt werden.';
    return false;
  }
  $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  if ($json === false) { $err = 'JSON-Kodierung fehlgeschlagen.'; return false; }
  if (file_put_contents($path, $json, LOCK_EX) === false) {
    $err = 'Datei konnte nicht geschrieben werden.'; return false;
  }
  return true;
}

$error_message   = '';
$success_message = '';

admin_logout();
admin_handle_login($error_message);

$authenticated = admin_is_authenticated();
$content_data  = load_content($content_path, $default_content);

if ($authenticated && isset($_POST['save_content'])) {
  $inc = $_POST['content'] ?? [];

  /* ── hero ── */
  $payload['hero'] = [
    'tagline' => trim((string)($inc['hero']['tagline'] ?? '')),
    'title'   => trim((string)($inc['hero']['title']   ?? '')),
    'text'    => trim((string)($inc['hero']['text']    ?? '')),
  ];

  /* ── about ── */
  $payload['about']['title']      = trim((string)($inc['about']['title'] ?? ''));
  $payload['about']['paragraphs'] = [];
  foreach ($default_content['about']['paragraphs'] as $i => $_) {
    $payload['about']['paragraphs'][] = trim((string)($inc['about']['paragraphs'][$i] ?? ''));
  }

  /* ── team ── */
  $payload['team']['title'] = trim((string)($inc['team']['title'] ?? ''));
  $payload['team']['intro'] = trim((string)($inc['team']['intro'] ?? ''));
  $payload['team']['members'] = [];
  foreach ($inc['team']['members'] ?? [] as $m) {
    $n = trim((string)($m['name'] ?? ''));
    $r = trim((string)($m['role'] ?? ''));
    $b = trim((string)($m['bio']  ?? ''));
    if ($n === '' && $r === '' && $b === '') continue;
    $payload['team']['members'][] = ['name' => $n, 'role' => $r, 'bio' => $b];
  }

  /* ── services ── */
  $payload['services'] = [
    'title' => trim((string)($inc['services']['title'] ?? '')),
    'intro' => trim((string)($inc['services']['intro'] ?? '')),
  ];

  /* ── why_us ── */
  $payload['why_us']['title'] = trim((string)($inc['why_us']['title'] ?? ''));
  $payload['why_us']['items'] = [];
  foreach ($default_content['why_us']['items'] as $i => $def) {
    $payload['why_us']['items'][] = [
      'icon'  => $def['icon'],
      'title' => trim((string)($inc['why_us']['items'][$i]['title'] ?? $def['title'])),
      'text'  => trim((string)($inc['why_us']['items'][$i]['text']  ?? $def['text'])),
    ];
  }

  /* ── gallery ── */
  $payload['gallery'] = [
    'title' => trim((string)($inc['gallery']['title'] ?? '')),
    'intro' => trim((string)($inc['gallery']['intro'] ?? '')),
  ];

  /* ── appointment ── */
  $payload['appointment'] = [
    'title' => trim((string)($inc['appointment']['title'] ?? '')),
    'text'  => trim((string)($inc['appointment']['text']  ?? '')),
    'note'  => trim((string)($inc['appointment']['note']  ?? '')),
  ];

  /* ── contact ── */
  $payload['contact'] = [
    'title'        => trim((string)($inc['contact']['title']        ?? '')),
    'studio_name'  => trim((string)($inc['contact']['studio_name']  ?? '')),
    'address_line' => trim((string)($inc['contact']['address_line'] ?? '')),
    'address_city' => trim((string)($inc['contact']['address_city'] ?? '')),
    'phone'        => trim((string)($inc['contact']['phone']        ?? '')),
    'email'        => trim((string)($inc['contact']['email']        ?? '')),
    'map_label'    => trim((string)($inc['contact']['map_label']    ?? '')),
  ];

  /* ── hours ── */
  $payload['hours']['title'] = trim((string)($inc['hours']['title'] ?? ''));
  $payload['hours']['days']  = [];
  foreach ($default_content['hours']['days'] as $i => $def) {
    $payload['hours']['days'][] = [
      'day'   => $def['day'],
      'hours' => trim((string)($inc['hours']['days'][$i]['hours'] ?? $def['hours'])),
    ];
  }

  /* ── social ── */
  $payload['social'] = [
    'instagram'        => trim((string)($inc['social']['instagram']        ?? '')),
    'instagram_handle' => trim((string)($inc['social']['instagram_handle'] ?? '')),
    'facebook'         => trim((string)($inc['social']['facebook']         ?? '')),
  ];

  /* ── footer (preserve credit) ── */
  $payload['footer'] = [
    'credit' => $content_data['footer']['credit'] ?? ($default_content['footer']['credit'] ?? ''),
  ];

  if (save_content($content_path, $payload, $error_message)) {
    $success_message = 'Inhalte erfolgreich gespeichert.';
    $content_data    = array_replace_recursive($default_content, $payload);
  }
}

$about_paragraphs = $content_data['about']['paragraphs'] ?? $default_content['about']['paragraphs'];
if (!is_array($about_paragraphs)) $about_paragraphs = $default_content['about']['paragraphs'];

$team_members = $content_data['team']['members'] ?? $default_content['team']['members'];
if (!is_array($team_members)) $team_members = $default_content['team']['members'];

$why_items = $content_data['why_us']['items'] ?? $default_content['why_us']['items'];
if (!is_array($why_items)) $why_items = $default_content['why_us']['items'];

$hours_days = $content_data['hours']['days'] ?? $default_content['hours']['days'];
if (!is_array($hours_days)) $hours_days = $default_content['hours']['days'];
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Inhalte bearbeiten · Lyv's Haarstudio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Carattere&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <style>
      :root {
        --beige: #f5e9dd;
        --beige-light: #fff7f0;
        --brown: #5b3a29;
        --accent: #8b5e3c;
        --max-width: 1040px;
        --heading-font: "Carattere", "Playfair Display", serif;
        --body-font: "Poppins", "Helvetica Neue", Arial, sans-serif;
      }
      *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
      body {
        font-family: var(--body-font);
        background: var(--beige);
        color: var(--brown);
        line-height: 1.6;
        padding: 2rem 1rem 5rem;
      }
      header { text-align: center; margin-bottom: 2rem; }
      h1 { font-family: var(--heading-font); font-size: clamp(2.2rem, 5vw, 3rem); }
      header p { font-size: .9rem; color: var(--accent); margin-top: .3rem; }

      main {
        max-width: var(--max-width);
        margin: 0 auto;
        background: #fff;
        border-radius: 28px;
        padding: 2rem;
        box-shadow: 0 20px 45px rgba(91,58,41,.12);
      }

      /* Tab nav */
      .tab-nav {
        display: flex;
        flex-wrap: wrap;
        gap: .4rem;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid rgba(91,58,41,.1);
        padding-bottom: 1rem;
      }
      .tab-btn {
        padding: .4rem .9rem;
        border-radius: 999px;
        border: 1px solid rgba(91,58,41,.18);
        background: var(--beige-light);
        color: var(--brown);
        font-family: var(--body-font);
        font-size: .82rem;
        font-weight: 500;
        cursor: pointer;
        transition: background .2s, color .2s;
      }
      .tab-btn.active { background: var(--accent); color: #fff; border-color: var(--accent); }

      .tab-panel { display: none; }
      .tab-panel.active { display: block; }

      /* Sections */
      .section {
        border: 1px solid rgba(91,58,41,.1);
        border-radius: 18px;
        padding: 1.4rem;
        margin-bottom: 1.2rem;
        background: var(--beige-light);
      }
      .section h2 {
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .14em;
        color: var(--accent);
        margin-bottom: 1rem;
        font-weight: 600;
      }
      .field { margin-bottom: .9rem; }
      label {
        display: block;
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .12em;
        margin-bottom: .28rem;
        color: var(--accent);
        font-weight: 600;
      }
      input[type="text"],
      input[type="email"],
      input[type="tel"],
      input[type="url"],
      input[type="password"],
      textarea {
        width: 100%;
        padding: .62rem .85rem;
        border-radius: 10px;
        border: 1px solid rgba(91,58,41,.18);
        font-size: .95rem;
        font-family: var(--body-font);
        color: var(--brown);
        background: #fff;
        transition: border-color .2s;
      }
      input:focus, textarea:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(139,94,60,.1);
      }
      textarea { min-height: 80px; resize: vertical; }
      .two-col { display: grid; gap: .8rem; }

      /* Cards */
      .card {
        background: #fff;
        border-radius: 14px;
        padding: .9rem;
        border: 1px solid rgba(91,58,41,.12);
        margin-bottom: .7rem;
      }
      .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: .7rem;
      }
      .card-header span { font-size: .82rem; font-weight: 600; color: var(--accent); }

      /* Hours grid */
      .hours-row {
        display: grid;
        grid-template-columns: 140px 1fr;
        align-items: center;
        gap: .6rem;
        margin-bottom: .5rem;
      }
      .hours-day-label {
        font-size: .85rem;
        font-weight: 600;
        padding: .55rem .75rem;
        background: var(--beige);
        border-radius: 8px;
        text-align: center;
      }

      /* Notices */
      .notice {
        padding: .8rem 1rem;
        border-radius: 12px;
        margin-bottom: 1.2rem;
        font-weight: 500;
        font-size: .92rem;
      }
      .notice.error   { background: rgba(201,69,69,.12); color: #8a2f2f; }
      .notice.success { background: rgba(81,139,60,.12);  color: #3e6b2c; }

      /* Buttons */
      .actions { display: flex; flex-wrap: wrap; gap: .8rem; margin-top: 1.5rem; }
      .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: .68rem 1.6rem;
        border-radius: 999px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-family: var(--body-font);
        font-size: .9rem;
        transition: transform .2s, box-shadow .2s;
      }
      .btn-primary { background: var(--accent); color: #fff; box-shadow: 0 8px 20px rgba(139,94,60,.22); }
      .btn-secondary { background: var(--beige-light); color: var(--brown); border: 1px solid rgba(91,58,41,.2); }
      .btn-text { background: none; border: none; color: var(--accent); cursor: pointer; font-size: .82rem; font-weight: 600; padding: .15rem 0; }
      .btn:hover, .btn:focus-visible { transform: translateY(-2px); }

      .muted { font-size: .82rem; color: rgba(91,58,41,.65); margin-top: .5rem; }

      @media (min-width: 600px) {
        .two-col { grid-template-columns: 1fr 1fr; }
      }
      @media (max-width: 600px) {
        main { padding: 1.4rem; }
        .hours-row { grid-template-columns: 1fr; }
        .hours-day-label { text-align: left; }
      }
    </style>
  </head>
  <body>
    <header>
      <h1>Inhalte bearbeiten</h1>
      <p>Lyv's Haarstudio · Admin</p>
    </header>

    <main>
      <?php if ($error_message !== ''): ?>
        <div class="notice error"><?php echo esc($error_message); ?></div>
      <?php endif; ?>
      <?php if ($success_message !== ''): ?>
        <div class="notice success"><?php echo esc($success_message); ?></div>
      <?php endif; ?>

      <?php if (!$authenticated): ?>
        <form method="post">
          <div class="section">
            <label for="access_code">Zugangscode</label>
            <input type="password" id="access_code" name="access_code" required />
          </div>
          <div class="actions">
            <button class="btn btn-primary" type="submit">Weiter</button>
            <a class="btn btn-secondary" href="admin.php">Zurück</a>
          </div>
        </form>
      <?php else: ?>

        <!-- Tab navigation -->
        <div class="tab-nav" role="tablist">
          <button class="tab-btn active" type="button" role="tab" aria-selected="true"  data-tab="hero">🏠 Hero</button>
          <button class="tab-btn"        type="button" role="tab" aria-selected="false" data-tab="about">📖 Über uns</button>
          <button class="tab-btn"        type="button" role="tab" aria-selected="false" data-tab="why">✨ Warum wir</button>
          <button class="tab-btn"        type="button" role="tab" aria-selected="false" data-tab="services">✂️ Leistungen</button>
          <button class="tab-btn"        type="button" role="tab" aria-selected="false" data-tab="gallery">📷 Galerie</button>
          <button class="tab-btn"        type="button" role="tab" aria-selected="false" data-tab="team">👥 Team</button>
          <button class="tab-btn"        type="button" role="tab" aria-selected="false" data-tab="appt">📅 Termin</button>
          <button class="tab-btn"        type="button" role="tab" aria-selected="false" data-tab="contact">📍 Kontakt</button>
          <button class="tab-btn"        type="button" role="tab" aria-selected="false" data-tab="hours">🕐 Zeiten</button>
          <button class="tab-btn"        type="button" role="tab" aria-selected="false" data-tab="social">🔗 Social</button>
        </div>

        <form method="post">
          <input type="hidden" name="save_content" value="1" />

          <!-- ── HERO ── -->
          <div class="tab-panel active" id="tab-hero" role="tabpanel">
            <div class="section">
              <h2>Hero-Bereich (Startseite oben)</h2>
              <div class="field">
                <label>Tagline (kleine Zeile)</label>
                <input type="text" name="content[hero][tagline]" value="<?php echo esc($content_data['hero']['tagline'] ?? ''); ?>" />
              </div>
              <div class="field">
                <label>Hauptüberschrift</label>
                <input type="text" name="content[hero][title]" value="<?php echo esc($content_data['hero']['title'] ?? ''); ?>" />
              </div>
              <div class="field">
                <label>Beschreibungstext</label>
                <textarea name="content[hero][text]"><?php echo esc($content_data['hero']['text'] ?? ''); ?></textarea>
              </div>
            </div>
          </div>

          <!-- ── ABOUT ── -->
          <div class="tab-panel" id="tab-about" role="tabpanel">
            <div class="section">
              <h2>Über uns / Geschichte</h2>
              <div class="field">
                <label>Überschrift</label>
                <input type="text" name="content[about][title]" value="<?php echo esc($content_data['about']['title'] ?? ''); ?>" />
              </div>
              <?php foreach ($about_paragraphs as $i => $para): ?>
                <div class="field">
                  <label>Absatz <?php echo $i + 1; ?></label>
                  <textarea name="content[about][paragraphs][<?php echo $i; ?>]"><?php echo esc($para); ?></textarea>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- ── WHY US ── -->
          <div class="tab-panel" id="tab-why" role="tabpanel">
            <div class="section">
              <h2>„Warum wir"-Bereich (4 Karten)</h2>
              <div class="field">
                <label>Überschrift</label>
                <input type="text" name="content[why_us][title]" value="<?php echo esc($content_data['why_us']['title'] ?? ''); ?>" />
              </div>
              <?php foreach ($why_items as $i => $item): ?>
                <div class="card">
                  <div class="card-header">
                    <span><?php echo esc($item['icon'] ?? ''); ?> Karte <?php echo $i + 1; ?></span>
                  </div>
                  <div class="two-col">
                    <div class="field">
                      <label>Titel</label>
                      <input type="text" name="content[why_us][items][<?php echo $i; ?>][title]" value="<?php echo esc($item['title'] ?? ''); ?>" />
                    </div>
                    <div class="field">
                      <label>Text</label>
                      <textarea name="content[why_us][items][<?php echo $i; ?>][text]"><?php echo esc($item['text'] ?? ''); ?></textarea>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- ── SERVICES ── -->
          <div class="tab-panel" id="tab-services" role="tabpanel">
            <div class="section">
              <h2>Leistungen & Preise – Einleitung</h2>
              <div class="field">
                <label>Überschrift</label>
                <input type="text" name="content[services][title]" value="<?php echo esc($content_data['services']['title'] ?? ''); ?>" />
              </div>
              <div class="field">
                <label>Einleitungstext</label>
                <textarea name="content[services][intro]"><?php echo esc($content_data['services']['intro'] ?? ''); ?></textarea>
              </div>
              <p class="muted">Preislisten und Kategorien kannst du unter <a href="admin-preise.php">Preise bearbeiten</a> anpassen.</p>
            </div>
          </div>

          <!-- ── GALLERY ── -->
          <div class="tab-panel" id="tab-gallery" role="tabpanel">
            <div class="section">
              <h2>Impressionen / Galerie</h2>
              <div class="field">
                <label>Überschrift</label>
                <input type="text" name="content[gallery][title]" value="<?php echo esc($content_data['gallery']['title'] ?? ''); ?>" />
              </div>
              <div class="field">
                <label>Einleitungstext</label>
                <textarea name="content[gallery][intro]"><?php echo esc($content_data['gallery']['intro'] ?? ''); ?></textarea>
              </div>
              <p class="muted">Bilder für die Galerie können unter <a href="admin-bilder.php">Bilder hochladen</a> verwaltet werden.</p>
            </div>
          </div>

          <!-- ── TEAM ── -->
          <div class="tab-panel" id="tab-team" role="tabpanel">
            <div class="section">
              <h2>Team</h2>
              <div class="field">
                <label>Überschrift</label>
                <input type="text" name="content[team][title]" value="<?php echo esc($content_data['team']['title'] ?? ''); ?>" />
              </div>
              <div class="field">
                <label>Einleitungstext</label>
                <textarea name="content[team][intro]"><?php echo esc($content_data['team']['intro'] ?? ''); ?></textarea>
              </div>
              <div id="team-members">
                <?php foreach ($team_members as $i => $member): ?>
                  <div class="card" data-member>
                    <div class="card-header">
                      <span>Teammitglied <?php echo $i + 1; ?></span>
                      <button class="btn-text" type="button" data-remove-member>Entfernen</button>
                    </div>
                    <div class="field">
                      <label>Name</label>
                      <input type="text"
                        name="content[team][members][<?php echo $i; ?>][name]"
                        data-name-template="content[team][members][__I__][name]"
                        value="<?php echo esc($member['name'] ?? ''); ?>" />
                    </div>
                    <div class="field">
                      <label>Rolle / Position</label>
                      <input type="text"
                        name="content[team][members][<?php echo $i; ?>][role]"
                        data-name-template="content[team][members][__I__][role]"
                        value="<?php echo esc($member['role'] ?? ''); ?>" />
                    </div>
                    <div class="field">
                      <label>Kurzbeschreibung</label>
                      <textarea
                        name="content[team][members][<?php echo $i; ?>][bio]"
                        data-name-template="content[team][members][__I__][bio]"><?php echo esc($member['bio'] ?? ''); ?></textarea>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
              <button class="btn btn-secondary" type="button" data-add-member>+ Teammitglied hinzufügen</button>
              <p class="muted">Teamfotos kannst du unter Bilder hochladen anpassen.</p>
            </div>
          </div>

          <!-- ── APPOINTMENT ── -->
          <div class="tab-panel" id="tab-appt" role="tabpanel">
            <div class="section">
              <h2>Termin-Bereich</h2>
              <div class="field">
                <label>Überschrift</label>
                <input type="text" name="content[appointment][title]" value="<?php echo esc($content_data['appointment']['title'] ?? ''); ?>" />
              </div>
              <div class="field">
                <label>Text</label>
                <textarea name="content[appointment][text]"><?php echo esc($content_data['appointment']['text'] ?? ''); ?></textarea>
              </div>
              <div class="field">
                <label>Hinweis (klein)</label>
                <textarea name="content[appointment][note]"><?php echo esc($content_data['appointment']['note'] ?? ''); ?></textarea>
              </div>
            </div>
          </div>

          <!-- ── CONTACT ── -->
          <div class="tab-panel" id="tab-contact" role="tabpanel">
            <div class="section">
              <h2>Kontaktbereich</h2>
              <div class="two-col">
                <div class="field">
                  <label>Überschrift</label>
                  <input type="text" name="content[contact][title]" value="<?php echo esc($content_data['contact']['title'] ?? ''); ?>" />
                </div>
                <div class="field">
                  <label>Studio-Name</label>
                  <input type="text" name="content[contact][studio_name]" value="<?php echo esc($content_data['contact']['studio_name'] ?? ''); ?>" />
                </div>
                <div class="field">
                  <label>Straße & Hausnummer</label>
                  <input type="text" name="content[contact][address_line]" value="<?php echo esc($content_data['contact']['address_line'] ?? ''); ?>" />
                </div>
                <div class="field">
                  <label>PLZ & Ort</label>
                  <input type="text" name="content[contact][address_city]" value="<?php echo esc($content_data['contact']['address_city'] ?? ''); ?>" />
                </div>
                <div class="field">
                  <label>Telefon</label>
                  <input type="tel" name="content[contact][phone]" value="<?php echo esc($content_data['contact']['phone'] ?? ''); ?>" />
                </div>
                <div class="field">
                  <label>E-Mail</label>
                  <input type="email" name="content[contact][email]" value="<?php echo esc($content_data['contact']['email'] ?? ''); ?>" />
                </div>
              </div>
            </div>
          </div>

          <!-- ── HOURS ── -->
          <div class="tab-panel" id="tab-hours" role="tabpanel">
            <div class="section">
              <h2>Öffnungszeiten</h2>
              <div class="field" style="margin-bottom:1.2rem;">
                <label>Überschrift</label>
                <input type="text" name="content[hours][title]" value="<?php echo esc($content_data['hours']['title'] ?? ''); ?>" />
              </div>
              <?php foreach ($hours_days as $i => $hday): ?>
                <div class="hours-row">
                  <div class="hours-day-label"><?php echo esc($hday['day'] ?? ''); ?></div>
                  <input type="text"
                    name="content[hours][days][<?php echo $i; ?>][hours]"
                    value="<?php echo esc($hday['hours'] ?? ''); ?>"
                    placeholder='z.B. "09:00 – 18:00 Uhr" oder "Geschlossen"' />
                </div>
              <?php endforeach; ?>
              <p class="muted" style="margin-top:.8rem;">Tipp: Für geschlossene Tage „Geschlossen" eingeben.</p>
            </div>
          </div>

          <!-- ── SOCIAL ── -->
          <div class="tab-panel" id="tab-social" role="tabpanel">
            <div class="section">
              <h2>Social-Media Links</h2>
              <div class="field">
                <label>Instagram URL</label>
                <input type="url" name="content[social][instagram]" value="<?php echo esc($content_data['social']['instagram'] ?? ''); ?>" />
              </div>
              <div class="field">
                <label>Instagram Handle (z.B. @lyvs_haarstudio)</label>
                <input type="text" name="content[social][instagram_handle]" value="<?php echo esc($content_data['social']['instagram_handle'] ?? ''); ?>" />
              </div>
              <div class="field">
                <label>Facebook URL (optional)</label>
                <input type="url" name="content[social][facebook]" value="<?php echo esc($content_data['social']['facebook'] ?? ''); ?>" />
              </div>
            </div>
          </div>

          <!-- ── Save bar ── -->
          <div class="actions" style="position:sticky; bottom:1rem; background:rgba(255,255,255,.95); padding:.8rem; border-radius:16px; box-shadow:0 8px 24px rgba(91,58,41,.12);">
            <button class="btn btn-primary" type="submit">💾 Speichern</button>
            <a class="btn btn-secondary" href="admin.php">Zurück</a>
            <button class="btn btn-secondary" type="submit" name="logout" value="1">Logout</button>
          </div>
        </form>
        <p class="muted" style="text-align:center; margin-top:1rem;">Änderungen sind sofort auf der Startseite sichtbar.</p>

        <template id="team-member-tpl">
          <div class="card" data-member>
            <div class="card-header">
              <span>Neues Teammitglied</span>
              <button class="btn-text" type="button" data-remove-member>Entfernen</button>
            </div>
            <div class="field">
              <label>Name</label>
              <input type="text" data-name-template="content[team][members][__I__][name]" value="" />
            </div>
            <div class="field">
              <label>Rolle / Position</label>
              <input type="text" data-name-template="content[team][members][__I__][role]" value="" />
            </div>
            <div class="field">
              <label>Kurzbeschreibung</label>
              <textarea data-name-template="content[team][members][__I__][bio]"></textarea>
            </div>
          </div>
        </template>

        <script>
          /* ── Tab switching ── */
          const tabBtns   = document.querySelectorAll('.tab-btn');
          const tabPanels = document.querySelectorAll('.tab-panel');

          tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
              const target = btn.dataset.tab;
              tabBtns.forEach(b => { b.classList.remove('active'); b.setAttribute('aria-selected', 'false'); });
              tabPanels.forEach(p => p.classList.remove('active'));
              btn.classList.add('active');
              btn.setAttribute('aria-selected', 'true');
              document.getElementById('tab-' + target)?.classList.add('active');
            });
          });

          /* ── Team member dynamic add/remove ── */
          const teamContainer = document.getElementById('team-members');
          const teamTpl       = document.getElementById('team-member-tpl');

          function refreshIndexes() {
            if (!teamContainer) return;
            teamContainer.querySelectorAll('[data-member]').forEach((card, i) => {
              card.querySelectorAll('[data-name-template]').forEach(el => {
                el.name = el.dataset.nameTemplate.replace(/__I__/g, i);
              });
            });
          }

          document.addEventListener('click', e => {
            if (e.target.matches('[data-add-member]')) {
              if (!teamTpl) return;
              teamContainer.appendChild(teamTpl.content.cloneNode(true));
              refreshIndexes();
            }
            if (e.target.matches('[data-remove-member]')) {
              e.target.closest('[data-member]')?.remove();
              refreshIndexes();
            }
          });

          refreshIndexes();
        </script>

      <?php endif; ?>
    </main>
  </body>
</html>
