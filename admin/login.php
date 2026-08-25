<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/blog/auth.php';
require_once __DIR__ . '/../includes/blog/sanitize.php';
require_once __DIR__ . '/_layout.php';

if (blog_admin()) {
    header('Location: /blog/admin');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    blog_verify_csrf();
    if (blog_login($_POST['email'] ?? '', $_POST['password'] ?? '')) {
        header('Location: /blog/admin');
        exit;
    }
    $error = 'Email or password is incorrect.';
}

admin_page_start('Sign in');
?>
<main class="admin-shell"><section class="panel login-panel">
    <h1>Blog admin</h1>
    <p class="hint">Sign in to write and publish Rest Easy articles.</p>
    <?php if ($error): ?><p class="error"><?= blog_e($error) ?></p><?php endif; ?>
    <form method="post">
        <input type="hidden" name="csrf" value="<?= blog_e(blog_csrf_token()) ?>">
        <label>Email <input type="email" name="email" autocomplete="email" required></label>
        <label>Password <input type="password" name="password" autocomplete="current-password" required></label>
        <p class="actions"><button type="submit">Sign in</button></p>
    </form>
    <p class="hint">First login: admin@resteasyservices.com.au / ChangeMeNow!2026. Change it immediately.</p>
</section></main>
<?php admin_page_end(); ?>
