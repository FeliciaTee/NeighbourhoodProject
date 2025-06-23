<?php

include 'connect.php';

// Get form data
$username = $_POST['username'];
$name = $_POST['name'];
$email = $_POST['email'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$password = $_POST['psw'];

// Encrypt password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// SQL with phone (no need to insert register_date if default exists)
$sql = "INSERT INTO residents (username, name, email, address, phone, password)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

// Debug 
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ssssss", $username, $name, $email, $address, $phone, $hashedPassword);

if ($stmt->execute()) {
    echo "<script>alert('Sign up successful!'); window.location.href='index.html';</script>";
} else {
    echo "Error executing statement: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
