<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['resident_id'])) {
    header("Location: index.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "neighborhoodproject";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sanitize input
$notification_id = $_POST['notification_id'];
$comment_text = trim($_POST['comment_text']);
$resident_id = $_SESSION['resident_id'];

if (!empty($comment_text)) {
    $sql = "INSERT INTO comments (notification_id, resident_id, comment_text, created_at) 
            VALUES (?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $notification_id, $resident_id, $comment_text);

    if ($stmt->execute()) {
        // Optional: Flash success message
        $_SESSION['comment_success'] = "Comment posted successfully!";
    } else {
        $_SESSION['comment_error'] = "Failed to post comment.";
    }

    $stmt->close();
}

$conn->close();

// Redirect back to notification page
header("Location: notification.php");
exit();
?>
