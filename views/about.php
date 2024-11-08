<?php
// Include the config file and navbar

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <style>
        /* Global styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Navbar styles */
        .navbar {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }

        .navbar ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
        }

        .navbar ul li {
            display: inline;
            margin: 0 15px;
        }

        .navbar ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }

        .navbar ul li a:hover {
            text-decoration: underline;
        }

        /* Main container styles */
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Content section */
        .content {
            padding: 20px;
        }

        .content h1, .content h2, .content h3 {
            color: #333;
        }

        /* Footer styles */
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <?php include('../views/navbar.php'); ?>

    <!-- Main Content Section -->
    <div class="container">
        <div class="content">
            <h1>About Us</h1>

            <section>
                <h2>Our Story</h2>
                <p>Welcome to our website! We are passionate about providing great content and services to our users. Our mission is to help you achieve your goals in an efficient and user-friendly way.</p>

                <h3>Who We Are</h3>
                <p>We are a team of developers and content creators dedicated to making your experience with us as valuable and enjoyable as possible. Our goal is to always improve and innovate to meet your needs.</p>

                <h3>Our Vision</h3>
                <p>We aim to be the best at what we do. We envision a platform where users have easy access to the tools and information they need to succeed. Our focus is on continuous growth and offering more to our audience.</p>
            </section>

            <!-- Optional: Add an image or video -->
            <section>
                <h3>Our Team</h3>
                <img src="../images/team-photo.jpg" alt="Our team" width="600" />
            </section>
        </div>
    </div>

    <!-- Footer Section -->
    <footer>
        <p>&copy; 2024 Your Website Name. All rights reserved.</p>
    </footer>

</body>
</html>
