<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $username = trim($_POST['username']); // must match column name: 'Username'
    $category = trim($_POST['category']);
    $location = trim($_POST['location']);
    $description = trim($_POST['description']);
    $imagePath = '';

    if (!empty($_FILES['image']['name'])) {
        $imageName = basename($_FILES['image']['name']);
        $targetDir = "uploads/";
        $targetFile = $targetDir . $imageName;

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;
        } else {
            echo "Image upload failed.";
            exit();
        }
    }

    $stmt = $conn->prepare("INSERT INTO reports (title, Username, category, location, description, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $username, $category, $location, $description, $imagePath);

    if ($stmt->execute()) {
        echo "<script>alert('Report submitted successfully!'); window.location.href='report.html';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
