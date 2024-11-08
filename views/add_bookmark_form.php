<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Bookmark</title>
    <link rel="stylesheet" href="../output.css"> <!-- Link to Tailwind CSS output file -->
    <style>
        /* Additional custom styles */
        body {
            background-color: #1f1f2e;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #e5e5e5;
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
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
        }
        .form-container .form-link {
            color: #5b9dd1;
        }
        .form-container .form-link:hover {
            color: #ff8a5c;
            text-decoration: underline;
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
        <h2>Add Bookmark</h2>

        <!-- Link to go back to bookmarks list -->
        <div class="text-center mb-4">
            <a href="/project/views/bookmarks_list.php" class="form-link">View Bookmarks</a>
        </div>

        <form action="/project/php/add_bookmark.php" method="post" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Bookmark Title" required class="form-input">
            
            <input type="url" name="url" placeholder="Bookmark URL" required pattern="https?://.+" class="form-input">
            
            <textarea name="description" placeholder="Description" class="form-textarea"></textarea>
            
            <input type="text" name="folder" placeholder="Folder (optional)" class="form-input">
            
            <input type="text" name="tags" placeholder="Tags (comma-separated)" class="form-input">
            
            <input type="file" name="file_upload" class="form-file">
            
            <button type="submit" class="form-button">Add Bookmark</button>
        </form>
    </div>

</body>
</html>
