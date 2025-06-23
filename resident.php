<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Resident Dashboard</title>
    <link rel="stylesheet" type="text/css" href="residentstyle.css">
    <style>

        .banner {
            display: flex;
            align-items: center;
        }

        .banner img {
            height: 50px;
            margin-right: 10px;
        }

        header span {
            font-size: 20px;
            font-weight: bold;
        }

        nav a {
            margin: 0 10px;
            color: black;
            text-decoration: none;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }

    
       

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #333;
        }

        .main {
            flex: 1;
            margin-left: 230px;
            padding: 20px;
            padding-top: 100px;
        }

        .card {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            max-width: 600px;
        }

        .card h2 {
            color: #33691e;
            margin-bottom: 20px;
        }

        .card p {
            font-size: 14px;
            margin: 10px 0;
            color: #444;
        }

        footer {
            text-align: center;
            background-color: #a4d373;
            padding: 20px;
            margin-left: 230px;
        }
    </style>
</head>
<body>

<header>
    <div class="banner">
        <img src="banner.png" alt="banner">
        <span>The Neighborhood: One-Stop Community Center</span>
    </div>
    <nav>
        <a href="index.html">Home</a>
        <a href="about.html">About Us</a>
        <a href="logout.php">Log Out</a>
    </nav>
</header>

<div class="sidebar">
    <ul>
        <li><a href="report.html">Lodge Report</a></li>
        <li><a href="#">Community Events</a></li>
        <li><a href="notescommunity.html">Community Notes</a></li>
        <li><a href="faq.html">Help & Support</a></li>
        <li><a href="notification.html"><strong>Notifications</strong></a></li>
    </ul>
</div>

<div class="main">
    <div class="card">
        <h2>Welcome, <?php echo $_SESSION['name']; ?>!</h2>
        <p><strong>Username:</strong> <?php echo $_SESSION['username']; ?></p>
        <p><strong>Email:</strong> <?php echo $_SESSION['email']; ?></p>
        <p><strong>Address:</strong> <?php echo $_SESSION['address']; ?></p>
        <p><strong>Resident ID:</strong> <?php echo $_SESSION['formatted_id']; ?></p>
    </div>
</div>

<footer>
    &copy; 2025 The Neighborhood Bandar Seri Ehsan. All rights reserved.
</footer>

</body>
</html>
