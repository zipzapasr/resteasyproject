<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/blog/auth.php';
require_once __DIR__ . '/../includes/blog/posts.php';
require_once __DIR__ . '/_layout.php';

$admin = blog_require_admin();
$message = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'])) {
    blog_verify_csrf();
    if (strlen($_POST['new_password']) < 12) {
        $error = 'Use a password with at least 12 characters.';
    } else {
        $statement = blog_db()->prepare('UPDATE admins SET password_hash = :hash, must_change_password = 0 WHERE id = :id');
        $statement->execute(['hash' => password_hash($_POST['new_password'], PASSWORD_DEFAULT), 'id' => $admin['id']]);
        $admin['must_change_password'] = 0;
        $message = 'Password updated.';
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_post_id'])) {
    blog_verify_csrf();
    $statement = blog_db()->prepare('SELECT featured_image FROM posts WHERE id = :id');
    $statement->execute(['id' => (int) $_POST['delete_post_id']]);
    $postToDelete = $statement->fetch();
    if ($postToDelete) {
        $statement = blog_db()->prepare('DELETE FROM posts WHERE id = :id');
        $statement->execute(['id' => (int) $_POST['delete_post_id']]);
        if ($postToDelete['featured_image']) {
            $imagePath = BLOG_UPLOAD_DIR . '/' . basename($postToDelete['featured_image']);
            if (is_file($imagePath)) {
                unlink($imagePath);
            }
        }
    }
    header('Location: /blog/admin?deleted=1');
    exit;
}

$status = in_array($_GET['status'] ?? null, ['draft', 'published'], true) ? $_GET['status'] : null;
$perPage = 10;
$totalPosts = blog_admin_post_count($status);
$totalPages = max(1, (int) ceil($totalPosts / $perPage));
$page = min(max(1, (int) ($_GET['page'] ?? 1)), $totalPages);
$posts = blog_admin_posts($status, $page, $perPage);
if (isset($_GET['deleted'])) {
    $message = 'Post deleted.';
}

admin_page_start('Posts');
admin_navigation();
?>
<main class="admin-shell">
    <?php if ($message): ?><p class="message"><?= blog_e($message) ?></p><?php endif; ?>
    <?php if ($error): ?><p class="error"><?= blog_e($error) ?></p><?php endif; ?>
    <?php if ($admin['must_change_password']): ?>
    <section class="panel">
        <h1>Set a private password</h1>
        <p>Replace the temporary password before publishing anything.</p>
        <form method="post">
            <input type="hidden" name="csrf" value="<?= blog_e(blog_csrf_token()) ?>">
            <label>New password <input type="password" name="new_password" minlength="12" required></label>
            <p class="actions"><button type="submit">Update password</button></p>
        </form>
    </section>
    <?php endif; ?>
    <section class="panel">
        <div class="actions" style="justify-content:space-between;margin-top:0">
            <h1 style="margin:0">Posts</h1>
            <a class="button" href="/blog/admin/post-edit">Create post</a>
        </div>
        <p><a href="/blog/admin">All</a> · <a href="/blog/admin?status=published">Published</a> · <a href="/blog/admin?status=draft">Drafts</a></p>
        <div class="table-wrap"><table>
            <thead><tr><th>Title</th><th>Status</th><th>Category</th><th>Updated</th><th>Actions</th></tr></thead>
            <tbody>
            <?php foreach ($posts as $post): ?>
                <tr><td><?= blog_e($post['title']) ?></td><td><?= blog_e(ucfirst($post['status'])) ?></td>
                    <td><?= blog_e($post['category_name'] ?: '—') ?></td><td><?= blog_e($post['updated_at']) ?></td>
                    <td class="post-actions"><a class="action-icon action-icon--edit" href="/blog/admin/post-edit?id=<?= (int) $post['id'] ?>" aria-label="Edit <?= blog_e($post['title']) ?>" title="Edit post">&#9998;</a>
                    <form method="post" onsubmit="return confirm('Delete this post permanently?');">
                        <input type="hidden" name="csrf" value="<?= blog_e(blog_csrf_token()) ?>">
                        <input type="hidden" name="delete_post_id" value="<?= (int) $post['id'] ?>">
                        <button class="action-icon action-icon--delete" type="submit" aria-label="Delete <?= blog_e($post['title']) ?>" title="Delete post">&#128465;</button>
                    </form></td></tr>
            <?php endforeach; ?>
            <?php if (!$posts): ?><tr><td colspan="5">No posts yet.</td></tr><?php endif; ?>
            </tbody>
        </table></div>
        <?php if ($totalPages > 1): ?><nav class="admin-pagination" aria-label="Post pages">
            <?php for ($number = 1; $number <= $totalPages; $number++): ?>
                <?php $href = '/blog/admin?page=' . $number . ($status ? '&status=' . rawurlencode($status) : ''); ?>
                <?php if ($number === $page): ?><span aria-current="page"><?= $number ?></span><?php else: ?><a href="<?= blog_e($href) ?>"><?= $number ?></a><?php endif; ?>
            <?php endfor; ?>
        </nav><?php endif; ?>
    </section>
</main>
<?php admin_page_end(); ?>
