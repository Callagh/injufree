<?php
session_start();
$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up to Injufree</title>
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
        <h2 class="text-3xl font-extrabold text-center text-green-700 mb-8">Create Account</h2>

        <?php if (!empty($error)): ?>
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
            <p class="text-red-600 text-sm"><?php echo htmlspecialchars($error); ?></p>
        </div>
        <?php endif; ?>

        <form id="signUpForm" action="formHandler.php" method="POST" class="space-y-6">
            <input type="text" placeholder="Enter your first name" id="name" name="name" required 
                class="mt-1 block w-full px-4 py-3 border rounded-xl shadow-sm border-green-200 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
            
            <input type="email" placeholder="Enter your email" id="email" name="email" required 
                class="mt-1 block w-full px-4 py-3 border rounded-xl shadow-sm border-green-200 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
            
            <input type="password" placeholder="Create your password" id="password" name="password" required 
                class="mt-1 block w-full px-4 py-3 border rounded-xl shadow-sm border-green-200 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
            
            <input type="password" placeholder="Confirm your password" id="confirmedPassword" name="confirmedPassword" required 
                class="mt-1 block w-full px-4 py-3 border rounded-xl shadow-sm border-green-200 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
            
            <input type="hidden" name="action" value="signUp">
            
            <div>
                <input type="checkbox" id="extra" class="peer hidden" name="extra">

                <label for="extra" class="ml-2 text-sm text-green-600 cursor-pointer bg-green-50 px-4 py-2 rounded-xl hover:bg-green-100 transition-colors duration-200 inline-block">
                    Show more options
                </label>
                
                <div class="hidden peer-checked:block mt-6 space-y-6 p-4 bg-green-50 rounded-xl">
                    <p class="font-bold text-green-700">You must be older than 16 years or have an adults permission to enter the below information</p>
                    
                    <div>
                        <label class="block text-sm font-medium text-green-600 mb-1">Date of Birth</label>
                        <input type="date" name="DOB" id="dob" placeholder="Enter Date of Birth"
                            class="mt-1 block w-full px-4 py-3 border rounded-xl shadow-sm border-green-200 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200">
                    </div>

                    <div class="flex items-center space-x-3">
                        <input type="checkbox" id="consent" name="consent" class="h-5 w-5 text-green-500 rounded border-green-300">
                        <label for="consent" class="text-sm text-green-600">I consent to the storage of the above information in line with GDPR guidelines</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                Sign Up
            </button>

            <div class="text-center text-green-600">
                <a href="index.php" class="text-sm hover:text-green-700 underline transition-colors duration-200">Already have an account? Sign in</a>
            </div>
        </form>
    </div>

    <img src="../images/logo.png" alt="Injufree Logo" 
         class="fixed bottom-5 left-5 w-24 opacity-90 hover:opacity-100 transition-opacity duration-200">

    <script>
        document.getElementById('dob').addEventListener('change', function() {
            const dob = new Date(this.value);
            const today = new Date();
            const age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            
            const consentCheckbox = document.getElementById('consent');
            const extraCheckbox = document.getElementById('extra');
            const extraLabel = extraCheckbox.nextElementSibling;
            const submitButton = document.querySelector('button[type="submit"]');
            
            if (age < 16) {
                
                submitButton.disabled = true;
                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                submitButton.textContent = 'You must be 16 years or older to store this data';
            } else {
                
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                submitButton.textContent = 'Sign Up';
            }
        });

       
        document.getElementById('extra').addEventListener('change', function() {
            const extraLabel = this.nextElementSibling;
            if (!this.checked) {
                
                document.getElementById('dob').value = '';
              
                const submitButton = document.querySelector('button[type="submit"]');
                submitButton.disabled = false;
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                submitButton.textContent = 'Sign Up';
                
                extraLabel.textContent = 'Show more options';
            } else {
                
                extraLabel.textContent = 'Show less options';
            }
        });

       
        window.addEventListener('beforeunload', function() {
            
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'clearSession.php', false); 
            xhr.send();
        });
    </script>
</body>
</html>
