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
} else {
    $folderData = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shared Folder</title>
    <link rel="stylesheet" href="../output.css"> <!-- Link to the dashboard CSS file -->
    <style>
        body {
            background-color: #1f1f2e;
            color: #e5e5e5;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .main-content {
            margin-left: 220px;
            padding: 20px;
            width: calc(100% - 220px);
            box-sizing: border-box;
        }

        .bookmark {
            background-color: #333;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 15px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
        }

        .bookmark h3, .bookmark p {
            color: #e5e5e5;
        }

        .bookmark a {
            color: #ff8a5c;
            text-decoration: none;
        }

        .bookmark a:hover {
            color: #ffb37e;
        }

        hr {
            border: 1px solid #444;
            margin: 20px 0;
        }

        .bookmark img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Include the navbar -->
    <?php include('../views/navbar.php'); ?>

    <div class="main-content">
        <?php if ($folderData): ?>
            <h1>Folder: <?php echo htmlspecialchars($folderData[0]['folder_name']); ?></h1>
            <p>Created by: <?php echo htmlspecialchars($folderData[0]['creator_name']); ?></p>
            
            <h2>Bookmarks:</h2>
            
            <?php foreach ($folderData as $bookmark): ?>
                <?php if ($bookmark['bookmark_id']): ?>
                    <div class="bookmark">
                        <h3><?php echo htmlspecialchars($bookmark['title']); ?></h3>
                        <p>URL: <a href="<?php echo htmlspecialchars($bookmark['url']); ?>" target="_blank"><?php echo htmlspecialchars($bookmark['url']); ?></a></p>
                        <p class="bookmark-description"><?php echo htmlspecialchars($bookmark['description']); ?></p>
                        
                        <?php if (!empty($bookmark['file_path'])): ?>
                            <p>File: <a href="/project/uploads/<?php echo htmlspecialchars($bookmark['file_path']); ?>" target="_blank" class="bookmark-file"><?php echo htmlspecialchars($bookmark['file_path']); ?></a></p>
                            
                            <!-- Image Preview for image file types -->
                            <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $bookmark['file_path'])): ?>
                                <img src="/project/uploads/<?php echo htmlspecialchars($bookmark['file_path']); ?>" alt="<?php echo htmlspecialchars($bookmark['title']); ?>">
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <hr>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Share link not found or invalid token.</p>
        <?php endif; ?>
    </div>
</body>
</html>
