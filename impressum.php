<?php $year = date('Y'); ?>
<!DOCTYPE html>
<html lang="de">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Impressum · Lyv's Haarstudio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Carattere&family=Poppins:wght@400;500;600&display=swap"
      rel="stylesheet"
    />
    <style>
      :root {
        --beige: #f5e9dd;
        --brown: #5b3a29;
        --accent: #8b5e3c;
        --body-font: "Poppins", "Helvetica Neue", Arial, sans-serif;
        --heading-font: "Carattere", "Playfair Display", serif;
        --max-width: 900px;
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
        padding: 2rem 1rem 4rem;
      }

      header {
        max-width: var(--max-width);
        margin: 0 auto 2rem;
        text-align: center;
      }

      h1 {
        font-family: var(--heading-font);
        font-size: clamp(2.4rem, 5vw, 3.1rem);
        margin-bottom: 0.5rem;
      }

      main {
        max-width: var(--max-width);
        margin: 0 auto;
        background: #fff;
        border-radius: 32px;
        padding: 2.5rem;
        box-shadow: 0 20px 40px rgba(91, 58, 41, 0.1);
      }

      section + section {
        margin-top: 2rem;
      }

      h2 {
        font-size: 1rem;
        letter-spacing: 0.15em;
        text-transform: uppercase;
        color: var(--accent);
        margin-bottom: 0.7rem;
      }

      a {
        color: var(--accent);
      }

      footer {
        text-align: center;
        margin-top: 2rem;
        font-size: 0.9rem;
      }

      .back-link {
        display: inline-block;
        margin-top: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        font-size: 0.85rem;
      }

      @media (max-width: 600px) {
        main {
          padding: 1.8rem;
        }
      }
    </style>
  </head>
  <body>
    <header>
      <h1>Impressum</h1>
      <p>Lyv's Haarstudio</p>
    </header>

    <main>
      <section>
        <h2>Angaben gemäß § 5 TMG</h2>
        <p>Lyv's Haarstudio<br />Schafmarkt 2</p>
      </section>

      <section>
        <h2>Vertreten durch</h2>
        <p>Lyv Petersen (Inhaberin)</p>
      </section>

      <section>
        <h2>Kontakt</h2>
        <p>
          Telefon: <a href="tel:+494662891898">04662 891898</a><br />
          E-Mail: <a href="mailto:hallo@lyvs-haarstudio.de">hallo@lyvs-haarstudio.de</a>
        </p>
      </section>

      <section>
        <h2>Umsatzsteuer-ID</h2>
        <p>Wird nach § 19 UStG (Kleinunternehmerregelung) nicht ausgewiesen.</p>
      </section>

      <section>
        <h2>Berufsbezeichnung</h2>
        <p>Friseurmeisterin (verliehen in Deutschland)</p>
      </section>

      <section>
        <h2>Berufsrechtliche Regelungen</h2>
        <p>
          Handwerksordnung (HwO) in der jeweils gültigen Fassung.<br />
          Informationen abrufbar unter <a href="https://www.gesetze-im-internet.de" target="_blank" rel="noreferrer"
            >www.gesetze-im-internet.de</a
          >.
        </p>
      </section>

      <section>
        <h2>Online-Streitbeilegung</h2>
        <p>
          Die Europäische Kommission stellt eine Plattform zur Online-Streitbeilegung bereit:
          <a href="https://ec.europa.eu/consumers/odr" target="_blank" rel="noreferrer">https://ec.europa.eu/consumers/odr</a>.
          Wir sind nicht verpflichtet und nicht bereit, an einem Streitbeilegungsverfahren vor einer
          Verbraucherschlichtungsstelle teilzunehmen.
        </p>
      </section>

      <section>
        <h2>Haftung für Inhalte</h2>
        <p>
          Als Diensteanbieter sind wir gemäß § 7 Abs.1 TMG für eigene Inhalte auf diesen Seiten nach den
          allgemeinen Gesetzen verantwortlich. Nach §§ 8 bis 10 TMG sind wir als Diensteanbieter jedoch nicht
          verpflichtet, übermittelte oder gespeicherte fremde Informationen zu überwachen oder nach Umständen zu
          forschen, die auf eine rechtswidrige Tätigkeit hinweisen.
        </p>
      </section>

      <section>
        <h2>Haftung für Links</h2>
        <p>
          Unser Angebot enthält Links zu externen Websites Dritter, auf deren Inhalte wir keinen Einfluss haben.
          Deshalb können wir für diese fremden Inhalte auch keine Gewähr übernehmen. Für die Inhalte der verlinkten
          Seiten ist stets der jeweilige Anbieter oder Betreiber der Seiten verantwortlich.
        </p>
      </section>

      <section>
        <h2>Urheberrecht</h2>
        <p>
          Die durch die Seitenbetreiber erstellten Inhalte und Werke auf diesen Seiten unterliegen dem deutschen
          Urheberrecht. Die Vervielfältigung, Bearbeitung, Verbreitung und jede Art der Verwertung außerhalb der
          Grenzen des Urheberrechtes bedürfen der schriftlichen Zustimmung des jeweiligen Autors bzw. Erstellers.
        </p>
      </section>

      <a class="back-link" href="index.php">Zurück zur Startseite</a>
    </main>

    <footer>&copy; <?php echo $year; ?> Lyv's Haarstudio</footer>
  </body>
</html>
