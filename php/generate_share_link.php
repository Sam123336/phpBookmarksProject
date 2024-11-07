<?php
// Include database configuration
include('config.php');

// Get folder ID from the request
$folderId = $_POST['folder_id'];

// Function to generate a unique token
function generateUniqueToken($length = 16) {
    return bin2hex(random_bytes($length / 2));
}

// Check if the folder already has a share token
$stmt = $pdo->prepare("SELECT share_token FROM folders WHERE id = :folder_id");
$stmt->execute(['folder_id' => $folderId]);
$folder = $stmt->fetch(PDO::FETCH_ASSOC);

if ($folder && empty($folder['share_token'])) {
    // Generate a new share token and store it
    $shareToken = generateUniqueToken();
    $stmt = $pdo->prepare("UPDATE folders SET share_token = :share_token WHERE id = :folder_id");
    $stmt->execute(['share_token' => $shareToken, 'folder_id' => $folderId]);
} else {
    $shareToken = $folder['share_token']; // Use existing token if it exists
}

// Get the current base URL dynamically
$baseUrl = "http://" . $_SERVER['HTTP_HOST']; // For localhost or live server

// Generate the full shareable link
$shareLink = $baseUrl . "/project/views/folder.php?token=" . $shareToken;

// Redirect back to the page with the share link
header("Location: ../views/public_folders.php?share_link=" . urlencode($shareLink));
exit;
?>
