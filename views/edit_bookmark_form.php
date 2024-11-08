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

if (!$bookmark_id) {
    echo "No bookmark selected.";
    exit;
}

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
    <style>
        /* Custom styling for Edit Bookmark */
        body {
            background-color: #1f1f2e;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #e5e5e5;
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .form-container {
            background-color: #333;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }
        .form-container h2 {
            color: #ff8a5c;
            margin-bottom: 1.5rem;
            font-size: 1.75rem;
        }
        .form-input, .form-textarea, .form-file, .form-button {
            width: 100%;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border-radius: 5px;
            border: 1px solid #555;
            background-color: #444;
            color: #e5e5e5;
        }
        .form-input:focus, .form-textarea:focus {
            outline: none;
            border-color: #ff8a5c;
            box-shadow: 0 0 5px rgba(255, 138, 92, 0.5);
        }
        .form-button {
            background-color: #ff8a5c;
            border: none;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-button:hover {
            background-color: #ffb37e;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Edit Bookmark</h2>
        <form action="/project/php/edit_bookmark.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($bookmark['id']); ?>">
            
            <input type="text" name="title" placeholder="Title" value="<?php echo htmlspecialchars($bookmark['title']); ?>" required class="form-input">
            
            <input type="url" name="url" placeholder="URL" value="<?php echo htmlspecialchars($bookmark['url']); ?>" required class="form-input">
            
            <textarea name="description" placeholder="Description" class="form-textarea"><?php echo htmlspecialchars($bookmark['description']); ?></textarea>
            
            <input type="text" name="tags" placeholder="Tags (comma-separated)" value="<?php echo htmlspecialchars($bookmark['tags']); ?>" class="form-input">
            
            <input type="file" name="file_upload" class="form-file">
            
            <button type="submit" class="form-button">Save Changes</button>
        </form>
    </div>
</body>
</html>
