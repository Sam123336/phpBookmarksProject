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
$bookmarks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if any bookmarks were retrieved
if (!$bookmarks) {
    $noBookmarksMessage = "No bookmarks available! Please add some to get started.";
}

// Include the view for displaying bookmarks
include('../views/bookmarks_list.php');  // Ensure this path is correct
?>
