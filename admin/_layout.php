<?php
declare(strict_types=1);

function admin_page_start(string $title): void
{
    ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title><?= blog_e($title) ?> | Rest Easy admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&family=Poppins:wght@600;700&display=swap">
    <link rel="stylesheet" href="/admin/assets/admin.css?v=20260824c">
</head>
<body>
    <?php
}

function admin_navigation(): void
{
    ?>
<header class="admin-bar"><div class="admin-shell">
    <a class="admin-brand" href="/blog/admin">Rest Easy Blog</a>
    <nav class="admin-nav">
        <a href="/blog/admin/post-edit">New post</a>
        <a href="/blog/admin/categories">Categories</a>
        <a href="/blog" target="_blank" rel="noopener">View blog</a>
        <a href="/blog/admin/logout">Log out</a>
    </nav>
</div></header>
    <?php
}

function admin_page_end(): void
{
    echo '</body></html>';
}
