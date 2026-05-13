<?php
session_start();
require_once('includes/config.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

$userId = $_SESSION['id'];

// Fetch mood data from database
$query = mysqli_query($con, "SELECT mood_date, mood FROM mood_entries WHERE user_id = '$userId'");

if (!$query) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . mysqli_error($con)]);
    exit();
}

$moodData = [];
while ($row = mysqli_fetch_assoc($query)) {
    $moodData[$row['mood_date']] = $row['mood'];
}

echo json_encode(['success' => true, 'moods' => $moodData]);
?>