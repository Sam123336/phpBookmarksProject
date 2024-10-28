<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Bookmark</title>
    <link rel="stylesheet" href="../output.css"> <!-- Link to Tailwind CSS output file -->
</head>
<body class="bg-gray-100 text-gray-900 font-sans p-6 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-semibold mb-6 text-center">Add Bookmark</h2>

        <form action="/project/php/add_bookmark.php" method="post" enctype="multipart/form-data" class="space-y-4">
            <input type="text" name="title" placeholder="Bookmark Title" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            
            <input type="url" name="url" placeholder="Bookmark URL" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            
            <textarea name="description" placeholder="Description" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            
            <input type="text" name="folder" placeholder="Folder" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            
            <input type="text" name="tags" placeholder="Tags" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            
            <input type="file" name="file_upload" class="w-full text-gray-700">
            
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition">Add Bookmark</button>
        </form>
    </div>

</body>
</html>
