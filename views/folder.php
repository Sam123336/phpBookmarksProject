<?php
include('config.php');
session_start();

// Check if the share_link parameter exists
if (isset($_GET['share_link'])) {
    $shareLink = $_GET['share_link'];

    // Decode the share link (URL-decoding)
    $decodedLink = urldecode($shareLink);

    // Parse the token from the decoded link (you may need to adjust this depending on your URL structure)
    parse_str(parse_url($decodedLink, PHP_URL_QUERY), $queryParams);
    $token = $queryParams['token'] ?? null;

    if ($token) {
        // Redirect to the folder page with the token
        header("Location: /project/views/folder.php?token=" . $token);
        exit;
    } else {
        echo "Invalid share link.";
        exit;
    }
} else {
    echo "Share link not found.";
    exit;
}
?>
