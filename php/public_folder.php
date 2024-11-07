<?php
include('config.php');
session_start();

// Check if the 'share_link' parameter is present
if (isset($_GET['share_link'])) {
    // Get the encoded share link
    $shareLink = $_GET['share_link'];

    // Decode the share link
    $decodedLink = urldecode($shareLink);

    // Ensure the decoded link is correct and points to folder.php
    if (strpos($decodedLink, 'folder.php') !== false) {
        // Extract the token from the query string of the decoded URL
        parse_str(parse_url($decodedLink, PHP_URL_QUERY), $params);
        $token = $params['token'] ?? null;

        // If the token exists, proceed with redirecting to folder.php
        if ($token) {
            header("Location: /project/views/folder.php?token=" . $token);
            exit;
        } else {
            echo "Invalid token.";
            exit;
        }
    } else {
        echo "Invalid share link.";
        exit;
    }
} else {
    echo "Share link parameter not found.";
    exit;
}
?>
