<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login_form.php'); // Redirect if user isn't logged in
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $search_term = $_GET['search'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT * FROM bookmarks WHERE user_id = ? AND (title LIKE ? OR tags LIKE ?)");
    $stmt->execute([$user_id, "%$search_term%", "%$search_term%"]);
    $bookmarks = $stmt->fetchAll();

    include('../views/bookmarks_list.php'); // Correct relative path for bookmarks list
}
?>
