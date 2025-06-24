<?php
session_start();

$conn = new mysqli("localhost", "root", "", "neighborhoodproject");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);

    $result = $conn->query("SELECT * FROM admin WHERE username = '$username'");

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['is_admin'] = true;
            $_SESSION['admin_id'] = $admin['admin_id'];
            header("Location: adminprofile.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "Admin not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="adminstyle.css">
    <title>Admin Login</title>
    <style>

        .login-box {
            background-color: white;
            width: 400px;
            padding: 70px;
            margin: 100px auto;
            box-shadow: 0 0 10px #ccc;
            border-radius: 8px;
        }
        .login-box h2 {
            text-align: center;
        }
        .login-box input[type=text],
        .login-box input[type=password] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        .login-box button {
            width: 100%;
            margin-top: 20px;
            padding: 15px;
            background: #04AA6D;
            color: white;
            border: none;
            cursor: pointer;
        }
        .login-box button:hover{
            background-color:rgb(6, 133, 86);
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="main">
            <ul>
                <img src="banner.png" alt="banner" class="banner">
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About Us</a></li>
            </ul>
        </div>

</div>

<div class="login-box">
    <h2>Admin Login</h2>

    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Log In</button>
    </form>
</div>

</body>
</html>