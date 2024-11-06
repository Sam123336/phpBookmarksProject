<?php
// Include your config file for database connection
include('../php/config.php');
session_start();

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /project/views/login_form.php');
    exit;
}

// Get the bookmark ID from the URL
$bookmark_id = $_GET['id'];

// Update the last accessed timestamp for this bookmark
$updateTimestampStmt = $pdo->prepare("UPDATE bookmarks SET last_accessed = NOW() WHERE id = ?");
$updateTimestampStmt->execute([$bookmark_id]);

// Fetch bookmark details from the database
$stmt = $pdo->prepare("SELECT title, url, description, tags, file_path FROM bookmarks WHERE id = ? AND user_id = ?");
$stmt->execute([$bookmark_id, $_SESSION['user_id']]);
$bookmark = $stmt->fetch(PDO::FETCH_ASSOC);

// If no bookmark is found, redirect or show an error
if (!$bookmark) {
    echo "Bookmark not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($bookmark['title']); ?> - Details</title>
    <link rel="stylesheet" href="../output.css">
</head>
<body>
    <h2><?php echo htmlspecialchars($bookmark['title']); ?></h2>
    <p><strong>URL:</strong> <a href="<?php echo htmlspecialchars($bookmark['url']); ?>" target="_blank"><?php echo htmlspecialchars($bookmark['url']); ?></a></p>
    <p><strong>Description:</strong> <?php echo htmlspecialchars($bookmark['description']); ?></p>
    <p><strong>Tags:</strong> <?php echo htmlspecialchars($bookmark['tags']); ?></p>
    <?php if ($bookmark['file_path']): ?>
        <p><strong>File:</strong> <a href="<?php echo htmlspecialchars($bookmark['file_path']); ?>" download>Download</a></p>
    <?php endif; ?>
</body>
</html>
