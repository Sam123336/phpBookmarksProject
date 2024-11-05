<?php
// Include config and session setup
include('../php/config.php');
session_start();

$folderName = $_GET['folder'] ?? '';
$user_id = $_SESSION['user_id'];

// Fetch bookmarks in the selected folder
$stmt = $pdo->prepare("
    SELECT title, url, description, tags, file_path 
    FROM bookmarks 
    WHERE folder_id = (SELECT id FROM folders WHERE name = ? AND user_id = ?)
");
$stmt->execute([$folderName, $user_id]);
$bookmarks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Folder: <?php echo htmlspecialchars($folderName); ?></title>
</head>
<body>
    <h2><?php echo htmlspecialchars($folderName); ?> - Bookmarks</h2>
    <?php foreach ($bookmarks as $bookmark): ?>
        <div class="bookmark-item">
            <strong><?php echo htmlspecialchars($bookmark['title']); ?></strong><br>
            <a href="<?php echo htmlspecialchars($bookmark['url']); ?>" target="_blank"><?php echo htmlspecialchars($bookmark['url']); ?></a><br>
            <p><?php echo htmlspecialchars($bookmark['description']); ?></p>
            <p><strong>Tags:</strong> <?php echo htmlspecialchars($bookmark['tags']); ?></p>
        </div>
    <?php endforeach; ?>
</body>
</html>
