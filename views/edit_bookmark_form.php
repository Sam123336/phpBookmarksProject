<?php
include('../php/config.php'); // Adjust the path based on your structure
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    header('Location: /project/views/login_form.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$bookmark_id = $_GET['id'] ?? null;

// Check if the bookmark ID exists
if (!$bookmark_id) {
    echo "No bookmark selected.";
    exit;
}

// Fetch the bookmark details
try {
    $stmt = $pdo->prepare("SELECT * FROM bookmarks WHERE id = ? AND user_id = ?");
    if (!$stmt->execute([$bookmark_id, $user_id])) {
        die("Query failed: " . implode(", ", $stmt->errorInfo()));
    }
    $bookmark = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$bookmark) {
        echo "Bookmark not found.";
        exit;
    }
} catch (PDOException $e) {
    die("Error fetching bookmark: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bookmark</title>
    <link rel="stylesheet" href="../output.css">
</head>
<body class="bg-gray-100 text-gray-900 font-sans p-6 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-semibold mb-6 text-center">Edit Bookmark</h2>

        <form action="/project/php/edit_bookmark.php" method="post" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($bookmark['id']); ?>">
            <input type="text" name="title" value="<?php echo htmlspecialchars($bookmark['title']); ?>" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="url" name="url" value="<?php echo htmlspecialchars($bookmark['url']); ?>" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <textarea name="description" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"><?php echo htmlspecialchars($bookmark['description']); ?></textarea>
           
            <input type="text" name="tags" value="<?php echo htmlspecialchars($bookmark['tags']); ?>" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <input type="file" name="file_upload" class="w-full text-gray-700">
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition">Save Changes</button>
        </form>
    </div>
</body>
</html>
