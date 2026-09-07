<?php
/**
 * Mail settings for Rest Easy enquiry forms.
 *
 * Hostinger blocks outbound Gmail SMTP (connect failed on 465 + 587).
 * We use Microsoft 365 SMTP (your real business mail).
 * If SMTP is also blocked, we fall back to PHP mail() via the host.
 *
 * IMPORTANT: set the Microsoft mailbox password below (or an App Password if MFA is on).
 */

function resteasy_smtp_credentials()
{
    return array(
        // Microsoft 365 / Outlook
        'host' => 'smtp.office365.com',
        'username' => 'info@resteasyservices.com.au',
        'password' => 'Pun7781!',
        'from_email' => 'info@resteasyservices.com.au',
        'from_name' => 'Rest Easy Services',
    );
}

/**
 * Apply Microsoft 365 SMTP auth settings.
 */
function resteasy_apply_smtp(PHPMailer $mail)
{
    $c = resteasy_smtp_credentials();
    $mail->isSMTP();
    $mail->Host = $c['host'];
    $mail->SMTPAuth = true;
    $mail->Username = $c['username'];
    $mail->Password = $c['password'];
    $mail->CharSet = 'UTF-8';
    $mail->Timeout = 25;
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true,
        ),
    );
}

/**
 * Headers that keep enquiry mail out of the spam folder.
 *
 * Sender sets the envelope Return-Path so SPF and DMARC align with the From
 * domain. A plain-text part and a domain Message-ID are both scored by spam
 * filters, and the default X-Mailer header advertises the PHPMailer version.
 */
function resteasy_apply_deliverability(PHPMailer $mail)
{
    $c = resteasy_smtp_credentials();
    $domain = substr($c['from_email'], strpos($c['from_email'], '@') + 1);

    $mail->Sender = $c['from_email'];
    $mail->XMailer = ' ';

    if ($mail->MessageID === '') {
        $mail->MessageID = sprintf('<%s.%s@%s>', time(), bin2hex(openssl_random_pseudo_bytes(8)), $domain);
    }

    // MsgHTML() already fills AltBody, but its html2text drops every line break
    // and runs the fields together, so rebuild it from the HTML body.
    if (trim($mail->Body) !== '') {
        $text = preg_replace('~<(script|style)\b[^>]*>.*?</\1>~is', '', $mail->Body);
        $text = preg_replace('~<br\s*/?>~i', "\n", $text);
        $text = preg_replace('~</(p|div|tr|h[1-6]|li|b|strong|td)>~i', "\n", $text);
        $text = html_entity_decode(strip_tags($text), ENT_QUOTES, 'UTF-8');
        $text = str_replace("\xC2\xA0", ' ', $text);
        $text = preg_replace('/[ \t]+/', ' ', $text);
        $text = preg_replace('/ *\n */', "\n", $text);
        $text = preg_replace('/\n{3,}/', "\n\n", $text);
        $mail->AltBody = trim($text);
    }
}

/**
 * Try Microsoft SMTP, then host PHP mail() fallback.
 * Returns true on success. Sets $errorOut on failure.
 */
function resteasy_smtp_send(PHPMailer $mail, &$errorOut = null)
{
    $c = resteasy_smtp_credentials();
    $errors = array();

    // Microsoft 365 preferred: STARTTLS on 587, then SMTPS on 465
    $attempts = array(
        array('tls', 587),
        array('ssl', 465),
    );

    foreach ($attempts as $attempt) {
        list($secure, $port) = $attempt;

        if (method_exists($mail, 'smtpClose')) {
            $mail->smtpClose();
        }

        resteasy_apply_smtp($mail);
        $mail->SMTPSecure = $secure;
        $mail->Port = $port;
        $mail->SetFrom($c['from_email'], $c['from_name']);
        resteasy_apply_deliverability($mail);

        if ($mail->Send()) {
            $errorOut = null;
            return true;
        }

        $errors[] = 'smtp ' . $secure . ':' . $port . ' => ' . $mail->ErrorInfo;
    }

    // Fallback: Hostinger local mailer (no external SMTP — usually allowed).
    // This leaves the web server IP as the sending host, so the SPF record must
    // include Hostinger or authenticated delivery is lost and mail lands in spam.
    if (method_exists($mail, 'smtpClose')) {
        $mail->smtpClose();
    }

    $mail->isMail();
    $mail->CharSet = 'UTF-8';
    $mail->SetFrom($c['from_email'], $c['from_name']);
    resteasy_apply_deliverability($mail);

    if ($mail->Send()) {
        $errorOut = null;
        return true;
    }

    $errors[] = 'php mail() => ' . $mail->ErrorInfo;
    $errorOut = implode(' | ', $errors);
    return false;
}
