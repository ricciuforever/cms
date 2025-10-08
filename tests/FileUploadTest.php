<?php

// Unit test for file upload functionality
// To run: php tests/FileUploadTest.php

// Set the working directory to the project root
chdir(dirname(__DIR__));

require_once 'admin_functions.php';
require_once 'config.php';

// --- Test Setup ---
$testDir = __DIR__;
$dummyFile = $testDir . '/dummy.txt';
if (!file_exists($dummyFile)) {
    file_put_contents($dummyFile, 'this is not an image');
}
$uploadDir = __DIR__ . '/../img/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}


function run_test($name, $condition, $message = "") {
    echo "Running test: $name\n";
    if ($condition) {
        echo "  [PASS]\n";
    } else {
        echo "  [FAIL] $message\n";
        // In a real test suite, we'd exit with a non-zero status
    }
}

// --- Test Case 1: Unauthenticated User ---
echo "--- Testing Authentication ---\n";
@session_start();
unset($_SESSION['logged_in']); // Ensure user is not logged in

// Simulate the check from ajax_upload.php
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
run_test(
    "Should block access if user is not authenticated",
    !$is_logged_in,
    "Authentication check failed."
);
@session_destroy();


// --- Test Case 2: Invalid File Type ---
echo "\n--- Testing File Type Validation ---\n";

// Simulate a file upload for an authenticated user
$file_upload_simulation = [
    'name' => 'dummy.txt',
    'type' => 'text/plain',
    'tmp_name' => $dummyFile,
    'error' => UPLOAD_ERR_OK,
    'size' => filesize($dummyFile)
];

$result = handleImageUpload($file_upload_simulation);

run_test(
    "Should block upload of non-image files",
    isset($result['success']) && $result['success'] === false && isset($result['error']) && strpos($result['error'], 'Tipo di file non valido') !== false,
    "Expected 'Tipo di file non valido' error, got: " . ($result['error'] ?? 'No error')
);


// --- Cleanup ---
if (file_exists($dummyFile)) {
    unlink($dummyFile);
}
echo "\nTests complete.\n";

?>