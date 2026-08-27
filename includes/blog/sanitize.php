    <?php
    declare(strict_types=1);

    function blog_slugify(string $value): string
    {
        $value = trim(strtolower($value));
        $value = preg_replace('~[^a-z0-9]+~', '-', $value) ?? '';
        return trim($value, '-') ?: 'post';
    }

    function blog_e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    function blog_sanitize_href(string $url): ?string
    {
        $url = html_entity_decode(trim($url), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        if ($url === '' || preg_match('~^(javascript|data|vbscript):~i', $url)) {
            return null;
        }
        if (preg_match('~^(https?://|mailto:|tel:)~i', $url)) {
            return $url;
        }
        // Root-relative paths are already safe for this site.
        if (str_starts_with($url, '/')) {
            return $url;
        }
        // TinyMCE (relative_urls) rewrites /page to ../../page from /blog/admin/post-edit.
        if (preg_match('~^(?:\./|\.\./)+~', $url)) {
            $parts = [];
            foreach (explode('/', $url) as $segment) {
                if ($segment === '' || $segment === '.') {
                    continue;
                }
                if ($segment === '..') {
                    array_pop($parts);
                    continue;
                }
                $parts[] = $segment;
            }
            return '/' . implode('/', $parts);
        }
        // Plain path/slug links such as "window-cleaning" from the link dialog.
        if (preg_match('~^[a-zA-Z0-9][a-zA-Z0-9_\-./?#=&%]*$~', $url)) {
            return '/' . $url;
        }
        return null;
    }

    function blog_link_rel(string $url): string
    {
        if (preg_match('~^(mailto:|tel:)~i', $url) || str_starts_with($url, '/')) {
            return '';
        }
        if (preg_match('~^https?://~i', $url)) {
            $host = strtolower((string) parse_url($url, PHP_URL_HOST));
            $siteHost = defined('BLOG_BASE_URL')
                ? strtolower((string) parse_url(BLOG_BASE_URL, PHP_URL_HOST))
                : 'resteasyservices.com.au';
            if ($host === $siteHost) {
                return '';
            }
            return ' rel="noopener noreferrer"';
        }
        return '';
    }

    function blog_clean_html(string $html): string
    {
        $allowed = '<p><br><strong><b><em><i><u><s><del><h2><h3><h4><ul><ol><li><a><blockquote><table><thead><tbody><tr><th><td>';
        $html = strip_tags($html, $allowed);
        $html = preg_replace('/\s(?:on\w+|style)\s*=\s*(["\']).*?\1/i', '', $html) ?? '';
        $html = preg_replace_callback(
            '/<a\b([^>]*)>(.*?)<\/a>/is',
            static function (array $matches): string {
                $attrs = $matches[1];
                $text = $matches[2];
                if (!preg_match('/\bhref\s*=\s*(["\'])(.*?)\1/i', $attrs, $hrefMatch)) {
                    return $text;
                }
                $safe = blog_sanitize_href($hrefMatch[2]);
                if ($safe === null) {
                    return $text;
                }
                $rel = blog_link_rel($safe);
                return '<a href="' . htmlspecialchars($safe, ENT_QUOTES, 'UTF-8') . '"' . $rel . '>' . $text . '</a>';
            },
            $html
        ) ?? '';
        return trim($html);
    }

    function blog_canonical_url(string $url): string
    {
        $url = trim($url);
        if ($url === '') {
            return '';
        }
        if (!filter_var($url, FILTER_VALIDATE_URL) || !in_array(parse_url($url, PHP_URL_SCHEME), ['http', 'https'], true)) {
            throw new RuntimeException('Canonical URL must be a full http:// or https:// URL.');
        }
        return $url;
    }

    function blog_normalize_schema(string $schema): string
    {
        $schema = trim($schema);
        if ($schema === '') {
            return '';
        }
        $schema = preg_replace('~^<script\b[^>]*application/ld\+json[^>]*>\s*|\s*</script>$~is', '', $schema) ?? '';
        try {
            $decoded = json_decode($schema, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new RuntimeException('Custom schema must be valid JSON-LD.');
        }
        if (!is_array($decoded)) {
            throw new RuntimeException('Custom schema must be a JSON object or array.');
        }
        return json_encode($decoded, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR);
    }

    function blog_upload_image(array $upload): ?array
    {
        if (($upload['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if (($upload['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK || ($upload['size'] ?? 0) > BLOG_MAX_IMAGE_BYTES) {
            throw new RuntimeException('Upload a valid image smaller than 8 MB.');
        }

        $info = @getimagesize($upload['tmp_name']);
        if (!$info || !in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            throw new RuntimeException('Upload a JPG, PNG, or WebP image.');
        }
        if (!function_exists('imagewebp')) {
            throw new RuntimeException('The PHP GD WebP extension is required for image uploads.');
        }

        $source = match ($info[2]) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($upload['tmp_name']),
            IMAGETYPE_PNG => imagecreatefrompng($upload['tmp_name']),
            IMAGETYPE_WEBP => imagecreatefromwebp($upload['tmp_name']),
        };
        if (!$source) {
            throw new RuntimeException('The image could not be processed.');
        }

        $sourceWidth = imagesx($source);
        $sourceHeight = imagesy($source);
        $width = min($sourceWidth, 1600);
        $height = (int) round($sourceHeight * ($width / $sourceWidth));
        $canvas = imagecreatetruecolor($width, $height);
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $width, $height, $sourceWidth, $sourceHeight);

        if (!is_dir(BLOG_UPLOAD_DIR) && !mkdir(BLOG_UPLOAD_DIR, 0755, true)) {
            throw new RuntimeException('The blog upload directory could not be created.');
        }
        $filename = bin2hex(random_bytes(16)) . '.webp';
        $target = BLOG_UPLOAD_DIR . '/' . $filename;
        $saved = imagewebp($canvas, $target, 78);
        imagedestroy($source);
        imagedestroy($canvas);
        if (!$saved) {
            throw new RuntimeException('The image could not be saved.');
        }

        return ['path' => $filename, 'width' => $width, 'height' => $height];
    }
