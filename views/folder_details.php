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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folder: <?php echo htmlspecialchars($folderName); ?></title>
    <link rel="stylesheet" href="../output.css"> <!-- Link to main CSS file -->
    <style>
        body {
            background-color: #1f1f2e;
            color: #e5e5e5;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .main-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        h2 {
            color: #ff8a5c;
            font-size: 28px;
            margin-bottom: 20px;
            text-align: center;
        }

        .bookmark-item {
            background-color: #333;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
        }

        .bookmark-item strong {
            font-size: 18px;
            color: #ff8a5c;
        }

        .bookmark-item a {
            color: #ffb37e;
            text-decoration: none;
            display: inline-block;
            margin-top: 5px;
        }

        .bookmark-item a:hover {
            color: #ffd4a3;
        }

        .bookmark-item p {
            color: #e5e5e5;
            margin: 5px 0;
        }

        .bookmark-item p strong {
            color: #c3c3c3;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <h2><?php echo htmlspecialchars($folderName); ?> - Bookmarks</h2>
        <?php foreach ($bookmarks as $bookmark): ?>
            <div class="bookmark-item">
                <strong><?php echo htmlspecialchars($bookmark['title']); ?></strong><br>
                <a href="<?php echo htmlspecialchars($bookmark['url']); ?>" target="_blank"><?php echo htmlspecialchars($bookmark['url']); ?></a><br>
                <p><?php echo htmlspecialchars($bookmark['description']); ?></p>
                <p><strong>Tags:</strong> <?php echo htmlspecialchars($bookmark['tags']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
