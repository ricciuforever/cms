<?php
// This file contains the form for adding/editing a type.

$is_editing = isset($_GET['slug']);
$type_data = null;
$name = '';
$slug = '';

if ($is_editing) {
    $slug = $_GET['slug'];
    if (isset($types[$slug])) {
        $type_data = $types[$slug];
        $name = $type_data['name'];
    }
}
?>
<div class="card">
    <div class="card-header">
        <h3><?= $is_editing ? 'Edit Type' : 'Add New Type' ?></h3>
    </div>
    <div class="card-body">
        <form method="post" action="admin.php?section=types&action=save">
            <?php if ($is_editing): ?>
                <input type="hidden" name="original_slug" value="<?= htmlspecialchars($slug) ?>">
            <?php endif; ?>

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($name) ?>" required>
                <div class="form-text">The "slug" will be generated automatically from the name.</div>
            </div>

            <button type="submit" class="btn btn-primary"><?= $is_editing ? 'Save Changes' : 'Add Type' ?></button>
            <a href="admin.php?section=types" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
