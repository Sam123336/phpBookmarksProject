<?php
include('../php/config.php');
session_start();

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /project/views/login_form.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$folder_name = $_POST['folder_name'] ?? '';

// Fetch bookmarks for the specified folder
$stmt = $pdo->prepare("
    SELECT b.title, b.url, b.description, b.tags
    FROM bookmarks b
    JOIN folders f ON b.folder_id = f.id
    WHERE f.user_id = ? AND f.name = ?
");
$stmt->execute([$user_id, $folder_name]);
$bookmarks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Set headers for DOC file download
header("Content-type: application/vnd.ms-word");
header("Content-Disposition: attachment;Filename={$folder_name}.doc");

echo "<html>";
echo "<body>";
echo "<h1>Bookmarks for Folder: " . htmlspecialchars($folder_name) . "</h1>";
foreach ($bookmarks as $bookmark) {
    echo "<h3>" . htmlspecialchars($bookmark['title']) . "</h3>";
    echo "<p><strong>URL:</strong> <a href='" . htmlspecialchars($bookmark['url']) . "'>" . htmlspecialchars($bookmark['url']) . "</a></p>";
    echo "<p><strong>Description:</strong> " . htmlspecialchars($bookmark['description']) . "</p>";
    echo "<p><strong>Tags:</strong> " . htmlspecialchars($bookmark['tags']) . "</p>";
    echo "<hr>";
}
echo "</body>";
echo "</html>";
exit;
?>
