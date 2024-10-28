<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /project/views/login_form.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $url = $_POST['url'];
    $description = $_POST['description'];
    $folder = $_POST['folder'];
    $tags = $_POST['tags'];
    $user_id = $_SESSION['user_id'];

    // Handle file upload
    $file_path = null;
    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == UPLOAD_ERR_OK) {
        $file_tmp_path = $_FILES['file_upload']['tmp_name'];
        $file_name = $_FILES['file_upload']['name'];
        $upload_dir = '../upload/';
        $file_path = $upload_dir . basename($file_name);

        if (!move_uploaded_file($file_tmp_path, $file_path)) {
            echo "Error uploading file.";
            exit;
        }
    }

    // Check if the folder already exists for the user
    $stmt = $pdo->prepare("SELECT id FROM folders WHERE user_id = ? AND name = ?");
    $stmt->execute([$user_id, $folder]);
    $folder_id = $stmt->fetchColumn();

    if (!$folder_id) {
        $stmt = $pdo->prepare("INSERT INTO folders (user_id, name) VALUES (?, ?)");
        $stmt->execute([$user_id, $folder]);
        $folder_id = $pdo->lastInsertId();
    }

    $stmt = $pdo->prepare("INSERT INTO bookmarks (user_id, folder_id, title, url, description, tags, created_at, file_path) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)");
    $stmt->execute([$user_id, $folder_id, $title, $url, $description, $tags, $file_path]);

    header('Location: /project/views/bookmarks_list.php');
    exit;
}
?>
