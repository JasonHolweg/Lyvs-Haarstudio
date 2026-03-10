<?php
require __DIR__ . '/admin-helpers.php';

$error_message = '';

admin_logout();
admin_handle_login($error_message);

$authenticated = admin_is_authenticated();
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin · Lyv's Haarstudio</title>
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
        --max-width: 920px;
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
        padding: 2.4rem;
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

      .field {
        margin-bottom: 1.2rem;
      }

      label {
        display: block;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        margin-bottom: 0.35rem;
        color: var(--accent);
      }

      input[type="password"] {
        width: 100%;
        padding: 0.7rem 0.9rem;
        border-radius: 10px;
        border: 1px solid rgba(91, 58, 41, 0.2);
        font-size: 1rem;
        font-family: inherit;
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

      .admin-links {
        display: grid;
        gap: 1rem;
        margin-top: 1.5rem;
      }

      .admin-link {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        padding: 1.2rem 1.4rem;
        border-radius: 18px;
        background: var(--beige-light);
        border: 1px solid rgba(91, 58, 41, 0.15);
        color: inherit;
        text-decoration: none;
      }

      .admin-link strong {
        font-size: 1.1rem;
      }

      .admin-footer {
        margin-top: 1.5rem;
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
      <h1>Admin Bereich</h1>
      <p>Lyv's Haarstudio</p>
    </header>

    <main>
      <?php if ($error_message !== '') : ?>
        <div class="notice error"><?php echo esc($error_message); ?></div>
      <?php endif; ?>

      <?php if (!$authenticated) : ?>
        <form method="post">
          <div class="field">
            <label for="access_code">Zugangscode</label>
            <input type="password" id="access_code" name="access_code" required />
          </div>
          <button class="btn btn-primary" type="submit">Weiter</button>
        </form>
      <?php else : ?>
        <p>Hier kannst du die Website-Inhalte pflegen.</p>
        <div class="admin-links">
          <a class="admin-link" href="admin-preise.php">
            <strong>Preise bearbeiten</strong>
            <span>Leistungen, Titel und Preise anpassen.</span>
          </a>
          <a class="admin-link" href="admin-bilder.php">
            <strong>Bilder hochladen</strong>
            <span>Hero, Salon und Team-Fotos austauschen.</span>
          </a>
          <a class="admin-link" href="admin-inhalte.php">
            <strong>Beschreibungen bearbeiten</strong>
            <span>Texte auf der Startseite aktualisieren.</span>
          </a>
        </div>

        <form method="post" class="admin-footer">
          <button class="btn btn-secondary" type="submit" name="logout" value="1">Logout</button>
        </form>
      <?php endif; ?>
    </main>
  </body>
</html>
