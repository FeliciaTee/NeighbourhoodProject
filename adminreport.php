<?php
include 'connect.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Delete function
if (isset($_GET['delete'])) {
    $report_id = intval($_GET['delete']);
    $conn->query("DELETE FROM reports WHERE report_id = $report_id");
    header("Location: adminreport.php");
    exit();
}

// Update function
if (isset($_POST['update'])) {
    $report_id = intval($_POST['report_id']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $conn->query("UPDATE reports SET description = '$description' WHERE report_id = $report_id");
    header("Location: adminreport.php");
    exit();
}

// Fetch all reports
$result = $conn->query("SELECT * FROM reports ORDER BY created_at DESC");
?>

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

        .report-image {
            flex: 1;
            background-color: #e2e2e2;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 10px;
        }

        textarea {
            width: 100%;
            height: 100px;
        }

        .btn-group {
            margin-top: 20px;
        }

        .btn-group button,
        .btn-group a {
            background-color: #c4edb7;
            border: 1px solid #89b383;
            padding: 8px 16px;
            font-family: "Lucida Console";
            border-radius: 5px;
            margin-right: 10px;
            cursor: pointer;
            text-decoration: none;
            color: black;
        }

        .btn-group a.delete-btn {
            background-color: #e74c3c;
            color: white;
        }
    </style>
</head>
<body>

<?php include("header.html"); ?>

<div class="main-container">
<?php include("adminSidebar.php"); ?>

<div class="content">
    <h2>Resident Reports</h2>

    <?php while ($row = $result->fetch_assoc()): ?>
    <div class="report-box">
        <div class="report-details">
            <form method="post">
                <input type="hidden" name="report_id" value="<?= htmlspecialchars($row['report_id']) ?>">

                <h3><?= htmlspecialchars($row['title']) ?></h3>
                <p><strong>Resident ID:</strong> <?= htmlspecialchars($row['user_id']) ?></p>
                <p><strong>Category:</strong> <?= htmlspecialchars($row['category']) ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($row['location']) ?></p>
                <p><strong>Created At:</strong> <?= htmlspecialchars($row['created_at']) ?></p>

                <p><strong>Description:</strong></p>
                <textarea name="description"><?= htmlspecialchars($row['description']) ?></textarea>

                <?php if (!empty($row['image'])): ?>
                <div class="report-image">
                    <img src="<?= htmlspecialchars($row['image']) ?>" alt="Report Image" width="150">
                </div>
                <?php endif; ?>

                <div class="btn-group">
                    <button type="submit" name="update">Update</button>
                    <a href="adminreport.php?delete=<?= $row['report_id'] ?>" class="delete-btn" onclick="return confirm('Confirm delete?');">Delete</a>
                </div>
            </form>
        </div>
    </div>
    <br>
    <?php endwhile; ?>
</div>
</div>
</body>
</html>
