<?php
session_start();

if (!isset($_SESSION['email'])) {
    header("Location: ./index.php");
    exit();
}

if (!isset($_SESSION['restResult'])) {
    header("Location: ./homePage.php");
    exit();
}

if (isset($_SESSION['DOB'])) {
    try {
        $DOB = new DateTime($_SESSION['DOB']);
        $currentDate = new DateTime();
        $diff = $DOB->diff($currentDate);
        $age = (int)$diff->y;
    } catch (Exception $e) {
        $age = null;
    }
} else {
    $age = null;
}

try {
    $result = $_SESSION['restResult'];
    
    // Validate required fields
    $requiredFields = ['score', 'details', 'minWater', 'waterIntake', 'sleep', 
                      'intensity', 'experience', 'proteinIntake', 'isInjury', 'weight'];
    
    foreach ($requiredFields as $field) {
        if (!isset($result[$field])) {
            throw new Exception("Missing required field: $field");
        }
    }
    
    // Sanitize and validate data
    $score = filter_var($result['score'], FILTER_VALIDATE_FLOAT);
    $details = is_array($result['details']) ? $result['details'] : [];
    $minWater = filter_var($result['minWater'], FILTER_VALIDATE_FLOAT);
    $waterIntake = filter_var($result['waterIntake'], FILTER_VALIDATE_FLOAT);
    $sleep = filter_var($result['sleep'], FILTER_VALIDATE_FLOAT);
    $intensity = htmlspecialchars($result['intensity']);
    $experience = htmlspecialchars($result['experience']);
    $proteinIntake = filter_var($result['proteinIntake'], FILTER_VALIDATE_FLOAT);
    $isInjury = filter_var($result['isInjury'], FILTER_VALIDATE_BOOLEAN);
    $weight = filter_var($result['weight'], FILTER_VALIDATE_FLOAT);
    
    if ($score === false || $minWater === false || $waterIntake === false || 
        $sleep === false || $proteinIntake === false || $weight === false) {
        throw new Exception("Invalid data format");
    }
    
    $scorePercentage = round($score * 100);
    
} catch (Exception $e) {
    $_SESSION['error'] = "An error occurred while processing your results. Please try again.";
    header("Location: ./homePage.php");
    exit();
}

// Research resources based on user input
$resources = [
    'hydration' => 'https://www.hydrationinfo.com',
    'sleep' => 'https://www.sleepfoundation.org',
    'protein' => 'https://www.proteinresearch.com',
    'injury' => 'https://www.injuryrecovery.com'
];

// Check if there are any personalized recommendations
$hasRecommendations = ($age >= 65 || $age >= 40 || $score < 0.6 || $score < 0.8 || $sleep < 7 || 
                      $waterIntake < $result['minWater'] || $proteinIntake < $result['minProtein']);

// Check if there are any research sources
$hasResearchSources = ($age >= 65 || $age >= 40 || (isset($result['waterIntake']) && $result['waterIntake'] < ($weight * 0.02)));

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

        <?php if ($hasRecommendations): ?>
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
                    echo '<p class="text-gray-700">• Maintain a consistent sleep schedule</p>';
                }

                if ($waterIntake < $result['minWater']) {
                    echo '<p class="text-gray-700">• Your current water intake is ' . number_format($waterIntake, 1) . 'L. Increase to at least ' . number_format($result['minWater'], 1) . 'L per day</p>';
                    echo '<p class="text-gray-700">• Monitor hydration throughout the day</p>';
                }

                if ($proteinIntake < $result['minProtein']) {
                    echo '<p class="text-gray-700">• Your current protein intake is ' . number_format($proteinIntake, 1) . 'g. Increase to at least ' . number_format($result['minProtein'], 1) . 'g per day</p>';
                    echo '<p class="text-gray-700">• Consider adding protein-rich foods or supplements to your diet</p>';
                }

                if ($isInjury) {
                    echo '<p class="text-gray-700">• Consult with a healthcare professional</p>';
                    echo '<p class="text-gray-700">• Follow RICE protocol (Rest, Ice, Compression, Elevation)</p>';
                    echo '<p class="text-gray-700">• Avoid aggravating the injured area</p>';
                }
                ?>
            </div>
        </div>
        <?php endif; ?>

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

    <?php if ($hasResearchSources): ?>
    <div class="bg-white/95 backdrop-blur-md border border-white/20 p-8 rounded-2xl shadow-2xl w-full max-w-md order-2 lg:order-3">
        <h3 class="text-xl font-semibold text-green-700 mb-4">Research Sources</h3>
        <div class="space-y-4">
            <?php if ($age >= 65): ?>
            <div class="bg-white p-4 rounded-lg shadow-md border border-green-100">
                <p class="text-sm text-green-700 mb-2">This research applies to you because you are over 65 years old. The study specifically focuses on recovery patterns in older adults, providing age-appropriate recommendations for rest periods and recovery strategies.</p>
                <a href="https://link.springer.com/article/10.1186/s40798-023-00597-1" target="_blank" class="text-sm text-green-600 hover:text-green-700 transition-colors duration-200">
                    Hayes, Eleanor Jayne, et al. "Recovery from Resistance Exercise in Older Adults: A Systematic Scoping Review." Sports Medicine-Open 9.1 (2023): 51.
                </a>
            </div>
            <?php elseif ($age >= 40): ?>
            <div class="bg-white p-4 rounded-lg shadow-md border border-green-100">
                <p class="text-sm text-green-700 mb-2">This research applies to you because you are between 40-64 years old. The study examines recovery rates in middle-aged athletes, providing insights into optimal rest periods for this age group.</p>
                <a href="https://www.frontiersin.org/journals/physiology/articles/10.3389/fphys.2022.916924/full" target="_blank" class="text-sm text-green-600 hover:text-green-700 transition-colors duration-200">
                    Markus, Irit, et al. "Age differences in recovery rate following an aerobic-based exercise protocol inducing muscle damage among amateur, male athletes." Frontiers in Physiology 13 (2022): 916924.
                </a>
            </div>
            <?php endif; ?>

            <?php if (isset($result['waterIntake']) && $result['waterIntake'] < ($weight * 0.02)): ?>
            <div class="bg-white p-4 rounded-lg shadow-md border border-green-100">
                <p class="text-sm text-green-700 mb-2">This research applies to you because your water intake is below the recommended level. Proper hydration is crucial for recovery and performance.</p>
                <a href="https://www.hydrationinfo.com" target="_blank" class="text-sm text-green-600 hover:text-green-700 transition-colors duration-200">
                    Learn more about hydration and its impact on recovery.
                </a>
            </div>
            <?php endif; ?>

            <?php if (isset($result['sleep']) && $result['sleep'] < 7): ?>
            <div class="bg-white p-4 rounded-lg shadow-md border border-green-100">
                <p class="text-sm text-green-700 mb-2">This research is relevant because your sleep duration is below the recommended 7 hours.</p>
                <a href="https://journals.lww.com/acsm-csmr/fulltext/2021/06000/sleep_and_injury_risk.3.aspx" target="_blank" class="text-sm text-green-600 hover:text-green-700 transition-colors duration-200">
                    Huang, Kevin, and Joseph Ihm. "Sleep and injury risk." Current sports medicine reports 20.6 (2021): 286-290.
                </a>
                <?php if (isset($result['recovery']) && $result['recovery'] === 'true'): ?>
                <a href="https://sponet.fi/Record/4074352" target="_blank" class="text-sm text-green-600 hover:text-green-700 transition-colors duration-200 block mt-2">
                    Vermeir, P. "The impact of sleep on the recovery of sport injuries." (2021).
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <?php if (isset($result['proteinIntake']) && $result['proteinIntake'] < ($weight * 0.8)): ?>
            <div class="bg-white p-4 rounded-lg shadow-md border border-green-100">
                <p class="text-sm text-green-700 mb-2">This research is relevant because your protein intake is below the recommended minimum of <?php echo round($weight * 0.8); ?>g daily.</p>
                <a href="https://www.sciencedirect.com/science/article/abs/pii/S0002916523194779" target="_blank" class="text-sm text-green-600 hover:text-green-700 transition-colors duration-200">
                    Standing Committee on the Scientific Evaluation of Dietary Reference Intakes, et al. "Dietary reference intakes for energy, carbohydrate, fiber, fat, fatty acids, cholesterol, protein, and amino acids." National Academies Press, 2005.
                </a>
            </div>
            <?php endif; ?>

            <?php if (isset($result['intensity']) && $result['intensity'] === 'high'): ?>
            <div class="bg-white p-4 rounded-lg shadow-md border border-green-100">
                <p class="text-sm text-green-700 mb-2">This research is relevant because you engage in high-intensity workouts, which require specific recovery considerations.</p>
                <a href="https://www.mdpi.com/1660-4601/20/22/7082" target="_blank" class="text-sm text-green-600 hover:text-green-700 transition-colors duration-200">
                    Leite, Carine DFC, et al. "Exercise-induced muscle damage after a high-intensity interval exercise session: systematic review." International journal of environmental research and public health 20.22 (2023): 7082.
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</body>
</html> 