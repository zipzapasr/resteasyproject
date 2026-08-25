<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/blog/auth.php';

blog_session_start();
$_SESSION = [];
session_destroy();
header('Location: /blog/admin/login');
