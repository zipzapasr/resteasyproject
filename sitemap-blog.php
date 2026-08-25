<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/blog/posts.php';

header('Content-Type: application/xml; charset=UTF-8');
$statement = blog_db()->query(
    "SELECT slug, updated_at FROM posts WHERE status = 'published' AND published_at <= CURRENT_TIMESTAMP ORDER BY published_at DESC"
);
$posts = $statement->fetchAll();
echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc><?= BLOG_BASE_URL ?>/blog</loc></url>
<?php foreach ($posts as $post): ?>
  <url><loc><?= BLOG_BASE_URL ?>/blog/<?= rawurlencode($post['slug']) ?></loc><lastmod><?= gmdate('c', strtotime($post['updated_at'])) ?></lastmod></url>
<?php endforeach; ?>
</urlset>
