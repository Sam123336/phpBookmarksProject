<?php
include('../php/config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /project/views/login_form.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch folders with bookmarks for the current user
$stmt = $pdo->prepare("
    SELECT f.name AS folder_name, b.id, b.title, b.url, b.description, b.tags, b.file_path
    FROM folders f
    LEFT JOIN bookmarks b ON f.id = b.folder_id
    WHERE f.user_id = ?
    ORDER BY f.name
");
$stmt->execute([$user_id]);
$bookmarks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group bookmarks by folder
$groupedBookmarks = [];
foreach ($bookmarks as $bookmark) {
    $folder = $bookmark['folder_name'];
    if (!isset($groupedBookmarks[$folder])) {
        $groupedBookmarks[$folder] = [];
    }
    $groupedBookmarks[$folder][] = $bookmark;
}

// Handle search
$searchResults = [];
$searchTerm = '';
$hasBookmarks = !empty($bookmarks); // Check if there are any bookmarks

if (isset($_GET['search'])) {
    $searchTerm = trim($_GET['search']);
    
    if (!empty($searchTerm)) {
        $searchStmt = $pdo->prepare("
            SELECT b.id, b.title, b.url, b.description, b.tags, b.file_path, f.name AS folder_name
            FROM bookmarks b
            LEFT JOIN folders f ON b.folder_id = f.id
            WHERE f.user_id = ? AND (b.title LIKE ? OR b.url LIKE ? OR b.description LIKE ? OR b.tags LIKE ?)
            ORDER BY f.name
        ");
        $searchWildcard = "%" . $searchTerm . "%";
        $searchStmt->execute([$user_id, $searchWildcard, $searchWildcard, $searchWildcard, $searchWildcard]);
        $searchResults = $searchStmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookmarks</title>
    <link rel="stylesheet" href="../output.css">
    <style>
        /* Custom styles for frosted glass effect */
        .frosted-glass {
            background: rgba(14, 13, 13, 0.68);
            border-radius: 16px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(1.8px);
            -webkit-backdrop-filter: blur(1.8px);
            border: 1px solid rgba(14, 13, 13, 0.06);
            position: relative;
        }

        body {
            background-color: #121212;
            color: #ffffff;
            font-family: sans-serif;
            padding: 6rem;
        }
        .delete-folder-button, .edit-bookmark-btn, .delete-bookmark-btn {
            background-color: transparent;
            border: none;
            color: #ffffff;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .delete-folder-button:hover { color: #ff4c4c; }
        .edit-bookmark-btn:hover { color: #b3b3ff; }
        .delete-bookmark-btn:hover { color: #ff4c4c; }
        
        .small-logo {
            width: 16px;
            height: 16px;
            vertical-align: middle;
            margin-right: 8px;
        }

        .folder-title {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
    </style>
    <script>
        function toggleBookmarks(folder) {
            const bookmarksList = document.getElementById(`bookmarks-${folder}`);
            const arrow = document.getElementById(`arrow-${folder}`);
            if (bookmarksList.style.display === "none") {
                bookmarksList.style.display = "block";
                arrow.textContent = "▼";
            } else {
                bookmarksList.style.display = "none";
                arrow.textContent = "►";
            }
        }
    </script>
</head>
<body>
    <h2 class="text-2xl font-semibold mb-4">Your Bookmarks</h2>
    <div class="flex space-x-4 mb-4">
        <a href="/project/php/logout.php" class="text-blue-400 hover:underline">Logout</a>
        <a href="/project/views/add_bookmark_form.php" class="text-blue-400 hover:underline">Add Bookmark</a>
    </div>

    <form action="/project/php/search.php" method="get" class="mb-6">
        <input type="text" name="search" placeholder="Search bookmarks" class="border border-gray-700 rounded px-3 py-2 w-full md:w-1/2 mb-2 md:mb-0 bg-gray-800 text-gray-200" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Search</button>
    </form>

    <?php if (!empty($groupedBookmarks)) : ?>
        <?php foreach ($groupedBookmarks as $folder => $bookmarks): ?>
            <section class="mb-6 frosted-glass p-4">
                <div class="folder-title" onclick="toggleBookmarks('<?php echo htmlspecialchars($folder, ENT_QUOTES); ?>')">
                    <span id="arrow-<?php echo htmlspecialchars($folder, ENT_QUOTES); ?>" class="mr-2">►</span>
                    <?php echo htmlspecialchars($folder); ?>
                </div>
                
                <button class="delete-folder-button" onclick="event.stopPropagation(); if(confirm('Are you sure you want to delete this folder and all its bookmarks?')) document.getElementById('delete-folder-<?php echo htmlspecialchars($folder, ENT_QUOTES); ?>').submit();">
                    <img src="/project/assets/delete-icon.svg" alt="Delete" width="16" height="16">
                </button>
                <form id="delete-folder-<?php echo htmlspecialchars($folder, ENT_QUOTES); ?>" action="/project/php/delete_folder.php" method="post" style="display: none;">
                    <input type="hidden" name="folder_name" value="<?php echo htmlspecialchars($folder); ?>">
                </form>
                
                <ul id="bookmarks-<?php echo htmlspecialchars($folder, ENT_QUOTES); ?>" class="space-y-4" style="display: none;">
                    <?php foreach ($bookmarks as $bookmark): ?>
                        <li class="p-4 bg-gray-800 rounded shadow">
                            <strong class="text-lg font-semibold">
                                <img src="https://s2.googleusercontent.com/s2/favicons?domain=<?php echo urlencode($bookmark['url']); ?>" alt="logo" class="small-logo">
                                <?php echo htmlspecialchars($bookmark['title']); ?>
                            </strong><br>
                            <a href="<?php echo htmlspecialchars($bookmark['url']); ?>" target="_blank" class="text-blue-400 hover:underline"><?php echo htmlspecialchars($bookmark['url']); ?></a><br>
                            <p class="text-gray-300"><?php echo htmlspecialchars($bookmark['description']); ?></p>
                            <small class="text-gray-500">Tags: <?php echo htmlspecialchars($bookmark['tags']); ?></small><br>

                            <?php if (!empty($bookmark['file_path'])): ?>
                                <p>File: <a href="/project/upload/<?php echo htmlspecialchars($bookmark['file_path']); ?>" target="_blank" class="text-blue-400 hover:underline"><?php echo htmlspecialchars($bookmark['file_path']); ?></a></p>
                                <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $bookmark['file_path'])): ?>
                                    <img src="/project/upload/<?php echo htmlspecialchars($bookmark['file_path']); ?>" alt="<?php echo htmlspecialchars($bookmark['title']); ?>" class="mt-2 w-24 h-24 object-cover">
                                <?php endif; ?>
                            <?php endif; ?>

                            <div class="mt-4 flex space-x-2">
                                <a href="/project/views/edit_bookmark_form.php?id=<?php echo $bookmark['id']; ?>" class="edit-bookmark-btn">Edit</a>
                                <form action="/project/php/delete_bookmark.php" method="post" onsubmit="return confirm('Are you sure you want to delete this bookmark?');">
                                    <input type="hidden" name="id" value="<?php echo $bookmark['id']; ?>">
                                    <button type="submit" class="delete-bookmark-btn">Delete</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endforeach; ?>
    <?php else : ?>
        <p class="p-4 bg-gray-800 rounded shadow">No matching folders or bookmarks found.</p>
    <?php endif; ?>

    <?php if (!empty($searchResults)): ?>
        <?php if (empty($searchResults) && $hasBookmarks): ?>
            <p class="p-4 bg-gray-800 rounded shadow">No bookmarks found matching your search!</p>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
