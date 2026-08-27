<?php
/**
 * Local router for PHP built-in server.
 * - Clean URL mapping (same as .htaccess)
 * - Cache-Control headers for static assets (fixes Lighthouse "efficient cache lifetimes")
 *
 * Start with: php -S localhost:8000 router.php
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$file = __DIR__ . $uri;

// Serve static files with long cache headers (CSS/JS/images/fonts)
if ($uri !== '/' && is_file($file)) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    $mimeTypes = array(
        'css'   => 'text/css; charset=UTF-8',
        'js'    => 'application/javascript; charset=UTF-8',
        'mjs'   => 'application/javascript; charset=UTF-8',
        'json'  => 'application/json; charset=UTF-8',
        'svg'   => 'image/svg+xml',
        'png'   => 'image/png',
        'jpg'   => 'image/jpeg',
        'jpeg'  => 'image/jpeg',
        'gif'   => 'image/gif',
        'webp'  => 'image/webp',
        'avif'  => 'image/avif',
        'ico'   => 'image/x-icon',
        'woff'  => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf'   => 'font/ttf',
        'eot'   => 'application/vnd.ms-fontobject',
        'map'   => 'application/json',
        'txt'   => 'text/plain; charset=UTF-8',
        'xml'   => 'application/xml',
        'webmanifest' => 'application/manifest+json',
    );

    $longCacheExt = array(
        'css', 'js', 'mjs', 'png', 'jpg', 'jpeg', 'gif', 'webp', 'avif', 'svg', 'ico',
        'woff', 'woff2', 'ttf', 'eot', 'map', 'webmanifest'
    );

    if (isset($mimeTypes[$ext])) {
        $mtime = filemtime($file);
        $etag = '"' . dechex($mtime) . '-' . dechex(filesize($file)) . '"';

        header('Content-Type: ' . $mimeTypes[$ext]);
        header('ETag: ' . $etag);
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $mtime) . ' GMT');
        header('Vary: Accept-Encoding');

        if (in_array($ext, $longCacheExt, true)) {
            header('Cache-Control: public, max-age=31536000, immutable');
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
        } else {
            header('Cache-Control: public, max-age=86400');
        }

        $ifNoneMatch = $_SERVER['HTTP_IF_NONE_MATCH'] ?? '';
        $ifModifiedSince = $_SERVER['HTTP_IF_MODIFIED_SINCE'] ?? '';
        if ($ifNoneMatch === $etag || ($ifModifiedSince && strtotime($ifModifiedSince) >= $mtime)) {
            http_response_code(304);
            return true;
        }

        header('Content-Length: ' . filesize($file));
        readfile($file);
        return true;
    }

    return false;
}

// Map /page or /page/ → page.php
$path = trim($uri, '/');
if ($path === '') {
    header('Cache-Control: no-cache, must-revalidate');
    require __DIR__ . '/index.php';
    return true;
}

if (preg_match('~^blog/admin/(login|logout|post-edit|categories)/?$~', $path, $matches)) {
    header('Cache-Control: no-cache, must-revalidate');
    require __DIR__ . '/admin/' . $matches[1] . '.php';
    return true;
}

if (in_array($path, ['blog', 'blog/admin'], true)) {
    header('Cache-Control: no-cache, must-revalidate');
    require __DIR__ . ($path === 'blog/admin' ? '/admin/index.php' : '/blog/index.php');
    return true;
}

if (preg_match('~^blog/([a-z0-9-]+)/?$~i', $path, $matches)) {
    $_GET['slug'] = strtolower($matches[1]);
    header('Cache-Control: no-cache, must-revalidate');
    require __DIR__ . '/blog/post.php';
    return true;
}

$phpFile = __DIR__ . '/' . $path . '.php';
if (is_file($phpFile)) {
    header('Cache-Control: no-cache, must-revalidate');
    require $phpFile;
    return true;
}

http_response_code(404);
header('Cache-Control: no-cache');
if (is_file(__DIR__ . '/404.php')) {
    require __DIR__ . '/404.php';
} else {
    echo '404 Not Found';
}
return true;
