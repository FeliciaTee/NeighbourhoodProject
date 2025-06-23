<?php
session_start();
include 'connect.php';

if (!isset($_SESSION['resident_id'])) {
    echo "Unauthorized.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $resident_id = $_SESSION['resident_id'];

    $stmt = $conn->prepare("INSERT INTO notes (title, content, resident_id, is_flagged) VALUES (?, ?, ?, 0)");
    $stmt->bind_param("ssi", $title, $content, $resident_id);
    $stmt->execute();
    $stmt->close();
}
?>