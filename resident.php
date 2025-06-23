<?php
session_start();
if (!isset($_SESSION['resident_id'])) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel = stylesheet type = "text/css"href="residentstyle.css">
    <title>Resident Dashboard</title>
    <style> 


    </style>




</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>
    <p>Your Resident ID: <?php echo $_SESSION['formatted_id']; ?></p>
    <a href="logout.php">Log Out</a>
</body>
</html>
