<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "neighborhoodproject");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM notes WHERE note_id = $id");
    header("Location: managenotes.php");
    exit();
}

if (isset($_GET['flag'])) {
    $id = intval($_GET['flag']);
    $conn->query("UPDATE notes SET is_flagged = NOT is_flagged WHERE note_id = $id");
    header("Location: managenotes.php");
    exit();
}

$result = $conn->query("SELECT * FROM notes ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Notes</title>
    <link rel="stylesheet" href="adminstyle.css">
</head>
<body>
    <div class="main">
    <ul>
      <img src="banner.png" alt="banner" width="200" height="100" class="banner">
      <li><a href="logout.php">Log Out</a></li>
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
        <li><a href="uploadedreports.php"><img src="document.png" class="uploadedreports"> Uploaded Reports</a></li>
        <li><a href="managenotes.php"><img src="bell.png" class="managenotes"> Manage Notes</a></li>
        <li><a href="noticeboard.php"><img src="notice.png" class="noticeboard"> Notice Board</a></li>
      </ul>
    </div>

    <div class="content-area">
      <h2>Resident Notes</h2>
    <div style="display: flex; flex-direction: column; align-items: flex-start;">
<?php while ($row = $result->fetch_assoc()): ?>
    <div style="border: 1px solid #ccc; margin: 15px 0; padding: 15px 20px; background-color: white; border-radius: 12px; width: 600px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <strong style="font-size: 16px;"><?= htmlspecialchars($row['title']) ?></strong><br>
        <div style="margin: 10px 0;"><?= nl2br(htmlspecialchars($row['content'])) ?></div>
        <small style="color: gray;"><?= $row['created_at'] ?></small><br><br>

        <!-- Flag Button -->
        <a href="?flag=<?= $row['note_id'] ?>" onclick="return confirm('Toggle flag?')" 
           style="background-color: gold; color: black; padding: 6px 14px; text-decoration: none; border-radius: 8px; margin-right: 10px;">
            <?= $row['is_flagged'] ? 'Unflag' : 'Flag' ?>
        </a>

        <!-- Delete Button -->
        <a href="?delete=<?= $row['note_id'] ?>" onclick="return confirm('Delete this note?')" 
           style="background-color: #e74c3c; color: white; padding: 6px 14px; text-decoration: none; border-radius: 8px;">
            Delete
        </a>
    </div>
<?php endwhile; ?>
</div>
</body>
<footer>
    © 2025 The Neighborhood Bandar Seri Ehsan. All rights reserved.
</footer>
</html>