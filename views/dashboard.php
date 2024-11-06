<?php
// Include database configuration, session, and navbar
include('../php/config.php');
session_start();

// Fetch public folders with their bookmarks from the database
$stmt = $pdo->prepare("
    SELECT f.name AS folder_name, f.id AS folder_id, b.id AS bookmark_id, b.title, b.url, b.description, b.tags, b.file_path
    FROM folders f
    LEFT JOIN bookmarks b ON f.id = b.folder_id
    WHERE f.public = 1
    ORDER BY f.name
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
        bookmarksList.style.maxHeight = `${bookmarksList.scrollHeight}px`;
    }
}
</script>

<!-- Navbar -->
<?php include('../views/navbar.php'); ?>

<!-- Page Content -->
<div class="container">
    <h1 class="dashboard-title">Public Folders</h1>
    <?php if (!empty($foldersGrouped)): ?>
        <div class="folders">
            <?php foreach ($foldersGrouped as $folderId => $folder): ?>
                <div class="folder-card" onclick="toggleBookmarks(<?php echo $folderId; ?>)" tabindex="0" aria-expanded="false">
                    <h2 class="folder-name"><?php echo htmlspecialchars($folder['folder_name']); ?></h2>

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
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="no-public-folders">No public folders available.</p>
    <?php endif; ?>
</div>
</body>
</html>
