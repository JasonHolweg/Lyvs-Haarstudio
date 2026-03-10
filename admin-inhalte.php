<?php
require __DIR__ . '/admin-helpers.php';

$content_path = __DIR__ . '/data/content.json';
$default_content = require __DIR__ . '/content-defaults.php';

function load_content($path, $defaults)
{
  if (!is_file($path)) {
    return $defaults;
  }

  $raw = file_get_contents($path);
  $decoded = json_decode($raw, true);

  if (!is_array($decoded)) {
    return $defaults;
  }

  return array_replace_recursive($defaults, $decoded);
}

function save_content($path, $data, &$error_message)
{
  $directory = dirname($path);
  if (!is_dir($directory) && !mkdir($directory, 0755, true)) {
    $error_message = 'Der Ordner fuer die Inhalte konnte nicht erstellt werden.';
    return false;
  }

  $payload = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  if ($payload === false) {
    $error_message = 'Die Inhaltsdaten konnten nicht gespeichert werden.';
    return false;
  }

  if (file_put_contents($path, $payload, LOCK_EX) === false) {
    $error_message = 'Die Inhaltsdaten konnten nicht geschrieben werden.';
    return false;
  }

  return true;
}

$error_message = '';
$success_message = '';

admin_logout();
admin_handle_login($error_message);

$authenticated = admin_is_authenticated();
$content_data = load_content($content_path, $default_content);

if ($authenticated && isset($_POST['save_content'])) {
  $incoming = $_POST['content'] ?? [];

  $payload = [
    'hero' => [
      'tagline' => trim((string) ($incoming['hero']['tagline'] ?? '')),
      'title' => trim((string) ($incoming['hero']['title'] ?? '')),
      'text' => trim((string) ($incoming['hero']['text'] ?? '')),
    ],
    'about' => [
      'title' => trim((string) ($incoming['about']['title'] ?? '')),
      'paragraphs' => [],
    ],
    'team' => [
      'title' => trim((string) ($incoming['team']['title'] ?? '')),
      'intro' => trim((string) ($incoming['team']['intro'] ?? '')),
      'members' => [],
    ],
    'services' => [
      'title' => trim((string) ($incoming['services']['title'] ?? '')),
      'intro' => trim((string) ($incoming['services']['intro'] ?? '')),
    ],
    'appointment' => [
      'title' => trim((string) ($incoming['appointment']['title'] ?? '')),
      'text' => trim((string) ($incoming['appointment']['text'] ?? '')),
      'note' => trim((string) ($incoming['appointment']['note'] ?? '')),
    ],
    'contact' => [
      'title' => trim((string) ($incoming['contact']['title'] ?? '')),
      'studio_name' => trim((string) ($incoming['contact']['studio_name'] ?? '')),
      'address_line' => trim((string) ($incoming['contact']['address_line'] ?? '')),
      'map_label' => trim((string) ($incoming['contact']['map_label'] ?? '')),
    ],
    'footer' => [
      'credit' => $content_data['footer']['credit'] ?? ($default_content['footer']['credit'] ?? ''),
    ],
  ];

  $incoming_paragraphs = $incoming['about']['paragraphs'] ?? [];
  foreach ($default_content['about']['paragraphs'] as $index => $paragraph) {
    $payload['about']['paragraphs'][] = trim((string) ($incoming_paragraphs[$index] ?? ''));
  }

  $incoming_members = $incoming['team']['members'] ?? [];
  foreach ($incoming_members as $entry) {
    $name = trim((string) ($entry['name'] ?? ''));
    $role = trim((string) ($entry['role'] ?? ''));
    $bio = trim((string) ($entry['bio'] ?? ''));

    if ($name === '' && $role === '' && $bio === '') {
      continue;
    }

    $payload['team']['members'][] = [
      'name' => $name,
      'role' => $role,
      'bio' => $bio,
    ];
  }


  if (save_content($content_path, $payload, $error_message)) {
    $success_message = 'Inhalte gespeichert.';
    $content_data = $payload;
  }
}

$about_paragraphs = $content_data['about']['paragraphs'] ?? $default_content['about']['paragraphs'];
if (!is_array($about_paragraphs)) {
  $about_paragraphs = $default_content['about']['paragraphs'];
}
$team_members = $content_data['team']['members'] ?? $default_content['team']['members'];
if (!is_array($team_members)) {
  $team_members = $default_content['team']['members'];
}
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Beschreibungen bearbeiten · Lyv's Haarstudio</title>
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
        --max-width: 1040px;
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

      .section {
        border: 1px solid rgba(91, 58, 41, 0.12);
        border-radius: 22px;
        padding: 1.6rem;
        margin-bottom: 1.6rem;
        background: var(--beige-light);
      }

      .section h2 {
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: var(--accent);
        margin-bottom: 1rem;
      }

      .field {
        margin-bottom: 1rem;
      }

      label {
        display: block;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        margin-bottom: 0.35rem;
        color: var(--accent);
      }

      input[type="text"],
      textarea,
      input[type="password"] {
        width: 100%;
        padding: 0.7rem 0.9rem;
        border-radius: 10px;
        border: 1px solid rgba(91, 58, 41, 0.2);
        font-size: 1rem;
        font-family: inherit;
      }

      textarea {
        min-height: 90px;
        resize: vertical;
      }

      .team-grid {
        display: grid;
        gap: 1rem;
      }

      .team-card {
        padding: 1rem;
        border-radius: 16px;
        border: 1px solid rgba(91, 58, 41, 0.15);
        background: #fff;
      }

      .team-card .card-actions {
        margin-top: 0.4rem;
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

      .btn-text {
        background: none;
        border: none;
        color: var(--accent);
        cursor: pointer;
        font-weight: 600;
        padding: 0.2rem 0;
      }

      .btn:hover,
      .btn:focus-visible {
        transform: translateY(-2px);
      }

      .muted {
        margin-top: 0.6rem;
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
      <h1>Beschreibungen bearbeiten</h1>
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
          <div class="section">
            <label for="access_code">Zugangscode</label>
            <input type="password" id="access_code" name="access_code" required />
          </div>
          <div class="actions">
            <button class="btn btn-primary" type="submit">Weiter</button>
            <a class="btn btn-secondary" href="admin.php">Zurueck</a>
          </div>
        </form>
      <?php else : ?>
        <form method="post">
          <input type="hidden" name="save_content" value="1" />

          <div class="section">
            <h2>Hero</h2>
            <div class="field">
              <label>Tagline</label>
              <input type="text" name="content[hero][tagline]" value="<?php echo esc($content_data['hero']['tagline'] ?? ''); ?>" />
            </div>
            <div class="field">
              <label>Ueberschrift</label>
              <input type="text" name="content[hero][title]" value="<?php echo esc($content_data['hero']['title'] ?? ''); ?>" />
            </div>
            <div class="field">
              <label>Text</label>
              <textarea name="content[hero][text]"><?php echo esc($content_data['hero']['text'] ?? ''); ?></textarea>
            </div>
          </div>

          <div class="section">
            <h2>Unsere Geschichte</h2>
            <div class="field">
              <label>Ueberschrift</label>
              <input type="text" name="content[about][title]" value="<?php echo esc($content_data['about']['title'] ?? ''); ?>" />
            </div>
            <?php foreach ($about_paragraphs as $index => $paragraph) : ?>
              <div class="field">
                <label>Absatz <?php echo esc($index + 1); ?></label>
                <textarea name="content[about][paragraphs][<?php echo esc($index); ?>]"><?php echo esc($paragraph); ?></textarea>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="section">
            <h2>Team</h2>
            <div class="field">
              <label>Ueberschrift</label>
              <input type="text" name="content[team][title]" value="<?php echo esc($content_data['team']['title'] ?? ''); ?>" />
            </div>
            <div class="field">
              <label>Einleitung</label>
              <textarea name="content[team][intro]"><?php echo esc($content_data['team']['intro'] ?? ''); ?></textarea>
            </div>
            <div class="team-grid" id="team-members">
              <?php foreach ($team_members as $index => $member) : ?>
                <div class="team-card" data-member>
                  <div class="field">
                    <label>Name</label>
                    <input
                      type="text"
                      name="content[team][members][<?php echo esc($index); ?>][name]"
                      data-name-template="content[team][members][__INDEX__][name]"
                      value="<?php echo esc($member['name'] ?? ''); ?>"
                    />
                  </div>
                  <div class="field">
                    <label>Rolle</label>
                    <input
                      type="text"
                      name="content[team][members][<?php echo esc($index); ?>][role]"
                      data-name-template="content[team][members][__INDEX__][role]"
                      value="<?php echo esc($member['role'] ?? ''); ?>"
                    />
                  </div>
                  <div class="field">
                    <label>Beschreibung</label>
                    <textarea name="content[team][members][<?php echo esc($index); ?>][bio]" data-name-template="content[team][members][__INDEX__][bio]"><?php echo esc($member['bio'] ?? ''); ?></textarea>
                  </div>
                  <div class="card-actions">
                    <button class="btn-text" type="button" data-remove-member>Entfernen</button>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="actions">
              <button class="btn btn-secondary" type="button" data-add-member>Teammitglied hinzufuegen</button>
            </div>
            <p class="muted">Neue Teammitglieder nutzen nach dem 4. Foto ein Platzhalterbild.</p>
          </div>

          <div class="section">
            <h2>Leistungen</h2>
            <div class="field">
              <label>Ueberschrift</label>
              <input type="text" name="content[services][title]" value="<?php echo esc($content_data['services']['title'] ?? ''); ?>" />
            </div>
            <div class="field">
              <label>Einleitung</label>
              <textarea name="content[services][intro]"><?php echo esc($content_data['services']['intro'] ?? ''); ?></textarea>
            </div>
          </div>

          <div class="section">
            <h2>Termin</h2>
            <div class="field">
              <label>Ueberschrift</label>
              <input type="text" name="content[appointment][title]" value="<?php echo esc($content_data['appointment']['title'] ?? ''); ?>" />
            </div>
            <div class="field">
              <label>Text</label>
              <textarea name="content[appointment][text]"><?php echo esc($content_data['appointment']['text'] ?? ''); ?></textarea>
            </div>
            <div class="field">
              <label>Hinweis</label>
              <textarea name="content[appointment][note]"><?php echo esc($content_data['appointment']['note'] ?? ''); ?></textarea>
            </div>
          </div>

          <div class="section">
            <h2>Kontakt</h2>
            <div class="field">
              <label>Ueberschrift</label>
              <input type="text" name="content[contact][title]" value="<?php echo esc($content_data['contact']['title'] ?? ''); ?>" />
            </div>
            <div class="field">
              <label>Studio-Name</label>
              <input type="text" name="content[contact][studio_name]" value="<?php echo esc($content_data['contact']['studio_name'] ?? ''); ?>" />
            </div>
            <div class="field">
              <label>Adresse</label>
              <input type="text" name="content[contact][address_line]" value="<?php echo esc($content_data['contact']['address_line'] ?? ''); ?>" />
            </div>
            <div class="field">
              <label>Map-Label</label>
              <input type="text" name="content[contact][map_label]" value="<?php echo esc($content_data['contact']['map_label'] ?? ''); ?>" />
            </div>
          </div>

          <div class="actions">
            <button class="btn btn-primary" type="submit">Speichern</button>
            <a class="btn btn-secondary" href="admin.php">Zurueck</a>
            <button class="btn btn-secondary" type="submit" name="logout" value="1">Logout</button>
          </div>
        </form>
        <p class="muted">Aenderungen sind sofort auf der Startseite sichtbar.</p>
      <?php endif; ?>
    </main>

    <template id="team-member-template">
      <div class="team-card" data-member>
        <div class="field">
          <label>Name</label>
          <input type="text" data-name-template="content[team][members][__INDEX__][name]" value="" />
        </div>
        <div class="field">
          <label>Rolle</label>
          <input type="text" data-name-template="content[team][members][__INDEX__][role]" value="" />
        </div>
        <div class="field">
          <label>Beschreibung</label>
          <textarea data-name-template="content[team][members][__INDEX__][bio]"></textarea>
        </div>
        <div class="card-actions">
          <button class="btn-text" type="button" data-remove-member>Entfernen</button>
        </div>
      </div>
    </template>

    <script>
      const teamContainer = document.querySelector("#team-members");
      const addMemberButton = document.querySelector("[data-add-member]");
      const memberTemplate = document.querySelector("#team-member-template");

      function refreshMemberIndexes() {
        if (!teamContainer) {
          return;
        }

        const members = teamContainer.querySelectorAll("[data-member]");
        members.forEach((member, index) => {
          member.querySelectorAll("[data-name-template]").forEach((field) => {
            field.name = field.dataset.nameTemplate.replace(/__INDEX__/g, index);
          });
        });
      }

      function addMember() {
        if (!teamContainer || !memberTemplate) {
          return;
        }

        const fragment = memberTemplate.content.cloneNode(true);
        teamContainer.appendChild(fragment);
        refreshMemberIndexes();
      }

      document.addEventListener("click", (event) => {
        if (event.target.matches("[data-add-member]")) {
          addMember();
        }

        if (event.target.matches("[data-remove-member]")) {
          const card = event.target.closest("[data-member]");
          if (card) {
            card.remove();
            refreshMemberIndexes();
          }
        }
      });

      if (addMemberButton && teamContainer) {
        refreshMemberIndexes();
      }
    </script>
  </body>
</html>
