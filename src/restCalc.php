<?php
session_start();

$genderAtBirth = $_POST['genderAtBirth'];
$age = $_POST['age'];
$waterIntake = $_POST['waterIntake'];
$sleep = $_POST['sleep'];
$weight = $_POST['weight'];
$recovery = $_POST['recovery'];
$intensity = $_POST['intensity'];
$experience = $_POST['experience'];

function calculateRest($genderAtBirth, $age, $waterIntake, $sleep, $weight, $recovery, $intensity, $experience) {
    $weights = [
        'age' => 0.15,
        'water' => 0.15,
        'sleep' => 0.20,
        'weight' => 0.10,
        'recovery' => 0.15,
        'intensity' => 0.15,
        'experience' => 0.10
    ];

    $scores = [];

    $ageScore = 1.0;
    if ($age < 18) {
        $ageScore = 0.7;
    } elseif ($age > 35) {
        $ageScore = max(0.5, 1.0 - (($age - 35) * 0.02));
    }
    $scores['age'] = $ageScore;

    $waterScore = 1.0;
    if ($waterIntake < 1.5) {
        $waterScore = 0.5;
    } elseif ($waterIntake < 2.5) {
        $waterScore = 0.7;
    } elseif ($waterIntake > 3.5) {
        $waterScore = 0.8;
    }
    $scores['water'] = $waterScore;

    $sleepScore = 1.0;
    if ($sleep < 6) {
        $sleepScore = 0.5;
    } elseif ($sleep < 7) {
        $sleepScore = 0.7;
    } elseif ($sleep > 9) {
        $sleepScore = 0.8;
    }
    $scores['sleep'] = $sleepScore;

    $height = 1.75;
    $bmi = $weight / ($height * $height);
    $weightScore = 1.0;
    if ($bmi < 18.5) {
        $weightScore = 0.7;
    } elseif ($bmi > 25) {
        $weightScore = 0.8;
    }
    $scores['weight'] = $weightScore;

    $scores['recovery'] = $recovery === 'true' ? 0.7 : 1.0;

    $intensityScores = [
        'high' => 0.6,
        'medium' => 0.8,
        'low' => 1.0
    ];
    $scores['intensity'] = $intensityScores[$intensity];

    $experienceScores = [
        'experienced' => 0.8,
        'intermediate' => 0.9,
        'beginner' => 1.0
    ];
    $scores['experience'] = $experienceScores[$experience];

    $weightedScore = 0;
    foreach ($weights as $factor => $weight) {
        $weightedScore += $scores[$factor] * $weight;
    }

    $restDays = round(28 - ($weightedScore * 27));

    return [
        'rest' => $restDays,
        'score' => $weightedScore,
        'details' => $scores
    ];
}

$result = calculateRest($genderAtBirth, $age, $waterIntake, $sleep, $weight, $recovery, $intensity, $experience);

$_SESSION['restResult'] = $result;

header("Location: results.php");
exit();
?>