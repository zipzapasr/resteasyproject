<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/blog/auth.php';
require_once __DIR__ . '/../includes/blog/posts.php';
require_once __DIR__ . '/_layout.php';

blog_require_admin();
$error = '';
$editId = max(0, (int) ($_GET['edit'] ?? 0));
$editing = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    blog_verify_csrf();
    try {
        if (isset($_POST['delete_id'])) {
            $statement = blog_db()->prepare('DELETE FROM categories WHERE id = :id');
            $statement->execute(['id' => (int) $_POST['delete_id']]);
        } elseif (isset($_POST['update_id'])) {
            $updateId = (int) $_POST['update_id'];
            $name = trim($_POST['name'] ?? '');
            if ($name === '') {
                throw new RuntimeException('A category name is required.');
            }
            $slug = blog_slugify($_POST['slug'] ?: $name);
            $statement = blog_db()->prepare('UPDATE categories SET name = :name, slug = :slug WHERE id = :id');
            $statement->execute([
                'name' => $name,
                'slug' => $slug,
                'id' => $updateId,
            ]);
        } else {
            $name = trim($_POST['name'] ?? '');
            if ($name === '') {
                throw new RuntimeException('A category name is required.');
            }
            $statement = blog_db()->prepare('INSERT INTO categories (name, slug) VALUES (:name, :slug)');
            $statement->execute(['name' => $name, 'slug' => blog_slugify($_POST['slug'] ?: $name)]);
        }
        header('Location: /blog/admin/categories');
        exit;
    } catch (Throwable $exception) {
        $error = 'That category name or slug is already in use.';
        if (isset($_POST['update_id'])) {
            $editId = (int) $_POST['update_id'];
        }
    }
}

$categories = blog_categories();
if ($editId > 0) {
    foreach ($categories as $category) {
        if ((int) $category['id'] === $editId) {
            $editing = $category;
            break;
        }
    }
    if (!$editing) {
        $editId = 0;
    }
}

admin_page_start($editing ? 'Edit category' : 'Categories');
admin_navigation();
?>
<main class="admin-shell"><section class="panel">
    <h1><?= $editing ? 'Edit category' : 'Categories' ?></h1>
    <?php if ($error): ?><p class="error"><?= blog_e($error) ?></p><?php endif; ?>
    <form method="post" class="grid">
        <input type="hidden" name="csrf" value="<?= blog_e(blog_csrf_token()) ?>">
        <?php if ($editing): ?>
            <input type="hidden" name="update_id" value="<?= (int) $editing['id'] ?>">
        <?php endif; ?>
        <label>Name <input name="name" value="<?= blog_e($editing['name'] ?? '') ?>" required></label>
        <label>Slug <input name="slug" value="<?= blog_e($editing['slug'] ?? '') ?>" placeholder="Generated from name if blank"></label>
        <p class="actions">
            <button type="submit"><?= $editing ? 'Update category' : 'Add category' ?></button>
            <?php if ($editing): ?>
                <a class="button-secondary" href="/blog/admin/categories">Cancel</a>
            <?php endif; ?>
        </p>
    </form>
    <div class="table-wrap"><table>
        <thead><tr><th>Name</th><th>Slug</th><th></th></tr></thead><tbody>
        <?php foreach ($categories as $category): ?>
            <tr>
                <td><?= blog_e($category['name']) ?></td>
                <td><?= blog_e($category['slug']) ?></td>
                <td class="table-actions">
                    <a class="button-edit" href="/blog/admin/categories?edit=<?= (int) $category['id'] ?>">Edit</a>
                    <form method="post" class="table-actions__delete" onsubmit="return confirm('Delete this category?');">
                        <input type="hidden" name="csrf" value="<?= blog_e(blog_csrf_token()) ?>">
                        <input type="hidden" name="delete_id" value="<?= (int) $category['id'] ?>">
                        <button class="button-danger" type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table></div>
</section></main>
<?php admin_page_end(); ?>
