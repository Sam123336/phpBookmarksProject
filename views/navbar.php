<!-- navbar.php -->
<nav class="navbar">
    <a href="/project/views/bookmarks_list.php">Home</a>
    <a href="/project/views/dashboard.php">Dashboard</a>
    <a href="/project/views/about.php">About</a>
    <a href="/project/views/new.php">New</a>
    <a href="/project/php/logout.php">Logout</a>
</nav>

<style>
    .navbar {
        width: 100%;
        background-color: #333;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        display: flex;
        justify-content: center;
    }

    .navbar a {
        color: #ff8a5c;
        text-decoration: none;
        padding: 10px 20px;
        font-weight: bold;
        transition: color 0.3s;
    }

    .navbar a:hover {
        color: #ffb37e;
    }
</style>
