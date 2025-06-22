<?php
session_start();
if (!isset($_SESSION['resident_id'])) {
    header("Location: login.php");
    exit();
}

$mysqli = new mysqli("localhost", "root", "", "your_database_name");
if ($mysqli->connect_errno) {
    die("Database connection failed: " . $mysqli->connect_error);
}

$notification_id = $mysqli->real_escape_string($_POST['notification_id']);
$comment_text = $mysqli->real_escape_string($_POST['comment_text']);
$resident_id = $_SESSION['resident_id'];

$sql = "INSERT INTO comments (notification_id, resident_id, comment_text)
        VALUES ('$notification_id', '$resident_id', '$comment_text')";

if ($mysqli->query($sql)) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
} else {
    echo "Error: " . $mysqli->error;
}
?>
