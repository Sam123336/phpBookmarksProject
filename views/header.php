<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo isset($title) ? $title : 'Bookmark Manager'; ?></title>
    <link rel="stylesheet" href="../output.css"> <!-- Link to Tailwind CSS output file -->
</head>
<body class="bg-gray-100 min-h-screen">
    <header class="bg-blue-600 text-white py-4">
        <div class="container mx-auto px-4">
            <h1 class="text-3xl font-bold">Bookmark Manager</h1>
            <nav>
                <ul class="flex space-x-4 mt-2">
                    <li><a href="index.php" class="hover:underline">Home</a></li>
                    <li><a href="add_bookmark.php" class="hover:underline">Add Bookmark</a></li>
                    <li><a href="bookmarks.php" class="hover:underline">View Bookmarks</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="logout.php" class="hover:underline">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php" class="hover:underline">Login</a></li>
                        <li><a href="register.php" class="hover:underline">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main class="container mx-auto px-4 py-6">
        <!-- Content will go here -->
    </main>
</body>
</html>
