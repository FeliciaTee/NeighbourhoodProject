<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "neighborhoodproject";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$resident_id = $_SESSION['resident_id'];
$title = $_POST['title'];
$description = $_POST['description'];

// Handle image upload
$targetDir = "uploads/";
$imageName = basename($_FILES["image"]["name"]);
$targetFile = $targetDir . time() . "_" . $imageName;
move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);

$sql = "INSERT INTO user_notifications (resident_id, title, description, image_path) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isss", $resident_id, $title, $description, $targetFile);

if ($stmt->execute()) {
    header("Location: notification.php");
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>
