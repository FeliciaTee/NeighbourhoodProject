<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "neighborhoodproject";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get input (and sanitize it to avoid SQL injection)
$user = $conn->real_escape_string($_POST['username']);
$pass = $conn->real_escape_string($_POST['password']);

// Query resident table
$sql = "SELECT * FROM residents WHERE username='$user' AND password='$pass'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // Store session data
    $_SESSION['resident_id'] = $row['resident_id'];
    $_SESSION['username'] = $row['username'];
    $_SESSION['name'] = $row['name'];

    // Create formatted ID like R001
    $_SESSION['formatted_id'] = 'R' . str_pad($row['resident_id'], 3, '0', STR_PAD_LEFT);

    header("Location: resident.php"); // make sure this file exists
    exit();
} else {
    echo "<script>alert('Login failed. Wrong username or password.'); window.location.href='index.html';</script>";
}

$conn->close();
?>
