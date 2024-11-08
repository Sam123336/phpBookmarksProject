<?php
// Include the config file and navbar

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>What's New</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        h2 {
            color: #333;
            text-align: center;
        }

        p {
            color: #555;
            font-size: 16px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <?php include('../views/navbar.php'); ?>

    <!-- Main Content Section -->
    <div class="container">
        <h2>What's New</h2>
        <p>We’ve added new features and improvements to our system:</p>
        <ul>
            <li><strong>New folder creation:</strong> Users can now create folders and share them with others.</li>
            <li><strong>Public folder option:</strong> You can mark your folders as public for easier access.</li>
            <li><strong>Improved interface:</strong> The user interface has been updated for better navigation and usability.</li>
        </ul>
        <p>Stay tuned for more updates!</p>
    </div>

</body>
</html>
