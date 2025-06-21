<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'connect.php'; // Make sure this connects to your database correctly

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize input
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $user_id = trim($_POST['user_id']);
    $password = password_hash($_POST['psw'], PASSWORD_DEFAULT); // Securely hash password

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO signup (name, email, address, user_id, password) VALUES (?, ?, ?, ?, ?)");

    if ($stmt) {
        $stmt->bind_param("sssss", $name, $email, $address, $user_id, $password);

        if ($stmt->execute()) {
            echo "Signup successful!";
        } else {
            echo "Error during signup: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Statement preparation failed: " . $conn->error;
    }

    $conn->close();
}
?>

