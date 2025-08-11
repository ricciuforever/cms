<?php
// This file contains the UI for displaying the pages table.
?>
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3>Pages</h3>
        <a href="admin.php?section=pages&action=edit" class="btn btn-success">Add New Page</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>URL</th>
                        <th>Date</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($pages)): foreach ($pages as $url => $page): ?>
                    <tr>
                        <td>
                            <a href="admin.php?section=pages&action=edit&url=<?= urlencode($url) ?>">
                                <strong><?= htmlspecialchars($page['title']) ?></strong>
                            </a>
                        </td>
                        <td><a href="<?= htmlspecialchars($page['url']) ?>" target="_blank"><?= htmlspecialchars($page['url']) ?></a></td>
                        <td><?= isset($page['created_at']) ? date('Y-m-d', $page['created_at']) : 'N/A' ?></td>
                        <td class="text-end">
                            <a href="admin.php?section=pages&action=edit&url=<?= urlencode($url) ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="admin.php?section=pages&action=delete&url=<?= urlencode($url) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this page?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="4">No pages found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
