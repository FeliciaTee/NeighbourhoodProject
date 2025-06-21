<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $user_id = trim($_POST['user_id']); // Make sure form uses this exact name
    $password = password_hash($_POST['psw'], PASSWORD_DEFAULT); // Secure password

    $sql = "INSERT INTO signup (name, email, address, user_id, password)
            VALUES ('$name', '$email', '$address', '$user_id', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Signup successful!'); window.location.href='index.html';</script>";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>

