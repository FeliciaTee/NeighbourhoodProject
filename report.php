<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  
    $title = $_POST['title'];
    $user_id = $_POST['id'];
    $category = $_POST['category'];
    $location = $_POST['location'];
    $description = $_POST['description'];

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
