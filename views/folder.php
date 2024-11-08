<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('../php/config.php');

session_start();

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Prepare the statement to retrieve folder and bookmark data based on the share token
    $stmt = $pdo->prepare("
        SELECT f.name AS folder_name, f.id AS folder_id, b.id AS bookmark_id, b.title, b.url, b.description, 
               b.tags, b.file_path, u.username AS creator_name
        FROM folders f
        LEFT JOIN bookmarks b ON f.id = b.folder_id
        LEFT JOIN users u ON f.user_id = u.id
        WHERE f.share_token = :token AND f.public = 1
        ORDER BY f.name
    ");
    $stmt->execute(['token' => $token]);
    $folderData = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Display the folder details
    if ($folderData) {
        // Display the folder name and creator (taking the first row's data)
        echo "<h1>Folder: " . htmlspecialchars($folderData[0]['folder_name']) . "</h1>";
        echo "<p>Created by: " . htmlspecialchars($folderData[0]['creator_name']) . "</p>";
        
        echo "<h2>Bookmarks:</h2>";
        
        foreach ($folderData as $bookmark) {
            if ($bookmark['bookmark_id']) { // Check if a bookmark exists
                echo "<div class='bookmark'>";
                echo "<h3>Title: " . htmlspecialchars($bookmark['title']) . "</h3>";
                echo "<p>URL: <a href='" . htmlspecialchars($bookmark['url']) . "'>" . htmlspecialchars($bookmark['url']) . "</a></p>";
                echo "<p>Description: " . htmlspecialchars($bookmark['description']) . "</p>";
                echo "<p>Tags: " . htmlspecialchars($bookmark['tags']) . "</p>";
                if ($bookmark['file_path']) {
                    echo "<p>File: <a href='" . htmlspecialchars($bookmark['file_path']) . "'>Download</a></p>";
                }
                echo "</div><hr>";
            }
        }
    } else {
        echo "<p>Share link not found or invalid token.</p>";
    }
} else {
    echo "<p>No token provided in the URL.</p>";
}
?>
