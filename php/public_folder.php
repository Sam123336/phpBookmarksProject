<?php
include('config.php');
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /project/views/login_form.php');
    exit;
}

// Get the folder name and mark it as public
if (isset($_POST['folder_name'])) {
    $folderName = $_POST['folder_name'];
    $userId = $_SESSION['user_id'];

    // Update the folder to set it as public
    $stmt = $pdo->prepare("UPDATE folders SET public = 1 WHERE name = ? AND user_id = ?");
    $stmt->execute([$folderName, $userId]);

    // Redirect back to the bookmarks list
    header('Location: /project/views/bookmarks_list.php');
    exit;
}
?>
