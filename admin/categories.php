<?php
// This file contains the UI for displaying the categories table.
?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3>Categories</h3>
        <a href="admin.php?section=categories&action=edit" class="btn btn-success">Add New Category</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)): foreach ($categories as $slug => $category): ?>
                    <tr>
                        <td>
                            <a href="admin.php?section=categories&action=edit&slug=<?= urlencode($slug) ?>">
                                <strong><?= htmlspecialchars($category['name']) ?></strong>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($category['slug']) ?></td>
                        <td class="text-end">
                            <a href="admin.php?section=categories&action=edit&slug=<?= urlencode($slug) ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="admin.php?section=categories&action=delete&slug=<?= urlencode($slug) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this category?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="3">No categories found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
