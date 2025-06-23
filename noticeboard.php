<?php
session_start();

if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "workshop project");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM notices WHERE notice_id = $id");
    header("Location: noticeboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_notice'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $conn->query("INSERT INTO notices (title, content) VALUES ('$title', '$content')");
    header("Location: noticeboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_notice'])) {
    $id = intval($_POST['notice_id']);
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $conn->query("UPDATE notices SET title='$title', content='$content' WHERE notice_id=$id");
    header("Location: noticeboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <title>Notice Board</title>
     <link rel="stylesheet" href="adminstyle.css">

</head>
<body>
        <div class="main">
            <ul>
                <img src="banner.png" alt="banner" width="200" height="100" class="banner">
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="logout.html">Log Out</a></li>
            </ul>
        </div>

        <h1>
            <a href="adminprofile.php">
            <img src="circle-user.png" alt="userprofile" width="40" height="40" class="userprofile">
            </a>
        </h1>

        <div class="dashboard">
        <div class="sidebar">
        <ul>
            <li><a href="uploadedreports.php">
            <img src="document.png" alt="uploadedreports" class="uploadedreports">
            Uploaded Reports
            </a></li>
            <li><a href="managenotes.php">
            <img src="bell.png" alt="managenotes" class="managenotes">
            Manage Notes
            </a></li>
            <li><a href="noticeboard.php">
            <img src="notice.png" alt="noticeboard" class="noticeboard">
            Notice Board
            </a></li>
        </ul>
        </div>    
        
            <div class="notice-content">
                <h2>Notice Management</h2>
                <?php
        $result = $conn->query("SELECT * FROM notices ORDER BY created_at DESC");
        while ($row = $result->fetch_assoc()):
        ?>
            <div class="notice-card">
                <form method="POST" action="noticeboard.php">
                    <input type="hidden" name="notice_id" value="<?= $row['notice_id'] ?>">
                    <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" required>
                    <textarea name="content" required><?= htmlspecialchars($row['content']) ?></textarea>
                    <p class="notice-date">Published: <?= $row['created_at'] ?></p>
                    <div class="card-buttons">
                        <button type="submit" name="update_notice">Update</button>
                        <a href="noticeboard.php?delete=<?= $row['notice_id'] ?>" onclick="return confirm('Are you sure you want to delete this notice?')">
                            <button type="button">Delete</button>
                        </a>
                    </div>
                </form>
            </div>
        <?php endwhile; ?>

        <div class="notice-card" style="border: 2px dashed gray; margin-top: 20px;">
            <form method="POST" action="noticeboard.php">
                <h3>Add New Notice
                <input type="text" name="title" placeholder="Notice Title" required>
                </h3>
                <textarea name="content" placeholder="Notice Content" required></textarea>
                <div class="card-buttons">
                    <button type="submit" name="add_notice">Add Notice</button>
                </div>
            </form>
        </div>
    </div>
</div>

<footer>
    © 2025 The Neighborhood Bandar Seri Ehsan. All rights reserved.
</footer>
</body>
</html>