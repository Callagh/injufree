<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    $_SESSION['error'] = "Please log in to access this page.";
    header("Location: /src/index.php");
    exit();
}

if (!isset($_SESSION['restResult'])) {
    header("Location: /src/homePage.php");
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
    header("Location: /src/homePage.php");
    exit();
}



// Check if there are any personalized recommendations
$hasRecommendations = ($age >= 65 || $age >= 40 || $score < 0.6 || $score < 0.8 || $sleep < 7 || 
                      $waterIntake < $result['minWater'] || $proteinIntake < $result['minProtein']);

// Check if there are any research sources
$hasResearchSources = true; // Always show research sources

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
            <img src="/images/logo.png" 
                 alt="Injufree Logo" 
                 class="w-[50px] h-[50px] mr-4">
            <h1 class="text-3xl font-bold cursor-pointer hover:text-green-100 transition-colors duration-200" onclick="window.location.href='/src/homePage.php'">
                Injufree
            </h1>
        </div>

        <img src="/images/homebutton.png" 
             alt="Home" 
             class="w-[50px] h-[50px] cursor-pointer hover:opacity-90 transition-opacity duration-200"
             onclick="window.location.href='/src/homePage.php'">
    </header>

    <div class="container mx-auto px-4 py-20 flex flex-col lg:flex-row lg:space-x-8 space-y-8 lg:space-y-0">
        <!-- Personalized Recommendations Card -->
        <?php if ($hasRecommendations): ?>
        <div class="bg-white/95 backdrop-blur-md border border-white/20 p-8 rounded-2xl shadow-2xl w-full lg:order-1">
            <h2 class="text-2xl font-bold text-green-700 mb-4">Personalized Recommendations</h2>
            <div class="space-y-4">
                <?php
                if ($isInjury) {
                    echo '<p class="text-gray-700 font-bold">• As you were injured you should consult a professional.</p>';
                    echo '<p class="text-gray-700 font-bold">• If something hurts, stop immediately.</p>';                   
                    echo '<p class="text-gray-700">• Follow RICE protocol (Rest, Ice, Compression, Elevation)</p>';
                    echo '<p class="text-gray-700">• Avoid aggravating the injured area</p>';
                }

                if ($age >= 65) {
                    echo '<p class="text-gray-700">• Focus on low-impact exercises and proper form</p>';
                    echo '<p class="text-gray-700">• Include proper warm-up and cool-down routines</p>';
                } elseif ($age >= 40) {
                    echo '<p class="text-gray-700">• Include proper warm-up and cool-down routines</p>';
                }

                if ($score < 0.6) {
                    echo '<p class="text-gray-700">• Consider taking a complete rest day</p>';
                    echo '<p class="text-gray-700">• Focus on hydration and nutrition</p>';
                } elseif ($score < 0.8) {
                    echo '<p class="text-gray-700">• Light activity or active recovery recommended</p>';
                }

                if ($sleep < 7) {
                    echo '<p class="text-gray-700">• Prioritize getting 7-9 hours of sleep and maintaining a consistent sleep schedule</p>';
                }

                echo '<p class="text-gray-700">• Aim to intake ' . number_format($result['minWater'], 1) . 'L of water per day</p>';
                if ($waterIntake < $result['minWater']) {
                    echo '<p class="text-gray-700">• Your current water intake is ' . number_format($waterIntake, 1) . 'L. Monitor hydration throughout the day</p>';
                }

                echo '<p class="text-gray-700">• Aim to intake ' . number_format($result['minProtein'], 1) . 'g of protein per day</p>';
                if ($proteinIntake < $result['minProtein']) {
                    echo '<p class="text-gray-700">• Your current protein intake is ' . number_format($proteinIntake, 1) . 'g. Consider adding protein-rich foods or supplements to your diet</p>';
                }
                ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Rest Recommendation Card -->
        <div class="bg-white/95 backdrop-blur-md border border-white/20 p-8 rounded-2xl shadow-2xl w-full lg:order-2">
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

            <div class="mt-8 flex justify-center space-x-4">
                <button onclick="window.location.href='/src/homePage.php'" 
                        class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                    Calculate Again
                </button>
                <button onclick="window.location.href='/src/homePage.php'" 
                        class="bg-white border-2 border-green-500 text-green-600 hover:bg-green-50 font-bold py-3 px-6 rounded-xl shadow-lg transition-all duration-200 transform hover:scale-[1.02]">
                    Back to Home
                </button>
            </div>
        </div>

        <!-- Research Sources Card -->
        <?php if ($hasResearchSources): ?>
        <div class="bg-white/95 backdrop-blur-md border border-white/20 p-8 rounded-2xl shadow-2xl w-full lg:order-3">
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
                    <a href="https://www.gssiweb.org/sports-science-exchange/article/dehydration-and-exercise-induced-muscle-damage-implications-for-recovery/1000" target="_blank" class="text-sm text-green-600 hover:text-green-700 transition-colors duration-200">
                    King, Michelle A., and Lindsay B. Baker. "Dehydration and exercise-induced muscle damage: Implications for recovery." Sports Science Exchange 29.207 (2020): 1.
                    </a>
                </div>
                <?php endif; ?>

                <?php if (isset($result['sleep']) && $result['sleep'] < 7): ?>
                <div class="bg-white p-4 rounded-lg shadow-md border border-green-100">
                    <p class="text-sm text-green-700 mb-2">Sleeping less than 7 hours leads to 1.7 times greater risk of injury. Read about it here.</p>
                    <a href="https://journals.lww.com/acsm-csmr/fulltext/2021/06000/sleep_and_injury_risk.3.aspx" target="_blank" class="text-sm text-green-600 hover:text-green-700 transition-colors duration-200">
                        Huang, Kevin, and Joseph Ihm. "Sleep and injury risk." Current sports medicine reports 20.6 (2021): 286-290.
                    </a>
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

                <?php if ($isInjury): ?>
                <div class="bg-white p-4 rounded-lg shadow-md border border-green-100">
                    <p class="text-sm text-green-700 mb-2">This research applies because you are recovering from injury.</p>
                    <a href="https://link.springer.com/article/10.1007/s40279-016-0667-x" target="_blank" class="text-sm text-green-600 hover:text-green-700 transition-colors duration-200">
                        Hickey, Jack T., et al. "Criteria for progressing rehabilitation and determining return-to-play clearance following hamstring strain injury: a systematic review." Sports medicine 47 (2017): 1375-1387.
                    </a>
                </div>
                <?php endif; ?>

                <!-- General research for all cases -->
                <div class="bg-white p-4 rounded-lg shadow-md border border-green-100">
                    <p class="text-sm text-green-700 mb-2">This research is useful if you are thinking of taking a longer break.</p>
                    <a href="https://www.mdpi.com/2813-0413/1/1/1" target="_blank" class="text-sm text-green-600 hover:text-green-700 transition-colors duration-200">
                        Encarnação, Irismar GA, et al. "Effects of detraining on muscle strength and hypertrophy induced by resistance training: a systematic review." Muscles 1.1 (2022): 1-15.
                    </a>
                </div>

                <?php if ($result['rest'] < 5): ?>
                <div class="bg-white p-4 rounded-lg shadow-md border border-green-100">
                    <p class="text-sm text-green-700 mb-2">Does the result seem low? Read here why high frequency is important for strength training.</p>
                    <a href="https://onlinelibrary.wiley.com/doi/full/10.1002/ejsc.12055" target="_blank" class="text-sm text-green-600 hover:text-green-700 transition-colors duration-200">
                        Pedersen, Helene, et al. "High‐frequency resistance training improves maximal lower‐limb strength more than low frequency." European Journal of Sport Science 24.5 (2024): 557-565.
                    </a>
                </div>
                <?php endif; ?>

                <?php if ($age > 65): ?>
                <div class="bg-white p-4 rounded-lg shadow-md border border-green-100">
                    <p class="text-sm text-green-700 mb-2">It is challenging to recommend recovery rates for athletes above 65, for a more detailed look, view the below.</p>
                    <a href="https://link.springer.com/article/10.1186/s40798-023-00597-1" target="_blank" class="text-sm text-green-600 hover:text-green-700 transition-colors duration-200">
                        Hayes, Eleanor Jayne, et al. "Recovery from Resistance Exercise in Older Adults: A Systematic Scoping Review." Sports Medicine-Open 9.1 (2023): 51.
                    </a>
                </div>
                <?php endif; ?>

                <?php if ($age > 25): ?>
                <div class="bg-white p-4 rounded-lg shadow-md border border-green-100">
                    <p class="text-sm text-green-700 mb-2">Does this time seem short? Enjoy it while you're young! View the below and see the advantage you have due to age.</p>
                    <a href="https://www.mdpi.com/2075-4663/7/6/132" target="_blank" class="text-sm text-green-600 hover:text-green-700 transition-colors duration-200">
                        Fernandes, John FT, Kevin L. Lamb, and Craig Twist. "Exercise-induced muscle damage and recovery in young and middle-aged males with different resistance training experience." Sports 7.6 (2019): 132.
                    </a>
                </div>
                <?php endif; ?>

                <?php if ($sleep > 7 && $isInjury): ?>
                <div class="bg-white p-4 rounded-lg shadow-md border border-green-100">
                    <p class="text-sm text-green-700 mb-2">This article shows why recovering from injury and low sleep can prolong recovery:</p>
                    <a href="https://sponet.de/Record/4074352" target="_blank" class="text-sm text-green-600 hover:text-green-700 transition-colors duration-200">
                        Vermeir, P. "The impact of sleep on the recovery of sport injury..." (2021).
                    </a>
                </div>
                <?php endif; ?>

                <?php if ($proteinIntake < $result['minProtein']): ?>
                <div class="bg-white p-4 rounded-lg shadow-md border border-green-100">
                    <p class="text-sm text-green-700 mb-2">Protein plays a key role in muscle recovery, research it here:</p>
                    <a href="https://www.nature.com/articles/s41430-022-01250-y" target="_blank" class="text-sm text-green-600 hover:text-green-700 transition-colors duration-200">
                        Pearson, Alice G., Karen Hind, and Lindsay S. Macnaughton. "The impact of dietary protein supplementation on recovery from resistance exercise-induced muscle damage: A systematic review with meta-analysis." European Journal of Clinical Nutrition 77.8 (2023): 767-783.
                    </a>
                </div>
                <?php endif; ?>

                <?php if ($proteinIntake < $result['minProtein'] && $isInjury): ?>
                <div class="bg-white p-4 rounded-lg shadow-md border border-green-100">
                    <p class="text-sm text-green-700 mb-2">Protein plays a big role in recovering from injury as shown here:</p>
                    <a href="https://www.sciencedirect.com/science/article/pii/S2161831322003234" target="_blank" class="text-sm text-green-600 hover:text-green-700 transition-colors duration-200">
                        Howard, Emily E., et al. "Skeletal muscle disuse atrophy and the rehabilitative role of protein in recovery from musculoskeletal injury." Advances in nutrition 11.4 (2020): 989-1001.
                    </a>
                </div>
                <?php endif; ?>

                <?php
                // Count the number of research cards shown
                $researchCardCount = 0;
                if ($isInjury) $researchCardCount++;
                $researchCardCount++; // For the general case
                if ($result['rest'] < 5) $researchCardCount++;
                if ($age > 65) $researchCardCount++;
                if ($age > 25) $researchCardCount++;
                if ($sleep > 7 && $isInjury) $researchCardCount++;
                if ($proteinIntake < $result['minProtein']) $researchCardCount++;
                if ($proteinIntake < $result['minProtein'] && $isInjury) $researchCardCount++;

                // Add gender-specific research if less than 3 cards are shown
                if ($researchCardCount < 3):
                ?>
                <div class="bg-white p-4 rounded-lg shadow-md border border-green-100">
                    <p class="text-sm text-green-700 mb-2">Did you know men are better at cardio recovery while women are better at weight recovery? Read up here.</p>
                    <div class="space-y-2">
                        <a href="https://journals.humankinetics.com/view/journals/ijspp/16/6/article-p752.xml" target="_blank" class="text-sm text-green-600 hover:text-green-700 transition-colors duration-200 block">
                            Hottenrott, Laura, et al. "Age-and sex-related differences in recovery from high-intensity and endurance exercise: a brief review." International journal of sports physiology and performance 16.6 (2021): 752-762.
                        </a>
                        <a href="https://physoc.onlinelibrary.wiley.com/doi/abs/10.1113/JP278699" target="_blank" class="text-sm text-green-600 hover:text-green-700 transition-colors duration-200 block">
                            Ansdell, Paul, et al. "Sex differences in fatigability and recovery relative to the intensity–duration relationship." The Journal of physiology 597.23 (2019): 5577-5595.
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</body>
</html> 