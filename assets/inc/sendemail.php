<?php

function wants_json_response(): bool {
    $xrw = $_SERVER['HTTP_X_REQUESTED_WITH'] ?? '';
    if (strcasecmp($xrw, 'XMLHttpRequest') === 0) return true;

    $accept = $_SERVER['HTTP_ACCEPT'] ?? '';
    if (stripos($accept, 'application/json') !== false) return true;

    return false;
}

$wantsJson = wants_json_response();

// Prevent notices/warnings from breaking output
ini_set('display_errors', '0');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

if ($wantsJson) {
    header('Content-Type: application/json; charset=utf-8');
    header('X-Content-Type-Options: nosniff');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
}

$response = array(
    'success' => false,
    'message' => ''
);

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $suburb = isset($_POST['suburb']) ? trim($_POST['suburb']) : '';
    $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
    $message = isset($_POST['message']) ? trim($_POST['message']) : '';
    
    // Validation
    if (empty($name)) {
        $response['message'] = 'Please enter your name.';
        if ($wantsJson) { echo json_encode($response); }
        exit;
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Please enter a valid email address.';
        if ($wantsJson) { echo json_encode($response); }
        exit;
    }
    
    if (empty($suburb)) {
        $response['message'] = 'Please enter your suburb.';
        if ($wantsJson) { echo json_encode($response); }
        exit;
    }

    if (empty($message)) {
        $response['message'] = 'Please enter your message.';
        if ($wantsJson) { echo json_encode($response); }
        exit;
    }
    
    // Set default subject if empty
    if (empty($subject)) {
        $subject = 'New Enquiry from Website';
    }
    
    // Recipient - Rest Easy Services email
    $toEmail = 'sales@resteasyservices.com.au';

    // Use a domain-based From to avoid DMARC/SMTP rejections.
    $serverName = $_SERVER['SERVER_NAME'] ?? 'resteasyservices.com.au';
    $serverName = preg_replace('/[^a-z0-9\.\-]/i', '', $serverName);
    if ($serverName === '') {
        $serverName = 'resteasyservices.com.au';
    }
    $fromEmail = 'noreply@' . $serverName;
    
    // Build email headers
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: Rest Easy Services <" . $fromEmail . ">\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    
    // Build email body
    $emailBody = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #1e3a5f; color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; background: #f9f9f9; }
            .field { margin-bottom: 15px; }
            .label { font-weight: bold; color: #1e3a5f; }
            .value { margin-top: 5px; }
            .footer { text-align: center; padding: 15px; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>New Website Enquiry</h2>
            </div>
            <div class='content'>
                <div class='field'>
                    <div class='label'>Name:</div>
                    <div class='value'>" . htmlspecialchars($name) . "</div>
                </div>
                <div class='field'>
                    <div class='label'>Email:</div>
                    <div class='value'>" . htmlspecialchars($email) . "</div>
                </div>
                <div class='field'>
                    <div class='label'>Phone:</div>
                    <div class='value'>" . htmlspecialchars($phone ?: 'Not provided') . "</div>
                </div>
                <div class='field'>
                    <div class='label'>Suburb:</div>
                    <div class='value'>" . htmlspecialchars($suburb) . "</div>
                </div>
                <div class='field'>
                    <div class='label'>Subject:</div>
                    <div class='value'>" . htmlspecialchars($subject) . "</div>
                </div>
                <div class='field'>
                    <div class='label'>Message:</div>
                    <div class='value'>" . nl2br(htmlspecialchars($message)) . "</div>
                </div>
            </div>
            <div class='footer'>
                This enquiry was submitted from the Rest Easy Services website.
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Always store the enquiry as a fallback (prevents “silent loss”)
    $submissionsFile = __DIR__ . DIRECTORY_SEPARATOR . 'contact_submissions.txt';
    $timestamp = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $entry = json_encode(array(
        'timestamp' => $timestamp,
        'ip' => $ip,
        'user_agent' => $ua,
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'suburb' => $suburb,
        'subject' => $subject,
        'message' => $message
    )) . "\n";
    $saved = @file_put_contents($submissionsFile, $entry, FILE_APPEND | LOCK_EX);

    // Send email using PHP mail()
    $mailSent = @mail($toEmail, $subject, $emailBody, $headers);
    
    if ($mailSent || $saved !== false) {
        $response['success'] = true;
        $response['message'] = 'Thank you for your enquiry! We have received your message and will get back to you as soon as possible.';
    } else {
        if ($wantsJson) {
            http_response_code(500);
        }
        $response['message'] = 'Sorry, there was a problem sending your message. Please try again or contact us directly at sales@resteasyservices.com.au';
    }
    
} else {
    if ($wantsJson) {
        http_response_code(405);
    }
    $response['message'] = 'Invalid request method.';
}

if ($wantsJson) {
    echo json_encode($response);
    exit;
}

// Non-AJAX form submit: redirect back instead of showing raw JSON
$ref = $_SERVER['HTTP_REFERER'] ?? '';
$defaultTarget = '/contact.php';
$target = $defaultTarget;
if ($ref) {
    $parts = parse_url($ref);
    $host = $parts['host'] ?? '';
    $curHost = $_SERVER['HTTP_HOST'] ?? '';
    if ($host === '' || strcasecmp($host, $curHost) === 0) {
        $path = $parts['path'] ?? '';
        $query = $parts['query'] ?? '';
        if ($path !== '') {
            $target = $path . ($query ? ('?' . $query) : '');
        }
    }
}

$sep = (strpos($target, '?') === false) ? '?' : '&';
$sent = $response['success'] ? '1' : '0';
http_response_code(303);
header('Location: ' . $target . $sep . 'sent=' . $sent);
exit;
?>
