<?php
$host = 'localhost'; 
$db = 'bookmark_manager'; // Replace with your database name
$user = 'root';           // Use your MySQL username
$pass = '';               // Use your MySQL password if needed

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
