<?php
session_start();

// Check if results exist
if (!isset($_SESSION['restResult'])) {
    header("Location: homePage.php");
    exit();
}

$result = $_SESSION['restResult'];
$restDays = $result['rest'];
$score = $result['score'];
$details = $result['details'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Injufree - Rest Results</title>
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

    <div class="bg-white/95 backdrop-blur-md border border-white/20 p-8 rounded-2xl shadow-2xl w-full max-w-2xl mt-20">
        <h2 class="text-3xl font-extrabold text-center text-green-700 mb-8">Your Rest Recommendation</h2>

        <!-- Main Result -->
        <div class="text-center mb-12">
            <div class="text-6xl font-bold text-green-600 mb-4"><?= $restDays ?></div>
            <div class="text-xl text-green-700 font-medium">Recommended Rest Days</div>
        </div>

        <!-- Score Bar -->
        <div class="mb-8">
            <div class="flex justify-between mb-2">
                <span class="text-sm font-medium text-green-700">Recovery Score</span>
                <span class="text-sm font-medium text-green-700"><?= number_format($score * 100, 1) ?>%</span>
            </div>
            <div class="w-full bg-green-100 rounded-full h-4">
                <div class="bg-gradient-to-r from-green-500 to-green-600 h-4 rounded-full transition-all duration-500" 
                     style="width: <?= $score * 100 ?>%"></div>
            </div>
        </div>

        <!-- Detailed Scores -->
        <div class="space-y-6">
            <h3 class="text-xl font-semibold text-green-700 mb-4">Detailed Analysis</h3>
            
            <?php
            $factorLabels = [
                'age' => 'Age',
                'water' => 'Water Intake',
                'sleep' => 'Sleep',
                'weight' => 'Weight',
                'recovery' => 'Recovery Status',
                'intensity' => 'Workout Intensity',
                'experience' => 'Experience Level'
            ];

            foreach ($details as $factor => $score) {
                $percentage = $score * 100;
                $color = $score >= 0.8 ? 'green' : ($score >= 0.6 ? 'yellow' : 'red');
                $colorClasses = [
                    'green' => 'from-green-500 to-green-600',
                    'yellow' => 'from-yellow-500 to-yellow-600',
                    'red' => 'from-red-500 to-red-600'
                ];
            ?>
                <div>
                    <div class="flex justify-between mb-2">
                        <span class="text-sm font-medium text-green-700"><?= $factorLabels[$factor] ?></span>
                        <span class="text-sm font-medium text-green-700"><?= number_format($percentage, 1) ?>%</span>
                    </div>
                    <div class="w-full bg-green-100 rounded-full h-3">
                        <div class="bg-gradient-to-r <?= $colorClasses[$color] ?> h-3 rounded-full transition-all duration-500" 
                             style="width: <?= $percentage ?>%"></div>
                    </div>
                </div>
            <?php } ?>
        </div>

        <!-- Recommendations -->
        <div class="mt-8 p-6 bg-green-50 rounded-xl">
            <h3 class="text-xl font-semibold text-green-700 mb-4">Recommendations</h3>
            <ul class="space-y-3">
                <?php if ($details['water'] < 0.8): ?>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">•</span>
                        <span class="text-green-700">Consider increasing your water intake to improve recovery</span>
                    </li>
                <?php endif; ?>
                
                <?php if ($details['sleep'] < 0.8): ?>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">•</span>
                        <span class="text-green-700">Aim for 7-9 hours of sleep for optimal recovery</span>
                    </li>
                <?php endif; ?>
                
                <?php if ($details['recovery'] === 0.7): ?>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">•</span>
                        <span class="text-green-700">Since you're in recovery, consider lighter workouts and longer rest periods</span>
                    </li>
                <?php endif; ?>
                
                <?php if ($details['intensity'] === 0.6): ?>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">•</span>
                        <span class="text-green-700">High intensity workouts require more recovery time - consider alternating with medium intensity sessions</span>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 flex justify-center space-x-4">
            <button onclick="window.location.href='homePage.php'" 
                    class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                Calculate Again
            </button>
            <button onclick="window.location.href='homePage.php'" 
                    class="bg-white border-2 border-green-500 text-green-600 hover:bg-green-50 font-bold py-3 px-6 rounded-xl shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                Back to Home
            </button>
        </div>
    </div>

    <img src="../images/logo.png" alt="Injufree Logo" 
         class="fixed bottom-5 left-5 w-24 opacity-90 hover:opacity-100 transition-opacity duration-200">
</body>
</html> 