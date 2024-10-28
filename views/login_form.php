<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="../output.css">


  
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded shadow-md w-full max-w-md">
        <h2 class="text-2xl font-semibold text-center text-gray-700 mb-6">Login</h2>
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