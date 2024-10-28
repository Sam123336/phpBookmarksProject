<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookmarks</title>
    <link rel="stylesheet" href="../output.css"><!-- Link to Tailwind CSS output file -->
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

    <ul class="space-y-4">
        <?php if (!empty($bookmarks)) : ?>
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
        <?php else : ?>
            <li class="p-4 bg-white rounded shadow">No bookmarks available. Add a bookmark to get started!</li>
        <?php endif; ?>
    </ul>
</body>
</html>
