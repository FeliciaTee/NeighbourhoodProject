<?php
session_start();

// Check login
if (!isset($_SESSION['resident_id'])) {
    header("Location: login.php");
    exit();
}

// Database setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "neighborhoodproject";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Sanitize POST inputs
$notification_id = isset($_POST['notification_id']) ? intval($_POST['notification_id']) : 0;
$image_path = isset($_POST['image_path']) ? $_POST['image_path'] : '';

if ($notification_id <= 0) {
    die("Invalid notification ID.");
}

// Check ownership
$stmt = $conn->prepare("SELECT resident_id, image_path FROM user_notifications WHERE notification_id = ?");
$stmt->bind_param("i", $notification_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Notification not found.");
}

$row = $result->fetch_assoc();
if ($row['resident_id'] != $_SESSION['resident_id']) {
    die("Unauthorized: You can only delete your own notification.");
}

// Delete image from server
$imageFilePath = $row['image_path'];
if (!empty($imageFilePath) && file_exists($imageFilePath)) {
    unlink($imageFilePath); // Delete the image file
}

// Delete from database
$deleteStmt = $conn->prepare("DELETE FROM user_notifications WHERE notification_id = ?");
$deleteStmt->bind_param("i", $notification_id);
$deleteStmt->execute();

// Redirect back with optional message
header("Location: notification.php");
exit();
?>
