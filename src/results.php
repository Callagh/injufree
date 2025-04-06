<?php
session_start();

if (!isset($_SESSION['restResult'])) {
    header("Location: /src/homePage.php");
    exit();
}

$result = $_SESSION['restResult'];
$restDays = $result['rest'];
$score = $result['score'];
$details = $result['details'];
$minWater = $result['minWater'];
$weight = isset($_SESSION['weight']) ? (float)$_SESSION['weight'] : null;

$age = isset($_SESSION['age']) ? $_SESSION['age'] : null;

if (!$age && isset($_SESSION['DOB']) && $_SESSION['DOB'] !== '') {
    try {
        $DOB = new DateTime($_SESSION['DOB']);
        $currentDate = new DateTime();
        $diff = $DOB->diff($currentDate);
        $age = (int)$diff->y;
    } catch (Exception $e) {
        $age = null;
    }
}

$name = isset($_SESSION['name']) ? $_SESSION['name'] : "Guest";
$DOB = isset($_SESSION['DOB']) ? $_SESSION['DOB'] : null;
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
        <h1 class="text-3xl font-bold cursor-pointer hover:text-green-100 transition-colors duration-200" onclick="window.location.href='/src/homePage.php'">
            Injufree
        </h1>

        <img src="/images/homeButton.png" 
             alt="Home" 
             class="w-[50px] h-[50px] cursor-pointer hover:opacity-90 transition-opacity duration-200"
             onclick="window.location.href='/src/homePage.php'">
    </header>

    <div class="bg-white/95 backdrop-blur-md border border-white/20 p-8 rounded-2xl shadow-2xl w-full max-w-2xl mt-20">
        <h2 class="text-3xl font-extrabold text-center text-green-700 mb-8">Your Rest Recommendation</h2>

        <div class="text-center mb-12">
            <div class="text-6xl font-bold text-green-600 mb-4">
                <?php 
                if ($result['isInjury']) {
                    echo $restDays;
                } else {
                    echo $restDays;
                }
                ?>
            </div>
            <div class="text-xl text-green-700 font-medium">
                <?php 
                if ($result['isInjury']) {
                    echo "Recommended Rest Period (days)";
                } else {
                    echo "Recommended Rest Days";
                }
                ?>
            </div>
            <?php if ($result['isInjury']): ?>
                <p class="text-sm text-yellow-600 mt-2">This is a suggested range. Please consult with your healthcare provider for specific guidance.</p>
            <?php endif; ?>
        </div>

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

        <div class="space-y-6">
            <h3 class="text-xl font-semibold text-green-700 mb-4">Detailed Analysis</h3>
            
            <?php
            $factorLabels = [
                'water' => 'Water Intake',
                'sleep' => 'Sleep',
                'recovery' => 'Recovery Status',
                'intensity' => 'Workout Intensity',
                'experience' => 'Experience Level',
                'protein' => 'Protein Intake'
            ];

            foreach ($details as $factor => $score) {
                if (!isset($factorLabels[$factor])) continue;
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

                <?php if ($details['age'] < 65): ?>
                    <li class="flex items-start">
                        <span class="text-green-600 mr-2">•</span>
                        <span class="text-green-700">If you are in any pain during exercise, it is important to rest and consult with your doctor</span>
                    </li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="mt-8 p-6 bg-green-50 rounded-xl">
            <h3 class="text-xl font-semibold text-green-700 mb-4">Nutrition Guidelines</h3>
            <div class="space-y-4">
                <div>
                    <h4 class="font-semibold text-green-700">Hydration</h4>
                    <p class="text-green-600">Aim to drink at least <?php echo $minWater; ?>L of water daily to support recovery.</p>
                </div>
                <div>
                    <h4 class="font-semibold text-green-700">Protein Intake</h4>
                    <p class="text-green-600">
                        <?php if (isset($result['proteinIntake'])): ?>
                            Your current protein intake: <?php echo $result['proteinIntake']; ?>g daily<br>
                        <?php endif; ?>
                        <?php if (isset($result['protein'])): ?>
                            Recommended protein intake: <?php echo round($weight * 1.4); ?>g daily
                            <?php if (isset($result['proteinIntake']) && $result['proteinIntake'] < ($weight * 1.4)): ?>
                                <br><span class="text-yellow-600">Consider increasing your protein intake to support muscle recovery.</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </p>
                </div>
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
                <p class="text-sm text-green-700 mb-2">This research is relevant because your water intake is below the recommended amount of <?php echo round($weight * 0.02, 1); ?>L daily.</p>
                <a href="https://www.gssiweb.org/sports-science-exchange/article/dehydration-and-exercise-induced-muscle-damage-implications-for-recovery/1000" target="_blank" class="text-sm text-green-600 hover:text-green-700 transition-colors duration-200">
                    King, Michelle A., and Lindsay B. Baker. "Dehydration and exercise-induced muscle damage: Implications for recovery." Sports Science Exchange 29.207 (2020): 1.
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

    <img src="/images/logo.png" alt="Injufree Logo" 
         class="fixed bottom-5 left-5 w-24 opacity-90 hover:opacity-100 transition-opacity duration-200">
</body>
</html> 