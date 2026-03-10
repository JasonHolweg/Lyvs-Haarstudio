<?php
require __DIR__ . '/admin-helpers.php';

$prices_path = __DIR__ . '/data/prices.json';

function load_prices($path)
{
  if (!is_file($path)) {
    return ['categories' => []];
  }

  $raw = file_get_contents($path);
  $decoded = json_decode($raw, true);

  if (!is_array($decoded) || !isset($decoded['categories']) || !is_array($decoded['categories'])) {
    return ['categories' => []];
  }

  return $decoded;
}

function save_prices($path, $data, &$error)
{
  $directory = dirname($path);
  if (!is_dir($directory) && !mkdir($directory, 0755, true)) {
    $error = 'Der Ordner fuer die Preisliste konnte nicht erstellt werden.';
    return false;
  }

  $payload = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
  if ($payload === false) {
    $error = 'Die Preisdaten konnten nicht gespeichert werden.';
    return false;
  }

  if (file_put_contents($path, $payload, LOCK_EX) === false) {
    $error = 'Die Preisdaten konnten nicht geschrieben werden.';
    return false;
  }

  return true;
}

$prices_data = load_prices($prices_path);
$error_message = '';
$success_message = '';

admin_logout();
admin_handle_login($error_message);

$authenticated = admin_is_authenticated();

if ($authenticated && isset($_POST['save_prices'])) {
  $incoming_categories = $_POST['categories'] ?? [];
  $categories = [];

  foreach ($incoming_categories as $category) {
    $title = trim((string) ($category['title'] ?? ''));
    $items = [];

    foreach (($category['items'] ?? []) as $item) {
      $label = trim((string) ($item['label'] ?? ''));
      $price = trim((string) ($item['price'] ?? ''));

      if ($label === '' && $price === '') {
        continue;
      }

      $items[] = [
        'label' => $label,
        'price' => $price,
      ];
    }

    if ($title === '' && empty($items)) {
      continue;
    }

    if ($title === '') {
      $title = 'Leistungen';
    }

    if (empty($items)) {
      $items[] = ['label' => 'Neue Position', 'price' => ''];
    }

    $categories[] = [
      'title' => $title,
      'items' => $items,
    ];
  }

  if (empty($categories)) {
    $error_message = 'Bitte mindestens einen Bereich und eine Position anlegen.';
  } else {
    $payload = ['categories' => $categories];
    if (save_prices($prices_path, $payload, $error_message)) {
      $success_message = 'Preisliste gespeichert.';
      $prices_data = $payload;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Preisliste bearbeiten · Lyv's Haarstudio</title>
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

      input[type="text"],
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

      .btn-text {
        background: none;
        border: none;
        color: var(--accent);
        cursor: pointer;
        font-weight: 600;
        padding: 0.4rem 0.2rem;
      }

      .btn:hover,
      .btn:focus-visible {
        transform: translateY(-2px);
      }

      .category-block {
        border: 1px solid rgba(91, 58, 41, 0.12);
        border-radius: 20px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        background: var(--beige-light);
      }

      .category-header {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: center;
        justify-content: space-between;
      }

      .category-header .field {
        flex: 1 1 260px;
      }

      .items {
        margin-top: 1.2rem;
        display: grid;
        gap: 0.9rem;
      }

      .item-row {
        display: grid;
        grid-template-columns: 1.8fr 1fr auto;
        gap: 0.75rem;
        align-items: center;
      }

      .actions {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1.5rem;
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

        .item-row {
          grid-template-columns: 1fr;
        }

        .category-header {
          align-items: flex-start;
        }
      }
    </style>
  </head>
  <body>
    <header>
      <h1>Preisliste bearbeiten</h1>
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
          <div class="field">
            <label for="access_code">Zugangscode</label>
            <input type="password" id="access_code" name="access_code" required />
          </div>
          <button class="btn btn-primary" type="submit">Weiter</button>
          <p class="muted">
            Den Zugangscode kannst du in der Datei <strong>lyvs/admin-config.php</strong> aendern.
          </p>
        </form>
      <?php else : ?>
        <form method="post">
          <input type="hidden" name="save_prices" value="1" />
          <div id="categories">
            <?php foreach ($prices_data['categories'] as $cat_index => $category) : ?>
              <div class="category-block" data-category-index="<?php echo esc($cat_index); ?>">
                <div class="category-header">
                  <div class="field">
                    <label>Bereichstitel</label>
                    <input
                      type="text"
                      name="categories[<?php echo esc($cat_index); ?>][title]"
                      value="<?php echo esc($category['title'] ?? ''); ?>"
                      required
                    />
                  </div>
                  <div>
                    <button class="btn btn-secondary" type="button" data-add-item>Position hinzufuegen</button>
                    <button class="btn-text" type="button" data-remove-category>Bereich entfernen</button>
                  </div>
                </div>

                <div class="items">
                  <?php foreach (($category['items'] ?? []) as $item_index => $item) : ?>
                    <div class="item-row">
                      <input
                        type="text"
                        name="categories[<?php echo esc($cat_index); ?>][items][<?php echo esc($item_index); ?>][label]"
                        value="<?php echo esc($item['label'] ?? ''); ?>"
                        placeholder="Beschreibung"
                        required
                      />
                      <input
                        type="text"
                        name="categories[<?php echo esc($cat_index); ?>][items][<?php echo esc($item_index); ?>][price]"
                        value="<?php echo esc($item['price'] ?? ''); ?>"
                        placeholder="Preis"
                      />
                      <button class="btn-text" type="button" data-remove-item>Entfernen</button>
                    </div>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>

          <div class="actions">
            <button class="btn btn-secondary" type="button" data-add-category>Neuen Bereich hinzufuegen</button>
            <button class="btn btn-primary" type="submit">Speichern</button>
            <a class="btn btn-secondary" href="admin.php">Zurueck</a>
          </div>
        </form>

        <form method="post" style="margin-top: 1.5rem">
          <button class="btn btn-secondary" type="submit" name="logout" value="1">Logout</button>
        </form>
        <p class="muted">Die Aenderungen sind sofort auf der Startseite sichtbar.</p>
      <?php endif; ?>
    </main>

    <template id="category-template">
      <div class="category-block" data-category-index="__CAT__">
        <div class="category-header">
          <div class="field">
            <label>Bereichstitel</label>
            <input
              type="text"
              data-name-template="categories[__CAT__][title]"
              value=""
              placeholder="z. B. Damen"
              required
            />
          </div>
          <div>
            <button class="btn btn-secondary" type="button" data-add-item>Position hinzufuegen</button>
            <button class="btn-text" type="button" data-remove-category>Bereich entfernen</button>
          </div>
        </div>
        <div class="items"></div>
      </div>
    </template>

    <template id="item-template">
      <div class="item-row">
        <input
          type="text"
          data-name-template="categories[__CAT__][items][__ITEM__][label]"
          value=""
          placeholder="Beschreibung"
          required
        />
        <input
          type="text"
          data-name-template="categories[__CAT__][items][__ITEM__][price]"
          value=""
          placeholder="Preis"
        />
        <button class="btn-text" type="button" data-remove-item>Entfernen</button>
      </div>
    </template>

    <script>
      const categoryContainer = document.querySelector("#categories");
      const categoryTemplate = document.querySelector("#category-template");
      const itemTemplate = document.querySelector("#item-template");

      if (categoryContainer && categoryTemplate && itemTemplate) {

      function applyNameTemplates(root, categoryIndex, itemIndex) {
        root.querySelectorAll("[data-name-template]").forEach((element) => {
          let name = element.dataset.nameTemplate;
          name = name.replace(/__CAT__/g, categoryIndex);
          name = name.replace(/__ITEM__/g, itemIndex);
          element.name = name;
        });
      }

      function addItemRow(categoryElement) {
        const categoryIndex = categoryElement.dataset.categoryIndex;
        const itemsContainer = categoryElement.querySelector(".items");
        const itemIndex = itemsContainer.querySelectorAll(".item-row").length;
        const fragment = itemTemplate.content.cloneNode(true);
        applyNameTemplates(fragment, categoryIndex, itemIndex);
        itemsContainer.appendChild(fragment);
      }

      function addCategoryBlock() {
        const categoryIndex = categoryContainer.querySelectorAll(".category-block").length;
        const fragment = categoryTemplate.content.cloneNode(true);
        applyNameTemplates(fragment, categoryIndex, 0);
        const block = fragment.querySelector(".category-block");
        block.dataset.categoryIndex = categoryIndex;
        categoryContainer.appendChild(fragment);
        addItemRow(categoryContainer.lastElementChild);
      }

      document.addEventListener("click", (event) => {
        if (event.target.matches("[data-add-item]")) {
          const categoryElement = event.target.closest(".category-block");
          if (categoryElement) {
            addItemRow(categoryElement);
          }
        }

        if (event.target.matches("[data-remove-item]")) {
          const row = event.target.closest(".item-row");
          if (row) {
            row.remove();
          }
        }

        if (event.target.matches("[data-remove-category]")) {
          const block = event.target.closest(".category-block");
          if (block) {
            block.remove();
          }
        }

        if (event.target.matches("[data-add-category]")) {
          addCategoryBlock();
        }
      });

        if (categoryContainer.children.length === 0) {
          addCategoryBlock();
        }
      }
    </script>
  </body>
</html>
