<?php
session_start();

$name = isset($_SESSION['name']) ? $_SESSION['name'] : "Guest";
$genderAtBirth = isset($_SESSION['genderAtBirth']) ? $_SESSION['genderAtBirth'] : "Unknown";
$DOB = isset($_SESSION['DOB']) ? $_SESSION['DOB'] : null;

$age = null;
if ($DOB && $DOB !== '') {
    try {
        $DOB = new DateTime($DOB);
        $currentDate = new DateTime();
        $diff = $DOB->diff($currentDate);
        $age = (int)$diff->y;
    } catch (Exception $e) {
        $age = null;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Injufree - Homepage</title>
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
        <h2 class="text-3xl font-extrabold text-center text-green-700 mb-8">Rest Calculator</h2>
        
        <form action="restCalc.php" method="POST" class="space-y-6">
            <div>
                <span class="block text-sm font-medium text-green-700 mb-2">Assigned Gender at Birth</span>
                <div class="flex space-x-6">
                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="genderAtBirth" value="male" class="form-radio text-green-500" required <?= ($genderAtBirth == "male") ? "checked" : ""?>>
                        <span class="text-sm font-medium text-green-600">Male</span>
                    </label>
                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="genderAtBirth" value="female" class="form-radio text-green-500" required <?= ($genderAtBirth == "female") ? "checked" : ""?>>
                        <span class="text-sm font-medium text-green-600">Female</span>
                    </label>
                </div>
            </div>

            <div>
                <label for="age" class="block text-sm font-medium text-green-700 mb-1">Age</label>
                <input type="number" id="age" name="age" 
                    value="<?= is_numeric($age) && $age > 0 ? htmlspecialchars($age) : '' ?>"
                    class="mt-1 block w-full px-4 py-3 border rounded-xl shadow-sm border-green-200 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" 
                    required min="1" max="120" <?= is_numeric($age) && $age > 0 ? 'readonly' : '' ?>>
                <?php if (!is_numeric($age) || $age <= 0): ?>
                    <p class="mt-1 text-sm text-green-600">Please enter your age</p>
                <?php endif; ?>
            </div>

            <div>
                <label for="waterIntake" class="block text-sm font-medium text-green-700 mb-1">Average Water Intake (litres per day)</label>
                <input type="number" id="waterIntake" name="waterIntake" placeholder="Enter litres"
                    class="mt-1 block w-full px-4 py-3 border rounded-xl shadow-sm border-green-200 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" required min="1" max="10">
            </div>

            <div>
                <label for="sleep" class="block text-sm font-medium text-green-700 mb-1">Average Sleep (hours per night)</label>
                <input type="number" id="sleep" name="sleep" placeholder="Enter hours"
                    class="mt-1 block w-full px-4 py-3 border rounded-xl shadow-sm border-green-200 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" required min="1" max="24">
            </div>

            <div>
                <label for="weight" class="block text-sm font-medium text-green-700 mb-1">Weight (kg)</label>
                <input type="number" id="weight" name="weight" placeholder="Enter weight"
                    class="mt-1 block w-full px-4 py-3 border rounded-xl shadow-sm border-green-200 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-200" required min="1" max="500">
            </div>

            <div>
                <span class="block text-sm font-medium text-green-700 mb-2">Are you in recovery?</span>
                <div class="flex space-x-6">
                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="recovery" value="true" class="form-radio text-green-500" required>
                        <span class="text-sm font-medium text-green-600">Yes</span>
                    </label>
                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="recovery" value="false" class="form-radio text-green-500" required>
                        <span class="text-sm font-medium text-green-600">No</span>
                    </label>
                </div>
            </div>

            <div>
                <span class="block text-sm font-medium text-green-700 mb-2">What intensity level was your last workout?</span>
                <div class="flex space-x-6">
                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="intensity" value="high" class="form-radio text-green-500" required>
                        <span class="text-sm font-medium text-green-600">High</span>
                    </label>
                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="intensity" value="medium" class="form-radio text-green-500" required>
                        <span class="text-sm font-medium text-green-600">Medium</span>
                    </label>
                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="intensity" value="low" class="form-radio text-green-500" required>
                        <span class="text-sm font-medium text-green-600">Low</span>
                    </label>
                </div>
            </div>

            <div>
                <span class="block text-sm font-medium text-green-700 mb-2">What is your exercise experience level?</span>
                <div class="flex space-x-6">
                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="experience" value="experienced" class="form-radio text-green-500" required>
                        <span class="text-sm font-medium text-green-600">Experienced</span>
                    </label>
                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="experience" value="intermediate" class="form-radio text-green-500" required>
                        <span class="text-sm font-medium text-green-600">Intermediate</span>
                    </label>
                    <label class="inline-flex items-center space-x-2 cursor-pointer">
                        <input type="radio" name="experience" value="beginner" class="form-radio text-green-500" required>
                        <span class="text-sm font-medium text-green-600">Beginner</span>
                    </label>
                </div>
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                Calculate Rest
            </button>

            <div class="text-center text-green-600">
                <a href="index.php" class="text-sm hover:text-green-700 underline transition-colors duration-200">Sign out</a>
            </div>
        </form>
    </div>

    <img src="../images/logo.png" alt="Injufree Logo" 
         class="fixed bottom-5 left-5 w-24 opacity-90 hover:opacity-100 transition-opacity duration-200">
</body>
</html>
