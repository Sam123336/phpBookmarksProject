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

// Debugging output
if (empty($bookmarks)) {
    echo "<p>No bookmarks found for this user.</p>";
} else {
    echo "<p>Bookmarks found: " . count($bookmarks) . "</p>";
}

// Group bookmarks by folder
$groupedBookmarks = [];
foreach ($bookmarks as $bookmark) {
    $folder = $bookmark['folder_name'];
    if (!isset($groupedBookmarks[$folder])) {
        $groupedBookmarks[$folder] = [];
    }
    $groupedBookmarks[$folder][] = $bookmark;
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
            background: rgba(14, 13, 13, 0.68); /* Dark transparent background */
            border-radius: 16px; /* Rounded corners */
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); /* Shadow effect */
            backdrop-filter: blur(1.8px); /* Frosted glass effect */
            -webkit-backdrop-filter: blur(1.8px); /* Frosted glass effect for Safari */
            border: 1px solid rgba(14, 13, 13, 0.06); /* Light border */
        }

        body {
            background-color: #121212; /* Dark background */
            color: #ffffff; /* White text color for better contrast */
            font-sans: sans-serif; /* Set font family */
            padding: 6rem; /* Padding around the body */
        }
    </style>
    <script>
        function toggleBookmarks(folder) {
            const bookmarksList = document.getElementById(`bookmarks-${folder}`);
            const arrow = document.getElementById(`arrow-${folder}`);
            if (bookmarksList.style.display === "none") {
                bookmarksList.style.display = "block";
                arrow.textContent = "▼"; // Down arrow when expanded
            } else {
                bookmarksList.style.display = "none";
                arrow.textContent = "►"; // Right arrow when collapsed
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
        <input type="text" name="search" placeholder="Search bookmarks" class="border border-gray-700 rounded px-3 py-2 w-full md:w-1/2 mb-2 md:mb-0 bg-gray-800 text-gray-200">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Search</button>
    </form>

    <?php 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    if (!empty($groupedBookmarks)) : ?>
        <?php foreach ($groupedBookmarks as $folder => $bookmarks): ?>
            <section class="mb-6 frosted-glass p-4">
                <h3 class="text-xl font-bold text-gray-200 cursor-pointer flex items-center" onclick="toggleBookmarks('<?php echo htmlspecialchars($folder); ?>')">
                    <span id="arrow-<?php echo htmlspecialchars($folder); ?>" class="mr-2">►</span>

                    <?php echo htmlspecialchars($folder); ?>
                    <form action="/project/php/delete_folder.php" method="post" onsubmit="return confirm('Are you sure you want to delete this folder and all its bookmarks?');" class="inline-block ml-4">
            <input type="hidden" name="folder_name" value="<?php echo htmlspecialchars($folder); ?>">
            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">Delete Folder</button>
        </form>
                </h3>
                <ul id="bookmarks-<?php echo htmlspecialchars($folder); ?>" class="space-y-4" style="display: none;">
                    <?php foreach ($bookmarks as $bookmark): ?>
                        <li class="p-4 bg-gray-800 rounded shadow">
                            <strong class="text-lg font-semibold"><?php echo htmlspecialchars($bookmark['title']); ?></strong><br>
                            <a href="<?php echo htmlspecialchars($bookmark['url']); ?>" target="_blank" class="text-blue-400 hover:underline"><?php echo htmlspecialchars($bookmark['url']); ?></a><br>
                            <p class="text-gray-300"><?php echo htmlspecialchars($bookmark['description']); ?></p>
                            <small class="text-gray-500">Tags: <?php echo htmlspecialchars($bookmark['tags']); ?></small><br>

                            <?php if (!empty($bookmark['file_path'])): ?>
                                <p>File: <a href="/project/upload/<?php echo htmlspecialchars($bookmark['file_path']); ?>" target="_blank" class="text-blue-400 hover:underline"><?php echo htmlspecialchars($bookmark['file_path']); ?></a></p>
                                <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $bookmark['file_path'])): ?>
                                    <img src="/project/upload/<?php echo htmlspecialchars($bookmark['file_path']); ?>" alt="<?php echo htmlspecialchars($bookmark['title']); ?>" class="mt-2 w-24 h-24 object-cover">
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- Edit and Delete Buttons -->
                            <div class="mt-4 flex space-x-2">
                                <a href="/project/views/edit_bookmark_form.php?id=<?php echo $bookmark['id']; ?>" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Edit</a>
                                <form action="/project/php/delete_bookmark.php" method="post" onsubmit="return confirm('Are you sure you want to delete this bookmark?');">
                                    <input type="hidden" name="id" value="<?php echo $bookmark['id']; ?>">
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete</button>
                                </form>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endforeach; ?>
    <?php else : ?>
        <p class="p-4 bg-gray-800 rounded shadow">No bookmarks available. Add a bookmark to get started!</p>
    <?php endif; ?>
</body>
</html>
