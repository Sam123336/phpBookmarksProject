<?php
// Include database configuration, session, and navbar
include('../php/config.php');
session_start();


$stmt = $pdo->prepare("
 SELECT f.name AS folder_name, f.id AS folder_id, b.id AS bookmark_id, b.title, b.url, b.description, b.tags, b.file_path, u.username AS creator_name
FROM folders f
LEFT JOIN bookmarks b ON f.id = b.folder_id
LEFT JOIN users u ON f.user_id = u.id
WHERE f.public = 1
ORDER BY f.name;

");
$stmt->execute();
$publicFolders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group bookmarks by folder for easy display
$foldersGrouped = [];
foreach ($publicFolders as $row) {
    $folderId = $row['folder_id'];
    if (!isset($foldersGrouped[$folderId])) {
        $foldersGrouped[$folderId] = [
            'folder_name' => $row['folder_name'],
            'creator_name' => $row['creator_name'],
            'bookmarks' => []
        ];
    }
    if ($row['bookmark_id']) {
        $foldersGrouped[$folderId]['bookmarks'][] = [
            'title' => $row['title'],
            'url' => $row['url'],
            'description' => $row['description'],
            'tags' => $row['tags'],
            'file_path' => $row['file_path']
        ];
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Folders Dashboard</title>
    <link rel="stylesheet" href="/project/css/styles.css">
    <link rel="stylesheet" href="/project/css/dashboard.css">
</head>
<body>
<style>
    /* General Styling */
    body {
        background-color: #333;
        color: #e5e5e5;
        font-family: Arial, sans-serif;
        margin: 0;
    }

    /* Navbar */
    .navbar {
        background-color: #333;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
    }
    .navbar a {
        color: #ff8a5c;
        text-decoration: none;
        padding: 10px 20px;
        font-weight: bold;
        transition: color 0.3s;
    }
    .navbar a:hover {
        color: #ffb37e;
    }

    /* Container */
    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        color: #ff8a5c;
    }

    /* Title */
    .dashboard-title {
        text-align: center;
        font-size: 2.5em;
        color: #ffb37e;
        margin-bottom: 30px;
    }

    /* Folder Cards */
    .folders {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    .folder-card {
        background-color: #444;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        color: #ffb37e;
        cursor: pointer;
        transition: transform 0.2s;
    }
    .folder-card:hover {
        transform: scale(1.02);
    }
    .folder-name {
        font-size: 1.8em;
        color: #ffb37e;
        margin-bottom: 15px;
        text-align: center;
    }

    .bookmark-list {
        list-style-type: none;
        padding: 0;
        margin: 0;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
    }
    .bookmark-list.open {
        padding-top: 10px;
        padding-bottom: 10px;
        max-height: 500px;
    }

    .bookmark-item {
        background-color: #555;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }
    .bookmark-title {
        font-size: 1.2em;
        color: #ffb37e;
        margin-bottom: 5px;
    }
    .bookmark-url {
        color: #ff8a5c;
        text-decoration: none;
    }
    .bookmark-url:hover {
        text-decoration: underline;
    }
    .bookmark-description {
        font-size: 0.9em;
        color: #ddd;
        margin: 5px 0;
    }
    .bookmark-file {
        display: inline-block;
        margin-top: 10px;
        font-size: 0.9em;
        color: #ff8a5c;
        text-decoration: none;
    }
    .bookmark-file:hover {
        text-decoration: underline;
    }
    .bookmark-tags {
        font-size: 0.85em;
        color: #ccc;
        margin-top: 10px;
    }

    /* No bookmarks and public folders message */
    .no-bookmarks, .no-public-folders {
        color: #999;
        font-style: italic;
        text-align: center;
        padding: 20px;
    }
       /* Search bar styling */
       .search-container {
        text-align: center;
        margin-bottom: 20px;
    }
    .search-bar {
        width: 100%;
        max-width: 400px;
        padding: 10px;
        font-size: 1em;
        border-radius: 5px;
        border: 1px solid #555;
        background-color: #222;
        color: #ff8a5c;
    }
    .creator-name {
        font-size: 0.9em;
        color: #ccc;
        margin-top: 5px;
        text-align: center;
    }
    .add-comment form {
    margin-top: 20px;
}
.add-comment textarea {
    width: 100%;
    padding: 10px;
    background-color: #444;
    color: #ff8a5c;
    border: 1px solid #555;
    border-radius: 5px;
}
.add-comment button {
    margin-top: 10px;
    background-color: #ff8a5c;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}
.add-comment button:hover {
    background-color: #ffb37e;
}
.icon-small {
        width: 16px; /* Adjust the width to your preference */
        height: 16px; /* Adjust the height to your preference */
    }
    .share-link-notification {
    background-color: #444;
    color: #ff8a5c;
    padding: 10px;
    margin: 10px 0;
    text-align: center;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}
.share-link-notification a {
    color: #ffb37e;
    text-decoration: underline;
}
.share-link-notification a:hover {
    color: #ff8a5c;
}
.button {
        background-color: : #555555;
        color: white;
        padding: 10px 20px;
        border: 1px solid #ff8a5c;
        border-radius: 5px;
        font-size: 1em;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .button:hover {
        background-color: #707070;
    
        transform: scale(1.05);
    }

    .button:focus {
        outline: none;
        box-shadow: 0 0 5px rgba(255, 138, 92, 0.7);
    }
</style>



<script>
function toggleBookmarks(folderId) {
    const bookmarksList = document.getElementById(`bookmarks-${folderId}`);
    if (bookmarksList.classList.contains("open")) {
        bookmarksList.classList.remove("open");
        bookmarksList.style.maxHeight = '0';
    } else {
        document.querySelectorAll('.bookmark-list').forEach(list => {
            list.classList.remove("open");
            list.style.maxHeight = '0';
        });
        bookmarksList.classList.add("open");
        bookmarksList.style.maxHeight = bookmarksList.scrollHeight + 'px';
    }
}


function filterFoldersAndBookmarks() {
    const query = document.getElementById('search-input').value.toLowerCase();
    document.querySelectorAll('.folder-card').forEach(folder => {
        const folderName = folder.querySelector('.folder-name').textContent.toLowerCase();
        let folderMatches = folderName.includes(query);

        // Check if any bookmarks in this folder match
        const bookmarks = folder.querySelectorAll('.bookmark-item');
        let bookmarkMatches = false;
        bookmarks.forEach(bookmark => {
            const title = bookmark.querySelector('.bookmark-title').textContent.toLowerCase();
            const description = bookmark.querySelector('.bookmark-description').textContent.toLowerCase();
            const tags = bookmark.querySelector('.bookmark-tags').textContent.toLowerCase();
            if (title.includes(query) || description.includes(query) || tags.includes(query)) {
                bookmark.style.display = 'block';
                bookmarkMatches = true;
            } else {
                bookmark.style.display = 'none';
            }
        });

        // Show/hide the folder based on matches
        folder.style.display = folderMatches || bookmarkMatches ? 'block' : 'none';
    });
}
function toggleComments(folderId) {
    const commentsSection = document.getElementById(`comments-${folderId}`);
    if (commentsSection.style.display === "none") {
        commentsSection.style.display = "block";
    } else {
        commentsSection.style.display = "none";
    }
}
</script>

<!-- Navbar -->
<?php include('../views/navbar.php'); ?>
<?php
if (isset($_SESSION['share_link'])) {
    $shareLink = $_SESSION['share_link'];
    echo "<div class='share-link-notification'>Share link created: <a href='$shareLink' target='_blank'>$shareLink</a></div>";
    unset($_SESSION['share_link']); // Clear the share link after displaying
}
?>

<!-- Page Content -->
<div class="container">
    <h1 class="dashboard-title">Public Folders</h1>
      <!-- Search Bar -->
      <div class="search-container">
        <input type="text" id="search-input" class="search-bar" placeholder="Search folders or bookmarks..." oninput="filterFoldersAndBookmarks()">
    </div>

    <?php if (!empty($foldersGrouped)): ?>
        <div class="folders">
            <?php foreach ($foldersGrouped as $folderId => $folder): ?>
                <div class="folder-card" onclick="toggleBookmarks(<?php echo $folderId; ?>)" tabindex="0" aria-expanded="false">
                    <h2 class="folder-name"><?php echo htmlspecialchars($folder['folder_name']); ?></h2>
                    
                    <p class="creator-name">Created by: <?php echo htmlspecialchars($folder['creator_name']); ?></p>


        <!-- Export Button -->
        <form action="/project/php/export_folder.php" method="post" style="display:inline;">
            <input type="hidden" name="folder_name" value="<?php echo htmlspecialchars($folder['folder_name']); ?>">
            <button type="submit" class="button">
                <img src="/project/images/export.png" alt="Export" class="icon-small">
            </button>
        </form>
                <!-- Share Button -->
<form action="/project/php/generate_share_link.php" method="post" style="display:inline;">
    <input type="hidden" name="folder_id" value="<?php echo $folderId; ?>">
    <button type="submit" class="button">
        <img src="/project/images/share.png" alt="Share" class="icon-small">
    </button>
</form>
                    <ul class="bookmark-list" id="bookmarks-<?php echo $folderId; ?>">
                        <?php if (!empty($folder['bookmarks'])): ?>
                            <?php foreach ($folder['bookmarks'] as $bookmark): ?>
                                <li class="bookmark-item">
                                    <h3 class="bookmark-title"><?php echo htmlspecialchars($bookmark['title']); ?></h3>
                                    <a href="<?php echo htmlspecialchars($bookmark['url']); ?>" target="_blank" class="bookmark-url">
                                        <?php echo htmlspecialchars($bookmark['url']); ?>
                                    </a>
                                    <p class="bookmark-description"><?php echo htmlspecialchars($bookmark['description']); ?></p>
                                    <?php if (!empty($bookmark['file_path'])): ?>
                                        <p>File: <a href="/project/uploads/<?php echo htmlspecialchars($bookmark['file_path']); ?>" target="_blank" class="bookmark-file"><?php echo htmlspecialchars($bookmark['file_path']); ?></a></p>
                                        <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $bookmark['file_path'])): ?>
                                            <img src="/project/uploads/<?php echo htmlspecialchars($bookmark['file_path']); ?>" alt="<?php echo htmlspecialchars($bookmark['title']); ?>" class="mt-2" style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px;">
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <p class="bookmark-tags"><strong>Tags:</strong> <?php echo htmlspecialchars($bookmark['tags']); ?></p>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="no-bookmarks">No bookmarks in this folder.</p>
                        <?php endif; ?>
                    </ul>
                    <h3 class="comments-toggle" onclick="toggleComments(<?php echo $folderId; ?>)"><img src="/project/images/comment.png" alt="" class="icon-small"></h3>
                    <div class="comments-section" id="comments-<?php echo $folderId; ?>" style="display: none;">
    <div class="existing-comments">
        <?php
        $stmt_comments = $pdo->prepare("SELECT c.comment_text, u.username 
                                        FROM comments c
                                        JOIN users u ON c.user_id = u.id
                                        WHERE c.folder_id = :folder_id
                                        ORDER BY c.created_at DESC");
        $stmt_comments->execute(['folder_id' => $folderId]);
        $comments = $stmt_comments->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($comments)):
            foreach ($comments as $comment): ?>
                <p><strong><?php echo htmlspecialchars($comment['username']); ?>:</strong> <?php echo htmlspecialchars($comment['comment_text']); ?></p>
            <?php endforeach;
        else: ?>
            <p>No comments yet. Be the first to comment!</p>
        <?php endif; ?>
    </div>

    <!-- Comment Form -->
    <div class="add-comment">
    <form action="../php/add_comment.php" method="POST">
        <input type="hidden" name="folder_id" value="<?php echo $folderId; ?>">
        <textarea name="comment_text" rows="2" placeholder="Add a comment..." required></textarea>
        <button type="submit">Post Comment</button>
    </form>
</div>
        </div>
        
</div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="no-public-folders">No public folders available.</p>
    <?php endif; ?>
</div>
</body>
</html> 