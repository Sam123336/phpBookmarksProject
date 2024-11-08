<!DOCTYPE html>
<html lang="en">
<head>
    <!-- <link rel="stylesheet" href="../output.css"> Link to Tailwind CSS output file -->
    <script src="https://cdn.tailwindcss.com"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background-color: #1f1f2e;
            font-family: 'Arial', sans-serif;
        }

        .form-container {
            background-color: #333;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            color: #e5e5e5;
        }

        .form-container h2 {
            color: #ff8a5c;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .form-container input {
            width: 100%;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 5px;
            background-color: #444;
            color: #e5e5e5;
            border: 1px solid #555;
        }

        .form-container input:focus {
            outline: none;
            border-color: #ff8a5c;
            box-shadow: 0 0 5px rgba(255, 138, 92, 0.5);
        }

        .form-container button {
            width: 100%;
            padding: 1rem;
            background-color: #ff8a5c;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #ffb37e;
        }

        .form-container p {
            text-align: center;
            margin-top: 1rem;
            color: #ccc;
        }

        .form-container a {
            color: #5b9dd1;
        }

        .form-container a:hover {
            color: #ff8a5c;
            text-deco<script src="https://cdn.tailwindcss.com"></script>ration: underline;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">

    <div class="form-container">
        <h2>Login</h2>
        <form action="/project/php/login.php" method="post" class="space-y-4">
            <div>
                <label class="block text-gray-600">Email</label>
                <input type="email" name="email" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-600">Password</label>
                <input type="password" name="password" required class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition duration-200">Login</button>
        </form>
        <p class="text-center text-gray-600 mt-4">Don't have an account? <a href="register_form.php" class="text-blue-500 hover:underline">Register here</a></p>
    </div>

</body>
</html>
