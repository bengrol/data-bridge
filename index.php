<?php
/**
 * Data Bridge Consulting — Site vitrine monopage
 * Cabinet de conseil en intelligence artificielle & data analytics
 *
 * Ce fichier unique contient :
 *  - le traitement du formulaire de contact (en haut, avant tout HTML)
 *  - la page HTML complète (hero, expertises, méthode, chiffres clés, stack, contact)
 *
 * ENVOI D'EMAIL VIA PHPMAILER (SMTP HOSTINGER) :
 *  - PHPMailer est inclus "en dur" dans /phpmailer/src (pas de Composer requis) :
 *      phpmailer/src/Exception.php
 *      phpmailer/src/PHPMailer.php
 *      phpmailer/src/SMTP.php
 *    Ces 3 fichiers doivent être uploadés tels quels à côté de index.php.
 *  - Renseignez vos identifiants SMTP Hostinger ci-dessous (section Configuration).
 *  - En cas d'échec d'envoi (identifiants invalides, port bloqué...), le message
 *    est quand même sauvegardé dans messages.log pour ne rien perdre.
 */

require __DIR__ . '/phpmailer/src/Exception.php';
require __DIR__ . '/phpmailer/src/PHPMailer.php';
require __DIR__ . '/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

// ---- Configuration ---------------------------------------------------
$destinataire = "b@Data-bridge-consulting.com";        // adresse qui reçoit les messages du formulaire
$sujet_email  = "Nouveau message depuis le site Data Bridge Consulting";

// Identifiants SMTP Hostinger (créés dans hPanel → Emails → Gérer → Créer un compte)
$smtp_host     = "smtp.hostinger.com";
$smtp_user     = "contact@Data-bridge-consulting.com";       // adresse email complète du compte Hostinger
$smtp_password = "MOT_DE_PASSE_DU_COMPTE_EMAIL"; // ⚠️ à remplacer — voir note de sécurité plus bas
$smtp_port     = 587;                          // 587 (STARTTLS) ou 465 (SSL, avec ENCRYPTION_SMTPS)

// ---- Traitement du formulaire -----------------------------------------
$erreurs = [];
$succes  = false;
$valeurs = ['nom' => '', 'email' => '', 'societe' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Honeypot anti-spam : champ invisible, un vrai humain ne le remplit jamais
    $honeypot = trim($_POST['site_web'] ?? '');

    $valeurs['nom']     = trim($_POST['nom'] ?? '');
    $valeurs['email']   = trim($_POST['email'] ?? '');
    $valeurs['societe'] = trim($_POST['societe'] ?? '');
    $valeurs['message'] = trim($_POST['message'] ?? '');

    if ($honeypot === '') {
        // Validation
        if ($valeurs['nom'] === '') {
            $erreurs['nom'] = "Merci d'indiquer votre nom.";
        }
        if ($valeurs['email'] === '' || !filter_var($valeurs['email'], FILTER_VALIDATE_EMAIL)) {
            $erreurs['email'] = "Merci d'indiquer une adresse email valide.";
        }
        if ($valeurs['message'] === '') {
            $erreurs['message'] = "Le message ne peut pas être vide.";
        } elseif (strlen($valeurs['message']) < 10) {
            $erreurs['message'] = "Votre message est un peu court, dites-nous en un peu plus.";
        }

        if (empty($erreurs)) {
            $corps = "Nouveau message reçu depuis le site Data Bridge Consulting\n\n"
                   . "Nom      : " . $valeurs['nom'] . "\n"
                   . "Email    : " . $valeurs['email'] . "\n"
                   . "Société  : " . ($valeurs['societe'] !== '' ? $valeurs['societe'] : '(non renseignée)') . "\n\n"
                   . "Message :\n" . $valeurs['message'] . "\n";

            $envoi_ok = false;

            try {
                $mail = new PHPMailer(true);

                $mail->isSMTP();
                $mail->Host       = $smtp_host;
                $mail->SMTPAuth   = true;
                $mail->Username   = $smtp_user;
                $mail->Password   = $smtp_password;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // ENCRYPTION_SMTPS si $smtp_port = 465
                $mail->Port       = $smtp_port;
                $mail->CharSet    = 'UTF-8';

                // L'expéditeur doit être une adresse du même domaine que le compte SMTP
                // (sinon SPF/DKIM échouent et le mail part en spam, voire est rejeté).
                $mail->setFrom($smtp_user, 'Site Data Bridge Consulting');
                $mail->addAddress($destinataire);
                $mail->addReplyTo($valeurs['email'], $valeurs['nom']);

                $mail->Subject = $sujet_email;
                $mail->Body    = $corps;

                $mail->send();
                $envoi_ok = true;
            } catch (PHPMailerException $e) {
                // On log l'erreur côté serveur sans jamais l'exposer au visiteur
                error_log('Échec envoi PHPMailer : ' . $mail->ErrorInfo);
            }

            // Sauvegarde systématique : utile en développement, et filet de sécurité
            // en production si jamais l'envoi SMTP échoue (identifiants, port bloqué...).
            save_message_log($valeurs);

            $succes  = $envoi_ok;
            if (!$envoi_ok) {
                $erreurs['envoi'] = "Votre message a été enregistré, mais l'envoi de l'email a échoué. Nous vous recontacterons dès que possible, ou écrivez-nous directement à " . e($destinataire) . ".";
            } else {
                $valeurs = ['nom' => '', 'email' => '', 'societe' => '', 'message' => ''];
            }
        }
    } else {
        // Honeypot rempli : on fait comme si tout allait bien, sans rien envoyer
        $succes  = true;
        $valeurs = ['nom' => '', 'email' => '', 'societe' => '', 'message' => ''];
    }
}

function save_message_log(array $donnees): void
{
    $ligne = sprintf(
        "[%s] %s <%s> (%s) : %s\n",
        date('Y-m-d H:i:s'),
        $donnees['nom'],
        $donnees['email'],
        $donnees['societe'] !== '' ? $donnees['societe'] : '—',
        str_replace("\n", ' ', $donnees['message'])
    );
    @file_put_contents(__DIR__ . '/messages.log', $ligne, FILE_APPEND | LOCK_EX);
}

function e(string $valeur): string
{
    return htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Data Bridge Consulting — Conseil en intelligence artificielle & data analytics</title>
<meta name="description" content="Data Bridge Consulting accompagne les entreprises dans leurs projets d'intelligence artificielle et de data analytics, du cadrage à la mise en production.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,400;9..144,500;9..144,600&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
  :root{
    --bg: #F5F6FA;
    --panel-dark: #121A2B;
    --ink: #121A2B;
    --muted: #5B6478;
    --line: #DBDFE9;
    --accent: #3448E0;
    --accent-ink: #EAEDFF;
    --gold: #D89A2B;
    --white: #FFFFFF;
    --radius: 3px;
    --max: 1080px;
  }

  *{ box-sizing: border-box; }
  html{ scroll-behavior: smooth; }
  @media (prefers-reduced-motion: reduce){ html{ scroll-behavior: auto; } }

  body{
    margin:0;
    background: var(--bg);
    color: var(--ink);
    font-family: "IBM Plex Sans", -apple-system, Segoe UI, sans-serif;
    font-size: 16px;
    line-height: 1.55;
  }

  h1,h2,h3{
    font-family: "Fraunces", Georgia, serif;
    font-weight: 500;
    line-height: 1.12;
    margin: 0;
    letter-spacing: -0.01em;
  }

  .mono{ font-family: "IBM Plex Mono", monospace; }

  a{ color: var(--accent); }

  .wrap{
    max-width: var(--max);
    margin: 0 auto;
    padding: 0 28px;
  }

  /* ---------- Header ---------- */
  header{
    position: sticky; top:0; z-index: 20;
    background: rgba(245,246,250,0.92);
    backdrop-filter: blur(6px);
    border-bottom: 1px solid var(--line);
  }
  .nav{
    display:flex; align-items:center; justify-content:space-between;
    padding: 18px 0;
  }
  .brand{
    font-family:"Fraunces", serif;
    font-size: 1.25rem;
    font-weight: 600;
    display:flex; align-items:center; gap:8px;
  }
  .brand .dot{
    width:8px; height:8px; border-radius:50%;
    background: var(--accent);
    display:inline-block;
  }
  .nav ul{
    list-style:none; display:flex; gap: 28px; margin:0; padding:0;
  }
  .nav a{
    color: var(--ink); text-decoration:none; font-size: 0.95rem;
  }
  .nav a:hover{ color: var(--accent); }
  .nav-cta{
    border:1px solid var(--ink); border-radius: var(--radius);
    padding: 8px 16px; font-size: 0.9rem;
  }
  .nav-cta:hover{ background: var(--ink); color: var(--white); }
  .burger{ display:none; }

  @media (max-width: 760px){
    .nav ul{ display:none; }
    .burger{ display:block; background:none; border:1px solid var(--line); border-radius: var(--radius); padding: 8px 12px; }
  }

  /* ---------- Hero ---------- */
  .hero{
    padding: 88px 0 72px;
    display:grid;
    grid-template-columns: 1.1fr 0.9fr;
    gap: 56px;
    align-items:center;
  }
  .hero p.eyebrow{
    color: var(--muted); font-size: 0.95rem; margin: 0 0 18px;
  }
  .hero h1{
    font-size: clamp(2.1rem, 4vw, 3.2rem);
  }
  .hero .lede{
    margin: 22px 0 32px;
    max-width: 46ch;
    color: var(--muted);
    font-size: 1.08rem;
  }
  .cta-row{ display:flex; gap: 16px; flex-wrap: wrap; }
  .btn{
    display:inline-block;
    padding: 13px 24px;
    border-radius: var(--radius);
    font-size: 0.97rem;
    text-decoration:none;
    border: 1px solid transparent;
  }
  .btn-primary{ background: var(--accent); color: var(--white); }
  .btn-primary:hover{ background:#2635b8; }
  .btn-ghost{ border-color: var(--ink); color: var(--ink); }
  .btn-ghost:hover{ background: var(--ink); color: var(--white); }

  .hero-graphic{
    background: var(--panel-dark);
    border-radius: 6px;
    padding: 28px 26px 22px;
  }
  .hero-graphic .cap{
    color: #9AA4C4; font-size: 0.82rem; margin-bottom: 18px;
  }
  .hero-graphic svg{ width:100%; height:auto; display:block; }

  /* ---------- Section shell ---------- */
  section{ padding: 76px 0; border-top: 1px solid var(--line); }
  .section-head{
    display:flex; justify-content:space-between; align-items:flex-end;
    gap: 24px; margin-bottom: 44px; flex-wrap: wrap;
  }
  .section-head h2{ font-size: clamp(1.6rem, 2.6vw, 2.1rem); max-width: 20ch; }
  .section-head .desc{ color: var(--muted); max-width: 38ch; }

  /* ---------- Expertises ---------- */
  .expertise-list{
    display:grid; grid-template-columns: repeat(2, 1fr); gap: 1px;
    background: var(--line); border: 1px solid var(--line);
  }
  .expertise-item{
    background: var(--bg); padding: 30px 28px;
  }
  .expertise-item .num{
    font-family:"IBM Plex Mono", monospace;
    color: var(--accent);
    font-size: 0.85rem;
    display:block; margin-bottom: 14px;
  }
  .expertise-item h3{ font-size: 1.25rem; margin-bottom: 10px; }
  .expertise-item p{ color: var(--muted); margin:0; font-size: 0.97rem; }

  @media (max-width: 700px){
    .expertise-list{ grid-template-columns: 1fr; }
    .hero{ grid-template-columns: 1fr; }
  }

  /* ---------- Méthode ---------- */
  .steps{ list-style:none; margin:0; padding:0; }
  .steps li{
    display:grid; grid-template-columns: 64px 1fr; gap: 20px;
    padding: 24px 0; border-bottom: 1px solid var(--line);
  }
  .steps li:first-child{ border-top: 1px solid var(--line); }
  .steps .step-num{ font-family:"IBM Plex Mono", monospace; color: var(--muted); font-size: 0.95rem; }
  .steps h3{ font-size: 1.1rem; margin-bottom: 6px; }
  .steps p{ margin:0; color: var(--muted); max-width: 60ch; }

  /* ---------- Chiffres clés ---------- */
  .stats-band{ background: var(--panel-dark); color: var(--accent-ink); border-top:none; }
  .stats-grid{
    display:grid; grid-template-columns: repeat(4, 1fr); gap: 32px;
  }
  .stat .n{
    font-family:"IBM Plex Mono", monospace;
    font-size: clamp(1.8rem, 3vw, 2.4rem);
    color: var(--white);
  }
  .stat .l{ color: #9AA4C4; font-size: 0.9rem; margin-top: 6px; }
  @media (max-width: 700px){ .stats-grid{ grid-template-columns: repeat(2,1fr); row-gap: 30px; } }

  /* ---------- Stack ---------- */
  .stack-tags{ display:flex; flex-wrap:wrap; gap: 10px; }
  .stack-tags span{
    font-family:"IBM Plex Mono", monospace;
    font-size: 0.85rem;
    border: 1px solid var(--line);
    border-radius: var(--radius);
    padding: 7px 12px;
    color: var(--ink);
  }

  /* ---------- Contact ---------- */
  .contact-grid{
    display:grid; grid-template-columns: 0.85fr 1.15fr; gap: 56px;
  }
  .contact-info h3{ font-size: 1.3rem; margin-bottom: 14px; }
  .contact-info p{ color: var(--muted); max-width: 40ch; }
  .contact-info .addr{ margin-top: 28px; font-size: 0.95rem; }
  .contact-info .addr div{ margin-bottom: 6px; }

  form .field{ margin-bottom: 18px; }
  form label{ display:block; font-size: 0.88rem; margin-bottom: 6px; color: var(--ink); }
  form input, form textarea{
    width:100%; padding: 12px 14px; border: 1px solid var(--line);
    border-radius: var(--radius); font-family: inherit; font-size: 0.97rem;
    background: var(--white); color: var(--ink);
  }
  form input:focus, form textarea:focus{
    outline: 2px solid var(--accent); outline-offset: 1px; border-color: var(--accent);
  }
  form textarea{ min-height: 140px; resize: vertical; }
  .field-error{ color:#B3261E; font-size: 0.85rem; margin-top: 5px; }
  .field.has-error input, .field.has-error textarea{ border-color:#B3261E; }
  .honeypot{ position:absolute; left:-9999px; top:-9999px; }

  .alert{
    padding: 14px 16px; border-radius: var(--radius); margin-bottom: 24px; font-size: 0.95rem;
  }
  .alert-success{ background:#E7F5EC; color:#1E6B3B; border:1px solid #BFE3CC; }
  .alert-error{ background:#FBEAE9; color:#8C231C; border:1px solid #F3C6C3; }

  @media (max-width: 760px){ .contact-grid{ grid-template-columns: 1fr; } }

  footer{
    border-top: 1px solid var(--line);
    padding: 32px 0;
    display:flex; justify-content:space-between; align-items:center;
    color: var(--muted); font-size: 0.88rem; flex-wrap:wrap; gap: 10px;
  }

  :focus-visible{ outline: 2px solid var(--accent); outline-offset: 2px; }
</style>
</head>
<body>

<header>
  <div class="wrap nav">
    <div class="brand"><span class="dot"></span>Data Bridge Consulting</div>
    <nav>
      <ul>
        <li><a href="#expertises">Expertises</a></li>
        <li><a href="#methode">Méthode</a></li>
        <li><a href="#stack">Stack</a></li>
        <li><a href="#contact">Contact</a></li>
      </ul>
    </nav>
    <a href="#contact" class="nav-cta">Discuter de mon projet</a>
  </div>
</header>

<main>

  <!-- ---------- Hero ---------- -->
  <section class="hero wrap" style="border-top:none;">
    <div>
      <p class="eyebrow">Conseil en intelligence artificielle & data analytics</p>
      <h1>Chaque donnée a une direction.<br>On vous aide à la trouver.</h1>
      <p class="lede">Data Bridge Consulting accompagne les entreprises dans leurs projets d'IA et de data analytics,
        du cadrage métier jusqu'à la mise en production des modèles.</p>
      <div class="cta-row">
        <a href="#contact" class="btn btn-primary">Discuter de mon projet</a>
        <a href="#expertises" class="btn btn-ghost">Voir nos expertises</a>
      </div>
    </div>

    <div class="hero-graphic">
      <div class="cap mono">précision du modèle — 6 derniers mois</div>
      <svg viewBox="0 0 360 180" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Graphique montrant la précision d'un modèle progressant de 71% à 94% sur six mois">
        <line x1="0" y1="150" x2="360" y2="150" stroke="#2A3452" stroke-width="1"/>
        <line x1="0" y1="100" x2="360" y2="100" stroke="#2A3452" stroke-width="1"/>
        <line x1="0" y1="50"  x2="360" y2="50"  stroke="#2A3452" stroke-width="1"/>
        <polyline points="0,132 60,118 120,104 180,86 240,66 300,44 360,30"
                  fill="none" stroke="#5C77FF" stroke-width="2.5"/>
        <circle cx="0" cy="132" r="3.5" fill="#5C77FF"/>
        <circle cx="60" cy="118" r="3.5" fill="#5C77FF"/>
        <circle cx="120" cy="104" r="3.5" fill="#5C77FF"/>
        <circle cx="180" cy="86" r="3.5" fill="#5C77FF"/>
        <circle cx="240" cy="66" r="3.5" fill="#5C77FF"/>
        <circle cx="300" cy="44" r="3.5" fill="#5C77FF"/>
        <circle cx="360" cy="30" r="4" fill="#D89A2B"/>
        <text x="0" y="168" fill="#9AA4C4" font-size="11" font-family="IBM Plex Mono">71%</text>
        <text x="336" y="24" fill="#D89A2B" font-size="11" font-family="IBM Plex Mono">94%</text>
      </svg>
    </div>
  </section>

  <!-- ---------- Expertises ---------- -->
  <section id="expertises" class="wrap">
    <div class="section-head">
      <h2>Quatre expertises, un seul objectif : des décisions mieux informées.</h2>
      <p class="desc">Nous intervenons seuls ou en renfort de vos équipes, sur tout ou partie de la chaîne de valeur de la donnée.</p>
    </div>
    <div class="expertise-list">
      <div class="expertise-item">
        <span class="num mono">01</span>
        <h3>Data engineering</h3>
        <p>Architecture des données, pipelines, qualité et gouvernance. On pose des fondations solides avant de parler modèles.</p>
      </div>
      <div class="expertise-item">
        <span class="num mono">02</span>
        <h3>Machine learning & IA générative</h3>
        <p>Modèles prédictifs, cas d'usage LLM, automatisation de tâches métier — de l'expérimentation au cas d'usage réel.</p>
      </div>
      <div class="expertise-item">
        <span class="num mono">03</span>
        <h3>MLOps</h3>
        <p>Mise en production, monitoring, réentraînement. Un modèle qui reste dans un notebook n'a jamais créé de valeur.</p>
      </div>
      <div class="expertise-item">
        <span class="num mono">04</span>
        <h3>Data visualization & BI</h3>
        <p>Tableaux de bord et restitutions pensés pour vos équipes métier, pas pour impressionner un comité de pilotage.</p>
      </div>
    </div>
  </section>

  <!-- ---------- Méthode ---------- -->
  <section id="methode" class="wrap">
    <div class="section-head">
      <h2>Une méthode en quatre étapes</h2>
      <p class="desc">Chaque mission suit le même fil, adapté à votre contexte et à la maturité data de vos équipes.</p>
    </div>
    <ol class="steps">
      <li>
        <span class="step-num mono">01</span>
        <div><h3>Cadrage</h3><p>On comprend vos enjeux métier, l'état réel de vos données et ce qu'une IA peut — ou ne peut pas — résoudre.</p></div>
      </li>
      <li>
        <span class="step-num mono">02</span>
        <div><h3>Architecture & data</h3><p>On structure, nettoie et fiabilise les données nécessaires. C'est souvent l'étape la plus longue, et la plus décisive.</p></div>
      </li>
      <li>
        <span class="step-num mono">03</span>
        <div><h3>Modélisation</h3><p>On conçoit et entraîne les modèles adaptés au problème, pas à la mode du moment.</p></div>
      </li>
      <li>
        <span class="step-num mono">04</span>
        <div><h3>Déploiement</h3><p>On industrialise, on monitore, et on transmet à vos équipes ce qu'il faut pour faire vivre la solution.</p></div>
      </li>
    </ol>
  </section>

  <!-- ---------- Chiffres clés ---------- -->
  <section class="stats-band">
    <div class="wrap stats-grid">
      <div class="stat"><div class="n mono">40+</div><div class="l">projets livrés</div></div>
      <div class="stat"><div class="n mono">12</div><div class="l">secteurs d'activité accompagnés</div></div>
      <div class="stat"><div class="n mono">12 ans</div><div class="l">d'expérience moyenne par consultant</div></div>
      <div class="stat"><div class="n mono">98%</div><div class="l">des projets livrés dans les délais</div></div>
    </div>
  </section>

  <!-- ---------- Stack ---------- -->
  <section id="stack" class="wrap">
    <div class="section-head">
      <h2>Une stack technique éprouvée</h2>
      <p class="desc">Nous choisissons les outils en fonction du besoin, pas l'inverse.</p>
    </div>
    <div class="stack-tags">
      <span>Python</span><span>PyTorch</span><span>TensorFlow</span><span>scikit-learn</span>
      <span>Spark</span><span>Airflow</span><span>dbt</span><span>Snowflake</span>
      <span>LangChain</span><span>Docker</span><span>Kubernetes</span><span>AWS</span><span>GCP</span>
    </div>
  </section>

  <!-- ---------- Contact ---------- -->
  <section id="contact" class="wrap">
    <div class="contact-grid">
      <div class="contact-info">
        <h3>Parlons de votre projet</h3>
        <p>Un premier échange de 30 minutes suffit généralement pour savoir si on peut vous aider, et comment.</p>
      </div>

      <div>
        <?php if ($succes): ?>
          <div class="alert alert-success" role="status">
            Votre message a bien été envoyé. Nous revenons vers vous sous 48 heures.
          </div>
        <?php elseif (isset($erreurs['envoi'])): ?>
          <div class="alert alert-error" role="alert">
            <?= $erreurs['envoi'] /* déjà échappé via e() lors de sa création */ ?>
          </div>
        <?php elseif (!empty($erreurs)): ?>
          <div class="alert alert-error" role="alert">
            Merci de corriger les champs signalés ci-dessous.
          </div>
        <?php endif; ?>

        <form action="#contact" method="post" novalidate>
          <div class="field <?= isset($erreurs['nom']) ? 'has-error' : '' ?>">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" value="<?= e($valeurs['nom']) ?>" autocomplete="name">
            <?php if (isset($erreurs['nom'])): ?><div class="field-error"><?= e($erreurs['nom']) ?></div><?php endif; ?>
          </div>

          <div class="field <?= isset($erreurs['email']) ? 'has-error' : '' ?>">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= e($valeurs['email']) ?>" autocomplete="email">
            <?php if (isset($erreurs['email'])): ?><div class="field-error"><?= e($erreurs['email']) ?></div><?php endif; ?>
          </div>

          <div class="field">
            <label for="societe">Société <span style="color:var(--muted);">(facultatif)</span></label>
            <input type="text" id="societe" name="societe" value="<?= e($valeurs['societe']) ?>" autocomplete="organization">
          </div>

          <div class="field <?= isset($erreurs['message']) ? 'has-error' : '' ?>">
            <label for="message">Message</label>
            <textarea id="message" name="message"><?= e($valeurs['message']) ?></textarea>
            <?php if (isset($erreurs['message'])): ?><div class="field-error"><?= e($erreurs['message']) ?></div><?php endif; ?>
          </div>

          <!-- Honeypot anti-spam : champ caché, laissé vide par un humain -->
          <div class="honeypot" aria-hidden="true">
            <label for="site_web">Ne pas remplir ce champ</label>
            <input type="text" id="site_web" name="site_web" tabindex="-1" autocomplete="off">
          </div>

          <button type="submit" class="btn btn-primary">Envoyer le message</button>
        </form>
      </div>
    </div>
  </section>

</main>

<footer class="wrap">
  <div>© <?= date('Y') ?> Data Bridge Consulting — Conseil en IA & data analytics</div>
</footer>

</body>
</html>
