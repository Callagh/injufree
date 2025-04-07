<?php
session_start();

if (!isset($_SESSION['restResult'])) {
    header("Location: ./homePage.php");
    exit();
}

$result = $_SESSION['restResult'];
$score = $result['score'];
$details = $result['details'];
$minWater = $result['minWater'];
$waterIntake = $result['waterIntake'];
$sleep = $result['sleep'];
$intensity = $result['intensity'];
$experience = $result['experience'];
$proteinIntake = $result['proteinIntake'];
$isInjury = $result['isInjury'];
$weight = $result['weight'];

$scorePercentage = round($score * 100);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rest Days Results - Injufree</title>
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

    <div class="bg-white/95 backdrop-blur-md border border-white/20 p-8 rounded-2xl shadow-2xl w-full max-w-2xl mt-20">
        <h1 class="text-4xl font-bold text-center text-green-700 mb-8">Your Rest Recommendation</h1>

        <div class="text-center mb-12">
            <div class="text-8xl font-bold text-green-600 mb-2"><?php echo $result['rest']; ?></div>
            <div class="text-xl text-green-700">Recommended Rest Days</div>
        </div>

        <div class="mb-8">
            <div class="flex justify-between items-center mb-2">
                <span class="text-lg font-semibold text-gray-700">Recovery Score</span>
                <span class="text-lg font-semibold text-green-600"><?php echo $scorePercentage; ?>%</span>
            </div>
            <div class="h-4 bg-green-100 rounded-full overflow-hidden">
                <div class="h-full bg-green-500 rounded-full" style="width: <?php echo $scorePercentage; ?>%"></div>
            </div>
        </div>

        <div class="mb-8">
            <h2 class="text-2xl font-bold text-green-700 mb-4">Detailed Analysis</h2>
            <div class="space-y-4">
                <?php foreach ($details as $factor => $value): ?>
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-lg font-semibold text-gray-700">
                                <?php echo ucfirst($factor); ?>
                            </span>
                            <span class="text-lg font-semibold text-green-600">
                                <?php echo round($value * 100, 1); ?>%
                            </span>
                        </div>
                        <div class="h-4 bg-green-100 rounded-full overflow-hidden">
                            <div class="h-full bg-green-500 rounded-full" style="width: <?php echo $value * 100; ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold text-green-700 mb-4">Personalized Recommendations</h2>
            <div class="space-y-4">
                <?php
                if ($age >= 65) {
                    echo '<p class="text-gray-700">• Consider longer rest periods between workouts (2-3 days)</p>';
                    echo '<p class="text-gray-700">• Focus on low-impact exercises and proper form</p>';
                } elseif ($age >= 40) {
                    echo '<p class="text-gray-700">• Allow for 1-2 days of rest between intense workouts</p>';
                    echo '<p class="text-gray-700">• Include proper warm-up and cool-down routines</p>';
                }

                if ($score < 0.6) {
                    echo '<p class="text-gray-700">• Consider taking a complete rest day</p>';
                    echo '<p class="text-gray-700">• Focus on hydration and nutrition</p>';
                } elseif ($score < 0.8) {
                    echo '<p class="text-gray-700">• Light activity or active recovery recommended</p>';
                    echo '<p class="text-gray-700">• Ensure proper sleep and nutrition</p>';
                }

                if ($sleep < 7) {
                    echo '<p class="text-gray-700">• Prioritize getting 7-9 hours of sleep</p>';
                    echo '<p class="text-gray-700">• Maintain consistent sleep schedule</p>';
                }

                if ($waterIntake < $minWater) {
                    echo '<p class="text-gray-700">• Increase daily water intake</p>';
                    echo '<p class="text-gray-700">• Monitor hydration throughout the day</p>';
                }

                if ($proteinIntake < ($weight * 0.8)) {
                    echo '<p class="text-gray-700">• Increase protein intake to support recovery</p>';
                    echo '<p class="text-gray-700">• Include protein-rich foods in each meal</p>';
                }

                if ($isInjury) {
                    echo '<p class="text-gray-700">• Consult with a healthcare professional</p>';
                    echo '<p class="text-gray-700">• Follow RICE protocol (Rest, Ice, Compression, Elevation)</p>';
                    echo '<p class="text-gray-700">• Avoid aggravating the injured area</p>';
                }
                ?>
            </div>
        </div>

        <div class="mt-8 flex justify-center space-x-4">
            <button onclick="window.location.href='./homePage.php'" 
                    class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                Calculate Again
            </button>
            <button onclick="window.location.href='./homePage.php'" 
                    class="bg-white border-2 border-green-500 text-green-600 hover:bg-green-50 font-bold py-3 px-6 rounded-xl shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                Back to Home
            </button>
        </div>
    </div>
</body>
</html> 