<?php
include('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $folder_id = $_POST['folder_id'];
    $user_id = $_SESSION['user_id'];
    $comment_text = $_POST['comment_text'];

    $stmt = $pdo->prepare("INSERT INTO comments (folder_id, user_id, comment_text) VALUES (?, ?, ?)");
    $stmt->execute([$folder_id, $user_id, $comment_text]);

    header('Location: /project/views/dashboard.php');
    exit;
}
?>
