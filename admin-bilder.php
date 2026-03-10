<?php
require __DIR__ . '/admin-helpers.php';

$images_dir = __DIR__ . '/images';

$slots = [
  'hero' => [
    'label' => 'Hero-Bild',
    'filename' => 'hero.jpg',
    'hint' => 'Startseite oben',
  ],
  'salon' => [
    'label' => 'Salon-Bild',
    'filename' => 'salon.jpg',
    'hint' => 'Bereich "Unsere Geschichte"',
  ],
  'team_lyv' => [
    'label' => 'Teamfoto Lyv',
    'filename' => 'team-lyv.jpg',
    'hint' => 'Teamkarte 1',
  ],
  'team_nova' => [
    'label' => 'Teamfoto Nova',
    'filename' => 'team-nova.jpg',
    'hint' => 'Teamkarte 2',
  ],
  'team_mika' => [
    'label' => 'Teamfoto Mika',
    'filename' => 'team-mika.jpg',
    'hint' => 'Teamkarte 3',
  ],
  'team_ella' => [
    'label' => 'Teamfoto Ella',
    'filename' => 'team-ella.jpg',
    'hint' => 'Teamkarte 4',
  ],
];

$error_message = '';
$success_message = '';

admin_logout();
admin_handle_login($error_message);

$authenticated = admin_is_authenticated();

if ($authenticated && isset($_POST['upload_images'])) {
  $errors = [];
  $uploaded = [];
  $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];

  if (!is_dir($images_dir) && !mkdir($images_dir, 0755, true)) {
    $errors[] = 'Der Bilderordner konnte nicht erstellt werden.';
  } else {
    foreach ($slots as $field => $slot) {
      if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
        continue;
      }

      $file = $_FILES[$field];

      if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = $slot['label'] . ': Upload fehlgeschlagen.';
        continue;
      }

      $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
      if (!in_array($extension, $allowed_extensions, true)) {
        $errors[] = $slot['label'] . ': Bitte JPG, PNG oder WEBP verwenden.';
        continue;
      }

      if (@getimagesize($file['tmp_name']) === false) {
        $errors[] = $slot['label'] . ': Datei ist kein gueltiges Bild.';
        continue;
      }

      $target_path = $images_dir . '/' . $slot['filename'];
      if (!move_uploaded_file($file['tmp_name'], $target_path)) {
        $errors[] = $slot['label'] . ': Speichern fehlgeschlagen.';
        continue;
      }

      $uploaded[] = $slot['label'];
    }
  }

  if (!empty($errors)) {
    $error_message = implode(' ', $errors);
  } elseif (empty($uploaded)) {
    $error_message = 'Bitte mindestens ein Bild auswaehlen.';
  } else {
    $success_message = 'Erfolgreich gespeichert: ' . implode(', ', $uploaded) . '.';
  }
}
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Bilder hochladen · Lyv's Haarstudio</title>
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
        --max-width: 980px;
        --heading-font: "Carattere", "Playfair Display", serif;
        --body-font: "Poppins", "Helvetica Neue", Arial, sans-serif;
      }

      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }

      body {
        font-family: var(--body-font);
        background: var(--beige);
        color: var(--brown);
        line-height: 1.6;
        padding: 2.5rem 1.5rem 4rem;
      }

      header {
        text-align: center;
        margin-bottom: 2rem;
      }

      h1 {
        font-family: var(--heading-font);
        font-size: clamp(2.4rem, 5vw, 3.2rem);
      }

      main {
        max-width: var(--max-width);
        margin: 0 auto;
        background: #fff;
        border-radius: 28px;
        padding: 2.5rem;
        box-shadow: 0 20px 45px rgba(91, 58, 41, 0.15);
      }

      .notice {
        padding: 0.9rem 1rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        font-weight: 500;
      }

      .notice.error {
        background: rgba(201, 69, 69, 0.15);
        color: #8a2f2f;
      }

      .notice.success {
        background: rgba(81, 139, 60, 0.15);
        color: #3e6b2c;
      }

      .image-grid {
        display: grid;
        gap: 1rem;
      }

      .image-card {
        padding: 1rem 1.2rem;
        border-radius: 18px;
        border: 1px solid rgba(91, 58, 41, 0.15);
        background: var(--beige-light);
      }

      label {
        font-size: 0.95rem;
        font-weight: 600;
        display: block;
      }

      .hint {
        font-size: 0.85rem;
        color: rgba(91, 58, 41, 0.7);
        margin-top: 0.2rem;
        margin-bottom: 0.6rem;
      }

      input[type="file"] {
        width: 100%;
      }

      .actions {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1.5rem;
      }

      .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem 1.8rem;
        border-radius: 999px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        font-family: inherit;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
      }

      .btn-primary {
        background: var(--accent);
        color: #fff;
        box-shadow: 0 12px 24px rgba(139, 94, 60, 0.25);
      }

      .btn-secondary {
        background: var(--beige-light);
        color: var(--brown);
        border: 1px solid rgba(91, 58, 41, 0.2);
      }

      .btn:hover,
      .btn:focus-visible {
        transform: translateY(-2px);
      }

      .muted {
        margin-top: 1rem;
        font-size: 0.9rem;
        color: rgba(91, 58, 41, 0.75);
      }

      @media (max-width: 700px) {
        main {
          padding: 1.8rem;
        }
      }
    </style>
  </head>
  <body>
    <header>
      <h1>Bilder hochladen</h1>
      <p>Lyv's Haarstudio</p>
    </header>

    <main>
      <?php if ($error_message !== '') : ?>
        <div class="notice error"><?php echo esc($error_message); ?></div>
      <?php endif; ?>
      <?php if ($success_message !== '') : ?>
        <div class="notice success"><?php echo esc($success_message); ?></div>
      <?php endif; ?>

      <?php if (!$authenticated) : ?>
        <form method="post">
          <div class="image-card">
            <label for="access_code">Zugangscode</label>
            <div class="hint">Bitte den Admin-Code eingeben.</div>
            <input type="password" id="access_code" name="access_code" required />
          </div>
          <div class="actions">
            <button class="btn btn-primary" type="submit">Weiter</button>
            <a class="btn btn-secondary" href="admin.php">Zurueck</a>
          </div>
        </form>
      <?php else : ?>
        <form method="post" enctype="multipart/form-data">
          <input type="hidden" name="upload_images" value="1" />
          <div class="image-grid">
            <?php foreach ($slots as $field => $slot) : ?>
              <div class="image-card">
                <label for="<?php echo esc($field); ?>"><?php echo esc($slot['label']); ?></label>
                <div class="hint">
                  <?php echo esc($slot['hint']); ?> · Datei: <strong><?php echo esc($slot['filename']); ?></strong>
                </div>
                <input type="file" id="<?php echo esc($field); ?>" name="<?php echo esc($field); ?>" accept="image/*" />
              </div>
            <?php endforeach; ?>
          </div>

          <div class="actions">
            <button class="btn btn-primary" type="submit">Bilder speichern</button>
            <a class="btn btn-secondary" href="admin.php">Zurueck</a>
            <button class="btn btn-secondary" type="submit" name="logout" value="1">Logout</button>
          </div>
        </form>
        <p class="muted">Erlaubt sind JPG, PNG oder WEBP. Bestehende Bilder werden ueberschrieben.</p>
      <?php endif; ?>
    </main>
  </body>
</html>
