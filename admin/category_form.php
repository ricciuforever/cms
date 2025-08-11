<?php
// This file contains the form for adding/editing a category.

$is_editing = isset($_GET['slug']);
$category_data = null;
$name = '';
$slug = '';

if ($is_editing) {
    $slug = $_GET['slug'];
    if (isset($categories[$slug])) {
        $category_data = $categories[$slug];
        $name = $category_data['name'];
    }
}
?>
<div class="card">
    <div class="card-header">
        <h3><?= $is_editing ? 'Edit Category' : 'Add New Category' ?></h3>
    </div>
    <div class="card-body">
        <form method="post" action="admin.php?section=categories&action=save">
            <?php if ($is_editing): ?>
                <input type="hidden" name="original_slug" value="<?= htmlspecialchars($slug) ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                <div class="form-text">The "slug" will be generated automatically from the name.</div>
            </div>

            <button type="submit" class="btn btn-primary"><?= $is_editing ? 'Save Changes' : 'Add Category' ?></button>
            <a href="admin.php?section=categories" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
