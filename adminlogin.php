<?php
session_start();

$conn = new mysqli("localhost", "root", "", "workshop project");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM admin WHERE username = '$username'");

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['is_admin'] = true;
            $_SESSION['admin_id'] = $admin['admin_id'];
            header("Location: uploadedreports.php");
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
<html>
<head>
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f0f0f0;
            padding: 40px;
        }
        .login-box {
            background-color: white;
            width: 400px;
            padding: 30px;
            margin: auto;
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
            padding: 10px;
            background: #04AA6D;
            color: white;
            border: none;
            cursor: pointer;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

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