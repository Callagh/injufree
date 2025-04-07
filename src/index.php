<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Injufree</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="flex items-center justify-center min-h-screen relative font-['Inter'] bg-gradient-to-br from-green-50 to-green-100">
    <header class="absolute top-0 left-0 w-full bg-gradient-to-r from-green-600 to-green-500 text-white py-4 shadow-md flex justify-between px-8 items-center">
        <div class="flex items-center">
            <img src="../images/logo.png" 
                 alt="Injufree Logo" 
                 class="w-[50px] h-[50px] mr-4">
            <h1 class="text-3xl font-bold cursor-pointer hover:text-green-100 transition-colors duration-200" onclick="window.location.href='./homePage.php'">
                Injufree
            </h1>
        </div>

        <img src="../images/homeButton.png" 
             alt="Home" 
             class="w-[50px] h-[50px] cursor-pointer hover:opacity-90 transition-opacity duration-200"
             onclick="window.location.href='./homePage.php'">
    </header>

    <div class="bg-white/95 backdrop-blur-md border border-white/20 p-8 rounded-2xl shadow-2xl w-full max-w-md mt-20">
        <h2 class="text-3xl font-bold text-center text-green-700 mb-8">Welcome Back</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <form action="formHandler.php" method="POST" class="space-y-6">
            <div>
                <label for="email" class="block text-lg font-medium text-green-700">Email</label>
                <input type="email" id="email" name="email" required
                       class="mt-1 block w-full px-3 py-2 bg-white border border-green-300 rounded-md text-sm shadow-sm placeholder-green-400
                              focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500">
            </div>

            <div>
                <label for="password" class="block text-lg font-medium text-green-700">Password</label>
                <input type="password" id="password" name="password" required
                       class="mt-1 block w-full px-3 py-2 bg-white border border-green-300 rounded-md text-sm shadow-sm placeholder-green-400
                              focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500">
            </div>

            <button type="submit" 
                    class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                Sign In
            </button>

            <p class="text-center text-green-700">
                Don't have an account? 
                <a href="./signUp.php" class="text-green-600 hover:text-green-700 font-semibold">Sign Up</a>
            </p>
        </form>
    </div>
</body>
</html>