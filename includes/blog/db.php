<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/blog.php';

function blog_db(): PDO
{
    static $db = null;
    if ($db instanceof PDO) {
        return $db;
    }

    if (!extension_loaded('pdo_sqlite')) {
        throw new RuntimeException('The PHP PDO SQLite extension is required for the blog.');
    }

    if (!is_dir(dirname(BLOG_DB_PATH))) {
        mkdir(dirname(BLOG_DB_PATH), 0755, true);
    }

    $db = new PDO('sqlite:' . BLOG_DB_PATH, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    $db->exec('PRAGMA journal_mode = WAL');
    $db->exec('PRAGMA foreign_keys = ON');
    blog_migrate($db);

    return $db;
}

function blog_migrate(PDO $db): void
{
    $db->exec(
        'CREATE TABLE IF NOT EXISTS admins (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            email TEXT NOT NULL UNIQUE,
            password_hash TEXT NOT NULL,
            must_change_password INTEGER NOT NULL DEFAULT 1,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        );
        CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE,
            slug TEXT NOT NULL UNIQUE
        );
        CREATE TABLE IF NOT EXISTS posts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            slug TEXT NOT NULL UNIQUE,
            excerpt TEXT NOT NULL DEFAULT \'\',
            body TEXT NOT NULL DEFAULT \'\',
            featured_image TEXT,
            image_width INTEGER,
            image_height INTEGER,
            category_id INTEGER,
            meta_title TEXT NOT NULL DEFAULT \'\',
            meta_description TEXT NOT NULL DEFAULT \'\',
            canonical_url TEXT NOT NULL DEFAULT \'\',
            custom_schema TEXT NOT NULL DEFAULT \'\',
            status TEXT NOT NULL DEFAULT \'draft\' CHECK(status IN (\'draft\', \'published\')),
            published_at TEXT,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY(category_id) REFERENCES categories(id) ON DELETE SET NULL
        );
        CREATE INDEX IF NOT EXISTS posts_slug_idx ON posts(slug);
        CREATE INDEX IF NOT EXISTS posts_status_published_idx ON posts(status, published_at);'
    );
    blog_add_column_if_missing($db, 'posts', 'canonical_url', "TEXT NOT NULL DEFAULT ''");
    blog_add_column_if_missing($db, 'posts', 'custom_schema', "TEXT NOT NULL DEFAULT ''");
}

function blog_add_column_if_missing(PDO $db, string $table, string $column, string $definition): void
{
    $columns = $db->query('PRAGMA table_info(' . $table . ')')->fetchAll();
    foreach ($columns as $existingColumn) {
        if ($existingColumn['name'] === $column) {
            return;
        }
    }
    $db->exec('ALTER TABLE ' . $table . ' ADD COLUMN ' . $column . ' ' . $definition);
}
