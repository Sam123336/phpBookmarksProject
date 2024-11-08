<?php
// Include your config file for database connection
include('../php/config.php');
session_start();

// Redirect if the user is not logged in
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

if (isset($_GET['search'])) {
    $searchTerm = trim($_GET['search']);
    
    if (!empty($searchTerm)) {
        $searchStmt = $pdo->prepare("
            SELECT b.id, b.title, b.url, b.description, b.tags, b.file_path, f.name AS folder_name
            FROM bookmarks b
            LEFT JOIN folders f ON b.folder_id = f.id
            WHERE f.user_id = ? 
            AND (b.title LIKE ? OR b.url LIKE ? OR b.description LIKE ? OR b.tags LIKE ? OR f.name LIKE ?)
            ORDER BY f.name
        ");
        $searchWildcard = "%" . $searchTerm . "%";
        $searchStmt->execute([$user_id, $searchWildcard, $searchWildcard, $searchWildcard, $searchWildcard, $searchWildcard]);
        $searchResults = $searchStmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}

// Fetch recently used bookmarks


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookmarks</title>
    <link rel="stylesheet" href="../output.css">
    <style>
        /* General layout */
 /* General layout */
        /* General layout */
        body {
            display: flex;
            flex-direction: column;
            background-color: #2c2c2c;
            color: #e5e5e5;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        /* Navbar styling */
        nav {
            width: 100%;
            background-color: #333;
            padding: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
        }

        nav a {
            color: #ff8a5c;
            text-decoration: none;
            padding: 10px 20px;
            font-weight: bold;
            transition: color 0.3s;
        }

        nav a:hover {
            color: #ffb37e;
        }

        h2 {
            font-size: 24px;
            color: #fff;
            margin-bottom: 20px;
        }

        /* Sidebar */
        .sidebar {
            width: 200px;
            background-color: #333333;
            padding: 20px;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
        }


.folder-list {
    list-style: none;
    padding: 0;
}

.folder-list li {
    margin: 10px 0;
    cursor: pointer;
    padding: 10px;
    border-radius: 5px;
    color: #e5e5e5;
}

.folder-list li:hover {
    background-color: #404040;
}

.folder-list li a {
    color: #ff8a5c;
    text-decoration: none;
}

.folder-list li a:hover {
    color: #ffb37e;
}

/* Main content area */
.main-content {
    margin-left: 220px; /* Adjusted to match new sidebar width */
    padding: 20px;
    width: calc(100% - 220px);
}

/* Search and top bar */


/* Buttons and Inputs */

/* Buttons and Inputs */
.button, .delete-folder-button, .edit-bookmark-btn, .delete-bookmark-btn {
    text-decoration: none;
    background-color:#707070;/* Make background transparent */
    border: none; /* Remove border */
    color: #ffffff;
    padding: 6px 12px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
    font-size: 12px;
}

.button:hover, .delete-folder-button:hover, .edit-bookmark-btn:hover, .delete-bookmark-btn:hover {
    background-color:  #555555; /* Keep hover background transparent */
    color: #ff4a4a; /* Optionally change text color on hover */
}

.top-bar {
    display: flex;
    justify-content: center; /* Centering the content */
    align-items: center; 
    margin-bottom: 30px;
    flex-direction: column; /* Stacking items vertically */
}

.search-container {
    display: flex;
    justify-content: center; /* Centering the search elements */
    align-items: center;
    margin-top: 10px; /* Space between the heading and search */
}

.search-input {
    width: 300px; /* Set a width for the input */
    padding: 8px 12px;
    border: 1px solid #555;
    background-color: #404040;
    color: #e5e5e5;
    border-radius: 5px;
    margin-right: 10px; /* Space between input and button */
}

        /* Navbar */
       
/* Folder-style Bookmark container */
.bookmark-container {
    margin-top: 15px;
    background: #333333;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.2);
    width: calc(33.33% - 15px); /* Display 3 boxes per row with some spacing */
    margin-bottom: 10px; /* Reduced margin to save space */
    position: relative;
}

.bookmarks-wrapper {
    display: flex;
    flex-wrap: wrap;
    gap: 15px; /* Space between items */
    align-items: flex-start;
}

@media (max-width: 768px) {
    .bookmark-container {
        width: calc(50% - 10px); /* Two per row on smaller screens */
    }
}

/* Folder Tab */
.bookmark-container::before {
    content: "";
    position: absolute;
    top: -10px;
    left: 20px;
    width: 50px;
    height: 10px;
    background-color: #555;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
}

.folder-title {
    font-size: 16px; /* Slightly smaller title font */
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
}

.folder-title span {
    font-weight: bold;
    color: #ff645a;
}

/* Smaller padding and font size for bookmark items */
.bookmark-item {
    padding: 8px; /* Reduced padding */
    border-radius: 5px;
    margin: 8px 0;
    background-color: #404040;
    color: #e5e5e5;
    font-size: 12px; /* Smaller font size */
}

/* Smaller icon and spacing adjustments */
.icon {
    width: 12px;
    height: 12px;
    margin-right: 5px; /* Less space next to icon */
}

.bookmark-item a {
    color: #ff8a5c;
    text-decoration: none;
}

.bookmark-item a:hover {
    color: #ffb37e;
    text-decoration: underline;
}

.folder-content {
    display: none;
    transition: max-height 0.3s ease;
}
.icon-small {
        width: 16px; /* Adjust the width to your preference */
        height: 16px; /* Adjust the height to your preference */
    }
    </style>
    <script>
        function toggleBookmarks(folder) {
    const bookmarksList = document.getElementById(`bookmarks-${folder}`);
    const isHidden = bookmarksList.style.display === "none" || !bookmarksList.style.display;

    // Toggle display only based on click, no arrow
    bookmarksList.style.display = isHidden ? "block" : "none";
}


        // Function to redirect to the add_bookmarks.php page
        function openAddCollectionForm() {
            window.location.href = 'add_bookmark_form.php';
        }
    </script>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <a href="/project/views/bookmarks_list.php">Home</a>
        <a href="/project/views/dashboard.php">Dashboard</a>
        <a href="/project/views/about.php">About</a>
        <a href="/project/views/new.php">New</a>
    </nav>
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>Folders</h2>
        <ul class="folder-list">
            <?php foreach ($groupedBookmarks as $folder => $bookmarks): ?>
                <li onclick="toggleBookmarks('<?php echo htmlspecialchars($folder, ENT_QUOTES); ?>')">
                    <span><?php echo htmlspecialchars($folder); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Main content -->
    <div class="main-content">
        <div class="top-bar">
            <h2>
            <img height="60px" width="70px" src="/project/images/save_it.png" alt="">

            </h2>
            <div class="flex space-x-4 mb-4">
    </div>

            
    <div class="search-container">
                <form action="/project/php/search.php" method="get" style="display:inline;">
    <input type="text" name="search" value="<?php echo htmlspecialchars($searchTerm); ?>" class="search-input" placeholder="Search bookmarks">
    <button type="submit" class="button"><img src="/project/images/search.png" alt="" class="icon-small"></button>
</form>

            </div>
        </div>

        <div>
            <!-- "New Collection" button opens add_bookmarks.php -->
            <button class="button" onclick="openAddCollectionForm()"><img src="/project/images/add.png" alt="" class="icon-small"></button>
            <button class="button"><a href="/project/php/logout.php" class="text-blue-400 hover:underline"><img src="/project/images/logout.png" alt="" class="icon-small"></a></button>
        </div>

        <div class="bookmarks-wrapper">
            <?php if (!empty($searchResults)): ?>
                <h3>Search Results:</h3>
                <?php foreach ($searchResults as $bookmark): ?>
                    <div class="bookmark-item">
                        <strong>
                            <a href="/project/views/bookmark_details.php?id=<?php echo $bookmark['id']; ?>">
                                <img src="https://s2.googleusercontent.com/s2/favicons?domain=<?php echo urlencode($bookmark['url']); ?>" class="icon" alt="">
                                <?php echo htmlspecialchars($bookmark['title']); ?>
                            </a>
                        </strong><br>
                        <a href="<?php echo htmlspecialchars($bookmark['url']); ?>" target="_blank"><?php echo htmlspecialchars($bookmark['url']); ?></a><br>
                        <p><?php echo htmlspecialchars($bookmark['description']); ?></p>
                        <p><strong>Tags:</strong> <?php echo htmlspecialchars($bookmark['tags']); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php elseif (!empty($searchTerm)): ?>
                <p>No matching bookmarks found.</p>
            <?php else: ?>
                <?php foreach ($groupedBookmarks as $folder => $bookmarks): ?>
                    <div class="bookmark-container">
                        <div class="folder-title" onclick="toggleBookmarks('<?php echo htmlspecialchars($folder, ENT_QUOTES); ?>')">
                            <?php echo htmlspecialchars($folder); ?>
                            <form action="/project/php/export_folder.php" method="post" style="display:inline;">
                                <input type="hidden" name="folder_name" value="<?php echo htmlspecialchars($folder); ?>">
                                <button type="submit" class="button"><img src="/project/images/export.png" alt="" class="icon-small"></button>
                            </form>
                            <form action="/project/php/delete_folder.php" method="post" style="display:inline;">
                                <input type="hidden" name="folder_name" value="<?php echo htmlspecialchars($folder); ?>">
                                <button type="submit" class="delete-folder-button"><img src="/project/images/delete.png" alt="" class="icon-small"></button>
                            </form>
                             <!-- New "Make Public" Button -->
                                          <!-- New "Make Public" Button -->
                        <form action="/project/php/public_folder.php" method="post" style="display:inline;">
                            <input type="hidden" name="folder_name" value="<?php echo htmlspecialchars($folder); ?>">
                            <button type="submit" class="button"><img src="/project/images/public.png" alt="" class="icon-small"></button>
                        </form>
    
                        </div>
                        <div id="bookmarks-<?php echo htmlspecialchars($folder, ENT_QUOTES); ?>" class="folder-content" style="display:none;">
                            <?php foreach ($bookmarks as $bookmark): ?>
                                <div class="bookmark-item">
                                    <strong>
                                        <a href="/project/views/bookmark_details.php?id=<?php echo $bookmark['id']; ?>">
                                            <img src="https://s2.googleusercontent.com/s2/favicons?domain=<?php echo urlencode($bookmark['url']); ?>" class="icon" alt="">
                                            <?php echo htmlspecialchars($bookmark['title']); ?>
                                        </a>
                                    </strong><br>
                                    <a href="<?php echo htmlspecialchars($bookmark['url']); ?>" target="_blank"><?php echo htmlspecialchars($bookmark['url']); ?></a><br>
                                    <p><?php echo htmlspecialchars($bookmark['description']); ?></p>
                                    <p><strong>Tags:</strong> <?php echo htmlspecialchars($bookmark['tags']); ?></p>
                                    <?php if (!empty($bookmark['file_path'])): ?>
                                <p>File: <a href="/project/upload/<?php echo htmlspecialchars($bookmark['file_path']); ?>" target="_blank" class="text-blue-400 hover:underline"><?php echo htmlspecialchars($bookmark['file_path']); ?></a></p>
                                <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $bookmark['file_path'])): ?>
                                    <img src="/project/upload/<?php echo htmlspecialchars($bookmark['file_path']); ?>" alt="<?php echo htmlspecialchars($bookmark['title']); ?>" class="mt-2 w-24 h-24 object-cover">
                                <?php endif; ?>
                            <?php endif; ?>
                            <div class="mt-4 flex space-x-2">
                                <a href="/project/views/edit_bookmark_form.php?id=<?php echo $bookmark['id']; ?>" class="edit-bookmark-btn"><img src="/project/images/edit.png" alt="" class="icon-small"></a>
                                <form action="/project/php/delete_bookmark.php" method="post" onsubmit="return confirm('Are you sure you want to delete this bookmark?');">
                                    <input type="hidden" name="id" value="<?php echo $bookmark['id']; ?>">
                                    <button type="submit" class="delete-bookmark-btn"><img src="/project/images/delete.png" alt="" class="icon-small"></button>
                                </form>
                            </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
    </div>
</body>
</html>
