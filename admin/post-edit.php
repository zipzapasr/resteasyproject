<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/blog/auth.php';
require_once __DIR__ . '/../includes/blog/posts.php';
require_once __DIR__ . '/_layout.php';

blog_require_admin();
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$post = $id ? blog_post_by_id($id) : null;
if ($id && !$post) {
    http_response_code(404);
    exit('Post not found.');
}
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    blog_verify_csrf();
    try {
        if (isset($_POST['delete_post']) && $post) {
            $statement = blog_db()->prepare('DELETE FROM posts WHERE id = :id');
            $statement->execute(['id' => $post['id']]);
            header('Location: /blog/admin');
            exit;
        }
        if (trim($_POST['title'] ?? '') === '') {
            throw new RuntimeException('A post title is required.');
        }
        if (trim(strip_tags($_POST['body'] ?? '')) === '') {
            throw new RuntimeException('Article content is required.');
        }
        $image = null;
        if (!empty($_FILES['featured_image']['name'])) {
            $image = blog_upload_image($_FILES['featured_image']);
        }
        $data = [
            'title' => $_POST['title'] ?? '',
            'slug' => $_POST['slug'] ?? '',
            'excerpt' => $_POST['excerpt'] ?? '',
            'body' => $_POST['body'] ?? '',
            'featured_image' => $image['path'] ?? ($post['featured_image'] ?? null),
            'image_width' => $image['width'] ?? ($post['image_width'] ?? null),
            'image_height' => $image['height'] ?? ($post['image_height'] ?? null),
            'category_id' => (int) ($_POST['category_id'] ?? 0),
            'meta_title' => $_POST['meta_title'] ?? '',
            'meta_description' => $_POST['meta_description'] ?? '',
            'canonical_url' => $_POST['canonical_url'] ?? '',
            'custom_schema' => $_POST['custom_schema'] ?? '',
            'status' => $_POST['status'] ?? 'draft',
            'published_at' => $post['published_at'] ?? null,
        ];
        $savedId = blog_save_post($data, $post['id'] ?? null);
        header('Location: /blog/admin/post-edit?id=' . $savedId . '&saved=1');
        exit;
    } catch (Throwable $exception) {
        $error = $exception->getMessage();
        $post = array_merge($post ?? [], $_POST);
    }
}

$categories = blog_categories();
$post ??= ['title' => '', 'slug' => '', 'excerpt' => '', 'body' => '', 'featured_image' => '', 'category_id' => '', 'meta_title' => '', 'meta_description' => '', 'canonical_url' => '', 'custom_schema' => '', 'status' => 'draft'];
$editorBody = blog_clean_html((string) $post['body']);
$tinyMceApiKey = getenv('TINYMCE_API_KEY') ?: BLOG_TINYMCE_API_KEY;
admin_page_start($id ? 'Edit post' : 'New post');
admin_navigation();
?>
<main class="admin-shell"><section class="panel">
    <h1><?= $id ? 'Edit post' : 'New post' ?></h1>
    <?php if (isset($_GET['saved'])): ?><p class="message">Post saved.</p><?php endif; ?>
    <?php if ($error): ?><p class="error"><?= blog_e($error) ?></p><?php endif; ?>
    <form method="post" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="csrf" value="<?= blog_e(blog_csrf_token()) ?>">
        <div class="grid">
            <label>Title <input name="title" value="<?= blog_e($post['title']) ?>" required></label>
            <label>URL slug <input name="slug" value="<?= blog_e($post['slug']) ?>" placeholder="Generated from title if blank"></label>
        </div>
        <label>Excerpt <textarea name="excerpt" maxlength="320"><?= blog_e($post['excerpt']) ?></textarea></label>
        <label for="blog-editor">Article body</label>
        <textarea class="body-editor" id="blog-editor" name="body"><?= blog_e($editorBody) ?></textarea>
        <p class="hint">Use Visual mode for writing or Tools → Source code to edit the HTML directly.</p>
        <div class="grid">
            <label>Category <select name="category_id"><option value="">No category</option><?php foreach ($categories as $category): ?><option value="<?= (int) $category['id'] ?>" <?= (int) $post['category_id'] === (int) $category['id'] ? 'selected' : '' ?>><?= blog_e($category['name']) ?></option><?php endforeach; ?></select></label>
            <label>Status <select name="status"><option value="draft" <?= $post['status'] === 'draft' ? 'selected' : '' ?>>Draft</option><option value="published" <?= $post['status'] === 'published' ? 'selected' : '' ?>>Published</option></select></label>
        </div>
        <label>Featured image <input type="file" name="featured_image" accept="image/jpeg,image/png,image/webp"></label>
        <?php if ($post['featured_image']): ?><img class="thumb" src="/uploads/blog/<?= blog_e($post['featured_image']) ?>" alt="Current featured image"><?php endif; ?>
        <div class="grid">
            <label>Meta title <input name="meta_title" maxlength="60" value="<?= blog_e($post['meta_title']) ?>"></label>
            <label>Meta description <input name="meta_description" maxlength="160" value="<?= blog_e($post['meta_description']) ?>"></label>
        </div>
        <label>Canonical URL <input type="url" name="canonical_url" value="<?= blog_e($post['canonical_url']) ?>" placeholder="https://resteasyservices.com.au/blog/example-post"></label>
        <p class="hint">Leave blank to use this post's own blog URL.</p>
        <label>Custom schema (JSON-LD) <textarea name="custom_schema" class="schema-editor" spellcheck="false" placeholder='{"@context":"https://schema.org","@type":"FAQPage"}'><?= blog_e($post['custom_schema']) ?></textarea></label>
        <p class="hint">Paste JSON-LD or a complete application/ld+json script block. It is validated before saving.</p>
        <p class="actions"><button type="submit">Save post</button><a class="button button-muted" href="/blog/admin">Cancel</a></p>
    </form>
    <?php if ($id): ?><form method="post" onsubmit="return confirm('Delete this post?');"><input type="hidden" name="csrf" value="<?= blog_e(blog_csrf_token()) ?>"><input type="hidden" name="delete_post" value="1"><button class="button-danger" type="submit">Delete post</button></form><?php endif; ?>
</section></main>
<script src="https://cdn.tiny.cloud/1/<?= blog_e($tinyMceApiKey) ?>/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#blog-editor',
    height: 520,
    menubar: 'file edit view insert format tools table help',
    plugins: 'advlist autolink lists link charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime table wordcount',
    toolbar: 'undo redo | blocks | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link table | removeformat | code fullscreen',
    content_style: 'body { font-family: Open Sans, Arial, sans-serif; font-size: 16px; line-height: 1.7; }',
    // Keep root-relative or absolute hrefs. Default relative_urls rewrites
    // https://site/page → ../../page from /blog/admin/post-edit, which the
    // server sanitizer then strips down to a bare <a>.
    relative_urls: false,
    remove_script_host: true,
    document_base_url: <?= json_encode(rtrim(BLOG_BASE_URL, '/') . '/', JSON_UNESCAPED_SLASHES) ?>,
    convert_urls: true,
    link_default_protocol: 'https',
    branding: false,
    promotion: false
});
document.querySelector('form[enctype="multipart/form-data"]').addEventListener('submit', function () {
    tinymce.triggerSave();
});
</script>
<?php admin_page_end(); ?>
