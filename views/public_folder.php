<?php
session_start();

// Display the share link if available in the session
if (isset($_SESSION['share_link'])) {
    echo "Share Link: <a href='" . htmlspecialchars($_SESSION['share_link']) . "'>" . htmlspecialchars($_SESSION['share_link']) . "</a>";
    unset($_SESSION['share_link']); // Clear the session variable after displaying
} else {
    echo "No share link available.";
}
?>
