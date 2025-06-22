<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'connect.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['title']);
    $user_id = trim($_POST['id']);
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

    $sql = "INSERT INTO reports (title, user_id, category, location, description, image)
            VALUES ('$title', '$user_id', '$category', '$location', '$description', '$imagePath')";

    if ($conn->query($sql) === TRUE) {
        echo "Report submitted successfully!";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
