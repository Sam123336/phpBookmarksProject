<?php
include('config.php');
session_start();

// Redirect to login if user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header('Location: /project/views/login_form.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $title = $_POST['title'];
    $url = $_POST['url'];
    $description = $_POST['description'];
    $folder = trim($_POST['folder']);
    $tags = $_POST['tags'];
    $user_id = $_SESSION['user_id'];

    // Initialize file path as null
    $file_path = null;

    // Handle file upload if present
    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['file_upload']['tmp_name'];
        $file_name = $_FILES['file_upload']['name'];
        $upload_dir = '../upload/';
        
        // Generate a unique name to prevent file overwriting
        $unique_name = uniqid() . '_' . basename($file_name);
        $file_path = $upload_dir . $unique_name;

        // Move the file to the upload directory
        if (!move_uploaded_file($file_tmp_path, $file_path)) {
            echo "Error uploading file.";
            exit;
        }
    }

    // Check if the folder already exists for this user
    $stmt = $pdo->prepare("SELECT id FROM folders WHERE user_id = ? AND name = ?");
    $stmt->execute([$user_id, $folder]);
    $folder_id = $stmt->fetchColumn();

    // If the folder does not exist, create it
    if (!$folder_id) {
        $stmt = $pdo->prepare("INSERT INTO folders (user_id, name) VALUES (?, ?)");
        $stmt->execute([$user_id, $folder]);
        $folder_id = $pdo->lastInsertId();
    }

    // Insert the bookmark into the database
    $stmt = $pdo->prepare("INSERT INTO bookmarks (user_id, folder_id, title, url, description, tags, created_at, file_path) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)");
    $stmt->execute([$user_id, $folder_id, $title, $url, $description, $tags, $file_path]);

    // Redirect to the bookmarks list after successful insertion
    header('Location: /project/views/bookmarks_list.php');
    exit;
}
?>
