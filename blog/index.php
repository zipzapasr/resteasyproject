<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/blog/posts.php';

$page = max(1, (int) ($_GET['page'] ?? 1));
$perPage = 10;
$total = blog_post_count();
$pages = max(1, (int) ceil($total / $perPage));
if ($page > $pages) {
    http_response_code(404);
    require __DIR__ . '/../404.php';
    exit;
}
$posts = blog_posts($page, $perPage);
?>
<!doctype html>
<html lang="en">
<head>
    <base href="/">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cleaning tips and guides | Rest Easy Services</title>
    <meta name="description" content="Cleaning guides, practical tips and local service advice from Rest Easy Services.">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="<?= BLOG_BASE_URL ?>/blog<?= $page > 1 ? '?page=' . $page : '' ?>">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Cleaning tips and guides | Rest Easy Services">
    <meta property="og:description" content="Cleaning guides and practical advice from Rest Easy Services.">
    <meta property="og:url" content="<?= BLOG_BASE_URL ?>/blog">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Poppins:wght@500;600;700&display=swap">
    <link rel="stylesheet" href="assets/vendors/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendors/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/vendors/thm-icons/style.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css?v=20260825h">
    <link rel="stylesheet" href="assets/css/color-1.css">
    <link rel="stylesheet" href="assets/css/blog.css?v=20260825h">
</head>
<body>
<div class="page-wrapper">
<?php include __DIR__ . '/../includes/header.php'; ?>
<main class="blog-page"><div class="container">
    <header class="blog-intro"><h1>Cleaning tips and guides</h1><p>Useful advice from the Rest Easy team for a cleaner, easier home.</p></header>
    <?php if ($posts): ?><section class="blog-grid">
        <?php foreach ($posts as $post): ?>
        <article class="blog-card">
            <?php if ($post['featured_image']): ?><a class="blog-card__image" href="/blog/<?= blog_e($post['slug']) ?>"><img src="/uploads/blog/<?= blog_e($post['featured_image']) ?>" width="<?= (int) $post['image_width'] ?>" height="<?= (int) $post['image_height'] ?>" alt="" loading="lazy" decoding="async"></a><?php endif; ?>
            <div class="blog-card__body">
                <div class="blog-card__meta"><?= blog_e($post['category_name'] ?: 'Cleaning tips') ?> · <?= blog_e(date('j M Y', strtotime($post['published_at']))) ?></div>
                <h2><a href="/blog/<?= blog_e($post['slug']) ?>"><?= blog_e($post['title']) ?></a></h2>
                <p><?= blog_e($post['excerpt']) ?></p>
                <a class="blog-card__link" href="/blog/<?= blog_e($post['slug']) ?>">View post</a>
            </div>
        </article>
        <?php endforeach; ?>
    </section>
    <nav class="blog-pagination" aria-label="Blog pages">
        <span class="blog-pagination__meta">Page <?= (int) $page ?> of <?= (int) $pages ?></span>
        <?php if ($page > 1): ?>
            <a href="/blog<?= $page - 1 > 1 ? ('?page=' . ($page - 1)) : '' ?>" aria-label="Previous page">&laquo; Prev</a>
        <?php else: ?>
            <span class="is-disabled" aria-disabled="true">&laquo; Prev</span>
        <?php endif; ?>
        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <?php if ($i === $page): ?>
                <span class="active" aria-current="page"><?= $i ?></span>
            <?php else: ?>
                <a href="/blog<?= $i > 1 ? ('?page=' . $i) : '' ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>
        <?php if ($page < $pages): ?>
            <a href="/blog?page=<?= $page + 1 ?>" aria-label="Next page">Next &raquo;</a>
        <?php else: ?>
            <span class="is-disabled" aria-disabled="true">Next &raquo;</span>
        <?php endif; ?>
    </nav>
    <?php else: ?><p class="text-center">New articles are on the way. Please check back soon.</p><?php endif; ?>
</div></main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</div><!-- /.page-wrapper -->
</body></html>
