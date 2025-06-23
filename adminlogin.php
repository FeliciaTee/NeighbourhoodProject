<?php
session_start();

$conn = new mysqli("localhost", "root", "", "workshop project");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $conn->real_escape_string($_POST["username"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM admin WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin["password"])) {
            $_SESSION["is_admin"] = true;
            $_SESSION["admin_username"] = $username;
            header("Location: noticeboard.php");
            exit();
        }
    }

    // Kalau salah, terus redirect balik (no error message)
    header("Location: adminlogin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Log In</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #b5dd97;
        }

        .login-box {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            width: 550px;
            margin: 50px auto;
        }

        input[type=text], input[type=password] {
            width: 90%;
            padding: 15px;
            margin: 5px 0 22px 0;
            border: none;
            background: #f1f1f1;
        }

        button {
            background-color: #04AA6D;
            color: white;
            padding: 10px 16px;
            border: none;
            cursor: pointer;
            width: 50%;
            display: block;
            margin: 15px auto;
        }

        .main ul {
            list-style: none;
            display: flex;
            background-color: #fff;
            padding: 10px;
        }

        .main li {
            margin: 0 15px;
        }

        .main a {
            text-decoration: none;
            font-weight: bold;
            color: #333;
        }

        .banner {
            height: 60px;
            margin-right: auto;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #eee;
            margin-top: 30px;
        }
    </style>
</head>
<body>

<div class="main">
    <ul>
        <img src="banner.png" alt="banner" class="banner">
        <li><a href="index.html">Home</a></li>
        <li><a href="about.html">About Us</a></li>
        <li><a href="adminlogin.php">Admin</a></li>
        <li><a href="emergency.html">EMERGENCY</a></li>
    </ul>
</div>

<form action="adminlogin.php" method="POST">
    <div class="login-box">
        <h1>Admin Login</h1>
        <p>Only authorized personnel may access this page.</p>
        <label for="username"><b>Username</b></label>
        <input type="text" name="username" required placeholder="Enter Username">
        
        <label for="password"><b>Password</b></label>
        <input type="password" name="password" required placeholder="Enter Password">

        <button type="submit">Log In</button>
    </div>
</form>

<footer>
    <p>© 2025 The Neighborhood Bandar Seri Ehsan. All rights reserved.</p>
</footer>

</body>
</html>
