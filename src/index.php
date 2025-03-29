<!DOCTYPE html>
<html lang="en">
<head>
    <title>Injufree - Sign In</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="flex items-center justify-center min-h-screen relative font-['Inter'] bg-gradient-to-br from-green-50 to-green-100">

    <header class="absolute top-0 left-0 w-full bg-gradient-to-r from-green-600 to-green-500 text-white py-4 shadow-md flex justify-between px-8 items-center">
        <h1 class="text-3xl font-bold cursor-pointer hover:text-green-100 transition-colors duration-200" onclick="window.location.href='homePage.php'">
            Injufree
        </h1>

        <img src="../images/homeButton.png" 
             alt="Home" 
             class="w-[50px] h-[50px] cursor-pointer hover:opacity-90 transition-opacity duration-200"
             onclick="window.location.href='homePage.php'">
    </header>

    <div class="bg-white/95 backdrop-blur-md border border-white/20 p-8 rounded-2xl shadow-2xl w-full max-w-md mt-20">
        <h2 class="text-3xl font-extrabold text-center text-green-700 mb-8">Welcome Back!</h2>
        
        <form action="formHandler.php" method="POST" class="space-y-6">
            
            <div>
                <label for="email" class="block text-sm font-medium text-green-700 mb-1">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter your email"
                    class="mt-1 block w-full px-4 py-3 border rounded-xl shadow-sm border-green-200 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-green-700 mb-1">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password"
                    class="mt-1 block w-full px-4 py-3 border rounded-xl shadow-sm border-green-200 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
            </div>

            <input type="hidden" name="action" value="signIn">

            <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                Sign In
            </button>

            <div class="text-center text-green-600 space-x-2">
                <a href="#" class="text-sm hover:text-green-700 underline transition-colors duration-200">Forgot password?</a>
                <span class="text-gray-400">|</span>
                <a href="signUp.html" class="text-sm hover:text-green-700 underline transition-colors duration-200">Sign up</a>
            </div>
            
        </form>

        <?php 
        session_start();
        if(isset($_SESSION['error'])){
            echo "<p class='text-red-500 text-center mt-4 font-semibold bg-red-50 py-2 rounded-lg'>{$_SESSION['error']}</p>";
        }
        ?>
    </div>

    <img src="../images/logo.png" alt="Injufree Logo" 
         class="fixed bottom-5 left-5 w-24 opacity-90 hover:opacity-100 transition-opacity duration-200">

</body>
</html>