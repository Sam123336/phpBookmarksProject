<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to the specified login form URL
    header('Location: /project/views/login_form.php');
    exit(); // It's a good practice to call exit after header
} else {
    // If the user is logged in, redirect to bookmarks
    header('Location: /project/views/bookmarks_list.php');
    exit(); // Also call exit here
}
?>
