<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /project/views/login_form.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bookmark_id = $_POST['id'];
    $title = $_POST['title'];
    $url = $_POST['url'];
    $description = $_POST['description'];
    $folder = $_POST['folder'];
    $tags = $_POST['tags'];
    $user_id = $_SESSION['user_id'];

    // Handle file upload if a new file is uploaded
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

    // Update the bookmark in the database
    $sql = "UPDATE bookmarks SET title = ?, url = ?, description = ?, tags = ?, file_path = IFNULL(?, file_path) WHERE id = ? AND user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$title, $url, $description, $tags, $file_path, $bookmark_id, $user_id]);

    header('Location: /project/views/bookmarks_list.php');
    exit;
}
?>
