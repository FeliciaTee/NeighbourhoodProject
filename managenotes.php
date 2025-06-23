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

// Delete note
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM notes WHERE note_id = $id");
    header("Location: managenotes.php");
    exit();
}

// Toggle flag
if (isset($_GET['flag'])) {
    $id = intval($_GET['flag']);
    $conn->query("UPDATE notes SET is_flagged = NOT is_flagged WHERE note_id = $id");
    header("Location: managenotes.php");
    exit();
}

$result = $conn->query("SELECT * FROM notes ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Notes</title>
  <link rel="stylesheet" href="adminstyle.css">
</head>
<body>
  <div class="main">
    <ul>
      <img src="banner.png" alt="banner" width="200" height="100" class="banner">
      <li><a href="#">Home</a></li>
      <li><a href="about.html">About Us</a></li>
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
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="note-card">
          <div class="note-title"><?= htmlspecialchars($row['title']) ?></div>
          <div class="note-content"><?= nl2br(htmlspecialchars($row['content'])) ?></div>
          <div class="note-time"><?= htmlspecialchars($row['created_at']) ?></div>
          <div class="note-buttons">
            <a href="?flag=<?= $row['note_id'] ?>" class="btn-flag" onclick="return confirm('Toggle flag on this note?')">
              <?= $row['is_flagged'] ? 'Unflag' : 'Flag' ?>
            </a>
            <a href="?delete=<?= $row['note_id'] ?>" class="btn-delete" onclick="return confirm('Delete this note?')">Delete</a>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</body>
</html>