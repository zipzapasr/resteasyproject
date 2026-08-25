<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';

function blog_session_start(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_name(BLOG_SESSION_NAME);
        session_set_cookie_params([
            'httponly' => true,
            'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
            'samesite' => 'Lax',
        ]);
        session_start();
    }
}

function blog_csrf_token(): string
{
    blog_session_start();
    return $_SESSION['blog_csrf'] ??= bin2hex(random_bytes(32));
}

function blog_verify_csrf(): void
{
    blog_session_start();
    if (!hash_equals($_SESSION['blog_csrf'] ?? '', $_POST['csrf'] ?? '')) {
        http_response_code(419);
        exit('Invalid form token. Please go back and try again.');
    }
}

function blog_admin(): ?array
{
    blog_session_start();
    if (empty($_SESSION['blog_admin_id'])) {
        return null;
    }
    $statement = blog_db()->prepare('SELECT id, email, must_change_password FROM admins WHERE id = :id');
    $statement->execute(['id' => $_SESSION['blog_admin_id']]);
    return $statement->fetch() ?: null;
}

function blog_require_admin(): array
{
    $admin = blog_admin();
    if (!$admin) {
        header('Location: /blog/admin/login');
        exit;
    }
    return $admin;
}

function blog_seed_admin(): void
{
    $db = blog_db();
    if ((int) $db->query('SELECT COUNT(*) FROM admins')->fetchColumn() > 0) {
        return;
    }
    $statement = $db->prepare('INSERT INTO admins (email, password_hash, must_change_password) VALUES (?, ?, 1)');
    $statement->execute(['admin@resteasyservices.com.au', password_hash('ChangeMeNow!2026', PASSWORD_DEFAULT)]);
}

function blog_login(string $email, string $password): bool
{
    blog_seed_admin();
    $statement = blog_db()->prepare('SELECT * FROM admins WHERE email = :email');
    $statement->execute(['email' => strtolower(trim($email))]);
    $admin = $statement->fetch();
    if (!$admin || !password_verify($password, $admin['password_hash'])) {
        return false;
    }
    blog_session_start();
    session_regenerate_id(true);
    $_SESSION['blog_admin_id'] = $admin['id'];
    return true;
}
