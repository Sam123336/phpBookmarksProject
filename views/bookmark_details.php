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
    <!-- <link rel="stylesheet" href="../output.css"> -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Additional styling */
        body {
            background-color: #1f1f2e;
            color: #e5e5e5;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .content {
            background-color: #333;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            padding: 20px;
            max-width: 600px;
            width: 90%;
            text-align: center;
        }
        h2 {
            color: #ff8a5c;
            font-size: 24px;
            margin-bottom: 15px;
        }
        .bookmark-description, p {
            font-size: 16px;
            margin: 8px 0;
        }
        a {
            color: #ffb37e;
            text-decoration: none;
        }
        a:hover {
            color: #ffd4a3;
        }
        .bookmark-file {
            color: #ff8a5c;
            font-weight: bold;
        }
        .bookmark-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="content">
        <h2><?php echo htmlspecialchars($bookmark['title']); ?></h2>
        <p><strong>URL:</strong> <a href="<?php echo htmlspecialchars($bookmark['url']); ?>" target="_blank"><?php echo htmlspecialchars($bookmark['url']); ?></a></p>
        <p class="bookmark-description"><?php echo htmlspecialchars($bookmark['description']); ?></p>
        <p><strong>Tags:</strong> <?php echo htmlspecialchars($bookmark['tags']); ?></p>
        
        <?php if (!empty($bookmark['file_path'])): ?>
            <p>File: <a href="/project/uploads/<?php echo htmlspecialchars($bookmark['file_path']); ?>" target="_blank" class="bookmark-file"><?php echo htmlspecialchars($bookmark['file_path']); ?></a></p>
            <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $bookmark['file_path'])): ?>
                <img src="/project/uploads/<?php echo htmlspecialchars($bookmark['file_path']); ?>" alt="<?php echo htmlspecialchars($bookmark['title']); ?>" class="bookmark-image">
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>
