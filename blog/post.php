<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/blog/posts.php';
require_once __DIR__ . '/../includes/google-form-config.php';

$slug = $_GET['slug'] ?? '';
$post = blog_post_by_slug($slug);
if (!$post) {
    http_response_code(404);
    require __DIR__ . '/../404.php';
    exit;
}
$title = $post['meta_title'] ?: $post['title'] . ' | Rest Easy Services';
$description = $post['meta_description'] ?: $post['excerpt'];
$defaultUrl = BLOG_BASE_URL . '/blog/' . rawurlencode($post['slug']);
$url = $post['canonical_url'] ?: $defaultUrl;
$image = $post['featured_image'] ? BLOG_BASE_URL . BLOG_UPLOAD_URL . '/' . rawurlencode($post['featured_image']) : '';
$googleForm = $resteasyGoogleForm;
?>
<!doctype html>
<html lang="en">
<head>
    <base href="/">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= blog_e($title) ?></title>
    <meta name="description" content="<?= blog_e($description) ?>">
    <meta name="robots" content="index,follow">
    <link rel="canonical" href="<?= blog_e($url) ?>">
    <meta property="og:type" content="article">
    <meta property="og:title" content="<?= blog_e($title) ?>">
    <meta property="og:description" content="<?= blog_e($description) ?>">
    <meta property="og:url" content="<?= blog_e($url) ?>">
    <?php if ($image): ?><meta property="og:image" content="<?= blog_e($image) ?>"><?php endif; ?>
    <meta property="article:published_time" content="<?= blog_e(gmdate('c', strtotime($post['published_at']))) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&family=Poppins:wght@500;600;700&display=swap">
    <link rel="stylesheet" href="assets/vendors/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendors/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="assets/vendors/thm-icons/style.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css?v=20260825h">
    <link rel="stylesheet" href="assets/css/color-1.css">
    <link rel="stylesheet" href="assets/css/blog.css?v=20260825h">
    <script type="application/ld+json"><?= json_encode([
        '@context' => 'https://schema.org',
        '@graph' => [
            ['@type' => 'BlogPosting', 'headline' => $post['title'], 'description' => $description, 'datePublished' => gmdate('c', strtotime($post['published_at'])), 'dateModified' => gmdate('c', strtotime($post['updated_at'])), 'mainEntityOfPage' => $url, 'image' => $image ?: null, 'publisher' => ['@type' => 'Organization', 'name' => 'Rest Easy Services']],
            ['@type' => 'BreadcrumbList', 'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => BLOG_BASE_URL . '/'],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => BLOG_BASE_URL . '/blog'],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $post['title'], 'item' => $url],
            ]],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?></script>
    <?php if ($post['custom_schema']): ?><script type="application/ld+json"><?= $post['custom_schema'] ?></script><?php endif; ?>
</head>
<body>
<div class="page-wrapper">
<?php include __DIR__ . '/../includes/header.php'; ?>
<main class="blog-page">
    <div class="container">
        <div class="blog-post-layout">
            <article class="blog-article">
                <header class="blog-article__header">
                    <div class="blog-card__meta"><?= blog_e($post['category_name'] ?: 'Cleaning tips') ?> · <?= blog_e(date('j F Y', strtotime($post['published_at']))) ?></div>
                    <h1><?= blog_e($post['title']) ?></h1>
                    <?php if ($post['excerpt']): ?><p><?= blog_e($post['excerpt']) ?></p><?php endif; ?>
                </header>
                <?php if ($post['featured_image']): ?>
                    <img class="blog-article__hero" src="/uploads/blog/<?= blog_e($post['featured_image']) ?>" width="<?= (int) $post['image_width'] ?>" height="<?= (int) $post['image_height'] ?>" alt="<?= blog_e($post['title']) ?>" fetchpriority="high" decoding="async">
                <?php endif; ?>
                <div class="blog-article__content"><?= $post['body'] ?></div>
            </article>

            <aside class="blog-sidebar" aria-label="Get a free quote">
                <div class="blog-sidebar__cta">
                    <h2>Let’s Connect</h2>
                    <p>Book a clean or ask a question — we’ll get back to you ASAP.</p>
                    <div class="ajax-form-messages blog-sidebar__messages" style="display:none;"></div>
                    <form id="blog-sidebar-form"
                          action="<?= htmlspecialchars($googleForm['action'], ENT_QUOTES, 'UTF-8') ?>"
                          method="POST"
                          target="gform_hidden_iframe_blog"
                          class="comment-one__form blog-sidebar__form"
                          data-google-form="1"
                          data-enquiry-email-url="<?= htmlspecialchars($resteasyEnquiryEmailUrl, ENT_QUOTES, 'UTF-8') ?>"
                          autocomplete="on">
                        <div class="comment-form__input-box">
                            <input type="text" placeholder="Full name *"
                                   name="<?= htmlspecialchars($googleForm['fields']['name'], ENT_QUOTES, 'UTF-8') ?>"
                                   data-enquiry-field="name" required>
                        </div>
                        <div class="comment-form__input-box">
                            <input type="email" placeholder="Email address *"
                                   name="<?= htmlspecialchars($googleForm['fields']['email'], ENT_QUOTES, 'UTF-8') ?>"
                                   data-enquiry-field="email" required>
                        </div>
                        <div class="comment-form__input-box">
                            <input type="tel" placeholder="Phone *"
                                   name="<?= htmlspecialchars($googleForm['fields']['phone'], ENT_QUOTES, 'UTF-8') ?>"
                                   data-enquiry-field="phone" required>
                        </div>
                        <div class="comment-form__input-box">
                            <input type="text" placeholder="Suburb"
                                   name="<?= htmlspecialchars($googleForm['fields']['suburb'], ENT_QUOTES, 'UTF-8') ?>"
                                   data-enquiry-field="suburb">
                        </div>
                        <div class="comment-form__input-box">
                            <textarea placeholder="Your Message"
                                      name="<?= htmlspecialchars($googleForm['fields']['message'], ENT_QUOTES, 'UTF-8') ?>"
                                      data-enquiry-field="message" rows="4"></textarea>
                        </div>
                        <input type="hidden" name="fvv" value="1">
                        <input type="hidden" name="fbzx" value="<?= htmlspecialchars($googleForm['fbzx'], ENT_QUOTES, 'UTF-8') ?>">
                        <input type="hidden" name="pageHistory" value="0">
                        <input type="hidden" name="submit" value="Submit">
                        <button class="thm-btn" type="submit" id="blog-sidebar-submit">
                            <span class="txt">Send Message +</span>
                        </button>
                    </form>
                    <iframe name="gform_hidden_iframe_blog" id="gform_hidden_iframe_blog" style="display:none;" title="Form submit"></iframe>
                </div>
            </aside>
        </div>
    </div>
</main>
<script>
(function () {
    var form = document.getElementById('blog-sidebar-form');
    if (!form) return;
    var iframe = document.getElementById('gform_hidden_iframe_blog');
    var msg = form.closest('.blog-sidebar__cta').querySelector('.blog-sidebar__messages');
    var btn = document.getElementById('blog-sidebar-submit');
    var btnTxt = btn ? btn.querySelector('.txt') : null;
    var originalBtnText = btnTxt ? btnTxt.textContent : '';
    var submitted = false;

    function showMessage(ok, html) {
        if (!msg) return;
        msg.style.display = 'block';
        msg.style.padding = '12px 14px';
        msg.style.marginBottom = '16px';
        msg.style.borderRadius = '8px';
        if (ok) {
            msg.style.backgroundColor = '#d4edda';
            msg.style.color = '#155724';
            msg.style.border = '1px solid #c3e6cb';
        } else {
            msg.style.backgroundColor = '#f8d7da';
            msg.style.color = '#721c24';
            msg.style.border = '1px solid #f5c6cb';
        }
        msg.innerHTML = html;
    }

    form.addEventListener('submit', function () {
        submitted = true;
        if (btn) btn.disabled = true;
        if (btnTxt) btnTxt.textContent = 'Sending...';
        if (msg) msg.style.display = 'none';
    });

    if (iframe) {
        iframe.addEventListener('load', function () {
            if (!submitted) return;
            submitted = false;
            showMessage(true, '<strong>Success!</strong> Thank you for your enquiry. We will get back to you soon.');
            try { form.reset(); } catch (e) {}
            if (btn) btn.disabled = false;
            if (btnTxt) btnTxt.textContent = originalBtnText || 'Send Message +';
        });
    }
})();
</script>
<?php include __DIR__ . '/../includes/footer.php'; ?>
</div><!-- /.page-wrapper -->
</body></html>
