<?php
// 1. Include the database connection
include 'connect.php';

// 2. Check if the form was submitted using POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. Retrieve form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $user_ID = trim($_POST['Id']);
    $password = password_hash($_POST['psw'], PASSWORD_DEFAULT); // encrypt password

    // 4. Insert data into the database
    $sql = "INSERT INTO signup (name, email, address, User ID, password)
            VALUES ('$name', '$email', '$address', '$user_ID', '$password')";

    if ($signup->query($sql) === TRUE) {
        echo "Signup successful!";
    } else {
        echo "Error: " . $signup->error;
    }

    // 5. Close connection (optional but recommended)
    $signup->close();
}
?>
