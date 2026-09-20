<?php
/**
 * Data Bridge Consulting — Mentions légales
 *
 * ⚠️ Contenu à personnaliser avant mise en ligne : les informations
 * ci-dessous (SIRET, hébergeur, directeur de publication...) sont des
 * exemples et doivent être remplacées par vos données réelles. En France,
 * ces mentions sont une obligation légale (art. 6 III de la LCEN).
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Mentions légales — Data Bridge Consulting</title>
<meta name="robots" content="noindex">
<link rel="alternate" hreflang="fr" href="mentions-legales.php">
<link rel="alternate" hreflang="en-us" href="legal-notice.php">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root{
    --bg: #F5F6FA; --ink: #121A2B; --muted: #5B6478; --line: #DBDFE9;
    --accent: #3448E0; --white: #FFFFFF; --radius: 3px; --max: 760px;
  }
  *{ box-sizing: border-box; }
  body{
    margin:0; background: var(--bg); color: var(--ink);
    font-family: "IBM Plex Sans", -apple-system, Segoe UI, sans-serif;
    font-size: 16px; line-height: 1.6;
  }
  h1,h2{ font-family:"Fraunces", Georgia, serif; font-weight:500; line-height:1.2; letter-spacing:-0.01em; }
  a{ color: var(--accent); }
  .wrap{ max-width: var(--max); margin: 0 auto; padding: 0 28px; }

  header{
    border-bottom: 1px solid var(--line); padding: 22px 0;
  }
  .brand{
    font-family:"Fraunces", serif; font-size: 1.15rem; font-weight:600;
    display:flex; align-items:center; gap:8px; text-decoration:none; color:var(--ink);
  }
  .brand .dot{ width:8px; height:8px; border-radius:50%; background: var(--accent); display:inline-block; }

  main{ padding: 56px 0 80px; }
  h1{ font-size: clamp(1.8rem, 3vw, 2.3rem); margin-bottom: 10px; }
  .updated{ color: var(--muted); font-size: 0.9rem; margin-bottom: 44px; }

  section{ margin-bottom: 36px; }
  h2{ font-size: 1.15rem; margin-bottom: 10px; }
  p, li{ color: var(--ink); }
  p{ margin: 0 0 12px; }
  ul{ margin: 0 0 12px; padding-left: 20px; }
  .note{
    background:#EAEDFF; border:1px solid #C9D0FF; border-radius: var(--radius);
    padding: 14px 16px; font-size: 0.92rem; color: var(--ink); margin-bottom: 40px;
  }

  footer{
    border-top: 1px solid var(--line); padding: 28px 0; color: var(--muted); font-size: 0.88rem;
  }
  footer a{ color: var(--muted); }
</style>
</head>
<body>

<header>
  <div class="wrap">
    <a href="index.php" class="brand"><span class="dot"></span>Data Bridge Consulting</a>
  </div>
</header>

<main class="wrap">
  <h1>Mentions légales</h1>
  <p class="updated">Dernière mise à jour : <?= date('d/m/Y') ?></p>

  <div class="note">
    ⚠️ Cette page est un modèle. Remplacez les informations ci-dessous par les données réelles de votre entreprise avant la mise en ligne du site.
  </div>

  <section>
    <h2>Éditeur du site</h2>
    <p>
      Le présent site est édité par <strong>Data Bridge Consulting SAS</strong>, société par actions simplifiée
      au capital de 10 000 €, immatriculée au Registre du Commerce et des Sociétés de Paris
      sous le numéro SIRET 000 000 000 00000.
    </p>
    <ul>
      <li>Siège social : 12 rue de la Data, 75011 Paris, France</li>
      <li>Téléphone : +33 1 84 60 12 30</li>
      <li>Email : <a href="mailto:contact@data-bridge-consulting.com">contact@data-bridge-consulting.com</a></li>
      <li>Numéro de TVA intracommunautaire : FR00 000000000</li>
    </ul>
  </section>

  <section>
    <h2>Directeur de la publication</h2>
    <p>Le directeur de la publication est [Nom Prénom], en qualité de [fonction, ex. Président de Data Bridge Consulting SAS].</p>
  </section>

  <section>
    <h2>Hébergement</h2>
    <p>
      Ce site est hébergé par <strong>Hostinger International Ltd.</strong>,
      61 Lordou Vironos Street, 6023 Larnaca, Chypre.
      Site web : <a href="https://www.hostinger.fr" target="_blank" rel="noopener">www.hostinger.fr</a>.
    </p>
  </section>

  <section>
    <h2>Propriété intellectuelle</h2>
    <p>
      L'ensemble des contenus présents sur ce site (textes, graphismes, logos, icônes) sont,
      sauf mention contraire, la propriété exclusive de Data Bridge Consulting SAS ou de ses partenaires.
      Toute reproduction, distribution ou représentation, totale ou partielle, sans autorisation
      préalable écrite est interdite et constituerait une contrefaçon sanctionnée par les
      articles L.335-2 et suivants du Code de la propriété intellectuelle.
    </p>
  </section>

  <section>
    <h2>Données personnelles</h2>
    <p>
      Les informations recueillies via le formulaire de contact (nom, email, société, message)
      sont utilisées uniquement pour répondre à votre demande et ne sont ni cédées ni vendues
      à des tiers. Conformément au Règlement Général sur la Protection des Données (RGPD) et à
      la loi Informatique et Libertés, vous disposez d'un droit d'accès, de rectification, de
      suppression et de portabilité de vos données, ainsi que d'un droit d'opposition à leur
      traitement, que vous pouvez exercer en écrivant à
      <a href="mailto:contact@data-bridge-consulting.com">contact@data-bridge-consulting.com</a>.
    </p>
    <p>
      Les données du formulaire sont conservées pour une durée de [durée, ex. 3 ans] à compter
      du dernier contact, sauf obligation légale contraire.
    </p>
  </section>

  <section>
    <h2>Cookies</h2>
    <p>
      Ce site n'utilise pas de cookies de suivi ou de mesure d'audience. Si cela évolue
      (ajout d'un outil d'analytics, par exemple), cette section sera mise à jour et un bandeau
      de consentement sera ajouté conformément à la réglementation applicable.
    </p>
  </section>

  <section>
    <h2>Droit applicable</h2>
    <p>
      Le présent site et les présentes mentions légales sont soumis au droit français.
      En cas de litige, et à défaut de résolution amiable, les tribunaux français seront
      seuls compétents.
    </p>
  </section>
</main>

<footer class="wrap">
  <a href="index.php">&larr; Retour à l'accueil</a>
</footer>

</body>
</html>
