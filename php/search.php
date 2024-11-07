<?php
include('config.php'); // Ensure the correct path to config.php
session_start();

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /project/views/login_form.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && !empty($_GET['search'])) {
    $search_term = trim($_GET['search']); // Get and trim the search term
    $user_id = $_SESSION['user_id'];

    // Prepare and execute the search query, joining folders to include folder names in search
    $stmt = $pdo->prepare("
        SELECT bookmarks.*, folders.name AS folder_name 
        FROM bookmarks 
        LEFT JOIN folders ON bookmarks.folder_id = folders.id 
        WHERE bookmarks.user_id = ? 
        AND (bookmarks.title LIKE ? OR bookmarks.tags LIKE ? OR folders.name LIKE ?)
    ");
    $search_pattern = "%$search_term%";
    $stmt->execute([$user_id, $search_pattern, $search_pattern, $search_pattern]);
    $bookmarks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Include the bookmarks_list.php to display results
    include('../views/bookmarks_list.php');
} else {
    // Redirect back to the bookmarks page if no search term is provided
    header('Location: /project/views/bookmarks_list.php');
    exit;
}
?>
