<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_id = trim($_POST['user_id']); 
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $password = password_hash($_POST['psw'], PASSWORD_DEFAULT); 

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

