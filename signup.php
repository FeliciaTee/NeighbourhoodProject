<?php
// Start session
session_start();

// Database connection
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

// Collect and sanitize form input
$username = trim($_POST['username']);
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$address = trim($_POST['address']);
$phone = trim($_POST['phone']);
$password = $_POST['psw']; // no need to trim passwords

// Check if username already exists
$check_sql = "SELECT resident_id FROM residents WHERE username = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $username);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    echo "<script>alert('Username already taken. Please choose another one.'); window.location.href='signup.html';</script>";
    $check_stmt->close();
    $conn->close();
    exit();
}
$check_stmt->close();

// Encrypt password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert into database
$sql = "INSERT INTO residents (username, name, email, address, phone, password) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ssssss", $username, $name, $email, $address, $phone, $hashedPassword);

if ($stmt->execute()) {
    echo "<script>alert('Sign up successful! Please log in.'); window.location.href='index.html';</script>";
} else {
    echo "Error executing statement: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
