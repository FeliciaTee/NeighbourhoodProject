<?php
include 'connect.php';

$title = $_POST['title'];
$content = $_POST['content'];

$stmt = $conn->prepare("INSERT INTO notes (title, content) VALUES (?, ?)");
$stmt->bind_param("ss", $title, $content);

if ($stmt->execute()) {
    echo "Note saved successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>


