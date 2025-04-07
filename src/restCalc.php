<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $age = isset($_POST['age']) ? (int)$_POST['age'] : null;
    $weight = isset($_POST['weight']) ? (float)$_POST['weight'] : null;
    $waterIntake = isset($_POST['waterIntake']) ? (float)$_POST['waterIntake'] : null;
    $sleep = isset($_POST['sleep']) ? (float)$_POST['sleep'] : null;
    $intensity = isset($_POST['intensity']) ? $_POST['intensity'] : 'medium';
    $experience = isset($_POST['experience']) ? $_POST['experience'] : 'beginner';
    $proteinIntake = isset($_POST['proteinIntake']) ? (float)$_POST['proteinIntake'] : null;
    $isInjury = isset($_POST['isInjury']) ? $_POST['isInjury'] === 'true' : false;

    $details = [];
    $totalScore = 0;
    $totalWeight = 0;
    $minWater = 0;

    $weights = [
        'age' => 0.2,
        'water' => 0.19,
        'sleep' => 0.25,
        'intensity' => 0.15,
        'experience' => 0.08,
        'protein' => 0.13
    ];

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

    $finalScore = $totalWeight > 0 ? $totalScore / $totalWeight : 0;

    $baseRestDays = max(1, min(10, round(10 - (9 * $finalScore))));

    if ($isInjury) {
        $minRestDays = min(10, $baseRestDays + 2);
        $maxRestDays = min(10, $baseRestDays + 4);
        $restDays = "$minRestDays-$maxRestDays";
    } else {
        $restDays = $baseRestDays;
    }

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
    header("Location: results.php");
    exit();
}
?>