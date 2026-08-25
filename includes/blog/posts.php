<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/sanitize.php';

function blog_categories(): array
{
    return blog_db()->query('SELECT * FROM categories ORDER BY name')->fetchAll();
}

function blog_post_by_slug(string $slug, bool $publishedOnly = true): ?array
{
    $sql = 'SELECT p.*, c.name AS category_name, c.slug AS category_slug FROM posts p
        LEFT JOIN categories c ON c.id = p.category_id WHERE p.slug = :slug';
    if ($publishedOnly) {
        $sql .= " AND p.status = 'published' AND p.published_at <= CURRENT_TIMESTAMP";
    }
    $statement = blog_db()->prepare($sql);
    $statement->execute(['slug' => $slug]);
    return $statement->fetch() ?: null;
}

function blog_post_by_id(int $id): ?array
{
    $statement = blog_db()->prepare('SELECT * FROM posts WHERE id = :id');
    $statement->execute(['id' => $id]);
    return $statement->fetch() ?: null;
}

function blog_posts(int $page = 1, int $perPage = 10): array
{
    $offset = max(0, ($page - 1) * $perPage);
    $statement = blog_db()->prepare(
        "SELECT p.id, p.title, p.slug, p.excerpt, p.featured_image, p.image_width, p.image_height,
                p.published_at, c.name AS category_name
         FROM posts p LEFT JOIN categories c ON c.id = p.category_id
         WHERE p.status = 'published' AND p.published_at <= CURRENT_TIMESTAMP
         ORDER BY p.published_at DESC LIMIT :limit OFFSET :offset"
    );
    $statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetchAll();
}

function blog_post_count(): int
{
    return (int) blog_db()->query(
        "SELECT COUNT(*) FROM posts WHERE status = 'published' AND published_at <= CURRENT_TIMESTAMP"
    )->fetchColumn();
}

function blog_admin_posts(?string $status = null, int $page = 1, int $perPage = 10): array
{
    $sql = 'SELECT p.*, c.name AS category_name FROM posts p LEFT JOIN categories c ON c.id = p.category_id';
    if (in_array($status, ['draft', 'published'], true)) {
        $sql .= ' WHERE p.status = :status';
    }
    $sql .= ' ORDER BY p.updated_at DESC LIMIT :limit OFFSET :offset';
    $statement = blog_db()->prepare($sql);
    if ($status) {
        $statement->bindValue(':status', $status, PDO::PARAM_STR);
    }
    $statement->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $statement->bindValue(':offset', max(0, ($page - 1) * $perPage), PDO::PARAM_INT);
    $statement->execute();
    return $statement->fetchAll();
}

function blog_admin_post_count(?string $status = null): int
{
    $sql = 'SELECT COUNT(*) FROM posts';
    if (in_array($status, ['draft', 'published'], true)) {
        $sql .= ' WHERE status = :status';
    }
    $statement = blog_db()->prepare($sql);
    $statement->execute($status ? ['status' => $status] : []);
    return (int) $statement->fetchColumn();
}

function blog_save_post(array $data, ?int $id = null): int
{
    $db = blog_db();
    $now = gmdate('Y-m-d H:i:s');
    $status = $data['status'] === 'published' ? 'published' : 'draft';
    $values = [
        'title' => trim($data['title']),
        'slug' => blog_slugify($data['slug'] ?: $data['title']),
        'excerpt' => trim($data['excerpt']),
        'body' => blog_clean_html($data['body']),
        'featured_image' => $data['featured_image'] ?: null,
        'image_width' => $data['image_width'] ?: null,
        'image_height' => $data['image_height'] ?: null,
        'category_id' => $data['category_id'] ?: null,
        'meta_title' => trim($data['meta_title']),
        'meta_description' => trim($data['meta_description']),
        'canonical_url' => blog_canonical_url($data['canonical_url'] ?? ''),
        'custom_schema' => blog_normalize_schema($data['custom_schema'] ?? ''),
        'status' => $status,
        'published_at' => $status === 'published' ? ($data['published_at'] ?: $now) : null,
        'updated_at' => $now,
    ];
    if ($id) {
        $values['id'] = $id;
        $statement = $db->prepare('UPDATE posts SET title=:title, slug=:slug, excerpt=:excerpt, body=:body,
            featured_image=:featured_image, image_width=:image_width, image_height=:image_height,
            category_id=:category_id, meta_title=:meta_title, meta_description=:meta_description,
            canonical_url=:canonical_url, custom_schema=:custom_schema,
            status=:status, published_at=:published_at, updated_at=:updated_at WHERE id=:id');
        $statement->execute($values);
        return $id;
    }
    $values['created_at'] = $now;
    $statement = $db->prepare('INSERT INTO posts (title, slug, excerpt, body, featured_image, image_width,
        image_height, category_id, meta_title, meta_description, canonical_url, custom_schema, status, published_at, created_at, updated_at)
        VALUES (:title, :slug, :excerpt, :body, :featured_image, :image_width, :image_height, :category_id,
        :meta_title, :meta_description, :canonical_url, :custom_schema, :status, :published_at, :created_at, :updated_at)');
    $statement->execute($values);
    return (int) $db->lastInsertId();
}
