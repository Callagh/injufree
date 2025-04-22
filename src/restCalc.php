/**
 * restCalc.php
 * 
 * This file calculates recommended rest days based on various health and fitness factors.
 * 
 * Key factors considered:
 * - Age (20% weight)
 * - Water intake (19% weight)
 * - Sleep duration (25% weight)
 * - Exercise intensity (15% weight)
 * - Experience level (8% weight)
 * - Protein intake (13% weight)
 * 
 * Special considerations:
 * - Injury status affects rest recommendations
 * - Age-based adjustments for recovery time
 * - Minimum and maximum rest day ranges
 * - Weighted scoring system for overall health assessment
 * 
 * Output:
 * - Base rest days for non-injured users
 * - Range of rest days for injured users
 * - Detailed scoring breakdown
 */

<?php
// Initialize session and enable error reporting
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if user is logged in, redirect if not
if (!isset($_SESSION['username'])) {
    $_SESSION['error'] = "Please log in to access this page.";
    header("Location: /src/index.php");
    exit();
}

// Process POST request for rest calculation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get and validate input parameters with default values
        $age = isset($_POST['age']) ? (int)$_POST['age'] : null;
        $weight = isset($_POST['weight']) ? (float)$_POST['weight'] : null;
        $waterIntake = isset($_POST['waterIntake']) ? (float)$_POST['waterIntake'] : null;
        $sleep = isset($_POST['sleep']) ? (float)$_POST['sleep'] : null;
        $intensity = isset($_POST['intensity']) ? $_POST['intensity'] : 'medium';
        $experience = isset($_POST['experience']) ? $_POST['experience'] : 'beginner';
        $proteinIntake = isset($_POST['proteinIntake']) ? (float)$_POST['proteinIntake'] : null;
        $isInjury = isset($_POST['recovery']) ? $_POST['recovery'] === 'true' : false;

        // Initialize calculation variables
        $details = [];
        $totalScore = 0;
        $totalWeight = 0;
        $minWater = 0;

        // Define weights for different factors in the calculation
        $weights = [
            'age' => 0.2,
            'water' => 0.19,
            'sleep' => 0.25,
            'intensity' => 0.15,
            'experience' => 0.08,
            'protein' => 0.13
        ];

        // Calculate age score based on age ranges
        $ageScore = 1.0;
        if ($age !== null) {
            if ($age <= 25) {
                $ageScore = 1.0;
            } elseif ($age <= 32) {
                $ageScore = 0.95;
            } elseif ($age <= 39) {
                $ageScore = 0.9;
            } elseif ($age <= 52) {
                $ageScore = 0.8;
            } elseif ($age <= 64) {
                $ageScore = 0.6;
            } elseif ($age <= 79) {
                $ageScore = 0.5;
            } else {
                $ageScore = 0.4;
            }
            $details['age'] = $ageScore;
            $totalScore += $ageScore * $weights['age'];
            $totalWeight += $weights['age'];
        }

        // Calculate water intake score based on weight
        if ($waterIntake !== null && $weight !== null) {
            $minWater = $weight * 0.02;
            if ($waterIntake == $minWater) {
                $waterScore = 0.5;
            } elseif ($waterIntake > $minWater) {
                $waterScore = min(1.0, 0.5 + (($waterIntake - $minWater) / $minWater) * 0.5);
            } else {
                $waterScore = max(0.3, 0.5 * ($waterIntake / $minWater));
            }
            $details['water'] = $waterScore;
            $totalScore += $waterScore * $weights['water'];
            $totalWeight += $weights['water'];
        }

        // Calculate sleep score based on hours
        if ($sleep !== null) {
            if ($sleep >= 8) {
                $sleepScore = 1.0;
            } elseif ($sleep == 7) {
                $sleepScore = 0.9;
            } else {
                $sleepScore = max(0.0, 0.9 - (7 - $sleep) * 0.15);
            }
            $details['sleep'] = $sleepScore;
            $totalScore += $sleepScore * $weights['sleep'];
            $totalWeight += $weights['sleep'];
        }

        // Calculate exercise intensity score
        if ($intensity === 'high') {
            $details['intensity'] = 0.33;
            $totalScore += 0.8 * $weights['intensity'];
            $totalWeight += $weights['intensity'];
        } elseif ($intensity === 'medium') {
            $details['intensity'] = 0.66;
            $totalScore += 0.87 * $weights['intensity'];
            $totalWeight += $weights['intensity'];
        } else {
            $details['intensity'] = 1.0;
            $totalScore += 0.95 * $weights['intensity'];
            $totalWeight += $weights['intensity'];
        }

        // Calculate experience level score
        if ($experience === 'beginner') {
            $details['experience'] = 0.33;
            $totalScore += 0.85 * $weights['experience'];
            $totalWeight += $weights['experience'];
        } elseif ($experience === 'intermediate') {
            $details['experience'] = 0.66;
            $totalScore += 0.93 * $weights['experience'];
            $totalWeight += $weights['experience'];
        } else {
            $details['experience'] = 1.0;
            $totalScore += 1.0 * $weights['experience'];
            $totalWeight += $weights['experience'];
        }

        // Calculate protein intake score based on weight
        if ($proteinIntake !== null && $weight !== null) {
            $minProtein = $weight * 0.8;
            $proteinScore = 0.0;
            if ($proteinIntake >= $minProtein) {
                if ($proteinIntake == $minProtein) {
                    $proteinScore = 0.65;
                } else {
                    $proteinScore = min(1.0, 0.65 + (($proteinIntake - $minProtein) / ($minProtein * 0.5)) * 0.35);
                }
            } else {
                $proteinScore = max(0.3, 0.65 - (($minProtein - $proteinIntake) / $minProtein) * 0.35);
            }
            $details['protein'] = $proteinScore;
            $totalScore += $proteinScore * $weights['protein'];
            $totalWeight += $weights['protein'];
        }

        // Calculate final score and base rest days
        $finalScore = $totalWeight > 0 ? $totalScore / $totalWeight : 0;
        $baseRestDays = max(1, min(10, round(10 - (9 * $finalScore))));

        // Adjust rest days for injured users
        if ($isInjury) {
            // Calculate additional rest days based on age
            $additionalDays = 0;
            if ($age >= 65) {
                $additionalDays = 4;
            } elseif ($age >= 50) {
                $additionalDays = 3;
            } elseif ($age >= 40) {
                $additionalDays = 2;
            } else {
                $additionalDays = 1;
            }

            // Calculate minimum and maximum rest days
            $minRestDays = min(10, $baseRestDays + $additionalDays);
            $maxRestDays = min(10, $baseRestDays + $additionalDays + 2);
            
            // Ensure minimum rest days is at least 3
            $minRestDays = max(3, $minRestDays);
            // Ensure maximum is at least one more than minimum
            $maxRestDays = max($minRestDays + 1, $maxRestDays); 
            
            // If max equals min, increment max
            if ($maxRestDays === $minRestDays) {
                $maxRestDays = min(10, $maxRestDays + 1);
            }
            
            $restDays = "$minRestDays-$maxRestDays";
        } else {
            $restDays = $baseRestDays;
        }

        // Store results in session
        $result = [
            'rest' => $restDays,
            'score' => $finalScore,
            'details' => $details,
            'minWater' => $minWater,
            'waterIntake' => $waterIntake,
            'sleep' => $sleep,
            'intensity' => $intensity,
            'experience' => $experience,
            'proteinIntake' => $proteinIntake,
            'isInjury' => $isInjury,
            'weight' => $weight,
            'minProtein' => $minProtein
        ];

        $_SESSION['restResult'] = $result;
        header("Location: /src/results.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "An error occurred: " . $e->getMessage();
        header("Location: /src/restCalc.php");
        exit();
    }
}
?>