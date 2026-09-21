<?php
/**
 * Data Bridge Consulting — Mentions légales
 *
 * ⚠️ Contenu à personnaliser avant mise en ligne : les informations
 * ci-dessous (hébergeur, directeur de publication...) sont des exemples
 * et doivent être remplacées par vos données réelles. L'éditeur est ici
 * Data Bridge L.L.C., une société immatriculée dans l'État du Nouveau-Mexique
 * (États-Unis) — les sections « Propriété intellectuelle » et « Droit
 * applicable » ont été adaptées en conséquence, mais méritent une relecture
 * par un juriste avant publication, notamment si le site cible aussi des
 * visiteurs européens.
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


  <section>
    <h2>Éditeur du site</h2>
    <p>
      Le présent site est édité par <strong>Data Bridge L.L.C.</strong>, société à responsabilité
      limitée (Limited Liability Company) immatriculée dans l'État du Nouveau-Mexique (États-Unis).
    </p>
    <ul>
      <li>Adresse : 1209 Mountain Road PL NE, STE N, Albuquerque, NM 87110, États-Unis</li>
      <li>Email : <a href="mailto:contact@data-bridge-consulting.com">contact@data-bridge-consulting.com</a></li>
    </ul>
  </section>

  <section>
    <h2>Directeur de la publication</h2>
    <p>Le directeur de la publication est Mr Adam P, en qualité de Manager de Data Bridge.</p>
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
      sauf mention contraire, la propriété exclusive de Data Bridge L.L.C. ou de ses partenaires.
      Toute reproduction, distribution ou représentation, totale ou partielle, sans autorisation
      préalable écrite est interdite et peut constituer une contrefaçon au regard du droit
      applicable en matière de propriété intellectuelle.
    </p>
  </section>

  <section>
    <h2>Données personnelles</h2>
    <p>
      Les informations recueillies via le formulaire de contact (nom, email, société, message)
      sont utilisées uniquement pour répondre à votre demande et ne sont ni cédées ni vendues
      à des tiers. Selon votre lieu de résidence, vous pouvez disposer de droits sur vos données
      (accès, rectification, suppression, opposition), par exemple au titre du RGPD si vous êtes
      situé dans l'Union européenne. Vous pouvez exercer ces droits en écrivant à
      <a href="mailto:contact@data-bridge-consulting.com">contact@data-bridge-consulting.com</a>.
      [À adapter avec votre conseil juridique selon les réglementations applicables à vos visiteurs —
      RGPD pour l'UE, lois étatiques américaines sur la vie privée, etc.]
    </p>
    <p>
      Les données du formulaire sont conservées pour une durée de 3 ans à compter
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
      Le présent site et les présentes mentions légales sont soumis au droit de l'État du
      Nouveau-Mexique (États-Unis), sans préjudice des règles de protection dont pourraient
      bénéficier les consommateurs de leur pays de résidence. En cas de litige, et à défaut de
      résolution amiable, les tribunaux compétents du Nouveau-Mexique seront saisis, sauf
      disposition légale impérative contraire.
    </p>
  </section>
</main>

<footer class="wrap">
  <a href="index.php">&larr; Retour à l'accueil</a>
</footer>

</body>
</html>
