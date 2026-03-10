<?php
require __DIR__ . '/admin-helpers.php';

$images_dir = __DIR__ . '/images';

$slots = [
  'hero'      => ['label' => 'Hero-Bild',    'filename' => 'hero.jpg',    'hint' => 'Startseite – großes Bild rechts'],
  'salon'     => ['label' => 'Salon-Bild',   'filename' => 'salon.jpg',   'hint' => '„Über uns"-Bereich'],
  'team_lyv'  => ['label' => 'Team: Lyv',    'filename' => 'team-lyv.jpg','hint' => 'Teamkarte 1'],
  'team_nova' => ['label' => 'Team: Nova',   'filename' => 'team-nova.jpg','hint'=> 'Teamkarte 2'],
  'team_mika' => ['label' => 'Team: Mika',   'filename' => 'team-mika.jpg','hint'=> 'Teamkarte 3'],
  'team_ella' => ['label' => 'Team: Ella',   'filename' => 'team-ella.jpg','hint'=> 'Teamkarte 4'],
  'gallery_1' => ['label' => 'Galerie 1',    'filename' => 'gallery-1.jpg','hint'=> 'Impressionen-Galerie Bild 1'],
  'gallery_2' => ['label' => 'Galerie 2',    'filename' => 'gallery-2.jpg','hint'=> 'Impressionen-Galerie Bild 2'],
  'gallery_3' => ['label' => 'Galerie 3',    'filename' => 'gallery-3.jpg','hint'=> 'Impressionen-Galerie Bild 3'],
  'gallery_4' => ['label' => 'Galerie 4',    'filename' => 'gallery-4.jpg','hint'=> 'Impressionen-Galerie Bild 4'],
  'gallery_5' => ['label' => 'Galerie 5',    'filename' => 'gallery-5.jpg','hint'=> 'Impressionen-Galerie Bild 5'],
  'gallery_6' => ['label' => 'Galerie 6',    'filename' => 'gallery-6.jpg','hint'=> 'Impressionen-Galerie Bild 6'],
];

$error_message   = '';
$success_message = '';

admin_logout();
admin_handle_login($error_message);

$authenticated = admin_is_authenticated();

if ($authenticated && isset($_POST['upload_images'])) {
  $errors    = [];
  $uploaded  = [];
  $allowed   = ['jpg', 'jpeg', 'png', 'webp'];

  if (!is_dir($images_dir) && !mkdir($images_dir, 0755, true)) {
    $errors[] = 'Der Bilderordner konnte nicht erstellt werden.';
  } else {
    foreach ($slots as $field => $slot) {
      if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        continue;
      }
      $file = $_FILES[$field];

      if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = $slot['label'] . ': Upload fehlgeschlagen (Code ' . $file['error'] . ').';
        continue;
      }

      $ext = strtolower(pathinfo((string)$file['name'], PATHINFO_EXTENSION));
      if (!in_array($ext, $allowed, true)) {
        $errors[] = $slot['label'] . ': Nur JPG, PNG oder WEBP erlaubt.';
        continue;
      }

      if (@getimagesize($file['tmp_name']) === false) {
        $errors[] = $slot['label'] . ': Kein gültiges Bild.';
        continue;
      }

      $target = $images_dir . '/' . $slot['filename'];
      if (!move_uploaded_file($file['tmp_name'], $target)) {
        $errors[] = $slot['label'] . ': Speichern fehlgeschlagen.';
        continue;
      }

      $uploaded[] = $slot['label'];
    }
  }

  if (!empty($errors)) {
    $error_message = implode(' ', $errors);
  } elseif (empty($uploaded)) {
    $error_message = 'Bitte mindestens ein Bild auswählen.';
  } else {
    $success_message = 'Gespeichert: ' . implode(', ', $uploaded) . '.';
  }
}

/* ── Groups for the UI ── */
$slot_groups = [
  'Allgemein'   => ['hero', 'salon'],
  'Team'        => ['team_lyv', 'team_nova', 'team_mika', 'team_ella'],
  'Galerie'     => ['gallery_1', 'gallery_2', 'gallery_3', 'gallery_4', 'gallery_5', 'gallery_6'],
];
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bilder verwalten · Lyv's Haarstudio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Carattere&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet" />
    <style>
      :root {
        --beige: #f5e9dd;
        --beige-light: #fff7f0;
        --brown: #5b3a29;
        --accent: #8b5e3c;
        --max-width: 980px;
        --heading-font: "Carattere","Playfair Display",serif;
        --body-font: "Poppins","Helvetica Neue",Arial,sans-serif;
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
      .notice {
        padding: .8rem 1rem;
        border-radius: 12px;
        margin-bottom: 1.2rem;
        font-weight: 500;
        font-size: .92rem;
      }
      .notice.error   { background: rgba(201,69,69,.12); color: #8a2f2f; }
      .notice.success { background: rgba(81,139,60,.12);  color: #3e6b2c; }

      .group-title {
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .15em;
        color: var(--accent);
        font-weight: 600;
        margin: 1.5rem 0 .8rem;
        display: flex;
        align-items: center;
        gap: .7rem;
      }
      .group-title::after { content: ""; flex: 1; height: 1px; background: rgba(91,58,41,.12); }

      .image-grid { display: grid; gap: .8rem; }
      .image-card {
        display: grid;
        grid-template-columns: 80px 1fr;
        gap: 1rem;
        align-items: start;
        padding: .9rem 1rem;
        border-radius: 16px;
        border: 1px solid rgba(91,58,41,.12);
        background: var(--beige-light);
        transition: border-color .2s;
      }
      .image-card:has(input[type="file"]:focus-within) { border-color: var(--accent); }
      .preview-thumb {
        width: 80px;
        height: 70px;
        border-radius: 10px;
        overflow: hidden;
        background: var(--beige);
        border: 1px solid rgba(91,58,41,.1);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
      }
      .preview-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
      .preview-thumb .thumb-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .2rem;
        font-size: .6rem;
        color: rgba(91,58,41,.45);
        text-align: center;
        padding: .3rem;
      }
      .preview-thumb .thumb-placeholder span { font-size: 1.4rem; display: block; }
      .card-info { min-width: 0; }
      .card-info .label { font-weight: 600; font-size: .9rem; margin-bottom: .15rem; }
      .card-info .hint { font-size: .78rem; color: rgba(91,58,41,.6); margin-bottom: .5rem; }
      input[type="file"] { width: 100%; font-size: .85rem; }
      .actions {
        display: flex;
        flex-wrap: wrap;
        gap: .8rem;
        margin-top: 1.5rem;
        position: sticky;
        bottom: 1rem;
        background: rgba(255,255,255,.95);
        padding: .8rem;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(91,58,41,.12);
      }
      .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: .65rem 1.5rem;
        border-radius: 999px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-family: var(--body-font);
        font-size: .88rem;
        transition: transform .2s, box-shadow .2s;
      }
      .btn-primary { background: var(--accent); color: #fff; box-shadow: 0 8px 20px rgba(139,94,60,.22); }
      .btn-secondary { background: var(--beige-light); color: var(--brown); border: 1px solid rgba(91,58,41,.2); }
      .btn:hover, .btn:focus-visible { transform: translateY(-2px); }
      .muted { font-size: .8rem; color: rgba(91,58,41,.6); margin-top: .8rem; }

      @media (min-width: 600px) {
        .image-grid { grid-template-columns: 1fr 1fr; }
      }
      @media (max-width: 500px) {
        .image-card { grid-template-columns: 60px 1fr; }
        .preview-thumb { width: 60px; height: 55px; }
        main { padding: 1.4rem; }
      }
    </style>
  </head>
  <body>
    <header>
      <h1>Bilder verwalten</h1>
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
          <div style="margin-bottom:1rem;">
            <label style="display:block;font-size:.8rem;text-transform:uppercase;letter-spacing:.12em;margin-bottom:.3rem;color:var(--accent);font-weight:600;">Zugangscode</label>
            <input type="password" id="access_code" name="access_code" required
              style="width:100%;padding:.65rem .85rem;border-radius:10px;border:1px solid rgba(91,58,41,.2);font-size:.95rem;font-family:inherit;" />
          </div>
          <div class="actions">
            <button class="btn btn-primary" type="submit">Weiter</button>
            <a class="btn btn-secondary" href="admin.php">Zurück</a>
          </div>
        </form>
      <?php else: ?>
        <form method="post" enctype="multipart/form-data">
          <input type="hidden" name="upload_images" value="1" />

          <?php foreach ($slot_groups as $group_label => $group_keys): ?>
            <div class="group-title"><?php echo esc($group_label); ?></div>
            <div class="image-grid">
              <?php foreach ($group_keys as $field): ?>
                <?php $slot = $slots[$field]; ?>
                <?php $existing = $images_dir . '/' . $slot['filename']; ?>
                <div class="image-card">
                  <!-- Preview thumbnail -->
                  <div class="preview-thumb">
                    <?php if (is_file($existing)): ?>
                      <img src="images/<?php echo esc($slot['filename']); ?>?<?php echo filemtime($existing); ?>"
                           alt="<?php echo esc($slot['label']); ?>" />
                    <?php else: ?>
                      <div class="thumb-placeholder">
                        <span>🖼</span>
                        kein Bild
                      </div>
                    <?php endif; ?>
                  </div>

                  <!-- Input -->
                  <div class="card-info">
                    <div class="label"><?php echo esc($slot['label']); ?></div>
                    <div class="hint"><?php echo esc($slot['hint']); ?></div>
                    <input type="file" id="<?php echo esc($field); ?>" name="<?php echo esc($field); ?>"
                           accept="image/jpeg,image/png,image/webp" />
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endforeach; ?>

          <div class="actions">
            <button class="btn btn-primary" type="submit">📤 Bilder speichern</button>
            <a class="btn btn-secondary" href="admin.php">Zurück</a>
            <button class="btn btn-secondary" type="submit" name="logout" value="1">Logout</button>
          </div>
        </form>
        <p class="muted">Erlaubt: JPG, PNG oder WEBP. Bestehende Bilder werden beim Upload überschrieben.</p>
      <?php endif; ?>
    </main>
  </body>
</html>
