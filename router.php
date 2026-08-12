<?php
/**
 * Local router for PHP built-in server.
 * Maps clean URLs (e.g. /cleaning-services-frankston) to .php files
 * the same way .htaccess does on Apache/production.
 *
 * Start with: php -S localhost:8000 router.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve existing files/directories as-is (assets, images, etc.)
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Map /page or /page/ → page.php
$path = trim($uri, '/');
if ($path === '') {
    require __DIR__ . '/index.php';
    return true;
}

$phpFile = __DIR__ . '/' . $path . '.php';
if (is_file($phpFile)) {
    require $phpFile;
    return true;
}

// Fallback 404
http_response_code(404);
if (is_file(__DIR__ . '/404.php')) {
    require __DIR__ . '/404.php';
} else {
    echo '404 Not Found';
}
return true;
