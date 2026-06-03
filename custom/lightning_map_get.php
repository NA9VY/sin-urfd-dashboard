<?php
header('Content-Type: application/json');

$strikesFile = '/home/pi/lightning_data/lightning_strikes.json';

if (!file_exists($strikesFile)) {
    echo json_encode([]);
    exit;
}

$strikes = json_decode(file_get_contents($strikesFile), true) ?? [];

// Increased to 60 minutes for testing
$now = time();
$recentStrikes = array_filter($strikes, function($s) use ($now) {
    return ($now - $s['timestamp']) <= 3600; // 60 minutes
});

echo json_encode(array_values($recentStrikes));
?>
