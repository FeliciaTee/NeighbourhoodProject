<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "admindb";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get input
$user = $_POST['username'];
$pass = $_POST['password'];

// Check login
$sql = "SELECT * FROM admin WHERE username='$user' AND password='$pass'";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    // Successful login
    header("Location: admin.html");
    exit();
} else {
    // Failed login
    echo "<script>alert('Login failed. Wrong username or password.'); window.location.href='index.html';</script>";
}

$conn->close();
?>
