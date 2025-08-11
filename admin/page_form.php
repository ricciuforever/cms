<?php
// This file contains the form for adding/editing a page.

$is_editing = isset($_GET['url']);
$page_data = null;
$page_url = '';
$page_title = '';
$page_description = '';
$page_keywords = '';
$page_image = '';
$page_parent = '';
$page_tipo = 'generico';
$page_robots = 'index,follow';
$page_html_content = '';
$page_categories = [];
$page_types = [];


if ($is_editing) {
    $page_url = $_GET['url'];
    if (isset($pages[$page_url])) {
        $page_data = $pages[$page_url];
        $page_title = $page_data['title'] ?? '';
        $page_description = $page_data['description'] ?? '';
        $page_keywords = $page_data['keywords'] ?? '';
        $page_image = $page_data['image'] ?? '';
        $page_parent = $page_data['parent'] ?? '';
        $page_tipo = $page_data['tipo'] ?? 'generico';
        $page_robots = $page_data['robots'] ?? 'index,follow';
        $page_html_content = $page_data['html_content'] ?? '';
        $page_categories = $page_data['categories'] ?? [];
        $page_types = $page_data['types'] ?? [];
    }
}

// Get parent pages for the dropdown
$parent_pages = [];
foreach ($pages as $url => $data) {
    if (empty($data['parent'])) {
        $parent_pages[$url] = $data;
    }
}
?>

<div class="card">
    <div class="card-header">
        <h3><?= $is_editing ? 'Edit Page' : 'Add New Page' ?></h3>
    </div>
    <div class="card-body">
        <form method="post" action="admin.php?section=pages&action=save">
            <?php if ($is_editing): ?>
                <input type="hidden" name="original_url" value="<?= htmlspecialchars($page_url) ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($page_title) ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description (SEO)</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($page_description) ?></textarea>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="keywords" class="form-label">Keywords (SEO)</label>
                    <input type="text" class="form-control" id="keywords" name="keywords" value="<?= htmlspecialchars($page_keywords) ?>">
                </div>
                 <div class="col-md-6">
                    <label for="image" class="form-label">Featured Image URL</label>
                    <input type="text" class="form-control" id="image" name="image" value="<?= htmlspecialchars($page_image) ?>">
                </div>
            </div>

             <div class="row mb-3">
                <div class="col-md-4">
                    <label for="parent" class="form-label">Parent Page</label>
                    <select id="parent" name="parent" class="form-select">
                        <option value="">-- No Parent --</option>
                        <?php foreach ($parent_pages as $url => $data): ?>
                            <option value="<?= htmlspecialchars($url) ?>" <?= ($page_parent == $url) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($data['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="tipo" class="form-label">Legacy Type</label>
                    <select id="tipo" name="tipo" class="form-select" required>
                        <option value="generico" <?= ($page_tipo == 'generico') ? 'selected' : '' ?>>Generico</option>
                        <option value="mature" <?= ($page_tipo == 'mature') ? 'selected' : '' ?>>Mature</option>
                        <option value="gay" <?= ($page_tipo == 'gay') ? 'selected' : '' ?>>Gay</option>
                        <option value="trans" <?= ($page_tipo == 'trans') ? 'selected' : '' ?>>Trans</option>
                        <option value="padrone" <?= ($page_tipo == 'padrone') ? 'selected' : '' ?>>Padrone</option>
                    </select>
                </div>
                <div class="col-md-4">
                     <label for="robots" class="form-label">Robots</label>
                     <select id="robots" name="robots" class="form-select" required>
                        <option value="index,follow" <?= ($page_robots == 'index,follow') ? 'selected' : '' ?>>Index, Follow</option>
                        <option value="noindex,follow" <?= ($page_robots == 'noindex,follow') ? 'selected' : '' ?>>Noindex, Follow</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <h5>Categories</h5>
                    <div class="card p-2" style="max-height: 150px; overflow-y: auto;">
                        <?php if (empty($categories)): ?>
                            <p class="text-muted">No categories found. Please add one first.</p>
                        <?php else: foreach ($categories as $slug => $category): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="categories[]" value="<?= htmlspecialchars($slug) ?>" id="cat_<?= htmlspecialchars($slug) ?>" <?= in_array($slug, $page_categories) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="cat_<?= htmlspecialchars($slug) ?>">
                                    <?= htmlspecialchars($category['name']) ?>
                                </label>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <h5>Types</h5>
                    <div class="card p-2" style="max-height: 150px; overflow-y: auto;">
                        <?php if (empty($types)): ?>
                             <p class="text-muted">No types found. Please add one first.</p>
                        <?php else: foreach ($types as $slug => $type): ?>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="types[]" value="<?= htmlspecialchars($slug) ?>" id="type_<?= htmlspecialchars($slug) ?>" <?= in_array($slug, $page_types) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="type_<?= htmlspecialchars($slug) ?>">
                                    <?= htmlspecialchars($type['name']) ?>
                                </label>
                            </div>
                        <?php endforeach; endif; ?>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="page_content_editor" class="form-label">Content</label>
                <textarea name="page_content" id="page_content_editor" rows="15"><?= htmlspecialchars($page_html_content) ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save Page</button>
            <a href="admin.php?section=pages" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<script>
    CKEDITOR.replace('page_content_editor');
</script>
