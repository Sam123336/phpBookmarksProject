<!-- <?php
// include('config.php');
// session_start();

// // Check if the user is logged in
// if (!isset($_SESSION['user_id'])) {
//     header('Location: /project/views/login_form.php');
//     exit;
// }

// // Fetch bookmarks for the logged-in user
// $user_id = $_SESSION['user_id'];
// $stmt = $pdo->prepare("SELECT * FROM bookmarks WHERE user_id = ?");
// $stmt->execute([$user_id]);

// // Initialize $bookmarks with fetched data
// $bookmarks = $stmt->fetchAll();

// // Include the bookmarks list view to display bookmarks
// include('../views/bookmarks_list.php');
// ?> -->
<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /project/views/login_form.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch bookmarks for the logged-in user
$stmt = $pdo->prepare("SELECT * FROM bookmarks WHERE user_id = ?");
$stmt->execute([$user_id]);
$bookmarks = $stmt->fetchAll();

if (!$bookmarks) {
    echo "No bookmarks retrieved!";
}

include('../views/bookmarks_list.php');  // Ensure path is correct
?>
