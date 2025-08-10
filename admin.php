<?php
session_start();
require_once 'config.php'; // Carica la configurazione (es. hash password)
require_once 'admin_functions.php';

//error_reporting(0);
$file_pagine = 'pagine.json';
$base_path = __DIR__;

// Funzione per mostrare un messaggio di errore e fermare lo script
function fail_gracefully($message) {
    $_SESSION['error_message'] = $message;
    header('Location: admin.php');
    exit();
}

// Logica di login e logout
if (isset($_POST['password'])) {
    // Usa password_verify per un confronto sicuro dell'hash
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


if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    // Il form di login verrà mostrato più avanti nell'HTML
} else {
    // Logica di upload immagini (invariata)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['new_image'])) {
        $uploadResult = handleImageUpload($_FILES['new_image']);
        if (isset($uploadResult['error'])) {
            $_SESSION['upload_error'] = $uploadResult['error'];
        } else {
            $_SESSION['upload_success'] = $uploadResult['success'];
            $_SESSION['image_path'] = $uploadResult['path'];
        }
        header('Location: ' . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']);
        exit();
    }

    if (!is_writable($file_pagine)) {
         $_SESSION['error_message'] = "<strong>Errore di permessi!</strong> Il file 'pagine.json' non è scrivibile. Applica i permessi 664 o 666 al file.";
    }
    clearstatcache();
    $pagine = json_decode(file_get_contents($file_pagine), true);
    if ($pagine === null) {
        $pagine = [];
    }

    // Logica per salvare/modificare una pagina
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
        $is_editing = isset($_POST['original_url']) && !empty($_POST['original_url']);

        if ($is_editing) {
            $url = $_POST['original_url'];
            $timestamp = $pagine[$url]['created_at'] ?? time();
            unset($pagine[$url]);
        } else {
            $safe_title = strtolower(trim($_POST['title']));
            $safe_title = preg_replace('/[^a-z0-9-]+/', '-', $safe_title);
            $safe_title = trim($safe_title, '-');

            $parent_url = rtrim($_POST['parent'], '/');
            $url = $parent_url . '/' . $safe_title;
            if ($url === '') $url = '/'; // Gestisce il caso della homepage
            $timestamp = time();
        }

        $pagine[$url] = [
            'title'        => $_POST['title'],
            'tipo'         => $_POST['tipo'],
            'url'          => $url,
            'description'  => $_POST['description'],
            'robots'       => $_POST['robots'],
            'keywords'     => $_POST['keywords'],
            'html_content' => $_POST['page_content'] ?? '', // Contenuto direttamente nel JSON
            'parent'       => $_POST['parent'],
            'image'        => $_POST['image'],
            'created_at'   => $timestamp
        ];

        ksort($pagine);
        $json_data = json_encode($pagine, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        if (file_put_contents($file_pagine, $json_data) === false) {
            fail_gracefully("Impossibile salvare il file 'pagine.json'. Controlla i permessi.");
        }

        $_SESSION['success_message'] = "Pagina '" . htmlspecialchars($_POST['title']) . "' salvata con successo!";
        header('Location: admin.php');
        exit();
    }

    // Logica per eliminare una pagina
    if (isset($_GET['delete']) && isset($pagine[$_GET['delete']])) {
        unset($pagine[$_GET['delete']]);
        $json_data = json_encode($pagine, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
         if (file_put_contents($file_pagine, $json_data) === false) {
            fail_gracefully("Impossibile salvare 'pagine.json' dopo l'eliminazione.");
        }

        $_SESSION['success_message'] = "Pagina eliminata con successo!";
        header('Location: admin.php');
        exit();
    }

    // Prepara i dati per il form di modifica
    $pagina_da_modificare = null;
    $contenuto_pagina = '';
    if (isset($_GET['edit']) && isset($pagine[$_GET['edit']])) {
        $pagina_da_modificare = $pagine[$_GET['edit']];
        $contenuto_pagina = $pagina_da_modificare['html_content'] ?? '';
    }

    $parent_pages = [];
    foreach ($pagine as $page_url => $data) {
        if (empty($data['parent'])) {
            $parent_pages[$page_url] = $data;
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
    <script src="https://cdn.ckeditor.com/4.16.2/full/ckeditor.js"></script>
    <style>
        body { background-color: #f8f9fa; }
        .form-control, .form-select { margin-bottom: 10px; }
        #image_upload_button { cursor: pointer; }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true): ?>
    <div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center">Login Amministrazione</h3>
                        <form method="post">
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <?php if (isset($error)): ?>
                                <div class="alert alert-danger"><?= $error ?></div>
                            <?php endif; ?>
                            <button type="submit" class="btn btn-primary w-100">Entra</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin.php">Admin CMS</a>
            <a href="?logout=true" class="btn btn-outline-light">Logout</a>
        </div>
    </nav>

    <div class="container mt-4">

        <?php
        if(isset($_SESSION['success_message'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
            unset($_SESSION['success_message']);
        }
        if(isset($_SESSION['error_message'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error_message'] . '</div>';
            unset($_SESSION['error_message']);
        }
        ?>

        <div class="card mb-4">
            <div class="card-header">
                <h3><?= $pagina_da_modificare ? 'Modifica Pagina' : 'Aggiungi Nuova Pagina' ?></h3>
            </div>
            <div class="card-body">
                <form method="post" >
                    <?php if ($pagina_da_modificare): ?>
                        <input type="hidden" name="original_url" value="<?= htmlspecialchars($pagina_da_modificare['url']) ?>">
                    <?php endif; ?>

                    <input type="text" class="form-control" name="title" placeholder="Titolo (da qui si genererà l'URL)" value="<?= htmlspecialchars($pagina_da_modificare['title'] ?? '') ?>" required>
                    <textarea class="form-control" name="description" placeholder="Description (per SEO)"><?= htmlspecialchars($pagina_da_modificare['description'] ?? '') ?></textarea>

                    <div class="row">
                        <div class="col-md-6"><input type="text" class="form-control" name="keywords" placeholder="Keywords (per SEO)" value="<?= htmlspecialchars($pagina_da_modificare['keywords'] ?? '') ?>"></div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <input type="text" class="form-control" name="image" id="image_url_field" placeholder="URL Immagine Anteprima" value="<?= htmlspecialchars($pagina_da_modificare['image'] ?? '') ?>">
                                <label for="image_upload_field" class="btn btn-secondary" id="image_upload_button">Carica...</label>
                                <input type="file" id="image_upload_field" style="display: none;">
                            </div>
                            <div id="upload_status" class="form-text"></div>
                        </div>
                    </div>

                     <div class="row">
                        <div class="col-md-4">
                            <select name="parent" class="form-select">
                                <option value="">-- Nessun Genitore (Categoria Principale) --</option>
                                <?php foreach ($parent_pages as $parent_url => $parent_data): ?>
                                    <option value="<?= htmlspecialchars($parent_url) ?>" <?= (($pagina_da_modificare['parent'] ?? '') == $parent_url) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($parent_data['title']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="tipo" class="form-select" required>
                                <option value="">-- Seleziona Tipo --</option>
                                <option value="generico" <?= (($pagina_da_modificare['tipo'] ?? '') == 'generico') ? 'selected' : '' ?>>Generico</option>
                                <option value="mature" <?= (($pagina_da_modificare['tipo'] ?? '') == 'mature') ? 'selected' : '' ?>>Mature</option>
                                <option value="gay" <?= (($pagina_da_modificare['tipo'] ?? '') == 'gay') ? 'selected' : '' ?>>Gay</option>
                                <option value="trans" <?= (($pagina_da_modificare['tipo'] ?? '') == 'trans') ? 'selected' : '' ?>>Trans</option>
                                <option value="padrone" <?= (($pagina_da_modificare['tipo'] ?? '') == 'padrone') ? 'selected' : '' ?>>Padrone</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                             <select name="robots" class="form-select" required>
                                <option value="index,follow" <?= (($pagina_da_modificare['robots'] ?? '') == 'index,follow') ? 'selected' : '' ?>>Index, Follow</option>
                                <option value="noindex,follow" <?= (($pagina_da_modificare['robots'] ?? '') == 'noindex,follow') ? 'selected' : '' ?>>Noindex, Follow</option>
                            </select>
                        </div>
                    </div>

                    <h5 class="mt-3">Contenuto Pagina</h5>
                    <textarea name="page_content" id="page_content_editor" rows="15"><?= htmlspecialchars($contenuto_pagina) ?></textarea>

                    <button type="submit" class="btn btn-primary mt-3"><?= $pagina_da_modificare ? 'Salva Modifiche' : 'Crea Pagina' ?></button>
                    <?php if ($pagina_da_modificare): ?>
                        <a href="admin.php" class="btn btn-secondary mt-3">Annulla</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="card">
             <div class="card-header">
                <h3>Elenco Pagine</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-sm">
                        <thead>
                            <tr>
                                <th>URL</th>
                                <th>Titolo</th>
                                <th class="text-end">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pagine)): foreach ($pagine as $url => $dati): ?>
                            <tr>
                                <td><a href="<?= htmlspecialchars($dati['url']) ?>" target="_blank"><?= htmlspecialchars($dati['url']) ?></a></td>
                                <td><?= htmlspecialchars($dati['title']) ?></td>
                                <td class="text-end">
                                    <a href="?edit=<?= urlencode($url) ?>" class="btn btn-sm btn-warning">Modifica</a>
                                    <a href="?delete=<?= urlencode($url) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Sei sicuro di voler eliminare questa pagina?');">Elimina</a>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                                <tr><td colspan="3">Nessuna pagina trovata.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    CKEDITOR.replace('page_content_editor');

    // SCRIPT PER UPLOAD AUTOMATICO (invariato)
    document.getElementById('image_upload_field').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file) {
            return;
        }

        const formData = new FormData();
        formData.append('image_file', file);

        const statusDiv = document.getElementById('upload_status');
        statusDiv.textContent = 'Caricamento...';
        statusDiv.className = 'form-text text-primary';

        fetch('ajax_upload.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('image_url_field').value = data.path;
                statusDiv.textContent = 'Caricamento completato!';
                statusDiv.className = 'form-text text-success';
            } else {
                statusDiv.textContent = 'Errore: ' + data.error;
                statusDiv.className = 'form-text text-danger';
            }
        })
        .catch(error => {
            statusDiv.textContent = 'Errore di rete o del server.';
            statusDiv.className = 'form-text text-danger';
        });
    });
</script>
</body>
</html>
