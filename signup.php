<?php
// Connect to database
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "neighborhoodproject";

// Create connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from form
$username = $_POST['username'];
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$address = $_POST['address'];
$password = $_POST['psw'];

// Encrypt password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert into database (assuming table name = 'residents')
$sql = "INSERT INTO residents (username, fullname, email, address, password)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $username, $fullname, $email, $address, $hashedPassword);

if ($stmt->execute()) {
    echo "<script>alert('Sign up successful! You may now log in.'); window.location.href = 'index.html';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
