<?php
/**
 * Data Bridge Consulting — logique partagée entre les versions FR (index.php) et EN (en.php).
 *
 * Ce fichier centralise :
 *  - la configuration (destinataire, identifiants SMTP Hostinger)
 *  - l'envoi d'email via PHPMailer (inclus manuellement, sans Composer)
 *  - la validation et le traitement du formulaire de contact, en FR ou EN
 *  - deux petits helpers : e() pour l'échappement HTML, save_message_log()
 *    pour garder une trace locale de chaque message (filet de sécurité).
 */

require __DIR__ . '/../phpmailer/src/Exception.php';
require __DIR__ . '/../phpmailer/src/PHPMailer.php';
require __DIR__ . '/../phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

// ---- Configuration ---------------------------------------------------
// Adresse qui reçoit les messages du formulaire (identique pour les deux langues)
const CONTACT_DESTINATAIRE = "contact@data-bridge-consulting.com";

// Identifiants SMTP Hostinger (créés dans hPanel → Emails → Gérer → Créer un compte)
const SMTP_HOST     = "smtp.hostinger.com";
const SMTP_USER     = "contact@data-bridge-consulting.com";        // adresse email complète du compte Hostinger
const SMTP_PASSWORD = "MOT_DE_PASSE_DU_COMPTE_EMAIL"; // ⚠️ à remplacer avant mise en ligne
const SMTP_PORT     = 587;                            // 587 (STARTTLS) ou 465 (SSL, avec ENCRYPTION_SMTPS)

function e(string $valeur): string
{
    return htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8');
}

function save_message_log(array $donnees): void
{
    $ligne = sprintf(
        "[%s] (%s) %s <%s> (%s) : %s\n",
        date('Y-m-d H:i:s'),
        $donnees['lang'],
        $donnees['nom'],
        $donnees['email'],
        $donnees['societe'] !== '' ? $donnees['societe'] : '—',
        str_replace("\n", ' ', $donnees['message'])
    );
    @file_put_contents(__DIR__ . '/../messages.log', $ligne, FILE_APPEND | LOCK_EX);
}

/**
 * Traite le formulaire de contact pour la langue donnée ('fr' ou 'en').
 * Retourne ['succes' => bool, 'erreurs' => array, 'valeurs' => array].
 */
function process_contact_form(string $lang): array
{
    $textes = [
        'fr' => [
            'nom_requis'     => "Merci d'indiquer votre nom.",
            'email_requis'   => "Merci d'indiquer une adresse email valide.",
            'message_vide'   => "Le message ne peut pas être vide.",
            'message_court'  => "Votre message est un peu court, dites-nous en un peu plus.",
            'echec_envoi'    => "Votre message a été enregistré, mais l'envoi de l'email a échoué. Nous vous recontacterons dès que possible, ou écrivez-nous directement à ",
            'sujet_email'    => "Nouveau message depuis le site Data Bridge Consulting (FR)",
            'corps_intro'    => "Nouveau message reçu depuis le site Data Bridge Consulting (version FR)",
            'corps_nom'      => "Nom",
            'corps_email'    => "Email",
            'corps_societe'  => "Société",
            'corps_non_renseignee' => "(non renseignée)",
            'corps_message'  => "Message",
            'nom_expediteur' => "Site Data Bridge Consulting (FR)",
        ],
        'en' => [
            'nom_requis'     => "Please enter your name.",
            'email_requis'   => "Please enter a valid email address.",
            'message_vide'   => "The message can't be empty.",
            'message_court'  => "Your message is a bit short — tell us a little more.",
            'echec_envoi'    => "Your message was saved, but the email failed to send. We'll get back to you as soon as possible, or you can write to us directly at ",
            'sujet_email'    => "New message from the Data Bridge Consulting website (EN)",
            'corps_intro'    => "New message received from the Data Bridge Consulting website (EN version)",
            'corps_nom'      => "Name",
            'corps_email'    => "Email",
            'corps_societe'  => "Company",
            'corps_non_renseignee' => "(not provided)",
            'corps_message'  => "Message",
            'nom_expediteur' => "Data Bridge Consulting Website (EN)",
        ],
    ][$lang];

    $erreurs = [];
    $succes  = false;
    $valeurs = ['nom' => '', 'email' => '', 'societe' => '', 'message' => ''];

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return compact('succes', 'erreurs', 'valeurs');
    }

    // Honeypot anti-spam : champ invisible, un vrai humain ne le remplit jamais
    $honeypot = trim($_POST['site_web'] ?? '');

    $valeurs['nom']     = trim($_POST['nom'] ?? '');
    $valeurs['email']   = trim($_POST['email'] ?? '');
    $valeurs['societe'] = trim($_POST['societe'] ?? '');
    $valeurs['message'] = trim($_POST['message'] ?? '');

    if ($honeypot !== '') {
        // Honeypot rempli : on fait comme si tout allait bien, sans rien envoyer
        return [
            'succes'  => true,
            'erreurs' => [],
            'valeurs' => ['nom' => '', 'email' => '', 'societe' => '', 'message' => ''],
        ];
    }

    if ($valeurs['nom'] === '') {
        $erreurs['nom'] = $textes['nom_requis'];
    }
    if ($valeurs['email'] === '' || !filter_var($valeurs['email'], FILTER_VALIDATE_EMAIL)) {
        $erreurs['email'] = $textes['email_requis'];
    }
    if ($valeurs['message'] === '') {
        $erreurs['message'] = $textes['message_vide'];
    } elseif (strlen($valeurs['message']) < 10) {
        $erreurs['message'] = $textes['message_court'];
    }

    if (!empty($erreurs)) {
        return compact('succes', 'erreurs', 'valeurs');
    }

    $corps = $textes['corps_intro'] . "\n\n"
           . $textes['corps_nom'] . "     : " . $valeurs['nom'] . "\n"
           . $textes['corps_email'] . "   : " . $valeurs['email'] . "\n"
           . $textes['corps_societe'] . " : " . ($valeurs['societe'] !== '' ? $valeurs['societe'] : $textes['corps_non_renseignee']) . "\n\n"
           . $textes['corps_message'] . " :\n" . $valeurs['message'] . "\n";

    $envoi_ok = send_contact_email($textes['sujet_email'], $corps, $valeurs, $textes['nom_expediteur']);

    save_message_log($valeurs + ['lang' => strtoupper($lang)]);

    $succes = $envoi_ok;
    if (!$envoi_ok) {
        $erreurs['envoi'] = $textes['echec_envoi'] . e(CONTACT_DESTINATAIRE) . ".";
    } else {
        $valeurs = ['nom' => '', 'email' => '', 'societe' => '', 'message' => ''];
    }

    return compact('succes', 'erreurs', 'valeurs');
}

function send_contact_email(string $sujet, string $corps, array $valeurs, string $nom_expediteur): bool
{
    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // ENCRYPTION_SMTPS si SMTP_PORT = 465
        $mail->Port       = SMTP_PORT;
        $mail->CharSet    = 'UTF-8';

        // L'expéditeur doit être une adresse du même domaine que le compte SMTP
        // (sinon SPF/DKIM échouent et le mail part en spam, voire est rejeté).
        $mail->setFrom(SMTP_USER, $nom_expediteur);
        $mail->addAddress(CONTACT_DESTINATAIRE);
        $mail->addReplyTo($valeurs['email'], $valeurs['nom']);

        $mail->Subject = $sujet;
        $mail->Body    = $corps;

        $mail->send();
        return true;
    } catch (PHPMailerException $e) {
        // On log l'erreur côté serveur sans jamais l'exposer au visiteur
        error_log('Échec envoi PHPMailer : ' . (isset($mail) ? $mail->ErrorInfo : $e->getMessage()));
        return false;
    }
}
