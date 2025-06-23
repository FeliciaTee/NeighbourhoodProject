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

// Get input
$user = $conn->real_escape_string($_POST['username']);
$pass = $conn->real_escape_string($_POST['password']);

// Query resident table by username only
$sql = "SELECT * FROM residents WHERE username='$user'";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // Verify the hashed password
    if (password_verify($pass, $row['password'])) {
        
        // Store session data
        $_SESSION['resident_id'] = $row['resident_id'];
        $_SESSION['username'] = $row['username'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['address'] = $row['address'];
        $_SESSION['phone'] = $row['phone'];
        $_SESSION['profile_pic'] = $row['profile_pic'];
        $_SESSION['last_login'] = $row['last_login'];
        $_SESSION['formatted_id'] = 'R' . str_pad($row['resident_id'], 3, '0', STR_PAD_LEFT);

        // update last login time
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $resident_id = $row['resident_id'];
        $now = date("Y-m-d H:i:s");
        $update_sql = "UPDATE residents SET last_login = ? WHERE resident_id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("si", $now, $resident_id);
        $stmt->execute();
        $stmt->close();

        header("Location: resident.php");
        exit();
    } else {
        echo "<script>alert('Login failed. Wrong password.'); window.location.href='index.html';</script>";
    }
} else {
    echo "<script>alert('Login failed. Username not found.'); window.location.href='index.html';</script>";
}

$conn->close();
?>
