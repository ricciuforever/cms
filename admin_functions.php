<?php
function handleImageUpload($file, $uploadDir = '/img/') {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Errore durante il caricamento del file. Codice: ' . $file['error']];
    }

    // Use __DIR__ to make the path portable.
    $targetDirectory = __DIR__ . $uploadDir;
    $fileName = preg_replace("/[^a-zA-Z0-9-_\.]/", "", basename($file['name']));
    $targetPath = $targetDirectory . time() . '-' . $fileName;
    $publicUrl = $uploadDir . time() . '-' . $fileName;

    if (!is_writable($targetDirectory)) {
        return ['success' => false, 'error' => 'La cartella di destinazione non è scrivibile.'];
    }

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ['success' => true, 'path' => $publicUrl];
    } else {
        return ['success' => false, 'error' => 'Errore sconosciuto durante lo spostamento del file.'];
    }
}
?>
