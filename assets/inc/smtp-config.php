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

        if ($mail->Send()) {
            $errorOut = null;
            return true;
        }

        $errors[] = 'smtp ' . $secure . ':' . $port . ' => ' . $mail->ErrorInfo;
    }

    // Fallback: Hostinger local mailer (no external SMTP — usually allowed)
    if (method_exists($mail, 'smtpClose')) {
        $mail->smtpClose();
    }

    $mail->isMail();
    $mail->CharSet = 'UTF-8';
    $mail->SetFrom($c['from_email'], $c['from_name']);

    if ($mail->Send()) {
        $errorOut = null;
        return true;
    }

    $errors[] = 'php mail() => ' . $mail->ErrorInfo;
    $errorOut = implode(' | ', $errors);
    return false;
}
