<?php
// This file contains the UI for displaying the types table.
?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3>Types</h3>
        <a href="admin.php?section=types&action=edit" class="btn btn-success">Add New Type</a>
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
                    <?php if (!empty($types)): foreach ($types as $slug => $type): ?>
                    <tr>
                        <td>
                            <a href="admin.php?section=types&action=edit&slug=<?= urlencode($slug) ?>">
                                <strong><?= htmlspecialchars($type['name']) ?></strong>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($type['slug']) ?></td>
                        <td class="text-end">
                            <a href="admin.php?section=types&action=edit&slug=<?= urlencode($slug) ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="admin.php?section=types&action=delete&slug=<?= urlencode($slug) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this type?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="3">No types found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
