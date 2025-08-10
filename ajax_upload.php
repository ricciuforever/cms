<?php
session_start();
require_once 'admin_functions.php';

// Sicurezza: Controlla se l'utente è loggato
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Accesso non autorizzato.']);
    exit();
}

// Controlla se è stato inviato un file
if (isset($_FILES['image_file'])) {
    $uploadResult = handleImageUpload($_FILES['image_file']);
    header('Content-Type: application/json');
    echo json_encode($uploadResult);
} else {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Nessun file ricevuto.']);
}
?>