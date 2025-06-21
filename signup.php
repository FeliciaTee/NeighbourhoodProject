<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'connect.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<pre>";
    echo "</pre>";

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $user_id = trim($_POST['user_id']);
    $password = $_POST['psw']; //pass enter will be same in db

    $sql = "INSERT INTO signup (name, email, address, user_id, password)
            VALUES ('$name', '$email', '$address', '$user_id', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Signup successful!";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
