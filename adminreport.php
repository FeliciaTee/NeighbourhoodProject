<!DOCTYPE html>
<html lang="en">
<head>
    <title>Resident Reports</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="adminstyle.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        body {
            font-family: 'Lucida Console', monospace;
            margin: 0;
            background-color: #d4e5b3;
            color: #000;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main-container {
            flex: 1;
            display: flex;
        }

        .header {
            background-color: #addf83;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .banner-img {
            width: 180px;
            height: 80px;
        }

        .nav-links a {
            margin: 0 10px;
            text-decoration: none;
            color: black;
            font-weight: bold;
        }

        .sidebar {
            width: 220px;
            background-color: #d5ecb3;
            padding: 20px 0;
            box-sizing: border-box;
            height: 100vh;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar li {
            margin-bottom: 15px;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            text-decoration: none;
            font-weight: bold;
            color: #000;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #b4efbb;
            color: #016b5b;
        }

        .sidebar a .icon {
            width: 24px;
            height: 24px;
            margin-right: 12px;
        }

        .content {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
        }

        .report-box {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            gap: 20px;
            max-width: 900px;
        }

        .report-details {
            flex: 3;
        }

        .report-details a {
            font-weight: bold;
            color: #2c4eb0;
            text-decoration: underline;
        }

        .report-image {
            flex: 1;
            background-color: #e2e2e2;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
        }

        .meta-info {
            font-size: 12px;
            color: #555;
        }

        .btn-group {
            margin-top: 20px;
        }

        .btn-group button {
            background-color: #c4edb7;
            border: 1px solid #89b383;
            padding: 8px 16px;
            font-family: "Lucida Console";
            border-radius: 5px;
            margin-right: 10px;
            cursor: pointer;
        }

        .btn-group button:hover {
            background-color: #aedc9c;
        }

        .close-btn {
            background-color: white;
            color: #1e7d3e;
            border: 1px solid #1e7d3e;
            padding: 8px 16px;
            border-radius: 5px;
            font-family: "Lucida Console";
            cursor: pointer;
            margin-top: 10px;
        }

        .close-btn:hover {
            background-color: #e1f5e8;
        }

        footer {
            background-color: #b4cd63;
            color: #000;
            text-align: center;
            padding: 15px;
        }

        #adminReport {
            background-color: #c4edb7;
            color: #016b5b;
        }
    </style>
</head>
<body>

<?php include("header.html"); ?>

<div class="main-container">

    <?php include("adminSidebar.php"); ?>

    <div class="content">
        <h2>Resident Reports</h2>
        <h3>Road Damage Report</h3>

        <div class="report-box">
            <div class="report-details">
                <p><a href="#">Large Pothole on Jalan Seri Ehsan 2</a></p>
                <p><strong>Report ID:</strong> RPT20250611001<br>
                   <strong>Resident ID:</strong> RES108235</p>
                <p>
                    A pothole approximately 60 cm wide and 15 cm deep is located in front of Surau Al-Hijrah, along Jalan Seri Ehsan 2. It has caused cars to swerve dangerously and poses a safety hazard, especially during rain when it becomes less visible.
                </p>
                <div class="meta-info">
                    Category: Road Damage – Pothole<br>
                    Location: Jalan Seri Ehsan 2, Bandar Seri Ehsan<br>
                    Status: Pending Review<br>
                    Created At: 2025-06-11<br>
                    Updated At: 2025-06-11
                </div>
                <button class="close-btn" onclick="alert('Closing details...')">Close Details</button>
            </div>
            <div class="report-image">
                <img src="placeholder.png" alt="Report Image" width="100">
            </div>
        </div>

        <div class="btn-group">
            <button>🔄 Update</button>
            <button>🗑️ Delete</button>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2025 The Neighborhood Community Center. All rights reserved.</p>
</footer>

</body>
</html>

