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
        $file_size = $_FILES['file_upload']['size'];
        $file_type = $_FILES['file_upload']['type'];

        // Specify the upload directory
        $upload_dir = '../upload/'; // Ensure this path is correct based on your structure
        $file_path = $upload_dir . basename($file_name);

        // Move the file to the specified directory
        if (move_uploaded_file($file_tmp_path, $file_path)) {
            // File upload successful
        } else {
            echo "Error uploading file.";
            exit;
        }
    }

    // Insert bookmark into the database
    $stmt = $pdo->prepare("INSERT INTO bookmarks (user_id, title, url, description, folder, tags, created_at, file_path) VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)");
    $stmt->execute([$user_id, $title, $url, $description, $folder, $tags, $file_path]);

    header('Location: /project/views/bookmarks_list.php');
    exit;
}
?>
