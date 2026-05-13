<?php
session_start();
require_once('includes/config.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Check if required data is provided
if (!isset($_POST['date']) || !isset($_POST['mood'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
    exit();
}

$userId = $_SESSION['id'];
$date = $_POST['date'];
$mood = $_POST['mood'];

// Check if entry already exists for this date
$checkQuery = mysqli_query($con, "SELECT id FROM mood_entries WHERE user_id = '$userId' AND mood_date = '$date'");

if (mysqli_num_rows($checkQuery) > 0) {
    // Update existing record
    $row = mysqli_fetch_assoc($checkQuery);
    $entryId = $row['id'];
    $updateQuery = mysqli_query($con, "UPDATE mood_entries SET mood = '$mood', last_updated = NOW() WHERE id = '$entryId'");
    
    if ($updateQuery) {
        echo json_encode(['success' => true, 'message' => 'Mood updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update mood: ' . mysqli_error($con)]);
    }
} else {
    // Insert new record
    $insertQuery = mysqli_query($con, "INSERT INTO mood_entries (user_id, mood_date, mood, created_at, last_updated) 
                                       VALUES ('$userId', '$date', '$mood', NOW(), NOW())");
    
    if ($insertQuery) {
        echo json_encode(['success' => true, 'message' => 'Mood saved successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save mood: ' . mysqli_error($con)]);
    }
}
?>
