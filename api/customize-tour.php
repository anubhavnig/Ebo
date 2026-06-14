<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/gemini.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJSON(['success' => false, 'message' => 'Invalid request method'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['destination']) || !isset($input['budget']) || !isset($input['duration'])) {
    sendJSON(['success' => false, 'message' => 'Missing required fields'], 400);
}

$destination = sanitize($input['destination']);
$budget = (int)$input['budget'];
$duration = (int)$input['duration'];
$preferences = sanitize($input['preferences'] ?? '');

if ($budget <= 0 || $duration <= 0) {
    sendJSON(['success' => false, 'message' => 'Invalid budget or duration'], 400);
}

try {
    $gemini = new GeminiAI();
    $itinerary = $gemini->customizeTour($destination, $budget, $duration, $preferences);

    sendJSON([
        'success' => true,
        'itinerary' => $itinerary,
        'destination' => $destination,
        'budget' => $budget,
        'duration' => $duration
    ]);
} catch (Exception $e) {
    sendJSON(['success' => false, 'message' => $e->getMessage()], 500);
}
?>