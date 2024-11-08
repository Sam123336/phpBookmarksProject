<?php
// Inside your toggle_public.php script
include('config.php');
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /project/views/login_form.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$folder_name = $_POST['folder_name']; // Folder name sent via POST

// Fetch the folder ID based on the folder name
$stmt = $pdo->prepare("SELECT id, public FROM folders WHERE name = ? AND user_id = ?");
$stmt->execute([$folder_name, $user_id]);
$folder = $stmt->fetch(PDO::FETCH_ASSOC);

if ($folder) {
    // Toggle the 'is_public' status (if 1, make it 0; if 0, make it 1)
    $newPublicStatus = $folder['public'] == 1 ? 0 : 1;

    // Update the public status in the database
    $updateStmt = $pdo->prepare("UPDATE folders SET public = ? WHERE id = ? AND user_id = ?");
    $updateStmt->execute([$newPublicStatus, $folder['id'], $user_id]);
} else {
    // Handle error if the folder is not found
    echo "Error: Folder not found.";
    exit;
}

// Redirect to the bookmarks list page
header('Location: /project/views/bookmarks_list.php');
exit;
?>
