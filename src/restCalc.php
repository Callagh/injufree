<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $age = isset($_POST['age']) ? (int)$_POST['age'] : null;
    $weight = isset($_POST['weight']) ? (float)$_POST['weight'] : null;
    $waterIntake = isset($_POST['waterIntake']) ? (float)$_POST['waterIntake'] : null;
    $sleep = isset($_POST['sleep']) ? (float)$_POST['sleep'] : null;
    $recovery = isset($_POST['recovery']) ? $_POST['recovery'] : 'false';
    $intensity = isset($_POST['intensity']) ? $_POST['intensity'] : 'medium';
    $experience = isset($_POST['experience']) ? $_POST['experience'] : 'beginner';
    $proteinIntake = isset($_POST['proteinIntake']) ? (float)$_POST['proteinIntake'] : null;
    $isInjury = isset($_POST['isInjury']) ? $_POST['isInjury'] === 'true' : false;

    $details = [];
    $totalScore = 0;
    $factors = 0;

    
    $weights = [
        'age' => 0.15,
        'water' => 0.2,
        'sleep' => 0.2,
        'intensity' => 0.2,
        'experience' => 0.15,
        'protein' => 0.1
    ];

    if ($age !== null) {
        $ageScore = 1.0;
        if ($age >= 65) {
            $ageScore = 0.7;
        } elseif ($age >= 40) {
            $ageScore = 0.8;
        }
        $details['age'] = $ageScore;
        $totalScore += $ageScore * $weights['age'];
        $factors += $weights['age'];
    }

    if ($waterIntake !== null && $weight !== null) {
        $minWater = $weight * 0.02;
        $waterScore = min(1.0, $waterIntake / $minWater);
        $details['water'] = $waterScore;
        $totalScore += $waterScore * $weights['water'];
        $factors += $weights['water'];
    }

    if ($sleep !== null) {
        $sleepScore = min(1.0, $sleep / 7);
        $details['sleep'] = $sleepScore;
        $totalScore += $sleepScore * $weights['sleep'];
        $factors += $weights['sleep'];
    }

    if ($intensity === 'high') {
        $details['intensity'] = 0.6;
        $totalScore += 0.6 * $weights['intensity'];
        $factors += $weights['intensity'];
    }

    if ($experience === 'beginner') {
        $details['experience'] = 0.7;
        $totalScore += 0.7 * $weights['experience'];
        $factors += $weights['experience'];
    }

    if ($proteinIntake !== null && $weight !== null) {
        $minProtein = $weight * 0.8;
        $proteinScore = min(1.0, $proteinIntake / $minProtein);
        $details['protein'] = $proteinScore;
        $totalScore += $proteinScore * $weights['protein'];
        $factors += $weights['protein'];
    }

    $finalScore = $factors > 0 ? $totalScore / $factors : 0;

    $restDays = 1;
    if ($finalScore < 0.6) {
        $restDays = 3;
    } elseif ($finalScore < 0.8) {
        $restDays = 2;
    }

    if ($isInjury) {
        $restDays = max(3, $restDays + 1);
    }

    $result = [
        'rest' => $restDays,
        'score' => $finalScore,
        'details' => $details,
        'minWater' => $minWater,
        'waterIntake' => $waterIntake,
        'sleep' => $sleep,
        'recovery' => $recovery,
        'intensity' => $intensity,
        'experience' => $experience,
        'proteinIntake' => $proteinIntake,
        'isInjury' => $isInjury,
        'weight' => $weight
    ];

    $_SESSION['restResult'] = $result;
    header("Location: results.php");
    exit();
}
?>