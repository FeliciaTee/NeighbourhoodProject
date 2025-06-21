<?php
include 'connect.php';

    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $content = mysqli_real_escape_string($conn, $_POST['content']);

    $sql = "INSERT INTO notes (title, content) VALUES ('$title', '$content')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Note saved successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
?>

