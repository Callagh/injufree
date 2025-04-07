<?php
session_start();

$name = isset($_SESSION['name']) ? $_SESSION['name'] : "Guest";
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
        <div class="flex items-center gap-4">
            <img src="../images/logo.png" alt="Injufree Logo" 
                 class="w-12 h-12 opacity-90 hover:opacity-100 transition-opacity duration-200">
            <h1 class="text-3xl font-bold cursor-pointer hover:text-green-100 transition-colors duration-200" onclick="window.location.href='homePage.php'">
                Injufree
            </h1>
        </div>

        <img src="../images/homeButton.png" 
             alt="Home" 
             class="w-[50px] h-[50px] cursor-pointer hover:opacity-90 transition-opacity duration-200"
             onclick="window.location.href='homePage.php'">
    </header>

    <div class="flex flex-col lg:flex-row justify-center items-start gap-8 w-full max-w-6xl mt-20 px-4 lg:px-0">
        <div class="bg-white/95 backdrop-blur-md border border-white/20 p-8 rounded-2xl shadow-2xl w-full max-w-md order-3 lg:order-1">
            <h3 class="text-xl font-semibold text-green-700 mb-4">Research Resources</h3>
            <p class="text-green-600 mb-4">These articles discuss why injury prevention is important:</p>
            <ul class="space-y-6 text-green-600">
                <li class="flex items-start">
                    <span class="text-green-600 mr-2">•</span>
                    <div class="space-y-2">
                        <span class="font-medium text-green-700">This article proves why the rest calculator is needed:</span>
                        <div class="pl-4 border-l-2 border-green-200">
                            <a href="https://onlinelibrary.wiley.com/doi/full/10.1111/j.1600-0838.2010.01152.x" target="_blank" class="text-sm italic hover:text-green-800 transition-colors duration-200">
                                Orlando, C., et al. "The effect of rest days on injury rates." Scandinavian journal of medicine & science in sports 21.6 (2011): e64-e71.
                            </a>
                        </div>
                    </div>
                </li>
                <li class="flex items-start">
                    <span class="text-green-600 mr-2">•</span>
                    <div class="space-y-2">
                        <span class="font-medium text-green-700">This article shows why it is important to exercise even after an injury:</span>
                        <div class="pl-4 border-l-2 border-green-200">
                            <a href="https://www.researchgate.net/profile/Paul-Glasziou/publication/8361041_Resting_injured_limbs_delays_recovery_A_systematic_review/links/0deec52d3b0de42725000000/Resting-injured-limbs-delays-recovery-A-systematic-review.pdf" target="_blank" class="text-sm italic hover:text-green-800 transition-colors duration-200">
                                Nash, Charlotte E., et al. "Resting injured limbs delays recovery: a systematic review." Journal of Family Practice 53.9 (2004): 706-706.
                            </a>
                        </div>
                    </div>
                </li>
                <li class="flex items-start">
                    <span class="text-green-600 mr-2">•</span>
                    <div class="space-y-2">
                        <span class="font-medium text-green-700">This article discusses how performance may never return to pre-injured levels, once again showing why injufree is needed:</span>
                        <div class="pl-4 border-l-2 border-green-200">
                            <a href="https://journals.sagepub.com/doi/10.1177/2325967116631949" target="_blank" class="text-sm italic hover:text-green-800 transition-colors duration-200">
                                Dodson, Christopher C., et al. "Anterior cruciate ligament injuries in National Football League athletes from 2010 to 2013: a descriptive epidemiology study." Orthopaedic journal of sports medicine 4.3 (2016): 2325967116631949.
                            </a>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <div class="bg-white/95 backdrop-blur-md border border-white/20 p-8 rounded-2xl shadow-2xl w-full max-w-md order-1 lg:order-2">
            <h2 class="text-3xl font-extrabold text-center text-green-700 mb-8">Rest Calculator</h2>
            
            <form action="restCalc.php" method="post" class="space-y-6">
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline"><?php echo $_SESSION['error']; ?></span>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                
                <div class="space-y-4">
                    <div>
                        <label for="age" class="block text-sm font-medium text-green-700">Age</label>
                        <input type="number" id="age" name="age" required min="0" max="120" class="mt-1 block w-full rounded-md border-green-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label for="weight" class="block text-sm font-medium text-green-700">Weight (kg)</label>
                        <input type="number" id="weight" name="weight" required min="0" max="300" class="mt-1 block w-full rounded-md border-green-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label for="experience" class="block text-sm font-medium text-green-700">Experience Level</label>
                        <select id="experience" name="experience" required class="mt-1 block w-full rounded-md border-green-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="experienced">Experienced</option>
                        </select>
                    </div>
                    <div>
                        <label for="intensity" class="block text-sm font-medium text-green-700">Workout Intensity</label>
                        <select id="intensity" name="intensity" required class="mt-1 block w-full rounded-md border-green-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div>
                        <label for="recovery" class="block text-sm font-medium text-green-700">Recovery Status</label>
                        <select id="recovery" name="recovery" required class="mt-1 block w-full rounded-md border-green-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="false">Normal</option>
                            <option value="true">Recovering from Injury</option>
                        </select>
                    </div>
                    <div>
                        <label for="proteinIntake" class="block text-sm font-medium text-green-700">Average Protein Intake (g/day)</label>
                        <input type="number" id="proteinIntake" name="proteinIntake" required min="0" max="500" class="mt-1 block w-full rounded-md border-green-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label for="waterIntake" class="block text-sm font-medium text-green-700">Water Intake (L/day)</label>
                        <input type="number" id="waterIntake" name="waterIntake" required min="0" max="20" step="0.1" class="mt-1 block w-full rounded-md border-green-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label for="sleep" class="block text-sm font-medium text-green-700">Sleep Hours</label>
                        <input type="number" id="sleep" name="sleep" required min="0" max="24" class="mt-1 block w-full rounded-md border-green-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                </div>
                <button type="submit" class="w-full bg-green-600 text-white py-3 px-4 rounded-xl hover:bg-green-700 transition-colors duration-200 font-semibold">
                    Calculate Rest Days
                </button>
            </form>
        </div>

        <div class="bg-white/95 backdrop-blur-md border border-white/20 p-8 rounded-2xl shadow-2xl w-full max-w-md order-2 lg:order-3">
            <h3 class="text-xl font-semibold text-green-700 mb-4">Field Descriptions</h3>
            <div class="space-y-4 text-green-600">
            <div>
                    <h4 class="font-medium text-green-700">Age</h4>
                    <p class="text-sm">Your current age in years. Used to adjust recovery recommendations based on age-related factors.</p>
            </div>
            <div>
                    <h4 class="font-medium text-green-700">Weight (kg)</h4>
                    <p class="text-sm">Your body weight in kilograms. Used to calculate protein needs and adjust recovery recommendations.</p>
            </div>
            <div>
                    <h4 class="font-medium text-green-700">Experience Level</h4>
                    <p class="text-sm">Your training experience level. Beginners typically need more recovery time than experienced athletes.</p>
            </div>
            <div>
                    <h4 class="font-medium text-green-700">Workout Intensity</h4>
                    <p class="text-sm">The intensity of your typical workouts. Higher intensity workouts require more recovery time.</p>
            </div>
            <div>
                    <h4 class="font-medium text-green-700">Recovery Status</h4>
                    <p class="text-sm">Whether you're recovering from an injury or in normal training. Injury recovery requires longer rest periods.</p>
                </div>
            <div>
                    <h4 class="font-medium text-green-700">Protein Intake (g/day)</h4>
                    <p class="text-sm">Your daily protein consumption in grams. Adequate protein is crucial for muscle recovery.</p>
                </div>
                <div>
                    <h4 class="font-medium text-green-700">Water Intake (L/day)</h4>
                    <p class="text-sm">Your daily water consumption in liters. Proper hydration is essential for recovery.</p>
            </div>
            <div>
                    <h4 class="font-medium text-green-700">Sleep Hours</h4>
                    <p class="text-sm">Your average daily sleep duration in hours. Quality sleep is vital for recovery and performance.</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
