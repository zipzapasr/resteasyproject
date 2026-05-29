<?php

// Prevent notices/warnings from breaking JSON output
ini_set('display_errors', '0');
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

$response = array(
    'success' => false,
    'message' => ''
);

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    
    // Validation
    if (empty($email)) {
        $response['message'] = 'Please enter your email address.';
        echo json_encode($response);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = 'Please enter a valid email address.';
        echo json_encode($response);
        exit;
    }
    
    // Store subscriber in a file
    $subscribersFile = __DIR__ . DIRECTORY_SEPARATOR . 'subscribers.txt';
    
    // Check if already subscribed
    $alreadySubscribed = false;
    if (file_exists($subscribersFile) && is_readable($subscribersFile)) {
        $existingEmails = @file($subscribersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if (is_array($existingEmails)) {
            foreach ($existingEmails as $existingEmail) {
                $parts = explode('|', $existingEmail, 2);
                if (isset($parts[0]) && strtolower(trim($parts[0])) === strtolower($email)) {
                    $alreadySubscribed = true;
                    break;
                }
            }
        }
    } elseif (file_exists($subscribersFile) && !is_readable($subscribersFile)) {
        http_response_code(500);
        $response['message'] = 'Sorry, we could not process subscriptions right now. Please try again later.';
        echo json_encode($response);
        exit;
    }
    
    if ($alreadySubscribed) {
        $response['success'] = true;
        $response['message'] = 'You are already subscribed to our newsletter!';
        echo json_encode($response);
        exit;
    }
    
    // Add new subscriber with timestamp
    $timestamp = date('Y-m-d H:i:s');
    $entry = $email . '|' . $timestamp . "\n";
    
    // If file exists but isn't writable, fail cleanly (avoid PHP warnings in output)
    if (file_exists($subscribersFile) && !is_writable($subscribersFile)) {
        http_response_code(500);
        $response['message'] = 'Sorry, we could not save your subscription right now. Please try again later.';
        echo json_encode($response);
        exit;
    }

    // If file doesn't exist, ensure directory is writable
    if (!file_exists($subscribersFile) && !is_writable(__DIR__)) {
        http_response_code(500);
        $response['message'] = 'Sorry, we could not save your subscription right now. Please try again later.';
        echo json_encode($response);
        exit;
    }

    $saved = @file_put_contents($subscribersFile, $entry, FILE_APPEND | LOCK_EX);
    
    if ($saved !== false) {
        // Send notification email to admin
        $toEmail = 'info@resteasyservices.com.au';
        $subject = 'New Newsletter Subscription';
        
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= "From: Rest Easy Services <noreply@resteasyservices.com.au>\r\n";
        
        $emailBody = "
        <html>
        <body style='font-family: Arial, sans-serif;'>
            <h2 style='color: #1e3a5f;'>New Newsletter Subscription</h2>
            <p><strong>Email:</strong> " . htmlspecialchars($email) . "</p>
            <p><strong>Subscribed on:</strong> " . $timestamp . "</p>
        </body>
        </html>
        ";
        
        @mail($toEmail, $subject, $emailBody, $headers);
        
        $response['success'] = true;
        $response['message'] = 'Thank you for subscribing! You\'ll receive our latest updates and tips.';
    } else {
        http_response_code(500);
        $response['message'] = 'Sorry, we could not save your subscription right now. Please try again later.';
    }
    
} else {
    http_response_code(405);
    $response['message'] = 'Invalid request method.';
}

echo json_encode($response);
?>
