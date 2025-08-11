<?php
session_start();
require_once 'config.php';
require_once 'admin_functions.php';

// --- HELPER FUNCTIONS ---
function generate_slug_from_text($text) {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9-]+/', '-', $text);
    return trim($text, '-');
}

function save_json_file($filepath, $data) {
    ksort($data);
    $json_data = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    if (file_put_contents($filepath, $json_data) === false) {
        fail_gracefully("Error saving file '$filepath'. Check permissions.");
    }
}

// --- FILE PATHS ---
$file_pages = 'pages.json';
$file_categories = 'categories.json';
$file_types = 'types.json';

// --- LOGIN LOGIC ---
if (isset($_POST['password'])) {
    if (password_verify($_POST['password'], ADMIN_PASSWORD_HASH)) {
        $_SESSION['logged_in'] = true;
        header('Location: admin.php');
        exit();
    } else {
        $error = 'Password errata!';
    }
}
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: admin.php');
    exit();
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    // --- ROUTING & DATA LOADING ---
    $section = $_GET['section'] ?? 'pages';
    $action = $_GET['action'] ?? 'list';

    $pages = json_decode(file_get_contents($file_pages), true) ?? [];
    $categories = json_decode(file_get_contents($file_categories), true) ?? [];
    $types = json_decode(file_get_contents($file_types), true) ?? [];

    // --- PROCESSING LOGIC ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($section === 'categories' && $action === 'save') {
            $name = $_POST['name'];
            $slug = generate_slug_from_text($name);
            $original_slug = $_POST['original_slug'] ?? null;
            if ($original_slug && $original_slug !== $slug) unset($categories[$original_slug]);
            $categories[$slug] = ['name' => $name, 'slug' => $slug];
            save_json_file($file_categories, $categories);
            $_SESSION['success_message'] = "Category '$name' saved.";
            header('Location: admin.php?section=categories');
            exit();
        }
        if ($section === 'types' && $action === 'save') {
            $name = $_POST['name'];
            $slug = generate_slug_from_text($name);
            $original_slug = $_POST['original_slug'] ?? null;
            if ($original_slug && $original_slug !== $slug) unset($types[$original_slug]);
            $types[$slug] = ['name' => $name, 'slug' => $slug];
            save_json_file($file_types, $types);
            $_SESSION['success_message'] = "Type '$name' saved.";
            header('Location: admin.php?section=types');
            exit();
        }
        if ($section === 'pages' && $action === 'save') {
            $is_editing = !empty($_POST['original_url']);
            if ($is_editing) {
                $url = $_POST['original_url'];
                unset($pages[$url]);
            }
            $parent_url = rtrim($_POST['parent'], '/');
            $url = $parent_url . '/' . generate_slug_from_text($_POST['title']);
            if ($url === '') $url = '/';

            $pages[$url] = [
                'title'        => $_POST['title'], 'tipo' => $_POST['tipo'], 'url' => $url,
                'description'  => $_POST['description'], 'robots' => $_POST['robots'], 'keywords' => $_POST['keywords'],
                'html_content' => $_POST['page_content'] ?? '', 'parent' => $_POST['parent'], 'image' => $_POST['image'],
                'created_at'   => $is_editing ? ($pages[$_POST['original_url']]['created_at'] ?? time()) : time(),
                'categories'   => $_POST['categories'] ?? [], 'types' => $_POST['types'] ?? []
            ];
            save_json_file($file_pages, $pages);
            $_SESSION['success_message'] = "Page '{$_POST['title']}' saved.";
            header('Location: admin.php?section=pages');
            exit();
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if ($action === 'delete') {
            if ($section === 'categories' && isset($_GET['slug'])) {
                $slug = $_GET['slug'];
                $name = $categories[$slug]['name'] ?? $slug;
                unset($categories[$slug]);
                save_json_file($file_categories, $categories);
                $_SESSION['success_message'] = "Category '$name' deleted.";
                header('Location: admin.php?section=categories');
                exit();
            }
            if ($section === 'types' && isset($_GET['slug'])) {
                $slug = $_GET['slug'];
                $name = $types[$slug]['name'] ?? $slug;
                unset($types[$slug]);
                save_json_file($file_types, $types);
                $_SESSION['success_message'] = "Type '$name' deleted.";
                header('Location: admin.php?section=types');
                exit();
            }
            if ($section === 'pages' && isset($_GET['url'])) {
                $url = $_GET['url'];
                $title = $pages[$url]['title'] ?? $url;
                unset($pages[$url]);
                save_json_file($file_pages, $pages);
                $_SESSION['success_message'] = "Page '$title' deleted.";
                header('Location: admin.php?section=pages');
                exit();
            }
        }
    }
}
?>
<!doctype html>
<html lang="it">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.ckeditor.com/4.16.2/full/ckeditor.js"></script>
    <style>
        body { display: flex; min-height: 100vh; flex-direction: column; }
        .main-container { display: flex; flex: 1; }
        .sidebar { width: 280px; background-color: #343a40; }
        .content { flex: 1; padding: 2rem; background-color: #f8f9fa; }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true): ?>
    <div class="container"><div class="row justify-content-center align-items-center vh-100"><div class="col-md-4"><div class="card"><div class="card-body">
        <h3 class="card-title text-center">Login</h3>
        <form method="post">
            <div class="mb-3"><label for="password" class="form-label">Password</label><input type="password" class="form-control" id="password" name="password" required></div>
            <?php if (isset($error)): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div></div></div></div></div>
<?php else: ?>
    <div class="main-container">
        <div class="sidebar text-white p-3 d-flex flex-column">
            <h4 class="mb-4">CMS Admin</h4>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item"><a href="admin.php?section=pages" class="nav-link text-white <?= $section === 'pages' ? 'active' : '' ?>"><i class="bi bi-file-earmark-text me-2"></i> Pages</a></li>
                <li class="nav-item"><a href="admin.php?section=categories" class="nav-link text-white <?= $section === 'categories' ? 'active' : '' ?>"><i class="bi bi-tag me-2"></i> Categories</a></li>
                <li class="nav-item"><a href="admin.php?section=types" class="nav-link text-white <?= $section === 'types' ? 'active' : '' ?>"><i class="bi bi-bookmark me-2"></i> Types</a></li>
            </ul>
            <hr>
            <a href="?logout=true" class="btn btn-danger">Logout</a>
        </div>

        <div class="content">
            <?php
            if(isset($_SESSION['success_message'])) {
                echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
                unset($_SESSION['success_message']);
            }
            if(isset($_SESSION['error_message'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
                unset($_SESSION['error_message']);
            }

            switch ($section) {
                case 'categories':
                    echo ($action === 'edit') ? include 'admin/category_form.php' : include 'admin/categories.php';
                    break;
                case 'types':
                    echo ($action === 'edit') ? include 'admin/type_form.php' : include 'admin/types.php';
                    break;
                case 'pages':
                default:
                    echo ($action === 'edit') ? include 'admin/page_form.php' : include 'admin/pages.php';
                    break;
            }
            ?>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
