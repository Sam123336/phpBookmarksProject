<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /project/views/login_form.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bookmark_id = $_POST['id'];
    $user_id = $_SESSION['user_id'];

    // Delete the bookmark from the database
    $stmt = $pdo->prepare("DELETE FROM bookmarks WHERE id = ? AND user_id = ?");
    $stmt->execute([$bookmark_id, $user_id]);

    header('Location: /project/views/bookmarks_list.php');
    exit;
}
?>
