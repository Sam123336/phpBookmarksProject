<?php
// Inside your toggle_public.php script
include('../php/config.php');
session_start();

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /project/views/login_form.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$folder_id = $_POST['folder_id']; // Folder ID sent via POST

// Fetch current public status of the folder
$stmt = $pdo->prepare("SELECT is_public FROM folders WHERE id = ? AND user_id = ?");
$stmt->execute([$folder_id, $user_id]);
$folder = $stmt->fetch(PDO::FETCH_ASSOC);

if ($folder) {
    // Toggle the 'is_public' status (if 1, make it 0; if 0, make it 1)
    $newPublicStatus = $folder['is_public'] == 1 ? 0 : 1;

    // Update the public status in the database
    $updateStmt = $pdo->prepare("UPDATE folders SET is_public = ? WHERE id = ? AND user_id = ?");
    $updateStmt->execute([$newPublicStatus, $folder_id, $user_id]);
}

// Redirect to the public dashboard or any page you want to show after update
header('Location: /project/views/public_dashboard.php');
exit;
?>
