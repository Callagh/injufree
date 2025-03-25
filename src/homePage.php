<?php
session_start();

// Check if session variables exist
$name = isset($_SESSION['name']) ? $_SESSION['name'] : "Guest";
$genderAtBirth = isset($_SESSION['genderAtBirth']) ? $_SESSION['genderAtBirth'] : "Unknown";
$DOB = isset($_SESSION['DOB']) ? $_SESSION['DOB'] : null;

// Calculate age
if ($DOB) {
    try {
        $DOB = new DateTime($DOB);
        $currentDate = new DateTime();
        $diff = $DOB->diff($currentDate);
        $age = $diff->y;
    } catch (Exception $e) {
        $age = "Invalid DOB";
    }
} else {
    $age = "Unknown";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Injufree - Homepage</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../dist/output.css" rel="stylesheet"> 
</head>


<body class="bg-gray-100 flex items-center justify-center min-h-screen relative">



    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-4">Rest Calculator</h2>
        
        <form action="formhandler.php" method="POST" class="space-y-4">
            
            <!-- Gender at Birth -->
            <div>
                <span class="block text-sm font-medium text-gray-600">Assigned Gender at Birth</span>
                <div class="mt-2 flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="genderAtBirth" value="male" class="form-radio text-blue-500" required <?= ($genderAtBirth == "male") ? "checked" : ""?>>
                        <span class="ml-2">Male</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="genderAtBirth" value="female" class="form-radio text-blue-500" required <?= ($genderAtBirth == "female") ? "checked" : ""?>>
                        <span class="ml-2">Female</span>
                    </label>
                </div>
            </div>

            <!-- Age -->
            <div>
                <label for="age" class="block text-sm font-medium text-gray-600">Age</label>
                <input type="number" id="age" name="age" value="<?= htmlspecialchars($age) ?>"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm border-gray-300" required min="1" max="120">
            </div>

            <!-- Water Intake -->
            <div>
                <label for="waterIntake" class="block text-sm font-medium text-gray-600">Average Water Intake (litres per day)</label>
                <input type="number" id="waterIntake" name="waterIntake" placeholder="Enter litres"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm border-gray-300" required min="1" max="10">
            </div>

            <!-- Sleep -->
            <div>
                <label for="sleep" class="block text-sm font-medium text-gray-600">Average Sleep (hours per night)</label>
                <input type="number" id="sleep" name="sleep" placeholder="Enter hours"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm border-gray-300" required min="1" max="24">
            </div>

            <!-- Weight -->
            <div>
                <label for="weight" class="block text-sm font-medium text-gray-600">Weight (kg)</label>
                <input type="number" id="weight" name="weight" placeholder="Enter weight"
                    class="mt-1 block w-full px-4 py-2 border rounded-lg shadow-sm border-gray-300" required min="1" max="500">
            </div>

            <!-- Recovery -->
            <div>
                <span class="block text-sm font-medium text-gray-600">Are you in recovery?</span>
                <div class="mt-2 flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="recovery" value="true" class="form-radio text-blue-500" required>
                        <span class="ml-2">Yes</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="recovery" value="false" class="form-radio text-blue-500" required>
                        <span class="ml-2">No</span>
                    </label>
                </div>
            </div>

            <!-- Intensity -->
            <div>
                <span class="block text-sm font-medium text-gray-600">What intensity level was your last workout?</span>
                <div class="mt-2 flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="intensity" value="high" class="form-radio text-blue-500" required>
                        <span class="ml-2">High</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="intensity" value="medium" class="form-radio text-blue-500" required>
                        <span class="ml-2">Medium</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="intensity" value="low" class="form-radio text-blue-500" required>
                        <span class="ml-2">Low</span>
                    </label>
                </div>
            </div>

            <!-- Experience Level -->
            <div>
                <span class="block text-sm font-medium text-gray-600">What is your exercise experience level?</span>
                <div class="mt-2 flex space-x-4">
                    <label class="inline-flex items-center">
                        <input type="radio" name="experience" value="experienced" class="form-radio text-blue-500" required>
                        <span class="ml-2">Experienced</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="experience" value="intermediate" class="form-radio text-blue-500" required>
                        <span class="ml-2">Intermediate</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="experience" value="beginner" class="form-radio text-blue-500" required>
                        <span class="ml-2">Beginner</span>
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg shadow-md transition duration-200">
                Submit
            </button>

            <a href="index.php" class="text-blue-500">Sign out</a>
        </form>

    </div>

</body>
</html>
