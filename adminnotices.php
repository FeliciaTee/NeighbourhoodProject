<!DOCTYPE html> 
<html lang="en">
<head>
    <title>Notice Management</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        body {
            font-family: 'Lucida Console', monospace;
            margin: 0;
            background-color: #d4e5b3;
            color: #000;
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

        .user-info {
            background-color: #b7e685;
            padding: 10px 20px;
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
            margin-left: 240px;
            padding: 20px;
        }

        .report-box {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            display: flex;
            gap: 20px;
            max-width: 900px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .report-details {
            flex: 3;
            font-family: "Lucida Console", monospace;
            font-size: 14px;
        }

        .report-details h2 {
            margin-top: 0;
        }

        .report-details a {
            color: #2c4eb0;
            text-decoration: underline;
        }

        .report-details span.highlight {
            color: darkred;
            font-weight: bold;
        }

        .report-details span.venue {
            font-weight: bold;
        }

        .report-image {
            flex: 1;
            background-color: #ececec;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
        }

        .btn-group {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }

        .btn-group button {
            background-color: #d6f5ce;
            border: 1px solid #89b383;
            padding: 10px 20px;
            border-radius: 8px;
            font-family: 'Lucida Console', monospace;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-group button:hover {
            background-color: #b4e7ac;
        }

        footer {
            background-color: #b4cd63;
            color: #000;
            text-align: center;
            padding: 15px;
            margin-top: 40px;
            clear: both;
        }

        #adminNotices {
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
    <h2>Notice Management</h2>

    <div class="report-box">
        <div class="report-details">
            <h2>Schedule Update</h2>
            <p><a href="#">Rescheduled: Community Meeting This Friday</a></p>
            <p><strong>Notice ID:</strong> NOTICE20250611002<br>
               <strong>Admin ID:</strong> ADMIN045</p>
            <p><strong>Content:</strong></p>
            <p>
                Please take note that the monthly community meeting has been
                <span class="highlight">rescheduled to Friday, 14 June 2025 at 8:30 PM</span>.
                The meeting will take place at the
                <span class="venue">Bandar Seri Ehsan Community Hall</span>.
                Agenda includes updates on security, maintenance issues, and upcoming events.
                All residents are encouraged to attend.
            </p>
            <p><strong>Created At:</strong> 2025-06-11 10:20:00<br>
               <strong>Updated At:</strong> 2025-06-11 10:20:00</p>
            <p><em>Published: June 10, 2025</em></p>
            
            <!-- ✅ Modified Close Details Button -->
            <button style="background-color: white; color: green; border: 2px solid green; padding: 10px 20px; border-radius: 8px; font-family: 'Lucida Console', monospace; font-size: 14px; cursor: pointer;" onclick="alert('Closing notice...')">
                Close Details
            </button>
        </div>

        <div class="report-image">
            <img src="placeholder.png" alt="Notice Image" width="100">
        </div>
    </div>

    <div class="btn-group">
        <button>➕ Add</button>
        <button>🗑️ Delete</button>
        <button>✏️ Edit</button>
    </div>
</div>

<footer>
    <p>&copy; 2025 The Neighborhood Bandar Seri Ehsan. All rights reserved.</p>
</footer>

</body>
</html>
