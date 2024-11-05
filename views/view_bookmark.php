<?php
// Include database config
include('../php/config.php');

if (isset($_GET['id'])) {
    $bookmark_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT title, url, description, tags FROM bookmarks WHERE id = ?");
    $stmt->execute([$bookmark_id]);
    $bookmark = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($bookmark) {
        echo "<h1>" . htmlspecialchars($bookmark['title']) . "</h1>";
        echo "<a href='" . htmlspecialchars($bookmark['url']) . "' target='_blank'>Visit Link</a><br>";
        echo "<p>Description: " . htmlspecialchars($bookmark['description']) . "</p>";
        echo "<p>Tags: " . htmlspecialchars($bookmark['tags']) . "</p>";
    } else {
        echo "Bookmark not found.";
    }
} else {
    echo "No bookmark specified.";
}
?>
