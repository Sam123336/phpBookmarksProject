<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- <link rel="stylesheet" href="../output.css"> Link to Tailwind CSS output file -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #2c3e50; /* Dark background for the whole page */
            font-family: 'Arial', sans-serif;
        }

        .form-container {
            background-color: #34495e;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            max-width: 500px;
            width: 100%;
            color: #ecf0f1;
        }

        .form-container h2 {
            color: #e67e22;
            font-size: 1.75rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .form-container input {
            width: 100%;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 5px;
            background-color: #3c4f68;
            color: #ecf0f1;
            border: 1px solid #455a64;
        }

        .form-container input:focus {
            outline: none;
            border-color: #e67e22;
            box-shadow: 0 0 5px rgba(231, 126, 34, 0.5);
        }

        .form-container button {
            width: 100%;
            padding: 1rem;
            background-color: #e67e22;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .form-container button:hover {
            background-color: #f39c12;
        }

        .form-container p {
            text-align: center;
            margin-top: 1rem;
            color: #bdc3c7;
        }

        .form-container a {
            color: #5b9dd1;
        }

        .form-container a:hover {
            color: #e67e22;
            text-decoration: underline;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">

    <div class="form-container">
        <h2>Register</h2>

        <form action="/project/php/register.php" method="POST" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" name="username" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password" required class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 transition">Register</button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-600">
            Already have an account? <a href="login_form.php" class="text-blue-500 hover:underline">Login here</a>
        </p>
    </div>

</body>
</html>
