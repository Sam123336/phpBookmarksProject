<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookmarks</title>
    <link rel="stylesheet" href="../output.css">
</head>
<body class="bg-gray-100 text-gray-900 font-sans p-6">
    <h2 class="text-2xl font-semibold mb-4">Your Bookmarks</h2>
    <div class="flex space-x-4 mb-4">
        <a href="/project/php/logout.php" class="text-blue-500 hover:underline">Logout</a>
        <a href="/project/views/add_bookmark_form.php" class="text-blue-500 hover:underline">Add Bookmark</a>
    </div>

    <form action="/project/php/search.php" method="get" class="mb-6">
        <input type="text" name="search" placeholder="Search bookmarks" class="border border-gray-300 rounded px-3 py-2 w-full md:w-1/2 mb-2 md:mb-0">
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Search</button>
    </form>

    <?php
    include('../config.php');
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header('Location: /project/views/login_form.php');
        exit;
    }

    $user_id = $_SESSION['user_id'];

    // Fetch folders with bookmarks for the current user
    $stmt = $pdo->prepare("
        SELECT f.name AS folder_name, b.title, b.url, b.description, b.tags, b.file_path
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
    ?>

    <?php if (!empty($groupedBookmarks)) : ?>
        <?php foreach ($groupedBookmarks as $folder => $bookmarks): ?>
            <section class="mb-6">
                <h3 class="text-xl font-bold text-gray-700"><?php echo htmlspecialchars($folder); ?></h3>
                <ul class="space-y-4">
                    <?php foreach ($bookmarks as $bookmark): ?>
                        <li class="p-4 bg-white rounded shadow">
                            <strong class="text-lg font-semibold"><?php echo htmlspecialchars($bookmark['title']); ?></strong><br>
                            <a href="<?php echo htmlspecialchars($bookmark['url']); ?>" target="_blank" class="text-blue-500 hover:underline"><?php echo htmlspecialchars($bookmark['url']); ?></a><br>
                            <p class="text-gray-700"><?php echo htmlspecialchars($bookmark['description']); ?></p>
                            <small class="text-gray-500">Tags: <?php echo htmlspecialchars($bookmark['tags']); ?></small><br>

                            <?php if (!empty($bookmark['file_path'])): ?>
                                <p>File: <a href="/project/upload/<?php echo htmlspecialchars($bookmark['file_path']); ?>" target="_blank" class="text-blue-500 hover:underline"><?php echo htmlspecialchars($bookmark['file_path']); ?></a></p>
                                <?php if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $bookmark['file_path'])): ?>
                                    <img src="/project/upload/<?php echo htmlspecialchars($bookmark['file_path']); ?>" alt="<?php echo htmlspecialchars($bookmark['title']); ?>" class="mt-2 w-24 h-24 object-cover">
                                <?php endif; ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endforeach; ?>
    <?php else : ?>
        <p class="p-4 bg-white rounded shadow">No bookmarks available. Add a bookmark to get started!</p>
    <?php endif; ?>
</body>
</html>

