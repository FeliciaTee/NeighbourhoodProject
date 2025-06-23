<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['resident_id'])) {
    header("Location: index.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "neighborhoodproject";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get current resident ID
$resident_id = $_SESSION['resident_id'];

// Get form inputs
$new_name = $_POST['name'];
$new_username = $_POST['username'];
$new_address = $_POST['address'];
$email = $_POST['email'];
$phone = $_POST['phone'];

// Handle profile picture upload (optional)
$profile_pic_name = $_SESSION['profile_pic']; // default to current pic
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
    $file_tmp = $_FILES['profile_pic']['tmp_name'];
    $file_name = basename($_FILES['profile_pic']['name']);
    $target_path = "uploads/" . $file_name;

    // Move the uploaded file
    if (move_uploaded_file($file_tmp, $target_path)) {
        $profile_pic_name = $file_name;
    }
}

// Update database — now includes email and phone
$sql = "UPDATE residents SET name = ?, username = ?, address = ?, email = ?, phone = ?, profile_pic = ? WHERE resident_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $new_name, $new_username, $new_address, $email, $phone, $profile_pic_name, $resident_id);

if ($stmt->execute()) {
    // Update session variables
    $_SESSION['name'] = $new_name;
    $_SESSION['username'] = $new_username;
    $_SESSION['address'] = $new_address;
    $_SESSION['email'] = $email;
    $_SESSION['phone'] = $phone;
    $_SESSION['profile_pic'] = $profile_pic_name;

    // line for alert success flag
    $_SESSION['update_success'] = true;

    // Redirect
    header("Location: resident.php");
    exit();
} else {
    echo "Error updating profile: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
