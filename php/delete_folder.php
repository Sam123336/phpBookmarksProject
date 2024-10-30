<?php
// Include database configuration
include('config.php');

// Start session and check if user is authenticated
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /project/views/login_form.php');
    exit;
}

// Debugging settings
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check for a valid database connection
if (!isset($pdo)) {
    die("Database connection failed.");
}

// Retrieve user and folder details
$user_id = $_SESSION['user_id'];
$folder_name = $_POST['folder_name'] ?? null;

if ($folder_name) {
    try {
        // Begin transaction for deleting folder and bookmarks
        $pdo->beginTransaction();

        // Delete all bookmarks within the specified folder for the user
        $stmt = $pdo->prepare("
            DELETE FROM bookmarks 
            WHERE folder_id IN (
                SELECT id FROM folders WHERE name = ? AND user_id = ?
            )
        ");
        $stmt->execute([$folder_name, $user_id]);

        // Delete the folder itself
        $stmt = $pdo->prepare("
            DELETE FROM folders WHERE name = ? AND user_id = ?
        ");
        $stmt->execute([$folder_name, $user_id]);

        // Commit the transaction
        $pdo->commit();

        // Redirect to bookmarks list after successful deletion
        header('Location: /project/views/bookmarks_list.php');
        exit;
        
    } catch (PDOException $e) {
        // Roll back transaction in case of error
        $pdo->rollBack();
        die("Error deleting folder: " . $e->getMessage());
    }
} else {
    die("No folder name provided.");
}
