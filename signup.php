<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'connect.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $password = $_POST['psw']; 

    $sql = "INSERT INTO signup (name, email, address, password)
            VALUES ('$name', '$email', '$address', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Signup successful!";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>

