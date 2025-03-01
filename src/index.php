
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Injufree </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../dist/output.css" rel="stylesheet">
    </head>
    <body class="bg-gray-100 flex items-center justify-center min-h-screen">
    
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold text-center text-gray-700 mb-4">Sign In</h2>
            
            <form action="./formHandler.php" method="POST" class="space-y-4">
                
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email"
                        class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm border-gray-300">
                </div>
                
                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-600">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password"
                        class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm border-gray-300">
                </div>
    
                <input type="hidden" name="action" value="signIn">
    
                <!-- Submit Button -->
                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200">
                    Sign In
                </button>
    
                <!-- Forgot Password & Sign Up -->
                <div class="text-center">
                    <a href="placeholder" class="text-sm text-blue-500 hover:underline">Forgot password?</a>
                    <span class="mx-2 text-gray-400">|</span>
                    <a href="signUp.html" class="text-sm text-blue-500 hover:underline">Sign up</a>
                </div>
                
            </form>
            <?php 
            session_start();
            if(isset($_SESSION['error'])){
            echo $_SESSION['error'];
            }
            ?>
        </div>
    
   
    
   </body>
   

</html>